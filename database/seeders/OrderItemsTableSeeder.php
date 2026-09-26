<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemsTableSeeder extends Seeder
{



    public function run(): void
    {
        DB::table('order_items')->insert([
            [
                'order_id' => 1,
                'product_id' => 1,
                'quantity' => 5,
                'price' => 180.00,
                'subtotal' => 900.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 2,
                'product_id' => 2,
                'quantity' => 2,
                'price' => 350.00,
                'subtotal' => 700.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 3,
                'product_id' => 3,
                'quantity' => 2,
                'price' => 250.00,
                'subtotal' => 500.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
