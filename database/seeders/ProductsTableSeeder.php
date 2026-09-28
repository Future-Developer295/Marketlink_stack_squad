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

            [
                'farmer_id' => 2,
                'category_id' => 1,
                'name' => 'Fresh Carrots',
                'description' => 'Fresh and crunchy carrots harvested from local farms.',
                'price' => 150.00,
                'stock_quantity' => 35,
                'unit' => 'kg',
                'image' => 'carrot.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'farmer_id' => 1,
                'category_id' => 2,
                'name' => 'Fresh Bananas',
                'description' => 'Fresh and naturally sweet bananas from local farmers.',
                'price' => 200.00,
                'stock_quantity' => 45,
                'unit' => 'kg',
                'image' => 'banana.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'farmer_id' => 2,
                'category_id' => 1,
                'name' => 'Fresh Spinach',
                'description' => 'Fresh green spinach, carefully harvested for great taste.',
                'price' => 120.00,
                'stock_quantity' => 30,
                'unit' => 'kg',
                'image' => 'spinach.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'farmer_id' => 1,
                'category_id' => 3,
                'name' => 'Fresh Milk',
                'description' => 'Fresh and pure farm milk delivered from local dairy farmers.',
                'price' => 220.00,
                'stock_quantity' => 25,
                'unit' => 'liter',
                'image' => 'milk.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'farmer_id' => 2,
                'category_id' => 2,
                'name' => 'Fresh Oranges',
                'description' => 'Juicy and fresh oranges picked from local farms.',
                'price' => 280.00,
                'stock_quantity' => 60,
                'unit' => 'kg',
                'image' => 'orange.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'farmer_id' => 1,
                'category_id' => 1,
                'name' => 'Fresh Potatoes',
                'description' => 'Fresh locally grown potatoes.',
                'price' => 100.00,
                'stock_quantity' => 80,
                'unit' => 'kg',
                'image' => 'potato.png',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'farmer_id' => 2,
                'category_id' => 3,
                'name' => 'Fresh Cucumbers',
                'description' => 'Fresh and crispy cucumbers from local farms.',
                'price' => 130.00,
                'stock_quantity' => 40,
                'unit' => 'kg',
                'image' => 'cucumbers.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}