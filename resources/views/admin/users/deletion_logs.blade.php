@extends('admin.layouts.app')
@section('title', $pageTitle)
@section('header_title', $pageTitle)

@section('panel')
<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
    @include('admin.components.header-toolbar', [
        'title' => $pageTitle,
        'items' => $logs,
        'createRoute' => null
    ])

    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">User Info</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Contact Details</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Reason</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Audits</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Deleted At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                        <td class="px-6 py-4">
                            <div class="text-[12px] font-black text-slate-900 dark:text-white uppercase leading-none mb-1">{{ __($log->user_name) }}</div>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest bg-slate-100 dark:bg-white/5 px-2 py-0.5 rounded ">{{ $log->role }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[11px] font-black text-slate-900 dark:text-white mb-1">{{ $log->email }}</div>
                            <div class="text-[10px] font-bold text-slate-400 tracking-wider">{{ $log->phone }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-white/5 text-[10px] font-black text-slate-700 dark:text-white/80 uppercase">{{ $log->reason }}</span>
                            @if($log->custom_reason)
                                <p class="text-[10px] text-slate-500 mt-1 max-w-xs leading-relaxed ">"{{ $log->custom_reason }}"</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-[10px] font-bold text-slate-500 dark:text-white/40">
                            <div>Joined: {{ $log->joined_at ? $log->joined_at->format('M d, Y') : 'N/A' }}</div>
                            <div class="mt-1 text-emerald-500 font-black ">Final Bal: {{ showAmount($log->final_balance) }}</div>
                        </td>
                        <td class="px-6 py-4 text-[10px] font-bold text-slate-500 dark:text-white/40 ">
                            {{ $log->deleted_at->format('M d, Y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-10 py-32 text-center text-slate-400 font-black uppercase tracking-widest text-[9px] opacity-20">{{ __($emptyMessage) }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($logs->hasPages())
        <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
            {{ paginateLinks($logs) }}
        </div>
    @endif
</div>
@endsection

