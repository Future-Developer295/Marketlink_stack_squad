<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('farmer_profile', function (Blueprint $table) {
            $table->id();




            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                  ->cascadeOnDelete();

            $table->string('stall_name', 200);
            $table->string('business_name', 150);
            $table->text('description');
            $table->text('address');

            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('country', 100);

            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);

            $table->string('operating_days', 255);
           $table->string('farmer_image')->nullable();                                                                                                                                                              
            $table->time('start_time');
            $table->time('end_time');

            $table->enum('approval_status', [
                'pending',
                'approved',
                'rejected'
            ]);

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                  ->cascadeOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

        });



    }

    public function down(): void
    {
        Schema::dropIfExists('farmer_profile');
    }
};
