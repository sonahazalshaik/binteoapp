@extends('admin.layouts.app')

@section('title', 'Gallery')
@section('header_title', 'Gallery')

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
            'title' => 'Gallery Photos',
            'items' => $galleries,
            'createRoute' => route('admin.marketplace.gallery.create'),
            'createLabel' => 'Add Photo'
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
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">Asset Signal</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">Creator Node</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">Asset ID</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($galleries as $item)
                    <tr class="group hover:bg-slate-50 dark:hover:bg-white/[0.01] transition-colors">
                        <td class="px-8 py-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="bulk-select-item w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500" value="{{ $item->id }}">
                            </label>
                        </td>
                        <td class="px-8 py-6">
                            <div class="w-16 h-20 rounded-xl overflow-hidden border border-slate-200 dark:border-white/10 group-hover:border-orange-500/30 transition-all shadow-sm">
                                <img src="{{ $item->photoUrl() }}" class="w-full h-full object-cover">
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <h5 class="text-[12px] font-black text-slate-900 dark:text-white uppercase leading-none">{{ @$item->marketplace->name ?? 'System Admin' }}</h5>
                                <p class="text-[9px] font-bold text-slate-400 dark:text-white/30 mt-1.5 uppercase tracking-widest">@<span>{{ @$item->marketplace->email ?? 'N/A' }}</span></p>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-tighter">SIG-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-[8px] font-black uppercase tracking-widest border border-emerald-500/10 w-max">Active</div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ $item->photoUrl() }}" target="_blank" class="h-10 px-3 rounded-xl bg-orange-500/10 text-orange-500 hover:bg-orange-500 hover:text-white transition-all flex items-center gap-2 shadow-sm group" title="View Source">
                                    <span class="material-symbols-rounded text-[18px]">visibility</span>
                                    <span class="text-[9px] font-black uppercase tracking-tighter hidden xl:block">View</span>
                                </a>
                                <a href="{{ route('admin.marketplace.gallery.edit', $item->marketplace_id) }}" class="h-10 px-3 rounded-xl bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white transition-all flex items-center gap-2 shadow-sm group" title="Refine Signal">
                                    <span class="material-symbols-rounded text-[18px]">tune</span>
                                    <span class="text-[9px] font-black uppercase tracking-tighter hidden xl:block">Edit</span>
                                </a>
                                <form action="{{ route('admin.marketplace.gallery.destroy', $item->id) }}" method="POST" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" data-swal-question="Purge this visual archive?" class="h-10 px-3 rounded-xl bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all flex items-center gap-2 shadow-sm group" title="Purge Asset">
                                        <span class="material-symbols-rounded text-[18px]">delete</span>
                                        <span class="text-[9px] font-black uppercase tracking-tighter hidden xl:block">Delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="100%" class="px-8 py-20 text-center text-muted ">No visual signals detected in archives</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- App View (Grid Mode) -->
        <div class="p-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak>
            @forelse($galleries as $item)
            <div class="group relative bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden transition-all hover:scale-[1.02] hover:-rotate-1 shadow-sm hover:shadow-2xl duration-500">
                <div class="aspect-[4/5] overflow-hidden relative group/img bg-slate-50 dark:bg-white/5 flex flex-col items-center justify-center">
                    <img src="{{ $item->photoUrl() }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover/img:scale-110 absolute inset-0 z-10" onerror="this.onerror=null; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">

                    <div class="hidden flex-col items-center justify-center text-slate-300 dark:text-white/10 z-0 relative w-full h-full">
                        <span class="material-symbols-rounded text-6xl mb-4">image_not_supported</span>
                        <span class="text-[8px] font-black uppercase tracking-[0.2em] ">Corrupted Asset</span>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent flex flex-col justify-end p-6 z-20">
                        <p class="text-[8px] font-black text-white/40 uppercase tracking-[0.3em] mb-1">Archived By</p>
                        <h5 class="text-xs font-black text-white uppercase tracking-tight leading-none mb-4 truncate">{{ @$item->marketplace->name ?? 'System Admin' }}</h5>
                        
                        <div class="flex flex-row w-full gap-2 mt-4">
                             <a href="{{ $item->photoUrl() }}" target="_blank" class="flex-1 h-12 rounded-xl bg-white/20 backdrop-blur-md text-white border border-white/20 flex flex-col items-center justify-center hover:bg-white hover:text-slate-900 transition-all font-black text-[8px] uppercase tracking-tight shadow-lg">
                                <span class="material-symbols-rounded text-[14px]">visibility</span> <span class="truncate">View</span>
                            </a>
                             <a href="{{ route('admin.marketplace.gallery.edit', $item->marketplace_id) }}" class="flex-1 h-12 rounded-xl bg-white/20 backdrop-blur-md text-white border border-white/20 flex flex-col items-center justify-center hover:bg-white hover:text-slate-900 transition-all font-black text-[8px] uppercase tracking-tight shadow-lg">
                                <span class="material-symbols-rounded text-[14px]">edit</span> <span class="truncate">Edit</span>
                            </a>
                            <form action="{{ route('admin.marketplace.gallery.destroy', $item->id) }}" method="POST" class="flex-1 flex" data-swal-question="Purge this visual archive?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex-1 w-full h-12 rounded-xl bg-rose-500 text-white flex flex-col items-center justify-center hover:bg-rose-600 transition-all shadow-lg active:scale-90 font-black text-[8px] uppercase tracking-tight ">
                                    <span class="material-symbols-rounded text-[14px]">delete</span> <span class="truncate">Delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="p-6 flex items-center justify-between bg-slate-50/50 dark:bg-white/[0.01]">
                      <div class="flex flex-col">
                         <span class="text-[7px] font-black text-slate-400 uppercase tracking-[0.25em] mb-1 ">Asset Node</span>
                         <span class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-tighter">SIG-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span>
                      </div>
                     <div class="px-3 py-1 rounded-lg bg-orange-500/10 text-orange-500 text-[8px] font-black uppercase tracking-widest border border-orange-500/10 shadow-lg shadow-orange-500/5">Verified</div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-40 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[3.5rem]">
                <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-6xl">photo_album</span>
                <p class="mt-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.4em] opacity-40">No visual signals detected in gallery archives</p>
            </div>
            @endforelse
        </div>

        @if($galleries->hasPages())
        <div class="p-8 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
            {{ $galleries->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

