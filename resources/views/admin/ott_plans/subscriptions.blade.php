@extends('admin.layouts.app')

@section('title', 'OTT Purchased Users')
@section('header_title', 'OTT Subscriptions')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-700">
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl transition-all duration-500">
        @include('admin.components.header-toolbar', [
            'title' => 'OTT Purchased Users',
            'items' => $subscriptions,
            'createRoute' => null,
            'createLabel' => null
        ])

        <div class="px-6 pt-6">
            @include('admin.components.table-toolbar')
        </div>

        <!-- Desktop Table View -->
        <div x-show="$store.viewMode && $store.viewMode.mode === 'table'" x-cloak class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-white/[0.02]">
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30">User</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30">Plan</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30">Validity</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30 text-center">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30 text-right">Join Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($subscriptions as $sub)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-white/[0.01] transition-all duration-300 group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-500 font-black shadow-inner">
                                    {{ substr($sub->user->username, 0, 2) }}
                                </div>
                                <div>
                                    <p class="text-[14px] font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $sub->user->username }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ">{{ $sub->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-[12px] font-black text-slate-900 dark:text-white uppercase tracking-tighter">{{ $sub->plan_name }}</span>
                                <span class="text-[9px] font-bold text-indigo-500 uppercase tracking-widest ">{{ showAmount($sub->price) }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-[11px] font-black text-slate-900 dark:text-white tracking-tight">{{ $sub->end_date->format('M d, Y') }}</span>
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ $sub->end_date->diffForHumans() }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($sub->status == 1 && $sub->end_date > now())
                                <span class="px-3 py-1.5 rounded-xl bg-emerald-500/10 text-emerald-600 text-[8px] font-black uppercase tracking-widest border border-emerald-500/10">Active</span>
                            @else
                                <span class="px-3 py-1.5 rounded-xl bg-red-500/10 text-red-500 text-[8px] font-black uppercase tracking-widest border border-red-500/10">Expired</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            <span class="text-[11px] font-bold text-slate-400 dark:text-white/20 uppercase tracking-widest">{{ $sub->created_at->format('M d, Y') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-10 py-40 text-center">
                            <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-6xl">person_off</span>
                            <p class="mt-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.5em] ">No active memberships</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- App View -->
        <div x-show="!$store.viewMode || $store.viewMode.mode === 'app'" x-cloak class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($subscriptions as $sub)
                <div class="bg-slate-50/50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-[2rem] p-6 transition-all duration-500 hover:shadow-2xl">
                    <div class="flex items-center gap-4 mb-6">
                         <div class="w-14 h-14 rounded-2xl bg-white dark:bg-white/10 border border-slate-100 dark:border-white/5 shadow-md flex items-center justify-center text-indigo-500 font-black text-lg">
                            {{ substr($sub->user->username, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tighter">{{ $sub->user->username }}</h4>
                            <div class="px-2 py-0.5 rounded-lg bg-indigo-500/10 text-indigo-500 text-[7px] font-black uppercase tracking-[0.2em] w-fit mt-1">{{ $sub->plan_name }}</div>
                        </div>
                    </div>
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center px-4 py-3 rounded-2xl bg-white dark:bg-black/20 border border-slate-100 dark:border-white/5">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Valid Until</span>
                            <span class="text-[10px] font-black text-slate-900 dark:text-white uppercase ">{{ $sub->end_date->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center px-4 py-3 rounded-2xl bg-white dark:bg-black/20 border border-slate-100 dark:border-white/5">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Status</span>
                            @if($sub->status == 1 && $sub->end_date > now())
                                <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest ">ACTIVE</span>
                            @else
                                <span class="text-[9px] font-black text-red-500 uppercase tracking-widest ">EXPIRED</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ">Joined: {{ $sub->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-32 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[3rem]">
                    <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-6xl">group_off</span>
                    <p class="mt-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.5em] ">No active memberships</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="px-8 pb-8">
            {{ paginateLinks($subscriptions) }}
        </div>
    </div>
</div>
@endsection

