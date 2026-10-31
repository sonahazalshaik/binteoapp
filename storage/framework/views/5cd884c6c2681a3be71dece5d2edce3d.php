<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="bg-white dark:bg-[#0F0F0F] min-h-screen pb-24 transition-colors duration-500 pt-[18px] lg:pt-15">
        <div class="max-w-[1600px] mx-auto px-4 md:px-8 lg:px-12">
            
            <!-- Breadcrumb Navigation -->
            <div class="mb-4 md:mb-6">
                <div class="flex items-center gap-2 text-slate-500 dark:text-gray-400 text-[10px] md:text-xs font-bold uppercase tracking-widest mb-1">
                    <a href="<?php echo e(route('home')); ?>" class="hover:text-[#ff571a] transition-colors">Home</a>
                    <span class="material-symbols-rounded text-[14px]">chevron_right</span>
                    <a href="<?php echo e(route('premium')); ?>" class="hover:text-[#ff571a] transition-colors">Mini OTT</a>
                    <span class="material-symbols-rounded text-[14px]">chevron_right</span>
                    <span class="text-amber-500">Premium Channels</span>
                </div>
                
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mt-2">
                    <div>
                        <h1 class="text-xl md:text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tight flex items-center gap-3">
                            All Premium Channels
                            <span class="material-symbols-rounded text-2xl md:text-3xl text-amber-500 material-symbols-filled animate-pulse">workspace_premium</span>
                        </h1>
                        <p class="text-[11px] md:text-sm font-bold text-slate-500 dark:text-slate-400 mt-1">Explore verified creator channels with active membership plans and exclusive VIP videos.</p>
                    </div>

                    <!-- Counter Badge -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 text-xs font-black uppercase tracking-widest self-start md:self-auto">
                        <span class="material-symbols-rounded text-base">verified</span>
                        <span><?php echo e($channels->total()); ?> <?php echo e(Str::plural('Channel', $channels->total())); ?> Available</span>
                    </div>
                </div>
            </div>

            <!-- Controls (Search & Sort Filters) -->
            <div class="w-full max-w-3xl lg:max-w-4xl mx-auto bg-slate-50 dark:bg-[#1A1A1A] rounded-2xl md:rounded-3xl p-2 sm:p-3 md:p-4 mb-6 md:mb-8 border border-slate-200/60 dark:border-white/5 shadow-sm">
                <form action="<?php echo e(route('channels.premium.all')); ?>" method="GET" class="flex flex-row items-center justify-between gap-1.5 sm:gap-3 md:gap-4">
                    
                    <!-- Search Input -->
                    <div class="relative flex-1 min-w-0 sm:flex-initial sm:w-72 md:w-80">
                        <span class="material-symbols-rounded absolute left-2.5 sm:left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm sm:text-lg pointer-events-none">search</span>
                        <input type="text" 
                               name="search" 
                               value="<?php echo e(request('search')); ?>" 
                               placeholder="Search channels..." 
                               class="w-full pl-7 sm:pl-10 pr-6 sm:pr-8 py-2 sm:py-2.5 rounded-xl bg-white dark:bg-black/30 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white text-[11px] sm:text-xs md:text-sm font-semibold placeholder:text-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all truncate">
                        <?php if(request('search')): ?>
                            <a href="<?php echo e(route('channels.premium.all', request()->except('search'))); ?>" class="absolute right-2 sm:right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-white">
                                <span class="material-symbols-rounded text-sm sm:text-base">close</span>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Sort Filter & Button -->
                    <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                        <span class="text-[10px] md:text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest whitespace-nowrap hidden md:inline">Sort:</span>
                        <select name="sort" 
                                onchange="this.form.submit()" 
                                class="px-2 sm:px-3.5 py-2 sm:py-2.5 rounded-xl bg-white dark:bg-black/30 border border-slate-200 dark:border-white/10 text-slate-800 dark:text-gray-200 text-[10px] sm:text-xs font-black uppercase tracking-wider focus:outline-none focus:border-amber-500 transition-all cursor-pointer max-w-[105px] sm:max-w-none">
                            <option value="latest" <?php echo e(request('sort') == 'latest' ? 'selected' : ''); ?>>Latest</option>
                            <option value="popular" <?php echo e(request('sort') == 'popular' ? 'selected' : ''); ?>>Popular</option>
                            <option value="most_videos" <?php echo e(request('sort') == 'most_videos' ? 'selected' : ''); ?>>Videos</option>
                            <option value="oldest" <?php echo e(request('sort') == 'oldest' ? 'selected' : ''); ?>>Oldest</option>
                        </select>
                        <button type="submit" class="px-2.5 sm:px-4 py-2 sm:py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-[10px] sm:text-xs font-black uppercase tracking-wider transition-all shadow-md shadow-amber-500/20 active:scale-95 shrink-0">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Channels Grid (NO CAROUSEL, Arranged Cleanly) -->
            <?php if($channels->count() > 0): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                    <?php $__currentLoopData = $channels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $channel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('frontend.partials.channel_plan_card_small', ['channel' => $channel, 'fullWidth' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Pagination -->
                <?php if($channels->hasPages()): ?>
                <div class="mt-10 md:mt-14 flex justify-center">
                    <?php echo e($channels->links()); ?>

                </div>
                <?php endif; ?>
            <?php else: ?>
                <!-- Empty State -->
                <div class="py-20 md:py-28 flex flex-col items-center justify-center text-center bg-slate-50 dark:bg-white/[0.02] rounded-3xl border-2 border-dashed border-slate-200 dark:border-white/10 p-8">
                    <div class="w-20 h-20 bg-amber-500/10 text-amber-500 rounded-3xl flex items-center justify-center mb-5 shadow-lg shadow-amber-500/10">
                        <span class="material-symbols-rounded text-4xl">workspace_premium</span>
                    </div>
                    <h3 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tight mb-2">
                        <?php echo e(request('search') ? 'No Matching Premium Channels' : 'No Premium Channels Available'); ?>

                    </h3>
                    <p class="text-xs md:text-sm font-medium text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-6">
                        <?php echo e(request('search') ? 'Try searching with different keywords or clear your search query.' : 'There are currently no channels with active plans. Check back soon for exclusive creator memberships.'); ?>

                    </p>
                    <?php if(request('search')): ?>
                        <a href="<?php echo e(route('channels.premium.all')); ?>" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-amber-500 text-white text-xs font-black uppercase tracking-widest hover:bg-amber-600 transition-all shadow-lg shadow-amber-500/20 active:scale-95">
                            <span class="material-symbols-rounded text-base">refresh</span>
                            Clear Search
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('premium')); ?>" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-slate-900 dark:bg-white/10 text-white text-xs font-black uppercase tracking-widest hover:bg-slate-800 dark:hover:bg-white/20 transition-all shadow-lg active:scale-95">
                            <span class="material-symbols-rounded text-base">slow_motion_video</span>
                            Browse Mini OTT Videos
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\premium_channels.blade.php ENDPATH**/ ?>