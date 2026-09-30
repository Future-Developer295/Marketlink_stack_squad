<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('farmer_profile', function (Blueprint $table) {
            $table->string('business_name', 150)->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->string('city', 100)->nullable()->change();
            $table->string('state', 100)->nullable()->change();
            $table->string('country', 100)->nullable()->change();
            $table->decimal('latitude', 10, 8)->nullable()->change();
            $table->decimal('longitude', 11, 8)->nullable()->change();
            $table->string('operating_days', 255)->nullable()->change();
            $table->time('start_time')->nullable()->change();
            $table->time('end_time')->nullable()->change();
        });
    }

    public function down(): void
    {
    }
};
