<!-- Tab: Dashboard (Bento Redesign) -->
<div x-show="tab === 'dashboard'" x-cloak x-transition class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Clean Integrated Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-slate-100 dark:border-white/5">
        <div>
            <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight ">
                System <span class="text-orange-500">Overview</span>
            </h2>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Synchronizing profile metrics...</p>
        </div>
        

    </div>

    <!-- Bento Stats Grid (2 Cards per Row) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <!-- Main Welcome Bento (Ultra Premium) -->
        <div class="col-span-1 md:col-span-2 relative group bg-slate-900 dark:bg-black rounded-2xl p-3 md:p-6 overflow-hidden shadow-2xl transition-all duration-500 border border-white/5 flex flex-col justify-between">
            <div class="absolute -top-12 -right-12 md:-top-24 md:-right-24 w-32 h-32 md:w-64 md:h-64 bg-orange-500/20 rounded-full blur-[50px] md:blur-[100px] group-hover:bg-orange-500/30 transition-colors"></div>
            <div class="relative z-10 h-full flex flex-col justify-between">
                <div>
                    <div class="inline-flex items-center gap-1.5 md:gap-2 px-1.5 md:px-2 py-0.5 bg-white/5 rounded-full border border-white/10 mb-2 md:mb-3">
                        <div class="w-1 h-1 md:w-1.5 md:h-1.5 rounded-full bg-orange-500 animate-pulse"></div>
                        <span class="text-[7px] md:text-[9px] font-black text-white/60 uppercase tracking-widest whitespace-nowrap">Active</span>
                    </div>
                    <h3 class="text-base md:text-2xl font-black text-white tracking-tighter leading-[1.1] mb-1.5 md:mb-2">
                        Creative <span class="text-orange-500 underline decoration-orange-500/30 underline-offset-2 md:underline-offset-4">Journey.</span>
                    </h3>
                    <p class="text-white/40 font-medium text-[9px] md:text-xs leading-tight md:leading-relaxed max-w-xs line-clamp-2 md:line-clamp-none">
                        Manage portfolio assets in one hub.
                    </p>
                </div>
                <div class="pt-3 md:pt-4">
                    <button @click="tab = 'gallery'" class="group/btn relative w-full md:w-auto px-3 md:px-6 py-2 md:py-3 bg-white text-slate-900 rounded-xl text-[8px] md:text-[9px] font-black uppercase tracking-widest overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-xl shadow-white/5">
                        <span class="relative z-10">Manage</span>
                        <div class="absolute inset-0 bg-orange-500 translate-y-full group-hover/btn:translate-y-0 transition-transform duration-300"></div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Status Bento (Orange Gradient Redesign) -->
        <div class="col-span-1 md:col-span-2 relative group rounded-2xl p-3 md:p-6 overflow-hidden shadow-2xl transition-all duration-500 border border-white/10 flex flex-col justify-between">
            <!-- Animated Mesh Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-orange-500 via-rose-500 to-rose-600 group-hover:scale-110 transition-transform duration-700"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_120%,rgba(255,255,255,0.2),transparent)]"></div>
            
            <div class="relative z-10 h-full flex flex-col justify-between text-white">
                <div class="flex md:flex-row flex-col justify-between items-start gap-2 md:gap-0">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg md:rounded-xl bg-white/10 backdrop-blur-xl border border-white/20 flex items-center justify-center shadow-2xl shrink-0">
                        <span class="material-symbols-rounded text-[16px] md:text-[20px] text-white fill-1">verified</span>
                    </div>
                    <div class="px-2 md:px-3 py-1 bg-white/10 backdrop-blur-md rounded-full border border-white/20 text-[7px] md:text-[9px] font-black uppercase tracking-widest whitespace-nowrap">
                        Verified
                    </div>
                </div>

                <div class="pt-3 md:pt-4">
                    <h4 class="text-[7px] md:text-[9px] font-black uppercase tracking-[0.2em] opacity-60 mb-1 line-clamp-1">Subscription Protocol</h4>
                    <p class="text-sm md:text-xl font-black tracking-tighter uppercase mb-2 md:mb-3 line-clamp-1">{{ $subscriptions->first()->plan_name ?? 'Basic Station' }}</p>
                    
                    <div class="space-y-0.5 md:space-y-1 mb-2 md:mb-4">
                        <div class="flex items-center gap-1 md:gap-2 text-[7px] md:text-[9px] font-bold text-white/60 uppercase tracking-widest">
                            <span class="material-symbols-rounded text-[10px] md:text-[12px]">calendar_today</span>
                            <span class="line-clamp-1">Start: {{ $subscriptions->first() ? \Carbon\Carbon::parse($subscriptions->first()->start_date)->format('M d') : 'N/A' }}</span>
                        </div>
                        <div class="flex items-center gap-1 md:gap-2 text-[7px] md:text-[9px] font-bold text-white/60 uppercase tracking-widest">
                            <span class="material-symbols-rounded text-[10px] md:text-[12px]">event_available</span>
                            <span class="line-clamp-1">End: {{ $subscriptions->first() ? \Carbon\Carbon::parse($subscriptions->first()->end_date)->format('M d') : 'Life' }}</span>
                        </div>
                    </div>
                    
                    <div class="relative h-1 md:h-1.5 w-full bg-white/10 rounded-full overflow-hidden backdrop-blur-sm border border-white/5">
                        <div class="absolute inset-y-0 left-0 bg-white shadow-[0_0_15px_rgba(255,255,255,0.5)] transition-all duration-1000" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric Cards -->
        @foreach([
            ['Assets', count($client->galleries), 'photo_library', 'text-blue-500'],
            ['Services', count($client->services), 'design_services', 'text-emerald-500'],
            ['Inquiries', count($client->contacts), 'mail', 'text-orange-500'],
            ['Network', 'Elite', 'groups', 'text-purple-500']
        ] as $stat)
        <div class="col-span-1 md:col-span-2 bg-white dark:bg-[#111] rounded-2xl border border-slate-200 dark:border-white/5 p-3 md:p-5 hover:shadow-lg transition-all flex flex-col justify-between">
            <div class="flex md:flex-row flex-col items-start md:items-center justify-between mb-2 md:mb-3 gap-2 md:gap-0">
                <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg md:rounded-xl bg-slate-50 dark:bg-white/5 flex items-center justify-center border border-slate-100 dark:border-white/10 shrink-0">
                    <span class="material-symbols-rounded text-base md:text-lg {{ $stat[3] }}">{{ $stat[2] }}</span>
                </div>
                <span class="text-[7px] md:text-[9px] font-black text-slate-400 uppercase tracking-widest">Active</span>
            </div>
            <div>
                <p class="text-xl md:text-2xl font-black text-slate-900 dark:text-white tracking-tighter mb-0.5">{{ $stat[1] }}</p>
                <p class="text-[7px] md:text-[9px] font-black text-slate-400 uppercase tracking-widest truncate">{{ $stat[0] }} Managed</p>
            </div>
        </div>
        @endforeach
    </div>
</div>

