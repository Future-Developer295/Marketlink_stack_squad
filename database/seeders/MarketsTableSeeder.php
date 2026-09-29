<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketsTableSeeder extends Seeder
{
    public function run(): void
    {
        $markets = [
            ['Lahore Farmers Market',    'Main Market Road', 'Lahore',     'Punjab', 31.5204, 74.3587, 'Mon, Tue, Wed, Thu, Fri', '08:00:00', '18:00:00'],
            ['Multan Fresh Market',      'Bosan Road',       'Multan',     'Punjab', 30.1575, 71.5249, 'Mon, Wed, Fri, Sat',      '09:00:00', '19:00:00'],
            ['Karachi Organic Market',   'University Road',  'Karachi',    'Sindh',  24.9207, 67.0882, 'Tue, Thu, Sat, Sun',      '08:00:00', '17:00:00'],
            ['Rawalpindi Fresh Bazaar',  'Committee Chowk',  'Rawalpindi', 'Punjab', 33.5984, 73.0441, 'Mon, Wed, Fri, Sat',      '09:00:00', '18:00:00'],
        ];

        foreach ($markets as [$name, $address, $city, $state, $lat, $lng, $days, $start, $end]) {
            DB::table('markets')->updateOrInsert(
                ['name' => $name],
                [
                    'address' => $address, 'city' => $city, 'state' => $state, 'country' => 'Pakistan',
                    'latitude' => $lat, 'longitude' => $lng, 'operating_days' => $days,
                    'start_time' => $start, 'end_time' => $end,
                    'created_at' => now(), 'updated_at' => now(),
                ]
            );
        }
    }
}
