@extends('admin.layouts.app')

@section('panel')
<div class="space-y-6 pb-10">
    <!-- Header -->
    <div class="flex items-center justify-between mb-2">
        <div>
            <h4 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">Telemetry & KPIs</h4>
            <p class="text-sm text-slate-400">Real-time user engagement and platform metrics</p>
        </div>
        <button onclick="window.location.reload()" class="flex items-center gap-2 px-4 py-2 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-lg text-xs font-black uppercase tracking-widest border border-indigo-100 dark:border-indigo-500/20 hover:bg-indigo-100 dark:hover:bg-indigo-500/20 active:scale-95 transition-all group">
            <span class="material-symbols-rounded text-sm group-active:animate-spin">refresh</span> Live
        </button>
    </div>

    <!-- Top KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Avg Watch Time -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:border-indigo-500/30 transition-colors">
            <div class="flex justify-between items-start mb-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-500 flex items-center justify-center">
                    <span class="material-symbols-rounded text-[18px]">timer</span>
                </div>
                <span class="px-2 py-0.5 rounded text-[10px] font-black bg-emerald-500/10 text-emerald-500">Today</span>
            </div>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ round($todayRollup->avg_watch_time_seconds / 60, 2) }} <span class="text-xs font-bold text-slate-400">min</span></h3>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mt-1">Avg Watch Time</p>
        </div>

        <!-- MAU -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:border-blue-500/30 transition-colors">
            <div class="flex justify-between items-start mb-3">
                <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-500/10 text-blue-500 flex items-center justify-center">
                    <span class="material-symbols-rounded text-[18px]">group</span>
                </div>
                <span class="px-2 py-0.5 rounded text-[10px] font-black bg-blue-500/10 text-blue-500">30 Days</span>
            </div>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($todayRollup->mau) }}</h3>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mt-1">Monthly Active Users</p>
        </div>

        <!-- Avg Session -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:border-emerald-500/30 transition-colors">
            <div class="flex justify-between items-start mb-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                    <span class="material-symbols-rounded text-[18px]">hourglass_empty</span>
                </div>
                <span class="px-2 py-0.5 rounded text-[10px] font-black bg-emerald-500/10 text-emerald-500">Today</span>
            </div>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ round($todayRollup->avg_session_duration_seconds / 60, 2) }} <span class="text-xs font-bold text-slate-400">min</span></h3>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mt-1">Avg Session Duration</p>
        </div>

        <!-- VCR -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:border-orange-500/30 transition-colors">
            <div class="flex justify-between items-start mb-3">
                <div class="w-8 h-8 rounded-lg bg-orange-50 dark:bg-orange-500/10 text-orange-500 flex items-center justify-center">
                    <span class="material-symbols-rounded text-[18px]">check_circle</span>
                </div>
                <span class="px-2 py-0.5 rounded text-[10px] font-black bg-emerald-500/10 text-emerald-500">Today</span>
            </div>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($todayRollup->avg_vcr_percentage, 1) }}<span class="text-xs font-bold text-slate-400">%</span></h3>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mt-1">Completion Rate</p>
            <!-- Mini progress bar -->
            <div class="w-full bg-slate-100 dark:bg-slate-800 h-1 rounded-full mt-3 overflow-hidden">
                <div class="bg-orange-500 h-full" style="width: {{ $todayRollup->avg_vcr_percentage }}%"></div>
            </div>
        </div>

    </div>

    <!-- Chart Section -->
    <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
        <h4 class="text-sm font-black text-slate-800 dark:text-white tracking-tight mb-6 uppercase">30-Day Platform Engagement</h4>
        
        @if(count($chartData['dates']) > 1)
            <div class="h-[300px] w-full">
                <canvas id="engagementChart"></canvas>
            </div>
        @else
            <div class="h-[300px] w-full flex flex-col items-center justify-center border-2 border-dashed border-slate-100 dark:border-slate-800 rounded-xl bg-slate-50/50 dark:bg-slate-800/20">
                <div class="w-16 h-16 rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700 flex items-center justify-center mb-4 text-indigo-200 dark:text-indigo-900">
                    <span class="material-symbols-rounded text-4xl">show_chart</span>
                </div>
                <h5 class="text-sm font-black text-slate-800 dark:text-white tracking-tight mb-1">Insufficient Data for Charting</h5>
                <p class="text-xs text-slate-400 max-w-xs text-center font-medium leading-relaxed">The engagement trend chart will automatically generate once the system has compiled at least two days of user activity.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('assets/global/js/vendor/chart.js.2.8.0.js') }}"></script>
@if(count($chartData['dates']) > 1)
<script>
    "use strict";
    var ctx = document.getElementById('engagementChart').getContext('2d');
    var engagementChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartData['dates']),
            datasets: [
                {
                    label: 'Avg Watch Time (min)',
                    data: @json($chartData['avg_watch_time']),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.05)',
                    borderWidth: 2,
                    pointRadius: 2,
                    pointBackgroundColor: '#6366f1',
                    yAxisID: 'y'
                },
                {
                    label: 'Video Completion Rate (%)',
                    data: @json($chartData['avg_vcr']),
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249, 115, 22, 0.05)',
                    borderWidth: 2,
                    pointRadius: 2,
                    pointBackgroundColor: '#f97316',
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'top',
                labels: {
                    fontColor: '#64748b',
                    fontFamily: 'Inter, sans-serif',
                    fontSize: 11,
                    usePointStyle: true,
                    padding: 20
                }
            },
            elements: {
                line: { tension: 0.4 }
            },
            tooltips: {
                mode: 'index',
                intersect: false,
                backgroundColor: '#1e293b',
                titleFontColor: '#fff',
                bodyFontColor: '#cbd5e1',
                borderColor: '#334155',
                borderWidth: 1,
                padding: 10
            },
            scales: {
                xAxes: [{
                    gridLines: { display: false, drawBorder: false },
                    ticks: { fontColor: '#94a3b8', fontSize: 10, maxTicksLimit: 10 }
                }],
                yAxes: [{
                    id: 'y',
                    type: 'linear',
                    position: 'left',
                    gridLines: { color: '#f1f5f9', drawBorder: false, borderDash: [4, 4] },
                    ticks: { fontColor: '#94a3b8', fontSize: 10, beginAtZero: true }
                }, {
                    id: 'y1',
                    type: 'linear',
                    position: 'right',
                    gridLines: { display: false },
                    ticks: { fontColor: '#94a3b8', fontSize: 10, beginAtZero: true }
                }]
            }
        }
    });
</script>
@endif
@endpush
