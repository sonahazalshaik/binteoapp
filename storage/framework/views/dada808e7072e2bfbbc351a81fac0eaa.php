

<?php $__env->startSection('panel'); ?>
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
                <div class="overflow-x-auto scrollbar-hide"><table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('User'); ?></th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Login at'); ?></th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('IP'); ?></th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Location'); ?></th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Browser | OS'); ?></th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            <?php $__empty_1 = true; $__currentLoopData = $loginLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                        <span class="fw-bold"><?php echo e(@$log->user->fullname); ?></span>
                                        <br>
                                        <span class="small"> <a href="<?php echo e(route('admin.users.detail', $log->user_id)); ?>"><span>@</span><?php echo e(@$log->user->username); ?></a> </span>
                                    </td>

                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                        <?php echo e(showDateTime($log->created_at)); ?> <br> <?php echo e(diffForHumans($log->created_at)); ?>

                                    </td>

                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                        <span class="fw-bold">
                                        <a href="<?php echo e(route('admin.report.login.ipHistory',[$log->user_ip])); ?>"><?php echo e($log->user_ip); ?></a>
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70"><?php echo e(__($log->city)); ?> <br> <?php echo e(__($log->country)); ?></td>
                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                        <?php echo e(__($log->browser)); ?> <br> <?php echo e(__($log->os)); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                    <td class="text-muted text-center" colspan="100%"><?php echo e(__($emptyMessage)); ?></td>
                                </tr>
                            <?php endif; ?>

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <?php if($loginLogs->hasPages()): ?>
                <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                    <?php echo e(paginateLinks($loginLogs)); ?>

                </div>
                <?php endif; ?>
            </div><!-- card end -->
        </div>


    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\users\logins.blade.php ENDPATH**/ ?>