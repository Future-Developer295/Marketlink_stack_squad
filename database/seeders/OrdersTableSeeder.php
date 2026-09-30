<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    public static function specs(): array
    {
        return [
            ['tag' => 'seed-order-1', 'customer' => 'customer@gmail.com',       'farmer' => 'farmer@gmail.com',       'items' => [[0, 3], [1, 2]], 'status' => 'picked_up', 'slot' => -3, 'note' => 'Please keep the products fresh.'],
            ['tag' => 'seed-order-2', 'customer' => 'customer@gmail.com',       'farmer' => 'alifarmer@gmail.com',    'items' => [[0, 2]],         'status' => 'confirmed', 'slot' => 2,  'note' => 'Will collect in the morning.'],
            ['tag' => 'seed-order-3', 'customer' => 'customer2@marketlink.com', 'farmer' => 'sanafarmer@gmail.com',   'items' => [[0, 5], [2, 1]], 'status' => 'ready',     'slot' => 1,  'note' => 'Customer will collect from the market.'],
            ['tag' => 'seed-order-4', 'customer' => 'customer2@marketlink.com', 'farmer' => 'farmer2@marketlink.com', 'items' => [[1, 4]],         'status' => 'picked_up', 'slot' => -1, 'note' => null],
            ['tag' => 'seed-order-5', 'customer' => 'customer3@marketlink.com', 'farmer' => 'farmer@gmail.com',       'items' => [[2, 2]],         'status' => 'pending',   'slot' => 3,  'note' => 'Order is waiting for confirmation.'],
            ['tag' => 'seed-order-6', 'customer' => 'customer3@marketlink.com', 'farmer' => 'sanafarmer@gmail.com',   'items' => [[1, 1]],         'status' => 'cancelled', 'slot' => 2,  'note' => 'Cancelled by customer.'],
            ['tag' => 'seed-order-7', 'customer' => 'customer4@marketlink.com', 'farmer' => 'farmer2@marketlink.com', 'items' => [[0, 2], [2, 2]], 'status' => 'pending',   'slot' => 5,  'note' => null],
        ];
    }

    public static function resolveItems(array $spec, int $farmerProfileId): array
    {
        $rows = [];
        foreach ($spec['items'] as [$index, $qty]) {
            $product = DB::table('products')
                ->where('farmer_id', $farmerProfileId)
                ->orderBy('id')
                ->skip($index)->first();

            if ($product) {
                $rows[] = [$product, $qty];
            }
        }
        return $rows;
    }

    public function run(): void
    {
        foreach (self::specs() as $spec) {
            $customerId = DB::table('users')->where('email', $spec['customer'])->value('id');
            $farmerUser = DB::table('users')->where('email', $spec['farmer'])->value('id');
            $farmerId   = DB::table('farmer_profile')->where('user_id', $farmerUser)->value('id');

            if (! $customerId || ! $farmerId) {
                continue;
            }

            $slotId = DB::table('pickup_slots')
                ->where('farmer_id', $farmerId)
                ->where('date', now()->addDays($spec['slot'])->toDateString())
                ->orderBy('start_time')
                ->value('id');

            if (! $slotId) {
                continue;
            }

            $total = 0;
            foreach (self::resolveItems($spec, $farmerId) as [$product, $qty]) {
                $total += $product->price * $qty;
            }

            $notes = '[' . $spec['tag'] . '] ' . ($spec['note'] ?? '');

            DB::table('orders')->updateOrInsert(
                ['user_id' => $customerId, 'farmer_id' => $farmerId, 'notes' => $notes],
                [
                    'pickup_slot_id' => $slotId,
                    'total_amount'   => $total,
                    'status'         => $spec['status'],
                    'order_date'     => now()->addDays($spec['slot'] - 1)->setTime(10, 30),
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]
            );
        }
    }
}
