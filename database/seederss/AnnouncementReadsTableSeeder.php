<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnnouncementReadsTableSeeder extends Seeder
{
    public function run(): void
    {
        // updateOrInsert keyed on the unique ('announcement_id','user_id') pair,
        // so reseeding an already-seeded database doesn't throw a duplicate-entry error.
        $reads = [
            ['announcement_id' => 1, 'user_id' => 4],
            ['announcement_id' => 2, 'user_id' => 5],
            ['announcement_id' => 3, 'user_id' => 6],
        ];

        foreach ($reads as $read) {
            DB::table('announcement_reads')->updateOrInsert(
                ['announcement_id' => $read['announcement_id'], 'user_id' => $read['user_id']],
                $read + ['read_at' => now(), 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}