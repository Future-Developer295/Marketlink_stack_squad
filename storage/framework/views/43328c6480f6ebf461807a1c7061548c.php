<?php $__env->startSection('nav_products_list'); ?>
    active
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_title', 'Products'); ?>

<?php $__env->startSection('body'); ?>

    <div class="panel">

        <div class="panel-header">

            <span class="panel-title">
                <i class="bi bi-box-seam-fill"></i>
                Products
            </span>

            <div class="panel-tools">

                <form class="search-box" method="GET" action="<?php echo e(route('products')); ?>">
                    <i class="bi bi-search"></i>

                    <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Search...">
                </form>

                <a class="btn-primary" href="<?php echo e(route('product_add')); ?>">
                    <i class="bi bi-plus-lg"></i>
                    Add Product
                </a>

            </div>

        </div>


        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>

            <div style="padding: 12px 22px; color: green;">
                <?php echo e(session('success')); ?>

            </div>

        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


        <div class="tbl-wrap">

            <table class="dtable">

                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Product</th>
                        <th>Farmer</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>


                <tbody>
                    <?php 
    $index = 1;
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <tr>

                            <td>
                                <span class="id-chip">
                                    <?php echo e($index++); ?>

                                </span>
                            </td>


                            <td>

                                <div class="prod-cell">

                                    <div class="prod-img">

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->image): ?>

                                            <img src="<?php echo e(asset('product_images/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>">

                                        <?php else: ?>

                                            <i class="bi bi-box-seam"></i>

                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    </div>


                                    <div class="prod-info">

                                        <strong>
                                            <?php echo e($product->name); ?>

                                        </strong>

                                        <span>
                                            Unit: <?php echo e($product->unit); ?>

                                        </span>

                                    </div>

                                </div>

                            </td>


                            
                            <td>
                                <?php echo e($product->farmer->name ?? 'N/A'); ?>

                            </td>


                            
                            <td>

                                <span class="badge-cat">
                                    <?php echo e($product->category->name ?? 'N/A'); ?>

                                </span>

                            </td>


                            
                            <td>

                                <span class="price-mono">
                                    PKR <?php echo e(number_format($product->price, 2)); ?>

                                </span>

                            </td>


                            
                            <td>

                                <span class="qty-tag">
                                    <?php echo e($product->stock_quantity); ?>

                                </span>

                            </td>


                            
                            <td>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->is_active): ?>

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-secondary">
                                        Inactive
                                    </span>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            </td>


                            
                            <td>

                                <div class="action-wrap">

                                    <a class="btn-ghost sm" href="<?php echo e(route('product_view', $product->id)); ?>" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>


                                    <a class="btn-ghost sm" href="<?php echo e(route('product_edit', $product->id)); ?>" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>


                                    <form action="<?php echo e(route('product_delete', $product->id)); ?>" method="POST"
                                        style="display:inline">
                                        <?php echo csrf_field(); ?>

                                        <button class="btn-ghost sm danger" type="submit" title="Delete"
                                            onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>

                            <td colspan="8" style="text-align:center; padding:40px;">
                                <i class="bi bi-box-seam" style="font-size:35px;"></i>

                                <br>

                                <strong>No products found</strong>

                                <br>

                                <span style="color:var(--muted);">
                                    Add your first product to get started.
                                </span>

                            </td>

                        </tr>

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </tbody>

            </table>

        </div>


        <div class="hr-thin"></div>

        <div style="padding:14px 22px; color:var(--muted); font-size:13px">

            Showing your products only.

        </div>

    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('Dashboard._master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MarketLink2\resources\views/Dashboard/Products/products.blade.php ENDPATH**/ ?>