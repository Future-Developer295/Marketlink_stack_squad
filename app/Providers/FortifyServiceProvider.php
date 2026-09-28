<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Responses\RegisterResponse;
use App\Models\User;
use App\Services\EmailOtp;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Laravel\Fortify\Fortify;
use Throwable;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Without this binding Fortify uses its own RegisterResponse, which logs
        // the new user in and sends them straight to /dashboard (no OTP step).
        $this->app->singleton(RegisterResponseContract::class, RegisterResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Render this app's own Blade views instead of Jetstream's default
        // Livewire login/register pages, so Fortify's routes (login/register)
        // show the MarketLink-branded forms in resources/views/Website/Auth/.
        Fortify::loginView(fn () => view('Website.Auth.login'));
        Fortify::registerView(fn () => view('Website.Auth.register'));
        Fortify::requestPasswordResetLinkView(fn () => view('Website.Auth.forgot-password'));
        Fortify::resetPasswordView(fn (Request $request) => view('Website.Auth.reset-password', ['request' => $request]));

        // Correct email + password but email not verified yet: do NOT log in.
        // Send a code (if there is no live one) and show the OTP page instead.
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where(Fortify::username(), $request->input(Fortify::username()))->first();

            if (! $user || ! Hash::check((string) $request->input('password'), $user->password)) {
                return null; // normal failed login
            }

            if (! $user->hasVerifiedEmail()) {
                $otp = app(EmailOtp::class);

                if (! $otp->hasActiveCode($user)) {
                    try {
                        $otp->send($user);
                    } catch (Throwable $e) {
                        report($e);
                    }
                }

                $request->session()->put('otp_email', $user->email);

                throw new HttpResponseException(redirect()->route('verification.notice'));
            }

            return $user;
        });

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('passkeys', function (Request $request) {
            $credentialId = $request->input('credential.id');

            return Limit::perMinute(10)->by(
                ($credentialId ?: $request->session()->getId()).'|'.$request->ip()
            );
        });
    }
}
