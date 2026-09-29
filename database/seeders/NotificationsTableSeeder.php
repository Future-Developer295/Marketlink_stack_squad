<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationsTableSeeder extends Seeder
{
    public function run(): void
    {
        // [email, type, title, message, is_read]
        $notifications = [
            ['customer@gmail.com',       'order',   'Order Confirmed',      'Your order has been confirmed successfully.',        false],
            ['customer@gmail.com',       'pickup',  'Pickup Reminder',      'Your pickup slot is scheduled for tomorrow.',        false],
            ['customer2@marketlink.com', 'order',   'Order Ready',          'Your order is ready for pickup at the market.',      false],
            ['customer3@marketlink.com', 'review',  'Review Added',         'Your review has been submitted successfully.',       true],
            ['customer4@marketlink.com', 'order',   'Order Received',       'We received your order and sent it to the farmer.',  false],
            ['farmer@gmail.com',         'order',   'New Order',            'You have received a new pre-order.',                 false],
            ['sanafarmer@gmail.com',     'review',  'New Review',           'A customer left a review on your stall.',            false],
            ['farmer2@marketlink.com',   'account', 'Profile Approved',     'Your farmer profile has been approved by admin.',    true],
            ['admin@gmail.com',          'system',  'New Farmer Signup',    'A new farmer is waiting for approval.',              false],
        ];

        foreach ($notifications as [$email, $type, $title, $message, $read]) {
            $userId = DB::table('users')->where('email', $email)->value('id');
            if (! $userId) {
                continue;
            }

            DB::table('notifications')->updateOrInsert(
                ['user_id' => $userId, 'title' => $title],
                [
                    'type'       => $type,
                    'message'    => $message,
                    'is_read'    => $read,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
