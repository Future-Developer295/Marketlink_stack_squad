<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeeklyStockTemplatesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('weekly_stock_templates')->insert([
            [
                'farmer_id' => 1,
                'product_id' => 1,
                'day_of_week' => 'Mon',
                'quantity' => 50,
                'unit' => 'kg',
                'start_date' => '2026-10-01',
                'end_date' => '2026-12-31',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'farmer_id' => 2,
                'product_id' => 2,
                'day_of_week' => 'Wed',
                'quantity' => 40,
                'unit' => 'kg',
                'start_date' => '2026-10-01',
                'end_date' => '2026-12-31',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'farmer_id' => 1,
                'product_id' => 3,
                'day_of_week' => 'Fri',
                'quantity' => 100,
                'unit' => 'kg',
                'start_date' => '2026-10-01',
                'end_date' => '2026-12-31',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}