
<?php $__env->startSection('title', 'Plans'); ?>
<?php $__env->startSection('header_title', 'Monthly Plans'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
        <?php echo $__env->make('admin.components.header-toolbar', ['title' => 'Plans'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="px-6 pt-6">
            <?php echo $__env->make('admin.components.table-toolbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <div class="overflow-x-auto scrollbar-hide" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
            <table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Name'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('User'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Price'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Videos'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Playlist'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Status'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                <?php $__empty_1 = true; $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70"><?php echo e(__($plan->name)); ?></td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php echo e(__($plan->user?->fullname)); ?> <br>
                                            <a href="<?php echo e(route('admin.users.detail', $plan->user_id)); ?>">
                                                <span>@</span><?php echo e($plan->user?->username); ?>

                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php echo e(showAmount($plan->price)); ?>

                                        </td>

                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <a href="<?php echo e(route('admin.plan.videos.list', $plan->id)); ?>" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                                <?php echo e($plan->videos_count); ?>

                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php echo e($plan->playlists_count); ?>

                                        </td>

                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php
                                                echo $plan->statusBadge;
                                            ?>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <div class="button--group">
        <a href="<?php echo e(Route::has('admin.plan.show') ? route('admin.plan.show', $plan->id ?? 0) : (Route::has('admin.plan.detail') ? route('admin.plan.detail', $plan->id ?? 0) : 'javascript:void(0)')); ?>" class="btn btn-sm btn-outline--info" title="<?php echo app('translator')->get('View Details'); ?>">
            <i class="las la-desktop"></i>
        </a>
        <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="<?php echo e(Route::has('admin.plan.destroy') ? route('admin.plan.destroy', $plan->id ?? 0) : (Route::has('admin.plan.delete') ? route('admin.plan.delete', $plan->id ?? 0) : 'javascript:void(0)')); ?>" data-question="<?php echo app('translator')->get('Are you sure you want to delete this record?'); ?>" title="<?php echo app('translator')->get('Delete'); ?>">
            <i class="las la-trash"></i>
        </button>
        
                                                <button class="btn btn-sm btn-outline--primary editBtn"
                                                    data-plan="<?php echo e($plan); ?>"
                                                    data-action="<?php echo e(route('admin.plan.update', $plan->id)); ?>" title="<?php echo app('translator')->get('Edit'); ?>">
                                                    <i class="las la-pencil-alt"></i>
                                                </button>

                                                <?php if(@$plan->status): ?>
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                                        data-action="<?php echo e(route('admin.plan.status', $plan->id)); ?>"
                                                        data-question="<?php echo app('translator')->get('Are you sure want to disable this plan?'); ?>" title="<?php echo app('translator')->get('Disable'); ?>">
                                                        <i class="las la-eye-slash"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-outline--success confirmationBtn"
                                                        data-action="<?php echo e(route('admin.plan.status', $plan->id)); ?>"
                                                        data-question="<?php echo app('translator')->get('Are you sure want to enable this plan?'); ?>" title="<?php echo app('translator')->get('Enable'); ?>">
                                                        <i class="las la-eye"></i>
                                                    </button>
                                                <?php endif; ?>

                                                <a href="<?php echo e(route('admin.plan.videos.list', $plan->id)); ?>" class="btn btn-sm btn-outline--info" title="<?php echo app('translator')->get('Video List'); ?>">
                                                    <i class="las la-video"></i>
                                                </a>
                                                
                                                <a href="<?php echo e(route('admin.plan.playlist.list', $plan->id)); ?>" class="btn btn-sm btn-outline--dark" title="<?php echo app('translator')->get('Playlist List'); ?>">
                                                    <i class="las la-list"></i>
                                                </a>
                                            </div>
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
                <?php if($plans->hasPages()): ?>
                    <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                        <?php echo paginateLinks($plans) ?>
                    </div>
                <?php endif; ?>
            </div><!-- card end -->
        </div>
    </div>

    <div id="createModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <form method="post">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form--label"><?php echo app('translator')->get('Name'); ?></label>
                            <input type="text" class="form-control" name="name" required
                                placeholder="<?php echo app('translator')->get('Enter plan name'); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form--label"><?php echo app('translator')->get('Price'); ?></label>
                            <div class="input-group">
                                <input class="form-control" name="price" type="number" placeholder="<?php echo app('translator')->get('Enter Price'); ?>"
                                    step="any" required>
                                <span class="input-group-text btn--base border-0"><?php echo e(__(gs('cur_text'))); ?></span>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45"><?php echo app('translator')->get('Update'); ?></button>
                    </div>
                </form>
            </div>
        </div>
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

<?php $__env->startPush('breadcrumb-plugins'); ?>
    <?php if (isset($component)) { $__componentOriginale48b4598ffc2f41a085f001458a956d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale48b4598ffc2f41a085f001458a956d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-form','data' => ['placeholder' => 'Title/username']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('search-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'Title/username']); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        (function($) {

            "use strict";

            $('.editBtn').on('click', function() {
                var modal = $('#createModal');
                var data = $(this).data('plan');

                const url = $(this).data('action');
                modal.find('form').attr('action', url);

                modal.find('form').attr('action', url);
                modal.find('.modal-title').text("<?php echo app('translator')->get('Edit Plan'); ?>");
                modal.find('[name="name"]').val(data.name);
                modal.find('[name="price"]').val(parseFloat(data.price).toFixed(2));
                modal.modal('show');
            });
        })(jQuery);
    </script>
    <style>
        #createModal .modal-body {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }
    </style>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\plan\index.blade.php ENDPATH**/ ?>