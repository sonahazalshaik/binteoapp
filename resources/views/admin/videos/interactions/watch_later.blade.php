@extends('admin.layouts.app')

@section('title', $pageTitle)
@section('header_title', 'Engagement Audit')

@section('content')
<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-sm">
    <div class="p-8 border-b border-slate-100 dark:border-white/10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 rounded-2xl overflow-hidden border border-slate-200 dark:border-white/10 shadow-lg">
                <img src="{{ $video->getThumbnailUrl() }}" class="w-full h-full object-cover">




            </div>
            <div>
                <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter leading-none mb-2">Watch Later Presence: {{ $video->title }}</h3>
                <p class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] leading-relaxed">Auditing saved content markers and delayed viewing signals</p>
            </div>
        </div>
        <a href="{{ route('admin.videos.index') }}" class="h-11 px-8 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/40 flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-white/10 transition-all border border-slate-200 dark:border-white/10">
            <span class="material-symbols-rounded text-lg">arrow_back</span> Back to Library
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-8 py-5 text-[9px] font-black uppercase tracking-widest text-slate-400">User Node</th>
                    <th class="px-8 py-5 text-[9px] font-black uppercase tracking-widest text-slate-400">Saved On</th>
                    <th class="px-8 py-5 text-[9px] font-black uppercase tracking-widest text-slate-400 text-right">Operations</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($playlists as $playlist)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center font-black text-[12px] text-slate-400 uppercase ">
                                {{ substr($playlist->user->username, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-black text-slate-900 dark:text-white text-[13px] leading-none mb-1 uppercase">{{ $playlist->user->fullname }}</div>
                                <div class="text-[9px] text-slate-400 font-bold lowercase tracking-tight">@<span>{{ $playlist->user->username }}</span></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-[11px] font-bold text-slate-500 dark:text-white/30 uppercase">
                        {{ $playlist->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-8 py-5 text-right">
                        <button class="h-10 px-6 rounded-xl bg-rose-600 text-white text-[9px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-sm confirmationBtn" 
                                data-action="{{ route('admin.videos.playlist.remove', $video->id) }}" 
                                data-params='{"playlist_id": "{{ $playlist->id }}"}'
                                data-question="@lang('Remove this video from the user\'s Watch Later intelligence feed?')">
                            <span class="material-symbols-rounded text-base mr-2">event_busy</span> Remove Marker
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-10 py-32 text-center">
                        <div class="flex flex-col items-center opacity-20">
                            <span class="material-symbols-rounded text-6xl text-slate-400">notification_important</span>
                            <p class="mt-6 text-[10px] font-black uppercase tracking-[0.5em] text-slate-400 ">No saved content markers detected for this node</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-8 border-t border-slate-100 dark:border-white/10 bg-slate-50/50 dark:bg-white/[0.01]">
        {{ $playlists->links() }}
    </div>
</div>
@endsection

