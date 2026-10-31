@extends('admin.layouts.app')

@section('title', 'Channel Subscriptions & Users')
@section('header_title', 'Subscribed Users')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-700">
    
    <!-- Top Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="p-6 bg-white dark:bg-[#121212] rounded-[2rem] border border-slate-200 dark:border-white/10 shadow-lg flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center">
                <span class="material-symbols-rounded text-3xl">subscriptions</span>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Purchases</p>
                <h4 class="text-2xl font-black text-slate-900 dark:text-white">{{ $stats['total_subscriptions'] }}</h4>
            </div>
        </div>

        <div class="p-6 bg-white dark:bg-[#121212] rounded-[2rem] border border-slate-200 dark:border-white/10 shadow-lg flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                <span class="material-symbols-rounded text-3xl">verified</span>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Currently Active</p>
                <h4 class="text-2xl font-black text-slate-900 dark:text-white">{{ $stats['active_subscriptions'] }}</h4>
            </div>
        </div>

        <div class="p-6 bg-white dark:bg-[#121212] rounded-[2rem] border border-slate-200 dark:border-white/10 shadow-lg flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center">
                <span class="material-symbols-rounded text-3xl">percent</span>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Admin Commission Split</p>
                <h4 class="text-2xl font-black text-slate-900 dark:text-white">30% Auto-Split</h4>
            </div>
        </div>
    </div>

    <!-- Main Table Container -->
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl transition-all duration-500">
        @include('admin.components.header-toolbar', [
            'title' => 'Channel Plan Subscriptions (Member Roster)',
            'items' => $subscriptions,
            'createRoute' => null,
            'createLabel' => null
        ])

        <div class="px-6 pt-6">
            @include('admin.components.table-toolbar')
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-white/[0.02]">
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30">Subscriber</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30">Channel / Creator</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30">Plan & Amount</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30">Validity</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($subscriptions as $sub)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-white/[0.01] transition-all duration-300 group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-500 font-black shadow-inner">
                                    {{ substr($sub->user->username ?? 'U', 0, 2) }}
                                </div>
                                <div>
                                    <p class="text-[14px] font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $sub->user->username ?? 'Unknown' }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $sub->user->email ?? 'No email' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                                    <span class="material-symbols-rounded text-lg">tv</span>
                                </div>
                                <div>
                                    <a href="{{ route('channels.show', $sub->channel->slug ?? $sub->channel->id ?? 1) }}" target="_blank" class="text-sm font-black text-slate-900 dark:text-white hover:text-amber-500 transition-colors">
                                        {{ $sub->channel->name ?? 'Unknown Channel' }}
                                    </a>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Creator: {{ $sub->channel->user->username ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-[12px] font-black text-slate-900 dark:text-white uppercase tracking-tighter">{{ $sub->plan->name ?? 'Channel Pass' }}</span>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[11px] font-black text-emerald-500">{{ showAmount($sub->amount) }}</span>
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">(30% admin split)</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-[11px] font-black text-slate-900 dark:text-white tracking-tight">{{ $sub->expires_at ? $sub->expires_at->format('M d, Y') : 'N/A' }}</span>
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ $sub->expires_at ? $sub->expires_at->diffForHumans() : '' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($sub->isActive())
                                <span class="px-3 py-1.5 rounded-xl bg-emerald-500/10 text-emerald-600 text-[8px] font-black uppercase tracking-widest border border-emerald-500/20">Active</span>
                            @else
                                <span class="px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-400 text-[8px] font-black uppercase tracking-widest">Expired</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center mx-auto mb-4">
                                <span class="material-symbols-rounded text-3xl">group_off</span>
                            </div>
                            <h4 class="text-base font-black text-slate-900 dark:text-white mb-1">No Channel Subscriptions Yet</h4>
                            <p class="text-xs text-slate-400">When fans subscribe to creators' channel plans, their purchases will appear here.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscriptions->hasPages())
        <div class="p-6 border-t border-slate-100 dark:border-white/5">
            {{ $subscriptions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
