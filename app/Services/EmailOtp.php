<?php

namespace App\Services;

use App\Mail\EmailVerificationCodeMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class EmailOtp
{
    public const CODE_LENGTH = 6;

    public const TTL_MINUTES = 10;

    public const MAX_ATTEMPTS = 5;

    public const RESEND_COOLDOWN_SECONDS = 60;

    public const RESULT_VERIFIED = 'verified';

    public const RESULT_INVALID = 'invalid';

    public const RESULT_EXPIRED = 'expired';

    public const RESULT_LOCKED = 'locked';

    /**
     * Generate a fresh 6-digit code, email it and store its hash.
     * Any previous code stops working. If the email cannot be sent the
     * exception is thrown and nothing is stored.
     */
    public function send(User $user): void
    {
        $code = str_pad((string) random_int(0, 999999), self::CODE_LENGTH, '0', STR_PAD_LEFT);

        Mail::to($user->email)->send(new EmailVerificationCodeMail($user, $code, self::TTL_MINUTES));

        $user->forceFill([
            'email_otp_hash' => Hash::make($code),
            'email_otp_expires_at' => now()->addMinutes(self::TTL_MINUTES),
            'email_otp_sent_at' => now(),
            'email_otp_attempts' => 0,
        ])->save();
    }

    /**
     * Check the code a user typed in and, when it is right, mark the email as verified.
     *
     * @return self::RESULT_*
     */
    public function verify(User $user, string $code): string
    {
        if ($user->email_otp_hash === null || $user->email_otp_expires_at === null) {
            return self::RESULT_EXPIRED;
        }

        if ($user->email_otp_expires_at->isPast()) {
            $this->clear($user);

            return self::RESULT_EXPIRED;
        }

        if ($user->email_otp_attempts >= self::MAX_ATTEMPTS) {
            $this->clear($user);

            return self::RESULT_LOCKED;
        }

        if (! Hash::check($code, $user->email_otp_hash)) {
            $user->forceFill(['email_otp_attempts' => $user->email_otp_attempts + 1])->save();

            if ($user->email_otp_attempts >= self::MAX_ATTEMPTS) {
                $this->clear($user);

                return self::RESULT_LOCKED;
            }

            return self::RESULT_INVALID;
        }

        $this->clear($user);
        $user->markEmailAsVerified();

        return self::RESULT_VERIFIED;
    }

    /**
     * Seconds left before the current code expires (0 when there is no valid code).
     */
    public function secondsUntilExpiry(User $user): int
    {
        if ($user->email_otp_hash === null || $user->email_otp_expires_at === null) {
            return 0;
        }

        return max(0, (int) now()->diffInSeconds($user->email_otp_expires_at, false));
    }

    /**
     * Seconds left before a new code may be requested.
     */
    public function secondsUntilResend(User $user): int
    {
        if ($user->email_otp_sent_at === null) {
            return 0;
        }

        $availableAt = $user->email_otp_sent_at->copy()->addSeconds(self::RESEND_COOLDOWN_SECONDS);

        return max(0, (int) now()->diffInSeconds($availableAt, false));
    }

    public function hasActiveCode(User $user): bool
    {
        return $this->secondsUntilExpiry($user) > 0;
    }

    /**
     * Forget the stored code (used after success, expiry or too many wrong tries).
     */
    public function clear(User $user): void
    {
        $user->forceFill([
            'email_otp_hash' => null,
            'email_otp_expires_at' => null,
            'email_otp_attempts' => 0,
        ])->save();
    }
}
