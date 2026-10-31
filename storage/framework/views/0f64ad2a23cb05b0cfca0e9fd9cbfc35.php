<?php $__env->startSection('title', 'Policy Violators'); ?>
<?php $__env->startSection('header_title', 'Policy Violations Database'); ?>

<?php $__env->startSection('panel'); ?>
<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300" x-data="{}">
    <?php echo $__env->make('admin.components.header-toolbar', [
        'title' => $pageTitle ?? 'Policy Violators',
        'items' => $users,
        'createRoute' => route('admin.users.create'),
        'createLabel' => 'Architect Node'
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="px-6 pt-6">
        <?php echo $__env->make('admin.components.table-toolbar', ['showBulkActions' => false], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
    
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate"><?php echo app('translator')->get('User'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate"><?php echo app('translator')->get('Email & Mobile'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate"><?php echo app('translator')->get('Status'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate text-center"><?php echo app('translator')->get('Active Strikes'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right truncate"><?php echo app('translator')->get('Action'); ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                        <td class="px-6 py-4">
                            <div class="text-[12px] font-black text-slate-900 dark:text-white uppercase leading-none mb-1"><?php echo e(__($user->fullname)); ?></div>
                            <a href="<?php echo e(route('admin.users.detail', $user->id)); ?>" class="text-[10px] font-bold text-slate-500 dark:text-white/50 tracking-tight"><span>@</span><?php echo e($user->username); ?></a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[11px] font-black text-slate-900 dark:text-white mb-1"><?php echo e($user->email); ?></div>
                            <div class="text-[10px] font-bold text-slate-400 tracking-wider"><?php echo e($user->mobile); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($user->status == Status::USER_ACTIVE): ?>
                                <span class="px-2 py-1 rounded-md bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase">Active</span>
                            <?php else: ?>
                                <span class="px-2 py-1 rounded-md bg-rose-50 dark:bg-rose-500/10 text-rose-500 text-[10px] font-black uppercase" title="<?php echo e($user->ban_reason); ?>">Banned</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if($user->copyright_strikes_count > 0): ?>
                                <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-rose-50 dark:bg-rose-500/10 text-rose-500 font-black text-[11px]"><?php echo e($user->copyright_strikes_count); ?></div>
                            <?php else: ?>
                                <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 font-black text-[11px]">0</div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="<?php echo e(route('admin.users.detail', $user->id)); ?>" class="h-8 px-3 rounded-xl bg-indigo-500 text-white flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all active:scale-95 shadow-sm">
                                    <span class="material-symbols-rounded text-[14px]">desktop_windows</span> Details
                                </a>
                                <?php if($user->status == Status::USER_ACTIVE): ?>
                                    <button type="button" class="h-8 px-3 rounded-xl bg-rose-500 text-white flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest hover:bg-rose-600 transition-all active:scale-95 shadow-sm" @click="$dispatch('open-ban-modal', { id: <?php echo e($user->id); ?>, name: '<?php echo e($user->username); ?>' })">
                                        <span class="material-symbols-rounded text-[14px]">block</span> Ban
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="h-8 px-3 rounded-xl bg-emerald-500 text-white flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all active:scale-95 shadow-sm confirmationBtn" data-action="<?php echo e(route('admin.users.status', $user->id)); ?>" data-question="Are you sure you want to unban this user?">
                                        <span class="material-symbols-rounded text-[14px]">check_circle</span> Unban
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-10 py-32 text-center text-slate-400 font-black uppercase tracking-widest text-[9px] opacity-20"><?php echo e(__($emptyMessage ?? 'No policy violators found')); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($users->hasPages()): ?>
        <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
            <?php echo e(paginateLinks($users)); ?>

        </div>
    <?php endif; ?>
</div>

<?php echo $__env->make('admin.users.partials.modals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\users\violators.blade.php ENDPATH**/ ?>