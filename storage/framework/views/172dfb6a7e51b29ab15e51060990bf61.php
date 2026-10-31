<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="py-8 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-10">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-10 gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight">Revenue Analytics</h1>
                    <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2">Breakdown of all income streams</p>
                </div>
                <a href="<?php echo e(route('user.transactions')); ?>" class="px-6 py-3 bg-white dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/10 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest flex items-center gap-2 hover:border-red-500 hover:text-red-500 transition-all">
                    <span class="material-symbols-rounded text-lg">receipt_long</span>
                    All Transactions
                </a>
            </div>

            <!-- Copyright & Policy Alert (Conditional) -->
            <?php
                $activeStrikes = auth()->user()->copyrightStrikes()->where('status', 'active')->with('video')->latest()->get();
            ?>

            <?php if($activeStrikes->count() > 0): ?>
            <div class="mb-10">
                <div class="bg-rose-500/5 dark:bg-rose-500/10 border border-rose-500/20 rounded-[2rem] p-6 lg:p-8">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <div class="flex items-center gap-5">
                            <div class="w-12 h-12 rounded-2xl bg-rose-500 flex items-center justify-center text-white shadow-xl shadow-rose-500/30">
                                <span class="material-symbols-rounded text-2xl">gavel</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">Policy Compliance Alert</h3>
                                <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest mt-1 flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                    <?php echo e($activeStrikes->count()); ?> Active <?php echo e(Str::plural('Strike', $activeStrikes->count())); ?> (<?php echo e($activeStrikes->count()); ?>/3)
                                </p>
                            </div>
                        </div>
                        <a href="<?php echo e(route('studio.dashboard')); ?>" class="px-5 py-2.5 bg-rose-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-rose-500/20 hover:scale-105 transition-all">Review Strikes</a>
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php $__currentLoopData = $activeStrikes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $strike): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white dark:bg-[#1A1A1A] p-4 rounded-2xl border border-rose-500/10 flex flex-col gap-3">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-rounded text-rose-500 text-lg">videocam</span>
                                <h4 class="text-[11px] font-black text-gray-900 dark:text-white truncate uppercase tracking-tight"><?php echo e($strike->video?->title ?? 'Removed Content'); ?></h4>
                            </div>
                            <p class="text-[10px] font-bold text-gray-500 dark:text-white/50 leading-relaxed line-clamp-1">"<?php echo e($strike->reason); ?>"</p>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Earnings Cards Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 mb-8">
                <?php
                    $earningsCards = [
                        ['Total Earnings', showAmount($totalEarnings ?? 0, 0), 'trending_up', 'linear-gradient(135deg, #10b981, #059669)'],
                        ['Settled Ad Rev', showAmount($settledAdEarnings ?? 0, 0), 'ads_click', 'linear-gradient(135deg, #3b82f6, #4f46e5)'],
                        ['Estimated Ad Rev (Live)', showAmount($estimatedAdEarnings ?? 0, 0), 'dynamic_feed', 'linear-gradient(135deg, #f59e0b, #ea580c)'],
                        ['Video Sales', showAmount($videoSalesEarnings ?? 0, 0), 'movie', 'linear-gradient(135deg, #8b5cf6, #7c3aed)'],
                        ['Subscriptions', showAmount($planEarnings ?? 0, 0), 'loyalty', 'linear-gradient(135deg, #ef4444, #e11d48)'],
                        ['Platform Fee (30%)', showAmount($adminCommission ?? 0, 0), 'receipt_long', 'linear-gradient(135deg, #64748b, #475569)'],
                    ];
                ?>

                <?php $__currentLoopData = $earningsCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="relative overflow-hidden rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-md group hover:scale-[1.02] transition-all duration-500" style="background: <?php echo e($card[3]); ?>">
                    <div class="relative z-10 flex sm:flex-col items-center sm:items-start gap-3 sm:gap-0">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white sm:mb-3 group-hover:scale-110 transition-transform shrink-0">
                            <span class="material-symbols-rounded text-base sm:text-lg"><?php echo e($card[2]); ?></span>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm sm:text-base font-black text-white tracking-tight truncate"><?php echo e($card[1]); ?></h3>
                            <p class="text-[7px] sm:text-[8px] font-black text-white/70 uppercase tracking-widest truncate"><?php echo e($card[0]); ?></p>
                        </div>
                    </div>
                    <span class="material-symbols-rounded absolute -right-2 -bottom-2 text-[50px] sm:text-[70px] text-white/10 rotate-12 group-hover:scale-125 transition-transform duration-700 pointer-events-none"><?php echo e($card[2]); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Recent Earnings Table -->
            <!-- Recent Earnings Table -->
            <div x-data="{ 
                showDetailModal: false, 
                selectedTrx: null,
                copied: false,
                copyTrx(text) {
                    navigator.clipboard.writeText(text);
                    this.copied = true;
                    setTimeout(() => this.copied = false, 2000);
                }
            }" class="bg-white dark:bg-[#181818] rounded-[2rem] sm:rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden transition-colors duration-500">
                <div class="px-6 sm:px-8 py-5 sm:py-6 border-b border-gray-50 dark:border-white/5 flex items-center justify-between">
                    <h2 class="text-sm sm:text-lg font-black text-gray-900 dark:text-white uppercase tracking-widest">Recent Earnings</h2>
                    <a href="<?php echo e(route('user.transactions')); ?>" class="text-[10px] font-black text-red-500 uppercase tracking-widest hover:text-red-600 flex items-center gap-1 transition-colors">
                        View All <span class="material-symbols-rounded text-sm">arrow_forward</span>
                    </a>
                </div>

                <!-- Desktop Table -->
                <div class="hidden md:block">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50 dark:bg-white/[0.02]">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">TRX ID</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Video</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Amount</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Commission Fee</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Post Balance</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Details</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            <?php $__empty_1 = true; $__currentLoopData = $recentTransactions ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50/30 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="px-8 py-5">
                                    <span class="font-black text-sm text-gray-900 dark:text-white font-mono"><?php echo e($trx->trx); ?></span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-sm font-bold text-gray-700 dark:text-gray-300"><?php echo e(showDateTime($trx->created_at)); ?></div>
                                    <div class="text-[9px] font-bold text-gray-400 uppercase mt-0.5"><?php echo e(diffForHumans($trx->created_at)); ?></div>
                                </td>
                                <td class="px-8 py-5">
                                    <?php if($trx->video): ?>
                                    <a href="<?php echo e(route('videos.show', $trx->video)); ?>" class="text-sm font-bold text-red-500 hover:text-red-600 truncate max-w-[180px] block" title="<?php echo e($trx->video->title); ?>">
                                        <?php echo e($trx->video->title); ?>

                                    </a>
                                    <?php else: ?>
                                    <span class="text-xs font-bold text-gray-400">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span class="font-black text-sm <?php echo e($trx->trx_type == '+' ? 'text-emerald-600' : 'text-red-500'); ?>">
                                        <?php echo e($trx->trx_type); ?> <?php echo e(showAmount($trx->amount, 0)); ?>

                                    </span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <?php if($trx->charge > 0): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-rose-500/10 text-rose-500 dark:text-rose-400 font-black text-xs font-mono">
                                            -<?php echo e(showAmount($trx->charge, 0)); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-xs font-bold text-gray-400 dark:text-gray-500">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-5 text-center text-sm font-bold text-gray-600 dark:text-gray-400">
                                    <?php echo e(showAmount($trx->post_balance, 0)); ?>

                                </td>
                                <td class="px-8 py-5 text-right whitespace-nowrap">
                                    <button type="button"
                                            @click="selectedTrx = <?php echo e(json_encode([
                                                'trx' => $trx->trx,
                                                'amount' => showAmount($trx->amount, 0),
                                                'charge' => showAmount($trx->charge ?? 0, 0),
                                                'post_balance' => showAmount($trx->post_balance, 0),
                                                'trx_type' => $trx->trx_type,
                                                'remark' => __(keyToTitle($trx->remark ?? 'General')),
                                                'raw_remark' => $trx->remark,
                                                'details' => $trx->details,
                                                'date' => showDateTime($trx->created_at, 'M d, Y - h:i A'),
                                                'time_ago' => diffForHumans($trx->created_at),
                                                'video_title' => $trx->video?->title,
                                                'video_url' => $trx->video ? route('videos.show', $trx->video) : null,
                                            ])); ?>; showDetailModal = true;" 
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-orange-500/10 hover:bg-gradient-to-r hover:from-[#ff5200] hover:to-orange-500 text-orange-600 hover:text-white font-black text-[11px] uppercase tracking-wider transition-all duration-200 border border-orange-500/20 active:scale-95 shadow-sm">
                                        <span class="material-symbols-rounded text-sm">visibility</span>
                                        <span>Detail</span>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="py-16 text-center">
                                    <span class="material-symbols-rounded text-5xl text-gray-200 dark:text-white/5 mb-4 block">payments</span>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No earnings recorded yet</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="md:hidden divide-y divide-gray-50 dark:divide-white/5">
                    <?php $__empty_1 = true; $__currentLoopData = $recentTransactions ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-4 space-y-3">
                        <div class="flex justify-between items-start">
                            <div class="min-w-0 flex-1">
                                <h4 class="font-black text-gray-900 dark:text-white text-sm truncate font-mono"><?php echo e($trx->trx); ?></h4>
                                <?php if($trx->video): ?>
                                <a href="<?php echo e(route('videos.show', $trx->video)); ?>" class="text-[10px] font-bold text-red-500 hover:text-red-600 truncate block"><?php echo e($trx->video->title); ?></a>
                                <?php endif; ?>
                                <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest mt-0.5"><?php echo e(diffForHumans($trx->created_at)); ?></p>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                <span class="font-black text-sm shrink-0 <?php echo e($trx->trx_type == '+' ? 'text-emerald-600' : 'text-red-500'); ?>">
                                    <?php echo e($trx->trx_type); ?> <?php echo e(showAmount($trx->amount, 0)); ?>

                                </span>
                                <?php if($trx->charge > 0): ?>
                                    <span class="text-[10px] font-black text-rose-500 bg-rose-500/10 px-2 py-0.5 rounded-md">
                                        Fee: -<?php echo e(showAmount($trx->charge, 0)); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex items-center justify-between gap-2 pt-1">
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest truncate flex-1"><?php echo e(__($trx->details)); ?></p>
                            <button type="button"
                                    @click="selectedTrx = <?php echo e(json_encode([
                                        'trx' => $trx->trx,
                                        'amount' => showAmount($trx->amount, 0),
                                        'charge' => showAmount($trx->charge ?? 0, 0),
                                        'post_balance' => showAmount($trx->post_balance, 0),
                                        'trx_type' => $trx->trx_type,
                                        'remark' => __(keyToTitle($trx->remark ?? 'General')),
                                        'raw_remark' => $trx->remark,
                                        'details' => $trx->details,
                                        'date' => showDateTime($trx->created_at, 'M d, Y - h:i A'),
                                        'time_ago' => diffForHumans($trx->created_at),
                                        'video_title' => $trx->video?->title,
                                        'video_url' => $trx->video ? route('videos.show', $trx->video) : null,
                                    ])); ?>; showDetailModal = true;"
                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-orange-500/10 text-orange-600 font-black text-[10px] uppercase tracking-wider border border-orange-500/20 active:scale-95 shrink-0">
                                <span class="material-symbols-rounded text-xs">visibility</span>
                                <span>Detail</span>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="py-16 text-center">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No earnings yet</p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Transaction Detail Popup Modal -->
                <template x-teleport="body">
                    <div x-show="showDetailModal" 
                         x-cloak
                         class="fixed inset-0 z-[999999] flex items-center justify-center p-4">
                        
                        <!-- Backdrop -->
                        <div x-show="showDetailModal"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             @click="showDetailModal = false"
                             class="fixed inset-0 bg-black/70 backdrop-blur-sm"></div>

                        <!-- Panel Card -->
                        <div x-show="showDetailModal"
                             x-transition:enter="transition ease-out duration-300 transform"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200 transform"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                             class="relative w-full max-w-lg bg-white dark:bg-[#18181b] rounded-[24px] sm:rounded-[28px] shadow-2xl border border-slate-100 dark:border-white/10 overflow-hidden z-10 p-6 sm:p-7">

                            <!-- Header -->
                            <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-white/5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-white shadow-md shrink-0"
                                         :class="selectedTrx?.trx_type === '+' ? 'bg-emerald-500 shadow-emerald-500/20' : 'bg-rose-500 shadow-rose-500/20'">
                                        <template x-if="selectedTrx?.trx_type === '+'">
                                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                            </svg>
                                        </template>
                                        <template x-if="selectedTrx?.trx_type !== '+'">
                                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                            </svg>
                                        </template>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tight">Transaction Detail</h3>
                                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest" x-text="selectedTrx?.time_ago"></p>
                                    </div>
                                </div>

                                <button @click="showDetailModal = false" 
                                        type="button"
                                        class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#ff5200] to-orange-500 hover:from-orange-600 hover:to-amber-500 text-white shadow-sm shadow-orange-500/30 flex items-center justify-center transition-all active:scale-95">
                                    <svg class="w-3.5 h-3.5 stroke-white stroke-[2.5]" fill="none" viewBox="0 0 24 24">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </div>

                            <!-- Amount Banner -->
                            <div class="my-5 p-4 rounded-2xl bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-white/5 flex items-center justify-between">
                                <div>
                                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 block mb-0.5">Amount Transferred</span>
                                    <span class="text-2xl font-black" 
                                          :class="selectedTrx?.trx_type === '+' ? 'text-emerald-500' : 'text-rose-500'" 
                                          x-text="(selectedTrx?.trx_type || '') + ' ' + (selectedTrx?.amount || '0.00')">
                                    </span>
                                </div>
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider"
                                      :class="selectedTrx?.trx_type === '+' ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-500 border border-rose-500/20'">
                                    Settled
                                </span>
                            </div>

                            <!-- Key-Value Breakdown Grid -->
                            <div class="space-y-3 text-xs">
                                <!-- TRX Hash -->
                                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/70 dark:bg-white/[0.02]">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">TRX ID</span>
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono font-bold text-slate-900 dark:text-white text-xs select-all" x-text="selectedTrx?.trx"></span>
                                        <button @click="copyTrx(selectedTrx?.trx)" class="p-1 rounded text-slate-400 hover:text-orange-500 transition-colors" title="Copy Transaction ID">
                                            <span class="material-symbols-rounded text-sm" x-text="copied ? 'check' : 'content_copy'"></span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Date & Time -->
                                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/70 dark:bg-white/[0.02]">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Date & Time</span>
                                    <span class="font-bold text-slate-700 dark:text-slate-300" x-text="selectedTrx?.date"></span>
                                </div>

                                <!-- Post Balance -->
                                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/70 dark:bg-white/[0.02]">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Wallet Balance After</span>
                                    <span class="font-black text-slate-900 dark:text-white" x-text="selectedTrx?.post_balance"></span>
                                </div>

                                <!-- Admin Fee / Charge -->
                                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/70 dark:bg-white/[0.02]">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Platform Fee / Charge</span>
                                    <span class="font-bold text-slate-600 dark:text-slate-400" x-text="selectedTrx?.charge || '₹0.00'"></span>
                                </div>

                                <!-- Related Video if any -->
                                <template x-if="selectedTrx?.video_title">
                                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/70 dark:bg-white/[0.02]">
                                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Associated Video</span>
                                        <a :href="selectedTrx?.video_url" target="_blank" class="font-bold text-red-500 hover:underline truncate max-w-[200px]" x-text="selectedTrx?.video_title"></a>
                                    </div>
                                </template>

                                <!-- Full Description / Signature -->
                                <div class="p-3.5 rounded-xl bg-orange-50/60 dark:bg-orange-950/20 border border-orange-200/50 dark:border-orange-500/10">
                                    <span class="text-[9px] font-black uppercase tracking-widest text-orange-600 dark:text-orange-400 block mb-1">Details & Remarks</span>
                                    <p class="font-bold text-xs text-slate-800 dark:text-slate-200 leading-relaxed" x-text="selectedTrx?.details"></p>
                                </div>
                            </div>

                            <!-- Footer CTA -->
                            <div class="mt-6 flex justify-end">
                                <button @click="showDetailModal = false"
                                        type="button"
                                        class="px-6 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-white/10 dark:hover:bg-white/20 text-slate-700 dark:text-white font-black text-xs uppercase tracking-wider transition-all active:scale-95">
                                    Close
                                </button>
                            </div>

                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>

<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\client\earnings.blade.php ENDPATH**/ ?>