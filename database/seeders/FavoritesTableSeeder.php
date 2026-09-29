<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavoritesTableSeeder extends Seeder
{
    public function run(): void
    {
        // CHECK rule: either farmer_id OR product_id is set, never both / neither.
        $favorites = [
            ['customer@gmail.com',       'farmer', 'sanafarmer@gmail.com'],
            ['customer@gmail.com',       'farmer', 'alifarmer@gmail.com'],
            ['customer@gmail.com',       'product', 1],
            ['customer2@marketlink.com', 'farmer', 'farmer@gmail.com'],
            ['customer2@marketlink.com', 'product', 8],
            ['customer3@marketlink.com', 'product', 3],
            ['customer3@marketlink.com', 'farmer', 'farmer2@marketlink.com'],
            ['customer4@marketlink.com', 'product', 12],
        ];

        $productIds = DB::table('products')->orderBy('id')->pluck('id')->values();

        foreach ($favorites as [$email, $type, $target]) {
            $userId = DB::table('users')->where('email', $email)->value('id');
            if (! $userId) {
                continue;
            }

            $farmerId = null;
            $productId = null;

            if ($type === 'farmer') {
                $farmerUser = DB::table('users')->where('email', $target)->value('id');
                $farmerId = DB::table('farmer_profile')->where('user_id', $farmerUser)->value('id');
            } else {
                $productId = $productIds[$target - 1] ?? null;
            }

            if (! $farmerId && ! $productId) {
                continue;
            }

            DB::table('favorites')->updateOrInsert(
                ['user_id' => $userId, 'farmer_id' => $farmerId, 'product_id' => $productId],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
