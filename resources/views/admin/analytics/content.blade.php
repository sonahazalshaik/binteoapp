@extends('admin.layouts.app')
@section('title', 'Content Performance')
@section('header_title', 'Performance Analytics Database')

@section('panel')
<div class="p-4 mb-4 grid grid-cols-1 sm:grid-cols-4 gap-4">
    <div class="p-4 rounded-xl border border-slate-100 dark:border-white/10 bg-white dark:bg-slate-900">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Reel Scroll Stop Rate</p>
        <p class="text-lg font-black text-slate-800 dark:text-white mt-1">{{ $scrollStopRate ?? 0 }}%</p>
    </div>
</div>
<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
    @include('admin.components.header-toolbar', [
        'title' => $pageTitle ?? 'Content Performance',
        'items' => $topContent,
        'createRoute' => null,
        'createLabel' => null
    ])
    
    <div class="overflow-x-auto scrollbar-hide mt-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">@lang('Video')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">@lang('Creator')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate text-right">@lang('Views')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">@lang('Completion')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">@lang('Rewatches')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right truncate">@lang('Engagement')</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($topContent as $content)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-8 rounded bg-slate-200 dark:bg-white/5 border border-slate-200 dark:border-white/10 overflow-hidden flex-shrink-0">
                                    @if($content->video->thumb_image)
                                        <img src="{{ getImage(getFilePath('thumbnail').'/'.$content->video->thumb_image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <span class="material-symbols-rounded text-[14px] text-slate-400">image</span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-[12px] font-black text-slate-900 dark:text-white uppercase leading-none mb-1 line-clamp-1" title="{{ $content->video->title }}">{{ Str::limit($content->video->title, 30) }}</div>
                                    <div class="text-[10px] font-bold text-slate-500 dark:text-white/50 tracking-tight">{{ $content->video->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[11px] font-black text-slate-900 dark:text-white"><span>@</span>{{ @$content->video->user->username }}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-[13px] font-black text-emerald-600 dark:text-emerald-400">{{ number_format($content->total_views) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-black text-slate-700 dark:text-white/80 w-10">{{ number_format($content->avg_completion, 1) }}%</span>
                                <div class="w-16 bg-slate-100 dark:bg-white/5 h-1.5 rounded-full overflow-hidden border border-slate-200 dark:border-white/10">
                                    <div class="bg-gradient-to-r from-emerald-400 to-emerald-500 h-full" style="width: {{ $content->avg_completion }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-black text-slate-700 dark:text-white/80">{{ number_format($content->total_rewatches) }}</span>
                                <span class="text-[9px] font-black text-indigo-600 dark:text-indigo-400">({{ number_format($content->rewatch_rate, 1) }}%)</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @php
                                $engagementScore = ($content->avg_completion * 0.6) + (min($content->rewatch_rate, 100) * 0.4);
                            @endphp
                            @if($engagementScore > 70)
                                <span class="px-2 py-1 rounded border border-emerald-200 dark:border-emerald-500/20 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[9px] font-black uppercase tracking-widest">High</span>
                            @elseif($engagementScore > 40)
                                <span class="px-2 py-1 rounded border border-orange-200 dark:border-orange-500/20 bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 text-[9px] font-black uppercase tracking-widest">Moderate</span>
                            @else
                                <span class="px-2 py-1 rounded border border-rose-200 dark:border-rose-500/20 bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 text-[9px] font-black uppercase tracking-widest">Low</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-10 py-32 text-center text-slate-400 font-black uppercase tracking-widest text-[9px] opacity-20">{{ __($emptyMessage ?? 'No performance data available') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

