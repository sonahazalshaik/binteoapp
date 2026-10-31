@extends('admin.layouts.app')

@section('title', 'Marketplace Services')
@section('header_title', 'Services')

@section('content')
<div x-data="{ 
    selectAll: false, 
    toggleAll() { 
        const checkboxes = document.querySelectorAll('.bulk-select-item');
        checkboxes.forEach(cb => { cb.checked = this.selectAll; });
    }
}" class="max-w-7xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-700 pb-24">
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl transition-all duration-500">
        @include('admin.components.header-toolbar', [
            'title' => 'Marketplace Services',
            'items' => $services,
            'createRoute' => route('admin.marketplace.services.create'),
            'createLabel' => 'Add Service'
        ])

        <div class="px-6 pt-6">
            @include('admin.components.table-toolbar', ['showBulkActions' => true, 'bulkActions' => ['delete']])
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto scrollbar-hide" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-white/[0.02]">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                            </label>
                        </th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">Service Specification</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">Origin Node (Creator)</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">Financial Rate</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($services as $item)
                    <tr class="group hover:bg-slate-50 dark:hover:bg-white/[0.01] transition-colors">
                        <td class="px-8 py-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="bulk-select-item w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500" value="{{ $item->id }}">
                            </label>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-black/20 overflow-hidden border border-slate-200 dark:border-white/10 group-hover:border-blue-500/30 transition-all">
                                    @if($item->service_img)
                                        <img src="{{ $item->photoUrl() }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-blue-500/5">
                                            <span class="material-symbols-rounded text-blue-500 text-xl">layers</span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="text-[12px] font-black text-slate-900 dark:text-white uppercase leading-none">{{ $item->service_name }}</h5>
                                    <p class="text-[9px] font-bold text-slate-400 dark:text-white/30 mt-1.5 uppercase tracking-widest">Signal Verified</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <h5 class="text-[11px] font-black text-slate-700 dark:text-white/70 uppercase leading-none">{{ @$item->marketplace->name ?? 'Unassigned' }}</h5>
                                <p class="text-[9px] font-bold text-slate-400 dark:text-white/20 mt-1 uppercase tracking-widest">{{ @$item->marketplace->type ?? 'N/A' }}</p>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-[13px] font-black text-emerald-500 ">{{ showAmount($item->price) }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="px-3 py-1 rounded-lg bg-blue-500/10 text-blue-500 text-[8px] font-black uppercase tracking-widest border border-blue-500/10 w-max">Active</div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.marketplace.services.edit', $item->id) }}" class="h-10 px-3 rounded-xl bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white transition-all flex items-center gap-2 shadow-sm group" title="Refine Protocol">
                                    <span class="material-symbols-rounded text-[18px]">edit_note</span>
                                    <span class="text-[9px] font-black uppercase tracking-tighter hidden xl:block">Edit</span>
                                </a>
                                <form action="{{ route('admin.marketplace.services.destroy', $item->id) }}" method="POST" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" data-swal-question="Immediately purge this service offering?" class="h-10 px-3 rounded-xl bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all flex items-center gap-2 shadow-sm group" title="Purge Protocol">
                                        <span class="material-symbols-rounded text-[18px]">delete_forever</span>
                                        <span class="text-[9px] font-black uppercase tracking-tighter hidden xl:block">Delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="100%" class="px-8 py-20 text-center text-muted ">No service protocols detected in repository</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- App View (Grid Mode) -->
        <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8" x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak>
            @forelse($services as $item)
            <div class="bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden relative group">
                <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="p-8">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-6 h-6 rounded-lg border-2 border-slate-200 dark:border-white/10 flex items-center justify-center">
                            <span class="material-symbols-rounded text-emerald-500 text-sm opacity-0 group-hover:opacity-100">check</span>
                        </div>
                         <div class="px-3 py-1 rounded-lg bg-blue-500/10 text-blue-500 text-[8px] font-black uppercase tracking-widest border border-blue-500/10 transition-all group-hover:bg-blue-500 group-hover:text-white">Active Service</div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 sm:gap-8 text-center sm:text-left mb-8">
                        <div class="relative flex-shrink-0">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-[1.5rem] p-1 bg-gradient-to-tr from-slate-200 to-slate-100 dark:from-white/10 dark:to-white/5 shadow-inner">
                                <div class="w-full h-full rounded-[1.2rem] overflow-hidden border-4 border-white dark:border-[#1a1a1a] shadow-xl relative bg-blue-500/5 flex items-center justify-center">
                                    @if($item->service_img)
                                        <img src="{{ $item->photoUrl() }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="material-symbols-rounded text-blue-500 text-3xl sm:text-4xl">layers</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow min-w-0 w-full">
                            <h4 class="text-lg font-black text-slate-900 dark:text-white truncate uppercase tracking-tighter leading-none mb-2 group-hover:text-blue-500 transition-colors">{{ $item->service_name }}</h4>
                             <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mb-4">
                                <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-white/5 text-[9px] font-black text-slate-500 dark:text-white/30 uppercase tracking-widest ">Node: {{ @$item->marketplace->name ?? 'Unassigned' }}</span>
                            </div>
                            <div class="grid grid-cols-1 gap-2">
                                <div class="p-3 bg-slate-50 dark:bg-white/[0.02] rounded-2xl border border-slate-100 dark:border-white/5 flex items-center justify-between">
                                    <span class="text-[7px] text-slate-400 font-black uppercase tracking-widest">Base Rate</span>
                                    <span class="text-lg font-black text-emerald-500 tracking-tighter">{{ showAmount($item->price) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3 mt-8">
                         <a href="{{ $item->photoUrl() }}" target="_blank" class="flex-1 h-12 rounded-xl bg-orange-500/10 text-orange-500 flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest hover:bg-orange-500 hover:text-white transition-all shadow-sm ">
                            <span class="material-symbols-rounded text-lg">visibility</span> View
                        </a>
                        <a href="{{ route('admin.marketplace.services.edit', $item->id) }}" class="flex-1 h-12 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all shadow-sm ">
                            <span class="material-symbols-rounded text-lg">edit</span> Edit
                        </a>
                        <form action="{{ route('admin.marketplace.services.destroy', $item->id) }}" method="POST" class="flex-1" data-swal-question="Immediately delete this service?">
                             @csrf
                             @method('DELETE')
                             <button type="submit" class="w-full h-12 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                                <span class="material-symbols-rounded text-lg">delete</span> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-40 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[3.5rem]">
                 <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-6xl">layers_clear</span>
                 <p class="mt-6 text-[11px] font-black text-slate-400 uppercase tracking-widest ">No active service offerings detected in network archives</p>
            </div>
            @endforelse
        </div>

        @if($services->hasPages())
        <div class="mt-12 p-8 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
            {{ $services->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

