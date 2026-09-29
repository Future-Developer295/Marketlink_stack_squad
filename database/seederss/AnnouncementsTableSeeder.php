<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnnouncementsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('announcement')->insert([
            [
                'admin_id' => 1,
                'title' => 'MarketLink New Update',
                'message' => 'MarketLink platform has been updated with new features.',
                'is_active' => true,
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'admin_id' => 1,
                'title' => 'Farmers Market Schedule',
                'message' => 'New market schedules are now available for customers.',
                'is_active' => true,
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'admin_id' => 1,
                'title' => 'Fresh Products Available',
                'message' => 'Fresh fruits, vegetables and grains are now available.',
                'is_active' => true,
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
