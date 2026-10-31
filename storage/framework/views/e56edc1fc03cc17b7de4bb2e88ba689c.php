<?php $__env->startSection('panel'); ?>
<div class="space-y-6 pb-10">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h4 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">Monetization & Growth</h4>
            <p class="text-sm text-slate-400">Financial health, ARPU, and user acquisition funnels</p>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-lg text-sm font-bold border border-indigo-100 dark:border-indigo-500/20">
            <i class="las la-wallet"></i> Total Daily Revenue: <?php echo e(showAmount($todayRevenue)); ?>

        </div>
    </div>

    <!-- Top KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">ARPU (30 Days)</p>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white"><?php echo e(showAmount($arpu)); ?></h3>
            <div class="flex items-center gap-1 mt-2">
                <span class="text-[10px] font-bold text-slate-400">Average Revenue Per User</span>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Ads Revenue</p>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white"><?php echo e(showAmount($revenueAds)); ?></h3>
            <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full mt-4 overflow-hidden">
                <?php $adsPercent = $totalRevenue30 > 0 ? ($revenueAds / $totalRevenue30) * 100 : 0; ?>
                <div class="bg-blue-500 h-full" style="width: <?php echo e($adsPercent); ?>%"></div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Subscriptions</p>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white"><?php echo e(showAmount($revenueSubs)); ?></h3>
            <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full mt-4 overflow-hidden">
                <?php $subsPercent = $totalRevenue30 > 0 ? ($revenueSubs / $totalRevenue30) * 100 : 0; ?>
                <div class="bg-emerald-500 h-full" style="width: <?php echo e($subsPercent); ?>%"></div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Video Purchases</p>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white"><?php echo e(showAmount($revenuePurchases)); ?></h3>
            <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full mt-4 overflow-hidden">
                <?php $purchPercent = $totalRevenue30 > 0 ? ($revenuePurchases / $totalRevenue30) * 100 : 0; ?>
                <div class="bg-orange-500 h-full" style="width: <?php echo e($purchPercent); ?>%"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue Trend Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <h4 class="text-lg font-black text-slate-800 dark:text-white tracking-tight mb-6">Revenue Growth (30 Days)</h4>
            <div id="revenueChart" class="h-80"></div>
            
            <!-- Recent Transactions Table -->
            <div class="mt-10 pt-10 border-t border-slate-100 dark:border-slate-800">
                <h4 class="text-md font-black text-slate-800 dark:text-white tracking-tight mb-6">Recent Revenue Sources</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 dark:border-slate-800">
                                <th class="pb-4">User / Email</th>
                                <th class="pb-4">Amount</th>
                                <th class="pb-4 text-center">Method</th>
                                <th class="pb-4 text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                            <?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="group hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-800 dark:text-white"><?php echo e($tx->username); ?></span>
                                        <span class="text-[10px] font-bold text-slate-400"><?php echo e($tx->email); ?></span>
                                    </div>
                                </td>
                                <td class="py-4">
                                    <span class="text-sm font-black text-emerald-500 tracking-tighter"><?php echo e(showAmount($tx->amount)); ?></span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="px-2 py-1 rounded-md bg-slate-100 dark:bg-slate-800 text-[9px] font-black uppercase text-slate-500">
                                        <?php echo e($tx->gateway_name ?? 'Manual'); ?>

                                    </span>
                                </td>
                                <td class="py-4 text-right">
                                    <span class="text-xs font-bold text-slate-400"><?php echo e(\Carbon\Carbon::parse($tx->created_at)->format('M d, h:i A')); ?></span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="py-10 text-center text-sm text-slate-400 ">No recent transactions found.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Traffic Sources -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <h4 class="text-lg font-black text-slate-800 dark:text-white tracking-tight mb-6">Acquisition Sources</h4>
            
            <div class="space-y-6">
                <?php
                    $totalSignups = array_sum($trafficSources);
                ?>
                
                <?php $__currentLoopData = $trafficSources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $source => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $percent = $totalSignups > 0 ? ($count / $totalSignups) * 100 : 0;
                    $colors = [
                        'Organic' => ['bg-emerald-500', 'text-emerald-600', 'border-emerald-100', 'bg-emerald-50', 'icon' => 'public'],
                        'Referral' => ['bg-indigo-500', 'text-indigo-600', 'border-indigo-100', 'bg-indigo-50', 'icon' => 'link'],
                        'Ads' => ['bg-rose-500', 'text-rose-600', 'border-rose-100', 'bg-rose-50', 'icon' => 'campaign']
                    ];
                    $style = $colors[$source];
                ?>
                <div class="relative">
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg <?php echo e($style[3]); ?> <?php echo e($style[1]); ?> border <?php echo e($style[2]); ?> flex items-center justify-center">
                                <span class="material-symbols-rounded text-[16px]"><?php echo e($style['icon']); ?></span>
                            </div>
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300"><?php echo e($source); ?></span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-black text-slate-800 dark:text-white"><?php echo e(number_format($count)); ?></span>
                            <span class="text-xs text-slate-400 font-bold ml-1">(<?php echo e(number_format($percent, 1)); ?>%)</span>
                        </div>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                        <div class="<?php echo e($style[0]); ?> h-full transition-all duration-1000" style="width: <?php echo e($percent); ?>%"></div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <div class="pt-6 mt-6 border-t border-slate-100 dark:border-slate-800">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-500/20 text-indigo-500 flex items-center justify-center flex-shrink-0">
                            <i class="las la-lightbulb text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Growth Insight</p>
                            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                                <?php if(isset($trafficSources['Organic']) && $trafficSources['Organic'] > max($trafficSources['Ads'], $trafficSources['Referral'])): ?>
                                    Organic traffic is your strongest acquisition channel today. Consider optimizing SEO metadata for videos.
                                <?php elseif(isset($trafficSources['Ads']) && $trafficSources['Ads'] > max($trafficSources['Organic'], $trafficSources['Referral'])): ?>
                                    Paid campaigns are driving the most signups. Monitor CPA (Cost Per Acquisition) closely to ensure profitability.
                                <?php else: ?>
                                    Referrals are driving strong growth. The creator economy and sharing tools are working well.
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
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
        var options = {
            series: [{
                name: 'Ads Revenue',
                data: <?php echo json_encode($history->pluck('revenue_ads'), 15, 512) ?>
            }, {
                name: 'Subscriptions',
                data: <?php echo json_encode($history->pluck('revenue_subscriptions'), 15, 512) ?>
            }, {
                name: 'Purchases',
                data: <?php echo json_encode($history->pluck('revenue_purchases'), 15, 512) ?>
            }],
            chart: {
                height: 350,
                type: 'bar',
                stacked: true,
                toolbar: { show: false }
            },
            colors: ['#3b82f6', '#10b981', '#f97316'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '40%',
                }
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: <?php echo json_encode($history->pluck('recorded_at')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d')), 15, 512) ?>,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#94a3b8', fontWeight: 600 } }
            },
            yaxis: {
                labels: { 
                    style: { colors: '#94a3b8', fontWeight: 600 },
                    formatter: function (val) { return "$" + val }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                labels: { colors: '#64748b' }
            },
            tooltip: { theme: 'dark' }
        };

        var chart = new ApexCharts(document.querySelector("#revenueChart"), options);
        chart.render();
    });
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\analytics\monetization.blade.php ENDPATH**/ ?>