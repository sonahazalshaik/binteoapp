
<?php $__env->startSection('title', 'Market Plans'); ?>
<?php $__env->startSection('header_title', 'Market Plans'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
        <?php echo $__env->make('admin.components.header-toolbar', [
            'title' => 'Market Plans',
            'createRoute' => route('admin.userplans.create'),
            'createLabel' => 'Add Market Plan'
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="px-6 pt-6">
            <?php echo $__env->make('admin.components.table-toolbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <div class="overflow-x-auto scrollbar-hide"><table class="w-full text-left border-collapse">
                        <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('S.No'); ?></th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Plan Name'); ?></th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Price'); ?></th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Duration (Months)'); ?></th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Content'); ?></th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            <?php $__empty_1 = true; $__currentLoopData = $userplans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userplan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70"><?php echo e($loop->iteration); ?></td>
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70"><?php echo e(__($userplan->plan_name)); ?></td>
                                
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70"><?php echo e(number_format($userplan->plan_price, 2)); ?> <?php echo e(__($general->cur_text)); ?></td>
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                    <?php if($userplan->plan_duration == 1): ?>
                                        <?php echo app('translator')->get('1 Month'); ?>
                                    <?php elseif($userplan->plan_duration == 12): ?>
                                        <?php echo app('translator')->get('Yearly'); ?>
                                    <?php else: ?>
                                        <?php echo e($userplan->plan_duration); ?> <?php echo app('translator')->get('Months'); ?>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70"><?php echo e(Str::limit(__($userplan->plan_content), 50)); ?></td>
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="<?php echo e(route('admin.userplans.edit', $userplan->id)); ?>" class="btn btn-sm btn-outline--info" title="<?php echo app('translator')->get('Manage Plan'); ?>">
                                            <i class="las la-cog"></i> <?php echo app('translator')->get('Manage'); ?>
                                        </a>
                                        <button class="btn btn-sm btn-outline--primary editBtn"
                                            data-userplan="<?php echo e(json_encode($userplan)); ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#userplanModal">
                                            <i class="las la-pencil-alt"></i> <?php echo app('translator')->get('Edit'); ?>
                                        </button>
                                        <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                            data-action="<?php echo e(route('admin.userplans.destroy', $userplan->id)); ?>"
                                            data-method="POST"
                                            data-question="<?php echo app('translator')->get('Are you sure you want to delete this market plan?'); ?>">
                                            <i class="las la-trash-alt"></i> <?php echo app('translator')->get('Delete'); ?>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                <td class="text-muted text-center" colspan="100%">
                                    <?php echo e(__($emptyMessage)); ?>

                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if($userplans->hasPages()): ?>
                <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                    <?php echo e(paginateLinks($userplans)); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<div class="modal fade" id="userplanModal" tabindex="-1" role="dialog" aria-labelledby="userplanModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="userplanModalLabel"></h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="las la-times"></i></button>
            </div>
            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <input type="hidden" name="id">

                    <div class="form-group">
                        <label><?php echo app('translator')->get('Plan Name (Tier Name)'); ?></label>
                        <input type="text" class="form-control" name="plan_name" required placeholder="Ex: Elite Creator">
                    </div>

                    <div class="form-group">
                        <label><?php echo app('translator')->get('Price'); ?> (<?php echo e(gs('cur_text')); ?>)</label>
                        <input type="number" step="0.01" class="form-control" name="plan_price" required placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label><?php echo app('translator')->get('Plan Duration (in months)'); ?></label>
                        <select name="plan_duration" class="form-control" required>
                            <option value=""><?php echo app('translator')->get('Select Duration'); ?></option>
                            <option value="1"><?php echo app('translator')->get('1 Month'); ?></option>
                            <option value="3"><?php echo app('translator')->get('3 Months'); ?></option>
                            <option value="6"><?php echo app('translator')->get('6 Months'); ?></option>
                            <option value="12"><?php echo app('translator')->get('Yearly'); ?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><?php echo app('translator')->get('Plan Features & Benefits'); ?></label>
                        <textarea name="plan_content" class="form-control" rows="5" required placeholder="Enter features line by line..."></textarea>
                    </div>

                    <div class="form-group">
                        <label><?php echo app('translator')->get('Marketplace Featured Status'); ?></label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured_plan" value="1" id="isFeaturedPlan">
                            <label class="form-check-label" for="isFeaturedPlan"><?php echo app('translator')->get('Show profile at the top of the marketplace'); ?></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?php echo app('translator')->get('Contact Form Access'); ?></label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="contact_access" value="1" id="contactAccess">
                            <label class="form-check-label" for="contactAccess"><?php echo app('translator')->get('Allow users to contact this creator via profile tab'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary w-100 h-45" id="btn-save"
                        value="add"><?php echo app('translator')->get('Submit'); ?></button>
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
    
    <button type="button" class="btn btn-sm btn-outline--primary" data-bs-toggle="modal"
        data-bs-target="#userplanModal" id="createBtn">
        <i class="las la-plus"></i> <?php echo app('translator')->get('Add New Market Plan'); ?>
    </button>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";
            let modal = $('#userplanModal');

            // Handle "Add New" button click
            $('#createBtn').on('click', function() {
                modal.find('.modal-title').text('<?php echo app('translator')->get('Add New Market Plan'); ?>');
                // Set the form action to the store route for new records
                modal.find('form').attr('action', '<?php echo e(route('admin.userplans.store')); ?>');
                modal.find('input[name=id]').val('');
                modal.find('form')[0].reset();
            });

            // Handle "Edit" button click
            $('.editBtn').on('click', function() {
                const userplan = $(this).data('userplan');

                modal.find('.modal-title').text('<?php echo app('translator')->get('Edit'); ?> ' + userplan.plan_name);
                // Set the form action to the update route for existing records
                let actionUrl = `<?php echo e(route('admin.userplans.update', ':id')); ?>`.replace(':id', userplan.id);
                modal.find('form').attr('action', actionUrl);
                modal.find('input[name=id]').val(userplan.id);
                modal.find('input[name=plan_name]').val(userplan.plan_name);
                modal.find('input[name=plan_price]').val(parseFloat(userplan.plan_price));
                // Updated to select the correct duration option
                modal.find('select[name=plan_duration]').val(userplan.plan_duration);
                modal.find('textarea[name=plan_content]').val(userplan.plan_content);
                modal.find('input[name=is_featured_plan]').prop('checked', userplan.is_featured_plan == 1);
                modal.find('input[name=contact_access]').prop('checked', userplan.contact_access == 1);
                modal.modal('show');
            });

            // Handle delete confirmation
            // The listener in index.blade.php was causing duplicate popups. 
            // We now rely solely on the global listener in the x-confirmation-modal component.

        })(jQuery);
    </script>
    <style>
        #userplanModal .modal-body {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }
    </style>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\userplan\index.blade.php ENDPATH**/ ?>