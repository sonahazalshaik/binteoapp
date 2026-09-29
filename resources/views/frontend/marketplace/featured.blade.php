@extends('layouts.app')

@section('content')
<div id="marketplace-featured" class="min-h-screen bg-white dark:bg-[#0A0A0A] transition-colors duration-500 pb-20 pt-8" 
     x-data="{ 
        loading: false,
        type: '{{ request('type') }}',
        location: '{{ request('location') }}',
        search: '{{ request('search') }}',
        showDropdown: false,
        async fetchProfiles(pageUrl = null) {
            this.loading = true;
            const url = pageUrl ? new URL(pageUrl) : new URL(window.location.href);
            if (!pageUrl) {
                if (this.type) url.searchParams.set('type', this.type); else url.searchParams.delete('type');
                if (this.location) url.searchParams.set('location', this.location); else url.searchParams.delete('location');
                if (this.search) url.searchParams.set('search', this.search); else url.searchParams.delete('search');
            }
            
            window.history.pushState({}, '', url);
            
            try {
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const contentType = response.headers.get('content-type') || '';
                if (!response.ok || !contentType.includes('application/json')) {
                    throw new Error('Expected JSON, got: ' + contentType);
                }
                const data = await response.json();
                document.getElementById('profiles-container').innerHTML = data.html;
                document.getElementById('pagination-container').innerHTML = data.pagination;
            } catch (error) {
                console.error('Error fetching profiles:', error);
            }
            this.loading = false;
        },
        setType(val) {
            this.type = val;
            this.showDropdown = false;
            this.fetchProfiles();
        },
        resetFilters() {
            this.type = '';
            this.location = '';
            this.search = '';
            this.fetchProfiles();
        },
        getActiveLabel() {
            return this.type ? this.type : 'All Roles';
        }
     }">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-10">
            <!-- Mobile Back Button -->
            <div class="block md:hidden mb-[-1rem]">
                <a href="{{ route('marketplace.index') }}" class="inline-flex items-center gap-2 text-gray-500 dark:text-gray-400 hover:text-orange-500 dark:hover:text-orange-400 font-bold text-sm transition-colors uppercase tracking-wider">
                    <span class="material-symbols-rounded text-lg">arrow_back</span>
                    Marketplace
                </a>
            </div>

            <div>
                <div class="flex items-center gap-3 mb-4 mt-2 md:mt-0">
                    <div class="w-10 h-10 rounded-2xl bg-orange-500/10 text-orange-500 flex items-center justify-center">
                        <span class="material-symbols-rounded">stars</span>
                    </div>
                    <span class="text-xs font-black text-orange-500 uppercase tracking-[0.25em]">Handpicked Excellence</span>
                </div>
                <h1 class="text-4xl md:text-6xl font-black tracking-tight dark:text-white">Featured Profiles</h1>
                <p class="text-gray-400 font-medium mt-4 text-lg">Discovery our most elite and handpicked professional profiles.</p>
            </div>
            
            <!-- Desktop Back Button -->
            <a href="{{ route('marketplace.index') }}" class="hidden md:flex group relative items-center gap-3 px-8 py-3.5 bg-gradient-to-r from-orange-500 to-rose-500 hover:from-orange-600 hover:to-rose-600 rounded-full text-xs font-black uppercase tracking-[0.2em] text-white shadow-xl shadow-orange-500/20 transition-all duration-300 overflow-hidden hover:scale-105 active:scale-95">
                <span class="material-symbols-rounded text-lg relative z-10 group-hover:-translate-x-1.5 transition-transform duration-500">arrow_back</span>
                <span class="relative z-10">Back</span>
            </a>
        </div>

        <!-- Optimized Responsive Filter Bar (AJAX) -->
        <div id="marketplace-filter-bar" class="mb-6 md:mb-12 sticky top-14 lg:top-20 z-30 bg-white/80 dark:bg-[#0A0A0A]/80 backdrop-blur-xl py-2 md:py-3 -mx-6 px-6 border-b border-gray-100 dark:border-white/5 transition-all duration-300">
            <div class="flex items-center justify-between gap-2 md:gap-4">
                <!-- x-dropdown for Role Filters -->
                <div class="flex-shrink-0">
                    <x-dropdown align="left" width="64" contentClasses="py-1 bg-white dark:bg-[#1a1c23] border border-gray-100 dark:border-white/10">
                        <x-slot name="trigger">
                            <button class="px-4 py-2.5 md:px-8 md:py-4 bg-gray-100 dark:bg-white/10 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest dark:text-white flex items-center gap-2 md:gap-3 group hover:bg-gray-200 dark:hover:bg-white/20 transition-all border border-transparent dark:border-white/10 shadow-sm whitespace-nowrap">
                                <span class="material-symbols-rounded text-indigo-500 text-xl md:text-lg">filter_list</span>
                                <span class="hidden md:inline" x-text="getActiveLabel()"></span>
                                <span class="material-symbols-rounded transition-transform duration-300 md:inline hidden">expand_more</span>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="p-2 space-y-1">
                                <button @click="resetFilters()" class="w-full flex flex-col p-4 rounded-xl hover:bg-slate-50 dark:hover:bg-white/5 transition-all text-left" :class="!type && !location ? 'bg-slate-50 dark:bg-white/5' : ''">
                                    <p class="text-[10px] font-black uppercase text-slate-900 dark:text-white" :class="!type && !location ? 'text-indigo-600' : ''">All Roles</p>
                                    <p class="text-[8px] font-bold text-gray-400 uppercase">Every featured profile</p>
                                </button>

                                <div class="h-px bg-gray-100 dark:bg-white/5 my-2 mx-2"></div>

                                @php
                                    $roles = [
                                        'Actor', 'Actress', 'Director', 'Producer', 'Executive Producer', 'Screenwriter', 
                                        'Dialogue Writer', 'Cinematographer (DOP)', 'Editor', 'Colorist', 'VFX Artist', 
                                        'Motion Graphics Designer', 'Thumbnail Designer', 'Music Director', 'Singer', 
                                        'Rap Artist', 'Lyricist', 'Sound Designer', 'Background Score Composer', 
                                        'Choreographer', 'Dance Crew', 'Makeup Artist', 'Costume Designer', 'Stylist', 
                                        'Photographer', 'Casting Director', 'Assistant Director', 'Production Manager', 
                                        'Line Producer', 'Short Film Creator', 'Reel Creator', 'YouTuber', 'Influencer', 
                                        'Brand Collaborator', 'OTT Partner', 'Distributor', 'Investor', 'Event Organizer', 
                                        'Studio Owner', 'Acting Trainer', 'Film School', 'Voice Over Artist', 'Dub Artist', 
                                        'Anchor / Host', 'Meme Creator', 'Marketing Partner'
                                    ];
                                @endphp
                                <div class="max-h-64 overflow-y-auto custom-scrollbar pr-1 space-y-1">
                                    @foreach($roles as $role)
                                        <button @click="setType('{{ $role }}')" class="w-full flex flex-col p-3 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-all text-left" :class="type === '{{ $role }}' ? 'bg-indigo-50 dark:bg-indigo-500/10' : ''">
                                            <p class="text-[10px] font-black uppercase text-slate-700 dark:text-slate-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400" :class="type === '{{ $role }}' ? 'text-indigo-600 dark:text-indigo-400' : ''">{{ $role }}</p>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Unified Mobile Search Bar -->
                <div class="flex-1 relative group">
                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                        <span class="material-symbols-rounded text-gray-400 group-focus-within:text-indigo-500 transition-colors" :class="loading ? 'animate-spin' : ''" x-text="loading ? 'progress_activity' : 'search'"></span>
                    </div>
                    <input type="text" 
                           x-model="search" 
                           @input.debounce.500ms="fetchProfiles()"
                           placeholder="Search featured profiles..." 
                           class="w-full pl-10 md:pl-11 pr-4 py-2.5 md:py-4 bg-gray-100 dark:bg-white/10 rounded-xl md:rounded-2xl border-none text-[11px] md:text-xs font-black text-slate-900 dark:text-white placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-500/20 transition-all shadow-sm">
                </div>
            </div>
        </div>

        <!-- Profiles Display Container -->
        <div id="profiles-container" class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8 transition-all duration-500" :class="loading ? 'opacity-40 grayscale blur-[2px] pointer-events-none scale-[0.98]' : 'opacity-100 grayscale-0 blur-0 scale-100'">
            @include('frontend.marketplace.partials.profiles_grid', ['marketplaces' => $featuredCreators])
        </div>

        <div id="pagination-container" class="mt-16 mb-24 md:mb-8">
            {{ $featuredCreators->links() }}
        </div>
    </div>
</div>

@push('style')
<style>
    /* Page-scoped icon visibility fix (this page only).
       The layout globally forces `.material-symbols-rounded` to `color: inherit`,
       which beats Tailwind's text-color utilities and leaves icons dark-on-dark
       in dark mode. Light mode restores the original colors; dark mode renders
       the icons white as designed. */
    html:not(.dark) #marketplace-featured span.material-symbols-rounded.text-gray-400 { color: #9ca3af !important; }
    html:not(.dark) #marketplace-featured span.material-symbols-rounded.text-gray-300 { color: #d1d5db !important; }
    html:not(.dark) #marketplace-featured span.material-symbols-rounded.text-slate-400 { color: #94a3b8 !important; }
    html:not(.dark) #marketplace-featured span.material-symbols-rounded.text-white { color: #ffffff !important; }
    html:not(.dark) #marketplace-featured span.material-symbols-rounded.text-yellow-400 { color: #facc15 !important; }
    html:not(.dark) #marketplace-featured span.material-symbols-rounded.text-indigo-500 { color: #6366f1 !important; }
    .dark #marketplace-featured span.material-symbols-rounded.text-gray-400 { color: rgba(255,255,255,0.7) !important; }
    .dark #marketplace-featured span.material-symbols-rounded.text-gray-300 { color: #ffffff !important; }
    .dark #marketplace-featured span.material-symbols-rounded.text-yellow-400 { color: #ffffff !important; }
    .dark #marketplace-featured span.material-symbols-rounded.text-white { color: #ffffff !important; }
    .dark #marketplace-featured span.material-symbols-rounded.text-indigo-500 { color: #ffffff !important; }
    /* Mobile styles for navbar toggling */
    @media (max-width: 1023px) {
        nav.fixed {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Pagination Mobile Overrides */
        #pagination-container nav .sm\:hidden {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        #pagination-container nav .sm\:hidden span,
        #pagination-container nav .sm\:hidden a {
            border-radius: 1rem !important;
            padding: 0.75rem 1.5rem !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            font-size: 0.75rem !important;
            border: none !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
            text-decoration: none;
        }
        #pagination-container nav .sm\:hidden a {
            background: linear-gradient(135deg, #f97316, #e11d48) !important;
            color: white !important;
        }
        #pagination-container nav .sm\:hidden span {
            background: #f3f4f6 !important;
            color: #9ca3af !important;
        }
        .dark #pagination-container nav .sm\:hidden span {
            background: rgba(255, 255, 255, 0.05) !important;
            color: rgba(255, 255, 255, 0.3) !important;
        }
    }
</style>
@endpush

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Intercept pagination clicks -> AJAX JSON (never full-page JSON pretty-print)
        const pagWrap = document.getElementById('pagination-container');
        if (pagWrap) {
            pagWrap.addEventListener('click', async (e) => {
                const link = e.target.closest('a[href]');
                if (!link) return;
                e.preventDefault();
                const pageUrl = new URL(link.href, window.location.origin);
                window.history.pushState({}, '', pageUrl);
                try {
                    const res = await fetch(pageUrl, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    });
                    const ct = res.headers.get('content-type') || '';
                    if (!res.ok || !ct.includes('application/json')) throw new Error('Expected JSON, got: ' + ct);
                    const data = await res.json();
                    document.getElementById('profiles-container').innerHTML = data.html;
                    pagWrap.innerHTML = data.pagination;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } catch (err) {
                    console.error('Pagination fetch failed, falling back to full load:', err);
                    window.location.href = pageUrl.toString();
                }
            });
        }
        const navbar = document.querySelector('nav.fixed');
        const filterBar = document.getElementById('marketplace-filter-bar');
        if (!navbar || !filterBar) return;
        
        let lastScroll = window.pageYOffset;
        
        window.addEventListener('scroll', () => {
            if (window.innerWidth >= 1024) {
                // reset styles for desktop
                navbar.style.transform = 'translateY(0)';
                filterBar.style.top = '5rem'; // lg:top-20
                filterBar.style.opacity = '1';
                filterBar.style.pointerEvents = 'auto';
                return;
            }

            const currentScroll = window.pageYOffset;
            const isScrollingDown = currentScroll > lastScroll && currentScroll > 50;
            const isScrollingUp = currentScroll < lastScroll;
            const atTop = currentScroll <= 50;

            if (isScrollingDown && !atTop) {
                // Hide navbar
                navbar.style.transform = 'translateY(-100%)';
                // Show filter in place of navbar
                filterBar.style.top = '0';
                filterBar.style.opacity = '1';
                filterBar.style.pointerEvents = 'auto';
            } else if (isScrollingUp || atTop) {
                // Show navbar
                navbar.style.transform = 'translateY(0)';
                if (atTop) {
                    // At top, filter stays below navbar
                    filterBar.style.top = '3.5rem'; // top-14
                    filterBar.style.opacity = '1';
                    filterBar.style.pointerEvents = 'auto';
                } else {
                    // Scrolling up (not at top), hide filter
                    filterBar.style.top = '3.5rem';
                    filterBar.style.opacity = '0';
                    filterBar.style.pointerEvents = 'none';
                }
            }
            lastScroll = currentScroll <= 0 ? 0 : currentScroll;
        }, { passive: true });
    });
</script>
@endpush

@endsection
