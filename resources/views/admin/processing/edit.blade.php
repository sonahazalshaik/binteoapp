@extends('admin.layouts.app')

@section('title', 'Refine Resolution Parameters')
@section('header_title', 'Modify Quality')

@section('content')
<div class="max-w-4xl mx-auto space-y-12 animate-in fade-in slide-in-from-bottom-10 duration-700 pb-24">
    <!-- Header -->
    <div class="flex items-center justify-between px-6 lg:px-0">
        <div>
            <h3 class="text-xs font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Modify Parameter</h3>
            <p class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest mt-3 leading-relaxed">Refining encoding parameters for #{{ $option->resolution }}</p>
        </div>
        <a href="{{ route('admin.processing.index') }}" class="w-12 h-12 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 dark:text-white/40 hover:bg-slate-900 hover:text-white transition-all shadow-xl active:scale-90">
            <span class="material-symbols-rounded">arrow_back</span>
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white/60 dark:bg-white/5 backdrop-blur-3xl border border-white dark:border-white/10 rounded-[2rem] p-6 shadow-2xl relative overflow-hidden group">
        <form action="{{ route('admin.processing.index') }}" method="POST" class="space-y-12">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Label -->
                <div class="space-y-4">
                    <label class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest ml-2">Tier Label</label>
                    <input type="text" name="resolution" value="{{ $option->resolution }}" required class="w-full h-16 px-8 rounded-xl bg-white dark:bg-black/40 border border-slate-200 dark:border-white/10 text-[12px] font-bold text-slate-900 dark:text-white outline-none focus:border-indigo-500 transition-all shadow-inner">
                </div>

                <!-- Bitrate -->
                <div class="space-y-4">
                    <label class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest ml-2">Target Bitrate (kbps)</label>
                    <input type="number" name="bitrate" value="{{ $option->bitrate }}" required class="w-full h-16 px-8 rounded-xl bg-white dark:bg-black/40 border border-slate-200 dark:border-white/10 text-[12px] font-bold text-slate-900 dark:text-white outline-none focus:border-indigo-500 transition-all shadow-inner">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-10 border-t border-slate-100 dark:border-white/5">
                <button type="submit" class="w-full h-12 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-black flex items-center justify-center gap-4 text-[12px] font-black uppercase tracking-widest hover:scale-[1.02] transition-all shadow-2xl active:scale-95 group overflow-hidden relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-orange-500 to-red-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <span class="material-symbols-rounded text-base relative z-10 ">sync_alt</span>
                    <span class="relative z-10 ">Sync Parameters</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection




