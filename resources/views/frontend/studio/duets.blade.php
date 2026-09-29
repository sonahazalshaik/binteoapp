@extends('layouts.app')

@section('content')
<div class="transition-colors duration-500">
    
    <!-- Mobile/Tablet Content UI -->
    <div class="lg:hidden max-w-7xl mx-auto px-6 py-10">
        <!-- Compact Header -->
        <div class="flex items-center justify-between mb-10">
             <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Your Duets</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ number_format($reels->total()) }} duet uploads</p>
            </div>
            <a href="{{ route('reels.index') }}" title="Browse Reels to Duet" class="w-12 h-12 gradient-orange text-white rounded-2xl flex items-center justify-center shadow-lg shadow-orange-500/20 active:scale-95 transition-transform">
                <span class="material-symbols-rounded">search</span>
            </a>
        </div>

        <!-- Mobile Content -->
        <div class="grid grid-cols-2 gap-3">
            @forelse($reels as $reel)
            @if(!$reel->slug) @continue @endif
            
                <div class="bg-white dark:bg-[#1A1A1A] rounded-2xl border border-slate-100 dark:border-white/10 shadow-sm overflow-hidden group hover:shadow-lg transition-all"
                     @php
                    $isReelOptimizing = ($reel->isBunnyReel() && !in_array($reel->bunny_status, ['ready', 'error', 'failed'])) || (!$reel->isBunnyReel() && $reel->status === 'processing');
                 @endphp
                 x-data="{ 
                    progress: 0, 
                    isOptimizing: {{ $isReelOptimizing ? 'true' : 'false' }},
                    init() { if(this.isOptimizing) this.pollStatus(); },
                    async pollStatus() {
                        try {
                            let res;
                            @if($reel->isBunnyReel())
                                res = await fetch('{{ route('reels.show', $reel) }}/status');
                            @else
                                res = { json: async () => ({ success: false }) };
                            @endif
                            const data = await res.json();
                            if(data.success) {
                                this.progress = data.encode_progress || 0;
                                if(data.encode_progress >= 100) window.location.reload();
                                else setTimeout(() => this.pollStatus(), 5000);
                            }
                        } catch(e) { setTimeout(() => this.pollStatus(), 10000); }
                    }
                 }">
                <a href="{{ route('reels.show', $reel) }}" class="block relative aspect-[2/3] bg-slate-100 dark:bg-black/40 overflow-hidden">
                    <img src="{{ $reel->getThumbnailUrl() }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.src='{{ $reel->getPreviewUrl() }}'; this.onerror=null;">
                    
                    <!-- Optimizing State Overlay -->
                    <template x-if="isOptimizing">
                        <div class="absolute inset-0 bg-black/60 backdrop-blur-md flex flex-col items-center justify-center z-20">
                            <div class="relative w-10 h-10 mb-2">
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="20" cy="20" r="16" stroke="currentColor" stroke-width="3" fill="transparent" class="text-white/10" />
                                    <circle cx="20" cy="20" r="16" stroke="currentColor" stroke-width="3" fill="transparent" class="text-rose-500 transition-all duration-500" stroke-dasharray="100.5" :stroke-dashoffset="100.5 - (100.5 * progress / 100)" />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center"></div>
                            </div>
                            <span class="text-[6px] font-black text-white uppercase tracking-widest animate-pulse">please wait awesome is loading</span>
                        </div>
                    </template>
                    <div class="absolute bottom-2 right-2 px-1.5 py-0.5 bg-black/70 backdrop-blur rounded text-[8px] font-black text-white">{{ gmdate('i:s', $reel->duration ?? 0) }}</div>
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/20">
                        <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30">
                            <span class="material-symbols-rounded text-white text-2xl material-symbols-filled">play_arrow</span>
                        </div>
                    </div>
                </a>
                
                <div class="p-3">
                    <h3 class="font-black text-[11px] text-slate-900 dark:text-white line-clamp-1 mb-2">{{ $reel->title }}</h3>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <div class="flex items-center gap-2 text-[9px] text-slate-500 dark:text-slate-400 font-bold">
                            <span class="flex items-center gap-0.5"><span class="material-symbols-rounded text-[10px]">visibility</span> {{ number_format($reel->views_count) }}</span>
                            <span class="flex items-center gap-0.5"><span class="material-symbols-rounded text-[10px]">favorite</span> {{ number_format($reel->likes_count ?? 0) }}</span>
                        </div>
                        @if($reel->status == 0 && $reel->bunny_status === null)
                            <span class="px-1.5 py-0.5 bg-slate-500 text-white rounded text-[7px] font-black uppercase">Draft</span>
                        @elseif((string)$reel->visibility === (string)\App\Constants\Status::PRIVATE)
                            <span class="px-1.5 py-0.5 bg-red-600 text-white rounded text-[7px] font-black uppercase flex items-center gap-0.5">
                                <span class="material-symbols-rounded text-[8px]">lock</span> Private
                            </span>
                        @endif
                    </div>
                    <div class="flex gap-1.5">
                        <a href="{{ route('studio.reels.edit', $reel) }}" class="flex-1 h-8 bg-blue-50 dark:bg-blue-500/10 text-blue-600 rounded-lg flex items-center justify-center text-xs hover:bg-blue-100 transition-colors" title="Edit">
                            <span class="material-symbols-rounded text-[14px]">edit</span>
                        </a>
                        <button type="button" @click="confirmDelete('{{ route('studio.reels.destroy', $reel) }}')" class="flex-1 h-8 bg-red-50 dark:bg-red-500/10 text-red-600 rounded-lg flex items-center justify-center text-xs hover:bg-red-100 transition-colors" title="Delete">
                            <span class="material-symbols-rounded text-[14px]">delete</span>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-2 flex flex-col items-center justify-center py-20 text-center">
                <div class="w-20 h-20 rounded-[2rem] bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-300 dark:text-white/10 mb-6">
                    <span class="material-symbols-rounded text-4xl">movie</span>
                </div>
                <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2">No duets yet</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest max-w-xs">Duets you create with other reels will appear here.</p>
            </div>
            @endforelse
        </div>

        @if($reels->hasPages())
            <div class="pt-6 pb-12">
                {{ $reels->links() }}
            </div>
        @endif
    </div>

    <!-- Desktop Content UI -->
    <div class="mx-auto px-6 py-6 hidden lg:block">
        <!-- Header -->
        <div class="flex items-center justify-between mb-12">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Your Duets</h1>
                <p class="text-slate-500 font-medium mt-1">Manage all your duet & remix reels.</p>
            </div>
            <a href="{{ route('reels.index') }}" 
               class="flex items-center gap-2 px-6 py-3 gradient-orange text-white font-black rounded-xl shadow-lg shadow-orange-500/20 transition-all hover:scale-105 active:scale-95">
                <span class="material-symbols-rounded">search</span>
                <span>Find Reel to Duet</span>
            </a>
        </div>

        <!-- Reels Grid (Desktop) -->
        <div>
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4 lg:gap-6 mb-12">
                @forelse($reels as $reel)
                @if(!$reel->slug) @continue @endif
                
                    <div class="bg-white dark:bg-[#1A1A1A] rounded-3xl border border-slate-100 dark:border-white/10 shadow-sm overflow-hidden group hover:shadow-xl transition-all duration-300"
                         @php
                        $isReelOptimizing = ($reel->isBunnyReel() && !in_array($reel->bunny_status, ['ready', 'error', 'failed'])) || (!$reel->isBunnyReel() && $reel->status === 'processing');
                     @endphp
                     x-data="{ 
                        progress: 0, 
                        isOptimizing: {{ $isReelOptimizing ? 'true' : 'false' }},
                        init() { if(this.isOptimizing) this.pollStatus(); },
                        async pollStatus() {
                            try {
                                let res;
                                @if($reel->isBunnyReel())
                                    res = await fetch('{{ route('reels.show', $reel) }}/status');
                                @else
                                    res = { json: async () => ({ success: false }) };
                                @endif
                                const data = await res.json();
                                if(data.success) {
                                    this.progress = data.encode_progress || 0;
                                    if(data.encode_progress >= 100) window.location.reload();
                                    else setTimeout(() => this.pollStatus(), 5000);
                                }
                            } catch(e) { setTimeout(() => this.pollStatus(), 10000); }
                        }
                     }">
                    <a href="{{ route('reels.show', $reel) }}" class="block relative aspect-[2/3] bg-slate-100 dark:bg-black/40 overflow-hidden">
                        <img src="{{ $reel->getThumbnailUrl() }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.src='{{ $reel->getPreviewUrl() }}'; this.onerror=null;">
                        
                        <!-- Optimizing State Overlay -->
                        <template x-if="isOptimizing">
                            <div class="absolute inset-0 bg-black/60 backdrop-blur-md flex flex-col items-center justify-center z-20">
                                <div class="relative w-12 h-12 mb-2">
                                    <svg class="w-full h-full transform -rotate-90">
                                        <circle cx="24" cy="24" r="20" stroke="currentColor" stroke-width="3" fill="transparent" class="text-white/10" />
                                        <circle cx="24" cy="24" r="20" stroke="currentColor" stroke-width="3" fill="transparent" class="text-rose-500 transition-all duration-500" stroke-dasharray="125.6" :stroke-dashoffset="125.6 - (125.6 * progress / 100)" />
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center"></div>
                                </div>
                                <span class="text-[7px] font-black text-white uppercase tracking-widest animate-pulse">please wait awesome is loading</span>
                            </div>
                        </template>
                        <div class="absolute bottom-3 right-3 px-2 py-1 bg-black/70 backdrop-blur rounded-lg text-[10px] font-black text-white">{{ gmdate('i:s', $reel->duration ?? 0) }}</div>
                        <div class="absolute top-3 left-3 flex flex-col gap-1">
                            @if($reel->status == 0 && $reel->bunny_status === null)
                                <span class="px-2 py-1 bg-slate-700 text-white rounded-lg text-[9px] font-black uppercase shadow-lg border border-white/10">Draft</span>
                            @elseif((string)$reel->visibility === (string)\App\Constants\Status::PRIVATE)
                                <span class="px-2 py-1 bg-red-600 text-white rounded-lg text-[9px] font-black uppercase shadow-lg shadow-red-600/20 flex items-center gap-1">
                                    <span class="material-symbols-rounded text-[10px]">lock</span> Private
                                </span>
                            @elseif($reel->bunny_status === 'ready' || $reel->status == 1)
                                <span class="px-2 py-1 bg-green-500 text-white rounded-lg text-[9px] font-black uppercase shadow-lg shadow-green-500/20">Public</span>
                            @elseif($reel->bunny_status === 'uploading' || $reel->status == 0)
                                <span class="px-2 py-1 bg-yellow-500 text-white rounded-lg text-[9px] font-black uppercase shadow-lg shadow-yellow-500/20 animate-pulse">Optimizing</span>
                            @else
                                <span class="px-2 py-1 bg-red-500 text-white rounded-lg text-[9px] font-black uppercase shadow-lg shadow-red-500/20">Rejected</span>
                            @endif
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/20">
                            <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30">
                                <span class="material-symbols-rounded text-white text-4xl material-symbols-filled">play_arrow</span>
                            </div>
                        </div>
                    </a>
                    
                    <div class="p-5">
                        <h3 class="font-black text-sm text-slate-900 dark:text-white line-clamp-1 mb-2">{{ $reel->title }}</h3>
                        <div class="flex items-center gap-3 text-[10px] text-slate-500 dark:text-slate-400 font-bold mb-5">
                            <span class="flex items-center gap-1"><span class="material-symbols-rounded text-[12px]">visibility</span> {{ number_format($reel->views_count) }}</span>
                            <span class="flex items-center gap-1"><span class="material-symbols-rounded text-[12px]">favorite</span> {{ number_format($reel->likes_count ?? 0) }}</span>
                            <span class="flex items-center gap-1"><span class="material-symbols-rounded text-[12px]">chat_bubble</span> {{ number_format($reel->comments_count ?? 0) }}</span>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('studio.reels.edit', $reel) }}" class="w-9 h-9 bg-blue-50 dark:bg-blue-500/10 text-blue-600 rounded-xl flex items-center justify-center hover:bg-blue-100 transition-colors" title="Edit">
                                <span class="material-symbols-rounded text-sm">edit</span>
                            </a>
                            <button type="button" @click="confirmDelete('{{ route('studio.reels.destroy', $reel) }}')" class="w-9 h-9 bg-red-50 dark:bg-red-500/10 text-red-600 rounded-xl flex items-center justify-center hover:bg-red-100 transition-colors" title="Delete">
                                <span class="material-symbols-rounded text-sm">delete</span>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-20 h-20 rounded-[2rem] bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-300 dark:text-white/10 mb-6">
                        <span class="material-symbols-rounded text-4xl">movie</span>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2">No duets found</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest max-w-xs">Duets you create with other reels will appear here.</p>
                </div>
                @endforelse
            </div>

            @if($reels->hasPages())
                <div class="pb-12">
                    {{ $reels->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection