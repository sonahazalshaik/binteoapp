@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 min-h-screen">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $pageTitle ?? 'Plan Sell History' }}</h1>
    </div>

    <div class="bg-white dark:bg-[#1E1E1E] rounded-3xl shadow-xl border border-gray-100 dark:border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-white/5">
                <thead class="bg-gray-50 dark:bg-white/5">
                    <tr>
                        <th class="px-6 py-4 text-left text-[11px] font-black text-gray-400 uppercase tracking-widest">Subscriber Identity</th>
                        <th class="px-6 py-4 text-left text-[11px] font-black text-gray-400 uppercase tracking-widest">Plan Name</th>
                        <th class="px-6 py-4 text-left text-[11px] font-black text-gray-400 uppercase tracking-widest">Revenue Generated</th>
                        <th class="px-6 py-4 text-left text-[11px] font-black text-gray-400 uppercase tracking-widest">Transaction Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-white/5 bg-white dark:bg-[#1E1E1E]">
                    @forelse($sellPlans as $sell)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900 dark:text-white">@<span>{{ $sell->user->username ?? 'Anonymous' }}</span></div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-600 dark:text-gray-300">{{ $sell->plan_name }}</div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-black text-green-600 dark:text-green-400">+${{ number_format($sell->plan_price ?? 0, 2) }}</div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-[10px] leading-5 font-black rounded-full bg-blue-100 text-blue-800 uppercase tracking-widest">Completed</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-sm font-bold text-gray-500 dark:text-gray-400">No monetization records found for your channel yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
