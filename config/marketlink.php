<?php

return [
    // Inbox that receives messages from the Contact page.
    // Set CONTACT_RECEIVER_EMAIL in .env to change it; falls back to MAIL_FROM_ADDRESS.
    'contact_email' => env('CONTACT_RECEIVER_EMAIL', env('MAIL_FROM_ADDRESS', 'hello@example.com')),
];
