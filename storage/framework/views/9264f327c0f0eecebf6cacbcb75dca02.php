

<?php $__env->startSection('title', 'Ad Details: ' . $advertisement->title); ?>
<?php $__env->startSection('header_title', 'Ad Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-700 pb-24">
    <!-- Ad Hero -->
    <div class="relative bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-[2.5rem] p-10 lg:p-16 shadow-sm overflow-hidden group">
        <div class="relative z-10 flex flex-col lg:flex-row items-center gap-12">
            <div class="w-48 h-48 lg:w-64 lg:h-64 rounded-3xl border-4 border-white dark:border-white/10 overflow-hidden shadow-2xl relative bg-slate-900">
                <?php if($advertisement->logo): ?>
                    <img src="<?php echo e(getImage(getFilePath('adLogo').'/'.$advertisement->logo)); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full flex flex-col items-center justify-center text-white p-6 text-center">
                         <span class="material-symbols-rounded text-6xl mb-2 opacity-50">branding_watermark</span>
                         <span class="font-black text-xs uppercase tracking-widest">No Logo</span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="flex-grow text-center lg:text-left space-y-6">
                <div>
                    <div class="flex items-center justify-center lg:justify-start gap-4 mb-2">
                        <h2 class="text-4xl lg:text-5xl font-black tracking-tighter text-slate-900 dark:text-white uppercase"><?php echo e($advertisement->title); ?></h2>
                        <?php if($advertisement->status): ?>
                            <span class="px-4 py-1 rounded-xl bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-widest border border-emerald-500/20">Running</span>
                        <?php else: ?>
                            <span class="px-4 py-1 rounded-xl bg-red-500/10 text-red-500 text-[10px] font-black uppercase tracking-widest border border-red-500/20">Paused</span>
                        <?php endif; ?>
                    </div>
                    <p class="text-sm lg:text-lg font-bold text-slate-400 dark:text-white/30 uppercase tracking-widest">Advertiser: <?php echo e($advertisement->user->username ?? 'System Admin'); ?></p>
                </div>

                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-6">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-rounded text-blue-500">category</span>
                        <span class="text-sm font-black text-slate-600 dark:text-white/60">
                            <?php $__currentLoopData = $advertisement->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e($category->name); ?><?php echo e(!$loop->last ? ',' : ''); ?>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-rounded text-cyan-500">ads_click</span>
                        <span class="text-sm font-black text-slate-600 dark:text-white/60 uppercase">Type: 
                            <?php if($advertisement->ad_type == 1): ?> CPM <?php elseif($advertisement->ad_type == 2): ?> CPC <?php else: ?> Both <?php endif; ?>
                        </span>
                    </div>
                </div>

                <div class="flex items-center justify-center lg:justify-start gap-4">
                    <a href="<?php echo e(route('admin.advertisement.edit', $advertisement->id)); ?>" class="h-14 px-8 rounded-2xl bg-slate-900 dark:bg-white text-white dark:text-black flex items-center gap-3 text-xs font-black uppercase tracking-widest hover:scale-105 transition-all shadow-xl active:scale-95">
                        <span class="material-symbols-rounded">edit</span>
                        Edit Ad
                    </a>
                    <a href="<?php echo e($advertisement->url); ?>" target="_blank" class="h-14 px-8 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-white/60 flex items-center gap-3 text-xs font-black uppercase tracking-widest hover:bg-slate-100 transition-all shadow-xl active:scale-95">
                        <span class="material-symbols-rounded">open_in_new</span>
                        Visit Target
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Blocks -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div class="bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm group">
            <span class="material-symbols-rounded text-blue-500 text-3xl mb-4">visibility</span>
            <h4 class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest mb-1">Impressions</h4>
            <p class="text-3xl font-black text-slate-900 dark:text-white"><?php echo e(number_format($advertisement->impression)); ?></p>
            <p class="text-[10px] font-bold text-slate-400 mt-2">Max Limit reached</p>
        </div>
        <div class="bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm group">
            <span class="material-symbols-rounded text-cyan-500 text-3xl mb-4">touch_app</span>
            <h4 class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest mb-1">Total Clicks</h4>
            <p class="text-3xl font-black text-slate-900 dark:text-white"><?php echo e(number_format($advertisement->click)); ?></p>
            <p class="text-[10px] font-bold text-slate-400 mt-2">Engagement metric</p>
        </div>
        <div class="bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm group">
            <span class="material-symbols-rounded text-emerald-500 text-3xl mb-4">payments</span>
            <h4 class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest mb-1">Budget Spent</h4>
            <p class="text-3xl font-black text-slate-900 dark:text-white">$<?php echo e(number_format($advertisement->total_amount, 2)); ?></p>
            <p class="text-[10px] font-bold text-slate-400 mt-2">Total economic value</p>
        </div>
        <div class="bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm group">
            <span class="material-symbols-rounded text-orange-500 text-3xl mb-4">ads_click</span>
            <h4 class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest mb-1">CTR (%)</h4>
            <p class="text-3xl font-black text-slate-900 dark:text-white">
                <?php echo e($advertisement->impression > 0 ? number_format(($advertisement->click / $advertisement->impression) * 100, 2) : '0.00'); ?>%
            </p>
            <p class="text-[10px] font-bold text-slate-400 mt-2">Performance ratio</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\advertisements\view.blade.php ENDPATH**/ ?>