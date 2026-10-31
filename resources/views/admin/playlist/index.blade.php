@extends('admin.layouts.app')

@section('title', 'Playlist Management')
@section('header_title', 'Playlists')

@section('content')
<div x-data="{ 
    selectAll: false, 
    toggleAll() { 
        const checkboxes = document.querySelectorAll('.bulk-select-item');
        checkboxes.forEach(cb => { cb.checked = this.selectAll; });
    }
}" class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] shadow-sm transition-all duration-300">
    @include('admin.components.header-toolbar', [
        'title' => 'Playlist Management',
        'items' => $playlists,
        'createRoute' => route('admin.playlist.create'),
        'createLabel' => 'Create Playlist'
    ])

    <div class="px-6 pt-6">
        @include('admin.components.table-toolbar', ['showBulkActions' => true, 'module' => 'playlists', 'bulkActions' => ['delete'], 'exportTotal' => count($playlists)])
    </div>
    
    <!-- Table View -->
    <div class="overflow-x-auto scrollbar-hide px-6" x-show="!$store.viewMode || $store.viewMode.mode === 'table'" x-cloak>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                        </label>
                    </th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Playlist Name')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Created By')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Visibility')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Content')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Price/Type')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right">@lang('Actions')</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse ($playlists as $playlist)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                        <td class="px-6 py-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" value="{{ $playlist->id }}" class="bulk-select-item w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                            </label>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                                    <span class="material-symbols-rounded text-2xl ">featured_play_list</span>
                                </div>
                                <div>
                                    <span class="block text-[13px] font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ __($playlist->title ?? $playlist->name) }}</span>
                                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">ID: #{{ $playlist->id }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center border border-slate-200 dark:border-white/10 overflow-hidden">
                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . @$playlist->user->image) }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('assets/images/avatar.png') }}'">
                                </div>
                                <div>
                                    <span class="block text-[11px] font-black text-slate-700 dark:text-white/70 uppercase">{{ $playlist->user?->fullname }}</span>
                                    <a href="{{ route('admin.users.detail', $playlist->user_id) }}" class="text-[9px] font-bold text-orange-500 hover:underline uppercase tracking-widest">
                                        @<span>{{ $playlist->user?->username }}</span>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if ($playlist->visibility == \App\Constants\Status::PUBLIC)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-500 text-[9px] font-black uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    @lang('Public')
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-500/10 text-slate-500 text-[9px] font-black uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                    @lang('Private')
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black text-slate-900 dark:text-white ">{{ $playlist->videos->count() }}</span>
                                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Videos</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black text-orange-500 ">{{ $playlist->reels->count() }}</span>
                                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Reels</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-2">
                                @if ($playlist->playlist_subscription)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-500/10 text-blue-500 text-[8px] font-black uppercase tracking-widest w-max">@lang('Sale Enabled')</span>
                                    <span class="text-[12px] font-black text-slate-900 dark:text-white ">{{ showAmount($playlist->price) }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-500/10 text-slate-400 text-[8px] font-black uppercase tracking-widest w-max">@lang('Standard')</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ">Free Access</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.playlist.videos.list', $playlist->id) }}" class="h-8 px-3 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center gap-2 text-slate-500 dark:text-white/40 hover:bg-blue-600 hover:text-white transition-all shadow-sm active:scale-95 group" title="@lang('Videos')">
                                    <span class="material-symbols-rounded text-sm ">movie</span>
                                    <span class="text-[9px] font-black uppercase tracking-tighter">Videos</span>
                                </a>
                                <a href="{{ route('admin.playlist.show', $playlist->id) }}" class="h-8 px-3 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center gap-2 text-slate-500 dark:text-white/40 hover:bg-slate-900 dark:hover:bg-white hover:text-white dark:hover:text-black transition-all shadow-sm active:scale-95 group" title="@lang('View Analytics')">
                                    <span class="material-symbols-rounded text-sm ">analytics</span>
                                    <span class="text-[9px] font-black uppercase tracking-tighter">View</span>
                                </a>
                                <a href="{{ route('admin.playlist.edit', $playlist->id) }}" class="h-8 px-3 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center gap-2 text-slate-500 dark:text-white/40 hover:bg-orange-500 hover:text-white transition-all shadow-sm active:scale-95 group" title="@lang('Modify')">
                                    <span class="material-symbols-rounded text-sm ">edit_note</span>
                                    <span class="text-[9px] font-black uppercase tracking-tighter">Edit</span>
                                </a>
                                <button class="h-8 px-3 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center gap-2 text-slate-500 dark:text-white/40 hover:bg-rose-500 hover:text-white transition-all shadow-sm active:scale-95 confirmationBtn group" 
                                        data-action="{{ route('admin.playlist.destroy', $playlist->id) }}" 
                                        data-question="@lang('Terminate this playlist curation framework?')" 
                                        title="@lang('Delete')">
                                    <span class="material-symbols-rounded text-sm ">delete_sweep</span>
                                    <span class="text-[9px] font-black uppercase tracking-tighter">Delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%" class="px-8 py-20 text-center opacity-20 text-[11px] font-black uppercase tracking-widest">
                            No playlists detected in current segment.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- App View (Grid View) -->
    <div class="px-6 pb-6" x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-6">
            @forelse ($playlists as $playlist)
                <div class="bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden relative group">
                    <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-orange-500 to-amber-400 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="p-8">
                        <div class="flex justify-between items-start mb-6">
                            <label class="w-6 h-6 rounded-lg border-2 border-slate-200 dark:border-white/10 flex items-center justify-center cursor-pointer hover:border-orange-500 transition-colors">
                                <input type="checkbox" value="{{ $playlist->id }}" class="bulk-select-item w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                            </label>
                            @if ($playlist->visibility == \App\Constants\Status::PUBLIC)
                                <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-500 text-[8px] font-black uppercase tracking-widest flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Public
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-slate-500/10 text-slate-500 text-[8px] font-black uppercase tracking-widest flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                    Private
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-6 mb-8">
                            <div class="w-20 h-20 rounded-[1.5rem] bg-orange-500/10 flex items-center justify-center text-orange-500 border-2 border-white dark:border-[#1a1a1a] shadow-xl">
                                <span class="material-symbols-rounded text-4xl ">featured_play_list</span>
                            </div>
                            <div class="flex-grow min-w-0">
                                <h4 class="text-lg font-black text-slate-900 dark:text-white truncate uppercase tracking-tighter mb-1">{{ __($playlist->title ?? $playlist->name) }}</h4>
                                <div class="flex items-center gap-3">
                                    <div class="w-5 h-5 rounded-full overflow-hidden border border-white dark:border-white/10 shadow-sm">
                                        <img src="{{ getImage(getFilePath('userProfile') . '/' . @$playlist->user->image) }}" class="w-full h-full object-cover">
                                    </div>
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">@ {{ $playlist->user?->username }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-slate-50 dark:bg-white/[0.02] p-4 rounded-2xl border border-slate-100 dark:border-white/5 flex flex-col items-center justify-center text-center">
                                <span class="text-xl font-black text-slate-900 dark:text-white mb-1">{{ $playlist->videos->count() }}</span>
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none">Videos</span>
                            </div>
                            <div class="bg-slate-50 dark:bg-white/[0.02] p-4 rounded-2xl border border-slate-100 dark:border-white/5 flex flex-col items-center justify-center text-center">
                                <span class="text-xl font-black text-orange-500 mb-1">{{ $playlist->reels->count() }}</span>
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none">Reels</span>
                            </div>
                        </div>

                        <div class="mt-8 pt-8 border-t border-slate-100 dark:border-white/5 grid grid-cols-3 gap-3">
                            <a href="{{ route('admin.playlist.videos.list', $playlist->id) }}" class="h-14 rounded-2xl bg-blue-500/5 text-blue-500 border border-blue-500/10 flex flex-col items-center justify-center gap-1 hover:bg-blue-600 hover:text-white transition-all shadow-sm group/btn">
                                <span class="material-symbols-rounded text-lg group-hover/btn:scale-110 transition-transform">movie</span>
                                <span class="text-[7px] font-black uppercase tracking-tighter">Videos</span>
                            </a>
                            <a href="{{ route('admin.playlist.show', $playlist->id) }}" class="h-14 rounded-2xl bg-slate-500/5 text-slate-500 border border-slate-500/10 flex flex-col items-center justify-center gap-1 hover:bg-slate-900 dark:hover:bg-white hover:text-white dark:hover:text-slate-900 transition-all shadow-sm group/btn">
                                <span class="material-symbols-rounded text-lg group-hover/btn:scale-110 transition-transform">analytics</span>
                                <span class="text-[7px] font-black uppercase tracking-tighter">View</span>
                            </a>
                            <a href="{{ route('admin.playlist.edit', $playlist->id) }}" class="h-14 rounded-2xl bg-orange-500/5 text-orange-500 border border-orange-500/10 flex flex-col items-center justify-center gap-1 hover:bg-orange-500 hover:text-white transition-all shadow-sm group/btn">
                                <span class="material-symbols-rounded text-lg group-hover/btn:scale-110 transition-transform">edit_note</span>
                                <span class="text-[7px] font-black uppercase tracking-tighter">Edit</span>
                            </a>
                            <button class="h-14 rounded-2xl bg-rose-500/5 text-rose-500 border border-rose-500/10 flex flex-col items-center justify-center gap-1 hover:bg-rose-500 hover:text-white transition-all shadow-sm group/btn confirmationBtn" 
                                    data-action="{{ route('admin.playlist.destroy', $playlist->id) }}" 
                                    data-question="@lang('Terminate this playlist curation framework?')">
                                <span class="material-symbols-rounded text-lg group-hover/btn:scale-110 transition-transform">delete_sweep</span>
                                <span class="text-[7px] font-black uppercase tracking-tighter">Delete</span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-40 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[3.5rem]">
                     <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-6xl uppercase">inventory_2</span>
                     <p class="mt-6 text-[11px] font-black text-slate-400 uppercase tracking-widest ">No playlists detected in current segment.</p>
                </div>
            @endforelse
        </div>
    </div>

    @if ($playlists->hasPages())
        <div class="p-8 border-t border-slate-100 dark:border-white/10 bg-slate-50/50 dark:bg-white/[0.01]">
            {{ $playlists->links() }}
        </div>
    @endif
</div>

<x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <div class="flex items-center gap-4">
        <x-search-form placeholder='Search playlists/users...' />
    </div>
@endpush

