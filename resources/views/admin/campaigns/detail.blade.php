@extends('admin.layouts.app')
@section('panel')
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
                <div class="overflow-x-auto scrollbar-hide"><table class="w-full text-left border-collapse">
                            @php
                                use Carbon\Carbon;
                            @endphp


                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Advertisement Name')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Start Date - End Date')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Ad Schedule Type')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Ad Reached')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Ad Engagement')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Ad Running')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Ad Type')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Daily Budget | Ad Cost')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Status')</th>
                                  
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                @forelse ($campaign->advertisements as $advertisement)
                                    @php
                                        $totalAdDays = 0;

                                        if ($advertisement->schedule_type == 1) {
                                            $start = Carbon::parse($advertisement->start_date);
                                            $end = Carbon::parse($advertisement->end_date);

                                            $totalAdDays += $start->diffInDays($end) + 1;
                                        }

                                        if ($advertisement->schedule_type == 2 && $advertisement->schedules) {
                                            foreach ($advertisement->schedules as $schedule) {
                                                $start = Carbon::parse($schedule->custom_start_date);
                                                $end = Carbon::parse($schedule->custom_end_date);

                                                $totalAdDays += $start->diffInDays($end) + 1;
                                            }
                                        }

                                    @endphp

                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">{{ __($advertisement->title) }}</td>

                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">{{ showDateTime($advertisement->start_date, 'Y-m-d') }} -
                                            {{ showDateTime($advertisement->end_date, 'Y-m-d') }}</td>

                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            @if ($advertisement->schedule_type == 1)
                                                @lang('Daily')
                                            @elseif ($advertisement->schedule_type == 2)
                                                @lang('Custom')
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            {{ formatNumber($advertisement->ad_reached) }} <br>
                                            <small class="text--success">@lang('Ads Reached'):
                                                {{ formatNumber($advertisement->adReaches()->count()) }} </small>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            {{ formatNumber($advertisement->ad_engagement) }} <br>
                                            <small class="text--success">@lang('Ads Engagement'):
                                                {{ formatNumber($advertisement->advertisementAnalytics()->count()) }}
                                            </small>
                                        </td>

                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">{{ round($totalAdDays) }} @lang('Days')</td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            @php
                                                echo $advertisement->adTypeBadge;
                                            @endphp
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            {{ showAmount($advertisement->daily_costs) }} <br>
                                            {{ showAmount($advertisement->total_amount) }}

                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            @php
                                                echo $advertisement->statusBadge;
                                            @endphp
                                        </td>
                                      
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                               <div class="button--group">
                                                <a class="btn btn-sm btn-outline--primary"
                                                    href="{{ route('admin.advance.ads.detail', $advertisement->id) }}"><i
                                                        class="las la-desktop"></i>@lang('Detail')</a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>


                        </table><!-- table end -->
                    </div>
                </div>
           
            </div><!-- card end -->
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder='Name' />
@endpush

