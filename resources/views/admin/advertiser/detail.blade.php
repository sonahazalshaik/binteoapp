@extends('admin.layouts.app')
@section('panel')
    <div class="max-w-[1600px] mx-auto space-y-10 pb-24">
        <div class="row justify-content-center gy-4">
            <div class="col-12">
                <div class="row gy-4">
                    @if (gs('ads_module'))
                        <div class="col-xxl-3 col-sm-6">
                            <x-widget style="7"
                                link="{{ route('admin.campaign.index', $user->id) }}"
                                title="Total Campaigns" icon="las la-bullhorn" value="{{ $totalCampaign }}" bg="indigo"
                                type="2" />
                        </div>
                    @endif

                    <div class="col-xxl-3 col-sm-6">
                            <x-widget style="7"
                                :link="gs('ads_module') 
                                    ? route('admin.advance.ads.index', $user->id) . '?search=' . $user->username 
                                    : route('admin.advertisement.index', $user->id) . '?search=' . $user->username"
                                title="Total Advertisements" icon="las la-ad" value="{{ $widget['total_ads'] }}" bg="indigo" type="2" />
                        </div>

                        <div class="col-xxl-3 col-sm-6">
                            <x-widget style="7"
                                :link="gs('ads_module') 
                                    ? route('admin.advance.ads.running', $user->id) . '?search=' . $user->username 
                                    : route('admin.advertisement.running', $user->id) . '?search=' . $user->username"
                                title="Running Advertisements" icon="las la-pause" value="{{ $widget['running_ads'] }}" bg="8" type="2" />
                        </div>

                        <div class="col-xxl-3 col-sm-6">
                            <x-widget style="7"
                                :link="gs('ads_module') 
                                    ? route('admin.advance.ads.pause', $user->id) . '?search=' . $user->username 
                                    : route('admin.advertisement.pause', $user->id) . '?search=' . $user->username"
                                title="Pause Advertisements" icon="la la-play" value="{{ $widget['pause_ads'] }}" bg="6" type="2" />
                        </div>

                    @if (!gs('ads_module'))
                        <div class="col-xxl-3 col-sm-6">
                            <x-widget style="7"
                                link="{{ route('admin.advertisement.click', $user->id) }}?search={{ $user->username }}"
                                title="Clickable Advertisements" icon="las la-mouse" value="{{ $widget['click_ads'] }}"
                                bg="17" type="2" />
                        </div>
                        <div class="col-xxl-3 col-sm-6">
                            <x-widget style="6"
                                link="{{ route('admin.advertisement.impression', $user->id) }}?search={{ $user->username }}"
                                title="Impresision Advertisements" icon="las la-eye" value="{{ $widget['impressions_ads'] }}"
                                bg="success" type="2" />
                        </div>
                        <div class="col-xxl-3 col-sm-6">
                            <x-widget style="6"
                                link="{{ route('admin.advertisement.both', $user->id) }}?search={{ $user->username }}"
                                title="Both Type Advertisements" icon="las la-video" value="{{ $widget['both_type_ads'] }}"
                                bg="8" type="2" />
                        </div>
                        <div class="col-xxl-3 col-sm-6">
                            <x-widget style="6"
                                link="{{ route('admin.report.transaction', $user->id) }}?search={{ $user->username }}"
                                title="Total Spent Amount" icon="la la-usd" value="{{ showAmount($widget['total_spent_amount']) }}"
                                bg="6" type="2" />
                        </div>
                        <div class="col-xxl-3 col-sm-6">
                            <x-widget style="6"
                                link="{{ route('admin.report.transaction', $user->id) }}?search={{ $user->username }}"
                                title="Last Seven Days Spent Amount" icon="las la-money-bill-wave-alt"
                                value="{{ showAmount($widget['last_seven_days_spent']) }}" bg="17" type="2" />
                        </div>
                    @endif

                    @if(gs('ads_module'))
                       <div class="col-xxl-3 col-sm-6">
                            <x-widget style="6"
                                link="{{route('admin.advance.ads.index', $user->id) }}"
                                title="Daily Scheduled Ads" icon="la la-sync" value="{{$dailyAds}}"
                                bg="2" type="2" />
                        </div>
                        <div class="col-xxl-3 col-sm-6">
                            <x-widget style="6"
                                link="{{route('admin.advance.ads.index', $user->id) }}"
                                title="Custom Scheduled Ads" icon="la la-chart-area" value="{{$customAds }}"
                                bg="1" type="2" />
                        </div>
                        <div class="col-xxl-3 col-sm-6">
                            <x-widget style="6"
                                link="{{route('admin.advance.ads.index', $user->id) }}"
                                title="Total Ads Budget" icon="la la-usd" value="{{ showAmount($totalBudget) }}"
                                bg="6" type="2" />
                        </div>
                        <div class="col-xxl-3 col-sm-6">
                            <x-widget style="6"
                                link="{{route('admin.advance.ads.index', $user->id) }}"
                                title="Available Budget" icon="las la-money-bill-wave-alt"
                                value="{{ showAmount($availableBudget) }}" bg="17" type="2" />
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card bg-white/60 dark:bg-black/20 backdrop-blur-3xl border border-slate-200 dark:border-white/10 rounded-[2.5rem] shadow-2xl overflow-hidden transition-all duration-500">
                    <div class="card-header d-flex justify-content-between p-8 border-b border-slate-100 dark:border-white/5">
                        <h4 class="text-[14px] font-black text-slate-900 dark:text-white uppercase tracking-tighter">@lang('Report for Impressions & Click')</h4>
                        <div class="border p-2 cursor-pointer rounded-xl bg-slate-50 dark:bg-white/5 border-slate-100 dark:border-white/10 chart-title-text flex items-center gap-2" id="dataPicker">
                            <i class="la la-calendar text-orange-500"></i>
                            <span class="text-[11px] font-bold"></span> <i class="la la-caret-down opacity-30"></i>
                        </div>
                    </div>
                    <div class="card-body p-8">
                        <div class="adsReport"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card bg-white/60 dark:bg-black/20 backdrop-blur-3xl border border-slate-200 dark:border-white/10 rounded-[2.5rem] shadow-2xl overflow-hidden transition-all duration-500">
                    <div class="card-header p-8 border-b border-slate-100 dark:border-white/5">
                        <h5 class="text-[14px] font-black text-slate-900 dark:text-white uppercase tracking-tighter">@lang('Advertiser Information')</h5>
                    </div>
                    <div class="card-body p-8">
                        @if ($user->advertiser_data)
                            <ul class="space-y-4">
                                @foreach ($user->advertiser_data as $val)
                                    @continue(!$val->value)
                                    <li class="flex flex-col gap-1.5 p-4 bg-slate-50 dark:bg-black/40 rounded-2xl border border-slate-100 dark:border-white/5">
                                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 ">{{ __($val->name) }}</span>
                                        <div class="text-[12px] font-bold text-slate-900 dark:text-white">
                                            @if ($val->type == 'checkbox')
                                                {{ implode(',', $val->value) }}
                                            @elseif($val->type == 'file')
                                                @if ($val->value)
                                                    <a href="{{ route('admin.download.attachment', encrypt(getFilePath('verify') . '/' . $val->value)) }}" class="flex items-center gap-2 text-orange-500 hover:text-orange-600 transition-colors">
                                                        <i class="fa-regular fa-file"></i> @lang('Attachment') 
                                                    </a>
                                                @else
                                                    <span class="text-slate-400">@lang('No File')</span>
                                                @endif
                                            @else
                                                {{ __($val->value) }}
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center py-12 opacity-30">
                                <span class="material-symbols-rounded text-4xl mb-2">folder_off</span>
                                <h5 class="text-[11px] font-black uppercase tracking-widest">@lang('Data not found')</h5>
                            </div>
                        @endif

                        @if ($user->advertiser_status == Status::ADVERTISER_REJECTED)
                            <div class="mt-8 p-6 bg-red-500/5 border border-red-500/10 rounded-2xl">
                                <h6 class="text-[10px] font-black uppercase tracking-widest text-red-500 mb-3 flex items-center gap-2">
                                    <span class="material-symbols-rounded text-[14px]">history</span>
                                    @lang('Rejection Reason')
                                </h6>
                                <p class="text-[12px] font-medium text-red-900/70 dark:text-red-400/70">{{ $user->advertiser_rejection_reason }}</p>
                            </div>
                        @endif

                        @if ($user->advertiser_status == Status::ADVERTISER_PENDING)
                            <div class="flex gap-4 mt-10">
                                <button class="flex-grow h-12 rounded-xl border border-red-500/20 text-red-500 font-black uppercase tracking-widest text-[10px] hover:bg-red-500 hover:text-white transition-all shadow-sm active:scale-95" data-bs-toggle="modal" data-bs-target="#advertiserRejectionModal">
                                    <span class="flex items-center justify-center gap-2">
                                        <span class="material-symbols-rounded text-lg">block</span>
                                        @lang('Reject')
                                    </span>
                                </button>
                                <button class="flex-grow h-12 rounded-xl bg-emerald-500 text-white font-black uppercase tracking-widest text-[10px] shadow-lg shadow-emerald-500/20 active:scale-95 transition-all confirmationBtn" data-question="@lang('Are you sure to approve this documents?')" data-action="{{ route('admin.advertiser.data.approve', $user->id) }}">
                                    <span class="flex items-center justify-center gap-2">
                                        <span class="material-symbols-rounded text-lg">check</span>
                                        @lang('Approve')
                                    </span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div id="advertiserRejectionModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content bg-white dark:bg-[#121212] rounded-[3rem] border-none shadow-2xl overflow-hidden">
                    <div class="p-8 sm:p-12">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-red-500/10 text-red-500 flex items-center justify-center">
                                    <span class="material-symbols-rounded">gavel</span>
                                </div>
                                <h5 class="text-[16px] font-black text-slate-900 dark:text-white uppercase tracking-tighter">@lang('Reject Documents')</h5>
                            </div>
                            <button type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors" data-bs-dismiss="modal">
                                <span class="material-symbols-rounded">close</span>
                            </button>
                        </div>

                        <form action="{{ route('admin.advertiser.data.reject', $user->id) }}" method="POST">
                            @csrf
                            <div class="space-y-6">
                                <div class="p-4 bg-orange-500/5 border border-orange-500/10 rounded-2xl">
                                    <p class="text-[11px] font-bold text-orange-600 dark:text-orange-400 leading-relaxed ">
                                        <span class="material-symbols-rounded text-[14px] align-middle mr-1">info</span>
                                        @lang('If you reject these documents, the user will be able to submit new ones, which will replace the previous records.')
                                    </p>
                                </div>

                                <x-textarea 
                                    name="reason" 
                                    label="Detailed Rejection Reason" 
                                    rows="5" 
                                    required 
                                    placeholder="Explain specifically why these documents were rejected..."
                                    hint="The advertiser will see this feedback in their verification panel."
                                />
                            </div>
                            <div class="mt-10 flex gap-4">
                                <button type="submit" class="flex-grow h-16 rounded-2xl bg-red-500 text-white font-black uppercase tracking-widest text-[12px] shadow-xl shadow-red-500/20 active:scale-95 transition-all">@lang('Confirm Rejection')</button>
                                <button type="button" class="px-8 h-16 rounded-2xl bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-white/40 font-black uppercase tracking-widest text-[12px]" data-bs-dismiss="modal">@lang('Cancel')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.users.login', $user->id) }}" target="_blank" class="h-10 px-6 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-black flex items-center gap-2 text-[10px] font-black uppercase tracking-widest shadow-lg shadow-slate-900/20 hover:scale-105 active:scale-95 transition-all">
        <i class="las la-sign-in-alt text-lg"></i>
        @lang('Login as Advertiser')
    </a>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/vendor/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/vendor/chart.js.2.8.0.js') }}"></script>
    <script src="{{ asset('assets/global/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/charts.js') }}"></script>
@endpush

@push('style-lib')
    <link type="text/css" href="{{ asset('assets/global/css/daterangepicker.css') }}" rel="stylesheet">
@endpush

@push('script')
    <script>
        "use strict";

        const start = moment().subtract(14, 'days');
        const end = moment();

        const dateRangeOptions = {
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                'Last 30 Days': [moment().subtract(30, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                    'month')],
                'Last 6 Months': [moment().subtract(6, 'months').startOf('month'), moment().endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
            },
            maxDate: moment()
        }

        const changeDatePickerText = (element, startDate, endDate) => {
            $(element).html(startDate.format('MMMM D, YYYY') + ' - ' + endDate.format('MMMM D, YYYY'));
        }

        let adsReport = lineChart(
            document.querySelector(".adsReport"),
            [{
                    name: "Clicks",
                    data: []
                },
                {
                    name: "Impressions",
                    data: []
                }
            ],
            []
        );

        const videoChart = (startDate, endDate) => {
            const data = {
                start_date: startDate.format('YYYY-MM-DD'),
                end_date: endDate.format('YYYY-MM-DD')
            }

            const url = @json(route('admin.advertiser.report', $user->id));

            $.get(url, data,
                function(data, status) {
                    if (status == 'success') {
                        adsReport.updateSeries(data.data);
                        adsReport.updateOptions({
                            colors: ['#3b82f6', '#f43f5e'],
                            yaxis: {
                                labels: {
                                    formatter: function(value) {
                                        return Math.round(value);
                                    },
                                    style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 900 }
                                }
                            },
                            xaxis: {
                                categories: data.created_on,
                                labels: { style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 900 } }
                            },
                            grid: { borderColor: 'rgba(148, 163, 184, 0.05)' }
                        });
                    }
                }
            );
        }

        $('#dataPicker').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('#dataPicker span', start, end));
        changeDatePickerText('#dataPicker span', start, end);
        videoChart(start, end);
        $('#dataPicker').on('apply.daterangepicker', (event, picker) => videoChart(picker.startDate, picker.endDate));
    </script>
@endpush

