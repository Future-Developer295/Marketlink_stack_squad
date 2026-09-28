<?php

use App\Models\Category;
use App\Models\FarmerProfile;
use App\Models\Market;
use App\Models\MarketFarmer;
use App\Models\PickupSlot;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\WeeklyStockTemplate;
use Illuminate\Support\Carbon;

function discoveryFixture(): array
{
    $user = User::factory()->create(['name' => 'Hina Grower', 'address' => 'Local Road', 'role' => 'farmer']);
    $farmer = FarmerProfile::create(['user_id' => $user->id, 'stall_name' => 'Hina Harvest', 'business_name' => 'Green Farm',
        'description' => 'Seasonal produce from our family farm.', 'address' => 'Farm Road', 'city' => 'Lahore',
        'state' => 'Punjab', 'country' => 'Pakistan', 'latitude' => 31.52, 'longitude' => 74.35,
        'operating_days' => 'Tuesday, Saturday', 'start_time' => '08:00', 'end_time' => '15:00', 'approval_status' => 'approved']);
    $market = Market::create(['name' => 'Riverside Market', 'address' => 'Canal Road', 'city' => 'Lahore',
        'state' => 'Punjab', 'country' => 'Pakistan', 'latitude' => 31.52, 'longitude' => 74.35,
        'operating_days' => 'Tuesday, Saturday', 'start_time' => '08:00', 'end_time' => '15:00']);
    $membership = MarketFarmer::create(['market_id' => $market->id, 'farmer_id' => $user->id, 'is_active' => true]);
    $category = Category::create(['name' => 'Vegetables', 'slug' => fake()->unique()->slug()]);
    $product = Product::create(['farmer_id' => $farmer->id, 'category_id' => $category->id, 'name' => 'Garden carrots',
        'description' => 'Fresh carrots', 'price' => 150, 'stock_quantity' => 12, 'unit' => 'kg', 'is_active' => true]);

    return compact('user', 'farmer', 'market', 'membership', 'category', 'product');
}

test('market discovery searches by area and filters days and city', function () {
    $data = discoveryFixture();
    $this->get('/markets?q=Canal&city=Lahore&day=Saturday')->assertOk()->assertViewHas('markets', fn ($markets) => $markets->total() === 1);
    $this->get('/markets?day=Monday')->assertOk()->assertSee('No markets found here yet.');
    $this->get('/markets?city=Karachi')->assertOk()->assertViewHas('markets', fn ($markets) => $markets->isEmpty());
});

test('market detail shows only approved active members and their harvest', function () {
    $first = discoveryFixture();
    $second = discoveryFixture();
    MarketFarmer::create(['market_id' => $first['market']->id, 'farmer_id' => $second['user']->id, 'is_active' => false]);
    $this->get('/markets/'.$first['market']->id)->assertOk()
        ->assertViewHas('farmerCount', 1)->assertViewHas('products', fn ($products) => $products->pluck('id')->all() === [$first['product']->id]);
    $first['farmer']->update(['approval_status' => 'pending']);
    $this->get('/markets/'.$first['market']->id)->assertOk()->assertViewHas('farmerCount', 0)->assertViewHas('products', fn ($products) => $products->isEmpty());
    $this->get('/markets')->assertOk()->assertViewHas('markets', fn ($markets) => $markets->firstWhere('id', $first['market']->id)->market_farmers_count === 0);
});

test('farmer discovery combines market category name day and stock filters', function () {
    $data = discoveryFixture();
    discoveryFixture();
    $url = '/farmers?'.http_build_query(['q' => 'Hina', 'city' => 'Lahore', 'market_id' => $data['market']->id, 'category_id' => $data['category']->id, 'day' => 'Tuesday', 'in_stock_only' => 1]);
    $this->get($url)->assertOk()->assertViewHas('farmers', fn ($farmers) => $farmers->total() === 1 && $farmers->first()->id === $data['farmer']->id);
    $data['product']->update(['stock_quantity' => 0]);
    $this->get($url)->assertOk()->assertViewHas('farmers', fn ($farmers) => $farmers->isEmpty());
});

test('rating filters exclude hidden reviews and do not invent ratings', function () {
    $data = discoveryFixture();
    Review::create(['user_id' => $data['user']->id, 'farmer_id' => $data['farmer']->id, 'product_id' => $data['product']->id, 'rating' => 5, 'comment' => 'Hidden', 'is_active' => false]);
    $this->get('/farmers?min_rating=4')->assertOk()->assertViewHas('farmers', fn ($farmers) => $farmers->isEmpty());
    Review::create(['user_id' => $data['user']->id, 'farmer_id' => $data['farmer']->id, 'product_id' => $data['product']->id, 'rating' => 4, 'comment' => 'Fresh', 'is_active' => true]);
    $this->get('/farmers?min_rating=4')->assertOk()->assertViewHas('farmers', fn ($farmers) => $farmers->first()->reviews_count === 1);
});

test('farmer profile scopes product filters and hides inactive and expired weekly stock', function () {
    $data = discoveryFixture();
    $other = discoveryFixture();
    WeeklyStockTemplate::create(['farmer_id' => $data['farmer']->id, 'product_id' => $data['product']->id, 'day_of_week' => 'Sat', 'quantity' => 20, 'unit' => 'kg', 'start_date' => today()->subDay(), 'end_date' => today()->addWeek(), 'is_active' => true]);
    WeeklyStockTemplate::create(['farmer_id' => $data['farmer']->id, 'product_id' => $data['product']->id, 'day_of_week' => 'Mon', 'quantity' => 30, 'unit' => 'kg', 'start_date' => today()->subWeek(), 'end_date' => today()->subDay(), 'is_active' => true]);
    $this->get('/farmers/'.$data['farmer']->id.'?q=carrots&max_price=200')->assertOk()
        ->assertViewHas('products', fn ($products) => $products->total() === 1 && $products->first()->id === $data['product']->id)
        ->assertViewHas('weeklyStock', fn ($stock) => $stock->count() === 1);
    $this->get('/farmers/'.$data['farmer']->id.'?max_price=100')->assertOk()->assertViewHas('products', fn ($products) => $products->isEmpty());
    $data['product']->update(['is_active' => false]);
    $this->get('/farmers/'.$data['farmer']->id)->assertOk()->assertViewHas('products', fn ($products) => $products->isEmpty())->assertViewHas('weeklyStock', fn ($stock) => $stock->isEmpty());
});

test('pickup cards omit elapsed unavailable and unrelated slots', function () {
    $this->travelTo(Carbon::parse('2026-10-02 10:00:00'));
    $data = discoveryFixture();
    foreach ([['09:00', true], ['11:00', true], ['13:00', false]] as [$start, $available]) {
        PickupSlot::create(['farmer_id' => $data['farmer']->id, 'market_id' => $data['market']->id, 'date' => today(), 'start_time' => $start, 'end_time' => '15:00', 'capacity' => 20, 'is_available' => $available]);
    }
    $this->get('/markets/'.$data['market']->id)->assertOk()->assertViewHas('pickupSlots', fn ($slots) => $slots->count() === 1);
    $this->get('/farmers/'.$data['farmer']->id)->assertOk()->assertViewHas('pickupSlots', fn ($slots) => $slots->count() === 1);
});

test('market discovery links retain market and day when shopping', function () {
    $data = discoveryFixture();
    discoveryFixture();
    $this->get('/products?market_id='.$data['market']->id.'&day=Saturday')->assertOk()
        ->assertViewHas('products', fn ($products) => $products->total() === 1 && $products->first()->id === $data['product']->id);
    $this->get('/products?market_id='.$data['market']->id.'&day=Monday')->assertOk()->assertViewHas('products', fn ($products) => $products->isEmpty());
});

test('unapproved farmer profiles and missing markets are unavailable', function () {
    $data = discoveryFixture();
    $data['farmer']->update(['approval_status' => 'pending']);
    $this->get('/farmers/'.$data['farmer']->id)->assertNotFound();
    $this->get('/markets/99999')->assertNotFound();
    $this->get('/farmers?min_rating=9')->assertSessionHasErrors('min_rating');
});

test('active grower badges follow the account status and abbreviated days can be searched', function () {
    $data = discoveryFixture();
    $data['user']->update(['is_active' => true]);
    $data['market']->update(['operating_days' => 'Mon, Tue, Sat']);
    $data['farmer']->update(['operating_days' => 'Mon, Tue, Sat']);
    $this->get('/farmers/'.$data['farmer']->id)->assertOk()->assertSee('Active grower');
    $this->get('/markets?day=Saturday')->assertOk()->assertViewHas('markets', fn ($markets) => $markets->total() === 1);
    $this->get('/farmers?day=Saturday')->assertOk()->assertViewHas('farmers', fn ($farmers) => $farmers->total() === 1);
    $this->get('/products?day=Saturday')->assertOk()->assertViewHas('products', fn ($products) => $products->total() === 1);
    $data['user']->update(['is_active' => false]);
    $this->get('/farmers/'.$data['farmer']->id)->assertOk()->assertSee('Currently inactive')->assertDontSee('Active grower');
});
