<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Per-farmer commission override (percentage, e.g. 12.50 = 12.5%).
     * Null means "use the platform default" (config('marketlink.default_commission_rate')).
     */
    public function up(): void
    {
        Schema::table('farmer_profile', function (Blueprint $table) {
            $table->decimal('commission_rate', 5, 2)->nullable()->after('approval_status');
        });
    }

    public function down(): void
    {
        Schema::table('farmer_profile', function (Blueprint $table) {
            $table->dropColumn('commission_rate');
        });
    }
};
