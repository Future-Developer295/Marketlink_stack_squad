<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnnouncementReadsTableSeeder extends Seeder
{
    public function run(): void
    {
        // (announcement_id, user_id) is UNIQUE.
        $reads = [
            'MarketLink New Update'    => ['customer@gmail.com', 'customer2@marketlink.com', 'farmer@gmail.com'],
            'Farmers Market Schedule'  => ['customer2@marketlink.com', 'customer3@marketlink.com'],
            'Fresh Products Available' => ['customer3@marketlink.com'],
            'Farmer Guidelines'        => ['farmer@gmail.com', 'sanafarmer@gmail.com'],
            'Your Profile Is Live'     => ['farmer@gmail.com'],
        ];

        foreach ($reads as $title => $emails) {
            $announcementId = DB::table('announcement')->where('title', $title)->value('id');
            if (! $announcementId) {
                continue;
            }

            foreach ($emails as $email) {
                $userId = DB::table('users')->where('email', $email)->value('id');
                if (! $userId) {
                    continue;
                }

                DB::table('announcement_reads')->updateOrInsert(
                    ['announcement_id' => $announcementId, 'user_id' => $userId],
                    ['read_at' => now(), 'created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }
}
