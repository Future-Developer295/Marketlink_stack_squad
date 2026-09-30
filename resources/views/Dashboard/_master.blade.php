<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>MarketLink — @yield('page_title', 'Dashboard')</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet" />

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('Assets/Dashboard_Asset/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('Assets/Dashboard_Asset/css/emerald-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('Assets/Dashboard_Asset/css/responsive.css') }}">

    <link rel="stylesheet" href="{{ asset('Assets/Shared/ajax.css') }}">

    @stack('styles')
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

                <span class="brand-name">
                    Market <span>Link</span>
                </span>

            </div>


            <nav class="sidebar-nav">

                <div class="nav-group-label">MAIN</div>

                @can('view dashboard')
                    <a class="nav-link-custom @yield('nav_dashboard_farmer')" href="{{ route('farmer_dashboard') }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        Farmer Dashboard
                    </a>
                @endcan


                @can('view admin dashboard')
                    <a class="nav-link-custom @yield('nav_dashboard_admin')" href="{{ route('admin_dashboard') }}">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                        Admin Dashboard
                    </a>
                @endcan


                @can('view profile')
                    <div class="nav-group-label">ACCOUNT</div>

                    <a class="nav-link-custom @yield('nav_profile')" href="{{ route('my_profile') }}">
                        <i class="bi bi-person-badge-fill"></i>
                        My Profile
                    </a>
                @endcan


                @can('join markets')

                    <details class="nav-dropdown">

                        <summary>
                            <i class="bi bi-shop-window nav-ic"></i>
                            My Markets
                            <i class="bi bi-chevron-down chevron"></i>
                        </summary>

                        <div class="nav-sub">

                            @can('view markets')
                                <a class="nav-sublink @yield('nav_my_markets_list')" href="{{ route('my_markets') }}">
                                    <i class="bi bi-list-ul"></i>
                                    Market Listing
                                </a>
                            @endcan

                            @can('join markets')
                                <a class="nav-sublink @yield('nav_my_markets_add')" href="{{ route('my_markets_join') }}">
                                    <i class="bi bi-plus-lg"></i>
                                    Join Market
                                </a>
                            @endcan

                        </div>

                    </details>

                @endcan


                @canany(['view categories', 'add categories', 'view products', 'add products'])
                    <div class="nav-group-label">CATALOG</div>
                @endcanany


                @canany(['view categories', 'add categories'])

                    <details class="nav-dropdown">

                        <summary>
                            <i class="bi bi-tags-fill nav-ic"></i>
                            Categories
                            <i class="bi bi-chevron-down chevron"></i>
                        </summary>

                        <div class="nav-sub">

                            @can('view categories')
                                <a class="nav-sublink @yield('nav_categories_list')" href="{{ route('categories') }}">
                                    <i class="bi bi-list-ul"></i>
                                    Category Listing
                                </a>
                            @endcan

                            @can('add categories')
                                <a class="nav-sublink @yield('nav_categories_add')" href="{{ route('category_add') }}">
                                    <i class="bi bi-plus-lg"></i>
                                    Add Category
                                </a>
                            @endcan

                        </div>

                    </details>

                @endcanany


                @canany(['view products', 'add products'])

                    <details class="nav-dropdown">

                        <summary>
                            <i class="bi bi-box-seam-fill nav-ic"></i>
                            Products
                            <i class="bi bi-chevron-down chevron"></i>
                        </summary>

                        <div class="nav-sub">

                            @can('view products')
                                <a class="nav-sublink @yield('nav_products_list')" href="{{ route('products') }}">
                                    <i class="bi bi-list-ul"></i>
                                    Product Listing
                                </a>
                            @endcan

                            @can('add products')
                                <a class="nav-sublink @yield('nav_products_add')" href="{{ route('product_add') }}">
                                    <i class="bi bi-plus-lg"></i>
                                    Add Product
                                </a>
                            @endcan

                        </div>

                    </details>

                @endcanany


                @canany(['view weekly stock', 'add weekly stock', 'view orders', 'view pickup slots', 'add pickup
                    slots', 'view markets', 'add markets'])
                    <div class="nav-group-label">OPERATIONS</div>
                @endcanany


                @canany(['view weekly stock', 'add weekly stock'])

                    <details class="nav-dropdown">

                        <summary>
                            <i class="bi bi-calendar2-week-fill nav-ic"></i>
                            Weekly Stock
                            <i class="bi bi-chevron-down chevron"></i>
                        </summary>

                        <div class="nav-sub">

                            @can('view weekly stock')
                                <a class="nav-sublink @yield('nav_stock_list')" href="{{ route('stock') }}">
                                    <i class="bi bi-list-ul"></i>
                                    Stock Listing
                                </a>
                            @endcan

                            @can('add weekly stock')
                                <a class="nav-sublink @yield('nav_stock_add')" href="{{ route('stock_add') }}">
                                    <i class="bi bi-plus-lg"></i>
                                    Add Schedule
                                </a>
                            @endcan

                        </div>

                    </details>

                @endcanany


                @can('view orders')
                    <a class="nav-link-custom @yield('nav_orders')" href="{{ route('orders') }}">
                        <i class="bi bi-cart-check-fill"></i>
                        Orders
                    </a>
                @endcan


                @canany(['view pickup slots', 'add pickup slots'])

                    <details class="nav-dropdown">

                        <summary>
                            <i class="bi bi-clock-history nav-ic"></i>
                            Pickup Slots
                            <i class="bi bi-chevron-down chevron"></i>
                        </summary>

                        <div class="nav-sub">

                            @can('view pickup slots')
                                <a class="nav-sublink @yield('nav_slots_list')" href="{{ route('slots') }}">
                                    <i class="bi bi-list-ul"></i>
                                    Slot Listing
                                </a>
                            @endcan

                            @can('add pickup slots')
                                <a class="nav-sublink @yield('nav_slots_add')" href="{{ route('slot_add') }}">
                                    <i class="bi bi-plus-lg"></i>
                                    Add Slot
                                </a>
                            @endcan

                        </div>

                    </details>

                @endcanany


                @canany(['add markets', 'edit markets', 'delete markets'])

                    <details class="nav-dropdown">

                        <summary>
                            <i class="bi bi-shop-window nav-ic"></i>
                            Markets
                            <i class="bi bi-chevron-down chevron"></i>
                        </summary>

                        <div class="nav-sub">

                            @can('view markets')
                                <a class="nav-sublink @yield('nav_markets_list')" href="{{ route('markets') }}">
                                    <i class="bi bi-list-ul"></i>
                                    Market Listing
                                </a>
                            @endcan

                            @can('add markets')
                                <a class="nav-sublink @yield('nav_markets_add')" href="{{ route('market_add') }}">
                                    <i class="bi bi-plus-lg"></i>
                                    Add Market
                                </a>
                            @endcan

                        </div>

                    </details>

                @endcanany


                @canany(['view users', 'add users', 'view roles', 'add roles', 'view permissions', 'add permissions',
                    'view farmers', 'view customers'])
                    <div class="nav-group-label">PEOPLE</div>
                @endcanany


                @canany(['view users', 'add users'])

                    <details class="nav-dropdown">

                        <summary>
                            <i class="bi bi-people-fill nav-ic"></i>
                            Users
                            <i class="bi bi-chevron-down chevron"></i>
                        </summary>

                        <div class="nav-sub">

                            @can('view users')
                                <a class="nav-sublink @yield('nav_users_list')" href="{{ route('users') }}">
                                    <i class="bi bi-list-ul"></i>
                                    User Listing
                                </a>
                            @endcan

                            @can('add users')
                                <a class="nav-sublink @yield('nav_users_add')" href="{{ route('user_add') }}">
                                    <i class="bi bi-plus-lg"></i>
                                    Add User
                                </a>
                            @endcan

                        </div>

                    </details>

                @endcanany


                @canany(['view roles', 'add roles'])

                    <details class="nav-dropdown">

                        <summary>
                            <i class="bi bi-person-badge-fill nav-ic"></i>
                            Roles
                            <i class="bi bi-chevron-down chevron"></i>
                        </summary>

                        <div class="nav-sub">

                            @can('view roles')
                                <a class="nav-sublink @yield('nav_roles_list')" href="{{ route('roles') }}">
                                    <i class="bi bi-list-ul"></i>
                                    Role Listing
                                </a>
                            @endcan

                            @can('add roles')
                                <a class="nav-sublink @yield('nav_roles_add')" href="{{ route('role_add') }}">
                                    <i class="bi bi-plus-lg"></i>
                                    Add Role
                                </a>
                            @endcan

                        </div>

                    </details>

                @endcanany


                @canany(['view permissions', 'add permissions'])

                    <details class="nav-dropdown">

                        <summary>
                            <i class="bi bi-key-fill nav-ic"></i>
                            Permissions
                            <i class="bi bi-chevron-down chevron"></i>
                        </summary>

                        <div class="nav-sub">

                            @can('view permissions')
                                <a class="nav-sublink @yield('nav_permissions_list')" href="{{ route('permissions') }}">
                                    <i class="bi bi-list-ul"></i>
                                    Permission Listing
                                </a>
                            @endcan

                            @can('add permissions')
                                <a class="nav-sublink @yield('nav_permissions_add')" href="{{ route('permission_add') }}">
                                    <i class="bi bi-plus-lg"></i>
                                    Add Permission
                                </a>
                            @endcan

                        </div>

                    </details>

                @endcanany


                @can('view farmers')
                    <a class="nav-link-custom @yield('nav_farmers')" href="{{ route('farmers') }}">
                        <i class="bi bi-person-workspace"></i>
                        Farmers
                    </a>
                @endcan


                @can('view customers')
                    <a class="nav-link-custom @yield('nav_customers')" href="{{ route('customers') }}">
                        <i class="bi bi-person-lines-fill"></i>
                        Customers
                    </a>
                @endcan


                @canany(['view reviews', 'view contact messages', 'view announcements', 'add announcements', 'view reports'])
                    <div class="nav-group-label">ENGAGEMENT</div>
                @endcanany


                @can('view reviews')
                    <a class="nav-link-custom @yield('nav_reviews')" href="{{ route('reviews') }}">
                        <i class="bi bi-star-fill"></i>
                        Reviews
                    </a>
                @endcan


                @can('view contact messages')
                    @php($unreadContact = rescue(fn () => \App\Models\ContactMessage::whereNull('read_at')->count(), 0, false))
                    <a class="nav-link-custom @yield('nav_contact_messages')" href="{{ route('contact_messages') }}">
                        <i class="bi bi-envelope-fill"></i>
                        Contact Messages
                        @if ($unreadContact > 0)
                            <span class="badge bg-success" style="margin-left:auto;">{{ $unreadContact }}</span>
                        @endif
                    </a>
                @endcan


                @canany(['view announcements', 'add announcements'])

                    <details class="nav-dropdown">

                        <summary>
                            <i class="bi bi-megaphone-fill nav-ic"></i>
                            Announcements
                            <i class="bi bi-chevron-down chevron"></i>
                        </summary>

                        <div class="nav-sub">

                            @can('view announcements')
                                <a class="nav-sublink @yield('nav_announcements_list')" href="{{ route('announcements') }}">
                                    <i class="bi bi-list-ul"></i>
                                    Announcement Listing
                                </a>
                            @endcan

                            @can('add announcements')
                                <a class="nav-sublink @yield('nav_announcements_add')" href="{{ route('announcement_add') }}">
                                    <i class="bi bi-plus-lg"></i>
                                    Add Announcement
                                </a>
                            @endcan

                        </div>

                    </details>

                @endcanany


                @can('view reports')
                    <a class="nav-link-custom @yield('nav_reports')" href="{{ route('reports') }}">
                        <i class="bi bi-bar-chart-line-fill"></i>
                        Reports
                    </a>
                @endcan

            </nav>


            <div class="sidebar-footer">

                <div class="user-row">

                    <div class="user-avatar">@include('Dashboard._avatar')</div>

                    <div class="user-meta">

                        <div class="user-name">
                            {{ auth()->user()->name }}
                        </div>

                    </div>

                </div>

            </div>

        </aside>


        <div class="sidebar-overlay"></div>


        <div class="content">

            <header class="topbar header">

                <div class="topbar-left">

                    <button class="burger-btn">
                        <i class="bi bi-list"></i>
                    </button>

                    <div>

                        <div class="page-title">
                            @yield('page_title', 'Dashboard')
                        </div>

                        <div class="breadcrumb-mini">

                            <span>MarketLink</span>

                            <i class="bi bi-chevron-right" style="font-size:10px"></i>

                            <span>
                                @yield('page_title', 'Dashboard')
                            </span>

                        </div>

                    </div>

                </div>


                <div class="topbar-right">

                    <div id="chnage_theme" class="icon-btn">
                        <i class="bi bi-sun-fill"></i>
                    </div>


                    <div class="user-dropdown">

                        <button class="user-avatar" type="button" onclick="toggleUserMenu()">@include('Dashboard._avatar')</button>


                        <div class="user-menu" id="userMenu">

                            <div class="user-menu-name">
                                {{ auth()->user()->name }}
                            </div>

                            <div class="user-menu-email">
                                {{ auth()->user()->email }}
                            </div>

                            <div class="menu-divider"></div>


                            <form action="{{ route('logout') }}" method="POST">

                                @csrf

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

                @yield('body')

            </div>

        </div>


        <script src="{{ asset('Assets/Dashboard_Asset/js/theme-change.js') }}"></script>
        <script src="{{ asset('Assets/Dashboard_Asset/js/responsive.js') }}"></script>
        <script src="{{ asset('Assets/Shared/ajax.js') }}"></script>

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

            document
                .querySelectorAll('.nav-sublink.active, .nav-link-custom.active')
                .forEach(function(el) {

                    var d = el.closest('details.nav-dropdown');

                    if (d) {
                        d.setAttribute('open', '');
                    }

                });
        </script>

        @stack('scripts')

    </div>

</body>

</html>
