<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailVerificationCodeMail extends Mailable
{
    use SerializesModels;

    public function __construct(
        public User $user,
        public string $code,
        public int $minutes,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your MarketLink verification code: '.$this->code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.verify-otp',
        );
    }
}
