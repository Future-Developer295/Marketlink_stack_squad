<?php

namespace App\Http\Responses;

use App\Services\EmailOtp;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Throwable;

class RegisterResponse implements RegisterResponseContract
{
    public function __construct(private EmailOtp $otp)
    {
    }

    /**
     * Fortify logs the new user in right after registration. Undo that:
     * nobody may be logged in until the emailed code has been verified.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toResponse($request)
    {
        $user = Auth::user();
        $email = $user?->email;
        $sent = false;

        // Email the 6-digit code. If SMTP fails, registration still succeeds and
        // the user can press "Resend code" on the verification page.
        if ($user) {
            try {
                $this->otp->send($user);
                $sent = true;
            } catch (Throwable $e) {
                report($e);
            }
        }

        Auth::guard(config('fortify.guard'))->logout();

        // regenerate() (not invalidate()) issues a new session id but keeps the
        // rest of the session, so a guest's cart survives registration.
        $request->session()->regenerate();
        $request->session()->regenerateToken();
        $request->session()->put('otp_email', $email);

        $redirect = redirect()->route('verification.notice');

        return $sent
            ? $redirect->with('success', 'Account created! We have emailed you a 6-digit verification code.')
            : $redirect->with('warning', 'Account created, but we could not send the code email. Please press "Resend code".');
    }
}
