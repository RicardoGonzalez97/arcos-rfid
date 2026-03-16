<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Jobs\BatchScanEventsJob;
use App\Jobs\CloseScanSessionJob;
use App\Traits\ApiResponse;
use App\Services\PurchaseOrderNormalizer;
use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Jobs\NormalizeDockOrdersJob;

class ScanSessionController extends Controller
{
use ApiResponse;

#1er paso crear sesión de escaneo
public function store(Request $request)
{
    $request->validate([
        'dock_id' => 'required|integer'
    ]);

    $dockId = $request->dock_id;

    /*
    |--------------------------------------------------------------------------
    | 1️⃣ Obtener purchase orders asignadas al dock
    |--------------------------------------------------------------------------
    */

    $purchaseOrders = DB::table('dock_purchase_orders')
        ->where('dock_id', $dockId)
        ->pluck('purchase_order_id');

    if ($purchaseOrders->isEmpty()) {
        return $this->fail(
            'No purchase orders assigned to this dock.',
            404
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 2️⃣ Mandar normalización a la cola
    |--------------------------------------------------------------------------
    */

    NormalizeDockOrdersJob::dispatch($dockId);

    /*
    |--------------------------------------------------------------------------
    | 3️⃣ Crear scan session
    |--------------------------------------------------------------------------
    */

    return DB::transaction(function () use ($dockId, $purchaseOrders) {

        $existingOpenSession = DB::table('order_scan_sessions')
            ->where('dock_id', $dockId)
            ->where('status', 'OPEN')
            ->lockForUpdate()
            ->first();

        if ($existingOpenSession) {
            return $this->fail(
                'This dock already has an OPEN scan session.',
                422
            );
        }

        $scanSessionId = (string) \Str::uuid();

        DB::table('order_scan_sessions')->insert([
            'scan_session_id' => $scanSessionId,
            'dock_id'         => $dockId,
            'status'          => 'OPEN',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return $this->created([
            'scan_session_id' => $scanSessionId,
            'dock_id'         => $dockId,
            'orders_linked'   => $purchaseOrders
        ], 'Scan session created successfully');

    });
}

#2 paso escaneo de productos
public function batch(Request $request)
{
    $request->validate([
        'dock_id' => 'required|integer',
        'events' => 'required|array|min:1',
        'events.*.rfid' => 'required|string',
    ]);

    $dockId = $request->dock_id;
    $events = $request->events;

    try {

        $result = DB::transaction(function () use ($dockId, $events) {

            $now = now();

            /*
            |--------------------------------------------------------------------------
            | 0️⃣ Validar sesión OPEN para el dock
            |--------------------------------------------------------------------------
            */
            $openSession = DB::table('order_scan_sessions')
                ->where('dock_id', $dockId)
                ->where('status', 'OPEN')
                ->lockForUpdate()
                ->first();

            if (!$openSession) {
                return [
                    'error' => true,
                    'message' => 'No OPEN scan session found for this dock'
                ];
            }

            $scanSessionId = $openSession->scan_session_id;

            /*
            |--------------------------------------------------------------------------
            | 1️⃣ Obtener órdenes del dock
            |--------------------------------------------------------------------------
            */
            $orderIds = DB::table('orders')
                ->where('dock_id', $dockId)
                ->pluck('order_id')
                ->toArray();

            if (empty($orderIds)) {
                return [
                    'error' => true,
                    'message' => 'No orders found for this dock'
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | 2️⃣ Traer order_products
            |--------------------------------------------------------------------------
            */
            $orderProducts = DB::table('order_products')
                ->whereIn('order_id', $orderIds)
                ->lockForUpdate()
                ->get()
                ->groupBy('product_id');

            /*
            |--------------------------------------------------------------------------
            | 3️⃣ Agrupar RFIDs
            |--------------------------------------------------------------------------
            */
            $groupedProducts = collect($events)
                ->groupBy('rfid')
                ->map(fn($items) => count($items));

            /*
            |--------------------------------------------------------------------------
            | 🔥 3.1 Validar TODOS los productos en una sola query
            |--------------------------------------------------------------------------
            */
            $productIds = $groupedProducts->keys()
                ->map(fn($id) => (string) $id)
                ->values()
                ->toArray();

            $existingProducts = Product::whereIn('product_id', $productIds)
                ->pluck('product_id')
                ->map(fn($id) => (string) $id)
                ->toArray();

            $existingProducts = array_flip($existingProducts);

            // Detectar productos inválidos
            $invalidProducts = [];

            foreach ($productIds as $pid) {
                if (!isset($existingProducts[$pid])) {
                    $invalidProducts[] = $pid;
                }
            }

            if (!empty($invalidProducts)) {
                return [
                    'error' => true,
                    'message' => 'Some products do not exist',
                    'invalid_products' => $invalidProducts
                ];
            }

            $insertData = [];
            $extraCount = 0;
            $scannedCount = 0;

            /*
            |--------------------------------------------------------------------------
            | 4️⃣ Procesar productos
            |--------------------------------------------------------------------------
            */
            foreach ($groupedProducts as $productId => $totalQty) {

                $productId = (string) $productId;

                $qtyToAssign = $totalQty;
                $pendingOrderProducts = $orderProducts[$productId] ?? collect();

                foreach ($pendingOrderProducts as $op) {

                    if ($qtyToAssign <= 0) break;

                    $remaining = $op->expected_quantity - $op->received_quantity;
                    if ($remaining <= 0) continue;

                    $assign = min($qtyToAssign, $remaining);

                    DB::table('order_products')
                        ->where('order_products_id', $op->order_products_id)
                        ->increment('received_quantity', $assign);

                    for ($i = 0; $i < $assign; $i++) {
                        $insertData[] = [
                            'scan_session_id' => $scanSessionId,
                            'dock_id'         => $dockId,
                            'order_id'        => $op->order_id,
                            'product_id'      => $productId,
                            'event_status'    => 'scanned',
                            'scanned_at'      => $now,
                        ];
                    }

                    $scannedCount += $assign;
                    $qtyToAssign -= $assign;
                }

                /*
                |--------------------------------------------------------------------------
                | Extras
                |--------------------------------------------------------------------------
                */
                if ($qtyToAssign > 0) {

                    for ($i = 0; $i < $qtyToAssign; $i++) {
                        $insertData[] = [
                            'scan_session_id' => $scanSessionId,
                            'dock_id'         => $dockId,
                            'order_id'        => null,
                            'product_id'      => $productId,
                            'event_status'    => 'extra',
                            'scanned_at'      => $now,
                        ];
                    }

                    $extraCount += $qtyToAssign;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 5️⃣ Insert masivo
            |--------------------------------------------------------------------------
            */
            if (!empty($insertData)) {
                foreach (array_chunk($insertData, 500) as $chunk) {
                    DB::table('scan_events')->insert($chunk);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 6️⃣ Calcular totales
            |--------------------------------------------------------------------------
            */
            $totals = DB::table('order_products')
                ->whereIn('order_id', $orderIds)
                ->selectRaw('
                    SUM(expected_quantity) as expected_total,
                    SUM(received_quantity) as fulfilled_total
                ')
                ->first();

            $expectedTotal  = $totals->expected_total ?? 0;
            $fulfilledTotal = $totals->fulfilled_total ?? 0;
            $missingTotal   = max($expectedTotal - $fulfilledTotal, 0);

            $extrasTotal = DB::table('scan_events')
                ->where('scan_session_id', $scanSessionId)
                ->where('event_status', 'extra')
                ->count();

            $scannedTotal = DB::table('scan_events')
                ->where('scan_session_id', $scanSessionId)
                ->where('event_status', 'scanned')
                ->count();

            /*
            |--------------------------------------------------------------------------
            | 7️⃣ Status
            |--------------------------------------------------------------------------
            */
            if ($missingTotal == 0 && $extrasTotal == 0) {
                $status = 'COMPLETE';
            } elseif ($missingTotal > 0 && $extrasTotal == 0) {
                $status = 'INCOMPLETE';
            } elseif ($missingTotal == 0 && $extrasTotal > 0) {
                $status = 'OVER_SCANNED';
            } else {
                $status = 'PARTIAL';
            }

            $led = $extraCount > 0 ? 'ROJO' : 'VERDE';

            return [
                'extras_in_this_batch'    => $extraCount,
                'total_extras_in_session' => $extrasTotal,
                'scanned_in_this_batch'   => $scannedCount,
                'expected_total'          => $expectedTotal,
                'scanned_total'           => $scannedTotal,
                'missing_total'           => $missingTotal,
                'status'                  => $status,
                'LED'                     => $led,
            ];
        });

        if (isset($result['error']) && $result['error'] === true) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'invalid_products' => $result['invalid_products'] ?? []
            ], 422);
        }

        return $this->ok($result, 'Batch processed successfully');

    } catch (\Throwable $e) {

        logger()->error("Dock scan batch failed", [
            'error' => $e->getMessage(),
            'dock_id' => $dockId,
        ]);

        return $this->fail('Internal Server Error', 500);
    }
}

public function close(int $dockId)
{
    $session = DB::table('order_scan_sessions')
        ->where('dock_id', $dockId)
        ->where('status', 'OPEN')
        ->lockForUpdate()
        ->first();

    if (!$session) {
        return $this->fail(
            message: 'No OPEN scan session found for this dock',
            status: 404
        );
    }

    DB::table('order_scan_sessions')
        ->where('scan_session_id', $session->scan_session_id)
        ->update([
            'status' => 'CLOSING',
            'updated_at' => now(),
        ]);

    CloseScanSessionJob::dispatch($session->scan_session_id);

    return $this->ok(
        data: [
            'dock_id' => $dockId,
            'scan_session_id' => $session->scan_session_id,
            'status' => 'CLOSING'
        ],
        message: 'Scan session closing started',
        status: 202
    );
}

#4 paso final mostrar resultados
public function show(int $dockId)
{
    /*
    |--------------------------------------------------------------------------
    | 1️⃣ Buscar la última sesión CERRADA del dock
    |--------------------------------------------------------------------------
    */
    $session = DB::table('order_scan_sessions')
        ->where('dock_id', $dockId)
        ->whereNotIn('status', ['OPEN', 'CLOSING'])
        ->orderByDesc('closed_at')
        ->first();

    if (!$session) {
        return $this->fail(
            message: 'No closed scan session found for this dock',
            status: 404
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 2️⃣ Obtener resultados por orden (sin extra_total)
    |--------------------------------------------------------------------------
    */
    $orders = DB::table('scan_session_results')
        ->select([
            'scan_session_results_id',
            'scan_session_id',
            'order_id',
            'dock_id',
            'expected_total',
            'scanned_total',
            'missing_total',
            'status',
            'created_at',
            'updated_at'
        ])
        ->where('scan_session_id', $session->scan_session_id)
        ->get();

    if ($orders->isEmpty()) {
        return $this->fail(
            message: 'Closed session has no results',
            status: 404
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 3️⃣ Totales globales (calculados en SQL para mejor rendimiento)
    |--------------------------------------------------------------------------
    */
    $totals = DB::table('scan_session_results')
        ->where('scan_session_id', $session->scan_session_id)
        ->selectRaw('
            SUM(expected_total) as expected_total,
            SUM(scanned_total) as scanned_total,
            SUM(missing_total) as missing_total
        ')
        ->first();

    $expectedTotal = $totals->expected_total ?? 0;
    $scannedTotal  = $totals->scanned_total ?? 0;
    $missingTotal  = $totals->missing_total ?? 0;

    /*
    |--------------------------------------------------------------------------
    | 4️⃣ Extras globales (no pertenecen a órdenes)
    |--------------------------------------------------------------------------
    */
    $extraTotal = DB::table('scan_events')
        ->where('scan_session_id', $session->scan_session_id)
        ->where('event_status', 'extra')
        ->count();

    /*
    |--------------------------------------------------------------------------
    | 5️⃣ Respuesta final
    |--------------------------------------------------------------------------
    */
    return $this->ok(
        data: [
            'dock_id'         => $dockId,
            'scan_session_id' => $session->scan_session_id,
            'session_status'  => $session->status,
            'closed_at'       => $session->closed_at,

            'totals' => [
                'expected_total' => (int) $expectedTotal,
                'scanned_total'  => (int) $scannedTotal,
                'missing_total'  => (int) $missingTotal,
                'extra_total'    => (int) $extraTotal,
            ],

            'orders' => $orders
        ],
        message: 'Closed scan session results retrieved successfully'
    );
}

}
