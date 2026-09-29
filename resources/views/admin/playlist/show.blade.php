@extends('admin.layouts.app')

@section('title', 'Playlist Details')
@section('header_title', 'Playlist Details')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-700 pb-24">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-6 lg:px-0">
        <div>
            <h3 class="text-2xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Playlist Overview</h3>
            <p class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3">Viewing details for: {{ $playlist->title ?? $playlist->name }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.playlist.edit', $playlist->id) }}" class="h-12 px-6 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-black flex items-center gap-3 text-[10px] font-black uppercase tracking-widest hover:scale-[1.02] transition-all shadow-xl active:scale-95 group">
                <span class="material-symbols-rounded text-lg transition-transform group-hover:rotate-12">edit_note</span>
                <span>Edit Playlist</span>
            </a>
            <a href="{{ route('admin.playlist.index') }}" class="h-12 px-6 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/60 flex items-center gap-3 text-[10px] font-black uppercase tracking-widest hover:bg-slate-900 dark:hover:bg-white hover:text-white dark:hover:text-black transition-all group">
                <span class="material-symbols-rounded text-lg transition-transform group-hover:-translate-x-1">arrow_back</span>
                <span>Back to Playlists</span>
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white/60 dark:bg-white/5 backdrop-blur-3xl border border-white dark:border-white/10 rounded-[2rem] p-6 shadow-xl">
            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 ">Total Videos</span>
            <div class="flex items-end justify-between">
                <span class="text-4xl font-black text-slate-900 dark:text-white leading-none">{{ $playlist->videos->count() }}</span>
                <span class="material-symbols-rounded text-3xl text-orange-500 ">movie</span>
            </div>
        </div>
        <div class="bg-white/60 dark:bg-white/5 backdrop-blur-3xl border border-white dark:border-white/10 rounded-[2rem] p-6 shadow-xl">
            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 ">Total Reels</span>
            <div class="flex items-end justify-between">
                <span class="text-4xl font-black text-slate-900 dark:text-white leading-none">{{ $playlist->reels->count() }}</span>
                <span class="material-symbols-rounded text-3xl text-rose-500 ">play_circle</span>
            </div>
        </div>
        <div class="bg-white/60 dark:bg-white/5 backdrop-blur-3xl border border-white dark:border-white/10 rounded-[2rem] p-6 shadow-xl">
            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 ">Visibility</span>
            <div class="flex items-end justify-between">
                <span class="text-xl font-black text-slate-900 dark:text-white uppercase leading-none">
                    {{ $playlist->visibility == 0 ? 'Public' : 'Private' }}
                </span>
                <span class="material-symbols-rounded text-3xl text-emerald-500 ">{{ $playlist->visibility == 0 ? 'globe' : 'lock' }}</span>
            </div>
        </div>
        <div class="bg-white/60 dark:bg-white/5 backdrop-blur-3xl border border-white dark:border-white/10 rounded-[2rem] p-6 shadow-xl">
            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 ">Price</span>
            <div class="flex items-end justify-between">
                <span class="text-2xl font-black text-slate-900 dark:text-white leading-none">
                    {{ $playlist->playlist_subscription ? showAmount($playlist->price) : 'FREE' }}
                </span>
                <span class="material-symbols-rounded text-3xl text-blue-500 ">payments</span>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Details & Creator -->
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white/60 dark:bg-white/5 backdrop-blur-3xl border border-white dark:border-white/10 rounded-[2.5rem] p-8 shadow-2xl space-y-8">
                <div>
                    <h4 class="text-[13px] font-black text-slate-900 dark:text-white uppercase tracking-tight mb-4">Description</h4>
                    <div class="p-6 rounded-3xl bg-slate-50 dark:bg-black/20 border border-slate-100 dark:border-white/5 text-[12px] leading-relaxed text-slate-500 dark:text-white/60 font-medium">
                        {{ $playlist->description ?? 'No description provided for this playlist.' }}
                    </div>
                </div>

                <div>
                    <h4 class="text-[13px] font-black text-slate-900 dark:text-white uppercase tracking-tight mb-4">Playlist Creator</h4>
                    <div class="flex items-center gap-4 p-6 rounded-3xl bg-slate-50 dark:bg-black/20 border border-slate-100 dark:border-white/5">
                        <div class="w-14 h-14 rounded-2xl bg-orange-500/10 flex items-center justify-center border border-orange-500/20 overflow-hidden">
                            <img src="{{ getImage(getFilePath('userProfile') . '/' . @$playlist->user->image) }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('assets/images/avatar.png') }}'">
                        </div>
                        <div>
                            <span class="block text-[14px] font-black text-slate-900 dark:text-white uppercase leading-tight">{{ $playlist->user?->fullname }}</span>
                            <a href="{{ route('admin.users.detail', $playlist->user_id) }}" class="text-[10px] font-bold text-orange-500 hover:underline uppercase tracking-widest">
                                @<span>{{ $playlist->user?->username }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Lists -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Videos -->
            <div class="bg-white/60 dark:bg-white/5 backdrop-blur-3xl border border-white dark:border-white/10 rounded-[2.5rem] shadow-2xl overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02] flex items-center justify-between">
                    <h4 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-[0.2em] ">Videos in Playlist</h4>
                    <span class="px-3 py-1 rounded-full bg-slate-900 dark:bg-white text-white dark:text-black text-[9px] font-black uppercase tracking-widest ">
                        {{ $playlist->videos->count() }} Videos
                    </span>
                </div>
                <div class="max-h-[400px] overflow-y-auto scrollbar-hide divide-y divide-slate-100 dark:divide-white/5 p-6 space-y-4">
                    @forelse($playlist->videos as $video)
                        <div class="flex items-center justify-between p-4 rounded-2xl hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="w-20 h-12 rounded-lg bg-slate-100 dark:bg-white/5 overflow-hidden">
                                    <img src="{{ $video->getThumbnailUrl() }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                                </div>
                                <div>
                                    <span class="block text-[12px] font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $video->title }}</span>
                                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $video->views_count }} Views • {{ $video->duration }}</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.videos.show', $video->id) }}" class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 hover:text-orange-500 transition-all group-hover:bg-white dark:group-hover:bg-white/10 group-hover:shadow-sm">
                                <span class="material-symbols-rounded text-lg ">arrow_outward</span>
                            </a>
                        </div>
                    @empty
                        <div class="py-12 text-center opacity-20 ">
                            <p class="text-[10px] font-black uppercase tracking-[0.3em]">No videos added to this playlist</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Reels -->
            <div class="bg-white/60 dark:bg-white/5 backdrop-blur-3xl border border-white dark:border-white/10 rounded-[2.5rem] shadow-2xl overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02] flex items-center justify-between">
                    <h4 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-[0.2em] ">Reels in Playlist</h4>
                    <span class="px-3 py-1 rounded-full bg-rose-500 text-white text-[9px] font-black uppercase tracking-widest ">
                        {{ $playlist->reels->count() }} Reels
                    </span>
                </div>
                <div class="max-h-[400px] overflow-y-auto scrollbar-hide divide-y divide-slate-100 dark:divide-white/5 p-6 space-y-4">
                    @forelse($playlist->reels as $reel)
                        <div class="flex items-center justify-between p-4 rounded-2xl hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-20 rounded-lg bg-slate-100 dark:bg-white/5 overflow-hidden">
                                    <img src="{{ $reel->getThumbnailUrl() }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                                </div>
                                <div>
                                    <span class="block text-[12px] font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $reel->title }}</span>
                                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $reel->views_count }} Views</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.reels.edit', $reel->id) }}" class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-all group-hover:bg-white dark:group-hover:bg-white/10 group-hover:shadow-sm">
                                <span class="material-symbols-rounded text-lg ">arrow_outward</span>
                            </a>
                        </div>
                    @empty
                        <div class="py-12 text-center opacity-20 ">
                            <p class="text-[10px] font-black uppercase tracking-[0.3em]">No reels added to this playlist</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

