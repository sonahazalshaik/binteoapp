@extends('admin.layouts.app')

@section('title', 'Comment Details')
@section('header_title', 'Comment Management')

@section('content')
<div class="space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-700 pb-24">
    <!-- Comment Inspection Hero -->
    <div class="relative bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-[3rem] p-10 lg:p-16 shadow-sm overflow-hidden group">
        <div class="relative z-10 flex flex-col lg:flex-row items-center gap-12">
            <div class="flex-grow text-center lg:text-left space-y-6">
                <div>
                    <h3 class="text-[11px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-[0.5em] mb-4">Metadata</h3>
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 mb-2">
                        <h2 class="text-4xl lg:text-5xl font-black tracking-tighter text-slate-900 dark:text-white uppercase">Comment #{{ $comment->id }}</h2>
                        <span class="px-4 py-1 rounded-xl bg-blue-500/10 text-blue-500 text-[10px] font-black uppercase tracking-[0.3em] border border-blue-500/20 ">Verified</span>
                    </div>
                    <p class="text-sm lg:text-lg font-bold text-slate-400 dark:text-white/30 uppercase tracking-widest mb-8">Author: {{ $comment->user->username ?? 'Unknown' }}</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-10">
                    <div class="bg-slate-50 dark:bg-white/[0.03] p-8 rounded-[2rem] border border-slate-100 dark:border-white/5">
                        <span class="material-symbols-rounded text-blue-500 text-3xl mb-4">person</span>
                        <h4 class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest mb-1">User Email</h4>
                        <p class="text-lg font-bold text-slate-900 dark:text-white truncate">{{ $comment->user->email ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-white/[0.03] p-8 rounded-[2rem] border border-slate-100 dark:border-white/5">
                        <span class="material-symbols-rounded text-cyan-500 text-3xl mb-4">movie</span>
                        <h4 class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest mb-1">On Video</h4>
                        <p class="text-lg font-bold text-slate-900 dark:text-white truncate">{{ $comment->video->title ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-center lg:justify-start gap-4">
                    <a href="{{ route('admin.comments.edit', $comment) }}" class="h-14 px-8 rounded-2xl bg-slate-900 dark:bg-white text-white dark:text-black flex items-center gap-3 text-xs font-black uppercase tracking-widest hover:scale-105 transition-all shadow-xl active:scale-95">
                        <span class="material-symbols-rounded">edit</span>
                        Edit Comment
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Payload Content -->
    <div class="bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-[3rem] p-10 lg:p-16 shadow-sm">
        <div class="flex items-center justify-between mb-10">
            <h3 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Comment Text</h3>
            <span class="material-symbols-rounded text-slate-300 dark:text-white/10 text-4xl">chat_bubble</span>
        </div>
        <div class="bg-slate-50 dark:bg-black/40 rounded-[2.5rem] p-12 lg:p-20 relative overflow-hidden">
             <div class="absolute top-0 right-0 p-8">
                <span class="material-symbols-rounded text-6xl text-slate-200 dark:text-white/5 -rotate-12">format_quote</span>
             </div>
             <p class="text-3xl lg:text-5xl font-black text-slate-900 dark:text-white tracking-tighter leading-tight relative z-10 transition-transform">
                "{{ $comment->comment }}"
             </p>
        </div>
    </div>

    <!-- Operations -->
    <div class="flex flex-col sm:flex-row items-center gap-6">
        <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" data-swal-question="Immediately delete this comment?" class="w-full">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full h-24 rounded-[2rem] bg-red-600 text-white flex items-center justify-center gap-4 text-sm font-black uppercase tracking-widest hover:bg-black hover:text-red-500 transition-all shadow-lg active:scale-95 group overflow-hidden relative">
                <span class="material-symbols-rounded text-2xl group-hover:rotate-180 transition-transform duration-700">delete</span>
                Delete Comment
            </button>
        </form>
        <a href="{{ route('admin.comments.index') }}" class="w-full sm:w-auto h-24 px-12 rounded-[2rem] border-2 border-slate-200 dark:border-white/10 flex items-center justify-center text-xs font-black uppercase tracking-widest text-slate-500 hover:text-red-600 transition-all">
            Back to List
        </a>
    </div>
</div>
@endsection

