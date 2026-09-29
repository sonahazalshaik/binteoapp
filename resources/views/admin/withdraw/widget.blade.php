<div class="mb-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Approved Withdrawals -->
        <div class="relative overflow-hidden group">
            <div class="bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 rounded-[2.5rem] p-6 shadow-sm hover:shadow-xl transition-all duration-300">
                <a href="{{ route('admin.withdraw.data.approved', request()->all()) }}" class="absolute inset-0 z-10"></a>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300">
                        <span class="material-symbols-rounded text-2xl">check_circle</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-black text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] block mb-1">Approved</span>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white leading-none">{{ showAmount($successful, 0) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Withdrawals -->
        <div class="relative overflow-hidden group">
            <div class="bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 rounded-[2.5rem] p-6 shadow-sm hover:shadow-xl transition-all duration-300">
                <a href="{{ route('admin.withdraw.data.pending', request()->all()) }}" class="absolute inset-0 z-10"></a>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-500 group-hover:bg-amber-500 group-hover:text-white transition-all duration-300">
                        <span class="material-symbols-rounded text-2xl">pending_actions</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-black text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] block mb-1">Pending</span>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white leading-none">{{ showAmount($pending, 0) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejected Withdrawals -->
        <div class="relative overflow-hidden group">
            <div class="bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 rounded-[2.5rem] p-6 shadow-sm hover:shadow-xl transition-all duration-300">
                <a href="{{ route('admin.withdraw.data.rejected', request()->all()) }}" class="absolute inset-0 z-10"></a>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-rose-500/10 flex items-center justify-center text-rose-500 group-hover:bg-rose-500 group-hover:text-white transition-all duration-300">
                        <span class="material-symbols-rounded text-2xl">cancel</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-black text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] block mb-1">Rejected</span>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white leading-none">{{ showAmount($rejected, 0) }}</h3>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

