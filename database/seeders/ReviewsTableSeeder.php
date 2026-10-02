<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewsTableSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = [
            ['customer@gmail.com',       'farmer@gmail.com',       0,    5, 'Fresh and good quality products.',           false],
            ['customer2@marketlink.com', 'farmer2@marketlink.com', 1,    4, 'Good quality and fresh, will order again.',  false],
            ['customer@gmail.com',       'alifarmer@gmail.com',    null, 5, 'Very reliable farmer and fresh products.',   false],
            ['customer2@marketlink.com', 'sanafarmer@gmail.com',   0,    5, 'Best fruits in the market.',                 false],
            ['customer3@marketlink.com', 'farmer@gmail.com',       2,    3, 'Decent quality but pickup was a bit late.',  false],
            ['customer4@marketlink.com', 'farmer2@marketlink.com', null, 2, 'Not happy with the packaging.',              true],
        ];

        foreach ($reviews as [$customer, $farmer, $productIndex, $rating, $comment, $flagged]) {
            $userId     = DB::table('users')->where('email', $customer)->value('id');
            $farmerUser = DB::table('users')->where('email', $farmer)->value('id');
            $farmerId   = DB::table('farmer_profile')->where('user_id', $farmerUser)->value('id');

            if (! $userId || ! $farmerId) {
                continue;
            }

            $productId = null;
            if ($productIndex !== null) {
                $productId = DB::table('products')->where('farmer_id', $farmerId)
                    ->orderBy('id')->skip($productIndex)->value('id');
            }

            DB::table('reviews')->updateOrInsert(
                ['user_id' => $userId, 'farmer_id' => $farmerId, 'product_id' => $productId],
                [
                    'rating'     => $rating,
                    'comment'    => $comment,
                    'is_flagged' => $flagged,
                    'is_active'  => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
