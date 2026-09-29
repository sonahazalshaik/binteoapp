<nav aria-label="Main Navigation" x-data="{ 
    mobileMenu: false, 
    searchGlobal: false,
    uploadPopup: false,
    darkMode: localStorage.getItem('dark') === 'true',
    query: '',
    results: [],
    loading: false,
    toggleDark() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('dark', this.darkMode);
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    },
    fetchSuggestions() {
        if (this.query.length < 2) { this.results = []; return; }
        this.loading = true;
        let type = @json(request()->routeIs('marketplace.*')) ? '&type=marketplace' : '';
        fetch(`/api/search/suggestions?q=${this.query}${type}`)
            .then(res => res.json())
            .then(data => {
                this.results = data;
                this.loading = false;
            })
            .catch(error => {
                console.error('Search suggestion error:', error);
                this.loading = false;
                this.results = [];
            });
    }
}" 
@open-global-search.window="searchGlobal = true; $nextTick(() => { if($refs.mobileSearch) $refs.mobileSearch.focus(); })"
x-init="if (darkMode) document.documentElement.classList.add('dark')" 
class="bg-white dark:bg-[#0F0F0F] border-b border-gray-100 dark:border-white/5 fixed top-0 left-0 right-0 z-50 transition-colors duration-500"
:class="{'flex': searchGlobal, 'hidden lg:block': !searchGlobal && @json(request()->routeIs('user.*') || request()->routeIs('studio.*') || request()->routeIs('reels.index') || request()->routeIs('channels.show'))}">
    <div class="px-6">
        <div class="flex justify-between h-14 lg:h-20 items-center">
            
            <!-- Branding & Logo Section (Hidden on Mobile Search) -->
            <div x-show="!searchGlobal" class="flex items-center space-x-4 lg:min-w-[280px] animate-in fade-in duration-300">
                <!-- Mobile Menu Toggle -->
                <button @click="mobileMenu = true" class="lg:hidden p-2 bg-gray-50 dark:bg-white/5 rounded-xl text-gray-500 hover:text-orange-500 transition-all active:scale-95">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>

                <!-- Dynamic Logo Section -->
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <div class="w-8 h-8 lg:w-10 lg:h-10 gradient-orange rounded-[0.5rem] lg:rounded-xl p-[1.5px] shadow-xl shadow-orange-200/50 overflow-hidden">
                        <div class="w-full h-full bg-white rounded-[6px] lg:rounded-[11px] flex items-center justify-center p-1.5">
                            <img src="{{ siteLogo() }}" class="w-full h-full object-contain" alt="{{ gs('site_name') }}">
                        </div>
                    </div>
                    <div class="flex flex-col hidden sm:flex">
                        <span class="text-2xl font-black text-[#2D2D2D] dark:text-white leading-none tracking-tight uppercase">{{ gs('site_name') }}</span>
                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Stream Everything</span>
                    </div>
                </a>
            </div>

            <!-- Expanding Search Hub (Mobile Only) -->
            <div x-show="searchGlobal" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="flex-grow flex flex-col gap-4 md:hidden relative"
                 style="display: none;">
                
                <div class="flex items-center gap-4 w-full">
                    <button @click="searchGlobal = false" class="text-gray-400 hover:text-orange-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <form action="{{ request()->routeIs('marketplace.*') ? route('marketplace.index') : route('search') }}" method="GET" class="flex-grow relative" x-init="query = '{{ request()->routeIs('marketplace.*') ? request()->query('search') : request()->query('q') }}'">
                        <input x-ref="mobileSearch" 
                               x-model="query"
                               @input.debounce.50ms="fetchSuggestions"
                               @click.away="results = []"
                               type="text" 
                               name="{{ request()->routeIs('marketplace.*') ? 'search' : 'q' }}"
                               placeholder="{{ request()->routeIs('marketplace.*') ? 'Search profiles...' : 'Search for amazing content...' }}" 
                               class="w-full bg-gray-100 dark:bg-white/5 border-none focus:ring-0 rounded-2xl pl-6 pr-[5.5rem] py-3 text-sm font-bold tracking-tight text-gray-900 dark:text-white">
                        
                        <button type="button" 
                                x-show="query.length > 0" 
                                @click="query = ''; results = []; $refs.mobileSearch.focus();" 
                                class="absolute right-[3.25rem] top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"
                                style="display: none;">
                            <span class="material-symbols-rounded text-[20px]">close</span>
                        </button>

                        <button type="submit" class="absolute right-1.5 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center bg-orange-500 text-white rounded-full shadow-lg shadow-orange-500/30 hover:scale-105 active:scale-95 transition-all">
                            <svg x-show="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <svg x-show="loading" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </button>
                    </form>
                </div>

                <!-- Mobile Autocomplete Dropdown -->
                <div x-show="results.length > 0 && query.length > 1" 
                     x-transition 
                     class="absolute top-full left-0 right-0 mt-3 bg-white dark:bg-[#1A1A1A] border border-gray-100 dark:border-white/5 rounded-[1.5rem] shadow-2xl overflow-hidden z-[200] backdrop-blur-3xl"
                     style="display: none;">
                    <div class="p-3 border-b border-gray-50 dark:border-white/5 flex items-center justify-between px-6">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Suggestions</span>
                    </div>
                    <div class="max-h-[60vh] overflow-y-auto">
                        <template x-for="item in results" :key="item.id">
                            <a :href="item.url" class="flex items-center space-x-4 px-6 py-4 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group border-b border-gray-50 dark:border-white/[0.02] last:border-0">
                                <div class="w-12 h-12 flex-shrink-0 shadow-sm overflow-hidden"
                                     :class="(item.type === 'Channel' || item.type === 'Talent') ? 'rounded-full' : 'w-16 aspect-video rounded-xl'">
                                    <img :src="item.thumbnail" class="w-full h-full object-cover" x-show="item.thumbnail">
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-white/10" x-show="!item.thumbnail">
                                        <template x-if="item.type === 'Channel' || item.type === 'Talent'">
                                            <div class="w-full h-full gradient-orange flex items-center justify-center text-white text-xs font-black" x-text="item.initials"></div>
                                        </template>
                                        <template x-if="item.type !== 'Channel' && item.type !== 'Talent'">
                                            <span class="material-symbols-rounded text-gray-400 text-xl">play_circle</span>
                                        </template>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-gray-800 dark:text-white truncate" x-text="item.title"></p>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[9px] font-black px-1.5 py-0.5 rounded-md uppercase tracking-wider" 
                                              :class="item.type === 'Video' ? 'bg-red-100 text-red-600' : (item.type === 'Reel' ? 'bg-purple-100 text-purple-600' : (item.type === 'Talent' ? 'bg-blue-100 text-blue-600' : 'bg-emerald-100 text-emerald-600'))" 
                                              x-text="item.type"></span>
                                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tight truncate" x-text="item.channel"></p>
                                    </div>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Global Search (Desktop Only with Smart Autocomplete) -->
            <div class="flex-1 hidden md:flex items-center justify-center max-w-xl mx-8 font-bold">
                <!-- Content same as before -->
                <div class="w-full relative group">
                    <form action="{{ request()->routeIs('marketplace.*') ? route('marketplace.index') : route('search') }}" method="GET" class="relative" x-init="query = '{{ request()->routeIs('marketplace.*') ? request()->query('search') : request()->query('q') }}'">
                        <input type="text" 
                            name="{{ request()->routeIs('marketplace.*') ? 'search' : 'q' }}"
                            x-model="query" 
                            x-ref="desktopSearch"
                            @input.debounce.50ms="fetchSuggestions"
                            @click.away="results = []"
                            placeholder="{{ request()->routeIs('marketplace.*') ? 'Search profiles...' : 'Search for amazing content...' }}" 
                            class="w-full bg-[#F7F7F7] dark:bg-white/5 border-transparent dark:text-white rounded-[1.25rem] py-3.5 pl-6 pr-[5.5rem] focus:ring-0 focus:bg-white dark:focus:bg-[#1A1A1A] transition-all text-sm shadow-sm border border-gray-50 dark:border-white/5 placeholder-gray-400">
                        
                        <button type="button" 
                                x-show="query.length > 0" 
                                @click="query = ''; results = []; $refs.desktopSearch.focus();" 
                                class="absolute right-[3.5rem] top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"
                                style="display: none;">
                            <span class="material-symbols-rounded text-[20px]">close</span>
                        </button>

                        <button type="submit" class="absolute right-1.5 top-1/2 -translate-y-1/2 w-11 h-11 flex items-center justify-center bg-orange-500 text-white rounded-full shadow-lg shadow-orange-500/30 hover:scale-105 active:scale-95 transition-all">
                            <svg x-show="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <svg x-show="loading" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </button>
                    </form>

                    <!-- Autocomplete Dropdown -->
                    <div x-show="results.length > 0 && query.length > 1" 
                         x-transition 
                         class="absolute left-0 right-0 mt-3 bg-white dark:bg-[#1A1A1A] border border-gray-100 dark:border-white/5 rounded-[2rem] shadow-2xl overflow-hidden z-[200] backdrop-blur-3xl"
                         style="display: none;">
                        <div class="p-3 border-b border-gray-50 dark:border-white/5 flex items-center justify-between px-6">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Suggestions</span>
                            <span x-text="results.length" class="text-[10px] bg-red-50 dark:bg-red-500/10 text-red-600 px-2 py-0.5 rounded-full font-black"></span>
                        </div>
                        <div class="py-2">
                            <template x-for="item in results" :key="item.id">
                                <a :href="item.url" class="flex items-center space-x-4 px-6 py-4 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                                    <div class="flex-shrink-0 shadow-sm overflow-hidden"
                                         :class="(item.type === 'Channel' || item.type === 'Talent') ? 'w-12 h-12 rounded-full' : 'w-16 aspect-video rounded-xl bg-gray-100 dark:bg-white/10'">
                                        <img :src="item.thumbnail" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" x-show="item.thumbnail">
                                        <div class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-white/10" x-show="!item.thumbnail">
                                            <template x-if="item.type === 'Channel' || item.type === 'Talent'">
                                                <div class="w-full h-full gradient-orange flex items-center justify-center text-white text-xs font-black" x-text="item.initials"></div>
                                            </template>
                                            <template x-if="item.type !== 'Channel' && item.type !== 'Talent'">
                                                <span class="material-symbols-rounded text-gray-400 text-xl">play_circle</span>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-black text-gray-800 dark:text-white truncate" x-text="item.title"></p>
                                        <div class="flex items-center gap-3 mt-1">
                                            <span class="text-[9px] font-black px-2 py-0.5 rounded-md uppercase tracking-widest" 
                                                  :class="item.type === 'Video' ? 'bg-red-50 dark:bg-red-500/10 text-red-600' : (item.type === 'Reel' ? 'bg-purple-50 dark:bg-purple-500/10 text-purple-600' : (item.type === 'Talent' ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-600' : 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600'))" 
                                              x-text="item.type"></span>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tight truncate" x-text="item.channel"></p>
                                        </div>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons (Hidden on Mobile Search) -->
            <div x-show="!searchGlobal" class="flex items-center space-x-2 sm:space-x-3 animate-in fade-in duration-300">
                
                @auth
                    @if(Auth::user()->role !== 'admin')
                        @if(Auth::user()->isCreator() && Auth::user()->channel)
                            {{-- Upload Popup Trigger (Creator with channel) --}}
                            <div class="relative" x-data="{}">
                                <button @click="uploadPopup = !uploadPopup" class="hidden lg:flex items-center space-x-2 px-3 sm:px-5 py-2.5 gradient-orange text-white rounded-2xl font-black text-[11px] uppercase tracking-widest shadow-lg shadow-orange-200/50 hover:scale-[1.02] transition-all group active:scale-95">
                                    <span class="material-symbols-rounded text-lg group-hover:rotate-12 transition-transform">add</span>
                                    <span class="hidden sm:inline">Create</span>
                                </button>

                                {{-- Upload Type Popup --}}
                                <div x-show="uploadPopup" @click.away="uploadPopup = false" x-cloak
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 mt-4 w-72 bg-white dark:bg-[#1E1E1E] border border-gray-100 dark:border-white/10 rounded-[2rem] shadow-2xl z-[200] overflow-hidden p-3"
                                     style="display: none;">

                                    <div class="px-4 py-3 mb-1">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">What do you want to create?</p>
                                    </div>

                                    {{-- Upload Video Option --}}
                                    <a href="{{ route('videos.create') }}" @click="uploadPopup = false" 
                                       class="flex items-center gap-4 p-4 hover:bg-gray-50 dark:hover:bg-white/5 rounded-[1.5rem] transition-all group">
                                        <div class="w-12 h-12 rounded-2xl bg-red-500/10 dark:bg-red-500/20 flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform">
                                            <span class="material-symbols-rounded text-2xl">videocam</span>
                                        </div>
                                        <div>
                                            <p class="text-[13px] font-black text-gray-900 dark:text-white uppercase tracking-wider">Upload Video</p>
                                            <p class="text-[10px] text-gray-400 font-bold mt-0.5">Long-form content</p>
                                        </div>
                                        <span class="material-symbols-rounded text-gray-300 dark:text-white/20 ml-auto group-hover:translate-x-1 transition-transform">chevron_right</span>
                                    </a>

                                    {{-- Upload Reel Option --}}
                                    <a href="{{ route('reels.create') }}" @click="uploadPopup = false"
                                       class="flex items-center gap-4 p-4 hover:bg-gray-50 dark:hover:bg-white/5 rounded-[1.5rem] transition-all group">
                                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500/10 to-pink-500/10 dark:from-purple-500/20 dark:to-pink-500/20 flex items-center justify-center text-purple-500 group-hover:scale-110 transition-transform">
                                            <span class="material-symbols-rounded text-2xl">slow_motion_video</span>
                                        </div>
                                        <div>
                                            <p class="text-[13px] font-black text-gray-900 dark:text-white uppercase tracking-wider">Upload Reel</p>
                                            <p class="text-[10px] text-gray-400 font-bold mt-0.5">Short-form · Max 90s</p>
                                        </div>
                                        <span class="material-symbols-rounded text-gray-300 dark:text-white/20 ml-auto group-hover:translate-x-1 transition-transform">chevron_right</span>
                                    </a>
                                </div>
                            </div>
                        @elseif(Auth::user()->isCreator() && !Auth::user()->channel)
                            {{-- Create Channel Button (Creator who hasn't made channel yet) --}}
                            <a href="{{ route('channels.create') }}" class="hidden lg:flex items-center space-x-2 px-3 sm:px-5 py-2.5 bg-gray-900 dark:bg-white/10 text-white rounded-2xl font-black text-[11px] uppercase tracking-widest shadow-lg shadow-gray-200/50 dark:shadow-none hover:scale-[1.02] transition-all group active:scale-95">
                                <span class="material-symbols-rounded text-lg group-hover:rotate-12 transition-transform">add_circle</span>
                                <span class="hidden sm:inline">Create Channel</span>
                            </a>
                        @else
                            {{-- Join as Creator Button (no plan purchased yet) --}}
                            <a href="{{ route('channels.create') }}" class="hidden lg:flex items-center space-x-2 px-3 sm:px-5 py-2.5 text-white rounded-2xl font-black text-[11px] uppercase tracking-widest shadow-lg shadow-orange-200/50 dark:shadow-orange-900/30 hover:scale-[1.02] transition-all group active:scale-95 gradient-orange">
                                <span class="material-symbols-rounded text-lg group-hover:rotate-12 transition-transform">rocket_launch</span>
                                <span class="hidden sm:flex items-center">
                                    <img src="{{ asset('assets/images/logo_icon/be-creator-logo.png') }}" style="height: 16px; width: 16px; display: inline-block; vertical-align: middle; margin-right: 2px; object-fit: contain;">Be a Creator
                                </span>
                            </a>
                        @endif
                    @endif
                @endauth

                <!-- Mobile Search Trigger -->
                <button @click="searchGlobal = true; $nextTick(() => { if($refs.mobileSearch) $refs.mobileSearch.focus(); })" 
                        class="md:hidden w-9 h-9 sm:w-11 sm:h-11 bg-gray-50 dark:bg-white/5 rounded-xl sm:rounded-2xl flex items-center justify-center text-gray-400 hover:text-red-500 transition-all active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>

                <!-- Theme Toggle -->
                <button @click="toggleDark()" class="hidden sm:flex w-9 h-9 sm:w-11 sm:h-11 bg-gray-50 dark:bg-white/5 rounded-xl sm:rounded-2xl items-center justify-center text-gray-400 hover:text-red-500 transition-all active:scale-90 relative">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 18v1m9-9h1m-18 0h1m3.343-5.657l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707.707M12 7a5 5 0 110 10 5 5 0 010-10z"></path></svg>
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </button>

                <!-- Notifications -->
                @auth
                 <div class="relative" x-data="{ 
                    notif: false, 
                    alerts: [],
                    unread: 0,
                    getClickUrl(url) {
                        if (!url) return '#';
                        try {
                            if (url.startsWith('/') || url.startsWith('#')) return url;
                            const parsed = new URL(url);
                            return parsed.pathname + parsed.search + parsed.hash;
                        } catch (e) {
                            return url;
                        }
                    },
                    init() {
                        this.fetchNotifications();

                        @if(Auth::check() && Auth::user()->role !== 'admin')
                            if (window.Echo) {
                                try {
                                    window.Echo.private('App.Models.User.{{ auth()->id() }}')
                                        .listen('UserNotification', (e) => {
                                            this.alerts.unshift({
                                                title: e.title || e.message,
                                                click_url: e.click_url || e.url,
                                                created_at: 'Just now'
                                            });
                                            this.unread++;
                                        });
                                } catch(echoErr) {
                                    console.warn('[Notifications] Echo connection failed, using polling fallback.');
                                    this.startPolling();
                                }
                            } else {
                                // No WebSocket available — use polling fallback (works on cPanel/shared hosting)
                                this.startPolling();
                            }
                        @endif
                    },
                    fetchNotifications() {
                        fetch('{{ route('notifications') }}', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                            .then(r => r.json())
                            .then(data => {
                                if (data) {
                                    this.alerts = data.alerts || [];
                                    this.unread = data.unread_count || 0;
                                }
                            })
                            .catch(() => {});
                    },
                    startPolling() {
                        // Poll every 30 seconds for new notifications
                        setInterval(() => {
                            if (document.hidden) return; // Skip if tab not visible
                            this.fetchNotifications();
                        }, 30000);
                    }
                }">
                    <button @click="notif = !notif; 
                                   if(notif) { 
                                       unread = 0; 
                                       fetch('{{ route('notifications.read_all') }}', { 
                                           method: 'POST', 
                                           headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } 
                                       }); 
                                   }" 
                            class="w-9 h-9 sm:w-11 sm:h-11 bg-gray-50 dark:bg-white/5 rounded-xl sm:rounded-2xl flex items-center justify-center text-gray-400 hover:text-red-500 transition-all relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span x-show="unread > 0" x-cloak x-text="unread" class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 bg-red-600 border-2 border-white dark:border-[#121212] rounded-full text-[9px] font-black text-white flex items-center justify-center shadow-lg shadow-red-500/20" style="display: none;"></span>
                    </button>
                    <div x-show="notif" @click.away="notif = false" x-transition 
                         class="fixed md:absolute left-1/2 -translate-x-1/2 md:left-auto md:right-0 md:translate-x-0 mt-4 w-[90vw] md:w-80 bg-white dark:bg-[#1E1E1E] shadow-2xl rounded-3xl border border-gray-100 dark:border-white/5 py-4 z-50 overflow-hidden" 
                         style="display: none;">
                         <div class="px-6 py-2 border-b border-gray-50 dark:border-white/5 pb-4 flex justify-between items-center">
                             <h3 class="font-black text-sm dark:text-white uppercase tracking-widest">Alerts</h3>
                             <span x-show="unread > 0" x-text="unread + ' New'" class="text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-black"></span>
                         </div>
                         <div class="max-h-96 overflow-y-auto w-full">
                            <template x-if="alerts.length === 0">
                                <div class="px-6 py-8 text-center text-gray-400 text-[10px] font-black uppercase tracking-widest">No notifications</div>
                            </template>
                             <template x-for="alert in alerts">
                                <a :href="getClickUrl(alert.click_url)" class="group block px-6 py-4 hover:bg-gray-50 dark:hover:bg-white/[0.03] border-b border-gray-50 dark:border-white/5 transition-all">
                                    <div class="flex gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                            <span class="material-symbols-rounded text-base text-gray-400 group-hover:text-red-500" 
                                                  x-text="alert.title.toLowerCase().includes('reel') ? 'movie' : (alert.title.toLowerCase().includes('video') ? 'play_circle' : (alert.title.toLowerCase().includes('mention') ? 'alternate_email' : 'notifications'))"></span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[11px] font-black text-gray-800 dark:text-gray-200 uppercase tracking-tight leading-tight" x-text="alert.title"></p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-[8px] font-black text-white px-1.5 py-0.5 rounded uppercase tracking-widest"
                                                      :class="alert.title.toLowerCase().includes('reel') ? 'bg-orange-500' : 'bg-blue-500'"
                                                      x-text="alert.title.toLowerCase().includes('reel') ? 'Reel' : 'Video'"></span>
                                                <p class="text-[9px] text-gray-400 font-bold" x-text="alert.created_at"></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </template>
                         </div>
                    </div>
                </div>
                @endauth

                @if(Auth::check() && Auth::user()->role !== 'admin')
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-gray-100 dark:bg-white/5 flex items-center justify-center font-black text-gray-900 dark:text-white shadow-sm border border-gray-100 dark:border-white/5 overflow-hidden active:scale-95 transition-transform uppercase group relative">
                                @php
                                    $name = Auth::user()->channel?->name ?? Auth::user()->fullname ?? Auth::user()->username ?? '??';
                                    $words = explode(' ', trim($name));
                                    $initials = count($words) >= 2 
                                        ? strtoupper(substr($words[0], 0, 1) . substr($words[count($words)-1], 0, 1))
                                        : (strlen($name) >= 2 
                                            ? strtoupper(substr($name, 0, 1) . substr($name, -1)) 
                                            : strtoupper(substr($name, 0, 2)));
                                @endphp

                                @if(Auth::user()->channel?->avatar)
                                    <img src="{{ getImage(getFilePath('channelAvatar') . '/' . Auth::user()->channel->avatar) }}" 
                                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 z-10"
                                         onerror="this.style.display='none';">
                                @elseif(Auth::user()->image)
                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . Auth::user()->image) }}" 
                                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 z-10"
                                         onerror="this.style.display='none';">
                                @endif

                                <div class="w-full h-full gradient-orange flex items-center justify-center text-white">
                                    <span class="text-sm font-black">{{ $initials }}</span>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-4 py-3 border-b border-gray-50 dark:border-white/5 mb-2">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Account</p>
                                <p class="text-sm font-bold truncate dark:text-white">{{ Auth::user()->fullname }}</p>
                                <p class="text-[11px] text-gray-400 truncate dark:text-gray-500 font-medium">{{ Auth::user()->email }}</p>
                            </div>
                            <x-dropdown-link :href="route('studio.dashboard')">Dashboard</x-dropdown-link>
                            @if(Auth::user()->channel)
                                <x-dropdown-link :href="route('channels.show', Auth::user()->channel)">Your Channel</x-dropdown-link>
                            @endif
                            <x-dropdown-link :href="route('user.plans.purchased')">Purchased Plans</x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">Your Profile</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">@csrf<x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600 font-bold">Sign Out</x-dropdown-link></form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="flex items-center space-x-1 sm:space-x-2 px-3 py-1.5 sm:px-4 sm:py-2 md:px-6 md:py-3 gradient-orange text-white rounded-xl md:rounded-2xl font-black text-[10px] sm:text-[11px] md:text-[13px] uppercase tracking-widest transition-all shadow-lg shadow-orange-200/50">Sign In</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Mobile Drawer (Full & Premium) -->
    <div x-show="mobileMenu" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-0 z-[100] lg:hidden" style="display: none;">
        
        <div @click="mobileMenu = false" class="absolute inset-0 bg-transparent transition-opacity"></div>

        <div class="absolute inset-y-0 left-0 w-56 bg-white dark:bg-[#0F0F0F] flex flex-col overflow-hidden transition-all duration-300 mobile-drawer-container">
            <style>
                .mobile-drawer-container .py-8 {
                    padding-top: 1.5rem !important;
                    padding-bottom: 0rem !important;
                }
            </style>
            <!-- Header -->
            <div class="h-12 flex items-center justify-between px-4 border-b border-gray-50 dark:border-white/5 shrink-0">
                <div class="flex items-center space-x-2.5">
                    <div class="w-7 h-7 gradient-orange rounded-md p-[1px] shadow-lg overflow-hidden">
                        <div class="w-full h-full bg-white rounded-[5px] flex items-center justify-center p-1">
                            <img src="{{ siteLogo() }}" class="w-full h-full object-contain" alt="{{ gs('site_name') }}">
                        </div>
                    </div>
                    <span class="text-base font-bold text-[#FF4B2B] uppercase tracking-tight">{{ $siteSettings->site_name }}</span>
                </div>
                <button @click="mobileMenu = false" class="p-1.5 text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>

            <div class="flex-1 overflow-y-auto py-0 custom-scrollbar">
                @auth
                @if(Auth::user()->role !== 'admin')
                <!-- Quick Create Section (App Style) -->
                <div class="px-3 py-3 border-b border-gray-50 dark:border-white/5 bg-gray-50/50 dark:bg-white/[0.02]">
                    @if(Auth::user()->isCreator() && Auth::user()->channel)
                    <div class="space-y-1.5">
                        {{-- Upload Video --}}
                        <a href="{{ route('videos.create') }}" class="w-full h-10 rounded-xl gradient-orange flex items-center px-3 justify-between group active:scale-95 transition-all shadow-md shadow-orange-500/20">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-white/20 backdrop-blur-md flex items-center justify-center text-white border border-white/20 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-rounded text-base">videocam</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[9.5px] font-black text-white uppercase tracking-widest leading-none">Upload Video</span>
                                    <span class="text-[6px] font-bold text-white/60 uppercase tracking-widest mt-1 leading-none">Long-form content</span>
                                </div>
                            </div>
                            <span class="material-symbols-rounded text-white/40 text-[13px] group-hover:translate-x-1 transition-transform">chevron_right</span>
                        </a>
                        {{-- Upload Reel --}}
                        <a href="{{ route('reels.create') }}" class="w-full h-10 rounded-xl bg-gradient-to-r from-purple-600 to-pink-500 flex items-center px-3 justify-between group active:scale-95 transition-all shadow-md shadow-purple-500/20">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-white/20 backdrop-blur-md flex items-center justify-center text-white border border-white/20 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-rounded text-base">slow_motion_video</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[9.5px] font-black text-white uppercase tracking-widest leading-none">Upload Reel</span>
                                    <span class="text-[6px] font-bold text-white/60 uppercase tracking-widest mt-1 leading-none">Short-form · Max 90s</span>
                                </div>
                            </div>
                            <span class="material-symbols-rounded text-white/40 text-[13px] group-hover:translate-x-1 transition-transform">chevron_right</span>
                        </a>
                    </div>
                    @elseif(Auth::user()->isCreator() && !Auth::user()->channel)
                    {{-- Create Channel CTA (Creator without channel) --}}
                    <a href="{{ route('channels.create') }}" class="w-full h-11 rounded-xl bg-gray-900 dark:bg-white/10 flex items-center px-3.5 justify-between group active:scale-95 transition-all shadow-md shadow-black/20">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/10 backdrop-blur-md flex items-center justify-center text-white border border-white/10 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-rounded text-[18px]">add_circle</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-white uppercase tracking-widest leading-none">Create Channel</span>
                                <span class="text-[6.5px] font-bold text-white/60 uppercase tracking-widest mt-1 leading-none">Set up your brand</span>
                            </div>
                        </div>
                        <span class="material-symbols-rounded text-white/40 text-[13px] group-hover:translate-x-1 transition-transform">chevron_right</span>
                    </a>
                    @else
                    {{-- Join as Creator CTA --}}
                    <a href="{{ route('channels.create') }}" style="background: linear-gradient(to right, #7c3aed, #4f46e5);" class="w-full h-11 rounded-xl flex items-center px-3.5 justify-between group active:scale-95 transition-all shadow-md shadow-violet-500/20">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/10 backdrop-blur-md flex items-center justify-center text-white border border-white/10 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-rounded text-[18px]">rocket_launch</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-white uppercase tracking-widest leading-none">Be a Creator</span>
                                <span class="text-[6.5px] font-bold text-white/60 uppercase tracking-widest mt-1 leading-none">Start your journey today</span>
                            </div>
                        </div>
                        <span class="material-symbols-rounded text-white/40 text-[13px] group-hover:translate-x-1 transition-transform">chevron_right</span>
                    </a>
                    @endif
                </div>
                @endif
                @endauth

                <!-- Theme Controller (Native Android Style) -->
                <div class="px-3 py-3 border-b border-gray-50 dark:border-white/5">
                    <button @click="toggleDark()" class="w-full h-10 bg-gray-50 dark:bg-white/5 rounded-[0.85rem] flex items-center justify-between px-3 group active:scale-95 transition-all">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-white dark:bg-[#1A1A1A] flex items-center justify-center text-gray-500 shadow-sm border border-gray-100 dark:border-white/5 group-hover:text-orange-500">
                                <span class="material-symbols-rounded text-base" x-show="!darkMode">light_mode</span>
                                <span class="material-symbols-rounded text-base" x-show="darkMode">dark_mode</span>
                            </div>
                            <span x-text="darkMode ? 'Dark Mode' : 'Light Mode'" class="text-[10px] font-black text-gray-900 dark:text-white uppercase tracking-wider whitespace-nowrap"></span>
                        </div>
                        <div class="w-8 h-4 rounded-full bg-gray-200 dark:bg-white/10 relative shrink-0">
                            <div :class="darkMode ? 'translate-x-4 bg-red-600' : 'translate-x-0 bg-gray-400'" class="absolute left-0 top-0 w-4 h-4 rounded-full transition-transform duration-300 shadow-md"></div>
                        </div>
                    </button>
                </div>

                <!-- Premium & Platforms -->
                <div class="px-4 py-3.5 border-b border-gray-50 dark:border-white/5">
                    <p class="text-[8.5px] font-black text-gray-400 dark:text-white/20 uppercase tracking-[0.25em] mb-2 px-2">Discover & Premium</p>
                    <nav aria-label="Mobile User Menu" class="space-y-0.5">
                        <a href="{{ route('home') }}" class="flex items-center px-3 py-2 rounded-[0.85rem] {{ request()->routeIs('home') && !request()->category ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5' }} transition-all font-bold text-[12px]">
                            <span class="material-symbols-rounded mr-2.5 text-[18px] {{ request()->routeIs('home') && !request()->category ? 'text-white' : 'infinite-icon icon-home' }}">home</span>
                            Home
                        </a>
                        <a href="{{ route('trending') }}" class="flex items-center px-3 py-2 rounded-[0.85rem] {{ request()->routeIs('trending') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5' }} transition-all font-bold text-[12px]">
                            <span class="material-symbols-rounded mr-2.5 text-[18px] {{ request()->routeIs('trending') ? 'text-white' : 'infinite-icon icon-trending' }}">trending_up</span>
                            Trending
                        </a>
                        <a href="{{ route('reels.index') }}" class="flex items-center px-3 py-2 rounded-[0.85rem] {{ request()->routeIs('reels.index') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5' }} transition-all font-bold text-[12px]">
                            <span class="material-symbols-rounded mr-2.5 text-[18px] {{ request()->routeIs('reels.index') ? 'text-white' : 'infinite-icon icon-reels' }}">slow_motion_video</span>
                            Reels
                        </a>
                        @php $miniOttNav = gs('mini_ott_status'); $miniOttNavSoon = $miniOttNav !== null && (int) $miniOttNav === 0; @endphp
                        <a href="{{ route('premium') }}" @if($miniOttNavSoon) @click.prevent="window.showMiniOttComingSoon(); if(navigator.vibrate) navigator.vibrate(20); mobileMenu=false" @endif class="flex items-center px-3 py-2 rounded-[0.85rem] {{ request()->routeIs('premium') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 bg-orange-500/5 border border-orange-500/10' }} transition-all font-bold text-[12px] cursor-pointer">
                            <span class="material-symbols-rounded mr-2.5 text-[18px] {{ request()->routeIs('premium') ? 'text-white' : 'infinite-icon icon-ott' }}">movie</span>
                            Mini OTT
                            @if($miniOttNavSoon)
                                <span class="ml-auto px-1.5 py-0.5 rounded-full bg-[#ff571a] text-white text-[7px] font-black uppercase tracking-widest">Soon</span>
                            @endif
                        </a>
                        <a href="{{ route('marketplace.index') }}" class="flex items-center px-3 py-2 rounded-[0.85rem] {{ request()->routeIs('marketplace.index') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5' }} transition-all font-bold text-[12px]">
                            <span class="material-symbols-rounded mr-2.5 text-[18px] {{ request()->routeIs('marketplace.index') ? 'text-white' : 'infinite-icon icon-marketplace' }}">hub</span>
                            Markethub
                        </a>
                    </nav>
                </div>

                <!-- Library -->
                @auth
                <div class="px-4 py-3.5 border-b border-gray-50 dark:border-white/5">
                    <p class="text-[8.5px] font-black text-gray-400 dark:text-white/20 uppercase tracking-[0.25em] mb-2 px-2">Library</p>
                    <nav aria-label="Mobile Creator Menu" class="space-y-0.5">
                        <a href="{{ route('history') }}" class="flex items-center px-3 py-2 rounded-[0.85rem] {{ request()->routeIs('history') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5' }} transition-all font-bold text-[12px]">
                            <span class="material-symbols-rounded mr-2.5 text-[18px] {{ request()->routeIs('history') ? 'text-white' : 'infinite-icon icon-history' }}">history</span>
                            History
                        </a>
                        <a href="{{ route('liked-videos') }}" class="flex items-center px-3 py-2 rounded-[0.85rem] {{ request()->routeIs('liked-videos') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5' }} transition-all font-bold text-[12px]">
                            <span class="material-symbols-rounded mr-2.5 text-[18px] {{ request()->routeIs('liked-videos') ? 'text-white' : 'infinite-icon icon-liked' }}">favorite</span>
                            Liked Videos
                        </a>
                        <a href="{{ route('playlists.index') }}" class="flex items-center px-3 py-2 rounded-[0.85rem] {{ request()->routeIs('playlists.index') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5' }} transition-all font-bold text-[12px]">
                            <span class="material-symbols-rounded mr-2.5 text-[18px] {{ request()->routeIs('playlists.index') ? 'text-white' : 'infinite-icon icon-playlist' }}">playlist_play</span>
                            Playlists
                        </a>
                        <a href="{{ route('watch-later') }}" class="flex items-center px-3 py-2 rounded-[0.85rem] {{ request()->routeIs('watch-later') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5' }} transition-all font-bold text-[12px]">
                            <span class="material-symbols-rounded mr-2.5 text-[18px] {{ request()->routeIs('watch-later') ? 'text-white' : 'infinite-icon icon-later' }}">schedule</span>
                            Watch Later
                        </a>
                        <a href="{{ route('user.plans.purchased') }}" class="flex items-center px-3 py-2 rounded-[0.85rem] {{ request()->routeIs('user.plans.purchased') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5' }} transition-all font-bold text-[12px]">
                            <span class="material-symbols-rounded mr-2.5 text-[18px] {{ request()->routeIs('user.plans.purchased') ? 'text-white' : 'text-emerald-500' }}">inventory_2</span>
                            My Plans
                        </a>
                    </nav>
                </div>
                @endauth

                <!-- Categories -->
                <div class="px-4 py-3.5 border-b border-gray-50 dark:border-white/5">
                    <p class="text-[8.5px] font-black text-gray-400 dark:text-white/20 uppercase tracking-[0.25em] mb-2 px-2">Categories</p>
                    <nav aria-label="Mobile Settings Menu" class="space-y-0.5">
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
                        @endphp
                        @foreach($categories as $category)
                            @php 
                                $slug = strtolower($category->slug);
                                $icon = 'category';
                                $colorClass = 'icon-default-cat';
                                
                                foreach($categoryIcons as $key => $val) {
                                    if(str_contains($slug, $key)) { 
                                        $icon = $val; 
                                        // Map slug keyword to the specific CSS class defined in app.blade.php
                                        if($key == 'music') $colorClass = 'icon-music';
                                        elseif($key == 'gaming') $colorClass = 'icon-gaming';
                                        elseif($key == 'movie' || $key == 'films') $colorClass = 'icon-films';
                                        elseif($key == 'entertainment') $colorClass = 'icon-entertainment';
                                        elseif($key == 'education') $colorClass = 'icon-education';
                                        elseif($key == 'news') $colorClass = 'icon-news';
                                        elseif($key == 'tech') $colorClass = 'icon-tech';
                                        elseif($key == 'sports') $colorClass = 'icon-sports';
                                        elseif($key == 'vlog') $colorClass = 'icon-vlog';
                                        elseif($key == 'cars') $colorClass = 'icon-cars';
                                        elseif($key == 'comedy') $colorClass = 'icon-comedy';
                                        elseif($key == 'cooking') $colorClass = 'icon-cooking';
                                        elseif($key == 'fashion') $colorClass = 'icon-fashion';
                                        elseif($key == 'film') $colorClass = 'icon-films';
                                        elseif($key == 'food') $colorClass = 'icon-food';
                                        elseif($key == 'kids') $colorClass = 'icon-kids';
                                        elseif($key == 'lifestyle') $colorClass = 'icon-lifestyle';
                                        elseif($key == 'live') $colorClass = 'icon-live';
                                        elseif($key == 'people') $colorClass = 'icon-people';
                                        elseif($key == 'pets') $colorClass = 'icon-pets';
                                        elseif($key == 'science') $colorClass = 'icon-science';
                                        elseif($key == 'talent') $colorClass = 'icon-talent';
                                        elseif($key == 'trending') $colorClass = 'icon-trending';
                                        break; 
                                    }
                                }
                                $icon = $category->icon ?: $icon;
                            @endphp
                            <a href="{{ route('category.show', $category->slug) }}" data-cat-link data-cat-id="{{ $category->id }}" data-cat-slug="{{ $category->slug }}" data-class-active="flex items-center px-3 py-2 rounded-[0.85rem] bg-orange-500 text-white font-bold text-[12px] transition-all" data-class-inactive="flex items-center px-3 py-2 rounded-[0.85rem] text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 font-bold text-[12px] transition-all" data-icon-active="material-symbols-rounded mr-2.5 text-[18px] text-white" data-icon-inactive="material-symbols-rounded mr-2.5 text-[18px] infinite-icon {{ $colorClass }}" class="flex items-center px-3 py-2 rounded-[0.85rem] {{ (strtolower(urldecode(request()->path())) === 'category/' . strtolower($category->slug) || request()->route('slug') === $category->slug) ? 'bg-orange-500 text-white active-menu-item' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5' }} font-bold text-[12px] transition-all">
                                <span class="material-symbols-rounded mr-2.5 text-[18px] {{ (strtolower(urldecode(request()->path())) === 'category/' . strtolower($category->slug) || request()->route('slug') === $category->slug) ? 'text-white' : 'infinite-icon ' . $colorClass }}">{{ $icon }}</span>
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </nav>
                </div>

                <!-- Legal & Support -->
                <div class="px-4 py-3.5">
                    <p class="text-[8.5px] font-black text-gray-400 dark:text-white/20 uppercase tracking-[0.25em] mb-2 px-2">Platform</p>
                    <nav aria-label="Mobile User Options" class="space-y-0.5">
                        <a href="{{ route('user.ticket.index') }}" class="flex items-center px-3 py-2 rounded-[0.85rem] {{ request()->routeIs('user.ticket.*') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5' }} transition-all font-bold text-[12px]">
                            <span class="material-symbols-rounded mr-2.5 text-[18px] {{ request()->routeIs('user.ticket.*') ? 'text-white' : 'infinite-icon icon-support' }}">support_agent</span>
                            Support Desk
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 rounded-[0.85rem] {{ request()->routeIs('profile.edit') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5' }} transition-all font-bold text-[12px]">
                            <span class="material-symbols-rounded mr-2.5 text-[18px] {{ request()->routeIs('profile.edit') ? 'text-white' : 'infinite-icon icon-settings' }}">settings</span>
                            Settings
                        </a>
                        <a href="{{ route('pages', 'about') }}" class="flex items-center px-3 py-2 rounded-[0.85rem] {{ request()->is('pages/about') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5' }} transition-all font-bold text-[12px]">
                            <span class="material-symbols-rounded mr-2.5 text-[18px] {{ request()->is('pages/about') ? 'text-white' : 'infinite-icon icon-about' }}">info</span>
                            About Us
                        </a>
                        @php
                            $policyPages = getContent('policy_pages.element');
                        @endphp
                        @foreach($policyPages as $policy)
                            @php
                                $policyTitle = strtolower($policy->data_values->title);
                                $icon = 'policy';
                                $policyColor = 'icon-policy';

                                if (str_contains($policyTitle, 'privacy')) {
                                    $icon = 'security';
                                    $policyColor = 'icon-privacy';
                                } elseif (str_contains($policyTitle, 'terms')) {
                                    $icon = 'gavel';
                                    $policyColor = 'icon-terms';
                                } elseif (str_contains($policyTitle, 'copyright')) {
                                    $icon = 'copyright';
                                    $policyColor = 'icon-copyright';
                                } elseif (str_contains($policyTitle, 'community')) {
                                    $icon = 'groups';
                                    $policyColor = 'icon-community';
                                }
                            @endphp
                            <a href="{{ route('policy.pages', [$policy->id, slug($policy->data_values->title)]) }}" class="flex items-center px-3 py-2 rounded-[0.85rem] {{ request()->url() == route('policy.pages', [$policy->id, slug($policy->data_values->title)]) ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5' }} transition-all font-bold text-[12px]">
                                <span class="material-symbols-rounded mr-2.5 text-[18px] {{ request()->url() == route('policy.pages', [$policy->id, slug($policy->data_values->title)]) ? 'text-white' : 'infinite-icon ' . $policyColor }}">{{ $icon }}</span>
                                {{ __($policy->data_values->title) }}
                            </a>
                        @endforeach
                    </nav>
                    
                    <!-- PWA Install Mobile Sidebar Promo -->
                    <div class="pwa-install-btn px-4 mt-6 mb-4 cursor-pointer group" style="display: none;">
                        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-orange-500/5 to-pink-500/5 border border-orange-500/10 p-3 sm:p-4 transition-all hover:border-orange-500/30 hover:bg-orange-500/10">
                            <!-- Background accent -->
                            <div class="absolute -right-4 -top-4 w-20 h-20 bg-gradient-to-br from-orange-500 to-pink-500 opacity-10 rounded-full blur-xl group-hover:opacity-20 transition-opacity"></div>
                            
                            <div class="relative z-10 flex flex-row items-center gap-2.5">
                                <div class="w-8 h-8 sm:w-9 sm:h-9 shrink-0 rounded-xl gradient-orange flex items-center justify-center text-white shadow-md shadow-orange-500/20">
                                    <span class="material-symbols-rounded text-[18px] sm:text-[20px]">android</span>
                                </div>
                                <h4 class="text-[9.5px] sm:text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-tight whitespace-nowrap">Install Lite APK</h4>
                            </div>
                        </div>
                        <img src="{{ siteLogo() }}" alt="App Icon" class="hidden">
                    </div>
                    
                    <div class="mt-6 text-center text-[8.5px] font-black text-gray-300 dark:text-gray-700 uppercase tracking-[0.4em] pb-8">
                        © {{ date('Y') }} {{ $siteSettings->site_name }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    window.formatComment = function(text) {
        if (!text) return '';
        let div = document.createElement('div');
        div.textContent = text;
        let escaped = div.innerHTML;
        return escaped.replace(/@(\w+)/g, '<a href="/@$1" class="text-orange-500 font-bold hover:underline transition-all">@$1</a>');
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Mobile sidebar auto-scroll — same behavior as the desktop sidebar:
        // exact active-link match + container-relative math (never scrolls the page).
        const mobileDrawer = document.querySelector('.mobile-drawer-container .flex-1.overflow-y-auto');
        if (!mobileDrawer) return;

        const norm = (p) => {
            if (!p) return '';
            try { p = decodeURIComponent(p); } catch (e) {}
            p = String(p).split('#')[0].split('?')[0].trim().toLowerCase();
            if (p.length > 1) p = p.replace(/\/+$/, '');
            if (p && !p.startsWith('/')) p = '/' + p;
            return p;
        };
        const hrefPath = (href) => {
            if (!href || href.startsWith('#') || href.startsWith('javascript:')) return '';
            try { return norm(new URL(href, window.location.origin).pathname); }
            catch (e) { return norm(href); }
        };
        const findExactActive = () => {
            const actives = Array.from(mobileDrawer.querySelectorAll('.active-menu-item'));
            if (!actives.length) return null;
            if (actives.length === 1) return actives[0];
            const currentPath = norm(window.location.pathname);
            // 1) exact href == current path (History, Liked, Playlists, Settings, ...)
            for (const el of actives) {
                if (hrefPath(el.getAttribute('href')) === currentPath) return el;
            }
            // 2) category data slug == current /category/<slug> (People, Blogs, Vlog, ...)
            for (const el of actives) {
                const slug = (el.getAttribute('data-cat-slug') || '').trim().toLowerCase();
                if (slug && currentPath === '/category/' + slug.replace(/^\/+|\/+$/g, '')) return el;
            }
            // 3) fallback: first active in DOM order, never the last
            return actives[0];
        };
        const scrollMobileToActive = () => {
            // drawer hidden (display:none / lg breakpoint) -> no layout, skip
            if (!mobileDrawer.getClientRects().length || !mobileDrawer.clientHeight) return false;
            const activeLink = findExactActive();
            if (!activeLink) return false;
            const dRect = mobileDrawer.getBoundingClientRect();
            const lRect = activeLink.getBoundingClientRect();
            if (!lRect.height && !lRect.width) return false;
            const target = mobileDrawer.scrollTop + (lRect.top - dRect.top) - (mobileDrawer.clientHeight / 2) + (lRect.height / 2);
            const max = Math.max(0, mobileDrawer.scrollHeight - mobileDrawer.clientHeight);
            mobileDrawer.scrollTo({ top: Math.min(Math.max(0, target), max), behavior: 'auto' });
            return true;
        };
        window.scrollMobileSidebarToActive = scrollMobileToActive;

        const scheduleScroll = () => {
            requestAnimationFrame(() => {
                scrollMobileToActive();
                // re-run after the 300ms slide transition + late layout shifts
                // (fonts/images above the active link move its exact point)
                setTimeout(scrollMobileToActive, 320);
                setTimeout(scrollMobileToActive, 1000);
            });
        };

        // Re-scroll on EVERY drawer open (scrollTop is lost while display:none),
        // not just the first one. The Alpine x-show wrapper toggles style.display.
        const drawerHost = mobileDrawer.closest('[x-show]') || mobileDrawer.parentElement;
        if (drawerHost) {
            const observer = new MutationObserver(() => {
                if (mobileDrawer.getClientRects().length) scheduleScroll();
            });
            observer.observe(drawerHost, { attributes: true, attributeFilter: ['style'] });
        }

        // page-level events (back/forward while drawer open, late assets, etc.)
        window.addEventListener('popstate', scheduleScroll);
        window.addEventListener('hashchange', scheduleScroll);
        window.addEventListener('load', scheduleScroll);
        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(() => setTimeout(scrollMobileToActive, 50));
        }
        scheduleScroll();
    });
</script>
