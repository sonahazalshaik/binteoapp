<x-app-layout>
    <div class="py-8 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-10">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-10 gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight">Revenue Analytics</h1>
                    <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2">Breakdown of all income streams</p>
                </div>
                <a href="{{ route('user.transactions') }}" class="px-6 py-3 bg-white dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/10 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest flex items-center gap-2 hover:border-red-500 hover:text-red-500 transition-all">
                    <span class="material-symbols-rounded text-lg">receipt_long</span>
                    All Transactions
                </a>
            </div>

            <!-- Copyright & Policy Alert (Conditional) -->
            @php
                $activeStrikes = auth()->user()->copyrightStrikes()->where('status', 'active')->with('video')->latest()->get();
            @endphp

            @if($activeStrikes->count() > 0)
            <div class="mb-10">
                <div class="bg-rose-500/5 dark:bg-rose-500/10 border border-rose-500/20 rounded-[2rem] p-6 lg:p-8">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <div class="flex items-center gap-5">
                            <div class="w-12 h-12 rounded-2xl bg-rose-500 flex items-center justify-center text-white shadow-xl shadow-rose-500/30">
                                <span class="material-symbols-rounded text-2xl">gavel</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">Policy Compliance Alert</h3>
                                <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest mt-1 flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                    {{ $activeStrikes->count() }} Active {{ Str::plural('Strike', $activeStrikes->count()) }} ({{ $activeStrikes->count() }}/3)
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('studio.dashboard') }}" class="px-5 py-2.5 bg-rose-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-rose-500/20 hover:scale-105 transition-all">Review Strikes</a>
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($activeStrikes as $strike)
                        <div class="bg-white dark:bg-[#1A1A1A] p-4 rounded-2xl border border-rose-500/10 flex flex-col gap-3">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-rounded text-rose-500 text-lg">videocam</span>
                                <h4 class="text-[11px] font-black text-gray-900 dark:text-white truncate uppercase tracking-tight">{{ $strike->video?->title ?? 'Removed Content' }}</h4>
                            </div>
                            <p class="text-[10px] font-bold text-gray-500 dark:text-white/50 leading-relaxed line-clamp-1">"{{ $strike->reason }}"</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Earnings Cards Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 mb-8">
                @php
                    $earningsCards = [
                        ['Total Earnings', showAmount($totalEarnings ?? 0), 'trending_up', 'linear-gradient(135deg, #10b981, #059669)'],
                        ['Settled Ad Rev', showAmount($settledAdEarnings ?? 0), 'ads_click', 'linear-gradient(135deg, #3b82f6, #4f46e5)'],
                        ['Estimated Ad Rev (Live)', showAmount($estimatedAdEarnings ?? 0), 'dynamic_feed', 'linear-gradient(135deg, #f59e0b, #ea580c)'],
                        ['Video Sales', showAmount($videoSalesEarnings ?? 0), 'movie', 'linear-gradient(135deg, #8b5cf6, #7c3aed)'],
                        ['Subscriptions', showAmount($planEarnings ?? 0), 'loyalty', 'linear-gradient(135deg, #ef4444, #e11d48)'],
                        ['Commission', showAmount($adminCommission ?? 0), 'receipt', 'linear-gradient(135deg, #64748b, #475569)'],
                    ];
                @endphp

                @foreach($earningsCards as $card)
                <div class="relative overflow-hidden rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-md group hover:scale-[1.02] transition-all duration-500" style="background: {{ $card[3] }}">
                    <div class="relative z-10 flex sm:flex-col items-center sm:items-start gap-3 sm:gap-0">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white sm:mb-3 group-hover:scale-110 transition-transform shrink-0">
                            <span class="material-symbols-rounded text-base sm:text-lg">{{ $card[2] }}</span>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm sm:text-base font-black text-white tracking-tight truncate">{{ $card[1] }}</h3>
                            <p class="text-[7px] sm:text-[8px] font-black text-white/70 uppercase tracking-widest truncate">{{ $card[0] }}</p>
                        </div>
                    </div>
                    <span class="material-symbols-rounded absolute -right-2 -bottom-2 text-[50px] sm:text-[70px] text-white/10 rotate-12 group-hover:scale-125 transition-transform duration-700 pointer-events-none">{{ $card[2] }}</span>
                </div>
                @endforeach
            </div>

            <!-- Recent Earnings Table -->
            <div class="bg-white dark:bg-[#181818] rounded-[2rem] sm:rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden transition-colors duration-500">
                <div class="px-6 sm:px-8 py-5 sm:py-6 border-b border-gray-50 dark:border-white/5 flex items-center justify-between">
                    <h2 class="text-sm sm:text-lg font-black text-gray-900 dark:text-white uppercase tracking-widest">Recent Earnings</h2>
                    <a href="{{ route('user.transactions') }}" class="text-[10px] font-black text-red-500 uppercase tracking-widest hover:text-red-600 flex items-center gap-1 transition-colors">
                        View All <span class="material-symbols-rounded text-sm">arrow_forward</span>
                    </a>
                </div>

                <!-- Desktop Table -->
                <div class="hidden md:block">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50 dark:bg-white/[0.02]">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">TRX ID</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Video</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Amount</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Post Balance</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Details</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            @forelse($recentTransactions ?? [] as $trx)
                            <tr class="hover:bg-gray-50/30 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="px-8 py-5">
                                    <span class="font-black text-sm text-gray-900 dark:text-white">{{ $trx->trx }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ showDateTime($trx->created_at) }}</div>
                                    <div class="text-[9px] font-bold text-gray-400 uppercase mt-0.5">{{ diffForHumans($trx->created_at) }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    @if($trx->video)
                                    <a href="{{ route('videos.show', $trx->video) }}" class="text-sm font-bold text-red-500 hover:text-red-600 truncate max-w-[180px] block" title="{{ $trx->video->title }}">
                                        {{ $trx->video->title }}
                                    </a>
                                    @else
                                    <span class="text-xs font-bold text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span class="font-black text-sm {{ $trx->trx_type == '+' ? 'text-emerald-600' : 'text-red-500' }}">
                                        {{ $trx->trx_type }} {{ showAmount($trx->amount) }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-center text-sm font-bold text-gray-600 dark:text-gray-400">
                                    {{ showAmount($trx->post_balance) }}
                                </td>
                                <td class="px-8 py-5 text-xs font-bold text-gray-500 dark:text-gray-400 max-w-[200px] truncate">{{ __($trx->details) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center">
                                    <span class="material-symbols-rounded text-5xl text-gray-200 dark:text-white/5 mb-4 block">payments</span>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No earnings recorded yet</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="md:hidden divide-y divide-gray-50 dark:divide-white/5">
                    @forelse($recentTransactions ?? [] as $trx)
                    <div class="p-4 space-y-2">
                        <div class="flex justify-between items-start">
                            <div class="min-w-0 flex-1">
                                <h4 class="font-black text-gray-900 dark:text-white text-sm truncate">{{ $trx->trx }}</h4>
                                @if($trx->video)
                                <a href="{{ route('videos.show', $trx->video) }}" class="text-[10px] font-bold text-red-500 hover:text-red-600 truncate block">{{ $trx->video->title }}</a>
                                @endif
                                <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ diffForHumans($trx->created_at) }}</p>
                            </div>
                            <span class="font-black text-sm shrink-0 {{ $trx->trx_type == '+' ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ $trx->trx_type }} {{ showAmount($trx->amount) }}
                            </span>
                        </div>
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest truncate">{{ __($trx->details) }}</p>
                    </div>
                    @empty
                    <div class="py-16 text-center">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No earnings yet</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

