<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CloseScanSessionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $scanSessionId;

    public function __construct(string $scanSessionId)
    {
        $this->scanSessionId = $scanSessionId;
    }

    public function handle(): void
    {
        DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | 0️⃣ Lock session
            |--------------------------------------------------------------------------
            */

            $session = DB::table('order_scan_sessions')
                ->where('scan_session_id', $this->scanSessionId)
                ->lockForUpdate()
                ->first();

            if (!$session || $session->status !== 'CLOSING') {
                return;
            }

            $dockId = $session->dock_id;

            /*
            |--------------------------------------------------------------------------
            | 1️⃣ Obtener órdenes vinculadas
            |--------------------------------------------------------------------------
            */

            $orderIds = DB::table('scan_session_orders')
                ->where('scan_session_id', $this->scanSessionId)
                ->pluck('order_id')
                ->toArray();

            /*
            |--------------------------------------------------------------------------
            | 2️⃣ Limpiar resultados anteriores
            |--------------------------------------------------------------------------
            */

            DB::table('scan_product_results')
                ->where('scan_session_id', $this->scanSessionId)
                ->delete();

            DB::table('scan_session_results')
                ->where('scan_session_id', $this->scanSessionId)
                ->delete();

            /*
            |--------------------------------------------------------------------------
            | 3️⃣ Procesar cada orden
            |--------------------------------------------------------------------------
            */

            foreach ($orderIds as $orderId) {

                $expected = DB::table('order_products')
                    ->where('order_id', $orderId)
                    ->select(
                        'product_id',
                        DB::raw('SUM(expected_quantity) as expected_qty')
                    )
                    ->groupBy('product_id')
                    ->get()
                    ->keyBy('product_id');

                $scanned = DB::table('scan_events')
                    ->where('scan_session_id', $this->scanSessionId)
                    ->where('order_id', $orderId)
                    ->select(
                        'product_id',
                        DB::raw('COUNT(*) as scanned_qty')
                    )
                    ->groupBy('product_id')
                    ->get()
                    ->keyBy('product_id');

                $results = [];

                foreach ($expected as $productId => $exp) {

                    $scannedQty = $scanned[$productId]->scanned_qty ?? 0;

                    if ($scannedQty < $exp->expected_qty) {
                        $status = 'MISSING';
                    } elseif ($scannedQty > $exp->expected_qty) {
                        $status = 'EXTRA';
                    } else {
                        $status = 'OK';
                    }

                    $results[] = [
                        'scan_session_id' => $this->scanSessionId,
                        'order_id'        => $orderId,
                        'product_id'      => $productId,
                        'expected_qty'    => $exp->expected_qty,
                        'scanned_qty'     => $scannedQty,
                        'status'          => $status,
                        'created_at'      => now(),
                    ];
                }

                // Productos escaneados que no estaban esperados en esta orden
                foreach ($scanned as $productId => $scan) {
                    if (!isset($expected[$productId])) {
                        $results[] = [
                            'scan_session_id' => $this->scanSessionId,
                            'order_id'        => $orderId,
                            'product_id'      => $productId,
                            'expected_qty'    => 0,
                            'scanned_qty'     => $scan->scanned_qty,
                            'status'          => 'EXTRA',
                            'created_at'      => now(),
                        ];
                    }
                }

                if (!empty($results)) {
                    DB::table('scan_product_results')->insert($results);
                }

                /*
                |--------------------------------------------------------------------------
                | 4️⃣ Totales por orden
                |--------------------------------------------------------------------------
                */

                $totals = DB::table('scan_product_results')
                    ->where('scan_session_id', $this->scanSessionId)
                    ->where('order_id', $orderId)
                    ->selectRaw('
                        SUM(expected_qty) as expected_total,
                        SUM(scanned_qty) as scanned_total,
                        SUM(CASE WHEN scanned_qty < expected_qty THEN expected_qty - scanned_qty ELSE 0 END) as missing_total,
                        SUM(CASE WHEN scanned_qty > expected_qty THEN scanned_qty - expected_qty ELSE 0 END) as extra_total
                    ')
                    ->first();

                $missing = (int) ($totals->missing_total ?? 0);
                $extra   = (int) ($totals->extra_total ?? 0);

                if ($missing === 0 && $extra === 0) {
                    $orderStatus = 'COMPLETE';
                } elseif ($missing > 0 && $extra === 0) {
                    $orderStatus = 'INCOMPLETE';
                } elseif ($missing === 0 && $extra > 0) {
                    $orderStatus = 'OVER_SCANNED';
                } else {
                    $orderStatus = 'PARTIAL';
                }

                DB::table('scan_session_results')->insert([
                    'scan_session_id' => $this->scanSessionId,
                    'dock_id'         => $dockId,
                    'order_id'        => $orderId,
                    'expected_total'  => $totals->expected_total ?? 0,
                    'scanned_total'   => $totals->scanned_total ?? 0,
                    'missing_total'   => $missing,
                    'extra_total'     => $extra,
                    'status'          => $orderStatus,
                    'created_at'      => now(),
                    'updated_at'      => now()
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 5️⃣ Extras globales (order_id NULL)
            |--------------------------------------------------------------------------
            */

            $globalExtras = DB::table('scan_events')
                ->where('scan_session_id', $this->scanSessionId)
                ->whereNull('order_id')
                ->count();

            /*
            |--------------------------------------------------------------------------
            | 6️⃣ Totales globales de sesión
            |--------------------------------------------------------------------------
            */

            $sessionTotals = DB::table('scan_session_results')
                ->where('scan_session_id', $this->scanSessionId)
                ->selectRaw('
                    SUM(expected_total) as expected_total,
                    SUM(scanned_total) as scanned_total,
                    SUM(missing_total) as missing_total,
                    SUM(extra_total) as extra_total
                ')
                ->first();

            $expectedTotal = (int) ($sessionTotals->expected_total ?? 0);
            $scannedTotal  = (int) ($sessionTotals->scanned_total ?? 0);
            $missingTotal  = (int) ($sessionTotals->missing_total ?? 0);
            $extraTotal    = (int) ($sessionTotals->extra_total ?? 0) + $globalExtras;

            if ($missingTotal === 0 && $extraTotal === 0) {
                $finalStatus = 'COMPLETE';
            } elseif ($missingTotal > 0 && $extraTotal === 0) {
                $finalStatus = 'INCOMPLETE';
            } elseif ($missingTotal === 0 && $extraTotal > 0) {
                $finalStatus = 'OVER_SCANNED';
            } else {
                $finalStatus = 'PARTIAL';
            }

            /*
            |--------------------------------------------------------------------------
            | 7️⃣ Cerrar sesión
            |--------------------------------------------------------------------------
            */

            DB::table('order_scan_sessions')
                ->where('scan_session_id', $this->scanSessionId)
                ->update([
                    'status'     => $finalStatus,
                    'closed_at'  => now(),
                    'updated_at' => now()
                ]);

            logger("Scan session closed", [
                'scan_session_id' => $this->scanSessionId,
                'final_status'    => $finalStatus,
                'global_extras'   => $globalExtras
            ]);
        });
    }
}