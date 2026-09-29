@extends('admin.layouts.app')

@section('title', 'Advertising Campaigns')
@section('header_title', 'Campaign Management')

@section('content')
<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm dark:shadow-2xl transition-all duration-300">
    @include('admin.components.header-toolbar', [
        'title' => 'Campaign Management',
        'items' => $campaigns,
        'createRoute' => route('admin.campaign.create'),
        'createLabel' => 'New Campaign'
    ])

    <div class="px-6 pt-6">
        @include('admin.components.table-toolbar')
    </div>
    <!-- Mobile Card View -->
    <div x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak class="p-4 space-y-3">
        @forelse($campaigns as $campaign)
        <div class="bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl p-4 space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-black text-slate-900 dark:text-white uppercase leading-none">{{ $campaign->title }}</p>
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">By {{ $campaign->user->username ?? 'System' }}</p>
                </div>
                <span class="text-[11px] font-black text-emerald-600 ">${{ number_format($campaign->total_amount, 2) }}</span>
            </div>
            <div class="flex flex-row w-full gap-2">
                <a href="{{ route('admin.campaign.detail', $campaign->id) }}" class="flex-1 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center gap-1.5 text-[8px] font-black uppercase tracking-widest transition-all">
                    <span class="material-symbols-rounded text-sm">monitoring</span> View
                </a>
                <a href="{{ route('admin.campaign.edit', $campaign->id) }}" class="flex-1 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center gap-1.5 text-[8px] font-black uppercase tracking-widest transition-all">
                    <span class="material-symbols-rounded text-sm">edit</span> Edit
                </a>
            </div>
        </div>
        @empty
        <div class="py-20 text-center text-slate-400 font-black uppercase tracking-widest text-[9px] opacity-30">No campaigns found</div>
        @endforelse
    </div>

    <!-- Desktop Table View -->
    <div x-show="$store.viewMode && $store.viewMode.mode === 'table'" x-cloak class="overflow-x-auto scrollbar-hide">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Signal Specs</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Architect</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Budget</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right">Operations</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($campaigns as $campaign)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 shadow-sm transition-transform group-hover:scale-110">
                                <span class="material-symbols-rounded text-lg">campaign</span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[12px] font-black text-slate-900 dark:text-white tracking-tight uppercase leading-none mb-1">{{ $campaign->title }}</p>
                                <p class="text-[8px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest ">Signal Code: #{{ $campaign->id }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-white/5 flex items-center justify-center text-[10px] font-black uppercase text-slate-400">
                                @php
                                    $cpWords = explode(' ', $campaign->user->username ?? 'S');
                                    $cpInitials = (count($cpWords) > 1) 
                                        ? substr($cpWords[0], 0, 1) . substr(end($cpWords), 0, 1) 
                                        : substr($cpWords[0], 0, 1);
                                @endphp
                                {{ $cpInitials }}
                            </div>
                            <span class="text-[11px] font-bold text-slate-600 dark:text-white/60 tracking-tight">{{ $campaign->user->username ?? 'System' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-[11px] font-black text-slate-900 dark:text-white ">${{ number_format($campaign->total_amount, 2) }}</span>
                        <div class="text-[8px] font-black text-emerald-500 uppercase tracking-widest mt-1">Via {{ $campaign->payment_method ?? 'Wallet' }}</div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                             <a href="{{ route('admin.campaign.detail', $campaign->id) }}" class="btn btn-sm btn-outline--primary">
                                <i class="las la-desktop"></i>@lang('Details')
                             </a>
                             <a href="{{ route('admin.campaign.edit', $campaign->id) }}" class="btn btn-sm btn-outline--warning">
                                <i class="las la-pencil-alt"></i>@lang('Edit')
                             </a>
                             <button class="btn btn-sm btn-outline--danger confirmationBtn" 
                                     data-action="{{ route('admin.campaign.delete', $campaign->id) }}" 
                                     data-method="DELETE"
                                     data-question="@lang('Are you sure you want to delete this campaign?')">
                                <i class="las la-trash"></i>@lang('Delete')
                             </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-10 py-32 text-center text-slate-400 font-black uppercase tracking-widest text-[9px] opacity-20">No Campaigns Found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($campaigns->hasPages())
        <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
            {{ $campaigns->links() }}
        </div>
    @endif
</div>
@endsection

