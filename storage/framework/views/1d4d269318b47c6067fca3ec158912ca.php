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
            
            <!-- Page Header -->
            <div class="mb-4 md:mb-6">
                <div class="flex items-center gap-2 text-slate-500 dark:text-gray-400 text-[10px] md:text-xs font-bold uppercase tracking-widest mb-1">
                    <a href="<?php echo e(route('premium')); ?>" class="hover:text-blue-500 transition-colors">OTT</a>
                    <span class="material-symbols-rounded text-[14px]">chevron_right</span>
                    <span>View All</span>
                </div>
                <h1 class="text-xl md:text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tight flex items-center gap-3">
                    <?php echo e(request()->category ? 'Premium ' . ucfirst(request()->category) : 'All Premium Videos'); ?>

                    <span class="material-symbols-rounded text-2xl md:text-3xl text-amber-500 material-symbols-filled">workspace_premium</span>
                </h1>
                <p class="text-[11px] md:text-sm font-bold text-slate-500 dark:text-slate-400 mt-1">Browse our complete collection of exclusive subscriber-only content.</p>
            </div>

            <!-- Dynamic Controls (Categories & Filters) -->
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4 md:mb-6 px-1">
                <div class="flex items-center gap-2">
                    <div class="text-[11px] md:text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest hidden md:block mr-2">
                        <span class="text-slate-900 dark:text-white text-sm md:text-base mr-1"><?php echo e($premiumVideos->total()); ?></span> Results
                    </div>
                    
                    <!-- Categories Dropdown -->
                    <div class="relative" x-data="{ openCat: false }">
                        <button @click="openCat = !openCat" @click.outside="openCat = false" 
                                class="flex items-center gap-1.5 px-3 py-1.5 md:px-4 md:py-2 bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 active:scale-95 text-slate-700 dark:text-gray-200 rounded-full text-[10px] md:text-xs font-black uppercase tracking-widest transition-all">
                            <span class="material-symbols-rounded text-[14px] md:text-[16px]">category</span>
                            <span><?php echo e(request()->category ? ucfirst(request()->category) : 'All Categories'); ?></span>
                            <span class="material-symbols-rounded text-[14px] md:text-[16px] transition-transform duration-300" :class="openCat ? 'rotate-180' : ''">keyboard_arrow_down</span>
                        </button>
                        
                        <div x-show="openCat" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                             class="absolute left-0 top-full mt-3 w-56 max-h-[60vh] overflow-y-auto native-scroll bg-white/95 dark:bg-[#1f1f1f]/95 backdrop-blur-xl border border-slate-200/50 dark:border-white/10 rounded-[1.25rem] shadow-2xl z-50" x-cloak>
                            
                            <style>
                                .native-scroll::-webkit-scrollbar { display: none; }
                                .native-scroll { -ms-overflow-style: none; scrollbar-width: none; }
                            </style>

                            <div class="px-4 py-3 border-b border-slate-100 dark:border-white/5 sticky top-0 bg-white/95 dark:bg-[#1f1f1f]/95 backdrop-blur-md z-10">
                                <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Select Category</span>
                            </div>
                            
                            <div class="p-1.5">
                                <a href="<?php echo e(route('premium.all', request()->sort ? ['sort' => request()->sort] : [])); ?>" 
                                   class="flex items-center justify-between px-3 py-2.5 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all <?php echo e(!request()->category ? 'bg-orange-50 dark:bg-[#ff571a]/10 text-[#ff571a]' : 'text-slate-600 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white'); ?>">
                                    All Premium
                                    <?php if(!request()->category): ?>
                                        <span class="material-symbols-rounded text-[14px] text-[#ff571a] material-symbols-filled">check_circle</span>
                                    <?php endif; ?>
                                </a>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('premium.all', array_merge(['category' => $category->slug], request()->sort ? ['sort' => request()->sort] : []))); ?>" 
                                       class="flex items-center justify-between px-3 py-2.5 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all <?php echo e(request()->category == $category->slug ? 'bg-orange-50 dark:bg-[#ff571a]/10 text-[#ff571a]' : 'text-slate-600 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white'); ?>">
                                        <?php echo e($category->name); ?>

                                        <?php if(request()->category == $category->slug): ?>
                                            <span class="material-symbols-rounded text-[14px] text-[#ff571a] material-symbols-filled">check_circle</span>
                                        <?php endif; ?>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sort Filters Dropdown -->
                <div class="relative" x-data="{ openFilters: false }">
                    <?php
                        $currentSort = request()->sort ?? 'latest';
                        $baseParams = request()->category ? ['category' => request()->category] : [];
                        $sortOptions = [
                            'latest' => ['label' => 'Latest', 'icon' => 'schedule'],
                            'most_viewed' => ['label' => 'Most Viewed', 'icon' => 'visibility'],
                            'trending' => ['label' => 'Trending', 'icon' => 'local_fire_department'],
                            'oldest' => ['label' => 'Oldest', 'icon' => 'history'],
                        ];
                    ?>
                    <button @click="openFilters = !openFilters" @click.outside="openFilters = false" 
                            class="flex items-center gap-1.5 px-3 py-1.5 md:px-4 md:py-2 bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 active:scale-95 text-slate-700 dark:text-gray-200 rounded-full text-[10px] md:text-xs font-black uppercase tracking-widest transition-all">
                        <span class="material-symbols-rounded text-[14px] md:text-[16px]">tune</span>
                        <span>Filter</span>
                        <?php if(request()->sort && request()->sort !== 'latest'): ?>
                            <div class="w-1.5 h-1.5 rounded-full bg-[#ff571a] ml-0.5"></div>
                        <?php endif; ?>
                    </button>
                    
                    <div x-show="openFilters" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                         class="absolute right-0 top-full mt-3 w-56 bg-white/95 dark:bg-[#1f1f1f]/95 backdrop-blur-xl border border-slate-200/50 dark:border-white/10 rounded-[1.25rem] shadow-2xl z-50 overflow-hidden" x-cloak>
                        
                        <div class="px-4 py-3 border-b border-slate-100 dark:border-white/5">
                            <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Sort By</span>
                        </div>
                        
                        <div class="p-1.5">
                            <?php $__currentLoopData = $sortOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('premium.all', array_merge($baseParams, $key === 'latest' ? [] : ['sort' => $key]))); ?>" 
                                   class="flex items-center justify-between px-3 py-2.5 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all <?php echo e($currentSort === $key ? 'bg-orange-50 dark:bg-[#ff571a]/10 text-[#ff571a]' : 'text-slate-600 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white'); ?>">
                                    <div class="flex items-center gap-3">
                                        <span class="material-symbols-rounded text-[16px] <?php echo e($currentSort === $key ? 'material-symbols-filled' : ''); ?> opacity-80"><?php echo e($opt['icon']); ?></span>
                                        <?php echo e($opt['label']); ?>

                                    </div>
                                    <?php if($currentSort === $key): ?>
                                        <span class="material-symbols-rounded text-[14px] text-[#ff571a] material-symbols-filled">check_circle</span>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div x-data="{
                    videos: [],
                    page: 1,
                    lastPage: <?php echo e($premiumVideos->lastPage()); ?>,
                    loading: false,
                    initialLoaded: false,
                    loadMore() {
                        if (this.loading || this.page > this.lastPage) return;
                        this.loading = true;
                        let url = '<?php echo e(route('premium.all')); ?>?page=' + this.page;
                        <?php if(request()->category): ?>
                            url += '&category=<?php echo e(request()->category); ?>';
                        <?php endif; ?>
                        <?php if(request()->sort): ?>
                            url += '&sort=<?php echo e(request()->sort); ?>';
                        <?php endif; ?>
                        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                            .then(r => r.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                const grid = doc.querySelector('[data-video-grid]');
                                if (grid) {
                                    this.$refs.grid.insertAdjacentHTML('beforeend', grid.innerHTML);
                                }
                                this.page++;
                                this.loading = false;
                            })
                            .catch(() => { this.loading = false; });
                    },
                    handleScroll() {
                        if (this.loading || this.page > this.lastPage) return;
                        const scrollPos = window.innerHeight + window.scrollY;
                        const threshold = document.body.offsetHeight - 600;
                        if (scrollPos >= threshold) {
                            this.loadMore();
                        }
                    }
                }" 
                x-init="page = 2"
                @scroll.window.throttle.200ms="handleScroll">
                
                <div x-ref="grid" data-video-grid class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-5">
                    <?php $__empty_1 = true; $__currentLoopData = $premiumVideos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="group cursor-pointer" onclick="window.location.href='<?php echo e(route('videos.show', $video)); ?>'">
                            <div class="relative aspect-video rounded-2xl overflow-hidden mb-3 shadow-md group-hover:shadow-xl transition-all duration-300">
                                <img src="<?php echo e($video->getThumbnailUrl()); ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 <?php echo e(($video->isRestricted() && !auth()->check()) ? 'blur-xl' : ''); ?>" onerror="this.src='<?php echo e($video->getPreviewUrl()); ?>'; this.onerror=null;">
                                
                                <?php if($video->isRestricted() && !auth()->check()): ?>
                                    <div class="absolute inset-0 z-40 bg-black/40 backdrop-blur-md flex flex-col items-center justify-center text-center p-4">
                                        <div class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center mb-2 shadow-xl shadow-rose-500/40">
                                            <span class="material-symbols-rounded text-xl font-black">explicit</span>
                                        </div>
                                        <h4 class="text-white font-black text-[8px] uppercase tracking-widest">Restricted Content</h4>
                                    </div>
                                <?php endif; ?>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60 group-hover:opacity-100 transition-opacity"></div>
                                
                                <!-- Play Center Icon -->
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30">
                                        <span class="material-symbols-rounded text-white material-symbols-filled">play_arrow</span>
                                    </div>
                                </div>

                                <!-- Time Badge -->
                                <div class="absolute bottom-2.5 right-2.5 px-1.5 py-0.5 bg-black/70 rounded-md text-[9px] font-bold text-white tracking-wider">
                                    <?php echo e($video->formatted_duration); ?>

                                </div>

                                <!-- Premium Tag -->
                                <div class="absolute top-2.5 left-2.5 px-2 py-0.5 gradient-orange text-white rounded-md text-[8px] font-black uppercase tracking-widest shadow-lg flex items-center gap-1">
                                    <span class="material-symbols-rounded text-[12px] material-symbols-filled">workspace_premium</span>
                                    Premium
                                </div>
                            </div>
                            <div class="px-1 flex gap-3">
                                <div class="flex-shrink-0">
                                    <?php if($video->user?->channel?->avatar): ?>
                                        <img src="<?php echo e(getImage(getFilePath('channelAvatar') . '/' . $video->user->channel->avatar)); ?>" class="w-8 h-8 rounded-full object-cover">
                                    <?php elseif($video->user?->image): ?>
                                        <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $video->user->image)); ?>" class="w-8 h-8 rounded-full object-cover">
                                    <?php else: ?>
                                        <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-white/10 flex items-center justify-center text-xs font-black text-slate-500 dark:text-slate-300"><?php echo e(substr($video->user?->channel?->name ?? $video->user?->username ?? 'C', 0, 1)); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h4 class="text-slate-900 dark:text-white font-black text-[12px] md:text-sm mb-1 line-clamp-2 leading-snug group-hover:text-[#ff571a] transition-colors"><?php echo e($video->title); ?></h4>
                                    <p class="text-[10px] font-bold text-slate-500 dark:text-gray-400 mb-0.5"><?php echo e($video->user->channel->name ?? $video->user->username); ?></p>
                                    <div class="flex items-center gap-2 text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                        <span><?php echo e(formatNumber($video->views)); ?> Views</span>
                                        <span class="w-1 h-1 rounded-full bg-slate-200 dark:bg-white/10"></span>
                                        <span><?php echo e($video->created_at->diffForHumans(null, true)); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-span-full py-20 flex flex-col items-center justify-center bg-slate-50 dark:bg-white/[0.02] rounded-3xl border-2 border-dashed border-slate-100 dark:border-white/5">
                            <span class="material-symbols-rounded text-5xl text-slate-300 dark:text-white/10 mb-4">movie_filter</span>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">No premium videos found</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Infinite Scroll Loader -->
                <div x-show="loading" class="mt-8 flex flex-col items-center justify-center py-6">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 bg-[#ff571a] rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                        <div class="w-2 h-2 bg-[#ff571a] rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                        <div class="w-2 h-2 bg-[#ff571a] rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-3">Loading more videos</p>
                </div>

                <!-- End of Content -->
                <div x-show="!loading && page > lastPage && lastPage > 1" x-cloak class="mt-8 flex flex-col items-center justify-center py-6">
                    <div class="w-12 h-[2px] bg-slate-200 dark:bg-white/10 rounded-full mb-3"></div>
                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">You've seen it all</p>
                </div>
            </div>

        </div>
    </div>

 <?php $__env->slot('pageStyles', null, []); ?> 
<style>
    [x-cloak] { display: none !important; }
</style>
 <?php $__env->endSlot(); ?>
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
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\premium_videos.blade.php ENDPATH**/ ?>