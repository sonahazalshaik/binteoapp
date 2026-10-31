<?php $__env->startSection('panel'); ?>
<div class="space-y-6 pb-10">
    <div class="mb-4">
        <h4 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">Technical Quality monitoring</h4>
        <p class="text-sm text-slate-400">System latency, error rates, and API performance</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Latency Chart -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <h5 class="font-black text-slate-800 dark:text-white uppercase text-xs tracking-widest mb-6">API Latency (24h)</h5>
            <div id="latencyChart" class="h-64"></div>
        </div>

        <!-- Error Distribution -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <h5 class="font-black text-slate-800 dark:text-white uppercase text-xs tracking-widest mb-6">HTTP Status Distribution</h5>
            <div id="errorChart" class="h-64"></div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 p-8">
        <h5 class="font-black text-slate-800 dark:text-white uppercase text-xs tracking-widest mb-8 text-center">Video Player Health (Live Metrics)</h5>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-3xl font-black text-slate-800 dark:text-white mb-2"><?php echo e($playerHealth['avg_load_time']); ?></div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Avg Load Time</p>
                <div class="mt-4 flex justify-center">
                    <?php
                        $loadColor = $playerHealthStatus['load'] == 'Fast' ? 'bg-emerald-50 text-emerald-500' : ($playerHealthStatus['load'] == 'Moderate' ? 'bg-orange-50 text-orange-500' : 'bg-rose-50 text-rose-500');
                    ?>
                    <span class="px-2 py-1 rounded <?php echo e($loadColor); ?> text-[10px] font-black uppercase"><?php echo e($playerHealthStatus['load']); ?></span>
                </div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-black text-slate-800 dark:text-white mb-2"><?php echo e($playerHealth['buffering_rate']); ?></div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Buffering Rate</p>
                <div class="mt-4 flex justify-center">
                    <?php
                        $bufColor = $playerHealthStatus['buffer'] == 'Healthy' ? 'bg-emerald-50 text-emerald-500' : ($playerHealthStatus['buffer'] == 'Fair' ? 'bg-orange-50 text-orange-500' : 'bg-rose-50 text-rose-500');
                    ?>
                    <span class="px-2 py-1 rounded <?php echo e($bufColor); ?> text-[10px] font-black uppercase"><?php echo e($playerHealthStatus['buffer']); ?></span>
                </div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-black text-slate-800 dark:text-white mb-2"><?php echo e($playerHealth['crash_rate']); ?></div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Crash Rate</p>
                <div class="mt-4 flex justify-center">
                    <?php
                        $crashColor = $playerHealthStatus['crash'] == 'Stable' ? 'bg-emerald-50 text-emerald-500' : ($playerHealthStatus['crash'] == 'Degraded' ? 'bg-orange-50 text-orange-500' : 'bg-rose-50 text-rose-500');
                    ?>
                    <span class="px-2 py-1 rounded <?php echo e($crashColor); ?> text-[10px] font-black uppercase"><?php echo e($playerHealthStatus['crash']); ?></span>
                </div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-black text-slate-800 dark:text-white mb-2"><?php echo e($playerHealth['cdn_latency']); ?></div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">CDN Latency</p>
                <div class="mt-4 flex justify-center">
                    <?php
                        $cdnColor = $playerHealthStatus['cdn'] == 'Edge Ready' ? 'bg-emerald-50 text-emerald-500' : ($playerHealthStatus['cdn'] == 'Standard' ? 'bg-orange-50 text-orange-500' : 'bg-rose-50 text-rose-500');
                    ?>
                    <span class="px-2 py-1 rounded <?php echo e($cdnColor); ?> text-[10px] font-black uppercase"><?php echo e($playerHealthStatus['cdn']); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-lib'); ?>
    <script src="<?php echo e(asset('assets/global/js/vendor/apexcharts.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Latency Chart
        new ApexCharts(document.querySelector("#latencyChart"), {
            series: [{
                name: 'Latency (ms)',
                data: <?php echo json_encode($apiPerformance->pluck('avg_time'), 15, 512) ?>
            }],
            chart: { type: 'line', height: 250, toolbar: { show: false } },
            stroke: { curve: 'stepline', width: 2 },
            colors: ['#6366f1'],
            xaxis: { categories: <?php echo json_encode($apiPerformance->pluck('hour'), 15, 512) ?> },
            grid: { borderColor: '#f1f5f9' },
            tooltip: { theme: 'dark' }
        }).render();

        // Error Distribution
        new ApexCharts(document.querySelector("#errorChart"), {
            series: <?php echo json_encode($errorRates->pluck('count'), 15, 512) ?>,
            labels: <?php echo json_encode($errorRates->pluck('status_code')->map(fn($s) => 'Status '.$s), 15, 512) ?>,
            chart: { type: 'donut', height: 250 },
            colors: ['#10b981', '#f59e0b', '#ef4444', '#6366f1'],
            legend: { position: 'bottom', labels: { colors: '#94a3b8' } },
            dataLabels: { enabled: false },
            tooltip: { theme: 'dark' }
        }).render();
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\analytics\technical.blade.php ENDPATH**/ ?>