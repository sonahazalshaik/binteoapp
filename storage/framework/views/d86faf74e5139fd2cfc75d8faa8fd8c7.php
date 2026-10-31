
<?php $__env->startSection('title', 'Storage'); ?>
<?php $__env->startSection('header_title', 'Storage'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
                <div class="overflow-x-auto scrollbar-hide"><table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Name'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Stroge Type'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Available Space'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Status'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                <?php $__empty_1 = true; $__currentLoopData = $storages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$storage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php echo e(__($storage->name)); ?>

                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php
                                                echo $storage->storageType;
                                            ?>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php echo e($storage->available_space); ?> <span>MB</span>
                                        </td>

                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php
                                                echo $storage->statusBadge;
                                            ?>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <div class="button--group">


                                                <?php
                                                    $url = '#';
                                                    if (@$storage->type == Status::WASABI_SERVER) {
                                                        $url = route('admin.storage.wasabi.form', @$storage->id);
                                                    } elseif (@$storage->type == Status::DIGITAL_OCEAN_SERVER) {
                                                        $url = route('admin.storage.digital.ocean.form', @$storage->id);
                                                    } elseif (@$storage->type == Status::FTP_SERVER) {
                                                        $url = route('admin.storage.ftp.form', @$storage->id);
                                                    }
                                                ?>

                                                <a href="<?php echo e($url); ?>"
                                                    class="btn btn-sm btn-outline--primary editGatewayBtn">
                                                    <i class="la la-pencil"></i><?php echo app('translator')->get('Edit'); ?>
                                                </a>

                                                <button class="btn btn-sm btn-outline--success checkBtn"
                                                    data-storage=<?php echo e($storage); ?>

                                                    data-action=<?php echo e(route('admin.storage.check.config', $storage->id)); ?>>
                                                    <i class="las la-check"></i>
                                                    <?php echo app('translator')->get('Check'); ?></button>

                                                <?php if($storage->status == Status::DISABLE): ?>
                                                    <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn"
                                                        data-question="<?php echo app('translator')->get('Are you sure to enable this storage?'); ?>"
                                                        data-action="<?php echo e(route('admin.storage.status', $storage->id)); ?>">
                                                        <i class="la la-eye"></i><?php echo app('translator')->get('Enable'); ?>
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-outline--danger ms-1 confirmationBtn"
                                                        data-question="<?php echo app('translator')->get('Are you sure to disable this storage?'); ?>"
                                                        data-action="<?php echo e(route('admin.storage.status', $storage->id)); ?>">
                                                        <i class="la la-eye-slash"></i><?php echo app('translator')->get('Disable'); ?>
                                                    </button>
                                                <?php endif; ?>
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
            </div><!-- card end -->
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-form','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('search-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
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
    <div class="button--group">
                                                <a class="btn btn-sm btn-outline--primary " href="<?php echo e(route('admin.storage.wasabi.form')); ?>"><?php echo app('translator')->get('Wasabi'); ?></a>
                                                <a class="btn btn-sm btn-outline--danger " href="<?php echo e(route('admin.storage.digital.ocean.form')); ?>"><?php echo app('translator')->get('Digital Ocean'); ?></a>
                                                <a class="btn btn-sm btn-outline--success " href="<?php echo e(route('admin.storage.ftp.form')); ?>"><?php echo app('translator')->get('FTP'); ?></a>
                                            </div>
<?php $__env->stopPush(); ?>



<?php $__env->startPush('script'); ?>
    <script>
        $('.checkBtn').on('click', function() {
            let storage = $(this).data('storage');
            let action = $(this).data('action');
            const btn = $(this);


            btn.prop('disabled', true);
            btn.html(`<i class="las la-spinner la-spin"></i> <?php echo app('translator')->get('Checking...'); ?>`);

            $.ajax({
                type: "get",
                url: action,
                success: function(response) {
                    if (response.status === 'success') {
                        btn.html(`<i class="las la-check"></i> <?php echo app('translator')->get('Connected'); ?>`);
                        btn.removeClass('btn-outline--danger').addClass('btn-outline--success');
                        notify('success', response.message);
                    } else {
                        btn.html(`<i class="las la-times"></i> <?php echo app('translator')->get('Failed'); ?>`);
                        btn.removeClass('btn-outline--success').addClass('btn-outline--danger');
                        notify('error', response.message);
                    }
                    btn.prop('disabled', false);
                }

            });
        });
    </script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\storage\index.blade.php ENDPATH**/ ?>