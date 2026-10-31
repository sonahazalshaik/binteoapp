@extends('admin.layouts.app')
@section('title', 'Reels')
@section('header_title', 'Reel Management')

@section('content')
<div x-data="{ 
    selectAll: false, 
    toggleAll() { 
        const checkboxes = document.querySelectorAll('.bulk-select-item');
        checkboxes.forEach(cb => { 
            cb.checked = this.selectAll; 
            cb.dispatchEvent(new Event('change', { bubbles: true }));
        });
    }
}" class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] shadow-sm transition-all duration-300">
    @include('admin.components.header-toolbar', [
        'title' => $pageTitle ?? 'Reels',
        'items' => $reels,
        'createRoute' => route('admin.reels.create'),
        'createLabel' => 'Add Reel'
    ])

    <div class="px-6 mt-4">
        <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 flex items-start gap-3 shadow-sm">
            <span class="material-symbols-rounded text-xl text-amber-500 mt-0.5">info</span>
            <div class="text-sm leading-relaxed">
                <span class="font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wide text-xs">Important Note:</span>
                <span class="font-medium text-amber-900 dark:text-amber-100 ml-2">
                    Reels are automatically deleted from BunnyCDN only when deleted by the user or via this Admin Panel. Please <strong class="font-bold text-red-600 dark:text-red-400">do not delete reels manually in BunnyCDN</strong> as it will not remove the entries from our site.
                </span>
            </div>
        </div>
    </div>

    <div class="px-6 pt-6">
        @include('admin.components.table-toolbar', [
            'showBulkActions' => true,
            'module' => 'reels',
            'bulkRoute' => route('admin.reels.bulk'),
            'bulkActions' => ['approve', 'unapprove', 'featured', 'unfeatured', 'trending', 'untrending', 'delete'],
            'exportTotal' => count($reels)
        ])
    </div>

    <div class="overflow-x-auto scrollbar-hide px-6" x-show="!$store.viewMode || $store.viewMode.mode === 'table'" x-cloak>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="selectAllHeader" x-model="selectAll" @change="toggleAll()" class="w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                        </label>
                    </th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Title')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Creator')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Duration')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Stats')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Status')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right">@lang('Action')</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($reels as $reel)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors">
                        <td class="px-6 py-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" value="{{ $reel->id }}" class="bulk-select-item w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                            </label>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-16 rounded-lg bg-slate-100 dark:bg-white/5 overflow-hidden shrink-0 relative group/thumb cursor-pointer" 
                                     onclick="playMedia('{{ $reel->isBunnyReel() ? $reel->getPlayUrl() : $reel->getVideoUrl() }}', '{{ addslashes($reel->title) }}')">
                                    <img src="{{ $reel->getThumbnailUrl() }}" class="w-full h-full object-cover" onerror="this.src='{{ $reel->getPreviewUrl() }}'; this.onerror=null;">
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover/thumb:opacity-100 transition-opacity">
                                        <span class="material-symbols-rounded text-white text-xl">play_arrow</span>
                                    </div>
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-slate-900 dark:text-white line-clamp-1">{{ $reel->title }}</p>
                                    <div class="flex items-center gap-2">
                                        @if($reel->is_trending)<span class="text-[8px] font-black text-orange-500 uppercase">🔥 Trending</span>@endif
                                        @if($reel->is_duet)<span class="text-[8px] font-black text-blue-500 uppercase">👥 Duet</span>@endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-sm">{{ '@' . @$reel->user->username }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold text-slate-500">{{ gmdate('i:s', $reel->duration) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-3 text-xs font-bold text-slate-500">
                                <span title="Views">👁 {{ $reel->views_count }}</span>
                                <span title="Likes">❤ {{ $reel->likes_count }}</span>
                                <span title="Comments">💬 {{ $reel->comments_count }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">{!! $reel->statusBadge !!}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="grid grid-cols-2 gap-2 w-max ml-auto">
                                <button onclick="playMedia('{{ $reel->isBunnyReel() ? $reel->getPlayUrl() : $reel->getVideoUrl() }}', '{{ addslashes($reel->title) }}')" class="px-3 h-8 rounded-lg bg-indigo-500 text-white flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 shadow-sm" title="Play">
                                    <span class="material-symbols-rounded text-sm">play_arrow</span>@lang('Play')
                                </button>
                                <a href="{{ route('admin.reels.edit', $reel->id) }}" class="px-3 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 shadow-sm" title="Edit">
                                    <span class="material-symbols-rounded text-sm">edit</span>@lang('Edit')
                                </a>
                                @if($reel->status != \App\Constants\Status::PUBLISHED)
                                <form action="{{ route('admin.reels.approve', $reel->id) }}" method="POST" class="inline">@csrf
                                    <button class="w-full px-3 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 shadow-sm" title="Approve">
                                        <span class="material-symbols-rounded text-sm">check</span>@lang('Approve')
                                    </button>
                                </form>
                                @endif
                                @if($reel->status != \App\Constants\Status::REJECTED)
                                <form action="{{ route('admin.reels.reject', $reel->id) }}" method="POST" class="inline">@csrf
                                    <button class="w-full px-3 h-8 rounded-lg bg-rose-500 text-white flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 shadow-sm" title="Reject">
                                        <span class="material-symbols-rounded text-sm">block</span>@lang('Reject')
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('admin.reels.toggle-trending', $reel->id) }}" method="POST" class="inline">@csrf
                                    <button class="w-full px-3 h-8 rounded-lg text-white flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 shadow-sm" style="background: {{ $reel->is_trending ? 'linear-gradient(to right, #f59e0b, #ea580c)' : 'linear-gradient(to right, #94a3b8, #64748b)' }} !important;" title="Toggle Trending">
                                        <span class="material-symbols-rounded text-sm">trending_up</span>@lang('Trend')
                                    </button>
                                </form>
                                <button class="px-3 h-8 rounded-lg bg-rose-600 text-white flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 shadow-sm confirmationBtn" data-action="{{ route('admin.reels.destroy', $reel->slug, false) }}" data-method="DELETE" data-question="@lang('Delete this reel permanently?')">
                                    <span class="material-symbols-rounded text-sm">delete</span>@lang('Delete')
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td class="text-muted text-center py-10" colspan="100%">{{ __($emptyMessage ?? 'No reels found') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak class="p-6 pb-24">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse($reels as $reel)
                <div class="bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] p-6 border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-xl transition-all duration-300 relative group">
                    <label class="absolute top-8 left-8 z-20 cursor-pointer">
                        <input type="checkbox" value="{{ $reel->id }}" class="bulk-select-item w-5 h-5 rounded-lg border-white/20 bg-black/20 text-rose-500 focus:ring-rose-500 backdrop-blur-md">
                    </label>
                    <div class="w-full aspect-[9/16] bg-slate-200 dark:bg-white/5 rounded-3xl mb-6 relative overflow-hidden flex items-center justify-center border-4 border-slate-100 dark:border-white/5 group-hover:border-rose-500/30 transition-all">
                        <img src="{{ $reel->getThumbnailUrl() }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <button onclick="playMedia('{{ $reel->isBunnyReel() ? $reel->getPlayUrl() : $reel->getVideoUrl() }}', '{{ addslashes($reel->title) }}')" class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/20 backdrop-blur-[2px] z-10">
                            <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 shadow-2xl">
                                <span class="material-symbols-rounded text-4xl text-white ml-1">play_arrow</span>
                            </div>
                        </button>
                        <div class="absolute bottom-3 right-3 bg-slate-900/80 backdrop-blur-md px-3 py-1 rounded-xl z-20">
                            <span class="text-[10px] font-black text-white tracking-widest ">{{ gmdate('i:s', $reel->duration) }}</span>
                        </div>
                    </div>

                    <!-- New Reel Status Control Row -->
                    <div class="grid grid-cols-2 gap-2 mb-6">
                        <div class="px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center gap-2 shadow-sm">
                            <div class="w-2.5 h-2.5 rounded-full {{ $reel->status == \App\Constants\Status::PUBLISHED ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500' }}"></div>
                            <span class="text-[10px] font-black text-slate-700 dark:text-white/60 uppercase tracking-widest">{{ $reel->status == \App\Constants\Status::PUBLISHED ? 'Published' : 'Pending' }}</span>
                        </div>
                        <form action="{{ route('admin.reels.toggle-trending', $reel->id) }}" method="POST" class="w-full swal-action-form"
                              data-swal-title="{{ $reel->is_trending ? 'Untrend Reel?' : 'Trend this Reel?' }}"
                              data-swal-text="This will update the visibility of this reel in the global trending feed.">
                            @csrf
                            <button type="submit" class="w-full py-2.5 rounded-xl text-white shadow-lg flex items-center justify-center gap-2 transition-all hover:scale-105 active:scale-95 border border-white/10" style="background: {{ $reel->is_trending ? 'linear-gradient(to bottom, #f59e0b, #ea580c)' : 'linear-gradient(to bottom, #475569, #1e293b)' }} !important;">
                                <span class="material-symbols-rounded text-sm {{ $reel->is_trending ? 'fill-1' : '' }}">local_fire_department</span>
                                <span class="text-[10px] font-black uppercase tracking-widest">{{ $reel->is_trending ? 'Trending' : 'Trend' }}</span>
                            </button>
                        </form>
                    </div>

                    <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase leading-tight mb-4 line-clamp-2">{{ $reel->title }}</h3>

                    <div class="flex flex-wrap items-center gap-2 mb-6">
                        <div class="px-3 py-1.5 bg-slate-100 dark:bg-white/5 rounded-xl border border-slate-200 dark:border-white/10">
                            <span class="text-[9px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest ">CREATOR: {{ '@' . @$reel->user->username }}</span>
                        </div>
                        @if($reel->is_trending)
                            <div class="px-3 py-1.5 bg-orange-500/10 rounded-xl border border-orange-500/20">
                                <span class="text-[9px] font-black text-orange-500 uppercase tracking-widest ">Trending</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 mb-6">
                        <div class="flex-1 bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5 rounded-2xl p-2 flex flex-col items-center justify-center text-center">
                            <span class="text-lg font-black text-slate-900 dark:text-white mb-1">{{ number_format($reel->views_count) }}</span>
                            <span class="text-[8px] font-black text-rose-500 uppercase tracking-widest leading-none">Views</span>
                        </div>
                        <div class="flex-1 bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5 rounded-2xl p-2 flex flex-col items-center justify-center text-center">
                            <span class="text-lg font-black text-slate-900 dark:text-white mb-1">{{ number_format($reel->likes_count) }}</span>
                            <span class="text-[8px] font-black text-slate-900 dark:text-white uppercase tracking-widest leading-none">Likes</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2 mb-4">
                        @if($reel->status != \App\Constants\Status::PUBLISHED)
                        <form action="{{ route('admin.reels.approve', $reel->id) }}" method="POST" class="flex-1 swal-action-form"
                              data-swal-title="Approve Reel?" data-swal-text="This will make the reel public and notify the creator.">
                            @csrf
                            <button class="w-full h-14 rounded-2xl text-white flex flex-col items-center justify-center hover:opacity-90 transition-all shadow-lg" style="background: linear-gradient(135deg, #10b981, #059669) !important;">
                                <span class="material-symbols-rounded text-base mb-0.5">check_circle</span>
                                <span class="text-[9px] font-black uppercase tracking-widest ">Approve</span>
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('admin.reels.edit', @$reel->id) }}" class="flex-1 h-14 rounded-2xl text-white flex flex-col items-center justify-center hover:opacity-90 transition-all shadow-lg" style="background: linear-gradient(135deg, #2563eb, #1e40af) !important;">
                            <span class="material-symbols-rounded text-base mb-0.5">edit</span>
                            <span class="text-[9px] font-black uppercase tracking-widest ">Edit</span>
                        </a>
                        <form action="{{ route('admin.reels.destroy', $reel->slug) }}" method="POST" class="flex-1 swal-action-form"
                              data-swal-title="Delete Reel?" data-swal-text="Are you sure you want to delete this reel? This action cannot be undone." data-swal-icon="error">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full h-14 rounded-2xl text-white flex flex-col items-center justify-center hover:opacity-90 transition-all shadow-lg" style="background: linear-gradient(135deg, #e11d48, #9f1239) !important;">
                                <span class="material-symbols-rounded text-base mb-0.5">delete</span>
                                <span class="text-[9px] font-black uppercase tracking-widest ">Delete</span>
                            </button>
                        </form>
                    </div>

                    <div class="flex items-center gap-2">
                        <form action="{{ route('admin.reels.toggle-trending', $reel->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button class="w-full h-12 rounded-2xl text-white flex flex-col items-center justify-center hover:opacity-90 transition-all shadow-lg" style="background: {{ $reel->is_trending ? 'linear-gradient(135deg, #f59e0b, #ea580c)' : 'linear-gradient(135deg, #94a3b8, #64748b)' }} !important;">
                                <span class="material-symbols-rounded text-lg mb-0.5">trending_up</span>
                                <span class="text-[7px] font-black uppercase tracking-[0.1em]">{{ $reel->is_trending ? 'Trending' : 'Trend' }}</span>
                            </button>
                        </form>
                        <button onclick="playMedia('{{ $reel->isBunnyReel() ? $reel->getPlayUrl() : $reel->getVideoUrl() }}', '{{ addslashes($reel->title) }}')" class="flex-1 h-12 rounded-2xl text-white flex flex-col items-center justify-center hover:opacity-90 transition-all shadow-lg" style="background: linear-gradient(135deg, #6366f1, #4338ca) !important;">
                            <span class="material-symbols-rounded text-lg mb-0.5">play_circle</span>
                            <span class="text-[7px] font-black uppercase tracking-[0.1em]">Play</span>
                        </button>
                        @if($reel->status != \App\Constants\Status::REJECTED)
                        <form action="{{ route('admin.reels.reject', $reel->id) }}" method="POST" class="flex-1 swal-action-form"
                              data-swal-title="Reject Reel?" data-swal-text="The reel will be hidden from public view. Notify creator?" data-swal-icon="warning">
                            @csrf
                            <button type="submit" class="w-full h-12 rounded-2xl text-white flex flex-col items-center justify-center hover:opacity-90 transition-all shadow-lg" style="background: linear-gradient(135deg, #ef4444, #b91c1c) !important;">
                                <span class="material-symbols-rounded text-lg mb-0.5">block</span>
                                <span class="text-[7px] font-black uppercase tracking-[0.1em]">Reject</span>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center text-slate-400 font-black uppercase tracking-widest ">
                    No Reels Found
                </div>
            @endforelse
        </div>
    </div>

    @if ($reels->hasPages())
        <div class="card-footer py-4 px-6 border-t border-slate-100 dark:border-white/5">{{ paginateLinks($reels) }}</div>
    @endif
</div>

<!-- Reel Player Modal -->
<div id="reelPlayerModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
    <div class="relative w-full max-w-sm aspect-[9/16] bg-black rounded-3xl overflow-hidden shadow-2xl">
        <button onclick="closeReelModal()" class="absolute top-4 right-4 z-50 w-10 h-10 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center hover:bg-white/30 transition-all">
            <span class="material-symbols-rounded">close</span>
        </button>
        <video id="reelVideoPlayer" class="w-full h-full object-contain" controls autoplay loop playsinline></video>
        <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/80 to-transparent">
            <h3 id="reelModalTitle" class="text-white font-black text-lg truncate"></h3>
        </div>
    </div>
</div>

<x-confirmation-modal />
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
@endpush

@push('script')
<script>
    window.hls = null;

    window.playMedia = function(url, title) {
        const modal = document.getElementById('reelPlayerModal');
        const video = document.getElementById('reelVideoPlayer');
        const titleEl = document.getElementById('reelModalTitle');
        
        if (!modal || !video || !titleEl) return;

        titleEl.textContent = title;
        modal.classList.remove('hidden');

        if (url.includes('.m3u8')) {
            if (typeof Hls !== 'undefined' && Hls.isSupported()) {
                if (window.hls) window.hls.destroy();
                window.hls = new Hls();
                window.hls.loadSource(url);
                window.hls.attachMedia(video);
                window.hls.on(Hls.Events.MANIFEST_PARSED, () => video.play());
            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = url;
                video.play();
            } else {
                window.adminSwal({title:'Playback Error',text:'HLS playback not supported.',icon:'error'});
            }
        } else {
            if (window.hls) { window.hls.destroy(); window.hls = null; }
            video.src = url;
            video.play().catch(err => console.error('Play error:', err));
        }
    };

    window.closeReelModal = function() {
        const modal = document.getElementById('reelPlayerModal');
        const video = document.getElementById('reelVideoPlayer');
        if (video) video.pause();
        if (window.hls) { window.hls.destroy(); window.hls = null; }
        if (video) video.src = '';
        if (modal) modal.classList.add('hidden');
    };

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('reelPlayerModal')?.addEventListener('click', function(e) {
            if (e.target === this) window.closeReelModal();
        });
    });
</script>
@endpush

@push('breadcrumb-plugins')
    <x-search-form placeholder="Search reels..." />
@endpush
