<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProductApprovedMail extends Mailable
{
    use SerializesModels;

    public function __construct(public Product $product)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your product "' . $this->product->name . '" has been approved',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.product-approved',
            with: [
                'product' => $this->product,
                'dashboardUrl' => route('products'),
            ],
        );
    }
}
