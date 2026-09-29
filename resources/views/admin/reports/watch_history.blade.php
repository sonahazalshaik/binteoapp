@extends('admin.layouts.app')

@section('panel')
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
                <div class="overflow-x-auto scrollbar-hide"><table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('User')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Role')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Content Type')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Title')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Watched At')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Actions')</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                @forelse ($logs as $log)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            {{ __($log->user?->fullname) }} <br>
                                            <a href="{{ route('admin.users.detail', $log->user_id) }}">
                                                <span>@</span>{{ $log->user?->username }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            @if($log->user?->creator_status)
                                                <span class="px-2 py-0.5 rounded-md bg-indigo-500/10 text-indigo-500 text-[8px] font-black uppercase tracking-widest">Creator</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-md bg-slate-500/10 text-slate-500 text-[8px] font-black uppercase tracking-widest">Regular</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            @if($log->reel_id)
                                                <span class="flex items-center gap-1.5 text-rose-500">
                                                    <span class="material-symbols-rounded text-lg">movie</span>
                                                    @lang('Reel')
                                                </span>
                                            @else
                                                <span class="flex items-center gap-1.5 text-blue-500">
                                                    <span class="material-symbols-rounded text-lg">play_circle</span>
                                                    @lang('Video')
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <span class="line-clamp-1">
                                                @if($log->reel_id)
                                                    {{ __($log->reel?->title ?? 'Deleted Reel') }}
                                                @else
                                                    {{ __($log->video?->title ?? 'Deleted Video') }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            {{ showDateTime($log->created_at) }}<br>
                                            <span class="text-[9px] text-slate-400 uppercase tracking-widest">{{ diffForHumans($log->created_at) }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            @if($log->video_id && $log->video)
                                                <a href="{{ route('videos.show', $log->video) }}" target="_blank"
                                                    class="btn btn-sm btn-outline--info">
                                                    <i class="las la-eye me-1"></i>@lang('View')
                                                </a>
                                            @elseif($log->reel_id && $log->reel)
                                                 <a href="{{ route('reels.show', $log->reel) }}" target="_blank"
                                                    class="btn btn-sm btn-outline--rose">
                                                    <i class="las la-eye me-1"></i>@lang('View')
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center py-20" colspan="100%">
                                            <div class="flex flex-col items-center justify-center opacity-40">
                                                <span class="material-symbols-rounded text-5xl mb-2">history</span>
                                                <p class="text-[10px] font-black uppercase tracking-widest">No watch history found</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($logs->hasPages())
                    <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                        {{ paginateLinks($logs) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>


    </div>
@endsection



@push('breadcrumb-plugins')
    <div class="flex flex-wrap items-center gap-2">
        <form action="" method="GET" class="flex flex-wrap items-center gap-2">
            <select name="role" class="form-control form-control-sm w-auto bg-white dark:bg-[#121212] border-slate-200 dark:border-white/10 text-[10px] font-black uppercase tracking-widest rounded-xl px-4 h-10" onchange="this.form.submit()">
                <option value="">@lang('All Roles')</option>
                <option value="regular" @selected(request()->role == 'regular')>@lang('Regular Users')</option>
                <option value="creator" @selected(request()->role == 'creator')>@lang('Creators')</option>
            </select>
        </form>
        <x-search-form placeholder="Username / Title" dateSearch='yes' />
    </div>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/daterangepicker.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/global/css/daterangepicker.css') }}">
@endpush
