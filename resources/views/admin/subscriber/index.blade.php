@extends('admin.layouts.app')

@section('title', 'List of Channels')
@section('header_title', 'List of Channels')

@section('content')
<div x-data="{ 
    selectAll: false, 
    showSubscribersModal: false,
    activeChannel: {},
    expandedRows: [],
    toggleAll() { 
        const checkboxes = document.querySelectorAll('.bulk-select-item');
        checkboxes.forEach(cb => { cb.checked = this.selectAll; });
    },
    openSubscribers(channel) {
        this.activeChannel = channel;
        this.showSubscribersModal = true;
    },
    isExpanded(id) {
        return this.expandedRows.includes(id);
    },
    toggleRow(id) {
        if (this.isExpanded(id)) {
            this.expandedRows = this.expandedRows.filter(rowId => rowId !== id);
        } else {
            this.expandedRows.push(id);
        }
    }
}" class="bg-white dark:bg-[#121212] rounded-[2rem] overflow-hidden border border-slate-200 dark:border-white/10 shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-700">
    @include('admin.components.header-toolbar', [
        'title' => 'List of Channels',
        'items' => $channels
    ])

    <div class="px-6 pt-6">
        @include('admin.components.table-toolbar', ['showBulkActions' => false])
    </div>

    <!-- Table View -->
    <div class="overflow-x-auto table-responsive" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Channel</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Channel Owner</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate text-center">Subscribers</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate text-right">Status</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($channels as $channel)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-100 dark:bg-white/5 flex items-center justify-center font-bold text-slate-400">
                                @if($channel->avatar)
                                    <img src="{{ getImage(getFilePath('channelAvatar').'/'.$channel->avatar) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-indigo-500 to-purple-600 text-white text-[10px] font-black uppercase ">
                                        {{ substr($channel->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-[11px] font-black text-slate-900 dark:text-white uppercase leading-none">{{ $channel->name }}</h4>
                                <p class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest mt-1">{{ number_format(($channel->videos_count ?? 0) + ($channel->reels_count ?? 0)) }} Total Content</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-[11px] font-bold text-slate-700 dark:text-white/70 ">{{ $channel->user?->fullname }}</span>
                            <a href="{{ route('admin.users.detail', $channel->user_id) }}" class="text-[9px] font-black text-indigo-500 hover:text-indigo-400 transition-colors uppercase tracking-widest mt-1">
                                @<span>{{ $channel->user?->username }}</span>
                            </a>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button @click="openSubscribers({{ $channel }})" class="px-4 py-2 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 hover:border-indigo-500 transition-all group/btn">
                            <span class="text-[13px] font-black tracking-tighter text-slate-800 dark:text-white/90 group-hover/btn:text-indigo-500 transition-colors">{{ number_format($channel->subscribers_count) }}</span>
                        </button>
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($channel->is_active)
                            <span class="px-2.5 py-1 rounded-md bg-emerald-500/10 text-emerald-500 text-[9px] font-black uppercase tracking-widest border border-emerald-500/20">Operational</span>
                        @else
                            <span class="px-2.5 py-1 rounded-md bg-rose-500/10 text-rose-500 text-[9px] font-black uppercase tracking-widest border border-rose-500/20">Decoupled</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                             <button @click="toggleRow({{ $channel->id }})" class="h-8 px-3 rounded-xl bg-slate-200 dark:bg-white/10 text-slate-700 dark:text-white flex items-center justify-center gap-2 hover:bg-indigo-500 hover:text-white transition-all shadow-sm group" :class="isExpanded({{ $channel->id }}) ? 'bg-indigo-500 text-white' : ''">
                                 <span class="material-symbols-rounded text-sm" x-text="isExpanded({{ $channel->id }}) ? 'expand_less' : 'expand_more'"></span>
                                 <span class="text-[9px] font-black uppercase tracking-tighter hidden xl:block">Info</span>
                             </button>
                             <a href="{{ route('admin.users.detail', $channel->user_id) }}" class="h-8 px-3 rounded-xl bg-indigo-500 text-white flex items-center justify-center gap-2 hover:scale-105 transition-all shadow-sm group" title="View Owner Info">
                                 <span class="material-symbols-rounded text-sm">visibility</span>
                                 <span class="text-[9px] font-black uppercase tracking-tighter hidden xl:block">Owner</span>
                            </a>
                        </div>
                    </td>
                </tr>
                <tr x-show="isExpanded({{ $channel->id }})" x-collapse x-cloak>
                    <td colspan="100%" class="px-12 py-8 bg-slate-50/50 dark:bg-white/[0.01]">
                        <div class="flex flex-col md:flex-row gap-8">
                            <div class="flex-1">
                                <h6 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Channel Description</h6>
                                <p class="text-xs text-slate-600 dark:text-white/40 leading-relaxed ">
                                    {{ $channel->description ?? 'No custom description has been provided for this channel yet.' }}
                                </p>
                            </div>
                            <div class="grid grid-cols-2 gap-4 w-full md:w-64">
                                <div class="bg-white dark:bg-white/5 p-4 rounded-2xl border border-slate-100 dark:border-white/5">
                                    <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Signal Status</span>
                                    <span class="text-[10px] font-black text-emerald-500 uppercase">Operational</span>
                                </div>
                                <div class="bg-white dark:bg-white/5 p-4 rounded-2xl border border-slate-100 dark:border-white/5">
                                    <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Growth Impact</span>
                                    <span class="text-[10px] font-black text-indigo-500 uppercase">High</span>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="py-20 text-center opacity-20 text-[11px] font-black uppercase tracking-widest" colspan="100%">No signals detected in current segment.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Main Grid View (App View) -->
    <div class="px-6 pb-6" x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 mt-6">
            @forelse($channels as $channel)
            <div x-data="{ gridExpanded: false }" class="bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden relative group">
                <!-- Top Header Decor -->
                <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-orange-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="p-8">
                    <!-- Top Bar -->
                    <div class="flex justify-end items-start mb-6 gap-2">
                         <button @click="gridExpanded = !gridExpanded" class="h-8 w-8 rounded-lg bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 hover:bg-indigo-500 hover:text-white transition-all shadow-sm">
                            <span class="material-symbols-rounded text-base" x-text="gridExpanded ? 'expand_less' : 'expand_more'"></span>
                         </button>
                         <div class="px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-500 text-[8px] font-black uppercase tracking-widest border border-indigo-500/20 shadow-lg shadow-indigo-500/10">
                            Channel Overview
                         </div>
                    </div>

                    <!-- Profile Section -->
                    <div class="flex items-center gap-6 relative">
                        <div class="relative group">
                            <div class="w-24 h-24 rounded-[1.8rem] overflow-hidden border-4 border-white dark:border-[#1a1a1a] shadow-xl relative bg-slate-100 dark:bg-white/5 flex items-center justify-center">
                                @if($channel->avatar)
                                    <img src="{{ getImage(getFilePath('channelAvatar').'/'.$channel->avatar) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-indigo-500 to-purple-600 text-white text-3xl font-black uppercase ">
                                        {{ substr($channel->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <!-- Dynamic Status Badge -->
                            <div class="absolute -bottom-1 -right-1 w-8 h-8 rounded-xl {{ $channel->is_active ? 'bg-emerald-500 shadow-emerald-500/30' : 'bg-rose-500 shadow-rose-500/30' }} text-white flex items-center justify-center shadow-lg border-2 border-white dark:border-[#121212]">
                                <span class="material-symbols-rounded text-sm">{{ $channel->is_active ? 'check_circle' : 'block' }}</span>
                            </div>
                        </div>

                        <div class="flex-grow min-w-0">
                            <!-- Status Badge -->
                            <div class="mb-3 flex gap-2">
                                @if($channel->is_active)
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-500/10 text-emerald-500 text-[7px] font-black uppercase tracking-widest border border-emerald-500/20 flex items-center gap-1">
                                        <span class="w-1 h-1 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-md bg-rose-500/10 text-rose-500 text-[7px] font-black uppercase tracking-widest border border-rose-500/20 flex items-center gap-1">
                                        <span class="w-1 h-1 rounded-full bg-rose-500"></span>
                                        Inactive
                                    </span>
                                @endif
                            </div>
                            <h4 class="text-lg font-black text-slate-900 dark:text-white truncate uppercase tracking-tighter mb-1">{{ $channel->name }}</h4>
                            <div class="flex items-center gap-2">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Owner: {{ $channel->user?->username }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Expandable Grid Info -->
                    <div x-show="gridExpanded" x-collapse x-cloak class="mt-6 pt-6 border-t border-dashed border-slate-100 dark:border-white/5">
                        <h6 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">About Channel</h6>
                        <p class="text-[11px] text-slate-500 dark:text-white/30 leading-relaxed">
                            {{ $channel->description ?? 'Zero description pulses detected for this node.' }}
                        </p>
                    </div>

                    <!-- Metadata Row -->
                    <div class="grid grid-cols-2 gap-4 mt-6">
                        <button @click="openSubscribers({{ $channel }})" class="bg-slate-50 dark:bg-white/[0.02] p-3 rounded-xl border border-slate-100 dark:border-white/5 text-left hover:border-indigo-500 transition-all group/meta">
                            <span class="block text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1 group-hover/meta:text-indigo-500">Total Subscribers</span>
                            <span class="block text-sm font-black text-indigo-500 ">{{ number_format($channel->subscribers_count) }}</span>
                        </button>
                        <div class="bg-slate-50 dark:bg-white/[0.02] p-3 rounded-xl border border-slate-100 dark:border-white/5">
                            <span class="block text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Video Count</span>
                            <span class="block text-[11px] font-black text-slate-700 dark:text-white/60 ">{{ number_format(($channel->videos_count ?? 0) + ($channel->reels_count ?? 0)) }}</span>
                        </div>
                    </div>

                    <!-- Action Grid -->
                    <div class="mt-8 pt-8 border-t border-slate-100 dark:border-white/5 grid grid-cols-1 gap-3">
                        <a href="{{ route('admin.users.detail', $channel->user_id) }}" class="h-14 rounded-2xl bg-indigo-500/5 text-indigo-500 border border-indigo-500/10 flex items-center justify-center gap-3 hover:bg-indigo-500 hover:text-white transition-all shadow-sm group/btn" title="View Owner Details">
                            <span class="material-symbols-rounded text-lg group-hover/btn:scale-110 transition-transform">visibility</span>
                            <span class="text-[8px] font-black uppercase tracking-widest">View Owner Details</span>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-40 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[3.5rem]">
                 <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-6xl">person_off</span>
                 <p class="mt-6 text-[11px] font-black text-slate-400 uppercase tracking-widest ">No signals detected.</p>
            </div>
            @endforelse
        </div>
    </div>

    <div class="p-8 border-t border-slate-100 dark:border-white/10 bg-slate-50/50 dark:bg-white/[0.01]">
        {{ $channels->links() }}
    </div>

    <!-- Subscribers Modal -->
    <div x-show="showSubscribersModal" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
        <div @click.away="showSubscribersModal = false" 
             class="w-full max-w-lg max-h-[90vh] bg-white dark:bg-[#121212] rounded-[2.5rem] sm:rounded-[3rem] border border-slate-200 dark:border-white/10 shadow-2xl p-6 sm:p-12 relative overflow-hidden animate-in zoom-in-95 duration-300 flex flex-col">
            
            <!-- Decor -->
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative mb-8 flex items-center justify-between shrink-0">
                <div class="min-w-0 pr-4">
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tighter mb-1 truncate" x-text="activeChannel.name + ' Audience'"></h3>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Active subscriber list</p>
                </div>
                <button @click="showSubscribersModal = false" class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 hover:bg-rose-500 hover:text-white transition-all shrink-0">
                    <span class="material-symbols-rounded">close</span>
                </button>
            </div>

            <div class="relative flex-grow overflow-y-auto pr-2 sm:pr-4 scrollbar-orange touch-pan-y">
                <template x-if="activeChannel.subscribers && activeChannel.subscribers.length > 0">
                    <div class="space-y-3">
                        <template x-for="sub in activeChannel.subscribers" :key="sub.id">
                            <div class="flex items-center justify-between p-3 sm:p-4 rounded-2xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5 hover:border-indigo-500/30 transition-all group/sub">
                                <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center text-xs font-black shrink-0" x-text="sub.firstname.charAt(0)"></div>
                                    <div class="min-w-0">
                                        <h5 class="text-[11px] sm:text-[12px] font-black text-slate-700 dark:text-white/80 uppercase tracking-tight truncate" x-text="sub.firstname + ' ' + sub.lastname"></h5>
                                        <p class="text-[8px] sm:text-[9px] font-bold text-slate-400 dark:text-white/20 tracking-widest truncate" x-text="'@' + sub.username"></p>
                                    </div>
                                </div>
                                <a :href="'/admin/users/detail/' + sub.id" class="h-8 sm:h-10 px-3 sm:px-4 rounded-xl bg-white dark:bg-white/10 border border-slate-200 dark:border-white/5 flex items-center gap-2 text-[7px] sm:text-[8px] font-black uppercase tracking-widest text-slate-600 dark:text-white/60 hover:bg-indigo-500 hover:text-white transition-all opacity-0 sm:opacity-100 group-hover/sub:opacity-100 shrink-0">
                                    <span class="hidden sm:inline">Analyze</span> <span class="material-symbols-rounded text-sm">arrow_forward</span>
                                </a>
                            </div>
                        </template>
                    </div>
                </template>
                <template x-if="!activeChannel.subscribers || activeChannel.subscribers.length == 0">
                    <div class="py-20 text-center opacity-30">
                        <span class="material-symbols-rounded text-5xl">person_off</span>
                        <p class="text-[11px] font-black uppercase tracking-[0.3em] mt-4 ">No subscribers found</p>
                    </div>
                </template>
            </div>

            <div class="mt-8 shrink-0">
                <button @click="showSubscribersModal = false" class="w-full py-4 rounded-[1.2rem] sm:rounded-[1.5rem] bg-indigo-500 text-white text-[10px] font-black uppercase tracking-widest shadow-xl shadow-indigo-500/20 hover:scale-[1.02] active:scale-95 transition-all">Close Report</button>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    (function($) {
        "use strict";
        
        $(document).on('input', 'input[name=search]', function() {
            var $input = $(this);
            var query = $input.val();
            
            // Store cursor position and focus state
            localStorage.setItem('subscriber_search_focus', 'true');
            localStorage.setItem('subscriber_search_query', query);
            
            clearTimeout(window.searchTimeout);
            window.searchTimeout = setTimeout(function() {
                $input.closest('form').submit();
            }, 600);
        });

        // Restore focus and cursor position after reload
        $(document).ready(function() {
            if (localStorage.getItem('subscriber_search_focus') === 'true') {
                var $input = $('input[name=search]');
                var val = $input.val();
                $input.focus().val('').val(val); // Focus and move cursor to end
                localStorage.removeItem('subscriber_search_focus');
            }
        });

    })(jQuery);
</script>
@endpush

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection

