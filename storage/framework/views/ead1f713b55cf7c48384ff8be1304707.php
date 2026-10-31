
<?php $__env->startSection('panel'); ?>
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
        <div class="px-6 py-8 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Execution Logs</h3>
                <p class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3 leading-relaxed">Detailed audit trail for cron job: #<?php echo e($cronJob->id); ?></p>
            </div>
            <button type="button" class="h-12 px-8 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center gap-3 text-[10px] font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all confirmationBtn" data-action="<?php echo e(route('admin.cron.log.flush', $cronJob->id)); ?>" data-question="<?php echo app('translator')->get('Are you sure to flush all logs?'); ?>">
                <span class="material-symbols-rounded text-lg">delete_sweep</span>
                Flush Logs
            </button>
        </div>

        <div class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-white/[0.02]">
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30"><?php echo app('translator')->get('Lifecycle'); ?></th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30"><?php echo app('translator')->get('Runtime Metrics'); ?></th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 w-1/3"><?php echo app('translator')->get('Outcome / Error'); ?></th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right"><?php echo app('translator')->get('Actions'); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[8px] font-black text-slate-400 uppercase">Start:</span>
                                        <span class="text-[10px] font-bold text-slate-600 dark:text-white/60"><?php echo e(showDateTime($log->start_at)); ?></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[8px] font-black text-slate-400 uppercase">End:</span>
                                        <span class="text-[10px] font-bold text-slate-600 dark:text-white/60"><?php echo e(showDateTime($log->end_at)); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase "><?php echo e($log->duration); ?> <?php echo app('translator')->get('Seconds'); ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if($log->error): ?>
                                    <div class="bg-red-500/5 border border-red-500/10 p-3 rounded-xl">
                                        <p class="text-[10px] font-bold text-red-500 leading-relaxed line-clamp-2">"<?php echo e($log->error); ?>"</p>
                                    </div>
                                <?php else: ?>
                                    <div class="flex items-center gap-2 text-emerald-500">
                                        <span class="material-symbols-rounded text-sm">check_circle</span>
                                        <span class="text-[9px] font-black uppercase tracking-widest">Successful</span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <?php if($log->error != null): ?>
                                    <button type="button" class="btn btn-sm btn-outline--success confirmationBtn" data-action="<?php echo e(route('admin.cron.schedule.log.resolved', $log->id)); ?>" data-question="<?php echo app('translator')->get('Are you sure to resolved this log?'); ?>">
                                        <i class="la la-check"></i> <?php echo app('translator')->get('Resolved'); ?>
                                    </button>
                                <?php else: ?>
                                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">N/A</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-10 py-32 text-center text-slate-400 font-black uppercase tracking-widest text-[9px] opacity-20">No execution logs recorded</td>
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

    <?php if (isset($component)) { $__componentOriginalbd5922df145d522b37bf664b524be380 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd5922df145d522b37bf664b524be380 = $attributes; } ?>
<?php $component = App\View\Components\ConfirmationModal::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirmation-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\ConfirmationModal::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd5922df145d522b37bf664b524be380)): ?>
<?php $attributes = $__attributesOriginalbd5922df145d522b37bf664b524be380; ?>
<?php unset($__attributesOriginalbd5922df145d522b37bf664b524be380); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd5922df145d522b37bf664b524be380)): ?>
<?php $component = $__componentOriginalbd5922df145d522b37bf664b524be380; ?>
<?php unset($__componentOriginalbd5922df145d522b37bf664b524be380); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\cron\logs.blade.php ENDPATH**/ ?>