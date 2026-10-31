

<?php $__env->startSection('panel'); ?>
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
                <div class="overflow-x-auto scrollbar-hide"><table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('User'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Role'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Content Type'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Title'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Watched At'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php echo e(__($log->user?->fullname)); ?> <br>
                                            <a href="<?php echo e(route('admin.users.detail', $log->user_id)); ?>">
                                                <span>@</span><?php echo e($log->user?->username); ?>

                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php if($log->user?->creator_status): ?>
                                                <span class="px-2 py-0.5 rounded-md bg-indigo-500/10 text-indigo-500 text-[8px] font-black uppercase tracking-widest">Creator</span>
                                            <?php else: ?>
                                                <span class="px-2 py-0.5 rounded-md bg-slate-500/10 text-slate-500 text-[8px] font-black uppercase tracking-widest">Regular</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php if($log->reel_id): ?>
                                                <span class="flex items-center gap-1.5 text-rose-500">
                                                    <span class="material-symbols-rounded text-lg">movie</span>
                                                    <?php echo app('translator')->get('Reel'); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="flex items-center gap-1.5 text-blue-500">
                                                    <span class="material-symbols-rounded text-lg">play_circle</span>
                                                    <?php echo app('translator')->get('Video'); ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <span class="line-clamp-1">
                                                <?php if($log->reel_id): ?>
                                                    <?php echo e(__($log->reel?->title ?? 'Deleted Reel')); ?>

                                                <?php else: ?>
                                                    <?php echo e(__($log->video?->title ?? 'Deleted Video')); ?>

                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php echo e(showDateTime($log->created_at)); ?><br>
                                            <span class="text-[9px] text-slate-400 uppercase tracking-widest"><?php echo e(diffForHumans($log->created_at)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php if($log->video_id && $log->video): ?>
                                                <a href="<?php echo e(route('videos.show', $log->video)); ?>" target="_blank"
                                                    class="btn btn-sm btn-outline--info">
                                                    <i class="las la-eye me-1"></i><?php echo app('translator')->get('View'); ?>
                                                </a>
                                            <?php elseif($log->reel_id && $log->reel): ?>
                                                 <a href="<?php echo e(route('reels.show', $log->reel)); ?>" target="_blank"
                                                    class="btn btn-sm btn-outline--rose">
                                                    <i class="las la-eye me-1"></i><?php echo app('translator')->get('View'); ?>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td class="text-muted text-center py-20" colspan="100%">
                                            <div class="flex flex-col items-center justify-center opacity-40">
                                                <span class="material-symbols-rounded text-5xl mb-2">history</span>
                                                <p class="text-[10px] font-black uppercase tracking-widest">No watch history found</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <?php if($logs->hasPages()): ?>
                    <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                        <?php echo e(paginateLinks($logs)); ?>

                    </div>
                <?php endif; ?>
            </div><!-- card end -->
        </div>


    </div>
<?php $__env->stopSection(); ?>



<?php $__env->startPush('breadcrumb-plugins'); ?>
    <div class="flex flex-wrap items-center gap-2">
        <form action="" method="GET" class="flex flex-wrap items-center gap-2">
            <select name="role" class="form-control form-control-sm w-auto bg-white dark:bg-[#121212] border-slate-200 dark:border-white/10 text-[10px] font-black uppercase tracking-widest rounded-xl px-4 h-10" onchange="this.form.submit()">
                <option value=""><?php echo app('translator')->get('All Roles'); ?></option>
                <option value="regular" <?php if(request()->role == 'regular'): echo 'selected'; endif; ?>><?php echo app('translator')->get('Regular Users'); ?></option>
                <option value="creator" <?php if(request()->role == 'creator'): echo 'selected'; endif; ?>><?php echo app('translator')->get('Creators'); ?></option>
            </select>
        </form>
        <?php if (isset($component)) { $__componentOriginale48b4598ffc2f41a085f001458a956d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale48b4598ffc2f41a085f001458a956d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-form','data' => ['placeholder' => 'Username / Title','dateSearch' => 'yes']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('search-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'Username / Title','dateSearch' => 'yes']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale48b4598ffc2f41a085f001458a956d1)): ?>
<?php $attributes = $__attributesOriginale48b4598ffc2f41a085f001458a956d1; ?>
<?php unset($__attributesOriginale48b4598ffc2f41a085f001458a956d1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale48b4598ffc2f41a085f001458a956d1)): ?>
<?php $component = $__componentOriginale48b4598ffc2f41a085f001458a956d1; ?>
<?php unset($__componentOriginale48b4598ffc2f41a085f001458a956d1); ?>
<?php endif; ?>
    </div>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script-lib'); ?>
    <script src="<?php echo e(asset('assets/global/js/moment.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/global/js/daterangepicker.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('style-lib'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/global/css/daterangepicker.css')); ?>">
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\reports\watch_history.blade.php ENDPATH**/ ?>