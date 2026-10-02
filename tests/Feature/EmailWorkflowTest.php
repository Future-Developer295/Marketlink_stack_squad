<?php

use App\Mail\FarmerApprovedMail;
use App\Mail\ProductApprovedMail;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\FarmerProfile;
use App\Models\Product;
use App\Models\User;
use App\Services\Cart;
use App\Services\EmailOtp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

function mailStaff(array $permissions): User
{
    foreach ($permissions as $name) {
        Permission::findOrCreate($name, 'web');
    }

    $user = User::factory()->create(['role' => 'admin']);
    $user->givePermissionTo($permissions);

    return $user;
}

function pendingProduct(): Product
{
    $farmerUser = User::factory()->create(['role' => 'farmer', 'is_active' => true]);
    $farmer = FarmerProfile::create([
        'user_id' => $farmerUser->id, 'stall_name' => 'Mail Grower', 'business_name' => 'Mail Farm',
        'description' => 'Local harvest', 'address' => 'Market Road', 'city' => 'Karachi', 'state' => 'Sindh',
        'country' => 'Pakistan', 'latitude' => 24.86, 'longitude' => 67.01, 'operating_days' => 'Friday',
        'start_time' => '09:00', 'end_time' => '17:00', 'approval_status' => 'approved',
    ]);
    $category = Category::create(['name' => 'Mail fruit', 'slug' => 'mail-fruit']);

    return Product::create([
        'farmer_id' => $farmer->id, 'category_id' => $category->id, 'name' => 'Mail apples',
        'description' => 'Fresh local apples', 'price' => 180, 'stock_quantity' => 10, 'unit' => 'kg',
        'is_active' => false, 'approval_status' => 'pending',
    ]);
}

function userPayload(User $user, array $overrides = []): array
{
    return array_merge([
        'name' => $user->name,
        'email' => $user->email,
        'phone' => '03001234567',
        'address' => 'Somewhere 12',
        'role' => $user->role,
    ], $overrides);
}

test('editing a pending farmer to active emails them once', function () {
    Mail::fake();
    $admin = mailStaff(['view users', 'edit users']);
    $farmer = User::factory()->create(['role' => 'farmer', 'is_active' => false]);

    $this->actingAs($admin)
        ->post(route('user_update', $farmer->id), userPayload($farmer, ['is_active' => '1']))
        ->assertRedirect(route('users'))
        ->assertSessionHas('success');

    expect($farmer->fresh()->is_active)->toBeTrue();
    Mail::assertSent(FarmerApprovedMail::class, fn ($mail) => $mail->hasTo($farmer->email));

    $this->actingAs($admin)->post(route('user_update', $farmer->id), userPayload($farmer, ['is_active' => '1']));
    Mail::assertSent(FarmerApprovedMail::class, 1);
});

test('activating a customer does not send the farmer email', function () {
    Mail::fake();
    $admin = mailStaff(['view users', 'edit users']);
    $customer = User::factory()->create(['role' => 'customer', 'is_active' => false]);

    $this->actingAs($admin)->post(route('user_update', $customer->id), userPayload($customer, ['is_active' => '1']));

    Mail::assertNothingSent();
});

test('one click farmer approval activates and emails, and cannot be repeated', function () {
    Mail::fake();
    $admin = mailStaff(['view users', 'edit users']);
    $farmer = User::factory()->create(['role' => 'farmer', 'is_active' => false]);

    $this->actingAs($admin)->post(route('user_approve', $farmer->id))->assertRedirect(route('users'))->assertSessionHas('success');
    expect($farmer->fresh()->is_active)->toBeTrue();
    Mail::assertSent(FarmerApprovedMail::class, 1);

    $this->actingAs($admin)->post(route('user_approve', $farmer->id))->assertSessionHas('warning');
    Mail::assertSent(FarmerApprovedMail::class, 1);
});

test('one click approval only works on farmers and needs the edit users permission', function () {
    Mail::fake();
    $customer = User::factory()->create(['role' => 'customer', 'is_active' => false]);
    $farmer = User::factory()->create(['role' => 'farmer', 'is_active' => false]);

    $this->actingAs(mailStaff(['view users', 'edit users']))->post(route('user_approve', $customer->id))->assertNotFound();
    $this->actingAs(mailStaff(['view users']))->post(route('user_approve', $farmer->id))->assertForbidden();
    expect($farmer->fresh()->is_active)->toBeFalse();
});

test('farmer approval still succeeds when the mail server is down', function () {
    Mail::shouldReceive('to')->andThrow(new RuntimeException('smtp down'));
    $admin = mailStaff(['view users', 'edit users']);
    $farmer = User::factory()->create(['role' => 'farmer', 'is_active' => false]);

    $this->actingAs($admin)->post(route('user_approve', $farmer->id))
        ->assertRedirect(route('users'))
        ->assertSessionHas('warning');

    expect($farmer->fresh()->is_active)->toBeTrue();
});

test('approving a product does not error when email fails', function () {
    Mail::shouldReceive('to')->andThrow(new RuntimeException('smtp down'));
    $admin = mailStaff(['view products', 'approve products']);
    $product = pendingProduct();

    $this->actingAs($admin)->post(route('product_approve', $product->id))
        ->assertRedirect(route('products'))
        ->assertSessionHas('warning');

    expect($product->fresh()->approval_status)->toBe('approved');
});

test('approving a product emails the farmer when mail works', function () {
    Mail::fake();
    $admin = mailStaff(['view products', 'approve products']);
    $product = pendingProduct();

    $this->actingAs($admin)->post(route('product_approve', $product->id))->assertSessionHas('success');

    Mail::assertSent(ProductApprovedMail::class, 1);
});

test('contact message is kept and success shown even if the mail server is down', function () {
    Mail::shouldReceive('to')->andThrow(new RuntimeException('smtp down'));

    $this->post('/contact', [
        'full_name' => 'Amina Khan',
        'email' => 'amina@example.com',
        'subject' => 'Other',
        'message' => 'Is the Saturday market open?',
    ])->assertRedirect('/contact')->assertSessionHas('success');

    $this->assertDatabaseHas('contact_messages', ['email' => 'amina@example.com']);
});

test('admin can read, open and delete contact messages', function () {
    $admin = mailStaff(['view contact messages', 'delete contact messages']);
    $message = ContactMessage::create([
        'full_name' => 'Amina Khan', 'email' => 'amina@example.com',
        'subject' => 'Other', 'message' => 'Is the Saturday market open?',
    ]);

    $this->actingAs($admin)->get(route('contact_messages'))
        ->assertOk()->assertSee('Amina Khan')->assertSee('Saturday market');

    expect($message->fresh()->read_at)->toBeNull();
    $this->actingAs($admin)->get(route('contact_message_show', $message->id))->assertOk()->assertSee('Reply by email');
    expect($message->fresh()->read_at)->not->toBeNull();

    $this->actingAs($admin)->post(route('contact_message_delete', $message->id))->assertRedirect(route('contact_messages'));
    $this->assertDatabaseMissing('contact_messages', ['id' => $message->id]);
});

test('contact messages are hidden from people without the permission', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $noPermission = mailStaff(['view users']);

    $this->actingAs($noPermission)->get(route('contact_messages'))->assertForbidden();
    $this->actingAs($customer)->get(route('contact_messages'))->assertForbidden();
});

test('contact messages list is paginated', function () {
    $admin = mailStaff(['view contact messages']);

    foreach (range(1, 20) as $n) {
        ContactMessage::create([
            'full_name' => 'Person '.$n, 'email' => "p{$n}@example.com",
            'subject' => 'Other', 'message' => 'A long enough message '.$n,
        ]);
    }

    $this->actingAs($admin)->get(route('contact_messages'))->assertOk()->assertSee('Showing 1–15 of 20');
    $this->actingAs($admin)->get(route('contact_messages', ['page' => 2]))->assertOk()->assertSee('Showing 16–20 of 20');
});

test('email failure details are only shown in debug mode', function () {
    $error = new RuntimeException('535 Username and Password not accepted');

    config(['app.debug' => false]);
    expect(EmailOtp::failureMessage($error))->not->toContain('535');

    config(['app.debug' => true]);
    expect(EmailOtp::failureMessage($error))->toContain('535');
});

test('cart keeps working when the cart_items table has not been migrated', function () {
    $user = User::factory()->create(['role' => 'customer']);
    $product = pendingProduct();
    $product->update(['is_active' => true, 'approval_status' => 'approved']);

    $reset = function () {
        (new ReflectionProperty(Cart::class, 'tableReady'))->setValue(null, null);
    };

    Schema::drop('cart_items');
    $reset();

    try {
        $this->actingAs($user);
        $cart = new Cart;
        $cart->add($product->id, 2);

        expect($cart->count())->toBe(2);
    } finally {
        $reset();
    }
});
