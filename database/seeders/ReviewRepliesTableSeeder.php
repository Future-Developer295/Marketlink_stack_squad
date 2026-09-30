<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewRepliesTableSeeder extends Seeder
{
    public function run(): void
    {
        $responses = [
            'Thank you for your valuable feedback.',
            'Thank you! We are glad you liked the products.',
            'Thank you for your kind words and support.',
            'Happy to serve you again, see you at the market!',
            'Sorry for the delay, we will improve our pickup timing.',
        ];

        $reviews = DB::table('reviews')->where('is_flagged', false)->orderBy('id')->pluck('id');

        foreach ($reviews as $i => $reviewId) {
            DB::table('review_replies')->updateOrInsert(
                ['review_id' => $reviewId],
                [
                    'response'   => $responses[$i % count($responses)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
