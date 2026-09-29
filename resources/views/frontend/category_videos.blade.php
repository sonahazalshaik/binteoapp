<x-app-layout>
    <div class="py-12 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="relative group py-2">
                <!-- Category Container -->
                <div class="flex items-center gap-3 overflow-x-auto pb-4 category-scrollbar select-none cursor-grab active:cursor-grabbing" id="category-container">
                    <a href="{{ route('home') }}" draggable="false" class="px-6 py-2.5 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all shrink-0 bg-gray-100 dark:bg-white/5 text-gray-500 hover:text-gray-900 dark:hover:text-white">All</a>
                    @foreach($categories as $cat)
                        <a href="{{ route('category.show', $cat->slug) }}" draggable="false" {!! strtolower(request()->path()) === 'category/' . strtolower($cat->slug) ? 'id="active-category"' : '' !!} class="px-6 py-2.5 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all shrink-0 {{ strtolower(request()->path()) === 'category/' . strtolower($cat->slug) ? 'bg-[#ff571a] text-white shadow-lg shadow-[#ff571a]/20' : 'bg-gray-100 dark:bg-white/5 text-gray-500 hover:text-gray-900 dark:hover:text-white' }}">{{ $cat->name }}</a>
                    @endforeach
                </div>
                <!-- Scrollbar Track & Thumb -->
                <div id="category-scrollbar-track" class="w-full h-1 bg-transparent dark:bg-transparent rounded-full relative cursor-pointer hover:h-1.5 transition-all mt-3">
                    <div id="category-scrollbar-thumb" class="absolute top-0 bottom-0 h-full bg-[#ff571a] rounded-full shadow-sm shadow-[#ff571a]/40 cursor-grab active:cursor-grabbing hover:bg-orange-600 transition-colors" style="width: 20%; transform: translateX(0px);"></div>
                </div>
            </div>
            <script>
                (function() {
                    const container = document.getElementById('category-container');
                    const track = document.getElementById('category-scrollbar-track');
                    const thumb = document.getElementById('category-scrollbar-thumb');
                    const activeItem = document.getElementById('active-category');

                    if (!container || !track || !thumb) return;

                    function getThumbWidth(trackWidth, clientWidth, scrollWidth) {
                        const ratio = clientWidth / scrollWidth;
                        return Math.max(45, Math.round(trackWidth * ratio * 0.65));
                    }

                    function updateScrollbar() {
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

                    window.addEventListener('resize', updateScrollbar);
                    window.addEventListener('load', updateScrollbar);
                    setTimeout(updateScrollbar, 200);
                })();
            </script>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php
                $categoryIcons = [
                    'music' => 'music_note',
                    'gaming' => 'sports_esports',
                    'movie' => 'movie_filter',
                    'films' => 'movie_filter',
                    'entertainment' => 'celebration',
                    'education' => 'school',
                    'news' => 'newspaper',
                    'tech' => 'devices',
                    'sports' => 'sports_soccer',
                    'vlog' => 'video_camera_back',
                    'cars' => 'directions_car',
                    'comedy' => 'theater_comedy',
                    'cooking' => 'restaurant',
                    'fashion' => 'checkroom',
                    'film' => 'movie_creation',
                    'food' => 'ramen_dining',
                    'kids' => 'child_care',
                    'lifestyle' => 'self_improvement',
                    'live' => 'live_tv',
                    'people' => 'groups',
                    'pets' => 'pets',
                    'science' => 'science',
                    'talent' => 'star',
                    'trending' => 'trending_up'
                ];
                $slug = strtolower($category->slug);
                $catIcon = 'category';
                foreach($categoryIcons as $key => $val) {
                    if(str_contains($slug, $key)) { 
                        $catIcon = $val; 
                        break; 
                    }
                }
                $catIcon = $category->icon ?: $catIcon;
            @endphp
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                <div class="flex items-center gap-4 md:gap-6">
                    <div class="w-12 h-12 md:w-16 md:h-16 rounded-2xl md:rounded-[2rem] bg-red-600 text-white flex items-center justify-center shadow-xl shadow-red-500/20 shrink-0">
                        <span class="material-symbols-rounded text-2xl md:text-3xl">{{ $catIcon }}</span>
                    </div>
                    <div>
                        <h1 class="text-xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ __($pageTitle) }}</h1>
                        <p class="text-[9px] md:text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mt-1 md:mt-2">Discovering content in {{ $category->name }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="px-6 py-3 bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 rounded-2xl">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ number_format($videos->total()) }} Videos @if(isset($reels))· {{ $reels->count() }} Reels @endif</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-blue-600/10 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                    <span class="material-symbols-rounded text-xl">movie</span>
                </div>
                <h2 class="text-xl md:text-2xl font-black text-gray-900 dark:text-white tracking-tight">Videos</h2>
            </div>

            @if($videos->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" id="video-grid">
                @foreach($videos as $video)
                @if(!$video->slug) @continue @endif
                    @include('frontend.partials.video_card', ['video' => $video])
                @endforeach
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

            @else
            <div class="py-12 flex flex-col items-center justify-center text-center bg-gray-50 dark:bg-white/[0.02] rounded-[2rem] border-2 border-dashed border-gray-200 dark:border-white/5 max-w-3xl mx-auto w-full">
                <div class="w-16 h-16 bg-gray-100 dark:bg-white/5 rounded-full flex items-center justify-center mb-4 text-gray-300 dark:text-white">
                    <span class="material-symbols-rounded text-3xl">video_library</span>
                </div>
                <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">No videos here yet</h3>
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-1">It seems this category is currently empty. Check back specifically later for new uploads!</p>
            </div>
            @endif

            @if(isset($reels) && $reels->count() > 0)
            <div class="mb-16">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-tr from-rose-500 via-[#ff571a] to-amber-500 rounded-xl md:rounded-2xl flex items-center justify-center text-white shadow-xl shadow-[#ff571a]/25">
                        <span class="material-symbols-rounded text-xl md:text-2xl">slow_motion_video</span>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-gray-900 dark:text-white tracking-tight">Reels</h2>
                        <p class="text-[9px] md:text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mt-0.5">Short content in {{ $category->name }}</p>
                    </div>
                </div>

                <div class="relative">
                    <div class="flex gap-4 md:gap-5 overflow-x-auto category-scrollbar pb-6 scroll-smooth" id="reels-carousel">
                        @foreach($reels as $reel)
                        <a href="{{ route('reels.index', ['reel' => $reel->slug]) }}" 
                           class="group relative flex-shrink-0 w-[160px] sm:w-[180px] md:w-[200px] aspect-[9/16] rounded-[1.5rem] overflow-hidden shadow-xl shadow-black/10 dark:shadow-black/40 hover:shadow-2xl hover:shadow-[#ff571a]/20 transition-all duration-500 hover:scale-[1.03] cursor-pointer border-2 border-white/10 dark:border-white/5"
                           onmouseenter="const v = this.querySelector('video'); if(v) v.play();" 
                           onmouseleave="const v = this.querySelector('video'); if(v) { v.pause(); v.currentTime = 0; }">
                            
                            <video src="{{ $reel->getVideoUrl() }}" 
                                   class="absolute inset-0 w-full h-full object-cover z-0" 
                                   muted loop playsinline preload="metadata"></video>

                            <img src="{{ $reel->getThumbnailUrl() }}" 
                                 class="absolute inset-0 w-full h-full object-cover z-10 transition-opacity duration-500 group-hover:opacity-0"
                                 onerror="this.src='{{ $reel->getPreviewUrl() }}'; this.onerror=null;">

                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent z-20"></div>
                            
                            <div class="absolute bottom-0 left-0 right-0 z-30 p-4">
                                <h3 class="text-white text-[11px] font-bold leading-tight line-clamp-2 drop-shadow-lg">{{ $reel->title }}</h3>
                                <div class="flex items-center gap-2 mt-2">
                                    <div class="w-5 h-5 rounded-full border border-white/40 overflow-hidden bg-gray-800">
                                        @if($reel->user->image)
                                            <img src="{{ getImage(getFilePath('userProfile') . '/' . $reel->user->image) }}" class="w-full h-full object-cover">
                                        @elseif($reel->user->channel && $reel->user->channel->avatar)
                                            <img src="{{ getImage(getFilePath('channelAvatar') . '/' . $reel->user->channel->avatar) }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <span class="text-white/80 text-[9px] font-bold truncate">{{ $reel->user->channel->name ?? $reel->user->name }}</span>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <div class="mb-16">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-gray-100 dark:bg-white/10 rounded-xl md:rounded-2xl flex items-center justify-center text-gray-400 dark:text-white">
                        <span class="material-symbols-rounded text-xl md:text-2xl">slow_motion_video</span>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-gray-900 dark:text-white tracking-tight">Reels</h2>
                        <p class="text-[9px] md:text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mt-0.5">Short content in {{ $category->name }}</p>
                    </div>
                </div>
                <div class="py-12 flex flex-col items-center justify-center text-center bg-gray-50 dark:bg-white/[0.02] rounded-[2rem] border-2 border-dashed border-gray-200 dark:border-white/5 max-w-3xl mx-auto w-full">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-white/5 rounded-full flex items-center justify-center mb-4 text-gray-300 dark:text-white">
                        <span class="material-symbols-rounded text-3xl">video_library</span>
                    </div>
                    <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">No reels found</h3>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-1">Be the first to upload a reel in this category!</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    @push('style')
    <style>
        .category-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .category-scrollbar::-webkit-scrollbar {
            display: none;
        }

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
            const categoryId = {{ $category->id }};
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

                        fetch("{{ route('video.get') }}?page=" + currentPage + "&category_id=" + categoryId)
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
