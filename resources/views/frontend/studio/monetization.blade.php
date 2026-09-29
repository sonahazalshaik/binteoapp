<x-app-layout>
    <div class="py-8 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500">
        
        @php
            $subProgress = min(100, ($totalSubscribers / ($general->minimum_subscribe ?: 1000)) * 100);
            $viewProgress = min(100, ($totalViews / ($general->minimum_views ?: 10000)) * 100);
            $hourProgress = min(100, ($totalWatchHours / ($general->watch_hours ?: 4000)) * 100);
            
            $isEligible = ($subProgress == 100 && $viewProgress == 100 && $hourProgress == 100);
            
            $user = auth()->user(); 
            $paidAmount = gs('monetization_amount');

            // Client earnings calculations
            $settledAdEarnings  = $user->transactions()->where('remark', 'ads_revenue')->sum('amount');
            $videoSalesEarnings = $user->transactions()->whereIn('remark', ['earn_from_video', 'video_ppv_commission'])->sum('amount');
            $estimatedAdEarnings = \App\Models\VideoEarning::whereHas('video', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->sum('estimated_revenue');
            $totalEarnings = $settledAdEarnings + $videoSalesEarnings + $estimatedAdEarnings;
            $playlistEarnings = 0;
            $planEarnings     = 0;
            $adminCommission  = 0;
        @endphp

        <div class="max-w-[1200px] mx-auto px-6 lg:px-10">
            <!-- Header Section -->
            <div class="flex flex-col items-center text-center mb-10 md:mb-16">
                <div class="w-24 h-24 md:w-20 md:h-20 rounded-[2.5rem] md:rounded-[2rem] bg-gradient-to-br from-orange-400 to-rose-500 text-white flex items-center justify-center shadow-2xl shadow-orange-500/40 mb-6 md:mb-8 relative group animate-in zoom-in duration-700">
                    <div class="absolute inset-0 bg-white/20 rounded-[2.5rem] md:rounded-[2rem] opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <span class="material-symbols-rounded text-[2.5rem] md:text-4xl relative z-10">monetization_on</span>
                </div>
                <h1 class="text-3xl md:text-5xl font-black text-gray-900 dark:text-white tracking-tighter mb-3 md:mb-4 animate-in slide-in-from-top-8 duration-700 bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400">Monetization Studio</h1>
                <p class="text-[11px] md:text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-[0.2em] max-w-xl mx-auto leading-relaxed animate-in fade-in duration-1000">Track your earnings, memberships, and qualification progress.</p>
            </div>

            @if($user->monetization_status != \App\Constants\Status::MONETIZATION_APPROVED)
            <!-- Road to Monetization (Dynamic Progress) -->
            <div class="mb-16">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 px-2 gap-5">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">Road to Monetization</h2>
                        <p class="text-[10px] md:text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mt-2">Qualification status based on platform standards</p>
                    </div>
                    @if($isEligible)
                        <div class="px-6 py-3 md:py-2 bg-emerald-500/10 text-emerald-500 rounded-2xl md:rounded-full text-[11px] md:text-[10px] font-black uppercase tracking-[0.2em] md:tracking-widest border border-emerald-500/20 flex items-center gap-2 w-full sm:w-auto justify-center shadow-lg shadow-emerald-500/5">
                            <span class="material-symbols-rounded text-[18px] md:text-sm">verified</span>
                            <span>Eligible for Review</span>
                        </div>
                    @else
                        <div class="px-6 py-3 md:py-2 bg-amber-500/10 text-amber-500 rounded-2xl md:rounded-full text-[11px] md:text-[10px] font-black uppercase tracking-[0.2em] md:tracking-widest border border-amber-500/20 flex items-center gap-2 w-full sm:w-auto justify-center shadow-lg shadow-amber-500/5">
                            <span class="material-symbols-rounded text-[18px] md:text-sm">pending</span>
                            <span>In Progress</span>
                        </div>
                    @endif
                </div>

            <div x-data="carouselProgress()" x-init="initCarousel($refs.carouselContainer)" class="mb-16">
                <div x-ref="carouselContainer" @scroll.passive="updateProgress($event.target)" class="flex md:grid overflow-x-auto md:overflow-visible snap-x snap-mandatory md:grid-cols-3 gap-5 md:gap-6 pb-4 -mx-6 px-6 lg:mx-0 lg:px-0 hide-scrollbar">
                    
                    <!-- Subscribers Progress -->
                    <div class="min-w-[70vw] sm:min-w-[240px] md:min-w-0 snap-center shrink-0 w-full md:w-auto relative overflow-hidden rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-md group hover:scale-[1.02] transition-all duration-500 bg-gradient-to-br from-emerald-400 to-emerald-600 border border-emerald-400/30 text-white">
                        <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="relative z-10 flex sm:flex-col items-start gap-3 sm:gap-0 w-full">
                            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white sm:mb-3 group-hover:scale-110 transition-transform shrink-0">
                                <span class="material-symbols-rounded text-base sm:text-lg">group</span>
                            </div>
                            <div class="min-w-0 w-full">
                                <h3 class="text-sm sm:text-base font-black text-white tracking-tight truncate">{{ number_format($totalSubscribers) }}</h3>
                                <p class="text-[7px] sm:text-[8px] font-black text-white/70 uppercase tracking-widest truncate">Subscribers</p>
                                <p class="text-[6px] sm:text-[7px] text-white/50 font-bold uppercase tracking-widest truncate mt-0.5">Goal: {{ number_format($general->minimum_subscribe ?: 1000) }}</p>
                                
                                <div class="mt-2 w-full">
                                    <div class="h-1 w-full bg-white/20 rounded-full overflow-hidden mb-0.5">
                                        <div class="h-full bg-white rounded-full transition-all duration-1000 ease-out" style="width: {{ $subProgress }}%"></div>
                                    </div>
                                    <div class="flex justify-between text-[6px] font-black text-white/80 uppercase tracking-widest">
                                        <span>Progress</span>
                                        <span>{{ round($subProgress, 0) }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="material-symbols-rounded absolute -right-2 -bottom-2 text-[50px] sm:text-[70px] rotate-12 group-hover:scale-125 transition-transform duration-700 pointer-events-none" style="color: #000000 !important; opacity: 0.15;">group</span>
                    </div>

                    <!-- Views Progress -->
                    <div class="min-w-[70vw] sm:min-w-[240px] md:min-w-0 snap-center shrink-0 w-full md:w-auto relative overflow-hidden rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-md group hover:scale-[1.02] transition-all duration-500 bg-gradient-to-br from-blue-400 to-blue-600 border border-blue-400/30 text-white">
                        <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="relative z-10 flex sm:flex-col items-start gap-3 sm:gap-0 w-full">
                            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white sm:mb-3 group-hover:scale-110 transition-transform shrink-0">
                                <span class="material-symbols-rounded text-base sm:text-lg">visibility</span>
                            </div>
                            <div class="min-w-0 w-full">
                                <h3 class="text-sm sm:text-base font-black text-white tracking-tight truncate">{{ number_format($totalViews) }}</h3>
                                <p class="text-[7px] sm:text-[8px] font-black text-white/70 uppercase tracking-widest truncate">Total Views</p>
                                <p class="text-[6px] sm:text-[7px] text-white/50 font-bold uppercase tracking-widest truncate mt-0.5">Goal: {{ number_format($general->minimum_views ?: 10000) }}</p>
                                
                                <div class="mt-2 w-full">
                                    <div class="h-1 w-full bg-white/20 rounded-full overflow-hidden mb-0.5">
                                        <div class="h-full bg-white rounded-full transition-all duration-1000 ease-out" style="width: {{ $viewProgress }}%"></div>
                                    </div>
                                    <div class="flex justify-between text-[6px] font-black text-white/80 uppercase tracking-widest">
                                        <span>Progress</span>
                                        <span>{{ round($viewProgress, 0) }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="material-symbols-rounded absolute -right-2 -bottom-2 text-[50px] sm:text-[70px] rotate-12 group-hover:scale-125 transition-transform duration-700 pointer-events-none" style="color: #000000 !important; opacity: 0.15;">visibility</span>
                    </div>

                    <!-- Watch Hours Progress -->
                    <div class="min-w-[70vw] sm:min-w-[240px] md:min-w-0 snap-center shrink-0 w-full md:w-auto relative overflow-hidden rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-md group hover:scale-[1.02] transition-all duration-500 bg-gradient-to-br from-purple-400 to-purple-600 border border-purple-400/30 text-white">
                        <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="relative z-10 flex sm:flex-col items-start gap-3 sm:gap-0 w-full">
                            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white sm:mb-3 group-hover:scale-110 transition-transform shrink-0">
                                <span class="material-symbols-rounded text-base sm:text-lg">schedule</span>
                            </div>
                            <div class="min-w-0 w-full">
                                <h3 class="text-sm sm:text-base font-black text-white tracking-tight truncate">{{ number_format($totalWatchHours, 1) }}</h3>
                                <p class="text-[7px] sm:text-[8px] font-black text-white/70 uppercase tracking-widest truncate">Watch Hours</p>
                                <p class="text-[6px] sm:text-[7px] text-white/50 font-bold uppercase tracking-widest truncate mt-0.5">Goal: {{ number_format($general->watch_hours ?: 4000) }}</p>
                                
                                <div class="mt-2 w-full">
                                    <div class="h-1 w-full bg-white/20 rounded-full overflow-hidden mb-0.5">
                                        <div class="h-full bg-white rounded-full transition-all duration-1000 ease-out" style="width: {{ $hourProgress }}%"></div>
                                    </div>
                                    <div class="flex justify-between text-[6px] font-black text-white/80 uppercase tracking-widest">
                                        <span>Progress</span>
                                        <span>{{ round($hourProgress, 0) }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="material-symbols-rounded absolute -right-2 -bottom-2 text-[50px] sm:text-[70px] rotate-12 group-hover:scale-125 transition-transform duration-700 pointer-events-none" style="color: #000000 !important; opacity: 0.15;">schedule</span>
                    </div>

                </div>

                <!-- Mobile Carousel Progress Indicators -->
                <div class="flex md:hidden justify-center items-center gap-2 mt-2">
                    <template x-for="i in 3">
                        <div class="h-1.5 rounded-full transition-all duration-300" :class="activeIndex === (i-1) ? 'w-6 bg-indigo-500' : 'w-1.5 bg-gray-300 dark:bg-gray-700'"></div>
                    </template>
                </div>
            </div>
            </div>

            <!-- Application Action Section -->
            <div class="mb-16 animate-in slide-in-from-bottom-10 duration-1000">
                <div class="bg-white dark:bg-[#181818] rounded-[2.5rem] md:rounded-[3rem] p-8 md:p-10 border border-gray-100 dark:border-white/5 shadow-2xl shadow-indigo-500/5 relative overflow-hidden">
                    <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-10 lg:gap-8">
                        <div class="flex flex-col md:flex-row items-center gap-6 md:gap-8 text-center md:text-left w-full">
                            <div class="w-24 h-24 md:w-20 md:h-20 rounded-[2.5rem] bg-indigo-500/10 text-indigo-500 flex items-center justify-center shrink-0 shadow-inner">
                                @if($user->monetization_status == Status::MONETIZATION_INITIATE)
                                    <span class="material-symbols-rounded text-[2.5rem] md:text-4xl">verified_user</span>
                                @elseif($user->monetization_status == Status::MONETIZATION_APPLYING)
                                    <span class="material-symbols-rounded text-[2.5rem] md:text-4xl animate-pulse">history_edu</span>
                                @elseif($user->monetization_status == Status::MONETIZATION_APPROVED)
                                    <span class="material-symbols-rounded text-[2.5rem] md:text-4xl">check_circle</span>
                                @else
                                    <span class="material-symbols-rounded text-[2.5rem] md:text-4xl">error</span>
                                @endif
                            </div>
                            <div class="max-w-xl">
                                @if($user->monetization_status == Status::MONETIZATION_INITIATE)
                                    @if($isEligible)
                                        <h3 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white mb-2 md:mb-3 tracking-tight">Ready for Prime Time?</h3>
                                        <p class="text-[11px] md:text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] md:tracking-widest leading-relaxed">You've cleared all hurdles. Join the Creator Program now.</p>
                                    @else
                                        <h3 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white mb-2 md:mb-3 tracking-tight">Want to skip the wait?</h3>
                                        <p class="text-[11px] md:text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] md:tracking-widest leading-relaxed">You haven't reached the milestones yet, but you can unlock monetization instantly.</p>
                                    @endif
                                @elseif($user->monetization_status == Status::MONETIZATION_APPLYING)
                                    <h3 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white mb-2 md:mb-3 tracking-tight">Review in Progress</h3>
                                    <p class="text-[11px] md:text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] md:tracking-widest leading-relaxed">Our team is carefully auditing your channel metrics.</p>
                                @elseif($user->monetization_status == Status::MONETIZATION_APPROVED)
                                    <h3 class="text-2xl md:text-3xl font-black text-emerald-500 mb-2 md:mb-3 tracking-tight">Monetization Active</h3>
                                    <p class="text-[11px] md:text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] md:tracking-widest leading-relaxed">Congratulations! Your channel is now generating revenue.</p>
                                @else
                                    <h3 class="text-2xl md:text-3xl font-black text-rose-500 mb-2 md:mb-3 tracking-tight">Application Rejected</h3>
                                    <p class="text-[11px] md:text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] md:tracking-widest leading-relaxed">Please review our policies and try again later.</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto shrink-0">
                            @if($user->monetization_status == Status::MONETIZATION_INITIATE)
                                @if($isEligible)
                                    <form action="{{ route('user.monetization.apply') }}" method="POST" class="w-full sm:w-auto">
                                        @csrf
                                        <button type="submit" class="w-full px-10 md:px-12 py-4 md:py-5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-[1.5rem] md:rounded-[2rem] font-black text-[11px] md:text-xs uppercase tracking-[0.2em] shadow-2xl shadow-indigo-500/40 transition-all active:scale-95 flex items-center justify-center gap-3 group">
                                            <span>Apply for Monetization</span>
                                            <span class="material-symbols-rounded text-lg group-hover:translate-x-1 transition-transform">arrow_forward</span>
                                        </button>
                                    </form>
                                @endif

                                @if($paidAmount > 0)
                                    <button type="button" onclick="openPaidMonetizationSwal()" class="w-full sm:w-auto px-4 sm:px-8 md:px-10 py-3 md:py-5 bg-gradient-to-br from-amber-400 to-orange-500 hover:from-amber-500 hover:to-orange-600 text-white rounded-[1.5rem] md:rounded-[2rem] font-black text-[10px] md:text-xs uppercase tracking-wider md:tracking-[0.2em] shadow-2xl shadow-amber-500/40 transition-all active:scale-95 flex items-center justify-center gap-2 group border border-amber-400/50 whitespace-nowrap">
                                        <span class="material-symbols-rounded text-lg">workspace_premium</span>
                                        <span>Unlock for {{ showAmount($paidAmount, 0) }}</span>
                                    </button>
                                @endif
                            @else
                                <div class="w-full px-10 md:px-12 py-4 md:py-5 bg-gray-100 dark:bg-white/5 text-gray-400 dark:text-gray-500 rounded-[1.5rem] md:rounded-[2rem] font-black text-[11px] md:text-xs uppercase tracking-[0.2em] text-center cursor-not-allowed">
                                    @if($user->monetization_status == Status::MONETIZATION_APPLYING)
                                        Status: Pending Review
                                    @elseif($user->monetization_status == Status::MONETIZATION_APPROVED)
                                        Status: Partner
                                    @else
                                        Status: Not Eligible
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- Background Accent -->
                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>
                </div>
            </div>

            @else
            <!-- Revenue Overview Grid -->
            <style>
                .hide-scrollbar::-webkit-scrollbar { display: none; }
                .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            </style>
            <div x-data="carouselProgress()" x-init="initCarousel($refs.carouselContainer)" class="mb-16">
                <div x-ref="carouselContainer" @scroll.passive="updateProgress($event.target)" class="flex md:grid overflow-x-auto md:overflow-visible snap-x snap-mandatory md:grid-cols-3 gap-5 md:gap-6 pb-4 -mx-6 px-6 lg:mx-0 lg:px-0 hide-scrollbar">
                    
                    <!-- Total Earnings -->
                    <div class="min-w-[70vw] sm:min-w-[240px] md:min-w-0 snap-center shrink-0 w-full md:w-auto relative overflow-hidden rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-md group hover:scale-[1.02] transition-all duration-500 bg-gradient-to-br from-emerald-400 to-emerald-600 border border-emerald-400/30 text-white">
                        <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="relative z-10 flex sm:flex-col items-center sm:items-start gap-3 sm:gap-0">
                            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white sm:mb-3 group-hover:scale-110 transition-transform shrink-0">
                                <span class="material-symbols-rounded text-base sm:text-lg">payments</span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-sm sm:text-base font-black text-white tracking-tight truncate">{{ showAmount($totalEarnings, 0) }}</h3>
                                <p class="text-[7px] sm:text-[8px] font-black text-white/70 uppercase tracking-widest truncate">Total Earnings</p>
                                <p class="text-[6px] sm:text-[7px] text-white/50 font-bold uppercase tracking-widest truncate mt-0.5">Lifetime Revenue</p>
                            </div>
                        </div>
                        <span class="material-symbols-rounded absolute -right-2 -bottom-2 text-[50px] sm:text-[70px] rotate-12 group-hover:scale-125 transition-transform duration-700 pointer-events-none" style="color: #000000 !important; opacity: 0.15;">savings</span>
                    </div>

                    <!-- This Month -->
                    <div class="min-w-[70vw] sm:min-w-[240px] md:min-w-0 snap-center shrink-0 w-full md:w-auto relative overflow-hidden rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-md group hover:scale-[1.02] transition-all duration-500 bg-gradient-to-br from-blue-400 to-blue-600 border border-blue-400/30 text-white">
                        <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="relative z-10 flex sm:flex-col items-center sm:items-start gap-3 sm:gap-0">
                            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white sm:mb-3 group-hover:scale-110 transition-transform shrink-0">
                                <span class="material-symbols-rounded text-base sm:text-lg">calendar_month</span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-sm sm:text-base font-black text-white tracking-tight truncate">{{ showAmount($thisMonthEarnings, 0) }}</h3>
                                <p class="text-[7px] sm:text-[8px] font-black text-white/70 uppercase tracking-widest truncate">This Month</p>
                                <p class="text-[6px] sm:text-[7px] text-white/50 font-bold uppercase tracking-widest truncate mt-0.5">{{ now()->format('F Y') }}</p>
                            </div>
                        </div>
                        <span class="material-symbols-rounded absolute -right-2 -bottom-2 text-[50px] sm:text-[70px] rotate-12 group-hover:scale-125 transition-transform duration-700 pointer-events-none" style="color: #000000 !important; opacity: 0.15;">trending_up</span>
                    </div>

                    <!-- Video Sales -->
                    <div class="min-w-[70vw] sm:min-w-[240px] md:min-w-0 snap-center shrink-0 w-full md:w-auto relative overflow-hidden rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-md group hover:scale-[1.02] transition-all duration-500 bg-gradient-to-br from-purple-400 to-purple-600 border border-purple-400/30 text-white">
                        <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="relative z-10 flex sm:flex-col items-center sm:items-start gap-3 sm:gap-0">
                            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white sm:mb-3 group-hover:scale-110 transition-transform shrink-0">
                                <span class="material-symbols-rounded text-base sm:text-lg">movie</span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-sm sm:text-base font-black text-white tracking-tight truncate">{{ showAmount($videoSalesEarnings, 0) }}</h3>
                                <p class="text-[7px] sm:text-[8px] font-black text-white/70 uppercase tracking-widest truncate">Video Sales</p>
                                <p class="text-[6px] sm:text-[7px] text-white/50 font-bold uppercase tracking-widest truncate mt-0.5">Pay-Per-View Income</p>
                            </div>
                        </div>
                        <span class="material-symbols-rounded absolute -right-2 -bottom-2 text-[50px] sm:text-[70px] rotate-12 group-hover:scale-125 transition-transform duration-700 pointer-events-none" style="color: #000000 !important; opacity: 0.15;">movie</span>
                    </div>

                </div>
                
                <!-- Mobile Carousel Progress Indicators -->
                <div class="flex md:hidden justify-center items-center gap-2 mt-2">
                    <template x-for="i in 3">
                        <div class="h-1.5 rounded-full transition-all duration-300" :class="activeIndex === (i-1) ? 'w-6 bg-indigo-500' : 'w-1.5 bg-gray-300 dark:bg-gray-700'"></div>
                    </template>
                </div>
            </div>

        @php
            $userTransactions = auth()->user()->transactions()->latest()->take(10)->get();
            $earningVideos = auth()->user()->videos()
                ->whereHas('earnings')
                ->withSum('earnings', 'estimated_revenue')
                ->orderByDesc('earnings_sum_estimated_revenue')
                ->take(5)
                ->get();
            $recentSubscribers = auth()->user()->channel 
                ? auth()->user()->channel->subscribers()->latest()->take(5)->get() 
                : collect();
        @endphp

        <!-- Interactive Analytics & Tools -->
        <div x-data="{ activeTab: 'history' }" class="mb-16">
            <!-- Navigation Tabs -->
            <div class="flex items-center gap-6 border-b border-gray-100 dark:border-white/5 pb-4 mb-8 overflow-x-auto scrollbar-hide">
                <button @click="activeTab = 'history'" :class="activeTab === 'history' ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400' : 'text-gray-400 dark:text-gray-500'" class="pb-2 text-xs font-black uppercase tracking-widest transition-all outline-none whitespace-nowrap">
                    Transaction History
                </button>
                <button @click="activeTab = 'videos'" :class="activeTab === 'videos' ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400' : 'text-gray-400 dark:text-gray-500'" class="pb-2 text-xs font-black uppercase tracking-widest transition-all outline-none whitespace-nowrap">
                    Video Performance
                </button>
                <button @click="activeTab = 'subscribers'" :class="activeTab === 'subscribers' ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400' : 'text-gray-400 dark:text-gray-500'" class="pb-2 text-xs font-black uppercase tracking-widest transition-all outline-none whitespace-nowrap">
                    Recent Subscribers
                </button>
            </div>

            <!-- Tab content: History -->
            <div x-show="activeTab === 'history'" x-transition class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-white/5 text-[10px] font-black uppercase tracking-wider text-gray-400 dark:text-gray-500">
                                <th class="p-6">Transaction Date</th>
                                <th class="p-6">Description</th>
                                <th class="p-6">Remark</th>
                                <th class="p-6">Status</th>
                                <th class="p-6 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/5 text-sm font-bold text-gray-700 dark:text-gray-300">
                            @forelse($userTransactions as $trx)
                                <tr>
                                    <td class="p-6 text-xs text-gray-400 dark:text-gray-500 font-bold">{{ showDateTime($trx->created_at, 'M d, Y') }}</td>
                                    <td class="p-6 font-black text-gray-900 dark:text-white">{{ __($trx->details) }}</td>
                                    <td class="p-6 text-xs uppercase tracking-wider text-gray-400 dark:text-gray-500 font-black font-semibold">{{ $trx->remark ? __($trx->remark) : 'Transfer' }}</td>
                                    <td class="p-6">
                                        <span class="px-3 py-1 bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-wider rounded-full border border-emerald-500/20">Success</span>
                                    </td>
                                    <td class="p-6 text-right font-black {{ $trx->trx_type == '+' ? 'text-emerald-500' : 'text-red-500' }}">
                                        {{ $trx->trx_type }}{{ showAmount($trx->amount) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-12 text-center text-gray-400 dark:text-gray-500 uppercase tracking-widest text-xs font-black">
                                        No transactions recorded yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab content: Video Performance -->
            <div x-show="activeTab === 'videos'" x-transition class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-white/5 text-[10px] font-black uppercase tracking-wider text-gray-400 dark:text-gray-500">
                                <th class="p-6">Video Details</th>
                                <th class="p-6">Views</th>
                                <th class="p-6 text-right">Ad Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/5 text-sm font-bold text-gray-700 dark:text-gray-300">
                            @forelse($earningVideos as $video)
                                <tr>
                                    <td class="p-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-16 h-10 rounded-lg bg-gray-100 overflow-hidden shrink-0">
                                                <img src="{{ getImage(getFilePath('thumbnail') . '/' . $video->thumbnail_path) }}" class="w-full h-full object-cover">
                                            </div>
                                            <span class="font-black text-gray-900 dark:text-white line-clamp-1">{{ $video->title }}</span>
                                        </div>
                                    </td>
                                    <td class="p-6 text-xs text-gray-400 dark:text-gray-500 font-black">{{ number_format($video->views_count) }}</td>
                                    <td class="p-6 text-right font-black text-emerald-500">{{ showAmount($video->earnings_sum_estimated_revenue) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-12 text-center text-gray-400 dark:text-gray-500 uppercase tracking-widest text-xs font-black">
                                        No video earnings recorded yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab content: Recent Subscribers -->
            <div x-show="activeTab === 'subscribers'" x-transition class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-white/5 text-[10px] font-black uppercase tracking-wider text-gray-400 dark:text-gray-500">
                                <th class="p-6">User</th>
                                <th class="p-6">Username</th>
                                <th class="p-6 text-right">Subscription Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/5 text-sm font-bold text-gray-700 dark:text-gray-300">
                            @forelse($recentSubscribers as $sub)
                                <tr>
                                    <td class="p-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-500 to-orange-600 text-white font-black text-[10px] flex items-center justify-center uppercase shrink-0 border border-gray-100 dark:border-white/5 overflow-hidden">
                                                @if($sub->image)
                                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $sub->image) }}" class="w-full h-full object-cover" onerror="this.remove();">
                                                @endif
                                                <span>{{ collect(explode(' ', $sub->fullname ?? $sub->username))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('') }}</span>
                                            </div>
                                            <span class="font-black text-gray-900 dark:text-white">{{ $sub->fullname }}</span>
                                        </div>
                                    </td>
                                    <td class="p-6 text-xs text-gray-400 dark:text-gray-500 font-black">{{ '@' . $sub->username }}</td>
                                    <td class="p-6 text-right text-xs text-gray-400 dark:text-gray-500 font-bold">
                                        {{ showDateTime($sub->pivot->created_at, 'M d, Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-12 text-center text-gray-400 dark:text-gray-500 uppercase tracking-widest text-xs font-black">
                                        No subscribers yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

            @if(false)
            <!-- Membership Tiers Section -->
            <div class="mb-16">
                <div class="flex items-center justify-between mb-8 px-2">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Membership Tiers</h2>
                        <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mt-1">Manage your fan subscription plans</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Tiers List -->
                    <div class="lg:col-span-2">
                        @if($memberships->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($memberships as $tier)
                                    <div class="bg-white dark:bg-[#181818] rounded-[2.5rem] p-8 border border-gray-100 dark:border-white/5 shadow-sm group hover:border-amber-500/30 transition-all duration-500">
                                        <div class="flex items-center justify-between mb-6">
                                            <h3 class="text-lg font-black text-gray-900 dark:text-white">{{ $tier->name }}</h3>
                                            <span class="text-amber-500 font-black text-xl">{{ showAmount($tier->price) }}</span>
                                        </div>
                                        <div class="space-y-3 mb-8">
                                            @foreach($tier->perks ?? [] as $perk)
                                                <div class="flex items-center space-x-3">
                                                    <span class="material-symbols-rounded text-emerald-500 text-lg">check_circle</span>
                                                    <span class="text-sm text-gray-600 dark:text-gray-400 font-bold">{{ $perk }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="flex items-center justify-between pt-6 border-t border-gray-100 dark:border-white/5">
                                            <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ $tier->subscribers()->count() }} Active Members</span>
                                            <div class="flex items-center gap-2">
                                                <button type="button" onclick="openEditModal({{ $tier->id }}, '{{ $tier->name }}', {{ $tier->price }}, '{{ implode(', ', $tier->perks ?? []) }}')" class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center">
                                                    <span class="material-symbols-rounded text-sm">edit</span>
                                                </button>
                                                <form action="{{ route('studio.memberships.destroy', $tier->id) }}" method="POST" id="delete-form-{{ $tier->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="confirmDelete({{ $tier->id }})" class="w-8 h-8 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center">
                                                        <span class="material-symbols-rounded text-sm">delete</span>
                                                    </button>
                                                </form>
                                                <div class="flex -space-x-2">
                                                    @foreach($tier->subscribers()->latest()->take(3)->get() as $sub)
                                                        <div class="w-6 h-6 rounded-full border-2 border-white dark:border-[#181818] bg-gray-200 overflow-hidden">
                                                            <img src="{{ getImage(getFilePath('userProfile') . '/' . $sub->user->image) }}" class="w-full h-full object-cover">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-white dark:bg-[#181818] rounded-[3rem] py-20 text-center border-2 border-dashed border-gray-100 dark:border-white/5">
                                <div class="w-20 h-20 rounded-[2rem] bg-gray-50 dark:bg-black/20 flex items-center justify-center text-gray-300 dark:text-gray-600 mx-auto mb-6">
                                    <span class="material-symbols-rounded text-4xl font-black">loyalty</span>
                                </div>
                                <p class="text-sm font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">No fan tiers created yet</p>
                            </div>
                        @endif
                    </div>

                    <!-- Creation Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-[#1A1A1A] rounded-[3rem] p-10 border border-gray-100 dark:border-white/5 shadow-2xl sticky top-24">
                            <h3 class="text-xl font-black text-gray-900 dark:text-white mb-8 tracking-tight">Forge New Tier</h3>
                            <form action="{{ route('studio.memberships.store') }}" method="POST" class="space-y-6">
                                @csrf
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 block px-2">Tier Identity</label>
                                    <input type="text" name="name" placeholder="e.g. Gold Member" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 dark:text-white placeholder-gray-300 dark:placeholder-gray-600 focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all" required>
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 block px-2">Monthly Fee (INR)</label>
                                    <div class="relative">
                                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 font-bold">₹</span>
                                        <input type="number" name="price" step="0.01" min="1" placeholder="9.99" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 rounded-2xl pl-10 pr-6 py-4 text-sm font-bold text-gray-900 dark:text-white placeholder-gray-300 dark:placeholder-gray-600 focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 block px-2">Perks (comma-separated)</label>
                                    <textarea name="perks" placeholder="Early access, Exclusive badge, Behind-the-scenes" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 dark:text-white placeholder-gray-300 dark:placeholder-gray-600 focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all min-h-[120px]" required></textarea>
                                </div>
                                <button type="submit" class="w-full py-5 bg-amber-500 hover:bg-amber-600 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-amber-500/20 transition-all active:scale-95 flex items-center justify-center gap-3 group">
                                    <span class="material-symbols-rounded text-lg group-hover:rotate-12 transition-transform">rocket_launch</span>
                                    <span>Deploy Tier</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Edit Tier Modal --}}
    <div id="editTierModal" class="fixed inset-0 z-[99999] flex items-center justify-center hidden" style="background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);">
        <div class="bg-white dark:bg-[#1A1A1A] rounded-[2rem] p-8 w-full max-w-md mx-4 shadow-2xl border border-gray-100 dark:border-white/5">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-black text-gray-900 dark:text-white tracking-tight">Edit Tier</h3>
                <button type="button" onclick="closeEditModal()" class="w-8 h-8 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                    <span class="material-symbols-rounded text-lg">close</span>
                </button>
            </div>
            <form id="editTierForm" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 block px-2">Tier Identity</label>
                    <input type="text" name="name" id="editTierName" placeholder="e.g. Gold Member" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 dark:text-white placeholder-gray-300 dark:placeholder-gray-600 focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all" required>
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 block px-2">Monthly Fee (INR)</label>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 font-bold">₹</span>
                        <input type="number" name="price" id="editTierPrice" step="0.01" min="1" placeholder="9.99" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 rounded-2xl pl-10 pr-6 py-4 text-sm font-bold text-gray-900 dark:text-white placeholder-gray-300 dark:placeholder-gray-600 focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all" required>
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 block px-2">Perks (comma-separated)</label>
                    <textarea name="perks" id="editTierPerks" placeholder="Early access, Exclusive badge, Behind-the-scenes" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 dark:text-white placeholder-gray-300 dark:placeholder-gray-600 focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all" required></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeEditModal()" class="flex-1 py-4 bg-gray-100 dark:bg-white/5 text-gray-400 dark:text-gray-500 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all active:scale-95">Cancel</button>
                    <button type="submit" class="flex-1 py-4 bg-amber-500 hover:bg-amber-600 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-amber-500/20 transition-all active:scale-95">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    @push('script')
    <script>
        function carouselProgress() {
            return {
                activeIndex: 0,
                initCarousel(el) {
                    this.updateProgress(el);
                },
                updateProgress(el) {
                    const scrollLeft = el.scrollLeft;
                    const cardWidth = el.scrollWidth / 3;
                    this.activeIndex = Math.round(scrollLeft / cardWidth);
                    if(this.activeIndex > 2) this.activeIndex = 2;
                }
            }
        }

        window.openPaidMonetizationSwal = function() {
            Swal.fire({
                title: 'Instant Monetization',
                html: `
                    <div class="text-center mb-2 mt-4">
                        <div class="w-16 h-16 rounded-[1.5rem] bg-amber-500/10 text-amber-500 flex items-center justify-center mx-auto mb-4">
                            <span class="material-symbols-rounded text-3xl">workspace_premium</span>
                        </div>
                        <p class="text-sm font-bold text-gray-500 dark:text-gray-400">Skip the milestone requirements and unlock monetization instantly for a one-time fee of <span class="text-amber-500">{{ showAmount(gs('monetization_amount'), 0) }}</span>.</p>
                    </div>
                    <div class="space-y-4 mb-2 mt-6 text-left">
                        <div class="flex items-center gap-4 bg-gray-50 dark:bg-white/5 p-4 rounded-2xl border border-gray-100 dark:border-white/5">
                            <span class="material-symbols-rounded text-emerald-500">check_circle</span>
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Earn from video ads immediately</span>
                        </div>
                        <div class="flex items-center gap-4 bg-gray-50 dark:bg-white/5 p-4 rounded-2xl border border-gray-100 dark:border-white/5">
                            <span class="material-symbols-rounded text-emerald-500">check_circle</span>
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Create fan membership tiers</span>
                        </div>
                        <div class="flex items-center gap-4 bg-gray-50 dark:bg-white/5 p-4 rounded-2xl border border-gray-100 dark:border-white/5">
                            <span class="material-symbols-rounded text-emerald-500">check_circle</span>
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Receive super chats</span>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Pay {{ showAmount(gs('monetization_amount'), 0) }} Now',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#6b7280',
                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                customClass: {
                    popup: 'rounded-[2rem]',
                    confirmButton: 'rounded-xl font-bold px-6 py-3 uppercase tracking-widest text-xs',
                    cancelButton: 'rounded-xl font-bold px-6 py-3 uppercase tracking-widest text-xs'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.initiatePayment();
                }
            });
        };

        window.initiatePayment = function() {
            Swal.fire({
                title: 'Processing Payment',
                text: 'Please wait...',
                allowOutsideClick: false,
                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                customClass: { popup: 'rounded-[2rem]' },
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch('{{ route('studio.monetization.buy') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    Swal.fire({
                        title: 'Error', 
                        text: data.error, 
                        icon: 'error',
                        background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                        customClass: { popup: 'rounded-[2rem]' }
                    });
                    return;
                }

                if (!data.key) {
                    Swal.fire({
                        title: 'Error', 
                        text: 'Razorpay key is missing.', 
                        icon: 'error',
                        background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                        customClass: { popup: 'rounded-[2rem]' }
                    });
                    return;
                }

                const options = {
                    "key": data.key,
                    "amount": data.amount,
                    "currency": data.currency,
                    "name": data.name,
                    "description": data.description,
                    "order_id": data.order_id,
                    "handler": function (response) {
                        // Verify payment
                        fetch('{{ route('studio.monetization.verify-payment') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_signature: response.razorpay_signature
                            })
                        })
                        .then(res => res.json())
                        .then(verification => {
                            if(verification.success) {
                                Swal.fire({
                                    title: 'Success', 
                                    text: verification.message, 
                                    icon: 'success',
                                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                                    customClass: { popup: 'rounded-[2rem]' }
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error', 
                                    text: verification.error || 'Verification failed', 
                                    icon: 'error',
                                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                                    customClass: { popup: 'rounded-[2rem]' }
                                });
                            }
                        });
                    },
                    "theme": {
                        "color": "#f59e0b" // amber-500
                    }
                };
                
                const rzp = new Razorpay(options);
                rzp.open();
                Swal.close();
            })
            .catch(err => {
                Swal.fire({
                    title: 'Error', 
                    text: 'An unexpected error occurred.', 
                    icon: 'error',
                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                    customClass: { popup: 'rounded-[2rem]' }
                });
            });
        };

        function openEditModal(id, name, price, perks) {
            document.getElementById('editTierForm').action = '{{ route('studio.memberships.store') }}'.replace('/monetization/membership', '/monetization/membership/' + id);
            document.getElementById('editTierName').value = name;
            document.getElementById('editTierPrice').value = price;
            document.getElementById('editTierPerks').value = perks;
            document.getElementById('editTierModal').classList.remove('hidden');
        }
        function closeEditModal() {
            document.getElementById('editTierModal').classList.add('hidden');
        }
        document.getElementById('editTierModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
        });
        function confirmDelete(id) {
            Swal.fire({
                title: 'Delete this tier?',
                text: 'This action cannot be undone. Members will lose access to this tier.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                customClass: {
                    popup: 'rounded-[2rem]',
                    confirmButton: 'rounded-xl font-bold px-6 py-2',
                    cancelButton: 'rounded-xl font-bold px-6 py-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
