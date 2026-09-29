<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        // updateOrInsert keyed on the unique 'slug' column,
        // so reseeding an already-seeded database doesn't
        // throw a duplicate-entry error.

        $categories = [
            [
                'name' => 'Vegetables',
                'slug' => 'vegetables',
                'icon' => 'vegetables.png',
            ],
            [
                'name' => 'Fruits',
                'slug' => 'fruits',
                'icon' => 'fruits.png',
            ],
            [
                'name' => 'Grains',
                'slug' => 'grains',
                'icon' => 'grains.png',
            ],
            [
                'name' => 'Dairy',
                'slug' => 'dairy',
                'icon' => 'dairy.png',
            ],
            [
                'name' => 'Herbs',
                'slug' => 'herbs',
                'icon' => 'herbs.png',
            ],
            [
                'name' => 'Spices',
                'slug' => 'spices',
                'icon' => 'spices.png',
            ],
        
            [
                'name' => 'Pulses',
                'slug' => 'pulses',
                'icon' => 'pulses.png',
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['slug' => $category['slug']],
                $category + [
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}

