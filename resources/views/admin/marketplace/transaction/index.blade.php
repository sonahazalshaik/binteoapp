@extends('admin.layouts.app')

@section('title', 'Marketplace Transaction Logs')
@section('header_title', 'Market Transactions')

@section('content')
<div class="bg-white dark:bg-[#121212] rounded-[2.5rem] overflow-hidden border border-slate-200 dark:border-white/10 shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-700">
    @include('admin.components.header-toolbar', [
        'title' => 'Market Transactions',
        'items' => $transactions,
    ])

    <div class="px-6 pt-6">
        @include('admin.components.table-toolbar')
    </div>


    <!-- Mobile Card View -->
    <div x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak class="p-4 space-y-3">
        @forelse($transactions as $trx)
        <div class="bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl p-4 space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                        <span class="material-symbols-rounded text-base">person</span>
                    </div>
                    <div>
                        <p class="text-[11px] font-black text-slate-900 dark:text-white uppercase leading-none">{{ $trx->marketplace->talent_name }}</p>
                        <p class="text-[8px] font-bold text-indigo-500 uppercase tracking-widest mt-0.5">{{ $trx->trx }}</p>
                    </div>
                </div>
                <span class="text-[13px] font-black text-emerald-600 dark:text-emerald-400 ">{{ gs('cur_sym') }}{{ showAmount($trx->amount) }}</span>
            </div>
            <div class="flex items-center justify-between">
                @if($trx->plan)
                <span class="px-2 py-0.5 rounded-lg bg-purple-500/10 text-purple-600 text-[7px] font-black uppercase ">{{ $trx->plan->plan_name }}</span>
                @else
                <span class="text-[8px] font-bold text-slate-400 uppercase ">Legacy Plan</span>
                @endif
                @if($trx->status == 1)
                <span class="px-2 py-0.5 rounded-lg bg-emerald-500 text-white text-[7px] font-black uppercase ">Settled</span>
                @else
                <span class="px-2 py-0.5 rounded-lg bg-amber-500/10 text-amber-600 text-[7px] font-black uppercase ">Pending</span>
                @endif
            </div>
            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ $trx->created_at->format('M d, Y H:i A') }}</p>
        </div>
        @empty
        <div class="py-20 text-center text-slate-400 font-black uppercase tracking-widest text-[9px] opacity-30">No transactions found</div>
        @endforelse
    </div>

    <!-- Desktop Table View -->
    <div x-show="$store.viewMode && $store.viewMode.mode === 'table'" x-cloak class="overflow-x-auto scrollbar-hide">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Transaction ID</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Talent Identity</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Tier / Plan</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Amount</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Status</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Timestamp</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($transactions as $trx)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                    <td class="px-8 py-6">
                        <span class="font-mono text-[11px] font-black text-slate-900 dark:text-white uppercase ">{{ $trx->trx }}</span>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-orange-500 shadow-lg shadow-orange-500/20 flex items-center justify-center text-white">
                                <span class="material-symbols-rounded text-sm">person</span>
                            </div>
                            <span class="font-black text-[11px] uppercase tracking-wider text-slate-700 dark:text-white/70 ">{{ $trx->marketplace->talent_name }}</span>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        @if($trx->plan)
                        <span class="px-3 py-1 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 text-[9px] font-black uppercase tracking-widest border border-purple-500/10 ">
                            {{ $trx->plan->plan_name }}
                        </span>
                        @else
                        <span class="text-[10px] font-bold text-slate-400 uppercase ">Legacy Plan</span>
                        @endif
                    </td>
                    <td class="px-8 py-6">
                        <span class="font-black text-[13px] text-emerald-600 dark:text-emerald-400 ">{{ gs('cur_sym') }}{{ showAmount($trx->amount) }}</span>
                    </td>
                    <td class="px-8 py-6 text-center">
                        @if($trx->status == 1)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[8px] font-black uppercase tracking-widest border border-emerald-500/10 ">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Settled
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 text-[8px] font-black uppercase tracking-widest border border-amber-500/10 ">
                            Pending
                        </span>
                        @endif
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="text-[10px] font-bold text-slate-500 dark:text-white/30 uppercase tracking-tighter ">
                            {{ $trx->created_at->format('M d, Y') }}
                            <span class="block text-[8px] opacity-60">{{ $trx->created_at->format('H:i A') }}</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-20 text-center">
                        <div class="flex flex-col items-center justify-center gap-4 border-2 border-dashed border-slate-200 dark:border-white/5 rounded-[2.5rem] p-12">
                            <span class="material-symbols-rounded text-5xl text-slate-300 dark:text-white/10">account_balance_wallet</span>
                            <p class="text-[11px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest">No marketplace transactions captured in log database</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transactions->hasPages())
    <div class="p-8 border-t border-slate-100 dark:border-white/10 bg-slate-50/50 dark:bg-white/[0.01]">
        {{ $transactions->links() }}
    </div>
    @endif
</div>
@endsection

