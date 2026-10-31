<?php $__env->startSection('title', 'Video PPV Commissions'); ?>
<?php $__env->startSection('header_title', 'Video Commissions'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] shadow-sm transition-all duration-300">
    <div class="px-8 py-6 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight"><?php echo e($pageTitle); ?></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Platform Revenue from PPV Videos</p>
        </div>
    </div>

    <div class="overflow-x-auto scrollbar-hide px-6">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30"><?php echo app('translator')->get('Date'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30"><?php echo app('translator')->get('Video'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30"><?php echo app('translator')->get('Purchaser'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30"><?php echo app('translator')->get('Creator'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30"><?php echo app('translator')->get('Total Amount'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-emerald-500"><?php echo app('translator')->get('Creator Earn'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-rose-500"><?php echo app('translator')->get('Platform Fee'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right"><?php echo app('translator')->get('Trx ID'); ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                <?php $__empty_1 = true; $__currentLoopData = $commissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors">
                    <td class="px-6 py-4">
                        <span class="font-bold text-sm"><?php echo e(showDateTime($commission->created_at, 'M d, Y')); ?></span><br>
                        <span class="text-[9px] text-slate-400 uppercase tracking-widest"><?php echo e(diffForHumans($commission->created_at)); ?></span>
                    </td>
                    <td class="px-6 py-4">
                        <?php if($commission->video): ?>
                            <a href="<?php echo e(route('admin.videos.show', $commission->video)); ?>" class="font-bold hover:text-indigo-500">
                                <?php echo e(strLimit(@$commission->video->title, 30)); ?>

                            </a>
                        <?php else: ?>
                            <span class="text-slate-400">Deleted Video</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php if($commission->purchaser): ?>
                            <a href="<?php echo e(route('admin.users.detail', $commission->purchaser_id)); ?>" class="font-bold hover:text-indigo-500">
                                <?php echo e(@$commission->purchaser->username ?? 'Guest'); ?>

                            </a>
                        <?php else: ?>
                            <span class="text-slate-400">Unknown</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php if($commission->creator): ?>
                            <a href="<?php echo e(route('admin.users.detail', $commission->creator_id)); ?>" class="font-bold hover:text-indigo-500">
                                <?php echo e(@$commission->creator->username ?? 'System'); ?>

                            </a>
                        <?php else: ?>
                            <span class="text-slate-400">Unknown</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 font-bold">
                        <?php echo e(showAmount($commission->total_amount)); ?>

                    </td>
                    <td class="px-6 py-4 font-black text-emerald-500">
                        <?php echo e(showAmount($commission->creator_commission)); ?>

                    </td>
                    <td class="px-6 py-4 font-black text-rose-500">
                        <?php echo e(showAmount($commission->admin_commission)); ?>

                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="text-[10px] font-black font-mono tracking-widest"><?php echo e($commission->trx); ?></span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td class="text-muted text-center py-10" colspan="100%">No commissions found yet.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($commissions->hasPages()): ?>
        <div class="p-6 border-t border-slate-100 dark:border-white/5">
            <?php echo e(paginateLinks($commissions)); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\video_commissions\index.blade.php ENDPATH**/ ?>