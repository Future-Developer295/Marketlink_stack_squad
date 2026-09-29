<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PickupSlotsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pickup_slots')->insert([
            [
                'farmer_id' => 1,
                'market_id' => 1,
                'date' => '2026-10-01',
                'start_time' => '08:00:00',
                'end_time' => '10:00:00',
                'capacity' => 20,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'farmer_id' => 2,
                'market_id' => 2,
                'date' => '2026-10-02',
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
                'capacity' => 15,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'farmer_id' => 1,
                'market_id' => 3,
                'date' => '2026-10-03',
                'start_time' => '14:00:00',
                'end_time' => '16:00:00',
                'capacity' => 25,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
