<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcement', function (Blueprint $table) {
            $table->id();


    $table->foreignId('admin_id')
          ->constrained('users')
          ->cascadeOnDelete();

    $table->string('title', 150);
    $table->text('message');
    $table->boolean('is_active')->default(1);
    $table->timestamp('published_at')->nullable();
    
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcement');
    }
};
