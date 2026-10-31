<?php $__env->startPush('style'); ?>
<style>
    @media (max-width: 1023px) {
        #navigation-header,
        header#navigation-header,
        .mobile-header {
            display: none !important;
        }
        main.flex-1 {
            padding-top: 0 !important;
            margin-left: 0 !important;
        }
    }
    .vip-glass {
        background: rgba(255, 255, 255, 0.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
    }
</style>
<?php $__env->stopPush(); ?>

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
    <div x-data="{
        videoFilter: '<?php echo e(request('filter', 'latest')); ?>',
        searchQuery: '<?php echo e(request('q', '')); ?>',
        shareChannel() {
            if (navigator.share) {
                navigator.share({
                    title: <?php echo \Illuminate\Support\Js::from($channel->name . ' - Premium Videos')->toHtml() ?>,
                    url: window.location.href
                });
            } else {
                navigator.clipboard.writeText(window.location.href);
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    icon: 'success',
                    title: 'Link copied to clipboard',
                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                });
            }
        }
    }" class="bg-[#F8F9FA] dark:bg-[#0A0A0A] min-h-screen text-slate-900 dark:text-white transition-colors duration-300 pb-20 sm:pb-20 lg:pb-16">

        <!-- Custom Top Bar (Native App Style) -->
        <header class="sticky top-0 z-40 bg-white/95 dark:bg-[#0A0A0A]/95 backdrop-blur-xl border-b border-slate-200/80 dark:border-white/10">
            <div class="max-w-[1440px] mx-auto px-3 sm:px-6 h-14 sm:h-16 flex items-center justify-between gap-2 sm:gap-4">
                
                <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
                    <a href="<?php echo e(route('channels.show', $channel->slug ?? $channel->id)); ?>" 
                       class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center bg-slate-100 dark:bg-white/10 text-slate-700 dark:text-white hover:bg-slate-200 dark:hover:bg-white/20 active:scale-95 transition-all flex-shrink-0"
                       title="Back to Channel">
                        <span class="material-symbols-rounded text-lg sm:text-xl">arrow_back</span>
                    </a>

                    <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl overflow-hidden bg-gradient-to-tr from-amber-500 to-orange-500 p-0.5 flex-shrink-0">
                            <div class="w-full h-full rounded-[10px] overflow-hidden bg-black flex items-center justify-center">
                                <?php $avatar = $channel->avatar ?? $channel->user->image; ?>
                                <?php if($avatar): ?>
                                    <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $avatar)); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <span class="text-xs font-black text-white"><?php echo e(substr($channel->name, 0, 1)); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-1">
                                <h1 class="text-xs sm:text-base font-black truncate tracking-tight text-slate-900 dark:text-white max-w-[130px] xs:max-w-[180px] sm:max-w-none"><?php echo e($channel->name); ?></h1>
                                <span class="material-symbols-rounded text-amber-500 text-xs sm:text-sm fill-1 shrink-0">verified</span>
                            </div>
                            <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-wider text-amber-500 flex items-center gap-1 whitespace-nowrap leading-none mt-0.5">
                                <span class="material-symbols-rounded text-[11px] sm:text-xs">workspace_premium</span>
                                <span>Premium Hub</span>
                            </span>
                        </div>
                    </div>
                </div>

                
                <div class="flex items-center gap-1.5 sm:gap-2 flex-shrink-0">
                    <a href="<?php echo e(route('channels.show', $channel->slug ?? $channel->id)); ?>" 
                       class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-black uppercase tracking-wider bg-slate-100 dark:bg-white/10 text-slate-700 dark:text-white hover:bg-slate-200 dark:hover:bg-white/20 transition-all">
                        <span class="material-symbols-rounded text-sm">home</span>
                        <span>Channel Home</span>
                    </a>

                    <button @click="shareChannel" 
                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center bg-slate-100 dark:bg-white/10 text-slate-700 dark:text-white hover:bg-slate-200 dark:hover:bg-white/20 active:scale-95 transition-all flex-shrink-0"
                            title="Share Premium Page">
                        <span class="material-symbols-rounded text-base sm:text-lg">share</span>
                    </button>

                    <?php if(!$hasSubscription && !$isOwner): ?>
                        <button type="button" 
                                onclick="window.dispatchEvent(new CustomEvent('open-channel-membership-modal'))" 
                                class="h-8 sm:h-10 px-3 sm:px-5 rounded-full bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 hover:opacity-95 text-white font-black text-[10px] sm:text-xs uppercase tracking-wider shadow-md shadow-orange-500/20 active:scale-95 transition-all flex items-center gap-1 whitespace-nowrap flex-shrink-0">
                            <span class="material-symbols-rounded text-sm sm:text-base">loyalty</span>
                            <span>VIP</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <!-- VIP Hero Section (Immersive Native Look) -->
        <div class="relative overflow-hidden bg-gradient-to-b from-amber-500/[0.08] via-orange-500/[0.04] to-transparent border-b border-slate-200/50 dark:border-white/5 py-8 md:py-12">
            
            <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-gradient-to-b from-amber-500/20 to-orange-500/0 blur-3xl pointer-events-none rounded-full"></div>

            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 relative z-10">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-6 md:gap-8">
                    
                    
                    <div class="relative flex-shrink-0 group">
                        <div class="absolute -inset-1 rounded-3xl bg-gradient-to-tr from-amber-500 via-orange-500 to-rose-500 blur-lg opacity-70 group-hover:opacity-100 transition duration-500"></div>
                        <div class="relative w-24 h-24 sm:w-28 sm:h-28 rounded-3xl p-1 bg-gradient-to-tr from-amber-500 to-orange-500">
                            <div class="w-full h-full rounded-[22px] overflow-hidden bg-slate-900 flex items-center justify-center border-2 border-white dark:border-[#0A0A0A]">
                                <?php if($avatar): ?>
                                    <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $avatar)); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <span class="text-3xl font-black text-white"><?php echo e(substr($channel->name, 0, 1)); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="absolute -bottom-2 inset-x-0 flex justify-center">
                            <span class="px-2.5 py-0.5 rounded-full bg-gradient-to-r from-amber-500 to-orange-500 text-white text-[8px] font-black uppercase tracking-widest shadow-md">
                                VIP Hub
                            </span>
                        </div>
                    </div>

                    
                    <div class="flex-grow text-center md:text-left space-y-3">
                        <div class="space-y-1">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 text-[10px] font-black uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Creator Exclusive Vault
                            </div>
                            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-slate-900 dark:text-white">
                                <?php echo e($channel->name); ?>

                            </h2>
                            <p class="text-xs sm:text-sm font-bold text-slate-500 dark:text-white/60">
                                @<span><?php echo e($channel->user->username ?? 'creator'); ?></span> • Official Member Content
                            </p>
                        </div>

                        
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 pt-1">
                            <div class="px-3.5 py-1.5 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 shadow-sm flex items-center gap-1.5 text-xs font-black">
                                <span class="material-symbols-rounded text-amber-500 text-base">video_library</span>
                                <span><?php echo e($totalPremiumCount); ?> Exclusive <?php echo e(Str::plural('Video', $totalPremiumCount)); ?></span>
                            </div>
                            <div class="px-3.5 py-1.5 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 shadow-sm flex items-center gap-1.5 text-xs font-black">
                                <span class="material-symbols-rounded text-rose-500 text-base">group</span>
                                <span><?php echo e(number_format($channel->subscribers_count)); ?> Subscribers</span>
                            </div>
                            <div class="px-3.5 py-1.5 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 shadow-sm flex items-center gap-1.5 text-xs font-black">
                                <span class="material-symbols-rounded text-indigo-500 text-base">visibility</span>
                                <span><?php echo e(number_format($totalViews)); ?> Exclusive Views</span>
                            </div>
                        </div>
                    </div>

                    
                    <div class="w-full md:w-auto md:max-w-sm flex-shrink-0">
                        <?php if($hasSubscription): ?>
                            <!-- Active Subscription Card -->
                            <div class="p-5 rounded-3xl bg-gradient-to-br from-emerald-500/10 via-emerald-500/5 to-transparent border border-emerald-500/30 shadow-xl backdrop-blur-md text-left space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-md">
                                            <span class="material-symbols-rounded text-lg">verified</span>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Membership Active</p>
                                            <h4 class="text-sm font-black text-slate-900 dark:text-white leading-tight">
                                                <?php echo e($activeSubscription->plan->name ?? 'VIP Member Pass'); ?>

                                            </h4>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-[9px] font-black uppercase tracking-widest">
                                        Unlocked
                                    </span>
                                </div>
                                <p class="text-[11px] font-medium text-slate-600 dark:text-white/70 leading-relaxed">
                                    You have full unlimited access to all exclusive content from this creator.
                                </p>
                                <div class="pt-2 border-t border-emerald-500/20 flex items-center justify-between text-[10px] font-bold text-slate-500 dark:text-white/50">
                                    <span>Expires <?php echo e($activeSubscription->expires_at ? $activeSubscription->expires_at->format('M d, Y') : 'Active'); ?></span>
                                    <button type="button" 
                                            onclick="window.dispatchEvent(new CustomEvent('open-channel-membership-modal'))" 
                                            class="text-emerald-600 dark:text-emerald-400 hover:underline font-black uppercase">
                                        Extend Plan
                                    </button>
                                </div>
                            </div>
                        <?php elseif($isOwner): ?>
                            <!-- Creator Studio Owner Notice -->
                            <div class="p-5 rounded-3xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 shadow-xl text-left space-y-3">
                                <div class="flex items-center gap-2 text-amber-500 font-black text-xs uppercase tracking-wider">
                                    <span class="material-symbols-rounded text-lg">admin_panel_settings</span>
                                    <span>Channel Owner</span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-white/60 font-medium">
                                    You have full creator access to view and manage your exclusive catalogue.
                                </p>
                                <a href="<?php echo e(route('studio.channel-plans.index')); ?>" 
                                   class="inline-flex items-center gap-2 text-xs font-black text-amber-500 hover:underline uppercase tracking-wider">
                                    <span>Manage Channel Plans</span>
                                    <span class="material-symbols-rounded text-sm">arrow_forward</span>
                                </a>
                            </div>
                        <?php else: ?>
                            <!-- Non-Subscribed Membership Pitch Card -->
                            <div class="p-5 sm:p-6 rounded-[2rem] bg-white dark:bg-[#141414] border border-amber-500/30 shadow-2xl shadow-orange-500/10 text-left space-y-4 relative overflow-hidden group">
                                <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                                <div class="relative z-10 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[9px] font-black uppercase tracking-[0.2em] text-amber-500">VIP Creator Pass</span>
                                        <?php if($minPlanPrice): ?>
                                            <span class="text-xs font-black text-slate-900 dark:text-white">From ₹<?php echo e(number_format($minPlanPrice, 0)); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <h4 class="text-base font-black text-slate-900 dark:text-white leading-tight">
                                        Unlock All <?php echo e($totalPremiumCount); ?> Exclusive Videos
                                    </h4>
                                    <p class="text-[11px] font-medium text-slate-500 dark:text-white/60 leading-relaxed">
                                        Join <?php echo e($channel->name); ?>'s VIP membership for ad-free 4K episodes and direct creator support.
                                    </p>
                                </div>
                                <button type="button" 
                                        onclick="window.dispatchEvent(new CustomEvent('open-channel-membership-modal'))" 
                                        class="w-full h-11 rounded-2xl bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-orange-500/20 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2">
                                    <span class="material-symbols-rounded text-base">lock_open</span>
                                    <span>Join VIP Membership</span>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

        <!-- Content Area: Filter Bar & Video Grid -->
        <main class="max-w-[1440px] mx-auto px-4 sm:px-6 pt-8">
            
            <!-- Filters & Search Toolbar -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 mb-8">
                
                
                <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1 sm:pb-0">
                    <button type="button" 
                            @click="videoFilter = 'latest'" 
                            :class="videoFilter === 'latest' ? 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 shadow-md' : 'bg-white dark:bg-white/5 text-slate-600 dark:text-white/70 hover:bg-slate-100 dark:hover:bg-white/10 border border-slate-200 dark:border-white/5'"
                            class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all">
                        Latest
                    </button>
                    <button type="button" 
                            @click="videoFilter = 'popular'" 
                            :class="videoFilter === 'popular' ? 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 shadow-md' : 'bg-white dark:bg-white/5 text-slate-600 dark:text-white/70 hover:bg-slate-100 dark:hover:bg-white/10 border border-slate-200 dark:border-white/5'"
                            class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all">
                        Most Popular
                    </button>
                    <button type="button" 
                            @click="videoFilter = 'oldest'" 
                            :class="videoFilter === 'oldest' ? 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 shadow-md' : 'bg-white dark:bg-white/5 text-slate-600 dark:text-white/70 hover:bg-slate-100 dark:hover:bg-white/10 border border-slate-200 dark:border-white/5'"
                            class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all">
                        Oldest
                    </button>
                </div>

                
                <form method="GET" action="<?php echo e(route('channels.premium', $channel->slug ?? $channel->id)); ?>" class="relative w-full sm:w-72">
                    <div class="relative flex items-center">
                        <span class="material-symbols-rounded absolute left-3.5 text-slate-400 text-lg">search</span>
                        <input type="text" 
                               name="q" 
                               value="<?php echo e(request('q')); ?>" 
                               placeholder="Search channel exclusives..." 
                               class="w-full h-10 pl-10 pr-4 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-xs font-bold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-amber-500 transition-colors">
                        <?php if(request('q')): ?>
                            <a href="<?php echo e(route('channels.premium', $channel->slug ?? $channel->id)); ?>" 
                               class="absolute right-3 text-slate-400 hover:text-slate-600 dark:hover:text-white">
                                <span class="material-symbols-rounded text-sm">close</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>

            </div>

            <!-- Videos Grid -->
            <?php
                $hasAnyVideos = ($recentPremiumVideos && $recentPremiumVideos->count() > 0) 
                             || ($popularPremiumVideos && $popularPremiumVideos->count() > 0) 
                             || ($oldestPremiumVideos && $oldestPremiumVideos->count() > 0);
            ?>

            <?php if($hasAnyVideos): ?>
                <div class="space-y-10">
                    
                    <div x-show="videoFilter === 'latest'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-5 gap-y-8">
                        <?php $__empty_1 = true; $__currentLoopData = $recentPremiumVideos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php echo $__env->make('frontend.partials.video_card', [
                                'video' => $video, 
                                'hasSubscription' => $hasSubscription, 
                                'isOwner' => $isOwner
                            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-span-full py-12 text-center text-xs font-bold text-slate-400 uppercase tracking-widest">No latest videos available</div>
                        <?php endif; ?>
                    </div>

                    
                    <div x-show="videoFilter === 'popular'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-5 gap-y-8">
                        <?php $__empty_1 = true; $__currentLoopData = $popularPremiumVideos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php echo $__env->make('frontend.partials.video_card', [
                                'video' => $video, 
                                'hasSubscription' => $hasSubscription, 
                                'isOwner' => $isOwner
                            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-span-full py-12 text-center text-xs font-bold text-slate-400 uppercase tracking-widest">No popular videos available</div>
                        <?php endif; ?>
                    </div>

                    
                    <div x-show="videoFilter === 'oldest'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-5 gap-y-8">
                        <?php $__empty_1 = true; $__currentLoopData = $oldestPremiumVideos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php echo $__env->make('frontend.partials.video_card', [
                                'video' => $video, 
                                'hasSubscription' => $hasSubscription, 
                                'isOwner' => $isOwner
                            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-span-full py-12 text-center text-xs font-bold text-slate-400 uppercase tracking-widest">No oldest videos available</div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php else: ?>
                
                <div class="py-24 text-center max-w-md mx-auto space-y-5">
                    <div class="w-20 h-20 rounded-3xl bg-amber-500/10 text-amber-500 flex items-center justify-center mx-auto shadow-inner">
                        <span class="material-symbols-rounded text-4xl">movie_filter</span>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-lg font-black text-slate-900 dark:text-white">
                            <?php if(request('q')): ?>
                                No results found for "<?php echo e(request('q')); ?>"
                            <?php else: ?>
                                No exclusive videos yet
                            <?php endif; ?>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-white/50 leading-relaxed">
                            <?php if(request('q')): ?>
                                Try searching for different keywords or clear your search query.
                            <?php else: ?>
                                <?php echo e($channel->name); ?> hasn't published any premium videos yet. Check back soon for exclusive content!
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="flex items-center justify-center gap-3 pt-2">
                        <?php if(request('q')): ?>
                            <a href="<?php echo e(route('channels.premium', $channel->slug ?? $channel->id)); ?>" class="h-10 px-5 rounded-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs font-black uppercase tracking-wider flex items-center justify-center">
                                Clear Search
                            </a>
                        <?php else: ?>
                            <a href="<?php echo e(route('channels.show', $channel->slug ?? $channel->id)); ?>" class="h-10 px-6 rounded-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs font-black uppercase tracking-wider flex items-center justify-center">
                                Browse Free Channel Videos
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

        </main>

        <!-- Floating Mobile Membership CTA Bar (Mobile Only for non-subscribers) -->
        <?php if(!$hasSubscription && !$isOwner): ?>
            <div class="lg:hidden fixed bottom-20 inset-x-4 z-40">
                <div class="p-3 bg-slate-950/95 text-white rounded-2xl border border-amber-500/30 shadow-2xl backdrop-blur-xl flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-9 h-9 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-rounded text-lg">workspace_premium</span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-black truncate leading-tight"><?php echo e($channel->name); ?> VIP</p>
                            <p class="text-[10px] text-amber-400 font-bold truncate">
                                <?php if($minPlanPrice): ?>
                                    From ₹<?php echo e(number_format($minPlanPrice, 0)); ?>/mo
                                <?php else: ?>
                                    Unlock <?php echo e($totalPremiumCount); ?> videos
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <button type="button" 
                            onclick="window.dispatchEvent(new CustomEvent('open-channel-membership-modal'))" 
                            class="h-9 px-4 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 text-white font-black text-xs uppercase tracking-wider flex-shrink-0 shadow-md">
                        Join Now
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <!-- Reusable Channel Membership Modal Component -->
        <?php if (isset($component)) { $__componentOriginalf45f692429248001c0aedfcef348f9e5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf45f692429248001c0aedfcef348f9e5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.channel-membership-modal','data' => ['channel' => $channel,'plans' => $plans]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('channel-membership-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['channel' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($channel),'plans' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($plans)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf45f692429248001c0aedfcef348f9e5)): ?>
<?php $attributes = $__attributesOriginalf45f692429248001c0aedfcef348f9e5; ?>
<?php unset($__attributesOriginalf45f692429248001c0aedfcef348f9e5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf45f692429248001c0aedfcef348f9e5)): ?>
<?php $component = $__componentOriginalf45f692429248001c0aedfcef348f9e5; ?>
<?php unset($__componentOriginalf45f692429248001c0aedfcef348f9e5); ?>
<?php endif; ?>

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
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\channels\premium.blade.php ENDPATH**/ ?>