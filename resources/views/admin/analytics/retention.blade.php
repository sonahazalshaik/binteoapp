@extends('admin.layouts.app')

@section('panel')
<div class="space-y-6 pb-10">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h4 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">User Retention & Cohorts</h4>
            <p class="text-sm text-slate-400">Measuring long-term platform stickiness</p>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 rounded-lg text-sm font-bold border border-rose-100 dark:border-rose-500/20 animate-pulse">
            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
            Active Today: {{ number_format($stats['active_today']) }} Users
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Retention KPI Cards -->
        <div class="bg-indigo-600 p-8 rounded-2xl shadow-xl shadow-indigo-500/20 text-white relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-xs font-bold uppercase tracking-widest opacity-60 mb-2">Day 1 Retention</p>
                <h3 class="text-4xl font-black mb-4">
                    {{ number_format($stats['day1'], 1) }}%
                </h3>
                <p class="text-xs opacity-80">Benchmark for activation health</p>
            </div>
            <span class="material-symbols-rounded absolute -right-4 -bottom-4 text-9xl opacity-10">group_add</span>
        </div>
        
        <div class="bg-emerald-600 p-8 rounded-2xl shadow-xl shadow-emerald-500/20 text-white relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-xs font-bold uppercase tracking-widest opacity-60 mb-2">Day 7 Retention</p>
                <h3 class="text-4xl font-black mb-4">
                    {{ number_format($stats['day7'], 1) }}%
                </h3>
                <p class="text-xs opacity-80">Benchmark for habit formation</p>
            </div>
            <span class="material-symbols-rounded absolute -right-4 -bottom-4 text-9xl opacity-10">loop</span>
        </div>
 
        <div class="bg-slate-900 p-8 rounded-2xl shadow-xl text-white relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-xs font-bold uppercase tracking-widest opacity-60 mb-2">Day 30 Retention</p>
                <h3 class="text-4xl font-black mb-4">
                    {{ number_format($stats['day30'], 1) }}%
                </h3>
                <p class="text-xs opacity-80">Benchmark for long-term loyalty</p>
            </div>
            <span class="material-symbols-rounded absolute -right-4 -bottom-4 text-9xl opacity-10">star</span>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800">
            <h5 class="font-black text-slate-800 dark:text-white uppercase text-xs tracking-widest">Cohort Analysis Table</h5>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Cohort Date</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">New Users</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Day 1</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Day 7</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Day 30</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @php
                        $cohorts = $retentionData->groupBy('cohort_date');
                    @endphp
                    @foreach($cohorts as $date => $metrics)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-6 py-4 text-sm font-black text-slate-800 dark:text-white">
                            {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-slate-500">
                            {{ number_format($metrics->first()->total_users) }}
                        </td>
                        @foreach([1, 7, 30] as $day)
                            @php 
                                $m = $metrics->where('day_n', $day)->first();
                                $rate = $m ? $m->retention_rate : 0;
                                // Color intensity based on retention
                                $colorClass = 'text-slate-400';
                                if($rate > 50) $colorClass = 'bg-emerald-500 text-white';
                                elseif($rate > 25) $colorClass = 'bg-emerald-200 text-emerald-800';
                                elseif($rate > 10) $colorClass = 'bg-orange-100 text-orange-800';
                            @endphp
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 rounded-lg text-xs font-black {{ $colorClass }}">
                                    {{ $rate ? number_format($rate, 1).'%' : '-' }}
                                </span>
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
