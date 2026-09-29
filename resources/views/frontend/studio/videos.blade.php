@extends('layouts.app')

@section('content')
<div class="transition-colors duration-500" x-data="{ activeTab: '{{ request()->get("tab", request()->has("reels_page") ? "reels" : "videos") }}' }" x-cloak>
    
    <!-- Mobile/Tablet Content UI -->
    <div class="lg:hidden max-w-7xl mx-auto px-6 py-10">
        <!-- Compact Header -->
        <div class="flex items-center justify-between mb-10">
             <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Your Content</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1" x-text="activeTab === 'videos' ? '{{ number_format($videos->total()) }} video uploads' : '{{ number_format($reels->total()) }} reel uploads'"></p>
            </div>
            <a :href="activeTab === 'videos' ? '{{ route('videos.create') }}' : '{{ route('reels.create') }}'" class="w-12 h-12 gradient-orange text-white rounded-2xl flex items-center justify-center shadow-lg shadow-orange-500/20 active:scale-95 transition-transform">
                <span class="material-symbols-rounded">add</span>
            </a>
        </div>

        <!-- Filters Horizontal -->
        <div class="flex gap-3 overflow-x-auto no-scrollbar mb-8 pb-2">
            <button @click="activeTab = 'videos'" 
                    :class="activeTab === 'videos' ? 'bg-blue-600 text-white shadow-blue-500/20 border-transparent' : 'bg-white dark:bg-white/5 text-slate-500 dark:text-slate-400 border-slate-100 dark:border-white/5'"
                    class="px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-sm transition-all border whitespace-nowrap">Videos</button>
            <button @click="activeTab = 'reels'" 
                    :class="activeTab === 'reels' ? 'bg-blue-600 text-white shadow-blue-500/20 border-transparent' : 'bg-white dark:bg-white/5 text-slate-500 dark:text-slate-400 border-slate-100 dark:border-white/5'"
                    class="px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-sm transition-all border whitespace-nowrap">Reels</button>
            <button @click="activeTab = 'watchlater'" 
                    :class="activeTab === 'watchlater' ? 'bg-blue-600 text-white shadow-blue-500/20 border-transparent' : 'bg-white dark:bg-white/5 text-slate-500 dark:text-slate-400 border-slate-100 dark:border-white/5'"
                    class="px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-sm transition-all border whitespace-nowrap">Watch Later</button>
            <button @click="activeTab = 'playlists'" 
                    :class="activeTab === 'playlists' ? 'bg-blue-600 text-white shadow-blue-500/20 border-transparent' : 'bg-white dark:bg-white/5 text-slate-500 dark:text-slate-400 border-slate-100 dark:border-white/5'"
                    class="px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-sm transition-all border whitespace-nowrap">Playlists</button>
        </div>
        
        <!-- Mobile Status Filters -->
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar mb-8">
            <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}" 
               class="px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all {{ !request()->get('status') ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/20' : 'bg-white dark:bg-white/5 text-slate-400 border border-slate-100 dark:border-white/5' }}">
                All
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'published']) }}" 
               class="px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all {{ request()->get('status') === 'published' ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/20' : 'bg-white dark:bg-white/5 text-slate-400 border border-slate-100 dark:border-white/5' }}">
                Published
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'draft']) }}" 
               class="px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all {{ request()->get('status') === 'draft' ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/20' : 'bg-white dark:bg-white/5 text-slate-400 border border-slate-100 dark:border-white/5' }}">
                Drafts
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'private']) }}" 
               class="px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all {{ request()->get('status') === 'private' ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/20' : 'bg-white dark:bg-white/5 text-slate-400 border border-slate-100 dark:border-white/5' }}">
                Private
            </a>
        </div>

        <!-- Videos Tab Content -->
        <div x-show="activeTab === 'videos'" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @forelse($videos as $video)
                <div class="bg-white dark:bg-white/5 rounded-[2.5rem] border border-slate-100 dark:border-white/10 shadow-sm overflow-hidden group"
                     @php
                    $isOptimizing = ($video->isBunnyVideo() && !in_array($video->bunny_status, ['ready', 'error', 'failed'])) || (!$video->isBunnyVideo() && $video->status === 'processing');
                 @endphp
                 x-data="{ 
                    progress: 0, 
                    isOptimizing: {{ $isOptimizing ? 'true' : 'false' }},
                    init() { if(this.isOptimizing) this.pollStatus(); },
                    async pollStatus() {
                        try {
                            let res;
                            @if($video->isBunnyVideo())
                                res = await fetch('{{ route('videos.show', $video) }}/status');
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
                <div class="relative aspect-video">
                    <img src="{{ $video->getThumbnailUrl() }}" class="w-full h-full object-cover" onerror="this.src='{{ $video->getPreviewUrl() }}'; this.onerror=null;">
                    
                    @if($video->status == \App\Constants\Status::DRAFT && $video->bunny_status !== 'ready')
                        <!-- Draft Processing/Uploading Overlay (Tap to Edit) -->
                        <a href="{{ route('videos.create', ['draft_id' => $video->id]) }}" class="absolute inset-0 bg-white/95 dark:bg-slate-950/95 backdrop-blur-md flex flex-col items-center justify-center p-3 text-center z-20 cursor-pointer hover:opacity-95 transition-opacity border border-slate-200 dark:border-slate-800">
                            <div class="relative w-10 h-10 mb-2 flex items-center justify-center">
                                <svg viewBox="0 0 40 40" class="w-full h-full transform -rotate-90">
                                    <circle cx="20" cy="20" r="16" stroke="#ff571a" stroke-opacity="0.15" stroke-width="3" fill="transparent" />
                                    <circle cx="20" cy="20" r="16" stroke="#ff571a" stroke-width="3" stroke-linecap="round" fill="transparent" class="transition-all duration-300" stroke-dasharray="100.5" :stroke-dashoffset="100.5 - (100.5 * (progress || 5) / 100)" />
                                </svg>
                            </div>
                            <span class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-wider mb-1">DRAFT UPLOADING</span>
                            <span class="text-[9px] font-bold text-slate-800 dark:text-slate-200 tracking-wide leading-tight px-2">Tap Edit to Continue</span>
                        </a>
                    @elseif($video->status != \App\Constants\Status::DRAFT)
                        <!-- Processing Overlay for Published Videos -->
                        <template x-if="isOptimizing">
                            <div class="absolute inset-0 bg-white/95 dark:bg-slate-950/95 backdrop-blur-md flex flex-col items-center justify-center p-3 text-center z-20 border border-slate-200 dark:border-slate-800">
                                <div class="relative w-10 h-10 mb-2 flex items-center justify-center">
                                    <svg viewBox="0 0 40 40" class="w-full h-full transform -rotate-90">
                                        <circle cx="20" cy="20" r="16" stroke="#ff571a" stroke-opacity="0.15" stroke-width="3" fill="transparent" />
                                        <circle cx="20" cy="20" r="16" stroke="#ff571a" stroke-width="3" stroke-linecap="round" fill="transparent" class="transition-all duration-300" stroke-dasharray="100.5" :stroke-dashoffset="100.5 - (100.5 * (progress || 5) / 100)" />
                                    </svg>
                                </div>
                                <span class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-wider mb-1">PROCESSING VIDEO</span>
                                <span class="text-[9px] font-bold text-slate-800 dark:text-slate-200 tracking-wide leading-tight px-2">Your video will be available within minutes</span>
                            </div>
                        </template>
                    @endif
                    
                    <!-- Play Overlay -->
                    <a href="{{ route('videos.show', $video) }}" class="absolute inset-0 flex items-center justify-center bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30">
                            <span class="material-symbols-rounded text-white text-4xl material-symbols-filled">play_arrow</span>
                        </div>
                    </a>

                    @if($video->status == \App\Constants\Status::DRAFT)
                        <a href="{{ route('videos.create', ['draft_id' => $video->id]) }}" class="absolute top-3 left-3 px-2.5 py-1 {{ $video->bunny_status === 'ready' ? 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/30' : 'bg-amber-500 hover:bg-amber-600 shadow-amber-500/30' }} rounded-lg text-[9px] font-black text-white uppercase tracking-widest shadow-lg flex items-center gap-1 z-10 transition-all" title="Edit Draft Video">
                            <span class="material-symbols-rounded text-[11px]">{{ $video->bunny_status === 'ready' ? 'check_circle' : 'edit' }}</span>
                            {{ $video->bunny_status === 'ready' ? 'Ready to Publish' : 'Draft' }}
                        </a>
                    @elseif($video->is_premium)
                        <div class="absolute top-4 left-4 w-9 h-9 bg-amber-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30 z-10 border-2 border-white dark:border-white/10">
                            <span class="material-symbols-rounded text-xl material-symbols-filled">workspace_premium</span>
                        </div>
                    @endif
                </div>
                
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <h4 class="font-black text-slate-900 dark:text-white truncate flex-1">{{ $video->title }}</h4>
                        @if($video->status == \App\Constants\Status::DRAFT)
                            <a href="{{ route('videos.create', ['draft_id' => $video->id]) }}" class="flex items-center gap-1 px-2.5 py-1 bg-amber-500 hover:bg-amber-600 text-white rounded-lg scale-90 transition-all shadow-sm" title="Edit Draft Video">
                                <span class="material-symbols-rounded text-[13px]">edit</span>
                                <span class="text-[9px] font-black uppercase">Draft</span>
                            </a>
                        @endif
                        @if($video->is_premium)
                            <div class="flex items-center gap-1 px-2 py-0.5 bg-amber-500 text-white rounded-lg scale-90">
                                <span class="material-symbols-rounded text-[12px] material-symbols-filled">workspace_premium</span>
                                <span class="text-[9px] font-black uppercase">Premium</span>
                            </div>
                        @endif
                        @if($video->scheduled_at && $video->scheduled_at->isFuture())
                            <div class="flex items-center gap-1 px-2 py-0.5 bg-blue-500 text-white rounded-lg scale-90">
                                <span class="material-symbols-rounded text-[12px] material-symbols-filled">schedule</span>
                                <span class="text-[9px] font-black uppercase" title="{{ $video->scheduled_at->format('M d, Y h:i A') }}">Scheduled</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="flex flex-col">
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Views</span>
                                <span class="text-xs font-black text-slate-900 dark:text-white">{{ number_format($video->views_count) }}</span>
                            </div>
                            <div class="h-6 w-px bg-slate-100 dark:bg-white/5"></div>
                            <div class="flex flex-col">
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1 flex items-center gap-0.5"><span class="material-symbols-rounded text-[10px] material-symbols-filled">thumb_up</span>Likes</span>
                                <span class="text-xs font-black text-slate-900 dark:text-white">{{ number_format($video->likes_count ?? 0) }}</span>
                            </div>
                            <div class="h-6 w-px bg-slate-100 dark:bg-white/5"></div>
                            <div class="flex flex-col">
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1 flex items-center gap-0.5"><span class="material-symbols-rounded text-[10px] material-symbols-filled">chat_bubble</span>Comments</span>
                                <span class="text-xs font-black text-slate-900 dark:text-white">{{ number_format($video->comments_count ?? 0) }}</span>
                            </div>
                            <div class="h-6 w-px bg-slate-100 dark:bg-white/5"></div>
                            <div class="flex flex-col">
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Date</span>
                                <span class="text-xs font-bold text-slate-500">{{ $video->created_at->format('d M') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        @if(auth()->user()->hasFeaturedAccess())
                        <button type="button" onclick="toggleFeatured('{{ route('studio.videos.toggle-featured', $video) }}', {{ $video->is_featured ? 'true' : 'false' }})" 
                                class="flex-1 py-4 bg-amber-50 dark:bg-amber-500/10 text-amber-600 text-center rounded-2xl font-black text-[9px] uppercase tracking-widest hover:bg-amber-100 transition-all border border-amber-200 dark:border-white/10">
                            {{ $video->is_featured ? 'Unfeature' : 'Feature' }}
                        </button>
                        @endif
                        <a href="{{ $video->status == \App\Constants\Status::DRAFT ? route('videos.create', ['draft_id' => $video->id]) : route('studio.videos.edit', $video) }}" class="flex-1 py-4 bg-slate-100 dark:bg-white/5 text-slate-900 dark:text-white text-center rounded-2xl font-black text-[9px] uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-white/10 transition-all border border-slate-200 dark:border-white/10">Edit</a>
                        <a href="{{ route('videos.show', $video) }}" class="flex-1 py-4 bg-blue-50 dark:bg-blue-500/10 text-blue-500 text-center rounded-2xl font-black text-[9px] uppercase tracking-widest hover:bg-blue-100 transition-all border border-blue-200 dark:border-white/10">Watch</a>
                        <button type="button" @click="confirmDelete('{{ route('studio.videos.destroy', $video) }}')" 
                                class="flex-1 py-4 bg-red-50 dark:bg-red-500/10 text-red-500 text-center rounded-2xl font-black text-[9px] uppercase tracking-widest hover:bg-red-100 transition-all border border-red-200 dark:border-white/10">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
            @empty
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-20 h-20 rounded-[2rem] bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-300 dark:text-white/10 mb-6">
                        <span class="material-symbols-rounded text-4xl">video_library</span>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2">No videos yet</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest max-w-xs">Start your creator journey by uploading your first video.</p>
                </div>
            @endforelse

            @if($videos->hasPages())
                <div class="pt-6 pb-12">
                    {{ $videos->appends(['reels_page' => $reels->currentPage()])->links() }}
                </div>
            @endif
        </div>

        <!-- Reels Tab Content (Mobile) -->
        <div x-show="activeTab === 'reels'" x-cloak>
            <div class="grid grid-cols-2 gap-3">
                @forelse($reels as $reel)
                @if(!$reel->slug) @continue @endif
                
                    <div class="bg-white dark:bg-[#1A1A1A] rounded-2xl border border-slate-100 dark:border-white/10 shadow-sm overflow-hidden group hover:shadow-lg transition-all"
                         @php
                        $isMixingReel = ($reel->is_duet || ($reel->music_source && $reel->music_source !== 'none'));
                        $isReelOptimizing = ($reel->bunny_status !== 'ready' && $reel->compression_status !== 3);
                        
                        $initialStatusText = 'Processing...';
                        if ($isMixingReel) {
                            $cStatus = (int)$reel->compression_status;
                            if ($cStatus === 0) $initialStatusText = 'Waiting for processing...';
                            elseif ($cStatus === 1) $initialStatusText = 'Mixing audio...';
                            elseif ($cStatus === 4) $initialStatusText = 'Uploading final video...';
                            elseif ($cStatus === 5) $initialStatusText = 'Processing video...';
                        }
                     @endphp
                     x-data="{ 
                        progress: 0, 
                        isOptimizing: {{ $isReelOptimizing ? 'true' : 'false' }},
                        isMixing: {{ ($isMixingReel && $isReelOptimizing && $reel->compression_status === 1 && !$reel->processing_bunny_id) ? 'true' : 'false' }},
                        statusText: '{{ $initialStatusText }}',
                        init() { 
                            if(this.isOptimizing) this.pollStatus(); 
                        },
                        async pollStatus() {
                            try {
                                let res;
                                @if($reel->slug)
                                    res = await fetch('{{ route('reels.check_status', $reel->slug) }}');
                                @else
                                    res = { json: async () => ({ success: false }) };
                                @endif
                                const data = await res.json();
                                if(data.success) {
                                    this.progress = data.encode_progress || 0;
                                    if(data.bunny_status === 'ready' && data.encode_progress >= 100 && (!data.is_mixing_reel || data.compression_status === 2)) {
                                        window.location.reload();
                                    } else if (data.bunny_status === 'error' || data.bunny_status === 'failed' || data.stage === 'failed') {
                                        console.log('[REEL-POLL] Reel #{{ $reel->id }} failed. Stopping poll.');
                                        this.statusText = 'Failed';
                                    } else {
                                        this.isMixing = data.is_mixing_reel && (data.compression_status === 0 || data.compression_status === 1);
                                        
                                        if (data.stage === 'queued') this.statusText = 'Waiting...';
                                        else if (data.stage === 'mixing') this.statusText = 'Mixing audio...';
                                        else if (data.stage === 'uploading_to_bunny') this.statusText = 'Uploading...';
                                        else if (data.stage === 'bunny_processing') this.statusText = 'Processing...';
                                        else this.statusText = 'Processing...';

                                        setTimeout(() => this.pollStatus(), 5000);
                                    }
                                } else {
                                    setTimeout(() => this.pollStatus(), 5000);
                                }
                            } catch(e) { 
                                setTimeout(() => this.pollStatus(), 10000); 
                            }
                        }
                     }">
                    <a href="{{ route('reels.show', $reel) }}" 
                       :class="isOptimizing ? 'cursor-not-allowed' : ''"
                       @click="if (isOptimizing) { event.preventDefault(); Swal.fire({toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, icon: 'info', title: isMixing ? 'This reel is still mixing audio...' : 'This reel is still processing on Bunny...', background: '#1A1A1A', color: '#ffffff'}); }"
                       class="block relative aspect-[2/3] bg-slate-100 dark:bg-black/40 overflow-hidden border border-slate-200 dark:border-slate-800">
                        <img src="{{ $reel->getThumbnailUrl() }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.src='{{ $reel->getPreviewUrl() }}'; this.onerror=null;">
                        
                        <!-- Optimizing State Overlay -->
                        <template x-if="isOptimizing">
                            <div class="absolute inset-0 bg-black/60 backdrop-blur-md flex flex-col items-center justify-center z-20 border border-slate-200 dark:border-slate-800">
                                <div class="relative w-10 h-10 mb-2">
                                    <svg viewBox="0 0 40 40" class="w-full h-full transform -rotate-90">
                                        <circle cx="20" cy="20" r="16" stroke="currentColor" stroke-width="3" fill="transparent" class="text-white/10" />
                                        <circle cx="20" cy="20" r="16" stroke="currentColor" stroke-width="3" fill="transparent" class="text-rose-500 transition-all duration-500" stroke-dasharray="100.5" :stroke-dashoffset="100.5 - (100.5 * (progress || 5) / 100)" />
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center"></div>
                                </div>
                                <span class="text-[7px] lg:text-[9px] font-black text-white uppercase tracking-wider text-center px-2 leading-tight animate-pulse" x-text="statusText"></span>
                                <span class="text-[6px] lg:text-[7px] text-white/60 uppercase font-bold tracking-wider mt-1 text-center px-2 animate-pulse">Your reel will post within minutes</span>
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
                    <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2">No reels yet</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest max-w-xs">Upload short, engaging vertical videos to reach more people.</p>
                </div>
                @endforelse
            </div>

            @if($reels->hasPages())
                <div class="pt-6 pb-12">
                    {{ $reels->appends(['videos_page' => $videos->currentPage()])->links() }}
                </div>
            @endif
        </div>

        <!-- Watch Later Tab Content (Mobile) -->
        <div x-show="activeTab === 'watchlater'" class="grid grid-cols-1 sm:grid-cols-2 gap-6" x-cloak>
            @forelse($watchLaters as $video)
                @if(!$video->slug) @continue @endif
                
                <div class="bg-white dark:bg-white/5 rounded-[2.5rem] border border-slate-100 dark:border-white/10 shadow-sm overflow-hidden group">
                    <div class="relative aspect-video">
                        <img src="{{ $video->getThumbnailUrl() }}" class="w-full h-full object-cover" onerror="this.src='{{ $video->getPreviewUrl() }}'; this.onerror=null;">
                        <a href="{{ $video->type === 'reel' ? route('reels.show', $video->slug) : route('videos.show', $video) }}" class="absolute inset-0 flex items-center justify-center bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30">
                                <span class="material-symbols-rounded text-white text-4xl material-symbols-filled">play_arrow</span>
                            </div>
                        </a>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <h4 class="font-black text-slate-900 dark:text-white truncate flex-1">{{ $video->title }}</h4>
                        </div>
                        <div class="flex items-center gap-3">
                             <a href="{{ $video->type === 'reel' ? route('reels.show', $video->slug) : route('videos.show', $video) }}" class="flex-1 py-4 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 text-center rounded-2xl font-black text-[9px] uppercase tracking-widest hover:bg-blue-100 transition-all">Watch Now</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-1 sm:col-span-2 flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-20 h-20 rounded-[2rem] bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-300 dark:text-white/10 mb-6">
                        <span class="material-symbols-rounded text-4xl">schedule</span>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2">No videos saved</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest max-w-xs">Videos you save to watch later will appear here.</p>
                </div>
            @endforelse
            @if($watchLaters->hasPages())
                <div class="pt-6 pb-12 col-span-1 sm:col-span-2">
                    {{ $watchLaters->appends(['videos_page' => $videos->currentPage(), 'reels_page' => $reels->currentPage()])->links() }}
                </div>
            @endif
        </div>

        <!-- Playlists Tab Content (Mobile) -->
        <div x-show="activeTab === 'playlists'" class="grid grid-cols-1 sm:grid-cols-2 gap-6" x-cloak>
            @forelse($playlists as $playlist)
            @php
                $firstItem = $playlist->videos->first() ?? $playlist->reels->first();
                $thumbnail = $firstItem ? $firstItem->getThumbnailUrl() : asset('assets/images/default.png');
            @endphp
            <div class="bg-white dark:bg-white/5 rounded-[2.5rem] border border-slate-100 dark:border-white/10 shadow-sm overflow-hidden group">
                <div class="relative aspect-video bg-slate-100 dark:bg-white/5 overflow-hidden">
                    <img src="{{ $thumbnail }}" alt="" class="w-full h-full object-cover absolute inset-0 group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-black/30 flex flex-col items-center justify-center text-white group-hover:bg-black/40 transition-colors z-10">
                        <span class="material-symbols-rounded text-5xl mb-2 drop-shadow-lg">playlist_play</span>
                        <span class="text-xs font-black uppercase tracking-widest drop-shadow-lg">{{ $playlist->videos_count }} videos</span>
                    </div>
                    <a href="{{ route('playlists.show', ['username' => optional($playlist->user->channel)->slug ?? $playlist->user->username ?? 'user', 'playlist' => $playlist->slug]) }}" class="absolute inset-0 z-20"></a>
                </div>
                
                <div class="p-6 relative z-20">
                    <h4 class="font-black text-slate-900 dark:text-white truncate mb-2">{{ $playlist->name }}</h4>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $playlist->created_at->format('M d, Y') }}</span>
                    
                    <div class="flex items-center gap-3 mt-6">
                         <a href="{{ route('playlists.show', ['username' => optional($playlist->user->channel)->slug ?? $playlist->user->username ?? 'user', 'playlist' => $playlist->slug]) }}" class="flex-1 py-4 bg-slate-100 dark:bg-white/5 text-slate-900 dark:text-white text-center rounded-2xl font-black text-[9px] uppercase tracking-widest hover:bg-slate-200 transition-all">View Playlist</a>
                    </div>
                </div>
            </div>
            @empty
                <div class="col-span-1 sm:col-span-2 flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-20 h-20 rounded-[2rem] bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-300 dark:text-white/10 mb-6">
                        <span class="material-symbols-rounded text-4xl">playlist_add</span>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2">No playlists</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest max-w-xs">Create playlists to organize your content.</p>
                </div>
            @endforelse
            @if($playlists->hasPages())
                <div class="pt-6 pb-12 col-span-1 sm:col-span-2">
                    {{ $playlists->appends(['videos_page' => $videos->currentPage(), 'reels_page' => $reels->currentPage()])->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Desktop Content UI -->
    <div class="mx-auto px-6 py-6 hidden lg:block">
        <!-- Header -->
        <div class="flex items-center justify-between mb-12">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Channel Content</h1>
                <p class="text-slate-500 font-medium mt-1">Manage all your uploaded videos and content performance.</p>
            </div>
            <a :href="activeTab === 'videos' ? '{{ route('videos.create') }}' : '{{ route('reels.create') }}'" 
               class="flex items-center gap-2 px-6 py-3 gradient-orange text-white font-black rounded-xl shadow-lg shadow-orange-500/20 transition-all hover:scale-105 active:scale-95">
                <span class="material-symbols-rounded" x-text="activeTab === 'videos' ? 'video_call' : 'movie'"></span>
                <span x-text="activeTab === 'videos' ? 'Upload Video' : 'Create Reel'"></span>
            </a>
        </div>

        <!-- Filters/Tabs -->
        <div class="flex items-center gap-8 border-b dark:border-white/5 mb-8 overflow-x-auto no-scrollbar">
            <button type="button" @click="activeTab = 'videos'; window.history.pushState(null, '', '?tab=videos');" 
               :class="activeTab === 'videos' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-white'"
               class="px-4 py-3 border-b-2 font-black text-sm uppercase tracking-widest transition-all">Videos</button>
            <button type="button" @click="activeTab = 'reels'; window.history.pushState(null, '', '?tab=reels');" 
               :class="activeTab === 'reels' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-white'"
               class="px-4 py-3 border-b-2 font-black text-sm uppercase tracking-widest transition-all">Reels</button>
            <button type="button" @click="activeTab = 'watchlater'; window.history.pushState(null, '', '?tab=watchlater');" 
               :class="activeTab === 'watchlater' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-white'"
               class="px-4 py-3 border-b-2 font-black text-sm uppercase tracking-widest transition-all">Watch Later</button>
            <button type="button" @click="activeTab = 'playlists'; window.history.pushState(null, '', '?tab=playlists');" 
               :class="activeTab === 'playlists' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-white'"
               class="px-4 py-3 border-b-2 font-black text-sm uppercase tracking-widest transition-all">Playlists</button>
        </div>

        <!-- Status Filters -->
        <div class="flex items-center gap-3 mb-10">
            <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}" 
               class="px-6 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all {{ !request()->get('status') ? 'bg-orange-600 text-white shadow-xl shadow-orange-600/20' : 'bg-slate-50 dark:bg-white/5 text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                All Content
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'published']) }}" 
               class="px-6 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all {{ request()->get('status') === 'published' ? 'bg-orange-600 text-white shadow-xl shadow-orange-600/20' : 'bg-slate-50 dark:bg-white/5 text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                Published
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'draft']) }}" 
               class="px-6 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all {{ request()->get('status') === 'draft' ? 'bg-orange-600 text-white shadow-xl shadow-orange-600/20' : 'bg-slate-50 dark:bg-white/5 text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                Drafts
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'private']) }}" 
               class="px-6 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all {{ request()->get('status') === 'private' ? 'bg-orange-600 text-white shadow-xl shadow-orange-600/20' : 'bg-slate-50 dark:bg-white/5 text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                Private
            </a>
        </div>

        <!-- Videos Tab Content (Desktop) -->
        <div x-show="activeTab === 'videos'" x-cloak>
            <div class="bg-white dark:bg-white/5 border border-slate-100 dark:border-white/10 rounded-[2.5rem] shadow-sm overflow-hidden mb-12">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b dark:border-white/5">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Video</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Views</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Comments</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Likes</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Options</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-white/5">
                            @forelse($videos as $video)
                @if(!$video->slug) @continue @endif
                
                            <tr class="group hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors"
                                @php
                                    $isOptimizing = ($video->isBunnyVideo() && !in_array($video->bunny_status, ['ready', 'error', 'failed'])) || (!$video->isBunnyVideo() && $video->status === 'processing');
                                @endphp
                                x-data="{ 
                                    progress: 0, 
                                    isOptimizing: {{ $isOptimizing ? 'true' : 'false' }},
                                    init() { if(this.isOptimizing) this.pollStatus(); },
                                    async pollStatus() {
                                        try {
                                            let res;
                                            @if($video->isBunnyVideo())
                                                res = await fetch('{{ route('videos.show', $video) }}/status');
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
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-32 aspect-video rounded-xl bg-slate-200 dark:bg-black/20 overflow-hidden shrink-0 relative group/thumb border border-slate-200 dark:border-slate-800">
                                            <img src="{{ $video->getThumbnailUrl() }}" class="w-full h-full object-cover" onerror="this.src='{{ $video->getPreviewUrl() }}'; this.onerror=null;">
                                            
                                             @if($video->status == \App\Constants\Status::DRAFT && $video->bunny_status !== 'ready')
                                                 <!-- Draft Processing/Uploading Overlay (Tap to Edit) -->
                                                 <a href="{{ route('videos.create', ['draft_id' => $video->id]) }}" class="absolute inset-0 bg-white/95 dark:bg-slate-950/95 backdrop-blur-md flex flex-col items-center justify-center p-2 text-center z-20 cursor-pointer hover:opacity-95 transition-opacity rounded-xl border border-slate-200 dark:border-slate-800">
                                                     <div class="relative w-8 h-8 mb-1 flex items-center justify-center">
                                                         <svg viewBox="0 0 32 32" class="w-full h-full transform -rotate-90">
                                                             <circle cx="16" cy="16" r="13" stroke="#ff571a" stroke-opacity="0.15" stroke-width="2.5" fill="transparent" />
                                                             <circle cx="16" cy="16" r="13" stroke="#ff571a" stroke-width="2.5" stroke-linecap="round" fill="transparent" class="transition-all duration-300" stroke-dasharray="81.6" :stroke-dashoffset="81.6 - (81.6 * (progress || 5) / 100)" />
                                                         </svg>
                                                     </div>
                                                     <span class="text-[9px] font-black text-slate-900 dark:text-white uppercase tracking-wider mb-0.5">DRAFT UPLOADING</span>
                                                     <span class="text-[7px] font-bold text-slate-800 dark:text-slate-200 tracking-wide leading-tight px-1">Tap Edit to Continue</span>
                                                 </a>
                                             @elseif($video->status != \App\Constants\Status::DRAFT)
                                                 <!-- Processing Overlay for Published Videos -->
                                                 <template x-if="isOptimizing">
                                                     <div class="absolute inset-0 bg-white/95 dark:bg-slate-950/95 backdrop-blur-md flex flex-col items-center justify-center p-2 text-center z-20 rounded-xl border border-slate-200 dark:border-slate-800">
                                                         <div class="relative w-8 h-8 mb-1 flex items-center justify-center">
                                                             <svg viewBox="0 0 32 32" class="w-full h-full transform -rotate-90">
                                                                 <circle cx="16" cy="16" r="13" stroke="#ff571a" stroke-opacity="0.15" stroke-width="2.5" fill="transparent" />
                                                                 <circle cx="16" cy="16" r="13" stroke="#ff571a" stroke-width="2.5" stroke-linecap="round" fill="transparent" class="transition-all duration-300" stroke-dasharray="81.6" :stroke-dashoffset="81.6 - (81.6 * (progress || 5) / 100)" />
                                                             </svg>
                                                         </div>
                                                         <span class="text-[9px] font-black text-slate-900 dark:text-white uppercase tracking-wider mb-0.5">PROCESSING VIDEO</span>
                                                         <span class="text-[7px] font-bold text-slate-800 dark:text-slate-200 tracking-wide leading-tight px-1">Available within minutes</span>
                                                     </div>
                                                 </template>
                                             @endif
                                             <a href="{{ route('videos.show', $video) }}" class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover/thumb:opacity-100 transition-opacity">
                                                 <span class="material-symbols-rounded text-white text-2xl material-symbols-filled">play_arrow</span>
                                             </a>
                                             @if($video->is_premium)
                                                 <div class="absolute top-2 right-2 w-7 h-7 bg-amber-500 text-white rounded-lg flex items-center justify-center shadow-lg">
                                                     <span class="material-symbols-rounded text-[18px] material-symbols-filled">workspace_premium</span>
                                                 </div>
                                             @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-black text-slate-900 dark:text-white truncate max-w-[300px] mb-0.5">{{ $video->title }}</p>
                                            <div class="flex items-center gap-2">
                                                @if($video->status == \App\Constants\Status::DRAFT)
                                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                                    <span class="text-[10px] font-black text-amber-500 uppercase">Draft</span>
                                                    @if($video->bunny_status === 'ready')
                                                        <span class="text-[10px] font-black text-emerald-500 uppercase">(Ready to Publish)</span>
                                                    @elseif($video->bunny_status === 'processing')
                                                        <span class="text-[10px] font-black text-blue-500 uppercase animate-pulse">(Processing...)</span>
                                                    @else
                                                        <span class="text-[10px] font-black text-amber-600 uppercase">(Upload Interrupted)</span>
                                                    @endif
                                                    <a href="{{ route('videos.create', ['draft_id' => $video->id]) }}" class="ml-1 px-2.5 py-0.5 bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/30 rounded-md text-[9px] font-black uppercase tracking-wider hover:bg-amber-500 hover:text-white transition-all flex items-center gap-1 shadow-sm" title="Edit Draft Video">
                                                        <span class="material-symbols-rounded text-[12px]">edit</span>
                                                        <span>Edit Draft</span>
                                                    </a>
                                                @elseif($video->moderation_status === 'struck')
                                                    <span class="w-2 h-2 rounded-full bg-rose-600"></span>
                                                    <span class="text-[10px] font-black text-rose-600 uppercase">Copyright Strike</span>
                                                @elseif($video->moderation_status === 'rejected')
                                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                                    <span class="text-[10px] font-black text-rose-500 uppercase">Rejected</span>
                                                @elseif($video->bunny_status === 'ready' || $video->status == 1)
                                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                                    <span class="text-[10px] font-black text-emerald-500 uppercase">Public</span>
                                                @else
                                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                                    <span class="text-[10px] font-black text-amber-500 uppercase flex items-center gap-1">
                                                        <span>Transcoding</span>
                                                        <span x-text="`(${Math.round(progress || 0)}%)`"></span>
                                                    </span>
                                                 @endif
                                                 @if($video->is_premium)
                                                     <div class="flex items-center gap-1.5 px-2 py-0.5 bg-amber-500/10 text-amber-500 rounded-full border border-amber-500/20">
                                                         <span class="material-symbols-rounded text-[12px] material-symbols-filled">workspace_premium</span>
                                                         <span class="text-[9px] font-black uppercase tracking-tighter">Premium</span>
                                                     </div>
                                                 @endif
                                             </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-xs font-bold text-slate-500">{{ $video->created_at->format('M d, Y') }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-xs font-black text-slate-900 dark:text-white">{{ number_format($video->views_count) }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-xs font-black text-slate-900 dark:text-white">{{ $video->comments_count }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-xs font-black text-slate-900 dark:text-white">{{ $video->likes_count }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-2">
                                        @if(auth()->user()->hasFeaturedAccess())
                                        <button type="button" onclick="toggleFeatured('{{ route('studio.videos.toggle-featured', $video) }}', {{ $video->is_featured ? 'true' : 'false' }})" 
                                                class="w-9 h-9 rounded-xl flex items-center justify-center bg-amber-50 dark:bg-amber-500/10 text-amber-600 hover:bg-amber-100 transition-all border border-amber-200/50 dark:border-amber-500/20"
                                                title="{{ $video->is_featured ? 'Unfeature' : 'Feature' }}">
                                            <span class="material-symbols-rounded text-sm">{{ $video->is_featured ? 'grade' : 'star' }}</span>
                                        </button>
                                        @endif
                                        <a href="{{ $video->status == \App\Constants\Status::DRAFT ? route('videos.create', ['draft_id' => $video->id]) : route('studio.videos.edit', $video) }}" class="w-9 h-9 rounded-xl flex items-center justify-center bg-blue-50 dark:bg-blue-500/10 text-blue-600 hover:bg-blue-100 transition-all border border-blue-200/50 dark:border-blue-500/20" title="Edit">
                                            <span class="material-symbols-rounded text-sm">edit</span>
                                        </a>
                                        <a href="{{ route('videos.show', $video) }}" class="w-9 h-9 rounded-xl flex items-center justify-center bg-slate-100 dark:bg-white/10 text-slate-600 dark:text-slate-300 hover:bg-slate-200 transition-all border border-slate-200 dark:border-white/10" title="Watch">
                                            <span class="material-symbols-rounded text-sm">visibility</span>
                                        </a>
                                        <button type="button" onclick="event.stopPropagation(); confirmDelete('{{ route('studio.videos.destroy', $video) }}')" 
                                                class="w-9 h-9 rounded-xl flex items-center justify-center bg-red-50 dark:bg-red-500/10 text-red-600 hover:bg-red-100 transition-all border border-red-200/50 dark:border-red-500/20" title="Delete">
                                            <span class="material-symbols-rounded text-sm">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-20">
                                        <div class="flex flex-col items-center justify-center text-center">
                                            <div class="w-20 h-20 rounded-[2rem] bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-300 dark:text-white/10 mb-6">
                                                <span class="material-symbols-rounded text-4xl">video_library</span>
                                            </div>
                                            <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2">No videos found</h3>
                                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest max-w-xs">Your uploaded videos will appear here.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($videos->hasPages())
                    <div class="px-8 py-6 border-t dark:border-white/5">
                        {{ $videos->appends(['reels_page' => $reels->currentPage()])->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Reels Tab Content (Desktop) -->
        <div x-show="activeTab === 'reels'" x-cloak>
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4 lg:gap-6 mb-12">
                @forelse($reels as $reel)
                @if(!$reel->slug) @continue @endif
                
                    <div class="bg-white dark:bg-[#1A1A1A] rounded-3xl border border-slate-100 dark:border-white/10 shadow-sm overflow-hidden group hover:shadow-xl transition-all duration-300"
                         @php
                        $isMixingReel = ($reel->is_duet || ($reel->music_source && $reel->music_source !== 'none'));
                        $isReelOptimizing = ($reel->bunny_status !== 'ready' && $reel->compression_status !== 3);
                        
                        $initialStatusText = 'Processing...';
                        if ($isMixingReel) {
                            $cStatus = (int)$reel->compression_status;
                            if ($cStatus === 0) $initialStatusText = 'Waiting for processing...';
                            elseif ($cStatus === 1) $initialStatusText = 'Mixing audio...';
                            elseif ($cStatus === 4) $initialStatusText = 'Uploading final video...';
                            elseif ($cStatus === 5) $initialStatusText = 'Processing video...';
                        }
                     @endphp
                     x-data="{ 
                        progress: 0, 
                        isOptimizing: {{ $isReelOptimizing ? 'true' : 'false' }},
                        isMixing: {{ ($isMixingReel && $isReelOptimizing && $reel->compression_status === 1 && !$reel->processing_bunny_id) ? 'true' : 'false' }},
                        statusText: '{{ $initialStatusText }}',
                        init() {
                            if(this.isOptimizing) this.pollStatus(); 
                        },
                        async pollStatus() {
                            try {
                                let res;
                                @if($reel->slug)
                                    res = await fetch('{{ route('reels.check_status', $reel->slug) }}');
                                @else
                                    res = { json: async () => ({ success: false }) };
                                @endif
                                const data = await res.json();
                                if(data.success) {
                                    this.progress = data.encode_progress || 0;
                                    if(data.bunny_status === 'ready' && data.encode_progress >= 100 && (!data.is_mixing_reel || data.compression_status === 2)) {
                                        window.location.reload();
                                    } else if (data.bunny_status === 'error' || data.bunny_status === 'failed' || data.stage === 'failed') {
                                        console.log('[REEL-POLL] Reel #{{ $reel->id }} failed. Stopping poll.');
                                        this.statusText = 'Failed';
                                    } else {
                                        this.isMixing = data.is_mixing_reel && (data.compression_status === 0 || data.compression_status === 1);
                                        
                                        if (data.stage === 'queued') this.statusText = 'Waiting...';
                                        else if (data.stage === 'mixing') this.statusText = 'Mixing audio...';
                                        else if (data.stage === 'uploading_to_bunny') this.statusText = 'Uploading...';
                                        else if (data.stage === 'bunny_processing') this.statusText = 'Processing...';
                                        else this.statusText = 'Processing...';

                                        setTimeout(() => this.pollStatus(), 5000);
                                    }
                                } else {
                                    setTimeout(() => this.pollStatus(), 5000);
                                }
                            } catch(e) { 
                                setTimeout(() => this.pollStatus(), 10000); 
                            }
                        }
                      }">
                    <a href="{{ route('reels.show', $reel) }}" 
                       :class="isOptimizing ? 'cursor-not-allowed' : ''"
                       @click="if (isOptimizing) { event.preventDefault(); Swal.fire({toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, icon: 'info', title: isMixing ? 'This reel is still mixing audio...' : 'This reel is still processing on Bunny...', background: '#1A1A1A', color: '#ffffff'}); }"
                       class="block relative aspect-[2/3] bg-slate-100 dark:bg-black/40 overflow-hidden">
                        <img src="{{ $reel->getThumbnailUrl() }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.src='{{ $reel->getPreviewUrl() }}'; this.onerror=null;">
                        
                        <!-- Optimizing State Overlay -->
                        <template x-if="isOptimizing">
                            <div class="absolute inset-0 bg-black/60 backdrop-blur-md flex flex-col items-center justify-center z-20">
                                <div class="relative w-10 h-10 mb-2">
                                    <svg class="w-full h-full transform -rotate-90">
                                        <circle cx="20" cy="20" r="16" stroke="currentColor" stroke-width="3" fill="transparent" class="text-white/10" />
                                        <circle cx="20" cy="20" r="16" stroke="currentColor" stroke-width="3" fill="transparent" class="text-rose-500 transition-all duration-500" stroke-dasharray="100.5" :stroke-dashoffset="100.5 - (100.5 * (progress || 5) / 100)" />
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center"></div>
                                </div>
                                <span class="text-[7px] lg:text-[9px] font-black text-white uppercase tracking-wider text-center px-2 leading-tight animate-pulse" x-text="statusText"></span>
                                <span class="text-[6px] lg:text-[7px] text-white/60 uppercase font-bold tracking-wider mt-1 text-center px-2 animate-pulse">Your reel will post within minutes</span>
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
                    <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2">No reels found</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest max-w-xs">Your short-form videos will appear here.</p>
                </div>
                @endforelse
            </div>

            @if($reels->hasPages())
                <div class="pb-12">
                    {{ $reels->appends(['videos_page' => $videos->currentPage()])->links() }}
                </div>
            @endif
        </div>

        <!-- Watch Later Tab Content (Desktop) -->
        <div x-show="activeTab === 'watchlater'" x-cloak>
            <div class="grid grid-cols-3 gap-6 mb-12">
                @forelse($watchLaters as $video)
                @if(!$video->slug) @continue @endif
                
                <div class="bg-white dark:bg-white/5 rounded-[2.5rem] border border-slate-100 dark:border-white/10 shadow-sm overflow-hidden group hover:shadow-xl transition-all duration-300">
                    <div class="relative aspect-video">
                        <img src="{{ $video->getThumbnailUrl() }}" class="w-full h-full object-cover" onerror="this.src='{{ $video->getPreviewUrl() }}'; this.onerror=null;">
                        <a href="{{ $video->type === 'reel' ? route('reels.show', $video->slug) : route('videos.show', $video) }}" class="absolute inset-0 flex items-center justify-center bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30">
                                <span class="material-symbols-rounded text-white text-4xl material-symbols-filled">play_arrow</span>
                            </div>
                        </a>
                    </div>
                    
                    <div class="p-6">
                        <h4 class="font-black text-slate-900 dark:text-white truncate mb-4">{{ $video->title }}</h4>
                        <div class="flex items-center gap-3">
                             <a href="{{ $video->type === 'reel' ? route('reels.show', $video->slug) : route('videos.show', $video) }}" class="w-full py-3 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 text-center rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-100 transition-all">Watch Now</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 flex flex-col items-center justify-center py-20 text-center bg-white dark:bg-white/5 rounded-[2.5rem] border border-slate-100 dark:border-white/10">
                    <div class="w-20 h-20 rounded-[2rem] bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-300 dark:text-white/10 mb-6">
                        <span class="material-symbols-rounded text-4xl">schedule</span>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2">No videos saved</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest max-w-xs">Videos you save to watch later will appear here.</p>
                </div>
                @endforelse
            </div>
            @if($watchLaters->hasPages())
                <div class="pb-12">
                    {{ $watchLaters->appends(['videos_page' => $videos->currentPage(), 'reels_page' => $reels->currentPage()])->links() }}
                </div>
            @endif
        </div>

        <!-- Playlists Tab Content (Desktop) -->
        <div x-show="activeTab === 'playlists'" x-cloak>
            <div class="grid grid-cols-3 gap-6 mb-12">
                @forelse($playlists as $playlist)
                @php
                    $firstItem = $playlist->videos->first() ?? $playlist->reels->first();
                    $thumbnail = $firstItem ? $firstItem->getThumbnailUrl() : asset('assets/images/default.png');
                @endphp
                <div class="bg-white dark:bg-white/5 rounded-[2.5rem] border border-slate-100 dark:border-white/10 shadow-sm overflow-hidden group hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="relative aspect-video bg-slate-100 dark:bg-white/5 overflow-hidden">
                        <img src="{{ $thumbnail }}" alt="" class="w-full h-full object-cover absolute inset-0 group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-black/30 flex flex-col items-center justify-center text-white group-hover:bg-black/40 transition-colors z-10">
                            <span class="material-symbols-rounded text-6xl mb-3 drop-shadow-lg">playlist_play</span>
                            <span class="text-xs font-black uppercase tracking-widest drop-shadow-lg">{{ $playlist->videos_count }} videos</span>
                        </div>
                        <a href="{{ route('playlists.show', ['username' => optional($playlist->user->channel)->slug ?? $playlist->user->username ?? 'user', 'playlist' => $playlist->slug]) }}" class="absolute inset-0 z-20"></a>
                    </div>
                    
                    <div class="p-6 relative z-40">
                        <h4 class="font-black text-slate-900 dark:text-white truncate text-lg mb-1">{{ $playlist->name }}</h4>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $playlist->created_at->format('M d, Y') }}</span>
                        
                        <div class="flex items-center gap-3 mt-6">
                             <a href="{{ route('playlists.show', ['username' => optional($playlist->user->channel)->slug ?? $playlist->user->username ?? 'user', 'playlist' => $playlist->slug]) }}" class="w-full py-3 bg-slate-100 dark:bg-white/5 text-slate-900 dark:text-white text-center rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">View Playlist</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 flex flex-col items-center justify-center py-20 text-center bg-white dark:bg-white/5 rounded-[2.5rem] border border-slate-100 dark:border-white/10">
                    <div class="w-20 h-20 rounded-[2rem] bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-300 dark:text-white/10 mb-6">
                        <span class="material-symbols-rounded text-4xl">playlist_add</span>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2">No playlists</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest max-w-xs">Create playlists to organize your content.</p>
                </div>
                @endforelse
            </div>
            @if($playlists->hasPages())
                <div class="pb-12">
                    {{ $playlists->appends(['videos_page' => $videos->currentPage(), 'reels_page' => $reels->currentPage()])->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Shared Delete Form -->
    <form id="delete-form-global" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <form id="premium-form-global" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="price" id="premium-price-input-global">
    </form>

    <form id="featured-form-global" method="POST" class="hidden">
        @csrf
    </form>
</div>
@push('script')
<script>
    function toggleFeatured(route, isFeatured) {
        if (isFeatured) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Are you sure to make this video is normal?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#F97316',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, make it normal!',
                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('featured-form-global');
                    form.action = route;
                    form.submit();
                }
            });
        } else {
            const form = document.getElementById('featured-form-global');
            form.action = route;
            form.submit();
        }
    }

</script>
@endpush
@endsection
