<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE announcement MODIFY audience ENUM('all', 'farmer', 'all_farmers') NOT NULL DEFAULT 'all'");
    }

    public function down(): void
    {
        DB::statement("UPDATE announcement SET audience = 'all' WHERE audience = 'all_farmers'");
        DB::statement("ALTER TABLE announcement MODIFY audience ENUM('all', 'farmer') NOT NULL DEFAULT 'all'");
    }
};
