<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('farmer_profile', function (Blueprint $table) {
            $table->unsignedInteger('cutoff_hours')->default(2)->after('end_time');
        });
    }

    public function down(): void
    {
        Schema::table('farmer_profile', function (Blueprint $table) {
            $table->dropColumn('cutoff_hours');
        });
    }
};
