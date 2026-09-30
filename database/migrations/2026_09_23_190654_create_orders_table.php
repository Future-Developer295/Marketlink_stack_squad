<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();


            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('farmer_id')
                ->constrained('farmer_profile')
                ->cascadeOnDelete();

            $table->foreignId('pickup_slot_id')
                ->constrained('pickup_slots')
                ->cascadeOnDelete();

            $table->decimal('total_amount', 10, 2);

            $table->enum('status', [
                'pending',
                'confirmed',
                'ready',
                'picked_up',
                'cancelled'
            ])->default('pending');

            $table->dateTime('order_date');

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
