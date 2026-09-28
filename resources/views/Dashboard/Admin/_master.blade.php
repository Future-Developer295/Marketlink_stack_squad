<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MarketLink — Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('Assets/Dashboard_Asset/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('Assets/Dashboard_Asset/css/responsive.css') }}">
</head>

<body>
    <div class="shell">

        <aside class="sidebar theme-admin" id="sidebar">
            <div class="sidebar-brand">
                <div class="brand-mark">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                    </svg>
                </div>
                <span class="brand-name">Market <span>Link</span></span>
            </div>
            <div class="nav-group-label" style="padding:0 18px 4px">Admin Panel</div>

            <nav class="sidebar-nav">
                <a class="nav-link-custom @yield('nav_dashboard')" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>

                <a class="nav-link-custom @yield('nav_users')" href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people-fill"></i> Users
                </a>

                <a class="nav-link-custom @yield('nav_farmers')" href="{{ route('admin.farmers.index') }}">
                    <i class="bi bi-person-workspace"></i> Farmers
                </a>

                <a class="nav-link-custom @yield('nav_customers')" href="{{ route('admin.customers.index') }}">
                    <i class="bi bi-person-lines-fill"></i> Customers
                </a>

                <a class="nav-link-custom @yield('nav_markets')" href="{{ route('admin.markets.index') }}">
                    <i class="bi bi-shop-window"></i> Markets
                </a>

                <a class="nav-link-custom @yield('nav_categories')" href="{{ route('admin.categories.index') }}">
                    <i class="bi bi-tags-fill"></i> Categories
                </a>

                <a class="nav-link-custom @yield('nav_products')" href="{{ route('admin.products.index') }}">
                    <i class="bi bi-box-seam-fill"></i> Products
                </a>

                <a class="nav-link-custom @yield('nav_reviews')" href="{{ route('admin.reviews.index') }}">
                    <i class="bi bi-star-fill"></i> Reviews
                </a>

                <a class="nav-link-custom @yield('nav_reports')" href="{{ route('admin.reports.index') }}">
                    <i class="bi bi-bar-chart-line-fill"></i> Reports
                </a>

                <a class="nav-link-custom @yield('nav_announcements')" href="{{ route('admin.announcements.index') }}">
                    <i class="bi bi-megaphone-fill"></i> Announcements
                </a>

            </nav>

            <div class="sidebar-footer">
                <div class="user-row">
                    <div class="user-avatar">A</div>
                    <div class="user-meta">
                                                <div class="user-name">Admin Panel</div>
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
                        <div class="page-title">@yield('page_title', 'Admin Dashboard')</div>
                        <div class="breadcrumb-mini">
                            <span>MarketLink</span> <i class="bi bi-chevron-right" style="font-size:10px"></i> <span>@yield('page_title', 'Admin Dashboard')</span>
                        </div>
                    </div>
                </div>
                <div class="topbar-right">
                    <div id="chnage_theme" class="icon-btn"><i class="bi bi-sun-fill"></i></div>
                    <div class="user-dropdown">
                        <button class="user-avatar" type="button" onclick="toggleUserMenu()">
                                                        A
                        </button>

                        <div class="user-menu" id="userMenu">
                            <div class="user-menu-name">
                                                                Admin
                            </div>
                            <div class="user-menu-email">
                                                                Admin@marketlink.test
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
        <script>
            function toggleUserMenu() {
                document.getElementById('userMenu').classList.toggle('show');
            }
            var burger = document.querySelector('.burger-btn');
            var sidebar = document.getElementById('sidebar');
            var overlay = document.querySelector('.sidebar-overlay');
            if (burger) {
                burger.addEventListener('click', function () {
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                });
            }
            if (overlay) {
                overlay.addEventListener('click', function () {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }
        </script>
    </div>
</body>

</html>
