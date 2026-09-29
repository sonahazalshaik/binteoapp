@use('App\Constants\Status')
<x-app-layout>
    <div class="py-8 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-10">
            
            @if (!$user->advertiser_status || $user->advertiser_status == Status::ADVERTISER_REJECTED)
                <!-- Onboarding / Application State -->
                <div class="max-w-3xl mx-auto py-12">
                    <div class="bg-white dark:bg-[#181818] rounded-[4rem] p-10 sm:p-16 border border-gray-100 dark:border-white/5 shadow-2xl relative overflow-hidden transition-all duration-500">
                        <!-- Background Accent -->
                        <div class="absolute -top-24 -right-24 w-80 h-80 bg-red-600/5 rounded-full blur-[100px]"></div>
                        
                        <div class="relative z-10 text-center">
                            <div class="w-24 h-24 rounded-[2.5rem] bg-red-600 text-white flex items-center justify-center mx-auto mb-10 shadow-2xl shadow-red-500/40">
                                <span class="material-symbols-rounded text-5xl">rocket_launch</span>
                            </div>
                            
                            <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tighter mb-6">Become an Advertiser</h1>
                            <p class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-12 leading-loose max-w-xl mx-auto">
                                Reach global audiences with precision. Launch video campaigns that convert and scale.
                            </p>

                            @if($user->advertiser_status == Status::ADVERTISER_REJECTED)
                                <div class="mb-12 p-8 bg-red-500/5 border border-red-500/10 rounded-[2.5rem] text-left relative overflow-hidden">
                                     <div class="absolute top-0 right-0 p-4 opacity-10">
                                        <span class="material-symbols-rounded text-6xl text-red-500">cancel</span>
                                    </div>
                                    <h4 class="text-red-500 font-black text-[10px] uppercase tracking-[0.3em] mb-2">Application Response</h4>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white mb-2">Submission was not successful</p>
                                    <p class="text-xs font-bold text-gray-400 leading-relaxed">{{ $user->advertiser_rejection_reason }}</p>
                                </div>
                            @endif

                            <form action="{{ route('user.advertiser.data.submit') }}" method="POST" enctype="multipart/form-data" class="text-left space-y-10">
                                @csrf
                                <div class="premium-form-container">
                                    <x-viser-form identifier="act" identifierValue="advertiser" />
                                </div>
                                <button type="submit" class="w-full py-6 bg-red-600 text-white rounded-[2rem] font-black text-sm uppercase tracking-[0.4em] shadow-2xl shadow-red-500/40 hover:bg-red-700 hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center gap-4">
                                    Submit Application
                                    <span class="material-symbols-rounded">arrow_right_alt</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            @elseif($user->advertiser_status == Status::ADVERTISER_PENDING)
                <!-- Pending Review State -->
                <div class="max-w-2xl mx-auto text-center py-24">
                    <div class="relative inline-block mb-12">
                        <div class="w-28 h-28 rounded-[3rem] bg-amber-500/10 border border-amber-500/20 flex items-center justify-center relative z-10">
                            <span class="material-symbols-rounded text-6xl text-amber-500 animate-pulse">hourglass_top</span>
                        </div>
                        <div class="absolute inset-0 bg-amber-500 blur-[50px] opacity-20 scale-150"></div>
                    </div>
                    <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter mb-4">Application Processing</h1>
                    <p class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] leading-loose max-w-sm mx-auto">
                        Our review experts are currently auditing your credentials. We'll update you shortly.
                    </p>
                    <div class="mt-14 inline-flex items-center gap-3 px-8 py-4 bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/10 rounded-[2rem] shadow-sm">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                        </span>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Estimated response: Under 12 Hours</span>
                    </div>
                </div>

            @elseif($user->advertiser_status == Status::ADVERTISER_APPROVED)
                <!-- Dashboard State -->
                <div class="flex flex-col sm:flex-row items-center justify-between mb-12 gap-8">
                    <div>
                        <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter">Campaign Hub</h1>
                        <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                             <span class="w-2 h-2 rounded-full gradient-orange"></span>
                             Real-time advertising performance
                        </p>
                    </div>
                    <a href="{{ route('user.advertiser.ad.create') }}" class="px-10 py-5 gradient-orange text-white rounded-[2rem] font-black text-xs uppercase tracking-[0.3em] shadow-2xl shadow-orange-500/20 hover:-translate-y-1 transition-all active:scale-95 flex items-center gap-3">
                        <span class="material-symbols-rounded text-xl">add_circle</span>
                        Launch New Ad
                    </a>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                    @php
                        $stats = [
                            ['Active Campaigns', $totalAds ?? 0, 'ads_click', 'linear-gradient(135deg, #3b82f6, #1d4ed8)', 'shadow-blue-500/20'],
                            ['Total Reach', formatNumber($totalImpressions ?? 0), 'groups', 'linear-gradient(135deg, #10b981, #047857)', 'shadow-emerald-500/20'],
                            ['Total Clicks', formatNumber($totalClicks ?? 0), 'touch_app', 'linear-gradient(135deg, #8b5cf6, #6d28d9)', 'shadow-purple-500/20'],
                            ['Ad Investment', showAmount($totalAmount ?? 0), 'payments', 'linear-gradient(135deg, #f59e0b, #b45309)', 'shadow-amber-500/20']
                        ];
                    @endphp
                    @foreach($stats as $stat)
                        <div class="p-8 rounded-[3rem] shadow-2xl group hover:scale-[1.03] transition-all duration-500 relative overflow-hidden" style="background: {{ $stat[3] }}">
                            <div class="relative z-10 text-white">
                                <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-rounded text-2xl">{{ $stat[2] }}</span>
                                </div>
                                <h3 class="text-3xl font-black tracking-tighter mb-1">{{ $stat[1] }}</h3>
                                <p class="text-[9px] font-black text-white/70 uppercase tracking-[0.2em]">{{ $stat[0] }}</p>
                            </div>
                            <span class="material-symbols-rounded absolute -right-6 -bottom-6 text-[140px] text-white/10 rotate-12 group-hover:scale-125 transition-transform duration-700 pointer-events-none">{{ $stat[2] }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Main Analysis Row -->
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-12">
                    <!-- Chart Section -->
                    <div class="xl:col-span-2 bg-white dark:bg-[#181818] rounded-[3.5rem] p-10 border border-gray-100 dark:border-white/5 shadow-sm transition-colors duration-500">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-12 gap-6 px-2">
                            <div>
                                <h2 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-widest flex items-center gap-3">Performance Trends</h2>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Impressions vs Engagement Clicks</p>
                            </div>
                            <div id="impressionDatePicker" class="flex items-center gap-3 bg-gray-50 dark:bg-white/2 border border-gray-100 dark:border-white/5 rounded-2xl px-6 py-3.5 cursor-pointer hover:border-red-500/50 transition-all">
                                <span class="material-symbols-rounded text-gray-400 text-lg">calendar_month</span>
                                <span class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-[0.2em]"></span>
                                <span class="material-symbols-rounded text-gray-400">expand_more</span>
                            </div>
                        </div>
                        <div class="px-2">
                             <div id="adsReportChart" class="min-h-[400px]"></div>
                        </div>
                    </div>

                    <!-- Side Highlighting Card -->
                    <div class="bg-gray-900 dark:bg-white rounded-[3.5rem] p-10 text-white dark:text-black relative overflow-hidden shadow-2xl flex flex-col justify-between">
                        <div class="absolute top-0 right-0 p-10 opacity-10">
                            <span class="material-symbols-rounded text-9xl">insights</span>
                        </div>
                        <div class="relative z-10">
                            <h2 class="text-3xl font-black tracking-tighter mb-4">Real-Time Insight</h2>
                            <p class="text-[11px] font-bold opacity-60 uppercase tracking-widest leading-relaxed mb-10">Your top-performing ads are seeing a 24% increase in click-through rates this week.</p>
                            
                            <div class="space-y-6">
                                <div class="p-5 rounded-3xl bg-white/10 dark:bg-black/5 backdrop-blur-md border border-white/10 dark:border-black/5">
                                    <p class="text-[9px] font-black uppercase tracking-widest mb-1 opacity-50">Estimated Reach (24h)</p>
                                    <p class="text-2xl font-black tracking-tighter">+124,500</p>
                                </div>
                                <div class="p-5 rounded-3xl bg-white/10 dark:bg-black/5 backdrop-blur-md border border-white/10 dark:border-black/5">
                                    <p class="text-[9px] font-black uppercase tracking-widest mb-1 opacity-50">Conversion ROI</p>
                                    <p class="text-2xl font-black tracking-tighter">8.4x</p>
                                </div>
                            </div>
                        </div>
                        <div class="relative z-10 pt-10">
                             <a href="{{ route('user.advertiser.ad.list') }}" class="w-full py-4 bg-red-600 text-white rounded-[1.5rem] font-black text-[10px] uppercase tracking-[0.2em] shadow-xl flex items-center justify-center gap-2 hover:scale-105 transition-all">
                                Optimization Tools
                                <span class="material-symbols-rounded text-sm">settings_suggest</span>
                             </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Ads Table -->
                <div class="bg-white dark:bg-[#181818] rounded-[3.5rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden transition-colors duration-500">
                    <div class="px-10 py-10 border-b border-gray-50 dark:border-white/5 flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-widest">Active Ad Portfolios</h2>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Live tracking of active distributions</p>
                        </div>
                        <a href="{{ route('user.advertiser.ad.list') }}" class="text-[10px] font-black text-red-500 uppercase tracking-widest hover:bg-red-50 dark:hover:bg-white/5 px-6 py-3 rounded-[1.5rem] transition-all">
                            Manage All
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50/20 dark:bg-white/[0.01]">
                                <tr>
                                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Campaign Reference</th>
                                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Distribution Type</th>
                                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Performance Metric</th>
                                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Protocol Status</th>
                                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Investment</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                                @forelse ($advertisements ?? [] as $ad)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors group">
                                    <td class="px-10 py-6">
                                        <div>
                                            <p class="font-black text-sm text-gray-900 dark:text-white mb-1 group-hover:text-red-500 transition-colors">{{ __($ad->title) }}</p>
                                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em]">{{ showDateTime($ad->created_at, 'M d, Y') }}</p>
                                        </div>
                                    </td>
                                    <td class="px-10 py-6">
                                        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-gray-100 dark:bg-white/5 rounded-xl border border-gray-100 dark:border-white/5">
                                             <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                             <span class="text-[9px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">@php echo $ad->adTypeBadge; @endphp</span>
                                        </div>
                                    </td>
                                    <td class="px-10 py-6">
                                        <div class="flex items-center justify-center gap-6">
                                            <div class="flex flex-col items-center">
                                                <span class="text-sm font-black text-emerald-500">{{ formatNumber($ad->available_impression) }}</span>
                                                <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Reach</span>
                                            </div>
                                            <div class="flex flex-col items-center">
                                                <span class="text-sm font-black text-blue-500">{{ formatNumber($ad->available_click ?? 0) }}</span>
                                                <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Engage</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-10 py-6 text-center">
                                        <div class="flex justify-center">
                                            @php echo $ad->statusBadge; @endphp
                                        </div>
                                    </td>
                                    <td class="px-10 py-6 text-right">
                                        <span class="font-black text-sm text-gray-900 dark:text-white">{{ showAmount($ad->total_amount ?? 0) }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-24 text-center">
                                        <div class="w-24 h-24 rounded-[2rem] bg-gray-50 dark:bg-white/2 flex items-center justify-center mx-auto mb-8">
                                            <span class="material-symbols-rounded text-6xl text-gray-100 dark:text-white/5">ads_click</span>
                                        </div>
                                        <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">No Active Ad Campaigns</h3>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-10">Start reaching your target audience today by launching a new ad.</p>
                                        <a href="{{ route('user.advertiser.ad.create') }}" class="inline-flex items-center gap-3 px-10 py-5 bg-red-600 text-white rounded-[2rem] font-black text-xs uppercase tracking-widest">
                                           Launch First Campaign 
                                           <span class="material-symbols-rounded">rocket_launch</span>
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
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
        @if ($user->advertiser_status == Status::ADVERTISER_APPROVED)
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
                maxDate: moment(),
                buttonClasses: 'px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg',
                applyClass: 'bg-red-600 text-white',
                cancelClass: 'bg-gray-100 dark:bg-white/5 text-gray-400'
            }

            let adsChart = lineChart(
                document.querySelector("#adsReportChart"),
                [{ name: "Clicks", data: [] }, { name: "Impressions", data: [] }],
                []
            );

            const updateChart = (startDate, endDate) => {
                $('#impressionDatePicker span').html(startDate.format('MMM D, YYYY') + ' - ' + endDate.format('MMM D, YYYY'));
                
                $.get("{{ route('user.advertiser.ad.chart') }}", {
                    start_date: startDate.format('YYYY-MM-DD'),
                    end_date: endDate.format('YYYY-MM-DD')
                }, function(res) {
                    adsChart.updateSeries([
                        { name: res.data[0].name, data: res.data[0].data },
                        { name: res.data[1].name, data: res.data[1].data }
                    ]);
                    adsChart.updateOptions({
                        colors: ['#3b82f6', '#ef4444'],
                        xaxis: { categories: res.created_on },
                        stroke: { width: 4, curve: 'smooth' },
                        markers: { size: 6, strokeWidth: 3, hover: { size: 9 } }
                    });
                });
            }

            $('#impressionDatePicker').daterangepicker(dateRangeOptions, (s, e) => updateChart(s, e));
            updateChart(start, end);
        @endif
    </script>
    @endpush
</x-app-layout>
