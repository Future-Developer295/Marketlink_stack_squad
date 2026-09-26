<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('notifications')->insert([
            [
                'user_id' => 4,
                'type' => 'order',
                'title' => 'Order Confirmed',
                'message' => 'Your order has been confirmed successfully.',
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'type' => 'pickup',
                'title' => 'Pickup Reminder',
                'message' => 'Your pickup slot is scheduled for tomorrow.',
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6,
                'type' => 'review',
                'title' => 'Review Added',
                'message' => 'Your review has been submitted successfully.',
                'is_read' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
