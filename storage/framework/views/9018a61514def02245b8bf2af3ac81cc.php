<?php $__env->startSection('nav_users_list'); ?>
active
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page_title', 'Users'); ?>
<?php $__env->startSection('body'); ?>
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-people-fill"></i> Users</span>
            <div class="panel-tools">
                <form class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" placeholder="Search…" />
                </form>
                <a class="btn-primary" href="<?php echo e(route('user_add')); ?>"><i class="bi bi-plus-lg"></i> Add User</a>
            </div>
        </div>

        <div class="tbl-wrap">
            <table class="dtable">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                            <td><span class='id-chip'><?php echo e($loop->iteration); ?></span></td>
                            <td><strong><?php echo e($user->name); ?></strong></td>
                            <td><?php echo e($user->email); ?></td>
                            <td><?php echo e($user->phone ?? '—'); ?></td>
                            <td><span class='badge-cat'><?php echo e($user->role); ?></span></td>
                            <td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->is_active): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td><div class='action-wrap'><a class='btn-ghost sm' href='<?php echo e(route("user_edit", $user->id)); ?>'><i class='bi bi-pencil'></i></a><form action='<?php echo e(route("user_delete", $user->id)); ?>' method='post' onsubmit="return confirm('Delete this user?');"><input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>"><button class='btn-ghost sm danger' type='submit'><i class='bi bi-trash'></i></button></form></div></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" style="text-align:center; color:var(--muted); padding:20px">No users found.</td>
                    </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard._master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MarketLink2\resources\views/Dashboard/Users/users.blade.php ENDPATH**/ ?>