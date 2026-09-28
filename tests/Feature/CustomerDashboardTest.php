<?php

use App\Models\Category;
use App\Models\FarmerProfile;
use App\Models\Favorite;
use App\Models\Market;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PickupSlot;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Carbon;

function dashboardCustomer(): User
{
    return User::factory()->create(['address' => 'Market Road', 'role' => 'customer']);
}

function dashboardOrder(User $user, string $status = 'pending'): Order
{
    $farmer = FarmerProfile::create([
        'user_id' => dashboardCustomer()->id, 'stall_name' => 'Garden Grower', 'business_name' => 'Garden Farm',
        'description' => 'Local harvest', 'address' => 'Market Road', 'city' => 'Karachi', 'state' => 'Sindh',
        'country' => 'Pakistan', 'latitude' => 24.86, 'longitude' => 67.01, 'operating_days' => 'Friday',
        'start_time' => '09:00', 'end_time' => '17:00', 'approval_status' => 'approved',
    ]);
    $market = Market::create(['name' => 'Garden Market', 'address' => 'Market Road', 'city' => 'Karachi',
        'state' => 'Sindh', 'country' => 'Pakistan', 'latitude' => 24.86, 'longitude' => 67.01,
        'operating_days' => 'Friday', 'start_time' => '09:00', 'end_time' => '17:00']);
    $slot = PickupSlot::create(['farmer_id' => $farmer->id, 'market_id' => $market->id,
        'date' => '2026-10-02', 'start_time' => '10:00', 'end_time' => '12:00', 'capacity' => 20, 'is_available' => true]);
    $category = Category::create(['name' => 'Fruit', 'slug' => fake()->unique()->slug()]);
    $product = Product::create(['farmer_id' => $farmer->id, 'category_id' => $category->id,
        'name' => 'Garden apples', 'description' => 'Fresh local apples', 'price' => 180, 'stock_quantity' => 10, 'unit' => 'kg', 'is_active' => true]);
    $order = Order::create(['user_id' => $user->id, 'farmer_id' => $farmer->id,
        'pickup_slot_id' => $slot->id, 'total_amount' => 200, 'status' => $status, 'order_date' => now()]);
    OrderItem::create(['order_id' => $order->id, 'product_id' => $product->id, 'quantity' => 2, 'price' => 100, 'subtotal' => 200]);

    return $order;
}

test('customer pages require authentication', function () {
    $this->get(route('customer_dashboard'))->assertRedirect(route('login'));
});

test('all customer pages render with scoped account data', function () {
    $user = dashboardCustomer();
    $order = dashboardOrder($user);
    dashboardOrder($user, 'picked_up');
    dashboardOrder(dashboardCustomer(), 'ready');
    $favorite = Favorite::create(['user_id' => $user->id, 'product_id' => $order->items->first()->product_id]);
    $this->actingAs($user)->get(route('customer_dashboard'))->assertOk()
        ->assertViewHas('stats', fn ($stats) => $stats === ['total_orders' => 2, 'active_orders' => 1, 'completed_orders' => 1, 'favorites' => 1]);
    foreach (['customer_orders', 'customer_favorites', 'customer_reviews', 'customer_notifications', 'customer_profile'] as $route) {
        $this->get(route($route))->assertOk();
    }
    $this->get(route('customer_order_detail', $order))->assertOk()->assertSee('Pickup directions');
});

test('cancellation follows the approved pickup cutoff', function (string $status, string $time, bool $allowed) {
    $this->travelTo(Carbon::parse('2026-10-02 '.$time));
    $user = dashboardCustomer();
    $order = dashboardOrder($user, $status);
    $response = $this->actingAs($user)->post(route('customer_order_cancel', $order));
    if ($allowed) {
        $response->assertSessionHasNoErrors();
        expect($order->fresh()->status)->toBe('cancelled');
    } else {
        $response->assertSessionHasErrors('order');
        expect($order->fresh()->status)->toBe($status);
    }
})->with([
    ['pending', '09:59:59', true], ['confirmed', '09:00:00', true],
    ['ready', '09:00:00', false], ['pending', '10:00:00', false],
    ['confirmed', '12:00:00', false], ['picked_up', '09:00:00', false], ['cancelled', '09:00:00', false],
]);

test('customers cannot view cancel reorder review or remove another customers records', function () {
    $order = dashboardOrder(dashboardCustomer(), 'picked_up');
    $user = dashboardCustomer();
    $favorite = Favorite::create(['user_id' => $order->user_id, 'product_id' => $order->items->first()->product_id]);
    $notification = Notification::create(['user_id' => $order->user_id, 'title' => 'Private', 'message' => 'Private update', 'type' => 'restock', 'is_read' => false]);
    $this->actingAs($user)->get(route('customer_order_detail', $order))->assertNotFound();
    $this->post(route('customer_order_cancel', $order))->assertNotFound();
    $this->post(route('customer_reorder', $order->items->first()))->assertNotFound();
    $this->post(route('customer_review_store'), ['item_id' => $order->items->first()->id, 'rating' => 5, 'comment' => 'Nice'])->assertNotFound();
    $this->delete(route('customer_favorite_remove', $favorite))->assertNotFound();
    $this->post(route('customer_notification_read', $notification))->assertNotFound();
});

test('reordering uses current prices and checks available quantity', function () {
    $user = dashboardCustomer();
    $order = dashboardOrder($user, 'picked_up');
    $item = $order->items->first();
    $this->actingAs($user)->post(route('customer_reorder', $item))->assertSessionHasNoErrors()->assertSessionHas('cart.'.$item->product_id, 2);
    $this->get('/cart')->assertSee('Rs. 360');
    $item->product->update(['stock_quantity' => 2]);
    $this->post(route('customer_reorder', $item))->assertSessionHasErrors('quantity')->assertSessionHas('cart.'.$item->product_id, 2);
});

test('reviews require completed purchases and update the existing review', function () {
    $user = dashboardCustomer();
    $order = dashboardOrder($user);
    $data = ['item_id' => $order->items->first()->id, 'rating' => 5, 'comment' => 'Fresh and delicious.'];
    $this->actingAs($user)->post(route('customer_review_store'), $data)->assertNotFound();
    $order->update(['status' => 'picked_up']);
    $this->post(route('customer_review_store'), $data)->assertSessionHasNoErrors();
    $this->post(route('customer_review_store'), array_replace($data, ['rating' => 4]))->assertSessionHasNoErrors();
    expect(Review::count())->toBe(1)->and(Review::first()->rating)->toBe(4);
    $this->post(route('customer_review_store'), array_replace($data, ['rating' => 6]))->assertSessionHasErrors('rating');
});

test('favorites save once and restock produces one update per customer', function () {
    $user = dashboardCustomer();
    $order = dashboardOrder($user);
    $product = $order->items->first()->product;
    $this->actingAs($user)->post(route('customer_favorite_save'), ['kind' => 'product', 'id' => $product->id])->assertSessionHasNoErrors();
    $this->post(route('customer_favorite_save'), ['kind' => 'product', 'id' => $product->id]);
    $this->post(route('customer_favorite_save'), ['kind' => 'farmer', 'id' => $product->farmer_id]);
    expect($user->favorites()->count())->toBe(2);
    $product->update(['stock_quantity' => 0]);
    $product->update(['stock_quantity' => 8]);
    $product->update(['stock_quantity' => 7]);
    expect(Notification::where('user_id', $user->id)->count())->toBe(1);
    $notification = Notification::first();
    $this->post(route('customer_notification_read', $notification))->assertRedirect();
    expect($notification->fresh()->is_read)->toBeTrue();
    $this->delete(route('customer_favorite_remove', $user->favorites()->first()))->assertRedirect();
    expect($user->favorites()->count())->toBe(1);
});

test('active and completed filters return only matching customer orders', function () {
    $user = dashboardCustomer();
    dashboardOrder($user, 'pending');
    dashboardOrder($user, 'ready');
    dashboardOrder($user, 'picked_up');
    $this->actingAs($user)->get(route('customer_orders', ['status' => 'active']))->assertOk()
        ->assertViewHas('orders', fn ($orders) => $orders->total() === 2);
    $this->get(route('customer_orders', ['status' => 'picked_up']))->assertOk()
        ->assertViewHas('orders', fn ($orders) => $orders->total() === 1);
});

test('account details save without clearing an omitted address', function () {
    $user = dashboardCustomer();
    $this->actingAs($user)->put(route('customer_profile_update'), [
        'name' => 'Updated Customer', 'email' => $user->email, 'phone' => '03001234567',
    ])->assertSessionHasNoErrors();
    expect($user->fresh()->name)->toBe('Updated Customer')->and($user->fresh()->address)->toBe('Market Road');
});

test('pickup guide and product save actions are available', function () {
    $order = dashboardOrder(dashboardCustomer());
    $this->get('/pickup-guidelines')->assertOk()->assertSee('Your market-day guide.');
    $this->get('/products/'.$order->items->first()->product_id)->assertOk()->assertSee('Save this fresh pick');
    $this->get('/farmers/'.$order->farmer_id)->assertOk()->assertSee('Save grower');
});
