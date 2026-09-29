@extends('admin.layouts.app')

@section('title', 'Copyright Management')
@section('header_title', 'Content Moderation')

@section('panel')
<div class="max-w-[1600px] mx-auto animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-20">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Issue Strike Form -->
        <div class="lg:col-span-1">
            <div class="bg-white/60 dark:bg-white/[0.03] backdrop-blur-xl border border-white dark:border-white/5 rounded-[2.5rem] p-8 shadow-xl">
                <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight mb-6 flex items-center gap-3">
                    <span class="material-symbols-rounded text-rose-500">gavel</span>
                    Issue Copyright Strike
                </h3>
                
                <form action="{{ route('admin.security.strikes.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Video ID</label>
                        <input type="number" name="video_id" placeholder="Enter Video ID..." class="w-full bg-slate-50 dark:bg-black/20 border-2 border-slate-300 dark:border-white/20 rounded-2xl py-4 px-6 text-xs font-bold text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-400 outline-none focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all shadow-sm" required>
                    </div>
                    
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Reason / Description</label>
                        <textarea name="reason" rows="4" placeholder="Describe the copyright violation..." class="w-full bg-slate-50 dark:bg-black/20 border-2 border-slate-300 dark:border-white/20 rounded-2xl py-4 px-6 text-[11px] font-bold text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-400 outline-none focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all resize-none shadow-sm" required></textarea>
                    </div>
                    
                    <button type="submit" class="w-full py-4 rounded-2xl bg-gradient-to-r from-rose-600 to-red-600 text-white text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-rose-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        Execute Strike
                    </button>
                </form>

                <div class="mt-8 p-6 rounded-2xl bg-rose-500/5 border border-rose-500/10">
                    <div class="flex gap-4">
                        <span class="material-symbols-rounded text-rose-500">warning</span>
                        <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 leading-relaxed uppercase tracking-wider">
                            IMPORTANT: 3 active strikes will automatically ban the user account. Strikes should only be issued after manual verification of the claim.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Strike History -->
        <div class="lg:col-span-2">
            <div class="bg-white/60 dark:bg-white/[0.03] backdrop-blur-xl border border-white dark:border-white/5 rounded-[2.5rem] p-8 shadow-xl">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">Strike History</h3>
                    <span class="px-4 py-1.5 rounded-full bg-rose-500/10 text-rose-500 text-[10px] font-black uppercase tracking-widest">
                        Live Moderation
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left border-b border-slate-100 dark:border-white/5">
                                <th class="pb-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">User / Creator</th>
                                <th class="pb-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Video Content</th>
                                <th class="pb-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                                <th class="pb-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($strikes as $strike)
                            <tr class="group">
                                <td class="py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center font-black text-slate-900 dark:text-white border border-white dark:border-white/10">
                                            {{ substr($strike->user->username, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $strike->user->username }}</p>
                                            <p class="text-[9px] font-bold text-slate-400 dark:text-white/20 uppercase tracking-widest mt-0.5">{{ $strike->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6">
                                    @if($strike->video)
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $strike->video->getThumbnailUrl() }}" class="w-12 h-8 rounded-lg object-cover shadow-lg">
                                        <div>
                                            <p class="text-[10px] font-black text-slate-700 dark:text-white/80 line-clamp-1 uppercase">{{ $strike->video->title }}</p>
                                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter mt-0.5">ID: #{{ $strike->video->id }}</p>
                                        </div>
                                    </div>
                                    @else
                                    <span class="text-[10px] font-bold text-slate-400 ">Content Deleted</span>
                                    @endif
                                </td>
                                <td class="py-6">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest 
                                        {{ $strike->status === 'active' ? 'bg-rose-500/10 text-rose-500' : 'bg-emerald-500/10 text-emerald-500' }}">
                                        {{ $strike->status }}
                                    </span>
                                </td>
                                <td class="py-6 text-right flex items-center justify-end gap-2" x-data="{}">
                                    @if(in_array($strike->status, ['active', 'resolved']))
                                    @if($strike->status === 'active')
                                    <form action="{{ route('admin.security.strikes.resolve', $strike->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="h-9 px-4 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-[9px] font-black text-slate-600 dark:text-white/60 uppercase tracking-widest hover:bg-emerald-500 hover:text-white hover:border-emerald-500 transition-all active:scale-95">
                                            Resolve
                                        </button>
                                    </form>
                                    @endif
                                    <button type="button" 
                                            @click="$dispatch('open-confirmation-modal', { 
                                                title: 'Neutralize Strike?', 
                                                message: 'Are you sure you want to remove this copyright strike? This may restore account access.',
                                                action: '{{ route('admin.moderation.strikes.remove', $strike->id) }}',
                                                type: 'rose'
                                            })"
                                            class="h-9 px-4 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center text-[9px] font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all active:scale-95">
                                        Remove
                                    </button>
                                    @else
                                    <span class="material-symbols-rounded text-emerald-500">check_circle</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-20 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-30">
                                        <span class="material-symbols-rounded text-6xl text-slate-200">shield_check</span>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-6 ">No copyright strikes recorded</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($strikes->hasPages())
                <div class="mt-8 pt-8 border-t border-slate-100 dark:border-white/5">
                    {{ $strikes->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
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

