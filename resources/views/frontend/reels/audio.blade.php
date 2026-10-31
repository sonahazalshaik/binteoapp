@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white dark:bg-[#0A0A0A] pb-20">
    {{-- Header --}}
    <div class="sticky top-0 z-50 bg-white/80 dark:bg-black/80 backdrop-blur-xl border-b border-gray-100 dark:border-white/5 px-4 sm:px-6 py-3.5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('reels.index') }}" class="w-9 h-9 rounded-full flex items-center justify-center bg-gray-100 dark:bg-white/5 text-gray-900 dark:text-white active:scale-90 transition-transform">
                <span class="material-symbols-rounded text-xl">arrow_back</span>
            </a>
            <h1 class="text-xs sm:text-sm font-black uppercase tracking-widest text-gray-900 dark:text-white">Audio Details</h1>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 pt-6 sm:pt-10">
        {{-- Music Info Card (Native Mobile App Layout) --}}
        <div class="p-6 sm:p-8 rounded-3xl bg-gray-50 dark:bg-white/[0.03] border border-gray-100 dark:border-white/5 mb-8 shadow-sm">
            <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-6">
                {{-- Cover Image --}}
                <div class="relative group shrink-0">
                    <div class="w-28 h-28 sm:w-36 sm:h-36 rounded-2xl sm:rounded-[2rem] overflow-hidden shadow-xl shadow-orange-500/10 border-2 border-white dark:border-[#121212] relative z-10">
                        @if($music->is_original)
                            <img src="{{ $music->cover_image ?? asset('assets/images/default.png') }}" class="w-full h-full object-cover">
                        @else
                            <img src="{{ getImage(getFilePath('reelMusic') . '/' . $music->cover_image) }}" class="w-full h-full object-cover">
                        @endif
                        <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                            <span class="material-symbols-rounded text-white text-3xl animate-pulse">music_note</span>
                        </div>
                    </div>
                    <div class="absolute -inset-2 bg-gradient-to-tr from-orange-500 to-rose-600 rounded-[2.5rem] blur-xl opacity-25 animate-pulse"></div>
                </div>

                {{-- Information & Details --}}
                <div class="flex-1 min-w-0 w-full flex flex-col items-center sm:items-start">
                    <h2 class="text-lg sm:text-2xl font-black text-gray-900 dark:text-white leading-tight mb-1 break-words line-clamp-2 max-w-full">{{ $music->title }}</h2>
                    <p class="text-orange-500 font-bold text-xs mb-4 flex items-center justify-center sm:justify-start gap-1.5">
                        <span class="material-symbols-rounded text-sm">person</span>
                        <span>{{ $music->artist }}</span>
                    </p>

                    <div class="flex flex-col sm:flex-row items-center gap-4 w-full pt-2 border-t border-gray-200/60 dark:border-white/5">
                        <div class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-white/5 rounded-full border border-gray-100 dark:border-white/5 shadow-sm">
                            <span class="material-symbols-rounded text-orange-500 text-base">movie</span>
                            <span class="text-xs font-black text-gray-900 dark:text-white">{{ number_format($music->usage_count) }}</span>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Reels</span>
                        </div>

                        @php
                            $useParams = $music->is_original 
                                ? ['original_reel_id' => str_replace('original_', '', $music->id)]
                                : ['music_id' => $music->id];
                        @endphp
                        <a href="{{ route('reels.create', $useParams) }}" 
                           class="w-full sm:w-auto py-3 px-6 rounded-full gradient-orange text-white font-black text-xs uppercase tracking-widest shadow-lg shadow-orange-500/20 active:scale-95 transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-rounded text-base">video_call</span>
                            Use This Audio
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grid Header with Filter Dropdown --}}
        <div class="mb-4 flex items-center justify-between px-1">
            <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Reels</h3>

            {{-- Filter Dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-[11px] font-bold text-gray-700 dark:text-gray-300 transition-all border border-gray-200 dark:border-white/10 active:scale-95">
                    <span class="material-symbols-rounded text-sm text-orange-500">sort</span>
                    <span>{{ ($sort ?? 'latest') == 'popular' ? 'Most Popular' : (($sort ?? 'latest') == 'oldest' ? 'Oldest First' : 'Latest First') }}</span>
                    <span class="material-symbols-rounded text-sm">expand_more</span>
                </button>
                <div x-show="open" @click.away="open = false" x-cloak 
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-2 w-44 bg-white dark:bg-[#181818] rounded-2xl shadow-2xl border border-gray-100 dark:border-white/10 overflow-hidden z-50 py-1">
                    <a href="?sort=latest" class="flex items-center gap-2 px-4 py-2.5 text-xs font-bold hover:bg-orange-500/10 hover:text-orange-500 transition-colors {{ ($sort ?? 'latest') == 'latest' ? 'text-orange-500 bg-orange-500/5' : 'text-gray-700 dark:text-gray-300' }}">
                        <span class="material-symbols-rounded text-sm">schedule</span>
                        Latest First
                    </a>
                    <a href="?sort=popular" class="flex items-center gap-2 px-4 py-2.5 text-xs font-bold hover:bg-orange-500/10 hover:text-orange-500 transition-colors {{ ($sort ?? '') == 'popular' ? 'text-orange-500 bg-orange-500/5' : 'text-gray-700 dark:text-gray-300' }}">
                        <span class="material-symbols-rounded text-sm">local_fire_department</span>
                        Most Popular
                    </a>
                    <a href="?sort=oldest" class="flex items-center gap-2 px-4 py-2.5 text-xs font-bold hover:bg-orange-500/10 hover:text-orange-500 transition-colors {{ ($sort ?? '') == 'oldest' ? 'text-orange-500 bg-orange-500/5' : 'text-gray-700 dark:text-gray-300' }}">
                        <span class="material-symbols-rounded text-sm">history</span>
                        Oldest First
                    </a>
                </div>
            </div>
        </div>

        {{-- Reels Grid --}}
        <div class="grid grid-cols-3 gap-1.5 sm:gap-4">
            @forelse($reels as $reel)
                <a href="{{ route('reels.show', $reel->slug) }}" class="relative aspect-[9/16] rounded-xl sm:rounded-2xl overflow-hidden bg-gray-100 dark:bg-white/5 group shadow-sm border border-transparent hover:border-orange-500/50 transition-all">
                    <img src="{{ $reel->getPreviewUrl() }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-80 group-hover:opacity-100 transition-opacity"></div>
                    <div class="absolute bottom-2 left-2 flex items-center gap-1 px-2 py-0.5 bg-black/40 backdrop-blur-md rounded-full text-white">
                        <span class="material-symbols-rounded text-[12px]">play_arrow</span>
                        <span class="text-[10px] font-black">{{ formatNumber($reel->views_count ?? $reel->viewsCount ?? 0) }}</span>
                    </div>
                </a>
            @empty
                <div class="col-span-3 py-16 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-4 text-gray-400 border border-gray-200 dark:border-white/5">
                        <span class="material-symbols-rounded text-3xl">music_off</span>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">No reels created with this audio yet.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ paginateLinks($reels) }}
        </div>
    </div>
</div>

<style>
    .gradient-orange {
        background: linear-gradient(135deg, #ff571a 0%, #ff1a1a 100%);
    }
</style>
@endsection
