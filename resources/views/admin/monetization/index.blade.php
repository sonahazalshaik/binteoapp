@extends('admin.layouts.app')

@section('title', 'Revenue Control Center')
@section('header_title', 'Monetization')

@section('content')
<div x-data="{ 
    isMonetized: {{ $settings->is_monetization_enabled ? 'true' : 'false' }},
    commission: {{ $settings->platform_commission }},
    showLogs: true,
    showSettingsModal: false,
    pulseTimeframe: 'monthly'
}" class="space-y-8 animate-in fade-in duration-700">

    <!-- Header & Global Signal (Clean White & Orange Native Theme) -->
    <div class="relative p-6 sm:p-8 rounded-3xl bg-white dark:bg-[#111111] border border-slate-200/80 dark:border-white/10 shadow-sm">
        <div class="relative flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <!-- Left Header Details -->
            <div class="space-y-2 max-w-xl">
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 text-xs font-bold border border-orange-200/60 dark:border-orange-500/20 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                        Active Network
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">Revenue Center</h1>
                <p class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400 leading-relaxed">
                    Centralized command for platform-wide monetization and yield management.
                </p>
            </div>

            <!-- Right Control Box -->
            <div class="w-full md:w-auto p-4 sm:p-5 rounded-2xl bg-slate-50 dark:bg-white/5 border border-slate-200/80 dark:border-white/10 flex items-center justify-between md:justify-start gap-6">
                <div class="space-y-0.5">
                    <p class="text-xs font-bold text-slate-900 dark:text-white">Global Monetization</p>
                    <p class="text-[11px] font-semibold transition-colors duration-300 flex items-center gap-1.5"
                       :class="isMonetized ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400'">
                        <span class="w-1.5 h-1.5 rounded-full" :class="isMonetized ? 'bg-emerald-500 animate-ping' : 'bg-slate-400'"></span>
                        <span x-text="isMonetized ? 'Signal Online' : 'Network Halted'"></span>
                    </p>
                </div>
                <form action="{{ route('admin.monetization.toggle-global') }}" method="POST" class="shrink-0">
                    @csrf
                    <button type="submit" 
                            class="relative inline-flex h-8 w-16 items-center rounded-full transition-colors duration-300 focus:outline-none shadow-sm"
                            :class="isMonetized ? 'bg-orange-600' : 'bg-slate-300 dark:bg-slate-700'">
                        <span class="inline-block h-6 w-6 transform rounded-full bg-white shadow-md transition-transform duration-300"
                              :class="isMonetized ? 'translate-x-9' : 'translate-x-1'"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bento Stats Grid (Orange & White Accent Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Revenue -->
        <div class="p-6 rounded-3xl bg-white dark:bg-[#111111] border border-orange-100 dark:border-white/10 shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 rounded-2xl bg-orange-500/10 text-orange-600 flex items-center justify-center border border-orange-500/15 group-hover:scale-105 transition-transform">
                    <span class="material-symbols-rounded text-xl">account_balance_wallet</span>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-orange-500/10 text-orange-600 text-[10px] font-bold">+12% growth</span>
            </div>
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Total Yield</p>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-center gap-1">
                <span class="text-orange-600 font-bold">₹</span>{{ number_format($totalEarnings, 2) }}
            </h2>
        </div>

        <!-- Network Commission -->
        <div class="p-6 rounded-3xl bg-white dark:bg-[#111111] border border-orange-100 dark:border-white/10 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 rounded-2xl bg-orange-500/10 text-orange-600 flex items-center justify-center border border-orange-500/15 group-hover:scale-105 transition-transform">
                    <span class="material-symbols-rounded text-xl">percent</span>
                </div>
                <button @click="showSettingsModal = true" class="text-xs font-bold text-orange-600 hover:underline underline-offset-4">Update Rate</button>
            </div>
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Network Commission</p>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $settings->platform_commission }}%</h2>
        </div>

        <!-- AdSense Status -->
        <div class="p-6 rounded-3xl bg-white dark:bg-[#111111] border border-orange-100 dark:border-white/10 shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 rounded-2xl bg-amber-500/10 text-amber-600 flex items-center justify-center border border-amber-500/15 group-hover:scale-105 transition-transform">
                    <span class="material-symbols-rounded text-xl">ads_click</span>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-600 text-[10px] font-bold">Future Sync</span>
            </div>
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Google AdSense</p>
            <h2 class="text-xl font-bold text-amber-600 tracking-tight">Ready to Sync</h2>
        </div>

        <!-- Active Videos -->
        <div class="p-6 rounded-3xl bg-white dark:bg-[#111111] border border-orange-100 dark:border-white/10 shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 rounded-2xl bg-orange-500/10 text-orange-600 flex items-center justify-center border border-orange-500/15 group-hover:scale-105 transition-transform">
                    <span class="material-symbols-rounded text-xl">movie</span>
                </div>
            </div>
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Earning Videos</p>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $videoEarnings->total() }}</h2>
        </div>
    </div>

    <!-- Revenue Pulse (Dynamic & Accurate Chart Section) -->
    <div class="p-8 rounded-[3rem] bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 mb-6">
            <div>
                <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">Revenue Pulse</h3>
                <p class="text-[10px] font-medium text-slate-400 uppercase tracking-[0.2em] mt-1" x-text="pulseTimeframe === 'monthly' ? 'Accurate yield trends over the last 30 days' : 'Accurate yield trends over the last 7 days'"></p>
            </div>
            <div class="flex items-center gap-2 p-1 rounded-2xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10">
                <button type="button" 
                        @click="pulseTimeframe = 'weekly'; if(window.updateRevenuePulseChart) window.updateRevenuePulseChart('weekly');" 
                        :class="pulseTimeframe === 'weekly' ? 'bg-white dark:bg-white/10 shadow-sm text-slate-900 dark:text-white' : 'text-slate-400 hover:text-slate-600'"
                        class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">Weekly</button>
                <button type="button" 
                        @click="pulseTimeframe = 'monthly'; if(window.updateRevenuePulseChart) window.updateRevenuePulseChart('monthly');" 
                        :class="pulseTimeframe === 'monthly' ? 'bg-white dark:bg-white/10 shadow-sm text-slate-900 dark:text-white' : 'text-slate-400 hover:text-slate-600'"
                        class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">Monthly</button>
            </div>
        </div>
        
        <div class="w-full min-h-[260px] relative">
            <div id="revenuePulseChart" class="w-full h-full min-h-[260px]"></div>
            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
            <script>
                (function() {
                    function renderPulse() {
                        if (typeof ApexCharts === 'undefined') {
                            setTimeout(renderPulse, 100);
                            return;
                        }
                        const pulseData = @json($pulseData ?? []);
                        let chartInstance = null;

                        window.updateRevenuePulseChart = function(type) {
                            const data = pulseData[type] || { labels: [], values: [] };
                            const options = {
                                series: [{ name: 'Revenue (₹)', data: data.values || [0, 0, 0, 0, 0, 0, 0] }],
                                chart: { type: 'area', height: 260, toolbar: { show: false }, animations: { enabled: true } },
                                colors: ['#f97316'],
                                stroke: { curve: 'smooth', width: 3 },
                                fill: { type: 'gradient', gradient: { opacityFrom: 0.45, opacityTo: 0.05 } },
                                xaxis: { categories: data.labels || [], labels: { style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 } } },
                                yaxis: { labels: { formatter: (val) => '₹' + Number(val).toFixed(2), style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 } } },
                                grid: { borderColor: 'rgba(226, 232, 240, 0.5)', strokeDashArray: 4 },
                                tooltip: { theme: 'dark', y: { formatter: (val) => '₹' + Number(val).toFixed(2) } }
                            };

                            const chartEl = document.querySelector("#revenuePulseChart");
                            if (!chartEl) return;
                            if (chartInstance) chartInstance.destroy();
                            chartInstance = new ApexCharts(chartEl, options);
                            chartInstance.render();
                        };

                        window.updateRevenuePulseChart('monthly');
                    }
                    if (document.readyState === 'complete' || document.readyState === 'interactive') {
                        setTimeout(renderPulse, 50);
                    } else {
                        document.addEventListener('DOMContentLoaded', renderPulse);
                    }
                })();
            </script>
        </div>
    </div>

    <!-- Earnings Feed (The Dynamic Part) -->
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[3rem] overflow-hidden shadow-sm">
        <div class="p-8 border-b border-slate-100 dark:border-white/10" x-data="{ showAdvanced: {{ request()->hasAny(['date', 'min_amount', 'max_amount']) ? 'true' : 'false' }} }">
            <form action="" method="GET" class="space-y-4">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <h2 class="text-xl font-black tracking-tighter text-slate-900 dark:text-white uppercase ">Yield Logs</h2>
                        <p class="text-[9px] font-bold text-slate-500 dark:text-white/30 uppercase tracking-widest mt-1">Recently generated revenue from platform videos</p>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                        <!-- Quick Search -->
                        <div class="relative flex-grow md:w-64 group">
                            <input type="text" name="search" value="{{ request()->search }}" placeholder="Search Video or Creator..." 
                                   class="w-full pl-11 pr-20 py-3 bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/5 rounded-2xl text-[11px] font-bold text-slate-900 dark:text-white outline-none focus:border-emerald-500/50 transition-all">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-rounded text-slate-400 text-lg group-focus-within:text-emerald-500 transition-colors">search</span>
                            
                            <button type="submit" class="absolute right-1.5 top-1.5 bottom-1.5 px-3 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-black text-[8px] font-black uppercase tracking-widest hover:bg-emerald-500 hover:text-white transition-all">
                                Go
                            </button>
                        </div>

                        <!-- Advanced Toggle -->
                        <button type="button" @click="showAdvanced = !showAdvanced" 
                                :class="showAdvanced ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'bg-emerald-500/10 text-emerald-500'"
                                class="h-12 px-4 rounded-2xl border border-emerald-500/10 flex items-center gap-2 text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all">
                            <span class="material-symbols-rounded text-lg" :class="showAdvanced ? 'fill-1' : ''">tune</span>
                            {{ request()->hasAny(['date', 'min_amount', 'max_amount']) ? 'Active' : 'Options' }}
                        </button>

                        <!-- Reset -->
                        <a href="{{ route('admin.monetization.index') }}" class="h-12 w-12 rounded-2xl bg-slate-100 dark:bg-white/5 text-slate-400 flex items-center justify-center hover:text-emerald-500 transition-all" title="Clear Pulse Grid">
                            <span class="material-symbols-rounded text-xl">restart_alt</span>
                        </a>
                    </div>
                </div>

                <!-- Advanced Options -->
                <div x-show="showAdvanced" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6 rounded-[2rem] bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5" x-cloak>
                    
                    <!-- Date Window -->
                    <div>
                        <label class="text-[8px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1.5 block px-1">Temporal Window</label>
                        <input name="date" type="text" class="date-range h-10 w-full px-4 rounded-xl bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 text-[10px] font-bold text-slate-600 dark:text-white/60 outline-none cursor-pointer" placeholder="Select Range..." value="{{ request()->date }}">
                    </div>

                    <!-- Revenue Range -->
                    <div>
                        <label class="text-[8px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1.5 block px-1">Yield Range (Min-Max)</label>
                        <div class="flex items-center gap-2">
                            <input type="number" step="any" name="min_amount" value="{{ request()->min_amount }}" class="h-10 w-full px-3 rounded-xl bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 text-[10px] font-bold text-slate-600 dark:text-white/60 outline-none" placeholder="Min Revenue">
                            <span class="w-2 h-[1px] bg-slate-300 dark:bg-white/10"></span>
                            <input type="number" step="any" name="max_amount" value="{{ request()->max_amount }}" class="h-10 w-full px-3 rounded-xl bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 text-[10px] font-bold text-slate-600 dark:text-white/60 outline-none" placeholder="Max Revenue">
                        </div>
                    </div>

                    <!-- Action -->
                    <div class="flex items-end justify-end">
                        <button type="submit" class="h-10 px-8 rounded-xl bg-emerald-500 text-white text-[10px] font-black uppercase tracking-[0.2em] hover:bg-emerald-600 hover:shadow-lg hover:shadow-emerald-500/30 transition-all flex items-center gap-2">
                            <span class="material-symbols-rounded text-sm">filter_alt</span>
                            Apply Yield Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-slate-50 dark:bg-white/[0.02]">
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100 dark:border-white/5">Target Video Segment</th>
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100 dark:border-white/5">Engagement Pulse</th>
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100 dark:border-white/5">Timestamp</th>
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-widest text-slate-400 text-right border-b border-slate-100 dark:border-white/5">Net Yield</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($videoEarnings as $earning)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-rounded text-orange-600 text-lg">play_circle</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[12px] font-black text-slate-900 dark:text-white tracking-tight truncate max-w-[300px] uppercase ">{{ $earning->video->title }}</p>
                                    <p class="text-[8px] font-black text-slate-400 dark:text-white/20 uppercase mt-1 tracking-widest">Creator: {{ $earning->video->user->name ?? 'Anonymous' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-6">
                                <div class="flex flex-col">
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-tighter">Impressions</span>
                                    <span class="text-[12px] font-black text-slate-900 dark:text-white">{{ number_format($earning->ad_impressions) }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-tighter">Yield Clicks</span>
                                    <span class="text-[12px] font-black text-slate-900 dark:text-white">{{ number_format($earning->ad_clicks) }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-widest">{{ $earning->created_at->format('M d, Y') }}</p>
                            <p class="text-[8px] font-bold text-slate-400 uppercase mt-0.5 tracking-widest">{{ $earning->created_at->format('H:i A') }}</p>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="inline-flex flex-col items-end">
                                <span class="text-[13px] font-black text-orange-600 dark:text-orange-400 bg-orange-500/10 px-4 py-2 rounded-xl border border-orange-500/20 shadow-sm">+₹{{ number_format($earning->estimated_revenue, 2) }}</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-10 py-32 text-center">
                            <div class="flex flex-col items-center gap-6 opacity-20">
                                <div class="w-20 h-20 rounded-[2rem] border-2 border-dashed border-slate-400 flex items-center justify-center">
                                    <span class="material-symbols-rounded text-4xl">receipt_long</span>
                                </div>
                                <p class="text-xs font-black uppercase tracking-[0.4em] leading-relaxed">No revenue logs detected in sync</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($videoEarnings->hasPages())
        <div class="p-8 border-t border-slate-100 dark:border-white/10">
            {{ $videoEarnings->links() }}
        </div>
        @endif
    </div>
    <!-- Settings Modal -->
    <div x-show="showSettingsModal" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
        <div @click.away="showSettingsModal = false" 
             class="w-full max-w-md bg-white dark:bg-[#121212] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-2xl p-10 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-500/10 rounded-full blur-3xl"></div>
            
            <h3 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tighter mb-2">Adjust Network Yield</h3>
            <p class="text-[10px] font-medium text-slate-400 uppercase tracking-widest mb-8">Set the global platform commission percentage.</p>

            <form action="{{ route('admin.monetization.update-settings') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-3">
                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Platform Commission (%)</label>
                    <div class="relative">
                        <input type="number" name="platform_commission" x-model="commission" step="0.01" min="0" max="100"
                               class="w-full h-16 bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/5 rounded-2xl px-6 text-xl font-black text-slate-900 dark:text-white outline-none focus:border-emerald-500/50 transition-all">
                        <span class="absolute right-6 top-1/2 -translate-y-1/2 text-xl font-black text-slate-300 dark:text-white/10">%</span>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="button" @click="showSettingsModal = false" 
                            class="flex-1 py-4 rounded-2xl bg-slate-100 dark:bg-white/5 text-slate-500 text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-white/10 transition-all">Cancel</button>
                    <button type="submit" 
                            class="flex-1 py-4 rounded-2xl orange-gradient-primary text-white text-[10px] font-black uppercase tracking-widest shadow-lg shadow-orange-500/20 hover:scale-[1.02] active:scale-95 transition-all">Apply Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes dash {
        0% { stroke-dasharray: 0, 1500; stroke-dashoffset: 0; }
        50% { stroke-dasharray: 1500, 1500; stroke-dashoffset: 0; }
        100% { stroke-dasharray: 1500, 1500; stroke-dashoffset: -1500; }
    }
    [x-cloak] { display: none !important; }
</style>

@push('script-lib')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ asset('assets/global/js/vendor/apexcharts.min.js') }}"></script>
@endpush

@push('script')
<script>
    (function () {
        function initPulseChart() {
            if (typeof ApexCharts === 'undefined') {
                setTimeout(initPulseChart, 100);
                return;
            }

            const pulseData = @json($pulseData ?? []);
            let chart = null;

            function renderPulseChart(type) {
                const data = pulseData[type] || { labels: [], values: [] };
                
                const options = {
                    series: [{
                        name: 'Revenue',
                        data: data.values || [0, 0, 0, 0, 0, 0, 0]
                    }],
                    chart: {
                        type: 'area',
                        height: 260,
                        sparkline: { enabled: false },
                        toolbar: { show: false },
                        animations: { enabled: true }
                    },
                    colors: ['#f97316'],
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.45,
                            opacityTo: 0.05,
                            stops: [0, 90, 100]
                        }
                    },
                    xaxis: {
                        categories: data.labels || [],
                        labels: {
                            style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 }
                        },
                        axisBorder: { show: false },
                        axisTicks: { show: false }
                    },
                    yaxis: {
                        labels: {
                            formatter: (val) => '₹' + Number(val).toFixed(2),
                            style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 }
                        }
                    },
                    grid: {
                        borderColor: 'rgba(226, 232, 240, 0.5)',
                        strokeDashArray: 4
                    },
                    tooltip: {
                        theme: 'dark',
                        y: {
                            formatter: (val) => '₹' + Number(val).toFixed(2)
                        }
                    }
                };

                const chartEl = document.querySelector("#revenuePulseChart");
                if (!chartEl) return;

                if (chart) {
                    chart.destroy();
                }
                chart = new ApexCharts(chartEl, options);
                chart.render();
            }

            renderPulseChart('monthly');

            window.updateRevenuePulseChart = function(type) {
                renderPulseChart(type);
            };
        }

        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            setTimeout(initPulseChart, 50);
        } else {
            document.addEventListener('DOMContentLoaded', initPulseChart);
        }
    })();
</script>
@endpush
@endsection

