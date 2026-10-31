
<?php $__env->startSection('panel'); ?>
    <div id="app">
        <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
                    <div class="card-body">

                        <div class="row justify-content-between mt-3">
                            <div class="col-md-7">
                                <ul>
                                    <li>
                                        <h5><?php echo app('translator')->get('Language Keywords of'); ?> <?php echo e(__($lang->name)); ?></h5>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-5 mt-md-0 mt-3">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#addModal" class="btn btn-sm btn-outline--primary float-end"><i class="fas fa-plus"></i> <?php echo app('translator')->get('Add New Key'); ?> </button>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive--sm table-responsive">
                            <table class="table table--light tabstyle--two custom-data-table white-space-wrap" id="myTable">
                                <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">
                                            <?php echo app('translator')->get('Key'); ?>
                                        </th>
                                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">
                                            <?php echo e(__($lang->name)); ?>

                                        </th>

                                        <th class="w-85"><?php echo app('translator')->get('Action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-white/5">

                                    <?php $__empty_1 = true; $__currentLoopData = $json; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                            <td class="white-space-wrap"><?php echo e($k); ?></td>
                                            <td class="text-left white-space-wrap"><?php echo e($language); ?></td>


                                            <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                                <div class="button--group">
                                                    <button type="button" data-bs-target="#editModal" data-bs-toggle="modal" data-title="<?php echo e($k); ?>" data-key="<?php echo e($k); ?>" data-value="<?php echo e($language); ?>" class="editModal btn btn-sm btn-outline--primary">
                                                        <i class="la la-pencil"></i> <?php echo app('translator')->get('Edit'); ?>
                                                    </button>

                                                    <button type="button" data-key="<?php echo e($k); ?>" data-value="<?php echo e($language); ?>" data-bs-toggle="modal" data-bs-target="#DelModal" class="btn btn-sm btn-outline--danger deleteKey">
                                                        <i class="la la-trash"></i> <?php echo app('translator')->get('Remove'); ?>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                            <td colspan="100%" class="text-center"><?php echo e(__($emptyMessage)); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if($json->hasPages()): ?>
                        <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                            <?php
                                echo paginateLinks($json);
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>


        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="addModalLabel"> <?php echo app('translator')->get('Add New'); ?></h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>

                    <form action="<?php echo e(route('admin.language.store.key', $lang->id)); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="key"><?php echo app('translator')->get('Key'); ?></label>
                                <input type="text" class="form-control" id="key" name="key" value="<?php echo e(old('key')); ?>" required>

                            </div>
                            <div class="form-group">
                                <label for="value"><?php echo app('translator')->get('Value'); ?></label>
                                <input type="text" class="form-control" id="value" name="value" value="<?php echo e(old('value')); ?>" required>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn--primary w-100 h-45"> <?php echo app('translator')->get('Submit'); ?></button>
                        </div>
                    </form>

                </div>
            </div>
        </div>


        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="editModalLabel"><?php echo app('translator')->get('Edit'); ?></h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="las la-times"></i></button>
                    </div>

                    <form action="<?php echo e(route('admin.language.update.key', $lang->id)); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <div class="form-group ">
                                <label for="inputName" class="form-title"></label>
                                <input type="text" class="form-control" name="value" required>
                            </div>
                            <input type="hidden" name="key">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn--primary w-100 h-45"><?php echo app('translator')->get('Submit'); ?></button>
                        </div>
                    </form>

                </div>
            </div>
        </div>


        <!-- Modal for DELETE -->
        <div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="DelModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="DelModalLabel"> <?php echo app('translator')->get('Confirmation Alert!'); ?></h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="las la-times"></i></button>
                    </div>
                    <div class="modal-body">
                        <p><?php echo app('translator')->get('Are you sure to delete this key from this language?'); ?></p>
                    </div>
                    <form action="<?php echo e(route('admin.language.delete.key', $lang->id)); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="key">
                        <input type="hidden" name="value">
                        <div class="modal-footer">
                            <button type="button" class="btn btn--dark" data-bs-dismiss="modal"><?php echo app('translator')->get('No'); ?></button>
                            <button type="submit" class="btn btn--primary"><?php echo app('translator')->get('Yes'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    
    <div class="modal fade" id="importModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?php echo app('translator')->get('Import Keywords'); ?></h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="las la-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><?php echo app('translator')->get('Import From'); ?></label>
                        <select class="form-control select_lang select2" data-minimum-results-for-search="-1" required>
                            <option value=""><?php echo app('translator')->get('Select One'); ?></option>
                            <option value="999"><?php echo app('translator')->get('System'); ?></option>
                            <?php $__currentLoopData = $list_lang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($data->id != $lang->id): ?>
                                    <option value="<?php echo e($data->id); ?>"><?php echo e(__($data->name)); ?></option>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal"><?php echo app('translator')->get('Close'); ?></button>
                    <button type="button" class="btn btn--primary import_lang"> <?php echo app('translator')->get('Import Now'); ?></button>
                </div><?php $__env->stopSection(); ?>


<?php $__env->startPush('breadcrumb-plugins'); ?>
    <?php if (isset($component)) { $__componentOriginale48b4598ffc2f41a085f001458a956d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale48b4598ffc2f41a085f001458a956d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-form','data' => ['placeholder' => 'Search keywords']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('search-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'Search keywords']); ?>
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
    <button type="button" class="btn btn-sm btn-outline--primary importBtn"><i class="la la-download"></i><?php echo app('translator')->get('Import Keywords'); ?></button>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";
            $(document).on('click', '.deleteKey', function() {
                var modal = $('#DelModal');
                modal.find('input[name=key]').val($(this).data('key'));
                modal.find('input[name=value]').val($(this).data('value'));
            });
            $(document).on('click', '.editModal', function() {
                var modal = $('#editModal');
                modal.find('.form-title').text($(this).data('title'));
                modal.find('input[name=key]').val($(this).data('key'));
                modal.find('input[name=value]').val($(this).data('value'));
            });


            $(document).on('click', '.importBtn', function() {
                $('#importModal').modal('show');
            });
            $(document).on('click', '.import_lang', function(e) {
                var id = $('.select_lang').val();

                if (id == '') {
                    notify('error', 'Invalide selection');

                    return 0;
                } else {
                    $.ajax({
                        type: "post",
                        url: "<?php echo e(route('admin.language.import.lang')); ?>",
                        data: {
                            id: id,
                            toLangid: "<?php echo e($lang->id); ?>",
                            _token: "<?php echo e(csrf_token()); ?>"
                        },
                        success: function(data) {
                            if (data == 'success') {
                                notify('success', 'Import Data Successfully');
                                window.location.href = "<?php echo e(url()->current()); ?>"
                            }
                        }
                    });
                }

            });
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\language\edit_lang.blade.php ENDPATH**/ ?>