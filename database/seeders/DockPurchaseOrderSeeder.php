<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DockPurchaseOrderSeeder extends Seeder
{
    public function run(): void
    {
        $docks = DB::table('supplier_appointment_slot_docks')->pluck('id')->toArray();
        $orders = DB::table('purchase_orders')->pluck('id')->toArray();

        $data = [];

        foreach ($orders as $orderId) {

            $dockId = $docks[array_rand($docks)];

            $data[] = [
                'dock_id' => $dockId,
                'purchase_order_id' => $orderId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('dock_purchase_orders')->insert($data);
    }
}