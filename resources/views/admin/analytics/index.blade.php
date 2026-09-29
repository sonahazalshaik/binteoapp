@extends('admin.layouts.app')

@section('title', 'Analytics & Reports')
@section('header_title', 'Main Dashboard')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="px-4 lg:px-0 grid grid-cols-1 lg:grid-cols-4 gap-4 mb-12 animate-in fade-in slide-in-from-bottom-10 duration-1000">
    <!-- Viewership Card -->
    <div class="bg-white/40 dark:bg-white/5 backdrop-blur-xl p-4 rounded-xl border border-slate-200 dark:border-white/10 shadow-xl relative overflow-hidden group">
        <div class="absolute -top-6 -right-10 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl"></div>
        <p class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest mb-3">Total Views</p>
        <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tighter">{{ number_format($totalViews) }}</h3>
        <div class="mt-6 flex items-center gap-2">
            <span class="text-emerald-500 text-xs font-black bg-emerald-500/10 px-2 py-1 rounded-lg flex items-center">
                <span class="material-symbols-rounded text-[12px]">trending_up</span> {{ $growth['views'] }}
            </span>
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1">Daily Progress</span>
        </div>
    </div>

    <!-- Active Population -->
    <div class="bg-white/40 dark:bg-white/5 backdrop-blur-xl p-4 rounded-xl border border-slate-200 dark:border-white/10 shadow-xl relative overflow-hidden group">
        <div class="absolute -top-6 -right-10 w-32 h-32 bg-rose-500/10 rounded-full blur-3xl"></div>
        <p class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest mb-3">Total Users</p>
        <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tighter">{{ number_format($totalUsers) }}</h3>
        <div class="mt-6 flex items-center gap-2">
            <span class="text-emerald-500 text-xs font-black bg-emerald-500/10 px-2 py-1 rounded-lg flex items-center">
                <span class="material-symbols-rounded text-[12px]">trending_up</span> {{ $growth['users'] }}
            </span>
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1">New Signups</span>
        </div>
    </div>

    <!-- Asset Volume -->
    <div class="bg-white/40 dark:bg-white/5 backdrop-blur-xl p-4 rounded-xl border border-slate-200 dark:border-white/10 shadow-xl relative overflow-hidden group">
        <div class="absolute -top-6 -right-10 w-32 h-32 bg-amber-500/10 rounded-full blur-3xl"></div>
        <p class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest mb-3">Total Videos</p>
        <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tighter">{{ number_format($totalVideos) }}</h3>
        <div class="mt-6 flex items-center gap-2">
            <span class="text-emerald-500 text-xs font-black bg-emerald-500/10 px-2 py-1 rounded-lg flex items-center">
                <span class="material-symbols-rounded text-[12px]">trending_up</span> {{ $growth['videos'] }}
            </span>
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1">Upload Frequency</span>
        </div>
    </div>

    <!-- Network Vitality -->
    <div class="bg-gradient-to-tr from-slate-900 to-slate-800 dark:from-white/10 dark:to-white/5 p-4 rounded-xl shadow-2xl relative overflow-hidden">
        <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-3">Server Status</p>
        <h3 class="text-xl font-black text-white tracking-tighter ">99.9<span class="text-xl ml-1 opacity-40">%</span></h3>
        <div class="mt-6 flex items-center gap-2">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
            </span>
            <span class="text-[9px] font-black text-emerald-400 uppercase tracking-widest">System Online</span>
        </div>
    </div>
</div>

<!-- Main Growth Trace -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-12">
    <div class="lg:col-span-2 bg-white/40 dark:bg-black/20 backdrop-blur-3xl rounded-[2rem] border border-white dark:border-white/5 p-6 shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h3 class="text-base font-black text-slate-900 dark:text-white tracking-tighter uppercase">Traffic Trend</h3>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-2 leading-relaxed">Success rate and views over last 7 days</p>
            </div>
            <div class="flex gap-2">
                <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest">Daily Views</span>
            </div>
        </div>
        <div class="h-[280px]">
            <canvas id="trafficChart"></canvas>
        </div>
    </div>

    <div class="bg-white/40 dark:bg-black/20 backdrop-blur-3xl rounded-[2rem] border border-white dark:border-white/5 p-6 shadow-2xl">
        <h3 class="text-base font-black text-slate-900 dark:text-white tracking-tighter uppercase mb-8">Source Mix</h3>
        <canvas id="mixChart" class="w-full"></canvas>
        <div class="mt-10 space-y-4">
            <div class="flex items-center justify-between p-4 bg-white/40 dark:bg-white/5 rounded-xl border border-white dark:border-white/5">
                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Return Rate</span>
                <span class="text-[12px] font-black text-slate-900 dark:text-white">{{ $returnRate }}%</span>
            </div>
            <div class="flex items-center justify-between p-4 bg-white/40 dark:bg-white/5 rounded-xl border border-white dark:border-white/5">
                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Avg Watch Time/User</span>
                <span class="text-[12px] font-black text-slate-900 dark:text-white">{{ gmdate('i\m s\s', (int) $avgWatchTimePerUserToday) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Intelligence -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-20">
    <!-- Top Assets -->
    <div class="bg-white/60 dark:bg-black/20 backdrop-blur-3xl rounded-[2rem] border border-white dark:border-white/5 overflow-hidden shadow-2xl">
        <div class="px-10 py-10 border-b border-white/40 dark:border-white/5">
            <h3 class="text-base font-black text-slate-900 dark:text-white tracking-tighter uppercase">Top Videos</h3>
            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-2 leading-relaxed">Most viewed content on the platform</p>
        </div>
        <div class="divide-y divide-white/40 dark:divide-white/5 max-h-[400px] overflow-y-auto custom-scrollbar">
            @foreach($topVideos as $video)
            <a href="{{ route('videos.show', $video->slug) }}" target="_blank" class="px-6 py-2.5 flex items-center justify-between hover:bg-white/40 dark:hover:bg-white/5 transition-all group border-b border-white/40 dark:border-white/5 last:border-b-0">
                <div class="flex items-center gap-5">
                    <span class="text-xs font-black text-slate-300 dark:text-white/20">0{{ $loop->iteration }}</span>
                    <div class="w-14 aspect-video rounded-lg overflow-hidden bg-slate-200 dark:bg-white/10 border border-white dark:border-white/10 group-hover:scale-110 transition-transform relative">
                        <img src="{{ $video->getPreviewUrl() }}" class="w-full h-full object-cover">
                        @if($video->isBunnyVideo())
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-1">
                                <span class="text-[6px] font-black text-white uppercase tracking-tighter">Bunny CDN</span>
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="text-[12px] font-black text-slate-900 dark:text-white tracking-tight truncate max-w-[200px]">{{ $video->title }}</p>
                        <p class="text-[9px] font-black text-blue-500 uppercase tracking-widest mt-1 ">High Reach</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[12px] font-black text-slate-900 dark:text-white tracking-tighter">{{ number_format($video->views_count) }} Views</p>
                    <p class="text-[9px] font-black text-emerald-500 uppercase mt-1">Trending</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white/60 dark:bg-black/20 backdrop-blur-3xl rounded-[2rem] border border-white dark:border-white/5 overflow-hidden shadow-2xl">
        <div class="px-10 py-10 border-b border-white/40 dark:border-white/5">
            <h3 class="text-base font-black text-slate-900 dark:text-white tracking-tighter uppercase">Recent Activity</h3>
            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-2 leading-relaxed">Latest interactions on the platform</p>
        </div>
        <div class="p-6 max-h-[400px] overflow-y-auto custom-scrollbar">
             <div class="space-y-6">
                 @forelse($recentActivity as $activity)
                 <div class="flex items-start gap-4 p-5 bg-white/40 dark:bg-white/5 rounded-[2rem] border border-white dark:border-white/5 transform hover:scale-105 transition-all">
                     <div class="w-10 h-10 rounded-full bg-indigo-500 shadow-lg shadow-indigo-500/20 flex items-center justify-center text-white overflow-hidden">
                         @if($activity->user && $activity->user->image)
                             <img src="{{ getImage(getFilePath('userProfile').'/'.$activity->user->image) }}" class="w-full h-full object-cover">
                         @else
                             <span class="material-symbols-rounded text-[12px]">person</span>
                         @endif
                     </div>
                     <div>
                         <p class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">{{ $activity->user ? $activity->user->username : 'Guest' }}</p>
                         <p class="text-[9px] font-medium text-slate-400 leading-relaxed mt-1">
                             Accessed {{ $activity->route_name ?: 'System' }}
                             <span class="ml-2 opacity-60">{{ $activity->created_at->diffForHumans() }}</span>
                         </p>
                     </div>
                 </div>
                 @empty
                 <div class="text-center py-10">
                     <p class="text-xs font-black text-slate-400 uppercase tracking-widest">No recent activity</p>
                 </div>
                 @endforelse
             </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.2);
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(148, 163, 184, 0.4);
    }
</style>

<script>
    const ctx = document.getElementById('trafficChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($history->pluck('recorded_at')->map(function($d) { return \Carbon\Carbon::parse($d)->format('M d'); })) !!},
            datasets: [{
                label: 'Daily Views',
                data: {!! json_encode($history->pluck('total_views')) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 5,
                fill: true,
                tension: 0.5,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#3b82f6',
                pointBorderWidth: 3,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { display: false },
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8', font: { weight: '900', size: 10 } }
                }
            }
        }
    });

    const mixCtx = document.getElementById('mixChart').getContext('2d');
    new Chart(mixCtx, {
        type: 'doughnut',
        data: {
            labels: ['Search', 'Viral', 'Direct'],
            datasets: [{
                data: [45, 35, 20],
                backgroundColor: ['#3b82f6', '#f43f5e', '#f59e0b'],
                borderWidth: 0,
                hoverOffset: 20
            }]
        },
        options: {
            cutout: '80%',
            plugins: { legend: { display: false } }
        }
    });
</script>
@endsection


