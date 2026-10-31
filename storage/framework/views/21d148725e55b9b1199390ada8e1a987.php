<?php $__env->startSection('panel'); ?>
<div class="row mb-none-30">
    <div class="col-xl-6 col-md-6 mb-30">
        <div class="card p-4 border-0 shadow-sm rounded-4 h-100">
            <h6 class="fw-black text-slate-800 uppercase tracking-tighter mb-4">Device Distribution</h6>
            <div id="device-chart"></div>
            <div class="mt-4">
                <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-slate-500 text-xs font-bold uppercase tracking-widest"><?php echo e($d->device ?: 'Unknown'); ?></span>
                    <span class="fw-bold"><?php echo e($d->count); ?> views</span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-md-6 mb-30">
        <div class="card p-4 border-0 shadow-sm rounded-4 h-100">
            <h6 class="fw-black text-slate-800 uppercase tracking-tighter mb-4">Top Geographic Locations</h6>
            <div id="country-chart"></div>
            <div class="mt-4">
                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-slate-500 text-xs font-bold uppercase tracking-widest"><?php echo e($c->country ?: 'Unknown'); ?></span>
                    <span class="fw-bold"><?php echo e($c->count); ?> views</span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    var deviceOptions = {
        series: <?php echo json_encode($devices->pluck('count'), 15, 512) ?>,
        chart: {
            type: 'donut',
            height: 300
        },
        labels: <?php echo json_encode($devices->pluck('device')->map(fn($d) => $d ?: 'Unknown'), 15, 512) ?>,
        colors: ['#F97316', '#3B82F6', '#10B981', '#6366F1'],
        legend: { position: 'bottom' }
    };
    var deviceChart = new ApexCharts(document.querySelector("#device-chart"), deviceOptions);
    deviceChart.render();

    var countryOptions = {
        series: [{
            data: <?php echo json_encode($countries->pluck('count'), 15, 512) ?>
        }],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                horizontal: true,
                borderRadius: 4,
            }
        },
        dataLabels: { enabled: false },
        xaxis: {
            categories: <?php echo json_encode($countries->pluck('country')->map(fn($c) => $c ?: 'Unknown'), 15, 512) ?>,
        },
        colors: ['#8B5CF6']
    };
    var countryChart = new ApexCharts(document.querySelector("#country-chart"), countryOptions);
    countryChart.render();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\analytics\audience.blade.php ENDPATH**/ ?>