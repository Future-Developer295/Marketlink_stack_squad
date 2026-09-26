<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // updateOrInsert (keyed on the unique 'email' column) instead of a raw
        // batch insert, so re-running `php artisan migrate --seed` on a database
        // that was already seeded updates these rows instead of throwing a
        // "Duplicate entry ... users_email_unique" error.
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin1234'),
                'phone' => '03001234567',
                'address' => 'Karachi, Pakistan',
                'role' => 'admin',
                'is_active' => true,
            ],
            [
                'name' => 'Ali Farmer',
                'email' => 'farmer@gmail.com',
                'password' => Hash::make('12345678'),
                'phone' => '03001234568',
                'address' => 'Lahore, Pakistan',
                'role' => 'farmer',
                'is_active' => true,
            ],
            [
                'name' => 'Ahmed Farmer',
                'email' => 'farmer2@marketlink.com',
                'password' => Hash::make('password123'),
                'phone' => '03001234570',
                'address' => 'Multan, Pakistan',
                'role' => 'farmer',
                'is_active' => true,
            ],
            [
                'name' => 'Ahmed Customer',
                'email' => 'customer@marketlink.com',
                'password' => Hash::make('password123'),
                'phone' => '03001234569',
                'address' => 'Karachi, Pakistan',
                'role' => 'customer',
                'is_active' => true,
            ],
            [
                'name' => 'Sara Customer',
                'email' => 'customer2@marketlink.com',
                'password' => Hash::make('password123'),
                'phone' => '03001234571',
                'address' => 'Islamabad, Pakistan',
                'role' => 'customer',
                'is_active' => true,
            ],
            [
                'name' => 'Hina Customer',
                'email' => 'customer3@marketlink.com',
                'password' => Hash::make('password123'),
                'phone' => '03001234572',
                'address' => 'Rawalpindi, Pakistan',
                'role' => 'customer',
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                $user + ['updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}
