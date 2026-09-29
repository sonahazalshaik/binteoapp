<a data-reel-id="{{ $reel->id }}" href="{{ route('reels.index', ['reel' => $reel->slug]) }}" class="block reel-card group relative bg-slate-900 rounded-[1.5rem] sm:rounded-[2rem] overflow-hidden aspect-[9/16] shadow-xl shadow-black/20 hover:shadow-2xl hover:shadow-orange-500/10 transition-all duration-500 hover:-translate-y-1 cursor-pointer"
     x-data="{ hover: false }"
     @mouseenter="hover = true; $el.querySelector('video')?.play()"
     @mouseleave="hover = false; $el.querySelector('video')?.pause(); if($el.querySelector('video')) $el.querySelector('video').currentTime = 0">
    
    {{-- Thumbnail / Video Preview --}}
    <div class="absolute inset-0 pointer-events-none">
        <img src="{{ $reel->getThumbnailUrl() }}" 
             class="w-full h-full object-cover transition-opacity duration-500"
             :class="hover ? 'opacity-0' : 'opacity-100'">
        
        @if($reel->isBunnyReel())
            <video src="{{ str_replace('play_1080p.mp4', 'play_360p.mp4', $reel->getVideoUrl()) }}"
                   class="absolute inset-0 w-full h-full object-cover"
                   muted loop playsinline preload="none"></video>
        @endif

        {{-- Age Restriction / Reported Gate --}}
        @if($reel->isRestricted())
            <div x-data="{ acknowledged: false }"
                 x-show="!acknowledged"
                 class="absolute inset-0 z-[45] bg-[#0F0F0F]/80 backdrop-blur-[30px] flex flex-col items-center justify-center p-4 text-center pointer-events-auto">
                <div class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mb-4 shadow-xl">
                    <span class="material-symbols-rounded text-3xl text-white opacity-90">shield_lock</span>
                </div>
                <h4 class="text-white text-[10px] font-black uppercase tracking-widest mb-1 leading-none">Restricted</h4>
                
                @if(!auth()->check())
                    <p class="text-white/50 text-[8px] font-bold uppercase tracking-tighter mb-4">Sign in to Verify</p>
                    <button @click.prevent.stop="window.location.href='{{ route('login') }}'" class="px-4 py-2 bg-rose-600 text-white rounded-xl font-black text-[9px] uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-lg shadow-rose-600/20 pointer-events-auto">
                        Sign In
                    </button>
                @else
                    <p class="text-white/50 text-[8px] font-bold uppercase tracking-tighter mb-4">Acknowledge to View</p>
                    <button @click.prevent.stop="acknowledged = true" class="px-4 py-2 bg-gradient-to-tr from-rose-600 to-orange-500 text-white rounded-xl font-black text-[9px] uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-lg shadow-rose-600/20 pointer-events-auto">
                        I Understand
                    </button>
                @endif
            </div>
        @endif
    </div>

    {{-- Gradient Overlay --}}
    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-80 group-hover:opacity-100 transition-opacity z-20 pointer-events-none" style="background: linear-gradient(to top, rgba(0, 0, 0, 0.95) 0%, rgba(0, 0, 0, 0.5) 45%, transparent 100%);"></div>

    {{-- Info Overlay --}}
    <div class="absolute inset-x-0 bottom-0 p-5 z-30 pointer-events-none" style="background: linear-gradient(to top, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.4) 65%, transparent 100%);">
        <h3 class="text-white font-bold text-xs line-clamp-2 leading-tight drop-shadow-md mb-3 group-hover:text-orange-400 transition-colors">
            {{ $reel->title }}
        </h3>
        
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full border border-white/20 overflow-hidden shadow-lg">
                    @if($reel->user->channel?->avatar)
                        <img src="{{ getImage(getFilePath('channelAvatar') . '/' . $reel->user->channel->avatar) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full gradient-orange flex items-center justify-center text-[8px] font-black text-white uppercase">
                            {{ substr($reel->user->username ?? $reel->user->fullname, 0, 1) }}
                        </div>
                    @endif
                </div>
                <span class="text-[10px] font-black text-white/70 uppercase tracking-tighter truncate max-w-[80px]">
                    {{ $reel->user->username ?? 'Creator' }}
                </span>
            </div>
            
            <div class="flex items-center gap-1.5 text-white/60">
                <span class="material-symbols-rounded text-[14px]">play_arrow</span>
                <span class="text-[9px] font-black tracking-widest">{{ formatNumber($reel->views_count) }}</span>
            </div>
        </div>
    </div>

    {{-- Hot/New Badges --}}
    {!! $reel->getBadgeHtml() !!}

    @if((string)$reel->visibility === (string)\App\Constants\Status::PRIVATE)
        <div class="absolute top-3 left-3 z-30 bg-red-600/90 backdrop-blur-sm border border-red-500/20 text-white px-2 py-0.5 rounded-lg flex items-center gap-1 shadow-md pointer-events-none">
            <span class="material-symbols-rounded text-xs">lock</span>
            <span class="text-[8px] font-black uppercase tracking-wider">Private</span>
        </div>
    @endif
    
    <div class="absolute top-3 right-3 z-30 flex flex-col gap-2">
        <button @click.prevent.stop="window.openVideoOptions('{{ $reel->id }}', '{{ addslashes($reel->title) }}', 'reel', {{ $reel->isLikedBy(auth()->user()) ? 'true' : 'false' }}, {{ $reel->isInWatchLater(auth()->user()) ? 'true' : 'false' }}, {{ $reel->isInAnyPlaylist(auth()->user()) ? 'true' : 'false' }}, @json($reel->getPlaylistMembershipIds(auth()->user())), {{ $reel->user_id }})" 
                class="w-8 h-8 rounded-full bg-black/40 backdrop-blur-md border border-white/10 flex items-center justify-center text-white hover:bg-orange-500 hover:border-orange-500 transition-all active:scale-95 pointer-events-auto">
            <span class="material-symbols-rounded text-lg">more_vert</span>
        </button>
        <div class="w-8 h-8 rounded-full bg-black/40 backdrop-blur-md border border-white/10 flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all scale-75 group-hover:scale-100 pointer-events-none">
            <span class="material-symbols-rounded text-lg">slow_motion_video</span>
        </div>
    </div>
</a>
