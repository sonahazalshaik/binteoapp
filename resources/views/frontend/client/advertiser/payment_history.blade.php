<x-app-layout>
    <div class="py-10 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-12 gap-6">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Billing History</h1>
                    <p class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2">All advertising payments and charges</p>
                </div>
                <div class="flex items-center gap-4">
                     <form action="" method="GET" class="relative group">
                        <input type="text" name="search" value="{{ request()->search }}" placeholder="Search TRX..." class="pl-12 pr-6 py-3.5 bg-white dark:bg-[#1A1A1A] border border-gray-100 dark:border-white/5 rounded-2xl text-xs font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none w-64 transition-all">
                        <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-red-500 transition-colors">search</span>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="hidden md:block">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50 dark:bg-white/2 border-b border-gray-100 dark:border-white/5">
                            <tr>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">TRX ID</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Net Amount</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Paid</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            @forelse($payments as $trx)
                            <tr class="hover:bg-gray-50/30 dark:hover:bg-white/2 transition-colors">
                                <td class="px-8 py-6">
                                    <span class="font-black text-sm text-gray-900 dark:text-white">{{ $trx->trx ?? 'N/A' }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ showDateTime($trx->created_at, 'd M Y') }}</div>
                                    <div class="text-[9px] font-bold text-gray-400 uppercase mt-0.5">{{ diffForHumans($trx->created_at) }}</div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="font-black text-sm text-gray-900 dark:text-white @if ($trx->trx_type == '+') text-emerald-600 @else text-red-500 @endif">
                                            {{ showAmount($trx->amount) }}
                                        </span>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase">Charge: {{ showAmount($trx->charge) }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="font-black text-sm text-gray-900 dark:text-white @if ($trx->trx_type == '+') text-emerald-600 @else text-red-500 @endif">
                                        {{ showAmount($trx->final_amount) }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex justify-center">
                                        @php echo $trx->statusBadge; @endphp
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <span class="material-symbols-rounded text-6xl text-gray-200 dark:text-white/5 mb-6">receipt</span>
                                    <p class="text-sm font-black text-gray-400 uppercase tracking-widest">No payment records found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="md:hidden divide-y divide-gray-50 dark:divide-white/5">
                    @forelse($payments as $trx)
                    <div class="p-6 space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-black text-gray-900 dark:text-white text-sm">{{ $trx->trx ?? 'N/A' }}</h4>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1">{{ diffForHumans($trx->created_at) }}</p>
                            </div>
                            <div class="text-right">
                                <span class="font-black text-sm block {{ $trx->trx_type == '+' ? 'text-emerald-600' : 'text-red-500' }}">
                                    {{ showAmount($trx->final_amount) }}
                                </span>
                                <span class="text-[8px] font-bold text-gray-400 uppercase">Charge: {{ showAmount($trx->charge) }}</span>
                            </div>
                        </div>
                        <div class="flex justify-center pt-2">
                             @php echo $trx->statusBadge; @endphp
                        </div>
                    </div>
                    @empty
                    <div class="py-16 text-center">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No billing history yet</p>
                    </div>
                    @endforelse
                </div>
            </div>

            @if($payments->hasPages())
            <div class="mt-12">
                {{ $payments->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
