

<?php $__env->startSection('page_title', 'Home'); ?>

<?php $__env->startSection('body'); ?>

<section class="ml-section pb-4">
    <div class="ml-container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="ml-eyebrow"><i class="fa-solid fa-leaf"></i> Local Farmers &middot; Fresh Produce &middot; Better Communities</span>
                <h1 class="display-5">Fresh Local Produce. <span class="text-success">Reserved Before Market Day.</span></h1>
                <p class="text-muted mt-3 fs-5">Discover nearby farmers, check weekly stock and reserve your favourites for convenient market pickup. No delivery delays, 100% farm fresh.</p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a href="<?php echo e(url('/markets')); ?>" class="btn ml-btn-primary"><i class="fa-solid fa-store"></i> Explore Markets</a>
                    <a href="<?php echo e(url('/register')); ?>" class="btn ml-btn-secondary"><i class="fa-solid fa-tractor"></i> Become a Farmer</a>
                </div>
                <div class="d-flex flex-wrap gap-4 mt-4 text-muted small">
                    <span><i class="fa-solid fa-check text-success"></i> 100% Verified Local Growers</span>
                    <span><i class="fa-solid fa-check text-success"></i> Direct Market Stalls</span>
                    <span><i class="fa-solid fa-check text-success"></i> Pay Stallholder at Pickup</span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="<?php echo e(asset('Assets/Website_Asset/home_img/ChatGPT Image Sep 27, 2026, 12_05_15 AM.png')); ?>" class="rounded-4 shadow" alt="Local farmer with fresh vegetables">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($farmers->isNotEmpty()): ?>
                    <?php ($spotlight = $farmers->first()); ?>
                    <div class="ml-card position-absolute bottom-0 start-0 m-3 p-3 d-flex align-items-center gap-3">
                        <div class="ml-avatar d-flex align-items-center justify-content-center bg-light">
                            <i class="fa-solid fa-tractor text-success"></i>
                        </div>
                        <div>
                            <strong class="d-block"><?php echo e($spotlight->stall_name ?? $spotlight->business_name); ?></strong>
                            <span class="small text-muted"><?php echo e($spotlight->user->name ?? 'Local Farmer'); ?> &middot; <?php echo e($spotlight->city); ?></span>
                        </div>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-5">
            <div class="col-6 col-lg-3"><div class="ml-stat"><strong><?php echo e($stats['markets']); ?>+</strong><span>Local Markets</span></div></div>
            <div class="col-6 col-lg-3"><div class="ml-stat"><strong><?php echo e($stats['farmers']); ?>+</strong><span>Active Farmers</span></div></div>
            <div class="col-6 col-lg-3"><div class="ml-stat"><strong><?php echo e($stats['products']); ?>+</strong><span>Fresh Products</span></div></div>
            <div class="col-6 col-lg-3"><div class="ml-stat"><strong><?php echo e($stats['orders']); ?>+</strong><span>Successful Pickups</span></div></div>
        </div>
    </div>
</section>

<section class="ml-section ml-section--muted">
    <div class="ml-container">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 ml-heading-block mb-4">
            <div>
                <span class="ml-eyebrow">Weekend Community Stalls</span>
                <h2>Find Local Markets Near You</h2>
                <p>Discover nearby markets, explore registered local farmers and reserve freshly picked crops before market stalls open.</p>
            </div>
            <a href="<?php echo e(url('/markets')); ?>" class="ml-link-more">View All Market Hubs <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="row g-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $markets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $market): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-md-4">
                <div class="ml-media-card">
                    <div class="ml-media-card__image">
                        <img src="https://images.unsplash.com/photo-1488459716781-31db52582fe9?auto=format&fit=crop&w=700&q=60" alt="<?php echo e($market->name); ?>">
                    </div>
                    <div class="ml-media-card__body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5><?php echo e($market->name); ?></h5>
                        </div>
                        <span class="text-muted small"><i class="fa-solid fa-location-dot"></i> <?php echo e($market->city); ?>, <?php echo e($market->state); ?></span>
                        <span class="text-muted small"><i class="fa-solid fa-users"></i> <?php echo e($market->market_farmers_count); ?> Registered Farmers</span>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="small text-muted"><?php echo e($market->operating_days); ?></span>
                            <a href="<?php echo e(url('/markets/'.$market->id)); ?>" class="ml-btn-link">View Market <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <?php echo $__env->make('Website.Partials.empty-state', [
                    'icon' => 'fa-store-slash',
                    'title' => 'No Markets Yet',
                    'message' => 'Markets will appear here as soon as they are added.',
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>

<section class="ml-section">
    <div class="ml-container">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 ml-heading-block mb-4">
            <div>
                <span class="ml-eyebrow">Provenance &amp; Growers</span>
                <h2>Fresh From Local Farmers</h2>
                <p>Meet the regional growers who cultivate your seasonal food, inspect their harvest schedules, and support regenerative family agriculture.</p>
            </div>
            <a href="<?php echo e(url('/farmers')); ?>" class="ml-link-more">Explore All Farmers <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="row g-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $farmers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $farmer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-md-4">
                <div class="ml-media-card">
                    <div class="ml-media-card__image">
                        <img src="https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=700&q=60" alt="<?php echo e($farmer->stall_name); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                            <form action="<?php echo e(route('favorite.toggle', ['type' => 'farmer', 'id' => $farmer->id])); ?>" method="POST" class="ml-image-fav">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="ml-favorite-btn" aria-label="<?php echo e($favoritedFarmerIds->contains($farmer->id) ? 'Remove from favorites' : 'Save farmer'); ?>">
                                    <i class="fa-<?php echo e($favoritedFarmerIds->contains($farmer->id) ? 'solid' : 'regular'); ?> fa-heart"></i>
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="ml-favorite-btn ml-image-fav" aria-label="Save farmer"><i class="fa-regular fa-heart"></i></a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="ml-media-card__body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5><?php echo e($farmer->stall_name ?? $farmer->business_name); ?></h5>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($farmer->reviews_avg_rating): ?>
                            <span class="ml-rating"><i class="fa-solid fa-star"></i> <?php echo e(number_format($farmer->reviews_avg_rating, 1)); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <span class="text-muted small"><i class="fa-solid fa-location-dot"></i> <?php echo e($farmer->city); ?>, <?php echo e($farmer->state); ?></span>
                        <span class="ml-badge ml-badge-mint w-fit align-self-start"><?php echo e($farmer->products_count); ?> Products</span>
                        <a href="<?php echo e(url('/farmers/'.$farmer->id)); ?>" class="btn ml-btn-primary ml-btn-sm mt-2">View Products <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <?php echo $__env->make('Website.Partials.empty-state', [
                    'icon' => 'fa-tractor',
                    'title' => 'No Farmers Yet',
                    'message' => 'Verified farmers will appear here once approved.',
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>

<section class="ml-section ml-section--muted">
    <div class="ml-container">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 ml-heading-block mb-4">
            <div>
                <span class="ml-eyebrow">Seasonal Availability</span>
                <h2>Fresh Produce, Ready to Reserve</h2>
                <p>Browse live weekly harvests direct from local fields. Reserve early to lock in your portion before market morning.</p>
            </div>
            <a href="<?php echo e(url('/products')); ?>" class="ml-link-more">See Full Catalog <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="row g-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-6 col-lg-3">
                <div class="ml-media-card">
                    <div class="ml-media-card__image">
                        <img src="<?php echo e($product->image ? asset('product_images/' . $product->image) : 'https://images.unsplash.com/photo-1592841200221-a6898f307baa?auto=format&fit=crop&w=500&q=60'); ?>" alt="<?php echo e($product->name); ?>">
                        <span class="ml-badge <?php echo e($product->stock_quantity > 5 ? 'ml-badge-mint' : 'ml-badge-warning'); ?> ml-image-tag"><?php echo e($product->stock_quantity > 5 ? 'In Stock' : 'Low Stock ('.$product->stock_quantity.' left)'); ?></span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                            <form action="<?php echo e(route('favorite.toggle', ['type' => 'product', 'id' => $product->id])); ?>" method="POST" class="ml-image-fav">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="ml-favorite-btn" aria-label="<?php echo e($favoritedProductIds->contains($product->id) ? 'Remove from favorites' : 'Save product'); ?>">
                                    <i class="fa-<?php echo e($favoritedProductIds->contains($product->id) ? 'solid' : 'regular'); ?> fa-heart"></i>
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="ml-favorite-btn ml-image-fav" aria-label="Save product"><i class="fa-regular fa-heart"></i></a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="ml-media-card__body">
                        <span class="small text-muted"><?php echo e($product->farmer->stall_name ?? $product->farmer->business_name ?? ''); ?></span>
                        <h6><?php echo e($product->name); ?></h6>
                        <strong class="text-success">Rs. <?php echo e(number_format($product->price, 0)); ?> <span class="fw-normal text-muted small">/ <?php echo e($product->unit); ?></span></strong>
                        <a href="<?php echo e(url('/products/'.$product->id)); ?>" class="text-center small ml-btn-link justify-content-center mt-1">View Product</a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <?php echo $__env->make('Website.Partials.empty-state', [
                    'icon' => 'fa-carrot',
                    'title' => 'No Products Yet',
                    'message' => 'Fresh listings will appear here as farmers publish their weekly stock.',
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>

<section class="ml-section">
    <div class="ml-container">
        <div class="ml-heading-block mx-auto text-center">
            <span class="ml-eyebrow">Effortless Field-to-Stall Process</span>
            <h2>How MarketLink Works</h2>
            <p class="mx-auto">A simple 4-step farmer-to-community reservation system. No couriers or delivery wait times, just fresh harvest packed for you.</p>
        </div>
        <div class="row g-4 text-center">
            <div class="col-6 col-lg-3">
                <div class="ml-step">
                    <div class="ml-step__icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                    <h6>Discover</h6>
                    <p class="text-muted small">Find nearby farmers markets and certified regional growers active in your neighbourhood.</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="ml-step">
                    <div class="ml-step__icon"><i class="fa-solid fa-list-check"></i></div>
                    <h6>Browse</h6>
                    <p class="text-muted small">Explore live weekly product listings and real-time stocks as harvested before market day.</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="ml-step">
                    <div class="ml-step__icon"><i class="fa-solid fa-bookmark"></i></div>
                    <h6>Reserve</h6>
                    <p class="text-muted small">Add seasonal produce to your reservation and select your preferred market day pickup.</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="ml-step">
                    <div class="ml-step__icon"><i class="fa-solid fa-basket-shopping"></i></div>
                    <h6>Pick Up</h6>
                    <p class="text-muted small">Collect your packed box directly from the grower at their stall and pay them seamlessly in person.</p>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <span class="ml-badge ml-badge-mint"><i class="fa-solid fa-circle-check"></i> Market pickup only &middot; Direct payment to farmers at the stall &middot; Zero middleman fees</span>
        </div>
    </div>
</section>

<section class="ml-section ml-section--muted">
    <div class="ml-container">
        <div class="ml-heading-block mb-4">
            <span class="ml-eyebrow">Interactive Geographic Locator</span>
            <h2>Find Markets &amp; Farmers Near You</h2>
            <p>Locate authorized pickup stalls, check market schedules, and map out your next pickup route.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-5">
                <form class="ml-filter-panel mb-3" action="<?php echo e(url('/markets')); ?>" method="GET">
                    <input type="text" name="q" class="form-control" placeholder="Search by area or market name...">
                </form>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $markets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $market): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="ml-card mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?php echo e($market->name); ?></strong>
                        <p class="small text-muted mt-1"><?php echo e($market->market_farmers_count); ?> Active Farmers &middot; <?php echo e($market->start_time); ?> - <?php echo e($market->end_time); ?></p>
                    </div>
                    <a href="<?php echo e(url('/markets/'.$market->id)); ?>" class="ml-btn-link">View</a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted small">No markets to show yet.</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="col-lg-7">
                <div class="ml-map">
                    <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=1200&q=60" alt="Map of markets">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ml-section">
    <div class="ml-container">
        <div class="ml-heading-block mx-auto text-center">
            <span class="ml-eyebrow">Community Impact</span>
            <h2>Why Choose MarketLink?</h2>
            <p class="mx-auto">Eliminating industrial cold storage and food-miles in favor of transparent, nutritious harvest directly from nearby fields.</p>
        </div>
        <div class="row g-4 text-center">
            <div class="col-6 col-lg-3">
                <div class="ml-step__icon mx-auto"><i class="fa-solid fa-handshake"></i></div>
                <h6 class="mt-3">Support Local Farmers</h6>
                <p class="text-muted small">Help local growers connect directly with neighbourhood shoppers and retain more from their harvest.</p>
            </div>
            <div class="col-6 col-lg-3">
                <div class="ml-step__icon mx-auto"><i class="fa-solid fa-carrot"></i></div>
                <h6 class="mt-3">Fresh Local Produce</h6>
                <p class="text-muted small">Enjoy vegetables and fruit harvested just hours prior to market pickup, preserving flavor and nutrients.</p>
            </div>
            <div class="col-6 col-lg-3">
                <div class="ml-step__icon mx-auto"><i class="fa-regular fa-bookmark"></i></div>
                <h6 class="mt-3">Reserve Before Market Day</h6>
                <p class="text-muted small">Guarantee supply of high-demand varieties without offering last-minute sell-outs before arrival.</p>
            </div>
            <div class="col-6 col-lg-3">
                <div class="ml-step__icon mx-auto"><i class="fa-solid fa-store"></i></div>
                <h6 class="mt-3">Convenient Pickup</h6>
                <p class="text-muted small">Skip long lines. Your pre-packaged crate is waiting at the stall with your items on arrival.</p>
            </div>
        </div>
    </div>
</section>

<section class="ml-section">
    <div class="ml-container">
        <div class="ml-cta-banner text-center">
            <span class="ml-badge ml-badge-mint mb-3"><i class="fa-solid fa-leaf"></i> Ready to Shop Local?</span>
            <h2>Ready to Shop Local?</h2>
            <p class="mt-2 mb-4" style="color: rgba(255,255,255,0.85);">Discover fresh seasonal harvest from verified farmers and reserve your weekly favourites before market stalls open.</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="<?php echo e(url('/markets')); ?>" class="btn ml-btn-primary">Explore Markets</a>
                <a href="<?php echo e(url('/register')); ?>" class="btn ml-btn-secondary">Become a Farmer</a>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('Website._master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Marketlink\resources\views/Website/Home/index.blade.php ENDPATH**/ ?>