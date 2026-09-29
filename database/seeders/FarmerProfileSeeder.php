<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FarmerProfileSeeder extends Seeder
{
    public function run(): void
    {
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
            [
                'user_id' => 4,
                'farmer_image' => null,
                'stall_name' => 'Usman Organic Farm',
                'business_name' => 'Usman Fresh Foods',
                'description' => 'Naturally grown organic fruits and vegetables.',
                'address' => 'Canal Road',
                'city' => 'Faisalabad',
                'state' => 'Punjab',
                'country' => 'Pakistan',
                'latitude' => 31.4504,
                'longitude' => 73.1350,
                'operating_days' => 'Mon, Tue, Thu, Fri, Sat',
                'start_time' => '07:00:00',
                'end_time' => '17:00:00',
                'approval_status' => 'approved',
                'approved_by' => 1,
                'approved_at' => now(),
            ],
            [
                'user_id' => 5,
                'farmer_image' => null,
                'stall_name' => 'Fatima Dairy Stall',
                'business_name' => 'Fatima Dairy Farm',
                'description' => 'Fresh milk and quality dairy products.',
                'address' => 'University Road',
                'city' => 'Peshawar',
                'state' => 'Khyber Pakhtunkhwa',
                'country' => 'Pakistan',
                'latitude' => 34.0151,
                'longitude' => 71.5249,
                'operating_days' => 'Mon, Tue, Wed, Thu, Fri, Sat',
                'start_time' => '06:00:00',
                'end_time' => '16:00:00',
                'approval_status' => 'approved',
                'approved_by' => 1,
                'approved_at' => now(),
            ],
            [
                'user_id' => 6,
                'farmer_image' => null,
                'stall_name' => 'Bilal Vegetable Stall',
                'business_name' => 'Bilal Green Farm',
                'description' => 'Fresh seasonal vegetables harvested daily.',
                'address' => 'Airport Road',
                'city' => 'Karachi',
                'state' => 'Sindh',
                'country' => 'Pakistan',
                'latitude' => 24.8607,
                'longitude' => 67.0011,
                'operating_days' => 'Mon, Tue, Wed, Thu, Fri, Sat',
                'start_time' => '08:00:00',
                'end_time' => '20:00:00',
                'approval_status' => 'approved',
                'approved_by' => 1,
                'approved_at' => now(),
            ],
            [
                'user_id' => 7,
                'farmer_image' => null,
                'stall_name' => 'Sana Fruit Stall',
                'business_name' => 'Sana Fresh Fruits',
                'description' => 'High-quality fresh seasonal fruits.',
                'address' => 'Satellite Town',
                'city' => 'Rawalpindi',
                'state' => 'Punjab',
                'country' => 'Pakistan',
                'latitude' => 33.5651,
                'longitude' => 73.0169,
                'operating_days' => 'Mon, Wed, Thu, Fri, Sat',
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
                'approval_status' => 'approved',
                'approved_by' => 1,
                'approved_at' => now(),
            ],
        ];

        foreach ($profiles as $profile) {
            DB::table('farmer_profile')->updateOrInsert(
                ['user_id' => $profile['user_id']],
                array_merge($profile, [
                    'updated_at' => now(),
                    'created_at' => now(),
                ])
            );
        }
    }
}