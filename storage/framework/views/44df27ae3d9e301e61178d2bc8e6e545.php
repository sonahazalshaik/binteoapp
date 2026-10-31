
<?php $__env->startSection('title', 'Advertisements'); ?>
<?php $__env->startSection('header_title', 'Advertisement Manager'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
    <?php echo $__env->make('admin.components.header-toolbar', [
        'title' => $pageTitle ?? 'Advertisement Overview',
        'items' => $advertisements,
        'createRoute' => route('admin.advertisement.create'),
        'createLabel' => 'Initialize Advertisement'
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="px-6 pt-6">
        <?php echo $__env->make('admin.components.table-toolbar', ['showBulkActions' => true, 'bulkActions' => ['delete']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
    
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                        </label>
                    </th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate"><?php echo app('translator')->get('Advertiser'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate"><?php echo app('translator')->get('Campaign Meta'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate"><?php echo app('translator')->get('Impressions'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate"><?php echo app('translator')->get('Clicks'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate"><?php echo app('translator')->get('Budget'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate"><?php echo app('translator')->get('Status'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right truncate"><?php echo app('translator')->get('Action'); ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                <?php $__empty_1 = true; $__currentLoopData = $advertisements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $advertisement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                        <td class="px-6 py-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" value="<?php echo e($advertisement->id); ?>" class="bulk-select-item w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                            </label>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[12px] font-black text-slate-900 dark:text-white uppercase leading-none mb-1"><?php echo e(__($advertisement->user->fullname)); ?></div>
                            <a href="<?php echo e(route('admin.users.detail', $advertisement->user_id)); ?>" class="text-[10px] font-bold text-slate-500 dark:text-white/50 tracking-tight"><span>@</span><?php echo e($advertisement->user->username); ?></a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[11px] font-black text-slate-900 dark:text-white mb-1"><?php echo e(__($advertisement->title)); ?></div>
                            <?php if($advertisement->url): ?>
                                <a href="<?php echo e($advertisement->url); ?>" target="_blank" class="text-[10px] font-bold text-blue-500 hover:text-blue-600 truncate max-w-[150px] inline-block"><i class="las la-external-link-alt mr-1"></i>Link</a>
                            <?php else: ?>
                                <span class="text-[10px] font-bold text-slate-400 ">No URL</span>
                            <?php endif; ?>
                            <div class="mt-2">
                                <?php echo $advertisement->adTypeBadge; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[13px] font-black text-slate-900 dark:text-white"><?php echo e(formatNumber($advertisement->impression)); ?></div>
                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Avail: <span class="text-emerald-500"><?php echo e(formatNumber($advertisement->available_impression)); ?></span></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[13px] font-black text-slate-900 dark:text-white"><?php echo e(formatNumber($advertisement->click)); ?></div>
                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Avail: <span class="text-emerald-500"><?php echo e(formatNumber($advertisement->available_click)); ?></span></div>
                        </td>
                        <td class="px-6 py-4 text-[12px] font-black text-emerald-600 dark:text-emerald-400"><?php echo e(showAmount($advertisement->total_amount)); ?></td>
                        <td class="px-6 py-4">
                            <?php echo $advertisement->statusBadge; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="<?php echo e(route('admin.advertisement.edit', $advertisement->id)); ?>" class="btn btn-sm btn-outline--warning">
                                    <i class="las la-pencil-alt"></i><?php echo app('translator')->get('Edit'); ?>
                                </a>
                                <?php if($advertisement->status == \App\Constants\Status::RUNNING): ?>
                                    <button class="confirmationBtn btn btn-sm btn-outline--danger" <?php if($advertisement->payment_status != \App\Constants\Status::PAYMENT_SUCCESS): ?> disabled <?php endif; ?> data-action="<?php echo e(route('admin.advertisement.status', $advertisement->id)); ?>" data-question="<?php echo app('translator')->get('Are you sure want to pause this advertisement?'); ?>">
                                        <i class="las la-pause"></i><?php echo app('translator')->get('Pause'); ?>
                                    </button>
                                <?php elseif($advertisement->status == \App\Constants\Status::PAUSE): ?>
                                    <button class="confirmationBtn btn btn-sm btn-outline--success" <?php if($advertisement->payment_status != \App\Constants\Status::PAYMENT_SUCCESS): ?> disabled <?php endif; ?> data-action="<?php echo e(route('admin.advertisement.status', $advertisement->id)); ?>" data-question="<?php echo app('translator')->get('Are you sure want to run this advertisement?'); ?>">
                                        <i class="las la-play"></i><?php echo app('translator')->get('Run'); ?>
                                    </button>
                                <?php endif; ?>
                                <button class="btn btn-sm btn-outline--danger confirmationBtn" 
                                        data-action="<?php echo e(route('admin.advertisement.delete', $advertisement->id)); ?>" 
                                        data-method="DELETE"
                                        data-question="<?php echo app('translator')->get('Are you sure you want to delete this advertisement?'); ?>">
                                    <i class="las la-trash"></i><?php echo app('translator')->get('Delete'); ?>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-10 py-32 text-center text-slate-400 font-black uppercase tracking-widest text-[9px] opacity-20"><?php echo e(__($emptyMessage ?? 'No advertisements found')); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($advertisements->hasPages()): ?>
        <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
            <?php echo e(paginateLinks($advertisements)); ?>

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

<?php $__env->startPush('breadcrumb-plugins'); ?>
    <div class="flex items-center gap-3">
        <?php if (isset($component)) { $__componentOriginale48b4598ffc2f41a085f001458a956d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale48b4598ffc2f41a085f001458a956d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-form','data' => ['placeholder' => 'Username / Title']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('search-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'Username / Title']); ?>
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


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\advertisements\index.blade.php ENDPATH**/ ?>