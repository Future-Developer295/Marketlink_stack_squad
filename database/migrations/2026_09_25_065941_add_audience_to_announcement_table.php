<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcement', function (Blueprint $table) {
            $table->enum('audience', ['all', 'farmer'])
                  ->default('all')
                  ->after('message');

            $table->foreignId('farmer_id')
                  ->nullable()
                  ->after('audience')
                  ->constrained('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('announcement', function (Blueprint $table) {
            $table->dropConstrainedForeignId('farmer_id');
            $table->dropColumn('audience');
        });
    }
};
