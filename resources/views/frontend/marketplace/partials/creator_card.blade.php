@php
    $isFeatured = $item->hasFeaturedAccess();
    $slug = \Illuminate\Support\Str::slug($item->name);
    // Role pill colors logic (fallback to purple)
    $typeStr = strtolower($item->type);
    $pillBg = 'bg-purple-100 dark:bg-purple-500/20';
    $pillText = 'text-purple-600 dark:text-purple-400';
    if (str_contains($typeStr, 'actor') || str_contains($typeStr, 'actress')) {
        $pillBg = 'bg-blue-100 dark:bg-blue-500/20'; $pillText = 'text-blue-600 dark:text-blue-400';
    } elseif (str_contains($typeStr, 'investor')) {
        $pillBg = 'bg-green-100 dark:bg-green-500/20'; $pillText = 'text-green-600 dark:text-green-400';
    }
    
    // Skills formatting
    $rawSkills = $item->skills ?? '';
    $skills = is_array($rawSkills) ? $rawSkills : explode(',', $rawSkills);
    $skills = array_filter(array_map('trim', $skills));
@endphp

<a href="{{ route('marketplace.portfolio.short', ['slug' => $item->slug]) }}" class="w-full bg-white dark:bg-[#1a1c23] rounded-2xl md:rounded-3xl border-2 border-red-50/50 dark:border-gray-800 shadow-sm hover:shadow-xl hover:dark:shadow-black/40 transition-all duration-300 overflow-hidden relative flex flex-col group cursor-pointer">
    <!-- Top Gradient Background (40%) -->
    <div class="h-16 md:h-24 bg-gradient-to-b from-red-100/60 dark:from-red-900/20 to-white dark:to-[#1a1c23] relative overflow-hidden shrink-0">
        @php 
            $coverSrc = $item->coverUrl();
        @endphp
        @if($coverSrc)
            <img src="{{ $coverSrc }}" loading="lazy" decoding="async" class="absolute inset-0 w-full h-full object-cover opacity-30 dark:opacity-20 mix-blend-multiply dark:mix-blend-overlay" onerror="this.style.display='none'">
        @endif
        @if($isFeatured)
            <div class="absolute top-2 md:top-3 left-2 md:left-3 z-10 flex items-center gap-0.5 md:gap-1 px-1.5 md:px-2 py-0.5 md:py-1 bg-white/90 dark:bg-red-500/20 backdrop-blur-md rounded-full text-red-500 dark:text-red-300 shadow-sm border border-white/50 dark:border-red-500/30">
                <span class="material-symbols-rounded text-[8px] md:text-[12px]">workspace_premium</span>
                <span class="text-[6px] md:text-[8px] font-black uppercase tracking-wider">Featured</span>
            </div>
        @endif
        
        <!-- Active Dot -->
        <div class="absolute top-2 md:top-4 right-2 md:right-4 z-10">
            <div class="w-3 h-3 md:w-4 md:h-4 bg-green-500 rounded-full border-2 border-white dark:border-[#1a1c23] shadow-sm flex items-center justify-center">
                <span class="material-symbols-rounded text-white text-[8px] md:text-[10px]">check</span>
            </div>
        </div>
    </div>

    <!-- Content Section (60%) -->
    <div class="px-3 md:px-4 pb-3 md:pb-4 -mt-12 md:-mt-16 flex-1 flex flex-col relative z-20">
        <!-- Avatar & Role -->
        <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-3 mt-1">
            <div class="relative w-24 h-24 md:w-32 md:h-32 shrink-0 mx-auto sm:mx-0">
                <div class="w-full h-full rounded-full border-[3px] md:border-[4px] border-white dark:border-[#1a1c23] bg-white dark:bg-gray-800 shadow-md overflow-hidden" style="background-color: {{ $item->getAvatarColor() }}">
                    @php $avatarUrl = $item->photoUrl(); @endphp
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" loading="lazy" decoding="async" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-2xl md:text-4xl font-black text-white uppercase">
                            {{ $item->getInitials() }}
                        </div>
                    @endif
                </div>
                <!-- Mobile Role Badge Overlapping Avatar -->
                <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 whitespace-nowrap z-30 md:hidden">
                    <span class="inline-block px-2 py-0.5 {{ $pillBg }} {{ $pillText }} border-2 border-white dark:border-[#1a1c23] rounded-full text-[8px] font-black uppercase tracking-widest shadow-sm">
                        {{ $item->type }}
                    </span>
                </div>
            </div>
            <!-- Desktop Role Badge (Right side) -->
            <div class="hidden md:block pb-2">
                <span class="inline-block px-3 py-1 {{ $pillBg }} {{ $pillText }} rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm">
                    {{ $item->type }}
                </span>
            </div>
        </div>

        <!-- Name & Brief -->
        <div class="mb-3 flex-1 flex flex-col">
            <h3 class="text-xs md:text-[16px] font-bold text-gray-900 dark:text-white line-clamp-2 leading-tight mb-1 h-[32px] md:h-[42px]" title="{{ $item->name }}">{{ $item->name }}</h3>
            <p class="text-[10px] md:text-xs text-gray-500 dark:text-gray-400 line-clamp-3 leading-relaxed h-[50px] md:h-[60px]">{{ $item->more_info ?? 'Professional Creative' }}</p>
        </div>

        <!-- Details Grid: Location, Rating, Exp -->
        <div class="mt-auto pt-3 border-t border-slate-100 dark:border-white/5 space-y-2 mb-4">
            <!-- Location -->
            <div class="flex items-center gap-1 text-gray-500 dark:text-gray-400 shrink-0" title="{{ $item->location ?? 'Global' }}">
                <span class="material-symbols-rounded text-[14px] md:text-[16px] text-gray-400 dark:text-gray-300 shrink-0">location_on</span>
                <span class="text-[10px] md:text-xs font-medium truncate">{{ \Illuminate\Support\Str::limit($item->location ?? 'Global', 16) }}</span>
            </div>
            
            <!-- Rating & Experience -->
            <div class="flex items-center justify-between gap-1 overflow-hidden">
                <div class="flex items-center gap-0.5 shrink-0">
                    <span class="material-symbols-rounded text-yellow-400 dark:text-white text-[14px] md:text-[16px] shrink-0">star</span>
                    <span class="font-bold text-gray-900 dark:text-white text-[10px] md:text-xs">{{ number_format($item->averageRating ?? 0, 1) }}</span>
                    <span class="text-gray-400 text-[9px] md:text-[10px] ml-0.5">({{ $item->totalRatings ?? 0 }})</span>
                </div>
                <div class="flex items-center gap-0.5 text-red-500 dark:text-red-400 shrink-0 truncate">
                    <span class="material-symbols-rounded text-[14px] md:text-[16px] shrink-0">business_center</span>
                    @php
                        $expRaw = is_array($item->years_of_experience) ? implode(', ', $item->years_of_experience) : ($item->years_of_experience ?? '5+');
                        $expClean = trim(str_ireplace([' years', ' year', ' yrs', ' yr', 'years', 'year', 'yrs', 'yr'], '', $expRaw));
                    @endphp
                    <span class="font-bold text-[10px] md:text-xs truncate">{{ $expClean }}{!! !preg_match('/[a-zA-Z]/', $expClean) ? ' <span class="inline">yrs</span>' : '' !!}</span>
                </div>
            </div>
        </div>

        <!-- View Profile Button -->
        <div class="shrink-0 mt-auto">
            <div class="w-full flex items-center justify-center gap-1 bg-gradient-to-r from-orange-500 to-red-500 dark:from-white/5 dark:to-white/5 text-white dark:text-white border border-transparent dark:border-white/10 rounded-md md:rounded-lg py-2 md:py-2.5 font-bold text-[10px] md:text-xs shadow-md hover:shadow-lg transition-all whitespace-nowrap">
                <span class="truncate">View Profile</span>
                <span class="material-symbols-rounded text-[12px] md:text-[14px] shrink-0">open_in_new</span>
            </div>
        </div>
    </div>
</a>
