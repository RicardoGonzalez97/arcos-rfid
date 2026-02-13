<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RfidTagsInfoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('rfid_tags_info')->insert([
            ['rfid_tags_info_id' => 1,  'rfid' => 'rfid-1',  'product_id' => 1, 'created_at' => Carbon::create(2025, 2, 13)],
            ['rfid_tags_info_id' => 2,  'rfid' => 'rfid-2',  'product_id' => 1, 'created_at' => Carbon::create(2025, 2, 13)],
            ['rfid_tags_info_id' => 3,  'rfid' => 'rfid-3',  'product_id' => 1, 'created_at' => Carbon::create(2025, 2, 13)],
            ['rfid_tags_info_id' => 4,  'rfid' => 'rfid-4',  'product_id' => 1, 'created_at' => Carbon::create(2025, 2, 13)],

            ['rfid_tags_info_id' => 5,  'rfid' => 'rfid-5',  'product_id' => 2, 'created_at' => Carbon::create(2025, 2, 13)],
            ['rfid_tags_info_id' => 6,  'rfid' => 'rfid-6',  'product_id' => 2, 'created_at' => Carbon::create(2025, 2, 13)],
            ['rfid_tags_info_id' => 7,  'rfid' => 'rfid-7',  'product_id' => 2, 'created_at' => Carbon::create(2025, 2, 13)],

            ['rfid_tags_info_id' => 8,  'rfid' => 'rfid-8',  'product_id' => 3, 'created_at' => Carbon::create(2025, 2, 13)],
            ['rfid_tags_info_id' => 9,  'rfid' => 'rfid-9',  'product_id' => 3, 'created_at' => Carbon::create(2025, 2, 13)],
            ['rfid_tags_info_id' => 10, 'rfid' => 'rfid-10', 'product_id' => 3, 'created_at' => Carbon::create(2025, 2, 13)],
            ['rfid_tags_info_id' => 11, 'rfid' => 'rfid-11', 'product_id' => 3, 'created_at' => Carbon::create(2025, 2, 13)],

            ['rfid_tags_info_id' => 12, 'rfid' => 'rfid-12', 'product_id' => 4, 'created_at' => Carbon::create(2025, 2, 13)],
            ['rfid_tags_info_id' => 13, 'rfid' => 'rfid-13', 'product_id' => 4, 'created_at' => Carbon::create(2025, 2, 13)],

            ['rfid_tags_info_id' => 14, 'rfid' => 'rfid-14', 'product_id' => 5, 'created_at' => Carbon::create(2025, 2, 13)],
            ['rfid_tags_info_id' => 15, 'rfid' => 'rfid-15', 'product_id' => 5, 'created_at' => Carbon::create(2025, 2, 13)],
            ['rfid_tags_info_id' => 16, 'rfid' => 'rfid-16', 'product_id' => 5, 'created_at' => Carbon::create(2025, 2, 13)],
        ]);
    }
}
