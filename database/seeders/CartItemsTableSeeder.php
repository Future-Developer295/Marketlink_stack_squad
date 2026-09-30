<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartItemsTableSeeder extends Seeder
{
    public function run(): void
    {
        $carts = [
            'customer@gmail.com'       => [[0, 2], [4, 1]],
            'customer3@marketlink.com' => [[7, 3]],
            'customer4@marketlink.com' => [[10, 2], [12, 1]],
        ];

        $productIds = DB::table('products')->where('is_active', true)->orderBy('id')->pluck('id')->values();

        foreach ($carts as $email => $lines) {
            $userId = DB::table('users')->where('email', $email)->value('id');
            if (! $userId) {
                continue;
            }

            foreach ($lines as [$offset, $qty]) {
                $productId = $productIds[$offset] ?? null;
                if (! $productId) {
                    continue;
                }

                DB::table('cart_items')->updateOrInsert(
                    ['user_id' => $userId, 'product_id' => $productId],
                    ['quantity' => $qty, 'created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }
}
