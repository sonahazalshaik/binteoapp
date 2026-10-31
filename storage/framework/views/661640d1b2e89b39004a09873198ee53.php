
<?php $__env->startSection('panel'); ?>

<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
    <div class="px-6 py-8 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
        <div>
            <h3 class="text-xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Cron Schedules</h3>
            <p class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3 leading-relaxed">Manage execution intervals for system automated tasks</p>
        </div>
        <button class="h-12 px-8 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-black flex items-center justify-center gap-3 text-[10px] font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-xl addSchedule">
            <span class="material-symbols-rounded text-lg">add_circle</span>
            Add New
        </button>
    </div>

    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30"><?php echo app('translator')->get('Name'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30"><?php echo app('translator')->get('Interval'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30"><?php echo app('translator')->get('Status'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right"><?php echo app('translator')->get('Actions'); ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                <?php $__empty_1 = true; $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                        <td class="px-6 py-4">
                            <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-tight"><?php echo e(__($schedule->name)); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[11px] font-bold text-slate-700 dark:text-white/70"><?php echo e(__($schedule->interval)); ?> <?php echo app('translator')->get('Seconds'); ?></span>
                        </td>
                        <td class="px-6 py-4"> <?php echo $schedule->statusBadge; ?> </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" class="btn btn-sm btn-outline--primary updateSchedule"
                                    data-id="<?php echo e($schedule->id); ?>" data-name="<?php echo e($schedule->name); ?>" data-interval="<?php echo e($schedule->interval); ?>">
                                    <i class="las la-pen"></i> <?php echo app('translator')->get('Edit'); ?>
                                </button>

                                <?php if(!$schedule->status): ?>
                                    <button type="button" class="btn btn-sm btn-outline--success confirmationBtn"
                                        data-action="<?php echo e(route('admin.cron.schedule.status', $schedule->id)); ?>"
                                        data-question="<?php echo app('translator')->get('Are you sure to enable this schedule?'); ?>">
                                        <i class="la la-eye"></i> <?php echo app('translator')->get('Enable'); ?>
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                        data-action="<?php echo e(route('admin.cron.schedule.status', $schedule->id)); ?>"
                                        data-question="<?php echo app('translator')->get('Are you sure to disable this schedule?'); ?>">
                                        <i class="la la-eye-slash"></i> <?php echo app('translator')->get('Disable'); ?>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-10 py-32 text-center text-slate-400 font-black uppercase tracking-widest text-[9px] opacity-20"><?php echo e(__($emptyMessage ?? 'No schedules detected')); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($schedules->hasPages()): ?>
        <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
            <?php echo e(paginateLinks($schedules)); ?>

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

<!-- Premium Modal for Schedule Management -->
<div class="modal" id="addSchedule" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title "><?php echo app('translator')->get('Add Cron Schedule'); ?></h4>
                <button type="button" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 hover:text-orange-500 transition-all" data-bs-dismiss="modal">
                    <span class="material-symbols-rounded">close</span>
                </button>
            </div>
            <form class="disableSubmission resetForm" method="post" action="<?php echo e(route('admin.cron.schedule.store')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id">
                <div class="modal-body space-y-2">
                    <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'name','label' => 'Schedule Name','placeholder' => 'E.g. 5 Minutes','required' => true,'hint' => 'Enter a descriptive name for this execution interval.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'name','label' => 'Schedule Name','placeholder' => 'E.g. 5 Minutes','required' => true,'hint' => 'Enter a descriptive name for this execution interval.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['type' => 'number','name' => 'interval','label' => 'Interval (Seconds)','placeholder' => '300','required' => true,'hint' => 'Define the duration in seconds between executions.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'number','name' => 'interval','label' => 'Interval (Seconds)','placeholder' => '300','required' => true,'hint' => 'Define the duration in seconds between executions.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                </div>
                <div class="modal-footer border-t-0 pt-4">
                    <button type="submit" class="w-full h-14 rounded-2xl bg-slate-900 dark:bg-white text-white dark:text-black flex items-center justify-center gap-3 text-[11px] font-black uppercase tracking-[0.2em] hover:scale-[1.02] active:scale-95 transition-all shadow-xl ">
                        <span class="material-symbols-rounded text-lg">verified</span>
                        Save Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb-plugins'); ?>
    <?php if (isset($component)) { $__componentOriginal3b9bf6c313f6db4d5c9389e5666c89a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b9bf6c313f6db4d5c9389e5666c89a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.back','data' => ['route' => ''.e(route('admin.cron.index')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('back'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => ''.e(route('admin.cron.index')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b9bf6c313f6db4d5c9389e5666c89a5)): ?>
<?php $attributes = $__attributesOriginal3b9bf6c313f6db4d5c9389e5666c89a5; ?>
<?php unset($__attributesOriginal3b9bf6c313f6db4d5c9389e5666c89a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b9bf6c313f6db4d5c9389e5666c89a5)): ?>
<?php $component = $__componentOriginal3b9bf6c313f6db4d5c9389e5666c89a5; ?>
<?php unset($__componentOriginal3b9bf6c313f6db4d5c9389e5666c89a5); ?>
<?php endif; ?>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";
            $('.updateSchedule').on('click', function() {
                let title = "<?php echo app('translator')->get('Refine Schedule'); ?>";
                var modal = $('#addSchedule');
                let id = $(this).data('id');
                let name = $(this).data('name');
                let interval = $(this).data('interval');
                modal.find('input[name=id]').val(id);
                modal.find('input[name=name]').val(name);
                modal.find('input[name=interval]').val(interval);
                modal.find('.modal-title').text(title)
                modal.modal('show');
            });

            $('.addSchedule').on('click', function() {
                let title = "<?php echo app('translator')->get('New Schedule'); ?>";
                let modal = $('#addSchedule');
                $('.resetForm').trigger('reset');
                modal.find('input[name=id]').val('');
                modal.find('.modal-title').text(title)
                modal.modal('show');
            })
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\cron\schedule.blade.php ENDPATH**/ ?>