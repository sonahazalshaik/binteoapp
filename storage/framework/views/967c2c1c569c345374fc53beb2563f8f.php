<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-white dark:bg-[#0A0A0A] pb-20">
    
    <div class="sticky top-0 z-50 bg-white/80 dark:bg-black/80 backdrop-blur-xl border-b border-gray-100 dark:border-white/5 px-4 sm:px-6 py-3.5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('reels.index')); ?>" class="w-9 h-9 rounded-full flex items-center justify-center bg-gray-100 dark:bg-white/5 text-gray-900 dark:text-white active:scale-90 transition-transform">
                <span class="material-symbols-rounded text-xl">arrow_back</span>
            </a>
            <h1 class="text-xs sm:text-sm font-black uppercase tracking-widest text-gray-900 dark:text-white">Audio Details</h1>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 pt-6 sm:pt-10">
        
        <div class="p-6 sm:p-8 rounded-3xl bg-gray-50 dark:bg-white/[0.03] border border-gray-100 dark:border-white/5 mb-8 shadow-sm">
            <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-6">
                
                <div class="relative group shrink-0">
                    <div class="w-28 h-28 sm:w-36 sm:h-36 rounded-2xl sm:rounded-[2rem] overflow-hidden shadow-xl shadow-orange-500/10 border-2 border-white dark:border-[#121212] relative z-10">
                        <?php if($music->is_original): ?>
                            <img src="<?php echo e($music->cover_image ?? asset('assets/images/default.png')); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <img src="<?php echo e(getImage(getFilePath('reelMusic') . '/' . $music->cover_image)); ?>" class="w-full h-full object-cover">
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                            <span class="material-symbols-rounded text-white text-3xl animate-pulse">music_note</span>
                        </div>
                    </div>
                    <div class="absolute -inset-2 bg-gradient-to-tr from-orange-500 to-rose-600 rounded-[2.5rem] blur-xl opacity-25 animate-pulse"></div>
                </div>

                
                <div class="flex-1 min-w-0 w-full flex flex-col items-center sm:items-start">
                    <h2 class="text-lg sm:text-2xl font-black text-gray-900 dark:text-white leading-tight mb-1 break-words line-clamp-2 max-w-full"><?php echo e($music->title); ?></h2>
                    <p class="text-orange-500 font-bold text-xs mb-4 flex items-center justify-center sm:justify-start gap-1.5">
                        <span class="material-symbols-rounded text-sm">person</span>
                        <span><?php echo e($music->artist); ?></span>
                    </p>

                    <div class="flex flex-col sm:flex-row items-center gap-4 w-full pt-2 border-t border-gray-200/60 dark:border-white/5">
                        <div class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-white/5 rounded-full border border-gray-100 dark:border-white/5 shadow-sm">
                            <span class="material-symbols-rounded text-orange-500 text-base">movie</span>
                            <span class="text-xs font-black text-gray-900 dark:text-white"><?php echo e(number_format($music->usage_count)); ?></span>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Reels</span>
                        </div>

                        <?php
                            $useParams = $music->is_original 
                                ? ['original_reel_id' => str_replace('original_', '', $music->id)]
                                : ['music_id' => $music->id];
                        ?>
                        <a href="<?php echo e(route('reels.create', $useParams)); ?>" 
                           class="w-full sm:w-auto py-3 px-6 rounded-full gradient-orange text-white font-black text-xs uppercase tracking-widest shadow-lg shadow-orange-500/20 active:scale-95 transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-rounded text-base">video_call</span>
                            Use This Audio
                        </a>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="mb-4 flex items-center justify-between px-1">
            <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Reels</h3>

            
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-[11px] font-bold text-gray-700 dark:text-gray-300 transition-all border border-gray-200 dark:border-white/10 active:scale-95">
                    <span class="material-symbols-rounded text-sm text-orange-500">sort</span>
                    <span><?php echo e(($sort ?? 'latest') == 'popular' ? 'Most Popular' : (($sort ?? 'latest') == 'oldest' ? 'Oldest First' : 'Latest First')); ?></span>
                    <span class="material-symbols-rounded text-sm">expand_more</span>
                </button>
                <div x-show="open" @click.away="open = false" x-cloak 
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-2 w-44 bg-white dark:bg-[#181818] rounded-2xl shadow-2xl border border-gray-100 dark:border-white/10 overflow-hidden z-50 py-1">
                    <a href="?sort=latest" class="flex items-center gap-2 px-4 py-2.5 text-xs font-bold hover:bg-orange-500/10 hover:text-orange-500 transition-colors <?php echo e(($sort ?? 'latest') == 'latest' ? 'text-orange-500 bg-orange-500/5' : 'text-gray-700 dark:text-gray-300'); ?>">
                        <span class="material-symbols-rounded text-sm">schedule</span>
                        Latest First
                    </a>
                    <a href="?sort=popular" class="flex items-center gap-2 px-4 py-2.5 text-xs font-bold hover:bg-orange-500/10 hover:text-orange-500 transition-colors <?php echo e(($sort ?? '') == 'popular' ? 'text-orange-500 bg-orange-500/5' : 'text-gray-700 dark:text-gray-300'); ?>">
                        <span class="material-symbols-rounded text-sm">local_fire_department</span>
                        Most Popular
                    </a>
                    <a href="?sort=oldest" class="flex items-center gap-2 px-4 py-2.5 text-xs font-bold hover:bg-orange-500/10 hover:text-orange-500 transition-colors <?php echo e(($sort ?? '') == 'oldest' ? 'text-orange-500 bg-orange-500/5' : 'text-gray-700 dark:text-gray-300'); ?>">
                        <span class="material-symbols-rounded text-sm">history</span>
                        Oldest First
                    </a>
                </div>
            </div>
        </div>

        
        <div class="grid grid-cols-3 gap-1.5 sm:gap-4">
            <?php $__empty_1 = true; $__currentLoopData = $reels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('reels.show', $reel->slug)); ?>" class="relative aspect-[9/16] rounded-xl sm:rounded-2xl overflow-hidden bg-gray-100 dark:bg-white/5 group shadow-sm border border-transparent hover:border-orange-500/50 transition-all">
                    <img src="<?php echo e($reel->getPreviewUrl()); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-80 group-hover:opacity-100 transition-opacity"></div>
                    <div class="absolute bottom-2 left-2 flex items-center gap-1 px-2 py-0.5 bg-black/40 backdrop-blur-md rounded-full text-white">
                        <span class="material-symbols-rounded text-[12px]">play_arrow</span>
                        <span class="text-[10px] font-black"><?php echo e(formatNumber($reel->views_count ?? $reel->viewsCount ?? 0)); ?></span>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-3 py-16 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-4 text-gray-400 border border-gray-200 dark:border-white/5">
                        <span class="material-symbols-rounded text-3xl">music_off</span>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">No reels created with this audio yet.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-8">
            <?php echo e(paginateLinks($reels)); ?>

        </div>
    </div>
</div>

<style>
    .gradient-orange {
        background: linear-gradient(135deg, #ff571a 0%, #ff1a1a 100%);
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\reels\audio.blade.php ENDPATH**/ ?>