<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('reviews')->insert([
            [
                'user_id' => 4,
                'farmer_id' => 1,
                'product_id' => 1,
                'rating' => 5,
                'comment' => 'Fresh and good quality products.',
                'is_flagged' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'farmer_id' => 2,
                'product_id' => 2,
                'rating' => 4,
                'comment' => 'Good quality and fresh apples.',
                'is_flagged' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6,
                'farmer_id' => 1,
                'product_id' => null,
                'rating' => 5,
                'comment' => 'Very reliable farmer and fresh products.',
                'is_flagged' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
