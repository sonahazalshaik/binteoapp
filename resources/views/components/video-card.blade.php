@props(['video', 'showDate' => true, 'deleteRoute' => null, 'deleteMethod' => 'DELETE'])

<div data-video-id="{{ $video->id }}" @php
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
      }" class="group cursor-pointer">
    <div class="block">
        <a href="{{ route('videos.show', $video) }}">
            <div class="aspect-video bg-gray-100 dark:bg-white/5 rounded-[1.5rem] overflow-hidden mb-4 relative shadow-sm group-hover:shadow-2xl group-hover:-translate-y-1 transition-all duration-500">
                <img src="{{ $video->getThumbnailUrl() }}" 
                     alt="{{ $video->title }} thumbnail"
                     loading="lazy"
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                     onerror="this.src='{{ $video->getPreviewUrl() }}'; this.onerror=null;">
                
                <!-- Branded Dynamic Loading State -->
                <template x-if="isOptimizing">
                    <div class="absolute inset-0 bg-black/60 backdrop-blur-md flex flex-col items-center justify-center z-20">
                        <div class="relative w-12 h-12 mb-2">
                            <svg class="w-full h-full transform -rotate-90">
                                <circle cx="24" cy="24" r="20" stroke="currentColor" stroke-width="3" fill="transparent" class="text-white/10" />
                                <circle cx="24" cy="24" r="20" stroke="currentColor" stroke-width="3" fill="transparent" class="text-orange-500 transition-all duration-500" stroke-dasharray="125.6" :stroke-dashoffset="125.6 - (125.6 * progress / 100)" />
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center"></div>
                        </div>
                        <span class="text-[7px] font-black text-white uppercase tracking-widest animate-pulse">please wait awesome is loading</span>
                    </div>
                </template>

                @if($video->is_premium)
                    <div class="absolute top-3 left-3 gradient-orange text-white text-[9px] px-3 py-1 rounded-full font-black uppercase tracking-[0.15em] shadow-lg z-20 flex items-center gap-1.5">
                        <span class="material-symbols-rounded text-[14px]">workspace_premium</span>
                        Premium
                    </div>
                @endif

                {{-- Age Restriction / Reported Gate --}}
                @if($video->isRestricted())
                    <div x-data="{ acknowledged: false }" 
                         x-show="!acknowledged"
                         class="absolute inset-0 z-[45] bg-[#0F0F0F]/80 backdrop-blur-[30px] flex flex-col items-center justify-center p-4 text-center select-none">
                        <div class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mb-4 shadow-xl shrink-0">
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
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                @if($deleteRoute)
                    <button onclick="event.preventDefault(); event.stopPropagation(); confirmDelete('{{ $deleteRoute }}', 'Remove this video from your history?', 'Yes, remove it!', '{{ $deleteMethod }}')" 
                            class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/60 backdrop-blur-md text-white flex items-center justify-center transition-all hover:bg-red-600 z-30 shadow-lg">
                        <span class="material-symbols-rounded text-sm">close</span>
                    </button>
                @endif

                <div x-data="{
                        durationSeconds: 0,
                        orig: @js($video->formatted_duration),
                        init() {
                            if (this.orig && this.orig !== '00:00' && this.orig !== '0:00' && this.orig !== '' ) return;
                            this.$nextTick(() => {
                                const v = this.$refs.probe;
                                if (!v) return;
                                const set = () => { if (v.duration && isFinite(v.duration) && v.duration !== Infinity) this.durationSeconds = v.duration; };
                                v.addEventListener('loadedmetadata', set, {once:true});
                                v.addEventListener('durationchange', set, {once:true});
                                try { v.load(); } catch(e) {}
                                if (v.readyState >= 1 && v.duration) set();
                                // Fallback: if Infinity (Chromium blob), seek workaround
                                v.addEventListener('loadedmetadata', () => {
                                    if (v.duration === Infinity) {
                                        try { v.currentTime = Number.MAX_SAFE_INTEGER; v.ontimeupdate = null; v.addEventListener('seeked', () => { if(isFinite(v.duration)) this.durationSeconds = v.duration; try{ v.currentTime=0;}catch(e){} }, {once:true}); } catch(e){}
                                    }
                                }, {once:true});
                            });
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
                        }
                    }" class="absolute bottom-3 right-3 bg-black/80 backdrop-blur-md text-white text-[10px] px-2 py-1 rounded-lg font-black tracking-widest flex items-center gap-1">
                    <span x-text="displayDuration">{{ ($video->formatted_duration !== '00:00') ? $video->formatted_duration : '--:--' }}</span>
                    @if(!$video->isBunnyVideo())
                        <video x-ref="probe" src="{{ $video->getVideoUrl() }}" preload="metadata" muted playsinline webkit-playsinline style="display:none"></video>
                    @endif
                </div>
            </div>
        </a>
        
        <div class="flex gap-2 sm:gap-3">
            <div class="flex-shrink-0 mt-1">
                <a href="{{ $video->channel ? route('channels.show', $video->channel) : '#' }}" class="block" aria-label="View {{ $video->channel->name ?? 'Creator' }}'s channel">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-red-600 overflow-hidden border border-gray-100 dark:border-white/5 shadow-sm active:scale-90 transition-transform">
                        @if($video->channel->avatar)
                            <img src="{{ getImage($video->channel->avatar) }}" alt="{{ $video->channel->name ?? 'Creator' }} avatar" loading="lazy" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-white text-[9px] sm:text-[10px] font-black uppercase">
                                {{ substr($video->channel->user->channel_name ?? $video->channel->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                </a>
            </div>
            
            <div class="flex-grow min-w-0 flex flex-col">
                <a href="{{ route('videos.show', $video) }}" class="block">
                    <h3 class="text-[13px] sm:text-[14px] lg:text-[15px] font-black text-gray-900 dark:text-white group-hover:text-red-600 transition-colors line-clamp-2 leading-tight min-h-[2.2rem] sm:min-h-[2.5rem]">
                        {{ $video->title }}
                    </h3>
                </a>
                <div class="mt-1 space-y-0.5 sm:space-y-1 mt-auto">
                    <a href="{{ $video->channel ? route('channels.show', $video->channel) : '#' }}" class="block text-[10px] sm:text-[12px] font-bold text-gray-400 dark:text-neutral-500 hover:text-gray-900 dark:hover:text-white transition-colors truncate">
                        {{ $video->channel->user->channel_name ?? $video->channel->name }}
                    </a>
                    <div class="flex flex-wrap items-center text-[9px] sm:text-[11px] font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider sm:tracking-widest">
                        <span class="whitespace-nowrap">{{ number_format($video->views_count) }} views</span>
                        @if($showDate)
                            <span class="mx-1 sm:mx-2 opacity-30">•</span>
                            <span class="whitespace-nowrap">{{ $video->created_at?->diffForHumans() ?? 'Just now' }}</span>
                        @endif
                    </div>
                </div>
            </div>
 
            <!-- Action Menu -->
            <div class="flex-shrink-0">
                <button onclick="window.openVideoOptions('{{ $video->id }}', '{{ addslashes($video->title) }}', '{{ $video->type }}', {{ $video->isLikedBy(auth()->user()) ? 'true' : 'false' }}, {{ $video->isInWatchLater(auth()->user()) ? 'true' : 'false' }}, {{ $video->isInAnyPlaylist(auth()->user()) ? 'true' : 'false' }}, @json($video->getPlaylistMembershipIds(auth()->user() ?? null)), {{ $video->user_id }})" 
                        aria-label="Video options"
                        class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center text-gray-400 dark:text-neutral-500 hover:bg-gray-100 dark:hover:bg-white/5 transition-all active:scale-95">
                    <span class="material-symbols-rounded text-[18px] sm:text-[22px]">more_vert</span>
                </button>
            </div>
        </div>
    </div>
</div>
