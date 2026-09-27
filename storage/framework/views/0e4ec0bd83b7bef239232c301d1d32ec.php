<?php $__env->startSection('page_title', 'Products'); ?>

<?php $__env->startSection('body'); ?>

<div class="ml-container">
    <nav class="ml-breadcrumb"><a href="<?php echo e(url('/')); ?>">Home</a> / <span class="active">Products</span></nav>
</div>

<section class="pb-4">
    <div class="ml-container">
        <div class="ml-hero">
            <span class="ml-eyebrow"><i class="fa-solid fa-shield-halved"></i> Local Farms &middot; Weekly Fresh Stock &middot; Pre-Order &amp; Stall Pickup</span>
            <h1 class="display-6">Fresh Products From Local Farmers</h1>
            <p class="text-muted mt-2">Browse fresh weekly stock, compare prices and reserve products for convenient market pickup directly from verified local growers.</p>
            <form class="d-flex flex-column flex-md-row gap-2 mt-4" action="<?php echo e(url('/products')); ?>" method="GET">
                <div class="flex-grow-1 position-relative">
                    <i class="fa-solid fa-magnifying-glass position-absolute" style="left:16px; top:14px; color:var(--ml-text-muted);"></i>
                    <input type="text" name="q" value="<?php echo e(request('q')); ?>" class="form-control ps-5" placeholder="Search products or farmers...">
                </div>
                <button type="submit" class="btn ml-btn-primary">Search Catalog <i class="fa-solid fa-arrow-right"></i></button>
            </form>
        </div>
    </div>
</section>

<section class="ml-section pt-3">
    <div class="ml-container">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="ml-filter-panel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0"><i class="fa-solid fa-sliders"></i> Filter Products</h6>
                        <a href="<?php echo e(url('/products')); ?>" class="small ml-btn-link">Clear All</a>
                    </div>
                    <form action="<?php echo e(url('/products')); ?>" method="GET">
                        <input type="hidden" name="q" value="<?php echo e(request('q')); ?>">

                        <div class="ml-form-label">Category</div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="category_id" value="" id="catAll" <?php echo e(! request('category_id') ? 'checked' : ''); ?> onchange="this.form.submit()">
                            <label class="form-check-label d-flex justify-content-between w-100" for="catAll">All Categories</label>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="category_id" value="<?php echo e($category->id); ?>" id="cat<?php echo e($category->id); ?>" <?php echo e(request('category_id') == $category->id ? 'checked' : ''); ?> onchange="this.form.submit()">
                            <label class="form-check-label d-flex justify-content-between w-100" for="cat<?php echo e($category->id); ?>"><?php echo e($category->name); ?> <span class="text-muted"><?php echo e($category->products_count); ?></span></label>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="ml-form-label mt-3">Market</div>
                        <select class="form-select mb-3" name="market_id" onchange="this.form.submit()">
                            <option value="">All Markets</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $markets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $market): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($market->id); ?>" <?php echo e(request('market_id') == $market->id ? 'selected' : ''); ?>><?php echo e($market->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>

                        <div class="ml-form-label">Market Day</div>
                        <select class="form-select mb-4" name="market_day" onchange="this.form.submit()">
                            <option value="">Any Day</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($day); ?>" <?php echo e(request('market_day') == $day ? 'selected' : ''); ?>><?php echo e($day); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>

                        <div class="ml-form-label mt-3">Max Price (Rs.)</div>
                        <input type="number" name="max_price" min="0" value="<?php echo e(request('max_price')); ?>" class="form-control mb-4" placeholder="No limit">

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="in_stock_only" value="1" id="inStockOnly" <?php echo e(request('in_stock_only') ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="inStockOnly">In Stock Only</label>
                        </div>

                        <div class="ml-form-label">Sort By</div>
                        <select class="form-select mb-4" name="sort">
                            <option value="newest" <?php echo e(request('sort', 'newest') == 'newest' ? 'selected' : ''); ?>>Newest</option>
                            <option value="price_low" <?php echo e(request('sort') == 'price_low' ? 'selected' : ''); ?>>Price: Low to High</option>
                            <option value="price_high" <?php echo e(request('sort') == 'price_high' ? 'selected' : ''); ?>>Price: High to Low</option>
                        </select>

                        <button type="submit" class="btn ml-btn-primary ml-btn-block"><i class="fa-solid fa-check"></i> Apply Filters</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div>
                        <strong><?php echo e($products->total()); ?> Products Found</strong>
                    </div>
                </div>

                <div class="row g-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-md-6 col-xl-4">
                        <div class="ml-media-card">
                            <div class="ml-media-card__image">
                                <img src="<?php echo e($product->image ? asset('product_images/' . $product->image) : 'https://images.unsplash.com/photo-1592841200221-a6898f307baa?auto=format&fit=crop&w=500&q=60'); ?>" alt="<?php echo e($product->name); ?>">
                                <span class="ml-badge <?php echo e($product->stock_quantity <= 0 ? 'ml-badge-danger' : 'ml-badge-mint'); ?> ml-image-tag"><?php echo e($product->stock_quantity <= 0 ? 'Out of Stock' : ($product->category->name ?? 'Fresh')); ?></span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                                    <form action="<?php echo e(route('favorite.toggle', ['type' => 'product', 'id' => $product->id])); ?>" method="POST" class="ml-image-fav">
                                        <?php echo csrf_field(); ?>
                                        <button class="ml-favorite-btn" type="submit" aria-label="<?php echo e($favoritedProductIds->contains($product->id) ? 'Remove from favorites' : 'Save product'); ?>">
                                            <i class="fa-<?php echo e($favoritedProductIds->contains($product->id) ? 'solid' : 'regular'); ?> fa-heart"></i>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <a href="<?php echo e(route('login')); ?>" class="ml-favorite-btn ml-image-fav" aria-label="Save product"><i class="fa-regular fa-heart"></i></a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="ml-media-card__body">
                                <span class="small text-muted d-flex justify-content-between"><span><i class="fa-solid fa-user"></i> <?php echo e($product->farmer->stall_name ?? $product->farmer->business_name ?? ''); ?></span><span>Available: <?php echo e($product->stock_quantity); ?> <?php echo e($product->unit); ?></span></span>
                                <h6><?php echo e($product->name); ?></h6>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <strong class="text-success">Rs. <?php echo e(number_format($product->price, 0)); ?> <span class="fw-normal text-muted small">/ <?php echo e($product->unit); ?></span></strong>
                                    <span class="small text-muted">Stall pickup</span>
                                </div>
                                <div class="d-flex gap-2 mt-1">
                                    <a href="<?php echo e(url('/products/'.$product->id)); ?>" class="btn ml-btn-secondary ml-btn-sm flex-grow-1">Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12">
                        <?php echo $__env->make('Website.Partials.empty-state', [
                            'icon' => 'fa-carrot',
                            'title' => 'No Products Found',
                            'message' => 'Try adjusting your search query or clearing filters.',
                            'actionUrl' => url('/products'),
                            'actionLabel' => 'Reset Search Filters',
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php echo $__env->make('Website.Partials.pagination', ['paginator' => $products], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('Website._master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MarketLink2\resources\views/Website/Products/index.blade.php ENDPATH**/ ?>