@extends('admin.layouts.app')

@section('panel')
    <div class="space-y-10 pb-10">
        <!-- System Alerts -->
        <!-- <div class="ffmpegAlert d-none"></div>
        @if (!gs('is_storage'))
            <div class="storageAlert d-none"></div>
            <div class="storage">
                <div class="p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-lg bg-red-500 text-white flex items-center justify-center flex-shrink-0">
                        <i class="las la-bell"></i>
                    </div>
                    <div>
                        <h5 class="text-sm font-bold text-red-600">@lang('Video Upload Disabled In Storage')</h5>
                        <p class="text-xs text-red-500/80 mt-0.5">@lang('Uploading videos to storage is currently disabled. Otherwise, videos will be uploaded to your local storage.') @lang('you can') <a href="{{ route('admin.setting.system.configuration') }}" class="font-bold underline">@lang('Enable Storage')</a> @lang('it in the settings').</p>
                    </div>
                </div>
            </div>
        @endif -->

        <!-- DASHBOARD SECTION -->
        <div>
            <div class="flex items-center justify-between mb-6 px-1">
                <h4 class="text-xl font-bold text-slate-800 dark:text-white">@lang('Dashboard')</h4>
                <a href="{{ route('cron') }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-lg text-indigo-600 text-xs font-bold hover:bg-indigo-100 transition-all">
                    <i class="las la-server"></i> @lang('Cron Setup')
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <x-native-card title="Total Users" value="{{ $widget['total_users'] }}" icon="las la-users" color="purple" link="{{ route('admin.users.all') }}" />
                <x-native-card title="Active Users" value="{{ $widget['verified_users'] }}" icon="las la-user-check" color="success" link="{{ route('admin.users.active') }}" />
                <x-native-card title="Online Users" value="{{ $widget['online_users'] }}" icon="las la-broadcast-tower" color="info" link="{{ route('admin.users.active') }}" />
                @php
                    $watchValue = $widget['total_watch_time'] < 3600 ? round($widget['total_watch_time'] / 60) . ' Mins' : round($widget['total_watch_time'] / 3600) . ' Hrs';
                @endphp
                <x-native-card title="Total Watch Time" value="{{ $watchValue }}" icon="las la-stopwatch" color="primary" link="{{ route('admin.analytics.advanced.videos') }}" />
                <x-native-card title="Revenue Summary" value="{{ showAmount($widget['estimated_revenue'], 0) }}" icon="las la-hand-holding-usd" color="warning" link="{{ route('admin.analytics.advanced.revenue') }}" />
            </div>
        </div>

        <!-- PAYMENTS & WITHDRAWALS SECTION -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-10">
            <!-- Payments -->
            <div>
                <div class="mb-6 px-1">
                    <h4 class="text-xl font-bold text-slate-800 dark:text-white">@lang('Payments')</h4>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-native-card title="Total Payment" value="{{ showAmount($deposit['total_deposit_amount'] + $deposit['total_deposit_charge']) }}" icon="las la-hand-holding-usd" color="success" link="{{ route('admin.deposit.list') }}" />
                    <x-native-card title="Pending Payments" value="{{ $deposit['total_deposit_pending'] }}" icon="las la-spinner" color="warning" link="{{ route('admin.deposit.pending') }}" />
                    <x-native-card title="Rejected Payments" value="{{ $deposit['total_deposit_rejected'] }}" icon="las la-ban" color="danger" link="{{ route('admin.deposit.rejected') }}" />
                    <x-native-card title="Payments Charge" value="{{ showAmount($deposit['total_deposit_charge']) }}" icon="las la-percentage" color="primary" link="{{ route('admin.deposit.list') }}" />
                </div>
            </div>

            <!-- Withdrawals -->
            <div>
                <div class="mb-6 px-1">
                    <h4 class="text-xl font-bold text-slate-800 dark:text-white">@lang('Withdrawals')</h4>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-native-card title="Total Withdrawn" value="{{ showAmount($withdrawals['total_withdraw_amount'] + $withdrawals['total_withdraw_charge']) }}" icon="las la-wallet" color="success" link="{{ route('admin.withdraw.data.all') }}" />
                    <x-native-card title="Pending Withdrawals" value="{{ $withdrawals['total_withdraw_pending'] }}" icon="las la-spinner" color="warning" link="{{ route('admin.withdraw.data.pending') }}" />
                    <x-native-card title="Rejected Withdrawals" value="{{ $withdrawals['total_withdraw_rejected'] }}" icon="las la-times-circle" color="danger" link="{{ route('admin.withdraw.data.rejected') }}" />
                    <x-native-card title="Withdrawal Charge" value="{{ showAmount($withdrawals['total_withdraw_charge']) }}" icon="las la-percent" color="primary" link="{{ route('admin.withdraw.data.all') }}" />
                </div>
            </div>
        </div>

        <!-- VIDEOS SECTION -->
        <div>
            <div class="mb-6 px-1">
                <h4 class="text-xl font-bold text-slate-800 dark:text-white">@lang('Videos')</h4>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <x-native-card title="Total Videos" value="{{ $widget['total_videos'] }}" icon="las la-video" color="primary" link="{{ route('admin.videos.index_legacy') }}" />
                <x-native-card title="Uploads Today" value="{{ $widget['uploads_today'] }}" icon="las la-cloud-upload-alt" color="info" link="{{ route('admin.videos.index_legacy') }}" />
                <x-native-card title="Free Videos" value="{{ $widget['free_videos'] }}" icon="las la-file-video" color="success" link="{{ route('admin.videos.free') }}" />
                <x-native-card title="Stock Videos" value="{{ $widget['stock_videos'] }}" icon="las la-hand-holding-usd" color="danger" link="{{ route('admin.videos.stock') }}" />
                <x-native-card title="Regular Videos" value="{{ $widget['regular_videos'] }}" icon="las la-video" color="warning" link="{{ route('admin.videos.regular') }}" />
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                <x-native-card title="Shorts & Reels" value="{{ $widget['total_reels'] }}" icon="las la-play" color="primary" link="{{ route('admin.reels.index') }}" viewMore="true" />
                <x-native-card title="Public Videos" value="{{ $widget['public_videos'] }}" icon="las la-file-video" color="success" link="{{ route('admin.videos.public') }}" viewMore="true" />
                <x-native-card title="Private Videos" value="{{ $widget['private_videos'] }}" icon="las la-video-slash" color="danger" link="{{ route('admin.videos.private') }}" viewMore="true" />
                <x-native-card title="Draft Videos" value="{{ $widget['draft_videos'] }}" icon="las la-edit" color="warning" link="{{ route('admin.videos.draft') }}" viewMore="true" />
            </div>
        </div>
    </div>

    @include('admin.partials.cron_modal')
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('cron') }}" target="_blank" class="btn btn-outline--primary btn-sm">
        <i class="las la-server"></i>@lang('Cron Setup')
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

        let dwChart = barChart(
            document.querySelector("#dwChartArea"),
            `{{ __(gs('cur_text')) }}`,
            [{
                    name: 'Payments',
                    data: []
                },
                {
                    name: 'Withdrawn',
                    data: []
                }
            ],
            [],
        );

        let trxChart = lineChart(
            document.querySelector("#transactionChartArea"),
            [{
                    name: "Plus Transactions",
                    data: []
                },
                {
                    name: "Minus Transactions",
                    data: []
                }
            ],
            []
        );


        const depositWithdrawChart = (startDate, endDate) => {

            const data = {
                start_date: startDate.format('YYYY-MM-DD'),
                end_date: endDate.format('YYYY-MM-DD')
            }

            const url = `{{ route('admin.chart.deposit.withdraw') }}`;

            $.get(url, data,
                function(data, status) {
                    if (status == 'success') {
                        dwChart.updateSeries(data.data);
                        dwChart.updateOptions({
                            xaxis: {
                                categories: data.created_on,
                            }
                        });
                    }
                }
            );
        }

        const transactionChart = (startDate, endDate) => {

            const data = {
                start_date: startDate.format('YYYY-MM-DD'),
                end_date: endDate.format('YYYY-MM-DD')
            }

            const url = `{{ route('admin.chart.transaction') }}`;


            $.get(url, data,
                function(data, status) {
                    if (status == 'success') {


                        trxChart.updateSeries(data.data);
                        trxChart.updateOptions({
                            xaxis: {
                                categories: data.created_on,
                            }
                        });
                    }
                }
            );
        }



        $('#dwDatePicker').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('#dwDatePicker span',
            start, end));
        $('#trxDatePicker').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('#trxDatePicker span',
            start, end));

        changeDatePickerText('#dwDatePicker span', start, end);
        changeDatePickerText('#trxDatePicker span', start, end);

        depositWithdrawChart(start, end);
        transactionChart(start, end);

        $('#dwDatePicker').on('apply.daterangepicker', (event, picker) => depositWithdrawChart(picker.startDate, picker
            .endDate));
        $('#trxDatePicker').on('apply.daterangepicker', (event, picker) => transactionChart(picker.startDate, picker
            .endDate));

        piChart(
            document.getElementById('userBrowserChart'),
            @json(@$chart['user_browser_counter']->keys()),
            @json(@$chart['user_browser_counter']->flatten())
        );

        piChart(
            document.getElementById('userOsChart'),
            @json(@$chart['user_os_counter']->keys()),
            @json(@$chart['user_os_counter']->flatten())
        );

        piChart(
            document.getElementById('userCountryChart'),
            @json(@$chart['user_country_counter']->keys()),
            @json(@$chart['user_country_counter']->flatten())
        );





        const ffmpeg = "{{ gs('ffmpeg_status') }}";
        const storage = "{{ gs('is_storage') }}";




        if (storage == 1) {

            $(document).ready(function() {

                const redirectUrl = "{{ route('admin.storage.index') }}"
                $.ajax({
                    type: "get",
                    url: "{{ route('admin.check.space') }}",

                    success: function(response) {


                        if (response.status == true) {
                            $('.storageAlert')
                                .removeClass('d-none')
                                .html(`
                                    <div class="custom-alert alert alert--danger" role="alert">
                                        <span class="alert__icon">
                                            <i class="far fa-bell"></i>
                                        </span>
                                        <div class="alert__content">
                                            <h5 class="alert-heading">@lang('Storage Limit Reached')</h5>
                                            <p>@lang('Unable to upload the file as the selected storage location has reached its capacity. Please free up space or upgrade your storage. <a href="${redirectUrl}">Click here</a>')</p>
                                        </div>
                                    </div>
                                `);
                        } else {
                            $('.storageAlert').addClass('d-none').html('');
                        }

                    }
                });
            });
        }




        if (ffmpeg == 1) {

            $(document).ready(function() {

                const redirectUrl = "{{ route('admin.setting.system.configuration') }}"
                $.ajax({
                    type: "get",
                    url: "{{ route('admin.setting.check.ffmpeg') }}",

                    success: function(response) {
                        if (response.status == 'error') {
                            $('.ffmpegAlert').removeClass('d-none').html(`
                                <div class="custom-alert alert alert--danger" role="alert">
                                    <span class="alert__icon">
                                        <i class="far fa-bell"></i>
                                    </span>
                                    <div class="alert__content">
                                        <h5 class="alert-heading">@lang('FFmpeg is required')</h5>
                                        <p>@lang('FFmpeg is essential for processing and converting video files on this platform. Without FFmpeg, video format conversions will not work properly. If you do not use FFmpeg, you can <a href="${redirectUrl}">Disable FFmpeg</a> it in the settings.')</p>
                                    </div>
                                </div>
                            `);
                        } else {
                            $('.ffmpegAlert').addClass('d-none').html('');
                        }

                    }
                });
            });
        }
    </script>
@endpush
@push('style')
    <style>
        .apexcharts-menu {
            min-width: 120px !important;
        }

        .custom-alert {
            align-items: flex-start;
            gap: 16px;
            padding: 16px;
        }

        .custom-alert.alert--danger {
            background-color: rgb(235 34 34 / 10%);
            border-left: 5px solid #eb2222;
        }

        .custom-alert .alert__icon {
            height: 36px;
            width: 36px;
            background: rgb(235 34 34 / 10%);
            color: #eb2222;
            border-radius: 50%;
            display: grid;
            place-content: center;
            flex-shrink: 0;
        }

        .alert__content {
            flex: 1;
        }

        .alert-heading {
            margin-bottom: 6px;
        }
    </style>
@endpush
