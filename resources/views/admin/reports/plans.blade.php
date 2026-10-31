@extends('admin.layouts.app')

@section('panel')
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
                <div class="overflow-x-auto scrollbar-hide"><table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Plan Name')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Owner')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Buyer')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Price')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Purchased Date')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Actions')</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                @forelse ($purchasedPlans as $purchased)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <span class="px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">
                                                {{ __(@$purchased->plan->name ?? 'Deleted Plan') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            {{ __($purchased->owner?->fullname) }} <br>
                                            @if($purchased->owner_id)
                                                <a href="{{ route('admin.users.detail', $purchased->owner_id) }}">
                                                    <span>@</span>{{ $purchased->owner?->username }}
                                                </a>
                                            @else
                                                <span class="text-slate-400 ">@lang('System')</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            {{ __($purchased->user?->fullname) }} <br>
                                            <a href="{{ route('admin.users.detail', $purchased->user_id) }}">
                                                <span>@</span>{{ $purchased->user?->username }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70 ">
                                            {{ @$purchased->plan->price > 0 ? showAmount(@$purchased->plan->price) : 'FREE' }}
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            {{ showDateTime($purchased->created_at) }}<br>
                                            <span class="text-[9px] text-slate-400 uppercase tracking-widest">{{ diffForHumans($purchased->created_at) }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('admin.plan.videos.list', $purchased->plan_id) }}"
                                                    class="px-3 py-1.5 rounded-lg bg-blue-500/10 text-blue-600 text-[9px] font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all">
                                                    <i class="las la-video me-1"></i>@lang('Videos')
                                                </a>
                                                <a href="{{ route('admin.plan.playlist.list', $purchased->plan_id) }}"
                                                    class="px-3 py-1.5 rounded-lg bg-indigo-500/10 text-indigo-600 text-[9px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all">
                                                    <i class="las la-list me-1"></i>@lang('Playlists')
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center py-20" colspan="100%">
                                            <div class="flex flex-col items-center justify-center opacity-40">
                                                <span class="material-symbols-rounded text-5xl mb-2">assignment</span>
                                                <p class="text-[10px] font-black uppercase tracking-widest">No plan purchases recorded</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($purchasedPlans->hasPages())
                    <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                        {{ paginateLinks($purchasedPlans) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>


    </div>
@endsection



@push('breadcrumb-plugins')
    <x-search-form placeholder="Search Plan or Username" dateSearch='yes' />
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/daterangepicker.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/global/css/daterangepicker.css') }}">
@endpush

