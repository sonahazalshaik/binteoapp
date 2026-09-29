@extends('admin.layouts.app')

@section('title', 'Channel Overview')
@section('header_title', 'Channel Details')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-700">
    <!-- Channel Header Identity -->
    <div class="relative rounded-[3.5rem] overflow-hidden bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 shadow-2xl h-[450px]">
        <!-- Banner -->
        <div class="absolute inset-0 z-0 overflow-hidden">
            @if($channel->banner)
                <img src="{{ getImage(getFilePath('channelBanner') . '/' . $channel->banner) }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-[#f8fafc] dark:bg-[#0a0a0a] flex items-center justify-center p-12">
                    <div class="w-full h-full rounded-[3.5rem] border-4 border-dashed border-slate-200 dark:border-white/10 flex flex-col items-center justify-center relative overflow-hidden group">
                        <div class="absolute inset-0 opacity-10 bg-[url('https://grainy-gradients.vercel.app/noise.svg')]"></div>
                        <div class="relative z-10 flex flex-col items-center gap-6 mb-20">
                            <div class="w-24 h-24 rounded-[2.5rem] bg-white dark:bg-white/5 shadow-2xl border border-white/20 dark:border-white/10 flex items-center justify-center">
                                <span class="material-symbols-rounded text-slate-300 dark:text-white/10 text-5xl">landscape</span>
                            </div>
                            <div class="text-center">
                                <p class="text-[11px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] ">No cover photo available</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-white dark:from-[#121212] via-white/40 dark:via-[#121212]/40 to-transparent"></div>
        </div>

        <!-- Identity Bar -->
        <div class="absolute bottom-0 inset-x-0 p-12 flex flex-col sm:flex-row items-end justify-between gap-8 z-10">
            <div class="flex items-center gap-8">
                <div class="w-28 h-28 rounded-3xl overflow-hidden border-4 border-white dark:border-white/10 shadow-2xl relative group">
                    @if($channel->avatar)
                        <img src="{{ getImage(getFilePath('channelAvatar') . '/' . $channel->avatar) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400">
                            <span class="material-symbols-rounded text-4xl">tv</span>
                        </div>
                    @endif
                </div>
                <div class="mb-4">
                    <h3 class="text-4xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">{{ $channel->name }}</h3>
                    <div class="flex items-center gap-4 mt-4">
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2">
                             @<span>{{ $channel->user->username }}</span>
                        </span>
                        <div class="w-1 h-1 rounded-full bg-slate-300"></div>
                        <span class="text-[10px] font-black text-orange-500 uppercase tracking-[0.2em] ">{{ number_format($channel->subscribers_count) }} Subscribers</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('admin.channels.edit', $channel) }}" class="h-14 px-10 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white font-black text-[11px] uppercase tracking-widest hover:bg-slate-50 transition-all flex items-center gap-3">
                    <span class="material-symbols-rounded text-lg">edit_note</span>
                    Edit Channel
                </a>
                <form action="{{ route('admin.channels.status', $channel) }}" method="POST">
                    @csrf
                    <button type="submit" class="h-14 px-10 rounded-2xl {{ $channel->is_active ? 'bg-rose-500' : 'bg-emerald-500' }} text-white font-black text-[11px] uppercase tracking-widest hover:scale-105 transition-all shadow-xl active:scale-95 flex items-center gap-3 ">
                        <span class="material-symbols-rounded text-lg">{{ $channel->is_active ? 'power_settings_new' : 'bolt' }}</span>
                        {{ $channel->is_active ? 'Disable Channel' : 'Enable Channel' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Analytics & Narrative -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white dark:bg-[#121212] rounded-[2.5rem] p-10 border border-slate-200 dark:border-white/10 shadow-sm relative overflow-hidden">
                <div class="flex items-center gap-4 mb-8">
                    <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest ">Channel Description</span>
                    <div class="h-[1px] flex-grow bg-slate-100 dark:bg-white/5"></div>
                </div>
                <p class="text-[13px] font-bold text-slate-500 dark:text-white/40 leading-relaxed ">
                    {{ $channel->description ?? 'No Channel description initialized for this creator yet...' }}
                </p>
            </div>

            <!-- Latest Videos -->
            <div class="bg-white dark:bg-[#121212] rounded-[2.5rem] p-10 border border-slate-200 dark:border-white/10 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest text-orange-500">Latest Videos</span>
                        <div class="h-[1px] w-20 bg-orange-500/10"></div>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    @forelse($channel->videos as $video)
                    <div class="group space-y-4">
                        <a href="{{ route('videos.show', $video) }}" target="_blank" class="block relative aspect-video rounded-3xl overflow-hidden border border-slate-100 dark:border-white/5 shadow-sm bg-slate-50 dark:bg-white/[0.02] cursor-pointer">
                            <img src="{{ $video->getThumbnailUrl() }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 scale-75 group-hover:scale-100 transition-transform duration-500">
                                    <span class="material-symbols-rounded text-white text-4xl fill-1">play_arrow</span>
                                </div>
                            </div>
                            <div class="absolute bottom-3 right-3 px-2 py-1 bg-black/80 backdrop-blur-md rounded-lg text-[9px] font-bold text-white">
                                {{ $video->duration ?? '00:00' }}
                            </div>
                        </a>
                        <div>
                            <a href="{{ route('videos.show', $video) }}" target="_blank" class="text-[11px] font-black text-slate-800 dark:text-white truncate uppercase tracking-tighter hover:text-orange-500 transition-colors block">{{ $video->title }}</a>
                            <p class="text-[9px] text-slate-400 font-bold mt-1 uppercase">{{ number_format($video->views_count) }} Views • {{ $video->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-20 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-3xl">
                        <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-5xl">smart_display</span>
                        <p class="mt-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">No videos broadcast yet</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Latest Reels -->
            <div class="bg-white dark:bg-[#121212] rounded-[2.5rem] p-10 border border-slate-200 dark:border-white/10 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest text-rose-500">Latest Reels</span>
                        <div class="h-[1px] w-20 bg-rose-500/10"></div>
                    </div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-6">
                    @forelse($channel->reels as $reel)
                    <div class="group space-y-3">
                        <a href="{{ route('reels.show', $reel) }}" target="_blank" class="block relative aspect-[9/16] rounded-2xl overflow-hidden border border-slate-100 dark:border-white/5 shadow-sm bg-slate-50 dark:bg-white/[0.02] cursor-pointer">
                            <img src="{{ $reel->getThumbnailUrl() }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-60"></div>
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <div class="w-12 h-12 rounded-full bg-rose-500/80 backdrop-blur-sm flex items-center justify-center text-white scale-75 group-hover:scale-100 transition-transform duration-500 shadow-xl">
                                    <span class="material-symbols-rounded text-2xl fill-1">play_arrow</span>
                                </div>
                            </div>
                            <div class="absolute bottom-3 left-3 flex items-center gap-1.5 text-white">
                                <span class="material-symbols-rounded text-sm">play_arrow</span>
                                <span class="text-[9px] font-black tracking-widest uppercase">{{ number_format($reel->views_count) }}</span>
                            </div>
                        </a>
                        <a href="{{ route('reels.show', $reel) }}" target="_blank" class="text-[9px] font-black text-slate-800 dark:text-white truncate uppercase tracking-tighter hover:text-rose-500 transition-colors block">{{ $reel->title ?? 'Untitled Reel' }}</a>
                    </div>
                    @empty
                    <div class="col-span-full py-20 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-3xl">
                        <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-5xl">movie_edit</span>
                        <p class="mt-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">No reels published yet</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Components -->
        <div class="space-y-8">
            <!-- Connectivity -->
            <div class="bg-white dark:bg-[#121212] rounded-[2.5rem] p-8 border border-slate-200 dark:border-white/10 shadow-sm">
                <div class="flex items-center gap-4 mb-6">
                    <span class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-widest ">Social Links</span>
                    <div class="h-[1px] flex-grow bg-slate-100 dark:bg-white/5"></div>
                </div>
                <div class="space-y-4">
                    @php $socials = $channel->social_links ?? []; @endphp
                    @foreach(['facebook', 'twitter', 'instagram', 'youtube'] as $key)
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-white/[0.02] border border-transparent hover:border-slate-200 dark:hover:border-white/10 transition-all cursor-default group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white dark:bg-white/5 flex items-center justify-center text-slate-400 group-hover:text-orange-500 transition-colors shadow-sm">
                                <span class="material-symbols-rounded text-lg">share</span>
                            </div>
                            <span class="text-[9px] font-black uppercase text-slate-600 dark:text-white/40 tracking-widest">{{ $key }}</span>
                        </div>
                        @if(isset($socials[$key]))
                            <span class="material-symbols-rounded text-emerald-500 text-base">verified</span>
                        @else
                            <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-base">not_interested</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Channel Owner -->
            <div class="bg-slate-900 rounded-[2.5rem] p-8 shadow-2xl relative overflow-hidden group">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-orange-500 opacity-10 blur-[80px] group-hover:opacity-20 transition-opacity"></div>
                <div class="flex items-center gap-4 mb-6 relative z-10">
                    <span class="text-[10px] font-black text-white/40 uppercase tracking-widest ">Channel Owner</span>
                </div>
                <div class="flex items-center gap-4 relative z-10">
                    <div class="w-16 h-16 rounded-2xl bg-white/10 p-0.5">
                        <div class="w-full h-full rounded-[0.9rem] overflow-hidden">
                            @if($channel->user->image)
                                <img src="{{ getImage(getFilePath('userProfile') . '/' . $channel->user->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-orange-500 flex items-center justify-center text-white font-black">
                                    {{ substr($channel->user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="font-black text-white tracking-tighter text-[15px] ">{{ $channel->user->fullname }}</div>
                        <div class="text-[9px] text-white/30 font-black uppercase tracking-widest mt-1 ">UID: #{{ $channel->user->id }}</div>
                    </div>
                </div>
                <div class="mt-8 relative z-10">
                    <a href="{{ route('admin.users.detail', $channel->user->id) }}" class="w-full h-12 rounded-xl bg-white/10 hover:bg-white/20 text-white text-[9px] font-black uppercase tracking-[0.2em] flex items-center justify-center transition-all border border-white/5">
                        View User Details
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

