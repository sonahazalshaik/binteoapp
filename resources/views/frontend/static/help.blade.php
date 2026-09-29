<x-app-layout>
    @php
        $supportElements = getContent('support.element');
        $quickActions = $supportElements->where('data_values.is_faq', '0');
        $faqArticles = $supportElements->where('data_values.is_faq', '1');
    @endphp

    <style>
        /* Modern Native App / Material You Inspired Aesthetics */
        .android-squircle {
            border-radius: 1.25rem;
        }
        .android-widget {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 20px -5px rgba(0,0,0,0.03), inset 0 1px 0 rgba(255,255,255,0.8);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .dark .android-widget {
            background: rgba(20, 20, 20, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.03);
            box-shadow: 0 4px 20px -5px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.03);
        }
        .android-widget:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -10px rgba(255, 87, 26, 0.15), inset 0 1px 0 rgba(255,255,255,1);
        }
        .dark .android-widget:hover {
            box-shadow: 0 10px 25px -10px rgba(255, 87, 26, 0.2), inset 0 1px 0 rgba(255,255,255,0.08);
        }
        
        .premium-gradient-text {
            background: linear-gradient(135deg, #ff571a 0%, #ff8c00 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .immersive-header {
            background: linear-gradient(180deg, rgba(255, 87, 26, 0.05) 0%, rgba(255, 255, 255, 0) 100%);
        }
        .dark .immersive-header {
            background: linear-gradient(180deg, rgba(255, 87, 26, 0.1) 0%, rgba(10, 10, 10, 0) 100%);
        }
        .glowing-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            animation: floatBlob 12s infinite ease-in-out alternate;
        }
        @keyframes floatBlob {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, -30px) scale(1.1); }
        }
        
        .search-bar-modern {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .search-bar-modern:focus-within {
            transform: scale(1.01);
            box-shadow: 0 15px 35px -10px rgba(255, 87, 26, 0.15);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,87,26,0.3); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255,87,26,0.5); }

        .tab-btn {
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
    </style>

    <div x-data="{ 
        search: '',
        activeTab: 'all',
        hasResults: true,
        checkResults() {
            this.$nextTick(() => {
                let faqVisible = 0;
                let actionVisible = 0;
                
                if (this.activeTab === 'all' || this.activeTab === 'faqs') {
                    faqVisible = Array.from(document.querySelectorAll('.faq-item')).filter(el => el.style.display !== 'none').length;
                }
                if (this.activeTab === 'all' || this.activeTab === 'actions') {
                    actionVisible = Array.from(document.querySelectorAll('.action-item')).filter(el => el.style.display !== 'none').length;
                }
                
                this.hasResults = (faqVisible + actionVisible) > 0;
            });
        },
        clearSearch() {
            this.search = '';
            this.checkResults();
        }
    }" x-init="checkResults()" x-effect="checkResults(); search; activeTab;" class="min-h-screen bg-[#fafafa] dark:bg-[#0a0a0a] pb-32 transition-colors duration-500 relative overflow-hidden">
        
        <!-- Abstract Background Orbs -->
        <div class="glowing-blob bg-rose-500/10 dark:bg-rose-600/10 w-[300px] h-[300px] top-[-50px] left-[-50px]"></div>
        <div class="glowing-blob bg-orange-500/10 dark:bg-orange-600/10 w-[400px] h-[400px] top-[15%] right-[-150px]" style="animation-delay: -5s;"></div>

        <!-- Hero Section -->
        <div class="immersive-header pt-16 pb-8 px-4 sm:px-6 relative z-10">
            <div class="max-w-[800px] mx-auto text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-tr from-[#ff571a] to-[#ff8c00] text-white shadow-xl shadow-orange-500/20 mb-4 transform -rotate-3 hover:rotate-0 transition-transform duration-300">
                    <span class="material-symbols-rounded text-2xl">volunteer_activism</span>
                </div>
                
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-gray-900 dark:text-white tracking-tight mb-3 leading-none">
                    How can we <span class="premium-gradient-text">help?</span>
                </h1>
                
                <p class="text-gray-400 dark:text-gray-500 font-bold uppercase tracking-[0.15em] text-[9px] sm:text-[10px] mb-6">Search our knowledge base or get in touch</p>
                
                <!-- Search Input Form -->
                <div class="max-w-xl mx-auto relative search-bar-modern flex items-center">
                    <div class="absolute inset-y-0 left-5 flex items-center text-gray-400 pointer-events-none">
                        <span class="material-symbols-rounded text-xl sm:text-2xl">search</span>
                    </div>
                    <input type="text" x-model="search" @input="checkResults()" placeholder="Describe your issue or search..." 
                           class="w-full h-12 sm:h-14 bg-white/90 dark:bg-[#151515]/90 backdrop-blur-xl border border-gray-200 dark:border-white/5 rounded-2xl pl-12 sm:pl-14 pr-32 text-xs sm:text-sm font-bold text-gray-950 dark:text-white shadow-md outline-none placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:border-[#ff571a]/50 focus:ring-1 focus:ring-[#ff571a]/50">
                    
                    <!-- Search/Clear Buttons -->
                    <div class="absolute right-2 flex items-center gap-1.5">
                        <template x-if="search.length > 0">
                            <button @click="clearSearch()" type="button" class="w-6 h-6 rounded-full bg-gray-100 dark:bg-white/10 text-gray-500 dark:text-gray-400 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-white/20 transition-all">
                                <span class="material-symbols-rounded text-[12px]">close</span>
                            </button>
                        </template>
                        <button type="button" @click="checkResults()" class="h-8 sm:h-10 px-4 bg-gradient-to-r from-[#ff571a] to-[#ff8c00] text-white rounded-xl font-bold text-[10px] sm:text-xs uppercase tracking-wider shadow-md hover:shadow-orange-500/20 transition-all active:scale-95">
                            Search
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="max-w-[1000px] mx-auto px-4 sm:px-6 relative z-20 space-y-8">
            
            <!-- Quick Ticket Submission Hint (appears when typing) -->
            <div x-show="search.length > 0" class="android-widget android-squircle p-4 border border-[#ff571a]/20 bg-gradient-to-r from-orange-50/50 to-rose-50/50 dark:from-orange-950/10 dark:to-rose-950/10 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left transition-all duration-300">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-rounded text-[#ff571a] animate-pulse">info</span>
                    <div>
                        <h4 class="text-xs font-black text-gray-900 dark:text-white">Looking to submit a support ticket?</h4>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400">You can instantly convert your query "<span class="text-[#ff571a] font-bold" x-text="search"></span>" into a direct support ticket.</p>
                    </div>
                </div>
                <a :href="'{{ route('user.ticket.create') }}?subject=' + encodeURIComponent(search)" class="shrink-0 px-4 py-2 bg-gradient-to-r from-[#ff571a] to-[#ff8c00] text-white text-[9px] font-black uppercase tracking-widest rounded-lg shadow-md hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Create Ticket
                </a>
            </div>

            <!-- Category Tabs for App Feel Navigation -->
            <div class="flex justify-center p-1 bg-white/60 dark:bg-white/5 backdrop-blur-md border border-gray-200 dark:border-white/5 rounded-xl max-w-[280px] mx-auto">
                <button @click="activeTab = 'all'" :class="activeTab === 'all' ? 'bg-[#ff571a] text-white shadow-md shadow-orange-500/20' : 'text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white'" class="tab-btn flex-1 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded-lg">All</button>
                <button @click="activeTab = 'actions'" :class="activeTab === 'actions' ? 'bg-[#ff571a] text-white shadow-md shadow-orange-500/20' : 'text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white'" class="tab-btn flex-1 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded-lg">Actions</button>
                <button @click="activeTab = 'faqs'" :class="activeTab === 'faqs' ? 'bg-[#ff571a] text-white shadow-md shadow-orange-500/20' : 'text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white'" class="tab-btn flex-1 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded-lg">FAQs</button>
            </div>

            <!-- Dynamic Search No Results State -->
            <div x-show="!hasResults" class="android-widget android-squircle p-6 sm:p-8 text-center max-w-md mx-auto my-6 animate-fade-in" style="display: none;">
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-950/30 rounded-full flex items-center justify-center text-[#ff571a] mx-auto mb-3">
                    <span class="material-symbols-rounded text-2xl">search_off</span>
                </div>
                <h3 class="text-sm font-black text-gray-900 dark:text-white mb-1.5">No results found</h3>
                <p class="text-[11px] text-gray-400 dark:text-gray-500 mb-4 leading-relaxed">No matching resources were found for "<span x-text="search"></span>". Need direct assistance?</p>
                <a :href="'{{ route('user.ticket.create') }}?subject=' + encodeURIComponent(search)" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-[#ff571a] to-[#ff8c00] text-white font-bold text-[10px] uppercase tracking-widest rounded-lg shadow-md hover:shadow-orange-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    <span class="material-symbols-rounded text-xs">edit_square</span>
                    Submit Support Ticket
                </a>
            </div>

            <div x-show="hasResults" class="space-y-8">
                <!-- Quick Actions Grid -->
                <div x-show="activeTab === 'all' || activeTab === 'actions'" class="space-y-3">
                    <div class="flex items-center gap-3 px-1">
                        <span class="material-symbols-rounded text-[#ff571a] text-xl">bolt</span>
                        <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-wider">Quick Actions</h2>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach($quickActions as $action)
                        <div data-search-text="{{ e(strtolower(strip_tags(__($action->data_values->title) . ' ' . __($action->data_values->content)))) }}"
                             x-show="search === '' || $el.getAttribute('data-search-text').includes(search.toLowerCase())"
                             class="android-widget android-squircle p-4 group cursor-pointer relative overflow-hidden action-item">
                            <div class="absolute inset-0 bg-gradient-to-br from-[#ff571a]/0 to-[#ff571a]/3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="w-9 h-9 rounded-xl bg-gray-100 dark:bg-white/5 text-[#ff571a] flex items-center justify-center mb-3 group-hover:bg-[#ff571a] group-hover:text-white transition-all duration-300 relative z-10">
                                <span class="material-symbols-rounded text-[18px]">{{ $action->data_values->icon }}</span>
                            </div>
                            <h3 class="text-[11px] font-black text-gray-900 dark:text-white mb-1 relative z-10">{{ __($action->data_values->title) }}</h3>
                            <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500 line-clamp-2 leading-tight relative z-10">{{ __($action->data_values->content) }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- FAQ Section -->
                    <div class="lg:col-span-2 space-y-3" x-show="activeTab === 'all' || activeTab === 'faqs'">
                        <div class="flex items-center gap-3 px-1">
                            <span class="material-symbols-rounded text-[#ff571a] text-xl">forum</span>
                            <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-wider">Popular Questions</h2>
                        </div>
                        
                        <div class="space-y-2">
                            @foreach($faqArticles as $article)
                            <div x-data="{ open: false }" 
                                 data-search-text="{{ e(strtolower(strip_tags(__($article->data_values->title) . ' ' . __($article->data_values->content)))) }}"
                                 x-show="search === '' || $el.getAttribute('data-search-text').includes(search.toLowerCase())"
                                 class="android-widget android-squircle overflow-hidden faq-item">
                                <button @click="open = !open" class="w-full p-4 flex items-center justify-between text-left relative z-10 focus:outline-none">
                                    <div class="flex items-center gap-3 pr-2">
                                        <div class="w-8 h-8 shrink-0 rounded-lg bg-gradient-to-br from-gray-100 to-white dark:from-white/10 dark:to-white/5 border border-gray-200/30 dark:border-white/5 flex items-center justify-center text-[#ff571a]">
                                            <span class="material-symbols-rounded text-base">{{ $article->data_values->icon ?? 'quiz' }}</span>
                                        </div>
                                        <span class="text-xs font-black text-gray-800 dark:text-white leading-tight">{{ __($article->data_values->title) }}</span>
                                    </div>
                                    <div class="w-7 h-7 shrink-0 rounded-full bg-gray-50 dark:bg-black/20 flex items-center justify-center transition-transform duration-300" :class="open ? 'rotate-180 bg-[#ff571a]/10 dark:bg-[#ff571a]/20' : ''">
                                        <span class="material-symbols-rounded text-gray-400 text-sm" :class="open ? 'text-[#ff571a]' : ''">keyboard_arrow_down</span>
                                    </div>
                                </button>
                                <div x-show="open" x-collapse>
                                    <div class="px-4 pb-4 pt-0 ml-11">
                                        <div class="text-[11px] font-medium text-gray-400 dark:text-gray-500 leading-relaxed border-l-2 border-[#ff571a]/20 pl-3">
                                            {{ __($article->data_values->content) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Side Info -->
                    <div class="space-y-4 lg:mt-9">
                        <!-- Support Card -->
                        <div class="android-squircle p-6 bg-gradient-to-br from-[#121212] to-[#222222] dark:from-[#1e1e1e] dark:to-[#121212] border border-white/5 text-white relative overflow-hidden shadow-xl group hover:-translate-y-1 transition-all duration-300">
                            <!-- Abstract shapes -->
                            <div class="absolute -right-8 -top-8 w-28 h-28 bg-[#ff571a] rounded-full blur-2xl opacity-15 group-hover:opacity-25 transition-opacity"></div>
                            
                            <div class="relative z-10">
                                <div class="w-9 h-9 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center mb-4">
                                    <span class="material-symbols-rounded text-[#ff571a] text-xl">support_agent</span>
                                </div>
                                
                                <h3 class="text-base font-black mb-1 tracking-tight">Need Direct Help?</h3>
                                <p class="text-white/50 text-[9px] font-bold uppercase tracking-[0.15em] mb-5">Our expert team is online 24/7</p>
                                
                                <div class="space-y-2.5 font-bold">
                                    <a :href="search ? '{{ route('user.ticket.create') }}?subject=' + encodeURIComponent(search) : '{{ route('user.ticket.create') }}'" class="w-full h-10 bg-gradient-to-r from-[#ff571a] to-[#ff8c00] text-white rounded-xl font-black text-[9px] uppercase tracking-widest flex items-center justify-center gap-2 shadow-md hover:shadow-orange-500/20 hover:scale-[1.01] active:scale-[0.99] transition-all">
                                        <span class="material-symbols-rounded text-sm">edit_square</span>
                                        Start a Ticket
                                    </a>
                                    <a href="{{ route('user.ticket.index') }}" class="w-full h-10 bg-white/5 border border-white/10 text-white rounded-xl font-black text-[9px] uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-white/10 hover:border-white/20 transition-all">
                                        <span class="material-symbols-rounded text-sm">history</span>
                                        Track Requests
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- App Status -->
                        <div class="android-widget android-squircle p-5 flex flex-col items-center text-center">
                            <div class="relative w-12 h-12 flex items-center justify-center mb-3">
                                <div class="absolute inset-0 bg-emerald-500/20 rounded-full animate-ping"></div>
                                <div class="w-9 h-9 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center text-white shadow-md relative z-10">
                                    <span class="material-symbols-rounded text-lg">done_all</span>
                                </div>
                            </div>
                            <h4 class="text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-wider mb-1">Systems Operational</h4>
                            <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500">All services are running smoothly globally.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Support Button (Mobile only) -->
        <a :href="search ? '{{ route('user.ticket.create') }}?subject=' + encodeURIComponent(search) : '{{ route('user.ticket.create') }}'" class="fixed md:hidden bottom-24 right-4 sm:bottom-6 sm:right-6 w-12 h-12 bg-gradient-to-r from-[#ff571a] to-[#ff8c00] text-white rounded-full flex items-center justify-center shadow-lg z-50 hover:scale-110 active:scale-95 transition-all">
            <span class="material-symbols-rounded text-xl">add_comment</span>
        </a>

        <!-- Mobile Bottom Nav Hint -->
        <!-- <div class="fixed bottom-6 left-4 right-4 md:hidden z-40">
            <div class="bg-white/90 dark:bg-black/90 backdrop-blur-xl border border-gray-200/80 dark:border-white/10 rounded-2xl p-2.5 flex items-center justify-around shadow-lg">
                <a href="{{ route('help') }}" class="text-[#ff571a] flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full bg-[#ff571a]/10 flex items-center justify-center mb-0.5">
                        <span class="material-symbols-rounded text-lg">home</span>
                    </div>
                    <span class="text-[7px] font-black uppercase tracking-widest">Help</span>
                </a>
                <a href="{{ route('user.ticket.index') }}" class="text-gray-400 dark:text-gray-500 flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center mb-0.5">
                        <span class="material-symbols-rounded text-lg">history</span>
                    </div>
                    <span class="text-[7px] font-black uppercase tracking-widest">Tickets</span>
                </a>
                <a href="{{ route('user.kyc.form') }}" class="text-gray-400 dark:text-gray-500 flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center mb-0.5">
                        <span class="material-symbols-rounded text-lg">verified_user</span>
                    </div>
                    <span class="text-[7px] font-black uppercase tracking-widest">Verify</span>
                </a>
            </div>
        </div> -->

    </div>
</x-app-layout>
