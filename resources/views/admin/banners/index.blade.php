@extends('admin.layouts.app')

@section('title', $pageTitle)
@section('header_title', $pageTitle)

@section('content')
<div x-data="{ 
    selectAll: false, 
    toggleAll() { 
        const checkboxes = document.querySelectorAll('.bulk-select-item');
        checkboxes.forEach(cb => { cb.checked = this.selectAll; });
    }
}" class="max-w-7xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-700">
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl transition-all duration-500">
        @include('admin.components.header-toolbar', [
            'title' => strtoupper($slot) . ' Campaigns',
            'items' => $banners ?? collect(),
            'createRoute' => route('admin.banners.create', $slot),
            'createLabel' => 'Add New Banner'
        ])

        <div class="px-6 pt-6">
            @include('admin.components.table-toolbar', ['showBulkActions' => true, 'module' => 'banners', 'bulkActions' => ['delete'], 'exportTotal' => count($banners)])
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto scrollbar-hide" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-white/[0.02]">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="w-4 h-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                            </label>
                        </th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">Banner Asset</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">Campaign Lifecycle</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">Target Node</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($banners as $item)
                    <tr class="group hover:bg-slate-50 dark:hover:bg-white/2 transition-colors">
                        <td class="px-8 py-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="bulk-select-item w-4 h-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500" value="{{ $item->id }}">
                            </label>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-24 h-14 rounded-xl bg-slate-100 dark:bg-black/20 overflow-hidden border border-slate-200 dark:border-white/10 group-hover:border-orange-500/30 transition-all">
                                    <img src="{{ getImage($item->image) }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('assets/images/default-banner.png') }}'">
                                </div>
                                <div>
                                    <h5 class="text-[11px] font-black text-slate-900 dark:text-white uppercase leading-none truncate max-w-[200px]">{{ $item->link ?? 'Direct Campaign' }}</h5>
                                    <p class="text-[9px] font-bold text-slate-400 dark:text-white/20 mt-1.5 uppercase tracking-widest ">{{ strtoupper($slot) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2 text-[10px] font-black text-slate-700 dark:text-white/70 ">
                                    <span class="material-symbols-rounded text-[14px]">calendar_today</span>
                                    {{ $item->start_date->format('M d, Y') }}
                                </div>
                                <div class="flex items-center gap-2 text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                    <span class="material-symbols-rounded text-[14px]">event_busy</span>
                                    Expires {{ $item->end_date->format('M d, Y') }}
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 rounded-lg bg-orange-500/10 text-orange-500 text-[9px] font-black uppercase tracking-[0.2em] border border-orange-500/20">
                                {{ strtoupper($slot) }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <form action="{{ route('admin.banners.status', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 rounded-lg {{ $item->status ? 'bg-emerald-500/10 text-emerald-500' : 'bg-rose-500/10 text-rose-500' }} text-[8px] font-black uppercase tracking-widest border border-current shadow-sm transition-all hover:scale-105">
                                    {{ $item->status ? 'Live' : 'Paused' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.banners.edit', ['slot' => $slot, 'id' => $item->id]) }}" class="h-10 px-4 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-500 hover:bg-orange-500 hover:text-white transition-all flex items-center gap-2 shadow-sm">
                                    <span class="material-symbols-rounded text-[18px]">edit_square</span>
                                    <span class="text-[9px] font-black uppercase tracking-tighter">Edit</span>
                                </a>
                                <form action="{{ route('admin.banners.destroy', $item->id) }}" method="POST" class="m-0" data-swal-question="Delete this banner permanently?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="h-10 px-4 rounded-xl bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all flex items-center gap-2 shadow-sm">
                                        <span class="material-symbols-rounded text-[18px]">delete</span>
                                        <span class="text-[9px] font-black uppercase tracking-tighter">Delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="100%" class="px-8 py-20 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-b-[2.5rem]">
                            <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-5xl">ad_units</span>
                            <p class="mt-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] opacity-40">No campaigns assigned to this node</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- App View (Grid Mode) -->
        <div class="p-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak>
            @forelse($banners as $item)
            <div class="group relative bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden transition-all hover:scale-[1.02] hover:-rotate-1 shadow-sm hover:shadow-2xl duration-500">
                <div class="aspect-[16/9] overflow-hidden relative group/img bg-slate-50 dark:bg-white/5 flex flex-col items-center justify-center">
                    <img src="{{ getImage($item->image) }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover/img:scale-110 absolute inset-0 z-10" onerror="this.onerror=null; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">

                    <div class="hidden flex-col items-center justify-center text-slate-300 dark:text-white/10 z-0 relative w-full h-full">
                        <span class="material-symbols-rounded text-6xl mb-4">image_not_supported</span>
                        <span class="text-[8px] font-black uppercase tracking-[0.2em] ">R2 Asset Syncing</span>
                    </div>

                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent opacity-100 transition-opacity duration-500 flex flex-col justify-end p-6 z-20">
                        <div class="flex flex-row w-full gap-2">
                             <a href="{{ route('admin.banners.edit', ['slot' => $slot, 'id' => $item->id]) }}" class="flex-1 h-12 rounded-xl bg-white/20 backdrop-blur-md text-white border border-white/20 flex flex-col items-center justify-center hover:bg-white hover:text-slate-900 transition-all font-black text-[8px] uppercase tracking-tight shadow-lg">
                                <span class="material-symbols-rounded text-[14px]">edit_square</span> <span class="truncate">Edit Banner</span>
                            </a>
                            <form action="{{ route('admin.banners.destroy', $item->id) }}" method="POST" class="flex-1 flex" data-swal-question="Delete this banner permanently?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex-1 w-full h-12 rounded-xl bg-rose-500 text-white flex flex-col items-center justify-center hover:bg-rose-600 transition-all shadow-lg active:scale-90 font-black text-[8px] uppercase tracking-tight ">
                                    <span class="material-symbols-rounded text-[14px]">delete_forever</span> <span class="truncate">Delete Banner</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="p-6 flex flex-col gap-3 bg-slate-50/50 dark:bg-white/[0.01]">
                      <div class="flex items-center justify-between">
                         <div class="flex flex-col">
                            <span class="text-[7px] font-black text-slate-400 uppercase tracking-[0.25em] mb-1 ">Campaign Node</span>
                            <span class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-tighter">{{ strtoupper($slot) }}-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</span>
                         </div>
                         <form action="{{ route('admin.banners.status', $item->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1 rounded-lg {{ $item->status ? 'bg-emerald-500/10 text-emerald-500' : 'bg-rose-500/10 text-rose-500' }} text-[8px] font-black uppercase tracking-widest border border-current shadow-sm">
                                {{ $item->status ? 'Live' : 'Paused' }}
                            </button>
                         </form>
                      </div>
                      <div class="flex items-center gap-2 text-[9px] font-bold text-slate-400 ">
                        <span class="material-symbols-rounded text-[12px]">calendar_today</span>
                        {{ $item->start_date->format('M d') }} - {{ $item->end_date->format('M d, Y') }}
                      </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-40 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[3.5rem]">
                <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-6xl">ad_units</span>
                <p class="mt-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.4em] opacity-40">No active deployments for {{ strtoupper($slot) }}</p>
            </div>
            @endforelse
        </div>

        @if($banners->hasPages())
        <div class="p-8 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
            {{ $banners->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

