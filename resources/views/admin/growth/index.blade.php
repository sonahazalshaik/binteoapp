@extends('admin.layouts.app')

@section('title', 'Growth & Acquisition')
@section('header_title', 'Platform Growth')

@section('panel')
<div class="max-w-[1600px] mx-auto animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-20">
    
    <!-- Growth Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <!-- Total Users -->
        <div class="bg-white/60 dark:bg-white/[0.03] backdrop-blur-xl border border-white dark:border-white/5 rounded-[2rem] p-8 shadow-xl relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <p class="text-[10px] font-black text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] mb-2">Total Community</p>
            <div class="flex items-baseline gap-3">
                <h2 class="text-4xl font-black text-slate-800 dark:text-white tracking-tighter">{{ number_format($totalUsers) }}</h2>
                <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">+{{ $newUsers }} this week</span>
            </div>
            <div class="mt-6 flex items-center gap-2">
                <span class="material-symbols-rounded text-blue-500">group</span>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ">Registered Users</span>
            </div>
        </div>

        <!-- Total Visitors -->
        <div class="bg-white/60 dark:bg-white/[0.03] backdrop-blur-xl border border-white dark:border-white/5 rounded-[2rem] p-8 shadow-xl relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <p class="text-[10px] font-black text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] mb-2">Unique Visitors</p>
            <div class="flex items-baseline gap-3">
                <h2 class="text-4xl font-black text-slate-800 dark:text-white tracking-tighter">{{ number_format($totalVisitors) }}</h2>
                <span class="text-[10px] font-black text-orange-500 uppercase tracking-widest">+{{ $newVisitors }} this week</span>
            </div>
            <div class="mt-6 flex items-center gap-2">
                <span class="material-symbols-rounded text-orange-500">visibility</span>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ">Potential Installs</span>
            </div>
        </div>

        <!-- Conversion Rate -->
        <div class="bg-white/60 dark:bg-white/[0.03] backdrop-blur-xl border border-white dark:border-white/5 rounded-[2rem] p-8 shadow-xl relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-rose-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <p class="text-[10px] font-black text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] mb-2">Conversion funnel</p>
            <div class="flex items-baseline gap-3">
                <h2 class="text-4xl font-black text-slate-800 dark:text-white tracking-tighter">{{ number_format($conversionRate, 1) }}%</h2>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest ">Visit to Signup</span>
            </div>
            <div class="mt-6 flex items-center gap-2">
                <span class="material-symbols-rounded text-rose-500">analytics</span>
                <div class="w-full h-1.5 bg-slate-100 dark:bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-rose-500 to-orange-500" style="width: {{ $conversionRate }}%"></div>
                </div>
            </div>
        </div>

        <!-- Active Now -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 dark:from-white/10 dark:to-white/5 rounded-[2rem] p-8 shadow-xl relative overflow-hidden group">
            <div class="absolute top-4 right-4 flex gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            </div>
            <p class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-2">Live Activity</p>
            <div class="flex items-baseline gap-3">
                <h2 class="text-4xl font-black text-white tracking-tighter">{{ number_format($liveUsersCount) }}</h2>
            </div>
            <div class="mt-6">
                <p class="text-[9px] font-bold text-white/30 uppercase tracking-[0.3em] ">Tracking session data</p>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Growth Chart -->
        <div class="lg:col-span-2 bg-white/60 dark:bg-white/[0.03] backdrop-blur-xl border border-white dark:border-white/5 rounded-[2.5rem] p-10 shadow-xl">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Growth Velocity</h3>
                    <p class="text-[10px] font-bold text-slate-400 dark:text-white/30 uppercase tracking-widest mt-1">Comparison between visitors and registrations (Last 30 Days)</p>
                </div>
                <div class="flex gap-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-blue-500 shadow-lg shadow-blue-500/20"></span>
                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Signups</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-orange-500 shadow-lg shadow-orange-500/20"></span>
                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Visitors</span>
                    </div>
                </div>
            </div>
            
            <div id="growthChart" class="min-h-[400px]"></div>
        </div>

        <!-- Retention/Conversion Insights -->
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white/60 dark:bg-white/[0.03] backdrop-blur-xl border border-white dark:border-white/5 rounded-[2.5rem] p-8 shadow-xl">
                <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight mb-8">Acquisition Channels</h3>
                
                <div class="space-y-6">
                    @php
                        $channels = [
                            ['name' => 'Direct Traffic', 'count' => 65, 'color' => 'blue'],
                            ['name' => 'Organic Search', 'count' => 15, 'color' => 'emerald'],
                            ['name' => 'Social Media', 'count' => 12, 'color' => 'rose'],
                            ['name' => 'Referral', 'count' => 8, 'color' => 'orange'],
                        ];
                    @endphp
                    @foreach($channels as $channel)
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-black text-slate-600 dark:text-white/60 uppercase tracking-widest">{{ $channel['name'] }}</span>
                            <span class="text-[10px] font-black text-slate-900 dark:text-white">{{ $channel['count'] }}%</span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 dark:bg-white/5 rounded-full overflow-hidden">
                            <div class="h-full bg-{{ $channel['color'] }}-500 rounded-full" style="width: {{ $channel['count'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-12 pt-8 border-t border-slate-100 dark:border-white/5">
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-orange-500/5 border border-orange-500/10 group hover:border-orange-500/30 transition-all duration-500">
                        <div class="w-12 h-12 rounded-xl bg-orange-500/10 text-orange-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="material-symbols-rounded">rocket_launch</span>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-900 dark:text-white uppercase">Growth Strategy</p>
                            <p class="text-[9px] font-bold text-slate-400 dark:text-white/30 uppercase tracking-widest mt-1">Acquisition is stable. Focus on retention.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conversion Funnel Card -->
            <div class="bg-gradient-to-br from-indigo-600 to-violet-600 p-8 rounded-[2.5rem] shadow-xl relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                <h3 class="text-white text-lg font-black uppercase tracking-tight mb-2">Funnel Efficiency</h3>
                <p class="text-white/60 text-[9px] font-black uppercase tracking-[0.2em] mb-8">Performance breakdown</p>
                
                <div class="flex items-center justify-between">
                    <div class="text-center">
                        <p class="text-white/40 text-[9px] font-black uppercase tracking-widest mb-1">CTR</p>
                        <h4 class="text-white text-2xl font-black ">4.2%</h4>
                    </div>
                    <div class="w-px h-10 bg-white/10"></div>
                    <div class="text-center">
                        <p class="text-white/40 text-[9px] font-black uppercase tracking-widest mb-1">BOUNCE</p>
                        <h4 class="text-white text-2xl font-black ">32%</h4>
                    </div>
                    <div class="w-px h-10 bg-white/10"></div>
                    <div class="text-center">
                        <p class="text-white/40 text-[9px] font-black uppercase tracking-widest mb-1">ROAS</p>
                        <h4 class="text-white text-2xl font-black ">2.8x</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isDark = document.documentElement.classList.contains('dark');
        
        var options = {
            series: [{
                name: 'Signups',
                data: @json($registrations->pluck('count'))
            }, {
                name: 'Visitors',
                data: @json($visitors->pluck('count'))
            }],
            chart: {
                type: 'area',
                height: 400,
                toolbar: { show: false },
                background: 'transparent',
                foreColor: isDark ? '#ffffff33' : '#64748b'
            },
            colors: ['#3b82f6', '#f97316'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [20, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: 4,
                lineCap: 'round'
            },
            grid: {
                borderColor: isDark ? '#ffffff05' : '#f1f5f9',
                strokeDashArray: 4,
                xaxis: { lines: { show: true } }
            },
            xaxis: {
                categories: @json($visitors->pluck('date')),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: {
                        fontSize: '9px',
                        fontWeight: 900,
                        cssClass: 'uppercase tracking-widest font-black'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '9px',
                        fontWeight: 900,
                        cssClass: 'uppercase tracking-widest font-black'
                    }
                }
            },
            tooltip: {
                theme: isDark ? 'dark' : 'light',
                x: { show: true },
                marker: { show: false }
            },
            legend: { show: false }
        };

        var chart = new ApexCharts(document.querySelector("#growthChart"), options);
        chart.render();
    });
</script>
@endpush

