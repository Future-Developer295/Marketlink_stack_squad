<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('announcement', function (Blueprint $table) {
                $table->enum('audience', ['all', 'farmer', 'all_farmers'])->default('all')->change();
            });

            return;
        }

        DB::statement("ALTER TABLE announcement MODIFY audience ENUM('all', 'farmer', 'all_farmers') NOT NULL DEFAULT 'all'");
    }

    public function down(): void
    {
        DB::statement("UPDATE announcement SET audience = 'all' WHERE audience = 'all_farmers'");
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('announcement', function (Blueprint $table) {
                $table->enum('audience', ['all', 'farmer'])->default('all')->change();
            });

            return;
        }
        DB::statement("ALTER TABLE announcement MODIFY audience ENUM('all', 'farmer') NOT NULL DEFAULT 'all'");
    }
};
