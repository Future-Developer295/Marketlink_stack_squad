
<?php $__env->startSection('nav_markets_list'); ?>
active
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page_title', 'Markets'); ?>
<?php $__env->startSection('body'); ?>
<div class="panel">
    <div class="panel-header">
        <span class="panel-title"><i class="bi bi-shop-window"></i> Markets</span>
        <div class="panel-tools">
            <form class="search-box" method="GET" action="<?php echo e(route('markets')); ?>">
                <i class="bi bi-search"></i>
                <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Search…" />
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('q')): ?>
                    <a href="<?php echo e(route('markets')); ?>" class="search-clear" title="Clear search">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </form>
            <a class="btn-primary" href="<?php echo e(route('market_add')); ?>"><i class="bi bi-plus-lg"></i> Add Market</a>
        </div>
    </div>

    <div class="tbl-wrap">
        <table class="dtable">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Name</th>
                    <th>City</th>
                    <th>Operating Days</th>
                    <th>Timing</th>
                    <th>Farmers</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
<?php
    $id = 0;
?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $markets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $market): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <span class="id-chip"><?php echo e(++$id); ?></span>
                    </td>

                    <td>
                        <strong><?php echo e($market->name); ?></strong>
                    </td>

                    <td><?php echo e($market->city); ?></td>

                    <td><?php echo e($market->operating_days); ?></td>

                    <td>
                        <?php echo e($market->start_time); ?> – <?php echo e($market->end_time); ?>

                    </td>

                    <td>
                        <span class="qty-tag">0</span>
                    </td>

                    <td>
                        <div class="action-wrap">

                            <a class="btn-ghost sm"
                                href="<?php echo e(route('market_edit', $market->id)); ?>">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="<?php echo e(route('market_delete', $market->id)); ?>"
                                method="post">
                                <?php echo csrf_field(); ?>

                                <button class="btn-ghost sm danger" type="submit">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Dashboard._master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Marketlink\resources\views/Dashboard/Markets/markets.blade.php ENDPATH**/ ?>