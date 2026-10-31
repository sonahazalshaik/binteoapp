@extends('admin.layouts.app')
@section('title', 'Video PPV Commissions')
@section('header_title', 'Video Commissions')

@section('content')
<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] shadow-sm transition-all duration-300">
    <div class="px-8 py-6 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $pageTitle }}</h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Platform Revenue from PPV Videos</p>
        </div>
    </div>

    <div class="overflow-x-auto scrollbar-hide px-6">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Date')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Video')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Purchaser')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Creator')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Total Amount')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-emerald-500">@lang('Creator Earn')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-rose-500">@lang('Platform Fee')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right">@lang('Trx ID')</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($commissions as $commission)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors">
                    <td class="px-6 py-4">
                        <span class="font-bold text-sm">{{ showDateTime($commission->created_at, 'M d, Y') }}</span><br>
                        <span class="text-[9px] text-slate-400 uppercase tracking-widest">{{ diffForHumans($commission->created_at) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($commission->video)
                            <a href="{{ route('admin.videos.show', $commission->video) }}" class="font-bold hover:text-indigo-500">
                                {{ strLimit(@$commission->video->title, 30) }}
                            </a>
                        @else
                            <span class="text-slate-400">Deleted Video</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($commission->purchaser)
                            <a href="{{ route('admin.users.detail', $commission->purchaser_id) }}" class="font-bold hover:text-indigo-500">
                                {{ @$commission->purchaser->username ?? 'Guest' }}
                            </a>
                        @else
                            <span class="text-slate-400">Unknown</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($commission->creator)
                            <a href="{{ route('admin.users.detail', $commission->creator_id) }}" class="font-bold hover:text-indigo-500">
                                {{ @$commission->creator->username ?? 'System' }}
                            </a>
                        @else
                            <span class="text-slate-400">Unknown</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-bold">
                        {{ showAmount($commission->total_amount) }}
                    </td>
                    <td class="px-6 py-4 font-black text-emerald-500">
                        {{ showAmount($commission->creator_commission) }}
                    </td>
                    <td class="px-6 py-4 font-black text-rose-500">
                        {{ showAmount($commission->admin_commission) }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="text-[10px] font-black font-mono tracking-widest">{{ $commission->trx }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="text-muted text-center py-10" colspan="100%">No commissions found yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($commissions->hasPages())
        <div class="p-6 border-t border-slate-100 dark:border-white/5">
            {{ paginateLinks($commissions) }}
        </div>
    @endif
</div>
@endsection

