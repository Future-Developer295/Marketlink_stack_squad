<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('market_farmers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('market_id')
                ->constrained('markets')
                ->cascadeOnDelete();

            $table->foreignId('farmer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->boolean('is_active')->default(1);

            $table->timestamps();

            $table->unique(['market_id', 'farmer_id']);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_farmers');
    }
};
