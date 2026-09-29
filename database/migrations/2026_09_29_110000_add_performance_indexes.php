<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Indexes for the paginated dashboard / account lists and the dashboard aggregates.
     *
     * Single-column foreign keys (orders.farmer_id, favorites.user_id, ...) are already
     * indexed by MySQL, so those two are only created when no index exists yet
     * (e.g. on SQLite). The composite indexes are always new.
     */
    public function up(): void
    {
        // Customer order list: WHERE user_id = ? [AND status = ?] ORDER BY order_date
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['user_id', 'status', 'order_date'], 'orders_user_status_date_index');
        });

        if (! Schema::hasIndex('orders', ['farmer_id'])) {
            Schema::table('orders', function (Blueprint $table) {
                $table->index('farmer_id', 'orders_farmer_id_index');
            });
        }

        // Catalog / stock checks: WHERE is_active = 1 AND stock_quantity > 0 [AND farmer_id = ?]
        Schema::table('products', function (Blueprint $table) {
            $table->index(['is_active', 'stock_quantity', 'farmer_id'], 'products_active_stock_farmer_index');
        });

        if (! Schema::hasIndex('favorites', ['user_id'])) {
            Schema::table('favorites', function (Blueprint $table) {
                $table->index('user_id', 'favorites_user_id_index');
            });
        }

        // Unread counts and the notifications list
        Schema::table('notifications', function (Blueprint $table) {
            $table->index(['user_id', 'is_read'], 'notifications_user_read_index');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_user_status_date_index');
        });

        if (Schema::hasIndex('orders', 'orders_farmer_id_index')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropIndex('orders_farmer_id_index');
            });
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_active_stock_farmer_index');
        });

        if (Schema::hasIndex('favorites', 'favorites_user_id_index')) {
            Schema::table('favorites', function (Blueprint $table) {
                $table->dropIndex('favorites_user_id_index');
            });
        }

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_user_read_index');
        });
    }
};
