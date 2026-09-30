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

    public function toResponse($request)
    {
        $user = Auth::user();
        $email = $user?->email;
        $sent = false;
        $failure = null;

        if ($user) {
            try {
                $this->otp->send($user);
                $sent = true;
            } catch (Throwable $e) {
                report($e);
                $failure = EmailOtp::failureMessage($e);
            }
        }

        Auth::guard(config('fortify.guard'))->logout();

        $request->session()->regenerate();
        $request->session()->regenerateToken();
        $request->session()->put('otp_email', $email);

        $redirect = redirect()->route('verification.notice');

        return $sent
            ? $redirect->with('success', 'Account created! We have emailed you a 6-digit verification code.')
            : $redirect->with('warning', 'Account created. '.($failure ?? 'We could not send the code email.').' Please press "Resend code".');
    }
}
