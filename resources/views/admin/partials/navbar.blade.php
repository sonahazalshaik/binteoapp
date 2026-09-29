@php
    $admin = auth()->guard('admin')->user();
@endphp

<header class="h-auto flex flex-col lg:flex-row items-center justify-between px-4 lg:px-12 py-3 lg:py-0 gap-4 lg:gap-6 z-[60] border-b border-white/5 shadow-2xl relative" style="background-color: #0a192f !important;">
    <!-- Solid Navy Blue Background (Forced via Inline CSS) -->
    <div class="absolute inset-0 -z-10" style="background-color: #0a192f !important;"></div>

    <!-- Left Section: Hamburger & Search (Exact Match) -->
    <div class="flex items-center gap-3 w-full lg:w-auto relative z-20">
        <button @click="sideBarOpen = !sideBarOpen" class="lg:hidden text-orange-500 hover:text-orange-400 transition-all z-[70]">
            <span class="material-symbols-rounded text-2xl" x-text="sideBarOpen ? 'close' : 'menu'">menu</span>
        </button>
        
        <div class="relative flex-grow lg:w-[500px]">
            <form class="navbar-search w-full relative">
                <span class="material-symbols-rounded absolute left-3 top-1/2 -translate-y-1/2 text-orange-500 text-lg">search</span>
                <input type="search" name="#0" id="searchInput" autocomplete="off" placeholder="@lang('Search here...')" 
                       class="w-full bg-white/5 border border-orange-500/20 rounded-lg pl-10 pr-4 py-2 text-sm text-white placeholder:text-orange-500/50 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all shadow-inner">
                <ul class="search-list absolute top-full left-0 right-0 mt-2 bg-white border border-slate-200 rounded-xl shadow-2xl z-[200] max-h-[350px] lg:max-h-[450px] overflow-x-hidden overflow-y-auto overscroll-contain scrollbar-orange" style="-webkit-overflow-scrolling: touch;"></ul>
            </form>
        </div>
    </div>

    <!-- Right Section: Actions -->
    <div class="flex items-center w-full lg:w-auto justify-between lg:justify-end relative z-10">
        
        <div class="flex items-center gap-2 sm:gap-4">
            <!-- Visit Website -->
            <a href="{{ route('home') }}" target="_blank" class="w-10 h-10 flex items-center justify-center text-orange-500 hover:text-orange-400 transition-all" title="@lang('Visit Website')">
                <span class="material-symbols-rounded text-2xl">language</span>
            </a>

            <!-- Notifications -->
            <div class="relative" x-data="{ openNotification: false }">
                <button @click="openNotification = !openNotification" class="w-10 h-10 flex items-center justify-center text-orange-500 hover:text-orange-400 transition-all relative">
                    <span class="material-symbols-rounded text-2xl">notifications</span>
                    @if($adminNotificationCount > 0)
                    <span class="absolute top-1 right-1 w-4 h-4 bg-red-600 rounded-full text-[9px] flex items-center justify-center text-white font-black shadow-lg">
                        {{ $adminNotificationCount > 9 ? '9' : $adminNotificationCount }}
                    </span>
                    @endif
                </button>
                
                <!-- Notifications Dropdown -->
                <div x-show="openNotification" @click.away="openNotification = false" x-cloak 
                     class="fixed sm:absolute top-16 sm:top-full right-4 sm:right-0 mt-4 w-[calc(100vw-2rem)] sm:w-[420px] max-w-[420px] bg-white border border-slate-200 rounded-[2rem] sm:rounded-[2.5rem] shadow-2xl z-[100] overflow-hidden"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                    <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                        <div>
                            <h3 class="text-slate-900 font-black uppercase tracking-[0.2em] text-xs">Notifications</h3>
                            <p class="text-[9px] text-slate-400 font-bold uppercase mt-1">Real-time system updates</p>
                        </div>
                        <span class="text-[10px] bg-rose-50/10 text-rose-500 px-3 py-1 rounded-full font-black uppercase ">{{ $adminNotificationCount }} Active</span>
                    </div>
                    <div class="max-h-[450px] overflow-y-auto scrollbar-hide">
                        @forelse($adminNotifications as $notification)
                            <a href="{{ route('admin.notification.read',$notification->id) }}" class="flex items-center gap-5 p-6 hover:bg-slate-50 border-b border-slate-100 transition-all group">
                                <div class="w-12 h-12 rounded-[1.25rem] bg-slate-100 flex items-center justify-center text-slate-400 group-hover:text-orange-500 group-hover:bg-orange-500/10 transition-all flex-shrink-0">
                                    <span class="material-symbols-rounded text-2xl">monitoring</span>
                                </div>
                                <div class="flex-grow">
                                    <p class="text-[13px] text-slate-700 font-bold leading-tight line-clamp-2 transition-colors">{{ __($notification->title) }}</p>
                                    <div class="flex items-center gap-2 mt-2">
                                        <span class="material-symbols-rounded text-[10px] text-slate-300">schedule</span>
                                        <span class="text-[9px] text-slate-400 uppercase tracking-widest font-black ">{{ diffForHumans($notification->created_at) }}</span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="py-20 text-center">
                                <span class="material-symbols-rounded text-4xl text-slate-200 mb-4">notifications_off</span>
                                <p class="text-[10px] text-slate-300 font-black uppercase tracking-[0.3em] ">No Critical Alerts</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="p-6 bg-slate-50 text-center border-t border-slate-100">
                        <a href="{{ route('admin.notifications') }}" class="inline-flex items-center gap-3 text-[10px] text-orange-500 hover:text-orange-400 uppercase tracking-[0.3em] font-black transition-all">
                            Full Activity Log
                            <span class="material-symbols-rounded text-sm">arrow_right_alt</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <a href="{{ route('admin.setting.system') }}" class="w-10 h-10 flex items-center justify-center text-orange-500 hover:text-orange-400 transition-all" title="@lang('System Setting')">
                <span class="material-symbols-rounded text-2xl">build</span>
            </a>
        </div>

        <!-- Profile Dropdown -->
        <div class="relative" x-data="{ openProfile: false }">
            <button @click="openProfile = !openProfile" 
                    class="flex items-center gap-3 p-1.5 pr-4 rounded-full border transition-all active:scale-95 group shadow-lg shadow-orange-500/20"
                    :class="openProfile ? 'bg-gradient-to-r from-orange-600 to-orange-700 border-orange-500 ring-4 ring-orange-500/10' : 'bg-gradient-to-r from-orange-500 to-orange-600 border-orange-400/50 hover:from-orange-600 hover:to-orange-700 hover:border-orange-400'">
                <div class="w-9 h-9 rounded-full bg-white flex items-center justify-center text-orange-500 font-black text-sm overflow-hidden transition-all shadow-inner group-hover:scale-105">
                    @if($admin->image)
                        <img src="{{ asset(getFilePath('adminProfile').'/'. $admin->image) }}" class="w-full h-full object-cover">
                    @else
                        <span class="material-symbols-rounded text-xl">person</span>
                    @endif
                </div>
                <div class="hidden sm:flex flex-col items-start leading-none min-w-0 text-left">
                    <span class="text-[11px] font-black uppercase tracking-widest text-white truncate max-w-[100px]">{{ $admin->username }}</span>
                    <div class="flex items-center gap-1 mt-0.5">
                        <span class="w-1 h-1 rounded-full bg-white animate-pulse"></span>
                        <span class="text-[9px] font-black text-white/80 uppercase tracking-[0.2em]">Admin</span>
                    </div>
                </div>
                <span class="material-symbols-rounded text-lg text-white transition-transform duration-300" 
                      :class="openProfile ? 'rotate-180' : ''">expand_more</span>
            </button>

            <!-- Profile Menu Dropdown -->
            <div x-show="openProfile" @click.away="openProfile = false" x-cloak 
                 class="absolute right-0 mt-4 w-[340px] bg-white border border-slate-200 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.15)] z-[100] overflow-hidden p-0"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                
                <div class="relative p-7 bg-gradient-to-br from-slate-900 via-[#0a192f] to-slate-900 overflow-hidden">
                    <!-- Background Accents -->
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-orange-500/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-blue-500/10 rounded-full blur-2xl"></div>
                    
                    <div class="relative z-10 flex items-center gap-5">
                        <div class="w-16 h-16 rounded-2xl bg-white/10 p-0.5 border border-white/20 relative z-10 shrink-0 shadow-2xl overflow-hidden">
                            @if($admin->image)
                                <img src="{{ asset(getFilePath('adminProfile').'/'. $admin->image) }}" class="w-full h-full object-cover rounded-[14px]">
                            @else
                                <div class="w-full h-full gradient-orange flex items-center justify-center rounded-[14px]">
                                    <span class="material-symbols-rounded text-3xl font-black text-white">person</span>
                                </div>
                            @endif
                        </div>
                        <div class="relative z-10 min-w-0 text-left">
                            <p class="text-[15px] font-black text-white uppercase tracking-wider truncate leading-tight">{{ $admin->username }}</p>
                            <p class="text-[11px] font-medium text-orange-500/80 truncate mt-1">{{ $admin->email }}</p>
                            <div class="mt-3 flex items-center gap-2">
                                <span class="px-2.5 py-1 bg-orange-500/10 text-orange-500 rounded-lg text-[8px] font-black uppercase tracking-widest border border-orange-500/20 shadow-[0_0_10px_rgba(249,115,22,0.1)]">
                                    {{ $admin->username == 'admin' ? 'Root Access' : 'Privileged' }}
                                </span>
                                <span class="text-[8px] font-black text-white/40 uppercase tracking-[0.2em] ">v{{ config('app.version') ?? '4.2.0' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 space-y-1 bg-white">
                    <div class="px-4 py-2 text-left">
                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-[0.3em]">System Identity</span>
                    </div>

                    <a href="{{ route('admin.profile') }}" class="flex items-center gap-5 p-4 hover:bg-slate-50 rounded-[1.8rem] transition-all group">
                        <div class="w-11 h-11 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:text-orange-500 group-hover:bg-orange-500/10 transition-all shrink-0">
                            <span class="material-symbols-rounded text-xl">admin_panel_settings</span>
                        </div>
                        <div class="text-left">
                            <span class="text-[13px] font-black text-slate-700 group-hover:text-slate-900 uppercase tracking-widest transition-colors block leading-none">Admin Profile</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1.5 block">Credentials & Avatar</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.password') }}" class="flex items-center gap-5 p-4 hover:bg-slate-50 rounded-[1.8rem] transition-all group">
                        <div class="w-11 h-11 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:text-orange-500 group-hover:bg-orange-500/10 transition-all shrink-0">
                            <span class="material-symbols-rounded text-xl">security</span>
                        </div>
                        <div class="text-left">
                            <span class="text-[13px] font-black text-slate-700 group-hover:text-slate-900 uppercase tracking-widest transition-colors block leading-none">Security Vault</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1.5 block">Authentication & MFA</span>
                        </div>
                    </a>

                    <div class="h-px bg-slate-100 my-4 mx-5"></div>

                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-5 p-4 hover:bg-rose-50 rounded-[1.8rem] transition-all text-rose-500 group">
                            <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center group-hover:bg-rose-500 group-hover:text-white transition-all shrink-0 shadow-sm shadow-rose-100">
                                <span class="material-symbols-rounded text-xl">logout</span>
                            </div>
                            <div class="text-left">
                                <span class="text-[13px] font-black uppercase tracking-widest block leading-none">Terminate Session</span>
                                <span class="text-[9px] font-bold text-rose-300 uppercase tracking-widest mt-1.5 block">Sign Out of Admin</span>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

