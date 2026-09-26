<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketFarmersTableSeeder extends Seeder
{
    public function run(): void
    {
        // updateOrInsert keyed on the unique ('market_id','farmer_id') pair, so
        // reseeding an already-seeded database doesn't throw a duplicate-entry error.
        $links = [
            ['market_id' => 1, 'farmer_id' => 1, 'is_active' => true],
            ['market_id' => 2, 'farmer_id' => 2, 'is_active' => true],
            ['market_id' => 3, 'farmer_id' => 1, 'is_active' => true],
        ];

        foreach ($links as $link) {
            DB::table('market_farmers')->updateOrInsert(
                ['market_id' => $link['market_id'], 'farmer_id' => $link['farmer_id']],
                $link + ['updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}
