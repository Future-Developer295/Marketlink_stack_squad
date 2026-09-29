<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FarmerRejectedMail extends Mailable
{
    use SerializesModels;

    public function __construct(public User $user, public string $reason)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Update on your MarketLink farmer account',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.farmer-rejected',
            text: 'emails.farmer-rejected-text',
            with: ['reason' => $this->reason, 'loginUrl' => route('login')],
        );
    }
}
