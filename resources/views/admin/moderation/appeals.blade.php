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
        'title' => $pageTitle ?? 'Appeal Management System',
        'items' => $appeals
    ])

    <div class="px-6 pt-6">
        @include('admin.components.table-toolbar', ['showBulkActions' => false])
    </div>

    <!-- Table View -->
    <div class="overflow-x-auto px-6 pb-6" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Petitioner</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Appeal Content</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Submitted</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($appeals as $appeal)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 overflow-hidden flex items-center justify-center border border-slate-200 dark:border-white/10 shadow-sm">
                                @if(@$appeal->user->image)
                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $appeal->user->image) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-[10px] font-black uppercase ">{{ substr(@$appeal->user->firstname ?? 'U', 0, 1) }}</span>
                                @endif
                            </div>
                            <a href="{{ route('admin.users.detail', $appeal->user_id) }}" class="hover:opacity-70 transition-opacity">
                                <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase leading-none">{{ @$appeal->user->username }}</span>
                                <p class="text-[9px] text-slate-400 uppercase tracking-widest mt-1">Petitioner</p>
                            </a>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-[11px] text-slate-600 dark:text-white/60 line-clamp-1 max-w-[400px] ">"{{ $appeal->reason }}"</p>
                    </td>
                    <td class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-tighter">
                        {{ $appeal->created_at->diffForHumans() }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="h-8 px-4 rounded-xl bg-emerald-500 text-white flex items-center justify-center gap-2 hover:opacity-90 transition-all shadow-lg shadow-emerald-500/20 handle-btn" 
                            data-id="{{ $appeal->id }}" 
                            data-reason="{{ $appeal->reason }}"
                            data-feedback="{{ $appeal->admin_feedback }}">
                            <span class="material-symbols-rounded text-sm">balance</span>
                            <span class="text-[9px] font-black uppercase tracking-tighter">Review Appeal</span>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="100%" class="py-20 text-center opacity-20 text-[11px] font-black uppercase tracking-widest">No judicial petitions pending.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- App View (Grid) -->
    <div class="px-6 pb-6" x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mt-6">
            @forelse($appeals as $appeal)
            <div class="bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden relative group">
                <div class="p-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-16 h-16 rounded-[1.25rem] bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center overflow-hidden shadow-lg shadow-emerald-500/5">
                            @if(@$appeal->user->image)
                                <img src="{{ getImage(getFilePath('userProfile') . '/' . $appeal->user->image) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-emerald-500 text-xl font-black ">{{ substr(@$appeal->user->firstname ?? 'U', 0, 1) }}</span>
                            @endif
                        </div>
                        <a href="{{ route('admin.users.detail', $appeal->user_id) }}" class="hover:opacity-70 transition-opacity">
                            <h4 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tighter leading-none mb-2">{{ @$appeal->user->username }}</h4>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Justice Petitioner</p>
                        </a>
                        <div class="ml-auto text-right">
                            <span class="px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-[8px] font-black uppercase tracking-widest border border-emerald-500/20">
                                Appeal
                            </span>
                        </div>
                    </div>

                    <div class="bg-slate-50 dark:bg-white/[0.02] p-5 rounded-3xl border border-slate-100 dark:border-white/5 mb-8 min-h-[100px] flex items-center justify-center ">
                        <p class="text-[11px] text-slate-600 dark:text-white/50 text-center leading-relaxed">"{{ $appeal->reason }}"</p>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-rounded text-slate-300 text-sm">schedule</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $appeal->created_at->diffForHumans() }}</span>
                        </div>
                        <button class="h-12 px-6 rounded-2xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[9px] font-black uppercase tracking-[0.2em] flex items-center justify-center gap-3 hover:opacity-90 transition-all shadow-xl handle-btn"
                            data-id="{{ $appeal->id }}" 
                            data-reason="{{ $appeal->reason }}"
                            data-feedback="{{ $appeal->admin_feedback }}">
                            Open Docket <span class="material-symbols-rounded text-sm">balance</span>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-40 text-center opacity-20 text-[11px] font-black uppercase tracking-widest">No judicial petitions pending.</div>
            @endforelse
        </div>
    </div>

    <div class="p-8 border-t border-slate-100 dark:border-white/10 bg-slate-50/50 dark:bg-white/[0.01]">
        {{ $appeals->links() }}
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
            var action = "{{ url('admin/moderation/reports') }}/" + data.id + "/handle";
            $('#handleForm').attr('action', action);
            $('#report-reason').text(data.reason);
            modal.find('[name=feedback]').val(data.feedback);
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush

