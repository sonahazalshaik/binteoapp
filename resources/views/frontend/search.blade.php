<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">
                Results for "{{ $searchQuery }}"
            </h1>
        </div>

        <!-- Channels Section -->
        @if($channels->count() > 0)
            <div class="space-y-6 mb-12">
                @foreach($channels as $channel)
                    <div class="flex flex-col md:flex-row items-center gap-8 p-8 bg-white dark:bg-white/[0.03] backdrop-blur-md rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-xl shadow-gray-200/20 dark:shadow-none hover:bg-gray-50 dark:hover:bg-white/[0.05] transition-all duration-300 group" 
                         x-data="{ 
                            subscribed: {{ auth()->check() ? ($channel->is_subscribed ? 'true' : 'false') : 'false' }},
                            subscribersCount: {{ $channel->subscribers_count }},
                            subDropdownOpen: false,
                            notificationPreference: '{{ auth()->check() ? (\App\Models\Subscription::where("user_id", auth()->id())->where("channel_id", $channel->id)->value("notification_preference") ?? "all") : "all" }}',
                            toggleSubscribe() {
                                @auth
                                    if ({{ auth()->id() }} === {{ $channel->user_id }}) {
                                        Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Cannot subscribe to your own channel', showConfirmButton: false, timer: 3000 });
                                        return;
                                    }
                                    
                                    if (this.subscribed) {
                                        this.subDropdownOpen = !this.subDropdownOpen;
                                        return;
                                    }

                                    this.performSubscribe();
                                @else
                                    window.location.href = '{{ route('login') }}';
                                @endauth
                            },
                             performSubscribe() {
                                 const prevSubscribed = this.subscribed;
                                 const prevCount = this.subscribersCount;
                                 
                                 this.subscribed = true;
                                 this.subscribersCount++;

                                 fetch('{{ route('channels.subscribe', $channel->id) }}', {
                                     method: 'POST',
                                     headers: { 
                                         'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                                         'Accept': 'application/json',
                                         'X-Requested-With': 'XMLHttpRequest',
                                         'Content-Type': 'application/json'
                                     }
                                 })
                                 .then(res => {
                                     if (!res.ok) throw new Error('Network response was not ok');
                                     return res.json();
                                 })
                                 .then(data => {
                                     if (data.error) throw new Error(data.error);
                                     this.subscribed = data.subscribed; 
                                     this.subscribersCount = data.subscribers_count;
                                     this.notificationPreference = data.notification_preference;
                                     
                                     if (this.subscribed) {
                                         this.subDropdownOpen = true;
                                     }
                                 })
                                 .catch(error => {
                                     console.error('Subscription failed:', error);
                                     this.subscribed = prevSubscribed;
                                     this.subscribersCount = prevCount;
                                     Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: error.message || 'Subscription failed', showConfirmButton: false, timer: 3000 });
                                 });
                             },
                            unsubscribe() {
                                Swal.fire({
                                    title: 'Unsubscribe?',
                                    text: 'Are you sure you want to unsubscribe?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Unsubscribe',
                                    confirmButtonColor: '#000000',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        fetch('{{ route('channels.subscribe', $channel->id) }}', {
                                            method: 'POST',
                                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                                        })
                                        .then(r => r.json())
                                        .then(d => { 
                                            this.subscribed = d.subscribed; 
                                            this.subscribersCount = d.subscribers_count;
                                        });
                                    }
                                });
                            },
                            setNotification(pref) {
                                this.notificationPreference = pref;
                                this.subDropdownOpen = false;
                                fetch('{{ route('channels.subscribe.preference', $channel->id) }}', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                    body: JSON.stringify({ preference: pref })
                                });
                            }
                         }">
                        <!-- Avatar -->
                        <a href="{{ route('channels.show', $channel) }}" class="shrink-0">
                            <div class="w-32 h-32 md:w-36 md:h-36 rounded-full overflow-hidden border-4 border-gray-100 dark:border-white/5 shadow-xl">
                                @if($channel->avatar)
                                    <img src="{{ getImage(getFilePath('channelAvatar') . '/' . $channel->avatar) }}" class="w-full h-full object-cover">
                                @else
                                    @php
                                        $words = explode(' ', trim($channel->name));
                                        $initials = count($words) >= 2 
                                            ? strtoupper(substr($words[0], 0, 1) . substr($words[count($words)-1], 0, 1))
                                            : strtoupper(substr($channel->name, 0, 1) . substr($channel->name, -1));
                                    @endphp
                                    <div class="w-full h-full gradient-orange flex items-center justify-center text-white text-4xl font-black">
                                        {{ $initials }}
                                    </div>
                                @endif
                            </div>
                        </a>

                        <!-- Info -->
                        <div class="flex-1 text-center md:text-left">
                            <a href="{{ route('channels.show', $channel) }}" class="block">
                                <h2 class="text-xl md:text-2xl font-black text-gray-900 dark:text-white hover:text-orange-500 transition-colors">{{ $channel->name }}</h2>
                                <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mt-1">
                                    @<span>{{ $channel->user->username }}</span> • <span>{{ formatNumber($channel->subscribers_count) }}</span> subscribers
                                </p>
                                <p class="text-sm text-gray-600 dark:text-[#AAAAAA] mt-3 line-clamp-2 leading-relaxed max-w-2xl">
                                    {{ $channel->description ?? 'No description available for this channel.' }}
                                </p>
                            </a>
                        </div>

                        <!-- Action -->
                        <div class="shrink-0 relative">
                             @if(auth()->id() !== $channel->user_id)
                                <div class="relative" @click.away="subDropdownOpen = false">
                                    <button 
                                        @click="toggleSubscribe" 
                                        :class="subscribed ? 'bg-gray-100 dark:bg-white/10 text-gray-900 dark:text-white pr-3' : 'bg-gray-900 dark:bg-white text-white dark:text-gray-900'" 
                                        class="px-6 py-2.5 rounded-full font-black text-[13px] shadow-lg transition-all active:scale-95 whitespace-nowrap flex items-center gap-2">
                                        
                                        <template x-if="subscribed">
                                            <div class="flex items-center gap-2">
                                                <span class="material-symbols-rounded text-xl" 
                                                      :class="{ 'material-symbols-filled': notificationPreference === 'all' }"
                                                      x-text="notificationPreference === 'all' ? 'notifications_active' : 'notifications_off'"></span>
                                                <span>Subscribed</span>
                                                <span class="material-symbols-rounded text-xl transition-transform" :class="subDropdownOpen ? 'rotate-180' : ''">keyboard_arrow_down</span>
                                            </div>
                                        </template>
                                        
                                        <template x-if="!subscribed">
                                            <span>Subscribe</span>
                                        </template>
                                    </button>

                                    <!-- Dropdown -->
                                    <div x-show="subDropdownOpen" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-[#282828] rounded-xl shadow-2xl py-2 z-[100] border border-gray-100 dark:border-white/10 overflow-hidden"
                                         style="display: none;">
                                        
                                        <button @click="setNotification('all')" class="w-full px-4 py-3 flex items-center gap-3 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors text-left">
                                            <span class="material-symbols-rounded text-xl" :class="notificationPreference === 'all' ? 'material-symbols-filled text-orange-600' : ''">notifications_active</span>
                                            <span class="text-sm font-bold">All</span>
                                        </button>

                                        <button @click="setNotification('none')" class="w-full px-4 py-3 flex items-center gap-3 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors text-left">
                                            <span class="material-symbols-rounded text-xl" :class="notificationPreference === 'none' ? 'material-symbols-filled' : ''">notifications_off</span>
                                            <span class="text-sm font-bold">None</span>
                                        </button>

                                        <div class="h-[1px] bg-gray-100 dark:bg-white/10 my-1"></div>

                                        <button @click="unsubscribe()" class="w-full px-4 py-3 flex items-center gap-3 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors text-red-600 text-left">
                                            <span class="material-symbols-rounded text-xl">person_remove</span>
                                            <span class="text-sm font-bold">Unsubscribe</span>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
                <div class="h-[1px] bg-gray-100 dark:bg-white/5 my-8"></div>
            </div>
        @endif

        <!-- Reels Section -->
        @if($reels->count() > 0)
            <div class="mb-16">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-orange-500/10 text-orange-500 flex items-center justify-center shadow-sm">
                            <span class="material-symbols-rounded text-xl">slow_motion_video</span>
                        </div>
                        <h2 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-wider">Reels</h2>
                    </div>
                    <a href="{{ route('reels.index', ['q' => $searchQuery]) }}" class="text-xs font-black text-orange-500 uppercase tracking-widest hover:text-orange-600 transition-all flex items-center gap-1 group">
                        View All
                        <span class="material-symbols-rounded text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    @foreach($reels as $reel)
                        @include('frontend.partials.reel_card', ['reel' => $reel])
                    @endforeach
                </div>
                <div class="h-[1px] bg-gray-100 dark:bg-white/5 my-12"></div>
            </div>
        @endif

        <!-- Videos Section -->
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center shadow-sm">
                <span class="material-symbols-rounded text-xl">movie</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-wider">Videos</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" id="video-grid">
            @forelse($videos as $video)
                @include('frontend.partials.video_card', ['video' => $video])
            @empty
                <div class="col-span-full py-20 flex flex-col items-center justify-center text-center opacity-40">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-white/5 rounded-full flex items-center justify-center mb-6">
                        <span class="material-symbols-rounded text-4xl">search_off</span>
                    </div>
                    <h3 class="text-xl font-bold dark:text-white">No videos found</h3>
                    <p class="text-sm mt-2">Try different keywords or check your spelling.</p>
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
            const searchQuery = @json($searchQuery);
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

                        fetch("{{ route('video.get') }}?page=" + currentPage + "&q=" + encodeURIComponent(searchQuery))
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
