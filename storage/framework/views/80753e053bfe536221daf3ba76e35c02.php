<?php $__env->startSection('page_title', 'Browse Markets'); ?>

<?php $__env->startSection('body'); ?>

    <div class="ml-container">
        <nav class="ml-breadcrumb">
            <a href="<?php echo e(url('/')); ?>">Home</a> /
            <span class="active">Markets</span>
        </nav>
    </div>

    <section class="pb-4">
        <div class="ml-container">
            <div class="ml-hero">
                <span class="ml-eyebrow">
                    <i class="fa-solid fa-store"></i> Explore Local Markets
                </span>

                <h1 class="display-6">Browse Local Markets</h1>

                <form class="d-flex flex-column flex-md-row gap-2 mt-4" action="<?php echo e(url('/markets')); ?>" method="GET">

                    <div class="flex-grow-1 position-relative">
                        <i class="fa-solid fa-magnifying-glass position-absolute"
                            style="left:16px; top:14px; color:var(--ml-text-muted);"></i>

                        <input type="text" name="q" value="<?php echo e(request('q')); ?>" class="form-control ps-5"
                            placeholder="Search markets, city or area...">
                    </div>

                    <button type="submit" class="btn ml-btn-primary">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Search
                    </button>
                </form>

                <p class="small text-muted mt-3 mb-0">
                    <i class="fa-solid fa-location-dot"></i>
                    Find local markets and pickup locations near you.
                </p>
            </div>
        </div>
    </section>

    <section class="ml-section pt-2">
        <div class="ml-container">
            <div class="row g-4">

                <div class="col-lg-3">
                    <div class="ml-filter-panel">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">
                                <i class="fa-solid fa-sliders"></i>
                                Filter Markets
                            </h6>

                            <a href="<?php echo e(url('/markets')); ?>" class="small ml-btn-link">
                                Clear All
                            </a>
                        </div>

                        <form action="<?php echo e(url('/markets')); ?>" method="GET">

                            <input type="hidden" name="q" value="<?php echo e(request('q')); ?>">

                            <div class="ml-form-label">Location</div>

                            <select class="form-select mb-4" name="city">
                                <option value="">All Cities</option>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($city); ?>" <?php echo e(request('city') == $city ? 'selected' : ''); ?>>
                                        <?php echo e($city); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>

                            <div class="ml-form-label">Market Day</div>

                            <select class="form-select mb-4" name="day">
                                <option value="">All Days</option>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($day); ?>" <?php echo e(request('day') == $day ? 'selected' : ''); ?>>
                                        <?php echo e($day); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>

                            <div class="ml-form-label">Sort By</div>

                            <select class="form-select mb-4" name="sort">
                                <option value="newest" <?php echo e(request('sort', 'newest') == 'newest' ? 'selected' : ''); ?>>
                                    Newest First
                                </option>

                                <option value="name" <?php echo e(request('sort') == 'name' ? 'selected' : ''); ?>>
                                    Name (A-Z)
                                </option>

                                <option value="most_farmers" <?php echo e(request('sort') == 'most_farmers' ? 'selected' : ''); ?>>
                                    Most Farmers
                                </option>
                            </select>

                            <button type="submit" class="btn ml-btn-primary ml-btn-block">
                                <i class="fa-solid fa-filter"></i>
                                Apply Filters
                            </button>

                        </form>
                    </div>
                </div>

                <div class="col-lg-9">

                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                        <strong><?php echo e($markets->total()); ?> Markets Found</strong>
                    </div>

                    <div class="row g-4">

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $markets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $market): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                            <div class="col-md-6 col-xl-4">
                                <div class="ml-media-card h-100">

                                    <div class="ml-media-card__body">
                                        <div class="ml-media-card__image">
                                            <img style="border-radius: 8px" src="https://images.unsplash.com/photo-1488459716781-31db52582fe9?auto=format&fit=crop&w=700&q=60"
                                                alt="<?php echo e($market->name); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <span class="ml-badge ml-badge-mint">
                                                <?php echo e($market->market_farmers_count); ?> Farmers
                                            </span>
                                        </div>

                                        <h5><?php echo e($market->name); ?></h5>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($market->city || $market->state): ?>
                                            <span class="text-muted small d-block mb-2">
                                                <i class="fa-solid fa-location-dot"></i>
                                                <?php echo e($market->city); ?>

                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($market->state): ?>
                                                    , <?php echo e($market->state); ?>

                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($market->address): ?>
                                            <span class="text-muted small d-block mb-2">
                                                <i class="fa-solid fa-map-location-dot"></i>
                                                <?php echo e($market->address); ?>

                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($market->operating_days): ?>
                                            <span class="text-muted small d-block mb-2">
                                                <i class="fa-solid fa-calendar-days"></i>
                                                <?php echo e($market->operating_days); ?>

                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($market->start_time && $market->end_time): ?>
                                            <span class="text-muted small d-block mb-3">
                                                <i class="fa-regular fa-clock"></i>
                                                <?php echo e($market->start_time); ?> - <?php echo e($market->end_time); ?>

                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        <a href="<?php echo e(url('/markets/' . $market->id)); ?>"
                                            class="btn ml-btn-primary ml-btn-block mt-2">
                                            View Details
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </a>

                                    </div>
                                </div>
                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                            <div class="col-12">
                                <?php echo $__env->make('Website.Partials.empty-state', [
                                    'icon' => 'fa-store-slash',
                                    'title' => 'No Markets Found',
                                    'message' => 'Try adjusting your search or filters.',
                                    'actionUrl' => url('/markets'),
                                    'actionLabel' => 'Reset Filters',
                                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>

                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    </div>

                    <?php echo $__env->make('Website.Partials.pagination', [
                        'paginator' => $markets,
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('Website._master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MarketLink2\resources\views/Website/Markets/index.blade.php ENDPATH**/ ?>