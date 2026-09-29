<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('markets')->insert([
            [
                'name' => 'Lahore Farmers Market',
                'address' => 'Main Market Road',
                'city' => 'Lahore',
                'state' => 'Punjab',
                'country' => 'Pakistan',
                'latitude' => 31.5204,
                'longitude' => 74.3587,
                'operating_days' => 'Mon, Tue, Wed, Thu, Fri',
                'start_time' => '08:00:00',
                'end_time' => '18:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Multan Fresh Market',
                'address' => 'Bosan Road',
                'city' => 'Multan',
                'state' => 'Punjab',
                'country' => 'Pakistan',
                'latitude' => 30.1575,
                'longitude' => 71.5249,
                'operating_days' => 'Mon, Wed, Fri, Sat',
                'start_time' => '09:00:00',
                'end_time' => '19:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Karachi Organic Market',
                'address' => 'University Road',
                'city' => 'Karachi',
                'state' => 'Sindh',
                'country' => 'Pakistan',
                'latitude' => 24.9207,
                'longitude' => 67.0882,
                'operating_days' => 'Tue, Thu, Sat, Sun',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
