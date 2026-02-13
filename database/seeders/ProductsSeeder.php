<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'product_id'   => 1,
                'name'         => 'LAPTOP',
                'provider'     => 'MICROSOFT',
                'code'         => '123',
                'created_at'   => Carbon::create(2025, 2, 12),
            ],
            [
                'product_id'   => 2,
                'name'         => 'MOUSE',
                'provider'     => 'APPLE',
                'code'         => '456',
                'created_at'   => Carbon::create(2025, 2, 12),
            ],
            [
                'product_id'   => 3,
                'name'         => 'TECLADO',
                'provider'     => 'NVIDIA',
                'code'         => '789',
                'created_at'   => Carbon::create(2025, 2, 12),
            ],
            [
                'product_id'   => 4,
                'name'         => 'MONITOR',
                'provider'     => 'APPLE',
                'code'         => '1011',
                'created_at'   => Carbon::create(2025, 2, 12),
            ],
            [
                'product_id'   => 5,
                'name'         => 'SSD',
                'provider'     => 'APPLE',
                'code'         => '1213',
                'created_at'   => Carbon::create(2025, 2, 12),
            ],
        ]);
    }
}
