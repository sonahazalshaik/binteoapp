@props(['video', 'index' => null, 'deleteRoute' => null, 'deleteMethod' => 'DELETE', 'showSno' => false, 'playlistUrl' => null])

<div id="item-{{ $video->type }}-{{ $video->id }}" data-video-id="{{ $video->id }}" data-video-type="{{ $video->type }}" class="group flex items-center gap-2 sm:gap-4 p-2 sm:p-3 rounded-2xl hover:bg-gray-100 dark:hover:bg-white/5 transition-all duration-300 relative">
    @if($showSno && $index !== null)
    <div class="flex-shrink-0 w-4 sm:w-6 text-center text-xs font-bold text-gray-400 sno-num">
        {{ $index + 1 }}
    </div>
    @endif
    
    <div class="relative w-28 sm:w-44 aspect-video rounded-xl overflow-hidden flex-shrink-0 shadow-sm">
        <img src="{{ $video->getThumbnailUrl() }}" class="w-full h-full object-cover {{ ($video->is_age_restricted && !auth()->check()) ? 'blur-xl' : '' }}" alt="{{ $video->title }}">

        @if($video->is_premium ?? false)
            <div class="absolute top-1 left-1 sm:top-1.5 sm:left-1.5 gradient-orange text-white text-[6px] sm:text-[8px] px-1.5 py-0.5 rounded-full font-black uppercase tracking-[0.1em] shadow-lg z-20 flex items-center gap-0.5">
                <span class="material-symbols-rounded text-[10px] sm:text-[12px]">workspace_premium</span>
                <span class="hidden sm:inline">Premium</span>
            </div>
        @endif

        @if($video->is_age_restricted && !auth()->check())
            <div class="absolute inset-0 z-40 bg-black/40 backdrop-blur-md flex flex-col items-center justify-center text-center p-1 sm:p-2">
                <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg bg-rose-600 text-white flex items-center justify-center mb-1 shadow-lg shadow-rose-500/40">
                    <span class="material-symbols-rounded text-[12px] sm:text-base font-black">explicit</span>
                </div>
                <p class="text-white font-black text-[6px] sm:text-[7px] uppercase tracking-tighter">18+</p>
            </div>
        @endif

        @if($video->type === 'reel')
            <a href="{{ route('reels.show', ['reel' => $video->slug]) }}" class="absolute inset-0"></a>
            <div class="absolute top-1 right-1 px-1 sm:px-1.5 py-0.5 bg-black/60 rounded text-[7px] sm:text-[8px] font-black text-white uppercase tracking-widest flex items-center gap-1">
                <span class="material-symbols-rounded text-[8px] sm:text-[10px]">movie</span> Reel
            </div>
        @else
            <a href="{{ $playlistUrl ?? route('videos.show', ['video' => $video->slug]) }}" class="absolute inset-0"></a>
            <div x-data="{
                    durationSeconds: 0,
                    orig: @js($video->formatted_duration),
                    init() {
                        if (this.orig && this.orig !== '00:00' && this.orig !== '0:00' && this.orig !== '' && this.orig !== '--:--') return;
                        this.$nextTick(() => {
                            const v = this.$refs.probe;
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
                    get displayDuration() {
                        if (this.orig && this.orig !== '00:00' && this.orig !== '0:00' && this.orig !== '' && this.orig !== '--:--') return this.orig;
                        if (this.durationSeconds > 0) {
                            const h = Math.floor(this.durationSeconds/3600);
                            const m = Math.floor((this.durationSeconds%3600)/60);
                            const s = Math.floor(this.durationSeconds%60);
                            if(h>0) return h+':'+String(m).padStart(2,'0')+':'+String(s).padStart(2,'0');
                            return String(m).padStart(2,'0')+':'+String(s).padStart(2,'0');
                        }
                        return (this.orig && this.orig !== '00:00' && this.orig !== '0:00') ? this.orig : '--:--';
                    }
                }" class="absolute bottom-1 right-1 sm:bottom-2 sm:right-2 px-1 sm:px-1.5 py-0.5 bg-black/80 rounded text-[8px] sm:text-[10px] font-black text-white uppercase tracking-widest">
                <span x-text="displayDuration">{{ ($video->formatted_duration !== '00:00' && $video->formatted_duration !== '--:--') ? $video->formatted_duration : '--:--' }}</span>
                @if(!$video->isBunnyVideo())
                    <video x-ref="probe" src="{{ $video->getVideoUrl() }}" preload="metadata" muted playsinline webkit-playsinline style="display:none"></video>
                @endif
            </div>
        @endif

        @if($video->is_age_restricted)
            <div class="absolute bottom-1 left-1 sm:bottom-1.5 sm:left-1.5 px-1.5 py-0.5 bg-rose-600/90 backdrop-blur-md rounded text-[7px] font-black text-white uppercase tracking-tighter shadow-sm border border-rose-500/20 z-20">
                18+
            </div>
        @endif
    </div>

    <!-- Content -->
    <div class="flex-grow min-w-0 flex flex-col justify-center">
        <h3 class="text-xs sm:text-base font-black text-gray-900 dark:text-white line-clamp-2 leading-tight mb-1 group-hover:text-red-600 transition-colors">
            @if($video->type === 'reel')
                <a href="{{ route('reels.show', ['reel' => $video->slug]) }}">{{ $video->title }}</a>
            @else
                <a href="{{ $playlistUrl ?? route('videos.show', ['video' => $video->slug]) }}">{{ $video->title }}</a>
            @endif
        </h3>
        <div class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs text-gray-500 dark:text-gray-400">
            <span class="truncate max-w-[80px] sm:max-w-none">{{ $video->user->channel->name ?? $video->user->name }}</span>
            <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-white/10 hidden sm:block"></span>
            <span class="hidden sm:block">{{ formatNumber($video->views_count ?? 0) }} views</span>
            <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-white/10 hidden sm:block"></span>
            <span class="hidden sm:block">{{ $video->created_at?->diffForHumans() ?? 'Just now' }}</span>
        </div>
    </div>

    <!-- Actions Menu -->
    <div class="flex items-center shrink-0">
        @if($deleteRoute)
            <button onclick="event.preventDefault(); event.stopPropagation(); asyncRemoveItem(this, '{{ $deleteRoute }}', '{{ $deleteMethod }}')" class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-gray-400 hover:text-red-600 transition-colors">
                <span class="material-symbols-rounded text-lg sm:text-2xl">close</span>
            </button>
        @endif
        <button onclick="window.openVideoOptions('{{ $video->id }}', '{{ addslashes($video->title) }}', '{{ $video->type }}', {{ $video->isLikedBy(auth()->user()) ? 'true' : 'false' }}, {{ $video->isInWatchLater(auth()->user()) ? 'true' : 'false' }}, {{ $video->isInAnyPlaylist(auth()->user()) ? 'true' : 'false' }}, @json($video->getPlaylistMembershipIds(auth()->user() ?? null)), {{ $video->user_id }})" 
            class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
            <span class="material-symbols-rounded text-lg sm:text-2xl">more_vert</span>
        </button>
    </div>
</div>
