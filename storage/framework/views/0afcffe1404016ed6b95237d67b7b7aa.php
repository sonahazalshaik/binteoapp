
<?php $__env->startSection('panel'); ?>
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
        <?php echo $__env->make('admin.components.header-toolbar', ['title' => 'Notification History'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="px-6 pt-6">
            <?php echo $__env->make('admin.components.table-toolbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <div class="overflow-x-auto scrollbar-hide" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
            <table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('User'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Sent'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Sender'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Subject'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php if($log->user): ?>
                                                <span class="fw-bold"><?php echo e($log->user->fullname); ?></span>
                                                <br>
                                                <span class="small">
                                                    <a href="<?php echo e(route('admin.users.detail', $log->user_id)); ?>"><span>@</span><?php echo e($log->user->username); ?></a>
                                                </span>
                                            <?php else: ?>
                                                <span class="fw-bold"><?php echo app('translator')->get('System'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php echo e(showDateTime($log->created_at)); ?>

                                            <br>
                                            <?php echo e(diffForHumans($log->created_at)); ?>

                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <span class="fw-bold"><?php echo e(keyToTitle($log->notification_type)); ?></span> <br> <?php echo app('translator')->get('via'); ?> <?php echo e(__($log->sender)); ?>

                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php if($log->subject): ?> <?php echo e(__($log->subject)); ?>

                                            <?php else: ?>
                                                <?php echo app('translator')->get('N/A'); ?> <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <button class="btn btn-sm btn-outline--primary notifyDetail" data-type="<?php echo e($log->notification_type); ?>" <?php if($log->notification_type == 'email'): ?> data-message="<?php echo e(route('admin.report.email.details', $log->id)); ?>" <?php else: ?> data-message="<?php echo e($log->message); ?>" <?php if($log->image): ?> data-image="<?php echo e(asset(getFilePath('push') . '/' . $log->image)); ?>" <?php endif; ?> <?php endif; ?> data-sent_to="<?php echo e($log->sent_to); ?>"><i class="las la-desktop"></i> <?php echo app('translator')->get('Detail'); ?></button>
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
                    <?php if($logs->hasPages()): ?>
                        <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                            <?php echo e(paginateLinks($logs)); ?>

                        </div>
                    <?php endif; ?>
                </div><!-- card end -->
            </div>
        </div>


        <div class="modal fade" id="notifyDetailModal" tabindex="-1" aria-labelledby="notifyDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="notifyDetailModalLabel"><?php echo app('translator')->get('Notification Details'); ?></h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="las la-times"></i></button>
                    </div>
                    <div class="modal-body">
                        <h3 class="text-center mb-3"><?php echo app('translator')->get('To'); ?>: <span class="sent_to"></span></h3>
                        <div class="detail"></div>
                    </div><?php $__env->stopSection(); ?>


    <?php $__env->startPush('breadcrumb-plugins'); ?>
        <?php if(@$user): ?>
            <a href="<?php echo e(route('admin.users.notification.single.form', $user->id)); ?>" class="btn btn-outline--primary btn-sm"><i class="las la-paper-plane"></i> <?php echo app('translator')->get('Send Notification'); ?></a>
        <?php else: ?>
            <?php if (isset($component)) { $__componentOriginale48b4598ffc2f41a085f001458a956d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale48b4598ffc2f41a085f001458a956d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-form','data' => ['placeholder' => 'Search Username','dateSearch' => 'yes']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('search-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'Search Username','dateSearch' => 'yes']); ?>
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
        <?php endif; ?>
    <?php $__env->stopPush(); ?>



    <?php $__env->startPush('script-lib'); ?>
        <script src="<?php echo e(asset('assets/global/js/moment.min.js')); ?>"></script>
        <script src="<?php echo e(asset('assets/global/js/daterangepicker.min.js')); ?>"></script>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('style-lib'); ?>
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/global/css/daterangepicker.css')); ?>">
    <?php $__env->stopPush(); ?>


    <?php $__env->startPush('script'); ?>
        <script>
            $('.notifyDetail').on('click', function() {
                var message = ''
                if ($(this).data('image')) {
                    message += `<img src="${$(this).data('image')}" class="w-100 mb-2" alt="image">`;
                }
                message += $(this).data('message');
                var sent_to = $(this).data('sent_to');
                var modal = $('#notifyDetailModal');
                if ($(this).data('type') == 'email') {
                    var message = `<iframe src="${message}" height="500" width="100%" title="Iframe Example"></iframe>`
                }
                $('.detail').html(message)
                $('.sent_to').text(sent_to)
                modal.modal('show');
            });
        </script>
    <?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\reports\notification_history.blade.php ENDPATH**/ ?>