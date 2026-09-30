<?php

use App\Mail\EmailVerificationCodeMail;
use App\Models\User;
use App\Services\EmailOtp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    foreach (['admin', 'farmer', 'customer'] as $role) {
        Role::findOrCreate($role, 'web');
    }
});

function otpUser(array $attributes = []): User
{
    static $counter = 0;
    $counter++;

    $user = new User();
    $user->forceFill(array_merge([
        'name' => 'Otp Tester',
        'email' => "otp{$counter}@example.com",
        'password' => 'password',
        'phone' => '03001234567',
        'address' => 'Karachi, Pakistan',
        'role' => 'customer',
        'is_active' => true,
        'email_verified_at' => null,
    ], $attributes))->save();

    return $user;
}

function otpIssueCode(User $user): string
{
    Mail::fake();

    app(EmailOtp::class)->send($user);

    $code = null;

    Mail::assertSent(EmailVerificationCodeMail::class, function (EmailVerificationCodeMail $mail) use (&$code) {
        $code = $mail->code;

        return true;
    });

    return $code;
}

function otpWrongCode(string $code): string
{
    return $code === '000000' ? '111111' : '000000';
}

function otpRegistrationPayload(string $role): array
{
    return [
        'name' => 'New '.ucfirst($role),
        'email' => "new-{$role}@example.com",
        'password' => 'password-1234',
        'password_confirmation' => 'password-1234',
        'phone' => '03001234567',
        'address' => 'Karachi, Pakistan',
        'role' => $role,
        'terms' => true,
    ];
}

test('the correct code verifies the user', function () {
    $user = otpUser();
    $code = otpIssueCode($user);

    $this->withSession(['otp_email' => $user->email])
        ->post(route('verification.otp.verify'), ['code' => $code])
        ->assertRedirect(route('login'))
        ->assertSessionHasNoErrors();

    $user->refresh();

    expect($user->hasVerifiedEmail())->toBeTrue()
        ->and($user->email_otp_hash)->toBeNull()
        ->and($user->email_otp_expires_at)->toBeNull();

    $this->assertGuest();
});

test('a code is still accepted just before it expires', function () {
    $user = otpUser();
    $code = otpIssueCode($user);

    $this->travel(9)->minutes();

    $this->withSession(['otp_email' => $user->email])
        ->post(route('verification.otp.verify'), ['code' => $code])
        ->assertSessionHasNoErrors();

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
});

test('a wrong code is rejected', function () {
    $user = otpUser();
    $code = otpIssueCode($user);

    $this->withSession(['otp_email' => $user->email])
        ->post(route('verification.otp.verify'), ['code' => otpWrongCode($code)])
        ->assertSessionHasErrors(['code' => 'That code is not correct. Please try again.']);

    $user->refresh();

    expect($user->hasVerifiedEmail())->toBeFalse()
        ->and($user->email_otp_attempts)->toBe(1)
        ->and($user->email_otp_hash)->not->toBeNull();
});

test('a code that is not exactly 6 digits fails validation', function () {
    $user = otpUser();
    otpIssueCode($user);

    $this->withSession(['otp_email' => $user->email])
        ->post(route('verification.otp.verify'), ['code' => '12345'])
        ->assertSessionHasErrors('code');

    expect($user->fresh()->email_otp_attempts)->toBe(0);
});

test('an expired code is rejected even when it is correct', function () {
    $user = otpUser();
    $code = otpIssueCode($user);

    $this->travel(11)->minutes();

    $this->withSession(['otp_email' => $user->email])
        ->post(route('verification.otp.verify'), ['code' => $code])
        ->assertSessionHasErrors(['code' => 'This code has expired. Please request a new one.']);

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

test('five wrong attempts lock the code, so even the right code no longer works', function () {
    $user = otpUser();
    $code = otpIssueCode($user);
    $wrong = otpWrongCode($code);

    foreach (range(1, 4) as $attempt) {
        $this->withSession(['otp_email' => $user->email])
            ->post(route('verification.otp.verify'), ['code' => $wrong])
            ->assertSessionHasErrors(['code' => 'That code is not correct. Please try again.']);
    }

    $this->withSession(['otp_email' => $user->email])
        ->post(route('verification.otp.verify'), ['code' => $wrong])
        ->assertSessionHasErrors(['code' => 'Too many wrong attempts. Please request a new code.']);

    $this->withSession(['otp_email' => $user->email])
        ->post(route('verification.otp.verify'), ['code' => $code])
        ->assertSessionHasErrors('code');

    $user->refresh();

    expect($user->hasVerifiedEmail())->toBeFalse()
        ->and($user->email_otp_hash)->toBeNull();

    $this->travel(61)->seconds();

    $this->withSession(['otp_email' => $user->email])
        ->post(route('verification.otp.resend'))
        ->assertSessionHasNoErrors();

    Mail::assertSent(EmailVerificationCodeMail::class, 2);
});

test('visiting the verify page without a pending user sends you to login', function () {
    $this->get(route('verification.notice'))->assertRedirect(route('login'));
});

test('the verify page renders for a pending user and masks the email', function () {
    $user = otpUser(['email' => 'johndoe@example.com']);
    otpIssueCode($user);

    $this->withSession(['otp_email' => $user->email])
        ->get(route('verification.notice'))
        ->assertOk()
        ->assertSee('jo*****@example.com')
        ->assertDontSee('johndoe@example.com');
});

test('resend is blocked within 60 seconds and allowed afterwards', function () {
    $user = otpUser();
    otpIssueCode($user);

    $this->withSession(['otp_email' => $user->email])
        ->post(route('verification.otp.resend'))
        ->assertSessionHasErrors('code');

    Mail::assertSent(EmailVerificationCodeMail::class, 1);

    $this->travel(61)->seconds();

    $this->withSession(['otp_email' => $user->email])
        ->post(route('verification.otp.resend'))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');

    Mail::assertSent(EmailVerificationCodeMail::class, 2);
});

test('an unverified user cannot log in even with the correct password', function () {
    Mail::fake();

    $user = otpUser();

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect(route('verification.notice'));

    $this->assertGuest();

    Mail::assertSent(EmailVerificationCodeMail::class, fn ($mail) => $mail->hasTo($user->email));
});

test('an unverified user with the wrong password is just a normal failed login', function () {
    Mail::fake();

    $user = otpUser();

    $this->post('/login', ['email' => $user->email, 'password' => 'not-the-password'])
        ->assertSessionHasErrors();

    $this->assertGuest();
    Mail::assertNothingSent();
});

test('a verified user can log in', function () {
    $user = otpUser(['email_verified_at' => now()]);

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertSessionHasNoErrors();

    $this->assertAuthenticatedAs($user);
});

test('registration emails a code, does not log the user in, and redirects to the code page', function (string $role) {
    Mail::fake();

    $payload = otpRegistrationPayload($role);

    $this->post('/register', $payload)
        ->assertRedirect(route('verification.notice'));

    $this->assertGuest();

    $user = User::where('email', $payload['email'])->firstOrFail();

    expect($user->hasVerifiedEmail())->toBeFalse()
        ->and($user->email_otp_hash)->not->toBeNull();

    Mail::assertSent(EmailVerificationCodeMail::class, 1);
    Mail::assertSent(EmailVerificationCodeMail::class, fn ($mail) => $mail->hasTo($payload['email']));
})->with(['farmer', 'customer']);
