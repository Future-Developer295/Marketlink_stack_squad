<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnnouncementsTableSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = DB::table('users')->where('role', 'admin')->value('id');
        $farmerUserId = DB::table('users')->where('email', 'farmer@gmail.com')->value('id');

        // audience: all | all_farmers | farmer (a single farmer -> farmer_id = users.id)
        $announcements = [
            ['MarketLink New Update',      'MarketLink platform has been updated with new features.',      'all',         null],
            ['Farmers Market Schedule',    'New market schedules are now available for customers.',        'all',         null],
            ['Fresh Products Available',   'Fresh fruits, vegetables and grains are now available.',       'all',         null],
            ['Farmer Guidelines',          'Please keep your weekly stock and pickup slots up to date.',   'all_farmers', null],
            ['Your Profile Is Live',       'Your stall is now visible to customers. Add more products!',   'farmer',      $farmerUserId],
        ];

        foreach ($announcements as [$title, $message, $audience, $farmerId]) {
            DB::table('announcement')->updateOrInsert(
                ['title' => $title],
                [
                    'admin_id'     => $adminId,
                    'message'      => $message,
                    'audience'     => $audience,
                    'farmer_id'    => $farmerId,
                    'is_active'    => true,
                    'published_at' => now(),
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]
            );
        }
    }
}
