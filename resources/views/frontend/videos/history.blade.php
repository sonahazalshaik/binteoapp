<x-app-layout>
    <!-- Swiper.js for premium mobile interactions -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <div class="min-h-screen bg-white dark:bg-[#0F0F0F] text-gray-900 dark:text-white transition-colors duration-500 selection:bg-red-600 selection:text-white">
        
        <div class="max-w-[1400px] mx-auto px-4 lg:px-12 pb-40">
            
            <!-- Profile Hub -->
            <div class="py-8 md:py-14 flex flex-col md:flex-row items-center md:items-start gap-6 md:gap-10">
                <div class="flex-shrink-0">
                    <div class="w-24 h-24 md:w-36 md:h-36 rounded-full overflow-hidden border-4 md:border-[6px] border-white dark:border-white/10 shadow-2xl bg-gray-50 dark:bg-white/5">
                        <img src="{{ auth()->user()->image ? getImage(getFilePath('userProfile') . '/' . auth()->user()->image) : asset('assets/images/avatar.png') }}" 
                             class="w-full h-full object-cover" 
                             onerror="this.src='{{ asset('assets/images/avatar.png') }}'">
                    </div>
                </div>
                <div class="flex-grow text-center md:text-left pt-1">
                    <h1 class="text-2xl md:text-5xl font-black tracking-tighter mb-3 leading-none uppercase">{{ auth()->user()->name }}</h1>
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-x-4 gap-y-2 text-xs md:text-base font-bold text-gray-400">
                        <span class="text-red-600">@ {{ auth()->user()->username }}</span>
                        <a href="{{ auth()->user()->channel ? route('channels.show', auth()->user()->channel->slug) : '#' }}" class="relative z-10 flex items-center gap-1 hover:text-gray-900 dark:hover:text-white transition-all cursor-pointer">
                            <span>View channel</span>
                            <span class="material-symbols-rounded text-lg">chevron_right</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content Activity Streams -->
            <div class="space-y-16">
                
                <!-- 1. WATCH HISTORY -->
                @if($videos->count() > 0)
                <section x-data="{ 
                    searchQuery: '', 
                    searchOpen: false,
                    videos: @js($videos->filter(fn($v) => $v->slug)->map(fn($v) => ['title' => strtolower($v->title)])->values()),
                    get hasMatches() {
                        if (this.searchQuery === '') return this.videos.length > 0;
                        const query = this.searchQuery.toLowerCase();
                        return this.videos.some(v => v.title.includes(query));
                    }
                }">
                    <div class="flex items-center justify-between mb-4">
                        <a href="{{ route('history') }}" class="flex items-center gap-1 active:scale-95 transition-transform shrink-0">
                            <h2 class="text-lg md:text-xl font-bold tracking-tight text-gray-900 dark:text-white">History</h2>
                            <span class="material-symbols-rounded text-xl text-gray-400 hidden sm:block">chevron_right</span>
                        </a>
                        
                        <div class="flex items-center gap-2">
                            <!-- Desktop Search -->
                            <div class="hidden md:block relative">
                                <span class="material-symbols-rounded absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px] pointer-events-none">search</span>
                                <input type="text" x-model="searchQuery" placeholder="Search history..." class="w-48 lg:w-64 bg-gray-100 dark:bg-white/5 border border-transparent focus:border-gray-200 dark:focus:border-white/10 rounded-full pl-9 pr-8 py-1.5 text-[13px] font-medium outline-none focus:ring-0 dark:text-white transition-all">
                                <button x-show="searchQuery !== ''" @click="searchQuery = ''" class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors" x-cloak>
                                    <span class="material-symbols-rounded text-[18px]">close</span>
                                </button>
                            </div>

                            <!-- Mobile Search Toggle -->
                            <button type="button" @click="searchOpen = !searchOpen; if(searchOpen) $nextTick(() => $refs.mobileSearch.focus())" class="md:hidden w-8 h-8 rounded-full flex items-center justify-center bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-white/10 transition-colors">
                                <span class="material-symbols-rounded text-[18px]">search</span>
                            </button>
                            
                            <button type="button" onclick="confirmDelete('{{ route('history.clear') }}', 'Clear all watch history? This cannot be undone.', 'Yes, clear it!', 'POST')" class="px-3 py-1.5 rounded-full bg-red-500/10 text-xs font-semibold text-red-500 hover:bg-red-500 hover:text-white transition-colors shrink-0">Clear all</button>
                        </div>
                    </div>

                    <!-- Mobile Search Box -->
                    <div x-show="searchOpen" x-collapse class="md:hidden mb-4 relative">
                        <span class="material-symbols-rounded absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[20px] pointer-events-none">search</span>
                        <input x-ref="mobileSearch" type="text" x-model="searchQuery" placeholder="Search watch history..." class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl pl-10 pr-10 py-2.5 text-[13px] font-medium outline-none focus:ring-2 focus:ring-red-600 dark:text-white transition-all">
                        <button x-show="searchQuery !== ''" @click="searchQuery = ''; $refs.mobileSearch.focus()" class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors" x-cloak>
                            <span class="material-symbols-rounded text-[20px]">close</span>
                        </button>
                    </div>
                    
                    <!-- List Layout -->
                    <div class="flex flex-col gap-1">
                        @foreach($videos as $index => $video)
                            @if(!$video->slug) @continue @endif
                            <div x-show="searchQuery === '' || '{{ strtolower(addslashes($video->title)) }}'.includes(searchQuery.toLowerCase())">
                                <x-video-list-card :video="$video" :index="$index" :deleteRoute="route('history.remove', $video->id)" :deleteMethod="'POST'" />
                            </div>
                        @endforeach
                    </div>

                    <!-- Empty State -->
                    <div x-show="!hasMatches" class="py-12 text-center flex flex-col items-center justify-center" x-cloak>
                        <span class="material-symbols-rounded text-6xl text-gray-300 dark:text-white/10 mb-4">search_off</span>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">No matches found</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Try adjusting your search to find what you're looking for.</p>
                    </div>
                </section>
                @endif

                <!-- 1.5 REELS HISTORY -->
                @if(isset($reels) && $reels->count() > 0)
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <a href="{{ route('history') }}#reels" class="flex items-center gap-1 active:scale-95 transition-transform">
                            <h2 class="text-lg md:text-xl font-bold tracking-tight text-gray-900 dark:text-white">Reels History</h2>
                            <span class="material-symbols-rounded text-xl text-gray-400">chevron_right</span>
                        </a>
                    </div>
                    
                    <div class="swiper activitySwiper">
                        <div class="swiper-wrapper flex items-start">
                            @foreach($reels as $reel)
                                @if(!$reel->slug) @continue @endif
                                <div class="swiper-slide !w-[45%] sm:!w-[30%] md:!w-[220px]">
                                    <div id="item-reel-{{ $reel->id }}" @php
                                            $isOptimizing = ($reel->isBunnyReel() && !in_array($reel->bunny_status, ['ready', 'error', 'failed'])) || (!$reel->isBunnyReel() && $reel->status === 'processing');
                                         @endphp
                                         x-data="{ 
                                            progress: 0, 
                                            isOptimizing: {{ $isOptimizing ? 'true' : 'false' }},
                                            init() { if(this.isOptimizing) this.pollStatus(); },
                                            async pollStatus() {
                                                try {
                                                    const res = await fetch('{{ route("reels.check_status", $reel->slug ?? "missing-slug") }}');
                                                    const data = await res.json();
                                                    if(data.success) {
                                                        this.progress = data.encode_progress || 0;
                                                        if(data.encode_progress >= 100 || data.bunny_status === 'ready') window.location.reload();
                                                        else setTimeout(() => this.pollStatus(), 5000);
                                                    }
                                                } catch(e) { setTimeout(() => this.pollStatus(), 10000); }
                                            }
                                         }"
                                         class="group block relative aspect-[9/16] rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl hover:shadow-orange-500/20 transition-all duration-500">
                                        
                                        <a href="{{ route('reels.show', $reel->slug ?? "missing-slug") }}" class="absolute inset-0">
                                            <img src="{{ $reel->getThumbnailUrl() }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" onerror="this.src='{{ asset('assets/images/default.png') }}'">
                                            
                                            <!-- Branded Dynamic Loading State -->
                                            <template x-if="isOptimizing">
                                                <div class="absolute inset-0 bg-black/60 backdrop-blur-md flex flex-col items-center justify-center z-[41]">
                                                    <div class="relative w-10 h-10 mb-2">
                                                        <svg class="w-full h-full transform -rotate-90">
                                                            <circle cx="20" cy="20" r="16" stroke="currentColor" stroke-width="3" fill="transparent" class="text-white/10" />
                                                            <circle cx="20" cy="20" r="16" stroke="currentColor" stroke-width="3" fill="transparent" class="text-rose-500 transition-all duration-500" stroke-dasharray="100.5" :stroke-dashoffset="100.5 - (100.5 * progress / 100)" />
                                                        </svg>
                                                        <div class="absolute inset-0 flex items-center justify-center"></div>
                                                    </div>
                                                    <span class="text-[6px] font-black text-white uppercase tracking-widest animate-pulse">loading awesome...</span>
                                                </div>
                                            </template>

                                            <!-- Age Restriction Overlay (Reels) -->
                                            @if($reel->is_age_restricted && !auth()->check())
                                                <div class="absolute inset-0 z-40 bg-black/60 backdrop-blur-md flex flex-col items-center justify-center text-center p-4">
                                                    <div class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center mb-2 shadow-xl shadow-rose-500/40">
                                                        <span class="material-symbols-rounded text-xl font-black">explicit</span>
                                                    </div>
                                                    <p class="text-white font-black text-[8px] uppercase tracking-widest">18+</p>
                                                </div>
                                            @endif

                                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                                        </a>

                                        <div class="absolute bottom-3 left-3 right-3 pointer-events-none">
                                            <p class="text-[10px] font-black text-white truncate mb-1">{{ $reel->title }}</p>
                                            <div class="flex items-center gap-2">
                                                <div class="w-4 h-4 rounded-full overflow-hidden border border-white/20">
                                                    <img src="{{ $reel->user->image ? getImage(getFilePath('userProfile') . '/' . $reel->user->image) : asset('assets/images/avatar.png') }}" class="w-full h-full object-cover">
                                                </div>
                                                <span class="text-[8px] font-bold text-gray-300 uppercase tracking-widest">{{ $reel->user->username }}</span>
                                            </div>
                                        </div>

                                        <div class="absolute top-2 right-2 flex flex-col gap-2 z-30">
                                            <button onclick="window.openVideoOptions('{{ $reel->id }}', '{{ addslashes($reel->title) }}', 'reel', {{ $reel->isLikedBy(auth()->user()) ? 'true' : 'false' }}, {{ $reel->isInWatchLater(auth()->user()) ? 'true' : 'false' }}, {{ $reel->isInAnyPlaylist(auth()->user()) ? 'true' : 'false' }}, @json($reel->getPlaylistMembershipIds(auth()->user() ?? null)), {{ $reel->user_id }})" 
                                                    class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-black/60 backdrop-blur-md text-white flex items-center justify-center transition-all hover:bg-orange-500 shadow-lg">
                                                <span class="material-symbols-rounded text-[16px] sm:text-[18px]">more_vert</span>
                                            </button>

                                            <button onclick="asyncRemoveItem(this, '{{ route('history.remove.reel', $reel->id) }}', 'POST')" 
                                                    class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-black/60 backdrop-blur-md text-white flex items-center justify-center transition-all hover:bg-red-600 shadow-lg">
                                                <span class="material-symbols-rounded text-[14px] sm:text-[16px]">close</span>
                                            </button>
                                        </div>

                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/20 backdrop-blur-[2px] pointer-events-none">
                                            <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30">
                                                <span class="material-symbols-rounded text-white text-2xl material-symbols-filled">play_arrow</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
                @endif

                <!-- 2. LIKED VIDEOS -->
                @if($likedVideos->count() > 0)
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <a href="{{ route('liked-videos') }}" class="flex items-center gap-1 active:scale-95 transition-transform">
                            <h2 class="text-lg md:text-xl font-bold tracking-tight text-gray-900 dark:text-white">Liked videos</h2>
                            <span class="material-symbols-rounded text-xl text-gray-400">chevron_right</span>
                        </a>
                        <a href="{{ route('liked-videos') }}" class="px-3 py-1.5 rounded-full bg-gray-100 dark:bg-white/10 text-xs font-semibold text-gray-900 dark:text-white hover:bg-gray-200 dark:hover:bg-white/20 transition-colors">View all</a>
                    </div>
                    
                    <div class="flex flex-col gap-1">
                        @foreach($likedVideos as $index => $video)
                            <x-video-list-card :video="$video" :index="$index" />
                        @endforeach
                    </div>
                </section>
                @endif

                <!-- 3. COMMENTED VIDEOS -->
                @if($commentedVideos->count() > 0)
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <a href="{{ route('history') }}#comments" class="flex items-center gap-1 active:scale-95 transition-transform">
                            <h2 class="text-lg md:text-xl font-bold tracking-tight text-gray-900 dark:text-white">Commented videos</h2>
                            <span class="material-symbols-rounded text-xl text-gray-400">chevron_right</span>
                        </a>
                    </div>
                    
                    <div class="flex flex-col gap-1">
                        @foreach($commentedVideos as $index => $video)
                            <x-video-list-card :video="$video" :index="$index" />
                        @endforeach
                    </div>
                </section>
                @endif

                <!-- 4. PLAYLISTS -->
                @if($playlists->count() > 0)
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <a href="{{ route('playlists.index') }}" class="flex items-center gap-1 active:scale-95 transition-transform">
                            <h2 class="text-lg md:text-xl font-bold tracking-tight text-gray-900 dark:text-white">Playlists</h2>
                            <span class="material-symbols-rounded text-xl text-gray-400">chevron_right</span>
                        </a>
                        <a href="{{ route('playlists.index') }}" class="w-8 h-8 rounded-full bg-gray-100 dark:bg-white/10 flex items-center justify-center text-gray-900 dark:text-white hover:bg-gray-200 dark:hover:bg-white/20 transition-colors" title="Create Playlist">
                            <span class="material-symbols-rounded text-[18px]">add</span>
                        </a>
                    </div>
                    
                    <div class="flex flex-col gap-1">
                        @foreach($playlists as $playlist)
                            <div class="group flex items-center gap-2 sm:gap-4 p-2 sm:p-3 rounded-2xl hover:bg-gray-100 dark:hover:bg-white/5 transition-all duration-300 relative">
                                <div class="relative w-28 sm:w-44 aspect-video rounded-xl overflow-hidden flex-shrink-0 shadow-sm">
                                    @php
                                        $firstVideo = $playlist->videos->first() ?? $playlist->reels->first();
                                    @endphp
                                    <img src="{{ $firstVideo ? $firstVideo->getThumbnailUrl() : asset('assets/images/default.png') }}" 
                                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000" 
                                         onerror="this.src='{{ asset('assets/images/default.png') }}'">

                                    <div class="absolute inset-y-0 right-0 w-1/3 bg-black/60 backdrop-blur-md flex flex-col items-center justify-center text-white">
                                        <span class="material-symbols-rounded text-xl sm:text-2xl">playlist_play</span>
                                        <span class="text-[8px] sm:text-[10px] font-black mt-1 tracking-widest">{{ $playlist->videos_count + ($playlist->reels_count ?? 0) }}</span>
                                    </div>
                                </div>

                                <div class="flex-grow min-w-0 flex flex-col justify-center">
                                    <h3 class="text-xs sm:text-base font-black text-gray-900 dark:text-white line-clamp-2 leading-tight mb-1 group-hover:text-red-600 transition-colors">
                                        <a href="{{ route('playlists.show', ['username' => optional($playlist->user->channel)->slug ?? $playlist->user->username ?? 'user', 'playlist' => $playlist->slug]) }}" class="absolute inset-0 z-10"></a>
                                        {{ $playlist->name }}
                                    </h3>
                                    <div class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs text-gray-500 dark:text-gray-400">
                                        <span class="font-bold uppercase tracking-widest">Library</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>

<script>
    // History page: instant per-entry removal (overrides the global
    // form-submit confirmDelete for this page only). Clear-all keeps
    // the full-reload flow since it re-renders empty states.
    function confirmDelete(url, message = "You won't be able to revert this!", confirmText = 'Yes, delete it!', method = 'DELETE') {
        const srcEl = (typeof event !== 'undefined' && event && event.target) ? event.target : document.activeElement;
        const slide = srcEl && srcEl.closest ? srcEl.closest('.swiper-slide') : null;

        // Bulk clear -> original full-reload behaviour.
        if (url.indexOf('/history/clear') !== -1) {
            return Swal.fire({
                title: 'Are you sure?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: confirmText,
                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            }).then((result) => {
                if (!result.isConfirmed) return;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
            });
        }

        return Swal.fire({
            title: 'Are you sure?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: confirmText,
            background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
        }).then((result) => {
            if (!result.isConfirmed) return;

            // Instant: collapse the card in the same frame.
            let wrapper = null;
            if (slide) {
                wrapper = slide.closest('.swiper-wrapper');
                slide.style.transition = 'all 0.3s ease';
                slide.style.opacity = '0';
                slide.style.transform = 'scale(0.95)';
                setTimeout(() => slide.remove(), 300);
            }
            Swal.fire({
                toast: true, position: 'top-end', showConfirmButton: false, timer: 2000,
                icon: 'success', title: 'Removed from history',
                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            });

            // Sync in the background; restore on failure.
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(async (res) => {
                if (!res.ok) throw new Error('Server error');
                await res.json().catch(() => ({}));
                if (wrapper && wrapper.querySelectorAll('.swiper-slide').length === 0) {
                    window.location.reload();
                }
            }).catch((e) => {
                console.error(e);
                Swal.fire({
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 2500,
                    icon: 'error', title: 'Could not sync removal — refreshing',
                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                });
                window.location.reload();
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const swipers = new Swiper('.activitySwiper', {
            slidesPerView: 2.1,
            spaceBetween: 12,
            freeMode: true,
            grabCursor: true,
            breakpoints: {
                768: {
                    slidesPerView: 'auto',
                    spaceBetween: 20
                }
            }
        });
    });
</script>

<style>
    .swiper { width: 100%; height: 100%; margin: -20px -10px; padding: 20px 10px; overflow: hidden; }
    .swiper-slide { height: auto; display: flex; align-items: stretch; }
    .swiper-slide > * { width: 100%; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
