<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewRepliesTableSeeder extends Seeder
{
    public function run(): void
    {
        $replies = [
            ['review_id' => 1, 'response' => 'Thank you for your valuable feedback.'],
            ['review_id' => 2, 'response' => 'Thank you! We are glad you liked the products.'],
            ['review_id' => 3, 'response' => 'Thank you for your kind words and support.'],
        ];

        foreach ($replies as $reply) {
            DB::table('review_replies')->updateOrInsert(
                ['review_id' => $reply['review_id']],
                $reply + ['updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}
