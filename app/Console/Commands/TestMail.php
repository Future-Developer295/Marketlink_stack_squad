<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMail extends Command
{
    protected $signature = 'marketlink:test-mail {to? : Recipient (defaults to the contact form receiver)}';

    protected $description = 'Send a test email and print the exact SMTP error if it fails';

    public function handle(): int
    {
        $to = $this->argument('to') ?: config('marketlink.contact_email');

        $this->line('Mailer: '.config('mail.default'));
        $this->line('Host:   '.config('mail.mailers.smtp.host').':'.config('mail.mailers.smtp.port'));
        $this->line('User:   '.config('mail.mailers.smtp.username'));
        $this->line('To:     '.$to);

        try {
            Mail::raw('MarketLink SMTP test — if you can read this, email is working.', function ($m) use ($to) {
                $m->to($to)->subject('MarketLink SMTP test');
            });
        } catch (\Throwable $e) {
            $this->error('FAILED: '.get_class($e));
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info('Sent OK. Check the inbox (and Spam folder) of '.$to);

        return self::SUCCESS;
    }
}
