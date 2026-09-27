<?php $__env->startSection('page_title', $market->name); ?>

<?php $__env->startSection('body'); ?>

    <div class="ml-container">
        <nav class="ml-breadcrumb"><a href="<?php echo e(url('/')); ?>">Home</a> / <a href="<?php echo e(url('/markets')); ?>">Markets</a> / <span
                class="active"><?php echo e($market->name); ?></span></nav>
    </div>

    <section class="pb-4">
        <div class="ml-container">
            <div class="position-relative rounded-4 overflow-hidden" style="height:340px;">
                <img src="https://images.unsplash.com/photo-1488459716781-31db52582fe9?auto=format&fit=crop&w=1400&q=60"
                    class="w-100 h-100" style="object-fit:cover;" alt="<?php echo e($market->name); ?>">
                <div class="position-absolute bottom-0 start-0 p-4 text-white"
                    style="background:linear-gradient(0deg, rgba(15,61,44,0.75), transparent); width:100%;">
                    <span class="ml-badge ml-badge-white mb-2"><i class="fa-solid fa-circle-check text-success"></i>
                        Community Market</span>
                    <h1 class="text-white"><?php echo e($market->name); ?></h1>
                    <p class="mb-0" style="max-width:640px;"><?php echo e($market->address); ?>, <?php echo e($market->city); ?>,
                        <?php echo e($market->state); ?></p>
                    <div class="d-flex flex-wrap gap-3 mt-3">
                        <a href="#reserve" class="btn ml-btn-primary">Reserve at This Market <i
                                class="fa-solid fa-arrow-right"></i></a>
                        <a href="#directions" class="btn ml-btn-secondary" style="background:rgba(255,255,255,0.95);">Get
                            Directions</a>
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-6 col-lg-3">
                    <div class="ml-card"><span class="small text-muted d-block">Market
                            Address</span><strong><?php echo e($market->city); ?>, <?php echo e($market->state); ?></strong></div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="ml-card"><span class="small text-muted d-block">Weekly
                            Schedule</span><strong><?php echo e($market->operating_days); ?></strong></div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="ml-card"><span class="small text-muted d-block">Stall
                            Hours</span><strong><?php echo e($market->start_time); ?> - <?php echo e($market->end_time); ?></strong></div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="ml-card"><span class="small text-muted d-block">Stallholder
                            Network</span><strong><?php echo e($market->market_farmers_count); ?> Registered Farmers</strong></div>
                </div>
            </div>
        </div>
    </section>

    <section class="ml-section pt-2">
        <div class="ml-container">
            <div class="row g-4">
                <div class="col-lg-8">
                    <span class="ml-eyebrow">Provenance &amp; Hub</span>
                    <h2>About <?php echo e($market->name); ?></h2>
                    <p class="text-muted mt-3">Discover fresh seasonal produce from local farmers and community growers at
                        <?php echo e($market->name); ?>. Browse live weekly harvests, reserve directly from participating growers, and
                        pick up your packed crates from the stall each market morning.</p>
                    <img src="https://images.unsplash.com/photo-1488459716781-31db52582fe9?auto=format&fit=crop&w=1000&q=60"
                        class="rounded-4 mt-4 w-100" style="max-height:320px; object-fit:cover;" alt="Market pavilion">
                </div>
                <div class="col-lg-4">
                    <div class="ml-card" id="reserve">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <strong>Market Quick Info</strong>
                        </div>
                        <ul class="list-unstyled small text-muted d-flex flex-column gap-2 mb-3">
                            <li class="d-flex justify-content-between"><span>Operating Days</span><strong
                                    class="text-dark"><?php echo e($market->operating_days); ?></strong></li>
                            <li class="d-flex justify-content-between"><span>Stall Hours</span><strong
                                    class="text-dark"><?php echo e($market->start_time); ?> - <?php echo e($market->end_time); ?></strong></li>
                            <li class="d-flex justify-content-between"><span>Pickup Location</span><strong
                                    class="text-dark"><?php echo e($market->address); ?></strong></li>
                            <li class="d-flex justify-content-between"><span>Participating Stalls</span><strong
                                    class="text-dark"><?php echo e($market->market_farmers_count); ?></strong></li>
                        </ul>
                        <a href="<?php echo e(url('/products')); ?>" class="btn ml-btn-primary ml-btn-block mb-2"><i
                                class="fa-solid fa-bookmark"></i> Reserve Products Now</a>
                        <a href="#directions" class="btn ml-btn-secondary ml-btn-block"><i class="fa-solid fa-map"></i> Get
                            Directions &amp; Map</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ml-section ml-section--muted">
        <div class="ml-container">
            <div class="d-flex justify-content-between align-items-center ml-heading-block mb-4">
                <div>
                    <span class="ml-eyebrow">Cultivators</span>
                    <h2>Farmers at This Market</h2>
                </div>
                <a href="<?php echo e(url('/farmers')); ?>" class="ml-link-more">View All Growers <i
                        class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="row g-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $farmers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $farmer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-md-4">
                        <div class="ml-card d-flex flex-column align-items-center text-center">
                            <div class="ml-avatar-lg mb-3 d-flex align-items-center justify-content-center bg-light">
                                <i class="fa-solid fa-tractor text-success fs-3"></i>
                            </div>
                            <strong><?php echo e($farmer->user->name ?? $farmer->stall_name); ?></strong>
                            <span class="small text-muted"><?php echo e($farmer->stall_name ?? $farmer->business_name); ?></span>
                            <span class="small text-muted"><?php echo e($farmer->city); ?>, <?php echo e($farmer->state); ?></span>
                            <div class="d-flex gap-3 small text-muted mt-2">
                                <span><?php echo e($farmer->products_count); ?> Products</span>
                            </div>
                            <a href="<?php echo e(url('/farmers/' . $farmer->id)); ?>" class="btn ml-btn-secondary ml-btn-sm mt-3">View
                                Farmer Profile</a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12">
                        <?php echo $__env->make('Website.Partials.empty-state', [
                            'icon' => 'fa-tractor',
                            'title' => 'No Farmers Registered',
                            'message' => 'This market does not have any active farmers yet.',
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>

    <section class="ml-section">
        <div class="ml-container">
            <div class="d-flex justify-content-between align-items-center ml-heading-block mb-4">
                <div>
                    <span class="ml-eyebrow">Available for Pre-Order</span>
                    <h2>Fresh Produce Available</h2>
                </div>
                <a href="<?php echo e(url('/products')); ?>" class="ml-link-more">View All Products <i
                        class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="row g-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-6 col-lg-3">
                        <div class="ml-media-card">
                            <div class="ml-media-card__image">
                                <img src="<?php echo e($product->image ? asset('product_images/' . $product->image) : 'https://images.unsplash.com/photo-1592841200221-a6898f307baa?auto=format&fit=crop&w=500&q=60'); ?>"
                                    alt="<?php echo e($product->name); ?>">
                                <span
                                    class="ml-badge <?php echo e($product->stock_quantity > 5 ? 'ml-badge-mint' : 'ml-badge-warning'); ?> ml-image-tag"><?php echo e($product->stock_quantity > 5 ? 'In Stock' : 'Low Stock'); ?></span>
                            </div>
                            <div class="ml-media-card__body">
                                <span
                                    class="small text-muted"><?php echo e($product->farmer->stall_name ?? ($product->farmer->business_name ?? '')); ?></span>
                                <h6><?php echo e($product->name); ?></h6>
                                <strong class="text-success">Rs. <?php echo e(number_format($product->price, 0)); ?> <span
                                        class="fw-normal text-muted small">/ <?php echo e($product->unit); ?></span></strong>
                                <div class="d-flex gap-2 mt-1">
                                    <a href="<?php echo e(url('/products/' . $product->id)); ?>"
                                        class="btn ml-btn-secondary ml-btn-sm flex-grow-1">Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12">
                        <?php echo $__env->make('Website.Partials.empty-state', [
                            'icon' => 'fa-carrot',
                            'title' => 'No Products Listed',
                            'message' => 'Farmers at this market have not published any stock yet.',
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>

    <section class="ml-section ml-section--muted">
        <div class="ml-container">
            <div class="ml-heading-block mx-auto text-center">
                <span class="ml-eyebrow">How Pre-Order Works</span>
                <h2>Market Pickup Workflow</h2>
                <p class="mx-auto">Pre-order during the week, skip courier delays, and inspect your produce directly at the
                    stall.</p>
            </div>
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="ml-step__icon mx-auto"><i class="fa-solid fa-basket-shopping"></i></div>
                    <h6 class="mt-3">Choose Products</h6>
                    <p class="text-muted small">Browse this weekend's live harvests from certified farmers at
                        <?php echo e($market->name); ?>.</p>
                </div>
                <div class="col-md-4">
                    <div class="ml-step__icon mx-auto"><i class="fa-regular fa-clock"></i></div>
                    <h6 class="mt-3">Select Pickup Slot</h6>
                    <p class="text-muted small">Choose your convenient pickup slot for packed basket readiness.</p>
                </div>
                <div class="col-md-4">
                    <div class="ml-step__icon mx-auto"><i class="fa-solid fa-hand-holding-heart"></i></div>
                    <h6 class="mt-3">Collect at Stall</h6>
                    <p class="text-muted small">Collect your ready crate directly from the farmer and pay stallside, zero
                        delivery costs.</p>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pickupSlots->isNotEmpty()): ?>
                <div class="row g-3 mt-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $pickupSlots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-4">
                            <div class="ml-card">
                                <span class="small text-muted d-block">Pickup Slot</span>
                                <strong><?php echo e(\Illuminate\Support\Carbon::parse($slot->date)->format('D, d M Y')); ?></strong>
                                <span class="small text-muted d-block"><?php echo e($slot->start_time); ?> -
                                    <?php echo e($slot->end_time); ?></span>
                                <span class="small text-muted">Capacity: <?php echo e($slot->capacity); ?></span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </section>

    <section class="ml-section">
        <div class="ml-container">
            <div class="ml-heading-block mb-4">
                <span class="ml-eyebrow">Community Voice</span>
                <h2>What Customers Say</h2>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($reviews->isNotEmpty()): ?>
                <div class="row g-4 align-items-start">
                    <div class="col-lg-3 text-center">
                        <h1 class="display-4"><?php echo e($ratingAverage); ?></h1>
                        <div class="ml-rating justify-content-center mb-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fa-solid fa-star <?php echo e($i > round($ratingAverage) ? 'text-muted' : ''); ?>"></i>
                            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <p class="text-muted small">Based on <?php echo e($ratingCount); ?> reviews of farmers at this market</p>
                    </div>
                    <div class="col-lg-9">
                        <div class="row g-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-4">
                                    <div class="ml-card h-100">
                                        <div class="ml-rating small mb-2">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?>
                                                <i
                                                    class="fa-solid fa-star <?php echo e($i > $review->rating ? 'text-muted' : ''); ?>"></i>
                                            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <p class="small text-muted"><?php echo e($review->comment); ?></p>
                                        <strong
                                            class="d-block small mt-2"><?php echo e($review->user->name ?? 'Verified Customer'); ?></strong>
                                        <span class="small text-muted"><?php echo e($review->created_at->diffForHumans()); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <?php echo $__env->make('Website.Partials.empty-state', [
                    'icon' => 'fa-comment-slash',
                    'title' => 'No Reviews Yet',
                    'message' => 'Farmers at this market have not received any reviews yet.',
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </section>

    <section class="ml-section ml-section--muted" id="directions">
        <?php
            $mapFarmers = $farmers
                ->map(function ($farmer) {
                    return [
                        'name' => $farmer->user?->name ?? 'Farmer',
                        'stall_name' => $farmer->stall_name ?? $farmer->business_name,
                        'latitude' => $farmer->latitude,
                        'longitude' => $farmer->longitude,
                    ];
                })
                ->values();
        ?>

        <div class="ml-container">

            <div class="ml-heading-block mb-4">
                <span class="ml-eyebrow">Plan Your Visit</span>
                <h2>Market Location &amp; Directions</h2>
            </div>

            <div class="row g-4 align-items-stretch">

                <div class="col-lg-7">
                    <div class="ml-map h-100 rounded-4 overflow-hidden" style="min-height:420px;">
                        <div id="marketMap" data-lat="<?php echo e($market->latitude); ?>" data-lng="<?php echo e($market->longitude); ?>"
                            data-farmers="<?php echo e($mapFarmers->toJson()); ?>" style="width:100%; height:420px;"></div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="d-flex flex-column gap-3 h-100">

                        <div class="ml-card">
                            <div class="d-flex align-items-start gap-3">
                                <i class="fa-solid fa-location-dot text-success fs-4"></i>

                                <div>
                                    <strong><?php echo e($market->name); ?></strong>

                                    <p class="small text-muted mb-0 mt-1">
                                        <?php echo e($market->address); ?>,
                                        <?php echo e($market->city); ?>,
                                        <?php echo e($market->state); ?>,
                                        <?php echo e($market->country); ?>

                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="ml-card">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="small text-muted d-block">
                                        Farmers at This Market
                                    </span>

                                    <strong>
                                        <?php echo e($market->market_farmers_count); ?> Farmers
                                    </strong>
                                </div>

                                <i class="fa-solid fa-tractor text-success fs-4"></i>
                            </div>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($market->latitude && $market->longitude): ?>
                            <div class="ml-card">
                                <span class="small text-muted d-block">
                                    Market Coordinates
                                </span>

                                <strong>
                                    <?php echo e($market->latitude); ?>, <?php echo e($market->longitude); ?>

                                </strong>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="ml-card flex-grow-1">

                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <strong>Farmers at This Market</strong>

                                <span class="small text-muted">
                                    <?php echo e($farmers->count()); ?> Farmers
                                </span>
                            </div>

                            <div class="d-flex flex-column gap-3">

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $farmers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $farmer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="d-flex align-items-center gap-2">

                                        <i class="fa-solid fa-location-dot text-success"></i>

                                        <div>
                                            <strong class="small">
                                                <?php echo e($farmer->stall_name ?? $farmer->business_name); ?>

                                            </strong>

                                            <span class="small text-muted d-block">
                                                <?php echo e($farmer->city); ?>, <?php echo e($farmer->state); ?>

                                            </span>
                                        </div>

                                    </div>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                                    <p class="small text-muted mb-0">
                                        No farmers available at this market.
                                    </p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            </div>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($market->latitude && $market->longitude): ?>
                            <a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo e($market->latitude); ?>,<?php echo e($market->longitude); ?>"
                                target="_blank" rel="noopener noreferrer" class="btn ml-btn-primary ml-btn-block">
                                <i class="fa-solid fa-diamond-turn-right"></i>
                                Get Directions
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="ml-section">
        <div class="ml-container">
            <div class="ml-cta-banner text-center">
                <h2>Fresh Produce Is Waiting</h2>
                <p class="mt-2 mb-4" style="color: rgba(255,255,255,0.85);">Explore products from local farmers and
                    reserve your favourites before stalls open market day.</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="<?php echo e(url('/products')); ?>" class="btn ml-btn-primary">Browse Market Products</a>
                    <a href="<?php echo e(url('/farmers')); ?>" class="btn ml-btn-secondary">Explore All Farmers</a>
                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('Website._master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MarketLink2\resources\views/Website/Markets/view.blade.php ENDPATH**/ ?>