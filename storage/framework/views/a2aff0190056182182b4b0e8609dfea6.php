<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($paginator ?? null) && $paginator->hasPages()): ?>
<div class="ml-pagination">
    <a href="<?php echo e($paginator->previousPageUrl()); ?>" class="<?php echo e($paginator->onFirstPage() ? 'disabled' : ''); ?>">
        <i class="fa-solid fa-chevron-left"></i>
    </a>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $paginator->getUrlRange(1, $paginator->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e($url); ?>" class="<?php echo e($page == $paginator->currentPage() ? 'active' : ''); ?>"><?php echo e($page); ?></a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <a href="<?php echo e($paginator->nextPageUrl()); ?>" class="<?php echo e($paginator->hasMorePages() ? '' : 'disabled'); ?>">
        <i class="fa-solid fa-chevron-right"></i>
    </a>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\xampp\htdocs\MarketLink2\resources\views/Website/Partials/pagination.blade.php ENDPATH**/ ?>