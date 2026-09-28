<?php

use App\Models\Category;
use App\Models\FarmerProfile;
use App\Models\Market;
use App\Models\PickupSlot;
use App\Models\Product;
use App\Models\User;

function shoppingProduct(array $attributes = []): Product
{
    $farmer = FarmerProfile::create([
        'user_id' => User::factory()->create(['address' => 'Market Road', 'role' => 'customer'])->id,
        'stall_name' => 'Test Grower', 'business_name' => 'Test Farm',
        'description' => 'Local grower', 'address' => 'Market Road',
        'city' => 'Karachi', 'state' => 'Sindh', 'country' => 'Pakistan',
        'latitude' => 24.86, 'longitude' => 67.01, 'operating_days' => 'Saturday',
        'start_time' => '09:00', 'end_time' => '17:00', 'approval_status' => 'approved',
    ]);
    $category = Category::create(['name' => 'Vegetables', 'slug' => fake()->unique()->slug()]);

    return Product::create(array_merge([
        'farmer_id' => $farmer->id, 'category_id' => $category->id,
        'name' => 'Garden tomatoes', 'description' => 'Fresh tomatoes',
        'price' => 180, 'stock_quantity' => 5, 'unit' => 'kg', 'is_active' => true,
    ], $attributes));
}

test('quick add persists a guest basket and returns its server calculated total', function () {
    $product = shoppingProduct();

    $this->postJson('/cart/add/'.$product->id, ['quantity' => 2, 'price' => 1])
        ->assertOk()->assertJsonPath('count', 2)->assertJsonPath('total', 360)
        ->assertSessionHas('cart.'.$product->id, 2);
});

test('adding beyond available stock rejects the request without changing the basket', function () {
    $product = shoppingProduct();

    $this->withSession(['cart' => [$product->id => 4]])
        ->postJson('/cart/add/'.$product->id, ['quantity' => 2])
        ->assertUnprocessable()->assertJsonValidationErrors('quantity')
        ->assertJsonPath('errors.quantity.0', 'Only 5 units are available. Check your basket quantity.')
        ->assertSessionHas('cart.'.$product->id, 4);
});

test('unavailable products cannot be added', function (array $attributes) {
    $product = shoppingProduct($attributes);

    $this->postJson('/cart/add/'.$product->id, ['quantity' => 1])
        ->assertUnprocessable()->assertJsonPath('errors.quantity.0', 'This product is no longer available.')
        ->assertSessionMissing('cart.'.$product->id);
})->with(['sold out' => [['stock_quantity' => 0]], 'inactive' => [['is_active' => false]]]);

test('basket quantity updates recalculate totals', function () {
    $product = shoppingProduct();

    $this->withSession(['cart' => [$product->id => 1]])
        ->postJson('/cart/update/'.$product->id, ['quantity' => 3])
        ->assertOk()->assertJsonPath('total', 540)->assertSessionHas('cart.'.$product->id, 3);
});

test('removing a product empties the basket', function () {
    $product = shoppingProduct();

    $this->withSession(['cart' => [$product->id => 2]])
        ->postJson('/cart/remove/'.$product->id)
        ->assertOk()->assertJsonPath('count', 0)->assertSessionMissing('cart.'.$product->id);
});

test('basket rendering reconciles reduced stock', function () {
    $product = shoppingProduct(['stock_quantity' => 2]);

    $this->withSession(['cart' => [$product->id => 5]])->get('/cart')
        ->assertSee('Rs. 360')->assertSessionHas('cart.'.$product->id, 2);
});

test('catalog filters categories and omits inactive products', function () {
    $product = shoppingProduct();
    shoppingProduct(['name' => 'Hidden harvest', 'is_active' => false]);
    shoppingProduct(['name' => 'Other category']);

    $this->get('/products?category_id='.$product->category_id)
        ->assertSee('Garden tomatoes')->assertDontSee('Hidden harvest')->assertDontSee('Other category');
});

test('inactive product details return not found', function () {
    $product = shoppingProduct(['is_active' => false]);

    $this->get('/products/'.$product->id)->assertNotFound();
});

test('normal forms still redirect with successful feedback', function () {
    $product = shoppingProduct();

    $this->from('/products')->post('/cart/add/'.$product->id, ['quantity' => 1])
        ->assertRedirect('/products')->assertSessionHas('success')->assertSessionHas('cart.'.$product->id, 1);
});

test('product detail and checkout render populated shopping views', function () {
    $product = shoppingProduct();

    $this->get('/products/'.$product->id)->assertSee('Garden tomatoes')->assertSee('View basket');
    $this->withSession(['cart' => [$product->id => 2]])->get('/checkout')
        ->assertSee('market day.')->assertSee('Rs. 360');
});

test('guests cannot receive a false successful checkout', function () {
    $this->post('/checkout')->assertRedirect(route('login'));
    $this->assertDatabaseCount('orders', 0);
});

test('checkout requires a valid pickup slot for the basket grower', function () {
    $product = shoppingProduct();

    $this->actingAs(User::factory()->create(['address' => 'Market Road', 'role' => 'customer']))->withSession(['cart' => [$product->id => 1]])
        ->post('/checkout')->assertSessionHasErrors('pickup_slot.'.$product->farmer_id);
    $this->assertDatabaseCount('orders', 0);
});

test('missing local product photos have a usable fallback', function () {
    $product = shoppingProduct(['image' => 'missing.jpg']);

    $this->get('/products/'.$product->id)->assertSee('product-placeholder.svg');
});

test('quantity updates reject excess stock and preserve the previous quantity', function () {
    $product = shoppingProduct();

    $this->withSession(['cart' => [$product->id => 2]])->postJson('/cart/update/'.$product->id, ['quantity' => 6])
        ->assertUnprocessable()->assertJsonPath('errors.quantity.0', 'Only 5 units are available.')
        ->assertSessionHas('cart.'.$product->id, 2);
});

test('zero quantity removes the item', function () {
    $product = shoppingProduct();

    $this->withSession(['cart' => [$product->id => 2]])->postJson('/cart/update/'.$product->id, ['quantity' => 0])
        ->assertOk()->assertJsonPath('count', 0)->assertSessionMissing('cart.'.$product->id);
});

test('invalid add quantities return validation feedback', function (mixed $quantity) {
    $product = shoppingProduct();

    $this->postJson('/cart/add/'.$product->id, ['quantity' => $quantity])
        ->assertUnprocessable()->assertJsonValidationErrors('quantity')->assertSessionMissing('cart.'.$product->id);
})->with(['zero' => [0], 'negative' => [-1], 'fraction' => [1.5], 'text' => ['two']]);

test('inactive items are removed when the basket is revisited', function () {
    $product = shoppingProduct(['is_active' => false]);

    $this->withSession(['cart' => [$product->id => 2]])->get('/cart')
        ->assertSee('Your basket is empty.')->assertSessionMissing('cart.'.$product->id);
});

test('empty checkout does not create an order', function () {
    $this->actingAs(User::factory()->create(['address' => 'Market Road', 'role' => 'customer']))
        ->post('/checkout')->assertRedirect('/cart')->assertSessionHas('warning');
    $this->assertDatabaseCount('orders', 0);
});

test('valid pickup checkout creates the order and clears the basket', function () {
    $product = shoppingProduct();
    $customer = User::factory()->create(['address' => 'Market Road', 'role' => 'customer']);
    $market = Market::create([
        'name' => 'Local Market', 'address' => 'Market Road', 'city' => 'Karachi',
        'state' => 'Sindh', 'country' => 'Pakistan', 'latitude' => 24.86,
        'longitude' => 67.01, 'operating_days' => 'Saturday', 'start_time' => '09:00', 'end_time' => '17:00',
    ]);
    $slot = PickupSlot::create([
        'farmer_id' => $product->farmer_id, 'market_id' => $market->id,
        'date' => now()->addDay()->toDateString(), 'start_time' => '09:00', 'end_time' => '11:00',
        'capacity' => 10, 'is_available' => true,
    ]);

    $this->actingAs($customer)->withSession(['cart' => [$product->id => 2]])
        ->post('/checkout', ['pickup_slot' => [$product->farmer_id => $slot->id]])
        ->assertRedirect('/checkout')->assertSessionHas('success')->assertSessionMissing('cart');
    $this->assertDatabaseHas('orders', ['user_id' => $customer->id, 'pickup_slot_id' => $slot->id, 'total_amount' => 360]);
    $this->assertDatabaseHas('order_items', ['product_id' => $product->id, 'quantity' => 2, 'subtotal' => 360]);
});
