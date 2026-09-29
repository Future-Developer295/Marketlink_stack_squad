<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProductRejectedMail extends Mailable
{
    use SerializesModels;

    public function __construct(public Product $product, public string $reason)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your product "' . $this->product->name . '" was not approved',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.product-rejected',
            with: [
                'product' => $this->product,
                'reason' => $this->reason,
                'editUrl' => route('product_edit', $this->product->id),
            ],
        );
    }
}
