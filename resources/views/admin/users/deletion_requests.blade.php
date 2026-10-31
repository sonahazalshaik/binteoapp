@extends('admin.layouts.app')
@section('title', $pageTitle)
@section('header_title', $pageTitle)

@section('panel')
<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
    @include('admin.components.header-toolbar', [
        'title' => $pageTitle,
        'items' => $requests,
        'createRoute' => null
    ])

    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">User Info</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Contact Details</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Reason</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Requested Date</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right truncate">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($requests as $req)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                        <td class="px-6 py-4">
                            @if($req->user)
                                <div class="text-[12px] font-black text-slate-900 dark:text-white uppercase leading-none mb-1">{{ __($req->user->fullname) }}</div>
                                <a href="{{ route('admin.users.detail', $req->user->id) }}" class="text-[10px] font-bold text-slate-500 dark:text-white/50 tracking-tight"><span>@</span>{{ $req->user->username }}</a>
                            @elseif($req->marketplace)
                                <div class="text-[12px] font-black text-slate-900 dark:text-white uppercase leading-none mb-1">{{ __($req->marketplace->name) }}</div>
                                <span class="text-[9px] font-black text-indigo-500 tracking-widest uppercase bg-indigo-500/10 px-1.5 py-0.5 rounded ">Talent: {{ $req->marketplace->type }}</span>
                            @else
                                <span class="text-xs text-rose-500 font-bold">User Not Found</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($req->user)
                                <div class="text-[11px] font-black text-slate-900 dark:text-white mb-1">{{ $req->user->email }}</div>
                                <div class="text-[10px] font-bold text-slate-400 tracking-wider">+{{ $req->user->dial_code }}{{ $req->user->mobile }}</div>
                            @elseif($req->marketplace)
                                <div class="text-[11px] font-black text-slate-900 dark:text-white mb-1">{{ $req->marketplace->email }}</div>
                                <div class="text-[10px] font-bold text-slate-400 tracking-wider">{{ $req->marketplace->number }}</div>
                            @else
                                <span class="text-slate-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-white/5 text-[10px] font-black text-slate-700 dark:text-white/80 uppercase">{{ $req->reason }}</span>
                            @if($req->custom_reason)
                                <p class="text-[10px] text-slate-500 mt-1 max-w-xs leading-relaxed ">"{{ $req->custom_reason }}"</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-[10px] font-bold text-slate-500 dark:text-white/40 ">
                            {{ $req->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('admin.users.deletion.approve', $req->id) }}" method="POST" class="swal-action-form inline"
                                      data-swal-title="Approve Deletion?" data-swal-text="This will delete the user account and all of their related videos, comments, and assets permanently! This action is irreversible.">
                                    @csrf
                                    <button type="submit" class="h-8 px-3 rounded-xl bg-rose-500 hover:bg-rose-600 text-white flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest transition-all active:scale-95 shadow-sm">
                                        <span class="material-symbols-rounded text-[14px]">delete_forever</span> Approve
                                    </button>
                                </form>

                                <form action="{{ route('admin.users.deletion.reject', $req->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="h-8 px-3 rounded-xl bg-slate-500 hover:bg-slate-600 text-white flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest transition-all active:scale-95 shadow-sm">
                                        <span class="material-symbols-rounded text-[14px]">cancel</span> Reject
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-10 py-32 text-center text-slate-400 font-black uppercase tracking-widest text-[9px] opacity-20">{{ __($emptyMessage) }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($requests->hasPages())
        <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
            {{ paginateLinks($requests) }}
        </div>
    @endif
</div>
@endsection

