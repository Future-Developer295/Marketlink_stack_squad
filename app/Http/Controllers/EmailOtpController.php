<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\EmailOtp;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class EmailOtpController extends Controller
{
    public function __construct(private EmailOtp $otp)
    {
    }

    public function show(Request $request)
    {
        $user = $this->pendingUser($request);

        if (! $user) {
            return redirect()->route('login')
                ->with('warning', 'Please login or register to verify your email.');
        }

        if ($user->hasVerifiedEmail()) {
            return $this->finish($request, $user);
        }

        return view('Website.Auth.verify-otp', [
            'maskedEmail' => $this->maskEmail($user->email),
            'expiresIn' => $this->otp->secondsUntilExpiry($user),
            'resendIn' => $this->otp->secondsUntilResend($user),
            'ttlMinutes' => EmailOtp::TTL_MINUTES,
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $user = $this->pendingUser($request);

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return $this->finish($request, $user);
        }

        $data = $request->validate([
            'code' => ['required', 'digits:'.EmailOtp::CODE_LENGTH],
        ], [
            'code.required' => 'Please enter the 6-digit code.',
            'code.digits' => 'The code must be exactly 6 digits.',
        ]);

        $result = $this->otp->verify($user, $data['code']);

        if ($result === EmailOtp::RESULT_VERIFIED) {
            event(new Verified($user));

            return $this->finish($request, $user);
        }

        $message = match ($result) {
            EmailOtp::RESULT_EXPIRED => 'This code has expired. Please request a new one.',
            EmailOtp::RESULT_LOCKED => 'Too many wrong attempts. Please request a new code.',
            default => 'That code is not correct. Please try again.',
        };

        throw ValidationException::withMessages(['code' => $message]);
    }

    public function resend(Request $request): RedirectResponse
    {
        $user = $this->pendingUser($request);

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return $this->finish($request, $user);
        }

        $wait = $this->otp->secondsUntilResend($user);

        if ($wait > 0) {
            throw ValidationException::withMessages([
                'code' => "Please wait {$wait} seconds before requesting a new code.",
            ]);
        }

        try {
            $this->otp->send($user);
        } catch (Throwable $e) {
            report($e);

            throw ValidationException::withMessages([
                'code' => 'We could not send the email right now. Please try again in a moment.',
            ]);
        }

        return back()->with('success', 'A new code has been sent to your email.');
    }

    /**
     * The person verifying: whoever just registered or tried to log in
     * (remembered in the session), or a logged-in user who changed their email.
     */
    private function pendingUser(Request $request): ?User
    {
        $email = $request->session()->get('otp_email') ?? $request->user()?->email;

        return $email ? User::where('email', $email)->first() : null;
    }

    private function finish(Request $request, User $user): RedirectResponse
    {
        $request->session()->forget('otp_email');

        if ($request->user()?->is($user)) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('login')
            ->with('success', 'Email verified! You can now login.');
    }

    private function maskEmail(string $email): string
    {
        [$local, $domain] = array_pad(explode('@', $email, 2), 2, '');

        return mb_substr($local, 0, 2).str_repeat('*', max(1, mb_strlen($local) - 2)).'@'.$domain;
    }
}
