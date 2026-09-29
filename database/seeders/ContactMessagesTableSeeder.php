<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactMessagesTableSeeder extends Seeder
{
    public function run(): void
    {
        // user_id is nullable (guests can contact too)
        $messages = [
            ['customer@gmail.com', 'Ahmed Customer', 'customer@gmail.com',      'Pickup timing question', 'Can I collect my order 30 minutes later than my slot?', null],
            [null,                 'Guest Visitor',  'guest@example.com',       'How do I become a farmer?', 'I grow vegetables and want to sell on MarketLink. What are the steps?', null],
            ['customer3@marketlink.com', 'Hina Customer', 'customer3@marketlink.com', 'Feedback', 'Great platform, please add more dairy products.', now()],
        ];

        foreach ($messages as [$userEmail, $fullName, $email, $subject, $body, $readAt]) {
            $userId = $userEmail ? DB::table('users')->where('email', $userEmail)->value('id') : null;

            DB::table('contact_messages')->updateOrInsert(
                ['email' => $email, 'subject' => $subject],
                [
                    'user_id'    => $userId,
                    'full_name'  => $fullName,
                    'message'    => $body,
                    'ip_address' => '127.0.0.1',
                    'read_at'    => $readAt,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
