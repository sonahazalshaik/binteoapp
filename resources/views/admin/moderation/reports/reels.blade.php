@extends('admin.layouts.app')

@section('panel')
<div x-data="{ 
    selectAll: false, 
    toggleAll() { 
        const checkboxes = document.querySelectorAll('.bulk-select-item');
        checkboxes.forEach(cb => { cb.checked = this.selectAll; });
    }
}" class="bg-white dark:bg-[#121212] rounded-[2rem] overflow-hidden border border-slate-200 dark:border-white/10 shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-700">
    
    @include('admin.components.header-toolbar', [
        'title' => $pageTitle ?? 'Reported Reels Dashboard',
        'items' => $reports
    ])

    <div class="px-6 pt-6">
        @include('admin.components.table-toolbar', ['showBulkActions' => false])
    </div>

    <!-- Table View -->
    <div class="overflow-x-auto px-6 pb-6" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Reporter</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Target Reel</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Reason</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Status</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($reports as $report)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 overflow-hidden flex items-center justify-center">
                                @if(@$report->user->image)
                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $report->user->image) }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('assets/images/avatar.png') }}'">
                                @else
                                    <span class="text-[10px] font-black uppercase ">{{ substr(@$report->user->firstname ?? 'G', 0, 1) }}</span>
                                @endif
                            </div>
                            <a href="{{ $report->user_id ? route('admin.users.detail', $report->user_id) : '#' }}" class="hover:opacity-70 transition-opacity">
                                <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase leading-none">{{ @$report->user->username ?? 'Guest' }}</span>
                                <p class="text-[9px] text-slate-400 uppercase tracking-widest mt-1">{{ diffForHumans($report->created_at) }}</p>
                            </a>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('reels.index', ['reel' => $report->reel->slug]) }}" target="_blank" class="flex items-center gap-4 group/video">
                            <div class="w-10 h-16 rounded-lg bg-slate-100 dark:bg-white/5 overflow-hidden relative shadow-sm">
                                <img src="{{ @$report->reel ? $report->reel->getThumbnailUrl() : asset('assets/images/default.jpg') }}" class="w-full h-full object-cover group-hover/video:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 group-hover/video:opacity-100 transition-opacity">
                                    <span class="material-symbols-rounded text-white text-sm">play_arrow</span>
                                </div>
                            </div>
                            <div class="min-w-0 max-w-[200px]">
                                <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase leading-tight line-clamp-1 group-hover/video:text-primary transition-colors">{{ @$report->reel->title }}</span>
                                <p class="text-[9px] text-slate-400 uppercase tracking-widest mt-1">Reel Source</p>
                            </div>
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-lg bg-rose-500/10 text-rose-500 text-[9px] font-black uppercase tracking-widest border border-rose-500/20">
                            {{ $report->reason }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($report->status == 0)
                            <span class="px-3 py-1 rounded-lg bg-amber-500/10 text-amber-500 text-[9px] font-black uppercase tracking-widest border border-amber-500/20">Pending</span>
                        @elseif($report->status == 1)
                            <span class="px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-[9px] font-black uppercase tracking-widest border border-emerald-500/20">Resolved</span>
                        @else
                            <span class="px-3 py-1 rounded-lg bg-rose-500/10 text-rose-500 text-[9px] font-black uppercase tracking-widest border border-rose-500/20">Rejected</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="h-8 px-3 rounded-xl bg-orange-500 text-white flex items-center justify-center gap-2 hover:opacity-90 transition-all shadow-lg shadow-orange-500/20 handle-btn" 
                            data-id="{{ $report->id }}" 
                            data-reason="{{ $report->reason }}"
                            data-feedback="{{ $report->admin_feedback }}"
                            data-video-title="{{ @$report->reel->title ?? 'Untitled Reel' }}"
                            data-video-thumb="{{ @$report->reel ? $report->reel->getThumbnailUrl() : asset('assets/images/default.jpg') }}">
                            <span class="material-symbols-rounded text-sm">gavel</span>
                            <span class="text-[9px] font-black uppercase tracking-tighter">Review</span>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="100%" class="py-20 text-center opacity-20 text-[11px] font-black uppercase tracking-widest">No reel violations reported.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- App View (Grid) -->
    <div class="px-6 pb-6" x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mt-6">
            @forelse($reports as $report)
            <div class="bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden relative group">
                <div class="relative h-64 overflow-hidden">
                    <img src="{{ @$report->reel ? $report->reel->getThumbnailUrl() : asset('assets/images/default.jpg') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6">
                        <span class="px-2 py-1 rounded-md bg-rose-500 text-white text-[8px] font-black uppercase tracking-widest shadow-lg shadow-rose-500/20 mb-2 inline-block">
                            {{ $report->reason }}
                        </span>
                        <h4 class="text-white font-black text-sm uppercase tracking-tighter line-clamp-2">{{ @$report->reel->title }}</h4>
                    </div>
                </div>

                <div class="p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center overflow-hidden border border-slate-200 dark:border-white/10 shadow-sm">
                            @if(@$report->user->image)
                                <img src="{{ getImage(getFilePath('userProfile') . '/' . $report->user->image) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-[10px] font-black uppercase opacity-50">{{ substr(@$report->user->firstname ?? 'G', 0, 1) }}</span>
                            @endif
                        </div>
                        <a href="{{ $report->user_id ? route('admin.users.detail', $report->user_id) : '#' }}" class="hover:opacity-70 transition-opacity">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Reporter</p>
                            <p class="text-[11px] font-black text-slate-900 dark:text-white uppercase ">{{ @$report->user->username ?? 'Guest' }}</p>
                        </a>
                        <div class="ml-auto text-right">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Status</p>
                            <p class="text-[9px] font-black {{ $report->status == 0 ? 'text-amber-500' : ($report->status == 1 ? 'text-emerald-500' : 'text-rose-500') }} uppercase tracking-tighter">
                                {{ $report->status == 0 ? 'Pending' : ($report->status == 1 ? 'Resolved' : 'Rejected') }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-6">
                        <button class="h-12 rounded-xl bg-orange-500 text-white text-[9px] font-black uppercase tracking-[0.2em] flex items-center justify-center gap-3 hover:opacity-90 transition-all shadow-xl shadow-orange-500/20 handle-btn"
                            data-id="{{ $report->id }}" 
                            data-reason="{{ $report->reason }}"
                            data-feedback="{{ $report->admin_feedback }}"
                            data-video-id="{{ $report->reel_id }}"
                            data-video-title="{{ @$report->reel->title }}"
                            data-video-thumb="{{ @$report->reel ? $report->reel->getThumbnailUrl() : '' }}">
                            Review <span class="material-symbols-rounded text-sm">gavel</span>
                        </button>
                        <a href="{{ route('reels.index', ['reel' => $report->reel->slug]) }}" target="_blank" class="h-12 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/60 text-[9px] font-black uppercase tracking-[0.2em] flex items-center justify-center gap-3 hover:bg-slate-200 transition-all">
                            Watch <span class="material-symbols-rounded text-sm">play_arrow</span>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-40 text-center opacity-20 text-[11px] font-black uppercase tracking-widest">No reel violations reported.</div>
            @endforelse
        </div>
    </div>

    <div class="p-8 border-t border-slate-100 dark:border-white/10 bg-slate-50/50 dark:bg-white/[0.01]">
        {{ $reports->links() }}
    </div>
</div>

@include('admin.moderation.reports.partial_modal')
@endsection

@push('script')
<script>
    (function($){
        "use strict";
        $('.handle-btn').on('click', function() {
            var modal = $('#handleModal');
            var data = $(this).data();
            var action = "{{ url('admin/moderation/reports/reels') }}/" + data.id + "/handle";
            
            $('#handleForm').attr('action', action);
            $('#report-reason').text(data.reason);
            
            $('#target-content-section').show();
            $('#video-title').html(data.videoTitle || 'Untitled Reel');
            $('#video-thumb').attr('src', data.videoThumb || '');
            
            modal.find('[name=feedback]').val(data.feedback);
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush

