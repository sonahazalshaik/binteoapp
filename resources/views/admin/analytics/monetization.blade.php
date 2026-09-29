@extends('admin.layouts.app')

@section('panel')
<div class="space-y-6 pb-10">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h4 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">Monetization & Growth</h4>
            <p class="text-sm text-slate-400">Financial health, ARPU, and user acquisition funnels</p>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-lg text-sm font-bold border border-indigo-100 dark:border-indigo-500/20">
            <i class="las la-wallet"></i> Total Daily Revenue: {{ showAmount($todayRevenue) }}
        </div>
    </div>

    <!-- Top KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">ARPU (30 Days)</p>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ showAmount($arpu) }}</h3>
            <div class="flex items-center gap-1 mt-2">
                <span class="text-[10px] font-bold text-slate-400">Average Revenue Per User</span>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Ads Revenue</p>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ showAmount($revenueAds) }}</h3>
            <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full mt-4 overflow-hidden">
                @php $adsPercent = $totalRevenue30 > 0 ? ($revenueAds / $totalRevenue30) * 100 : 0; @endphp
                <div class="bg-blue-500 h-full" style="width: {{ $adsPercent }}%"></div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Subscriptions</p>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ showAmount($revenueSubs) }}</h3>
            <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full mt-4 overflow-hidden">
                @php $subsPercent = $totalRevenue30 > 0 ? ($revenueSubs / $totalRevenue30) * 100 : 0; @endphp
                <div class="bg-emerald-500 h-full" style="width: {{ $subsPercent }}%"></div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Video Purchases</p>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ showAmount($revenuePurchases) }}</h3>
            <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full mt-4 overflow-hidden">
                @php $purchPercent = $totalRevenue30 > 0 ? ($revenuePurchases / $totalRevenue30) * 100 : 0; @endphp
                <div class="bg-orange-500 h-full" style="width: {{ $purchPercent }}%"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue Trend Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <h4 class="text-lg font-black text-slate-800 dark:text-white tracking-tight mb-6">Revenue Growth (30 Days)</h4>
            <div id="revenueChart" class="h-80"></div>
            
            <!-- Recent Transactions Table -->
            <div class="mt-10 pt-10 border-t border-slate-100 dark:border-slate-800">
                <h4 class="text-md font-black text-slate-800 dark:text-white tracking-tight mb-6">Recent Revenue Sources</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 dark:border-slate-800">
                                <th class="pb-4">User / Email</th>
                                <th class="pb-4">Amount</th>
                                <th class="pb-4 text-center">Method</th>
                                <th class="pb-4 text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                            @forelse($recentTransactions as $tx)
                            <tr class="group hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-800 dark:text-white">{{ $tx->username }}</span>
                                        <span class="text-[10px] font-bold text-slate-400">{{ $tx->email }}</span>
                                    </div>
                                </td>
                                <td class="py-4">
                                    <span class="text-sm font-black text-emerald-500 tracking-tighter">{{ showAmount($tx->amount) }}</span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="px-2 py-1 rounded-md bg-slate-100 dark:bg-slate-800 text-[9px] font-black uppercase text-slate-500">
                                        {{ $tx->gateway_name ?? 'Manual' }}
                                    </span>
                                </td>
                                <td class="py-4 text-right">
                                    <span class="text-xs font-bold text-slate-400">{{ \Carbon\Carbon::parse($tx->created_at)->format('M d, H:i') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-10 text-center text-sm text-slate-400 ">No recent transactions found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Traffic Sources -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <h4 class="text-lg font-black text-slate-800 dark:text-white tracking-tight mb-6">Acquisition Sources</h4>
            
            <div class="space-y-6">
                @php
                    $totalSignups = array_sum($trafficSources);
                @endphp
                
                @foreach($trafficSources as $source => $count)
                @php
                    $percent = $totalSignups > 0 ? ($count / $totalSignups) * 100 : 0;
                    $colors = [
                        'Organic' => ['bg-emerald-500', 'text-emerald-600', 'border-emerald-100', 'bg-emerald-50', 'icon' => 'public'],
                        'Referral' => ['bg-indigo-500', 'text-indigo-600', 'border-indigo-100', 'bg-indigo-50', 'icon' => 'link'],
                        'Ads' => ['bg-rose-500', 'text-rose-600', 'border-rose-100', 'bg-rose-50', 'icon' => 'campaign']
                    ];
                    $style = $colors[$source];
                @endphp
                <div class="relative">
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg {{ $style[3] }} {{ $style[1] }} border {{ $style[2] }} flex items-center justify-center">
                                <span class="material-symbols-rounded text-[16px]">{{ $style['icon'] }}</span>
                            </div>
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $source }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-black text-slate-800 dark:text-white">{{ number_format($count) }}</span>
                            <span class="text-xs text-slate-400 font-bold ml-1">({{ number_format($percent, 1) }}%)</span>
                        </div>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                        <div class="{{ $style[0] }} h-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
                @endforeach

                <div class="pt-6 mt-6 border-t border-slate-100 dark:border-slate-800">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-500/20 text-indigo-500 flex items-center justify-center flex-shrink-0">
                            <i class="las la-lightbulb text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Growth Insight</p>
                            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                                @if(isset($trafficSources['Organic']) && $trafficSources['Organic'] > max($trafficSources['Ads'], $trafficSources['Referral']))
                                    Organic traffic is your strongest acquisition channel today. Consider optimizing SEO metadata for videos.
                                @elseif(isset($trafficSources['Ads']) && $trafficSources['Ads'] > max($trafficSources['Organic'], $trafficSources['Referral']))
                                    Paid campaigns are driving the most signups. Monitor CPA (Cost Per Acquisition) closely to ensure profitability.
                                @else
                                    Referrals are driving strong growth. The creator economy and sharing tools are working well.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/vendor/apexcharts.min.js') }}"></script>
@endpush

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var options = {
            series: [{
                name: 'Ads Revenue',
                data: @json($history->pluck('revenue_ads'))
            }, {
                name: 'Subscriptions',
                data: @json($history->pluck('revenue_subscriptions'))
            }, {
                name: 'Purchases',
                data: @json($history->pluck('revenue_purchases'))
            }],
            chart: {
                height: 350,
                type: 'bar',
                stacked: true,
                toolbar: { show: false }
            },
            colors: ['#3b82f6', '#10b981', '#f97316'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '40%',
                }
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: @json($history->pluck('recorded_at')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#94a3b8', fontWeight: 600 } }
            },
            yaxis: {
                labels: { 
                    style: { colors: '#94a3b8', fontWeight: 600 },
                    formatter: function (val) { return "$" + val }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                labels: { colors: '#64748b' }
            },
            tooltip: { theme: 'dark' }
        };

        var chart = new ApexCharts(document.querySelector("#revenueChart"), options);
        chart.render();
    });
</script>
@endpush

