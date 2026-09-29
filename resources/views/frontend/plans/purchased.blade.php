@extends('layouts.app')

@section('content')
<div id="purchased-plans" class="min-h-screen bg-[#F8F9FA] dark:bg-[#0F0F0F] transition-colors duration-500 py-10" x-data="{ activeTab: '{{ request()->has('creator_page') ? 'creator' : 'ott' }}' }">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header & Tabs -->
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-5">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-2">{{ $pageTitle ?? 'My Subscriptions' }}</h1>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Manage your active plans, perks, and billing history.</p>
            </div>
            
            <div class="flex bg-gray-200/50 dark:bg-white/5 p-1 rounded-xl self-start md:self-auto border border-gray-200/50 dark:border-white/5">
                <button @click="activeTab = 'ott'" 
                        :class="activeTab === 'ott' ? 'bg-white dark:bg-[#1A1A1A] shadow-sm text-emerald-600 dark:text-emerald-400 border border-gray-100 dark:border-white/5' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white'" 
                        class="px-5 py-2.5 rounded-lg text-xs font-black uppercase tracking-wider transition-all flex items-center gap-2">
                    <span class="material-symbols-rounded text-[16px]">movie</span>
                    OTT Plans
                </button>
                <button @click="activeTab = 'creator'" 
                        :class="activeTab === 'creator' ? 'bg-white dark:bg-[#1A1A1A] shadow-sm text-rose-600 dark:text-rose-400 border border-gray-100 dark:border-white/5' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white'" 
                        class="px-5 py-2.5 rounded-lg text-xs font-black uppercase tracking-wider transition-all flex items-center gap-2">
                    <span class="material-symbols-rounded text-[16px]">stars</span>
                    Creator Plans
                </button>
            </div>
        </div>

        <!-- OTT Plans Tab -->
        <div x-show="activeTab === 'ott'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            @if($ottPlans->isEmpty())
                <div class="w-full bg-white dark:bg-[#1A1A1A] rounded-[2rem] border border-gray-100 dark:border-white/5 py-20 px-4 text-center shadow-sm">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center">
                        <span class="material-symbols-rounded text-4xl text-emerald-500">movie_filter</span>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">No OTT Subscriptions</h3>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-6">You haven't subscribed to any premium streaming plans yet.</p>
                    <a href="{{ route('user.ott-plans.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg hover:shadow-emerald-500/25 hover:-translate-y-0.5">
                        Browse OTT Plans
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 gap-4">
                    @foreach($ottPlans as $plan)
                        @php
                            $isExpired = $plan->end_date && \Carbon\Carbon::parse($plan->end_date)->isPast();
                            $daysLeft = $plan->end_date && !$isExpired ? max(0, round(now()->diffInDays(\Carbon\Carbon::parse($plan->end_date), false))) : 0;
                        @endphp
                        <div class="group bg-white dark:bg-[#1A1A1A] rounded-2xl border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden relative">
                            <!-- Status Indicator Line -->
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $isExpired ? 'bg-gray-300 dark:bg-gray-700' : 'bg-emerald-500' }}"></div>

                            <div class="p-5 md:p-6 pl-6 md:pl-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                                
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        @if($isExpired)
                                            <span class="px-2.5 py-1 bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400 rounded-md text-[9px] font-black uppercase tracking-widest flex items-center gap-1">
                                                <span class="material-symbols-rounded text-[14px]">history</span> Expired
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-md text-[9px] font-black uppercase tracking-widest flex items-center gap-1">
                                                <span class="material-symbols-rounded text-[14px]">check_circle</span> Active
                                            </span>
                                            @if($plan->end_date && $daysLeft <= 7)
                                                <span class="px-2.5 py-1 bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 rounded-md text-[9px] font-black uppercase tracking-widest animate-pulse">
                                                    Expiring Soon
                                                </span>
                                            @endif
                                        @endif
                                        <span class="text-xs font-bold text-gray-400 dark:text-gray-500">Purchased: {{ \Carbon\Carbon::parse($plan->start_date ?? $plan->created_at)->format('d M Y') }}</span>
                                    </div>
                                    
                                    <h4 class="text-lg md:text-xl font-black text-gray-900 dark:text-white mb-1">{{ $plan->plan_name ?? $plan->ottPlan->name ?? 'Premium OTT' }}</h4>
                                    
                                    <div class="flex items-center gap-1.5 text-xs font-bold {{ $isExpired ? 'text-rose-500' : 'text-gray-500 dark:text-gray-400' }} mt-2">
                                        <span class="material-symbols-rounded text-[16px]">event</span>
                                        Expires: {{ $plan->end_date ? \Carbon\Carbon::parse($plan->end_date)->format('d M Y') : 'Lifetime' }}
                                        @if(!$isExpired && $plan->end_date)
                                            <span class="ml-1 text-emerald-500">({{ $daysLeft }} days left)</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-row md:flex-col items-center md:items-end justify-between w-full md:w-auto border-t md:border-t-0 md:border-l border-gray-100 dark:border-white/10 pt-4 md:pt-0 md:pl-6 shrink-0">
                                    <div class="text-left md:text-right">
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Paid</p>
                                        <div class="text-2xl font-black text-gray-900 dark:text-white tracking-tighter">
                                            {{ showAmount($plan->amount ?? $plan->ottPlan->price ?? 0, 0) }}
                                        </div>
                                    </div>
                                    <button class="mt-0 md:mt-4 px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-white/5 dark:hover:bg-white/10 rounded-lg text-gray-700 dark:text-white text-xs font-bold transition-colors flex items-center gap-2">
                                        <span class="material-symbols-rounded text-[16px]">receipt_long</span>
                                        Invoice
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($ottPlans->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $ottPlans->appends(['creator_page' => request()->creator_page])->links() }}
                </div>
                @endif
            @endif
        </div>

        <!-- Creator Plans Tab -->
        <div x-show="activeTab === 'creator'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            @if($creatorPlans->isEmpty())
                <div class="w-full bg-white dark:bg-[#1A1A1A] rounded-[2rem] border border-gray-100 dark:border-white/5 py-20 px-4 text-center shadow-sm">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-rose-50 dark:bg-rose-900/20 flex items-center justify-center">
                        <span class="material-symbols-rounded text-4xl text-rose-500">stars</span>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">No Creator Subscriptions</h3>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-6">You haven't subscribed to any premium creator plans.</p>
                    <a href="{{ route('user.plans.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg hover:-translate-y-0.5">
                        Browse Creator Plans
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 gap-4">
                    @foreach($creatorPlans as $plan)
                        @php
                            $isExpired = $plan->expired_date && \Carbon\Carbon::parse($plan->expired_date)->isPast();
                        @endphp
                        <div class="group bg-white dark:bg-[#1A1A1A] rounded-2xl border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden relative">
                            <!-- Status Indicator Line -->
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $isExpired ? 'bg-gray-300 dark:bg-gray-700' : 'bg-rose-500' }}"></div>

                            <div class="p-5 md:p-6 pl-6 md:pl-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                                
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        @if($isExpired)
                                            <span class="px-2.5 py-1 bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400 rounded-md text-[9px] font-black uppercase tracking-widest flex items-center gap-1">
                                                <span class="material-symbols-rounded text-[14px]">history</span> Expired
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 rounded-md text-[9px] font-black uppercase tracking-widest flex items-center gap-1">
                                                <span class="material-symbols-rounded text-[14px]">check_circle</span> Active
                                            </span>
                                        @endif
                                        <span class="text-xs font-bold text-gray-400 dark:text-gray-500">Purchased: {{ \Carbon\Carbon::parse($plan->created_at)->format('d M Y') }}</span>
                                    </div>
                                    
                                    <h4 class="text-lg md:text-xl font-black text-gray-900 dark:text-white mb-1">{{ $plan->plan->name ?? $plan->plan_name ?? 'Creator Subscription' }}</h4>
                                    
                                    <div class="flex items-center gap-1.5 text-xs font-bold {{ $isExpired ? 'text-rose-500' : 'text-gray-500 dark:text-gray-400' }} mt-2">
                                        <span class="material-symbols-rounded text-[16px]">event</span>
                                        Expires: {{ $plan->expired_date ? \Carbon\Carbon::parse($plan->expired_date)->format('d M Y') : 'Lifetime' }}
                                    </div>
                                </div>

                                <div class="flex flex-row md:flex-col items-center md:items-end justify-between w-full md:w-auto border-t md:border-t-0 md:border-l border-gray-100 dark:border-white/10 pt-4 md:pt-0 md:pl-6 shrink-0">
                                    <div class="text-left md:text-right">
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Plan Price</p>
                                        <div class="text-2xl font-black text-gray-900 dark:text-white tracking-tighter">
                                            {{ showAmount($plan->price ?? $plan->plan->price ?? $plan->plan_price ?? 0, 0) }}
                                        </div>
                                    </div>
                                    <a href="{{ route('user.plans.invoice', $plan->id) }}" class="mt-0 md:mt-4 px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-white/5 dark:hover:bg-white/10 rounded-lg text-gray-700 dark:text-white text-xs font-bold transition-colors flex items-center gap-2">
                                        <span class="material-symbols-rounded text-[16px]">receipt_long</span>
                                        Invoice
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($creatorPlans->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $creatorPlans->appends(['ott_page' => request()->ott_page])->links() }}
                </div>
                @endif
            @endif
        </div>

    </div>
</div>

<style>
    /* Page-scoped icon visibility fix (this page only).
       The layout globally forces `.material-symbols-rounded` to `color: inherit`,
       which beats Tailwind's text-color utilities and leaves icons dark-on-dark
       in dark mode. Light mode restores originals; dark mode renders white. */
    html:not(.dark) #purchased-plans span.material-symbols-rounded.text-emerald-500 { color: #10b981 !important; }
    html:not(.dark) #purchased-plans span.material-symbols-rounded.text-rose-500 { color: #f43f5e !important; }
    .dark #purchased-plans span.material-symbols-rounded.text-emerald-500 { color: #ffffff !important; }
    .dark #purchased-plans span.material-symbols-rounded.text-rose-500 { color: #ffffff !important; }
</style>
@endsection
