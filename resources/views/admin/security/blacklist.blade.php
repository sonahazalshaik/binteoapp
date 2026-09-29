@extends('admin.layouts.app')

@section('title', 'Community Safety')
@section('header_title', 'Keyword Blacklist')

@section('panel')
<div class="max-w-[1600px] mx-auto animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-24 px-6 lg:px-0" x-data="{ search: '' }">
    
    <!-- Hero Header -->
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h3 class="text-4xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Security Shield</h3>
            <p class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.5em] mt-4 leading-relaxed">Manage your community's active keyword blacklist</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="px-6 py-4 rounded-3xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 shadow-sm">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Active Blocks</p>
                <p class="text-2xl font-black text-rose-500 leading-none">{{ $keywords->total() }} <span class="text-[10px] uppercase tracking-tighter">Keywords</span></p>
            </div>
            <div class="w-14 h-14 rounded-3xl bg-rose-500 text-white flex items-center justify-center shadow-lg shadow-rose-500/20">
                <span class="material-symbols-rounded text-2xl">security</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Add Keyword Panel -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white/80 dark:bg-[#121212]/80 backdrop-blur-3xl border border-white dark:border-white/10 rounded-[3rem] p-10 shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-rose-500/10 rounded-full blur-3xl group-hover:bg-rose-500/20 transition-all"></div>
                
                <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tighter mb-8 flex items-center gap-3">
                    <span class="material-symbols-rounded text-rose-500">add_moderator</span>
                    Expand Shield
                </h3>
                
                <form action="{{ route('admin.security.blacklist.store') }}" method="POST" class="space-y-8 relative z-10">
                    @csrf
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Restricted Phrase</label>
                        <div class="relative group/input">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 material-symbols-rounded text-slate-400 group-focus-within/input:text-rose-500 transition-colors">edit_note</span>
                            <input type="text" name="keyword" placeholder="Enter keyword..." class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/5 rounded-2xl py-5 pl-14 pr-6 text-sm font-bold text-slate-900 dark:text-white outline-none focus:border-rose-500/50 focus:ring-4 focus:ring-rose-500/5 transition-all" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full py-5 rounded-[2rem] bg-slate-900 dark:bg-white text-white dark:text-black text-[11px] font-black uppercase tracking-[0.3em] shadow-2xl hover:scale-[1.02] active:scale-95 transition-all relative overflow-hidden group/btn ">
                        <div class="absolute inset-0 bg-rose-600 opacity-0 group-hover/btn:opacity-100 transition-opacity"></div>
                        <span class="relative z-10">Add to Blacklist</span>
                    </button>
                </form>

                <div class="mt-12 p-8 rounded-[2rem] bg-rose-500/5 border border-rose-500/10 relative z-10">
                    <div class="flex gap-5">
                        <div class="w-10 h-10 rounded-xl bg-rose-500/10 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-rounded text-rose-500 text-lg">info</span>
                        </div>
                        <p class="text-[10px] font-bold text-slate-500 dark:text-white/30 leading-relaxed uppercase tracking-widest">
                            Protected content framework automatically filters any comment containing these restricted phrases to preserve community integrity.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Blacklist Explorer -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white/80 dark:bg-[#121212]/80 backdrop-blur-3xl border border-white dark:border-white/10 rounded-[3rem] p-10 shadow-2xl relative overflow-hidden">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                    <div>
                        <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tighter leading-none">Prohibited Library</h3>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-3">Visual repository of active filters</p>
                    </div>
                    
                    <div class="relative w-full md:w-80 group/search">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 material-symbols-rounded text-slate-400 group-focus-within/search:text-rose-500 transition-colors">search</span>
                        <input type="text" x-model="search" placeholder="Search blacklist..." class="w-full h-12 bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/5 rounded-2xl pl-12 pr-6 text-xs font-bold text-slate-900 dark:text-white outline-none focus:border-rose-500/50 transition-all">
                    </div>
                </div>

                <div class="relative">
                    <div class="flex flex-wrap gap-3 max-h-[600px] overflow-y-auto pr-4 scrollbar-hide pb-4">
                        @forelse($keywords as $k)
                        <div class="animate-in fade-in zoom-in duration-300"
                             x-show="search === '' || '{{ strtolower($k->keyword) }}'.includes(search.toLowerCase())">
                            <div class="flex items-center gap-3 pl-4 pr-2 py-2 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 hover:border-rose-500/50 hover:bg-rose-500/5 transition-all group/tag shadow-sm">
                                <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $k->keyword }}</span>
                                <form action="{{ route('admin.security.blacklist.destroy', $k->id) }}" method="POST" class="inline swal-action-form" 
                                      data-swal-title="Remove Filter?" 
                                      data-swal-text="This keyword will no longer be blocked.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-7 h-7 rounded-lg bg-slate-50 dark:bg-white/5 text-slate-400 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center">
                                        <span class="material-symbols-rounded text-sm">close</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div class="w-full py-20 text-center">
                            <div class="flex flex-col items-center justify-center opacity-30">
                                <span class="material-symbols-rounded text-7xl text-slate-200 dark:text-white/10">verified_user</span>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mt-8 leading-none">No active filters</p>
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <!-- Fade out at bottom if too many -->
                    <div class="absolute bottom-0 left-0 right-0 h-10 bg-gradient-to-t from-white dark:from-[#121212] to-transparent pointer-events-none"></div>
                </div>

                @if($keywords->hasPages())
                <div class="mt-10 pt-8 border-t border-slate-100 dark:border-white/5">
                    {{ $keywords->links() }}
                </div>
                @endif
            </div>
            
            <!-- Quick Actions / Templates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-3xl p-8 text-white shadow-xl shadow-indigo-500/20 relative overflow-hidden group">
                    <span class="material-symbols-rounded absolute -right-6 -bottom-6 text-9xl opacity-10 group-hover:scale-110 transition-transform">auto_fix_high</span>
                    <h4 class="text-lg font-black uppercase tracking-tighter mb-2 relative z-10">Smart Sync</h4>
                    <p class="text-[10px] font-bold opacity-60 uppercase tracking-widest leading-relaxed relative z-10">Automatically synchronize the latest prohibited word databases from global safety repositories.</p>
                </div>
                <div class="bg-gradient-to-br from-rose-600 to-orange-600 rounded-3xl p-8 text-white shadow-xl shadow-rose-500/20 relative overflow-hidden group">
                    <span class="material-symbols-rounded absolute -right-6 -bottom-6 text-9xl opacity-10 group-hover:scale-110 transition-transform">cleaning_services</span>
                    <h4 class="text-lg font-black uppercase tracking-tighter mb-2 relative z-10">Purge Library</h4>
                    <p class="text-[10px] font-bold opacity-60 uppercase tracking-widest leading-relaxed relative z-10">Reset the security framework by clearing all active keyword filters from the database.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endsection

