<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pickup_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')
                ->constrained('farmer_profile')
                ->cascadeOnDelete();

            $table->foreignId('market_id')
                ->constrained('markets')
                ->cascadeOnDelete();

            $table->date('date');

            $table->time('start_time');
            $table->time('end_time');

            $table->integer('capacity');

            $table->boolean('is_available')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickup_slots');
    }
};
