@extends('admin.layouts.app')

@section('title', 'Copyright Strike Management')
@section('header_title', 'Struck Creators')

@section('content')
<div class="bg-white dark:bg-[#121212] rounded-[2rem] overflow-hidden border border-slate-200 dark:border-white/10 shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-700">
    @include('admin.components.header-toolbar', [
        'title' => $pageTitle ?? 'Active Strikes Directory',
        'items' => $strikes,
    ])

    <div class="px-6 pt-6">
        @include('admin.components.table-toolbar', ['showBulkActions' => false])
    </div>

    <!-- App View (Cards) -->
    <div x-show="$store.viewMode.mode === 'app'" class="p-6 lg:p-8 animate-in fade-in duration-500">
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($strikes as $strike)
            <div class="group bg-slate-50/50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5 rounded-[2rem] p-6 hover:bg-white dark:hover:bg-white/[0.04] transition-all duration-500 shadow-sm hover:shadow-xl hover:shadow-black/5">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl overflow-hidden bg-slate-100 dark:bg-white/5 border border-white dark:border-white/10 shadow-sm">
                            @if(@$strike->user->channel->avatar)
                                <img src="{{ getImage(getFilePath('channelAvatar').'/'.$strike->user->channel->avatar) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-rose-500 to-orange-400 text-white text-[10px] font-black uppercase ">
                                    {{ substr($strike->user->firstname ?? 'U', 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <a href="{{ route('admin.users.detail', $strike->user_id) }}" class="hover:opacity-70 transition-opacity">
                            <h4 class="text-[11px] font-black text-slate-900 dark:text-white uppercase leading-none">{{ $strike->user->fullname ?? 'Unknown' }}</h4>
                            <p class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest mt-1">@ {{ $strike->user->username }}</p>
                        </a>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <span class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase tracking-widest bg-rose-500/10 text-rose-500 border border-rose-500/20">STRIKE</span>
                        <span class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase tracking-widest {{ $strike->status == 'active' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-slate-500/10 text-slate-500' }} border border-{{ $strike->status == 'active' ? 'emerald' : 'slate' }}-500/20 ">{{ strtoupper($strike->status) }}</span>
                    </div>
                </div>

                @if($strike->video)
                <div class="relative rounded-2xl overflow-hidden aspect-video mb-4 border border-slate-200 dark:border-white/5 shadow-inner group-hover:scale-[1.02] transition-transform duration-700">
                    <img src="{{ $strike->video->getThumbnailUrl() }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-4">
                        <h5 class="text-white text-[10px] font-black uppercase line-clamp-2">{{ $strike->video->title }}</h5>
                    </div>
                </div>
                @else
                <div class="aspect-video mb-4 rounded-2xl bg-slate-100 dark:bg-white/5 flex flex-col items-center justify-center border border-dashed border-slate-300 dark:border-white/10 opacity-50">
                    <span class="material-symbols-rounded text-2xl text-slate-400">videocam_off</span>
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-2">Content Erased</span>
                </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <span class="text-[8px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em] mb-1 block">Moderation Decision</span>
                        <p class="text-[10px] font-bold text-slate-600 dark:text-white/60 leading-relaxed line-clamp-2">{{ $strike->reason }}</p>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-white/5">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-slate-700 dark:text-white/70 ">{{ $strike->created_at->format('M d, Y') }}</span>
                            <span class="text-[8px] font-bold text-slate-400 dark:text-white/20 uppercase tracking-widest">{{ $strike->created_at->diffForHumans() }}</span>
                        </div>
                        
                        @if(in_array($strike->status, ['active', 'resolved']))
                        <button type="button" 
                                @click="$dispatch('open-confirmation-modal', { 
                                    title: 'Neutralize Strike?', 
                                    message: 'Remove this strike from creator {{ $strike->user->username }}?',
                                    action: '{{ route('admin.moderation.strikes.remove', $strike->id) }}',
                                    type: 'emerald'
                                })"
                                class="h-8 px-4 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center gap-2 hover:bg-emerald-500 hover:text-white transition-all shadow-sm active:scale-95">
                            <span class="material-symbols-rounded text-sm">remove_circle</span>
                            <span class="text-[9px] font-black uppercase tracking-tighter">Remove</span>
                        </button>
                        @else
                        <span class="text-[9px] font-black text-slate-300 dark:text-white/10 uppercase ">ARCHIVED</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center opacity-30">
                <span class="material-symbols-rounded text-6xl">shield_check</span>
                <p class="text-[10px] font-black uppercase mt-4">No violation records detected</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Table View -->
    <div x-show="$store.viewMode.mode === 'table'" class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Creator Identity</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Target Content</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Strike Logic (Reason)</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Chronology</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate text-right">Command Center</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($strikes as $strike)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-100 dark:bg-white/5 flex items-center justify-center font-bold text-slate-400">
                                @if(@$strike->user->channel->avatar)
                                    <img src="{{ getImage(getFilePath('channelAvatar').'/'.$strike->user->channel->avatar) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-rose-500 to-orange-400 text-white text-[10px] font-black uppercase ">
                                        {{ substr($strike->user->firstname ?? 'U', 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <a href="{{ route('admin.users.detail', $strike->user_id) }}" class="hover:opacity-70 transition-opacity">
                                <h4 class="text-[11px] font-black text-slate-900 dark:text-white uppercase leading-none">{{ $strike->user->fullname ?? 'Unknown User' }}</h4>
                                <p class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest mt-1">@ {{ $strike->user->username ?? 'deleted' }}</p>
                            </a>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($strike->video)
                            <div class="flex items-center gap-3">
                                <div class="w-14 h-8 rounded-lg overflow-hidden bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 shadow-sm relative group/thumb">
                                    <img src="{{ $strike->video->getThumbnailUrl() }}" class="w-full h-full object-cover group-hover/thumb:scale-110 transition-transform duration-500">
                                </div>
                                <div>
                                    <h5 class="text-[10px] font-black text-slate-700 dark:text-white/70 uppercase tracking-tighter line-clamp-1 max-w-[150px]">{{ $strike->video->title }}</h5>
                                    <span class="text-[8px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest">ID: #{{ $strike->video->id }}</span>
                                </div>
                            </div>
                        @else
                            <span class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest">CONTENT DELETED</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1.5">
                             <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase tracking-widest bg-rose-500/10 text-rose-500 border border-rose-500/20">STRIKE</span>
                                @if($strike->status == 'active')
                                    <span class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase tracking-widest bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 ">ACTIVE</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase tracking-widest bg-slate-500/10 text-slate-500 border border-slate-500/20 ">{{ $strike->status }}</span>
                                @endif
                            </div>
                            <p class="text-[10px] font-bold text-slate-600 dark:text-white/50 leading-snug line-clamp-2 max-w-[250px]">
                                {{ $strike->reason }}
                            </p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] font-black text-slate-700 dark:text-white/70 ">{{ $strike->created_at->format('M d, Y') }}</span>
                            <span class="text-[9px] font-bold text-slate-400 dark:text-white/20 uppercase tracking-widest">{{ $strike->created_at->diffForHumans() }}</span>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2 w-max ml-auto">
                             @if(in_array($strike->status, ['active', 'resolved']))
                                <button type="button" 
                                        @click="$dispatch('open-confirmation-modal', { 
                                            title: 'Neutralize Strike?', 
                                            message: 'Are you sure you want to remove this copyright strike from {{ $strike->user->username }}? This may restore their account access if they were auto-banned.',
                                            action: '{{ route('admin.moderation.strikes.remove', $strike->id) }}',
                                            type: 'emerald'
                                        })"
                                        class="h-9 px-4 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center gap-2 hover:bg-emerald-500 hover:text-white transition-all shadow-sm group">
                                     <span class="material-symbols-rounded text-lg">undo</span>
                                     <span class="text-[9px] font-black uppercase tracking-tighter">Remove Strike</span>
                                </button>
                             @else
                                <span class="text-[9px] font-black text-slate-300 dark:text-white/10 uppercase ">ARCHIVED</span>
                             @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="100%" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center justify-center gap-4">
                            <div class="w-16 h-16 rounded-[2rem] bg-slate-50 dark:bg-white/5 flex items-center justify-center">
                                <span class="material-symbols-rounded text-3xl text-slate-300 dark:text-white/10">shield_with_heart</span>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tighter">System Sanitized</h3>
                                <p class="text-[10px] font-bold text-slate-400 dark:text-white/20 uppercase tracking-widest mt-1">No active copyright violations detected in core network</p>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($strikes->hasPages())
    <div class="px-6 py-4 bg-slate-50/50 dark:bg-white/[0.02] border-t border-slate-100 dark:border-white/5">
        {{ paginateLinks($strikes) }}
    </div>
    @endif
</div>

{{-- Confirmation Modal --}}
<div x-data="{ 
    open: false, 
    title: '', 
    message: '', 
    action: '', 
    type: 'rose',
    init() {
        window.addEventListener('open-confirmation-modal', (e) => {
            this.title = e.detail.title;
            this.message = e.detail.message;
            this.action = e.detail.action;
            this.type = e.detail.type || 'rose';
            this.open = true;
        });
    }
}" x-show="open" class="fixed inset-0 z-[1000] flex items-center justify-center p-4" x-cloak>
    <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-md" @click="open = false"></div>
    <div class="relative w-full max-w-md bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] shadow-2xl border border-slate-100 dark:border-white/5 overflow-hidden animate-in zoom-in-95 duration-300">
        <div :class="type === 'emerald' ? 'bg-emerald-500' : 'bg-rose-500'" class="h-1.5 w-full"></div>
        <div class="p-8 sm:p-10 text-center">
            <div :class="type === 'emerald' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-rose-500/10 text-rose-500'" class="w-20 h-20 rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-inner">
                <span class="material-symbols-rounded text-4xl" x-text="type === 'emerald' ? 'verified' : 'warning'"></span>
            </div>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tighter mb-4" x-text="title"></h3>
            <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest leading-loose mb-10" x-text="message"></p>
            
            <div class="grid grid-cols-2 gap-4">
                <button @click="open = false" class="h-14 rounded-2xl bg-slate-100 dark:bg-white/5 text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-white/10 transition-all">
                    Cancel
                </button>
                <a :href="action" class="h-14 rounded-2xl flex items-center justify-center text-[10px] font-black text-white uppercase tracking-widest transition-all shadow-lg active:scale-95" :class="type === 'emerald' ? 'bg-emerald-500 shadow-emerald-500/20 hover:bg-emerald-600' : 'bg-rose-500 shadow-rose-500/20 hover:bg-rose-600'">
                    Confirm
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

