<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('page_title', 'MarketLink'); ?> | MarketLink</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('Assets/Website_Asset/css/dashboard.css')); ?>" rel="stylesheet">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->is(
            'products*',
            'cart',
            'checkout',
            'pickup-guidelines',
            'markets*',
            'farmers*',
            'about',
            '/',
            'dashboard/customer*')): ?>
        <link
            href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap"
            rel="stylesheet">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->is('products*', 'cart', 'checkout', 'pickup-guidelines', 'markets*', 'farmers*', 'about', '/')): ?>
        <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
        <link href="<?php echo e(asset('Assets/Website_Asset/css/shop.css')); ?>" rel="stylesheet">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->is('dashboard/customer*')): ?>
        <link href="<?php echo e(asset('Assets/Website_Asset/css/customer-dashboard.css')); ?>" rel="stylesheet">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->is('markets*', 'farmers*')): ?>
        <link href="<?php echo e(asset('Assets/Website_Asset/css/discovery.css')); ?>" rel="stylesheet">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php echo $__env->yieldContent('page_styles'); ?>
</head>

<body
    class="<?php echo e(request()->is('products*', 'cart', 'checkout', 'pickup-guidelines', 'markets*', 'farmers*', 'about', '/') ? 'harvest-shop' : ''); ?> <?php echo e(request()->is('dashboard/customer*') ? 'customer-area' : ''); ?>">

    <nav class="navbar navbar-expand-lg ml-navbar">
        <div class="ml-container d-flex align-items-center justify-content-between w-100">
            <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
                <span class="ml-logo-icon"><i class="fa-solid fa-leaf"></i></span>
                MarketLink
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mlNavbar">
                <i class="fa-solid fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="mlNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link ml-nav-link <?php echo e(request()->is('/') ? 'active' : ''); ?>"
                            href="<?php echo e(url('/')); ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link ml-nav-link <?php echo e(request()->is('markets*') ? 'active' : ''); ?>"
                            href="<?php echo e(url('/markets')); ?>">Markets</a></li>
                    <li class="nav-item"><a
                            class="nav-link ml-nav-link <?php echo e(request()->is('farmers*') ? 'active' : ''); ?>"
                            href="<?php echo e(url('/farmers')); ?>">Farmers</a></li>
                    <li class="nav-item"><a
                            class="nav-link ml-nav-link <?php echo e(request()->is('products*') ? 'active' : ''); ?>"
                            href="<?php echo e(url('/products')); ?>">Products</a></li>
                    <li class="nav-item"><a class="nav-link ml-nav-link <?php echo e(request()->is('about*') ? 'active' : ''); ?>"
                            href="<?php echo e(url('/about')); ?>">About</a></li>
                    <li class="nav-item"><a
                            class="nav-link ml-nav-link <?php echo e(request()->is('contact*') ? 'active' : ''); ?>"
                            href="<?php echo e(url('/contact')); ?>">Contact</a></li>
                </ul>

                <div class="ml-navbar-actions">
                    <a class="ml-icon-btn" href="<?php echo e(url('/products') . '#harvest'); ?>" aria-label="Search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </a>

                    <a href="<?php echo e(url('/cart')); ?>" class="ml-icon-btn" aria-label="Cart">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <?php ($cartCount = collect(session('cart', []))->sum()); ?>
                        <span class="ml-cart-badge" data-cart-count><?php echo e($cartCount); ?></span>
                    </a>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                        <div class="dropdown d-inline-block">
                            <button class="btn p-0 border-0 bg-transparent shadow-none dropdown-toggle-no-caret"
                                type="button" id="userMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="ml-avatar-circle">
                                    <i class="fa-solid fa-user"></i>
                                </span>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end ml-dropdown-menu" aria-labelledby="userMenuDropdown">
                                <!-- User Profile Header -->
                                <li class="ml-dropdown-header">
                                    <span class="user-name"><?php echo e(auth()->user()->name); ?></span>
                                    <span class="user-role">Customer Account</span>
                                </li>

                                <li>
                                    <hr class="ml-dropdown-divider">
                                </li>

                                <!-- Links -->
                                <li>
                                    <a class="dropdown-item ml-dropdown-item" href="<?php echo e(route('customer_dashboard')); ?>">
                                        <i class="fa-solid fa-gauge"></i> Dashboard
                                    </a>
                                </li>

                                <li>
                                    <form action="<?php echo e(url('/logout')); ?>" method="POST" class="m-0">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="dropdown-item ml-dropdown-item text-danger">
                                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo e(url('/login')); ?>" class="ml-nav-link d-none d-lg-inline me-2">Login</a>
                        <a href="<?php echo e(url('/register')); ?>" class="btn ml-btn-primary ml-btn-sm">Register</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </nav>



    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->is('products*', 'cart', 'checkout', 'pickup-guidelines', 'markets*', 'farmers*', 'about', '/')): ?>
        <div class="shop-mobile-head"><a href="<?php echo e(url('/products')); ?>"><i class="fa-solid fa-leaf"></i>
                MarketLink<span>THE LOCAL EDIT</span></a><a class="ml-icon-btn" href="<?php echo e(url('/cart')); ?>"
                aria-label="View basket"><i class="fa-solid fa-bag-shopping"></i><span class="ml-cart-badge"
                    data-cart-count><?php echo e(collect(session('cart', []))->sum()); ?></span></a></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <main>
        <?php echo $__env->yieldContent('body'); ?>
    </main>

    <footer class="ml-footer">
        <div class="ml-container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <a class="navbar-brand mb-3 d-inline-flex" href="<?php echo e(url('/')); ?>">
                        <span class="ml-logo-icon"><i class="fa-solid fa-leaf"></i></span>
                        MarketLink
                    </a>
                    <p class="text-muted small mt-2">Connecting local farmers, fresh seasonal produce, and
                        health-conscious communities through direct field-to-stall pre-orders.</p>
                    <span class="ml-badge ml-badge-mint mt-2"><i class="fa-solid fa-shield-halved"></i> 100% Pre-Order
                        &amp; Market Pickup</span>
                </div>
                <div class="col-6 col-lg-2">
                    <h6>Explore</h6>
                    <a href="<?php echo e(url('/markets')); ?>">Market Hubs</a>
                    <a href="<?php echo e(url('/farmers')); ?>">Verified Growers</a>
                    <a href="<?php echo e(url('/products')); ?>">Seasonal Harvest</a>
                </div>
                <div class="col-6 col-lg-2">
                    <h6>Company</h6>
                    <a href="<?php echo e(url('/about')); ?>">Our Mission</a>
                    <a href="<?php echo e(url('/contact')); ?>">Contact Stalls</a>
                    <a href="<?php echo e(url('/pickup-guidelines')); ?>">Pickup Guidelines</a>
                </div>
                <div class="col-6 col-lg-2">
                    <h6>For Farmers</h6>
                    <a href="<?php echo e(url('/register')); ?>">Become a Farmer</a>
                    <a href="<?php echo e(url('/login')); ?>">Stallholder Sign In</a>
                    <a href="<?php echo e(url('/farmers')); ?>">Harvest Calendar</a>
                </div>
            </div>
            <div class="ml-footer-bottom">
                <span>&copy; <?php echo e(date('Y')); ?> MarketLink Marketplace. Fresh community provenance.</span>
                <div class="d-flex gap-3">
                    <a href="<?php echo e(url('/contact')); ?>">Contact &amp; Support</a>
                    <a href="<?php echo e(url('/pickup-guidelines')); ?>">Pickup Guide</a>
                </div>
            </div>
        </div>
    </footer>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="<?php echo e(asset('Assets/Website_Asset/js/website.js')); ?>"></script>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->is('products*', 'cart', 'checkout', 'pickup-guidelines', 'markets*', 'farmers*', 'about', '/')): ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://unpkg.com/lenis@1.1.20/dist/lenis.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script src="<?php echo e(asset('Assets/Website_Asset/js/shop.js')); ?>"></script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->is('dashboard/customer*')): ?>
    <script src="<?php echo e(asset('Assets/Website_Asset/js/customer-dashboard.js')); ?>" defer></script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->is('markets*', 'farmers*')): ?>
    <script src="<?php echo e(asset('Assets/Website_Asset/js/discovery.js')); ?>" defer></script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php echo $__env->yieldContent('page_scripts'); ?>

<?php echo $__env->yieldPushContent('scripts'); ?>

</body>
</html><?php /**PATH C:\xampp\htdocs\Marketlink\resources\views/Website/_master.blade.php ENDPATH**/ ?>