<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderProductsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('order_products')->insert([
            [
                'order_products_id' => 1,
                'order_id'          => 1,
                'product_id'        => 1,
                'expected_quantity' => 2,
                'received_quantity' => 0,
                'unit_price'        => 10,
                'created_at'        => Carbon::create(2025, 2, 13),
            ],
            [
                'order_products_id' => 2,
                'order_id'          => 1,
                'product_id'        => 2,
                'expected_quantity' => 3,
                'received_quantity' => 0,
                'unit_price'        => 20,
                'created_at'        => Carbon::create(2025, 2, 13),
            ],
            [
                'order_products_id' => 3,
                'order_id'          => 1,
                'product_id'        => 3,
                'expected_quantity' => 4,
                'received_quantity' => 0,
                'unit_price'        => 30,
                'created_at'        => Carbon::create(2025, 2, 13),
            ],
            [
                'order_products_id' => 4,
                'order_id'          => 2,
                'product_id'        => 4,
                'expected_quantity' => 2,
                'received_quantity' => 0,
                'unit_price'        => 10,
                'created_at'        => Carbon::create(2025, 2, 13),
            ],
            [
                'order_products_id' => 5,
                'order_id'          => 2,
                'product_id'        => 5,
                'expected_quantity' => 3,
                'received_quantity' => 0,
                'unit_price'        => 20,
                'created_at'        => Carbon::create(2025, 2, 13),
            ],
            [
                'order_products_id' => 6,
                'order_id'          => 3,
                'product_id'        => 1,
                'expected_quantity' => 2,
                'received_quantity' => 0,
                'unit_price'        => 10,
                'created_at'        => Carbon::create(2025, 2, 13),
            ],
        ]);
    }
}
