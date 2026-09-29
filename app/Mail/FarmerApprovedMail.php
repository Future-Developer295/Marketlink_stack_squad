<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FarmerApprovedMail extends Mailable
{
    use SerializesModels;

    public function __construct(public User $user)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your MarketLink farmer account has been approved',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.farmer-approved',
            text: 'emails.farmer-approved-text',
            with: ['loginUrl' => route('login')],
        );
    }
}
