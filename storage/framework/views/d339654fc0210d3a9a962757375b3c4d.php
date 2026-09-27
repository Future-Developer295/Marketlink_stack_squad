

<?php $__env->startSection('page_title', 'Farmers'); ?>

<?php $__env->startSection('body'); ?>

<section class="ml-section pt-4">
    <div class="ml-container">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="ml-filter-panel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0"><i class="fa-solid fa-sliders me-2"></i>Filter Farmers</h5>
                        <a href="<?php echo e(url('/farmers')); ?>" class="ml-btn-link">Clear All</a>
                    </div>

                    <form action="<?php echo e(url('/farmers')); ?>" method="GET">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('q')): ?>
                            <input type="hidden" name="q" value="<?php echo e(request('q')); ?>">
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="mb-4">
                            <label class="ml-form-label mb-2">Location</label>
                            <select name="city" class="form-select">
                                <option value="">All Cities</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($city); ?>" <?php echo e(request('city') == $city ? 'selected' : ''); ?>><?php echo e($city); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="ml-form-label mb-2">Sort By</label>
                            <select name="sort" class="form-select">
                                <option value="top_rated" <?php echo e(request('sort', 'top_rated') == 'top_rated' ? 'selected' : ''); ?>>Top Rated</option>
                                <option value="newest" <?php echo e(request('sort') == 'newest' ? 'selected' : ''); ?>>Newest</option>
                                <option value="most_products" <?php echo e(request('sort') == 'most_products' ? 'selected' : ''); ?>>Most Products</option>
                                <option value="name" <?php echo e(request('sort') == 'name' ? 'selected' : ''); ?>>Name</option>
                            </select>
                        </div>

                        <button type="submit" class="btn ml-btn-primary w-100">
                            <i class="fa-solid fa-filter me-2"></i>Apply Filters
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="mb-0"><?php echo e($farmers->total()); ?> Farmers Found</h5>
                </div>

                <div class="row g-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $farmers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $farmer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="ml-media-card h-100">
                                <div class="ml-media-card__image position-relative" style="height:260px;">
                                    <img
                                        src="<?php echo e($farmer->farmer_image ? asset('farmer_images/' . $farmer->farmer_image) : 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=700&q=80'); ?>"
                                        alt="<?php echo e($farmer->stall_name ?? $farmer->business_name); ?>"
                                        style="width:100%;height:100%;object-fit:cover;"
                                    >
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
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <h4 class="mb-1"><?php echo e($farmer->stall_name ?? $farmer->business_name); ?></h4>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($farmer->reviews_count > 0): ?>
                                            <span class="ml-rating">
                                                <i class="fa-solid fa-star"></i>
                                                <?php echo e(number_format($farmer->reviews_avg_rating ?? 0, 1)); ?>

                                                <span>(<?php echo e($farmer->reviews_count); ?>)</span>
                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                    <div class="text-muted mb-2">
                                        <i class="fa-solid fa-user me-2"></i><?php echo e($farmer->user->name ?? 'Farmer'); ?>

                                    </div>

                                    <div class="text-muted mb-4">
                                        <i class="fa-solid fa-location-dot me-2"></i>
                                        <?php echo e($farmer->city ?: 'Location not added'); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($farmer->state): ?>, <?php echo e($farmer->state); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <span class="text-muted">
                                            <?php echo e($farmer->products_count); ?> <?php echo e($farmer->products_count == 1 ? 'Product' : 'Products'); ?>

                                        </span>
                                        <a href="<?php echo e(url('/farmers/' . $farmer->id)); ?>" class="btn ml-btn-primary ml-btn-sm">View Farmer</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-12">
                            <div class="ml-card text-center py-5">
                                <i class="fa-solid fa-tractor fs-1 text-success mb-3"></i>
                                <h4>No Farmers Found</h4>
                                <p class="text-muted">Try changing your filters.</p>
                                <a href="<?php echo e(url('/farmers')); ?>" class="btn ml-btn-primary">Clear Filters</a>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($farmers->hasPages()): ?>
                    <div class="mt-5">
                        <?php echo e($farmers->links()); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('Website._master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MarketLink2\resources\views/Website/Farmers/index.blade.php ENDPATH**/ ?>