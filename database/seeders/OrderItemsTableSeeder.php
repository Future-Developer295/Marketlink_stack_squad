<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemsTableSeeder extends Seeder
{
    public function run(): void
    {
        foreach (OrdersTableSeeder::specs() as $spec) {
            $customerId = DB::table('users')->where('email', $spec['customer'])->value('id');
            $farmerUser = DB::table('users')->where('email', $spec['farmer'])->value('id');
            $farmerId   = DB::table('farmer_profile')->where('user_id', $farmerUser)->value('id');

            $orderId = DB::table('orders')
                ->where('user_id', $customerId)
                ->where('farmer_id', $farmerId)
                ->where('notes', '[' . $spec['tag'] . '] ' . ($spec['note'] ?? ''))
                ->value('id');

            if (! $orderId) {
                continue;
            }

            foreach (OrdersTableSeeder::resolveItems($spec, $farmerId) as [$product, $qty]) {
                DB::table('order_items')->updateOrInsert(
                    ['order_id' => $orderId, 'product_id' => $product->id],
                    [
                        'quantity'   => $qty,
                        'price'      => $product->price,           // real price snapshot
                        'subtotal'   => $product->price * $qty,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
