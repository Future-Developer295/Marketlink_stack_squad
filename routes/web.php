<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

// Single entry point for "/dashboard": sends admin/farmer/customer to
// their own dashboard. Registered under both names since different
// layouts in the app reference either name to build the link.
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {

    Route::get('/dashboard/farmer', [DashboardController::class, 'farmerDashboard'])
        ->name('farmer_dashboard')
        ->middleware('permission:view dashboard');

    Route::get('/dashboard/admin', [DashboardController::class, 'adminDashboard'])
        ->name('admin_dashboard')
        ->middleware('permission:view admin dashboard');

    Route::get('/dashboard/my-profile', [DashboardController::class, 'myProfile'])
        ->name('my_profile')
        ->middleware('permission:view profile');
    Route::post('/dashboard/my-profile/update', [DashboardController::class, 'myProfileUpdate'])
        ->name('my_profile_update')
        ->middleware('permission:edit profile');

    Route::get('/dashboard/my-markets', [DashboardController::class, 'myMarkets'])
        ->name('my_markets')
        ->middleware('permission:view markets');
    Route::get('/dashboard/my-markets/join', [DashboardController::class, 'myMarketsJoin'])
        ->name('my_markets_join')
        ->middleware('permission:join markets');
    Route::post('/dashboard/my-markets/join/store', [DashboardController::class, 'myMarketsJoinStore'])
        ->name('my_markets_join_store')
        ->middleware('permission:join markets');
    Route::post('/dashboard/my-markets/{id}/leave', [DashboardController::class, 'myMarketsLeave'])
        ->name('my_markets_leave')
        ->middleware('permission:leave markets');

    Route::get('/dashboard/categories', [DashboardController::class, 'categories'])
        ->name('categories')
        ->middleware('permission:view categories');
    Route::get('/dashboard/categories/add', [DashboardController::class, 'categoryAdd'])
        ->name('category_add')
        ->middleware('permission:add categories');
    Route::post('/dashboard/categories/store', [DashboardController::class, 'categoryStore'])
        ->name('category_store')
        ->middleware('permission:add categories');
    Route::get('/dashboard/categories/edit/{id}', [DashboardController::class, 'categoryEdit'])
        ->name('category_edit')
        ->middleware('permission:edit categories');
    Route::post('/dashboard/categories/update/{id}', [DashboardController::class, 'categoryUpdate'])
        ->name('category_update')
        ->middleware('permission:edit categories');
    Route::post('/dashboard/categories/delete/{id}', [DashboardController::class, 'categoryDelete'])
        ->name('category_delete')
        ->middleware('permission:delete categories');

    Route::get('/dashboard/products', [DashboardController::class, 'products'])
        ->name('products')
        ->middleware('permission:view products');
    Route::get('/dashboard/products/add', [DashboardController::class, 'productAdd'])
        ->name('product_add')
        ->middleware('permission:add products');
    Route::post('/dashboard/products/store', [DashboardController::class, 'productStore'])
        ->name('product_store')
        ->middleware('permission:add products');
    Route::get('/dashboard/products/edit/{id}', [DashboardController::class, 'productEdit'])
        ->name('product_edit')
        ->middleware('permission:edit products');
    Route::get('/dashboard/products/view/{id}', [DashboardController::class, 'productView'])
        ->name('product_view')
        ->middleware('permission:view products');
    Route::post('/dashboard/products/update/{id}', [DashboardController::class, 'productUpdate'])
        ->name('product_update')
        ->middleware('permission:edit products');
    Route::post('/dashboard/products/delete/{id}', [DashboardController::class, 'productDelete'])
        ->name('product_delete')
        ->middleware('permission:delete products');

    Route::get('/dashboard/weekly-stock', [DashboardController::class, 'stock'])
        ->name('stock')
        ->middleware('permission:view weekly stock');
    Route::get('/dashboard/weekly-stock/add', [DashboardController::class, 'stockAdd'])
        ->name('stock_add')
        ->middleware('permission:add weekly stock');
    Route::post('/dashboard/weekly-stock/store', [DashboardController::class, 'stockStore'])
        ->name('stock_store')
        ->middleware('permission:add weekly stock');
    Route::get('/dashboard/weekly-stock/edit/{id}', [DashboardController::class, 'stockEdit'])
        ->name('stock_edit')
        ->middleware('permission:edit weekly stock');
    Route::post('/dashboard/weekly-stock/update/{id}', [DashboardController::class, 'stockUpdate'])
        ->name('stock_update')
        ->middleware('permission:edit weekly stock');
    Route::post('/dashboard/weekly-stock/delete/{id}', [DashboardController::class, 'stockDelete'])
        ->name('stock_delete')
        ->middleware('permission:delete weekly stock');

    Route::get('/dashboard/orders', [DashboardController::class, 'orders'])
        ->name('orders')
        ->middleware('permission:view orders');
    Route::get('/dashboard/orders/view/{id}', [DashboardController::class, 'orderView'])
        ->name('order_view')
        ->middleware('permission:view order');
    Route::post('/dashboard/orders/status/{id}', [DashboardController::class, 'orderStatusUpdate'])
        ->name('order_status_update')
        ->middleware('permission:update order status');

    Route::get('/dashboard/pickup-slots', [DashboardController::class, 'slots'])
        ->name('slots')
        ->middleware('permission:view pickup slots');
    Route::get('/dashboard/pickup-slots/add', [DashboardController::class, 'slotAdd'])
        ->name('slot_add')
        ->middleware('permission:add pickup slots');
    Route::post('/dashboard/pickup-slots/store', [DashboardController::class, 'slotStore'])
        ->name('slot_store')
        ->middleware('permission:add pickup slots');
    Route::get('/dashboard/pickup-slots/edit/{id}', [DashboardController::class, 'slotEdit'])
        ->name('slot_edit')
        ->middleware('permission:edit pickup slots');
    Route::post('/dashboard/pickup-slots/update/{id}', [DashboardController::class, 'slotUpdate'])
        ->name('slot_update')
        ->middleware('permission:edit pickup slots');
    Route::post('/dashboard/pickup-slots/delete/{id}', [DashboardController::class, 'slotDelete'])
        ->name('slot_delete')
        ->middleware('permission:delete pickup slots');

    Route::get('/dashboard/markets', [DashboardController::class, 'markets'])
        ->name('markets')
        ->middleware('permission:view markets');
    Route::get('/dashboard/markets/add', [DashboardController::class, 'marketAdd'])
        ->name('market_add')
        ->middleware('permission:add markets');
    Route::post('/dashboard/markets/store', [DashboardController::class, 'marketStore'])
        ->name('market_store')
        ->middleware('permission:add markets');
    Route::get('/dashboard/markets/edit/{id}', [DashboardController::class, 'marketEdit'])
        ->name('market_edit')
        ->middleware('permission:edit markets');
    Route::post('/dashboard/markets/update/{id}', [DashboardController::class, 'marketUpdate'])
        ->name('market_update')
        ->middleware('permission:edit markets');
    Route::post('/dashboard/markets/delete/{id}', [DashboardController::class, 'marketDelete'])
        ->name('market_delete')
        ->middleware('permission:delete markets');

    Route::get('/dashboard/users', [DashboardController::class, 'users'])
        ->name('users')
        ->middleware('permission:view users');
    Route::get('/dashboard/users/add', [DashboardController::class, 'userAdd'])
        ->name('user_add')
        ->middleware('permission:add users');
    Route::post('/dashboard/users/store', [DashboardController::class, 'userStore'])
        ->name('user_store')
        ->middleware('permission:add users');
    Route::get('/dashboard/users/edit/{id}', [DashboardController::class, 'userEdit'])
        ->name('user_edit')
        ->middleware('permission:edit users');
    Route::post('/dashboard/users/update/{id}', [DashboardController::class, 'userUpdate'])
        ->name('user_update')
        ->middleware('permission:edit users');
    Route::post('/dashboard/users/delete/{id}', [DashboardController::class, 'userDelete'])
        ->name('user_delete')
        ->middleware('permission:delete users');

    Route::get('/dashboard/roles', [DashboardController::class, 'roles'])
        ->name('roles')
        ->middleware('permission:view roles');

    Route::get('/dashboard/roles/add', [DashboardController::class, 'roleAdd'])
        ->name('role_add')
        ->middleware('permission:add roles');

    Route::post('/dashboard/roles/store', [DashboardController::class, 'roleStore'])
        ->name('role_store')
        ->middleware('permission:add roles');

    Route::get('/dashboard/roles/view/{id}', [DashboardController::class, 'roleView'])
        ->name('role_view')
        ->middleware('permission:view roles');

    Route::get('/dashboard/roles/edit/{id}', [DashboardController::class, 'roleEdit'])
        ->name('role_edit')
        ->middleware('permission:edit roles');

    Route::post('/dashboard/roles/update/{id}', [DashboardController::class, 'roleUpdate'])
        ->name('role_update')
        ->middleware('permission:edit roles');

    Route::post('/dashboard/roles/delete/{id}', [DashboardController::class, 'roleDelete'])
        ->name('role_delete')
        ->middleware('permission:delete roles');


    Route::get('/dashboard/permissions', [DashboardController::class, 'permissions'])
        ->name('permissions')
        ->middleware('permission:view permissions');

    Route::get('/dashboard/permissions/add', [DashboardController::class, 'permissionAdd'])
        ->name('permission_add')
        ->middleware('permission:add permissions');

    Route::post('/dashboard/permissions/store', [DashboardController::class, 'permissionStore'])
        ->name('permission_store')
        ->middleware('permission:add permissions');

    Route::get('/dashboard/permissions/view/{id}', [DashboardController::class, 'permissionView'])
        ->name('permission_view')
        ->middleware('permission:view permissions');

    Route::get('/dashboard/permissions/edit/{id}', [DashboardController::class, 'permissionEdit'])
        ->name('permission_edit')
        ->middleware('permission:edit permissions');

    Route::post('/dashboard/permissions/update/{id}', [DashboardController::class, 'permissionUpdate'])
        ->name('permission_update')
        ->middleware('permission:edit permissions');

    Route::post('/dashboard/permissions/delete/{id}', [DashboardController::class, 'permissionDelete'])
        ->name('permission_delete')
        ->middleware('permission:delete permissions');

    Route::get('/dashboard/farmers', [DashboardController::class, 'farmers'])
        ->name('farmers')
        ->middleware('permission:view farmers');
    Route::get('/dashboard/farmers/edit/{id}', [DashboardController::class, 'farmerEdit'])
        ->name('farmer_edit')
        ->middleware('permission:edit farmers');
    Route::post('/dashboard/farmers/update/{id}', [DashboardController::class, 'farmerUpdate'])
        ->name('farmer_update')
        ->middleware('permission:edit farmers');
    Route::post('/dashboard/farmers/delete/{id}', [DashboardController::class, 'farmerDelete'])
        ->name('farmer_delete')
        ->middleware('permission:delete farmers');

    Route::get('/dashboard/customers', [DashboardController::class, 'customers'])
        ->name('customers')
        ->middleware('permission:view customers');
    Route::get('/dashboard/customers/view/{id}', [DashboardController::class, 'customerView'])
        ->name('customer_view')
        ->middleware('permission:view customer');
    Route::post('/dashboard/customers/delete/{id}', [DashboardController::class, 'customerDelete'])
        ->name('customer_delete')
        ->middleware('permission:delete customers');

    Route::get('/dashboard/reviews', [DashboardController::class, 'reviews'])
        ->name('reviews')
        ->middleware('permission:view reviews');
    Route::post('/dashboard/reviews/flag/{id}', [DashboardController::class, 'reviewFlag'])
        ->name('review_flag')
        ->middleware('permission:flag reviews');
    Route::post('/dashboard/reviews/delete/{id}', [DashboardController::class, 'reviewDelete'])
        ->name('review_delete')
        ->middleware('permission:delete reviews');

    Route::get('/dashboard/reports', [DashboardController::class, 'reports'])
        ->name('reports')
        ->middleware('permission:view reports');
    Route::post('/dashboard/reports/generate', [DashboardController::class, 'reportGenerate'])
        ->name('report_generate')
        ->middleware('permission:generate reports');
    Route::get('/dashboard/reports/download/{id}', [DashboardController::class, 'reportDownload'])
        ->name('report_download')
        ->middleware('permission:download reports');

    Route::get('/dashboard/announcements', [DashboardController::class, 'announcements'])
        ->name('announcements')
        ->middleware('permission:view announcements');
    Route::get('/dashboard/announcements/add', [DashboardController::class, 'announcementAdd'])
        ->name('announcement_add')
        ->middleware('permission:add announcements');
    Route::post('/dashboard/announcements/store', [DashboardController::class, 'announcementStore'])
        ->name('announcement_store')
        ->middleware('permission:add announcements');
    Route::get('/dashboard/announcements/edit/{id}', [DashboardController::class, 'announcementEdit'])
        ->name('announcement_edit')
        ->middleware('permission:edit announcements');
    Route::post('/dashboard/announcements/update/{id}', [DashboardController::class, 'announcementUpdate'])
        ->name('announcement_update')
        ->middleware('permission:edit announcements');
    Route::post('/dashboard/announcements/delete/{id}', [DashboardController::class, 'announcementDelete'])
        ->name('announcement_delete')
        ->middleware('permission:delete announcements');
});

Route::get('/', [WebsiteController::class, 'home']);

Route::get('/markets', [WebsiteController::class, 'markets']);
Route::get('/markets/{market}', [WebsiteController::class, 'marketDetail']);

Route::get('/farmers', [WebsiteController::class, 'farmers']);
Route::get('/farmers/{farmer}', [WebsiteController::class, 'farmerDetail']);

Route::get('/products', [WebsiteController::class, 'products']);
Route::get('/products/{product}', [WebsiteController::class, 'productDetail']);

Route::get('/cart', [WebsiteController::class, 'cart']);
Route::post('/cart/add/{product}', [WebsiteController::class, 'addToCart']);
Route::post('/cart/update/{product}', [WebsiteController::class, 'updateCartItem']);
Route::post('/cart/remove/{product}', [WebsiteController::class, 'removeFromCart']);

Route::get('/checkout', [WebsiteController::class, 'checkout']);
Route::post('/checkout', [WebsiteController::class, 'placeOrder']);

Route::get('/about', [WebsiteController::class, 'about']);

Route::get('/contact', [WebsiteController::class, 'contact']);
Route::post('/contact', [WebsiteController::class, 'submitContact']);

// /login, /register and /logout are intentionally NOT defined here.
// Laravel Fortify already registers them (named 'login', 'register', 'logout')
// and was winning the route match anyway, making duplicate routes here dead
// code. See app/Providers/FortifyServiceProvider.php for the custom
// loginView()/registerView() that make Fortify render this app's own
// Website.Auth.login / Website.Auth.register Blade views.

Route::middleware('auth')->group(function () {

    // Customer Dashboard
    Route::prefix('dashboard/customer')->group(function () {
        Route::get('/', [WebsiteController::class, 'index'])
            ->name('customer_dashboard');

        Route::get('/profile', [WebsiteController::class, 'profile'])
            ->name('customer_profile');

        Route::put('/profile', [WebsiteController::class, 'updateProfile'])
            ->name('customer_profile_update');

        Route::get('/orders', [WebsiteController::class, 'orders'])
            ->name('customer_orders');

        Route::get('/orders/{order}', [WebsiteController::class, 'orderDetail'])
            ->name('customer_order_detail');

        Route::post('/orders/{order}/cancel', [WebsiteController::class, 'cancelOrder'])
            ->name('customer_order_cancel');

        Route::post('/reorder/{item}', [WebsiteController::class, 'reorderItem'])
            ->name('customer_reorder');

        Route::get('/reviews', [WebsiteController::class, 'reviews'])
            ->name('customer_reviews');

        Route::get('/favorites', [WebsiteController::class, 'favorites'])
            ->name('customer_favorites');

        Route::delete('/favorites/{favorite}', [WebsiteController::class, 'removeFavorite'])
            ->name('customer_favorite_remove');

        Route::get('/notifications', [WebsiteController::class, 'notifications'])
            ->name('customer_notifications');

        Route::post('/notifications/{notification}/read', [WebsiteController::class, 'markNotificationRead'])
            ->name('customer_notification_read');
    });
});
