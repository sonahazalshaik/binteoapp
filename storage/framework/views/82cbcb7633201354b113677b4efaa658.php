

<?php $__env->startSection('title', 'OTT Subscription Plans'); ?>
<?php $__env->startSection('header_title', 'OTT Plans'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-700">
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl transition-all duration-500">
        <?php echo $__env->make('admin.components.header-toolbar', [
            'title' => 'OTT Subscription Plans',
            'items' => $plans,
            'createRoute' => route('admin.ott-plans.create'),
            'createLabel' => 'Add New OTT Plan'
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="px-6 pt-6">
            <?php echo $__env->make('admin.components.table-toolbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <!-- Desktop Table View -->
        <div x-show="$store.viewMode && $store.viewMode.mode === 'table'" x-cloak class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-white/[0.02]">
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30">Plan Name</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30">Price</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30 text-center">OTT Access</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30 text-center">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    <?php $__empty_1 = true; $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-white/[0.01] transition-all duration-300 group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 overflow-hidden shadow-lg transition-all duration-500 group-hover:scale-110 flex items-center justify-center p-0.5">
                                    <div class="w-full h-full bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-500">
                                        <span class="material-symbols-rounded text-2xl">movie</span>
                                    </div>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[14px] font-black text-slate-900 dark:text-white tracking-tight uppercase leading-none mb-2 transition-colors group-hover:text-indigo-500"><?php echo e($item->name); ?></p>
                                    <p class="text-[9px] font-bold text-slate-400 dark:text-white/20 uppercase tracking-widest flex items-center gap-2">
                                        <span class="w-1 h-1 rounded-full bg-indigo-500/30"></span> Validity: 
                                        <?php if($item->duration == 0): ?>
                                            Lifetime
                                        <?php elseif($item->duration == 12): ?>
                                            Yearly
                                        <?php elseif($item->duration == 1): ?>
                                            1 Month
                                        <?php else: ?>
                                            <?php echo e($item->duration); ?> Months
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-lg font-black text-slate-900 dark:text-white tracking-tighter leading-none mb-1.5"><?php echo e(showAmount($item->price)); ?></span>
                                <span class="text-[8px] text-emerald-500 font-black uppercase tracking-widest ">OTT Access Value</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <?php if($item->ott_access): ?>
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-orange-500/10 text-orange-600 text-[8px] font-black uppercase tracking-widest border border-orange-500/20 shadow-sm shadow-orange-500/10">
                                    <span class="material-symbols-rounded text-sm">workspace_premium</span>
                                    Enabled
                                </span>
                            <?php else: ?>
                                <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest ">None</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <form action="<?php echo e(route('admin.ott-plans.status', $item->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-2xl <?php echo e($item->status ? 'bg-emerald-500/10 text-emerald-600' : 'bg-slate-100 dark:bg-white/5 text-slate-400'); ?> text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full <?php echo e($item->status ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300'); ?>"></span>
                                    <?php echo e($item->status ? 'Active' : 'Disabled'); ?>

                                </button>
                            </form>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-3">
                                 <a href="<?php echo e(route('admin.ott-plans.edit', $item->id)); ?>" class="h-10 px-6 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/30 flex items-center gap-2 text-[9px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all active:scale-90 border border-transparent shadow-sm">
                                    <span class="material-symbols-rounded text-lg">settings_suggest</span>
                                    Edit
                                 </a>
                                 <form action="<?php echo e(route('admin.ott-plans.delete', $item->id)); ?>" method="POST" data-swal-question="Immediately delete this OTT plan?">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="w-10 h-10 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all active:scale-90 shadow-sm">
                                        <span class="material-symbols-rounded text-xl">delete_forever</span>
                                    </button>
                                 </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-10 py-40 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[2rem] m-8">
                            <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-6xl">movie_off</span>
                            <p class="mt-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.5em] opacity-30">No OTT Tiers Defined</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- App View (Grid Mode) -->
        <div x-show="!$store.viewMode || $store.viewMode.mode === 'app'" x-cloak class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php $__empty_1 = true; $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-slate-50/50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-[2rem] p-6 group hover:border-indigo-500/50 transition-all duration-500 shadow-sm hover:shadow-2xl">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-14 h-14 rounded-2xl bg-white dark:bg-white/10 border border-slate-100 dark:border-white/5 shadow-md flex items-center justify-center text-indigo-500 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-rounded text-2xl">movie</span>
                        </div>
                        <div class="flex gap-2">
                             <a href="<?php echo e(route('admin.ott-plans.edit', $item->id)); ?>" class="w-10 h-10 rounded-xl bg-white dark:bg-white/5 text-slate-400 hover:text-indigo-500 flex items-center justify-center transition-all border border-slate-100 dark:border-white/10">
                                <span class="material-symbols-rounded text-lg">edit</span>
                             </a>
                             <form action="<?php echo e(route('admin.ott-plans.delete', $item->id)); ?>" method="POST" data-swal-question="Delete this plan?">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="w-10 h-10 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                                    <span class="material-symbols-rounded text-xl">delete</span>
                                </button>
                             </form>
                        </div>
                    </div>
                    <div class="mb-6">
                        <h4 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tighter mb-1"><?php echo e($item->name); ?></h4>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ">
                            <?php if($item->duration == 0): ?>
                                Lifetime Access
                            <?php elseif($item->duration == 12): ?>
                                Yearly Access
                            <?php elseif($item->duration == 1): ?>
                                1 Month Access
                            <?php else: ?>
                                <?php echo e($item->duration); ?> Months Access
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="flex items-center justify-between mt-auto pt-6 border-t border-slate-100 dark:border-white/5">
                        <span class="text-2xl font-black text-slate-900 dark:text-white tracking-tighter"><?php echo e(showAmount($item->price)); ?></span>
                        <form action="<?php echo e(route('admin.ott-plans.status', $item->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="px-4 py-2 rounded-xl <?php echo e($item->status ? 'bg-emerald-500/10 text-emerald-600' : 'bg-slate-200 dark:bg-white/5 text-slate-400'); ?> text-[8px] font-black uppercase tracking-widest transition-all">
                                <?php echo e($item->status ? 'Active' : 'Disabled'); ?>

                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full py-32 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[3rem]">
                    <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-6xl">movie_off</span>
                    <p class="mt-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.5em] ">No OTT Plans Found</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="px-8 pb-8">
            <?php echo e(paginateLinks($plans)); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\ott_plans\index.blade.php ENDPATH**/ ?>