@extends('admin.layouts.app')

@section('panel')
<div class="space-y-6 pb-10">
    <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-8">
        <div>
            <h4 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Creator Excellence</h4>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Leaderboards, top performers, and real-time creator growth</p>
        </div>

        <form action="" method="GET" class="flex items-center gap-3">
            <!-- Temporal Window -->
            <div class="relative group">
                <input name="date" type="date" value="{{ request()->date ?? date('Y-m-d') }}" 
                       class="h-11 px-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-[11px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400 outline-none focus:border-indigo-500/50 transition-all">
            </div>
            
            <button type="submit" class="h-11 px-6 rounded-2xl bg-indigo-600 text-white text-[11px] font-black uppercase tracking-widest hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-600/20 active:scale-95 transition-all flex items-center gap-2">
                <span class="material-symbols-rounded text-lg">sync</span>
                Refresh Pulse
            </button>

            <a href="{{ route('admin.analytics.creators') }}" class="h-11 w-11 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center hover:text-indigo-500 transition-all">
                <span class="material-symbols-rounded text-xl">restart_alt</span>
            </a>
        </form>
    </div>

    <!-- Creator Highlights -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
        <!-- Top Earners -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <h4 class="text-lg font-black text-slate-800 dark:text-white tracking-tight mb-6 flex items-center gap-2">
                <span class="material-symbols-rounded text-yellow-500">payments</span> Top Earners (Today)
            </h4>
            <div class="space-y-4">
                @forelse($topByEarnings as $item)
                <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50">
                    <div class="flex items-center gap-3">
                        @if($item->user->image)
                            <img src="{{ getImage(getFilePath('userProfile') . '/' . $item->user->image, getFileSize('userProfile')) }}" class="w-10 h-10 rounded-full border-2 border-white dark:border-slate-700 shadow-sm">
                        @else
                            <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 font-black text-xs border-2 border-white dark:border-slate-700 shadow-sm">
                                {{ getUserInitials($item->user) }}
                            </div>
                        @endif
                        <div>
                            <p class="text-sm font-black text-slate-800 dark:text-white">{{ $item->user->username }}</p>
                            <p class="text-xs text-slate-400 font-bold">Creator</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-black text-emerald-600">{{ showAmount($item->earnings) }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-slate-400 text-center py-4 ">No earnings data for today</p>
                @endforelse
            </div>
        </div>

        <!-- Top Authority -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <h4 class="text-lg font-black text-slate-800 dark:text-white tracking-tight mb-6 flex items-center gap-2">
                <span class="material-symbols-rounded text-indigo-500">star</span> Top Creators (Subscribers)
            </h4>
            <div class="space-y-4">
                @forelse($topBySubscribers as $item)
                <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50">
                    <div class="flex items-center gap-3">
                        @if($item->user->image)
                            <img src="{{ getImage(getFilePath('userProfile') . '/' . $item->user->image, getFileSize('userProfile')) }}" class="w-10 h-10 rounded-full border-2 border-white dark:border-slate-700 shadow-sm">
                        @else
                            <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 font-black text-xs border-2 border-white dark:border-slate-700 shadow-sm">
                                {{ getUserInitials($item->user) }}
                            </div>
                        @endif
                        <div>
                            <p class="text-sm font-black text-slate-800 dark:text-white">{{ $item->user->username }}</p>
                            <p class="text-xs text-slate-400 font-bold">Creator</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-black text-indigo-600">{{ number_format($item->total_subscribers) }}</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Total Subscribers</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-slate-400 text-center py-4 ">No growth data for today</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Main Leaderboard -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="p-8 border-b border-slate-100 dark:border-slate-800">
            <h4 class="text-lg font-black text-slate-800 dark:text-white tracking-tight">Creator Leaderboard (By Views)</h4>
            <p class="text-sm text-slate-400">Ranking of top 20 creators based on today's view performance</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="px-8 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">Rank</th>
                        <th class="px-8 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">Creator</th>
                        <th class="px-8 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">Daily Views</th>
                        <th class="px-8 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">Watch Time (Min)</th>
                        <th class="px-8 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">Total Subscribers</th>
                        <th class="px-8 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">Earnings</th>
                        <th class="px-8 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($leaderboard as $index => $item)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                        <td class="px-8 py-5">
                            @if($index < 3)
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-sm {{ $index == 0 ? 'bg-yellow-100 text-yellow-700' : ($index == 1 ? 'bg-slate-200 text-slate-700' : 'bg-orange-100 text-orange-700') }}">
                                    {{ $index + 1 }}
                                </div>
                            @else
                                <span class="text-sm font-bold text-slate-400 pl-3">#{{ $index + 1 }}</span>
                            @endif
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                @if($item->user->image)
                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $item->user->image, getFileSize('userProfile')) }}" class="w-10 h-10 rounded-full border border-slate-100 dark:border-slate-800">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 font-black text-xs border border-slate-100 dark:border-slate-800">
                                        {{ getUserInitials($item->user) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-black text-slate-800 dark:text-white">{{ $item->user->username }}</p>
                                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-tighter">{{ $item->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-sm font-black text-slate-700 dark:text-slate-300">{{ number_format($item->views) }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-sm font-black text-slate-700 dark:text-slate-300">{{ number_format($item->watch_time_minutes, 1) }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-sm font-black text-indigo-600">{{ number_format($item->total_subscribers) }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-sm font-black text-emerald-600">{{ showAmount($item->earnings) }}</span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <a href="{{ route('admin.users.detail', $item->user->id) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-lg text-xs font-black border border-indigo-100 dark:border-indigo-500/20 hover:bg-indigo-600 hover:text-white transition-all">
                                View Profile
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-10 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <span class="material-symbols-rounded text-4xl text-slate-200">group_off</span>
                                <p class="text-sm text-slate-400 font-bold ">No creators found in leaderboard for this period.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

