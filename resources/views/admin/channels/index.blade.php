@extends('admin.layouts.app')

@section('title', 'Channel Management')
@section('header_title', 'Virtual Channels')

@section('content')
<div x-data="{ 
    selectAll: false, 
    toggleAll() { 
        const checkboxes = document.querySelectorAll('.bulk-select-item');
        checkboxes.forEach(cb => { 
            cb.checked = this.selectAll; 
            cb.dispatchEvent(new Event('change', { bubbles: true }));
        });
    }
}" class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm dark:shadow-2xl transition-all duration-300">
    @include('admin.components.header-toolbar', [
        'title' => 'Virtual Channels',
        'items' => $channels,
        'createRoute' => route('admin.channels.create'),
        'createLabel' => 'Add Channel'
    ])

    <div class="px-6 pt-6">
        @include('admin.components.table-toolbar', [
            'showBulkActions' => true,
            'module' => 'channels',
            'bulkRoute' => route('admin.channels.bulk'),
            'bulkActions' => ['approve', 'unapprove', 'featured', 'unfeatured', 'trending', 'untrending', 'delete'],
            'exportTotal' => count($channels)
        ])
    </div>

    <!-- Table View -->
    <div class="overflow-x-auto scrollbar-hide" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="selectAllHeader" x-model="selectAll" @change="toggleAll()" class="w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                        </label>
                    </th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">@lang('Channel')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">@lang('Creator')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">@lang('Subscribers')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">@lang('Status')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate text-right">@lang('Actions')</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($channels as $channel)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                    <td class="px-6 py-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" class="bulk-select-item w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500" value="{{ $channel->id }}">
                        </label>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-100 dark:bg-white/5 flex items-center justify-center font-bold text-slate-400">
                                @php
                                    $channelName = $channel->name ?? $channel->channel_name;
                                    $words = explode(' ', $channelName);
                                    $initials = strtoupper(substr($words[0], 0, 1) . (count($words) > 1 ? substr(end($words), 0, 1) : ''));
                                @endphp
                                @if($channel->avatar)
                                    <img src="{{ getImage(getFilePath('channelAvatar').'/'.$channel->avatar) }}" class="w-full h-full object-cover">
                                @elseif($channel->image)
                                    <img src="{{ getImage(getFilePath('channelProfile').'/'.$channel->image) }}" class="w-full h-full object-cover">
                                @else
                                    {{ $initials }}
                                @endif
                            </div>
                            <div>
                                <span class="text-[11px] font-bold text-slate-700 dark:text-white/70 block">{{ $channel->channel_name }}</span>
                                <span class="text-[9px] text-slate-400">ID: {{ $channel->id }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                        @<span>{{ $channel->user->username }}</span>
                    </td>
                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                        {{ number_format($channel->subscribers_count) }}
                    </td>
                    <td class="px-6 py-4">
                        @if($channel->is_active)
                            <span class="px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-[8px] font-black uppercase tracking-widest border border-emerald-500/20">Live Stream</span>
                        @else
                            <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-white/5 text-slate-400 text-[8px] font-black uppercase tracking-widest border border-slate-200 dark:border-white/5">Offline</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="button--group justify-end flex items-center gap-2">
                            <a href="{{ route('admin.channels.show', $channel) }}" class="btn btn-sm btn-outline--primary"><i class="la la-desktop"></i> @lang('Details')</a>
                            <a href="{{ route('admin.channels.edit', $channel) }}" class="btn btn-sm btn-outline--info"><i class="la la-pencil"></i> @lang('Edit')</a>
                            
                            <form action="{{ route('admin.channels.toggle.featured', $channel->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 h-8 rounded-xl text-white shadow-lg flex items-center gap-1 transition-all hover:scale-105 border-0" style="background: {{ $channel->is_featured ? 'linear-gradient(to right, #f59e0b, #ea580c)' : 'rgba(148, 163, 184, 0.1)' }} !important; color: {{ $channel->is_featured ? 'white' : '#94a3b8' }} !important;" title="{{ $channel->is_featured ? 'Unfeature' : 'Feature' }}">
                                    <span class="material-symbols-rounded text-sm {{ $channel->is_featured ? 'fill-1' : '' }}">{{ $channel->is_featured ? 'grade' : 'star' }}</span>
                                    <span class="text-[8px] font-black uppercase tracking-tighter">{{ $channel->is_featured ? 'Featured' : 'Feature' }}</span>
                                </button>
                            </form>
                            <form action="{{ route('admin.channels.toggle.trending', $channel->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 h-8 rounded-xl text-white shadow-lg flex items-center gap-1 transition-all hover:scale-105 border-0" style="background: {{ $channel->is_trending ? 'linear-gradient(to right, #10b981, #0d9488)' : 'rgba(148, 163, 184, 0.1)' }} !important; color: {{ $channel->is_trending ? 'white' : '#94a3b8' }} !important;" title="{{ $channel->is_trending ? 'Untrend' : 'Trend' }}">
                                    <span class="material-symbols-rounded text-sm {{ $channel->is_trending ? 'fill-1' : '' }}">{{ $channel->is_trending ? 'trending_up' : 'trending_down' }}</span>
                                    <span class="text-[8px] font-black uppercase tracking-tighter">{{ $channel->is_trending ? 'Trending' : 'Trend' }}</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                    <td class="text-muted text-center" colspan="100%">@lang('No broadcast channels detected in system archives')</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Main Grid View (App View) -->
    <div class="px-6 pb-6" x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            @forelse($channels as $channel)
            <div class="bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden relative group">
                <!-- Top Header Decor -->
                <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-emerald-500 via-blue-500 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="p-8">
                    <!-- Top Bar -->
                    <div class="flex justify-between items-start mb-6">
                        <label class="w-6 h-6 rounded-lg border-2 border-slate-200 dark:border-white/10 flex items-center justify-center cursor-pointer hover:border-rose-500 transition-colors">
                            <input type="checkbox" class="bulk-select-item w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500" value="{{ $channel->id }}">
                        </label>
                         <div class="flex items-center gap-2">
                             <form action="{{ route('admin.channels.toggle.featured', $channel->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 h-8 rounded-xl text-white shadow-lg flex items-center gap-1 transition-all hover:scale-105 border-0" style="background: {{ $channel->is_featured ? 'linear-gradient(to right, #f59e0b, #ea580c)' : 'rgba(148, 163, 184, 0.1)' }} !important; color: {{ $channel->is_featured ? 'white' : '#94a3b8' }} !important;" title="{{ $channel->is_featured ? 'Unfeature' : 'Feature' }}">
                                    <span class="material-symbols-rounded text-sm {{ $channel->is_featured ? 'fill-1' : '' }}">{{ $channel->is_featured ? 'grade' : 'star' }}</span>
                                    <span class="text-[8px] font-black uppercase tracking-tighter">{{ $channel->is_featured ? 'Featured' : 'Feature' }}</span>
                                </button>
                             </form>
                             <form action="{{ route('admin.channels.toggle.trending', $channel->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 h-8 rounded-xl text-white shadow-lg flex items-center gap-1 transition-all hover:scale-105 border-0" style="background: {{ $channel->is_trending ? 'linear-gradient(to right, #10b981, #0d9488)' : 'rgba(148, 163, 184, 0.1)' }} !important; color: {{ $channel->is_trending ? 'white' : '#94a3b8' }} !important;" title="{{ $channel->is_trending ? 'Untrend' : 'Trend' }}">
                                    <span class="material-symbols-rounded text-sm {{ $channel->is_trending ? 'fill-1' : '' }}">{{ $channel->is_trending ? 'trending_up' : 'trending_down' }}</span>
                                    <span class="text-[8px] font-black uppercase tracking-tighter">{{ $channel->is_trending ? 'Trending' : 'Trend' }}</span>
                                </button>
                             </form>
                             <a href="{{ route('admin.channels.show', $channel->id) }}" class="px-3 h-8 rounded-xl text-white shadow-lg flex items-center gap-1 transition-all hover:scale-105 border-0" style="background: linear-gradient(to right, #3b82f6, #2563eb) !important;" title="View Stats">
                                <span class="material-symbols-rounded text-sm">monitoring</span>
                                <span class="text-[8px] font-black uppercase tracking-tighter">Stats</span>
                             </a>
                        </div>
                    </div>

                    <!-- Middle Section: Visual and Info -->
                    <div class="flex flex-col sm:flex-row items-center gap-6 sm:gap-8 text-center sm:text-left">
                        <!-- Branding Box -->
                        <div class="relative flex-shrink-0">
                            <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-[2rem] p-1 bg-gradient-to-tr from-slate-200 to-slate-100 dark:from-white/10 dark:to-white/5 shadow-inner -rotate-3 group-hover:rotate-0 transition-transform">
                                <div class="w-full h-full rounded-[1.8rem] overflow-hidden border-4 border-white dark:border-[#1a1a1a] shadow-xl relative flex items-center justify-center bg-slate-100 dark:bg-white/5 text-slate-300 font-black text-2xl sm:text-3xl ">
                                    @php
                                        $channelName = $channel->name ?? $channel->channel_name;
                                        $words = explode(' ', $channelName);
                                        $initials = strtoupper(substr($words[0], 0, 1) . (count($words) > 1 ? substr(end($words), 0, 1) : ''));
                                    @endphp
                                    @if($channel->avatar)
                                        <img src="{{ getImage(getFilePath('channelAvatar').'/'.$channel->avatar) }}" class="w-full h-full object-cover">
                                    @elseif($channel->image)
                                        <img src="{{ getImage(getFilePath('channelProfile').'/'.$channel->image) }}" class="w-full h-full object-cover">
                                    @else
                                        {{ $initials }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Data Section -->
                        <div class="flex-grow min-w-0 w-full">
                            <h4 class="text-lg font-black text-slate-900 dark:text-white truncate uppercase tracking-tighter mb-2">{{ $channel->channel_name }}</h4>
                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mb-4">
                                <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-white/5 text-[9px] font-black text-slate-500 dark:text-white/30 uppercase tracking-widest truncate max-w-[120px]">Creator: @<span>{{ $channel->user->username }}</span></span>
                                @if($channel->status == 1)
                                    <span class="px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-[8px] font-black uppercase tracking-widest border border-emerald-500/20 ">Live Stream</span>
                                @else
                                    <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-white/5 text-slate-400 text-[8px] font-black uppercase tracking-widest border border-slate-200 dark:border-white/5 ">Offline</span>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-y-4 gap-x-4">
                                <div>
                                    <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">Subscribers</span>
                                    <span class="block text-[10px] font-bold text-slate-600 dark:text-white uppercase truncate">{{ number_format($channel->subscribers_count) }} Users</span>
                                </div>
                                <div>
                                    <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">Status</span>
                                    <span class="block text-[10px] font-bold text-slate-600 dark:text-white uppercase truncate">{{ $channel->is_active ? 'Live Network' : 'Disconnected' }}</span>
                                </div>
                                <div>
                                    <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">Category</span>
                                    <span class="block text-[10px] font-bold text-slate-600 dark:text-white uppercase truncate">Community</span>
                                </div>
                                <div>
                                    <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">Created</span>
                                    <span class="block text-[10px] font-bold text-slate-600 dark:text-white uppercase truncate">{{ $channel->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Row 1 -->
                    <div class="mt-8 pt-8 border-t border-slate-100 dark:border-white/5 flex flex-row w-full gap-2 sm:gap-3">
                         <a href="{{ route('admin.channels.show', $channel) }}" class="flex-1 h-12 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-white/40 flex flex-col items-center justify-center gap-1 text-[8px] font-black uppercase tracking-tight hover:bg-slate-200 dark:hover:bg-white/10 transition-all overflow-hidden">
                            <span class="material-symbols-rounded text-[14px]">visibility</span> <span class="truncate">Stats</span>
                        </a>
                        <a href="{{ route('admin.channels.edit', $channel) }}" class="flex-1 h-12 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-white/40 flex flex-col items-center justify-center gap-1 text-[8px] font-black uppercase tracking-tight hover:bg-slate-200 dark:hover:bg-white/10 transition-all overflow-hidden">
                            <span class="material-symbols-rounded text-[14px]">edit</span> <span class="truncate">Edit</span>
                        </a>
                        <form action="{{ route('admin.channels.destroy', $channel) }}" method="POST" class="flex-1 flex" data-swal-question="Immediately terminate this channel identity?">
                             @csrf
                             @method('DELETE')
                             <button type="submit" class="flex-1 w-full h-12 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-rose-500/60 dark:text-rose-500/30 flex flex-col items-center justify-center gap-1 text-[8px] font-black uppercase tracking-tight hover:bg-rose-500 hover:text-white transition-all overflow-hidden">
                                <span class="material-symbols-rounded text-[14px]">delete</span> <span class="truncate">Delete</span>
                            </button>
                        </form>
                    </div>

                    <!-- Actions Row 2 -->
                    <div class="mt-2 sm:mt-3 flex flex-row w-full gap-2 sm:gap-3">
                        <a href="javascript:void(0)" onclick="window.adminSwal({title:'System Alert',text:'Deep subscriber management and node injection tools will be unlocked in the final subscription module patch.',icon:'info'})" class="flex-1 h-12 rounded-xl bg-emerald-500 text-white flex flex-col items-center justify-center gap-1 text-[8px] font-black uppercase tracking-tight hover:opacity-90 active:scale-95 transition-all shadow-lg shadow-emerald-500/20 overflow-hidden">
                            <span class="material-symbols-rounded text-[14px]">rss_feed</span> <span class="truncate">Subscribe</span>
                        </a>
                        <a href="{{ route('admin.channels.show', $channel) }}" class="flex-1 h-12 rounded-xl bg-blue-500 text-white flex flex-col items-center justify-center gap-1 text-[8px] font-black uppercase tracking-tight hover:opacity-90 active:scale-95 transition-all shadow-lg shadow-blue-500/20 overflow-hidden">
                            <span class="material-symbols-rounded text-[14px]">account_balance_wallet</span> <span class="truncate">Analytics</span>
                        </a>
                    </div>

                    <!-- Actions Row 3 -->
                    <div class="mt-2 sm:mt-3 flex flex-row w-full gap-2 sm:gap-3">
                        <form action="{{ route('admin.channels.status', $channel) }}" method="POST" class="flex-1 flex">
                             @csrf
                             <button type="submit" class="flex-1 w-full h-12 rounded-xl border-2 {{ $channel->is_active ? 'border-rose-500/30 text-rose-500 hover:bg-rose-500' : 'border-emerald-500/30 text-emerald-500 hover:bg-emerald-500' }} hover:text-white flex flex-col items-center justify-center gap-1 text-[8px] font-black uppercase tracking-tight transition-all overflow-hidden">
                                <span class="material-symbols-rounded text-[14px]">{{ $channel->is_active ? 'block' : 'power' }}</span> <span class="truncate">{{ $channel->is_active ? 'Unapprove' : 'Approve' }}</span>
                            </button>
                        </form>
                        <button class="flex-1 h-12 rounded-xl border-2 border-slate-900/10 dark:border-white/10 text-slate-900 dark:text-white/40 hover:bg-slate-900 dark:hover:bg-white hover:text-white dark:hover:text-black flex flex-col items-center justify-center gap-1 text-[8px] font-black uppercase tracking-tight transition-all overflow-hidden">
                            <span class="material-symbols-rounded text-[14px]">cancel</span> <span class="truncate">Block</span>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-40 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[3.5rem]">
                 <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-6xl">tv_off</span>
                 <p class="mt-6 text-[11px] font-black text-slate-400 uppercase tracking-widest">No broadcast channels detected in system archives</p>
            </div>
            @endforelse
        </div>
    </div>

    <div class="p-8 border-t border-slate-100 dark:border-white/10 bg-slate-50/50 dark:bg-white/[0.01]">
        {{ $channels->links() }}
    </div>
</div>
@endsection

