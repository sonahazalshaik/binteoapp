@extends('admin.layouts.app')

@section('title', 'Marketplace Portfolios')
@section('header_title', 'Portfolios')

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
            'title' => 'Marketplace Portfolios',
            'items' => $portfolios,
            'createRoute' => route('admin.marketplace.portfolio.create'),
            'createLabel' => 'Add Portfolio'
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
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">Cover Preview</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">Portfolio Title</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">Creator Node</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">Assets</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($portfolios as $item)
                    <tr class="group hover:bg-slate-50 dark:hover:bg-white/[0.01] transition-colors">
                        <td class="px-8 py-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="bulk-select-item w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500" value="{{ $item->id }}">
                            </label>
                        </td>
                        <td class="px-8 py-6">
                            <div class="w-20 h-14 rounded-xl overflow-hidden border border-slate-200 dark:border-white/10 group-hover:border-orange-500/30 transition-all shadow-sm bg-slate-50 dark:bg-white/5 flex items-center justify-center">
                                @if($item->images->count() > 0)
                                    <img src="{{ getImage($item->images->first()->image) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="material-symbols-rounded text-slate-300 dark:text-white/20 text-2xl">image</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <h5 class="text-[12px] font-black text-slate-900 dark:text-white uppercase leading-none">{{ $item->title }}</h5>
                            <p class="text-[9px] font-bold text-slate-400 dark:text-white/30 mt-1.5 uppercase tracking-widest">{{ Str::limit(strip_tags($item->description), 60) }}</p>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <h5 class="text-[11px] font-black text-slate-700 dark:text-white/70 uppercase leading-none">{{ @$item->marketplace->name ?? 'Unassigned' }}</h5>
                                <p class="text-[9px] font-bold text-slate-400 dark:text-white/20 mt-1 uppercase tracking-widest">{{ @$item->marketplace->type ?? 'N/A' }}</p>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 rounded-lg bg-indigo-500/10 text-indigo-500 text-[8px] font-black uppercase tracking-widest border border-indigo-500/10 w-max">{{ $item->images->count() }} Photo(s)</span>
                        </td>
                        <td class="px-8 py-6">
                            @if($item->status == 1)
                                <div class="px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-[8px] font-black uppercase tracking-widest border border-emerald-500/10 w-max">Active</div>
                            @else
                                <div class="px-3 py-1 rounded-lg bg-rose-500/10 text-rose-500 text-[8px] font-black uppercase tracking-widest border border-rose-500/10 w-max">Hidden</div>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.marketplace.portfolio.edit', $item->id) }}" class="h-10 px-3 rounded-xl bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white transition-all flex items-center gap-2 shadow-sm group" title="Edit Portfolio">
                                    <span class="material-symbols-rounded text-[18px]">edit_note</span>
                                    <span class="text-[9px] font-black uppercase tracking-tighter hidden xl:block">Edit</span>
                                </a>
                                <button type="button" class="h-10 px-3 rounded-xl bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all flex items-center gap-2 shadow-sm group confirmationBtn"
                                    data-action="{{ route('admin.marketplace.portfolio.destroy', $item->id) }}"
                                    data-question="Are you sure to delete this portfolio?">
                                    <span class="material-symbols-rounded text-[18px]">delete_forever</span>
                                    <span class="text-[9px] font-black uppercase tracking-tighter hidden xl:block">Delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="100%" class="px-8 py-20 text-center text-muted ">No portfolio entries detected in database</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- App View (Grid Mode) -->
        <div class="p-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8" x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak>
            @forelse($portfolios as $item)
            <div class="bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden relative group">
                <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-orange-500 via-amber-500 to-yellow-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="h-48 bg-slate-100 dark:bg-white/5 relative overflow-hidden">
                    @if($item->images->count() > 0)
                        <img src="{{ getImage($item->images->first()->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-white/20">
                            <span class="material-symbols-rounded text-5xl">image</span>
                        </div>
                    @endif
                    <div class="absolute top-4 right-4 bg-white/90 dark:bg-black/80 backdrop-blur-md px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-900 dark:text-white shadow-sm">
                        {{ $item->images->count() }} Photos
                    </div>
                </div>
                <div class="p-8">
                    <div class="flex justify-between items-start mb-4">
                        <h4 class="text-lg font-black text-slate-900 dark:text-white truncate uppercase tracking-tighter leading-none group-hover:text-orange-500 transition-colors">{{ $item->title }}</h4>
                        <div class="shrink-0 ml-3">
                            @if($item->status == 1)
                                <div class="px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-[8px] font-black uppercase tracking-widest border border-emerald-500/10">Active</div>
                            @else
                                <div class="px-3 py-1 rounded-lg bg-rose-500/10 text-rose-500 text-[8px] font-black uppercase tracking-widest border border-rose-500/10">Hidden</div>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-white/5 text-[9px] font-black text-slate-500 dark:text-white/30 uppercase tracking-widest ">Node: {{ @$item->marketplace->name ?? 'Unassigned' }}</span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-white/60 mb-6 line-clamp-2">{{ strip_tags($item->description) }}</p>
                    <div class="flex gap-3 mt-auto pt-4 border-t border-slate-100 dark:border-white/5">
                        <a href="{{ route('admin.marketplace.portfolio.edit', $item->id) }}" class="flex-1 h-12 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all shadow-sm ">
                            <span class="material-symbols-rounded text-lg">edit</span> Edit
                        </a>
                        <button type="button" class="flex-1 h-12 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all shadow-sm confirmationBtn"
                            data-action="{{ route('admin.marketplace.portfolio.destroy', $item->id) }}"
                            data-question="Are you sure to delete this portfolio?">
                            <span class="material-symbols-rounded text-lg">delete</span> Delete
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-40 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[3.5rem]">
                <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-6xl">work_history</span>
                <p class="mt-6 text-[11px] font-black text-slate-400 uppercase tracking-widest ">No portfolio entries detected in archives</p>
            </div>
            @endforelse
        </div>

        @if($portfolios->hasPages())
        <div class="mt-12 p-8 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
            {{ $portfolios->links() }}
        </div>
        @endif
    </div>
</div>

<x-confirmation-modal />
@endsection

