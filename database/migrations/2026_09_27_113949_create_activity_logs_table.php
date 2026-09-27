<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Who performed the action (nullable so log survives user deletion).
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Snapshot of the actor's name/role at the time, kept even if the
            // user account is later deleted or renamed.
            $table->string('actor_name')->nullable();
            $table->string('actor_role')->nullable();

            // e.g. approved, rejected, deleted, updated, flagged, created
            $table->string('action', 50);

            // Human readable summary, e.g. "Deleted product #12 (Tomatoes)"
            $table->string('description', 500);

            // What the action was performed on, e.g. Product / FarmerProfile / Review
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();

            $table->timestamps();

            $table->index(['subject_type', 'subject_id']);
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
