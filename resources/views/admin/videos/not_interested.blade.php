@extends('admin.layouts.app')
@section('title', 'Not Interested Videos')
@section('header_title', 'Not Interested Feedbacks')

@section('content')
<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] shadow-sm transition-all duration-300">
    <div class="px-6 pt-6">
        <h4 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-widest mb-2">User Feedbacks</h4>
        <p class="text-xs text-slate-400 mb-6 uppercase tracking-[0.2em]">Videos marked as "Not Interested" by users</p>
    </div>
    
    <div class="overflow-x-auto scrollbar-hide px-6 pb-6">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('User')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Video')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Marked At')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right">@lang('Action')</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($interactions as $interaction)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-white/5 overflow-hidden">
                                <img src="{{ getImage(getFilePath('userProfile') . '/' . $interaction->user->image) }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('assets/images/avatar.png') }}'">
                            </div>
                            <div>
                                <a href="{{ route('admin.users.detail', $interaction->user_id) }}" class="font-bold text-sm text-slate-900 dark:text-white">
                                    {{ $interaction->user->username }}
                                </a>
                                <p class="text-[9px] text-slate-400">{{ $interaction->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-16 h-10 rounded-lg bg-slate-100 dark:bg-white/5 overflow-hidden">
                                <img src="{{ $interaction->video->getThumbnailUrl() }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="font-bold text-sm text-slate-900 dark:text-white line-clamp-1">{{ $interaction->video->title }}</p>
                                <span class="text-[9px] text-slate-400">ID: {{ $interaction->video->id }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-xs font-medium text-slate-500">
                        {{ showDateTime($interaction->created_at, 'd M, Y h:i A') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="btn btn-sm text-white confirmationBtn" style="background-color: #e11d48 !important;" 
                                data-action="{{ route('admin.videos.not-interested.destroy', $interaction->id) }}" 
                                data-method="POST" data-question="@lang('Restore this video to user feed?')">
                            <span class="material-symbols-rounded text-sm">delete</span>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="text-muted text-center py-10" colspan="100%">@lang('No data found')</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($interactions->hasPages())
        <div class="card-footer py-4 px-6 border-t border-slate-100 dark:border-white/5">
            {{ paginateLinks($interactions) }}
        </div>
    @endif
</div>

<x-confirmation-modal />
@endsection

