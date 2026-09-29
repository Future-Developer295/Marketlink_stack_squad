<?php

namespace Database\Seeders;

use App\Models\FarmerProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FarmerProfileSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::where('role', 'admin')->value('id');

        $farmers = [
            [
                'name' => 'Sana',
                'email' => 'sanafarmer@gmail.com',
                'phone' => '03001234567',
                'address' => 'Satellite Town, Rawalpindi',
                'stall_name' => 'Sana Fruit Stall',
                'business_name' => 'Sana Fresh Fruits',
                'description' => 'High-quality fresh seasonal fruits.',
                'profile_address' => 'Satellite Town',
                'city' => 'Rawalpindi',
                'state' => 'Punjab',
                'country' => 'Pakistan',
                'latitude' => 33.5651,
                'longitude' => 73.0169,
                'operating_days' => 'Mon, Wed, Thu, Fri, Sat',
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
            ],
            [
                'name' => 'Ali',
                'email' => 'alifarmer@gmail.com',
                'phone' => '03007654321',
                'address' => 'Gulshan-e-Iqbal, Karachi',
                'stall_name' => 'Ali Farm Stall',
                'business_name' => 'Ali Fresh Farms',
                'description' => 'Fresh seasonal produce from local farms.',
                'profile_address' => 'Gulshan-e-Iqbal',
                'city' => 'Karachi',
                'state' => 'Sindh',
                'country' => 'Pakistan',
                'latitude' => 24.9206,
                'longitude' => 67.0873,
                'operating_days' => 'Tue, Wed, Thu, Fri, Sat',
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
            ],
        ];

        foreach ($farmers as $farmerData) {
            $user = User::updateOrCreate(
                [
                    'email' => $farmerData['email'],
                ],
                [
                    'name' => $farmerData['name'],
                    'password' => Hash::make('password'),
                    'phone' => $farmerData['phone'],
                    'address' => $farmerData['address'],
                    'role' => 'farmer',
                    'is_active' => true,
                ]
            );

            FarmerProfile::updateOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'farmer_image' => null,
                    'stall_name' => $farmerData['stall_name'],
                    'business_name' => $farmerData['business_name'],
                    'description' => $farmerData['description'],
                    'address' => $farmerData['profile_address'],
                    'city' => $farmerData['city'],
                    'state' => $farmerData['state'],
                    'country' => $farmerData['country'],
                    'latitude' => $farmerData['latitude'],
                    'longitude' => $farmerData['longitude'],
                    'operating_days' => $farmerData['operating_days'],
                    'start_time' => $farmerData['start_time'],
                    'end_time' => $farmerData['end_time'],
                    'approval_status' => 'approved',
                    'approved_by' => $adminId,
                    'approved_at' => now(),
                ]
            );
        }
    }
}
