@extends('admin.layouts.app')

@section('title', 'Creator Plans')
@section('header_title', 'Creator Plans')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-700">
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl transition-all duration-500">
        @include('admin.components.header-toolbar', [
            'title' => 'Creator Membership Tiers',
            'items' => $plans,
            'createRoute' => route('admin.plans.create'),
            'createLabel' => 'Add New Creator Plan'
        ])

        <div class="px-6 pt-6">
            @include('admin.components.table-toolbar', ['module' => 'plans', 'exportTotal' => count($plans)])
        </div>

        <!-- Grid View (App View) -->
        <div x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($plans as $item)
                <div class="bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden relative group flex flex-col">
                    <!-- Top Decor -->
                    <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-blue-600 via-indigo-500 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="p-8 flex-grow">
                        <!-- Header -->
                        <div class="flex justify-between items-start mb-8">
                            <div class="w-14 h-14 rounded-2xl bg-blue-500/10 text-blue-500 flex items-center justify-center shadow-inner">
                                <span class="material-symbols-rounded text-2xl">verified_user</span>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="text-2xl font-black text-slate-900 dark:text-white tracking-tighter">{{ showAmount($item->price, 0) }}</span>
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-1">Tier Investment</span>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="space-y-4 mb-8">
                            <div>
                                <h4 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter leading-none group-hover:text-blue-500 transition-colors">{{ $item->name }}</h4>
                                <div class="flex items-center gap-3 mt-3">
                                    <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-white/5 text-[9px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest ">
                                        {{ $item->duration }} Days Validity
                                    </span>
                                    @if($item->video_access)
                                        <span class="px-3 py-1 rounded-lg bg-orange-500/10 text-orange-600 text-[9px] font-black uppercase tracking-widest border border-orange-500/20">
                                            Premium Access
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Status Toggle -->
                        <div class="pt-6 border-t border-slate-100 dark:border-white/5">
                            <form action="{{ route('admin.plans.status', $item->id) }}" method="POST" class="flex items-center justify-between">
                                @csrf
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest ">Subscription State</span>
                                <button type="submit" class="inline-flex items-center gap-2.5 px-4 py-2 rounded-xl {{ $item->status ? 'bg-emerald-500 text-white' : 'bg-slate-100 dark:bg-white/5 text-slate-400' }} text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-lg shadow-emerald-500/10">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $item->status ? 'bg-white animate-pulse' : 'bg-slate-300' }}"></span>
                                    {{ $item->status ? 'Active' : 'Offline' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-slate-50 dark:bg-white/[0.02] p-6 flex gap-3 border-t border-slate-100 dark:border-white/5">
                        <a href="{{ route('admin.plans.edit', $item->id) }}" class="flex-grow h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-xl shadow-blue-500/20 ">
                            <span class="material-symbols-rounded text-sm">settings_suggest</span> Edit Tier
                        </a>
                        <button type="button" 
                                class="w-12 h-12 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all shadow-sm confirmationBtn"
                                data-action="{{ route('admin.plans.destroy', $item->id) }}" 
                                data-method="DELETE"
                                data-question="Purge this membership tier from the system?">
                            <span class="material-symbols-rounded text-xl">delete_forever</span>
                        </button>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-40 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[3.5rem]">
                    <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-6xl">money_off</span>
                    <p class="mt-6 text-[11px] font-black text-slate-400 uppercase tracking-widest ">No Membership Tiers Defined</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Desktop Table View -->
        <div x-show="$store.viewMode && $store.viewMode.mode === 'table'" x-cloak class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-white/[0.02]">
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30">Plan Name</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30">Price</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30 text-center">Video Access</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30 text-center">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($plans as $item)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-white/[0.01] transition-all duration-300 group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 overflow-hidden shadow-lg transition-all duration-500 group-hover:scale-110 flex items-center justify-center p-0.5">
                                    <div class="w-full h-full bg-blue-500/10 rounded-xl flex items-center justify-center text-blue-500">
                                        <span class="material-symbols-rounded text-2xl">verified_user</span>
                                    </div>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[14px] font-black text-slate-900 dark:text-white tracking-tight uppercase leading-none mb-2 transition-colors group-hover:text-blue-500">{{ $item->name }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 dark:text-white/20 uppercase tracking-widest flex items-center gap-2">
                                        <span class="w-1 h-1 rounded-full bg-blue-500/30"></span> Validity: {{ $item->duration }} Days
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-lg font-black text-slate-900 dark:text-white tracking-tighter leading-none mb-1.5">{{ showAmount($item->price, 0) }}</span>
                                <span class="text-[8px] text-emerald-500 font-black uppercase tracking-widest ">Tier Access Value</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($item->video_access)
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-orange-500/10 text-orange-600 text-[8px] font-black uppercase tracking-widest border border-orange-500/20 shadow-sm shadow-orange-500/10">
                                    <span class="material-symbols-rounded text-sm">workspace_premium</span>
                                    Enabled
                                </span>
                            @else
                                <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest ">None</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            <form action="{{ route('admin.plans.status', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-2xl {{ $item->status ? 'bg-emerald-500/10 text-emerald-600' : 'bg-slate-100 dark:bg-white/5 text-slate-400' }} text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $item->status ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300' }}"></span>
                                    {{ $item->status ? 'Active' : 'Disabled' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-3">
                                 <a href="{{ route('admin.plans.edit', $item->id) }}" class="h-10 px-6 rounded-xl bg-blue-600 text-white flex items-center gap-2 text-[9px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-sm">
                                    <span class="material-symbols-rounded text-lg">settings_suggest</span>
                                    Edit
                                 </a>
                                 <button class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center hover:scale-105 transition-all shadow-sm confirmationBtn" 
                                         data-action="{{ route('admin.plans.destroy', $item->id) }}" 
                                         data-method="DELETE"
                                         data-question="@lang('Immediately delete this creator plan?')">
                                    <span class="material-symbols-rounded text-xl">delete_forever</span>
                                 </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-10 py-40 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[2rem] m-8">
                            <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-6xl">money_off</span>
                            <p class="mt-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.5em] opacity-30">No Creator Membership Tiers Defined</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-confirmation-modal />
@endsection

