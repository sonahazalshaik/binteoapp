@extends('admin.layouts.app')

@section('panel')
<div class="max-w-[1600px] mx-auto animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-20">
    
    <!-- Header -->
    <div class="relative mb-12">
        <div class="absolute inset-0 bg-gradient-to-r from-rose-500/10 to-orange-500/10 blur-3xl rounded-[3rem]"></div>
        <div class="relative bg-white/40 dark:bg-black/20 backdrop-blur-3xl p-8 rounded-[2.5rem] border border-white/20 shadow-2xl flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase ">Keyword <span class="text-rose-500">Blacklist</span></h2>
                <p class="text-[10px] font-black text-slate-400 dark:text-white/30 uppercase tracking-[0.3em] mt-2">Manage restricted words for comments and content</p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-rose-500/10 flex items-center justify-center">
                    <span class="material-symbols-rounded text-3xl text-rose-500 fill-1">shield</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Add Keyword Form -->
        <div class="lg:col-span-1">
            <div class="bg-white/60 dark:bg-white/[0.03] backdrop-blur-xl border border-white/20 dark:border-white/5 rounded-[2.5rem] p-8 shadow-xl">
                <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight mb-6 flex items-center gap-3">
                    <span class="material-symbols-rounded text-orange-500">add_circle</span>
                    Add New Word
                </h3>
                
                <form action="{{ route('admin.setting.keyword.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Restricted Keyword</label>
                        <input type="text" name="word" placeholder="Enter word to block..." class="w-full bg-slate-100/50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl py-4 px-6 text-xs font-bold text-slate-900 dark:text-white outline-none focus:ring-4 focus:ring-rose-500/10 transition-all" required>
                    </div>
                    
                    <button type="submit" class="w-full py-4 rounded-2xl bg-gradient-to-r from-rose-600 to-orange-500 text-white text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-rose-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        Blacklist Keyword
                    </button>
                </form>

                <div class="mt-8 p-6 rounded-2xl bg-orange-500/5 border border-orange-500/10">
                    <div class="flex gap-4">
                        <span class="material-symbols-rounded text-orange-500">info</span>
                        <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 leading-relaxed uppercase tracking-wider">
                            Any content or comment containing these words will be automatically flagged or hidden depending on system sensitivity settings.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Keyword List -->
        <div class="lg:col-span-2">
            <div class="bg-white/60 dark:bg-white/[0.03] backdrop-blur-xl border border-white/20 dark:border-white/5 rounded-[2.5rem] p-8 shadow-xl">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">Active Blacklist</h3>
                    <span class="px-4 py-1.5 rounded-full bg-rose-500/10 text-rose-500 text-[10px] font-black uppercase tracking-widest">
                        {{ $keywords->count() }} Restricted Words
                    </span>
                </div>

                <div class="flex flex-wrap gap-3">
                    @forelse($keywords as $keyword)
                        <div class="group flex items-center gap-3 pl-5 pr-2 py-2 rounded-full bg-slate-100/50 dark:bg-white/5 border border-slate-200 dark:border-white/10 hover:border-rose-500/30 transition-all duration-300">
                            <span class="text-[11px] font-black text-slate-700 dark:text-white uppercase tracking-wider">{{ $keyword->word }}</span>
                            <form action="{{ route('admin.setting.keyword.delete', $keyword->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-full bg-white dark:bg-white/5 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-all">
                                    <span class="material-symbols-rounded text-base">close</span>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="w-full py-20 flex flex-col items-center justify-center border border-dashed border-slate-200 dark:border-white/10 rounded-[2rem]">
                            <span class="material-symbols-rounded text-5xl text-slate-200 dark:text-white/5">security_update_good</span>
                            <p class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.3em] mt-6 ">Blacklist is currently empty</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
    .fill-1 { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
</style>
@endpush

