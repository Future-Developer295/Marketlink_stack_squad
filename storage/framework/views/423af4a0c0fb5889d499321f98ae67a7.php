<?php $__env->startSection('page_title', 'Login'); ?>

<?php $__env->startSection('body'); ?>

<div class="ml-container">
    <nav class="ml-breadcrumb"><a href="<?php echo e(url('/')); ?>">Home</a> / <span class="active">Login</span></nav>
</div>

<section class="ml-section pt-2">
    <div class="ml-container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="ml-card">
                    <div class="text-center mb-4">
                        <span class="ml-eyebrow"><i class="fa-solid fa-lock"></i> Welcome Back</span>
                        <h2>Login to MarketLink</h2>
                        <p class="text-muted small">Access your reservations, favorites and pickup history.</p>
                    </div>

                    <?php echo $__env->make('Website.Partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    <form method="POST" action="<?php echo e(url('/login')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label class="ml-form-label">Email Address</label>
                            <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="you@example.com" required>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label class="ml-form-label">Password</label>
                            <input type="password" name="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Enter your password" required>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberMe" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                                <label class="form-check-label small" for="rememberMe">Remember me</label>
                            </div>
                            <a href="<?php echo e(url('/password/reset')); ?>" class="small ml-btn-link">Forgot password?</a>
                        </div>
                        <button type="submit" class="btn ml-btn-primary ml-btn-block"><i class="fa-solid fa-right-to-bracket"></i> Login</button>
                    </form>

                    <p class="text-center small text-muted mt-4 mb-0">
                        Don't have an account? <a href="<?php echo e(url('/register')); ?>" class="ml-btn-link">Register</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('Website._master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MarketLink2\resources\views/Website/Auth/login.blade.php ENDPATH**/ ?>