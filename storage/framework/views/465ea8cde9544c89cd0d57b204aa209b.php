

<?php $__env->startSection('nav_categories_list'); ?>
    active
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_title', 'Categories'); ?>

<?php $__env->startSection('body'); ?>

    <div class="panel">

        <div class="panel-header">

            <span class="panel-title">
                <i class="bi bi-tags-fill"></i> Categories
            </span>

            <div class="panel-tools">

                <form class="search-box" method="GET" action="<?php echo e(route('categories')); ?>">
                    <i class="bi bi-search"></i>

                    <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Search..." />

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('q')): ?>
                        <a href="<?php echo e(route('categories')); ?>" class="search-clear" title="Clear search">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </form>

                <a class="btn-primary" href="<?php echo e(route('category_add')); ?>">
                    <i class="bi bi-plus-lg"></i> Add Category
                </a>

            </div>

        </div>

        <div class="tbl-wrap">

            <table class="dtable">

                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Icon</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    <?php 
    $index = 1;
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <tr>

                            <td>
                                <span class="id-chip">
                                    <?php echo e($index++); ?>

                                </span>
                            </td>

                            <td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->icon): ?>
                                    <i class="<?php echo e($category->icon); ?>"></i>
                                <?php else: ?>
                                    -
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>

                            <td>
                                <strong>
                                    <?php echo e($category->name); ?>

                                </strong>
                            </td>

                            <td>
                                <?php echo e($category->slug); ?>

                            </td>

                            <td>
                                <div class="action-wrap">

                                    <a class="btn-ghost sm" href="<?php echo e(route('category_edit', $category->id)); ?>">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="<?php echo e(route('category_delete', $category->id)); ?>" method="POST"
                                        style="display:inline;">
                                        <?php echo csrf_field(); ?>

                                        <button class="btn-ghost sm danger" type="submit"
                                            onclick="return confirm('Are you sure you want to delete this category?')">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </form>

                                </div>
                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>
                            <td colspan="5" style="text-align:center; padding:30px;">
                                No categories found.
                            </td>
                        </tr>

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('Dashboard._master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Marketlink\resources\views/Dashboard/Categories/categories.blade.php ENDPATH**/ ?>