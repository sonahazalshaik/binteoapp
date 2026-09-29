<!-- Header Actions aligned with Of2On Matrimonyx reference -->
<div class="p-6 lg:p-8 border-b border-slate-100 dark:border-white/10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-white dark:bg-[#121212] rounded-t-[2rem]">
    <!-- Header Text -->
    <div>
        <h3 class="text-2xl font-black tracking-tighter text-slate-900 dark:text-white capitalize">{{ $title ?? 'Module Overview' }}</h3>
        <p class="text-xs font-medium text-slate-500 mt-1">{{ count($items ?? []) }} entries registered</p>
    </div>
    
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:flex-1 md:min-w-0">
        <!-- Sort + Search Row -->
        <div class="flex flex-row items-center gap-2 sm:gap-3 flex-grow md:flex-grow md:min-w-0">
            <!-- Sort Pill -->
            <div x-data="{ open: false }" class="relative shrink-0">
                <button @click="open = !open" type="button" class="h-10 sm:h-11 px-3 sm:px-5 rounded-full bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 hover:bg-slate-50 flex items-center justify-center gap-1.5 text-[11px] sm:text-[13px] font-black uppercase tracking-widest shadow-sm transition-all focus:outline-none whitespace-nowrap">
                    <span class="material-symbols-rounded text-base sm:text-lg">sort</span> 
                    <span class="hidden xs:inline">Sort</span>
                </button>
                <div x-show="open" @click.away="open = false" x-transition.opacity x-cloak class="absolute top-full left-0 mt-2 w-48 bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 rounded-xl shadow-xl z-50 p-2">
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'id_desc']) }}" class="block px-3 py-2 text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-white/5 rounded-lg transition-colors">Newest First</a>
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'id_asc']) }}" class="block px-3 py-2 text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-white/5 rounded-lg transition-colors">Oldest First</a>
                </div>
            </div>

            <!-- Search Pill -->
            <form id="live-search-form" action="{{ request()->url() }}" method="GET" class="relative flex-grow min-w-0 sm:min-w-[220px] md:min-w-[280px]">
                <input type="text" name="search" id="live-search-input" value="{{ request()->search }}" placeholder="Search entries..." class="w-full h-10 sm:h-11 pl-10 sm:pl-12 pr-10 rounded-full bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 text-[11px] sm:text-[13px] font-bold text-slate-900 dark:text-white transition-all outline-none shadow-sm placeholder:text-slate-500/50">
                
                <span id="search-icon" class="material-symbols-rounded absolute left-3.5 sm:left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none transition-opacity duration-200">search</span>
                
                @if(request()->search)
                    <a href="{{ request()->fullUrlWithQuery(['search' => null, 'page' => null]) }}" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 transition-colors">
                        <span class="material-symbols-rounded text-base">close</span>
                    </a>
                @endif
                @foreach(request()->except('search', 'page') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
            </form>
        </div>

        <!-- Add Generic Pill -->
        @if(isset($createDisabled) && $createDisabled)
        <button type="button" disabled class="h-10 sm:h-11 px-6 rounded-full bg-slate-300 dark:bg-white/10 text-white dark:text-white/30 flex items-center justify-center gap-2 text-[11px] sm:text-[13px] font-black uppercase tracking-widest cursor-not-allowed whitespace-nowrap shrink-0" title="Maximum limit reached. Delete an existing entry first.">
            <span class="material-symbols-rounded text-base sm:text-lg">lock</span>
            <span class="hidden sm:inline">{{ $createLabel ?: 'Add Entry' }}</span>
        </button>
        @elseif(isset($createRoute))
        <a href="{{ $createRoute ?: 'javascript:void(0)' }}" class="h-10 sm:h-11 px-6 rounded-full bg-rose-500 text-white flex items-center justify-center gap-2 text-[11px] sm:text-[13px] font-black uppercase tracking-widest hover:bg-rose-600 active:scale-95 transition-all shadow-lg shadow-rose-500/20 whitespace-nowrap focus:outline-none shrink-0">
            <span class="material-symbols-rounded text-base sm:text-lg">add</span>
            <span class="hidden sm:inline">{{ $createLabel ?: 'Add Entry' }}</span>
        </a>
        @elseif(isset($createClass))
        <button type="button" class="{{ $createClass }} h-10 sm:h-11 px-6 rounded-full bg-rose-500 text-white flex items-center justify-center gap-2 text-[11px] sm:text-[13px] font-black uppercase tracking-widest hover:bg-rose-600 active:scale-95 transition-all shadow-lg shadow-rose-500/20 whitespace-nowrap focus:outline-none shrink-0" {{ $createAttributes ?? '' }}>
            <span class="material-symbols-rounded text-base sm:text-lg">add</span>
            <span class="hidden sm:inline">{{ $createLabel ?? 'Add Entry' }}</span>
        </button>
        @endif

        <!-- Legacy Plugins Injection -->
        <div class="flex flex-row items-center gap-2 empty:hidden">
            @stack('breadcrumb-plugins')
        </div>
    </div>
</div>

<script>
    (function() {
        let debounceTimer;
        const searchInputId = 'live-search-input';
        const searchFormId = 'live-search-form';
        const contentAreaId = 'live-content-area';
        const globalLoaderId = 'global-loader';

        function updateContent(url, isPagination = false) {
            const contentArea = document.getElementById(contentAreaId);
            const globalLoader = document.getElementById(globalLoaderId);
            if (!contentArea) return;

            // Show loading state — full-screen loader only for pagination, subtle fade for live search
            if (isPagination && globalLoader) {
                globalLoader.style.opacity = '1';
                globalLoader.style.pointerEvents = 'auto';
            }
            contentArea.style.opacity = '0.5';

            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.getElementById(contentAreaId);
                    
                    if (newContent) {
                        // Save focus state if it was the search input
                        const activeEl = document.activeElement;
                        const isSearchFocused = activeEl && activeEl.id === searchInputId;
                        const selectionStart = isSearchFocused ? activeEl.selectionStart : null;
                        const selectionEnd = isSearchFocused ? activeEl.selectionEnd : null;

                        contentArea.innerHTML = newContent.innerHTML;

                        // Restore focus and cursor position
                        if (isSearchFocused) {
                            const newSearchInput = document.getElementById(searchInputId);
                            if (newSearchInput) {
                                newSearchInput.focus();
                                if (selectionStart !== null) {
                                    newSearchInput.setSelectionRange(selectionStart, selectionEnd);
                                }
                            }
                        }

                        // Re-initialize Alpine.js
                        if (window.Alpine) {
                            if (typeof window.Alpine.initTree === 'function') {
                                window.Alpine.initTree(contentArea);
                            } else if (typeof window.Alpine.discoverUninitializedComponents === 'function') {
                                window.Alpine.discoverUninitializedComponents((el) => {
                                    window.Alpine.initializeComponent(el);
                                });
                            }
                        }

                        // Re-initialize custom scripts
                        if (typeof initQuickStrike === 'function') initQuickStrike();
                    }
                })
                .finally(() => {
                    contentArea.style.opacity = '1';
                    if (isPagination && globalLoader) {
                        globalLoader.style.opacity = '0';
                        globalLoader.style.pointerEvents = 'none';
                    }
                })
                .catch(error => {
                    console.error('Live update error:', error);
                });
        }

        // Live Search Handler
        document.addEventListener('input', function(e) {
            if (e.target.id === searchInputId) {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    const searchForm = document.getElementById(searchFormId);
                    if (!searchForm) return;

                    const formData = new FormData(searchForm);
                    const params = new URLSearchParams(formData);
                    const url = `${searchForm.action}?${params.toString()}`;

                    window.history.pushState({ path: url }, '', url);
                    updateContent(url);
                }, 400);
            }
        });

        // Live Pagination Handler
        document.addEventListener('click', function(e) {
            const link = e.target.closest('.pagination a, .page-link');
            if (link && link.href && document.getElementById(contentAreaId)) {
                e.preventDefault();
                window.history.pushState({ path: link.href }, '', link.href);
                updateContent(link.href, true);
                
                // Scroll to top of content area
                const scrollContainer = document.querySelector('.flex-grow.overflow-y-auto') || window;
                scrollContainer.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });

        // Handle Back/Forward browser buttons
        window.onpopstate = function(event) {
            if (event.state && event.state.path) {
                updateContent(event.state.path);
            }
        };
    })();
</script>
