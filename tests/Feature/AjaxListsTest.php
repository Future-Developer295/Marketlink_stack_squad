<?php

use App\Models\Category;
use App\Models\FarmerProfile;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;

function ajaxStaff(array $permissions, string $role = 'admin'): User
{
    foreach ($permissions as $name) {
        Permission::findOrCreate($name, 'web');
    }

    $user = User::factory()->create(['role' => $role]);
    $user->givePermissionTo($permissions);

    return $user;
}

function ajaxCategories(int $count): void
{
    foreach (range(1, $count) as $n) {
        Category::create(['name' => sprintf('Category %02d', $n), 'slug' => 'category-'.$n]);
    }
}

function ajaxProduct(): Product
{
    $farmer = FarmerProfile::create([
        'user_id' => User::factory()->create(['role' => 'farmer'])->id, 'stall_name' => 'Ajax Grower', 'business_name' => 'Ajax Farm',
        'description' => 'Local harvest', 'address' => 'Market Road', 'city' => 'Karachi', 'state' => 'Sindh',
        'country' => 'Pakistan', 'latitude' => 24.86, 'longitude' => 67.01, 'operating_days' => 'Friday',
        'start_time' => '09:00', 'end_time' => '17:00', 'approval_status' => 'approved',
    ]);
    $category = Category::create(['name' => 'Ajax fruit', 'slug' => 'ajax-fruit']);

    return Product::create([
        'farmer_id' => $farmer->id, 'category_id' => $category->id, 'name' => 'Ajax apples',
        'description' => 'Fresh local apples', 'price' => 180, 'stock_quantity' => 10, 'unit' => 'kg', 'is_active' => true,
    ]);
}

test('a redirect style action answers JSON when the request asks for JSON', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $product = ajaxProduct();

    $this->actingAs($customer)
        ->postJson(route('customer_favorite_save'), ['kind' => 'product', 'id' => $product->id])
        ->assertOk()
        ->assertJsonStructure(['success', 'type', 'message', 'redirect', 'cart_count'])
        ->assertJson(['success' => true, 'type' => 'success']);

    expect($customer->favorites()->count())->toBe(1);
});

test('session validation errors become a 422 with message and errors', function () {
    $customer = User::factory()->create(['role' => 'customer', 'phone' => '0300123456']);

    $this->actingAs($customer)
        ->putJson(route('customer_profile_update'), [
            'name' => 'Sara', 'email' => $customer->email, 'phone' => '0300123456',
            'current_password' => 'not-the-password', 'password' => 'brand-new-pass1', 'password_confirmation' => 'brand-new-pass1',
        ])
        ->assertStatus(422)
        ->assertJson(['success' => false])
        ->assertJsonStructure(['message', 'errors' => ['current_password']]);

    $this->postJson('/contact', [])->assertStatus(422)->assertJsonStructure(['message', 'errors']);
});

test('customer favorites are paginated', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    Favorite::create(['user_id' => $customer->id, 'product_id' => ajaxProduct()->id]);

    $this->actingAs($customer)->get(route('customer_favorites'))
        ->assertOk()
        ->assertSee('id="favorites-list"', false)
        ->assertViewHas('favorites', fn ($favorites) => $favorites instanceof LengthAwarePaginator && $favorites->total() === 1);
});

test('dashboard lists paginate, search and keep the query string on page 2', function () {
    ajaxCategories(16);
    $admin = ajaxStaff(['view categories']);

    $this->actingAs($admin)->get(route('categories'))
        ->assertOk()
        ->assertSee('id="categories-list"', false)
        ->assertViewHas('categories', fn ($categories) => $categories->count() === 15 && $categories->total() === 16);

    $this->get(route('categories', ['page' => 2]))
        ->assertOk()
        ->assertViewHas('categories', fn ($categories) => $categories->currentPage() === 2 && $categories->count() === 1);

    $this->get(route('categories', ['q' => 'Category 07']))
        ->assertOk()
        ->assertViewHas('categories', fn ($categories) => $categories->total() === 1 && $categories->first()->name === 'Category 07');
});

test('deleting the only row on the last page leaves a valid previous page', function () {
    ajaxCategories(16);
    $admin = ajaxStaff(['view categories', 'delete categories']);
    $last = Category::orderBy('id')->get()->last();

    $this->actingAs($admin)
        ->postJson(route('category_delete', $last->id))
        ->assertOk()
        ->assertJson(['success' => true, 'message' => 'Category deleted successfully.']);

    $this->get(route('categories', ['page' => 2]))
        ->assertOk()
        ->assertViewHas('categories', fn ($categories) => $categories->isEmpty() && $categories->lastPage() === 1);

    $this->get(route('categories', ['page' => 1]))
        ->assertOk()
        ->assertViewHas('categories', fn ($categories) => $categories->count() === 15 && $categories->total() === 15);
});

test('an admin cannot delete their own account from the users list', function () {
    $admin = ajaxStaff(['view users', 'delete users']);

    $this->actingAs($admin)
        ->postJson(route('user_delete', $admin->id))
        ->assertStatus(422)
        ->assertJson(['success' => false, 'type' => 'error']);

    expect(User::find($admin->id))->not->toBeNull();
});

test('a farmer without a profile sees an empty paginated orders list', function () {
    $farmer = ajaxStaff(['view orders'], 'farmer');

    $this->actingAs($farmer)->get(route('orders'))
        ->assertOk()
        ->assertViewHas('orders', fn ($orders) => $orders instanceof LengthAwarePaginator && $orders->total() === 0);
});
