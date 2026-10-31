<x-app-layout>
    <div class="py-8 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row items-center justify-between mb-10 gap-6">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Withdrawal Log</h1>
                    <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                        Tracking all payout requests
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('user.withdraw.methods') }}" class="px-8 py-4 bg-gray-900 dark:bg-white text-white dark:text-black rounded-[2rem] font-black text-xs uppercase tracking-widest shadow-2xl hover:opacity-90 transition-all active:scale-95 flex items-center gap-2">
                        <span class="material-symbols-rounded text-lg">payments</span>
                        Withdraw Funds
                    </a>
                </div>
            </div>

            <!-- List Section -->
            <div class="bg-white dark:bg-[#181818] rounded-[3rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden transition-all duration-500">
                <div class="px-8 py-6 border-b border-gray-50 dark:border-white/5 bg-gray-50/50 dark:bg-white/[0.02]">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">History</h2>
                        <form action="" method="GET" class="relative group max-w-sm w-full">
                            <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-red-500 transition-colors">search</span>
                            <input type="text" name="search" value="{{ request()->search }}" placeholder="Search ID..." 
                                   class="w-full bg-white dark:bg-white/5 border border-gray-100 dark:border-white/10 rounded-2xl pl-12 pr-6 py-2.5 text-xs font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all">
                        </form>
                    </div>
                </div>

                <!-- Desktop Table -->
                <div class="hidden md:block">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/10 dark:bg-white/[0.01]">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Method</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">TRX ID</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Amount</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Final</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            @forelse($withdraws as $withdraw)
                            <tr class="hover:bg-gray-50/30 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-white/5 flex items-center justify-center">
                                            <span class="material-symbols-rounded text-xl text-gray-400">account_balance</span>
                                        </div>
                                        <span class="text-sm font-black text-gray-900 dark:text-white">{{ __($withdraw->method->name ?? 'N/A') }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest">#{{ $withdraw->trx ?? 'N/A' }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ showDateTime($withdraw->created_at, 'd M, Y') }}</div>
                                    <div class="text-[9px] font-bold text-gray-400 uppercase mt-1">{{ diffForHumans($withdraw->created_at) }}</div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="text-sm font-black text-red-600">- {{ showAmount($withdraw->amount) }}</div>
                                    <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ showAmount($withdraw->charge) }} charge</div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="text-sm font-black text-gray-900 dark:text-white">{{ showAmount($withdraw->final_amount, currencyFormat: false) }} {{ $withdraw->currency }}</div>
                                    <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">1 {{ __($general->cur_text) }} = {{ showAmount($withdraw->rate, currencyFormat: false) }} {{ $withdraw->currency }}</div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="flex justify-center">
                                            @if($withdraw->status == 1)
                                                <span class="px-4 py-1.5 rounded-full bg-emerald-500/10 text-emerald-600 text-[9px] font-black uppercase tracking-widest">Approved</span>
                                            @elseif($withdraw->status == 2)
                                                <span class="px-4 py-1.5 rounded-full bg-amber-500/10 text-amber-600 text-[9px] font-black uppercase tracking-widest">Pending</span>
                                            @elseif($withdraw->status == 3)
                                                <span class="px-4 py-1.5 rounded-full bg-red-500/10 text-red-600 text-[9px] font-black uppercase tracking-widest">Rejected</span>
                                            @endif
                                        </div>
                                        @if($withdraw->admin_feedback)
                                            <div class="flex items-start gap-1 justify-center max-w-[200px]">
                                                <span class="material-symbols-rounded text-[10px] text-gray-400 mt-0.5">info</span>
                                                <span class="text-[8px] font-bold text-gray-400 dark:text-gray-500 text-center leading-tight">{{ $withdraw->admin_feedback }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-20 text-center">
                                    <span class="material-symbols-rounded text-6xl text-gray-200 dark:text-white/5 mb-6">payments</span>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No withdrawal history found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View -->
                <div class="md:hidden divide-y divide-gray-50 dark:divide-white/5">
                    @forelse($withdraws as $withdraw)
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-white/5 flex items-center justify-center">
                                    <span class="material-symbols-rounded text-xl text-gray-400">account_balance</span>
                                </div>
                                <div>
                                    <h4 class="font-black text-gray-900 dark:text-white text-sm">{{ __($withdraw->method->name ?? 'N/A') }}</h4>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">#{{ $withdraw->trx ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-black text-red-600">-{{ showAmount($withdraw->amount) }}</span>
                                <p class="text-[9px] font-bold text-gray-400 uppercase mt-0.5">{{ showDateTime($withdraw->created_at, 'd M, Y') }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col gap-3 bg-gray-50/50 dark:bg-white/2 p-4 rounded-xl border border-gray-100 dark:border-white/5">
                            <div class="flex justify-between items-center">
                                <div class="text-[9px] font-black text-gray-900 dark:text-white uppercase tracking-widest">{{ showAmount($withdraw->final_amount, currencyFormat: false) }} {{ $withdraw->currency }}</div>
                                @if($withdraw->status == 1)
                                    <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">Approved</span>
                                @elseif($withdraw->status == 2)
                                    <span class="text-[9px] font-black text-amber-500 uppercase tracking-widest">Pending</span>
                                @else
                                    <span class="text-[9px] font-black text-red-500 uppercase tracking-widest">Rejected</span>
                                @endif
                            </div>
                            @if($withdraw->admin_feedback)
                                <div class="pt-3 border-t border-gray-100 dark:border-white/5 flex items-start gap-2">
                                    <span class="material-symbols-rounded text-xs text-gray-400">info</span>
                                    <div class="flex-1">
                                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Admin Note</p>
                                        <p class="text-[9px] font-bold text-gray-500 dark:text-gray-400 leading-relaxed">{{ $withdraw->admin_feedback }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="py-16 text-center">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No results found</p>
                    </div>
                    @endforelse
                </div>
            </div>

            @if($withdraws->hasPages())
                <div class="mt-12">
                    {{ $withdraws->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
