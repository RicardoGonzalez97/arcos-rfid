<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuickScanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run()
{
    $now = now();

    $productId = DB::table('products')->insertGetId([
        'name' => 'Seed Product',
        'provider' => 'Seed',
        'code' => 'SEED-' . uniqid(),
        'created_at' => $now,
    ]);

    $orderId = DB::table('orders')->insertGetId([
        'location' => 'Seed',
        'type' => 'incoming',
        'created_at' => $now,
    ]);

    DB::table('rfid_tags_info')->insert([
        'rfid' => 'RFID-SEED',
        'product_id' => $productId,
        'created_at' => $now,
    ]);

    DB::table('order_products')->insert([
        'order_id' => $orderId,
        'product_id' => $productId,
        'quantity' => 5,
        'unit_price' => 50,
        'created_at' => $now,
    ]);
}

}
