@extends('layouts.app')

@section('content')
<style>
    /* Authentic YouTube Studio Styles */
    .yt-tab {
        color: #606060;
        font-family: "Roboto", "Arial", sans-serif;
        font-weight: 500;
        font-size: 14px;
        padding: 16px 32px;
        border-bottom: 3px solid transparent;
        transition: color 0.2s, border-bottom 0.2s;
    }
    .dark .yt-tab { color: #aaaaaa; }
    
    .yt-tab:hover { color: #0f0f0f; }
    .dark .yt-tab:hover { color: #ffffff; }
    
    .yt-tab.active {
        color: #065fd4;
        border-bottom: 3px solid #065fd4;
    }
    .dark .yt-tab.active {
        color: #3ea6ff;
        border-bottom: 3px solid #3ea6ff;
    }

    .yt-card {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 4px;
    }
    .dark .yt-card {
        background: #282828;
        border: 1px solid #3f3f3f;
    }

    .metric-btn {
        border-bottom: 4px solid transparent;
        transition: background-color 0.2s, border-bottom 0.2s;
    }
    .metric-btn:hover { background-color: #f9f9f9; }
    .dark .metric-btn:hover { background-color: #3f3f3f; }
    
    .metric-btn.active { background-color: #f9f9f9; }
    .dark .metric-btn.active { background-color: #3f3f3f; }

    /* Hide scrollbar */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div class="min-h-screen bg-[#f9f9f9] dark:bg-[#1f1f1f] text-[#0f0f0f] dark:text-white font-[Roboto,Arial,sans-serif] pb-28 lg:pb-8" x-data="studioAnalytics()">
    
    <!-- Header Title -->
    <div class="px-6 lg:px-8 py-6">
        <h1 class="text-[24px] font-bold tracking-tight">Channel analytics</h1>
    </div>

    <!-- Tabs Container -->
    <div class="px-6 lg:px-8 border-b border-[#e5e5e5] dark:border-[#3f3f3f] flex overflow-x-auto hide-scrollbar">
        <template x-for="tab in tabs" :key="tab.id">
            <button @click="switchTab(tab.id)"
                    class="yt-tab whitespace-nowrap focus:outline-none"
                    :class="activeTab === tab.id ? 'active' : ''"
                    x-text="tab.name">
            </button>
        </template>
    </div>

    <!-- Main Content Area -->
    <div class="max-w-[1600px] mx-auto px-6 lg:px-8 py-6 flex flex-col lg:flex-row gap-6">
        
        <!-- Left Area (Main Chart) -->
        <div class="flex-1 min-w-0 space-y-6">
            
            <!-- OVERVIEW TAB -->
            <div x-show="activeTab === 'overview'" x-transition.opacity.duration.200ms>
                <div class="yt-card overflow-hidden">
                    <!-- Title Area -->
                    <div class="p-6 pb-2">
                        <h2 class="text-[18px] font-normal text-[#0f0f0f] dark:text-white">Your channel got <span class="font-bold" x-text="overviewStats[0].value"></span> views in the last 28 days</h2>
                    </div>

                    <!-- Metrics Selector -->
                    <div class="flex overflow-x-auto hide-scrollbar border-b border-[#e5e5e5] dark:border-[#3f3f3f] snap-x">
                        <template x-for="(stat, index) in overviewStats" :key="index">
                            <button @click="changeOverviewMetric(index)"
                                    class="metric-btn flex-none w-[140px] lg:flex-1 p-4 text-left focus:outline-none relative snap-start"
                                    :class="activeOverviewMetric === index ? 'active' : ''"
                                    :style="activeOverviewMetric === index ? `border-bottom-color: ${stat.color}` : ''">
                                
                                <p class="text-[13px] text-[#606060] dark:text-[#aaaaaa] whitespace-normal line-clamp-2 min-h-[36px]" x-text="stat.label"></p>
                                <h3 class="text-[20px] lg:text-[24px] font-normal text-[#0f0f0f] dark:text-white mt-1" x-text="stat.value"></h3>
                                
                                <!-- Small placeholder sparkline -->
                                <div class="mt-2 text-[12px] flex items-center gap-1" :style="`color: ${stat.color}`">
                                    <span class="material-symbols-rounded text-[14px]">trending_up</span>
                                    <span>--</span>
                                </div>
                            </button>
                        </template>
                    </div>

                    <!-- Chart Container -->
                    <div class="p-6">
                        <div id="overview-chart" class="w-full h-[300px]"></div>
                    </div>
                </div>
            </div>

            <!-- REACH TAB -->
            <div x-show="activeTab === 'reach'" x-cloak x-transition.opacity.duration.200ms>
                <div class="yt-card overflow-hidden">
                    <div class="flex overflow-x-auto hide-scrollbar border-b border-[#e5e5e5] dark:border-[#3f3f3f] snap-x">
                        <div class="flex-none w-[160px] lg:flex-1 p-4 lg:p-6 border-r border-[#e5e5e5] dark:border-[#3f3f3f] snap-start">
                            <p class="text-[13px] text-[#606060] dark:text-[#aaaaaa] whitespace-normal line-clamp-2 min-h-[36px]">Impressions</p>
                            <h3 class="text-[20px] lg:text-[24px] font-normal text-[#0f0f0f] dark:text-white mt-1" x-text="reachData.totalImpressions"></h3>
                        </div>
                        <div class="flex-none w-[180px] lg:flex-1 p-4 lg:p-6 snap-start">
                            <p class="text-[13px] text-[#606060] dark:text-[#aaaaaa] whitespace-normal line-clamp-2 min-h-[36px]">Impressions click-through rate</p>
                            <h3 class="text-[20px] lg:text-[24px] font-normal text-[#0f0f0f] dark:text-white mt-1" x-text="reachData.averageCtr + '%'"></h3>
                        </div>
                    </div>
                    <div class="p-4 lg:p-6">
                        <div id="reach-chart" class="w-full h-[300px]"></div>
                    </div>
                </div>
            </div>

            <!-- AUDIENCE TAB -->
            <div x-show="activeTab === 'audience'" x-cloak x-transition.opacity.duration.200ms>
                <div class="yt-card overflow-hidden" x-show="hasAudience">
                    <div class="p-6 pb-2">
                        <h2 class="text-[18px] font-normal text-[#0f0f0f] dark:text-white">Who your viewers are</h2>
                    </div>
                    <div class="border-t border-[#e5e5e5] dark:border-[#3f3f3f]">
                        <div class="grid grid-cols-1 lg:grid-cols-2 divide-y lg:divide-y-0 lg:divide-x divide-[#e5e5e5] dark:divide-[#3f3f3f]">
                            <div class="p-6">
                                <h3 class="text-[13px] text-[#606060] dark:text-[#aaaaaa] font-medium mb-4">Devices</h3>
                                <div id="audience-devices-chart" class="h-[240px]"></div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-[13px] text-[#606060] dark:text-[#aaaaaa] font-medium mb-4">Top countries</h3>
                                <div id="audience-countries-chart" class="h-[240px]" x-show="hasCountryData"></div>
                                <div x-show="!hasCountryData" class="h-[240px] flex items-center justify-center text-center">
                                    <p class="text-[13px] text-[#606060] dark:text-[#aaaaaa]">No country data available.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="!hasAudience" class="yt-card p-16 text-center">
                    <span class="material-symbols-rounded text-6xl text-[#909090] dark:text-[#717171] mb-4">groups</span>
                    <h3 class="text-[18px] font-normal text-[#0f0f0f] dark:text-white">Not enough audience data</h3>
                    <p class="text-[13px] text-[#606060] dark:text-[#aaaaaa] mt-2">More viewers are required to show this report.</p>
                </div>
            </div>

            <div x-show="activeTab === 'revenue'" x-cloak class="yt-card p-16 text-center">
                <span class="material-symbols-rounded text-6xl text-[#065fd4] dark:text-[#3ea6ff] mb-4">monetization_on</span>
                <h3 class="text-[18px] font-normal text-[#0f0f0f] dark:text-white">Your estimated revenue</h3>
                <p class="text-[13px] text-[#606060] dark:text-[#aaaaaa] mt-2">Revenue data is processing and usually updates in 2 days.</p>
            </div>

        </div>

        <!-- Right Area (Realtime Sidebar) -->
        <div class="w-full lg:w-[320px] shrink-0">
            <div class="yt-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-[18px] font-normal text-[#0f0f0f] dark:text-white">Realtime</h3>
                    <div class="flex items-center gap-1 text-[#065fd4] dark:text-[#3ea6ff]">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-current"></span>
                        </span>
                        <span class="text-[12px] font-medium uppercase">Updating live</span>
                    </div>
                </div>

                <!-- 48 Hours -->
                <div class="border-t border-[#e5e5e5] dark:border-[#3f3f3f] py-4">
                    <div class="mb-2">
                        <span class="text-[20px] font-normal text-[#0f0f0f] dark:text-white" x-text="realtimeData.last48.total"></span>
                    </div>
                    <p class="text-[13px] text-[#606060] dark:text-[#aaaaaa] mb-2">Views · Last 48 hours</p>
                    <div class="h-[60px] w-full" id="realtime-48h-chart"></div>
                </div>

                <!-- 60 Mins -->
                <div class="border-t border-[#e5e5e5] dark:border-[#3f3f3f] py-4">
                    <div class="mb-2">
                        <span class="text-[20px] font-normal text-[#0f0f0f] dark:text-white" x-text="realtimeData.last60.total"></span>
                    </div>
                    <p class="text-[13px] text-[#606060] dark:text-[#aaaaaa] mb-2">Views · Last 60 minutes</p>
                    <div class="h-[60px] w-full" id="realtime-60m-chart"></div>
                </div>
                
                <div @click="toggleRealtimeDetails()" class="mt-2 text-[13px] text-[#065fd4] dark:text-[#3ea6ff] font-medium cursor-pointer hover:underline select-none">
                    <span x-text="showRealtimeDetails ? 'Show less' : 'See more'"></span>
                </div>

                <div x-show="showRealtimeDetails" x-collapse class="space-y-4 mt-4 border-t border-[#e5e5e5] dark:border-[#3f3f3f] pt-4">
                    <div>
                        <p class="text-[12px] text-[#606060] dark:text-[#aaaaaa] font-medium mb-2">Last 48 hours &mdash; breakdown</p>
                        <div class="space-y-1 max-h-[200px] overflow-y-auto custom-scrollbar">
                            <template x-for="(val, i) in realtimeData.last48.data" :key="i">
                                <div class="flex items-center justify-between text-[12px] text-[#0f0f0f] dark:text-white">
                                    <span x-text="realtimeData.last48.labels[i] || i" class="text-[#606060] dark:text-[#aaaaaa]"></span>
                                    <span class="font-medium" x-text="val.toLocaleString()"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div>
                        <p class="text-[12px] text-[#606060] dark:text-[#aaaaaa] font-medium mb-2">Last 60 minutes &mdash; breakdown</p>
                        <div class="space-y-1 max-h-[200px] overflow-y-auto">
                            <template x-for="(val, i) in realtimeData.last60.data" :key="i">
                                <div class="flex items-center justify-between text-[12px] text-[#0f0f0f] dark:text-white">
                                    <span x-text="realtimeData.last60.labels[i] || i" class="text-[#606060] dark:text-[#aaaaaa]"></span>
                                    <span class="font-medium" x-text="val.toLocaleString()"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Load ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('studioAnalytics', () => ({
            activeTab: 'overview',
            activeOverviewMetric: 0,
            tabs: [
                { id: 'overview', name: 'Overview' },
                { id: 'reach', name: 'Reach' },
                { id: 'audience', name: 'Audience' },
                { id: 'revenue', name: 'Revenue' },
            ],
            overviewStats: [
                { label: 'Views', value: '0', color: '#065fd4' }, // Blue
                { label: 'Watch time (hours)', value: '0.0', color: '#8e24aa' }, // Purple
                { label: 'Subscribers', value: '0', color: '#00897b' }, // Teal
                { label: 'Estimated revenue', value: '₹0.00', color: '#00c853' } // Green
            ],
            reachData: { totalImpressions: 0, averageCtr: 0 },
            realtimeData: { last48: { total: 0, data: [], labels: [] }, last60: { total: 0, data: [], labels: [] } },
            audienceData: { countries: { labels: [], data: [] }, devices: { labels: [], data: [] } },
            hasAudience: false,
            hasCountryData: false,
            showRealtimeDetails: false,
            charts: {},
            rawData: null,

            init() {
                this.loadOverviewData();
                this.loadReachData();
                this.loadAudienceData();
                this.loadRealtimeData();
                setInterval(() => this.loadRealtimeData(), 15000);
            },

            switchTab(tabId) {
                this.activeTab = tabId;
                setTimeout(() => {
                    if(tabId === 'overview' && this.charts.overview) this.charts.overview.render();
                    if(tabId === 'reach' && this.charts.reach) this.charts.reach.render();
                    if(tabId === 'audience') {
                        if(this.charts.audienceDevices) this.charts.audienceDevices.render();
                        if(this.charts.audienceCountries) this.charts.audienceCountries.render();
                    }
                }, 50);
            },

            changeOverviewMetric(index) {
                this.activeOverviewMetric = index;
                this.renderOverviewChart();
            },

            async loadOverviewData() {
                const res = await fetch("{{ route('studio.analytics.overview') }}");
                this.rawData = await res.json();
                
                const totalViews = this.rawData.views.reduce((a, b) => a + b, 0);
                const totalSubs = this.rawData.subscribers.reduce((a, b) => a + b, 0);
                const totalRev = this.rawData.revenue.reduce((a, b) => a + b, 0);

                this.overviewStats[0].value = totalViews.toLocaleString();
                this.overviewStats[1].value = this.rawData.totalWatchTimeHours.toFixed(1);
                this.overviewStats[2].value = (totalSubs > 0 ? '+' : '') + totalSubs.toLocaleString();
                this.overviewStats[3].value = '₹' + totalRev.toFixed(2);

                this.renderOverviewChart();
            },

            renderOverviewChart() {
                if(!this.rawData) return;
                
                let dataToRender = [];
                const color = this.overviewStats[this.activeOverviewMetric].color;
                const name = this.overviewStats[this.activeOverviewMetric].label;

                switch(this.activeOverviewMetric) {
                    case 0: dataToRender = this.rawData.views; break;
                    case 1: dataToRender = this.rawData.watchTime; break;
                    case 2: dataToRender = this.rawData.subscribers; break;
                    case 3: dataToRender = this.rawData.revenue; break;
                }

                const isDark = document.documentElement.classList.contains('dark');

                const options = {
                    series: [{ name: name, data: dataToRender }],
                    chart: { 
                        type: 'area', 
                        height: 300, 
                        toolbar: { show: false }, 
                        background: 'transparent',
                        fontFamily: 'Roboto, Arial, sans-serif'
                    },
                    colors: [color],
                    fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0.0, stops: [0, 100] } },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 2 },
                    xaxis: { 
                        categories: this.rawData.dates, 
                        tooltip: { enabled: false },
                        axisBorder: { show: false }, 
                        axisTicks: { show: false },
                        tickAmount: window.innerWidth < 768 ? 4 : 8,
                        labels: { 
                            style: { colors: isDark ? '#aaaaaa' : '#606060', fontSize: '12px' },
                            rotate: 0,
                            hideOverlappingLabels: true,
                        }
                    },
                    yaxis: { 
                        labels: { 
                            formatter: (val) => val.toLocaleString(),
                            style: { colors: isDark ? '#aaaaaa' : '#606060', fontSize: '12px' } 
                        } 
                    },
                    grid: { borderColor: isDark ? '#3f3f3f' : '#e5e5e5', strokeDashArray: 0 },
                    theme: { mode: isDark ? 'dark' : 'light' }
                };

                if(this.charts.overview) this.charts.overview.destroy();
                this.charts.overview = new ApexCharts(document.querySelector("#overview-chart"), options);
                this.charts.overview.render();
            },

            async loadReachData() {
                const res = await fetch("{{ route('studio.analytics.reach') }}");
                const data = await res.json();
                
                this.reachData.totalImpressions = data.totalImpressions.toLocaleString();
                this.reachData.averageCtr = data.averageCtr;

                const isDark = document.documentElement.classList.contains('dark');

                const options = {
                    series: [{ name: 'Impressions', type: 'column', data: data.impressions }, { name: 'CTR (%)', type: 'line', data: data.ctr }],
                    chart: { height: 300, type: 'line', toolbar: { show: false }, background: 'transparent', fontFamily: 'Roboto, Arial, sans-serif' },
                    stroke: { width: [0, 2], curve: 'smooth' },
                    colors: [isDark ? '#3f3f3f' : '#e5e5e5', '#065fd4'],
                    dataLabels: { enabled: false },
                    labels: data.dates,
                    xaxis: { 
                        type: 'category',
                        axisBorder: { show: false }, 
                        axisTicks: { show: false },
                        tickAmount: window.innerWidth < 768 ? 4 : 8,
                        labels: { 
                            style: { colors: isDark ? '#aaaaaa' : '#606060', fontSize: '12px' },
                            rotate: 0,
                            hideOverlappingLabels: true,
                        }
                    },
                    yaxis: [
                        { title: { text: 'Impressions', style: { color: isDark ? '#aaaaaa' : '#606060', fontWeight: 'normal' } }, labels: { style: { colors: isDark ? '#aaaaaa' : '#606060' } } }, 
                        { opposite: true, title: { text: 'Click-through rate', style: { color: isDark ? '#aaaaaa' : '#606060', fontWeight: 'normal' } }, labels: { style: { colors: isDark ? '#aaaaaa' : '#606060' } } }
                    ],
                    grid: { borderColor: isDark ? '#3f3f3f' : '#e5e5e5' },
                    theme: { mode: isDark ? 'dark' : 'light' }
                };

                if(this.charts.reach) this.charts.reach.destroy();
                this.charts.reach = new ApexCharts(document.querySelector("#reach-chart"), options);
                this.charts.reach.render();
            },

            async loadAudienceData() {
                try {
                    const res = await fetch("{{ route('studio.analytics.audience') }}");
                    const data = await res.json();

                    this.audienceData = {
                        countries: data.countries || { labels: [], data: [] },
                        devices: data.devices || { labels: [], data: [] },
                    };

                    const deviceTotal = (this.audienceData.devices.data || []).reduce((a, b) => a + b, 0);
                    const countryLabels = this.audienceData.countries.labels || [];
                    const countryTotal = (this.audienceData.countries.data || []).reduce((a, b) => a + b, 0);

                    this.hasAudience = deviceTotal > 0;
                    this.hasCountryData = countryTotal > 0 && countryLabels.length > 0 && countryLabels[0] !== 'Unknown';

                    this.renderAudienceCharts();
                } catch (e) {
                    console.error('Failed to load audience data', e);
                }
            },

            renderAudienceCharts() {
                const isDark = document.documentElement.classList.contains('dark');
                const labelColor = isDark ? '#aaaaaa' : '#606060';
                const borderColor = isDark ? '#3f3f3f' : '#e5e5e5';
                const devices = this.audienceData.devices || { labels: [], data: [] };
                const countries = this.audienceData.countries || { labels: [], data: [] };

                if (this.charts.audienceDevices) { this.charts.audienceDevices.destroy(); this.charts.audienceDevices = null; }
                if (this.hasAudience && document.querySelector("#audience-devices-chart")) {
                    this.charts.audienceDevices = new ApexCharts(document.querySelector("#audience-devices-chart"), {
                        chart: { type: 'donut', height: 240, toolbar: { show: false }, background: 'transparent', fontFamily: 'Roboto, Arial, sans-serif' },
                        series: devices.data,
                        labels: devices.labels,
                        colors: ['#065fd4', '#00897b', '#f9a825', '#d81b60'],
                        dataLabels: { enabled: false },
                        legend: { position: 'bottom', labels: { colors: labelColor } },
                        stroke: { width: 0 },
                        plotOptions: { pie: { donut: { labels: { show: true, total: { show: true, label: 'Views', color: labelColor, formatter: (w) => w.globals.seriesTotals.reduce((a, b) => a + b, 0) } } } } },
                        theme: { mode: isDark ? 'dark' : 'light' }
                    });
                    this.charts.audienceDevices.render();
                }

                if (this.charts.audienceCountries) { this.charts.audienceCountries.destroy(); this.charts.audienceCountries = null; }
                if (this.hasAudience && this.hasCountryData && document.querySelector("#audience-countries-chart")) {
                    this.charts.audienceCountries = new ApexCharts(document.querySelector("#audience-countries-chart"), {
                        chart: { type: 'bar', height: 240, toolbar: { show: false }, background: 'transparent', fontFamily: 'Roboto, Arial, sans-serif' },
                        series: [{ name: 'Views', data: countries.data }],
                        plotOptions: { bar: { horizontal: true, barHeight: '50%', borderRadius: 2 } },
                        colors: ['#065fd4'],
                        dataLabels: { enabled: false },
                        xaxis: { categories: countries.labels, labels: { style: { colors: labelColor, fontSize: '12px' } }, axisBorder: { show: false }, axisTicks: { show: false } },
                        yaxis: { labels: { style: { colors: labelColor } } },
                        grid: { borderColor: borderColor },
                        theme: { mode: isDark ? 'dark' : 'light' }
                    });
                    this.charts.audienceCountries.render();
                }
            },

            async loadRealtimeData() {
                const res = await fetch("{{ route('studio.analytics.realtime') }}");
                const data = await res.json();
                
                this.realtimeData.last48.total = data.last48Hours.total.toLocaleString();
                this.realtimeData.last48.data = data.last48Hours.data;
                this.realtimeData.last48.labels = data.last48Hours.labels;
                this.realtimeData.last60.total = data.last60Minutes.total.toLocaleString();
                this.realtimeData.last60.data = data.last60Minutes.data;
                this.realtimeData.last60.labels = data.last60Minutes.labels;

                const isDark = document.documentElement.classList.contains('dark');
                const barColor = '#065fd4'; // YT Blue for realtime bars

                const commonOptions = {
                    chart: { type: 'bar', sparkline: { enabled: true }, background: 'transparent', animations: { enabled: true, speed: 300 } },
                    plotOptions: { bar: { columnWidth: '85%' } },
                    colors: [barColor],
                    tooltip: { fixed: { enabled: false }, x: { show: true }, y: { title: { formatter: function () { return 'Views:' } } }, marker: { show: false } }
                };

                const options48 = { ...commonOptions, series: [{ name: 'Views', data: data.last48Hours.data }], xaxis: { categories: data.last48Hours.labels } };
                if(!this.charts.realtime48) {
                    this.charts.realtime48 = new ApexCharts(document.querySelector("#realtime-48h-chart"), options48);
                    this.charts.realtime48.render();
                } else {
                    this.charts.realtime48.updateSeries([{ data: data.last48Hours.data }]);
                }

                const options60 = { ...commonOptions, series: [{ name: 'Views', data: data.last60Minutes.data }], xaxis: { categories: data.last60Minutes.labels } };
                if(!this.charts.realtime60) {
                    this.charts.realtime60 = new ApexCharts(document.querySelector("#realtime-60m-chart"), options60);
                    this.charts.realtime60.render();
                } else {
                    this.charts.realtime60.updateSeries([{ data: data.last60Minutes.data }]);
                }
            },

            toggleRealtimeDetails() {
                this.showRealtimeDetails = !this.showRealtimeDetails;
            },
        }));
    });
</script>
@endsection
