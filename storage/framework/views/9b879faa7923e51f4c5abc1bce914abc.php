

<?php $__env->startSection('title', 'Moderation Command Center'); ?>
<?php $__env->startSection('header_title', 'Content Enforcement'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8 animate-slide-up">
    <!-- Statistics Header -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-[2.5rem] p-8 shadow-sm group hover:border-orange-500/30 transition-all duration-500 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-orange-500/5 rounded-full blur-3xl group-hover:bg-orange-500/10 transition-colors"></div>
            <div class="relative z-10 flex items-center gap-6">
                <div class="w-16 h-16 rounded-3xl bg-orange-500/10 text-orange-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                    <span class="material-symbols-rounded text-3xl">flag</span>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] mb-1">Total Reports</p>
                    <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter"><?php echo e($reports->total()); ?></h3>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-[2.5rem] p-8 shadow-sm group hover:border-amber-500/30 transition-all duration-500 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-500/5 rounded-full blur-3xl group-hover:bg-amber-500/10 transition-colors"></div>
            <div class="relative z-10 flex items-center gap-6">
                <div class="w-16 h-16 rounded-3xl bg-amber-500/10 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                    <span class="material-symbols-rounded text-3xl">pending_actions</span>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] mb-1">Pending Review</p>
                    <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter"><?php echo e($reports->where('status', 'pending')->count()); ?></h3>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-[2.5rem] p-8 shadow-sm group hover:border-emerald-500/30 transition-all duration-500 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-500/5 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-colors"></div>
            <div class="relative z-10 flex items-center gap-6">
                <div class="w-16 h-16 rounded-3xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                    <span class="material-symbols-rounded text-3xl">verified_user</span>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] mb-1">Resolved</p>
                    <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter"><?php echo e($reports->where('status', 'resolved')->count()); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white/60 dark:bg-black/20 backdrop-blur-3xl border border-white dark:border-white/5 rounded-[3rem] overflow-hidden shadow-2xl transition-all duration-500">
        <div class="p-8 border-b border-slate-100 dark:border-white/5 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Active Investigations</h2>
                <p class="text-[10px] font-black text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] mt-1">Review and manage reported platform content</p>
            </div>
            <div class="flex items-center gap-4">
                <?php echo $__env->make('admin.components.table-toolbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>

        <!-- Desktop Table View -->
        <div x-show="!$store.viewMode || $store.viewMode.mode === 'table'" x-cloak class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-white/[0.02]">
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100 dark:border-white/5">Content Data</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100 dark:border-white/5">Reporter Identity</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100 dark:border-white/5">Violation Details</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100 dark:border-white/5 text-right text-orange-500 ">Enforcement</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-white/[0.01] transition-all duration-300 group">
                        <td class="px-8 py-6">
                            <?php if($report->video_id): ?>
                                <div class="flex items-center gap-6">
                                    <div class="w-28 aspect-video rounded-2xl overflow-hidden border border-slate-200 dark:border-white/10 relative group/thumb shadow-lg transition-transform duration-500 group-hover:scale-105">
                                        <?php if($report->video->thumbnail_path): ?>
                                            <img src="<?php echo e(getImage($report->video->thumbnail_path)); ?>" class="w-full h-full object-cover">
                                        <?php endif; ?>
                                        <div class="absolute inset-0 bg-rose-600/40 backdrop-blur-[2px] flex items-center justify-center opacity-0 group-hover/thumb:opacity-100 transition-all duration-500">
                                            <span class="material-symbols-rounded text-white text-xl animate-pulse">policy</span>
                                        </div>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[14px] font-black text-slate-900 dark:text-white tracking-tight truncate max-w-[250px] "><?php echo e($report->video->title); ?></p>
                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="px-2 py-0.5 rounded-md bg-rose-500/10 text-rose-500 text-[9px] font-black uppercase tracking-widest border border-rose-500/20">Video ID #<?php echo e($report->video_id); ?></span>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter ">Published <?php echo e($report->video->created_at->diffForHumans()); ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php elseif($report->comment_id): ?>
                                <div class="flex items-center gap-6">
                                    <div class="w-14 h-14 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-600 border border-amber-500/10 group-hover:scale-110 transition-transform duration-500 shadow-inner">
                                        <span class="material-symbols-rounded text-2xl">chat_bubble</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[13px] font-medium text-slate-600 dark:text-white/70 leading-snug line-clamp-2 pr-4 border-l-2 border-amber-500/30 pl-4">"<?php echo e($report->comment->content); ?>"</p>
                                        <p class="text-[9px] font-black text-amber-600 uppercase tracking-widest mt-2">Comment Moderation</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 dark:from-white/5 dark:to-white/10 flex items-center justify-center text-slate-500 dark:text-white font-black uppercase text-sm border border-white dark:border-white/5 shadow-sm">
                                    <?php echo e(substr($report->user->name, 0, 1)); ?>

                                </div>
                                <div class="min-w-0">
                                    <p class="text-[13px] font-black text-slate-900 dark:text-white tracking-tight truncate"><?php echo e($report->user->name); ?></p>
                                    <p class="text-[9px] font-bold text-slate-400 dark:text-white/20 uppercase tracking-widest mt-0.5 ">Community Reporter</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="bg-rose-50 dark:bg-rose-500/5 border border-rose-100 dark:border-rose-500/10 rounded-2xl px-6 py-3.5 max-w-xs group-hover:bg-rose-500 group-hover:text-white transition-all duration-500">
                                <p class="text-[11px] font-black text-rose-600 dark:text-rose-400/80 group-hover:text-white leading-relaxed uppercase tracking-tighter ">Reason: <?php echo e($report->reason); ?></p>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-3 opacity-40 group-hover:opacity-100 transition-all duration-500">
                                <?php if($report->status === 'pending'): ?>
                                    <form action="<?php echo e(route('admin.reports.resolve', $report)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button class="w-11 h-11 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-emerald-500 hover:border-emerald-500/30 hover:bg-emerald-500/5 transition-all active:scale-90" title="Resolve Manually">
                                            <span class="material-symbols-rounded">done_all</span>
                                        </button>
                                    </form>
                                    <form action="<?php echo e(route('admin.reports.content.destroy', $report)); ?>" method="POST" data-swal-question="WARNING: Permanently remove this content?">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button class="w-11 h-11 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:border-rose-500/30 hover:bg-rose-500/5 transition-all active:scale-90" title="Delete Content">
                                            <span class="material-symbols-rounded">delete_forever</span>
                                        </button>
                                    </form>
                                    <?php if($report->video_id): ?>
                                    <form action="<?php echo e(route('admin.reports.strike', $report)); ?>" method="POST" data-swal-question="APPROVE & STRIKE: This will remove the video and penalize the creator. Proceed?">
                                        <?php echo csrf_field(); ?>
                                        <button class="px-6 h-11 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest shadow-xl shadow-slate-900/20 active:scale-95 transition-all" title="Enforce Strike">
                                            <span class="material-symbols-rounded text-sm">gavel</span>
                                            Issue Strike
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="flex items-center gap-2 px-4 py-1.5 rounded-xl bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                                        <span class="material-symbols-rounded text-sm">verified</span>
                                        <span class="text-[9px] font-black uppercase tracking-widest ">Resolved Case</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-10 py-40 text-center">
                            <div class="flex flex-col items-center justify-center opacity-30">
                                <span class="material-symbols-rounded text-6xl mb-4">shield_check</span>
                                <p class="text-xs font-black uppercase tracking-[0.4em]">All Content Secure</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak class="p-6 space-y-6">
            <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white dark:bg-white/5 border border-slate-100 dark:border-white/5 rounded-[2rem] p-6 shadow-sm space-y-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl <?php echo e($report->video_id ? 'bg-rose-500/10 text-rose-500' : 'bg-amber-500/10 text-amber-500'); ?> flex items-center justify-center">
                            <span class="material-symbols-rounded text-2xl"><?php echo e($report->video_id ? 'movie' : 'chat_bubble'); ?></span>
                        </div>
                        <div>
                            <p class="text-[12px] font-black text-slate-900 dark:text-white uppercase tracking-tight"><?php echo e($report->video_id ? 'Video Infraction' : 'Comment Violation'); ?></p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">By <?php echo e($report->user->name); ?></p>
                        </div>
                    </div>
                    <?php if($report->status === 'pending'): ?>
                        <span class="px-3 py-1 rounded-lg bg-amber-500/10 text-amber-600 text-[9px] font-black uppercase border border-amber-500/20 animate-pulse">Reviewing</span>
                    <?php else: ?>
                        <span class="px-3 py-1 rounded-lg bg-emerald-500 text-white text-[9px] font-black uppercase shadow-lg shadow-emerald-500/20">Secured</span>
                    <?php endif; ?>
                </div>

                <div class="p-4 bg-rose-500/5 rounded-2xl border border-rose-500/10">
                    <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest mb-1 ">Reason for Flag</p>
                    <p class="text-xs font-bold text-slate-700 dark:text-white/80 leading-relaxed"><?php echo e($report->reason); ?></p>
                </div>

                <?php if($report->status === 'pending'): ?>
                <div class="grid grid-cols-1 gap-2 pt-2">
                    <?php if($report->video_id): ?>
                    <form action="<?php echo e(route('admin.reports.strike', $report)); ?>" method="POST" class="w-full">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full h-14 rounded-2xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 flex items-center justify-center gap-3 text-[10px] font-black uppercase tracking-widest active:scale-95 transition-all shadow-xl">
                            <span class="material-symbols-rounded">gavel</span> Issue Official Strike
                        </button>
                    </form>
                    <?php endif; ?>
                    <div class="grid grid-cols-2 gap-2">
                        <form action="<?php echo e(route('admin.reports.resolve', $report)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest transition-all">
                                <span class="material-symbols-rounded text-base">check</span> Resolve
                            </button>
                        </form>
                        <form action="<?php echo e(route('admin.reports.content.destroy', $report)); ?>" method="POST">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="w-full h-12 rounded-2xl bg-rose-500/10 text-rose-500 border border-rose-500/20 flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest transition-all">
                                <span class="material-symbols-rounded text-base">delete</span> Remove
                            </button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="py-20 text-center text-slate-400 font-black uppercase tracking-[0.3em] text-[10px] opacity-20">All Clear</div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if($reports->hasPages()): ?>
        <div class="p-8 border-t border-slate-100 dark:border-white/5 bg-slate-50/30 dark:bg-white/[0.01]">
            <?php echo e($reports->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<style>
    @keyframes slide-up {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .animate-slide-up {
        animation: slide-up 0.6s cubic-bezier(0.2, 0, 0.2, 1) forwards;
    }
</style>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\reports\index.blade.php ENDPATH**/ ?>