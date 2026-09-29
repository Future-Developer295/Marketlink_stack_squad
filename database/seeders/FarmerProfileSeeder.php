<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FarmerProfileSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = DB::table('users')->where('role', 'admin')->value('id');

        $profiles = [
            'farmer@gmail.com' => [
                'stall_name' => 'Ali Green Stall', 'business_name' => 'Ali Green Farms',
                'description' => 'Fresh vegetables and grains from our Lahore farm.',
                'address' => 'Ferozepur Road', 'city' => 'Lahore', 'state' => 'Punjab', 'country' => 'Pakistan',
                'latitude' => 31.5204, 'longitude' => 74.3587,
                'operating_days' => 'Mon, Tue, Wed, Thu, Fri', 'start_time' => '08:00:00', 'end_time' => '17:00:00',
            ],
            'farmer2@marketlink.com' => [
                'stall_name' => 'Ahmed Orchard Stall', 'business_name' => 'Ahmed Orchards',
                'description' => 'Multan mangoes, citrus and seasonal fruits.',
                'address' => 'Bosan Road', 'city' => 'Multan', 'state' => 'Punjab', 'country' => 'Pakistan',
                'latitude' => 30.1575, 'longitude' => 71.5249,
                'operating_days' => 'Mon, Wed, Fri, Sat', 'start_time' => '09:00:00', 'end_time' => '18:00:00',
            ],
            'sanafarmer@gmail.com' => [
                'stall_name' => 'Sana Fruit Stall', 'business_name' => 'Sana Fresh Fruits',
                'description' => 'High-quality fresh seasonal fruits.',
                'address' => 'Satellite Town', 'city' => 'Rawalpindi', 'state' => 'Punjab', 'country' => 'Pakistan',
                'latitude' => 33.5651, 'longitude' => 73.0169,
                'operating_days' => 'Mon, Wed, Thu, Fri, Sat', 'start_time' => '09:00:00', 'end_time' => '18:00:00',
            ],
            'alifarmer@gmail.com' => [
                'stall_name' => 'Ali Farm Stall', 'business_name' => 'Ali Fresh Farms',
                'description' => 'Fresh seasonal produce from local farms.',
                'address' => 'Gulshan-e-Iqbal', 'city' => 'Karachi', 'state' => 'Sindh', 'country' => 'Pakistan',
                'latitude' => 24.9206, 'longitude' => 67.0873,
                'operating_days' => 'Tue, Wed, Thu, Fri, Sat', 'start_time' => '09:00:00', 'end_time' => '18:00:00',
            ],
        ];

        foreach ($profiles as $email => $data) {
            $userId = DB::table('users')->where('email', $email)->value('id');
            if (! $userId) {
                continue;
            }

            DB::table('farmer_profile')->updateOrInsert(
                ['user_id' => $userId],
                $data + [
                    'farmer_image'    => null,
                    'approval_status' => 'approved',
                    'approved_by'     => $adminId,
                    'approved_at'     => now(),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]
            );
        }
    }
}
