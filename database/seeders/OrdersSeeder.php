<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrdersSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('PRAGMA foreign_keys = ON');

        DB::table('orders')->insert([
            [
                'order_id'   => 1,
                'location'   => 'CDMX',
                'type'       => 'NORMAL',
                'truck_id'   => 123,
                'created_at' => Carbon::create(2026, 2, 8),
            ],
            [
                'order_id'   => 2,
                'location'   => 'CHIAPAS',
                'type'       => 'NORMAL',
                'truck_id'   => 456,
                'created_at' => Carbon::create(2026, 2, 8),
            ],
            [
                'order_id'   => 3,
                'location'   => 'GUADALAJARA',
                'type'       => 'NORMAL',
                'truck_id'   => 789,
                'created_at' => Carbon::create(2026, 2, 8),
            ],
        ]);

        logger('✅ OrdersSeeder ejecutado');
    }
}
