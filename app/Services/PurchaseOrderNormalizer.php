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
        logger()->info('🚀 START normalize()', [
            'purchase_order_id' => $purchaseOrderId
        ]);

        try {

            DB::transaction(function () use ($purchaseOrderId) {

                $now = now();

                logger()->info('🧩 Transaction started');

                /*
                |------------------------------------------------------------------
                | 0️⃣ Evitar doble integración
                |------------------------------------------------------------------
                */

                $alreadyIntegrated = ExternalIntegration::where([
                    'external_source' => 'SQLITE_DEMO',
                    'external_type'   => 'purchase_order',
                    'external_id'     => $purchaseOrderId,
                ])
                ->lockForUpdate()
                ->exists();

                logger()->info('🔍 alreadyIntegrated check', [
                    'result' => $alreadyIntegrated
                ]);

                if ($alreadyIntegrated) {
                    logger()->warning('⚠️ SKIP: ya estaba integrada', [
                        'purchase_order_id' => $purchaseOrderId
                    ]);
                    return;
                }

                /*
                |------------------------------------------------------------------
                | 1️⃣ Obtener purchase order
                |------------------------------------------------------------------
                */

                $purchaseOrder = PurchaseOrder::with('items')
                    ->findOrFail($purchaseOrderId);

                logger()->info('📦 PurchaseOrder loaded', [
                    'id' => $purchaseOrder->id,
                    'items_count' => $purchaseOrder->items->count()
                ]);

                /*
                |------------------------------------------------------------------
                | 2️⃣ Obtener dock
                |------------------------------------------------------------------
                */

                $dockId = DB::table('dock_purchase_orders')
                    ->where('purchase_order_id', $purchaseOrderId)
                    ->value('dock_id');

                logger()->info('🚚 Dock lookup', [
                    'dock_id' => $dockId
                ]);

                if (!$dockId) {
                    logger()->error('❌ No dock found');
                    throw new \Exception("No dock assigned to purchase order {$purchaseOrderId}");
                }

                /*
                |------------------------------------------------------------------
                | 3️⃣ Crear order
                |------------------------------------------------------------------
                */

                $order = Order::where('order_id', $purchaseOrder->id)->first();

                if (!$order) {

                    logger()->info('🆕 Creating order');

                    $order = Order::create([
                        'order_id'          => $purchaseOrder->id,
                        'purchase_order_id' => $purchaseOrder->id,
                        'location'          => $purchaseOrder->ciudad ?? 'DEFAULT',
                        'type'              => $purchaseOrder->tipo ?? 'PURCHASE',
                        'dock_id'           => $dockId,
                    ]);

                } else {
                    logger()->info('ℹ️ Order already exists', [
                        'order_id' => $order->order_id
                    ]);
                }

                /*
                |------------------------------------------------------------------
                | 4️⃣ Registrar integración
                |------------------------------------------------------------------
                */

                logger()->info('🧾 Registering integration');

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
                |------------------------------------------------------------------
                | 5️⃣ Items
                |------------------------------------------------------------------
                */

                $items = $purchaseOrder->items;

                if ($items->isEmpty()) {
                    logger()->warning('⚠️ No items found → EXIT');
                    return;
                }

                logger()->info('📦 Items ready', [
                    'count' => $items->count()
                ]);

                $productIds = $items
                    ->pluck('id')
                    ->map(fn ($id) => (string) $id)
                    ->toArray();

                logger()->info('🔑 Product IDs', [
                    'ids' => $productIds
                ]);

                /*
                |------------------------------------------------------------------
                | 6️⃣ Productos existentes
                |------------------------------------------------------------------
                */

                $existingProducts = Product::whereIn('product_id', $productIds)
                    ->pluck('product_id')
                    ->flip()
                    ->toArray();

                logger()->info('📦 Existing products', [
                    'count' => count($existingProducts)
                ]);

                /*
                |------------------------------------------------------------------
                | 7️⃣ Insertar nuevos productos
                |------------------------------------------------------------------
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

                logger()->info('🆕 Products to insert', [
                    'count' => count($productsToInsert)
                ]);

                if (!empty($productsToInsert)) {
                    DB::table('products')->insert($productsToInsert);
                    logger()->info('✅ Products inserted');
                }

                /*
                |------------------------------------------------------------------
                | 8️⃣ Limpiar order_products
                |------------------------------------------------------------------
                */

                DB::table('order_products')
                    ->where('order_id', $order->order_id)
                    ->delete();

                logger()->info('🧹 order_products cleaned');

                /*
                |------------------------------------------------------------------
                | 9️⃣ Insertar order_products
                |------------------------------------------------------------------
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

                logger()->info('📦 order_products prepared', [
                    'count' => count($orderProductsInsert)
                ]);

                DB::table('order_products')->insert($orderProductsInsert);

                logger()->info('✅ order_products inserted');

            });

            logger()->info('🎉 END normalize() SUCCESS');

        } catch (\Throwable $e) {

            logger()->error('💥 ERROR normalize()', [
                'purchase_order_id' => $purchaseOrderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }
}