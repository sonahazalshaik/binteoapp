@extends('admin.layouts.app')

@section('title', 'Revenue Control Center')
@section('header_title', 'Monetization')

@section('content')
<div x-data="{ 
    isMonetized: {{ $settings->is_monetization_enabled ? 'true' : 'false' }},
    commission: {{ $settings->platform_commission }},
    showLogs: true,
    showSettingsModal: false
}" class="space-y-8 animate-in fade-in duration-700">

    <!-- Header & Global Signal -->
    <div class="relative p-8 rounded-[3rem] overflow-hidden border border-emerald-500/20 bg-[#0a0a0a] shadow-2xl">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 via-transparent to-transparent"></div>
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-emerald-500/10 rounded-full blur-[100px]"></div>
        
        <div class="relative flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="space-y-2 text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-3">
                    <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-[10px] font-black uppercase tracking-[0.2em] border border-emerald-500/20">Active Network</span>
                    <h1 class="text-3xl font-black text-white tracking-tighter uppercase ">Revenue Center</h1>
                </div>
                <p class="text-white/40 text-[11px] font-medium uppercase tracking-widest leading-relaxed">Centralized command for platform-wide monetization and yield management.</p>
            </div>

            <div class="flex flex-col items-center gap-4 p-6 rounded-[2rem] bg-white/5 border border-white/10 backdrop-blur-md">
                <p class="text-[9px] font-black text-white/30 uppercase tracking-[0.3em]">Global Monetization Switch</p>
                <form action="{{ route('admin.monetization.toggle-global') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="relative inline-flex h-10 w-20 items-center rounded-full transition-all duration-500 focus:outline-none shadow-lg"
                            :class="isMonetized ? 'bg-emerald-500 shadow-emerald-500/20' : 'bg-white/10'">
                        <span class="inline-block h-8 w-8 transform rounded-full bg-white shadow-md transition-transform duration-500"
                              :class="isMonetized ? 'translate-x-11' : 'translate-x-1'"></span>
                    </button>
                </form>
                <span class="text-[10px] font-black uppercase tracking-widest transition-colors duration-500"
                      :class="isMonetized ? 'text-emerald-400' : 'text-white/20'"
                      x-text="isMonetized ? 'Signal Online' : 'Network Halted'"></span>
            </div>
        </div>
    </div>

    <!-- Bento Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Revenue -->
        <div class="p-8 rounded-[2.5rem] bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 shadow-sm group hover:border-emerald-500/30 transition-all duration-500">
            <div class="flex items-center justify-between mb-6">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center border border-emerald-500/10 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-rounded">account_balance_wallet</span>
                </div>
                <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">+12% growth</span>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Yield</p>
            <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter ">
                <span class="text-emerald-500 opacity-50 mr-1">$</span>{{ number_format($totalEarnings, 2) }}
            </h2>
        </div>

        <!-- Network Commission -->
        <div class="p-8 rounded-[2.5rem] bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 shadow-sm relative overflow-hidden group">
            <div class="flex items-center justify-between mb-6">
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-500 flex items-center justify-center border border-blue-500/10 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-rounded">percent</span>
                </div>
                <button @click="showSettingsModal = true" class="text-[10px] font-black text-blue-500 uppercase tracking-widest hover:underline decoration-2 underline-offset-4">Update Rate</button>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Network Commission</p>
            <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter ">{{ $settings->platform_commission }}%</h2>
        </div>

        <!-- AdSense Status -->
        <div class="p-8 rounded-[2.5rem] bg-[#1a73e8]/5 border border-[#1a73e8]/20 shadow-sm group">
            <div class="flex items-center justify-between mb-6">
                <div class="w-12 h-12 rounded-2xl bg-[#1a73e8]/10 text-[#1a73e8] flex items-center justify-center border border-[#1a73e8]/10 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-rounded">ads_click</span>
                </div>
                <span class="px-2 py-0.5 rounded-full bg-[#1a73e8]/10 text-[#1a73e8] text-[8px] font-black uppercase tracking-widest">Future Integration</span>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Google AdSense</p>
            <h2 class="text-xl font-black text-[#1a73e8] tracking-tighter uppercase ">Ready to Sync</h2>
        </div>

        <!-- Active Videos -->
        <div class="p-8 rounded-[2.5rem] bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 shadow-sm group">
            <div class="flex items-center justify-between mb-6">
                <div class="w-12 h-12 rounded-2xl bg-purple-500/10 text-purple-500 flex items-center justify-center border border-purple-500/10 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-rounded">movie</span>
                </div>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Earning Videos</p>
            <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter ">{{ $videoEarnings->total() }}</h2>
        </div>
    </div>

    <!-- Revenue Pulse (Dynamic Chart Section) -->
    <div class="p-8 rounded-[3rem] bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 mb-10">
            <div>
                <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter ">Revenue Pulse</h3>
                <p class="text-[10px] font-medium text-slate-400 uppercase tracking-[0.2em] mt-1">Platform-wide yield trends over the last 30 days</p>
            </div>
            <div class="flex items-center gap-2 p-1 rounded-2xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10">
                <button class="px-4 py-2 rounded-xl bg-white dark:bg-white/10 shadow-sm text-[9px] font-black uppercase tracking-widest text-slate-900 dark:text-white transition-all">Weekly</button>
                <button class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-all">Monthly</button>
            </div>
        </div>
        
        <div class="h-64 relative">
            <!-- Simple simulated SVG Chart for the premium look -->
            <svg class="w-full h-full drop-shadow-[0_10px_20px_rgba(16,185,129,0.2)]" viewBox="0 0 1000 200" preserveAspectRatio="none">
                <defs>
                    <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#10b981" stop-opacity="0.3" />
                        <stop offset="100%" stop-color="#10b981" stop-opacity="0" />
                    </linearGradient>
                </defs>
                <path d="M0,150 Q100,80 200,120 T400,60 T600,100 T800,40 T1000,80 L1000,200 L0,200 Z" fill="url(#gradient)" />
                <path d="M0,150 Q100,80 200,120 T400,60 T600,100 T800,40 T1000,80" fill="none" stroke="#10b981" stroke-width="4" stroke-linecap="round" class="animate-[dash_3s_ease-in-out_infinite]" />
                
                <!-- Data Points -->
                <circle cx="200" cy="120" r="4" fill="#10b981" class="animate-pulse" />
                <circle cx="400" cy="60" r="4" fill="#10b981" class="animate-pulse" />
                <circle cx="800" cy="40" r="4" fill="#10b981" class="animate-pulse" />
            </svg>
            
            <!-- Labels Overlay -->
            <div class="absolute inset-0 flex justify-between items-end pb-2 opacity-30 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                <span>Mon</span>
                <span>Tue</span>
                <span>Wed</span>
                <span>Thu</span>
                <span>Fri</span>
                <span>Sat</span>
                <span>Sun</span>
            </div>
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
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-rounded text-emerald-500 text-lg">play_circle</span>
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
                                <span class="text-[13px] font-black text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 px-4 py-2 rounded-xl border border-emerald-500/20 shadow-sm">+${{ number_format($earning->estimated_revenue, 2) }}</span>
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
@endsection

