@php
    $sideBarLinks = $sideBarLinks ?? [];
    $iconMap = [
        'dashboard' => 'dashboard',
        'telemetry' => 'pie_chart',
        'analytics' => 'analytics',
        'manage_users' => 'group',
        'marketplace' => 'storefront',
        'manage_videos' => 'video_library',
        'categories' => 'category',
        'video_resolutions' => 'aspect_ratio',
        'manage_reels' => 'movie_filter',
        'ott_plans' => 'tv',
        'monthly_plan' => 'calendar_today',
        'playlists' => 'playlist_add_check',
        'Payments' => 'payments',
        'withdrawals' => 'account_balance',
        'reports' => 'receipt_long',
        'support_ticket' => 'support_agent',
        'subscriber' => 'notifications_active',
        'comments' => 'forum',
        'system_setting' => 'settings',
        'storage' => 'database',
        'frontend_manager' => 'desktop_windows',
        'extra' => 'auto_fix_high',
        'banner_ads' => 'ads_click',
        'security' => 'security',
        'growth' => 'trending_up',
        'real_time_intelligence' => 'sensors',
        'featured_management' => 'star',
        'marketplace_ratings' => 'star_half'
    ];
@endphp

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #f97316;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-button:single-button {
        background-color: transparent;
        display: block;
        border-style: solid;
        height: 10px;
        width: 10px;
    }
    /* Up Arrow */
    .custom-scrollbar::-webkit-scrollbar-button:single-button:vertical:decrement {
        border-width: 0 4px 4px 4px;
        border-color: transparent transparent #f97316 transparent;
    }
    /* Down Arrow */
    .custom-scrollbar::-webkit-scrollbar-button:single-button:vertical:increment {
        border-width: 4px 4px 0 4px;
        border-color: #f97316 transparent transparent transparent;
    }
    
    /* Firefox */
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #f97316 transparent;
    }
</style>

<nav class="flex-grow px-4 py-6 space-y-1.5 overflow-y-auto custom-scrollbar">
    <div class="flex items-center gap-2 text-[10px] font-black text-orange-500 uppercase tracking-[0.3em] mb-5 px-4">
        <span class="material-symbols-rounded text-xs">grid_view</span>
        1. Dashboard & Reports
    </div>
    
    @foreach($sideBarLinks as $key => $data)
        @if(isset($data->disabled) && $data->disabled)
            @continue
        @endif
        @php
            $icon = $iconMap[$key] ?? 'circle';
            // Determine if active
            $isActive = false;
            if(isset($data->menu_active)){
                $isActive = menuActive($data->menu_active, 3, @$data->params->key);
            }
        @endphp

        @if(isset($data->submenu))
            @php 
                $isAnySubActive = false;
                foreach($data->submenu as $menu) {
                    if(@$menu->menu_active && menuActive($menu->menu_active, null, @$menu->params->key)) {
                        // Check if this route is excluded from expanding this specific parent
                        $excluded = (array)(@$data->exclude_expansion ?? []);
                        if(!in_array($menu->menu_active, $excluded)) {
                            $isAnySubActive = true;
                            break;
                        }
                    }
                }
                // Fallback: match current route against submenu route prefix
                if (!$isAnySubActive) {
                    $currentRoute = request()->route()->getName();
                    foreach($data->submenu as $menu) {
                        $menuActive = $menu->menu_active ?? '';
                        if (is_array($menuActive)) { continue; }
                        $prefix = str_replace(['.*', '*'], '', $menuActive);
                        if ($prefix === 'admin' || $prefix === 'admin.') { continue; }
                        
                        // Strict prefix match to allow deep routes (e.g., .create, .edit) without false sibling matches
                        if ($prefix && str_starts_with($currentRoute, $prefix)) {
                            $excluded = (array)(@$data->exclude_expansion ?? []);
                            if (!in_array($menuActive, $excluded)) {
                                $isAnySubActive = true;
                                break;
                            }
                        }
                    }
                }
            @endphp
            <div x-data="{ open: {{ $isAnySubActive ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between px-4 py-3.5 rounded-2xl {{ $isAnySubActive ? 'bg-gradient-to-r from-orange-600 to-orange-400 shadow-xl shadow-orange-500/30 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }} transition-all duration-300 group">
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-rounded text-xl {{ $isAnySubActive ? 'fill-1' : 'opacity-40 group-hover:opacity-100' }}">{{ $icon }}</span>
                        <span class="font-black text-[12px] uppercase tracking-widest whitespace-nowrap">{{ __(@$data->title) }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @foreach(@$data->counters ?? [] as $counter)
                            @if(isset($$counter) && $$counter > 0)
                                <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse border border-white/20"></span>
                                @break
                            @endif
                        @endforeach
                        <span class="material-symbols-rounded text-lg transition-transform duration-500" :class="{ 'rotate-180': open }">expand_more</span>
                    </div>
                </button>
                
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="pl-12 space-y-1.5 py-1" x-cloak>
                    @php
                        $currentRoute = request()->route()->getName();
                        $anySubInSection = false;
                        foreach($data->submenu as $sm) {
                            $smActive = $sm->menu_active ?? '';
                            if (is_array($smActive)) { continue; }
                            $smPrefix = str_replace(['.*', '*'], '', $smActive);
                            if ($smPrefix === 'admin' || $smPrefix === 'admin.') { continue; }
                            
                            if ($smPrefix && str_starts_with($currentRoute, $smPrefix)) {
                                $anySubInSection = true;
                                break;
                            }
                        }
                    @endphp
                    @forelse($data->submenu as $menu)
                        @php 
                            $subActive = @$menu->menu_active ? menuActive($menu->menu_active, null, @$menu->params->key) : false; 
                            if (!$subActive && $anySubInSection && $loop->first) {
                                $subActive = true;
                            }
                            $counter = @$menu->counter;
                        @endphp
                        <a href="{{ route(@$menu->route_name, (array)@$menu->params) }}" class="flex items-center justify-between py-2.5 pr-4 text-[10px] font-black uppercase tracking-widest {{ $subActive ? 'text-orange-500 active-menu-item' : 'text-white/50 hover:text-white' }} transition-all duration-300 min-w-0">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="material-symbols-rounded text-[14px] {{ $subActive ? 'fill-1' : '' }} flex-shrink-0">arrow_right_alt</span> 
                                <span class="overflow-hidden text-ellipsis whitespace-nowrap">{{ __($menu->title) }}</span>
                            </div>
                            @if(isset($$counter) && $$counter > 0)
                                <span class="px-2 py-0.5 rounded-full bg-orange-500/20 text-orange-500 text-[9px] font-black flex-shrink-0">{{ $$counter }}</span>
                            @endif
                        </a>
                    @empty
                        <div class="py-2 pr-4 text-[9px] font-black uppercase tracking-widest text-white/30 ">
                             @lang('No links available')
                        </div>
                    @endforelse
                </div>
            </div>
        @else
            @php 
                $isActive = menuActive($data->menu_active, 3, @$data->params->key);
                $counter = @$data->counter; 
            @endphp
            <a href="{{ route(@$data->route_name) }}" class="flex items-center justify-between px-4 py-3.5 rounded-2xl {{ $isActive ? 'bg-gradient-to-r from-orange-600 to-orange-400 shadow-xl shadow-orange-500/30 text-white active-menu-item' : 'text-white/80 hover:bg-white/10 hover:text-white' }} transition-all duration-300 group min-w-0">
                <div class="flex items-center gap-4 min-w-0">
                    <span class="material-symbols-rounded text-xl {{ $isActive ? 'fill-1' : 'opacity-40 group-hover:opacity-100' }} flex-shrink-0">{{ $icon }}</span>
                    <span class="font-black text-[12px] uppercase tracking-widest whitespace-nowrap overflow-hidden text-ellipsis">{{ __(@$data->title) }}</span>
                </div>
                @if(isset($$counter) && $$counter > 0)
                    <span class="px-2 py-0.5 rounded-full bg-orange-500/20 text-orange-500 text-[9px] font-black flex-shrink-0">{{ $$counter }}</span>
                @endif
            </a>
        @endif
        
        {{-- Section Dividers --}}
        @if($key == 'analytics')
            <div class="flex items-center gap-2 text-[10px] font-black text-orange-500 uppercase tracking-[0.3em] mb-5 px-4 mt-8">
                <span class="material-symbols-rounded text-xs">person_outline</span>
                2. Users & Marketplace
            </div>
        @elseif($key == 'marketplace')
            <div class="flex items-center gap-2 text-[10px] font-black text-orange-500 uppercase tracking-[0.3em] mb-5 px-4 mt-8">
                <span class="material-symbols-rounded text-xs">movie_edit</span>
                3. Content Management
            </div>
        @elseif($key == 'manage_reels')
            <div class="flex items-center gap-2 text-[10px] font-black text-orange-500 uppercase tracking-[0.3em] mb-5 px-4 mt-8">
                <span class="material-symbols-rounded text-xs">layers</span>
                4. Premium & Subscription
            </div>
        @elseif($key == 'playlists')
            <div class="flex items-center gap-2 text-[10px] font-black text-orange-500 uppercase tracking-[0.3em] mb-5 px-4 mt-8">
                <span class="material-symbols-rounded text-xs">account_balance_wallet</span>
                5. Financial Desk
            </div>
        @elseif($key == 'reports')
            <div class="flex items-center gap-2 text-[10px] font-black text-orange-500 uppercase tracking-[0.3em] mb-5 px-4 mt-8">
                <span class="material-symbols-rounded text-xs">forum</span>
                6. Interaction & Help
            </div>
        @elseif($key == 'comments')
            <div class="flex items-center gap-2 text-[10px] font-black text-orange-500 uppercase tracking-[0.3em] mb-5 px-4 mt-8">
                <span class="material-symbols-rounded text-xs">settings</span>
                7. System Control
            </div>
        @endif
    @endforeach

</nav>

<script>
    window.addEventListener('load', function() {
        // Use a slightly longer timeout to ensure Alpine.js has finished opening submenus
        setTimeout(() => {
            const activeLink = document.querySelector('.active-menu-item');
            if (activeLink) {
                const container = activeLink.closest('nav');
                if (container) {
                    // Standard scrollIntoView is usually enough if the element is visible
                    activeLink.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }
        }, 800);
    });
</script>

