<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            RolesAndPermissionsSeeder::class,

            FarmerProfileSeeder::class,

            CategoriesTableSeeder::class,
            MarketsTableSeeder::class,
            MarketFarmersTableSeeder::class,

            ProductsTableSeeder::class,
            WeeklyStockTemplatesTableSeeder::class,
            PickupSlotsTableSeeder::class,

            OrdersTableSeeder::class,
            OrderItemsTableSeeder::class,

            FavoritesTableSeeder::class,
            ReviewsTableSeeder::class,
            ReviewRepliesTableSeeder::class,

            NotificationsTableSeeder::class,
            ReportsTableSeeder::class,

            AnnouncementsTableSeeder::class,
            AnnouncementReadsTableSeeder::class,
        ]);
    }
}