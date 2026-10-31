<?php $__env->startSection('panel'); ?>
<div x-data="{ 
    selectAll: false, 
    toggleAll() { 
        const checkboxes = document.querySelectorAll('.bulk-select-item');
        checkboxes.forEach(cb => { cb.checked = this.selectAll; });
    }
}" class="bg-white dark:bg-[#121212] rounded-[2rem] overflow-hidden border border-slate-200 dark:border-white/10 shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-700">
    
    <?php echo $__env->make('admin.components.header-toolbar', [
        'title' => $pageTitle ?? 'Appeal Management System',
        'items' => $appeals
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="px-6 pt-6">
        <?php echo $__env->make('admin.components.table-toolbar', ['showBulkActions' => false], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <!-- Table View -->
    <div class="overflow-x-auto px-6 pb-6" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Petitioner</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Appeal Content</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Submitted</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                <?php $__empty_1 = true; $__currentLoopData = $appeals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appeal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 overflow-hidden flex items-center justify-center border border-slate-200 dark:border-white/10 shadow-sm">
                                <?php if(@$appeal->user->image): ?>
                                    <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $appeal->user->image)); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <span class="text-[10px] font-black uppercase "><?php echo e(substr(@$appeal->user->firstname ?? 'U', 0, 1)); ?></span>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo e(route('admin.users.detail', $appeal->user_id)); ?>" class="hover:opacity-70 transition-opacity">
                                <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase leading-none"><?php echo e(@$appeal->user->username); ?></span>
                                <p class="text-[9px] text-slate-400 uppercase tracking-widest mt-1">Petitioner</p>
                            </a>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-[11px] text-slate-600 dark:text-white/60 line-clamp-1 max-w-[400px] ">"<?php echo e($appeal->reason); ?>"</p>
                    </td>
                    <td class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-tighter">
                        <?php echo e($appeal->created_at->diffForHumans()); ?>

                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="h-8 px-4 rounded-xl bg-emerald-500 text-white flex items-center justify-center gap-2 hover:opacity-90 transition-all shadow-lg shadow-emerald-500/20 handle-btn" 
                            data-id="<?php echo e($appeal->id); ?>" 
                            data-reason="<?php echo e($appeal->reason); ?>"
                            data-feedback="<?php echo e($appeal->admin_feedback); ?>">
                            <span class="material-symbols-rounded text-sm">balance</span>
                            <span class="text-[9px] font-black uppercase tracking-tighter">Review Appeal</span>
                        </button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="100%" class="py-20 text-center opacity-20 text-[11px] font-black uppercase tracking-widest">No judicial petitions pending.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- App View (Grid) -->
    <div class="px-6 pb-6" x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mt-6">
            <?php $__empty_1 = true; $__currentLoopData = $appeals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appeal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden relative group">
                <div class="p-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-16 h-16 rounded-[1.25rem] bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center overflow-hidden shadow-lg shadow-emerald-500/5">
                            <?php if(@$appeal->user->image): ?>
                                <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $appeal->user->image)); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="text-emerald-500 text-xl font-black "><?php echo e(substr(@$appeal->user->firstname ?? 'U', 0, 1)); ?></span>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo e(route('admin.users.detail', $appeal->user_id)); ?>" class="hover:opacity-70 transition-opacity">
                            <h4 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tighter leading-none mb-2"><?php echo e(@$appeal->user->username); ?></h4>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Justice Petitioner</p>
                        </a>
                        <div class="ml-auto text-right">
                            <span class="px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-[8px] font-black uppercase tracking-widest border border-emerald-500/20">
                                Appeal
                            </span>
                        </div>
                    </div>

                    <div class="bg-slate-50 dark:bg-white/[0.02] p-5 rounded-3xl border border-slate-100 dark:border-white/5 mb-8 min-h-[100px] flex items-center justify-center ">
                        <p class="text-[11px] text-slate-600 dark:text-white/50 text-center leading-relaxed">"<?php echo e($appeal->reason); ?>"</p>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-rounded text-slate-300 text-sm">schedule</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter"><?php echo e($appeal->created_at->diffForHumans()); ?></span>
                        </div>
                        <button class="h-12 px-6 rounded-2xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[9px] font-black uppercase tracking-[0.2em] flex items-center justify-center gap-3 hover:opacity-90 transition-all shadow-xl handle-btn"
                            data-id="<?php echo e($appeal->id); ?>" 
                            data-reason="<?php echo e($appeal->reason); ?>"
                            data-feedback="<?php echo e($appeal->admin_feedback); ?>">
                            Open Docket <span class="material-symbols-rounded text-sm">balance</span>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full py-40 text-center opacity-20 text-[11px] font-black uppercase tracking-widest">No judicial petitions pending.</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="p-8 border-t border-slate-100 dark:border-white/10 bg-slate-50/50 dark:bg-white/[0.01]">
        <?php echo e($appeals->links()); ?>

    </div>
</div>

<?php echo $__env->make('admin.moderation.reports.partial_modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script>
    (function($){
        "use strict";
        $('.handle-btn').on('click', function() {
            var modal = $('#handleModal');
            var data = $(this).data();
            var action = "<?php echo e(url('admin/moderation/reports')); ?>/" + data.id + "/handle";
            $('#handleForm').attr('action', action);
            $('#report-reason').text(data.reason);
            modal.find('[name=feedback]').val(data.feedback);
            modal.modal('show');
        });
    })(jQuery);
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\moderation\appeals.blade.php ENDPATH**/ ?>