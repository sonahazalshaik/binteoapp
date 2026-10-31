@extends('admin.layouts.app')

@section('title', 'Channel Membership Plans')
@section('header_title', 'Channel Plans')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-700">
    
    <!-- Top Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="p-6 bg-white dark:bg-[#121212] rounded-[2rem] border border-slate-200 dark:border-white/10 shadow-lg flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center">
                <span class="material-symbols-rounded text-3xl">loyalty</span>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Configured Tiers</p>
                <h4 class="text-2xl font-black text-slate-900 dark:text-white">{{ $stats['total_tiers'] }}</h4>
            </div>
        </div>

        <div class="p-6 bg-white dark:bg-[#121212] rounded-[2rem] border border-slate-200 dark:border-white/10 shadow-lg flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                <span class="material-symbols-rounded text-3xl">check_circle</span>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Active Tiers</p>
                <h4 class="text-2xl font-black text-slate-900 dark:text-white">{{ $stats['active_tiers'] }}</h4>
            </div>
        </div>

        <div class="p-6 bg-white dark:bg-[#121212] rounded-[2rem] border border-slate-200 dark:border-white/10 shadow-lg flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center">
                <span class="material-symbols-rounded text-3xl">group</span>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Active Members</p>
                <h4 class="text-2xl font-black text-slate-900 dark:text-white">{{ $stats['active_subscribers'] }}</h4>
            </div>
        </div>

        <div class="p-6 bg-white dark:bg-[#121212] rounded-[2rem] border border-slate-200 dark:border-white/10 shadow-lg flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-rose-500/10 text-rose-500 flex items-center justify-center">
                <span class="material-symbols-rounded text-3xl">payments</span>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Commission Earned</p>
                <h4 class="text-2xl font-black text-slate-900 dark:text-white">{{ showAmount($stats['total_revenue']) }}</h4>
            </div>
        </div>
    </div>

    <!-- Main Table Container -->
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl transition-all duration-500">
        @include('admin.components.header-toolbar', [
            'title' => 'Channel Plan Tiers (Creator Offerings)',
            'items' => $plans,
            'createRoute' => route('admin.channel-plans.create'),
            'createLabel' => 'Add New Channel Plan'
        ])

        <div class="px-6 pt-6">
            @include('admin.components.table-toolbar')
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-white/[0.02]">
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30">Plan Tier</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30">Duration</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30">Default Price</th>
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
                                    <div class="w-full h-full bg-amber-500/10 rounded-xl flex items-center justify-center text-amber-500">
                                        <span class="material-symbols-rounded text-2xl">workspace_premium</span>
                                    </div>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[14px] font-black text-slate-900 dark:text-white tracking-tight uppercase leading-none mb-2 transition-colors group-hover:text-amber-500">{{ $item->name }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 dark:text-white/30 uppercase tracking-widest line-clamp-1">
                                        {{ $item->description ?? 'Channel membership subscription tier' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-slate-900 dark:text-white">{{ $item->duration_months }} {{ Str::plural('Month', $item->duration_months) }}</span>
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ $item->duration_days }} Days Access</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-lg font-black text-slate-900 dark:text-white tracking-tighter leading-none mb-1.5">{{ showAmount($item->default_price) }}</span>
                                <span class="text-[8px] text-emerald-500 font-black uppercase tracking-widest">Suggested Creator Price</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <form action="{{ route('admin.channel-plans.status', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-2xl {{ $item->status ? 'bg-emerald-500/10 text-emerald-600' : 'bg-slate-100 dark:bg-white/5 text-slate-400' }} text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $item->status ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300' }}"></span>
                                    {{ $item->status ? 'Active' : 'Disabled' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.channel-plans.edit', $item->id) }}" class="h-10 px-5 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/60 flex items-center gap-2 text-[10px] font-black uppercase tracking-widest hover:bg-amber-500 hover:text-white transition-all active:scale-90 shadow-sm">
                                    <span class="material-symbols-rounded text-sm">edit</span> Edit
                                </a>
                                <form action="{{ route('admin.channel-plans.delete', $item->id) }}" method="POST" data-swal-question="Delete this Channel Plan tier? Creators will no longer be able to select it.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all active:scale-90 shadow-sm">
                                        <span class="material-symbols-rounded text-base">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center mx-auto mb-4">
                                <span class="material-symbols-rounded text-3xl">loyalty</span>
                            </div>
                            <h4 class="text-base font-black text-slate-900 dark:text-white mb-1">No Channel Plans Found</h4>
                            <p class="text-xs text-slate-400">Click the button above to add your first channel subscription tier.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
