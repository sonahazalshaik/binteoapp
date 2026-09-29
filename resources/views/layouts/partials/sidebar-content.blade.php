    <div class="px-6 mb-8">
        <p class="text-[10px] font-black text-gray-400 dark:text-white/40 uppercase tracking-[0.25em] mb-4 px-4 mt-2">Discover</p>
        <nav aria-label="Main Actions" class="space-y-1.5">
            <a href="{{ route('home') }}" class="flex items-center space-x-4 px-4 py-3.5 rounded-2xl {{ request()->routeIs('home') && !request()->category ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'hover:bg-gray-50 dark:hover:bg-white/5 text-gray-700 dark:text-[#F1F1F1]' }} transition-all group">
                <span class="material-symbols-rounded text-[22px] {{ request()->routeIs('home') && !request()->category ? 'text-white' : 'infinite-icon icon-home' }}">home</span>
                <span class="font-bold text-[14px]">Home</span>
            </a>
            <a href="{{ route('trending') }}" class="flex items-center space-x-4 px-4 py-3.5 rounded-2xl {{ request()->routeIs('trending') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'hover:bg-gray-50 dark:hover:bg-white/5 text-gray-700 dark:text-[#F1F1F1]' }} transition-all group">
                <span class="material-symbols-rounded text-[22px] {{ request()->routeIs('trending') ? 'text-white' : 'infinite-icon icon-trending' }}">trending_up</span>
                <span class="font-bold text-[14px]">Trending</span>
            </a>
            <a href="{{ route('reels.index') }}" class="flex items-center space-x-4 px-4 py-3.5 rounded-2xl {{ request()->routeIs('reels.index') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'hover:bg-gray-50 dark:hover:bg-white/5 text-gray-700 dark:text-[#F1F1F1]' }} transition-all group">
                <span class="material-symbols-rounded text-[22px] {{ request()->routeIs('reels.index') ? 'text-white' : 'infinite-icon icon-reels' }}">slow_motion_video</span>
                <span class="font-bold text-[14px]">Reels</span>
            </a>
        </nav>
    </div>

    <!-- Discover & Premium -->
    <div class="px-6 mb-8">
        <p class="text-[10px] font-black text-gray-400 dark:text-white/40 uppercase tracking-[0.25em] mb-4 px-4 mt-2">Premium Experience</p>
        @php $miniOttSidebar = gs('mini_ott_status'); $miniOttSidebarSoon = $miniOttSidebar !== null && (int) $miniOttSidebar === 0; @endphp
        <nav aria-label="Library Links" class="space-y-1.5">
            <a href="{{ route('premium') }}" @if($miniOttSidebarSoon) onclick="event.preventDefault(); window.showMiniOttComingSoon(); if(navigator.vibrate) navigator.vibrate(20);" @endif class="flex items-center space-x-4 px-4 py-3.5 rounded-2xl {{ request()->routeIs('premium') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'hover:bg-gray-50 dark:hover:bg-white/5 border border-orange-500/10 bg-orange-500/5 text-orange-500' }} transition-all group cursor-pointer">
                <span class="material-symbols-rounded text-[22px] {{ request()->routeIs('premium') ? 'text-white' : 'infinite-icon icon-ott' }}">movie</span>
                <span class="font-bold text-[14px] {{ request()->routeIs('premium') ? 'text-white' : 'text-orange-500' }}">Mini OTT</span>
                @if($miniOttSidebarSoon)
                    <span class="ml-auto px-2 py-1 rounded-full bg-[#ff571a] text-white text-[8px] font-black uppercase tracking-widest">Soon</span>
                @endif
            </a>
            <a href="{{ route('marketplace.index') }}" class="flex items-center space-x-4 px-4 py-3.5 rounded-2xl {{ request()->routeIs('marketplace.index') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'hover:bg-gray-50 dark:hover:bg-white/5 text-gray-700 dark:text-[#F1F1F1]' }} transition-all group">
                <span class="material-symbols-rounded text-[22px] {{ request()->routeIs('marketplace.index') ? 'text-white' : 'infinite-icon icon-marketplace' }}">hub</span>
                <span class="font-bold text-[14px]">Marketplace</span>
            </a>
        </nav>
    </div>

    <!-- Library Section -->
    @auth
    <div class="px-6 mb-8">
        <p class="text-[10px] font-black text-gray-400 dark:text-white/40 uppercase tracking-[0.25em] mb-4 px-4 mt-2">Library</p>
        <nav aria-label="Creator Tools" class="space-y-1.5">
            <a href="{{ route('history') }}" class="flex items-center space-x-4 px-4 py-3.5 rounded-2xl {{ request()->routeIs('history') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'hover:bg-gray-50 dark:hover:bg-white/5 text-gray-700 dark:text-[#F1F1F1]' }} transition-all group">
                <span class="material-symbols-rounded text-[22px] {{ request()->routeIs('history') ? 'text-white' : 'infinite-icon icon-history' }}">history</span>
                <span class="font-bold text-[14px]">History</span>
            </a>
            <a href="{{ route('liked-videos') }}" class="flex items-center space-x-4 px-4 py-3.5 rounded-2xl {{ request()->routeIs('liked-videos') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'hover:bg-gray-50 dark:hover:bg-white/5 text-gray-700 dark:text-[#F1F1F1]' }} transition-all group">
                <span class="material-symbols-rounded text-[22px] {{ request()->routeIs('liked-videos') ? 'text-white' : 'infinite-icon icon-liked' }}">favorite</span>
                <span class="font-bold text-[14px]">Liked Videos</span>
            </a>
            <a href="{{ route('playlists.index') }}" class="flex items-center space-x-4 px-4 py-3.5 rounded-2xl {{ request()->routeIs('playlists.index') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'hover:bg-gray-50 dark:hover:bg-white/5 text-gray-700 dark:text-[#F1F1F1]' }} transition-all group">
                <span class="material-symbols-rounded text-[22px] {{ request()->routeIs('playlists.index') ? 'text-white' : 'infinite-icon icon-playlist' }}">playlist_play</span>
                <span class="font-bold text-[14px]">Playlists</span>
            </a>
            <a href="{{ route('watch-later') }}" class="flex items-center space-x-4 px-4 py-3.5 rounded-2xl {{ request()->routeIs('watch-later') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'hover:bg-gray-50 dark:hover:bg-white/5 text-gray-700 dark:text-[#F1F1F1]' }} transition-all group">
                <span class="material-symbols-rounded text-[22px] {{ request()->routeIs('watch-later') ? 'text-white' : 'infinite-icon icon-later' }}">schedule</span>
                <span class="font-bold text-[14px]">Watch Later</span>
            </a>
        </nav>
    </div>
    @endauth

    <!-- Categories -->
    <div class="px-6 mb-8">
        <p class="text-[10px] font-black text-gray-400 dark:text-white/40 uppercase tracking-[0.25em] mb-4 px-4 mt-2">Categories</p>
        <nav aria-label="Categories Links" class="space-y-1.5">
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
                <a href="{{ route('category.show', $category->slug) }}" data-cat-link data-cat-id="{{ $category->id }}" data-cat-slug="{{ $category->slug }}" class="flex items-center space-x-4 px-4 py-3.5 rounded-2xl {{ (strtolower(urldecode(request()->path())) === 'category/' . strtolower($category->slug) || request()->route('slug') === $category->slug) ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'text-gray-700 dark:text-[#F1F1F1] hover:bg-gray-50 dark:hover:bg-white/5' }} transition-all group">
                    <span class="material-symbols-rounded text-[22px] {{ (strtolower(urldecode(request()->path())) === 'category/' . strtolower($category->slug) || request()->route('slug') === $category->slug) ? 'text-white' : 'infinite-icon ' . $colorClass }}">{{ $icon }}</span>
                    <span class="font-bold text-[14px]">{{ $category->name }}</span>
                </a>
            @endforeach
        </nav>
    </div>

    <!-- Management -->
    <div class="px-6 mb-8">
        <p class="text-[10px] font-black text-gray-400 dark:text-white/40 uppercase tracking-[0.25em] mb-4 px-4 mt-2">Platform</p>
        <nav aria-label="Platform Links" class="space-y-1.5">
            <a href="{{ route('user.ticket.index') }}" class="flex items-center space-x-4 px-4 py-3.5 rounded-2xl {{ request()->routeIs('user.ticket.*') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'hover:bg-gray-50 dark:hover:bg-white/5 text-gray-700 dark:text-[#F1F1F1]' }} transition-all group">
                <span class="material-symbols-rounded text-[22px] {{ request()->routeIs('user.ticket.*') ? 'text-white' : 'infinite-icon icon-support' }}">support_agent</span>
                <span class="font-bold text-[14px]">Support Desk</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="flex items-center space-x-4 px-4 py-3.5 rounded-2xl {{ request()->routeIs('profile.edit') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'hover:bg-gray-50 dark:hover:bg-white/5 text-gray-700 dark:text-[#F1F1F1]' }} transition-all group">
                <span class="material-symbols-rounded text-[22px] {{ request()->routeIs('profile.edit') ? 'text-white' : 'infinite-icon icon-settings' }}">settings</span>
                <span class="font-bold text-[14px]">Settings</span>
            </a>
            <a href="{{ route('pages', 'about') }}" class="flex items-center space-x-4 px-4 py-3.5 rounded-2xl {{ request()->is('pages/about') ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'hover:bg-gray-50 dark:hover:bg-white/5 text-gray-700 dark:text-[#F1F1F1]' }} transition-all group">
                <span class="material-symbols-rounded text-[22px] {{ request()->is('pages/about') ? 'text-white' : 'infinite-icon icon-about' }}">info</span>
                <span class="font-bold text-[14px]">About Us</span>
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
                <a href="{{ route('policy.pages', [$policy->id, slug($policy->data_values->title)]) }}" class="flex items-center space-x-4 px-4 py-3.5 rounded-2xl {{ request()->url() == route('policy.pages', [$policy->id, slug($policy->data_values->title)]) ? 'bg-orange-500 text-white shadow-xl shadow-orange-500/20 active-menu-item' : 'hover:bg-gray-50 dark:hover:bg-white/5 text-gray-700 dark:text-[#F1F1F1]' }} transition-all group">
                    <span class="material-symbols-rounded text-[22px] {{ request()->url() == route('policy.pages', [$policy->id, slug($policy->data_values->title)]) ? 'text-white' : 'infinite-icon ' . $policyColor }}">{{ $icon }}</span>
                    <span class="font-bold text-[14px]">{{ __($policy->data_values->title) }}</span>
                </a>
            @endforeach
        </nav>
    </div>

    <!-- PWA Install Sidebar Promo -->
    <div class="pwa-install-btn px-4 mb-8 cursor-pointer group" style="display: none;">
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
</div>


