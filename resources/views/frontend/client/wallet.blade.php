<x-app-layout>
    <div class="py-8 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6">
            <!-- Header -->
            <div class="mb-10">
                <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Financial Hub</h1>
                <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2">Manage your earnings and payouts</p>
            </div>

            <!-- Main Balance Card -->
            <div class="relative overflow-hidden rounded-[3rem] p-8 sm:p-12 mb-10 shadow-2xl transition-all duration-500 group" 
                 style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                <!-- Background Decoration -->
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-emerald-500/20 rounded-full blur-[100px] group-hover:bg-emerald-500/30 transition-all duration-700"></div>
                <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-60 h-60 bg-blue-500/20 rounded-full blur-[80px] group-hover:bg-blue-500/30 transition-all duration-700"></div>
                
                <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-10">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center">
                                <span class="material-symbols-rounded text-white text-base">account_balance_wallet</span>
                            </div>
                            <p class="text-[10px] font-black text-white/50 uppercase tracking-[0.3em]">Available Balance</p>
                        </div>
                        <h1 class="text-5xl sm:text-7xl font-black text-white tracking-tighter mb-6">{{ showAmount($user->balance) }}</h1>

                        <div class="flex flex-wrap gap-4">
                            @if(@$user->withdrawSetting && @$user->withdrawSetting->withdrawMethod)
                                <div class="flex items-center gap-2.5 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl px-5 py-2.5 backdrop-blur-sm">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                    </span>
                                    <span class="text-[9px] font-black text-emerald-400 uppercase tracking-widest">Payout Configured</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2.5 bg-amber-500/10 border border-amber-500/20 rounded-2xl px-5 py-2.5 backdrop-blur-sm">
                                    <span class="material-symbols-rounded text-amber-500 text-base">warning</span>
                                    <span class="text-[9px] font-black text-amber-400 uppercase tracking-widest">Setup Payout Method</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                        <a href="{{ route('user.withdraw.methods') }}" class="flex-1 lg:flex-none px-10 py-5 bg-white text-black rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:-translate-y-1 transition-all active:scale-95 text-center flex items-center justify-center gap-3">
                            <span class="material-symbols-rounded text-xl">payments</span>
                            Get Paid
                        </a>
                        <a href="{{ route('user.deposit') }}" class="flex-1 lg:flex-none px-10 py-5 bg-white/10 text-white border border-white/10 rounded-2xl font-black text-xs uppercase tracking-[0.2em] backdrop-blur-md hover:bg-white/20 transition-all active:scale-95 text-center flex items-center justify-center gap-3">
                            <span class="material-symbols-rounded text-xl">add_card</span>
                            Add Funds
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-12">
                @php
                    $stats = [
                        ['Total Income', showAmount($totalEarned ?? 0), 'trending_up', 'text-emerald-500', 'bg-emerald-500/10'],
                        ['Withdrawn', showAmount($totalWithdrawn ?? 0), 'money_off', 'text-red-500', 'bg-red-500/10'],
                        ['Pending', showAmount($pendingWithdraw ?? 0), 'hourglass_empty', 'text-amber-500', 'bg-amber-500/10'],
                        ['Deposits', showAmount($totalDeposit ?? 0), 'savings', 'text-blue-500', 'bg-blue-500/10']
                    ];
                @endphp
                @foreach($stats as $stat)
                <div class="bg-white dark:bg-[#181818] p-6 rounded-[2rem] border border-gray-100 dark:border-white/5 shadow-sm transition-all duration-300 hover:shadow-lg">
                    <div class="w-10 h-10 rounded-xl {{ $stat[4] }} flex items-center justify-center mb-5">
                        <span class="material-symbols-rounded {{ $stat[3] }} text-xl">{{ $stat[2] }}</span>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tight mb-1">{{ $stat[1] }}</h3>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $stat[0] }}</p>
                </div>
                @endforeach
            </div>

            <!-- Transaction History Card -->
            <div class="bg-white dark:bg-[#181818] rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden transition-colors duration-500">
                <div class="px-8 py-6 border-b border-gray-50 dark:border-white/5 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-widest">Withdraw History</h2>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Your latest payout requests</p>
                    </div>
                    <a href="{{ route('user.withdraw.log') }}" class="text-[10px] font-black text-red-500 uppercase tracking-widest hover:bg-red-50 dark:hover:bg-white/5 px-4 py-2 rounded-xl transition-all">
                        View All
                    </a>
                </div>

                <!-- Desktop Table -->
                <div class="hidden md:block">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50 dark:bg-white/[0.02]">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Method</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Amount</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            @forelse($withdraws as $withdraw)
                            <tr class="hover:bg-gray-50/30 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="px-8 py-5 text-sm font-bold text-gray-700 dark:text-gray-300">{{ showDateTime($withdraw->created_at, 'd M, Y') }}</td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-white/5 flex items-center justify-center">
                                            <span class="material-symbols-rounded text-base text-gray-400">payments</span>
                                        </div>
                                        <span class="text-sm font-black text-gray-900 dark:text-white">{{ $withdraw->method->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-sm font-black text-gray-900 dark:text-white text-center">{{ showAmount($withdraw->amount) }}</td>
                                <td class="px-8 py-5">
                                    <div class="flex justify-center">
                                        @if($withdraw->status == 1)
                                            <span class="px-4 py-1.5 rounded-full bg-emerald-500/10 text-emerald-600 text-[9px] font-black uppercase tracking-widest">Approved</span>
                                        @elseif($withdraw->status == 2)
                                            <span class="px-4 py-1.5 rounded-full bg-amber-500/10 text-amber-600 text-[9px] font-black uppercase tracking-widest">Pending</span>
                                        @elseif($withdraw->status == 3)
                                            <span class="px-4 py-1.5 rounded-full bg-red-500/10 text-red-600 text-[9px] font-black uppercase tracking-widest">Rejected</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('user.withdraw.preview', $withdraw->id) }}" class="text-gray-400 hover:text-red-500 transition-colors">
                                        <span class="material-symbols-rounded text-lg">info</span>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center">
                                    <span class="material-symbols-rounded text-5xl text-gray-200 dark:text-white/5 mb-4 block">account_balance_wallet</span>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No payout history</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View -->
                <div class="md:hidden divide-y divide-gray-50 dark:divide-white/5">
                    @forelse($withdraws as $withdraw)
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="font-black text-gray-900 dark:text-white text-sm tracking-tight">{{ $withdraw->method->name ?? 'N/A' }}</h4>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">{{ showDateTime($withdraw->created_at, 'd M, Y') }}</p>
                            </div>
                            <span class="text-sm font-black text-gray-900 dark:text-white">{{ showAmount($withdraw->amount) }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-gray-50/50 dark:bg-white/2 p-3 rounded-xl border border-gray-100 dark:border-white/5">
                            @if($withdraw->status == 1)
                                <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">Approved</span>
                            @elseif($withdraw->status == 2)
                                <span class="text-[9px] font-black text-amber-500 uppercase tracking-widest">Pending</span>
                            @else
                                <span class="text-[9px] font-black text-red-500 uppercase tracking-widest">Rejected</span>
                            @endif
                            <a href="{{ route('user.withdraw.preview', $withdraw->id) }}" class="text-[9px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-1">Details <span class="material-symbols-rounded text-sm">chevron_right</span></a>
                        </div>
                    </div>
                    @empty
                    <div class="py-16 text-center">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No payouts yet</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
