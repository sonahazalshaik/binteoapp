<?php $__env->startSection('title', 'Content Performance'); ?>
<?php $__env->startSection('header_title', 'Performance Analytics Database'); ?>

<?php $__env->startSection('panel'); ?>
<div class="p-4 mb-4 grid grid-cols-1 sm:grid-cols-4 gap-4">
    <div class="p-4 rounded-xl border border-slate-100 dark:border-white/10 bg-white dark:bg-slate-900">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Reel Scroll Stop Rate</p>
        <p class="text-lg font-black text-slate-800 dark:text-white mt-1"><?php echo e($scrollStopRate ?? 0); ?>%</p>
    </div>
</div>
<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
    <?php echo $__env->make('admin.components.header-toolbar', [
        'title' => $pageTitle ?? 'Content Performance',
        'items' => $topContent,
        'createRoute' => null,
        'createLabel' => null
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <div class="overflow-x-auto scrollbar-hide mt-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate"><?php echo app('translator')->get('Video'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate"><?php echo app('translator')->get('Creator'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate text-right"><?php echo app('translator')->get('Views'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate"><?php echo app('translator')->get('Completion'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate"><?php echo app('translator')->get('Rewatches'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right truncate"><?php echo app('translator')->get('Engagement'); ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                <?php $__empty_1 = true; $__currentLoopData = $topContent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-8 rounded bg-slate-200 dark:bg-white/5 border border-slate-200 dark:border-white/10 overflow-hidden flex-shrink-0">
                                    <?php if($content->video->thumb_image): ?>
                                        <img src="<?php echo e(getImage(getFilePath('thumbnail').'/'.$content->video->thumb_image)); ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center">
                                            <span class="material-symbols-rounded text-[14px] text-slate-400">image</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <div class="text-[12px] font-black text-slate-900 dark:text-white uppercase leading-none mb-1 line-clamp-1" title="<?php echo e($content->video->title); ?>"><?php echo e(Str::limit($content->video->title, 30)); ?></div>
                                    <div class="text-[10px] font-bold text-slate-500 dark:text-white/50 tracking-tight"><?php echo e($content->video->slug); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[11px] font-black text-slate-900 dark:text-white"><span>@</span><?php echo e(@$content->video->user->username); ?></div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-[13px] font-black text-emerald-600 dark:text-emerald-400"><?php echo e(number_format($content->total_views)); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-black text-slate-700 dark:text-white/80 w-10"><?php echo e(number_format($content->avg_completion, 1)); ?>%</span>
                                <div class="w-16 bg-slate-100 dark:bg-white/5 h-1.5 rounded-full overflow-hidden border border-slate-200 dark:border-white/10">
                                    <div class="bg-gradient-to-r from-emerald-400 to-emerald-500 h-full" style="width: <?php echo e($content->avg_completion); ?>%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-black text-slate-700 dark:text-white/80"><?php echo e(number_format($content->total_rewatches)); ?></span>
                                <span class="text-[9px] font-black text-indigo-600 dark:text-indigo-400">(<?php echo e(number_format($content->rewatch_rate, 1)); ?>%)</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <?php
                                $engagementScore = ($content->avg_completion * 0.6) + (min($content->rewatch_rate, 100) * 0.4);
                            ?>
                            <?php if($engagementScore > 70): ?>
                                <span class="px-2 py-1 rounded border border-emerald-200 dark:border-emerald-500/20 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[9px] font-black uppercase tracking-widest">High</span>
                            <?php elseif($engagementScore > 40): ?>
                                <span class="px-2 py-1 rounded border border-orange-200 dark:border-orange-500/20 bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 text-[9px] font-black uppercase tracking-widest">Moderate</span>
                            <?php else: ?>
                                <span class="px-2 py-1 rounded border border-rose-200 dark:border-rose-500/20 bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 text-[9px] font-black uppercase tracking-widest">Low</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-10 py-32 text-center text-slate-400 font-black uppercase tracking-widest text-[9px] opacity-20"><?php echo e(__($emptyMessage ?? 'No performance data available')); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\analytics\content.blade.php ENDPATH**/ ?>