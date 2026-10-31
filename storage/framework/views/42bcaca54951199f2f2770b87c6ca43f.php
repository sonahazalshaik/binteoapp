
<?php $__env->startSection('title', 'Saved Audios'); ?>
<?php $__env->startSection('header_title', 'Saved Audio Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] shadow-sm transition-all duration-300">
    <?php echo $__env->make('admin.components.header-toolbar', [
        'title' => $pageTitle,
        'items' => $savedAudios,
        'createRoute' => null
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="px-6 pt-6">
        <?php echo $__env->make('admin.components.table-toolbar', ['showBulkActions' => false], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <div class="overflow-x-auto scrollbar-hide px-6">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30"><?php echo app('translator')->get('Audio Track'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30"><?php echo app('translator')->get('User / Creator'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30"><?php echo app('translator')->get('Saved At'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right"><?php echo app('translator')->get('Action'); ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                <?php $__empty_1 = true; $__currentLoopData = $savedAudios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $saved): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $music = $saved->reelMusic; ?>
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-white/5 flex items-center justify-center overflow-hidden shrink-0">
                                    <?php if($music->cover_image): ?>
                                        <img src="<?php echo e(getImage(getFilePath('reelMusic') . '/' . $music->cover_image)); ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <span class="material-symbols-rounded text-slate-400">music_note</span>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-slate-900 dark:text-white uppercase tracking-tight"><?php echo e($music->title); ?></p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><?php echo e($music->artist ?? 'Original Audio'); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-sm text-slate-900 dark:text-white"><?php echo e($saved->user->fullname); ?></span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><?php echo e('@' . $saved->user->username); ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold text-slate-500"><?php echo e($saved->created_at->format('M d, Y H:i')); ?></span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="<?php echo e(route('admin.reels.saved-audios.destroy', $saved->id)); ?>" data-method="DELETE" data-question="<?php echo app('translator')->get('Remove this saved audio entry?'); ?>">
                                <span class="material-symbols-rounded text-sm">delete</span><?php echo app('translator')->get('Remove'); ?>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td class="text-muted text-center py-10" colspan="100%"><?php echo app('translator')->get('No saved audio entries found'); ?></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($savedAudios->hasPages()): ?>
        <div class="card-footer py-4 px-6 border-t border-slate-100 dark:border-white/5"><?php echo e(paginateLinks($savedAudios)); ?></div>
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

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\reels\saved_audios.blade.php ENDPATH**/ ?>