

<?php $__env->startSection('title', 'Campaign Intelligence: ' . $campaign->title); ?>
<?php $__env->startSection('header_title', 'Initiative Oversight'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-12 animate-in fade-in slide-in-from-bottom-10 duration-700 pb-24">
    <!-- Header -->
    <div class="flex items-center justify-between px-6 lg:px-0">
        <div>
            <h3 class="text-xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Campaign Oversight</h3>
            <p class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3 leading-relaxed">In-depth performance analysis for #<?php echo e($campaign->title); ?></p>
        </div>
        <a href="<?php echo e(route('admin.campaign.index')); ?>" class="w-12 h-12 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 dark:text-white/40 hover:bg-slate-900 hover:text-white transition-all shadow-xl active:scale-90">
            <span class="material-symbols-rounded">arrow_back</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Summary Card -->
        <div class="lg:col-span-2 space-y-10">
            <div class="bg-white/60 dark:bg-white/5 backdrop-blur-3xl border border-white dark:border-white/10 rounded-[2rem] p-6 shadow-2xl relative overflow-hidden group">
                 <div class="flex items-start gap-4">
                    <div class="w-24 h-12 rounded-[2rem] bg-gradient-to-r from-orange-500 to-red-600 text-white flex items-center justify-center shadow-2xl shadow-indigo-600/30">
                        <span class="material-symbols-rounded text-xl">analytics</span>
                    </div>
                    <div class="flex-grow">
                        <h4 class="text-[12px] font-black text-slate-900 dark:text-white uppercase tracking-tighter leading-tight"><?php echo e($campaign->title); ?></h4>
                        <div class="flex items-center gap-4 mt-4">
                            <span class="px-4 py-1 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-black text-[9px] font-black uppercase tracking-widest shadow-lg">Advertiser: <?php echo e($campaign->user->username ?? 'System'); ?></span>
                            <span class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest ">Launched: <?php echo e($campaign->created_at->format('M d, Y')); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Associated Assets -->
            <div class="bg-white/40 dark:bg-black/20 backdrop-blur-3xl p-6 rounded-[2rem] border border-slate-200 dark:border-white/10 shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between mb-10">
                    <h5 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight ">Linked Advertisements</h5>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest"><?php echo e($campaign->advertisements->count()); ?> Creative(s)</span>
                </div>
                
                <div class="space-y-6">
                    <?php $__empty_1 = true; $__currentLoopData = $campaign->advertisements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex items-center justify-between p-6 bg-white/60 dark:bg-white/5 rounded-[2rem] border border-white dark:border-white/5 group hover:border-indigo-500/30 transition-all">
                            <div class="flex items-center gap-5">
                                <div class="w-16 aspect-video rounded-xl overflow-hidden bg-slate-900 border border-white/10">
                                    <?php if($ad->logo): ?>
                                        <img src="<?php echo e(getImage(getFilePath('adLogo').'/'.$ad->logo)); ?>" class="w-full h-full object-cover">
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <p class="text-[12px] font-black text-slate-900 dark:text-white uppercase tracking-tight"><?php echo e($ad->title); ?></p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1 "><?php echo e($ad->impression); ?> Impressions • <?php echo e($ad->click); ?> Clicks</p>
                                </div>
                            </div>
                            <a href="<?php echo e(route('admin.advertisement.show', $ad->id)); ?>" class="p-3 rounded-xl bg-slate-50 dark:bg-white/5 text-slate-400 hover:text-orange-500 transition-all">
                                <span class="material-symbols-rounded">open_in_new</span>
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-center py-10 text-slate-400 font-black uppercase tracking-widest text-xs opacity-20">No Advertisements Linked</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Strategy Info -->
        <div class="space-y-8">
            <div class="bg-white/40 dark:bg-black/20 backdrop-blur-3xl p-4 rounded-xl border border-slate-200 dark:border-white/10 shadow-2xl relative overflow-hidden group">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Financial Status</p>
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full <?php if($campaign->status == 1): ?> bg-emerald-500 <?php else: ?> bg-slate-300 <?php endif; ?> shadow-lg"></span>
                    <h4 class="text-[12px] font-black text-slate-900 dark:text-white uppercase ">$<?php echo e(number_format($campaign->total_amount, 2)); ?></h4>
                </div>
                
                <div class="mt-8 space-y-6 pt-6 border-t border-slate-100 dark:border-white/5">
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Global Status</span>
                        <span class="text-xs font-black <?php if($campaign->status == 1): ?> text-emerald-600 <?php else: ?> text-slate-400 <?php endif; ?> uppercase tracking-widest"><?php echo e($campaign->status == 1 ? 'Active' : 'Paused'); ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Payment</span>
                        <span class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">Verified</span>
                    </div>
                </div>
            </div>

            <form action="<?php echo e(route('admin.campaign.status', $campaign->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button class="w-full h-12 rounded-3xl <?php if($campaign->status == 1): ?> bg-rose-600 text-white <?php else: ?> bg-emerald-600 text-white <?php endif; ?> flex items-center justify-center gap-4 text-[9px] font-black uppercase tracking-[0.3em] hover:scale-[1.05] transition-all shadow-xl active:scale-95 ">
                    <span class="material-symbols-rounded"><?php if($campaign->status == 1): ?> pause_circle <?php else: ?> play_circle <?php endif; ?></span>
                    <?php echo e($campaign->status == 1 ? 'Suspend Initiative' : 'Resume Initiative'); ?>

                </button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\campaign\detail.blade.php ENDPATH**/ ?>