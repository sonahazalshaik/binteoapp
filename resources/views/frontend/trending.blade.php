<x-app-layout>
    <div id="trending-page" class="py-12 bg-white dark:bg-[#0F0F0F] min-h-screen">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-12">
                <div class="flex flex-col md:flex-row md:items-center gap-6">
                    <div class="w-16 h-16 rounded-[2rem] bg-gradient-to-tr from-[#FF4B2B] to-[#FF416C] flex items-center justify-center text-white shadow-2xl shadow-red-500/20 animate-pulse">
                        <span class="material-symbols-rounded text-3xl fill-1">trending_up</span>
                    </div>
                    <div>
                        <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight uppercase ">Trending Now</h1>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                            <p class="text-[11px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Live Updates • Top {{ $videos->total() }} Videos</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Videos Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-8">
                @forelse($videos as $key => $video)
                <div class="premium-card group bg-transparent transition-all duration-300" data-video-id="{{ $video->id }}" 
                     @php
                        $isOptimizing = ($video->isBunnyVideo() && !in_array($video->bunny_status, ['ready', 'error', 'failed'])) || (!$video->isBunnyVideo() && $video->status === 'processing');
                        $hasAccess = true;
                        if ($video->is_premium) {
                            $hasAccess = false;
                            if (auth()->check()) {
                                if (auth()->id() == $video->user_id || auth()->user()->isPurchased($video->id) || auth()->user()->hasPremiumAccess()) {
                                    $hasAccess = true;
                                }
                            }
                        }
                     @endphp
                     x-data="{ 
                        hover: false, 
                        touchTimer: null,
                        progress: 0,
                        currentTime: 0,
                        durationSeconds: 0,
                        orig: @js($video->formatted_duration),
                        isOptimizing: {{ $isOptimizing ? 'true' : 'false' }},
                        init() {
                            if (this.isOptimizing) {
                                this.pollStatus();
                            }
                            if (this.orig && this.orig !== '00:00' && this.orig !== '0:00' && this.orig !== '' ) return;
                            this.$nextTick(() => {
                                const v = this.$el.querySelector('video') || this.$refs.probe;
                                if (!v) return;
                                const set = () => { if (v.duration && isFinite(v.duration) && v.duration !== Infinity) this.durationSeconds = v.duration; };
                                v.addEventListener('loadedmetadata', set, {once:true});
                                v.addEventListener('durationchange', set, {once:true});
                                try { v.load(); } catch(e) {}
                                if (v.readyState >= 1 && v.duration) set();
                                v.addEventListener('loadedmetadata', () => {
                                    if (v.duration === Infinity) {
                                        try { v.currentTime = Number.MAX_SAFE_INTEGER; v.ontimeupdate = null; v.addEventListener('seeked', () => { if(isFinite(v.duration)) this.durationSeconds = v.duration; try{ v.currentTime=0;}catch(e){} }, {once:true}); } catch(e){}
                                    }
                                }, {once:true});
                            });
                        },
                        playVideo() {
                            this.hover = true;
                            this.$nextTick(() => { 
                                const v = this.$el.querySelector('video');
                                if(v) {
                                    v.play().catch(() => {});
                                    v.ontimeupdate = () => {
                                        this.currentTime = v.currentTime;
                                        this.durationSeconds = v.duration || 0;
                                    };
                                }
                            });
                        },
                        stopVideo() {
                            this.hover = false;
                            const v = this.$el.querySelector('video');
                            if(v) {
                                v.pause();
                                v.currentTime = 0;
                                v.ontimeupdate = null;
                                this.currentTime = 0;
                            }
                        },
                        get displayDuration() {
                            if (this.orig && this.orig !== '00:00' && this.orig !== '0:00' && this.orig !== '') return this.orig;
                            if (this.durationSeconds > 0) {
                                const h = Math.floor(this.durationSeconds/3600);
                                const m = Math.floor((this.durationSeconds%3600)/60);
                                const s = Math.floor(this.durationSeconds%60);
                                if(h>0) return h+':'+String(m).padStart(2,'0')+':'+String(s).padStart(2,'0');
                                return String(m).padStart(2,'0')+':'+String(s).padStart(2,'0');
                            }
                            return (this.orig && this.orig !== '00:00' && this.orig !== '0:00') ? this.orig : '--:--';
                        },
                        async pollStatus() {
                            try {
                                const response = await fetch('{{ route("videos.check_status", $video->slug) }}');
                                const data = await response.json();
                                if (data.success) {
                                    this.progress = data.encode_progress || 0;
                                    if (data.encode_progress >= 100) {
                                        window.location.reload();
                                    } else {
                                        setTimeout(() => this.pollStatus(), 5000);
                                    }
                                }
                            } catch (e) {
                                setTimeout(() => this.pollStatus(), 10000);
                            }
                        }
                     }" 
                     @mouseenter="playVideo()" 
                     @mouseleave="stopVideo()"
                     @touchstart="touchTimer = setTimeout(() => playVideo(), 500)"
                     @touchend="clearTimeout(touchTimer); stopVideo()"
                     @touchmove="clearTimeout(touchTimer); stopVideo()">
                    <a href="{{ route('videos.show', $video) }}" class="block">
                        <!-- Thumbnail Container -->
                        <div class="aspect-video relative rounded-[1.25rem] overflow-hidden bg-slate-200 dark:bg-white/5 border border-gray-100 dark:border-white/5 shadow-sm group-hover:shadow-md transition-shadow">
                            
                            <!-- Rank Indicator Overlay (Commented out as requested) -->
                            {{-- 
                            <div class="absolute top-2 left-2 z-40 pointer-events-none">
                                <div class="relative flex items-center justify-center">
                                    <span class="text-4xl font-black opacity-20 group-hover:opacity-40 transition-all duration-500 text-gradient-orange select-none">
                                        {{ ($videos->currentPage() - 1) * $videos->perPage() + $key + 1 }}
                                    </span>
                                    <div class="absolute inset-0 flex items-center justify-center translate-y-1 translate-x-1">
                                        <span class="text-xl font-black text-white select-none drop-shadow-md">
                                            #{{ ($videos->currentPage() - 1) * $videos->perPage() + $key + 1 }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            --}}

                            <!-- Video Background (Native App Feel) -->
                            @if($video->isBunnyVideo())
                                <img :src="hover ? '{{ $video->getPreviewUrl() }}' : ''" 
                                       class="absolute inset-0 w-full h-full object-cover transition-transform duration-700" 
                                       :class="hover ? 'scale-110' : 'scale-100'"
                                       loading="lazy" />
                            @elseif($video->video_path)
                                <video src="{{ asset(getFilePath('video') . '/' . $video->video_path) }}" 
                                       class="absolute inset-0 w-full h-full object-cover transition-transform duration-700" 
                                       :class="hover ? 'scale-110' : 'scale-100'"
                                       muted loop playsinline preload="metadata"></video>
                            @endif

                            <!-- Static Thumbnail Overlay -->
                            <img src="{{ $video->getThumbnailUrl() }}" 
                                 class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 z-10"
                                 :class="hover ? 'opacity-0' : 'opacity-100'"
                                 onerror="this.src='{{ $video->getPreviewUrl() }}'; this.onerror=null;">

                            <!-- Glassmorphism Play Overlay -->
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500 pointer-events-none z-20">
                                <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 shadow-2xl scale-75 group-hover:scale-100 transition-transform">
                                    <span class="material-symbols-rounded text-white text-3xl">play_arrow</span>
                                </div>
                            </div>

                            <!-- 18+ Badge -->
                            @if($video->is_age_restricted)
                                <div class="absolute top-2.5 left-2.5 z-30 bg-rose-600 text-white text-[9px] font-black px-1.5 py-0.5 rounded shadow-lg flex items-center gap-1 uppercase tracking-tighter border border-white/20">
                                    <span class="material-symbols-rounded text-[11px]">explicit</span>
                                    18+
                                </div>
                            @endif

                            <!-- Premium Badge -->
                            @if($video->is_premium)
                                <div class="absolute top-2.5 right-2.5 z-30 gradient-orange text-white text-[9px] font-black px-1.5 py-0.5 rounded shadow-lg flex items-center gap-1 uppercase tracking-tighter border border-white/20">
                                    <span class="material-symbols-rounded text-[11px] material-symbols-filled">workspace_premium</span>
                                    <span>Premium</span>
                                </div>
                            @endif

                            <!-- Age Restriction / Reported Gate -->
                            @if($video->isRestricted() && !$isOptimizing)
                                <div x-data="{ acknowledged: false }"
                                     x-show="!acknowledged"
                                     class="absolute inset-0 z-[45] bg-[#0F0F0F]/80 backdrop-blur-[30px] flex flex-col items-center justify-center p-4 text-center">
                                    <div class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mb-3 shadow-xl">
                                        <span class="material-symbols-rounded text-3xl text-white opacity-90">shield_lock</span>
                                    </div>
                                    <h4 class="text-white text-[10px] font-black uppercase tracking-widest mb-1 leading-none">Restricted</h4>
                                    
                                    @if(!auth()->check())
                                        <p class="text-white/50 text-[8px] font-bold uppercase tracking-tighter mb-4">Sign in to Verify</p>
                                        <a href="{{ route('login') }}" class="px-4 py-2 bg-rose-600 text-white rounded-xl font-black text-[9px] uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-lg shadow-rose-600/20">
                                            Sign In
                                        </a>
                                    @else
                                        <p class="text-white/50 text-[8px] font-bold uppercase tracking-tighter mb-4">Acknowledge to View</p>
                                        <button @click.prevent.stop="acknowledged = true" class="px-4 py-2 bg-gradient-to-tr from-rose-600 to-orange-500 text-white rounded-xl font-black text-[9px] uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-lg shadow-rose-600/20">
                                            I Understand
                                        </button>
                                    @endif
                                </div>
                            @endif

                            <!-- Premium Access Overlay -->
                            @if($video->is_premium && !$hasAccess && !$isOptimizing && !$video->is_age_restricted)
                                <div class="absolute inset-0 z-40 bg-black/60 backdrop-blur-[4px] flex flex-col items-center justify-center text-center p-4">
                                    <div class="w-12 h-12 rounded-xl gradient-orange text-white flex items-center justify-center mb-3 shadow-xl shadow-orange-500/40">
                                        <span class="material-symbols-rounded text-2xl material-symbols-filled">lock</span>
                                    </div>
                                    <h4 class="text-white font-black text-[10px] uppercase tracking-widest mb-1">Premium Access</h4>
                                    <p class="text-orange-400 font-black text-xs">{{ showAmount($video->price) }}</p>
                                </div>
                            @endif

                            <!-- Optimizing State -->
                            <template x-if="isOptimizing">
                                <div class="absolute inset-0 bg-black/60 backdrop-blur-md flex items-center justify-center z-50 pointer-events-none">
                                    <div class="flex flex-col items-center">
                                        <div class="relative w-16 h-16 mb-4 flex items-center justify-center">
                                            <!-- Circular Progress -->
                                            <svg class="w-full h-full transform -rotate-90">
                                                <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="4" fill="transparent" class="text-white/10" />
                                                <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="4" fill="transparent" 
                                                    class="text-orange-500 transition-all duration-500"
                                                    stroke-dasharray="175.9"
                                                    :stroke-dashoffset="175.9 - (175.9 * progress / 100)" />
                                            </svg>
                                        </div>
                                        <span class="text-[9px] font-black text-white uppercase tracking-[0.2em] animate-pulse">please wait awesome is loading</span>
                                    </div>
                                </div>
                            </template>

                            <!-- Duration Badge (Native Style) -->
                            <div x-text="displayDuration" class="absolute bottom-2 right-2 z-20 px-1.5 py-0.5 bg-black/80 backdrop-blur-md rounded-md text-[10px] font-black text-white tracking-tight shadow-sm">
                                {{ ($video->formatted_duration !== '00:00') ? $video->formatted_duration : '--:--' }}
                            </div>
                            @if(!$video->isBunnyVideo() && !$video->video_path)
                                <video x-ref="probe" src="{{ $video->getVideoUrl() }}" preload="metadata" muted playsinline webkit-playsinline style="display:none"></video>
                            @elseif(!$video->isBunnyVideo() && !str_contains($video->video_path, '.'))
                                <video x-ref="probe" src="{{ $video->getVideoUrl() }}" preload="metadata" muted playsinline webkit-playsinline style="display:none"></video>
                            @endif

                            <!-- Age Restriction Badge -->
                            @if($video->is_age_restricted)
                                <div class="absolute top-2 left-2 z-20 px-1.5 py-0.5 bg-rose-600/90 backdrop-blur-md rounded-md text-[8px] font-black text-white uppercase tracking-tighter shadow-sm border border-rose-500/20">
                                    18+
                                </div>
                            @endif
                            
                            <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </div>
                    </a>

                    <!-- Meta Container -->
                    <div class="mt-4 flex gap-3">
                        <!-- Avatar Section -->
                        <div class="shrink-0 pt-0.5">
                            <a href="{{ $video->user?->channel ? route('channels.show', $video->user->channel) : '#' }}" class="block">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-gray-200 to-gray-300 dark:from-white/10 dark:to-white/5 flex items-center justify-center text-slate-500 overflow-hidden border border-gray-100 dark:border-white/5 transition-transform active:scale-90">
                                    @if($video->user?->channel?->avatar)
                                        <img src="{{ getImage(getFilePath('channelAvatar') . '/' . $video->user->channel->avatar) }}" class="w-full h-full object-cover">
                                    @elseif($video->user?->image)
                                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $video->user->image) }}" class="w-full h-full object-cover">
                                    @else
                                        @php
                                            $name = $video->user?->channel?->name ?? $video->user?->name ?? 'C';
                                            $words = explode(' ', trim($name));
                                            $initials = count($words) >= 2 
                                                ? strtoupper(substr($words[0], 0, 1) . substr($words[count($words)-1], 0, 1))
                                                : strtoupper(substr($name, 0, 2));
                                        @endphp
                                        <span class="text-xs font-black text-gray-600 dark:text-white">{{ $initials }}</span>
                                    @endif
                                </div>
                            </a>
                        </div>

                        <!-- Detail Section -->
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('videos.show', $video) }}">
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white leading-snug line-clamp-2 transition-colors group-hover:text-blue-600" title="{{ $video->title }}">
                                    {{ $video->title }}
                                </h3>
                            </a>
                            <div class="mt-1.5 flex flex-wrap items-center gap-1 text-[11px] font-medium text-slate-500 dark:text-slate-400">
                                <a href="{{ $video->user?->channel ? route('channels.show', $video->user->channel) : '#' }}" class="hover:text-slate-900 dark:hover:text-white transition-colors">{{ $video->user?->channel?->name ?? $video->user?->name ?? 'Creator' }}</a>
                                <span class="text-[10px]">•</span>
                                <span>{{ formatNumber($video->views_count ?? 0) }} views</span>
                                <span class="text-[10px]">•</span>
                                <span>{{ $video->created_at->diffForHumans() }}</span>
                            </div>

                            @if($video->daily_views > 0)
                            <div class="mt-2 flex items-center gap-1.5 px-2 py-1 bg-red-50 dark:bg-red-500/10 rounded-lg border border-red-100 dark:border-red-500/20 w-fit">
                                <span class="material-symbols-rounded text-xs text-red-500 fill-1">local_fire_department</span>
                                <span class="text-[9px] font-black text-red-500 uppercase tracking-widest">{{ number_format($video->daily_views) }} Today</span>
                            </div>
                            @endif
                            
                        </div>

                        <!-- Action Menu -->
                        <div class="shrink-0">
                            <button type="button" @click.prevent.stop="window.openVideoOptions('{{ $video->id }}', '{{ addslashes($video->title) }}', 'video', {{ $video->isLikedBy(auth()->user()) ? 'true' : 'false' }}, {{ $video->isInWatchLater(auth()->user()) ? 'true' : 'false' }}, {{ $video->isInAnyPlaylist(auth()->user()) ? 'true' : 'false' }}, @json($video->getPlaylistMembershipIds(auth()->user() ?? null)), {{ $video->user_id }})" 
                                    class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-white/5 transition-colors active:scale-95">
                                <span class="material-symbols-rounded text-xl">more_vert</span>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-32 flex flex-col items-center justify-center text-center opacity-40 grayscale">
                    <div class="w-24 h-24 rounded-[2.5rem] bg-gray-100 dark:bg-white/5 flex items-center justify-center mb-6">
                        <span class="material-symbols-rounded text-5xl trending-empty-icon">trending_down</span>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase ">No Trending Content</h3>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-2">Check back soon for viral updates</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($videos->hasPages())
            <div class="mt-20">
                {{ $videos->links() }}
            </div>
            @endif
        </div>
    </div>

    <style>
        /* Page-scoped icon visibility fix (this page only).
           The layout globally forces `.material-symbols-rounded` to `color: inherit`,
           which beats Tailwind's text-color utilities and leaves icons dark-on-dark
           in dark mode. */
        html:not(.dark) #trending-page span.material-symbols-rounded.text-red-500 { color: #ef4444 !important; }
        .dark #trending-page span.material-symbols-rounded.text-red-500 { color: #ef4444 !important; }
        .dark #trending-page span.material-symbols-rounded.trending-empty-icon { color: rgba(255,255,255,0.6) !important; }
    </style>
</x-app-layout>

