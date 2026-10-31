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
        'title' => $pageTitle ?? 'Reported Users Registry',
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
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Target User</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Violation Reason</th>
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
                                @if(@$report->user->channel->avatar)
                                    <img src="{{ getImage($report->user->channel->avatar) }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('assets/images/avatar.png') }}'">
                                @elseif(@$report->user->image)
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
                        <a href="{{ route('admin.users.detail', $report->reportedUser->id ?? 0) }}" class="flex items-center gap-3 group/target">
                            <div class="w-10 h-10 rounded-xl bg-rose-500/10 border border-rose-500/20 overflow-hidden flex items-center justify-center">
                                @if(@$report->reportedUser->channel->avatar)
                                    <img src="{{ getImage($report->reportedUser->channel->avatar) }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('assets/images/avatar.png') }}'">
                                @elseif(@$report->reportedUser->image)
                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $report->reportedUser->image) }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('assets/images/avatar.png') }}'">
                                @else
                                    <span class="text-rose-500 text-[10px] font-black uppercase ">{{ substr(@$report->reportedUser->firstname ?? 'U', 0, 1) }}</span>
                                @endif
                            </div>
                            <div>
                                <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase leading-none group-hover/target:text-rose-500 transition-colors">{{ @$report->reportedUser->username }}</span>
                                <p class="text-[9px] text-slate-400 uppercase tracking-widest mt-1">Target Identity</p>
                            </div>
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-lg bg-rose-500/10 text-rose-500 text-[9px] font-black uppercase tracking-widest border border-rose-500/20">
                                {{ $report->reason }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($report->status == 'pending')
                            <span class="px-3 py-1 rounded-lg bg-amber-500/10 text-amber-500 text-[9px] font-black uppercase tracking-widest border border-amber-500/20">Pending</span>
                        @elseif($report->status == 'resolved')
                            <span class="px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-[9px] font-black uppercase tracking-widest border border-emerald-500/20">Resolved</span>
                        @elseif($report->status == 'rejected')
                            <span class="px-3 py-1 rounded-lg bg-rose-500/10 text-rose-500 text-[9px] font-black uppercase tracking-widest border border-rose-500/20">Rejected</span>
                        @else
                            <span class="px-3 py-1 rounded-lg bg-slate-500/10 text-slate-500 text-[9px] font-black uppercase tracking-widest border border-slate-500/20">{{ ucfirst($report->status) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            @if($report->status == 'pending')
                            <form action="{{ route('admin.moderation.reports.handle', $report->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="h-8 px-3 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center gap-2 hover:bg-rose-500 hover:text-white transition-all shadow-sm" title="Reject Report">
                                    <span class="material-symbols-rounded text-sm">close</span>
                                    <span class="text-[9px] font-black uppercase tracking-tighter">Reject</span>
                                </button>
                            </form>
                            @endif
                            <button class="h-8 px-3 rounded-xl bg-orange-500 text-white flex items-center justify-center gap-2 hover:opacity-90 transition-all shadow-lg shadow-orange-500/20 handle-btn" 
                                data-id="{{ $report->id }}" 
                                data-reason="{{ $report->reason }}"
                                data-desc="{{ $report->description }}"
                                data-feedback="{{ $report->admin_feedback }}">
                                <span class="material-symbols-rounded text-sm">gavel</span>
                                <span class="text-[9px] font-black uppercase tracking-tighter">Review</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="100%" class="py-20 text-center opacity-20 text-[11px] font-black uppercase tracking-widest">No nodes detected in current segment.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- App View (Grid) -->
    <div class="px-6 pb-6" x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mt-6">
            @forelse($reports as $report)
            <div class="bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden relative group">
                <div class="p-8">
                    <div class="flex justify-between items-start mb-6">
                        <span class="px-3 py-1 rounded-lg bg-rose-500/10 text-rose-500 text-[8px] font-black uppercase tracking-widest border border-rose-500/20">
                            {{ $report->reason }}
                        </span>
                        @if($report->status == 'pending')
                            <span class="px-2 py-0.5 rounded-md bg-amber-500/10 text-amber-500 text-[7px] font-black uppercase tracking-widest border border-amber-500/20 flex items-center gap-1">
                                <span class="w-1 h-1 rounded-full bg-amber-500 animate-pulse"></span>
                                Pending
                            </span>
                        @elseif($report->status == 'resolved')
                            <span class="px-2 py-0.5 rounded-md bg-emerald-500/10 text-emerald-500 text-[7px] font-black uppercase tracking-widest border border-emerald-500/20 flex items-center gap-1">
                                <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                                Resolved
                            </span>
                        @elseif($report->status == 'rejected')
                            <span class="px-2 py-0.5 rounded-md bg-rose-500/10 text-rose-500 text-[7px] font-black uppercase tracking-widest border border-rose-500/20 flex items-center gap-1">
                                <span class="w-1 h-1 rounded-full bg-rose-500"></span>
                                Rejected
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center gap-6 mb-8">
                        <div class="relative">
                            <div class="w-20 h-20 rounded-[1.5rem] bg-rose-500/10 border-4 border-white dark:border-[#1a1a1a] shadow-xl overflow-hidden flex items-center justify-center">
                                @if(@$report->reportedUser->channel->avatar)
                                    <img src="{{ getImage($report->reportedUser->channel->avatar) }}" class="w-full h-full object-cover">
                                @elseif(@$report->reportedUser->image)
                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $report->reportedUser->image) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-rose-500 text-2xl font-black ">{{ substr(@$report->reportedUser->firstname ?? 'U', 0, 1) }}</span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('admin.users.detail', $report->reportedUser->id ?? 0) }}" class="flex-grow min-w-0 hover:opacity-70 transition-opacity">
                            <h4 class="text-lg font-black text-slate-900 dark:text-white truncate uppercase tracking-tighter mb-1">{{ @$report->reportedUser->username }}</h4>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Reported Identity</p>
                        </a>
                    </div>

                    <div class="bg-slate-50 dark:bg-white/[0.02] p-4 rounded-2xl border border-slate-100 dark:border-white/5 mb-6">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 rounded-lg bg-slate-200 dark:bg-white/5 flex items-center justify-center">
                                @if(@$report->user->image)
                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $report->user->image) }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <span class="text-[10px] font-black uppercase">{{ substr(@$report->user->firstname ?? 'G', 0, 1) }}</span>
                                @endif
                            </div>
                            <a href="{{ $report->user_id ? route('admin.users.detail', $report->user_id) : '#' }}" class="hover:opacity-70 transition-opacity">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Reporter</p>
                                <p class="text-[10px] font-bold text-slate-700 dark:text-white/70">{{ @$report->user->username ?? 'Guest' }}</p>
                            </a>
                        </div>
                        @if($report->description)
                            <p class="text-[10px] text-slate-500 dark:text-white/40 line-clamp-2 mt-2">"{{ $report->description }}"</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-6">
                        <button class="h-12 rounded-xl bg-orange-500 text-white text-[9px] font-black uppercase tracking-[0.2em] flex items-center justify-center gap-3 hover:opacity-90 transition-all shadow-xl shadow-orange-500/20 handle-btn"
                            data-id="{{ $report->id }}" 
                            data-reason="{{ $report->reason }}"
                            data-desc="{{ $report->description }}"
                            data-feedback="{{ $report->admin_feedback }}">
                            Review detail <span class="material-symbols-rounded text-sm">gavel</span>
                        </button>
                        <a href="{{ route('admin.users.detail', $report->reportedUser->id ?? 0) }}" class="h-12 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/60 text-[9px] font-black uppercase tracking-[0.2em] flex items-center justify-center gap-3 hover:bg-slate-200 transition-all">
                            Profile <span class="material-symbols-rounded text-sm">visibility</span>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-40 text-center opacity-20 text-[11px] font-black uppercase tracking-widest">No nodes detected in current segment.</div>
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
        $('.handle-btn').on('click', function(e) {
            e.preventDefault();
            $('.tooltip').remove();
            
            var modal = $('#handleModal');
            var id = $(this).data('id');
            var reason = $(this).data('reason');
            var desc = $(this).data('desc');
            var feedback = $(this).data('feedback');
            
            var fullReason = `<div class="fw-bold text-danger mb-1">${reason}</div>`;
            if (desc) {
                fullReason += `<div class="text-muted small mt-2 pt-2 border-top">${desc}</div>`;
            }

            modal.find('#report-reason').html(fullReason);
            modal.find('[name=feedback]').val(feedback);
            
            var actionUrl = "{{ route('admin.moderation.reports.handle', ':id') }}";
            modal.find('form').attr('action', actionUrl.replace(':id', id));
            
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush

