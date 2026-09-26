<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'farmer_id' => 1,
                'category_id' => 1,
                'name' => 'Fresh Tomatoes',
                'description' => 'Fresh and organic tomatoes.',
                'price' => 180.00,
                'stock_quantity' => 50,
                'unit' => 'kg',
                'image' => 'tomatoes.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'farmer_id' => 2,
                'category_id' => 2,
                'name' => 'Fresh Apples',
                'description' => 'Fresh and high quality apples.',
                'price' => 350.00,
                'stock_quantity' => 40,
                'unit' => 'kg',
                'image' => 'apples.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'farmer_id' => 1,
                'category_id' => 3,
                'name' => 'Organic Wheat',
                'description' => 'Fresh and organically grown wheat.',
                'price' => 250.00,
                'stock_quantity' => 100,
                'unit' => 'kg',
                'image' => 'wheat.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
