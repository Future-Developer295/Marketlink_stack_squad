<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketFarmersTableSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'farmer@gmail.com'       => ['Lahore Farmers Market'],
            'farmer2@marketlink.com' => ['Multan Fresh Market', 'Lahore Farmers Market'],
            'sanafarmer@gmail.com'   => ['Rawalpindi Fresh Bazaar', 'Lahore Farmers Market'],
            'alifarmer@gmail.com'    => ['Karachi Organic Market'],
        ];

        foreach ($map as $email => $marketNames) {
            $userId = DB::table('users')->where('email', $email)->value('id');
            if (! $userId) {
                continue;
            }

            foreach ($marketNames as $marketName) {
                $marketId = DB::table('markets')->where('name', $marketName)->value('id');
                if (! $marketId) {
                    continue;
                }

                DB::table('market_farmers')->updateOrInsert(
                    ['market_id' => $marketId, 'farmer_id' => $userId],
                    ['is_active' => true, 'created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }
}
