@extends('layouts.app')

@section('content')
<div id="purchased-plans" class="min-h-screen bg-[#F8F9FA] dark:bg-[#0F0F0F] transition-colors duration-500 py-10" x-data="{ activeTab: '{{ request()->has('creator_page') ? 'creator' : 'channel' }}' }">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header & Tabs -->
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-5">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-2">{{ $pageTitle ?? 'My Subscriptions' }}</h1>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Manage your active plans, perks, and billing history.</p>
            </div>
            
            <div class="flex items-center bg-gray-200/50 dark:bg-white/5 p-1 rounded-xl self-start md:self-auto border border-gray-200/50 dark:border-white/5 max-w-full">
                <button @click="activeTab = 'channel'" 
                        :class="activeTab === 'channel' ? 'bg-white dark:bg-[#1A1A1A] shadow-sm text-amber-500 dark:text-amber-400 border border-gray-100 dark:border-white/5' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white'" 
                        class="px-2.5 py-1.5 sm:px-5 sm:py-2.5 rounded-lg text-[10px] sm:text-xs font-black uppercase tracking-tight sm:tracking-wider whitespace-nowrap transition-all flex items-center gap-1.5 sm:gap-2">
                    <span class="material-symbols-rounded text-[14px] sm:text-[16px] shrink-0">smart_display</span>
                    <span>Creator Channel Plans</span>
                </button>
                <button @click="activeTab = 'creator'" 
                        :class="activeTab === 'creator' ? 'bg-white dark:bg-[#1A1A1A] shadow-sm text-rose-600 dark:text-rose-400 border border-gray-100 dark:border-white/5' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white'" 
                        class="px-2.5 py-1.5 sm:px-5 sm:py-2.5 rounded-lg text-[10px] sm:text-xs font-black uppercase tracking-tight sm:tracking-wider whitespace-nowrap transition-all flex items-center gap-1.5 sm:gap-2">
                    <span class="material-symbols-rounded text-[14px] sm:text-[16px] shrink-0">stars</span>
                    <span>Creator Plans</span>
                </button>
            </div>
        </div>

        <!-- Channel Plans Tab -->
        <div x-show="activeTab === 'channel'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            @if(!isset($channelPlans) || $channelPlans->isEmpty())
                <div class="w-full bg-white dark:bg-[#1A1A1A] rounded-[2rem] border border-gray-100 dark:border-white/5 py-20 px-4 text-center shadow-sm">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center">
                        <span class="material-symbols-rounded text-4xl text-amber-500">smart_display</span>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">No Channel Subscriptions</h3>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">
                        You haven't subscribed to any channel plans yet. Unlock exclusive premium videos and support your favourite creators.
                    </p>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg hover:shadow-amber-500/25 hover:-translate-y-0.5">
                        <span class="material-symbols-rounded text-[18px]">explore</span>
                        Explore Channels
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 gap-4">
                    @foreach($channelPlans as $plan)
                        @php
                            $channel = $plan->channel;
                            $channelPlan = $plan->plan;
                            $isExpired = $plan->expires_at && \Carbon\Carbon::parse($plan->expires_at)->isPast();
                            $daysLeft = $plan->expires_at && !$isExpired ? max(0, round(now()->diffInDays(\Carbon\Carbon::parse($plan->expires_at), false))) : 0;
                            $channelAvatar = $channel && $channel->avatar ? getImage(getFilePath('channelAvatar') . '/' . $channel->avatar) : null;
                            $channelUrl = $channel ? route('channels.show', $channel->slug ?? $channel->id) : '#';
                            $premiumUrl = $channel ? route('channels.premium', $channel->slug ?? $channel->id) : '#';
                        @endphp
                        <div class="group bg-white dark:bg-[#1A1A1A] rounded-2xl border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden relative">
                            <!-- Status Indicator Line -->
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $isExpired ? 'bg-gray-300 dark:bg-gray-700' : 'bg-amber-500' }}"></div>

                            <div class="p-5 md:p-6 pl-6 md:pl-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                                <div class="flex items-start gap-4 flex-1">
                                    <!-- Channel Avatar -->
                                    <a href="{{ $channelUrl }}" class="shrink-0 group-hover:scale-105 transition-transform">
                                        @if($channelAvatar)
                                            <img src="{{ $channelAvatar }}" alt="{{ $channel->name ?? 'Channel' }}" class="w-12 h-12 md:w-14 md:h-14 rounded-2xl object-cover border border-gray-100 dark:border-white/10 shadow-sm">
                                        @else
                                            <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center font-black text-lg border border-amber-500/20">
                                                {{ substr($channel->name ?? 'C', 0, 1) }}
                                            </div>
                                        @endif
                                    </a>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2.5 mb-2 flex-wrap">
                                            @if($isExpired)
                                                <span class="px-2.5 py-1 bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400 rounded-md text-[9px] font-black uppercase tracking-widest flex items-center gap-1">
                                                    <span class="material-symbols-rounded text-[14px]">history</span> Expired
                                                </span>
                                            @else
                                                <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-md text-[9px] font-black uppercase tracking-widest flex items-center gap-1">
                                                    <span class="material-symbols-rounded text-[14px]">check_circle</span> Active
                                                </span>
                                                @if($plan->expires_at && $daysLeft <= 7)
                                                    <span class="px-2.5 py-1 bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 rounded-md text-[9px] font-black uppercase tracking-widest animate-pulse">
                                                        Expiring Soon
                                                    </span>
                                                @endif
                                            @endif

                                            @if($channelPlan && $channelPlan->duration_label)
                                                <span class="px-2.5 py-1 bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-md text-[9px] font-black uppercase tracking-widest">
                                                    {{ $channelPlan->duration_label }}
                                                </span>
                                            @endif

                                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500">
                                                Purchased: {{ \Carbon\Carbon::parse($plan->starts_at ?? $plan->created_at)->format('d M Y') }}
                                            </span>
                                        </div>

                                        <div class="flex items-baseline gap-2 flex-wrap">
                                            <h4 class="text-lg md:text-xl font-black text-gray-900 dark:text-white">
                                                {{ $plan->plan_name ?? $channelPlan->name ?? 'Channel Membership' }}
                                            </h4>
                                            <span class="text-gray-400 dark:text-gray-600">•</span>
                                            <a href="{{ $channelUrl }}" class="text-sm font-bold text-gray-600 dark:text-gray-300 hover:text-amber-500 dark:hover:text-amber-400 transition-colors flex items-center gap-1">
                                                <span class="material-symbols-rounded text-[15px] text-amber-500">verified</span>
                                                {{ $channel->name ?? 'Channel' }}
                                            </a>
                                        </div>

                                        <div class="flex items-center gap-1.5 text-xs font-bold {{ $isExpired ? 'text-rose-500' : 'text-gray-500 dark:text-gray-400' }} mt-2">
                                            <span class="material-symbols-rounded text-[16px]">event</span>
                                            Expires: {{ $plan->expires_at ? \Carbon\Carbon::parse($plan->expires_at)->format('d M Y') : 'Lifetime' }}
                                            @if(!$isExpired && $plan->expires_at)
                                                <span class="ml-1 text-emerald-500 font-extrabold">({{ $daysLeft }} {{ \Illuminate\Support\Str::plural('day', $daysLeft) }} left)</span>
                                            @endif
                                        </div>

                                        @if($plan->trx)
                                            <p class="text-[10px] font-mono text-gray-400 dark:text-gray-500 mt-1.5">
                                                Trx: #{{ $plan->trx }} {{ $plan->payment_method ? '• ' . strtoupper($plan->payment_method) : '' }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-row md:flex-col items-center md:items-end justify-between w-full md:w-auto border-t md:border-t-0 md:border-l border-gray-100 dark:border-white/10 pt-4 md:pt-0 md:pl-6 shrink-0 gap-3">
                                    <div class="text-left md:text-right">
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Paid</p>
                                        <div class="text-2xl font-black text-gray-900 dark:text-white tracking-tighter">
                                            {{ showAmount($plan->amount, 0) }}
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        @if(!$isExpired)
                                            <a href="{{ $premiumUrl }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-black uppercase tracking-wider transition-all shadow-md shadow-amber-500/20 flex items-center gap-1.5">
                                                <span class="material-symbols-rounded text-[16px]">play_circle</span>
                                                Watch Premium
                                            </a>
                                        @else
                                            <a href="{{ $channelUrl }}" class="px-4 py-2 bg-amber-500/10 hover:bg-amber-500/20 text-amber-600 dark:text-amber-400 rounded-lg text-xs font-black uppercase tracking-wider transition-all flex items-center gap-1.5">
                                                <span class="material-symbols-rounded text-[16px]">autorenew</span>
                                                Renew Pass
                                            </a>
                                        @endif
                                        <a href="{{ $channelUrl }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-white/5 dark:hover:bg-white/10 rounded-lg text-gray-700 dark:text-white text-xs font-bold transition-colors flex items-center gap-1">
                                            <span class="material-symbols-rounded text-[16px]">tv</span>
                                            Channel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($channelPlans->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $channelPlans->appends(['creator_page' => request()->creator_page])->links() }}
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
                    {{ $creatorPlans->appends(['channel_page' => request()->channel_page])->links() }}
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
    html:not(.dark) #purchased-plans span.material-symbols-rounded.text-amber-500 { color: #f59e0b !important; }
    .dark #purchased-plans span.material-symbols-rounded.text-emerald-500 { color: #ffffff !important; }
    .dark #purchased-plans span.material-symbols-rounded.text-rose-500 { color: #ffffff !important; }
    .dark #purchased-plans span.material-symbols-rounded.text-amber-500 { color: #ffffff !important; }
</style>
@endsection
