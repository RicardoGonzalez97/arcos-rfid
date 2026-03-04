<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BatchScanEventsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $scanSessionId;
    protected array $events;

    /**
     * Create a new job instance.
     */
    public function __construct(string $scanSessionId, array $events)
    {
        $this->scanSessionId = $scanSessionId;
        $this->events = $events;
    }

    /**
     * Execute the job.
     */
public function handle(): void
{
    try {

        DB::transaction(function () {

            $now = Carbon::now();

            /*
            |--------------------------------------------------------------------------
            | 1️⃣ Validar sesión
            |--------------------------------------------------------------------------
            */
            $session = DB::table('order_scan_sessions')
                ->where('scan_session_id', $this->scanSessionId)
                ->lockForUpdate()
                ->first();

            if (!$session || $session->status !== 'OPEN') {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | 2️⃣ Obtener órdenes vinculadas a esta sesión
            |--------------------------------------------------------------------------
            */
            $sessionOrderIds = DB::table('scan_session_orders')
                ->where('scan_session_id', $this->scanSessionId)
                ->pluck('order_id');

            if ($sessionOrderIds->isEmpty()) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | 3️⃣ Agrupar RFIDs por producto
            |--------------------------------------------------------------------------
            */
            $groupedProducts = collect($this->events)
                ->groupBy('rfid')
                ->map(fn($items) => count($items));

            $insertData = [];
            $extraCount = 0;

            /*
            |--------------------------------------------------------------------------
            | 4️⃣ Procesar productos SOLO de órdenes de esta sesión
            |--------------------------------------------------------------------------
            */
            foreach ($groupedProducts as $productId => $totalQty) {

                $qtyToAssign = $totalQty;

                $pendingOrderProducts = DB::table('order_products')
                    ->whereIn('order_id', $sessionOrderIds)
                    ->where('product_id', $productId)
                    ->whereColumn('received_quantity', '<', 'expected_quantity')
                    ->orderBy('created_at')
                    ->lockForUpdate()
                    ->get();

                foreach ($pendingOrderProducts as $op) {

                    if ($qtyToAssign <= 0) break;

                    $remaining = $op->expected_quantity - $op->received_quantity;
                    $assign = min($qtyToAssign, $remaining);

                    DB::table('order_products')
                        ->where('order_products_id', $op->order_products_id)
                        ->increment('received_quantity', $assign);

                    for ($i = 0; $i < $assign; $i++) {
                        $insertData[] = [
                            'scan_session_id' => $this->scanSessionId,
                            'order_id'        => $op->order_id,
                            'product_id'      => $productId,
                            'event_status'    => 'scanned',
                            'scanned_at'      => $now,
                        ];
                    }

                    $qtyToAssign -= $assign;
                }

                /*
                |--------------------------------------------------------------------------
                | 5️⃣ Si sobran productos → extras
                |--------------------------------------------------------------------------
                */
                if ($qtyToAssign > 0) {

                    for ($i = 0; $i < $qtyToAssign; $i++) {
                        $insertData[] = [
                            'scan_session_id' => $this->scanSessionId,
                            'order_id'        => null,
                            'product_id'      => $productId,
                            'event_status'    => 'extra',
                            'scanned_at'      => $now,
                        ];
                    }

                    $extraCount += $qtyToAssign;
                }
            }

            if (!empty($insertData)) {
                DB::table('scan_events')->insert($insertData);
            }

            /*
            |--------------------------------------------------------------------------
            | 6️⃣ Regenerar snapshot por producto (solo órdenes de la sesión)
            |--------------------------------------------------------------------------
            */
            DB::table('scan_product_results')
                ->where('scan_session_id', $this->scanSessionId)
                ->delete();

            $productsStatus = DB::table('order_products')
                ->whereIn('order_id', $sessionOrderIds)
                ->select(
                    'order_id',
                    'product_id',
                    'expected_quantity',
                    'received_quantity'
                )
                ->get();

            foreach ($productsStatus as $product) {

                $status = $product->received_quantity >= $product->expected_quantity
                    ? 'COMPLETE'
                    : 'MISSING';

                DB::table('scan_product_results')->insert([
                    'scan_session_id' => $this->scanSessionId,
                    'order_id'        => $product->order_id,
                    'product_id'      => $product->product_id,
                    'expected_qty'    => $product->expected_quantity,
                    'scanned_qty'     => $product->received_quantity,
                    'status'          => $status,
                    'created_at'      => $now,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 7️⃣ Calcular estado por orden
            |--------------------------------------------------------------------------
            */
            $orderStatuses = DB::table('scan_product_results')
                ->where('scan_session_id', $this->scanSessionId)
                ->select(
                    'order_id',
                    DB::raw("SUM(CASE WHEN status = 'MISSING' THEN 1 ELSE 0 END) as missing_count")
                )
                ->groupBy('order_id')
                ->get();

            $sessionComplete = true;

            foreach ($orderStatuses as $order) {
                if ($order->missing_count > 0) {
                    $sessionComplete = false;
                    break;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 8️⃣ Totales SOLO de órdenes de esta sesión
            |--------------------------------------------------------------------------
            */
            $totals = DB::table('order_products')
                ->whereIn('order_id', $sessionOrderIds)
                ->selectRaw('
                    SUM(expected_quantity) as expected_total,
                    SUM(received_quantity) as scanned_total,
                    SUM(expected_quantity - received_quantity) as missing_total
                ')
                ->first();

            DB::table('scan_session_results')->updateOrInsert(
                ['scan_session_id' => $this->scanSessionId],
                [
                    'expected_total' => $totals->expected_total ?? 0,
                    'scanned_total'  => $totals->scanned_total ?? 0,
                    'missing_total'  => $totals->missing_total ?? 0,
                    'extra_total'    => $extraCount,
                    'status'         => $sessionComplete ? 'COMPLETE' : 'INCOMPLETE',
                    'updated_at'     => $now,
                ]
            );

        });
        logger()->info("Scan batch processed", [
            'scan_session_id' => $this->scanSessionId,
            'status' => $sessionComplete ? 'COMPLETE' : 'INCOMPLETE',
            'extra_total' => $extraCount,
        ]);


    } catch (\Throwable $e) {

        logger()->error("BatchScanEventsJob failed", [
            'error' => $e->getMessage(),
            'scan_session_id' => $this->scanSessionId,
        ]);

        throw $e;
    }
}





}
