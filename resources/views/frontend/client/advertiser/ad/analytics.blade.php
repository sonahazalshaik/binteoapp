<x-app-layout>
    <div class="py-10 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen">
        <div class="max-w-[1200px] mx-auto px-6">
             <div class="mb-12">
                <a href="{{ route('user.advertiser.ad.list') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 hover:text-red-600 transition-colors">
                    <span class="material-symbols-rounded text-lg">arrow_back</span>
                    Back to Inventory
                </a>
                <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Ad Analytics</h1>
                <p class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2">{{ $advertisement->title }}</p>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                @foreach([
                    ['Current Reach', formatNumber($advertisement->available_impression), 'visibility', 'text-emerald-500', 'bg-emerald-500/10'],
                    ['Engagements', formatNumber($advertisement->available_click ?? 0), 'touch_app', 'text-blue-500', 'bg-blue-500/10'],
                    ['Conversion Rate', '0.0%', 'trending_up', 'text-amber-500', 'bg-amber-500/10'],
                    ['Total Cost', showAmount($advertisement->total_amount ?? 0), 'payments', 'text-rose-500', 'bg-rose-500/10']
                ] as $stat)
                <div class="bg-white dark:bg-[#1A1A1A] p-6 rounded-[2rem] border border-gray-100 dark:border-white/5 shadow-sm">
                    <div class="w-10 h-10 rounded-xl {{ $stat[4] }} flex items-center justify-center mb-4">
                        <span class="material-symbols-rounded {{ $stat[3] }} text-xl">{{ $stat[2] }}</span>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tight mb-1">{{ $stat[1] }}</h3>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $stat[0] }}</p>
                </div>
                @endforeach
            </div>

            <div class="bg-white dark:bg-[#1A1A1A] rounded-[3rem] p-10 border border-gray-100 dark:border-white/5 shadow-sm mb-12">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-10 gap-6">
                    <h2 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-widest flex items-center gap-3">
                        <span class="material-symbols-rounded text-red-600">query_stats</span>
                        Performance Reports
                    </h2>
                    <div id="impressionDatePicker" class="flex items-center gap-3 bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 rounded-2xl px-6 py-3 cursor-pointer hover:border-red-500 transition-colors">
                        <span class="material-symbols-rounded text-gray-400">calendar_today</span>
                        <span class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest"></span>
                        <span class="material-symbols-rounded text-gray-400">arrow_drop_down</span>
                    </div>
                </div>
                <div id="adsReportChart" class="min-h-[500px]"></div>
            </div>
        </div>
    </div>

    @push('style-lib')
        <link type="text/css" href="{{ asset('assets/global/css/daterangepicker.css') }}" rel="stylesheet">
    @endpush

    @push('script-lib')
        <script src="{{ asset('assets/global/js/vendor/apexcharts.min.js') }}"></script>
        <script src="{{ asset('assets/global/js/moment.min.js') }}"></script>
        <script src="{{ asset('assets/global/js/daterangepicker.min.js') }}"></script>
        <script src="{{ asset('assets/global/js/charts.js') }}"></script>
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
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(30, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
            },
            maxDate: moment()
        }

        let adsChart = lineChart(
            document.querySelector("#adsReportChart"),
            [
                { name: "Clicks", data: [] }, 
                { name: "Impressions", data: [] },
                { name: "Reached Users", data: [] }
            ],
            []
        );

        const updateChart = (startDate, endDate) => {
            $('#impressionDatePicker span').html(startDate.format('MMM D, YYYY') + ' - ' + endDate.format('MMM D, YYYY'));
            
            $.get("{{ route('user.advertiser.ad.analytics.chart', $advertisement->id) }}", {
                start_date: startDate.format('YYYY-MM-DD'),
                end_date: endDate.format('YYYY-MM-DD')
            }, function(res) {
                adsChart.updateSeries([
                    { name: res.data[0].name, data: res.data[0].data },
                    { name: res.data[1].name, data: res.data[1].data },
                    { name: res.data[2].name, data: res.data[2].data }
                ]);
                adsChart.updateOptions({
                    colors: ['#3b82f6', '#ef4444', '#f59e0b'],
                    xaxis: { categories: res.created_on }
                });
            });
        }

        $('#impressionDatePicker').daterangepicker(dateRangeOptions, (s, e) => updateChart(s, e));
        updateChart(start, end);
    </script>
    @endpush
</x-app-layout>
