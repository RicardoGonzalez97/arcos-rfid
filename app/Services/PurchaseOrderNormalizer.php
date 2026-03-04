<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProduct;
use App\Models\PurchaseOrder;
use App\Models\ExternalIntegration;
use Illuminate\Support\Facades\DB;

class PurchaseOrderNormalizer
{
    public function normalize(int $purchaseOrderId): void
    {
        DB::transaction(function () use ($purchaseOrderId) {

            /*
            |--------------------------------------------------------------------------
            | 0️⃣ Verificar si ya fue integrada
            |--------------------------------------------------------------------------
            */

            $alreadyIntegrated = ExternalIntegration::where([
                'external_source' => 'SQLITE_DEMO',
                'external_type'   => 'purchase_order',
                'external_id'     => $purchaseOrderId,
            ])->exists();

            if ($alreadyIntegrated) {
                return; // 🔥 evita duplicados
            }

            /*
            |--------------------------------------------------------------------------
            | 1️⃣ Obtener purchase order
            |--------------------------------------------------------------------------
            */

            $purchaseOrder = PurchaseOrder::with('items')
                ->findOrFail($purchaseOrderId);

            /*
            |--------------------------------------------------------------------------
            | 2️⃣ Crear orden estándar
            |--------------------------------------------------------------------------
            */

            $order = Order::create([
                'location' => $purchaseOrder->ciudad ?? 'DEFAULT',
                'type'     => $purchaseOrder->tipo ?? 'PURCHASE',
                'dock_id'  => 1,
            ]);

            /*
            |--------------------------------------------------------------------------
            | 3️⃣ Registrar integración
            |--------------------------------------------------------------------------
            */

            ExternalIntegration::create([
                'external_source'   => 'SQLITE_DEMO',
                'external_type'     => 'purchase_order',
                'external_id'       => $purchaseOrder->id,
                'internal_order_id' => $order->order_id,
            ]);

            /*
            |--------------------------------------------------------------------------
            | 4️⃣ Procesar items
            |--------------------------------------------------------------------------
            */

            foreach ($purchaseOrder->items as $item) {
                $externalId = (string) $item->id;
                $product = Product::firstOrCreate(
                ['product_id' => $externalId], // 👈 aquí
                [
                    'code'     => $item->codigo_cliente,
                    'name'     => $item->modelo ?? 'SIN NOMBRE',
                    'provider' => $purchaseOrder->proveedor,
                ]
            );

                OrderProduct::create([
                    'order_id'          => $order->order_id,
                    'product_id'        => $product->product_id,
                    'expected_quantity' => $item->cantidad,
                    'unit_price'        => $item->precio_unitario,
                ]);
            }
        });
    }
}