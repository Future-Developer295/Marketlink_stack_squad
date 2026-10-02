<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PickupSlotsTableSeeder extends Seeder
{
    public function run(): void
    {
        $dayOffsets = [-3, -1, 1, 2, 3, 5];
        $windows    = [['09:00:00', '11:00:00', 20], ['14:00:00', '16:00:00', 15]];

        foreach (DB::table('farmer_profile')->orderBy('id')->get() as $farmer) {
            $marketIds = DB::table('market_farmers')
                ->where('farmer_id', $farmer->user_id)
                ->where('is_active', true)
                ->pluck('market_id');

            foreach ($marketIds as $marketId) {
                foreach ($dayOffsets as $offset) {
                    $date = now()->addDays($offset)->toDateString();

                    foreach ($windows as [$start, $end, $capacity]) {
                        DB::table('pickup_slots')->updateOrInsert(
                            [
                                'farmer_id'  => $farmer->id,
                                'market_id'  => $marketId,
                                'date'       => $date,
                                'start_time' => $start,
                            ],
                            [
                                'end_time'     => $end,
                                'capacity'     => $capacity,
                                'is_available' => $offset >= 0,
                                'created_at'   => now(),
                                'updated_at'   => now(),
                            ]
                        );
                    }
                }
            }
        }
    }
}
