<x-app-layout>
<style>
    .orange-scrollbar::-webkit-scrollbar {
        height: 3px;
    }
    @media (max-width: 768px) {
        .orange-scrollbar::-webkit-scrollbar {
            height: 4px;
        }
    }
    .orange-scrollbar::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 10px;
    }
    .dark .orange-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }
    .orange-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(to right, #ff571a, #ef4444);
        border-radius: 10px;
    }
    /* Homepage channel card subscriber icon: layout forces color:inherit on all
       material icons, so restore rose (light) / white (dark) + filled glyph here */
    .channel-count-icon:not(:empty) {
        color: #f43f5e !important;
        font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24 !important;
    }
    .dark .channel-count-icon:not(:empty) {
        color: #ffffff !important;
    }
    /* Homepage creator card rating star: same layout override issue —
       yellow (light) / white (dark) + filled glyph */
    .creator-rating-icon:not(:empty) {
        color: #facc15 !important;
        font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24 !important;
    }
    .dark .creator-rating-icon:not(:empty) {
        color: #ffffff !important;
    }
</style>
    <!-- Welcome Hero Section (Modern & Impactful) -->
    <div class="relative z-0 overflow-hidden bg-white dark:bg-[#0F0F0F] pt-8 md:pt-16 pb-6 md:pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-3xl md:text-7xl font-black tracking-tight mb-2 md:mb-4 animate-in fade-in slide-in-from-top-10 duration-700">
                <span class="text-gray-900 dark:text-white">Welcome to </span>
                <span class="text-[#ff571a]">Binteo</span>
            </h1>
            <p class="max-w-2xl mx-auto text-gray-500 dark:text-gray-400 font-medium text-[11px] md:text-lg leading-relaxed mb-6 md:mb-10 animate-in fade-in slide-in-from-top-10 duration-1000 delay-200 px-4 md:px-0">
                Discover amazing videos, create content, and connect with professional creators. Your ultimate social video platform.
            </p>
            
            <div class="grid grid-cols-2 md:flex md:flex-row items-center justify-center gap-2 md:gap-4 animate-in fade-in slide-in-from-bottom-10 duration-1000 delay-300 w-full px-4 md:px-0 mt-2 md:mt-0">
                <a href="{{ route('videos.create') }}" class="col-span-1 px-2 md:px-8 py-3 md:py-4 bg-[#ff571a] text-white rounded-xl font-black text-[9px] md:text-sm uppercase tracking-widest shadow-xl shadow-[#ff571a]/30 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-1.5 md:gap-3">
                    <span class="material-symbols-rounded text-[16px] md:text-[22px]">videocam</span>
                    <span class="whitespace-nowrap">Upload Video</span>
                </a>
                <a href="{{ route('reels.create') }}" class="col-span-1 px-2 md:px-8 py-3 md:py-4 bg-white dark:bg-white/5 text-gray-900 dark:text-white rounded-xl font-black text-[9px] md:text-sm uppercase tracking-widest border border-gray-200 dark:border-white/10 hover:bg-gray-100 dark:hover:bg-white/10 active:scale-95 transition-all flex items-center justify-center gap-1.5 md:gap-3">
                    <span class="material-symbols-rounded text-[16px] md:text-[22px]">play_circle</span>
                    <span class="whitespace-nowrap">Create Reel</span>
                </a>
                <a href="{{ route('marketplace.index') }}" class="col-span-2 md:col-auto px-4 md:px-8 py-3 md:py-4 bg-white dark:bg-white/5 text-gray-900 dark:text-white rounded-xl font-black text-[10px] md:text-sm uppercase tracking-widest border border-gray-200 dark:border-white/10 hover:bg-gray-100 dark:hover:bg-white/10 active:scale-95 transition-all flex items-center justify-center gap-2 md:gap-3">
                    <span class="material-symbols-rounded text-[18px] md:text-[22px]">storefront</span>
                    <span class="whitespace-nowrap">Explore Marketplace</span>
                </a>
            </div>
        </div>

        <!-- Background subtle glow -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full pointer-events-none overflow-hidden">
            <div class="absolute top-[-10%] left-[20%] w-[40%] h-[40%] bg-[#ff571a]/10 blur-[120px] rounded-full"></div>
            <div class="absolute bottom-[-10%] right-[20%] w-[30%] h-[30%] bg-purple-500/5 blur-[100px] rounded-full"></div>
        </div>
    </div>

    <!-- Featured Videos Section (Replacing Ad Slot) -->
    @if(isset($featuredVideos) && $featuredVideos->count() > 0)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 mb-12">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-[#ff571a]/10 text-[#ff571a] flex items-center justify-center shadow-sm">
                <span class="material-symbols-rounded text-[22px] fill-1">stars</span>
            </div>
            <div>
                <h2 class="text-xl md:text-2xl font-black text-gray-900 dark:text-white tracking-tight">Featured Content</h2>
                <p class="text-[9px] md:text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mt-0.5">Handpicked for you</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($featuredVideos->take(4) as $video)
                <div class="relative group">
                    @include('frontend.partials.video_card', ['video' => $video])
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Interactive Section (Tabs) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12" x-data="{ activeTab: 'trending' }">
        <!-- Tab Navigation -->
        <div class="flex items-center justify-center mb-10 md:mb-12">
            <div class="inline-flex p-1 md:p-1.5 bg-gray-100 dark:bg-white/5 rounded-[1.2rem] md:rounded-2xl border border-gray-200 dark:border-white/10 shadow-inner max-w-full overflow-x-auto no-scrollbar">
                <button 
                    @click="activeTab = 'trending'"
                    :class="activeTab === 'trending' ? 'bg-white dark:bg-white/10 text-[#ff571a] shadow-lg' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                    class="px-3 md:px-8 py-2 md:py-3 rounded-lg md:rounded-xl font-black text-[9px] md:text-xs uppercase tracking-widest transition-all flex items-center gap-1.5 md:gap-3 whitespace-nowrap"
                >
                    <span class="material-symbols-rounded text-[16px] md:text-[20px]" :class="activeTab === 'trending' ? 'fill-1' : ''">trending_up</span>
                    Trending
                </button>
                <button 
                    @click="activeTab = 'creators'"
                    :class="activeTab === 'creators' ? 'bg-white dark:bg-white/10 text-[#ff571a] shadow-lg' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                    class="px-3 md:px-8 py-2 md:py-3 rounded-lg md:rounded-xl font-black text-[9px] md:text-xs uppercase tracking-widest transition-all flex items-center gap-1.5 md:gap-3 whitespace-nowrap"
                >
                    <span class="material-symbols-rounded text-[16px] md:text-[20px]" :class="activeTab === 'creators' ? 'fill-1' : ''">group</span>
                    Featured Profiles
                </button>
            </div>
        </div>

        <!-- Trending Videos Content -->
        <div x-show="activeTab === 'trending'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            @if(isset($trendingVideos) && $trendingVideos->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($trendingVideos as $index => $video)
                        <div class="relative group">
                            <!-- Premium Ranking Badge (Commented out as requested) -->
                            {{-- 
                            <div class="absolute -top-4 -left-2 z-30 pointer-events-none">
                                <div class="relative flex items-center justify-center">
                                    <span class="text-7xl font-black opacity-30 group-hover:opacity-60 transition-all duration-500 text-gradient-orange select-none">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="absolute inset-0 flex items-center justify-center translate-y-2 translate-x-2">
                                        <span class="text-3xl font-black text-white select-none drop-shadow-md">
                                            #{{ $index + 1 }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            --}}
                            @include('frontend.partials.video_card', ['video' => $video, 'isTrending' => true])
                        </div>
                    @endforeach
                </div>
                <div class="pt-8 md:pt-12 text-center">
                    <a href="{{ route('trending') }}" class="group inline-flex items-center gap-2 md:gap-3 px-8 md:px-12 py-3.5 md:py-5 bg-[#ff571a] rounded-full text-xs md:text-sm font-black uppercase tracking-widest text-white hover:bg-orange-600 transition-all shadow-xl shadow-[#ff571a]/20">
                        View All Trending
                        <span class="material-symbols-rounded text-lg md:text-xl group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
            @else
                <div class="py-20 flex flex-col items-center justify-center text-center bg-gray-50 dark:bg-white/[0.03] rounded-3xl border-2 border-dashed border-gray-200 dark:border-white/10 p-8">
                    <div class="w-20 h-20 bg-[#ff571a]/10 text-[#ff571a] rounded-full flex items-center justify-center mb-6">
                        <span class="material-symbols-rounded text-4xl">video_library</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">No trending videos today</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xs mx-auto mt-2">Check back later to see what's hot in the ecosystem.</p>
                </div>
            @endif
        </div>

        <!-- Featured Profiles Content -->
        <div x-show="activeTab === 'creators'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8 md:space-y-12 overflow-hidden pb-12">
            
            {{-- Category: Channels --}}
            @if(isset($featuredChannels) && $featuredChannels->count() > 0)
            <div x-data="{ 
                speed: 0.8,
                paused: false,
                isDown: false,
                dragged: false,
                startX: 0,
                scrollLeft: 0,
                init() {
                    const el = this.$refs.slider;
                    const loop = () => {
                        if (!this.paused && !this.isDown) {
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
                <div class="flex items-center gap-2 md:gap-3 mb-4 md:mb-6 px-4 md:px-0">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg md:rounded-xl bg-[#ff571a]/10 flex items-center justify-center text-[#ff571a]">
                        <span class="material-symbols-rounded text-lg md:text-xl">stars</span>
                    </div>
                    <h3 class="text-base md:text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Featured Channels</h3>
                </div>
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
                         class="flex gap-4 md:gap-6 overflow-x-auto no-scrollbar py-2 md:py-4 cursor-grab active:cursor-grabbing select-none">
                        @foreach($featuredChannels->concat($featuredChannels) as $channel)
                            @include('frontend.partials.channel_card_small', ['channel' => $channel])
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Category: Actors --}}
            @if(isset($featuredActors) && $featuredActors->count() > 0)
            <div x-data="{ 
                speed: 0.6,
                paused: false,
                isDown: false,
                dragged: false,
                startX: 0,
                scrollLeft: 0,
                init() {
                    const el = this.$refs.slider;
                    const loop = () => {
                        if (!this.paused && !this.isDown) {
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
                <div class="flex items-center gap-2 md:gap-3 mb-4 md:mb-6 px-4 md:px-0">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg md:rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                        <span class="material-symbols-rounded text-lg md:text-xl">theater_comedy</span>
                    </div>
                    <h3 class="text-base md:text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Top Actors</h3>
                </div>
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
                         class="flex gap-4 md:gap-6 overflow-x-auto no-scrollbar py-2 md:py-4 cursor-grab active:cursor-grabbing select-none">
                        @foreach($featuredActors->concat($featuredActors) as $creator)
                            @include('frontend.partials.creator_card_small', ['creator' => $creator])
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Category: Influencers --}}
            @if(isset($featuredInfluencers) && $featuredInfluencers->count() > 0)
            <div x-data="{ 
                speed: -0.6,
                paused: false,
                isDown: false,
                dragged: false,
                startX: 0,
                scrollLeft: 0,
                init() {
                    const el = this.$refs.slider;
                    el.scrollLeft = el.scrollWidth / 2;
                    const loop = () => {
                        if (!this.paused && !this.isDown) {
                            el.scrollLeft += this.speed;
                            if (el.scrollLeft <= 0) {
                                el.scrollLeft = el.scrollWidth / 2;
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
                <div class="flex items-center gap-2 md:gap-3 mb-4 md:mb-6 px-4 md:px-0">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg md:rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                        <span class="material-symbols-rounded text-lg md:text-xl">campaign</span>
                    </div>
                    <h3 class="text-base md:text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Top Influencers</h3>
                </div>
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
                         class="flex gap-4 md:gap-6 overflow-x-auto no-scrollbar py-2 md:py-4 cursor-grab active:cursor-grabbing select-none">
                        @foreach($featuredInfluencers->concat($featuredInfluencers) as $creator)
                            @include('frontend.partials.creator_card_small', ['creator' => $creator])
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Category: Investors --}}
            @if(isset($featuredInvestors) && $featuredInvestors->count() > 0)
            <div x-data="{ 
                speed: 0.6,
                paused: false,
                isDown: false,
                dragged: false,
                startX: 0,
                scrollLeft: 0,
                init() {
                    const el = this.$refs.slider;
                    const loop = () => {
                        if (!this.paused && !this.isDown) {
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
                <div class="flex items-center gap-2 md:gap-3 mb-4 md:mb-6 px-4 md:px-0">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg md:rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                        <span class="material-symbols-rounded text-lg md:text-xl">payments</span>
                    </div>
                    <h3 class="text-base md:text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Premium Investors</h3>
                </div>
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
                         class="flex gap-4 md:gap-6 overflow-x-auto no-scrollbar py-2 md:py-4 cursor-grab active:cursor-grabbing select-none">
                        @foreach($featuredInvestors->concat($featuredInvestors) as $creator)
                            @include('frontend.partials.creator_card_small', ['creator' => $creator])
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <div class="pt-8 md:pt-12 text-center">
                <a href="{{ route('marketplace.index') }}" class="group inline-flex items-center gap-2 md:gap-3 px-8 md:px-12 py-3.5 md:py-5 bg-orange-500 rounded-full text-xs md:text-sm font-black uppercase tracking-widest text-white hover:bg-orange-600 transition-all shadow-xl shadow-orange-500/20">
                    Explore Profile Marketplace
                    <span class="material-symbols-rounded text-lg md:text-xl group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
            </div>
        </div>

        <!-- Featured Videos Content -->
        <div x-show="activeTab === 'featured'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            @if(isset($featuredVideos) && $featuredVideos->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($featuredVideos as $video)
                        <div class="relative group">
                            <!-- Featured Badge Overlay -->
                            <div class="absolute top-3 right-3 z-30">
                                <div class="px-3 py-1 bg-[#ff571a] text-white text-[10px] font-black uppercase tracking-widest rounded-lg shadow-lg shadow-[#ff571a]/30 flex items-center gap-1.5 border border-white/20">
                                    <span class="material-symbols-rounded text-sm fill-1">star</span>
                                    Featured
                                </div>
                            </div>
                            @include('frontend.partials.video_card', ['video' => $video])
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-20 flex flex-col items-center justify-center text-center bg-gray-50 dark:bg-white/[0.03] rounded-3xl border-2 border-dashed border-gray-200 dark:border-white/10 p-8">
                    <div class="w-20 h-20 bg-[#ff571a]/10 text-[#ff571a] rounded-full flex items-center justify-center mb-6">
                        <span class="material-symbols-rounded text-4xl">grade</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">No featured videos yet</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xs mx-auto mt-2">Curated content from our top creators will appear here soon.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Banner Slot 2 -->
    @if(isset($slot2Banners) && $slot2Banners->count() > 0)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        @include('frontend.partials.banner_carousel', ['banners' => $slot2Banners])
    </div>
    @endif

    <!-- ═══ REELS SECTION (Native App Inspired) ═══ -->
    @if(isset($reels) && $reels->count() > 0)
    <div class="relative overflow-hidden pt-16 pb-6 bg-gradient-to-b from-transparent via-gray-50/50 to-transparent dark:via-white/[0.02]">
        <!-- Section Header -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 md:mb-12">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 md:gap-4">
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-tr from-rose-500 via-[#ff571a] to-amber-500 rounded-xl md:rounded-2xl flex items-center justify-center text-white shadow-xl shadow-[#ff571a]/25 animate-pulse">
                        <span class="material-symbols-rounded text-xl md:text-2xl">slow_motion_video</span>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">Reels</h2>
                        <p class="text-[9px] md:text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mt-0.5">Short • Trending • Viral</p>
                    </div>
                </div>
                <div class="flex items-center justify-between md:justify-end gap-3 w-full md:w-auto">
                    <div class="flex items-center gap-2">
                        <button onclick="document.getElementById('reels-carousel').scrollBy({left: -200, behavior: 'smooth'})" 
                                class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-white dark:bg-[#272727] flex items-center justify-center text-gray-900 dark:text-white transition-all border border-gray-200 dark:border-white/10 active:scale-90 shadow-xl hover:bg-gray-50 dark:hover:bg-[#3f3f3f] group">
                            <span class="material-symbols-rounded text-xl md:text-2xl group-hover:-translate-x-1 transition-transform">chevron_left</span>
                        </button>
                        <button onclick="document.getElementById('reels-carousel').scrollBy({left: 200, behavior: 'smooth'})" 
                                class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-white dark:bg-[#272727] flex items-center justify-center text-gray-900 dark:text-white transition-all border border-gray-200 dark:border-white/10 active:scale-90 shadow-xl hover:bg-gray-50 dark:hover:bg-[#3f3f3f] group">
                            <span class="material-symbols-rounded text-xl md:text-2xl group-hover:translate-x-1 transition-transform">chevron_right</span>
                        </button>
                    </div>
                    <a href="{{ route('reels.index') }}" class="group flex items-center gap-2 px-5 md:px-6 py-2.5 md:py-3 bg-gray-900 dark:bg-white/10 text-white rounded-xl md:rounded-2xl text-[10px] md:text-[11px] font-black uppercase tracking-widest shadow-lg hover:shadow-xl transition-all border border-transparent dark:border-white/10 whitespace-nowrap">
                        View All
                        <span class="material-symbols-rounded text-sm md:text-base group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Reels Carousel -->
        <div class="relative">
            <!-- Gradient Fade Edges -->
            <div class="absolute left-0 top-0 bottom-0 w-16 bg-gradient-to-r from-white dark:from-[#0F0F0F] to-transparent z-10 pointer-events-none hidden lg:block"></div>
            <div class="absolute right-0 top-0 bottom-0 w-16 bg-gradient-to-l from-white dark:from-[#0F0F0F] to-transparent z-10 pointer-events-none hidden lg:block"></div>

            <div class="flex gap-4 md:gap-5 overflow-x-auto orange-scrollbar px-4 sm:px-6 lg:px-8 pb-8 scroll-smooth" id="reels-carousel">
                @foreach($reels->take(5) as $index => $reel)
                <a data-reel-id="{{ $reel->id }}" href="{{ route('reels.index', ['reel' => $reel->slug]) }}" 
                   class="group relative flex-shrink-0 w-[180px] sm:w-[200px] md:w-[220px] aspect-[9/16] rounded-[1.8rem] overflow-hidden shadow-xl shadow-black/10 dark:shadow-black/40 hover:shadow-2xl hover:shadow-[#ff571a]/20 transition-all duration-500 hover:scale-[1.03] cursor-pointer border-2 border-white/10 dark:border-white/5"
                   @mouseenter="$el.querySelector('video')?.play()" 
                   @mouseleave="$el.querySelector('video')?.pause(); $el.querySelector('video').currentTime = 0">
                    
                    <!-- Video Background -->
                    <video src="{{ $reel->getVideoUrl() }}" 
                           class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 z-0" 
                           muted loop playsinline preload="metadata"></video>

                    <!-- Static Thumbnail/Preview Fallback -->
                    <img src="{{ $reel->getThumbnailUrl() }}" 
                         class="absolute inset-0 w-full h-full object-cover z-10 transition-opacity duration-500 group-hover:opacity-0"
                         onerror="this.src='{{ $reel->getPreviewUrl() }}'; this.onerror=null;">

                    <!-- Multi-layer Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-black/30 group-hover:from-black/70 transition-all duration-500" style="background: linear-gradient(to top, rgba(0, 0, 0, 0.95) 0%, rgba(0, 0, 0, 0.5) 40%, rgba(0, 0, 0, 0) 70%, rgba(0, 0, 0, 0.3) 100%);"></div>
                    
                    <!-- Shimmer Effect on Hover -->
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none" style="background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,0.08) 45%, transparent 50%); background-size: 200% 100%; animation: shimmer 2s infinite;">
                    </div>

                    <!-- Top Badge -->
                    {!! $reel->getBadgeHtml() !!}

                    <!-- Play Button Center (on hover) -->
                    <div class="absolute inset-0 flex items-center justify-center z-10 opacity-0 group-hover:opacity-100 transition-all duration-300 pointer-events-none">
                        <div class="w-14 h-14 rounded-full bg-white/20 backdrop-blur-xl flex items-center justify-center border border-white/30 shadow-2xl scale-75 group-hover:scale-100 transition-transform duration-500">
                            <span class="material-symbols-rounded text-white text-3xl ml-0.5">play_arrow</span>
                        </div>
                    </div>

                    {{-- Age Restriction / Reported Gate --}}
                    @if($reel->isRestricted())
                        <div x-data="{ acknowledged: false }"
                             x-show="!acknowledged"
                             class="absolute inset-0 z-[45] bg-[#0F0F0F]/80 backdrop-blur-[30px] flex flex-col items-center justify-center p-4 text-center">
                            <div class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mb-4 shadow-xl">
                                <span class="material-symbols-rounded text-3xl text-white opacity-90">shield_lock</span>
                            </div>
                            <h4 class="text-white text-[10px] font-black uppercase tracking-widest mb-1 leading-none">Restricted</h4>
                            
                            @if(!auth()->check())
                                <p class="text-white/50 text-[8px] font-bold uppercase tracking-tighter mb-4">Sign in to Verify</p>
                                <div class="px-4 py-2 bg-rose-600 text-white rounded-xl font-black text-[9px] uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-lg shadow-rose-600/20">
                                    Sign In
                                </div>
                            @else
                                <p class="text-white/50 text-[8px] font-bold uppercase tracking-tighter mb-4">Acknowledge to View</p>
                                <button @click.prevent.stop="acknowledged = true" class="px-4 py-2 bg-gradient-to-tr from-rose-600 to-orange-500 text-white rounded-xl font-black text-[9px] uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-lg shadow-rose-600/20">
                                    I Understand
                                </button>
                            @endif
                        </div>
                    @endif

                    <!-- Bottom Content -->
                    <div class="absolute bottom-0 left-0 right-0 z-20 p-4 pb-5" style="background: linear-gradient(to top, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.4) 65%, transparent 100%);">
                        <!-- User Info -->
                        <div class="flex items-center gap-2.5 mb-3">
                            <div class="w-8 h-8 rounded-full border-2 border-white/40 overflow-hidden shadow-lg flex-shrink-0 bg-gray-800">
                                @if($reel->user->image)
                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $reel->user->image) }}" class="w-full h-full object-cover">
                                @elseif($reel->user->channel && $reel->user->channel->avatar)
                                    <img src="{{ getImage(getFilePath('channelAvatar') . '/' . $reel->user->channel->avatar) }}" class="w-full h-full object-cover">

                                @else
                                    <div class="w-full h-full bg-gradient-to-tr from-[#ff571a] to-rose-500 flex items-center justify-center text-white text-[10px] font-black">
                                        {{ strtoupper(substr($reel->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <span class="text-white text-[11px] font-black truncate drop-shadow-lg">{{ $reel->user->channel->name ?? $reel->user->name }}</span>
                        </div>

                        <!-- Title -->
                        <h3 class="text-white text-[13px] font-bold leading-snug line-clamp-2 drop-shadow-lg mb-3">{{ $reel->title }}</h3>

                        <!-- Engagement Stats -->
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-1 text-white/80">
                                <span class="material-symbols-rounded text-[14px]">favorite</span>
                                <span class="text-[10px] font-black">{{ $reel->likes_count > 999 ? number_format($reel->likes_count / 1000, 1) . 'K' : $reel->likes_count }}</span>
                            </div>
                            <div class="flex items-center gap-1 text-white/80">
                                <span class="material-symbols-rounded text-[14px]">visibility</span>
                                <span class="text-[10px] font-black">{{ $reel->views_count > 999 ? number_format($reel->views_count / 1000, 1) . 'K' : $reel->views_count }}</span>
                            </div>
                            <div class="flex items-center gap-1 text-white/80">
                                <span class="material-symbols-rounded text-[14px]">chat_bubble</span>
                                <span class="text-[10px] font-black">{{ $reel->comments_count }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="absolute top-4 right-3 z-30">
                        <button onclick="event.preventDefault(); event.stopPropagation(); window.openVideoOptions('{{ $reel->id }}', '{{ addslashes($reel->title) }}', 'reel', {{ $reel->isLikedBy(auth()->user()) ? 'true' : 'false' }}, {{ $reel->isInWatchLater(auth()->user()) ? 'true' : 'false' }}, {{ $reel->isInAnyPlaylist(auth()->user()) ? 'true' : 'false' }}, @json($reel->getPlaylistMembershipIds(auth()->user() ?? null)), {{ $reel->user_id }})" 
                                class="w-8 h-8 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center border border-white/10 text-white hover:bg-orange-500 transition-all shadow-lg">
                            <span class="material-symbols-rounded text-[18px]">more_vert</span>
                        </button>
                    </div>
                </a>
                @endforeach

                <!-- "See All" Card -->
                <a href="{{ route('reels.index') }}" class="group relative flex-shrink-0 w-[180px] sm:w-[200px] md:w-[220px] aspect-[9/16] rounded-[1.8rem] overflow-hidden border-2 border-dashed border-gray-200 dark:border-white/10 hover:border-[#ff571a] dark:hover:border-[#ff571a] transition-all duration-500 flex items-center justify-center bg-gray-50 dark:bg-white/[0.02] hover:bg-[#ff571a]/5 dark:hover:bg-[#ff571a]/5">
                    <div class="flex flex-col items-center gap-4 text-gray-400 dark:text-gray-600 group-hover:text-[#ff571a] transition-colors duration-300">
                        <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-white/5 group-hover:bg-[#ff571a]/10 flex items-center justify-center transition-all duration-300 group-hover:scale-110">
                            <span class="material-symbols-rounded text-3xl">arrow_forward</span>
                        </div>
                        <div class="text-center">
                            <span class="text-xs font-black uppercase tracking-widest block">See All</span>
                            <span class="text-[9px] font-bold uppercase tracking-[0.15em] opacity-60 mt-1 block">{{ $reels->count() }}+ Reels</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <style>
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(calc(-50% - 12px)); }
        }
        @keyframes marquee-reverse {
            0% { transform: translateX(calc(-50% - 12px)); }
            100% { transform: translateX(0); }
        }
        .animate-marquee {
            animation: marquee 40s linear infinite;
        }
        .animate-marquee-reverse {
            animation: marquee-reverse 40s linear infinite;
        }
        .group\/marquee:hover .animate-marquee,
        .group\/marquee:hover .animate-marquee-reverse {
            animation-play-state: paused;
        }

        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        .category-scrollbar {
            -ms-overflow-style: none !important;
            scrollbar-width: none !important;
        }
        .category-scrollbar::-webkit-scrollbar {
            display: none !important;
        }
    </style>
    @endif

    <!-- Category Scroller & Main Feed -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-2 pb-12">
        <div class="relative mb-8 group py-2">
            <!-- Category Container -->
            <div class="flex items-center gap-3 overflow-x-auto pb-4 category-scrollbar select-none cursor-grab active:cursor-grabbing" id="home-category-container">
                <a href="{{ route('home') }}" draggable="false" {!! request()->routeIs('home') ? 'id="home-active-category"' : '' !!} class="px-6 py-2.5 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all shrink-0 {{ request()->routeIs('home') ? 'bg-[#ff571a] text-white shadow-lg shadow-[#ff571a]/20' : 'bg-gray-100 dark:bg-white/5 text-gray-500 hover:text-gray-900 dark:hover:text-white' }}">All</a>
                @if(isset($categories))
                    @foreach($categories as $category)
                        <a href="{{ route('category.show', $category->slug) }}" draggable="false" {!! strtolower(request()->path()) === 'category/' . strtolower($category->slug) ? 'id="home-active-category"' : '' !!} data-cat-link data-cat-id="{{ $category->id }}" data-cat-slug="{{ $category->slug }}" class="px-6 py-2.5 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all shrink-0 {{ strtolower(request()->path()) === 'category/' . strtolower($category->slug) ? 'bg-[#ff571a] text-white shadow-lg shadow-[#ff571a]/20' : 'bg-gray-100 dark:bg-white/5 text-gray-500 hover:text-gray-900 dark:hover:text-white' }}">{{ $category->name }}</a>
                    @endforeach
                @endif
            </div>
            <!-- Scrollbar Track & Thumb -->
            <div id="home-category-scrollbar-track" class="w-full h-1 bg-transparent dark:bg-transparent rounded-full relative cursor-pointer hover:h-1.5 transition-all mt-3">
                <div id="home-category-scrollbar-thumb" class="absolute top-0 bottom-0 h-full bg-[#ff571a] rounded-full shadow-sm shadow-[#ff571a]/40 cursor-grab active:cursor-grabbing hover:bg-orange-600 transition-colors" style="width: 20%; transform: translateX(0px);"></div>
            </div>
            <script>
                (function() {
                    const container = document.getElementById('home-category-container');
                    const track = document.getElementById('home-category-scrollbar-track');
                    const thumb = document.getElementById('home-category-scrollbar-thumb');
                    const activeItem = document.getElementById('home-active-category');

                    if (!container) return;

                    function getThumbWidth(trackWidth, clientWidth, scrollWidth) {
                        const ratio = clientWidth / scrollWidth;
                        return Math.max(45, Math.round(trackWidth * ratio * 0.65));
                    }

                    function updateScrollbar() {
                        if (!track || !thumb) return;
                        const scrollWidth = container.scrollWidth;
                        const clientWidth = container.clientWidth;
                        const maxScroll = scrollWidth - clientWidth;
                        const trackWidth = track.clientWidth;

                        if (maxScroll <= 0) {
                            track.style.opacity = '0';
                            track.style.pointerEvents = 'none';
                            return;
                        } else {
                            track.style.opacity = '1';
                            track.style.pointerEvents = 'auto';
                        }

                        const thumbWidth = getThumbWidth(trackWidth, clientWidth, scrollWidth);
                        const maxThumbLeft = trackWidth - thumbWidth;
                        const scrollRatio = container.scrollLeft / maxScroll;
                        const thumbLeft = Math.max(0, Math.min(maxThumbLeft, scrollRatio * maxThumbLeft));

                        thumb.style.width = thumbWidth + 'px';
                        thumb.style.transform = `translateX(${thumbLeft}px)`;
                    }

                    // Initial position centering active category
                    if (activeItem) {
                        const scrollPos = activeItem.offsetLeft - (container.clientWidth / 2) + (activeItem.clientWidth / 2);
                        container.scrollLeft = Math.max(0, scrollPos);
                    }
                    updateScrollbar();

                    // Listen to scroll events
                    container.addEventListener('scroll', updateScrollbar, { passive: true });

                    // Drag container functionality (nav buttons)
                    let isContainerDragging = false;
                    let containerStartX = 0;
                    let containerStartScroll = 0;
                    let hasDraggedMoved = false;

                    container.addEventListener('mousedown', (e) => {
                        isContainerDragging = true;
                        hasDraggedMoved = false;
                        containerStartX = e.pageX - container.offsetLeft;
                        containerStartScroll = container.scrollLeft;
                    });

                    window.addEventListener('mousemove', (e) => {
                        if (!isContainerDragging) return;
                        const x = e.pageX - container.offsetLeft;
                        const walk = (x - containerStartX) * 1.5;
                        if (Math.abs(walk) > 4) {
                            hasDraggedMoved = true;
                        }
                        container.scrollLeft = containerStartScroll - walk;
                    });

                    window.addEventListener('mouseup', () => {
                        isContainerDragging = false;
                    });

                    // Prevent click navigating when dragging
                    container.querySelectorAll('a').forEach(link => {
                        link.addEventListener('click', (e) => {
                            if (hasDraggedMoved) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        });
                    });

                    // Wheel scrolling
                    container.addEventListener('wheel', (e) => {
                        if (e.deltaY !== 0) {
                            e.preventDefault();
                            container.scrollLeft += e.deltaY;
                        }
                    }, { passive: false });

                    // Drag scrollbar thumb functionality
                    if (thumb && track) {
                        let isThumbDragging = false;
                        let thumbStartX = 0;
                        let thumbStartScroll = 0;

                        thumb.addEventListener('mousedown', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            isThumbDragging = true;
                            thumbStartX = e.clientX;
                            thumbStartScroll = container.scrollLeft;
                            document.body.style.userSelect = 'none';
                        });

                        thumb.addEventListener('touchstart', (e) => {
                            isThumbDragging = true;
                            thumbStartX = e.touches[0].clientX;
                            thumbStartScroll = container.scrollLeft;
                        }, { passive: true });

                        window.addEventListener('mousemove', (e) => {
                            if (!isThumbDragging) return;
                            const trackWidth = track.clientWidth;
                            const scrollWidth = container.scrollWidth;
                            const clientWidth = container.clientWidth;
                            const maxScroll = scrollWidth - clientWidth;
                            if (maxScroll <= 0) return;

                            const thumbWidth = getThumbWidth(trackWidth, clientWidth, scrollWidth);
                            const maxThumbLeft = trackWidth - thumbWidth;

                            const deltaX = e.clientX - thumbStartX;
                            const scrollDelta = (deltaX / maxThumbLeft) * maxScroll;
                            container.scrollLeft = thumbStartScroll + scrollDelta;
                        });

                        window.addEventListener('touchmove', (e) => {
                            if (!isThumbDragging) return;
                            const trackWidth = track.clientWidth;
                            const scrollWidth = container.scrollWidth;
                            const clientWidth = container.clientWidth;
                            const maxScroll = scrollWidth - clientWidth;
                            if (maxScroll <= 0) return;

                            const thumbWidth = getThumbWidth(trackWidth, clientWidth, scrollWidth);
                            const maxThumbLeft = trackWidth - thumbWidth;

                            const deltaX = e.touches[0].clientX - thumbStartX;
                            const scrollDelta = (deltaX / maxThumbLeft) * maxScroll;
                            container.scrollLeft = thumbStartScroll + scrollDelta;
                        }, { passive: true });

                        window.addEventListener('mouseup', () => {
                            if (isThumbDragging) {
                                isThumbDragging = false;
                                document.body.style.userSelect = '';
                            }
                        });

                        window.addEventListener('touchend', () => {
                            isThumbDragging = false;
                        });

                        // Track click to jump scroll position
                        track.addEventListener('click', (e) => {
                            if (e.target === thumb || isThumbDragging) return;
                            const rect = track.getBoundingClientRect();
                            const clickX = e.clientX - rect.left;
                            const trackWidth = track.clientWidth;
                            const scrollWidth = container.scrollWidth;
                            const clientWidth = container.clientWidth;
                            const maxScroll = scrollWidth - clientWidth;
                            if (maxScroll <= 0) return;

                            const thumbWidth = getThumbWidth(trackWidth, clientWidth, scrollWidth);
                            const maxThumbLeft = trackWidth - thumbWidth;

                            const targetThumbLeft = clickX - (thumbWidth / 2);
                            const scrollRatio = Math.max(0, Math.min(1, targetThumbLeft / maxThumbLeft));
                            container.scrollTo({
                                left: scrollRatio * maxScroll,
                                behavior: 'smooth'
                            });
                        });
                    }

                    window.addEventListener('resize', updateScrollbar);
                    window.addEventListener('load', updateScrollbar);
                    setTimeout(updateScrollbar, 200);
                })();
            </script>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" id="video-grid">
            @forelse($videos as $index => $video)
                @if($index == 8 && (isset($slot3Banners) || isset($slot4Banners)))
                    @if((isset($slot3Banners) && $slot3Banners->count() > 0) || (isset($slot4Banners) && $slot4Banners->count() > 0))
                        <div class="col-span-full grid grid-cols-1 md:grid-cols-2 gap-6 my-6">
                            @if(isset($slot3Banners) && $slot3Banners->count() > 0)
                            <div class="w-full">
                                @include('frontend.partials.banner_carousel', ['banners' => $slot3Banners])
                            </div>
                            @endif
                            @if(isset($slot4Banners) && $slot4Banners->count() > 0)
                            <div class="w-full">
                                @include('frontend.partials.banner_carousel', ['banners' => $slot4Banners])
                            </div>
                            @endif
                        </div>
                    @endif
                @endif
                @include('frontend.partials.video_card', ['video' => $video])
            @empty
                <div class="col-span-full py-20 flex flex-col items-center justify-center text-center bg-gray-50 dark:bg-white/[0.03] rounded-3xl border-2 border-dashed border-gray-200 dark:border-white/10 p-8">
                    <div class="w-20 h-20 bg-[#ff571a]/10 text-[#ff571a] rounded-full flex items-center justify-center mb-6">
                        <span class="material-symbols-rounded text-4xl">movie</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Nothing to see here yet</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Be the first to upload amazing content!</p>
                </div>
            @endforelse
        </div>

        @if($videos->hasPages())
        <div id="infinite-loader" class="infinite-loader">
            <div class="loader-spinner">
                <div class="spinner-ring"></div>
            </div>
            <span class="loader-text">Loading more videos...</span>
        </div>
        <div id="scroll-sentinel"></div>
        @endif
    </div>

    @push('style')
    <style>
        .infinite-loader {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
            gap: 16px;
        }
        .infinite-loader.active {
            display: flex;
        }
        .infinite-loader.loaded {
            display: none !important;
        }
        .loader-spinner {
            width: 40px;
            height: 40px;
            position: relative;
        }
        .spinner-ring {
            width: 40px;
            height: 40px;
            border: 3px solid #e5e7eb;
            border-top-color: #ff571a;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .loader-text {
            font-size: 11px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.2em;
        }
        .dark .spinner-ring {
            border-color: #374151;
            border-top-color: #ff571a;
        }
        #scroll-sentinel {
            height: 1px;
        }
    </style>
    @endpush

    @push('script')
    <script>
        (function() {
            'use strict';
            let currentPage = {{ $videos->currentPage() }};
            let lastPage = {{ $videos->lastPage() }};
            const grid = document.getElementById('video-grid');
            const sentinel = document.getElementById('scroll-sentinel');
            const loader = document.getElementById('infinite-loader');
            let loading = false;
            let hasMore = currentPage < lastPage;

            if (!hasMore && loader) {
                loader.classList.add('loaded');
            }

            if (sentinel && hasMore) {
                const observer = new IntersectionObserver(function(entries) {
                    if (entries[0].isIntersecting && !loading && hasMore) {
                        loading = true;
                        if (loader) loader.classList.add('active');

                        currentPage++;

                        fetch("{{ route('video.get') }}?page=" + currentPage)
                            .then(res => res.json())
                            .then(res => {
                                if (res.status === 'success') {
                                    grid.insertAdjacentHTML('beforeend', res.html);
                                    hasMore = res.has_more;
                                    if (!hasMore && loader) {
                                        loader.classList.add('loaded');
                                        observer.unobserve(sentinel);
                                    }
                                }
                            })
                            .finally(() => {
                                loading = false;
                                if (loader) loader.classList.remove('active');
                            });
                    }
                }, { rootMargin: '200px' });

                observer.observe(sentinel);
            }
        })();
    </script>
    @endpush
</x-app-layout>

