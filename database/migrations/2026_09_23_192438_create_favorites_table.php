<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();


            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('farmer_id')
                ->nullable()
                ->constrained('farmer_profile')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products')
                ->cascadeOnDelete();

            $table->timestamps();

      
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
