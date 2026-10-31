@extends('admin.layouts.app')

@section('panel')
<div class="space-y-6 pb-10">
    <!-- Top Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">MAU</p>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($latest->mau ?? 0) }}</h3>
            <div class="flex items-center gap-1 mt-2">
                <span class="text-[10px] font-bold text-emerald-500 bg-emerald-50 dark:bg-emerald-500/10 px-1.5 py-0.5 rounded">Active</span>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">DAU/MAU Ratio</p>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($dauMauRatio, 1) }}%</h3>
            <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full mt-4 overflow-hidden">
                <div class="bg-orange-500 h-full" style="width: {{ $dauMauRatio }}%"></div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">New Users (Today)</p>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($newUsersToday) }}</h3>
            <div class="flex items-center gap-1 mt-2">
                <span class="text-xs text-slate-500">Real-time update</span>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Active Creators</p>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($latest->active_creators ?? 0) }}</h3>
            <div class="flex items-center gap-1 mt-2">
                <span class="text-xs text-slate-500">Last aggregated</span>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Videos Uploaded</p>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($videosToday) }}</h3>
            <div class="flex items-center gap-1 mt-2 text-orange-500">
                <i class="las la-arrow-up"></i>
                <span class="text-xs font-bold">Today</span>
            </div>
        </div>
    </div>

    <!-- Engagement Chart -->
    <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h4 class="text-lg font-black text-slate-800 dark:text-white tracking-tight">User Engagement Trend</h4>
                <p class="text-sm text-slate-400">Daily active users vs Monthly active users over 30 days</p>
            </div>
            <div class="flex gap-2">
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-500">
                    <span class="w-2 h-2 rounded-full bg-orange-500"></span> DAU
                </div>
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-500">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span> MAU
                </div>
            </div>
        </div>
        <div id="engagementChart" class="h-80"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Session Health -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <h4 class="text-lg font-black text-slate-800 dark:text-white tracking-tight mb-6">Session Health</h4>
            <div class="space-y-6">
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-bold text-slate-500 uppercase tracking-wider">Avg Session Duration</span>
                        <span class="text-sm font-black text-slate-800 dark:text-white">{{ gmdate("H:i:s", $latest->avg_session_duration ?? 0) }}</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                        <div class="bg-indigo-500 h-full" style="width: 65%"></div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 pt-4">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700">
                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Sessions Per User</p>
                        <p class="text-xl font-black text-slate-800 dark:text-white">{{ $sessionsPerUser ?? '2.4' }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700">
                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Return Rate</p>
                        <p class="text-xl font-black text-slate-800 dark:text-white">{{ $returnRate ?? '42' }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Aggregation Status -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <h4 class="text-lg font-black text-slate-800 dark:text-white tracking-tight mb-6">Aggregation Engine Status</h4>
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 rounded-full border-4 border-emerald-500 border-t-transparent animate-spin flex items-center justify-center relative">
                    <div class="absolute inset-0 flex items-center justify-center rotate-0">
                        <span class="material-symbols-rounded text-emerald-500 text-3xl">check_circle</span>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800 dark:text-white">Job: analytics:aggregate</p>
                    <p class="text-xs text-slate-400 mt-1">Last successful run: <span class="text-slate-600 dark:text-slate-200 font-bold">{{ $latest ? $latest->created_at->diffForHumans() : 'Never' }}</span></p>
                    <div class="flex items-center gap-2 mt-4">
                        <span class="flex h-2 w-2 rounded-full bg-emerald-500"></span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-emerald-500">Healthy</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Currently Online Users -->
    <div id="live-users-container" class="bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 relative">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h4 class="text-lg font-black text-slate-800 dark:text-white tracking-tight">@lang('Currently Live Users')</h4>
                <p class="text-sm text-slate-400">@lang('Users active in the last 5 minutes')</p>
            </div>
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> <span id="online-users-total">{{ $onlineUsers->total() }}</span> @lang('Online')
            </div>
        </div>
        
        @if($onlineUsers->isEmpty())
            <div class="text-center py-8" id="empty-online-users">
                <span class="material-symbols-rounded text-5xl text-slate-300 dark:text-slate-700 mb-3">group_off</span>
                <p class="text-sm font-bold text-slate-500">@lang('No users currently online.')</p>
            </div>
        @else
            <div id="online-users-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 max-h-[400px] overflow-y-auto pr-2 scrollbar-hide">
                @include('admin.analytics.partials.online_users')
            </div>
            
            <div id="loading-spinner" class="hidden justify-center py-4">
                <div class="w-6 h-6 border-2 border-emerald-500/20 border-t-emerald-500 rounded-full animate-spin"></div>
            </div>
        @endif
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
                name: 'DAU',
                data: @json($history->pluck('dau'))
            }, {
                name: 'MAU',
                data: @json($history->pluck('mau'))
            }],
            chart: {
                height: 320,
                type: 'area',
                toolbar: { show: false },
                zoom: { enabled: false },
                sparkline: { enabled: false }
            },
            colors: ['#f97316', '#6366f1'],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [20, 100, 100, 100]
                }
            },
            xaxis: {
                categories: @json($history->pluck('recorded_at')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#94a3b8', fontWeight: 600 } }
            },
            yaxis: {
                labels: { style: { colors: '#94a3b8', fontWeight: 600 } }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                padding: { left: 0, right: 0 }
            },
            tooltip: {
                theme: 'dark',
                x: { show: true },
            }
        };

        var chart = new ApexCharts(document.querySelector("#engagementChart"), options);
        chart.render();

        // Dynamic Live Users Polling & Infinite Scroll
        let currentPage = 1;
        let isFetching = false;
        let hasMorePages = {{ $onlineUsers->hasMorePages() ? 'true' : 'false' }};
        const gridContainer = document.getElementById('online-users-grid');
        const loadingSpinner = document.getElementById('loading-spinner');

        // Infinite Scroll
        if (gridContainer) {
            gridContainer.addEventListener('scroll', function() {
                if (gridContainer.scrollTop + gridContainer.clientHeight >= gridContainer.scrollHeight - 50 && !isFetching && hasMorePages) {
                    currentPage++;
                    isFetching = true;
                    if(loadingSpinner) loadingSpinner.classList.remove('hidden');
                    if(loadingSpinner) loadingSpinner.classList.add('flex');

                    fetch(`?page=${currentPage}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(res => res.text())
                    .then(html => {
                        if (html.trim() === '') {
                            hasMorePages = false;
                        } else {
                            gridContainer.insertAdjacentHTML('beforeend', html);
                        }
                    })
                    .catch(error => console.error('Error fetching more users:', error))
                    .finally(() => {
                        isFetching = false;
                        if(loadingSpinner) loadingSpinner.classList.add('hidden');
                        if(loadingSpinner) loadingSpinner.classList.remove('flex');
                    });
                }
            });
        }

        // Auto-refresh (only runs if user is at the top to prevent wiping scrolled data)
        setInterval(function() {
            if (gridContainer && gridContainer.scrollTop === 0 && currentPage === 1) {
                fetch(window.location.pathname)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        
                        const newGrid = doc.getElementById('online-users-grid');
                        const newTotal = doc.getElementById('online-users-total');
                        
                        if (newGrid && gridContainer) {
                            gridContainer.innerHTML = newGrid.innerHTML;
                        }
                        if (newTotal) {
                            const totalSpan = document.getElementById('online-users-total');
                            if(totalSpan) totalSpan.innerHTML = newTotal.innerHTML;
                        }
                    })
                    .catch(error => console.error('Error refreshing live users:', error));
            }
        }, 15000); // 15 seconds
    });
</script>
@endpush

@push('style')
<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush
