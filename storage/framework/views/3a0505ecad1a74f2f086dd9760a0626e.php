<?php $__env->startSection('title', $pageTitle); ?>
<?php $__env->startSection('header_title', $pageTitle); ?>

<?php $__env->startSection('panel'); ?>
<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
    <?php echo $__env->make('admin.components.header-toolbar', [
        'title' => $pageTitle,
        'items' => $requests,
        'createRoute' => null
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">User Info</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Contact Details</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Reason</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Requested Date</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right truncate">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                        <td class="px-6 py-4">
                            <?php if($req->user): ?>
                                <div class="text-[12px] font-black text-slate-900 dark:text-white uppercase leading-none mb-1"><?php echo e(__($req->user->fullname)); ?></div>
                                <a href="<?php echo e(route('admin.users.detail', $req->user->id)); ?>" class="text-[10px] font-bold text-slate-500 dark:text-white/50 tracking-tight"><span>@</span><?php echo e($req->user->username); ?></a>
                            <?php elseif($req->marketplace): ?>
                                <div class="text-[12px] font-black text-slate-900 dark:text-white uppercase leading-none mb-1"><?php echo e(__($req->marketplace->name)); ?></div>
                                <span class="text-[9px] font-black text-indigo-500 tracking-widest uppercase bg-indigo-500/10 px-1.5 py-0.5 rounded ">Talent: <?php echo e($req->marketplace->type); ?></span>
                            <?php else: ?>
                                <span class="text-xs text-rose-500 font-bold">User Not Found</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($req->user): ?>
                                <div class="text-[11px] font-black text-slate-900 dark:text-white mb-1"><?php echo e($req->user->email); ?></div>
                                <div class="text-[10px] font-bold text-slate-400 tracking-wider">+<?php echo e($req->user->dial_code); ?><?php echo e($req->user->mobile); ?></div>
                            <?php elseif($req->marketplace): ?>
                                <div class="text-[11px] font-black text-slate-900 dark:text-white mb-1"><?php echo e($req->marketplace->email); ?></div>
                                <div class="text-[10px] font-bold text-slate-400 tracking-wider"><?php echo e($req->marketplace->number); ?></div>
                            <?php else: ?>
                                <span class="text-slate-400">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-white/5 text-[10px] font-black text-slate-700 dark:text-white/80 uppercase"><?php echo e($req->reason); ?></span>
                            <?php if($req->custom_reason): ?>
                                <p class="text-[10px] text-slate-500 mt-1 max-w-xs leading-relaxed ">"<?php echo e($req->custom_reason); ?>"</p>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-[10px] font-bold text-slate-500 dark:text-white/40 ">
                            <?php echo e($req->created_at->format('M d, Y H:i')); ?>

                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form action="<?php echo e(route('admin.users.deletion.approve', $req->id)); ?>" method="POST" class="swal-action-form inline"
                                      data-swal-title="Approve Deletion?" data-swal-text="This will delete the user account and all of their related videos, comments, and assets permanently! This action is irreversible.">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="h-8 px-3 rounded-xl bg-rose-500 hover:bg-rose-600 text-white flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest transition-all active:scale-95 shadow-sm">
                                        <span class="material-symbols-rounded text-[14px]">delete_forever</span> Approve
                                    </button>
                                </form>

                                <form action="<?php echo e(route('admin.users.deletion.reject', $req->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="h-8 px-3 rounded-xl bg-slate-500 hover:bg-slate-600 text-white flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest transition-all active:scale-95 shadow-sm">
                                        <span class="material-symbols-rounded text-[14px]">cancel</span> Reject
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-10 py-32 text-center text-slate-400 font-black uppercase tracking-widest text-[9px] opacity-20"><?php echo e(__($emptyMessage)); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($requests->hasPages()): ?>
        <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
            <?php echo e(paginateLinks($requests)); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\users\deletion_requests.blade.php ENDPATH**/ ?>