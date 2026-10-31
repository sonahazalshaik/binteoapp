<?php $__env->startSection('title', $pageTitle); ?>
<?php $__env->startSection('header_title', $pageTitle); ?>

<?php $__env->startSection('panel'); ?>
<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
    <?php echo $__env->make('admin.components.header-toolbar', [
        'title' => $pageTitle,
        'items' => $logs,
        'createRoute' => null
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">User Info</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Contact Details</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Reason</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Audits</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Deleted At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                        <td class="px-6 py-4">
                            <div class="text-[12px] font-black text-slate-900 dark:text-white uppercase leading-none mb-1"><?php echo e(__($log->user_name)); ?></div>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest bg-slate-100 dark:bg-white/5 px-2 py-0.5 rounded "><?php echo e($log->role); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[11px] font-black text-slate-900 dark:text-white mb-1"><?php echo e($log->email); ?></div>
                            <div class="text-[10px] font-bold text-slate-400 tracking-wider"><?php echo e($log->phone); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-white/5 text-[10px] font-black text-slate-700 dark:text-white/80 uppercase"><?php echo e($log->reason); ?></span>
                            <?php if($log->custom_reason): ?>
                                <p class="text-[10px] text-slate-500 mt-1 max-w-xs leading-relaxed ">"<?php echo e($log->custom_reason); ?>"</p>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-[10px] font-bold text-slate-500 dark:text-white/40">
                            <div>Joined: <?php echo e($log->joined_at ? $log->joined_at->format('M d, Y') : 'N/A'); ?></div>
                            <div class="mt-1 text-emerald-500 font-black ">Final Bal: <?php echo e(showAmount($log->final_balance)); ?></div>
                        </td>
                        <td class="px-6 py-4 text-[10px] font-bold text-slate-500 dark:text-white/40 ">
                            <?php echo e($log->deleted_at->format('M d, Y H:i')); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-10 py-32 text-center text-slate-400 font-black uppercase tracking-widest text-[9px] opacity-20"><?php echo e(__($emptyMessage)); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($logs->hasPages()): ?>
        <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
            <?php echo e(paginateLinks($logs)); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\users\deletion_logs.blade.php ENDPATH**/ ?>