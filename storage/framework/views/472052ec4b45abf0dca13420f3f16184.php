<?php $__env->startSection('panel'); ?>
<div x-data="{ 
    activeTab: 'reports',
    selectAll: false, 
    toggleAll() { 
        const checkboxes = document.querySelectorAll('.bulk-select-item');
        checkboxes.forEach(cb => { cb.checked = this.selectAll; });
    }
}" class="bg-white dark:bg-[#121212] rounded-[2rem] overflow-hidden border border-slate-200 dark:border-white/10 shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-700">
    
    <?php echo $__env->make('admin.components.header-toolbar', [
        'title' => $pageTitle ?? 'Reported Comments Dashboard',
        'items' => $reports
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="px-6 pt-6 flex gap-4 border-b border-slate-100 dark:border-white/5">
        <button @click="activeTab = 'reports'" 
                class="px-4 py-3 text-[11px] font-black uppercase tracking-widest transition-all border-b-2"
                :class="activeTab === 'reports' ? 'border-orange-500 text-orange-500' : 'border-transparent text-slate-400 hover:text-slate-900 dark:hover:text-white'">
            Reported Comments (<?php echo e($reports->total()); ?>)
        </button>
        <button @click="activeTab = 'blocked'" 
                class="px-4 py-3 text-[11px] font-black uppercase tracking-widest transition-all border-b-2"
                :class="activeTab === 'blocked' ? 'border-orange-500 text-orange-500' : 'border-transparent text-slate-400 hover:text-slate-900 dark:hover:text-white'">
            Blocked Users (<?php echo e($blockedUsers->total() ?? 0); ?>)
        </button>
    </div>

    <div class="px-6 pt-6">
        <?php echo $__env->make('admin.components.table-toolbar', ['showBulkActions' => false], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <div x-show="activeTab === 'reports'">
        <!-- Table View -->
        <div class="overflow-x-auto px-6 pb-6" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-white/[0.02]">
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Reporter</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Comment Author</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Comment</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Reason</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Status</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 overflow-hidden flex items-center justify-center">
                                <?php if(@$report->reporter->image): ?>
                                    <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $report->reporter->image)); ?>" class="w-full h-full object-cover" onerror="this.src='<?php echo e(asset('assets/images/avatar.png')); ?>'">
                                <?php else: ?>
                                    <span class="text-[10px] font-black uppercase "><?php echo e(substr(@$report->reporter->firstname ?? 'G', 0, 1)); ?></span>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo e($report->user_id ? route('admin.users.detail', $report->user_id) : '#'); ?>" class="hover:opacity-70 transition-opacity">
                                <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase leading-none"><?php echo e(@$report->reporter->username ?? 'Guest'); ?></span>
                                <p class="text-[9px] text-slate-400 uppercase tracking-widest mt-1"><?php echo e(diffForHumans($report->created_at)); ?></p>
                            </a>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-white/5 overflow-hidden flex items-center justify-center border border-slate-200 dark:border-white/10 shadow-sm">
                                <?php if(@$report->comment->user->image): ?>
                                    <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $report->comment->user->image)); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <span class="text-[9px] font-black uppercase opacity-50"><?php echo e(substr(@$report->comment->user->firstname ?? 'U', 0, 1)); ?></span>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo e(@$report->comment->user_id ? route('admin.users.detail', $report->comment->user_id) : '#'); ?>" class="hover:opacity-70 transition-opacity">
                                <p class="text-[10px] font-black text-slate-900 dark:text-white uppercase "><?php echo e(@$report->comment->user->username ?? 'Deleted User'); ?></p>
                            </a>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="min-w-0 max-w-[250px]">
                            <?php if($report->comment): ?>
                                <span class="text-[11px] font-medium text-slate-700 dark:text-white/80 line-clamp-2" title="<?php echo e($report->comment->comment); ?>"><?php echo e($report->comment->comment); ?></span>
                                <?php if($report->comment->video): ?>
                                    <a href="<?php echo e(route('admin.videos.show', $report->comment->video_id)); ?>" target="_blank" class="text-[9px] text-orange-500 hover:text-orange-600 font-bold uppercase tracking-widest mt-1 inline-flex items-center gap-1">
                                        View Video <span class="material-symbols-rounded text-[10px]">open_in_new</span>
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-[11px] font-medium text-rose-500 ">Comment Deleted</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-lg bg-rose-500/10 text-rose-500 text-[9px] font-black uppercase tracking-widest border border-rose-500/20">
                            <?php echo e($report->reason); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <?php if($report->status == 'pending'): ?>
                            <span class="px-3 py-1 rounded-lg bg-amber-500/10 text-amber-500 text-[9px] font-black uppercase tracking-widest border border-amber-500/20">Pending</span>
                        <?php else: ?>
                            <span class="px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-[9px] font-black uppercase tracking-widest border border-emerald-500/20">Reviewed</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <?php if($report->status == 'pending'): ?>
                            <div class="flex items-center justify-end gap-2">
                                <?php if($report->comment): ?>
                                    <form action="<?php echo e(route('admin.comment.reports.delete.comment', $report->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="h-8 px-3 rounded-xl bg-rose-500 text-white flex items-center justify-center gap-2 hover:opacity-90 transition-all shadow-lg shadow-rose-500/20 confirmationBtn" data-question="Are you sure you want to delete this comment?">
                                            <span class="material-symbols-rounded text-sm">delete</span>
                                            <span class="text-[9px] font-black uppercase tracking-tighter">Delete Comment</span>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <form action="<?php echo e(route('admin.comment.reports.destroy', $report->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="h-8 px-3 rounded-xl bg-slate-100 dark:bg-white/10 text-slate-600 dark:text-white flex items-center justify-center gap-2 hover:bg-slate-200 dark:hover:bg-white/20 transition-all confirmationBtn" data-question="Are you sure you want to dismiss this report?">
                                        <span class="material-symbols-rounded text-sm">close</span>
                                        <span class="text-[9px] font-black uppercase tracking-tighter">Dismiss</span>
                                    </button>
                                </form>
                            </div>
                        <?php else: ?>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Action Taken</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="100%" class="py-20 text-center opacity-20 text-[11px] font-black uppercase tracking-widest">No reports in this frequency.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- App View (Grid) -->
    <div class="px-6 pb-6" x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mt-6">
            <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden relative group p-8">
                
                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100 dark:border-white/5">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center overflow-hidden border border-slate-200 dark:border-white/10 shadow-sm">
                        <?php if(@$report->reporter->image): ?>
                            <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $report->reporter->image)); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <span class="text-[10px] font-black uppercase opacity-50"><?php echo e(substr(@$report->reporter->firstname ?? 'G', 0, 1)); ?></span>
                        <?php endif; ?>
                    </div>
                    <a href="<?php echo e($report->user_id ? route('admin.users.detail', $report->user_id) : '#'); ?>" class="hover:opacity-70 transition-opacity">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Reporter</p>
                        <p class="text-[11px] font-black text-slate-900 dark:text-white uppercase "><?php echo e(@$report->reporter->username ?? 'Guest'); ?></p>
                    </a>
                    <div class="ml-auto text-right">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Status</p>
                        <p class="text-[9px] font-black <?php echo e($report->status == 'pending' ? 'text-amber-500' : 'text-emerald-500'); ?> uppercase tracking-tighter"><?php echo e(ucfirst($report->status)); ?></p>
                    </div>
                </div>

                <div class="mb-6">
                    <span class="px-2 py-1 rounded-md bg-rose-500/10 text-rose-500 text-[8px] font-black uppercase tracking-widest border border-rose-500/20 mb-2 inline-block">
                        <?php echo e($report->reason); ?>

                    </span>
                    
                    <?php if($report->comment): ?>
                        <div class="bg-slate-50 dark:bg-white/[0.02] rounded-xl p-4 mt-2">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-[10px] font-black text-slate-900 dark:text-white">@ <?php echo e(@$report->comment->user->username ?? 'Deleted'); ?></span>
                            </div>
                            <p class="text-[11px] text-slate-600 dark:text-white/70 ">"<?php echo e(strLimit($report->comment->comment, 100)); ?>"</p>
                        </div>
                    <?php else: ?>
                        <p class="text-[11px] font-medium text-rose-500 mt-2">Comment Deleted</p>
                    <?php endif; ?>
                </div>

                <?php if($report->status == 'pending'): ?>
                    <div class="grid grid-cols-2 gap-3 mt-auto">
                        <?php if($report->comment): ?>
                            <form action="<?php echo e(route('admin.comment.reports.delete.comment', $report->id)); ?>" method="POST" class="col-span-1">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="w-full h-12 rounded-xl bg-rose-500 text-white text-[9px] font-black uppercase tracking-[0.2em] flex items-center justify-center gap-2 hover:opacity-90 transition-all shadow-xl shadow-rose-500/20 confirmationBtn" data-question="Are you sure you want to delete this comment?">
                                    <span class="material-symbols-rounded text-sm">delete</span> Delete
                                </button>
                            </form>
                        <?php endif; ?>
                        <form action="<?php echo e(route('admin.comment.reports.destroy', $report->id)); ?>" method="POST" class="<?php echo e($report->comment ? 'col-span-1' : 'col-span-2'); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full h-12 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/60 text-[9px] font-black uppercase tracking-[0.2em] flex items-center justify-center gap-2 hover:bg-slate-200 dark:hover:bg-white/10 transition-all confirmationBtn" data-question="Are you sure you want to dismiss this report?">
                                <span class="material-symbols-rounded text-sm">close</span> Dismiss
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="h-12 flex items-center justify-center border-t border-slate-100 dark:border-white/5 mt-auto">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Action Taken</span>
                    </div>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full py-40 text-center opacity-20 text-[11px] font-black uppercase tracking-widest">No reports in this frequency.</div>
            <?php endif; ?>
            </div>
        </div>

        <div class="p-8 border-t border-slate-100 dark:border-white/10 bg-slate-50/50 dark:bg-white/[0.01]">
            <?php echo e($reports->links()); ?>

        </div>
    </div>

    <!-- Blocked Users View -->
    <div x-show="activeTab === 'blocked'" style="display: none;">
        <!-- Table View -->
        <div class="overflow-x-auto px-6 pb-6" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-white/[0.02]">
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Blocking User</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Blocked User</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Offending Comment</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Date Blocked</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    <?php $__empty_1 = true; $__currentLoopData = $blockedUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blocked): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 overflow-hidden flex items-center justify-center">
                                    <?php if(@$blocked->blocker->image): ?>
                                        <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $blocked->blocker->image)); ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <span class="text-[10px] font-black uppercase "><?php echo e(substr(@$blocked->blocker->firstname ?? 'U', 0, 1)); ?></span>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo e(@$blocked->user_id ? route('admin.users.detail', $blocked->user_id) : '#'); ?>" class="hover:opacity-70 transition-opacity">
                                    <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase leading-none"><?php echo e(@$blocked->blocker->username ?? 'Unknown'); ?></span>
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 overflow-hidden flex items-center justify-center border border-slate-200 dark:border-white/10 shadow-sm">
                                    <?php if(@$blocked->blocked->image): ?>
                                        <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $blocked->blocked->image)); ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <span class="text-[10px] font-black uppercase opacity-50"><?php echo e(substr(@$blocked->blocked->firstname ?? 'U', 0, 1)); ?></span>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo e(@$blocked->blocked_user_id ? route('admin.users.detail', $blocked->blocked_user_id) : '#'); ?>" class="hover:opacity-70 transition-opacity">
                                    <p class="text-[11px] font-black text-slate-900 dark:text-white uppercase "><?php echo e(@$blocked->blocked->username ?? 'Unknown'); ?></p>
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($blocked->comment_text): ?>
                                <span class="text-[11px] font-medium text-slate-700 dark:text-white/80 line-clamp-2" title="<?php echo e($blocked->comment_text); ?>"><?php echo e(strLimit($blocked->comment_text, 100)); ?></span>
                            <?php else: ?>
                                <span class="text-[10px] text-slate-400">Blocked via profile</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-medium text-slate-500"><?php echo e(showDateTime($blocked->created_at)); ?></span>
                            <br>
                            <span class="text-[9px] text-slate-400 uppercase tracking-widest"><?php echo e(diffForHumans($blocked->created_at)); ?></span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 rounded-full bg-slate-50 dark:bg-white/[0.02] flex items-center justify-center mb-4">
                                    <span class="material-symbols-rounded text-2xl text-slate-300 dark:text-white/20">block</span>
                                </div>
                                <h3 class="text-[13px] font-black text-slate-900 dark:text-white uppercase tracking-widest mb-1">No Blocked Users</h3>
                                <p class="text-[11px] font-medium text-slate-400">No users have been blocked yet.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Grid View -->
        <div class="px-6 pb-6" x-show="$store.viewMode && $store.viewMode.mode === 'app'" style="display: none;">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $blockedUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blocked): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white dark:bg-[#1A1A1A] rounded-2xl border border-slate-200 dark:border-white/10 p-5 hover:shadow-xl hover:shadow-slate-200/50 dark:hover:shadow-none transition-all group relative">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 overflow-hidden flex items-center justify-center">
                            <?php if(@$blocked->blocker->image): ?>
                                <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $blocked->blocker->image)); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="text-[10px] font-black uppercase "><?php echo e(substr(@$blocked->blocker->firstname ?? 'U', 0, 1)); ?></span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 uppercase tracking-widest block mb-0.5">Blocking User</span>
                            <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase "><?php echo e(@$blocked->blocker->username ?? 'Unknown'); ?></span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 mb-4 pt-4 border-t border-slate-100 dark:border-white/5">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 overflow-hidden flex items-center justify-center border border-slate-200 dark:border-white/10">
                            <?php if(@$blocked->blocked->image): ?>
                                <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $blocked->blocked->image)); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="text-[10px] font-black uppercase opacity-50"><?php echo e(substr(@$blocked->blocked->firstname ?? 'U', 0, 1)); ?></span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 uppercase tracking-widest block mb-0.5">Blocked User</span>
                            <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase "><?php echo e(@$blocked->blocked->username ?? 'Unknown'); ?></span>
                        </div>
                    </div>
                    
                    <?php if($blocked->comment_text): ?>
                        <div class="mb-4 pt-4 border-t border-slate-100 dark:border-white/5">
                            <span class="text-[9px] text-slate-400 uppercase tracking-widest block mb-1">Offending Comment</span>
                            <p class="text-[11px] text-slate-600 dark:text-white/70 line-clamp-3">"<?php echo e($blocked->comment_text); ?>"</p>
                        </div>
                    <?php endif; ?>

                    <div class="pt-4 border-t border-slate-100 dark:border-white/5 flex items-center justify-between">
                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-400"><i class="las la-clock mr-1"></i><?php echo e(diffForHumans($blocked->created_at)); ?></span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full">
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="w-16 h-16 rounded-full bg-slate-50 dark:bg-white/[0.02] flex items-center justify-center mb-4">
                            <span class="material-symbols-rounded text-2xl text-slate-300 dark:text-white/20">block</span>
                        </div>
                        <h3 class="text-[13px] font-black text-slate-900 dark:text-white uppercase tracking-widest mb-1">No Blocked Users</h3>
                        <p class="text-[11px] font-medium text-slate-400">No users have been blocked yet.</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <?php if($blockedUsers->hasPages()): ?>
                <div class="mt-6 border-t border-slate-100 dark:border-white/5 pt-6">
                    <?php echo e($blockedUsers->links()); ?>

                </div>
            <?php endif; ?>
        </div>
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


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\comment_reports\index.blade.php ENDPATH**/ ?>