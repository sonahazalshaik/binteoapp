@extends('admin.layouts.app')

@section('title', 'Withdrawal Methods')
@section('header_title', 'Payout Channels')

@section('content')
<div x-data="{ 
    selectAll: false, 
    toggleAll() { 
        const checkboxes = document.querySelectorAll('.bulk-select-item');
        checkboxes.forEach(cb => { cb.checked = this.selectAll; });
    }
}" class="bg-white dark:bg-[#121212] rounded-[2rem] overflow-hidden border border-slate-200 dark:border-white/10 shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-700">
    
    @include('admin.components.header-toolbar', [
        'createLabel' => 'Add Method',
        'createRoute' => route('admin.withdraw.method.create')
    ])

    <div class="px-6 pt-6">
        @include('admin.components.table-toolbar', ['showBulkActions' => false])
    </div>

    <!-- Table View -->
    <div class="overflow-x-auto table-responsive" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                        </label>
                    </th>
                    <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Method Info</th>
                    <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Fees & Charges</th>
                    <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Limits</th>
                    <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Status</th>
                    <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($methods as $method)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                    <td class="px-6 py-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" class="bulk-select-item w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500" value="{{ $method->id }}">
                        </label>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-white/5 overflow-hidden border border-slate-200 dark:border-white/10 p-2 group-hover:scale-105 transition-transform">
                                <img src="{{ str_starts_with($method->image ?? '', 'http') ? route('admin.withdraw.method.image', $method->id) : getImage($method->image) }}" class="w-full h-full object-fill">
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[13px] font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ __($method->name) }}</span>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-0.5">{{ __($method->currency) }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex flex-col gap-1">
                            <span class="text-[11px] font-black text-slate-700 dark:text-white/70">{{ showAmount($method->fixed_charge)}} {{ $method->currency }}</span>
                            <span class="text-[9px] font-black text-red-500 uppercase tracking-widest">+ {{ showAmount($method->percent_charge, currencyFormat:false) }}% Fee</span>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex flex-col gap-1">
                            <span class="text-[11px] font-black text-slate-700 dark:text-white/70">{{ showAmount($method->min_limit, currencyFormat: false) }} - {{ showAmount($method->max_limit, currencyFormat: false) }}</span>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest ">{{ $method->currency }} Range</span>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        @php echo $method->statusBadge @endphp
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.withdraw.method.edit', $method->id)}}" class="h-9 px-4 rounded-xl bg-blue-600 text-white flex items-center justify-center gap-2 hover:scale-105 transition-all shadow-sm group/btn" title="Edit Method">
                                <span class="material-symbols-rounded text-sm group-hover/btn:rotate-12 transition-transform">edit</span>
                                <span class="text-[9px] font-black uppercase tracking-tighter">Edit</span>
                            </a>
                            
                            @if($method->status == Status::ENABLE)
                                <button class="h-9 px-4 rounded-xl bg-amber-500 text-white flex items-center justify-center gap-2 hover:scale-105 transition-all shadow-sm confirmationBtn group/btn" 
                                        data-question="@lang('Disable this payout method?')" 
                                        data-action="{{ route('admin.withdraw.method.status',$method->id) }}"
                                        title="Disable">
                                    <span class="material-symbols-rounded text-sm group-hover/btn:scale-110 transition-transform">visibility_off</span>
                                    <span class="text-[9px] font-black uppercase tracking-tighter">Disable</span>
                                </button>
                            @else
                                <button class="h-9 px-4 rounded-xl bg-emerald-500 text-white flex items-center justify-center gap-2 hover:scale-105 transition-all shadow-sm confirmationBtn group/btn" 
                                        data-question="@lang('Enable this payout method?')" 
                                        data-action="{{ route('admin.withdraw.method.status',$method->id) }}"
                                        title="Enable">
                                    <span class="material-symbols-rounded text-sm group-hover/btn:scale-110 transition-transform">visibility</span>
                                    <span class="text-[9px] font-black uppercase tracking-tighter">Enable</span>
                                </button>
                            @endif

                            <button class="h-9 w-9 rounded-xl bg-rose-600 text-white flex items-center justify-center hover:scale-105 transition-all shadow-sm confirmationBtn" 
                                    data-action="{{ route('admin.withdraw.method.delete', $method->id) }}" 
                                    data-method="DELETE"
                                    data-question="@lang('Permanently delete this method?')"
                                    title="Delete">
                                <span class="material-symbols-rounded text-sm">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="py-20 text-center opacity-20 text-[11px] font-black uppercase tracking-widest" colspan="100%">No withdrawal methods detected.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Grid View (App View) -->
    <div class="px-6 pb-6" x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 mt-6">
            @forelse($methods as $method)
            <div class="bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden relative group">
                <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-emerald-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="p-8">
                    <!-- Top Bar -->
                    <div class="flex justify-between items-start mb-8">
                        <label class="w-6 h-6 rounded-lg border-2 border-slate-200 dark:border-white/10 flex items-center justify-center cursor-pointer hover:border-emerald-500 transition-colors">
                            <input type="checkbox" class="bulk-select-item w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500" value="{{ $method->id }}">
                        </label>
                        <div class="flex flex-col items-end">
                            @php echo $method->statusBadge @endphp
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-2 tracking-[0.2em]">{{ $method->currency }} Channel</span>
                        </div>
                    </div>

                    <!-- Profile Section Style -->
                    <div class="flex items-center gap-6 mb-8">
                        <div class="w-20 h-20 rounded-[1.8rem] bg-slate-50 dark:bg-white/5 border-4 border-white dark:border-[#1a1a1a] shadow-xl p-4 flex items-center justify-center group-hover:scale-105 transition-transform duration-500">
                            <img src="{{ str_starts_with($method->image ?? '', 'http') ? route('admin.withdraw.method.image', $method->id) : getImage($method->image) }}" class="w-full h-full object-fill">
                        </div>
                        <div class="flex-grow min-w-0">
                            <h4 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tighter leading-none mb-1">{{ __($method->name) }}</h4>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest opacity-60">1 {{ gs('cur_text') }} = {{ getAmount($method->rate) }} {{ $method->currency }}</p>
                        </div>
                    </div>

                    <!-- Metadata Grid -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 dark:bg-white/[0.02] p-4 rounded-2xl border border-slate-100 dark:border-white/5">
                            <span class="block text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Fee Structure</span>
                            <span class="block text-[11px] font-black text-slate-700 dark:text-white ">{{ showAmount($method->fixed_charge) }} + {{ getAmount($method->percent_charge) }}%</span>
                        </div>
                        <div class="bg-slate-50 dark:bg-white/[0.02] p-4 rounded-2xl border border-slate-100 dark:border-white/5">
                            <span class="block text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Status Protocol</span>
                            <span class="block text-[9px] font-black {{ $method->status == Status::ENABLE ? 'text-emerald-500' : 'text-rose-500' }} uppercase ">{{ $method->status == Status::ENABLE ? 'Active' : 'Disabled' }}</span>
                        </div>
                        <div class="col-span-2 bg-slate-50 dark:bg-white/[0.02] p-4 rounded-2xl border border-slate-100 dark:border-white/5">
                            <span class="block text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Transaction Boundaries</span>
                            <span class="block text-[11px] font-black text-emerald-500 ">
                                {{ showAmount($method->min_limit, currencyFormat: false) }} - {{ showAmount($method->max_limit, currencyFormat: false) }} {{ $method->currency }}
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 pt-8 border-t border-slate-100 dark:border-white/5 grid grid-cols-3 gap-3">
                        <a href="{{ route('admin.withdraw.method.edit', $method->id) }}" class="h-14 rounded-2xl bg-blue-500/5 text-blue-500 border border-blue-500/10 flex flex-col items-center justify-center gap-1 hover:bg-blue-500 hover:text-white transition-all shadow-sm group/btn">
                            <span class="material-symbols-rounded text-lg group-hover/btn:scale-110 transition-transform">edit</span>
                            <span class="text-[7px] font-black uppercase tracking-tighter">Edit</span>
                        </a>
                        
                        @if($method->status == Status::ENABLE)
                        <button class="h-14 rounded-2xl bg-amber-500/5 text-amber-500 border border-amber-500/10 flex flex-col items-center justify-center gap-1 hover:bg-amber-500 hover:text-white transition-all shadow-sm confirmationBtn group/btn"
                                data-question="@lang('Disable this method?')"
                                data-action="{{ route('admin.withdraw.method.status',$method->id) }}">
                            <span class="material-symbols-rounded text-lg group-hover/btn:scale-110 transition-transform">visibility_off</span>
                            <span class="text-[7px] font-black uppercase tracking-tighter">Disable</span>
                        </button>
                        @else
                        <button class="h-14 rounded-2xl bg-emerald-500/5 text-emerald-500 border border-emerald-500/10 flex flex-col items-center justify-center gap-1 hover:bg-emerald-500 hover:text-white transition-all shadow-sm confirmationBtn group/btn"
                                data-question="@lang('Enable this method?')"
                                data-action="{{ route('admin.withdraw.method.status',$method->id) }}">
                            <span class="material-symbols-rounded text-lg group-hover/btn:scale-110 transition-transform">visibility</span>
                            <span class="text-[7px] font-black uppercase tracking-tighter">Enable</span>
                        </button>
                        @endif

                        <button class="h-14 rounded-2xl bg-rose-500/5 text-rose-500 border border-rose-500/10 flex flex-col items-center justify-center gap-1 hover:bg-rose-500 hover:text-white transition-all shadow-sm confirmationBtn"
                                data-action="{{ route('admin.withdraw.method.delete', $method->id) }}"
                                data-method="DELETE"
                                data-question="@lang('Delete this method permanently?')">
                            <span class="material-symbols-rounded text-lg">delete</span>
                            <span class="text-[7px] font-black uppercase tracking-tighter">Delete</span>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-40 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[3.5rem]">
                 <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-6xl">payments</span>
                 <p class="mt-6 text-[11px] font-black text-slate-400 uppercase tracking-widest ">No methods detected in current segment.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<x-confirmation-modal />
@endsection

