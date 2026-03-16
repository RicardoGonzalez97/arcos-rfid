<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\ExternalIntegration;
use Illuminate\Support\Facades\DB;

class PurchaseOrderNormalizer
{
    public function normalize(int $purchaseOrderId): void
    {
        DB::transaction(function () use ($purchaseOrderId) {

            $now = now();

            /*
            |--------------------------------------------------------------------------
            | 0️⃣ Evitar doble integración (lock para queues)
            |--------------------------------------------------------------------------
            */

            $alreadyIntegrated = ExternalIntegration::where([
                'external_source' => 'SQLITE_DEMO',
                'external_type'   => 'purchase_order',
                'external_id'     => $purchaseOrderId,
            ])
            ->lockForUpdate()
            ->exists();

            if ($alreadyIntegrated) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | 1️⃣ Obtener purchase order con items
            |--------------------------------------------------------------------------
            */

            $purchaseOrder = PurchaseOrder::with('items')
                ->findOrFail($purchaseOrderId);

            /*
            |--------------------------------------------------------------------------
            | 2️⃣ Obtener dock asignado
            |--------------------------------------------------------------------------
            */

            $dockId = DB::table('dock_purchase_orders')
                ->where('purchase_order_id', $purchaseOrderId)
                ->value('dock_id');

            if (!$dockId) {
                throw new \Exception("No dock assigned to purchase order {$purchaseOrderId}");
            }

            /*
            |--------------------------------------------------------------------------
            | 3️⃣ Crear orden interna (ID = purchase_order_id)
            |--------------------------------------------------------------------------
            */

            $order = Order::where('order_id', $purchaseOrder->id)->first();

            if (!$order) {

                $order = Order::create([
                    'order_id'          => $purchaseOrder->id,
                    'purchase_order_id' => $purchaseOrder->id,
                    'location'          => $purchaseOrder->ciudad ?? 'DEFAULT',
                    'type'              => $purchaseOrder->tipo ?? 'PURCHASE',
                    'dock_id'           => $dockId,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 4️⃣ Registrar integración
            |--------------------------------------------------------------------------
            */

            ExternalIntegration::updateOrInsert(
                [
                    'external_source' => 'SQLITE_DEMO',
                    'external_type'   => 'purchase_order',
                    'external_id'     => $purchaseOrder->id,
                ],
                [
                    'internal_order_id' => $order->order_id,
                    'created_at'        => $now,
                    'updated_at'        => $now
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | 5️⃣ Preparar items
            |--------------------------------------------------------------------------
            */

            $items = $purchaseOrder->items;

            if ($items->isEmpty()) {
                return;
            }

            $productIds = $items
                ->pluck('id')
                ->map(fn ($id) => (string) $id)
                ->toArray();

            /*
            |--------------------------------------------------------------------------
            | 6️⃣ Obtener productos existentes
            |--------------------------------------------------------------------------
            */

            $existingProducts = Product::whereIn('product_id', $productIds)
                ->pluck('product_id')
                ->flip()
                ->toArray();

            /*
            |--------------------------------------------------------------------------
            | 7️⃣ Insertar productos faltantes (batch)
            |--------------------------------------------------------------------------
            */

            $productsToInsert = [];

            foreach ($items as $item) {

                $productId = (string) $item->id;

                if (!isset($existingProducts[$productId])) {

                    $productsToInsert[] = [
                        'product_id' => $productId,
                        'code'       => $item->codigo_cliente,
                        'name'       => $item->modelo ?? 'SIN NOMBRE',
                        'provider'   => $purchaseOrder->proveedor,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            if (!empty($productsToInsert)) {
                DB::table('products')->insert($productsToInsert);
            }

            /*
            |--------------------------------------------------------------------------
            | 8️⃣ Limpiar order_products si el job se ejecuta otra vez
            |--------------------------------------------------------------------------
            */

            DB::table('order_products')
                ->where('order_id', $order->order_id)
                ->delete();

            /*
            |--------------------------------------------------------------------------
            | 9️⃣ Insertar order_products en batch
            |--------------------------------------------------------------------------
            */

            $orderProductsInsert = [];

            foreach ($items as $item) {

                $productId = (string) $item->id;

                $orderProductsInsert[] = [
                    'order_id'          => $order->order_id,
                    'product_id'        => $productId,
                    'expected_quantity' => $item->cantidad,
                    'received_quantity' => 0,
                    'unit_price'        => $item->precio_unitario,
                    'is_completed'      => false,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ];
            }

            DB::table('order_products')->insert($orderProductsInsert);

        });
    }
}