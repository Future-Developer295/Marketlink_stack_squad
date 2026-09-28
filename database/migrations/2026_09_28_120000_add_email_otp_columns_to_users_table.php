<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email_otp_hash')->nullable()->after('email_verified_at');
            $table->timestamp('email_otp_expires_at')->nullable()->after('email_otp_hash');
            $table->timestamp('email_otp_sent_at')->nullable()->after('email_otp_expires_at');
            $table->unsignedTinyInteger('email_otp_attempts')->default(0)->after('email_otp_sent_at');
        });

        // Accounts that already exist were created before email verification
        // was required. Mark them verified so nobody (including the admin and
        // the seeded demo accounts) gets locked out of login.
        DB::table('users')
            ->whereNull('email_verified_at')
            ->update(['email_verified_at' => now()]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email_otp_hash',
                'email_otp_expires_at',
                'email_otp_sent_at',
                'email_otp_attempts',
            ]);
        });
    }
};
