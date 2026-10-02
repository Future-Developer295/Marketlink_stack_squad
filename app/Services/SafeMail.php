<?php

namespace App\Services;

use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Throwable;

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
