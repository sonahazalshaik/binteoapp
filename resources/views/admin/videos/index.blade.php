@extends('admin.layouts.app')
@section('title', 'Videos')
@section('header_title', 'Video Management')

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
        'title' => 'Video Management',
        'items' => $videos,
        'createRoute' => route('admin.videos.create'),
        'createLabel' => 'Add Video'
    ])

    <div class="px-6 mt-4">
        <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 flex items-start gap-3 shadow-sm">
            <span class="material-symbols-rounded text-xl text-amber-500 mt-0.5">info</span>
            <div class="text-sm leading-relaxed">
                <span class="font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wide text-xs">Important Note:</span>
                <span class="font-medium text-amber-900 dark:text-amber-100 ml-2">
                    Videos are automatically deleted from BunnyCDN only when deleted by the user or via this Admin Panel. Please <strong class="font-bold text-red-600 dark:text-red-400">do not delete videos manually in BunnyCDN</strong> as it will not remove the entries from our site.
                </span>
            </div>
        </div>
    </div>

    <div class="px-6 pt-6">
        @include('admin.components.table-toolbar', [
            'showBulkActions' => true,
            'module' => 'videos',
            'bulkRoute' => route('admin.videos.bulk'),
            'bulkActions' => ['featured', 'unfeatured', 'trending', 'untrending', 'delete'],
            'exportTotal' => count($videos)
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
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Video')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Creator')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Type & Visibility')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Details')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Status')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right">@lang('Action')</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($videos as $video)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                    <td class="px-6 py-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" value="{{ $video->id }}" class="bulk-select-item w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                        </label>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.videos.show', $video) }}" class="w-16 h-10 rounded-lg bg-slate-100 dark:bg-white/5 overflow-hidden shrink-0 relative group/thumb">
                                <img src="{{ $video->getThumbnailUrl() }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover/thumb:opacity-100 transition-opacity">
                                    <span class="material-symbols-rounded text-white text-xl">visibility</span>
                                </div>
                            </a>
                            <div>
                                <a href="{{ route('admin.videos.show', $video) }}" class="font-bold text-sm text-slate-900 dark:text-white line-clamp-1 hover:text-indigo-500 transition-colors">{{ $video->title }}</a>
                                <span class="text-[9px] text-slate-400 block">{{ $video->category->name ?? 'Uncategorized' }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.users.detail', @$video->user->id) }}" class="font-bold">
                            <span>@</span>{{ __(@$video->user->username) }}
                        </a><br>
                        <span class="text-xs text-slate-400">{{ __(@$video->user->channel_name) }}</span>
                    </td>
                        <td class="px-6 py-4">
                            {{ $video->is_shorts_video ? __('Shorts') : __('Regular') }}
                        </td>
                        <td class="px-6 py-4">@php echo $video->visibilityStatus; @endphp</td>
                        <td class="px-6 py-4">{!! $video->statusBadge !!}</td>
                        <td class="px-6 py-4">
                            <div class="grid grid-cols-2 gap-2 w-max ml-auto">
                                <a href="{{ route('admin.videos.show', $video) }}" class="px-3 h-8 rounded-lg bg-indigo-500 text-white flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 shadow-sm">
                                    <span class="material-symbols-rounded text-sm">visibility</span>@lang('View')
                                </a>
                                <a href="{{ route('admin.videos.likes', $video) }}" class="px-3 h-8 rounded-lg bg-rose-500 text-white flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 shadow-sm">
                                    <span class="material-symbols-rounded text-sm">favorite</span>@lang('Likes')
                                </a>
                                <a href="{{ route('admin.videos.manage.comments', $video) }}" class="px-3 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 shadow-sm">
                                    <span class="material-symbols-rounded text-sm">chat_bubble</span>@lang('Comms')
                                </a>
                                <a href="{{ route('admin.videos.manage.playlists', $video) }}" class="px-3 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 shadow-sm">
                                    <span class="material-symbols-rounded text-sm">playlist_add</span>@lang('Lists')
                                </a>
                                <a href="{{ route('admin.videos.manage.watch-later', $video) }}" class="px-3 h-8 rounded-lg bg-slate-700 text-white flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 shadow-sm">
                                    <span class="material-symbols-rounded text-sm">schedule</span>@lang('Later')
                                </a>
                                <a href="@if (!$video->isPublished()) javascript:void(0) @else{{ route('admin.videos.analytics', $video->id) }} @endif" class="px-3 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 shadow-sm @if (!$video->isPublished()) opacity-50 cursor-not-allowed @endif">
                                    <span class="material-symbols-rounded text-sm">analytics</span>@lang('Stats')
                                </a>
                                <form action="{{ route('admin.videos.toggle-featured', $video) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="w-full h-8 rounded-lg text-white flex items-center justify-center gap-1 shadow-md px-3 border-0 transition-all hover:scale-105" style="background: {{ $video->is_featured ? 'linear-gradient(to right, #f59e0b, #ea580c)' : 'linear-gradient(to right, #94a3b8, #64748b)' }} !important;">
                                        <span class="material-symbols-rounded text-sm">{{ $video->is_featured ? 'grade' : 'star' }}</span>
                                        <span class="text-[8px] font-black uppercase tracking-tight">{{ $video->is_featured ? 'Featured' : 'Feature' }}</span>
                                    </button>
                                </form>
                                <form action="{{ route('admin.videos.toggle-trending', $video) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="w-full h-8 rounded-lg text-white flex items-center justify-center gap-1 shadow-md px-3 border-0 transition-all hover:scale-105" style="background: {{ $video->is_trending ? 'linear-gradient(to right, #10b981, #0d9488)' : 'linear-gradient(to right, #94a3b8, #64748b)' }} !important;">
                                        <span class="material-symbols-rounded text-sm">{{ $video->is_trending ? 'trending_up' : 'trending_down' }}</span>
                                        <span class="text-[8px] font-black uppercase tracking-tight">{{ $video->is_trending ? 'Trending' : 'Trend' }}</span>
                                    </button>
                                </form>
                                <form action="{{ route('admin.videos.toggle-age-restricted', $video->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="w-full h-8 rounded-lg text-white flex items-center justify-center gap-1 shadow-md px-3 border-0 transition-all hover:scale-105" style="background: {{ $video->is_age_restricted ? 'linear-gradient(to right, #8b5cf6, #6d28d9)' : 'linear-gradient(to right, #94a3b8, #64748b)' }} !important;">
                                        <span class="material-symbols-rounded text-sm">{{ $video->is_age_restricted ? '18_up_rating' : 'no_adult_content' }}</span>
                                        <span class="text-[8px] font-black uppercase tracking-tight">{{ $video->is_age_restricted ? '18+' : 'Restrict' }}</span>
                                    </button>
                                </form>
                                <a href="{{ route('admin.videos.edit', $video) }}" class="px-3 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 shadow-sm">
                                    <span class="material-symbols-rounded text-sm">edit</span>@lang('Edit')
                                </a>
                                <button class="px-3 h-8 rounded-lg bg-rose-600 text-white flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 shadow-sm confirmationBtn" data-action="{{ route('admin.videos.destroy', $video, false) }}" data-method="DELETE" data-question="@lang('Are you sure?')">
                                    <span class="material-symbols-rounded text-sm">delete</span>@lang('Delete')
                                </button>
                                <button type="button" class="px-3 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 shadow-sm strikeBtn" data-id="{{ $video->id }}" data-title="{{ $video->title }}">
                                    <span class="material-symbols-rounded text-sm">gavel</span>@lang('Strike')
                                </button>
                                @if($video->status == \App\Constants\Status::VIDEO_STRUCK)
                                    @php $strikeId = $video->getActiveStrikeId(); @endphp
                                    @if($strikeId)
                                        <a href="{{ route('admin.moderation.strikes.remove', $strikeId) }}" class="px-3 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 shadow-sm">
                                            <span class="material-symbols-rounded text-sm">undo</span>@lang('Remove Strike')
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-muted text-center py-10" colspan="100%">{{ __($emptyMessage) }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak class="p-6 pb-24">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($videos as $video)
                <div class="bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] p-6 border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-xl transition-all duration-300 relative group">
                    
                    <div class="w-full aspect-video bg-slate-200 dark:bg-white/5 rounded-3xl mb-4 relative overflow-hidden flex items-center justify-center border-4 border-slate-100 dark:border-white/5 group-hover:border-[#ff3366]/30 transition-all">
                        <img src="{{ $video->getThumbnailUrl() }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <a href="{{ route('admin.videos.show', $video) }}" class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/20 backdrop-blur-[2px]">
                            <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 shadow-2xl">
                                <span class="material-symbols-rounded text-4xl text-white">visibility</span>
                            </div>
                        </a>
                        <div x-data="{
                                durationSeconds: 0,
                                orig: @js($video->formatted_duration),
                                init() {
                                    if (this.orig && this.orig !== '00:00' && this.orig !== '0:00' && this.orig !== '') return;
                                    this.$nextTick(() => {
                                        const v = this.$refs.probe;
                                        if (!v) return;
                                        const set = () => { if (v.duration && isFinite(v.duration) && v.duration !== Infinity) this.durationSeconds = v.duration; };
                                        v.addEventListener('loadedmetadata', set, {once:true});
                                        v.addEventListener('durationchange', set, {once:true});
                                        try { v.load(); } catch(e) {}
                                        if (v.readyState >= 1 && v.duration) set();
                                        v.addEventListener('loadedmetadata', () => {
                                            if (v.duration === Infinity) {
                                                try { v.currentTime = Number.MAX_SAFE_INTEGER; v.ontimeupdate = null; v.addEventListener('seeked', () => { if(isFinite(v.duration)) this.durationSeconds = v.duration; try{ v.currentTime=0;}catch(e){} }, {once:true}); } catch(e){}
                                            }
                                        }, {once:true});
                                    });
                                },
                                get displayDuration() {
                                    if (this.orig && this.orig !== '00:00' && this.orig !== '0:00' && this.orig !== '') return this.orig;
                                    if (this.durationSeconds > 0) {
                                        const h = Math.floor(this.durationSeconds/3600);
                                        const m = Math.floor((this.durationSeconds%3600)/60);
                                        const s = Math.floor(this.durationSeconds%60);
                                        if(h>0) return h+':'+String(m).padStart(2,'0')+':'+String(s).padStart(2,'0');
                                        return String(m).padStart(2,'0')+':'+String(s).padStart(2,'0');
                                    }
                                    return (this.orig && this.orig !== '00:00' && this.orig !== '0:00') ? this.orig : '--:--';
                                }
                            }" class="absolute bottom-3 right-3 bg-slate-900/80 backdrop-blur-md px-3 py-1 rounded-xl">
                            <span class="text-[10px] font-black text-white tracking-widest" x-text="displayDuration">{{ ($video->formatted_duration !== '00:00' && $video->formatted_duration !== '0:00' && $video->formatted_duration) ? $video->formatted_duration : '--:--' }}</span>
                            @if(!$video->isBunnyVideo())
                                <video x-ref="probe" src="{{ $video->getVideoUrl() }}" preload="metadata" muted playsinline webkit-playsinline style="display:none"></video>
                            @endif
                        </div>
                    </div>

                    <!-- New Status Control Row -->
                    <div class="grid grid-cols-3 gap-2 mb-6">
                        <form action="{{ route('admin.videos.toggle-featured', $video) }}" method="POST" class="w-full swal-action-form"
                              data-swal-title="{{ $video->is_featured ? 'Remove from Featured?' : 'Feature this Video?' }}"
                              data-swal-text="This will update the visibility of this video on the platform.">
                            @csrf
                            <button type="submit" class="w-full py-2.5 rounded-xl text-white shadow-lg flex items-center justify-center gap-1.5 transition-all hover:scale-105 active:scale-95 border border-white/10" style="background: {{ $video->is_featured ? 'linear-gradient(to bottom, #f59e0b, #ea580c)' : 'linear-gradient(to bottom, #475569, #1e293b)' }} !important;">
                                <span class="material-symbols-rounded text-sm {{ $video->is_featured ? 'fill-1' : '' }}">{{ $video->is_featured ? 'grade' : 'star' }}</span>
                                <span class="text-[9px] font-black uppercase tracking-tighter">{{ $video->is_featured ? 'Featured' : 'Feature' }}</span>
                            </button>
                        </form>
                        <form action="{{ route('admin.videos.toggle-trending', $video) }}" method="POST" class="w-full swal-action-form"
                              data-swal-title="{{ $video->is_trending ? 'Remove from Trending?' : 'Set as Trending?' }}"
                              data-swal-text="This will update the priority of this video in the trending feed.">
                            @csrf
                            <button type="submit" class="w-full py-2.5 rounded-xl text-white shadow-lg flex items-center justify-center gap-1.5 transition-all hover:scale-105 active:scale-95 border border-white/10" style="background: {{ $video->is_trending ? 'linear-gradient(to bottom, #10b981, #0d9488)' : 'linear-gradient(to bottom, #475569, #1e293b)' }} !important;">
                                <span class="material-symbols-rounded text-sm {{ $video->is_trending ? 'fill-1' : '' }}">{{ $video->is_trending ? 'trending_up' : 'trending_down' }}</span>
                                <span class="text-[9px] font-black uppercase tracking-tighter">{{ $video->is_trending ? 'Trending' : 'Trend' }}</span>
                            </button>
                        </form>
                        <form action="{{ route('admin.videos.toggle-age-restricted', $video->id) }}" method="POST" class="w-full swal-action-form"
                              data-swal-title="{{ $video->is_age_restricted ? 'Remove 18+ Restriction?' : 'Apply 18+ Restriction?' }}"
                              data-swal-text="Gated videos require user verification and authentication.">
                            @csrf
                            <button type="submit" class="w-full py-2.5 rounded-xl text-white shadow-lg flex items-center justify-center gap-1.5 transition-all hover:scale-105 active:scale-95 border border-white/10" style="background: {{ $video->is_age_restricted ? 'linear-gradient(to bottom, #8b5cf6, #6d28d9)' : 'linear-gradient(to bottom, #475569, #1e293b)' }} !important;">
                                <span class="material-symbols-rounded text-sm {{ $video->is_age_restricted ? 'fill-1' : '' }}">{{ $video->is_age_restricted ? 'explicit' : 'no_adult_content' }}</span>
                                <span class="text-[9px] font-black uppercase tracking-tighter">{{ $video->is_age_restricted ? '18+' : 'Restrict' }}</span>
                            </button>
                        </form>
                    </div>

                    <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase leading-tight mb-4 line-clamp-2">{{ $video->title }}</h3>

                    <div class="flex flex-wrap items-center gap-2 mb-6">
                        <div class="px-3 py-1.5 bg-slate-100 dark:bg-white/5 rounded-xl border border-slate-200 dark:border-white/10">
                            <span class="text-[9px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest ">CREATOR: {{ '@' . @$video->user->username }}</span>
                        </div>
                        <div class="px-3 py-1.5 bg-slate-100 dark:bg-white/5 rounded-xl border border-slate-200 dark:border-white/10">
                            <span class="text-[9px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest ">{{ showDateTime($video->created_at, 'M d, Y') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mb-6">
                        <div class="flex-1 bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5 rounded-2xl p-2 flex flex-col items-center justify-center text-center">
                            <span class="text-lg font-black text-slate-900 dark:text-white mb-1">{{ $video->likes()->count() ?? 0 }}</span>
                            <span class="text-[8px] font-black text-rose-500 uppercase tracking-widest leading-none">Likes</span>
                        </div>
                        <div class="flex-1 bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5 rounded-2xl p-2 flex flex-col items-center justify-center text-center">
                            <span class="text-lg font-black text-slate-900 dark:text-white mb-1">{{ $video->comments()->count() ?? 0 }}</span>
                            <span class="text-[8px] font-black text-slate-900 dark:text-white uppercase tracking-widest leading-none">Comms</span>
                        </div>
                        <div class="flex-1 bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5 rounded-2xl p-2 flex flex-col items-center justify-center text-center">
                            <span class="text-lg font-black text-slate-900 dark:text-white mb-1">{{ $video->playlists()->count() ?? 0 }}</span>
                            <span class="text-[8px] font-black text-amber-500 uppercase tracking-widest leading-none">Lists</span>
                        </div>
                    </div>

                    <hr class="border-slate-100 dark:border-white/10 mb-6">

                    <div class="flex items-center gap-2 mb-4">
                        <a href="{{ route('admin.videos.show', @$video->id) }}" class="flex-1 h-14 rounded-2xl text-white flex flex-col items-center justify-center hover:opacity-90 transition-all shadow-lg" style="background: linear-gradient(135deg, #6366f1, #4338ca) !important;">
                            <span class="material-symbols-rounded text-base mb-0.5">visibility</span>
                            <span class="text-[9px] font-black uppercase tracking-widest ">View</span>
                        </a>
                        <a href="{{ route('admin.videos.edit', @$video->id) }}" class="flex-1 h-14 rounded-2xl text-white flex flex-col items-center justify-center hover:opacity-90 transition-all shadow-lg" style="background: linear-gradient(135deg, #2563eb, #1e40af) !important;">
                            <span class="material-symbols-rounded text-base mb-0.5">edit</span>
                            <span class="text-[9px] font-black uppercase tracking-widest ">Edit</span>
                        </a>
                        <form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST" class="flex-1 swal-action-form"
                              data-swal-title="Delete Video?" data-swal-text="This action is permanent and cannot be undone." data-swal-icon="error">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full h-14 rounded-2xl text-white flex flex-col items-center justify-center hover:opacity-90 transition-all shadow-lg" style="background: linear-gradient(135deg, #e11d48, #9f1239) !important;">
                                <span class="material-symbols-rounded text-base mb-0.5">delete</span>
                                <span class="text-[9px] font-black uppercase tracking-widest ">Delete</span>
                            </button>
                        </form>
                        <button type="button" class="flex-1 h-14 rounded-2xl text-white flex flex-col items-center justify-center hover:opacity-90 transition-all shadow-lg shadow-black/25 strikeBtn" style="background: linear-gradient(135deg, #1f2937, #111827) !important;" data-id="{{ $video->id }}" data-title="{{ $video->title }}">
                            <span class="material-symbols-rounded text-base mb-0.5">gavel</span>
                            <span class="text-[9px] font-black uppercase tracking-widest ">Strike</span>
                        </button>
                        @if($video->status == \App\Constants\Status::VIDEO_STRUCK)
                            @php $strikeId = $video->getActiveStrikeId(); @endphp
                            @if($strikeId)
                                <a href="{{ route('admin.moderation.strikes.remove', $strikeId) }}" class="flex-1 h-14 rounded-2xl text-white flex flex-col items-center justify-center hover:opacity-90 transition-all shadow-lg" style="background: linear-gradient(135deg, #10b981, #059669) !important;">
                                    <span class="material-symbols-rounded text-base mb-0.5">undo</span>
                                    <span class="text-[9px] font-black uppercase tracking-widest ">Restore</span>
                                </a>
                            @endif
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.videos.likes', @$video->id) }}" class="flex-1 h-12 rounded-2xl text-white flex flex-col items-center justify-center hover:opacity-90 transition-all shadow-lg shadow-rose-500/20" style="background-color: #ff3366 !important;" title="Likes">
                            <span class="material-symbols-rounded text-lg mb-0.5">favorite</span>
                            <span class="text-[7px] font-black uppercase tracking-[0.1em]">Likes</span>
                        </a>
                        <a href="{{ route('admin.videos.manage.comments', @$video->id) }}" class="flex-1 h-12 rounded-2xl text-white flex flex-col items-center justify-center hover:opacity-90 transition-all shadow-lg shadow-emerald-500/20" style="background-color: #10b981 !important;" title="Comments">
                            <span class="material-symbols-rounded text-lg mb-0.5">chat_bubble</span>
                            <span class="text-[7px] font-black uppercase tracking-[0.1em]">Comms</span>
                        </a>
                        <a href="{{ route('admin.videos.manage.playlists', @$video->id) }}" class="flex-1 h-12 rounded-2xl text-white flex flex-col items-center justify-center hover:opacity-90 transition-all shadow-lg shadow-amber-500/20" style="background-color: #f59e0b !important;" title="Playlists">
                            <span class="material-symbols-rounded text-lg mb-0.5">playlist_play</span>
                            <span class="text-[7px] font-black uppercase tracking-[0.1em]">Lists</span>
                        </a>
                        <a href="{{ route('admin.videos.manage.watch-later', @$video->id) }}" class="flex-1 h-12 rounded-2xl text-white flex flex-col items-center justify-center hover:opacity-90 transition-all shadow-lg shadow-slate-900/20" style="background-color: #0f172a !important;" title="Watch Later">
                            <span class="material-symbols-rounded text-lg mb-0.5">schedule</span>
                            <span class="text-[7px] font-black uppercase tracking-[0.1em]">Later</span>
                        </a>
                        <a href="@if (!$video->isPublished()) javascript:void(0) @else{{ route('admin.videos.analytics', @$video->id) }} @endif" class="flex-1 h-12 rounded-2xl text-white flex flex-col items-center justify-center hover:opacity-90 transition-all shadow-lg shadow-indigo-500/20" style="background-color: #4f46e5 !important;" title="Stats">
                            <span class="material-symbols-rounded text-lg mb-0.5">analytics</span>
                            <span class="text-[7px] font-black uppercase tracking-[0.1em]">Stats</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center text-slate-400 font-black uppercase tracking-widest ">
                    No Videos Found
                </div>
            @endforelse
        </div>
    </div>

    @if ($videos->hasPages())
        <div class="card-footer py-4 px-6 border-t border-slate-100 dark:border-white/5">
            {{ paginateLinks($videos) }}
        </div>
    @endif
</div>

@push('script')
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script>
    window.hls = null;

    window.playMedia = function(url, title) {
        const modal = document.getElementById('mediaPlayerModal');
        const video = document.getElementById('mediaVideoPlayer');
        const titleEl = document.getElementById('mediaModalTitle');
        
        if (!modal || !video || !titleEl) {
            console.error('Media Player elements not found');
            return;
        }

        titleEl.textContent = title;
        modal.classList.remove('hidden');

        if (url.includes('.m3u8')) {
            if (typeof Hls !== 'undefined' && Hls.isSupported()) {
                if (window.hls) {
                    window.hls.destroy();
                }
                window.hls = new Hls();
                window.hls.loadSource(url);
                window.hls.attachMedia(video);
                window.hls.on(Hls.Events.MANIFEST_PARSED, function() {
                    video.play();
                });
            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = url;
                video.play();
            } else {
                window.adminSwal({title:'Playback Error',text:'HLS playback not supported.',icon:'error'});
            }
        } else {
            if (window.hls) {
                window.hls.destroy();
                window.hls = null;
            }
            video.src = url;
            video.play().catch(err => console.error('Play error:', err));
        }
    };

    window.closeMediaModal = function() {
        const modal = document.getElementById('mediaPlayerModal');
        const video = document.getElementById('mediaVideoPlayer');
        if (video) video.pause();
        if (window.hls) {
            window.hls.destroy();
            window.hls = null;
        }
        if (video) video.src = '';
        if (modal) modal.classList.add('hidden');
    };

    document.addEventListener('click', function(e) {
        if (e.target.id === 'mediaPlayerModal') window.closeMediaModal();
    });

    // Quick Strike Logic
    window.initQuickStrike = function() {
        document.querySelectorAll('.strikeBtn').forEach(btn => {
            btn.onclick = function() {
                const id = this.getAttribute('data-id');
                const title = this.getAttribute('data-title');
                const modal = document.getElementById('quickStrikeModal');
                document.getElementById('strike_video_id').value = id;
                document.getElementById('strike_video_title').textContent = title;
                modal.classList.remove('hidden');
            };
        });
    };

    // Initial load
    initQuickStrike();

    window.closeStrikeModal = function() {
        document.getElementById('quickStrikeModal').classList.add('hidden');
    };
</script>
@endpush

<!-- Quick Strike Modal -->
<div id="quickStrikeModal" class="fixed inset-0 z-[110] hidden flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
    <div class="relative w-full max-w-md bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] overflow-hidden shadow-2xl border border-white/10 p-8">
        <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight mb-2">Issue Quick Strike</h3>
        <p class="text-[10px] font-bold text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] mb-8">Video: <span id="strike_video_title" class="text-rose-500"></span></p>

        <form action="{{ route('admin.security.strikes.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="video_id" id="strike_video_id">
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest ml-1">Reason for Strike</label>
                <textarea name="reason" rows="4" class="w-full bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl p-4 text-sm font-bold text-slate-900 dark:text-white outline-none focus:ring-4 focus:ring-rose-500/10 transition-all resize-none" placeholder="Explain the violation..." required></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeStrikeModal()" class="flex-1 py-4 rounded-2xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white text-[10px] font-black uppercase tracking-widest">Cancel</button>
                <button type="submit" class="flex-[2] py-4 rounded-2xl bg-gradient-to-r from-rose-600 to-red-600 text-white text-[10px] font-black uppercase tracking-widest shadow-xl shadow-rose-500/20">Execute Strike</button>
            </div>
        </form>
    </div>
</div>

<!-- Media Player Modal -->
<div id="mediaPlayerModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
    <div class="relative w-full max-w-4xl aspect-video bg-black rounded-3xl overflow-hidden shadow-2xl">
        <button onclick="closeMediaModal()" class="absolute top-4 right-4 z-50 w-10 h-10 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center hover:bg-white/30 transition-all">
            <span class="material-symbols-rounded">close</span>
        </button>
        <video id="mediaVideoPlayer" class="w-full h-full object-contain" controls autoplay playsinline></video>
        <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/80 to-transparent">
            <h3 id="mediaModalTitle" class="text-white font-black text-xl truncate uppercase tracking-tighter"></h3>
        </div>
    </div>
</div>

<x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Username/Title/Channel Name" />
@endpush

