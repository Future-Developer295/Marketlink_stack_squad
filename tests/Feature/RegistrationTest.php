<?php

use App\Mail\EmailVerificationCodeMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Laravel\Fortify\Features;
use Spatie\Permission\Models\Role;

test('registration screen can be rendered', function () {
    $this->get('/register')->assertStatus(200);
})->skip(function () {
    return ! Features::enabled(Features::registration());
}, 'Registration support is not enabled.');

test('new users register, get an emailed code and are NOT logged in', function () {
    Mail::fake();
    Role::findOrCreate('customer', 'web');

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '03001234567',
        'address' => 'Karachi, Pakistan',
        'role' => 'customer',
        'password' => 'password-1234',
        'password_confirmation' => 'password-1234',
        'terms' => true,
    ]);

    $this->assertGuest();
    $response->assertRedirect(route('verification.notice'));

    expect(User::where('email', 'test@example.com')->first()->hasVerifiedEmail())->toBeFalse();
    Mail::assertSent(EmailVerificationCodeMail::class, 1);
})->skip(function () {
    return ! Features::enabled(Features::registration());
}, 'Registration support is not enabled.');
