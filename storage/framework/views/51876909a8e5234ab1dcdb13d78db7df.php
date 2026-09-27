<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MarketLink — <?php echo $__env->yieldContent('page_title', 'Dashboard'); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo e(asset('Assets/Dashboard_Asset/css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('Assets/Dashboard_Asset/css/responsive.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('Assets/Dashboard_Asset/css/emerald-theme.css')); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body>
    <div class="shell">

        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <div class="brand-mark">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                    </svg>
                </div>
                <span class="brand-name">Market <span>Link</span></span>
            </div>

            <nav class="sidebar-nav">

                <div class="nav-group-label">MAIN</div>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view dashboard')): ?>
                    <a class="nav-link-custom <?php echo $__env->yieldContent('nav_dashboard_farmer'); ?>" href="<?php echo e(route('dashboard')); ?>">
                        <i class="bi bi-grid-1x2-fill"></i> Farmer Dashboard
                    </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view admin dashboard')): ?>
                    <a class="nav-link-custom <?php echo $__env->yieldContent('nav_dashboard_admin'); ?>" href="<?php echo e(route('admin_dashboard')); ?>">
                        <i class="bi bi-grid-3x3-gap-fill"></i> Admin Dashboard
                    </a>
                <?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('role', 'farmer')): ?>
                    <div class="nav-group-label">ACCOUNT</div>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view profile')): ?>
                        <a class="nav-link-custom <?php echo $__env->yieldContent('nav_profile'); ?>" href="<?php echo e(route('my_profile')); ?>">
                            <i class="bi bi-person-badge-fill"></i> My Profile
                        </a>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view markets', 'join markets'])): ?>
                        <details class="nav-dropdown">
                            <summary>
                                <i class="bi bi-shop-window nav-ic"></i> My Markets
                                <i class="bi bi-chevron-down chevron"></i>
                            </summary>

                            <div class="nav-sub">

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view markets')): ?>
                                    <a class="nav-sublink <?php echo $__env->yieldContent('nav_my_markets_list'); ?>" href="<?php echo e(route('my_markets')); ?>">
                                        <i class="bi bi-list-ul"></i> Market Listing
                                    </a>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('join markets')): ?>
                                    <a class="nav-sublink <?php echo $__env->yieldContent('nav_my_markets_add'); ?>" href="<?php echo e(route('my_markets_join')); ?>">
                                        <i class="bi bi-plus-lg"></i> Join Market
                                    </a>
                                <?php endif; ?>

                            </div>
                        </details>
                    <?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view categories', 'view products'])): ?>
                    <div class="nav-group-label">CATALOG</div>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view categories', 'add categories'])): ?>
                    <details class="nav-dropdown">
                        <summary>
                            <i class="bi bi-tags-fill nav-ic"></i> Categories
                            <i class="bi bi-chevron-down chevron"></i>
                        </summary>
                        <div class="nav-sub">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view categories')): ?>
                                <a class="nav-sublink <?php echo $__env->yieldContent('nav_categories_list'); ?>" href="<?php echo e(route('categories')); ?>">
                                    <i class="bi bi-list-ul"></i> Category Listing
                                </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add categories')): ?>
                                <a class="nav-sublink <?php echo $__env->yieldContent('nav_categories_add'); ?>" href="<?php echo e(route('category_add')); ?>">
                                    <i class="bi bi-plus-lg"></i> Add Category
                                </a>
                            <?php endif; ?>
                        </div>
                    </details>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view products', 'add products'])): ?>
                    <details class="nav-dropdown">
                        <summary>
                            <i class="bi bi-box-seam-fill nav-ic"></i> Products
                            <i class="bi bi-chevron-down chevron"></i>
                        </summary>
                        <div class="nav-sub">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view products')): ?>
                                <a class="nav-sublink <?php echo $__env->yieldContent('nav_products_list'); ?>" href="<?php echo e(route('products')); ?>">
                                    <i class="bi bi-list-ul"></i> Product Listing
                                </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add products')): ?>
                                <a class="nav-sublink <?php echo $__env->yieldContent('nav_products_add'); ?>" href="<?php echo e(route('product_add')); ?>">
                                    <i class="bi bi-plus-lg"></i> Add Product
                                </a>
                            <?php endif; ?>
                        </div>
                    </details>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view weekly stock', 'view orders', 'view pickup stock', 'view pickup slots', 'view markets'])): ?>
                    <div class="nav-group-label">OPERATIONS</div>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view weekly stock', 'add weekly stock'])): ?>
                    <details class="nav-dropdown">
                        <summary>
                            <i class="bi bi-calendar2-week-fill nav-ic"></i> Weekly Stock
                            <i class="bi bi-chevron-down chevron"></i>
                        </summary>
                        <div class="nav-sub">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view weekly stock')): ?>
                                <a class="nav-sublink <?php echo $__env->yieldContent('nav_stock_list'); ?>" href="<?php echo e(route('stock')); ?>">
                                    <i class="bi bi-list-ul"></i> Stock Listing
                                </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add weekly stock')): ?>
                                <a class="nav-sublink <?php echo $__env->yieldContent('nav_stock_add'); ?>" href="<?php echo e(route('stock_add')); ?>">
                                    <i class="bi bi-plus-lg"></i> Add Schedule
                                </a>
                            <?php endif; ?>
                        </div>
                    </details>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view orders')): ?>
                    <a class="nav-link-custom <?php echo $__env->yieldContent('nav_orders'); ?>" href="<?php echo e(route('orders')); ?>">
                        <i class="bi bi-cart-check-fill"></i> Orders
                    </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view product sales analytics')): ?>
                    <a class="nav-link-custom <?php echo $__env->yieldContent('nav_product_sales_analytics'); ?>" href="<?php echo e(route('product_sales_analytics')); ?>">
                        <i class="bi bi-graph-up-arrow"></i> Product Sales Analytics
                    </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view pickup slots', 'add pickup slots'])): ?>
                    <details class="nav-dropdown">
                        <summary>
                            <i class="bi bi-clock-history nav-ic"></i> Pickup Slots
                            <i class="bi bi-chevron-down chevron"></i>
                        </summary>
                        <div class="nav-sub">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view pickup slots')): ?>
                                <a class="nav-sublink <?php echo $__env->yieldContent('nav_slots_list'); ?>" href="<?php echo e(route('slots')); ?>">
                                    <i class="bi bi-list-ul"></i> Slot Listing
                                </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add pickup slots')): ?>
                                <a class="nav-sublink <?php echo $__env->yieldContent('nav_slots_add'); ?>" href="<?php echo e(route('slot_add')); ?>">
                                    <i class="bi bi-plus-lg"></i> Add Slot
                                </a>
                            <?php endif; ?>
                        </div>
                    </details>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view markets', 'add markets'])): ?>
                    <details class="nav-dropdown">
                        <summary>
                            <i class="bi bi-shop-window nav-ic"></i> Markets
                            <i class="bi bi-chevron-down chevron"></i>
                        </summary>
                        <div class="nav-sub">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view markets')): ?>
                                <a class="nav-sublink <?php echo $__env->yieldContent('nav_markets_list'); ?>" href="<?php echo e(route('markets')); ?>">
                                    <i class="bi bi-list-ul"></i> Market Listing
                                </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add markets')): ?>
                                <a class="nav-sublink <?php echo $__env->yieldContent('nav_markets_add'); ?>" href="<?php echo e(route('market_add')); ?>">
                                    <i class="bi bi-plus-lg"></i> Add Market
                                </a>
                            <?php endif; ?>
                        </div>
                    </details>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view users', 'view roles', 'view permissions', 'view farmers', 'view customers'])): ?>
                    <div class="nav-group-label">PEOPLE</div>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view users', 'add users'])): ?>
                    <details class="nav-dropdown">
                        <summary>
                            <i class="bi bi-people-fill nav-ic"></i> Users
                            <i class="bi bi-chevron-down chevron"></i>
                        </summary>
                        <div class="nav-sub">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view users')): ?>
                                <a class="nav-sublink <?php echo $__env->yieldContent('nav_users_list'); ?>" href="<?php echo e(route('users')); ?>">
                                    <i class="bi bi-list-ul"></i> User Listing
                                </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add users')): ?>
                                <a class="nav-sublink <?php echo $__env->yieldContent('nav_users_add'); ?>" href="<?php echo e(route('user_add')); ?>">
                                    <i class="bi bi-plus-lg"></i> Add User
                                </a>
                            <?php endif; ?>
                        </div>
                    </details>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view roles', 'add roles'])): ?>
                    <details class="nav-dropdown">
                        <summary>
                            <i class="bi bi-person-badge-fill nav-ic"></i> Roles
                            <i class="bi bi-chevron-down chevron"></i>
                        </summary>

                        <div class="nav-sub">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view roles')): ?>
                                <a class="nav-sublink <?php echo $__env->yieldContent('nav_roles_list'); ?>" href="<?php echo e(route('roles')); ?>">
                                    <i class="bi bi-list-ul"></i> Role Listing
                                </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add roles')): ?>
                                <a class="nav-sublink <?php echo $__env->yieldContent('nav_roles_add'); ?>" href="<?php echo e(route('role_add')); ?>">
                                    <i class="bi bi-plus-lg"></i> Add Role
                                </a>
                            <?php endif; ?>
                        </div>
                    </details>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view permissions', 'add permissions'])): ?>
                    <details class="nav-dropdown">
                        <summary>
                            <i class="bi bi-key-fill nav-ic"></i> Permissions
                            <i class="bi bi-chevron-down chevron"></i>
                        </summary>

                        <div class="nav-sub">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view permissions')): ?>
                                <a class="nav-sublink <?php echo $__env->yieldContent('nav_permissions_list'); ?>" href="<?php echo e(route('permissions')); ?>">
                                    <i class="bi bi-list-ul"></i> Permission Listing
                                </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add permissions')): ?>
                                <a class="nav-sublink <?php echo $__env->yieldContent('nav_permissions_add'); ?>" href="<?php echo e(route('permission_add')); ?>">
                                    <i class="bi bi-plus-lg"></i> Add Permission
                                </a>
                            <?php endif; ?>
                        </div>
                    </details>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view farmers')): ?>
                    <a class="nav-link-custom <?php echo $__env->yieldContent('nav_farmers'); ?>" href="<?php echo e(route('farmers')); ?>">
                        <i class="bi bi-person-workspace"></i> Farmers
                    </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view farmer leaderboard')): ?>
                    <a class="nav-link-custom <?php echo $__env->yieldContent('nav_farmer_leaderboard'); ?>" href="<?php echo e(route('farmer_leaderboard')); ?>">
                        <i class="bi bi-trophy-fill"></i> Farmer Leaderboard
                    </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view customers')): ?>
                    <a class="nav-link-custom <?php echo $__env->yieldContent('nav_customers'); ?>" href="<?php echo e(route('customers')); ?>">
                        <i class="bi bi-person-lines-fill"></i> Customers
                    </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view low stock alerts')): ?>
                    <a class="nav-link-custom <?php echo $__env->yieldContent('nav_low_stock'); ?>" href="<?php echo e(route('low_stock_alerts')); ?>">
                        <i class="bi bi-exclamation-triangle-fill"></i> Low Stock Alerts
                    </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view reviews', 'view announcements', 'view reports'])): ?>
                    <div class="nav-group-label">ENGAGEMENT</div>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view reviews')): ?>
                    <a class="nav-link-custom <?php echo $__env->yieldContent('nav_reviews'); ?>" href="<?php echo e(route('reviews')); ?>">
                        <i class="bi bi-star-fill"></i> Reviews
                    </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view announcements', 'add announcements'])): ?>
                    <details class="nav-dropdown">
                        <summary>
                            <i class="bi bi-megaphone-fill nav-ic"></i> Announcements
                            <i class="bi bi-chevron-down chevron"></i>
                        </summary>
                        <div class="nav-sub">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view announcements')): ?>
                                <a class="nav-sublink <?php echo $__env->yieldContent('nav_announcements_list'); ?>" href="<?php echo e(route('announcements')); ?>">
                                    <i class="bi bi-list-ul"></i> Announcement Listing
                                </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add announcements')): ?>
                                <a class="nav-sublink <?php echo $__env->yieldContent('nav_announcements_add'); ?>" href="<?php echo e(route('announcement_add')); ?>">
                                    <i class="bi bi-plus-lg"></i> Add Announcement
                                </a>
                            <?php endif; ?>
                        </div>
                    </details>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view reports')): ?>
                    <a class="nav-link-custom <?php echo $__env->yieldContent('nav_reports'); ?>" href="<?php echo e(route('reports')); ?>">
                        <i class="bi bi-bar-chart-line-fill"></i> Reports
                    </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view activity log')): ?>
                    <a class="nav-link-custom <?php echo $__env->yieldContent('nav_activity_log'); ?>" href="<?php echo e(route('activity_log')); ?>">
                        <i class="bi bi-clock-history"></i> Activity Log
                    </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view commission tracking')): ?>
                    <a class="nav-link-custom <?php echo $__env->yieldContent('nav_commission_tracking'); ?>" href="<?php echo e(route('commission_tracking')); ?>">
                        <i class="bi bi-percent"></i> Commission Tracking
                    </a>
                <?php endif; ?>

            </nav>

            <div class="sidebar-footer">
                <div class="user-row">
                    <div class="user-avatar"> <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?></div>
                    <div class="user-meta">
                        <div class="user-name"> <?php echo e(auth()->user()->name); ?></div>
                    </div>
                </div>
            </div>
        </aside>
        <div class="sidebar-overlay"></div>

        <div class="content">
            <header class="topbar header">
                <div class="topbar-left">
                    <button class="burger-btn"><i class="bi bi-list"></i></button>
                    <div>
                        <div class="page-title"><?php echo $__env->yieldContent('page_title', 'Dashboard'); ?></div>
                        <div class="breadcrumb-mini">
                            <span>MarketLink</span> <i class="bi bi-chevron-right" style="font-size:10px"></i>
                            <span><?php echo $__env->yieldContent('page_title', 'Dashboard'); ?></span>
                        </div>
                    </div>
                </div>
                <div class="topbar-right">
                    <div id="chnage_theme" class="icon-btn"><i class="bi bi-sun-fill"></i></div>
                    <div class="user-dropdown">
                        <button class="user-avatar" type="button" onclick="toggleUserMenu()">
                            <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                        </button>


                        <div class="user-menu" id="userMenu">
                            <div class="user-menu-name">
                                <?php echo e(auth()->user()->name); ?>

                            </div>

                            <div class="user-menu-email">
                                <?php echo e(auth()->user()->email); ?>

                            </div>

                            <div class="menu-divider"></div>

                            <form action="<?php echo e(route('logout')); ?>" method="POST">
                                <?php echo csrf_field(); ?>

                                <button type="submit" class="logout-btn">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Logout
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </header>
            <div class="page-body">
                <?php echo $__env->yieldContent('body'); ?>
            </div>
        </div>

        <script src="<?php echo e(asset('Assets/Dashboard_Asset/js/theme-change.js')); ?>"></script>
        <script src="<?php echo e(asset('Assets/Dashboard_Asset/js/ajax-search.js')); ?>"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
        <script>
            function toggleUserMenu() {
                document.getElementById('userMenu').classList.toggle('show');
            }
            var burger = document.querySelector('.burger-btn');
            var sidebar = document.getElementById('sidebar');
            var overlay = document.querySelector('.sidebar-overlay');
            if (burger) {
                burger.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                });
            }
            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }
            document.querySelectorAll('.nav-sublink.active, .nav-link-custom.active').forEach(function(el) {
                var d = el.closest('details.nav-dropdown');
                if (d) d.setAttribute('open', '');
            });
        </script>
        <?php echo $__env->yieldPushContent('scripts'); ?>
    </div>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\Marketlink\resources\views/Dashboard/_master.blade.php ENDPATH**/ ?>