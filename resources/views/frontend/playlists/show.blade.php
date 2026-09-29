@php
    $firstItem = $playlist->videos->first() ?? $playlist->reels->first();
    $thumbnail = $firstItem ? $firstItem->getThumbnailUrl() : asset('assets/images/default.png');
    $itemCount = $playlist->videos->count() + $playlist->reels->count();
    $playRoute = '#';
    if ($playlist->videos->first()) {
        $playRoute = route('videos.show.in_playlist', ['video' => $playlist->videos->first()->slug, 'username' => optional($playlist->user->channel)->slug ?? $playlist->user->username ?? 'user', 'playlist' => $playlist->slug]);
    } elseif ($playlist->reels->first()) {
        $playRoute = route('reels.show', ['reel' => $playlist->reels->first()->slug, 'playlist' => \Illuminate\Support\Str::slug($playlist->name), 'list' => $playlist->id]);
    }



@endphp

<x-app-layout>
    <div x-data="{ 
        shareOpen: false,
        removing: {},
        shareVideo() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ addslashes($playlist->name) }}',
                    text: 'Check out this playlist on {{ $general->site_name }}',
                    url: window.location.href,
                }).catch(console.error);
            } else {
                this.shareOpen = true;
            }
        },
        async removeItem(id, type, url) {
            const key = type + '-' + id;
            // Single guarded request + instant DOM removal: the shared
            // toggle endpoint detaches (item is present), and rapid repeat
            // clicks have nothing left to hit — no re-add race.
            if (this.removing[key]) return;
            this.removing[key] = true;
            let el = document.getElementById('item-' + type + '-' + id);
            if (el) {
                el.style.maxHeight = el.scrollHeight + 'px';
                el.style.overflow = 'hidden';
                requestAnimationFrame(() => {
                    el.style.maxHeight = '0px';
                    el.style.margin = '0';
                    el.style.padding = '0';
                    el.style.opacity = '0';
                });
                setTimeout(() => {
                    el.remove();
                    // Re-sequence video serial numbers + count badges so
                    // they always match the entries actually on screen.
                    const videoRows = document.querySelectorAll('[data-playlist-row].pl-video-row');
                    videoRows.forEach((row, i) => {
                        const sno = row.querySelector('.sno-num');
                        if (sno) sno.textContent = i + 1;
                    });
                    const remaining = document.querySelectorAll('[data-playlist-row]').length;
                    const badge = document.getElementById('pl-badge-count');
                    if (badge) badge.textContent = remaining;
                    const itemsEl = document.getElementById('pl-items-count');
                    if (itemsEl) itemsEl.textContent = remaining + (remaining === 1 ? ' Item' : ' Items');
                    if (remaining === 0) window.location.reload();
                }, 300);
            }
            Swal.fire({
                toast: true, position: 'top-end', showConfirmButton: false, timer: 2000,
                icon: 'success', title: 'Removed from playlist',
                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            });
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ playlist_id: '{{ $playlist->id }}' })
                });
                if (!response.ok) throw new Error('Request failed (' + response.status + ')');
            } catch(e) {
                console.error(e);
                Swal.fire({
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 2500,
                    icon: 'error', title: 'Could not sync removal — refreshing',
                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                });
                window.location.reload();
            }
        }
    }" id="playlist-show" class="min-h-screen bg-white dark:bg-[#0F0F0F] transition-colors duration-500 pb-20">
        
        @if($itemCount > 0)
        <!-- Cinematic Hero Header -->
        <div class="relative w-full overflow-hidden">
            <!-- Blurred Backdrop -->
            <div class="absolute inset-0 z-0">
                <img src="{{ $thumbnail }}" class="w-full h-[500px] object-cover blur-3xl opacity-30 dark:opacity-20 scale-110">
                <div class="absolute inset-0 bg-gradient-to-b from-transparent via-white/50 to-white dark:via-[#0F0F0F]/50 dark:to-[#0F0F0F]"></div>
            </div>

            <!-- Content Container -->
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-8 lg:pt-12 lg:pb-10">
                <div class="flex flex-col lg:flex-row items-center lg:items-end gap-6 lg:gap-10">
                    
                    <!-- Prominent Thumbnail Card -->
                    <div class="relative w-64 sm:w-72 aspect-video lg:aspect-square rounded-[2rem] overflow-hidden shadow-2xl shadow-black/20 group transform transition-transform duration-700 hover:scale-[1.02]">
                        <img src="{{ $thumbnail }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 {{ ($firstItem && $firstItem->is_age_restricted && !auth()->check()) ? 'blur-2xl' : '' }}">
                        
                        @if($firstItem && $firstItem->is_age_restricted && !auth()->check())
                            <div class="absolute inset-0 z-40 bg-black/40 backdrop-blur-md flex flex-col items-center justify-center text-center p-6">
                                <div class="w-16 h-16 rounded-[1.5rem] bg-rose-600 text-white flex items-center justify-center mb-4 shadow-2xl shadow-rose-500/40">
                                    <span class="material-symbols-rounded text-4xl font-black">explicit</span>
                                </div>
                                <h4 class="text-white font-black text-lg uppercase tracking-widest mb-1">Age Restricted</h4>
                                <p class="text-rose-100/60 font-bold text-[10px] uppercase tracking-widest">Sign in to confirm age</p>
                            </div>
                        @endif
                        @if($itemCount > 0)
                        <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500 backdrop-blur-[2px]">
                            <a href="{{ $playRoute }}" class="w-20 h-20 bg-white/20 backdrop-blur-xl rounded-full flex items-center justify-center border border-white/30 text-white shadow-2xl scale-75 group-hover:scale-100 transition-all duration-500">
                                <span class="material-symbols-rounded text-5xl translate-x-1">play_arrow</span>
                            </a>
                        </div>
                        @endif
                        <!-- Items Badge -->
                        <div class="absolute bottom-6 right-6 px-4 py-2 bg-black/60 backdrop-blur-md rounded-2xl border border-white/10 text-white flex items-center gap-2">
                            <span class="material-symbols-rounded text-xl">playlist_play</span>
                            <span id="pl-badge-count" class="text-sm font-black tracking-widest">{{ $itemCount }}</span>
                        </div>
                    </div>

                    <!-- Playlist Info & Actions -->
                    <div class="flex-grow text-center lg:text-left space-y-6">
                        <div class="space-y-2">
                            <h1 class="text-2xl md:text-4xl font-black text-gray-900 dark:text-white leading-tight tracking-tighter uppercase">{{ $playlist->name }}</h1>
                            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3 text-xs font-bold text-gray-400 uppercase tracking-widest">
                                <span>{{ $playlist->user->name }}</span>
                                <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-white/10"></span>
                                <span id="pl-items-count" class="text-red-600">{{ $itemCount }} Items</span>
                                <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-white/10"></span>
                                <span>Updated {{ $playlist->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons (Native Android Style) -->
                        <div class="flex items-center justify-center lg:justify-start gap-2 sm:gap-3 pt-4 w-full">
                            <a href="{{ $playRoute }}" class="flex-1 h-12 px-2 sm:px-8 rounded-full font-black text-[10px] sm:text-xs uppercase tracking-widest flex items-center justify-center gap-1 sm:gap-2 transition-all {{ $itemCount > 0 ? 'bg-gray-900 dark:bg-white text-white dark:text-black active:scale-95 shadow-xl shadow-black/10 dark:shadow-none' : 'bg-gray-200 dark:bg-white/5 text-gray-400 dark:text-gray-600 pointer-events-none' }}">
                                <span class="material-symbols-rounded text-lg sm:text-xl fill-1">play_arrow</span>
                                <span class="truncate">Play All</span>
                            </a>
                            @php
                                $allItems = $playlist->videos->merge($playlist->reels);
                                $randomItem = $allItems->count() > 0 ? $allItems->random() : null;
                                $shuffleRoute = '#';
                                if ($randomItem) {
                                    if ($randomItem instanceof \App\Models\Video) {
                                         $shuffleRoute = route('videos.show.in_playlist', ['video' => $randomItem->slug, 'username' => optional($playlist->user->channel)->slug ?? $playlist->user->username ?? 'user', 'playlist' => $playlist->slug]);
                                    } else {
                                         $shuffleRoute = route('reels.show', ['reel' => $randomItem->slug, 'playlist' => \Illuminate\Support\Str::slug($playlist->name), 'list' => $playlist->id]);
                                    }
                                }
                            @endphp
                            <a href="{{ $shuffleRoute }}" class="flex-1 h-12 px-2 sm:px-8 rounded-full font-black text-[10px] sm:text-xs uppercase tracking-widest flex items-center justify-center gap-1 sm:gap-2 transition-all border {{ $itemCount > 0 ? 'bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-white active:scale-95 border-gray-200 dark:border-white/10' : 'bg-transparent text-gray-300 dark:text-gray-700 border-gray-200 dark:border-white/5 pointer-events-none' }}">
                                <span class="material-symbols-rounded text-lg sm:text-xl">shuffle</span>
                                <span class="truncate">Shuffle</span>
                            </a>
                            <button @click="shareVideo()" class="w-12 h-12 shrink-0 bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-white rounded-full flex items-center justify-center active:scale-95 transition-all border border-gray-200 dark:border-white/10">
                                <span class="material-symbols-rounded text-xl">share</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video & Reel List Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-32">
            <div class="space-y-1">
                {{-- Videos --}}
                @foreach($playlist->videos as $index => $video)
                    <div id="item-video-{{ $video->id }}" data-playlist-row class="pl-video-row group flex flex-row items-center gap-2 sm:gap-6 p-2 sm:p-4 rounded-[1.5rem] sm:rounded-[2rem] hover:bg-gray-100 dark:hover:bg-white/5 transition-all duration-300 relative">
                        <div class="flex-shrink-0 w-4 sm:w-8 text-center text-[10px] sm:text-xs font-black text-gray-400 group-hover:text-red-600 transition-colors sno-num">
                            {{ $index + 1 }}
                        </div>
                        
                        <!-- Mini Thumbnail -->
                        <div class="relative w-28 sm:w-56 aspect-video rounded-xl sm:rounded-2xl overflow-hidden flex-shrink-0 shadow-sm group-hover:shadow-xl transition-all duration-500">
                            <img src="{{ $video->getThumbnailUrl() }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 {{ ($video->is_age_restricted && !auth()->check()) ? 'blur-xl' : '' }}">
                            
                            @if($video->is_age_restricted && !auth()->check())
                                <div class="absolute inset-0 z-40 bg-black/40 backdrop-blur-md flex flex-col items-center justify-center text-center p-1 sm:p-2">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-md sm:rounded-lg bg-rose-600 text-white flex items-center justify-center mb-0.5 sm:mb-1 shadow-lg shadow-rose-500/40">
                                        <span class="material-symbols-rounded text-sm sm:text-base font-black">explicit</span>
                                    </div>
                                    <p class="text-white font-black text-[6px] sm:text-[7px] uppercase tracking-tighter">18+</p>
                                </div>
                            @endif
                            <a href="{{ route('videos.show.in_playlist', ['video' => $video->slug, 'username' => optional($playlist->user->channel)->slug ?? $playlist->user->username ?? 'user', 'playlist' => $playlist->slug]) }}" class="absolute inset-0"></a>

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
                                }" class="absolute bottom-1 right-1 sm:bottom-2 sm:right-2 px-1.5 py-0.5 bg-black/80 rounded-md sm:rounded-lg text-[8px] sm:text-[10px] font-black text-white">
                                <span x-text="displayDuration">{{ ($video->formatted_duration !== '00:00' && $video->formatted_duration !== '--:--') ? $video->formatted_duration : '--:--' }}</span>
                                @if(!$video->isBunnyVideo())
                                    <video x-ref="probe" src="{{ $video->getVideoUrl() }}" preload="metadata" muted playsinline webkit-playsinline style="display:none"></video>
                                @endif
                            </div>

                            @if($video->is_age_restricted)
                                <div class="absolute top-1 left-1 sm:top-2 sm:left-2 px-1 py-0.5 bg-rose-600/90 backdrop-blur-md rounded text-[6px] sm:text-[7px] font-black text-white uppercase tracking-tighter shadow-sm border border-rose-500/20">
                                    18+
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-1 items-center justify-between min-w-0 gap-1 sm:gap-4">
                            <!-- Info -->
                            <div class="flex-grow min-w-0">
                                <h3 class="text-xs sm:text-lg font-black text-gray-900 dark:text-white line-clamp-2 leading-tight mb-1 group-hover:text-red-600 transition-colors">
                                    <a href="{{ route('videos.show.in_playlist', ['video' => $video->slug, 'username' => optional($playlist->user->channel)->slug ?? $playlist->user->username ?? 'user', 'playlist' => $playlist->slug]) }}">{{ $video->title }}</a>
                                </h3>
                                <div class="flex items-center gap-1 sm:gap-3 text-[9px] sm:text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                    <span class="truncate">{{ $video->user->channel->name ?? $video->user->name }}</span>
                                    <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-white/10 shrink-0 hidden sm:block"></span>
                                    <span class="shrink-0 hidden sm:inline">{{ formatNumber($video->views_count) }} views</span>
                                </div>
                            </div>

                            <!-- Actions Menu -->
                            <div class="flex items-center shrink-0">
                                <button type="button" @click="removeItem('{{ $video->id }}', 'video', '{{ route('videos.playlist.toggle', $video) }}')" class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-gray-400 hover:text-red-600 transition-colors">
                                    <span class="material-symbols-rounded text-lg sm:text-2xl">delete</span>
                                </button>
                                <button onclick="window.openVideoOptions('{{ $video->id }}', '{{ addslashes($video->title) }}', 'video', {{ $video->isLikedBy(auth()->user()) ? 'true' : 'false' }}, {{ $video->isInWatchLater(auth()->user()) ? 'true' : 'false' }}, {{ $video->isInAnyPlaylist(auth()->user()) ? 'true' : 'false' }}, @json($video->getPlaylistMembershipIds(auth()->user() ?? null)), {{ $video->user_id }})" 
                                    class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-gray-400 hover:text-gray-900 dark:hover:text-white">
                                    <span class="material-symbols-rounded text-lg sm:text-2xl">more_vert</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Reels --}}
                @foreach($playlist->reels as $reel)
                    <div id="item-reel-{{ $reel->id }}" data-playlist-row class="group flex flex-row items-center gap-2 sm:gap-6 p-2 sm:p-4 rounded-[1.5rem] sm:rounded-[2rem] hover:bg-gray-100 dark:hover:bg-white/5 transition-all duration-300 relative">
                        <div class="flex-shrink-0 w-4 sm:w-8 text-center">
                            <span class="material-symbols-rounded text-purple-500 text-sm sm:text-lg">slow_motion_video</span>
                        </div>
                        
                        <!-- Mini Reel Thumbnail -->
                        <div class="relative w-16 sm:w-56 h-28 sm:h-32 aspect-[9/16] rounded-xl sm:rounded-2xl overflow-hidden flex-shrink-0 shadow-sm group-hover:shadow-xl transition-all duration-500 bg-gray-900">
                            <img src="{{ $reel->getThumbnailUrl() }}" class="w-full h-full object-cover opacity-80 transition-transform duration-700 group-hover:scale-105 {{ ($reel->is_age_restricted && !auth()->check()) ? 'blur-xl' : '' }}">
                            
                            @if($reel->is_age_restricted && !auth()->check())
                                <div class="absolute inset-0 z-40 bg-black/40 backdrop-blur-md flex flex-col items-center justify-center text-center p-1 sm:p-2">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-md sm:rounded-lg bg-rose-600 text-white flex items-center justify-center mb-0.5 sm:mb-1 shadow-lg shadow-rose-500/40">
                                        <span class="material-symbols-rounded text-sm sm:text-base font-black">explicit</span>
                                    </div>
                                    <p class="text-white font-black text-[6px] sm:text-[7px] uppercase tracking-tighter">18+</p>
                                </div>
                            @endif
                            <a href="{{ route('reels.show', $reel->slug) }}" class="absolute inset-0"></a>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="material-symbols-rounded text-white text-xl sm:text-3xl opacity-0 group-hover:opacity-100 transition-opacity">play_arrow</span>
                            </div>

                            @if($reel->is_age_restricted)
                                <div class="absolute top-1 left-1 sm:top-2 sm:left-2 px-1 py-0.5 bg-rose-600/90 backdrop-blur-md rounded text-[6px] sm:text-[7px] font-black text-white uppercase tracking-tighter shadow-sm border border-rose-500/20">
                                    18+
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-1 items-center justify-between min-w-0 gap-1 sm:gap-4">
                            <!-- Info -->
                            <div class="flex-grow min-w-0">
                                <div class="flex items-center gap-1 sm:gap-2 mb-1">
                                    <span class="px-1.5 sm:px-2 py-0.5 bg-purple-500/10 text-purple-500 rounded-md sm:rounded-lg text-[7px] sm:text-[9px] font-black uppercase tracking-widest border border-purple-500/20">Reel</span>
                                </div>
                                <h3 class="text-xs sm:text-lg font-black text-gray-900 dark:text-white line-clamp-2 leading-tight mb-1 group-hover:text-purple-600 transition-colors">
                                    <a href="{{ route('reels.show', $reel->slug) }}">{{ $reel->title }}</a>
                                </h3>
                                <div class="flex items-center gap-1 sm:gap-3 text-[9px] sm:text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                    <span class="truncate">{{ $reel->user->channel->name ?? $reel->user->name }}</span>
                                </div>
                            </div>

                            <!-- Actions Menu -->
                            <div class="flex items-center shrink-0">
                                <button type="button" @click="removeItem('{{ $reel->id }}', 'reel', '{{ route('playlists.toggle-reel', $reel) }}')" class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-gray-400 hover:text-red-600 transition-colors">
                                    <span class="material-symbols-rounded text-lg sm:text-2xl">delete</span>
                                </button>
                                <button onclick="window.openVideoOptions('{{ $reel->id }}', '{{ addslashes($reel->title) }}', 'reel', {{ $reel->isLikedBy(auth()->user()) ? 'true' : 'false' }}, {{ $reel->isInWatchLater(auth()->user()) ? 'true' : 'false' }}, {{ $reel->isInAnyPlaylist(auth()->user()) ? 'true' : 'false' }}, @json($reel->getPlaylistMembershipIds(auth()->user() ?? null)), {{ $reel->user_id }})" 
                                    class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-gray-400 hover:text-gray-900 dark:hover:text-white">
                                    <span class="material-symbols-rounded text-lg sm:text-2xl">more_vert</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
        @else
        <!-- Simple Native Android Empty State -->
        <div class="flex flex-col items-center justify-center h-[calc(100vh-12rem)] md:min-h-[70vh] md:h-auto text-center px-4">
            <div class="w-24 h-24 md:w-32 md:h-32 bg-gray-200 dark:bg-white/10 rounded-full flex items-center justify-center mb-6 text-gray-500 dark:text-gray-400">
                <span class="material-symbols-rounded text-5xl md:text-7xl">playlist_play</span>
            </div>
            <h2 class="text-xl md:text-4xl font-black text-gray-900 dark:text-white mb-2 md:mb-4 tracking-tight">No videos in {{ $playlist->name }} yet</h2>
            <p class="text-[11px] md:text-lg text-gray-500 dark:text-gray-400 max-w-[250px] md:max-w-md mx-auto font-medium leading-snug md:leading-relaxed">
                Videos you save for this playlist will appear here. Start curating your perfect collection!
            </p>
            <a href="{{ route('home') }}" class="mt-8 md:mt-10 px-6 py-3 md:px-8 md:py-4 bg-gray-900 dark:bg-white text-white dark:text-black rounded-full font-black text-[10px] md:text-sm uppercase tracking-widest hover:scale-105 active:scale-95 transition-all flex items-center gap-2 md:gap-3 shadow-lg md:shadow-xl shadow-black/10 dark:shadow-none">
                <span class="material-symbols-rounded text-base md:text-xl">explore</span>
                Explore Videos
            </a>
        </div>
        @endif

        <!-- Native Share Modal (Consistent with Player) -->
        <div x-show="shareOpen" 
             class="fixed inset-0 z-[300000] flex items-center justify-center lg:p-4" 
             x-cloak
             @keydown.escape.window="shareOpen = false">
            
            <div x-show="shareOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-black/60 backdrop-blur-md" 
                 @click="shareOpen = false"></div>

            <div x-show="shareOpen" 
                 x-transition:enter="ease-out duration-500 transform" 
                 x-transition:enter-start="translate-y-full lg:translate-y-0 lg:scale-95" 
                 x-transition:enter-end="translate-y-0 lg:scale-100" 
                 x-transition:leave="ease-in duration-300 transform" 
                 x-transition:leave-start="translate-y-0 lg:scale-100" 
                 x-transition:leave-end="translate-y-full lg:translate-y-0 lg:scale-95" 
                 class="w-full fixed bottom-0 lg:relative lg:bottom-auto lg:max-w-md bg-white dark:bg-[#1A1A1A] rounded-t-[2rem] lg:rounded-[2.5rem] shadow-2xl overflow-hidden transition-all duration-500">
                
                <div class="p-6">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-widest ">Share</h3>
                        <button @click="shareOpen = false" class="w-10 h-10 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center text-gray-400">
                            <span class="material-symbols-rounded">close</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-4 sm:grid-cols-5 gap-4 mb-8">
                        @php
                            $shareLinks = [
                                ['name' => 'WhatsApp', 'icon' => 'fa-whatsapp', 'color' => '#25D366', 'url' => 'https://wa.me/?text='],
                                ['name' => 'Facebook', 'icon' => 'fa-facebook-f', 'color' => '#1877F2', 'url' => 'https://www.facebook.com/sharer/sharer.php?u='],
                                ['name' => 'Twitter', 'icon' => 'fa-x-twitter', 'color' => '#000000', 'url' => 'https://twitter.com/intent/tweet?url='],
                                ['name' => 'Email', 'icon' => 'fa-envelope', 'color' => '#EA4335', 'url' => 'mailto:?body='],
                                ['name' => 'Reddit', 'icon' => 'fa-reddit-alien', 'color' => '#FF4500', 'url' => 'https://www.reddit.com/submit?url='],
                            ];
                        @endphp
                        @foreach($shareLinks as $link)
                            <a href="{{ $link['url'] . urlencode(url()->current()) }}" target="_blank" class="flex flex-col items-center gap-2 group">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center text-white shadow-lg transition-transform group-active:scale-90" style="background-color: {{ $link['color'] }}">
                                    <i class="fab {{ $link['icon'] }} text-xl"></i>
                                </div>
                                <span class="text-[10px] font-black text-gray-500 uppercase tracking-tighter">{{ $link['name'] }}</span>
                            </a>
                        @endforeach
                    </div>

                    <div class="bg-gray-50 dark:bg-black/40 border border-gray-100 dark:border-white/5 rounded-2xl p-2 flex items-center gap-3">
                        <input type="text" id="share-url-playlist" readonly value="{{ url()->current() }}" class="flex-1 bg-transparent border-0 focus:ring-0 text-sm text-gray-600 dark:text-gray-300 font-medium px-2">
                        <button onclick="copyToClipboard('{{ url()->current() }}')" class="bg-red-600 text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest active:scale-95 transition-all">Copy</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(() => {
                    Swal.fire({
                        toast: true,
                        position: 'bottom',
                        showConfirmButton: false,
                        timer: 3000,
                        icon: 'success',
                        title: 'Link copied to clipboard',
                        background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                    });
                });
            }
        </script>
    </div>
</x-app-layout>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    .text-gradient-orange {
        background: linear-gradient(to right, #ff571a, #ef4444);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Page-scoped icon visibility fix (this page only).
       The layout globally forces `.material-symbols-rounded` to `color: inherit`,
       which beats Tailwind's text-color utilities and leaves icons dark-on-dark
       in dark mode. */
    html:not(.dark) #playlist-show span.material-symbols-rounded.text-purple-500 { color: #a855f7 !important; }
    html:not(.dark) #playlist-show span.material-symbols-rounded.text-white { color: #ffffff !important; }
    .dark #playlist-show span.material-symbols-rounded.text-purple-500 { color: #ffffff !important; }
    .dark #playlist-show span.material-symbols-rounded.text-white { color: #ffffff !important; }
</style>


