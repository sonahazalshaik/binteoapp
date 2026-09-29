@php
    $about = getContent('about.content', true);
    
    // Fetch real platform statistics (Live Data)
    $activeUsersCount = \App\Models\User::where('status', 'active')->count();
    $totalUploadsCount = \App\Models\Video::count() + \App\Models\Reel::count();
    $countriesData = json_decode(file_get_contents(resource_path('views/partials/country.json')), true);
    $countriesCount = count($countriesData);
    
    $displayUsers = number_format_short($activeUsersCount);
    $displayUploads = number_format_short($totalUploadsCount);
    $displayCountries = $countriesCount;

    function number_format_short($n) {
        if ($n >= 1000000) return round($n / 1000000, 1) . 'M';
        if ($n >= 1000) return round($n / 1000, 1) . 'K';
        return $n;
    }
@endphp

@if($about)
<div class="min-h-screen bg-[#FDFDFF] dark:bg-[#080808] transition-colors duration-500 pb-20 sm:pb-10 font-sans">
    
    {{-- Header Section --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-8 md:px-12 pt-6 sm:pt-8 mb-8 sm:mb-12">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3">
                <button onclick="history.back()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white dark:bg-[#111111] border border-gray-100 dark:border-white/5 text-gray-400 hover:text-red-500 transition-all active:scale-90 shadow-sm">
                    <span class="material-symbols-rounded text-xl">arrow_back_ios_new</span>
                </button>
                <div>
                    <h1 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight leading-none">About Us</h1>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Our Story</p>
                </div>
            </div>
        </div>

        {{-- Hero Section --}}
        <div class="bg-white dark:bg-[#111111] rounded-[2rem] p-6 sm:p-10 border border-gray-100 dark:border-white/5 shadow-sm relative overflow-hidden mb-8 group">
            <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 via-transparent to-orange-500/5 opacity-50"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-8 md:gap-12">
                <div class="flex-1 text-center md:text-left">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-500 mb-4 border border-red-100 dark:border-red-500/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                        <span class="text-[9px] font-black uppercase tracking-[0.2em]">Our Journey</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-gray-900 dark:text-white leading-[1.1] tracking-tight mb-4">
                        Empowering the <br/>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-orange-500">Next Generation.</span>
                    </h2>
                    <p class="text-xs sm:text-sm md:text-base text-gray-500 dark:text-gray-400 font-medium leading-relaxed max-w-lg mx-auto md:mx-0">
                        {{ __(@$about->data_values->title) }}
                    </p>
                </div>
                
                <div class="w-full md:w-[45%] relative">
                    <div class="relative rounded-2xl overflow-hidden shadow-xl border-4 border-white dark:border-[#222] transition-transform duration-500 group-hover:scale-[1.02] aspect-[4/3]">
                        <img src="{{ frontendImage('about', @$about->data_values->image, '600x400') }}" alt="About" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    </div>
                    
                    {{-- Floating satisfaction --}}
                    <div class="absolute -bottom-4 -left-4 bg-white dark:bg-[#1A1A1A] p-3 rounded-xl shadow-xl border border-gray-100 dark:border-white/10 animate-float z-20 hidden sm:block">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-orange-500 rounded-lg flex items-center justify-center text-white shadow-lg shadow-red-500/30">
                                <span class="material-symbols-rounded text-base">favorite</span>
                            </div>
                            <div>
                                <h4 class="text-base font-black text-gray-900 dark:text-white leading-none">99%</h4>
                                <p class="text-gray-400 text-[9px] font-bold uppercase tracking-widest mt-1">Satisfaction</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Section --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-10">
            <div class="bg-white dark:bg-[#111111] p-5 sm:p-6 rounded-2xl border border-gray-100 dark:border-white/5 shadow-sm flex flex-col items-center justify-center text-center hover:-translate-y-1 transition-transform">
                <span class="material-symbols-rounded text-2xl text-red-500 mb-3 opacity-90">group</span>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white leading-none mb-1.5">{{ $displayUsers }}</h3>
                <p class="text-gray-400 font-bold text-[9px] uppercase tracking-[0.15em]">Active Users</p>
            </div>
            <div class="bg-white dark:bg-[#111111] p-5 sm:p-6 rounded-2xl border border-gray-100 dark:border-white/5 shadow-sm flex flex-col items-center justify-center text-center hover:-translate-y-1 transition-transform">
                <span class="material-symbols-rounded text-2xl text-orange-500 mb-3 opacity-90">cloud_upload</span>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white leading-none mb-1.5">{{ $displayUploads }}</h3>
                <p class="text-gray-400 font-bold text-[9px] uppercase tracking-[0.15em]">Total Uploads</p>
            </div>
            <div class="bg-white dark:bg-[#111111] p-5 sm:p-6 rounded-2xl border border-gray-100 dark:border-white/5 shadow-sm flex flex-col items-center justify-center text-center hover:-translate-y-1 transition-transform">
                <span class="material-symbols-rounded text-2xl text-blue-500 mb-3 opacity-90">public</span>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white leading-none mb-1.5">{{ $displayCountries }}</h3>
                <p class="text-gray-400 font-bold text-[9px] uppercase tracking-[0.15em]">Countries</p>
            </div>
            <div class="bg-white dark:bg-[#111111] p-5 sm:p-6 rounded-2xl border border-gray-100 dark:border-white/5 shadow-sm flex flex-col items-center justify-center text-center hover:-translate-y-1 transition-transform">
                <span class="material-symbols-rounded text-2xl text-emerald-500 mb-3 opacity-90">support_agent</span>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white leading-none mb-1.5">24/7</h3>
                <p class="text-gray-400 font-bold text-[9px] uppercase tracking-[0.15em]">Live Support</p>
            </div>
        </div>

        {{-- Core Philosophy --}}
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-6 px-1">
                <h2 class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">Our Core Philosophy</h2>
                <div class="h-[1px] flex-1 bg-gray-100 dark:bg-white/5"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @for($i = 1; $i <= 4; $i++)
                    @php
                        $tKey = 'title' . $i;
                        $iKey = 'image' . $i;
                        $cKey = 'content' . $i;
                    @endphp
                    @if(@$about->data_values->$tKey)
                    <div class="group bg-white dark:bg-[#111111] p-5 sm:p-6 rounded-2xl border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-md transition-all hover:-translate-y-1">
                        <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-5">
                            <div class="w-12 h-12 rounded-xl overflow-hidden shadow-sm bg-gray-50 dark:bg-[#1A1A1A] p-1.5 shrink-0 border border-gray-100 dark:border-white/5">
                                <img src="{{ frontendImage('about', @$about->data_values->$iKey, '600x400') }}" alt="val" class="w-full h-full object-cover rounded-lg group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div>
                                <h4 class="text-sm sm:text-base font-black text-gray-900 dark:text-white mb-1.5 tracking-tight group-hover:text-red-500 transition-colors">{{ __(@$about->data_values->$tKey) }}</h4>
                                <div class="text-gray-500 dark:text-gray-400 text-xs sm:text-[13px] leading-relaxed">
                                    @php echo @$about->data_values->$cKey @endphp
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endfor
            </div>
        </div>

        {{-- CTA --}}
        <div class="bg-gray-900 dark:bg-white rounded-3xl p-8 sm:p-10 text-center relative overflow-hidden shadow-xl">
            <div class="absolute inset-0 bg-gradient-to-r from-red-500/20 to-orange-500/20 opacity-30"></div>
            <div class="relative z-10">
                <h2 class="text-2xl sm:text-3xl font-black text-white dark:text-black mb-2 tracking-tight">Ready to start?</h2>
                <p class="text-[11px] sm:text-xs font-bold text-gray-400 dark:text-gray-500 mb-6 uppercase tracking-widest">Join the fastest growing creator ecosystem.</p>
                <div class="flex flex-wrap justify-center gap-3 sm:gap-4">
                    <a href="{{ route('register') }}" class="px-6 py-3 bg-red-500 text-white rounded-xl font-black text-[11px] uppercase tracking-[0.2em] transition-all hover:bg-red-600 active:scale-95 shadow-lg shadow-red-500/20 flex items-center gap-2">
                        Get Started
                        <span class="material-symbols-rounded text-base">arrow_forward</span>
                    </a>
                    <a href="{{ route('pages', 'about') }}" class="px-6 py-3 bg-white/10 dark:bg-black/5 text-white dark:text-black border border-white/20 dark:border-black/10 rounded-xl font-black text-[11px] uppercase tracking-[0.2em] transition-all hover:bg-white/20 dark:hover:bg-black/10 active:scale-95">
                        Learn More
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-float {
        animation: float 4s ease-in-out infinite;
    }
</style>
@endif




