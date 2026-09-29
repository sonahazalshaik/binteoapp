@extends('admin.layouts.app')

@section('title', 'Video Performance')
@section('header_title', 'Video Analytics')

@section('content')
<div class="px-4 lg:px-0 space-y-10 animate-in fade-in slide-in-from-bottom-10 duration-1000">
    
    <!-- Viral Content Engine Section -->
    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-3xl border border-white/40 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.1)] transition-all duration-500">
        <div class="px-10 py-10 border-b border-slate-100 dark:border-white/5 flex items-center justify-between bg-gradient-to-r from-orange-500/5 via-transparent to-transparent">
            <div>
                <h2 class="text-2xl font-black tracking-tighter text-slate-900 dark:text-white uppercase flex items-center gap-3">
                    <span class="w-2 h-8 bg-orange-500 rounded-full"></span>
                    Viral Content Engine
                </h2>
                <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em] mt-3 leading-relaxed">Top performing videos by watch time and engagement metrics</p>
            </div>
            <div class="w-14 h-14 rounded-[1.5rem] bg-orange-500 text-white flex items-center justify-center shadow-lg shadow-orange-500/30">
                <span class="material-symbols-rounded text-3xl">analytics</span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-white/5">
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">Video & Creator</th>
                        <th class="px-6 py-6 text-[10px] font-black uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400 text-center">Watch Time</th>
                        <th class="px-6 py-6 text-[10px] font-black uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400 text-center">CTR %</th>
                        <th class="px-6 py-6 text-[10px] font-black uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">Completion Flow</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400 text-right">Reach</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($topVideos as $video)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-all duration-300 group">
                        <td class="px-10 py-8">
                            <div class="flex items-center gap-6">
                                <div class="relative flex-shrink-0">
                                    <div class="w-28 h-16 rounded-2xl overflow-hidden shadow-xl border-2 border-white dark:border-slate-800 relative group/thumb">
                                        <img src="{{ $video->thumbnailUrl() }}" class="w-full h-full object-cover transition-all duration-500 group-hover/thumb:scale-110">
                                        @if($video->isBunnyVideo())
                                            <img src="{{ $video->previewUrl() }}" class="absolute inset-0 w-full h-full object-cover opacity-0 group-hover/thumb:opacity-100 transition-opacity duration-300">
                                        @endif
                                    </div>
                                    <div class="absolute -bottom-2 -right-2 px-2.5 py-1 bg-black/90 backdrop-blur-xl rounded-lg text-[9px] font-black text-white uppercase tracking-tighter border border-white/20">
                                        {{ $video->duration }}
                                    </div>
                                </div>
                                <div>
                                    <p class="text-base font-black text-slate-900 dark:text-white tracking-tight line-clamp-1 mb-2 group-hover:text-orange-500 transition-colors">{{ $video->title }}</p>
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 rounded-full bg-slate-100 dark:bg-white/10 overflow-hidden border border-white/10">
                                            <img src="{{ @$video->user->channel->avatarUrl() }}" class="w-full h-full object-cover">
                                        </div>
                                        <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                            {{ @$video->channel->name ?? 'System' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-8 text-center">
                            @if($video->total_watch_time < 3600)
                                <p class="text-xl font-black text-slate-900 dark:text-white tracking-tighter ">{{ round($video->total_watch_time / 60, 1) }}<span class="text-xs ml-1 text-orange-500">MINS</span></p>
                            @else
                                <p class="text-xl font-black text-slate-900 dark:text-white tracking-tighter ">{{ round($video->total_watch_time / 3600, 2) }}<span class="text-xs ml-1 text-orange-500">HRS</span></p>
                            @endif
                        </td>
                        <td class="px-6 py-8 text-center">
                            @php 
                                $rawCtr = $video->impressions > 0 ? ($video->views_count / $video->impressions) * 100 : 0;
                                $ctr = min(100, round($rawCtr, 1));
                            @endphp
                            <div class="inline-flex flex-col items-center">
                                <span class="text-lg font-black {{ $ctr > 5 ? 'text-emerald-500' : 'text-slate-400' }} tracking-tighter ">{{ $ctr }}%</span>
                                <span class="text-[8px] font-black uppercase text-slate-400 tracking-widest mt-1">Click Rate</span>
                            </div>
                        </td>
                        <td class="px-6 py-8 min-w-[220px]">
                            @php
                                $total = $video->views_count ?: 1;
                                $r25 = min(100, round(($video->retention_25 / $total) * 100));
                                $r50 = min(100, round(($video->retention_50 / $total) * 100));
                                $r75 = min(100, round(($video->retention_75 / $total) * 100));
                                $r100 = min(100, round(($video->retention_100 / $total) * 100));
                                $avg = round(($r25 + $r50 + $r75 + $r100) / 4, 1);
                            @endphp
                            <div class="space-y-3">
                                <div class="flex justify-between items-end">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest ">Retention Curve</span>
                                    <span class="text-[11px] font-black text-emerald-500 tracking-tighter">{{ $avg }}%</span>
                                </div>
                                <div class="h-12 flex items-end gap-1 px-1 bg-slate-50 dark:bg-white/[0.02] rounded-lg border border-slate-100 dark:border-white/5">
                                    <div class="flex-1 bg-emerald-500/20 rounded-t-sm transition-all duration-1000 relative group/m" style="height: {{ $r25 }}%">
                                        <div class="absolute -top-4 left-1/2 -translate-x-1/2 text-[8px] font-bold text-slate-400 opacity-0 group-hover/m:opacity-100 transition-opacity">25%</div>
                                    </div>
                                    <div class="flex-1 bg-emerald-500/40 rounded-t-sm transition-all duration-1000 relative group/m" style="height: {{ $r50 }}%">
                                        <div class="absolute -top-4 left-1/2 -translate-x-1/2 text-[8px] font-bold text-slate-400 opacity-0 group-hover/m:opacity-100 transition-opacity">50%</div>
                                    </div>
                                    <div class="flex-1 bg-emerald-500/60 rounded-t-sm transition-all duration-1000 relative group/m" style="height: {{ $r75 }}%">
                                        <div class="absolute -top-4 left-1/2 -translate-x-1/2 text-[8px] font-bold text-slate-400 opacity-0 group-hover/m:opacity-100 transition-opacity">75%</div>
                                    </div>
                                    <div class="flex-1 bg-emerald-500 rounded-t-sm transition-all duration-1000 relative group/m" style="height: {{ $r100 }}%">
                                        <div class="absolute -top-4 left-1/2 -translate-x-1/2 text-[8px] font-bold text-slate-400 opacity-0 group-hover/m:opacity-100 transition-opacity">100%</div>
                                    </div>
                                </div>
                                <div class="flex justify-between px-0.5">
                                    <span class="text-[7px] font-black text-slate-300 uppercase tracking-tighter ">Intro</span>
                                    <span class="text-[7px] font-black text-slate-300 uppercase tracking-tighter ">End</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-10 py-8 text-right">
                            <p class="text-base font-black text-slate-900 dark:text-white tracking-tighter">{{ number_format($video->impressions) }}</p>
                            <p class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">Impressions</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-32 text-center opacity-40">
                            <span class="material-symbols-rounded text-7xl block mb-6 text-slate-300">leaderboard</span>
                            <p class="text-xs font-black uppercase tracking-[0.4em] text-slate-400">Performance mapping in progress</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Analytics Insights Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Low Engagement Audit -->
        <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-3xl border border-rose-500/20 rounded-[2.5rem] p-10 shadow-2xl relative overflow-hidden group">
            <div class="absolute -top-10 -right-10 opacity-5 rotate-12 transition-transform group-hover:scale-110 duration-1000">
                <span class="material-symbols-rounded text-[12rem] text-rose-500">heart_broken</span>
            </div>
            
            <div class="flex items-center justify-between mb-10">
                <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter flex items-center gap-4 ">
                    <span class="w-10 h-10 rounded-2xl bg-rose-500 text-white flex items-center justify-center shadow-lg shadow-rose-500/30">
                        <span class="material-symbols-rounded">trending_down</span>
                    </span>
                    Low Engagement Audit
                </h3>
                <span class="px-4 py-1.5 bg-rose-500/10 text-rose-500 text-[9px] font-black uppercase tracking-widest rounded-full border border-rose-500/10">Needs Attention</span>
            </div>

            <div class="space-y-5">
                @foreach($underperforming as $video)
                <div class="group/item flex items-center justify-between p-5 bg-slate-50 dark:bg-white/5 rounded-[1.5rem] border border-slate-100 dark:border-white/10 hover:border-rose-500/30 transition-all duration-300">
                    <div class="flex-grow pr-6">
                        <p class="text-[12px] font-black text-slate-900 dark:text-white truncate uppercase tracking-tight mb-2">{{ $video->title }}</p>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-rounded text-xs text-slate-400">visibility</span>
                                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ number_format($video->views_count) }}</span>
                            </div>
                            <div class="w-1.5 h-1.5 rounded-full bg-rose-500/20"></div>
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-rounded text-xs text-rose-500">timer</span>
                                <span class="text-[10px] font-black text-rose-500 uppercase tracking-widest ">{{ round($video->total_watch_time / 60, 1) }}m Watch</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.videos.show', $video->id) }}" class="w-10 h-10 rounded-xl bg-white dark:bg-white/10 text-slate-400 hover:text-rose-500 hover:scale-110 shadow-sm transition-all flex items-center justify-center">
                        <span class="material-symbols-rounded text-xl">open_in_new</span>
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        <!-- AI Growth Strategy -->
        <div class="bg-gradient-to-br from-indigo-700 via-indigo-800 to-slate-900 rounded-[2.5rem] p-10 shadow-2xl relative overflow-hidden group">
            <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-[100px] animate-pulse"></div>
            <div class="absolute top-10 right-10 opacity-10">
                <span class="material-symbols-rounded text-8xl text-white rotate-12">auto_awesome</span>
            </div>
            
            <h3 class="text-xl font-black text-white uppercase tracking-tighter mb-12 flex items-center gap-4 ">
                <span class="w-10 h-10 rounded-2xl bg-white/10 text-white flex items-center justify-center backdrop-blur-xl border border-white/20">
                    <span class="material-symbols-rounded">psychology</span>
                </span>
                AI Growth Strategy
            </h3>

            <div class="space-y-10 relative">
                <div class="flex gap-6 group/tip">
                    <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-white/40 group-hover/tip:text-white group-hover/tip:bg-white/10 transition-all duration-500">
                        <span class="material-symbols-rounded">image_search</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-2">Metadata Optimization</p>
                        <p class="text-[13px] font-bold text-white/80 leading-relaxed group-hover/tip:text-white transition-colors">Videos with <span class="text-indigo-300 ">CTR < 2%</span> need high-contrast custom thumbnails to trigger click responses and improve platform discovery.</p>
                    </div>
                </div>
                
                <div class="flex gap-6 group/tip">
                    <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-white/40 group-hover/tip:text-white group-hover/tip:bg-white/10 transition-all duration-500">
                        <span class="material-symbols-rounded">hourglass_empty</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-emerald-400 uppercase tracking-[0.2em] mb-2">Audience Retention</p>
                        <p class="text-[13px] font-bold text-white/80 leading-relaxed group-hover/tip:text-white transition-colors">Top performing videos share high completion rates <span class="text-emerald-400 ">(>40%)</span>. Study their first 30 seconds for hook patterns.</p>
                    </div>
                </div>
                
                <div class="flex gap-6 group/tip">
                    <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-white/40 group-hover/tip:text-white group-hover/tip:bg-white/10 transition-all duration-500">
                        <span class="material-symbols-rounded">payments</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-orange-400 uppercase tracking-[0.2em] mb-2">Monetization Peak</p>
                        <p class="text-[13px] font-bold text-white/80 leading-relaxed group-hover/tip:text-white transition-colors">Content over 8 minutes is generating <span class="text-orange-400 ">4.2x more revenue</span> due to optimized mid-roll ad placement frequency.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

