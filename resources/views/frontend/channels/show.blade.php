@push('style')
<style>
    @media (max-width: 1023px) {
        main.flex-1 {
            padding-top: 52px !important;
            margin-left: 0 !important;
        }
    }
</style>
@endpush

<x-app-layout>
    <div x-data="{ 
        activeTab: 'home',
        aboutOpen: false,
        videoFilter: 'latest',
        subscribed: {{ (auth()->check() && \App\Models\Subscription::where('user_id', auth()->id())->where('channel_id', $channel->id)->exists()) ? 'true' : 'false' }},
        subscribersCount: {{ $channel->subscribers_count }},
        subDropdownOpen: false,
        mobileOptionsOpen: false,
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
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                this.subscribed = data.subscribed;
                this.subscribersCount = data.subscribers_count;
                this.notificationPreference = data.notification_preference;
                
                // Automatically open dropdown on success
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
                text: 'Are you sure you want to unsubscribe from this channel?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Unsubscribe',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#000000',
                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            }).then((result) => {
                if (result.isConfirmed) {
                    const prevSubscribed = this.subscribed;
                    const prevCount = this.subscribersCount;
                    
                    this.subscribed = false;
                    this.subscribersCount = Math.max(0, this.subscribersCount - 1);
                    this.subDropdownOpen = false;

                    fetch('{{ route('channels.subscribe', $channel->id) }}', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Network response was not ok');
                        return res.json();
                    })
                    .then(data => {
                        this.subscribed = data.subscribed;
                        this.subscribersCount = data.subscribers_count;
                    })
                    .catch(error => {
                        console.error('Unsubscribe failed:', error);
                        this.subscribed = prevSubscribed;
                        this.subscribersCount = prevCount;
                    });
                }
            });
        },
        setNotification(pref) {
            const oldPref = this.notificationPreference;
            this.notificationPreference = pref;
            this.subDropdownOpen = false;

            fetch('{{ route('channels.subscribe.preference', $channel->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ preference: pref })
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        icon: 'success',
                        title: pref === 'all' ? 'Notifications set to All' : 'Notifications set to None',
                        background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                    });
                }
            })
            .catch(() => {
                this.notificationPreference = oldPref;
            });
        },
        shareChannel() {
            if (navigator.share) {
                navigator.share({
                    title: @js($channel->name),
                    url: '{{ route('channels.show', $channel) }}'
                });
            } else {
                navigator.clipboard.writeText('{{ route('channels.show', $channel) }}');
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    icon: 'success',
                    title: 'Link copied to clipboard',
                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                });
            }
        }
    }" class="bg-white dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500 overflow-x-hidden">

    <!-- Custom Mobile Header -->
    <div id="channel-mobile-bar" class="flex lg:hidden fixed top-0 left-0 right-0 h-[52px] z-[9999] flex-row items-center justify-between px-2 bg-white/95 dark:bg-[#0F0F0F]/95 backdrop-blur-xl border-b border-black/5 dark:border-white/5">
        
        {{-- Left: Back --}}
        <div class="flex items-center shrink-0">
            <button onclick="history.back()" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-black/5 dark:hover:bg-white/10 active:scale-90 transition-all text-gray-800 dark:text-gray-200">
                <span class="material-symbols-rounded text-[22px]">arrow_back</span>
            </button>
        </div>

        {{-- Center: Channel name --}}
        <div class="flex-1 min-w-0 px-2 text-center">
            <span class="text-sm font-bold text-gray-900 dark:text-white truncate block">{{ $channel->name }}</span>
        </div>

        {{-- Right: Search + More --}}
        <div class="flex items-center gap-0.5 shrink-0">
            <button @click="$dispatch('open-global-search')" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-black/5 dark:hover:bg-white/10 active:scale-90 transition-all text-gray-800 dark:text-gray-200">
                <span class="material-symbols-rounded text-[22px]">search</span>
            </button>

            <div class="relative" @click.outside="mobileOptionsOpen = false">
                <button @click="mobileOptionsOpen = !mobileOptionsOpen" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-black/5 dark:hover:bg-white/10 active:scale-90 transition-all text-gray-800 dark:text-gray-200">
                    <span class="material-symbols-rounded text-[22px]">more_vert</span>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="mobileOptionsOpen"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 bg-white dark:bg-neutral-800 rounded-2xl shadow-2xl ring-1 ring-black/5 dark:ring-white/10 p-1.5 z-50"
                     x-cloak>

                    <button @click="shareChannel(); mobileOptionsOpen = false" class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl text-[13px] font-medium text-gray-700 dark:text-gray-200 hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                        <span class="material-symbols-rounded text-lg text-blue-500">share</span>
                        <span>Share</span>
                    </button>

                    <button @click="aboutOpen = true; mobileOptionsOpen = false" class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl text-[13px] font-medium text-gray-700 dark:text-gray-200 hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                        <span class="material-symbols-rounded text-lg text-gray-400">info</span>
                        <span>About this channel</span>
                    </button>

                    @auth
                        @if(auth()->id() != $channel->user_id)
                            <div class="my-1 mx-2 border-t border-gray-100 dark:border-white/10"></div>
                            <button @click="$dispatch('open-report', { type: 'channel', id: {{ $channel->id }}, name: '{{ addslashes($channel->name) }}' }); mobileOptionsOpen = false" class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl text-[13px] font-medium text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                <span class="material-symbols-rounded text-lg">flag</span>
                                <span>Report user</span>
                            </button>
                        @endif
                    @else
                        <div class="my-1 mx-2 border-t border-gray-100 dark:border-white/10"></div>
                        <a href="{{ route('login') }}" class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl text-[13px] font-medium text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                            <span class="material-symbols-rounded text-lg">flag</span>
                            <span>Report user</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
        
        <!-- About Section Modal (Slide-up native look on mobile, centered popup on desktop) -->
        <div x-show="aboutOpen" 
             class="fixed inset-0 z-[200000] flex items-end lg:items-center lg:justify-center p-0 lg:p-4"
             x-cloak>
            
            <!-- Backdrop -->
            <div x-show="aboutOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="aboutOpen = false"
                 class="fixed inset-0 bg-black/80 backdrop-blur-md"></div>

            <div x-show="aboutOpen" 
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="translate-y-full lg:translate-y-0 lg:scale-95 lg:opacity-0"
                 x-transition:enter-end="translate-y-0 lg:scale-100 lg:opacity-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-y-0 lg:scale-100 lg:opacity-100"
                 x-transition:leave-end="translate-y-full lg:translate-y-0 lg:scale-95 lg:opacity-0"
                 class="w-full lg:max-w-xl xl:max-w-2xl h-[85vh] lg:h-auto lg:max-h-[85vh] bg-white dark:bg-[#0F0F0F] lg:rounded-[3rem] rounded-t-[2.5rem] shadow-2xl relative flex flex-col overflow-hidden border-t lg:border border-gray-100 dark:border-white/10 mx-auto">
                
                <div class="sticky top-0 z-10 bg-white/80 dark:bg-[#0F0F0F]/80 backdrop-blur-xl border-b border-gray-100 dark:border-white/5 px-4 h-14 flex items-center shrink-0">
                    <button @click="aboutOpen = false" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-white/10 transition-all text-gray-800 dark:text-white">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                    <h2 class="ml-4 text-lg font-black tracking-tight text-gray-900 dark:text-white">{{ $channel->user->channel_name ?? $channel->name ?? $channel->user->username }}</h2>
                </div>

                <div class="flex-1 overflow-y-auto p-6 space-y-10 pb-20 custom-scrollbar">
                    <!-- Description Section -->
                    <section>
                        <h3 class="text-xl font-black mb-4 text-gray-900 dark:text-white">Description</h3>
                        <p class="text-sm font-medium text-gray-600 dark:text-neutral-400 leading-relaxed whitespace-pre-wrap">{{ trim($channel->user->description ?? 'Welcome to my channel! Subscribe for more amazing content.') }}</p>
                    </section>

                    <!-- Links Section (Dynamic) -->
                    @if($channel->user->social_links && count($channel->user->social_links) > 0)
                    <section>
                        <h3 class="text-xl font-black mb-6 text-gray-900 dark:text-white">Links</h3>
                        <div class="space-y-6">
                            @php
                                $social_icons = [
                                    'facebook' => ['text-blue-600', '<svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>'],
                                    'twitter' => ['text-slate-900 dark:text-white', '<svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.045 4.126H5.078z"/></svg>'],
                                    'instagram' => ['text-pink-600', '<svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>'],
                                    'youtube' => ['text-red-600', '<svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186c-.275-1.037-1.091-1.854-2.128-2.128C19.505 3.5 12 3.5 12 3.5s-7.505 0-9.37.558c-1.037.275-1.853 1.091-2.128 2.128C0 8.053 0 12 0 12s0 3.947.502 5.814c.275 1.037 1.091 1.854 2.128 2.128C4.495 20.5 12 20.5 12 20.5s7.505 0 9.37-.558c1.037-.275 1.853-1.091 2.128-2.128.502-1.867.502-5.814.502-5.814s0-3.947-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>']
                                ];
                            @endphp
                            @foreach($channel->user->social_links as $key => $link)
                                @if($link && isset($social_icons[$key]))
                                    <a href="{{ $link }}" target="_blank" class="flex items-center gap-5 group">
                                        <div class="w-12 h-12 rounded-full bg-gray-50 dark:bg-white/5 flex items-center justify-center {{ $social_icons[$key][0] }} group-hover:scale-110 transition-transform">
                                            {!! $social_icons[$key][1] !!}
                                        </div>
                                        <div class="flex-grow overflow-hidden">
                                            <p class="font-black text-sm capitalize text-gray-900 dark:text-white">{{ $key }}</p>
                                            <p class="text-xs text-blue-600 dark:text-blue-400 font-bold truncate">{{ str_replace(['https://', 'http://', 'www.'], '', $link) }}</p>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </section>
                    @endif

                    <!-- More Info Section -->
                    <section>
                        <h3 class="text-xl font-black mb-6 text-gray-900 dark:text-white">More info</h3>
                        <div class="space-y-6">
                            <div class="flex items-center gap-5">
                                <span class="material-symbols-rounded text-gray-500 dark:text-white">public</span>
                                <p class="text-sm font-bold text-blue-600 dark:text-blue-400">{{ str_replace(['https://', 'http://', 'www.'], '', route('channels.show', $channel)) }}</p>
                            </div>
                            <div class="flex items-center gap-5 text-gray-600 dark:text-white">
                                <span class="material-symbols-rounded">language</span>
                                <p class="text-sm font-bold">{{ $channel->user->country_name ?? 'India' }}</p>
                            </div>
                            <div class="flex items-center gap-5 text-gray-600 dark:text-white">
                                <span class="material-symbols-rounded">info</span>
                                <p class="text-sm font-bold">Joined {{ $channel->user->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="flex items-center gap-5 text-gray-600 dark:text-white">
                                <span class="material-symbols-rounded">trending_up</span>
                                <p class="text-sm font-bold">{{ number_format($channel->user->videos->sum('views_count')) }} views</p>
                            </div>
                            @auth
                            @if(auth()->id() != $channel->user_id)
                            <div class="pt-4 border-t border-gray-100 dark:border-white/5">
                                <button @click="window.dispatchEvent(new CustomEvent('open-report', { detail: { id: {{ $channel->id }}, type: 'channel' } })); aboutOpen = false;" class="flex items-center gap-5 text-red-500 hover:text-red-600 transition-colors w-full text-left">
                                    <span class="material-symbols-rounded">flag</span>
                                    <span class="text-sm font-bold">Report User</span>
                                </button>
                            </div>
                            @endif
                            @endauth
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <!-- Banner Section (Cinematic & Dynamic) -->
        <div class="max-w-[1400px] mx-auto overflow-hidden md:rounded-2xl md:mt-3 px-0 md:px-4">
            <div class="w-full aspect-[4/1] md:aspect-[6.2/1] bg-gray-100 dark:bg-[#1A1A1A] relative overflow-hidden group rounded-none md:rounded-2xl border border-black/5 dark:border-white/5 shadow-sm">
                @if($channel->banner)
                    <img src="{{ getImage(getFilePath('cover') . '/' . $channel->banner) }}" class="w-full h-full object-cover object-center">
                @else
                    <div class="absolute inset-0 bg-gray-50 dark:bg-[#0c0c0c]"></div>
                    <div class="absolute inset-4 rounded-xl border border-dashed border-gray-200 dark:border-white/5 flex flex-col items-center justify-center gap-2">
                        <div class="w-10 h-10 rounded-lg bg-white dark:bg-white/5 flex items-center justify-center shadow-md border border-gray-100 dark:border-white/10">
                            <span class="material-symbols-rounded text-gray-300 dark:text-white/40 text-xl">landscape</span>
                        </div>
                        <div class="text-center">
                            <span class="text-[9px] font-black text-gray-400 dark:text-white/40 uppercase tracking-[0.25em] block">No cover photo available</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Channel Identity Section (Compact) -->
        <div class="max-w-[1400px] mx-auto px-4 md:px-12 py-4 md:py-6">
            <div class="flex flex-col md:flex-row gap-4 md:gap-8">
                <!-- Avatar -->
                <div class="flex-shrink-0">
                    <div class="w-20 h-20 md:w-32 md:h-32 rounded-full bg-red-600 border-4 border-white dark:border-[#0F0F0F] shadow-2xl overflow-hidden shadow-black/10 mx-auto md:mx-0">
                        @php $avatar = $channel->avatar ?? $channel->user->image; @endphp
                        @if($avatar)
                            <img src="{{ getImage(getFilePath('userProfile') . '/' . $avatar) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-white text-4xl md:text-6xl font-black">
                                {{ substr($channel->user->channel_name ?? $channel->name ?? $channel->user->username, 0, 1) }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Info Cluster -->
                <div class="flex-grow text-center md:text-left space-y-4">
                    <div class="space-y-1">
                        <h1 class="text-xl md:text-3xl font-black tracking-tight text-gray-900 dark:text-white leading-none capitalize">
                            {{ $channel->user->channel_name ?? $channel->name ?? $channel->user->username }}
                        </h1>
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-x-2 text-[12px] md:text-sm font-bold text-gray-500 dark:text-neutral-400">
                            <span class="text-gray-900 dark:text-white">@<span>{{ $channel->user->username }}</span></span>
                            <span>•</span>
                            <span x-text="subscribersCount"></span> subscribers
                            <span>•</span>
                            <span>{{ $channel->user->videos->count() + $channel->user->reels->count() }} videos</span>
                        </div>
                    </div>

                    <!-- Description Toggle -->
                    <div class="max-w-2xl w-full overflow-hidden">
                        <button type="button" @click="aboutOpen = true" class="group flex items-center text-left hover:opacity-80 transition-opacity w-full">
                            <p class="text-[13px] md:text-sm font-bold text-gray-500 dark:text-neutral-400 line-clamp-1 mr-2">{{ trim($channel->user->description ?? 'Welcome to my official channel!') }}</p>
                            <span class="text-gray-900 dark:text-white font-black text-[13px] md:text-sm whitespace-nowrap flex items-center shrink-0">
                                more <span class="material-symbols-rounded text-[16px] ml-0.5">chevron_right</span>
                            </span>
                        </button>
                    </div>

                    <!-- Social Icons Row (Mobile Visual) -->
                    @if($channel->user->social_links && count($channel->user->social_links) > 0)
                    <div class="flex items-center justify-center md:justify-start gap-4">
                        @php
                            $social_icons = [
                                'facebook' => ['text-blue-600', '<svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>'],
                                'twitter' => ['text-slate-900 dark:text-white', '<svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.045 4.126H5.078z"/></svg>'],
                                'instagram' => ['text-pink-600', '<svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>'],
                                'youtube' => ['text-red-600', '<svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186c-.275-1.037-1.091-1.854-2.128-2.128C19.505 3.5 12 3.5 12 3.5s-7.505 0-9.37.558c-1.037.275-1.853 1.091-2.128 2.128C0 8.053 0 12 0 12s0 3.947.502 5.814c.275 1.037 1.091 1.854 2.128 2.128C4.495 20.5 12 20.5 12 20.5s7.505 0 9.37-.558c1.037-.275 1.853-1.091 2.128-2.128.502-1.867.502-5.814.502-5.814s0-3.947-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>']
                            ];
                        @endphp
                        @foreach($channel->user->social_links as $key => $link)
                            @if($link && isset($social_icons[$key]))
                                <a href="{{ $link }}" target="_blank" class="w-10 h-10 rounded-full bg-gray-50 dark:bg-white/5 flex items-center justify-center {{ $social_icons[$key][0] }} hover:scale-110 active:scale-95 transition-all border border-gray-100 dark:border-white/5">
                                    {!! $social_icons[$key][1] !!}
                                </a>
                            @endif
                        @endforeach
                    </div>
                    @endif

                    <!-- Subscribe Button -->
                    <div class="pt-2 flex flex-col md:flex-row items-center gap-4">
                        @if(auth()->id() != $channel->user_id)
                            <div class="flex items-center gap-2 w-full md:w-auto relative" @click.away="subDropdownOpen = false">
                                <button @click="toggleSubscribe" 
                                        :class="subscribed ? 'bg-gray-100 dark:bg-white/10 text-gray-900 dark:text-white pr-4' : 'bg-black dark:bg-white text-white dark:text-black'" 
                                        class="h-10 px-8 flex-grow md:flex-grow-0 rounded-full font-black text-sm transition-all active:scale-95 flex items-center justify-center gap-2 shadow-xl shadow-black/5">
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

                                <!-- Dropdown Menu -->
                                <div x-show="subDropdownOpen" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute left-0 top-full mt-2 w-48 bg-white dark:bg-[#282828] rounded-xl shadow-2xl py-2 z-[100] border border-gray-100 dark:border-white/10 overflow-hidden"
                                     style="display: none;">
                                    
                                    <button @click="setNotification('all')" class="w-full px-4 py-3 flex items-center gap-3 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                                        <span class="material-symbols-rounded text-xl" :class="notificationPreference === 'all' ? 'material-symbols-filled text-orange-600' : ''">notifications_active</span>
                                        <span class="text-sm font-bold">All</span>
                                    </button>

                                    <button @click="setNotification('none')" class="w-full px-4 py-3 flex items-center gap-3 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                                        <span class="material-symbols-rounded text-xl" :class="notificationPreference === 'none' ? 'material-symbols-filled' : ''">notifications_off</span>
                                        <span class="text-sm font-bold">None</span>
                                    </button>

                                    <div class="h-[1px] bg-gray-100 dark:bg-white/10 my-1"></div>

                                    <button @click="unsubscribe()" class="w-full px-4 py-3 flex items-center gap-3 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors text-red-600">
                                        <span class="material-symbols-rounded text-xl">person_remove</span>
                                        <span class="text-sm font-bold">Unsubscribe</span>
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-2 w-full md:w-auto overflow-hidden">
                                <a href="{{ route('profile.edit') }}" class="h-10 px-6 bg-gray-100 dark:bg-white/10 text-gray-900 dark:text-white rounded-full font-black text-[10px] uppercase tracking-widest flex items-center justify-center flex-grow md:flex-grow-0 border border-gray-200 dark:border-white/10 transition-colors hover:bg-gray-200 dark:hover:bg-white/20 whitespace-nowrap">Customize</a>
                                <a href="{{ route('studio.videos') }}" class="h-10 px-6 bg-gray-100 dark:bg-white/10 text-gray-900 dark:text-white rounded-full font-black text-[10px] uppercase tracking-widest flex items-center justify-center flex-grow md:flex-grow-0 border border-gray-200 dark:border-white/10 transition-colors hover:bg-gray-200 dark:hover:bg-white/20 whitespace-nowrap">Videos</a>
                                <a href="{{ route('studio.analytics') }}" class="h-10 px-6 bg-gray-100 dark:bg-white/10 text-gray-900 dark:text-white rounded-full font-black text-[10px] uppercase tracking-widest flex items-center justify-center flex-grow md:flex-grow-0 border border-gray-200 dark:border-white/10 transition-colors hover:bg-gray-200 dark:hover:bg-white/20 whitespace-nowrap">Analytics</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tab Navigation (Compact) -->
            <div class="mt-4 md:mt-6 border-b border-gray-100 dark:border-white/5 no-scrollbar overflow-x-auto">
                <div class="flex items-center">
                    @foreach (['Home', 'Videos', 'Reels', 'Premium', 'Playlists', 'About'] as $tab)
                        @php 
                            $tabId = strtolower($tab);
                            $isPremiumTab = $tab === 'Premium';
                            $miniOttComingSoon = $isPremiumTab && gs('mini_ott_status') !== null && (int) gs('mini_ott_status') === 0;
                        @endphp
                        <button @click="activeTab = '{{ $tabId }}'" 
                                class="px-6 md:px-8 py-4 relative group transition-colors flex items-center gap-1.5"
                                :class="activeTab === '{{ $tabId }}' ? 'text-gray-900 dark:text-white' : 'text-gray-500 dark:text-neutral-400'">
                            <span class="text-[14px] md:text-base font-black tracking-tight"
                                  :class="activeTab === '{{ $tabId }}' ? 'opacity-100' : 'opacity-60 group-hover:opacity-100'">
                                {{ $tab }}
                            </span>
                            @if($isPremiumTab && $miniOttComingSoon)
                                <span class="px-1.5 py-0.5 rounded-full bg-[#ff571a] text-white text-[7px] font-black uppercase tracking-widest leading-none">Soon</span>
                            @endif
                            <div x-show="activeTab === '{{ $tabId }}'" 
                                 class="absolute bottom-0 left-0 right-0 h-[3px] bg-gray-900 dark:bg-white rounded-t-full"></div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Dynamic Content Grid (Streamlined) -->
        <main class="max-w-[1400px] mx-auto px-4 md:px-12 pt-6 pb-12">
            
            <!-- Home Tab -->
            <div x-show="activeTab === 'home'" class="space-y-12">
                @if($popularVideos->count() > 0)
                <section>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-4 gap-y-10">
                        @foreach($popularVideos as $video)
                            @include('frontend.partials.video_card', ['video' => $video])
                        @endforeach
                    </div>
                </section>
                @else
                    <div class="flex flex-col items-center justify-center py-24 text-center">
                        <div class="w-20 h-20 rounded-[2rem] bg-gray-50 dark:bg-white/5 flex items-center justify-center text-gray-300 dark:text-white/10 mb-6">
                            <span class="material-symbols-rounded text-4xl">video_library</span>
                        </div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">No popular videos yet</h3>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest max-w-xs">When this channel uploads videos and gains views, they will appear here.</p>
                    </div>
                @endif
            </div>

            <!-- Videos Tab -->
            <div x-show="activeTab === 'videos'" x-cloak>
                @if($recentVideos->count() > 0)
                    <div class="flex items-center gap-3 mb-8">
                        <button @click="videoFilter = 'latest'" 
                                :class="videoFilter === 'latest' ? 'bg-black dark:bg-white text-white dark:text-black' : 'bg-gray-100 dark:bg-white/10 text-gray-900 dark:text-white hover:bg-gray-200 dark:hover:bg-white/20'"
                                class="px-4 py-2 rounded-lg font-black text-xs uppercase tracking-widest transition-all">Latest</button>
                        <button @click="videoFilter = 'popular'" 
                                :class="videoFilter === 'popular' ? 'bg-black dark:bg-white text-white dark:text-black' : 'bg-gray-100 dark:bg-white/10 text-gray-900 dark:text-white hover:bg-gray-200 dark:hover:bg-white/20'"
                                class="px-4 py-2 rounded-lg font-black text-xs uppercase tracking-widest transition-all">Popular</button>
                        <button @click="videoFilter = 'oldest'" 
                                :class="videoFilter === 'oldest' ? 'bg-black dark:bg-white text-white dark:text-black' : 'bg-gray-100 dark:bg-white/10 text-gray-900 dark:text-white hover:bg-gray-200 dark:hover:bg-white/20'"
                                class="px-4 py-2 rounded-lg font-black text-xs uppercase tracking-widest transition-all">Oldest</button>
                    </div>
                    <div class="space-y-10">
                        <div x-show="videoFilter === 'latest'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-4 gap-y-10">
                            @foreach($recentVideos as $video)
                                @include('frontend.partials.video_card', ['video' => $video])
                            @endforeach
                        </div>
                        <div x-show="videoFilter === 'popular'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-4 gap-y-10">
                            @foreach($popularVideos as $video)
                                @include('frontend.partials.video_card', ['video' => $video])
                            @endforeach
                        </div>
                        <div x-show="videoFilter === 'oldest'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-4 gap-y-10">
                            @foreach($oldestVideos as $video)
                                @include('frontend.partials.video_card', ['video' => $video])
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-24 text-center">
                        <div class="w-20 h-20 rounded-[2rem] bg-gray-50 dark:bg-white/5 flex items-center justify-center text-gray-300 dark:text-white/10 mb-6">
                            <span class="material-symbols-rounded text-4xl">movie</span>
                        </div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">This channel has no videos</h3>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest max-w-xs">The creator hasn't published any public content yet.</p>
                    </div>
                @endif
            </div>

            <!-- Reels Tab -->
            <div x-show="activeTab === 'reels'" x-cloak>
                @if($reels->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2 md:gap-4">
                        @foreach($reels as $reel)
                            @if(!$reel->slug) @continue @endif
                            @include('frontend.partials.reel_card', ['reel' => $reel])
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-24 text-center">
                        <div class="w-20 h-20 rounded-[2rem] bg-gray-50 dark:bg-white/5 flex items-center justify-center text-gray-300 dark:text-white/10 mb-6">
                            <span class="material-symbols-rounded text-4xl">movie</span>
                        </div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">No Reels yet</h3>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest max-w-xs">This creator hasn't uploaded any Reels yet. Vertical short-form videos will appear here.</p>
                    </div>
                @endif
            </div>

            <!-- Premium Tab — Native Android M3 Coming Soon aware -->
            <div x-show="activeTab === 'premium'" x-cloak>
                @php $miniOttSoon = gs('mini_ott_status') !== null && (int) gs('mini_ott_status') === 0; @endphp
                @if($miniOttSoon)
                    <div class="flex flex-col items-center justify-center py-8 md:py-12">
                        <div class="w-full max-w-[520px] relative overflow-hidden bg-white dark:bg-[#1E1E1E] rounded-[32px] border border-slate-100 dark:border-white/[0.06] shadow-[0_8px_32px_rgba(0,0,0,0.08)] dark:shadow-[0_12px_40px_rgba(0,0,0,0.3)] text-center overflow-hidden">
                        <div class="h-1 w-full bg-gradient-to-r from-[#ff571a] via-[#ff8a1a] to-[#ff571a] opacity-90"></div>
                            <div class="absolute inset-x-0 top-0 h-[100px] bg-gradient-to-b from-orange-500/[0.06] to-transparent pointer-events-none"></div>
                            <div class="relative z-10 p-6 md:p-8">
                                <div class="relative w-[72px] h-[72px] mx-auto mb-4">
                                    <div class="absolute inset-0 bg-[#ff571a]/20 rounded-[22px] blur-[14px]"></div>
                                    <div class="relative w-full h-full rounded-[22px] bg-orange-50 dark:bg-[#ff571a]/15 border border-orange-100 dark:border-[#ff571a]/20 flex items-center justify-center">
                                        <div class="w-10 h-10 rounded-[14px] bg-[#ff571a] flex items-center justify-center shadow-md">
                                            <span class="material-symbols-rounded text-[22px] text-white material-symbols-filled">movie</span>
                                        </div>
                                        <div class="absolute -top-1 -right-1 w-6 h-6 rounded-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 flex items-center justify-center border-2 border-white dark:border-[#1E1E1E]">
                                            <span class="material-symbols-rounded text-[12px]">hourglass_empty</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 mb-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span class="text-[9px] font-black uppercase tracking-[0.18em]">Coming Soon</span>
                                </div>
                                <h3 class="text-[20px] font-black text-slate-900 dark:text-white tracking-tight leading-none">Mini OTT <span class="text-[#ff571a]">Coming Soon</span></h3>
                                <p class="text-[12px] font-medium text-slate-500 dark:text-white/50 leading-relaxed mt-1.5 max-w-[380px] mx-auto">This channel’s premium catalogue will be available when Mini OTT launches. Stay tuned — curated originals & ad-free playback are on the way.</p>
                                <div class="flex items-center justify-center gap-1.5 mt-4 flex-wrap">
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-[10px] font-bold text-slate-600 dark:text-white/60">Exclusive</span>
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-[10px] font-bold text-slate-600 dark:text-white/60">4K Ready</span>
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-[10px] font-bold text-slate-600 dark:text-white/60">Ad-free</span>
                                </div>
                                <div class="mt-5 flex flex-col sm:flex-row gap-2 justify-center">
                                    <a href="{{ route('premium') }}" class="h-10 px-6 rounded-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-black text-[11px] uppercase tracking-widest flex items-center justify-center gap-1.5 active:scale-[0.98] transition-all">
                                        <span class="material-symbols-rounded text-[16px]">movie</span> Explore Mini OTT
                                    </a>
                                    <button onclick="activeTab='videos'" class="h-10 px-6 rounded-full bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white font-black text-[11px] uppercase tracking-widest flex items-center justify-center">Browse Videos</button>
                                </div>
                            </div>
                        </div>
                    </div>
@elseif($premiumVideos->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-4 gap-y-10">
                        @foreach($premiumVideos as $video)
                            @include('frontend.partials.video_card', ['video' => $video])
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-24 text-center">
                        <div class="w-20 h-20 rounded-[2rem] bg-gray-50 dark:bg-white/5 flex items-center justify-center text-gray-300 dark:text-white/10 mb-6">
                            <span class="material-symbols-rounded text-4xl">workspace_premium</span>
                        </div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">No premium content</h3>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest max-w-xs">This channel hasn't published any premium videos yet.</p>
                    </div>
                @endif
            </div>

            <div x-show="activeTab === 'playlists'" x-cloak>
                @if($playlists->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-12">
                        @foreach($playlists as $playlist)
                            @php
                                $firstVideo = $playlist->videos()->first();
                                $firstReel = $playlist->reels()->first();
                            @endphp
                            <a href="{{ route('playlists.show', ['username' => optional($playlist->user->channel)->slug ?? $playlist->user->username ?? 'user', 'playlist' => $playlist->slug]) }}" class="group space-y-3">


                                <div class="relative aspect-video rounded-2xl overflow-hidden bg-gray-100 dark:bg-white/5 border border-gray-100 dark:border-white/10 group-hover:shadow-2xl transition-all duration-500">
                                    @if($firstVideo)
                                        <img src="{{ $firstVideo->getThumbnailUrl() }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" onerror="this.src='{{ $firstVideo->getPreviewUrl() }}'; this.onerror=null;">
                                    @elseif($firstReel)
                                        <img src="{{ $firstReel->getThumbnailUrl() }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" onerror="this.src='{{ $firstReel->getPreviewUrl() }}'; this.onerror=null;">
                                    @endif
                                    <div class="absolute inset-y-0 right-0 w-1/3 bg-black/60 backdrop-blur-md flex flex-col items-center justify-center text-white">
                                        <span class="material-symbols-rounded text-3xl">playlist_play</span>
                                        <span class="text-sm font-black mt-2">{{ $playlist->videos_count + $playlist->reels_count }}</span>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-black text-sm truncate group-hover:text-red-600 transition-colors">{{ $playlist->name }}</h3>
                                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mt-1">Playlist</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-24 text-center">
                        <div class="w-20 h-20 rounded-[2rem] bg-gray-50 dark:bg-white/5 flex items-center justify-center text-gray-300 dark:text-white/10 mb-6">
                            <span class="material-symbols-rounded text-4xl">featured_play_list</span>
                        </div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">No playlists created</h3>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest max-w-xs">Organized content will appear here once the creator sets up playlists.</p>
                    </div>
                @endif
            </div>

            <!-- About Tab (Desktop Mirror of Modal) -->
            <div x-show="activeTab === 'about'" x-cloak class="max-w-4xl space-y-14">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-16">
                    <div class="md:col-span-2 space-y-12">
                        <section>
                            <h3 class="font-black text-xl mb-4 leading-none text-gray-900 dark:text-white">Description</h3>
                            <p class="text-sm md:text-base font-medium text-gray-600 dark:text-neutral-400 whitespace-pre-wrap leading-relaxed">{{ trim($channel->user->description ?? 'No description provided.') }}</p>
                        </section>
                        
                        @if($channel->user->social_links && count($channel->user->social_links) > 0)
                        <section>
                            <h3 class="font-black text-xl mb-6 leading-none text-gray-900 dark:text-white">Links</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($channel->user->social_links as $key => $link)
                                    @if($link)
                                    <a href="{{ $link }}" target="_blank" class="flex items-center gap-4 text-blue-600 dark:text-blue-400 font-bold text-sm hover:underline">
                                        <span class="capitalize">{{ $key }}</span>
                                    </a>
                                    @endif
                                @endforeach
                            </div>
                        </section>
                        @endif
                    </div>

                    <section class="space-y-8">
                        <h3 class="font-black text-xl mb-6 leading-none text-gray-900 dark:text-white">Stats</h3>
                        <div class="space-y-6">
                            <div class="flex items-center gap-4 text-sm font-bold text-gray-700 dark:text-gray-300 border-b border-gray-100 dark:border-white/5 pb-4">
                                <span class="material-symbols-rounded opacity-40">info</span>
                                <span>Joined {{ $channel->user->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center gap-4 text-sm font-bold text-gray-700 dark:text-gray-300 border-b border-gray-100 dark:border-white/5 pb-4">
                                <span class="material-symbols-rounded opacity-40">trending_up</span>
                                <span>{{ number_format($channel->user->videos->sum('views_count')) }} views</span>
                            </div>
                            <div class="flex items-center gap-4 text-sm font-bold text-gray-700 dark:text-gray-300 border-b border-gray-100 dark:border-white/5 pb-4">
                                <span class="material-symbols-rounded opacity-40">language</span>
                                <span>{{ $channel->user->country_name ?? 'India' }}</span>
                            </div>
                            @auth
                            @if(auth()->id() != $channel->user_id)
                            <button @click="window.dispatchEvent(new CustomEvent('open-report', { detail: { id: {{ $channel->id }}, type: 'channel' } }))" class="flex items-center gap-4 text-sm font-bold text-red-500 hover:text-red-600 transition-colors pt-2 w-full text-left">
                                <span class="material-symbols-rounded">flag</span>
                                <span>Report User</span>
                            </button>
                            @endif
                            @endauth
                        </div>
                    </section>
                </div>
            </div>

        </main>
    </div>
</x-app-layout>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    [x-cloak] { display: none !important; }
</style>
