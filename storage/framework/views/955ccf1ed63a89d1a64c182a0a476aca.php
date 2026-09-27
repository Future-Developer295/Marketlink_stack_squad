<?php $__env->startSection('nav_reports'); ?>
active
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_title', 'Reports'); ?>

<?php $__env->startSection('body'); ?>

<div class="panel" style="max-width:1440px">
    <div class="panel-header">
        <span class="panel-title">
            <i class="bi bi-bar-chart-line-fill"></i> Generate Report
        </span>
    </div>


<div style="padding:22px">
    <form action="<?php echo e(route('report_generate')); ?>" method="post">
        <?php echo csrf_field(); ?>

        <div class="grid-3">

            <div class="field">
                <label class="field-label">Report Type</label>

                <select name="report_type" class="field-input">
                    <option value="sales">Sales Summary</option>
                    <option value="orders">Orders</option>
                    <option value="farmers">Farmer Activity</option>
                    <option value="products">Product Inventory</option>
                </select>
            </div>

            <div class="field">
                <label class="field-label">Date From</label>
                <input type="date" name="date_from" class="field-input" required>
            </div>

            <div class="field">
                <label class="field-label">Date To</label>
                <input type="date" name="date_to" class="field-input" required>
            </div>

        </div>

        <button class="btn-primary" type="submit">
            <i class="bi bi-file-earmark-bar-graph"></i>
            Generate Report
        </button>

    </form>
</div>


</div>

<div class="spacer-24"></div>

<div class="panel">


<div class="panel-header" style="display:flex; justify-content:space-between; align-items:center;">

    <span class="panel-title">
        <i class="bi bi-folder2-open"></i> Generated Reports
    </span>

    <form action="<?php echo e(route('reports')); ?>" method="GET" style="margin:0">

        <select name="report_type"
                class="field-input"
                onchange="this.form.submit()"
                style="width:220px;">

            <option value="all"
                <?php echo e(!request('report_type') || request('report_type') == 'all' ? 'selected' : ''); ?>>
                All Reports
            </option>

            <option value="sales"
                <?php echo e(request('report_type') == 'sales' ? 'selected' : ''); ?>>
                Sales Summary
            </option>

            <option value="orders"
                <?php echo e(request('report_type') == 'orders' ? 'selected' : ''); ?>>
                Orders
            </option>

            <option value="farmers"
                <?php echo e(request('report_type') == 'farmers' ? 'selected' : ''); ?>>
                Farmer Activity
            </option>

            <option value="products"
                <?php echo e(request('report_type') == 'products' ? 'selected' : ''); ?>>
                Product Inventory
            </option>

        </select>

    </form>

</div>

<div class="tbl-wrap">

    <table class="dtable">

        <thead>
            <tr>
                <th>#ID</th>
                <th>Type</th>
                <th>Date Range</th>
                <th>Generated At</th>
                <th>File</th>
            </tr>
        </thead>

        <tbody>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <tr>

                <td>
                    <span class="id-chip">
                        <?php echo e($report->id); ?>

                    </span>
                </td>

                <td>
                    <span class="badge-cat">
                        <?php echo e(ucfirst($report->report_type)); ?>

                    </span>
                </td>

                <td>
                    <?php echo e($report->date_from->format('d M Y')); ?>

                    –
                    <?php echo e($report->date_to->format('d M Y')); ?>

                </td>

                <td>
                    <?php echo e($report->generated_at->format('Y-m-d')); ?>

                </td>

                <td>
                    <a class="btn-ghost sm"
                       href="<?php echo e(route('report_download', $report->id)); ?>">

                        <i class="bi bi-download"></i>
                        Download

                    </a>
                </td>

            </tr>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

            <tr>
                <td colspan="5" style="text-align:center; padding:30px;">
                    No reports found.
                </td>
            </tr>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </tbody>

    </table>

</div>


</div>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard._master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MarketLink2\resources\views/Dashboard/Reports/reports.blade.php ENDPATH**/ ?>