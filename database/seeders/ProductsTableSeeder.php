<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    public function run(): void
    {
        $farmers = DB::table('farmer_profile')->orderBy('id')->pluck('id')->values();

        if ($farmers->isEmpty()) {
            throw new \RuntimeException('Seed farmer profiles before products.');
        }

        $adminId = DB::table('users')->where('role', 'admin')->value('id');

        $farmerCount = $farmers->count();
        $pick = fn (int $i) => $farmers[$i % $farmerCount];

        $products = [
            [
                'farmer_id' => $pick(0),
                'category_id' => 1,
                'name' => 'Fresh Garlic',
                'description' => 'Fresh locally grown garlic.',
                'price' => 280,
                'stock_quantity' => 50,
                'unit' => 'kg',
                'image' => 'vegetables/garlic.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(1),
                'category_id' => 1,
                'name' => 'Fresh Tomatoes',
                'description' => 'Fresh and juicy tomatoes from local farms.',
                'price' => 180,
                'stock_quantity' => 40,
                'unit' => 'kg',
                'image' => 'vegetables/tomatoes.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(2),
                'category_id' => 1,
                'name' => 'Fresh Spinach',
                'description' => 'Fresh green spinach from local farms.',
                'price' => 120,
                'stock_quantity' => 30,
                'unit' => 'kg',
                'image' => 'vegetables/spinach.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(3),
                'category_id' => 1,
                'name' => 'Fresh Carrots',
                'description' => 'Fresh and naturally grown carrots.',
                'price' => 180,
                'stock_quantity' => 35,
                'unit' => 'kg',
                'image' => 'vegetables/carrot.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(4),
                'category_id' => 1,
                'name' => 'Fresh Beetroot',
                'description' => 'Fresh quality beetroot.',
                'price' => 200,
                'stock_quantity' => 30,
                'unit' => 'kg',
                'image' => 'vegetables/beet.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(5),
                'category_id' => 1,
                'name' => 'Fresh Bell Pepper',
                'description' => 'Fresh and colorful bell peppers.',
                'price' => 250,
                'stock_quantity' => 25,
                'unit' => 'kg',
                'image' => 'vegetables/bell pepper.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(6),
                'category_id' => 2,
                'name' => 'Fresh Apples',
                'description' => 'Fresh and high quality apples.',
                'price' => 350,
                'stock_quantity' => 40,
                'unit' => 'kg',
                'image' => 'fruits/Fresh-apple.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(7),
                'category_id' => 2,
                'name' => 'Fresh Apricot',
                'description' => 'Sweet and fresh apricots.',
                'price' => 400,
                'stock_quantity' => 30,
                'unit' => 'kg',
                'image' => 'fruits/Apricot.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(8),
                'category_id' => 2,
                'name' => 'Fresh Bananas',
                'description' => 'Fresh naturally sweet bananas.',
                'price' => 200,
                'stock_quantity' => 45,
                'unit' => 'kg',
                'image' => 'fruits/banana.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(9),
                'category_id' => 2,
                'name' => 'Fresh Mangoes',
                'description' => 'Juicy and naturally sweet mangoes.',
                'price' => 300,
                'stock_quantity' => 50,
                'unit' => 'kg',
                'image' => 'fruits/mango.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(10),
                'category_id' => 2,
                'name' => 'Fresh Oranges',
                'description' => 'Juicy fresh oranges.',
                'price' => 280,
                'stock_quantity' => 60,
                'unit' => 'kg',
                'image' => 'fruits/orange.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(11),
                'category_id' => 2,
                'name' => 'Fresh Strawberries',
                'description' => 'Fresh and naturally sweet strawberries.',
                'price' => 500,
                'stock_quantity' => 25,
                'unit' => 'kg',
                'image' => 'fruits/Strawberry.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(12),
                'category_id' => 3,
                'name' => 'Black Rice',
                'description' => 'Nutritious black rice from local farms.',
                'price' => 350,
                'stock_quantity' => 80,
                'unit' => 'kg',
                'image' => 'grains/black rice.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(13),
                'category_id' => 3,
                'name' => 'Buckwheat',
                'description' => 'Fresh quality buckwheat.',
                'price' => 320,
                'stock_quantity' => 70,
                'unit' => 'kg',
                'image' => 'grains/buckwheat.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(14),
                'category_id' => 3,
                'name' => 'Fresh Corn Grain',
                'description' => 'Fresh quality corn grain.',
                'price' => 240,
                'stock_quantity' => 90,
                'unit' => 'kg',
                'image' => 'grains/corngarin.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(15),
                'category_id' => 3,
                'name' => 'Organic Oats',
                'description' => 'Healthy and naturally grown oats.',
                'price' => 300,
                'stock_quantity' => 60,
                'unit' => 'kg',
                'image' => 'grains/oats.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(16),
                'category_id' => 3,
                'name' => 'Red Rice',
                'description' => 'Premium quality red rice.',
                'price' => 330,
                'stock_quantity' => 75,
                'unit' => 'kg',
                'image' => 'grains/red rice.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(17),
                'category_id' => 3,
                'name' => 'Premium Wheat',
                'description' => 'Premium quality wheat from local farms.',
                'price' => 250,
                'stock_quantity' => 100,
                'unit' => 'kg',
                'image' => 'grains/wheat.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(18),
                'category_id' => 4,
                'name' => 'Fresh Milk',
                'description' => 'Fresh and pure farm milk.',
                'price' => 220,
                'stock_quantity' => 25,
                'unit' => 'liter',
                'image' => 'dairy/milk.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(19),
                'category_id' => 4,
                'name' => 'Fresh Cheese',
                'description' => 'Fresh farm-made cheese.',
                'price' => 850,
                'stock_quantity' => 20,
                'unit' => 'kg',
                'image' => 'dairy/cheese.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(20),
                'category_id' => 5,
                'name' => 'Fresh Mixed Herbs',
                'description' => 'Fresh aromatic mixed herbs.',
                'price' => 180,
                'stock_quantity' => 30,
                'unit' => 'kg',
                'image' => 'herbs/herb1.png',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(21),
                'category_id' => 5,
                'name' => 'Fresh Garden Herbs',
                'description' => 'Fresh garden herbs.',
                'price' => 200,
                'stock_quantity' => 25,
                'unit' => 'kg',
                'image' => 'herbs/herb3.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(22),
                'category_id' => 5,
                'name' => 'Premium Fresh Herbs',
                'description' => 'Premium fresh herbs.',
                'price' => 220,
                'stock_quantity' => 20,
                'unit' => 'kg',
                'image' => 'herbs/herb4.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(23),
                'category_id' => 6,
                'name' => 'Red Chilli',
                'description' => 'Fresh quality red chilli.',
                'price' => 450,
                'stock_quantity' => 35,
                'unit' => 'kg',
                'image' => 'spices/red chilli.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(24),
                'category_id' => 6,
                'name' => 'Fresh Turmeric',
                'description' => 'Natural turmeric from local farms.',
                'price' => 500,
                'stock_quantity' => 30,
                'unit' => 'kg',
                'image' => 'spices/turmeric.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(25),
                'category_id' => 7,
                'name' => 'Organic Fresh Produce',
                'description' => 'Fresh organically grown produce.',
                'price' => 400,
                'stock_quantity' => 35,
                'unit' => 'kg',
                'image' => 'organic1.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(26),
                'category_id' => 7,
                'name' => 'Organic Farm Selection',
                'description' => 'Premium organic farm selection.',
                'price' => 450,
                'stock_quantity' => 30,
                'unit' => 'kg',
                'image' => 'organic2.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(27),
                'category_id' => 8,
                'name' => 'Fresh Pulses',
                'description' => 'Quality pulses from local farmers.',
                'price' => 320,
                'stock_quantity' => 70,
                'unit' => 'kg',
                'image' => 'pulses/pulses.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(28),
                'category_id' => 8,
                'name' => 'Premium Pulses Mix',
                'description' => 'Premium quality pulses mix.',
                'price' => 350,
                'stock_quantity' => 60,
                'unit' => 'kg',
                'image' => 'pulses/pulses1.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(29),
                'category_id' => 8,
                'name' => 'Farm Fresh Pulses',
                'description' => 'Fresh nutritious pulses.',
                'price' => 330,
                'stock_quantity' => 50,
                'unit' => 'kg',
                'image' => 'pulses/pulses2.jpg',
                'is_active' => true,
            ],

            [
                'farmer_id' => $pick(30),
                'category_id' => 8,
                'name' => 'Premium Farm Pulses',
                'description' => 'High quality farm pulses.',
                'price' => 360,
                'stock_quantity' => 45,
                'unit' => 'kg',
                'image' => 'pulses/pulses3.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            DB::table('products')->updateOrInsert(
                [
                    'category_id' => $product['category_id'],
                    'name'        => $product['name'],
                ],
                $product + [
                    'approval_status'  => 'approved',
                    'rejection_reason' => null,
                    'approved_by'      => $adminId,
                    'approved_at'      => now(),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]
            );
        }

        $this->command->info('Products seeded: ' . count($products));
    }
}
