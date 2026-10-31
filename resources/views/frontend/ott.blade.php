<x-app-layout>
    <div class="bg-white dark:bg-[#0F0F0F] min-h-screen pb-24 transition-colors duration-500">
        
        <!-- Hero Section: Immersive Responsive Layout -->
        @if($featuredVideos->isNotEmpty())
        <div x-data="{ 
                 active: 0, 
                 count: {{ count($featuredVideos) }},
                 startX: null,
                 interval: null,
                 startAutoPlay() {
                     this.stopAutoPlay();
                     this.interval = setInterval(() => { this.next() }, 6000);
                 },
                 stopAutoPlay() {
                     if(this.interval) clearInterval(this.interval);
                 },
                 next() { this.active = (this.active + 1) % this.count },
                 prev() { this.active = (this.active - 1 + this.count) % this.count },
                 handleStart(x) { 
                     this.startX = x;
                     this.stopAutoPlay();
                 },
                 handleEnd(x) {
                     if (this.startX === null) return;
                     let diff = this.startX - x;
                     if (Math.abs(diff) > 50) {
                         diff > 0 ? this.next() : this.prev();
                     }
                     this.startX = null;
                     this.startAutoPlay();
                 }
             }" 
             x-init="startAutoPlay()"
             @touchstart="handleStart($event.touches[0].clientX)"
             @touchend="handleEnd($event.changedTouches[0].clientX)"
             @mousedown="handleStart($event.clientX)"
              @mouseup="handleEnd($event.clientX)"
              x-cloak
              class="relative w-full bg-white dark:bg-[#0F0F0F] overflow-hidden h-[45dvh] md:h-[50dvh] lg:h-[45vh] 2xl:h-[50vh] max-h-[550px] cursor-grab active:cursor-grabbing">
            @foreach($featuredVideos as $index => $video)
                <div x-show="active === {{ $index }}" 
                     x-transition:enter="transition ease-out duration-[1500ms]"
                     x-transition:enter-start="opacity-0 scale-105"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute inset-0">
                    
                    <!-- Background Backdrop -->
                    <div class="absolute inset-0 bg-white dark:bg-[#0F0F0F]">
                        <!-- Image Container (Full width on mobile, 85% right-aligned on desktop) -->
                        <div class="absolute top-0 right-0 w-full lg:w-[85%] h-full">
                            <img src="{{ $video->getThumbnailUrl() }}" 
                                 class="w-full h-full object-cover object-top md:object-center opacity-100 dark:opacity-85 {{ ($video->is_age_restricted && !auth()->check()) ? 'blur-2xl' : '' }}"
                                 onerror="this.src='{{ $video->getPreviewUrl() }}'; this.onerror=null;">
                            
                            @if($video->is_age_restricted && !auth()->check())
                                <div class="absolute inset-0 z-40 bg-black/40 backdrop-blur-md flex flex-col items-center justify-center text-center p-4">
                                    <div class="w-16 h-16 rounded-[2rem] bg-rose-600 text-white flex items-center justify-center mb-4 shadow-2xl shadow-rose-500/40 animate-pulse">
                                        <span class="material-symbols-rounded text-4xl font-black">explicit</span>
                                    </div>
                                    <h4 class="text-white font-black text-xl uppercase tracking-widest mb-2">Age Restricted</h4>
                                    <p class="text-rose-100/60 font-bold text-xs uppercase tracking-widest">Sign in to confirm age</p>
                                </div>
                            @endif
                            
                            <!-- Mobile & Tablet Bottom Fade (to make text readable) -->
                            <div class="absolute inset-0 bg-gradient-to-t from-white via-white/80 to-transparent dark:from-[#0F0F0F] dark:via-[#0F0F0F]/80 dark:to-transparent lg:hidden"></div>
                            
                            <!-- Desktop Seamless Left Fade (blends image into the solid background) -->
                            <div class="hidden lg:block absolute inset-y-0 left-0 w-[60%] bg-gradient-to-r from-white via-white/90 to-transparent dark:from-[#0F0F0F] dark:via-[#0F0F0F]/90 dark:to-transparent"></div>
                            
                            <!-- Desktop Bottom Fade (subtle fade for the bottom edge) -->
                            <div class="hidden lg:block absolute inset-x-0 bottom-0 h-[40%] bg-gradient-to-t from-white to-transparent dark:from-[#0F0F0F] dark:to-transparent"></div>
                        </div>
                    </div>

                    <!-- Content Overlay -->
                    <div class="absolute inset-0 z-10 w-full h-full flex flex-col justify-end lg:justify-center">
                        <!-- max-w-[1600px] ensures it perfectly aligns with the grid below on ultra-wide screens -->
                        <div class="max-w-[1600px] mx-auto w-full px-4 md:px-8 lg:px-12 pb-14 md:pb-16 lg:pb-0">
                            <div class="w-full max-w-xl md:max-w-2xl lg:max-w-3xl xl:max-w-4xl">
                                
                                <!-- Meta Tags -->
                                <div class="flex items-center gap-2 lg:gap-3 mb-2 md:mb-4 lg:mb-6">
                                    @if($video->isNewRelease())
                                        <span class="px-2.5 py-1 bg-red-600 text-white text-[8px] lg:text-[11px] font-black uppercase tracking-widest rounded shadow-lg shadow-red-600/30">New Release</span>
                                    @endif
                                    <span class="px-2.5 py-1 bg-black/40 dark:bg-white/10 text-white text-[8px] lg:text-[11px] font-black uppercase tracking-widest rounded backdrop-blur-md border border-white/20">{{ $video->getResolutionBadge() }}</span>
                                    @if($video->isRestricted())
                                        <span class="px-2.5 py-1 bg-rose-600 text-white text-[8px] lg:text-[11px] font-black uppercase tracking-widest rounded shadow-lg shadow-rose-600/30 flex items-center gap-1">
                                            <span class="material-symbols-rounded text-[10px] material-symbols-filled">explicit</span>
                                            18+
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Title -->
                                <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-5xl xl:text-6xl font-black text-slate-900 dark:text-white mb-2 md:mb-3 lg:mb-4 tracking-tighter leading-[1.05] drop-shadow-xl line-clamp-2">{{ $video->title }}</h2>
                                
                                <!-- Description -->
                                <p class="text-slate-700 dark:text-slate-300 text-[10px] sm:text-xs md:text-sm lg:text-sm mb-4 md:mb-5 lg:mb-6 line-clamp-2 md:line-clamp-3 font-medium opacity-90 leading-relaxed drop-shadow-md max-w-2xl">{{ $video->description }}</p>
                                
                                <!-- Actions -->
                                <div class="flex items-center gap-3 md:gap-4">
                                    <a href="{{ route('videos.show', $video) }}" class="px-5 md:px-6 lg:px-8 py-2.5 md:py-3 lg:py-3.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl font-black text-[9px] md:text-[10px] lg:text-xs uppercase tracking-widest flex items-center gap-2 hover:scale-105 active:scale-95 transition-all shadow-xl shadow-slate-900/20 dark:shadow-white/10 group">
                                        <span class="material-symbols-rounded text-lg lg:text-xl material-symbols-filled group-hover:animate-pulse">play_arrow</span>
                                        Watch Now
                                    </a>
                                    <button onclick="window.openVideoOptions('{{ $video->id }}', '{{ addslashes($video->title) }}', 'video', {{ $video->isLikedBy(auth()->user()) ? 'true' : 'false' }}, {{ $video->isInWatchLater(auth()->user()) ? 'true' : 'false' }}, {{ $video->isInAnyPlaylist(auth()->user()) ? 'true' : 'false' }}, @json($video->getPlaylistMembershipIds(auth()->user() ?? null)), {{ $video->user_id }})" class="w-9 h-9 md:w-10 md:h-10 lg:w-11 lg:h-11 bg-white/20 dark:bg-white/10 backdrop-blur-md text-slate-900 dark:text-white rounded-xl flex items-center justify-center border border-slate-900/10 dark:border-white/20 hover:bg-white/30 dark:hover:bg-white/20 transition-all group">
                                        <span class="material-symbols-rounded text-lg lg:text-xl group-hover:rotate-90 transition-transform duration-300">add</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Carousel Dots -->
            <div class="absolute bottom-4 md:bottom-8 left-0 right-0 z-20 flex justify-center gap-1.5">
                @foreach($featuredVideos as $index => $video)
                    <button @click="active = {{ $index }}" 
                            :class="active === {{ $index }} ? 'w-6 bg-slate-900 dark:bg-white' : 'w-1.5 bg-slate-300 dark:bg-white/20'"
                            class="h-1.5 rounded-full transition-all duration-500"></button>
                @endforeach
            </div>
        </div>
        @else
        <!-- Empty Hero State (Native App Look) -->
        <div class="relative w-full h-[45dvh] md:h-[50dvh] lg:h-[45vh] 2xl:h-[50vh] max-h-[550px] flex items-center justify-center overflow-hidden bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-[#151515] dark:via-[#0F0F0F] dark:to-[#1a1208]">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-10 left-10 w-40 h-40 md:w-72 md:h-72 rounded-full bg-[#ff571a]/10 blur-[80px]"></div>
                <div class="absolute bottom-10 right-10 w-40 h-40 md:w-72 md:h-72 rounded-full bg-indigo-500/10 blur-[80px]"></div>
            </div>
            <div class="relative z-10 text-center px-6">
                <div class="w-20 h-20 md:w-24 md:h-24 mx-auto mb-6 rounded-[2rem] bg-white dark:bg-white/5 shadow-xl border border-slate-100 dark:border-white/10 flex items-center justify-center">
                    <span class="material-symbols-rounded text-4xl md:text-5xl text-slate-300 dark:text-white/20">movie_filter</span>
                </div>
                <h2 class="text-xl md:text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tight mb-2">Nothing Streaming Yet</h2>
                <p class="text-[10px] md:text-xs font-bold text-slate-400 dark:text-white/30 uppercase tracking-[0.25em] max-w-md mx-auto leading-relaxed">Featured releases will appear here soon. Stay tuned for fresh content.</p>
            </div>
        </div>
        @endif

        <!-- Main Content Area -->
        <div class="max-w-[1600px] mx-auto px-4 md:px-8 lg:px-12 mt-8 space-y-12">
            
            <!-- Trending Native Grid -->
            @if(isset($trendingPremiumVideos) && $trendingPremiumVideos->isNotEmpty())
            <section class="mb-8 md:mb-10">
                <div class="flex items-center justify-between mb-4 md:mb-6 px-1">
                    <div>
                        <h3 class="text-lg md:text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Trending Now</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Most watched this week</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 lg:gap-8">
                    @foreach($trendingPremiumVideos->take(4) as $video)
                        <div class="group cursor-pointer" onclick="window.location.href='{{ route('videos.show', $video) }}'">
                            <div class="relative aspect-video rounded-2xl overflow-hidden mb-3 border border-slate-100 dark:border-white/5 shadow-sm group-hover:shadow-lg transition-all duration-300">
                                <img src="{{ $video->getThumbnailUrl() }}" class="w-full h-full object-cover {{ ($video->is_age_restricted && !auth()->check()) ? 'blur-xl' : '' }}" onerror="this.src='{{ $video->getPreviewUrl() }}'; this.onerror=null;">
                                @if($video->is_age_restricted && !auth()->check())
                                    <div class="absolute inset-0 z-40 bg-black/40 backdrop-blur-md flex flex-col items-center justify-center text-center p-4">
                                        <div class="w-8 h-8 rounded-xl bg-rose-600 text-white flex items-center justify-center mb-1 shadow-xl shadow-rose-500/40">
                                            <span class="material-symbols-rounded text-sm font-black">explicit</span>
                                        </div>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60 group-hover:opacity-100 transition-opacity"></div>
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30">
                                        <span class="material-symbols-rounded text-white material-symbols-filled">play_arrow</span>
                                    </div>
                                </div>
                                <div class="absolute bottom-2.5 right-2.5 px-1.5 py-0.5 bg-black/70 rounded-md text-[9px] font-bold text-white tracking-wider">{{ $video->formatted_duration }}</div>
                                <div class="absolute top-2.5 left-2.5 px-2 py-0.5 gradient-orange text-white rounded-md text-[8px] font-black uppercase tracking-widest shadow-lg flex items-center gap-1">
                                    <span class="material-symbols-rounded text-[12px] material-symbols-filled">workspace_premium</span>
                                    Premium
                                </div>
                            </div>
                            <div class="px-1">
                                <h4 class="text-slate-900 dark:text-white font-black text-[12px] md:text-sm mb-1 truncate leading-snug group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $video->title }}</h4>
                                <div class="flex items-center gap-2 text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                    <span>{{ formatNumber($video->views) }} Views</span>
                                    <span class="w-1 h-1 rounded-full bg-slate-200 dark:bg-white/10"></span>
                                    <span>{{ $video->created_at->diffForHumans(null, true) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($trendingPremiumVideos->count() > 0)
                <!-- View All Button -->
                <div class="mt-6 flex justify-center">
                    <a href="{{ route('premium.trending') }}" class="w-full md:w-auto md:px-12 text-center py-3 bg-slate-100 dark:bg-white/5 text-slate-900 dark:text-white rounded-xl font-black text-[10px] uppercase tracking-widest flex items-center justify-center gap-2 active:scale-95 hover:bg-slate-200 dark:hover:bg-white/10 transition-all">
                        View All Trending <span class="material-symbols-rounded text-[14px]">arrow_forward</span>
                    </a>
                </div>
                @endif
            </section>
            @else
            <!-- Trending Empty State (Native App Look) -->
            <section class="mb-8 md:mb-10">
                <div class="flex items-center justify-between mb-4 md:mb-6 px-1">
                    <div>
                        <h3 class="text-lg md:text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Trending Now</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Most watched this week</p>
                    </div>
                </div>
                <div class="py-14 md:py-16 flex flex-col items-center justify-center bg-slate-50 dark:bg-white/[0.02] rounded-[2rem] border border-slate-100 dark:border-white/5">
                    <div class="relative mb-5">
                        <div class="absolute inset-0 bg-[#ff571a]/20 blur-2xl rounded-full"></div>
                        <div class="relative w-16 h-16 md:w-20 md:h-20 rounded-[1.5rem] bg-white dark:bg-white/5 shadow-lg border border-slate-100 dark:border-white/10 flex items-center justify-center">
                            <span class="material-symbols-rounded text-3xl md:text-4xl text-slate-300 dark:text-white/20 material-symbols-filled">local_fire_department</span>
                        </div>
                    </div>
                    <h4 class="text-sm md:text-base font-black text-slate-500 dark:text-white/40 uppercase tracking-widest mb-2">No Trending Videos</h4>
                    <p class="text-[9px] md:text-[11px] font-bold text-slate-400/70 dark:text-white/20 uppercase tracking-[0.25em] text-center px-6">The most watched picks will show up here once videos gain views</p>
                </div>
            </section>
            @endif

            <!-- Categories (Native App Style) -->
            <section class="mb-4 md:mb-8" x-data="{ 
                    scrollProgress: 0,
                    isDown: false,
                    isDragging: false,
                    startX: 0,
                    scrollLeft: 0,
                    updateScroll() {
                        const el = this.$refs.slider;
                        if(el.scrollWidth > el.clientWidth) {
                            this.scrollProgress = (el.scrollLeft / (el.scrollWidth - el.clientWidth)) * 100;
                        } else {
                            this.scrollProgress = 0;
                        }
                    },
                    startDrag(e) {
                        this.isDown = true;
                        this.isDragging = false;
                        this.$refs.slider.style.scrollBehavior = 'auto';
                        this.startX = e.pageX || e.touches?.[0]?.pageX || 0;
                        this.scrollLeft = this.$refs.slider.scrollLeft;
                    },
                    endDrag() {
                        this.isDown = false;
                        this.$refs.slider.style.scrollBehavior = '';
                        setTimeout(() => { this.isDragging = false; }, 50);
                    },
                    doDrag(e) {
                        if (!this.isDown) return;
                        e.preventDefault();
                        const x = e.pageX || e.touches?.[0]?.pageX || 0;
                        const walk = x - this.startX;
                        if (Math.abs(walk) > 5) this.isDragging = true;
                        this.$refs.slider.scrollLeft = this.scrollLeft - walk;
                    },
                    handleClick(e) {
                        if (this.isDragging) {
                            e.preventDefault();
                            e.stopPropagation();
                        }
                    }
                }" x-init="setTimeout(() => updateScroll(), 100)">
                <style>
                    .native-scroll::-webkit-scrollbar { display: none; }
                    .native-scroll { -ms-overflow-style: none; scrollbar-width: none; }
                </style>
                <div x-ref="slider" 
                     @scroll="updateScroll" 
                     @mousedown.prevent="startDrag($event)"
                     @mouseleave="endDrag"
                     @mouseup="endDrag"
                     @mousemove="doDrag($event)"
                     @touchstart.passive="startDrag($event)"
                     @touchend="endDrag"
                     @touchmove="doDrag($event)"
                     class="flex overflow-x-auto native-scroll gap-2 md:gap-3 px-1 pb-2 cursor-grab active:cursor-grabbing select-none">
                    <a @click="handleClick($event)" href="{{ route('premium') }}" class="flex-shrink-0 block px-4 md:px-6 py-2 md:py-2.5 rounded-full text-[10px] md:text-xs font-black uppercase tracking-widest whitespace-nowrap transition-all shadow-sm {{ !request()->category ? 'bg-[#ff571a] text-white shadow-[#ff571a]/30' : 'bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-gray-300 hover:bg-slate-200 dark:hover:bg-white/10' }}">All Premium</a>
                    @foreach($categories as $category)
                        <a @click="handleClick($event)" href="{{ route('premium', ['category' => $category->slug]) }}" class="flex-shrink-0 block px-4 md:px-6 py-2 md:py-2.5 rounded-full text-[10px] md:text-xs font-black uppercase tracking-widest whitespace-nowrap transition-all shadow-sm {{ request()->category == $category->slug ? 'bg-[#ff571a] text-white shadow-[#ff571a]/30' : 'bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-gray-300 hover:bg-slate-200 dark:hover:bg-white/10' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
                <!-- Orange Progress Bar -->
                <div class="max-w-[200px] mx-auto mt-2 h-[3px] bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full bg-[#ff571a] rounded-full transition-all duration-75" :style="`width: ${scrollProgress}%`"></div>
                </div>
            </section>

            <!-- Premium Videos Grid -->
            <section>
                <div class="flex items-center justify-between mb-4 md:mb-6 px-1">
                    <div>
                        <h3 class="text-lg md:text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ request()->category ? 'Premium ' . ucfirst(request()->category) : 'Premium Hits' }}</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Exclusive content for subscribers</p>
                    </div>
                    <a href="{{ route('premium.all', request()->category ? ['category' => request()->category] : []) }}" class="hidden md:flex items-center gap-1 text-[10px] md:text-[11px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest hover:underline bg-blue-500/10 px-3 py-1.5 rounded-lg">View All <span class="material-symbols-rounded text-[14px]">chevron_right</span></a>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 lg:gap-8">
                    @forelse($premiumVideos->take(4) as $index => $video)
                        <div class="group cursor-pointer" 
                             @php
                                $isOptimizing = ($video->isBunnyVideo() && !in_array($video->bunny_status, ['ready', 'error', 'failed'])) || (!$video->isBunnyVideo() && $video->status === 'processing');
                             @endphp
                             x-data="{ 
                                progress: 0, 
                                isOptimizing: {{ $isOptimizing ? 'true' : 'false' }},
                                init() { if(this.isOptimizing) this.pollStatus(); },
                                async pollStatus() {
                                    try {
                                        const res = await fetch('{{ route("videos.check_status", $video->slug) }}');
                                        const data = await res.json();
                                        if(data.success) {
                                            this.progress = data.encode_progress || 0;
                                            if(data.encode_progress >= 100) window.location.reload();
                                            else setTimeout(() => this.pollStatus(), 5000);
                                        }
                                    } catch(e) { setTimeout(() => this.pollStatus(), 10000); }
                                }
                             }"
                             onclick="window.location.href='{{ route('videos.show', $video) }}'">
                            <div class="relative aspect-video rounded-2xl overflow-hidden mb-3 shadow-md group-hover:shadow-xl transition-all duration-300">
                                <img src="{{ $video->getThumbnailUrl() }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 {{ ($video->isRestricted() && !auth()->check()) ? 'blur-xl' : '' }}" onerror="this.src='{{ $video->getPreviewUrl() }}'; this.onerror=null;">
                                
                                <!-- Branded Dynamic Loading State -->
                                <template x-if="isOptimizing">
                                    <div class="absolute inset-0 bg-black/60 backdrop-blur-md flex flex-col items-center justify-center z-[41]">
                                        <div class="relative w-10 h-10 mb-2">
                                            <svg class="w-full h-full transform -rotate-90">
                                                <circle cx="20" cy="20" r="16" stroke="currentColor" stroke-width="3" fill="transparent" class="text-white/10" />
                                                <circle cx="20" cy="20" r="16" stroke="currentColor" stroke-width="3" fill="transparent" class="text-orange-500 transition-all duration-500" stroke-dasharray="100.5" :stroke-dashoffset="100.5 - (100.5 * progress / 100)" />
                                            </svg>
                                            <div class="absolute inset-0 flex items-center justify-center"></div>
                                        </div>
                                        <span class="text-[6px] font-black text-white uppercase tracking-widest animate-pulse">please wait awesome is loading</span>
                                    </div>
                                </template>
                                
                                @if($video->isRestricted() && !auth()->check())
                                    <div class="absolute inset-0 z-40 bg-black/40 backdrop-blur-md flex flex-col items-center justify-center text-center p-4">
                                        <div class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center mb-2 shadow-xl shadow-rose-500/40">
                                            <span class="material-symbols-rounded text-xl font-black">explicit</span>
                                        </div>
                                        <h4 class="text-white font-black text-[8px] uppercase tracking-widest">Restricted Content</h4>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60 group-hover:opacity-100 transition-opacity"></div>
                                
                                <!-- Play Center Icon -->
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30">
                                        <span class="material-symbols-rounded text-white material-symbols-filled">play_arrow</span>
                                    </div>
                                </div>

                                <!-- Time Badge -->
                                <div class="absolute bottom-2.5 right-2.5 px-1.5 py-0.5 bg-black/70 rounded-md text-[9px] font-bold text-white tracking-wider">
                                    {{ $video->formatted_duration }}
                                </div>

                                <!-- Premium Tag -->
                                <div class="absolute top-2.5 left-2.5 px-2 py-0.5 gradient-orange text-white rounded-md text-[8px] font-black uppercase tracking-widest shadow-lg flex items-center gap-1">
                                    <span class="material-symbols-rounded text-[12px] material-symbols-filled">workspace_premium</span>
                                    Premium
                                </div>
                                @if($video->is_age_restricted)
                                    <div class="absolute top-2.5 right-2.5 px-2 py-0.5 bg-rose-600 text-white rounded-md text-[8px] font-black uppercase tracking-widest shadow-lg flex items-center gap-1">
                                        18+
                                    </div>
                                @endif
                            </div>
                            <div class="px-1">
                                <h4 class="text-slate-900 dark:text-white font-black text-[12px] md:text-sm mb-1 line-clamp-2 leading-snug group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $video->title }}</h4>
                                <div class="flex items-center gap-2 text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                    <span>{{ formatNumber($video->views) }} Views</span>
                                    <span class="w-1 h-1 rounded-full bg-slate-200 dark:bg-white/10"></span>
                                    <span>{{ $video->created_at->diffForHumans(null, true) }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-20 flex flex-col items-center justify-center bg-slate-50 dark:bg-white/[0.02] rounded-3xl border-2 border-dashed border-slate-100 dark:border-white/5">
                            <span class="material-symbols-rounded text-5xl text-slate-300 dark:text-white/10 mb-4">movie_filter</span>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">No videos available</p>
                        </div>
                    @endforelse
                </div>
                
                @if($premiumVideos->count() > 0)
                <!-- View All Button -->
                <div class="mt-6 flex justify-center">
                    <a href="{{ route('premium.all', request()->category ? ['category' => request()->category] : []) }}" class="w-full md:w-auto md:px-12 text-center py-3 bg-slate-100 dark:bg-white/5 text-slate-900 dark:text-white rounded-xl font-black text-[10px] uppercase tracking-widest flex items-center justify-center gap-2 active:scale-95 hover:bg-slate-200 dark:hover:bg-white/10 transition-all">
                        View All {{ request()->category ? ucfirst(request()->category) : 'Premium' }} <span class="material-symbols-rounded text-[14px]">arrow_forward</span>
                    </a>
                </div>
                @endif
            </section>

            <!-- ═══ PREMIUM CHANNELS SECTION ═══ -->
            @if(isset($subscriptionChannels) && $subscriptionChannels->count() > 0)
            <section class="mt-12 md:mt-16 pt-8 border-t border-gray-100 dark:border-white/5">
                <div class="flex items-center gap-2 md:gap-3 mb-4 md:mb-6 px-1">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg md:rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-500">
                        <span class="material-symbols-rounded text-lg md:text-xl">workspace_premium</span>
                    </div>
                    <div>
                        <h3 class="text-base md:text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Premium Channels</h3>
                        <p class="text-[9px] md:text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mt-0.5">Subscribe to exclusive creator channels & unlock VIP content</p>
                    </div>
                </div>

                @php
                    $subscriptionChannels = $subscriptionChannels->take(12);
                    $canLoopOttPlans = $subscriptionChannels->count() > 1;
                @endphp
                <div x-data="{ 
                    speed: 0.8,
                    paused: false,
                    isDown: false,
                    dragged: false,
                    startX: 0,
                    scrollLeft: 0,
                    canLoop: {{ $canLoopOttPlans ? 'true' : 'false' }},
                    init() {
                        if (!this.canLoop) return;
                        const el = this.$refs.slider;
                        const loop = () => {
                            if (!this.paused && !this.isDown && el) {
                                el.scrollLeft += this.speed;
                                if (el.scrollLeft >= el.scrollWidth / 2) {
                                    el.scrollLeft = 0;
                                }
                            }
                            requestAnimationFrame(loop);
                        };
                        loop();
                    },
                    handleMouseDown(e) {
                        this.isDown = true;
                        this.paused = true;
                        this.dragged = false;
                        this.startX = e.pageX - this.$refs.slider.offsetLeft;
                        this.scrollLeft = this.$refs.slider.scrollLeft;
                    },
                    handleMouseMove(e) {
                        if (!this.isDown) return;
                        e.preventDefault();
                        const x = e.pageX - this.$refs.slider.offsetLeft;
                        const walk = (x - this.startX) * 1.5;
                        if (Math.abs(x - this.startX) > 5) {
                            this.dragged = true;
                        }
                        this.$refs.slider.scrollLeft = this.scrollLeft - walk;
                    },
                    handleMouseUp() {
                        this.isDown = false;
                        this.paused = false;
                        setTimeout(() => { this.dragged = false; }, 50);
                    },
                    handleTouchStart(e) {
                        this.isDown = true;
                        this.paused = true;
                        this.dragged = false;
                        this.startX = e.touches[0].pageX - this.$refs.slider.offsetLeft;
                        this.scrollLeft = this.$refs.slider.scrollLeft;
                    },
                    handleTouchMove(e) {
                        if (!this.isDown) return;
                        const x = e.touches[0].pageX - this.$refs.slider.offsetLeft;
                        const walk = (x - this.startX) * 1.5;
                        if (Math.abs(x - this.startX) > 5) {
                            this.dragged = true;
                        }
                        this.$refs.slider.scrollLeft = this.scrollLeft - walk;
                    },
                    handleTouchEnd() {
                        this.isDown = false;
                        this.paused = false;
                        setTimeout(() => { this.dragged = false; }, 50);
                    }
                }">
                    <div class="relative">
                        <div x-ref="slider" 
                             @mousedown="handleMouseDown($event)"
                             @mousemove="handleMouseMove($event)"
                             @mouseup="handleMouseUp()"
                             @mouseleave="handleMouseUp()"
                             @touchstart="handleTouchStart($event)"
                             @touchmove="handleTouchMove($event)"
                             @touchend="handleTouchEnd()"
                             @mouseenter="paused = true" 
                             @mouseleave="paused = false"
                             @dragstart.prevent
                             @click="if (dragged) { $event.preventDefault(); $event.stopPropagation(); }"
                             class="flex gap-4 md:gap-6 overflow-x-auto no-scrollbar py-2 md:py-4 {{ $canLoopOttPlans ? 'cursor-grab active:cursor-grabbing' : '' }} select-none">
                            @foreach(($canLoopOttPlans ? $subscriptionChannels->concat($subscriptionChannels) : $subscriptionChannels) as $channel)
                                @include('frontend.partials.channel_plan_card_small', ['channel' => $channel])
                            @endforeach
                        </div>
                    </div>

                    <!-- View All Premium Channels Button Below Card -->
                    <div class="mt-6 flex justify-center">
                        <a href="{{ route('channels.premium.all') }}" class="w-full md:w-auto md:px-12 text-center py-3 bg-slate-100 dark:bg-white/5 text-slate-900 dark:text-white rounded-xl font-black text-[10px] uppercase tracking-widest flex items-center justify-center gap-2 active:scale-95 hover:bg-slate-200 dark:hover:bg-white/10 transition-all">
                            View All Premium Channels <span class="material-symbols-rounded text-[14px]">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </section>
            @endif

        </div>
    </div>
</x-app-layout>

