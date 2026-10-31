@extends('admin.layouts.app')

@section('title', 'Video Processing')
@section('header_title', 'System Status')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12 animate-in fade-in slide-in-from-bottom-6 duration-700">
    <!-- Configuration Panel -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white dark:bg-[#121212] p-6 rounded-[2rem] border border-slate-200 dark:border-white/10 shadow-sm relative overflow-hidden group">
            <div class="absolute -top-6 -right-10 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl"></div>
            <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter mb-6 flex items-center gap-3">
                <span class="material-symbols-rounded text-indigo-500">settings_input_component</span>
                Network Tiers
            </h3>
            
            <div class="space-y-3">
                @foreach($options as $option)
                <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-white/5 rounded-xl border border-slate-100 dark:border-white/5 hover:border-indigo-500/30 transition-all">
                    <div>
                        <p class="text-[12px] font-black text-slate-900 dark:text-white tracking-tight uppercase ">{{ $option->resolution }}</p>
                        <p class="text-[8px] font-bold text-slate-400 dark:text-white/20 uppercase tracking-widest mt-1">{{ number_format($option->bitrate) }} kbps Target</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.processing.edit', $option->id) }}" class="w-8 h-8 rounded-lg bg-white dark:bg-white/10 flex items-center justify-center text-slate-400 hover:text-indigo-500 border border-slate-100 dark:border-white/10 shadow-sm" title="Edit Parameters">
                            <span class="material-symbols-rounded text-base">edit</span>
                        </a>
                        <form action="{{ route('admin.processing.toggle-option', $option) }}" method="POST">
                            @csrf
                            <button class="relative inline-flex h-5 w-10 items-center rounded-full transition-colors {{ $option->is_enabled ? 'bg-indigo-600' : 'bg-slate-300 dark:bg-white/10' }}">
                                <span class="inline-block h-3 w-3 transform rounded-full bg-white transition-transform {{ $option->is_enabled ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8 p-4 bg-slate-50 dark:bg-indigo-500/5 rounded-xl border border-indigo-500/10">
                <p class="text-[8px] font-black text-indigo-500 uppercase tracking-[0.2em] leading-relaxed ">
                    Changes to quality parameters will strictly apply to future ingestions only.
                </p>
            </div>
        </div>

        <!-- Health Indicator -->
        <div class="bg-gradient-to-tr from-emerald-600 to-teal-500 p-6 rounded-[2rem] shadow-xl text-white relative overflow-hidden group">
            <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-white/20 rounded-full blur-3xl"></div>
            <div class="relative z-10 flex flex-col gap-6">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-md">
                    <span class="material-symbols-rounded text-xl fill-1">bolt</span>
                </div>
                <div>
                    <h4 class="text-base font-black tracking-tighter uppercase leading-none">Pulse Status: Online</h4>
                    <p class="text-[8px] font-black text-white/50 uppercase tracking-[0.3em] mt-3 ">All encoding nodes active</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Jobs -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm">
            <div class="p-6 border-b border-slate-100 dark:border-white/10 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Ingestion Queue</h2>
                    <p class="text-[9px] font-bold text-slate-500 dark:text-white/30 uppercase tracking-widest mt-1">Real-time video encoding and transformation logs</p>
                </div>
                <a href="{{ route('admin.processing.create') }}" class="h-10 px-8 rounded-xl orange-gradient-primary text-white flex items-center gap-2 text-[11px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-lg shadow-orange-500/20 active:scale-95 ">
                    <span class="material-symbols-rounded text-lg">add_circle</span>
                    New Tier
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-white/[0.02]">
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-slate-400">Content Context</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-slate-400 text-center">Status</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-slate-400 text-right">Operations</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($jobs as $job)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-20 aspect-video rounded-lg overflow-hidden bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex-shrink-0 transition-transform group-hover:scale-105">
                                        @if($job->video->thumbnail_path)
                                            <img src="{{ getImage($job->video->thumbnail_path) }}" class="w-full h-full object-cover">

                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[12px] font-black text-slate-900 dark:text-white tracking-tight truncate max-w-[200px] uppercase mb-1">{{ $job->video->title }}</p>
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 rounded bg-indigo-500/10 text-indigo-500 text-[7px] font-black uppercase tracking-widest">{{ $job->resolution }}</span>
                                            <p class="text-[8px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest ">Node: #{{ substr(md5($job->id), 0, 6) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full @if($job->status === 'completed') bg-emerald-500 @elseif($job->status === 'failed') bg-red-500 @else bg-amber-500 animate-pulse @endif"></span>
                                    <span class="text-[8px] font-black uppercase tracking-widest @if($job->status === 'completed') text-emerald-600 @elseif($job->status === 'failed') text-red-600 @else text-amber-600 @endif ">{{ $job->status }}</span>
                                </div>
                                @if($job->error_message)
                                <p class="text-[7px] font-bold text-red-500 line-clamp-1 mt-1 max-w-[120px] mx-auto ">{{ $job->error_message }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
        <a href="{{ Route::has('admin.processing.show') ? route('admin.processing.show', $option->id ?? 0) : (Route::has('admin.processing.detail') ? route('admin.processing.detail', $option->id ?? 0) : 'javascript:void(0)') }}" class="btn btn-sm btn-outline--info" title="@lang('View Details')">
            <i class="las la-desktop"></i>
        </a>
        <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ Route::has('admin.processing.destroy') ? route('admin.processing.destroy', $option->id ?? 0) : (Route::has('admin.processing.delete') ? route('admin.processing.delete', $option->id ?? 0) : 'javascript:void(0)') }}" data-question="@lang('Are you sure you want to delete this record?')" title="@lang('Delete')">
            <i class="las la-trash"></i>
        </button>
        
                                    <!-- VIEW: BLUE with Text -->
                                    <a href="{{ route('admin.processing.show', $job->id) }}" class="h-9 px-4 rounded-xl bg-blue-600 text-white flex items-center gap-2 text-[9px] font-black uppercase tracking-widest shadow-sm">
                                        <span class="material-symbols-rounded text-lg">monitoring</span>
                                        Log
                                    </a>

                                    @if($job->status === 'failed')
                                    <form action="{{ route('admin.processing.retry-job', $job) }}" method="POST">
                                        @csrf
                                        <button class="h-9 px-4 rounded-xl bg-emerald-500 text-white flex items-center gap-2 text-[9px] font-black uppercase tracking-widest shadow-sm">
                                            <span class="material-symbols-rounded text-lg">refresh</span>
                                            Retry
                                        </button>
                                    </form>
                                    @endif
                                    
                                    <!-- PURGE: RED with Text -->
                                    <form action="{{ route('admin.processing.destroy-job', $job) }}" method="POST" data-swal-question="Immediately delete this job record?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="h-9 px-4 rounded-xl bg-red-600 text-white flex items-center gap-2 text-[9px] font-black uppercase tracking-widest shadow-sm">
                                            <span class="material-symbols-rounded text-lg">delete</span>
                                            Purge
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-10 py-32 text-center text-slate-400 font-black uppercase tracking-widest text-[9px] opacity-20">System idle — No signals in ingestion queue</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($jobs->hasPages())
            <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-white/[0.01]">
                {{ $jobs->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

