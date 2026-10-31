<x-app-layout>
    <div class="py-10 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-12 gap-6">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Purchased Plans</h1>
                    <p class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2">Active and expired plan subscriptions</p>
                </div>
                <a href="{{ route('user.plans.index') }}" class="px-8 py-3.5 bg-gray-900 dark:bg-white text-white dark:text-black rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl hover:opacity-90 transition-all active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-rounded text-lg">auto_awesome</span>
                    Browse Plans
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($purchases ?? [] as $purchase)
                <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] p-8 border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-xl transition-all group">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-red-600 to-orange-500 flex items-center justify-center text-white shadow-lg shadow-red-500/20">
                            <span class="material-symbols-rounded text-2xl">workspace_premium</span>
                        </div>
                        @if($purchase->is_active ?? false)
                            <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-600 text-[9px] font-black uppercase border border-emerald-500/10">Active</span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-gray-100 dark:bg-white/5 text-gray-400 text-[9px] font-black uppercase">Expired</span>
                        @endif
                    </div>
                    <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">{{ $purchase->plan->name ?? 'Plan' }}</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Purchased {{ showDateTime($purchase->created_at, 'd M Y') }}</p>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-3 bg-gray-50/50 dark:bg-white/2 rounded-xl">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Amount Paid</span>
                            <span class="text-xs font-black text-gray-900 dark:text-white">{{ showAmount($purchase->amount ?? $purchase->price ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50/50 dark:bg-white/2 rounded-xl">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Duration</span>
                            <span class="text-xs font-black text-gray-900 dark:text-white">{{ $purchase->plan->duration ?? 30 }} Days</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full bg-white dark:bg-[#1A1A1A] rounded-[2rem] p-20 border border-gray-100 dark:border-white/5 text-center">
                    <span class="material-symbols-rounded text-6xl text-gray-200 dark:text-white/5 mb-6">credit_card_off</span>
                    <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">No Plans Purchased</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-8">Browse available plans to unlock premium features</p>
                    <a href="{{ route('user.plans.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-red-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-red-500/20 hover:bg-red-700 transition-all">
                        Explore Plans
                    </a>
                </div>
                @endforelse
            </div>

            @if(isset($purchases) && $purchases->hasPages())
            <div class="mt-12">
                {{ $purchases->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
