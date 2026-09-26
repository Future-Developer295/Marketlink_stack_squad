<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
        UsersTableSeeder::class,
        RolesAndPermissionsSeeder::class,

        FarmerProfileSeeder::class,
        CategoriesTableSeeder::class,
        MarketsTableSeeder::class,
        ProductsTableSeeder::class,
        MarketFarmersTableSeeder::class,
        PickupSlotsTableSeeder::class,
        WeeklyStockTemplatesTableSeeder::class,
        OrdersTableSeeder::class,
        OrderItemsTableSeeder::class,
        FavoritesTableSeeder::class,
        ReviewsTableSeeder::class,
        ReviewRepliesTableSeeder::class,
        ReportsTableSeeder::class,
        NotificationsTableSeeder::class,
        AnnouncementsTableSeeder::class,
        AnnouncementReadsTableSeeder::class,
    ]);
    }
}