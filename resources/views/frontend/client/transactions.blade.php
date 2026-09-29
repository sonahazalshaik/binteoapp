<x-app-layout>
    <div class="py-8 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6">
            <!-- Header Section -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-12 gap-8">
                <div>
                    <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter">Transaction Protocol</h1>
                    <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        End-to-end ledger of account activity
                    </p>
                </div>

                <!-- Strategic Filters -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" 
                            class="px-8 py-4 bg-white dark:bg-[#181818] rounded-2xl border border-gray-100 dark:border-white/5 shadow-sm text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-[0.2em] flex items-center gap-3 hover:border-red-500/50 hover:bg-gray-50 dark:hover:bg-white/[0.03] transition-all">
                        <span class="material-symbols-rounded text-xl">tune</span>
                        Protocol Filter
                        <span class="material-symbols-rounded text-lg opacity-40 transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    
                    <div x-show="open" @click.outside="open = false" x-cloak 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         class="absolute right-0 mt-4 w-full sm:w-[400px] bg-white dark:bg-[#181818] rounded-[3rem] border border-gray-100 dark:border-white/10 shadow-[0_40px_80px_-20px_rgba(0,0,0,0.2)] dark:shadow-[0_40px_80px_-20px_rgba(0,0,0,0.6)] p-10 z-[100]">
                        
                        <form action="" method="GET" class="space-y-8">
                            <div class="space-y-3">
                                <label class="px-5 text-[9px] font-black text-gray-400 uppercase tracking-[0.3em] block">Reference Token (TRX)</label>
                                <div class="premium-form-container">
                                    <input type="text" name="search" value="{{ request()->search }}" placeholder="Search ID..." 
                                           class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-red-500/20">
                                </div>
                            </div>

                            <div class="space-y-3">
                                <label class="px-5 text-[9px] font-black text-gray-400 uppercase tracking-[0.3em] block">Flow Direction</label>
                                <div class="premium-form-container">
                                    <select name="trx_type" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 dark:text-white outline-none">
                                        <option value="" class="dark:bg-[#181818]">All Operations</option>
                                        <option value="+" @selected(request()->trx_type == '+') class="dark:bg-[#181818]">Inbound Credit (+)</option>
                                        <option value="-" @selected(request()->trx_type == '-') class="dark:bg-[#181818]">Outbound Debit (-)</option>
                                    </select>
                                </div>
                            </div>

                            @if(isset($remarks) && count($remarks) > 0)
                            <div class="space-y-3">
                                <label class="px-5 text-[9px] font-black text-gray-400 uppercase tracking-[0.3em] block">Context Segment</label>
                                <div class="premium-form-container">
                                    <select name="remark" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 dark:text-white outline-none">
                                        <option value="" class="dark:bg-[#181818]">Universal Feed</option>
                                        @foreach($remarks ?? [] as $remark)
                                            <option value="{{ $remark->remark }}" @selected(request()->remark == $remark->remark) class="dark:bg-[#181818]">{{ __(keyToTitle($remark->remark)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif

                            <button type="submit" class="w-full py-5 bg-red-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.4em] shadow-2xl shadow-red-500/30 hover:bg-red-700 transition-all active:scale-95">Re-establish Feed</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Ledger Core -->
            <div class="bg-white dark:bg-[#181818] rounded-[3.5rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden transition-all duration-500">
                <!-- Desktop High-Fidelity Table -->
                <div class="hidden xl:block">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/20 dark:bg-white/[0.01]">
                            <tr>
                                <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Protocol ID</th>
                                <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Timestamp</th>
                                <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Net Displacement</th>
                                <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Post-Protocol Balance</th>
                                <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Event Signature</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            @forelse($transactions as $trx)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-all group">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-5">
                                        <div class="w-11 h-11 rounded-2xl {{ $trx->trx_type == '+' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-red-500/10 text-red-600' }} flex items-center justify-center border {{ $trx->trx_type == '+' ? 'border-emerald-500/20' : 'border-red-500/20' }} group-hover:scale-110 transition-transform">
                                            <span class="material-symbols-rounded text-xl font-black">{{ $trx->trx_type == '+' ? 'expand_more' : 'expand_less' }}</span>
                                        </div>
                                        <span class="font-black text-sm text-gray-900 dark:text-white group-hover:text-red-500 transition-colors uppercase tracking-tight">{{ $trx->trx ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="text-[11px] font-black text-gray-700 dark:text-gray-300">{{ showDateTime($trx->created_at, 'M d, Y - H:i') }}</div>
                                    <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1 opacity-70">{{ diffForHumans($trx->created_at) }}</div>
                                </td>
                                <td class="px-10 py-8 text-center">
                                    <div class="inline-flex items-center gap-2 px-4 py-2 {{ $trx->trx_type == '+' ? 'bg-emerald-500/5 text-emerald-600' : 'bg-red-500/5 text-red-600' }} rounded-xl border {{ $trx->trx_type == '+' ? 'border-emerald-500/10' : 'border-red-500/10' }}">
                                        <span class="text-sm font-black">{{ $trx->trx_type }}{{ showAmount($trx->amount) }}</span>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-tighter">
                                    {{ showAmount($trx->post_balance) }}
                                </td>
                                <td class="px-10 py-8 text-right">
                                    <span class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest underline decoration-gray-200 dark:decoration-white/5 underline-offset-8">
                                        {{ __($trx->details) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-32 text-center">
                                    <div class="w-24 h-24 rounded-[2.5rem] bg-gray-50 dark:bg-white/5 flex items-center justify-center mx-auto mb-8">
                                        <span class="material-symbols-rounded text-6xl text-gray-100 dark:text-white/5">receipt_long</span>
                                    </div>
                                    <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2 tracking-tight">Ledger Silent</h3>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.3em]">No authenticated events were found for the selected protocol.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile & Tablet Optimized Flow -->
                <div class="xl:hidden divide-y divide-gray-50 dark:divide-white/5">
                    @forelse($transactions as $trx)
                    <div class="p-8 space-y-6 group hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-all">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl {{ $trx->trx_type == '+' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-red-500/10 text-red-600' }} flex items-center justify-center border {{ $trx->trx_type == '+' ? 'border-emerald-500/20' : 'border-red-500/20' }}">
                                    <span class="material-symbols-rounded text-lg font-black">{{ $trx->trx_type == '+' ? 'expand_more' : 'expand_less' }}</span>
                                </div>
                                <div>
                                    <h4 class="font-black text-gray-900 dark:text-white text-sm uppercase tracking-tight">{{ $trx->trx ?? 'N/A' }}</h4>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-1">{{ diffForHumans($trx->created_at) }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-black text-sm {{ $trx->trx_type == '+' ? 'text-emerald-600' : 'text-red-500' }}">
                                    {{ $trx->trx_type }}{{ showAmount($trx->amount) }}
                                </span>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter mt-1">{{ showAmount($trx->post_balance) }}</p>
                            </div>
                        </div>
                        <div class="px-5 py-3 bg-gray-50 dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/5">
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 font-black uppercase tracking-widest leading-relaxed">Sig: {{ __($trx->details) }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="py-24 text-center">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Feed Empty</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Enhanced Pagination -->
            @if($transactions->hasPages())
                <div class="mt-16">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
