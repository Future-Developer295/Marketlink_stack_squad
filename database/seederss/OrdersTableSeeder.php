<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('orders')->insert([
            [
                'user_id' => 4,
                'farmer_id' => 1,
                'pickup_slot_id' => 1,
                'total_amount' => 900.00,
                'status' => 'pending',
                'order_date' => '2026-10-01 08:30:00',
                'notes' => 'Please keep the products fresh.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'farmer_id' => 2,
                'pickup_slot_id' => 2,
                'total_amount' => 700.00,
                'status' => 'confirmed',
                'order_date' => '2026-10-02 10:30:00',
                'notes' => 'Customer will collect from the market.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6,
                'farmer_id' => 1,
                'pickup_slot_id' => 3,
                'total_amount' => 500.00,
                'status' => 'ready',
                'order_date' => '2026-10-03 14:30:00',
                'notes' => 'Order is ready for pickup.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
