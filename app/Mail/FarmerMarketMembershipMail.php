<?php

namespace App\Mail;

use App\Models\Market;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FarmerMarketMembershipMail extends Mailable
{
    use SerializesModels;

    /**
     * @param  string  $action    'joined' or 'left'
     * @param  bool    $forAdmin  true = notification sent to the admin, false = sent to the farmer
     */
    public function __construct(
        public User $farmer,
        public Market $market,
        public string $action,
        public bool $forAdmin = false,
    ) {
    }

    public function envelope(): Envelope
    {
        $joined = $this->action === 'joined';

        $subject = $this->forAdmin
            ? "Farmer {$this->farmer->name} " . ($joined ? 'joined' : 'left') . " {$this->market->name}"
            : ($joined
                ? "You have joined {$this->market->name} on MarketLink"
                : "You have left {$this->market->name} on MarketLink");

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.farmer-market-membership',
            text: 'emails.farmer-market-membership-text',
            with: [
                'joined' => $this->action === 'joined',
                'actionUrl' => $this->forAdmin ? route('farmers') : route('my_markets'),
            ],
        );
    }
}
