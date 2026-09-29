<?php

namespace App\Services;

use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Send an email without ever breaking the request when SMTP has a problem.
 * The exception is written to storage/logs/laravel.log and false is returned,
 * so callers can tell the person "saved, but the email could not be sent".
 */
class SafeMail
{
    public static function send(?string $to, Mailable $mailable): bool
    {
        if (! $to) {
            return false;
        }

        try {
            Mail::to($to)->send($mailable);

            return true;
        } catch (Throwable $e) {
            report($e);

            return false;
        }
    }
}
