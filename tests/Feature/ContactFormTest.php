<?php

use App\Mail\ContactMessageMail;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;

test('contact page can be rendered', function () {
    $this->get('/contact')->assertStatus(200);
});

test('contact form saves the message and emails the team', function () {
    Mail::fake();

    $this->post('/contact', [
        'full_name' => 'Tariq Mahmood',
        'email' => 'tariq@example.com',
        'subject' => 'Farmer Onboarding',
        'message' => 'How do I join as a farmer?',
    ])->assertRedirect('/contact')->assertSessionHas('success');

    $this->assertDatabaseHas('contact_messages', [
        'email' => 'tariq@example.com',
        'subject' => 'Farmer Onboarding',
    ]);
    Mail::assertSent(ContactMessageMail::class, 1);
});

test('contact form rejects invalid input', function () {
    $this->post('/contact', [
        'full_name' => '',
        'email' => 'not-an-email',
        'subject' => 'Made up topic',
        'message' => 'short',
    ])->assertSessionHasErrors(['full_name', 'email', 'subject', 'message']);

    expect(ContactMessage::count())->toBe(0);
});

test('contact form ignores bots that fill the honeypot', function () {
    Mail::fake();

    $this->post('/contact', [
        'full_name' => 'Spam Bot',
        'email' => 'bot@example.com',
        'subject' => 'Other',
        'message' => 'Buy cheap stuff now please',
        'website' => 'http://spam.example',
    ])->assertRedirect('/contact');

    expect(ContactMessage::count())->toBe(0);
    Mail::assertNothingSent();
});
