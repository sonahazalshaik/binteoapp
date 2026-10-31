<?php $__env->startSection('panel'); ?>
<div class="row mb-none-30">
    <div class="col-xl-12 col-md-12 mb-30">
        <div class="card p-4 border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-black text-slate-800 uppercase tracking-tighter m-0">Revenue Overview</h5>
                <div class="dropdown">
                    <button class="btn btn-outline-light btn-sm rounded-3 text-slate-500 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Filter Period
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Last 30 Days</a></li>
                        <li><a class="dropdown-item" href="#">Last 6 Months</a></li>
                        <li><a class="dropdown-item" href="#">Last Year</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div id="revenue-chart"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-md-6 mb-30">
        <div class="card p-4 border-0 shadow-sm rounded-4 h-100">
            <h6 class="fw-black text-slate-800 uppercase tracking-tighter mb-4">Monthly Income Summary</h6>
            <div class="table-responsive--md">
                <table class="table table--light">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Total Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $monthlyRevenue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($rev->month); ?></td>
                            <td class="fw-bold"><?php echo e(gs('cur_sym')); ?><?php echo e(showAmount($rev->total)); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-md-6 mb-30">
        <div class="card p-4 border-0 shadow-sm rounded-4 h-100">
            <h6 class="fw-black text-slate-800 uppercase tracking-tighter mb-4">Weekly Performance</h6>
            <div id="weekly-revenue-chart"></div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    var options = {
        series: [{
            name: 'Revenue',
            data: <?php echo json_encode($monthlyRevenue->pluck('total'), 15, 512) ?>
        }],
        chart: {
            type: 'area',
            height: 350,
            toolbar: { show: false }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        colors: ['#F97316'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.2,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: <?php echo json_encode($monthlyRevenue->pluck('month'), 15, 512) ?>,
        }
    };
    var chart = new ApexCharts(document.querySelector("#revenue-chart"), options);
    chart.render();

    var weeklyOptions = {
        series: [{
            name: 'Weekly Revenue',
            data: <?php echo json_encode($weeklyRevenue->pluck('total'), 15, 512) ?>
        }],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                borderRadius: 10,
                columnWidth: '50%',
            }
        },
        colors: ['#3B82F6'],
        xaxis: {
            categories: <?php echo json_encode($weeklyRevenue->pluck('week'), 15, 512) ?>,
        }
    };
    var weeklyChart = new ApexCharts(document.querySelector("#weekly-revenue-chart"), weeklyOptions);
    weeklyChart.render();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\analytics\revenue.blade.php ENDPATH**/ ?>