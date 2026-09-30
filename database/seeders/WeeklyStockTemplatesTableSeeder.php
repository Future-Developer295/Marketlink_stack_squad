<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeeklyStockTemplatesTableSeeder extends Seeder
{
    public function run(): void
    {
        $days = ['Mon', 'Wed', 'Fri'];

        foreach (DB::table('farmer_profile')->orderBy('id')->pluck('id') as $farmerId) {
            $products = DB::table('products')
                ->where('farmer_id', $farmerId)
                ->orderBy('id')
                ->limit(3)
                ->get();

            foreach ($products as $i => $product) {
                DB::table('weekly_stock_templates')->updateOrInsert(
                    [
                        'farmer_id'   => $farmerId,
                        'product_id'  => $product->id,
                        'day_of_week' => $days[$i % 3],
                    ],
                    [
                        'quantity'   => min(50, (int) $product->stock_quantity),
                        'unit'       => $product->unit,
                        'start_date' => now()->toDateString(),
                        'end_date'   => now()->addMonths(3)->toDateString(),
                        'is_active'  => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
