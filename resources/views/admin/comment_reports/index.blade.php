@extends('admin.layouts.app')

@section('panel')
<div x-data="{ 
    activeTab: 'reports',
    selectAll: false, 
    toggleAll() { 
        const checkboxes = document.querySelectorAll('.bulk-select-item');
        checkboxes.forEach(cb => { cb.checked = this.selectAll; });
    }
}" class="bg-white dark:bg-[#121212] rounded-[2rem] overflow-hidden border border-slate-200 dark:border-white/10 shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-700">
    
    @include('admin.components.header-toolbar', [
        'title' => $pageTitle ?? 'Reported Comments Dashboard',
        'items' => $reports
    ])

    <div class="px-6 pt-6 flex gap-4 border-b border-slate-100 dark:border-white/5">
        <button @click="activeTab = 'reports'" 
                class="px-4 py-3 text-[11px] font-black uppercase tracking-widest transition-all border-b-2"
                :class="activeTab === 'reports' ? 'border-orange-500 text-orange-500' : 'border-transparent text-slate-400 hover:text-slate-900 dark:hover:text-white'">
            Reported Comments ({{ $reports->total() }})
        </button>
        <button @click="activeTab = 'blocked'" 
                class="px-4 py-3 text-[11px] font-black uppercase tracking-widest transition-all border-b-2"
                :class="activeTab === 'blocked' ? 'border-orange-500 text-orange-500' : 'border-transparent text-slate-400 hover:text-slate-900 dark:hover:text-white'">
            Blocked Users ({{ $blockedUsers->total() ?? 0 }})
        </button>
    </div>

    <div class="px-6 pt-6">
        @include('admin.components.table-toolbar', ['showBulkActions' => false])
    </div>

    <div x-show="activeTab === 'reports'">
        <!-- Table View -->
        <div class="overflow-x-auto px-6 pb-6" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-white/[0.02]">
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Reporter</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Comment Author</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Comment</th>
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
                                @if(@$report->reporter->image)
                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $report->reporter->image) }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('assets/images/avatar.png') }}'">
                                @else
                                    <span class="text-[10px] font-black uppercase ">{{ substr(@$report->reporter->firstname ?? 'G', 0, 1) }}</span>
                                @endif
                            </div>
                            <a href="{{ $report->user_id ? route('admin.users.detail', $report->user_id) : '#' }}" class="hover:opacity-70 transition-opacity">
                                <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase leading-none">{{ @$report->reporter->username ?? 'Guest' }}</span>
                                <p class="text-[9px] text-slate-400 uppercase tracking-widest mt-1">{{ diffForHumans($report->created_at) }}</p>
                            </a>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-white/5 overflow-hidden flex items-center justify-center border border-slate-200 dark:border-white/10 shadow-sm">
                                @if(@$report->comment->user->image)
                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $report->comment->user->image) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-[9px] font-black uppercase opacity-50">{{ substr(@$report->comment->user->firstname ?? 'U', 0, 1) }}</span>
                                @endif
                            </div>
                            <a href="{{ @$report->comment->user_id ? route('admin.users.detail', $report->comment->user_id) : '#' }}" class="hover:opacity-70 transition-opacity">
                                <p class="text-[10px] font-black text-slate-900 dark:text-white uppercase ">{{ @$report->comment->user->username ?? 'Deleted User' }}</p>
                            </a>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="min-w-0 max-w-[250px]">
                            @if($report->comment)
                                <span class="text-[11px] font-medium text-slate-700 dark:text-white/80 line-clamp-2" title="{{ $report->comment->comment }}">{{ $report->comment->comment }}</span>
                                @if($report->comment->video)
                                    <a href="{{ route('admin.videos.show', $report->comment->video_id) }}" target="_blank" class="text-[9px] text-orange-500 hover:text-orange-600 font-bold uppercase tracking-widest mt-1 inline-flex items-center gap-1">
                                        View Video <span class="material-symbols-rounded text-[10px]">open_in_new</span>
                                    </a>
                                @endif
                            @else
                                <span class="text-[11px] font-medium text-rose-500 ">Comment Deleted</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-lg bg-rose-500/10 text-rose-500 text-[9px] font-black uppercase tracking-widest border border-rose-500/20">
                            {{ $report->reason }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($report->status == 'pending')
                            <span class="px-3 py-1 rounded-lg bg-amber-500/10 text-amber-500 text-[9px] font-black uppercase tracking-widest border border-amber-500/20">Pending</span>
                        @else
                            <span class="px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-[9px] font-black uppercase tracking-widest border border-emerald-500/20">Reviewed</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($report->status == 'pending')
                            <div class="flex items-center justify-end gap-2">
                                @if($report->comment)
                                    <form action="{{ route('admin.comment.reports.delete.comment', $report->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="h-8 px-3 rounded-xl bg-rose-500 text-white flex items-center justify-center gap-2 hover:opacity-90 transition-all shadow-lg shadow-rose-500/20 confirmationBtn" data-question="Are you sure you want to delete this comment?">
                                            <span class="material-symbols-rounded text-sm">delete</span>
                                            <span class="text-[9px] font-black uppercase tracking-tighter">Delete Comment</span>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.comment.reports.destroy', $report->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="h-8 px-3 rounded-xl bg-slate-100 dark:bg-white/10 text-slate-600 dark:text-white flex items-center justify-center gap-2 hover:bg-slate-200 dark:hover:bg-white/20 transition-all confirmationBtn" data-question="Are you sure you want to dismiss this report?">
                                        <span class="material-symbols-rounded text-sm">close</span>
                                        <span class="text-[9px] font-black uppercase tracking-tighter">Dismiss</span>
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Action Taken</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="100%" class="py-20 text-center opacity-20 text-[11px] font-black uppercase tracking-widest">No reports in this frequency.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- App View (Grid) -->
    <div class="px-6 pb-6" x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mt-6">
            @forelse($reports as $report)
            <div class="bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden relative group p-8">
                
                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100 dark:border-white/5">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center overflow-hidden border border-slate-200 dark:border-white/10 shadow-sm">
                        @if(@$report->reporter->image)
                            <img src="{{ getImage(getFilePath('userProfile') . '/' . $report->reporter->image) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-[10px] font-black uppercase opacity-50">{{ substr(@$report->reporter->firstname ?? 'G', 0, 1) }}</span>
                        @endif
                    </div>
                    <a href="{{ $report->user_id ? route('admin.users.detail', $report->user_id) : '#' }}" class="hover:opacity-70 transition-opacity">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Reporter</p>
                        <p class="text-[11px] font-black text-slate-900 dark:text-white uppercase ">{{ @$report->reporter->username ?? 'Guest' }}</p>
                    </a>
                    <div class="ml-auto text-right">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Status</p>
                        <p class="text-[9px] font-black {{ $report->status == 'pending' ? 'text-amber-500' : 'text-emerald-500' }} uppercase tracking-tighter">{{ ucfirst($report->status) }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <span class="px-2 py-1 rounded-md bg-rose-500/10 text-rose-500 text-[8px] font-black uppercase tracking-widest border border-rose-500/20 mb-2 inline-block">
                        {{ $report->reason }}
                    </span>
                    
                    @if($report->comment)
                        <div class="bg-slate-50 dark:bg-white/[0.02] rounded-xl p-4 mt-2">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-[10px] font-black text-slate-900 dark:text-white">@ {{ @$report->comment->user->username ?? 'Deleted' }}</span>
                            </div>
                            <p class="text-[11px] text-slate-600 dark:text-white/70 ">"{{ strLimit($report->comment->comment, 100) }}"</p>
                        </div>
                    @else
                        <p class="text-[11px] font-medium text-rose-500 mt-2">Comment Deleted</p>
                    @endif
                </div>

                @if($report->status == 'pending')
                    <div class="grid grid-cols-2 gap-3 mt-auto">
                        @if($report->comment)
                            <form action="{{ route('admin.comment.reports.delete.comment', $report->id) }}" method="POST" class="col-span-1">
                                @csrf
                                <button type="submit" class="w-full h-12 rounded-xl bg-rose-500 text-white text-[9px] font-black uppercase tracking-[0.2em] flex items-center justify-center gap-2 hover:opacity-90 transition-all shadow-xl shadow-rose-500/20 confirmationBtn" data-question="Are you sure you want to delete this comment?">
                                    <span class="material-symbols-rounded text-sm">delete</span> Delete
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('admin.comment.reports.destroy', $report->id) }}" method="POST" class="{{ $report->comment ? 'col-span-1' : 'col-span-2' }}">
                            @csrf
                            <button type="submit" class="w-full h-12 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/60 text-[9px] font-black uppercase tracking-[0.2em] flex items-center justify-center gap-2 hover:bg-slate-200 dark:hover:bg-white/10 transition-all confirmationBtn" data-question="Are you sure you want to dismiss this report?">
                                <span class="material-symbols-rounded text-sm">close</span> Dismiss
                            </button>
                        </form>
                    </div>
                @else
                    <div class="h-12 flex items-center justify-center border-t border-slate-100 dark:border-white/5 mt-auto">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Action Taken</span>
                    </div>
                @endif
            </div>
            @empty
            <div class="col-span-full py-40 text-center opacity-20 text-[11px] font-black uppercase tracking-widest">No reports in this frequency.</div>
            @endforelse
            </div>
        </div>

        <div class="p-8 border-t border-slate-100 dark:border-white/10 bg-slate-50/50 dark:bg-white/[0.01]">
            {{ $reports->links() }}
        </div>
    </div>

    <!-- Blocked Users View -->
    <div x-show="activeTab === 'blocked'" style="display: none;">
        <!-- Table View -->
        <div class="overflow-x-auto px-6 pb-6" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-white/[0.02]">
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Blocking User</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Blocked User</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Offending Comment</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Date Blocked</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($blockedUsers as $blocked)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 overflow-hidden flex items-center justify-center">
                                    @if(@$blocked->blocker->image)
                                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $blocked->blocker->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-[10px] font-black uppercase ">{{ substr(@$blocked->blocker->firstname ?? 'U', 0, 1) }}</span>
                                    @endif
                                </div>
                                <a href="{{ @$blocked->user_id ? route('admin.users.detail', $blocked->user_id) : '#' }}" class="hover:opacity-70 transition-opacity">
                                    <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase leading-none">{{ @$blocked->blocker->username ?? 'Unknown' }}</span>
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 overflow-hidden flex items-center justify-center border border-slate-200 dark:border-white/10 shadow-sm">
                                    @if(@$blocked->blocked->image)
                                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $blocked->blocked->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-[10px] font-black uppercase opacity-50">{{ substr(@$blocked->blocked->firstname ?? 'U', 0, 1) }}</span>
                                    @endif
                                </div>
                                <a href="{{ @$blocked->blocked_user_id ? route('admin.users.detail', $blocked->blocked_user_id) : '#' }}" class="hover:opacity-70 transition-opacity">
                                    <p class="text-[11px] font-black text-slate-900 dark:text-white uppercase ">{{ @$blocked->blocked->username ?? 'Unknown' }}</p>
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($blocked->comment_text)
                                <span class="text-[11px] font-medium text-slate-700 dark:text-white/80 line-clamp-2" title="{{ $blocked->comment_text }}">{{ strLimit($blocked->comment_text, 100) }}</span>
                            @else
                                <span class="text-[10px] text-slate-400">Blocked via profile</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-medium text-slate-500">{{ showDateTime($blocked->created_at) }}</span>
                            <br>
                            <span class="text-[9px] text-slate-400 uppercase tracking-widest">{{ diffForHumans($blocked->created_at) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 rounded-full bg-slate-50 dark:bg-white/[0.02] flex items-center justify-center mb-4">
                                    <span class="material-symbols-rounded text-2xl text-slate-300 dark:text-white/20">block</span>
                                </div>
                                <h3 class="text-[13px] font-black text-slate-900 dark:text-white uppercase tracking-widest mb-1">No Blocked Users</h3>
                                <p class="text-[11px] font-medium text-slate-400">No users have been blocked yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Grid View -->
        <div class="px-6 pb-6" x-show="$store.viewMode && $store.viewMode.mode === 'app'" style="display: none;">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-6">
                @forelse($blockedUsers as $blocked)
                <div class="bg-white dark:bg-[#1A1A1A] rounded-2xl border border-slate-200 dark:border-white/10 p-5 hover:shadow-xl hover:shadow-slate-200/50 dark:hover:shadow-none transition-all group relative">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 overflow-hidden flex items-center justify-center">
                            @if(@$blocked->blocker->image)
                                <img src="{{ getImage(getFilePath('userProfile') . '/' . $blocked->blocker->image) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-[10px] font-black uppercase ">{{ substr(@$blocked->blocker->firstname ?? 'U', 0, 1) }}</span>
                            @endif
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 uppercase tracking-widest block mb-0.5">Blocking User</span>
                            <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase ">{{ @$blocked->blocker->username ?? 'Unknown' }}</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 mb-4 pt-4 border-t border-slate-100 dark:border-white/5">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 overflow-hidden flex items-center justify-center border border-slate-200 dark:border-white/10">
                            @if(@$blocked->blocked->image)
                                <img src="{{ getImage(getFilePath('userProfile') . '/' . $blocked->blocked->image) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-[10px] font-black uppercase opacity-50">{{ substr(@$blocked->blocked->firstname ?? 'U', 0, 1) }}</span>
                            @endif
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 uppercase tracking-widest block mb-0.5">Blocked User</span>
                            <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase ">{{ @$blocked->blocked->username ?? 'Unknown' }}</span>
                        </div>
                    </div>
                    
                    @if($blocked->comment_text)
                        <div class="mb-4 pt-4 border-t border-slate-100 dark:border-white/5">
                            <span class="text-[9px] text-slate-400 uppercase tracking-widest block mb-1">Offending Comment</span>
                            <p class="text-[11px] text-slate-600 dark:text-white/70 line-clamp-3">"{{ $blocked->comment_text }}"</p>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-slate-100 dark:border-white/5 flex items-center justify-between">
                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-400"><i class="las la-clock mr-1"></i>{{ diffForHumans($blocked->created_at) }}</span>
                    </div>
                </div>
                @empty
                <div class="col-span-full">
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="w-16 h-16 rounded-full bg-slate-50 dark:bg-white/[0.02] flex items-center justify-center mb-4">
                            <span class="material-symbols-rounded text-2xl text-slate-300 dark:text-white/20">block</span>
                        </div>
                        <h3 class="text-[13px] font-black text-slate-900 dark:text-white uppercase tracking-widest mb-1">No Blocked Users</h3>
                        <p class="text-[11px] font-medium text-slate-400">No users have been blocked yet.</p>
                    </div>
                </div>
                @endforelse
            </div>
            
            @if($blockedUsers->hasPages())
                <div class="mt-6 border-t border-slate-100 dark:border-white/5 pt-6">
                    {{ $blockedUsers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<x-confirmation-modal />
@endsection

