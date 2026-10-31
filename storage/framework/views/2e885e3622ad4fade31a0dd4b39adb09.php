

<?php $__env->startSection('panel'); ?>
    <div class="card mb-5">
        <div class="card-header"><b class="lead"><?php echo app('translator')->get('Weekly Holidays'); ?></b></div>
        <form action="<?php echo e(route('admin.setting.offday')); ?>" method="post">
            <?php echo csrf_field(); ?>
            <div class="card-body">
                <div class="row">
                    <?php $__currentLoopData = week(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
                    
                        <div class="form-group col-lg-3 col-sm-6 col-md-4">
                            <label class="form-control-label"><?php echo e(__($day)); ?></label>
                            <input name="off_days[<?php echo e($day); ?>]" data-height="50" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="<?php echo app('translator')->get('Holiday'); ?>" data-off="<?php echo app('translator')->get('Withdraw day'); ?>" type="checkbox" <?php if(@gs()->off_days[$day]): ?> checked <?php endif; ?>>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <div class="form-group mb-0 mt-3">
                            <button class="btn btn--primary w-100 h-45" type="submit"><?php echo app('translator')->get('Submit'); ?></button>
                        </div>
                
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="overflow-x-auto scrollbar-hide"><table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                    <th scope="col"><?php echo app('translator')->get('SL'); ?></th>
                                    <th scope="col"><?php echo app('translator')->get('Title'); ?></th>
                                    <th scope="col"><?php echo app('translator')->get('Date'); ?></th>
                                    <th scope="col"><?php echo app('translator')->get('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                <?php $__empty_1 = true; $__currentLoopData = $holidays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $holiday): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70"><?php echo e($holidays->firstItem() + $loop->index); ?></td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70"><?php echo e(__($holiday->title)); ?></td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70"><?php echo e($holiday->day_off); ?></td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <button
                                                    class="btn btn-sm btn-outline--danger removeBtn" data-action="<?php echo e(route('admin.setting.remove', $holiday->id)); ?>">
                                                <i class="la la-trash"></i> <?php echo app('translator')->get('Delete'); ?>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="text-muted text-center" colspan="100%"><?php echo app('translator')->get('Data not found'); ?></td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <?php if($holidays->hasPages()): ?>
                    <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                        <?php echo e(paginateLinks($holidays)); ?>

                    </div>
                <?php endif; ?>
            </div><!-- card end -->
        </div>
    </div>

    <div class="modal fade" id="addHoliday">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo app('translator')->get('Add Holiday'); ?></h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="<?php echo e(route('admin.setting.holiday.submit')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Title'); ?></label>
                            <input class="form-control" name="title" type="text" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Enter Date'); ?></label>
                            <input class="form-control" name="date" type="date" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--primary w-100 h-45" type="submit"><?php echo app('translator')->get('Submit'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeModalLabel"><?php echo app('translator')->get('Remove Holiday'); ?></h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <span aria-hidden="true"><i class="las la-times"></i></span>
                    </button>
                </div>
                <form action="" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <p><?php echo app('translator')->get('Are you sure to remove this holiday?'); ?></p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--dark" data-bs-dismiss="modal" type="button"><?php echo app('translator')->get('No'); ?></button>
                        <button class="btn btn--primary" type="submit"><?php echo app('translator')->get('Yes'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb-plugins'); ?>
    <button class="btn btn-outline--primary btn-sm" data-bs-toggle="modal" data-bs-target="#addHoliday">
        <i class="las la-plus"></i> <?php echo app('translator')->get('Add New'); ?>
    </button>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";
            $('.removeBtn').on('click', function() {
                var modal = $('#removeModal');
                modal.find('form').attr('action', $(this).data('action'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\setting\holiday.blade.php ENDPATH**/ ?>