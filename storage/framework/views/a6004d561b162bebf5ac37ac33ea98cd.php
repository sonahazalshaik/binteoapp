
<?php $__env->startSection('title', 'Not Interested Videos'); ?>
<?php $__env->startSection('header_title', 'Not Interested Feedbacks'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] shadow-sm transition-all duration-300">
    <div class="px-6 pt-6">
        <h4 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-widest mb-2">User Feedbacks</h4>
        <p class="text-xs text-slate-400 mb-6 uppercase tracking-[0.2em]">Videos marked as "Not Interested" by users</p>
    </div>
    
    <div class="overflow-x-auto scrollbar-hide px-6 pb-6">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30"><?php echo app('translator')->get('User'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30"><?php echo app('translator')->get('Video'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30"><?php echo app('translator')->get('Marked At'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right"><?php echo app('translator')->get('Action'); ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                <?php $__empty_1 = true; $__currentLoopData = $interactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $interaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-white/5 overflow-hidden">
                                <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $interaction->user->image)); ?>" class="w-full h-full object-cover" onerror="this.src='<?php echo e(asset('assets/images/avatar.png')); ?>'">
                            </div>
                            <div>
                                <a href="<?php echo e(route('admin.users.detail', $interaction->user_id)); ?>" class="font-bold text-sm text-slate-900 dark:text-white">
                                    <?php echo e($interaction->user->username); ?>

                                </a>
                                <p class="text-[9px] text-slate-400"><?php echo e($interaction->user->email); ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-16 h-10 rounded-lg bg-slate-100 dark:bg-white/5 overflow-hidden">
                                <img src="<?php echo e($interaction->video->getThumbnailUrl()); ?>" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="font-bold text-sm text-slate-900 dark:text-white line-clamp-1"><?php echo e($interaction->video->title); ?></p>
                                <span class="text-[9px] text-slate-400">ID: <?php echo e($interaction->video->id); ?></span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-xs font-medium text-slate-500">
                        <?php echo e(showDateTime($interaction->created_at, 'd M, Y h:i A')); ?>

                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="btn btn-sm text-white confirmationBtn" style="background-color: #e11d48 !important;" 
                                data-action="<?php echo e(route('admin.videos.not-interested.destroy', $interaction->id)); ?>" 
                                data-method="POST" data-question="<?php echo app('translator')->get('Restore this video to user feed?'); ?>">
                            <span class="material-symbols-rounded text-sm">delete</span>
                        </button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td class="text-muted text-center py-10" colspan="100%"><?php echo app('translator')->get('No data found'); ?></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($interactions->hasPages()): ?>
        <div class="card-footer py-4 px-6 border-t border-slate-100 dark:border-white/5">
            <?php echo e(paginateLinks($interactions)); ?>

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


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\videos\not_interested.blade.php ENDPATH**/ ?>