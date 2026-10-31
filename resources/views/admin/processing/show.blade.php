@extends('admin.layouts.app')

@section('title', 'Processing Job Oversight')
@section('header_title', 'Job Details')

@section('content')
<div class="max-w-5xl mx-auto space-y-12 animate-in fade-in slide-in-from-bottom-10 duration-700 pb-24">
    <!-- Header -->
    <div class="flex items-center justify-between px-6 lg:px-0">
        <div>
            <h3 class="text-xs font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Job Intelligence</h3>
            <p class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest mt-3 leading-relaxed">In-depth status analysis for encoding task #{{ substr(md5($job->id), 0, 8) }}</p>
        </div>
        <a href="{{ route('admin.processing.index') }}" class="w-12 h-12 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 dark:text-white/40 hover:bg-slate-900 hover:text-white transition-all shadow-xl active:scale-90">
            <span class="material-symbols-rounded">arrow_back</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Media Summary -->
        <div class="lg:col-span-2 space-y-10">
            <div class="bg-white/60 dark:bg-white/5 backdrop-blur-3xl border border-white dark:border-white/10 rounded-[2rem] p-6 shadow-2xl relative overflow-hidden group">
                 <div class="flex items-start gap-4">
                    <div class="w-48 aspect-video rounded-3xl overflow-hidden bg-slate-900 border border-slate-200 dark:border-white/10 shadow-2xl transition-transform group-hover:scale-105 duration-500">
                        @if($job->video->thumbnail_path)
                            <img src="{{ Storage::url($job->video->thumbnail_path) }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex-grow">
                        <h4 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter leading-tight">{{ $job->video->title }}</h4>
                        <div class="flex items-center gap-4 mt-4">
                            <span class="px-4 py-1 rounded-xl bg-gradient-to-r from-orange-500 to-red-600 text-white text-[9px] font-black uppercase tracking-widest shadow-lg shadow-indigo-600/20">Target: {{ $job->resolution }}</span>
                            <span class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest ">Video ID: #{{ $job->video->id }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Execution Logs -->
            <div class="bg-slate-900 rounded-[2rem] p-6 shadow-2xl border border-white/5 relative overflow-hidden">
                <div class="flex items-center justify-between mb-8">
                    <h5 class="text-xs font-black text-white uppercase tracking-tight ">Technical Logs</h5>
                    <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse shadow-lg shadow-emerald-500/50"></span>
                </div>
                <div class="space-y-4 font-mono text-[9px] text-emerald-400/60 leading-relaxed max-h-64 overflow-y-auto scrollbar-hide">
                    @if($job->status === 'completed')
                        <p class="text-emerald-400 font-bold opacity-100">[INFO] Job initiated at {{ $job->started_at }}</p>
                        <p>[INFO] Probing source file architecture...</p>
                        <p>[INFO] Mapping H.264 video streams...</p>
                        <p>[INFO] Transcoding sequence at {{ $job->resolution }} started</p>
                        <p>[INFO] Audio bitstream copy verified</p>
                        <p class="text-emerald-400 font-bold opacity-100">[SUCCESS] Final render completed in {{ differenceInHuman($job->started_at, $job->completed_at) }}</p>
                    @elseif($job->status === 'failed')
                        <p class="text-emerald-400 font-bold opacity-100">[INFO] Job initiated at {{ $job->started_at }}</p>
                        <p class="text-rose-500 font-black uppercase">[CRITICAL ERROR] {{ $job->error_message }}</p>
                        <p class="text-rose-400 opacity-60">[DEBUG] Server terminated encoding process ungracefully</p>
                    @else
                        <p class="text-amber-400 font-bold opacity-100">[WAITING] Task queued in system buffer...</p>
                        <p class="text-slate-500 ">Waiting for dispatcher to assign encoding node</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Task Info -->
        <div class="space-y-8">
            <div class="bg-white/40 dark:bg-black/20 backdrop-blur-3xl p-4 rounded-xl border border-slate-200 dark:border-white/10 shadow-2xl relative overflow-hidden group">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Process State</p>
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full @if($job->status === 'completed') bg-emerald-500 @elseif($job->status === 'failed') bg-rose-500 @else bg-amber-500 animate-pulse @endif shadow-lg"></span>
                    <h4 class="text-xl font-black text-slate-900 dark:text-white uppercase ">{{ $job->status }}</h4>
                </div>
                
                <div class="mt-8 space-y-6 pt-6 border-t border-slate-100 dark:border-white/5">
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Started</span>
                        <span class="text-xs font-black text-slate-900 dark:text-white ">{{ $job->started_at ? $job->started_at : 'Pending' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Finished</span>
                        <span class="text-xs font-black text-slate-900 dark:text-white ">{{ $job->completed_at ? $job->completed_at : '---' }}</span>
                    </div>
                </div>
            </div>

            @if($job->status === 'failed')
                <form action="{{ route('admin.processing.retry-job', $job) }}" method="POST">
                    @csrf
                    <button class="w-full h-12 rounded-3xl bg-gradient-to-r from-orange-500 to-red-600 text-white flex items-center justify-center gap-4 text-[9px] font-black uppercase tracking-[0.3em] hover:scale-[1.05] transition-all shadow-lg shadow-orange-500/20 active:scale-95 ">
                        <span class="material-symbols-rounded">refresh</span>
                        Relaunch Task
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection




