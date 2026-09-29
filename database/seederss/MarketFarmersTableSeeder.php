<?php

namespace Database\Seeders;

use App\Models\FarmerProfile;
use App\Models\Market;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketFarmersTableSeeder extends Seeder
{
    public function run(): void
    {
        $farmers = FarmerProfile::orderBy('id')
            ->take(2)
            ->get();

        $markets = Market::orderBy('id')
            ->take(2)
            ->get();

        if ($farmers->count() < 2) {
            throw new \RuntimeException(
                'At least 2 farmer profiles are required before seeding market farmers.'
            );
        }

        if ($markets->count() < 1) {
            throw new \RuntimeException(
                'At least 1 market is required before seeding market farmers.'
            );
        }

        DB::table('market_farmers')->delete();

        foreach ($farmers as $index => $farmer) {
            $market = $markets[$index] ?? $markets[0];

            DB::table('market_farmers')->updateOrInsert(
                [
                    'market_id' => $market->id,
                    'farmer_id' => $farmer->user_id,
                ],
                [
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        $this->command->info(
            'Market farmers synced successfully.'
        );
    }
}