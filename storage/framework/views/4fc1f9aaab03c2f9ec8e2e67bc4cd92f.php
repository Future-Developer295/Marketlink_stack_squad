<div class="ml-empty-state">
    <div class="ml-empty-state__icon">
        <i class="fa-solid <?php echo e($icon ?? 'fa-seedling'); ?>"></i>
    </div>
    <h4><?php echo e($title ?? 'Nothing Found'); ?></h4>
    <p class="text-muted mt-2 mb-4"><?php echo e($message ?? 'Try adjusting your search or filters to find what you are looking for.'); ?></p>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($actionUrl ?? null) && ($actionLabel ?? null)): ?>
        <a href="<?php echo e($actionUrl); ?>" class="btn ml-btn-primary">
            <i class="fa-solid fa-rotate-right"></i> <?php echo e($actionLabel); ?>

        </a>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\MarketLink2\resources\views/Website/Partials/empty-state.blade.php ENDPATH**/ ?>