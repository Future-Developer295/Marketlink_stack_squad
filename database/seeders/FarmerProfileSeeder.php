<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FarmerProfileSeeder extends Seeder
{
    public function run(): void
    {
        // updateOrInsert keyed on the unique 'user_id' column, so reseeding an
        // already-seeded database doesn't throw a duplicate-entry error.
        $profiles = [
            [
                'user_id' => 2,
                'farmer_image' => null,
                'stall_name' => 'Ali Fresh Stall',
                'business_name' => 'Ali Farm',
                'description' => 'Fresh and organic farm products.',
                'address' => 'Main Market Road',
                'city' => 'Lahore',
                'state' => 'Punjab',
                'country' => 'Pakistan',
                'latitude' => 31.5204,
                'longitude' => 74.3587,
                'operating_days' => 'Mon, Tue, Wed, Thu, Fri',
                'start_time' => '08:00:00',
                'end_time' => '18:00:00',
                'approval_status' => 'approved',
                'approved_by' => 1,
                'approved_at' => now(),
            ],
            [
                'user_id' => 3,
                'farmer_image' => null,
                'stall_name' => 'Ahmed Fresh Stall',
                'business_name' => 'Ahmed Farm',
                'description' => 'Fresh fruits and vegetables.',
                'address' => 'Bosan Road',
                'city' => 'Multan',
                'state' => 'Punjab',
                'country' => 'Pakistan',
                'latitude' => 30.1575,
                'longitude' => 71.5249,
                'operating_days' => 'Mon, Wed, Fri, Sat',
                'start_time' => '09:00:00',
                'end_time' => '19:00:00',
                'approval_status' => 'approved',
                'approved_by' => 1,
                'approved_at' => now(),
            ],
        ];

        foreach ($profiles as $profile) {
            DB::table('farmer_profile')->updateOrInsert(
                ['user_id' => $profile['user_id']],
                $profile + ['updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}