<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            FarmerProfileSeeder::class,
            RolesAndPermissionsSeeder::class,

            CategoriesTableSeeder::class,
            MarketsTableSeeder::class,
            MarketFarmersTableSeeder::class,
            ProductsTableSeeder::class,

            WeeklyStockTemplatesTableSeeder::class,
            PickupSlotsTableSeeder::class,

            OrdersTableSeeder::class,
            OrderItemsTableSeeder::class,
            CartItemsTableSeeder::class,

            FavoritesTableSeeder::class,
            ReviewsTableSeeder::class,
            ReviewRepliesTableSeeder::class,

            NotificationsTableSeeder::class,
            ReportsTableSeeder::class,
            AnnouncementsTableSeeder::class,
            AnnouncementReadsTableSeeder::class,
            ContactMessagesTableSeeder::class,
        ]);
    }
}
