<div class="bg-white dark:bg-[#181818] rounded-[2rem] sm:rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden transition-all duration-500">
    <!-- Desktop High-Fidelity Table (Exact Match with /client/earnings) -->
    <div class="hidden md:block">
        <table class="w-full text-left">
            <thead class="bg-gray-50/50 dark:bg-white/[0.02]">
                <tr>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">TRX ID</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Context / Details</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Amount</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Commission Fee</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Post Balance</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Details</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50/30 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="px-8 py-5">
                        <span class="font-black text-sm text-gray-900 dark:text-white font-mono"><?php echo e($trx->trx ?? 'N/A'); ?></span>
                    </td>
                    <td class="px-8 py-5">
                        <div class="text-sm font-bold text-gray-700 dark:text-gray-300"><?php echo e(showDateTime($trx->created_at)); ?></div>
                        <div class="text-[9px] font-bold text-gray-400 uppercase mt-0.5"><?php echo e(diffForHumans($trx->created_at)); ?></div>
                    </td>
                    <td class="px-8 py-5">
                        <span class="text-xs font-bold text-gray-600 dark:text-gray-400 truncate max-w-[200px] block" title="<?php echo e($trx->details); ?>">
                            <?php echo e(__($trx->details)); ?>

                        </span>
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
                                data-trx-detail="<?php echo e(json_encode([
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
                                ])); ?>"
                                @click="$dispatch('open-trx-detail', JSON.parse($el.getAttribute('data-trx-detail')))" 
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-orange-500/10 hover:bg-gradient-to-r hover:from-[#ff5200] hover:to-orange-500 text-orange-600 hover:text-white font-black text-[11px] uppercase tracking-wider transition-all duration-200 border border-orange-500/20 active:scale-95 shadow-sm">
                            <span class="material-symbols-rounded text-sm">visibility</span>
                            <span>Detail</span>
                        </button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="py-20 text-center">
                        <div class="w-20 h-20 rounded-3xl bg-gray-50 dark:bg-white/5 flex items-center justify-center mx-auto mb-4">
                            <span class="material-symbols-rounded text-5xl text-gray-200 dark:text-white/10">receipt_long</span>
                        </div>
                        <h3 class="text-base font-black text-gray-900 dark:text-white mb-1 uppercase tracking-tight">No Transactions Found</h3>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">No transaction records match the current filter.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile & Tablet Optimized Flow -->
    <div class="md:hidden divide-y divide-gray-50 dark:divide-white/5">
        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="p-4 space-y-3">
            <div class="flex justify-between items-start">
                <div class="min-w-0 flex-1">
                    <h4 class="font-black text-gray-900 dark:text-white text-sm truncate font-mono"><?php echo e($trx->trx ?? 'N/A'); ?></h4>
                    <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest mt-0.5"><?php echo e(diffForHumans($trx->created_at)); ?></p>
                </div>
                <div class="flex flex-col items-end gap-1">
                    <span class="font-black text-sm shrink-0 <?php echo e($trx->trx_type == '+' ? 'text-emerald-600' : 'text-red-500'); ?>">
                        <?php echo e($trx->trx_type); ?> <?php echo e(showAmount($trx->amount, 0)); ?>

                    </span>
                    <?php if($trx->charge > 0): ?>
                        <span class="text-[10px] font-black text-rose-500 bg-rose-500/10 px-2 py-0.5 rounded-md font-mono">
                            Fee: -<?php echo e(showAmount($trx->charge, 0)); ?>

                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="flex items-center justify-between gap-2 pt-1">
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest truncate flex-1"><?php echo e(__($trx->details)); ?></p>
                <button type="button"
                        data-trx-detail="<?php echo e(json_encode([
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
                        ])); ?>"
                        @click="$dispatch('open-trx-detail', JSON.parse($el.getAttribute('data-trx-detail')))"
                        class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-orange-500/10 text-orange-600 font-black text-[10px] uppercase tracking-wider border border-orange-500/20 active:scale-95 shrink-0">
                    <span class="material-symbols-rounded text-xs">visibility</span>
                    <span>Detail</span>
                </button>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="py-16 text-center">
            <div class="w-16 h-16 rounded-2xl bg-gray-50 dark:bg-white/5 flex items-center justify-center mx-auto mb-3">
                <span class="material-symbols-rounded text-4xl text-gray-200 dark:text-white/10">receipt_long</span>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No transactions found</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Enhanced Pagination (Handles AJAX interception) -->
<?php if($transactions->hasPages()): ?>
    <div class="mt-12 ajax-pagination">
        <?php echo e($transactions->links()); ?>

    </div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\client\partials\transactions_table.blade.php ENDPATH**/ ?>