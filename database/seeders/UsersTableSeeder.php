<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // ---------- Admin ----------
            ['Admin User',      'admin@gmail.com',            'admin1234',   '03001234567', 'Karachi, Pakistan',              'admin'],

            // ---------- Farmers ----------
            ['Ali Farmer',      'farmer@gmail.com',           '12345678',    '03001234568', 'Lahore, Pakistan',               'farmer'],
            ['Ahmed Farmer',    'farmer2@marketlink.com',     'password123', '03001234570', 'Multan, Pakistan',               'farmer'],
            ['Sana',            'sanafarmer@gmail.com',       'password',    '03001234573', 'Satellite Town, Rawalpindi',     'farmer'],
            ['Ali',             'alifarmer@gmail.com',        'password',    '03007654321', 'Gulshan-e-Iqbal, Karachi',       'farmer'],

            // ---------- Customers ----------
            ['Ahmed Customer',  'customer@gmail.com',         'password123', '03001234569', 'Karachi, Pakistan',              'customer'],
            ['Sara Customer',   'customer2@marketlink.com',   'password123', '03001234571', 'Islamabad, Pakistan',            'customer'],
            ['Hina Customer',   'customer3@marketlink.com',   'password123', '03001234572', 'Rawalpindi, Pakistan',           'customer'],
            ['Usman Customer',  'customer4@marketlink.com',   'password123', '03001234574', 'Lahore, Pakistan',               'customer'],
        ];

        foreach ($users as [$name, $email, $password, $phone, $address, $role]) {
            DB::table('users')->updateOrInsert(
                ['email' => $email],
                [
                    'name'              => $name,
                    'password'          => Hash::make($password),
                    'phone'             => $phone,
                    'address'           => $address,
                    'role'              => $role,
                    'is_active'         => true,
                    'approval_status'   => 'approved',
                    'email_verified_at' => now(),   // demo accounts skip the email OTP step
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]
            );
        }
    }
}
