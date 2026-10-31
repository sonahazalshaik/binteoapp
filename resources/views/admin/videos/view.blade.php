@extends('admin.layouts.app')

@section('title', 'Video Details')
@section('header_title', 'Video Detail')

@section('content')
<div class="min-h-screen bg-[#f8fafc] dark:bg-[#0a0a0b] -m-10 p-10 font-['Outfit',sans-serif]">
    <div class="max-w-[1400px] mx-auto space-y-8">
        
        <!-- App-Style Header -->
        <div class="flex items-center justify-between animate-in fade-in slide-in-from-top-4 duration-700">
            <div class="flex items-center gap-5">
                <a href="{{ route('admin.videos.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 shadow-sm border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-indigo-600 transition-all">
                    <span class="material-symbols-rounded">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Video Intelligence</h1>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mt-0.5">Management Console</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" 
                            class="h-12 px-6 rounded-2xl bg-indigo-600 text-white font-bold text-xs uppercase tracking-widest flex items-center gap-2 shadow-lg shadow-indigo-600/20 hover:scale-[1.02] transition-all">
                        <span class="material-symbols-rounded text-lg">admin_panel_settings</span>
                        Moderate
                        <span class="material-symbols-rounded text-lg transition-transform" :class="{ 'rotate-180': open }">expand_more</span>
                    </button>
                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         class="absolute right-0 mt-3 w-64 bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 rounded-3xl shadow-2xl z-50 p-2 overflow-hidden backdrop-blur-xl">
                         
                         @if($video->moderation_status !== 'approved')
                            <form action="{{ route('admin.videos.approve', $video->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-emerald-50 text-emerald-600 dark:hover:bg-emerald-500/10 transition-all text-left">
                                    <span class="material-symbols-rounded text-xl">check_circle</span>
                                    <span class="text-[11px] font-bold uppercase tracking-wider">Approve</span>
                                </button>
                            </form>
                        @endif

                        @if($video->moderation_status !== 'rejected')
                            <form action="{{ route('admin.videos.reject', $video->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-rose-50 text-rose-600 dark:hover:bg-rose-500/10 transition-all text-left">
                                    <span class="material-symbols-rounded text-xl">block</span>
                                    <span class="text-[11px] font-bold uppercase tracking-wider">Reject</span>
                                </button>
                            </form>
                        @endif
                         
                         <a href="{{ route('admin.videos.edit', $video->id) }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-slate-50 dark:hover:bg-white/5 text-slate-600 dark:text-white/60 transition-all">
                            <span class="material-symbols-rounded text-xl">edit</span>
                            <span class="text-[11px] font-bold uppercase tracking-wider">Edit Data</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Main Content Area -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Immersive Video Card -->
                <div class="bg-white dark:bg-[#121212] rounded-[2.5rem] shadow-sm border border-slate-200 dark:border-white/10 overflow-hidden">
                    <div class="p-4">
                        <div class="aspect-video rounded-[2rem] overflow-hidden bg-black relative shadow-inner">
                            <video id="player" playsinline controls poster="{{ $video->getThumbnailUrl() }}" class="w-full h-full object-contain">
                                @if($video->isBunnyVideo())
                                    <source src="{{ $video->getPlayUrl() }}" type="application/x-mpegURL">
                                @else
                                    <source src="{{ $video->getVideoUrl() }}" type="video/mp4">
                                @endif
                            </video>
                        </div>
                    </div>
                    
                    <div class="px-8 pb-8 pt-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight mb-2">{{ $video->title }}</h2>
                                <div class="flex items-center gap-4">
                                    <span class="flex items-center gap-1.5 text-xs font-bold text-slate-400">
                                        <span class="material-symbols-rounded text-base">calendar_today</span>
                                        {{ $video->created_at->format('M d, Y') }}
                                    </span>
                                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                    <span class="flex items-center gap-1.5 text-xs font-bold text-slate-400">
                                        <span class="material-symbols-rounded text-base">schedule</span>
                                        {{ $video->duration ?? '00:00' }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                {!! $video->statusBadge !!}
                                <span class="px-3 py-1 rounded-full bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold uppercase tracking-widest">{{ $video->visibility }}</span>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center gap-4">
                            <div class="flex-1 p-4 rounded-2xl bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-white/5 flex grid grid-cols-2 md:grid-cols-4 gap-6 items-center justify-between">
                                <a href="{{ route('admin.videos.likes', $video->id) }}" class="flex items-center gap-3 group/link hover:bg-white dark:hover:bg-white/5 p-2 rounded-xl transition-all">
                                    <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center group-hover/link:scale-110 transition-transform">
                                        <span class="material-symbols-rounded text-xl fill-1">favorite</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900 dark:text-white">{{ number_format($video->likes->count()) }}</div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Likes</div>
                                    </div>
                                </a>
                                <a href="{{ route('admin.videos.manage.comments', $video->id) }}" class="flex items-center gap-3 group/link hover:bg-white dark:hover:bg-white/5 p-2 rounded-xl transition-all">
                                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center group-hover/link:scale-110 transition-transform">
                                        <span class="material-symbols-rounded text-xl">comment</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900 dark:text-white">{{ number_format($video->allComments->count()) }}</div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Comments</div>
                                    </div>
                                </a>
                                <a href="{{ route('admin.videos.manage.playlists', $video->id) }}" class="flex items-center gap-3 group/link hover:bg-white dark:hover:bg-white/5 p-2 rounded-xl transition-all">
                                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center group-hover/link:scale-110 transition-transform">
                                        <span class="material-symbols-rounded text-xl">playlist_play</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900 dark:text-white">{{ number_format($video->playlists->count()) }}</div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Playlists</div>
                                    </div>
                                </a>
                                <div class="flex items-center gap-3 p-2">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                                        <span class="material-symbols-rounded text-xl">visibility</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900 dark:text-white">{{ number_format($video->views_count) }}</div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Total Views</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Descriptive Section -->
                <div class="bg-white dark:bg-[#121212] rounded-[2.5rem] shadow-sm border border-slate-200 dark:border-white/10 p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                            <span class="material-symbols-rounded text-xl">subject</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Description</h3>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-medium">
                        {{ $video->description ?? 'No description provided.' }}
                    </p>
                </div>

                <!-- Related Content -->
                @if($relatedVideos->count() > 0)
                <div class="space-y-4">
                    <div class="flex items-center justify-between px-2">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Related Content</h3>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $relatedVideos->count() }} Videos</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($relatedVideos->take(4) as $v)
                            <a href="{{ route('admin.videos.show', $v->id) }}" class="group bg-white dark:bg-[#121212] rounded-3xl p-3 border border-slate-200 dark:border-white/10 shadow-sm flex items-center gap-4 hover:shadow-md transition-all">
                                <div class="w-24 aspect-video rounded-2xl overflow-hidden shrink-0">
                                    <img src="{{ $v->getThumbnailUrl() }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>
                                <div class="flex-grow overflow-hidden">
                                    <h4 class="text-xs font-bold text-slate-900 dark:text-white truncate mb-1">{{ $v->title }}</h4>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase">{{ number_format($v->views_count) }} Views</span>
                                        <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase">{{ $v->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Feedback Section -->
                <div class="bg-white dark:bg-[#121212] rounded-[2.5rem] shadow-sm border border-slate-200 dark:border-white/10 p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                <span class="material-symbols-rounded text-xl">forum</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Feedback Registry</h3>
                        </div>
                    </div>
                    
                    <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                        @forelse($video->allComments as $comment)
                            <div class="p-5 rounded-3xl bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-white dark:bg-white/5 shadow-sm flex items-center justify-center overflow-hidden border border-slate-100 dark:border-white/10">
                                        @if($comment->user->image)
                                            <img src="{{ getImage(getFilePath('userProfile') . '/' . $comment->user->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-sm font-bold text-indigo-500">{{ substr($comment->user->username, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div class="flex-grow">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-[11px] font-bold text-slate-900 dark:text-white">{{ $comment->user->fullname }}</span>
                                            <span class="text-[9px] font-bold text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-normal">{{ $comment->content }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center opacity-40">
                                <span class="material-symbols-rounded text-4xl mb-2">cloud_off</span>
                                <p class="text-[10px] font-bold uppercase tracking-widest">No comments available</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-4 space-y-8 sticky top-10 animate-in fade-in slide-in-from-right-8 duration-700">
                
                <!-- Native App-Style Profile Card -->
                <div class="bg-white dark:bg-[#121212] rounded-[2.5rem] shadow-sm border border-slate-200 dark:border-white/10 overflow-hidden">
                    <div class="h-32 bg-indigo-600 relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 to-purple-600"></div>
                        <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                    </div>
                    
                    <div class="px-8 pb-8 -mt-12 relative z-10 text-center">
                        <div class="w-24 h-24 rounded-3xl p-1 bg-white dark:bg-[#121212] mx-auto shadow-xl mb-4">
                            <div class="w-full h-full rounded-[1.2rem] overflow-hidden bg-slate-100">
                                @if($video->user->image)
                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $video->user->image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-indigo-500 font-bold text-3xl">{{ substr($video->user->username, 0, 1) }}</div>
                                @endif
                            </div>
                        </div>
                        
                        <h4 class="text-xl font-bold text-slate-900 dark:text-white">{{ $video->user->fullname }}</h4>
                        <p class="text-[11px] font-bold text-slate-400 mb-6">@<span>{{ $video->user->username }}</span></p>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-white/[0.03] text-center border border-slate-100 dark:border-white/5">
                                <div class="text-lg font-extrabold text-slate-900 dark:text-white">{{ number_format($userStats['total_videos']) }}</div>
                                <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">Videos</div>
                            </div>
                            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-white/[0.03] text-center border border-slate-100 dark:border-white/5">
                                <div class="text-lg font-extrabold text-slate-900 dark:text-white">{{ number_format($userStats['subscribers']) }}</div>
                                <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">Subscribers</div>
                            </div>
                        </div>
                        
                        <a href="{{ route('admin.users.detail', $video->user->id) }}" class="w-full h-12 rounded-2xl bg-slate-900 dark:bg-white text-white dark:text-black font-bold text-[10px] uppercase tracking-widest flex items-center justify-center gap-2 mt-6 shadow-xl hover:scale-[1.02] transition-all">
                            <span class="material-symbols-rounded text-lg">person</span>
                            View Profile
                        </a>
                    </div>
                </div>

                <!-- Banner Slot 1 -->
                @if($slot1Banners->count() > 0)
                <div class="rounded-[2.5rem] overflow-hidden shadow-sm border border-slate-200 dark:border-white/10 group">
                    <div x-data="{ active: 0, count: {{ $slot1Banners->count() }} }" class="relative aspect-[16/9] bg-slate-100">
                        @foreach($slot1Banners as $index => $banner)
                            <a x-show="active === {{ $index }}" 
                               x-transition:enter="transition ease-out duration-500"
                               x-transition:enter-start="opacity-0"
                               x-transition:enter-end="opacity-100"
                               href="{{ route('banner.redirect', $banner->slug) }}" target="_blank" class="absolute inset-0">
                                <img src="{{ getImage($banner->image) }}" class="w-full h-full object-cover">
                            </a>
                        @endforeach
                        
                        @if($slot1Banners->count() > 1)
                            <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-1.5">
                                @foreach($slot1Banners as $index => $banner)
                                    <button @click="active = {{ $index }}" class="w-2 h-2 rounded-full transition-all" :class="active === {{ $index }} ? 'bg-white scale-125' : 'bg-white/40'"></button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Banner Slot 2 -->
                @if($slot2Banners->count() > 0)
                <div class="rounded-[2.5rem] overflow-hidden shadow-sm border border-slate-200 dark:border-white/10 group">
                    <div x-data="{ active: 0, count: {{ $slot2Banners->count() }} }" class="relative aspect-[16/9] bg-slate-100">
                        @foreach($slot2Banners as $index => $banner)
                            <a x-show="active === {{ $index }}" 
                               x-transition:enter="transition ease-out duration-500"
                               x-transition:enter-start="opacity-0"
                               x-transition:enter-end="opacity-100"
                               href="{{ route('banner.redirect', $banner->slug) }}" target="_blank" class="absolute inset-0">
                                <img src="{{ getImage($banner->image) }}" class="w-full h-full object-cover">
                            </a>
                        @endforeach
                        
                        @if($slot2Banners->count() > 1)
                            <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-1.5">
                                @foreach($slot2Banners as $index => $banner)
                                    <button @click="active = {{ $index }}" class="w-2 h-2 rounded-full transition-all" :class="active === {{ $index }} ? 'bg-white scale-125' : 'bg-white/40'"></button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Playlists Distribution -->
                <div class="bg-white dark:bg-[#121212] rounded-[2.5rem] shadow-sm border border-slate-200 dark:border-white/10 p-8">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-widest mb-6">Distribution</h3>
                    <div class="space-y-3">
                        @forelse($video->playlists as $playlist)
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-white/[0.03] border border-transparent hover:border-slate-200 dark:hover:border-white/10 transition-all">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div class="w-8 h-8 rounded-lg bg-white dark:bg-white/5 flex items-center justify-center text-indigo-500 shadow-sm border border-slate-100 dark:border-white/5">
                                        <span class="material-symbols-rounded text-lg">auto_stories</span>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700 dark:text-white truncate">{{ $playlist->name }}</span>
                                </div>
                                <span class="material-symbols-rounded text-emerald-500 text-base">verified</span>
                            </div>
                        @empty
                            <p class="text-[10px] font-bold text-slate-400 text-center uppercase tracking-widest py-4">No active playlists</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Player Dependencies -->
<link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
<script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

<style>
    .plyr--full-ui.plyr--video .plyr__control--overlaid { background: #4f46e5 !important; }
    .plyr--video .plyr__control.plyr__tab-focus, .plyr--video .plyr__control:hover, .plyr--video .plyr__control[aria-expanded=true] { background: #4f46e5 !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; }
    .fill-1 { font-variation-settings: 'FILL' 1; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const video = document.querySelector('#player');
        const source = video.querySelector('source').src;
        
        const defaultOptions = {
            controls: ['play-large', 'play', 'progress', 'current-time', 'duration', 'mute', 'volume', 'settings', 'pip', 'fullscreen'],
            settings: ['quality', 'speed'],
            invertTime: false,
        };

        if (Hls.isSupported() && source.includes('.m3u8')) {
            const hls = new Hls({ maxBufferLength: 30, enableWorker: true });
            hls.loadSource(source);
            hls.attachMedia(video);
            window.hls = hls;

            const player = new Plyr(video, defaultOptions);
            hls.on(Hls.Events.MANIFEST_PARSED, () => {
                const qualities = hls.levels.map(l => l.height);
                player.config.quality.options = [0, ...qualities];
                player.quality = 0;
            });

            player.on('qualitychange', (event) => {
                const quality = event.detail.quality;
                hls.currentLevel = quality === 0 ? -1 : hls.levels.findIndex(l => l.height === quality);
            });
        } else {
            const player = new Plyr(video, defaultOptions);
        }
    });
</script>
@endsection

