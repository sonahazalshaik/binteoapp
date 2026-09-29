@extends('admin.layouts.app')
@section('title', 'Policy Violators')
@section('header_title', 'Policy Violations Database')

@section('panel')
<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300" x-data="{}">
    @include('admin.components.header-toolbar', [
        'title' => $pageTitle ?? 'Policy Violators',
        'items' => $users,
        'createRoute' => route('admin.users.create'),
        'createLabel' => 'Architect Node'
    ])

    <div class="px-6 pt-6">
        @include('admin.components.table-toolbar', ['showBulkActions' => false])
    </div>
    
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">@lang('User')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">@lang('Email & Mobile')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">@lang('Status')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate text-center">@lang('Active Strikes')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right truncate">@lang('Action')</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                        <td class="px-6 py-4">
                            <div class="text-[12px] font-black text-slate-900 dark:text-white uppercase leading-none mb-1">{{ __($user->fullname) }}</div>
                            <a href="{{ route('admin.users.detail', $user->id) }}" class="text-[10px] font-bold text-slate-500 dark:text-white/50 tracking-tight"><span>@</span>{{ $user->username }}</a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[11px] font-black text-slate-900 dark:text-white mb-1">{{ $user->email }}</div>
                            <div class="text-[10px] font-bold text-slate-400 tracking-wider">{{ $user->mobile }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->status == Status::USER_ACTIVE)
                                <span class="px-2 py-1 rounded-md bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase">Active</span>
                            @else
                                <span class="px-2 py-1 rounded-md bg-rose-50 dark:bg-rose-500/10 text-rose-500 text-[10px] font-black uppercase" title="{{ $user->ban_reason }}">Banned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($user->copyright_strikes_count > 0)
                                <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-rose-50 dark:bg-rose-500/10 text-rose-500 font-black text-[11px]">{{ $user->copyright_strikes_count }}</div>
                            @else
                                <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 font-black text-[11px]">0</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.detail', $user->id) }}" class="h-8 px-3 rounded-xl bg-indigo-500 text-white flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all active:scale-95 shadow-sm">
                                    <span class="material-symbols-rounded text-[14px]">desktop_windows</span> Details
                                </a>
                                @if($user->status == Status::USER_ACTIVE)
                                    <button type="button" class="h-8 px-3 rounded-xl bg-rose-500 text-white flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest hover:bg-rose-600 transition-all active:scale-95 shadow-sm" @click="$dispatch('open-ban-modal', { id: {{ $user->id }}, name: '{{ $user->username }}' })">
                                        <span class="material-symbols-rounded text-[14px]">block</span> Ban
                                    </button>
                                @else
                                    <button type="button" class="h-8 px-3 rounded-xl bg-emerald-500 text-white flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all active:scale-95 shadow-sm confirmationBtn" data-action="{{ route('admin.users.status', $user->id) }}" data-question="Are you sure you want to unban this user?">
                                        <span class="material-symbols-rounded text-[14px]">check_circle</span> Unban
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-10 py-32 text-center text-slate-400 font-black uppercase tracking-widest text-[9px] opacity-20">{{ __($emptyMessage ?? 'No policy violators found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($users->hasPages())
        <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
            {{ paginateLinks($users) }}
        </div>
    @endif
</div>

@include('admin.users.partials.modals')
@endsection

