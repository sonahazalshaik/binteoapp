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
        isOptimizing: {{ $isOptimizing ? 'true' : 'false' }},
        hasAccess: {{ $hasAccess ? 'true' : 'false' }},
        isAgeRestricted: {{ $video->is_age_restricted ? 'true' : 'false' }},
        isRestricted: {{ $video->isRestricted() ? 'true' : 'false' }},
        isScheduled: {{ ($video->scheduled_at && $video->scheduled_at->isFuture()) ? 'true' : 'false' }},
        acknowledged: false,
        init() {
            if (this.isOptimizing) {
                this.pollStatus();
            }
            let orig = '{{ $video->duration ?? '' }}';
            if (!orig || orig === '00:00' || orig === '0:00' || orig === '--:--') {
                this.$nextTick(() => {
                    const v = this.$el.querySelector('video');
                    if (!v) return;
                    const setDuration = () => {
                        if (v.duration && isFinite(v.duration) && v.duration > 0) {
                            this.durationSeconds = v.duration;
                        }
                    };
                    v.addEventListener('loadedmetadata', setDuration, { once: true });
                    v.addEventListener('durationchange', setDuration, { once: true });
                    try { v.load(); } catch(e) {}
                    if (v.readyState >= 1 && v.duration) setDuration();
                });
            }
        },
        playVideo() {
            if (this.isScheduled) return;
            if (!this.hasAccess) return;
            if (this.isRestricted && !this.acknowledged) return;
            this.hover = true;
            this.currentTime = 0;
            
            const v = this.$el.querySelector('video');
            if(v) {
                v.play().catch(() => {});
                const updateTime = () => {
                    if (!this.hover) return;
                    this.currentTime = v.currentTime;
                    if (v.duration && isFinite(v.duration) && v.duration > 0) {
                        this.durationSeconds = v.duration;
                    }
                    requestAnimationFrame(updateTime);
                };
                requestAnimationFrame(updateTime);
            } else {
                // For animated preview image (Bunny Stream WebP/GIF)
                let startTime = Date.now();
                const timer = setInterval(() => {
                    if (!this.hover) {
                        clearInterval(timer);
                        return;
                    }
                    this.currentTime = (Date.now() - startTime) / 1000;
                }, 100);
            }
        },
        stopVideo() {
            this.hover = false;
            const v = this.$el.querySelector('video');
            if(v) {
                v.pause();
                v.currentTime = 0;
            }
            this.currentTime = 0;
        },
        get displayDuration() {
            let orig = '{{ $video->formatted_duration !== "--:--" ? $video->formatted_duration : ($video->duration ?? "") }}';
            let origSecs = 0;
            if (orig && orig !== '00:00' && orig !== '0:00' && orig !== '--:--') {
                let parts = orig.split(':').reverse();
                for (let i = 0; i < parts.length; i++) {
                    origSecs += parseInt(parts[i] || 0) * Math.pow(60, i);
                }
            }
            if (origSecs === 0 && this.durationSeconds > 0) {
                origSecs = this.durationSeconds;
            }

            if (this.hover && this.currentTime > 0 && origSecs > 0) {
                let remaining = Math.max(0, origSecs - this.currentTime);
                let h = Math.floor(remaining / 3600);
                let m = Math.floor((remaining % 3600) / 60);
                let s = Math.floor(remaining % 60);
                
                if (h > 0 || (orig && orig.split(':').length === 3 && parseInt(orig.split(':')[0]) > 0)) {
                    return h + ':' + (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s);
                } else {
                    return (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s);
                }
            }

            if (orig && orig !== '00:00' && orig !== '0:00' && orig !== '' && orig !== '--:--' && orig !== '00:00:00') {
                let parts = orig.split(':');
                if (parts.length === 3) {
                    let h = parseInt(parts[0]);
                    return h > 0 ? (h + ':' + parts[1] + ':' + parts[2]) : (parts[1] + ':' + parts[2]);
                }
                return orig;
            }

            if (this.durationSeconds > 0) {
                let d = this.durationSeconds;
                let h = Math.floor(d / 3600);
                let m = Math.floor((d % 3600) / 60);
                let s = Math.floor(d % 60);
                if (h > 0) {
                    return h + ':' + (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s);
                } else {
                    return (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s);
                }
            }

            return (orig && orig !== '00:00' && orig !== '0:00' && orig !== '00:00:00') ? orig : '--:--';
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
            <!-- Video Background (Native App Feel) -->
            @if($video->isBunnyVideo())
                <img :src="hover ? '{{ $video->getPreviewUrl() }}' : 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs='" 
                       class="absolute inset-0 w-full h-full object-cover transition-transform duration-700" 
                       :class="hover ? 'scale-110' : 'scale-100'" />
            @elseif($video->video_path)
                <video src="{{ asset(getFilePath('video') . '/' . $video->video_path) }}" 
                       class="absolute inset-0 w-full h-full object-cover transition-transform duration-700" 
                       :class="hover ? 'scale-110' : 'scale-100'"
                       muted loop playsinline preload="metadata"></video>
            @endif

            <!-- Static Thumbnail Overlay -->
            <img src="{{ $video->getThumbnailUrl() }}" 
                 alt="{{ $video->title }} thumbnail"
                 loading="lazy"
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

            <!-- Premium Badge (Right for Trending, Left otherwise) -->
            @if($video->is_premium)
                <div class="absolute top-2.5 {{ (isset($isTrending) && $isTrending) ? 'right-2.5' : ($video->is_age_restricted ? 'left-14' : 'left-2.5') }} z-30 gradient-orange text-white text-[9px] font-black px-1.5 py-0.5 rounded shadow-lg flex items-center gap-1 uppercase tracking-tighter border border-white/20">
                    <span class="material-symbols-rounded text-[11px] material-symbols-filled">workspace_premium</span>
                    <span class="{{ request()->routeIs('history') ? 'hidden lg:inline' : '' }}">Premium</span>
                </div>
            @elseif($video->scheduled_at && $video->scheduled_at->isFuture())
                <div class="absolute inset-0 z-[35] bg-black/80 backdrop-blur-[3px] flex flex-col items-center justify-center text-center p-2">
                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center mb-1 border border-blue-500/20">
                        <span class="material-symbols-rounded text-base sm:text-lg animate-pulse">schedule</span>
                    </div>
                    <p class="text-white font-black text-[7px] sm:text-[8px] uppercase tracking-widest leading-none mb-0.5">Premieres</p>
                    <p class="text-blue-400 font-bold text-[7px] sm:text-[8px] leading-none">{{ $video->scheduled_at->timezone('Asia/Kolkata')->format('M d, g:i A') }}</p>
                </div>
            @endif

            <!-- Age Restriction / Reported Gate -->
            @if($video->isRestricted() && !$isOptimizing)
                <div x-show="!acknowledged"
                     class="absolute inset-0 z-[45] bg-[#0F0F0F]/80 backdrop-blur-[30px] flex flex-col items-center justify-center p-4 text-center select-none">
                    <div class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mb-3 shadow-xl shrink-0">
                        <span class="material-symbols-rounded text-3xl text-white opacity-90 flex items-center justify-center leading-none select-none">shield_lock</span>
                    </div>
                    <h4 class="text-white text-[10px] font-black uppercase tracking-widest mb-1 leading-none">Restricted</h4>
                    
                    @if(!auth()->check())
                        <p class="text-white/50 text-[8px] font-bold uppercase tracking-tighter mb-4">Sign in to Verify</p>
                        <button @click.prevent.stop="window.location.href='{{ route('login') }}'" class="px-4 py-2 bg-rose-600 text-white rounded-xl font-black text-[9px] uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-lg shadow-rose-600/20">
                            Sign In
                        </button>
                    @else
                        <p class="text-white/50 text-[8px] font-bold uppercase tracking-tighter mb-4">Acknowledge to View</p>
                        <button @click.prevent.stop="acknowledged = true" class="px-4 py-2 bg-gradient-to-tr from-rose-600 to-orange-500 text-white rounded-xl font-black text-[9px] uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-lg shadow-rose-600/20">
                            I Understand
                        </button>
                    @endif
                </div>
            @endif

            <!-- Premium Access Overlay (Only if not purchased/no access AND not optimizing) -->
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
                            <div class="absolute inset-0 flex items-center justify-center">
                            </div>
                        </div>
                        <span class="text-[9px] font-black text-white uppercase tracking-[0.2em] animate-pulse">please wait awesome is loading</span>
                    </div>
                </div>
            </template>

            <div x-text="displayDuration" class="absolute bottom-2 right-2 z-20 px-1.5 py-0.5 bg-black/80 backdrop-blur-md rounded-md text-[10px] font-black text-white tracking-tight shadow-sm">
                {{ $video->formatted_duration }}
            </div>
            
            <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        </div>
    </a>

    <!-- Meta Container -->
    <div class="mt-4 flex gap-3">
        <!-- Avatar Section -->
        <div class="shrink-0 pt-0.5">
            <a href="{{ $video->user?->channel ? route('channels.show', $video->user->channel) : '#' }}" class="block" aria-label="View {{ $video->user?->channel?->name ?? 'Creator' }}'s channel">
                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-gray-200 to-gray-300 dark:from-white/10 dark:to-white/5 flex items-center justify-center text-slate-500 overflow-hidden border border-gray-100 dark:border-white/5 transition-transform active:scale-90">
                    @if($video->user?->channel?->avatar)
                        <img src="{{ getImage(getFilePath('channelAvatar') . '/' . $video->user->channel->avatar) }}" alt="{{ $video->user->channel->name ?? 'Creator' }} avatar" loading="lazy" class="w-full h-full object-cover">
                    @elseif($video->user?->image)
                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $video->user->image) }}" alt="{{ $video->user->name ?? 'Creator' }} avatar" loading="lazy" class="w-full h-full object-cover">
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
                @if($video->scheduled_at && $video->scheduled_at->isFuture())
                    <span class="text-blue-500 font-bold">Scheduled for {{ $video->scheduled_at->format('M d, Y h:i A') }}</span>
                @else
                    <span>{{ formatNumber($video->views_count ?? 0) }} views</span>
                    <span class="text-[10px]">•</span>
                    <span>{{ $video->created_at->diffForHumans() }}</span>
                @endif
            </div>
        </div>

        <!-- Action Menu -->
        <div class="shrink-0">
            <button @click.prevent.stop="window.openVideoOptions('{{ $video->id }}', '{{ addslashes($video->title) }}', 'video', {{ $video->isLikedBy(auth()->user()) ? 'true' : 'false' }}, {{ $video->isInWatchLater(auth()->user()) ? 'true' : 'false' }}, {{ $video->isInAnyPlaylist(auth()->user()) ? 'true' : 'false' }}, @json($video->getPlaylistMembershipIds(auth()->user())), {{ $video->user_id }})" 
                    aria-label="Video options"
                    class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-white/5 transition-colors active:scale-95">
                <span class="material-symbols-rounded text-xl">more_vert</span>
            </button>
        </div>
    </div>


</div>
