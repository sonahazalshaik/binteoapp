
<?php $__env->startSection('title', 'Campaigns'); ?>
<?php $__env->startSection('header_title', 'Campaign System'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
        <?php echo $__env->make('admin.components.header-toolbar', [
            'title' => 'Campaign Management',
            'items' => $campaigns,
            'createRoute' => Route::has('admin.campaign.create') ? route('admin.campaign.create') : 'javascript:void(0)',
            'createLabel' => 'New Campaign'
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="px-6 pt-6">
            <?php echo $__env->make('admin.components.table-toolbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <div class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-white/[0.02]">
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Title'); ?></th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Slug'); ?></th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Total Amount'); ?></th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Available Amount'); ?></th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Payment Status'); ?></th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Status'); ?></th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right whitespace-nowrap"><?php echo app('translator')->get('Actions'); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    <?php $__empty_1 = true; $__currentLoopData = $campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                            <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70"><?php echo e(__($campaign->title)); ?></td>
                            <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70"><?php echo e(__($campaign->slug)); ?></td>
                            <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                <?php echo e(showAmount($campaign->total_amount )); ?>

                            </td>
                            <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                <?php echo e(showAmount($campaign->available_amount)); ?>

                            </td>
                            <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                <?php echo $campaign->campaignPaymentStatus; ?>
                            </td>
                            <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                <?php echo $campaign->campaignStatus; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?php echo e(route('admin.campaign.detail', $campaign->id)); ?>" class="btn btn-sm btn-outline--primary" title="<?php echo app('translator')->get('Detail'); ?>">
                                        <i class="las la-desktop"></i>
                                    </a>
                                    <?php if(@$campaign->status): ?>
                                        <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                            data-action="<?php echo e(route('admin.campaign.status', $campaign->id)); ?>"
                                            data-question="<?php echo app('translator')->get('Are you sure want to disable this campaign?'); ?>">
                                            <i class="las la-eye-slash"></i>
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-outline--success confirmationBtn"
                                            data-action="<?php echo e(route('admin.campaign.status', $campaign->id)); ?>"
                                            data-question="<?php echo app('translator')->get('Are you sure want to enable this campaign?'); ?>">
                                            <i class="las la-eye"></i>
                                        </button>
                                    <?php endif; ?>
                                    <button class="btn btn-sm btn-outline--danger confirmationBtn" 
                                            data-action="<?php echo e(Route::has('admin.campaigns.destroy') ? route('admin.campaigns.destroy', $campaign->id ?? 0) : (Route::has('admin.campaigns.delete') ? route('admin.campaigns.delete', $campaign->id ?? 0) : 'javascript:void(0)')); ?>" 
                                            data-question="<?php echo app('translator')->get('Are you sure you want to delete this record?'); ?>" 
                                            title="<?php echo app('translator')->get('Delete'); ?>">
                                        <i class="las la-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                            <td class="px-10 py-32 text-center text-slate-400 font-black uppercase tracking-widest text-[9px] opacity-20" colspan="100%"><?php echo e(__($emptyMessage ?? 'No campaigns found')); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($campaigns->hasPages()): ?>
            <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                <?php echo e(paginateLinks($campaigns)); ?>

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
    <?php if (isset($component)) { $__componentOriginale48b4598ffc2f41a085f001458a956d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale48b4598ffc2f41a085f001458a956d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-form','data' => ['placeholder' => 'Name']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('search-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'Name']); ?>
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


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\campaigns\index.blade.php ENDPATH**/ ?>