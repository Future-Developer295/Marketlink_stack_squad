<?php

use App\Models\Category;
use App\Models\FarmerProfile;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;

test('homepage shows available products from approved growers with matching category counts', function () {
    $user = User::factory()->create(['role' => 'farmer', 'address' => 'Farm Road']);
    $farmer = FarmerProfile::create(['user_id' => $user->id, 'stall_name' => 'Local Harvest', 'business_name' => 'Local Farm',
        'description' => 'Fresh harvest.', 'address' => 'Farm Road', 'city' => 'Lahore', 'state' => 'Punjab', 'country' => 'Pakistan',
        'latitude' => 31.52, 'longitude' => 74.35, 'operating_days' => 'Saturday', 'start_time' => '08:00', 'end_time' => '15:00', 'approval_status' => 'approved']);
    $category = Category::create(['name' => 'Vegetables', 'slug' => 'vegetables']);
    $available = Product::create(['farmer_id' => $farmer->id, 'category_id' => $category->id, 'name' => 'Fresh carrots', 'description' => 'Carrots', 'price' => 100, 'stock_quantity' => 10, 'unit' => 'kg', 'is_active' => true]);
    Product::create(['farmer_id' => $farmer->id, 'category_id' => $category->id, 'name' => 'Sold out beans', 'description' => 'Beans', 'price' => 100, 'stock_quantity' => 0, 'unit' => 'kg', 'is_active' => true]);
    Product::create(['farmer_id' => $farmer->id, 'category_id' => $category->id, 'name' => 'Hidden produce', 'description' => 'Hidden', 'price' => 100, 'stock_quantity' => 10, 'unit' => 'kg', 'is_active' => false]);
    $published = Review::create(['user_id' => $user->id, 'farmer_id' => $farmer->id, 'rating' => 4, 'comment' => '<b>Fresh harvest</b>']);
    Review::create(['user_id' => $user->id, 'farmer_id' => $farmer->id, 'rating' => 5, 'comment' => 'Flagged feedback', 'is_flagged' => true]);
    Review::create(['user_id' => $user->id, 'farmer_id' => $farmer->id, 'rating' => 5, 'comment' => 'Hidden feedback', 'is_active' => false]);

    $this->get('/')->assertViewHas('products', fn ($products) => $products->pluck('id')->all() === [$available->id])
        ->assertViewHas('categories', fn ($categories) => $categories->first()->products_count === 1)
        ->assertViewHas('reviews', fn ($reviews) => $reviews->pluck('id')->all() === [$published->id])
        ->assertSee('<b>Fresh harvest</b>')->assertDontSee('<b>Fresh harvest</b>', false)
        ->assertDontSee('Sold out beans')->assertDontSee('Hidden produce');

    $farmer->update(['approval_status' => 'pending']);

    $this->get('/')->assertViewHas('products', fn ($products) => $products->isEmpty())
        ->assertViewHas('reviews', fn ($reviews) => $reviews->isEmpty())
        ->assertViewHas('categories', fn ($categories) => $categories->first()->products_count === 0);
});

test('homepage has useful empty states before catalogue and reviews are published', function () {
    $this->get('/')->assertSee('A new season is coming.')
        ->assertSee('The growers are preparing their next harvest.')
        ->assertSee('New market locations will appear here')
        ->assertSee('Every market visit has a story.');
});
