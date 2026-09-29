@use('App\Constants\Status')
<x-app-layout>
    <div x-data="{ 
        shareOpen: false,
        menuOpen: false,
        removing: {},
        searchQuery: '',
        searchOpen: false,
        shareVideo() {
            if (navigator.share) {
                navigator.share({
                    title: 'Liked videos',
                    text: 'Check out these liked videos on {{ $general->site_name }}',
                    url: window.location.href,
                }).catch(console.error);
            } else {
                this.shareOpen = true;
            }
        },
        async removeItem(id, type, ev = null) {
            const key = type + '-' + id;
            // Idempotent DELETE endpoint: repeat clicks can never re-like.
            // Row is removed from the DOM instantly (optimistic) so rapid
            // clicks in the same second have nothing left to hit.
            if (this.removing[key]) return;
            this.removing[key] = true;
            const el = document.getElementById('item-' + type + '-' + id);
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
                        // Re-sequence serial numbers + header count so they
                        // always match the entries actually on screen.
                        const rows = document.querySelectorAll('[data-liked-row]');
                        rows.forEach((row, i) => {
                            const sno = row.querySelector('.sno-num');
                            if (sno) sno.textContent = i + 1;
                        });
                        const countEl = document.getElementById('liked-count');
                        if (countEl) countEl.textContent = rows.length + (rows.length === 1 ? ' video' : ' videos');
                        if (rows.length === 0) window.location.reload();
                    }, 300);
            }
            Swal.fire({
                toast: true, position: 'top-end', showConfirmButton: false, timer: 2000,
                icon: 'success', title: 'Removed from Liked videos',
                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            });
            try {
                const url = type === 'reel'
                    ? '{{ route('reels.unlike', '__ID__') }}'.replace('__ID__', id)
                    : '{{ route('videos.unlike', '__ID__') }}'.replace('__ID__', id);

                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
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
    }" id="liked-videos" class="min-h-screen bg-[#FDFDFD] dark:bg-[#0F0F0F] transition-colors duration-500 pb-20">
        <!-- Native Android Top Bar (Mobile) -->
        <div class="sticky top-0 z-40 bg-white/90 dark:bg-[#0F0F0F]/90 backdrop-blur-xl border-b border-gray-100 dark:border-white/5 lg:hidden">
            <div class="px-4 h-14 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="text-gray-600 dark:text-gray-400">
                        <span class="material-symbols-rounded">arrow_back</span>
                    </a>
                    <span class="text-base font-bold dark:text-white">Liked videos</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 relative">
                    <button @click="searchOpen = !searchOpen; if(searchOpen) $nextTick(() => $refs.mobileSearchInput.focus())" class="w-10 h-10 flex items-center justify-center transition-colors hover:bg-gray-100 dark:hover:bg-white/10 rounded-full" :class="searchOpen ? 'bg-gray-100 dark:bg-white/10 text-red-600' : ''">
                        <span class="material-symbols-rounded">search</span>
                    </button>
                    <button @click="menuOpen = !menuOpen" @click.outside="menuOpen = false" class="w-10 h-10 flex items-center justify-center">
                        <span class="material-symbols-rounded">more_vert</span>
                    </button>

                        <div x-show="menuOpen" @click.outside="menuOpen = false" x-transition.origin.top.right x-cloak
                         class="absolute right-0 top-12 z-50 w-56 bg-white dark:bg-[#1A1A1A] rounded-2xl shadow-2xl border border-gray-100 dark:border-white/5 p-1.5">
                        
                        @php
                            $firstItem = count($videos) > 0 ? $videos[0] : null;
                            $playRoute = '#';
                            if ($firstItem) {
                                $playRoute = $firstItem->type === 'video' 
                                    ? route('videos.show.virtual', ['virtualSlug' => 'liked', 'video' => $firstItem->slug]) 
                                    : route('reels.show', ['reel' => $firstItem->slug]);
                            }
                        @endphp
                        <a href="{{ $playRoute }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-all">
                            <span class="material-symbols-rounded">play_arrow</span>
                            Play all
                        </a>

                        @php
                            $shuffleItem = count($videos) > 0 ? $videos->random() : null;
                            $shuffleRoute = '#';
                            if ($shuffleItem) {
                                $shuffleRoute = $shuffleItem->type === 'video'
                                    ? route('videos.show.virtual', ['virtualSlug' => 'liked', 'video' => $shuffleItem->slug])
                                    : route('reels.show', ['reel' => $shuffleItem->slug]);
                            }
                        @endphp
                        <a href="{{ $shuffleRoute }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-all">
                            <span class="material-symbols-rounded">shuffle</span>
                            Shuffle play
                        </a>

                        <button @click="shareVideo(); menuOpen = false" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-all">
                            <span class="material-symbols-rounded">share</span>
                            Share
                        </button>
                    </div>
                </div>
            </div>
            
            <div x-show="searchOpen" x-transition x-cloak class="px-4 pb-3">
                <div class="relative flex items-center">
                    <span class="material-symbols-rounded absolute left-3 text-gray-400 text-[20px]">search</span>
                    <input x-ref="mobileSearchInput" x-model="searchQuery" type="text" placeholder="Search liked videos..." class="w-full bg-gray-100/80 dark:bg-white/5 border border-transparent focus:border-red-500/30 focus:ring-4 focus:ring-red-500/10 rounded-xl pl-10 pr-10 py-2.5 text-sm font-medium outline-none dark:text-white transition-all shadow-inner">
                    <button x-show="searchQuery !== ''" @click="searchQuery = ''; $refs.mobileSearchInput.focus()" class="absolute right-3 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                        <span class="material-symbols-rounded text-[20px]">close</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-10">
            @if(count($videos) > 0)
            <div class="flex flex-col lg:flex-row gap-6 lg:gap-10">
                
                <!-- Simple Playlist Info Section -->
                <div class="lg:w-72 flex-shrink-0">
                    <div class="lg:sticky lg:top-24">
                        <a href="{{ $playRoute }}" class="relative aspect-video lg:aspect-square rounded-2xl md:rounded-3xl overflow-hidden shadow-lg group mb-4 block">
                            @if(count($videos) > 0)
                                <img src="{{ $videos[0]->getThumbnailUrl() }}" class="w-full h-full object-cover" alt="Playlist Cover">
                                <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="material-symbols-rounded text-white text-5xl">play_arrow</span>
                                </div>
                            @else
                                <div class="w-full h-full bg-gray-100 dark:bg-white/5 flex items-center justify-center">
                                    <span class="material-symbols-rounded text-gray-300 dark:text-white/10 text-6xl">favorite</span>
                                </div>
                            @endif
                        </a>


                        <div class="px-2">
                            <h1 class="text-xl md:text-2xl font-black text-gray-900 dark:text-white mb-1.5 leading-tight">Liked videos</h1>
                            <div class="flex flex-col gap-1 text-[11px] md:text-xs text-gray-500 dark:text-gray-400 mb-6">
                                <span class="font-bold">{{ auth()->user()->fullname }}</span>
                                <div class="flex items-center gap-2">
                                    <span id="liked-count">{{ count($videos) }} videos</span>
                                    <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-white/10"></span>
                                    <span>Updated today</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 md:gap-3 mb-6 w-full">
                                <a href="{{ $playRoute }}" class="flex-1 h-10 md:h-12 rounded-full font-bold text-xs md:text-sm flex items-center justify-center gap-2 transition-transform {{ count($videos) > 0 ? 'bg-gray-900 dark:bg-white text-white dark:text-black active:scale-95' : 'bg-gray-200 dark:bg-white/5 text-gray-400 dark:text-gray-600 pointer-events-none' }}">
                                    <span class="material-symbols-rounded text-lg md:text-xl">play_arrow</span> Play
                                </a>

                                <a href="{{ $shuffleRoute }}" class="shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center transition-transform {{ count($videos) > 0 ? 'bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-white active:scale-95' : 'bg-transparent border border-gray-200 dark:border-white/5 text-gray-300 dark:text-gray-700 pointer-events-none' }}">
                                    <span class="material-symbols-rounded text-lg md:text-xl">shuffle</span>
                                </a>
                                
                                <button @click="shareVideo()" class="shrink-0 w-10 h-10 md:w-12 md:h-12 bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-white rounded-full flex items-center justify-center active:scale-95 transition-transform">
                                    <span class="material-symbols-rounded text-lg md:text-xl">share</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Video List Section -->
                <div class="flex-grow">
                    
                    
                        <!-- Local Search Bar -->
                        <div class="hidden lg:block mb-6 relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="material-symbols-rounded text-gray-400 text-lg">search</span>
                            </div>
                            <input x-model="searchQuery" type="text" placeholder="Search liked videos..." class="w-full bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl pl-12 pr-12 py-3.5 text-sm font-medium outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent dark:text-white transition-all shadow-sm">
                            <button x-show="searchQuery !== ''" @click="searchQuery = ''" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors" x-cloak>
                                <span class="material-symbols-rounded text-lg">close</span>
                            </button>
                        </div>
                        <div class="space-y-1">
                            @foreach($videos as $index => $video)
                                <div x-show="searchQuery === '' || '{{ strtolower(addslashes($video->title)) }}'.includes(searchQuery.toLowerCase())" id="item-{{ $video->type }}-{{ $video->id }}" data-video-id="{{ $video->id }}" data-video-type="{{ $video->type }}" data-liked-row class="group flex items-center gap-2 sm:gap-4 p-2 sm:p-3 rounded-2xl hover:bg-gray-100 dark:hover:bg-white/5 transition-all duration-300 relative">
                                    <div class="flex-shrink-0 w-4 sm:w-6 text-center text-xs font-bold text-gray-400 sno-num">
                                        {{ $index + 1 }}
                                    </div>
                                    
                                    <div class="relative w-28 sm:w-44 aspect-video rounded-xl overflow-hidden flex-shrink-0 shadow-sm">
                                        <img src="{{ $video->getThumbnailUrl() }}" class="w-full h-full object-cover {{ ($video->is_age_restricted && !auth()->check()) ? 'blur-xl' : '' }}" alt="{{ $video->title }}">

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
                                            <a href="{{ route('videos.show.virtual', ['virtualSlug' => 'liked', 'video' => $video->slug]) }}" class="absolute inset-0"></a>
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
                                            <div class="absolute top-1 left-1 px-1.5 py-0.5 bg-rose-600/90 backdrop-blur-md rounded text-[7px] font-black text-white uppercase tracking-tighter shadow-sm border border-rose-500/20">
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
                                                <a href="{{ route('videos.show.virtual', ['virtualSlug' => 'liked', 'video' => $video->slug]) }}">{{ $video->title }}</a>
                                            @endif
                                        </h3>
                                        <div class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs text-gray-500 dark:text-gray-400">
                                            <span class="truncate max-w-[80px] sm:max-w-none">{{ $video->user->fullname }}</span>
                                            <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-white/10 hidden sm:block"></span>
                                            <span class="hidden sm:block">{{ formatNumber($video->views_count) }} views</span>
                                        </div>
                                    </div>

                                    <div class="flex-shrink-0 flex items-center gap-0 sm:gap-1">
                                        <button @click="removeItem('{{ $video->id }}', '{{ $video->type }}', $event)" title="Remove from Liked videos" class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-gray-400 hover:text-red-600 active:scale-90 transition-all">
                                            <span class="material-symbols-rounded text-lg sm:text-2xl">delete</span>
                                        </button>
                                        <button onclick="window.openVideoOptions('{{ $video->id }}', '{{ addslashes($video->title) }}', '{{ $video->type }}', {{ $video->isLikedBy(auth()->user()) ? 'true' : 'false' }}, {{ $video->isInWatchLater(auth()->user()) ? 'true' : 'false' }}, {{ $video->isInAnyPlaylist(auth()->user()) ? 'true' : 'false' }}, @json($video->getPlaylistMembershipIds(auth()->user() ?? null)), {{ $video->user_id }})" 
                                                class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-gray-400 hover:text-gray-900 dark:hover:text-white active:scale-90 transition-transform">
                                            <span class="material-symbols-rounded text-lg sm:text-2xl">more_vert</span>
                                        </button>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                        </div>
                </div>
            </div>
            @else
            <!-- Simple Native Android Empty State -->
            <div class="flex flex-col items-center justify-center h-[calc(100vh-12rem)] md:min-h-[60vh] md:h-auto text-center px-4">
                <div class="w-24 h-24 md:w-32 md:h-32 bg-gray-200 dark:bg-white/10 rounded-full flex items-center justify-center mb-6 text-gray-500 dark:text-gray-400">
                    <span class="material-symbols-rounded text-5xl md:text-7xl">favorite</span>
                </div>
                <h2 class="text-xl md:text-3xl font-black text-gray-900 dark:text-white mb-1.5 md:mb-3 tracking-tight">No liked videos yet</h2>
                <p class="text-[11px] md:text-base text-gray-500 dark:text-gray-400 max-w-[250px] md:max-w-sm mx-auto font-medium leading-snug md:leading-relaxed">
                    Videos you like will appear here for you to watch again.
                </p>
                <a href="{{ route('home') }}" class="mt-6 md:mt-8 px-6 py-2.5 md:px-8 md:py-3.5 bg-gray-900 dark:bg-white text-white dark:text-black rounded-full font-black text-[10px] md:text-sm uppercase tracking-widest hover:scale-105 active:scale-95 transition-all flex items-center gap-1.5 md:gap-2 shadow-lg md:shadow-xl shadow-black/10 dark:shadow-none">
                    <span class="material-symbols-rounded text-base md:text-lg">explore</span>
                    Explore Videos
                </a>
            </div>
            @endif
        </div>
            </div>
        </div>

        <!-- Native Share Modal -->
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

        <style>
            /* Page-scoped icon visibility fix (this page only).
               The layout globally forces `.material-symbols-rounded` to `color: inherit`,
               which beats Tailwind's text-color utilities and leaves icons dark-on-dark
               in dark mode. Light mode restores originals; dark mode renders white. */
            html:not(.dark) #liked-videos span.material-symbols-rounded.text-gray-400 { color: #9ca3af !important; }
            html:not(.dark) #liked-videos span.material-symbols-rounded.text-gray-300 { color: #d1d5db !important; }
            html:not(.dark) #liked-videos span.material-symbols-rounded.text-white { color: #ffffff !important; }
            .dark #liked-videos span.material-symbols-rounded.text-gray-400 { color: rgba(255,255,255,0.7) !important; }
            .dark #liked-videos span.material-symbols-rounded.text-gray-300 { color: #ffffff !important; }
            .dark #liked-videos span.material-symbols-rounded.text-white { color: #ffffff !important; }
        </style>
    </div>
</x-app-layout>

