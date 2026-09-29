<x-app-layout>
    <div class="py-10 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500">
        <div class="max-w-[1200px] mx-auto px-6">
            <!-- Hero Header -->
            <div class="flex flex-col items-center text-center mb-16">
                <div class="relative mb-8">
                    <div class="w-24 h-24 rounded-[2.5rem] bg-amber-500 text-white flex items-center justify-center shadow-2xl shadow-amber-500/40 relative z-10">
                        <span class="material-symbols-rounded text-5xl">monetization_on</span>
                    </div>
                    <div class="absolute inset-0 bg-amber-500 blur-[40px] opacity-20 scale-150"></div>
                </div>
                <h1 class="text-5xl font-black text-gray-900 dark:text-white tracking-tighter mb-4">Partner Program</h1>
                <p class="text-[11px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.3em] max-w-xl mx-auto leading-relaxed">
                    Transform your passion into profit. Unlock premium features and revenue streams.
                </p>
            </div>

            <!-- Eligibility Roadmaps -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16">
                <!-- Subscriber Goal -->
                <div class="bg-white dark:bg-[#181818] p-10 rounded-[3.5rem] border border-gray-100 dark:border-white/5 shadow-sm relative overflow-hidden group">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-10">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center">
                                    <span class="material-symbols-rounded text-amber-500">groups</span>
                                </div>
                                <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">Community Reach</h3>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-black text-gray-900 dark:text-white">{{ number_format($totalSubscriber) }}</p>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">/ {{ number_format($minSubs) }} GOAL</p>
                            </div>
                        </div>
                        
                        <div class="relative w-full h-4 bg-gray-50 dark:bg-white/2 rounded-full overflow-hidden mb-6 border border-gray-100 dark:border-white/5">
                            <div class="absolute inset-y-0 left-0 bg-amber-500 rounded-full transition-all duration-1000 shadow-[0_0_20px_rgba(245,158,11,0.4)]" 
                                 style="width: {{ min($subscriberInPercent, 100) }}%">
                                <div class="w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent animate-shimmer"></div>
                            </div>
                        </div>
                        
                        <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.15em] leading-relaxed">
                            You need <span class="text-amber-500 font-black">{{ max(0, $minSubs - $totalSubscriber) }} more</span> subscribers to qualify for fan funding.
                        </p>
                    </div>
                    <span class="material-symbols-rounded absolute -right-8 -bottom-8 text-[160px] text-amber-500/[0.03] rotate-12 pointer-events-none">groups</span>
                </div>

                <!-- View Goal -->
                <div class="bg-white dark:bg-[#181818] p-10 rounded-[3.5rem] border border-gray-100 dark:border-white/5 shadow-sm relative overflow-hidden group">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-10">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center">
                                    <span class="material-symbols-rounded text-blue-500">visibility</span>
                                </div>
                                <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">Content Impact</h3>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-black text-gray-900 dark:text-white">{{ number_format($totalViews) }}</p>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">/ {{ number_format($minViews) }} GOAL</p>
                            </div>
                        </div>
                        
                        <div class="relative w-full h-4 bg-gray-50 dark:bg-white/2 rounded-full overflow-hidden mb-6 border border-gray-100 dark:border-white/5">
                            <div class="absolute inset-y-0 left-0 bg-blue-500 rounded-full transition-all duration-1000 shadow-[0_0_20px_rgba(59,130,246,0.4)]" 
                                 style="width: {{ min($viewInPercent, 100) }}%">
                                <div class="w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent animate-shimmer"></div>
                            </div>
                        </div>
                        
                        <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.15em] leading-relaxed">
                            You need <span class="text-blue-500 font-black">{{ max(0, $minViews - $totalViews) }} more</span> views total across all videos.
                        </p>
                    </div>
                    <span class="material-symbols-rounded absolute -left-8 -bottom-8 text-[160px] text-blue-500/[0.03] -rotate-12 pointer-events-none">visibility</span>
                </div>
            </div>

            <!-- Application Action Card -->
            <div class="bg-white dark:bg-[#181818] rounded-[4rem] p-12 sm:p-20 border border-gray-100 dark:border-white/5 shadow-2xl relative overflow-hidden text-center mb-20">
                <!-- Background Glow -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-red-500/5 rounded-full blur-[120px]"></div>

                <div class="relative z-10 max-w-2xl mx-auto">
                    @if($user->monetization_status == App\Constants\Status::MONETIZATION_APPROVED)
                        <div class="w-20 s-20 rounded-[2rem] bg-emerald-500 text-white flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-emerald-500/30">
                            <span class="material-symbols-rounded text-4xl">verified</span>
                        </div>
                        <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-4">You're a Platform Partner!</h2>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest leading-loose mb-12">Congratulations! Your account is active for all revenue tools. Manage your existing content and advertising preferences in the Studio.</p>
                        <a href="{{ route('studio.videos') }}" class="inline-flex items-center gap-3 px-12 py-5 bg-gray-900 dark:bg-white text-white dark:text-black rounded-[2rem] font-black text-xs uppercase tracking-widest shadow-2xl hover:scale-105 transition-all active:scale-95">
                            Enter Video Studio
                            <span class="material-symbols-rounded">arrow_right_alt</span>
                        </a>
                    @elseif($user->monetization_status == App\Constants\Status::MONETIZATION_APPLYING)
                        <div class="w-20 s-20 rounded-[2rem] bg-amber-500 text-white flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-amber-500/30 animate-pulse">
                            <span class="material-symbols-rounded text-4xl">hourglass_top</span>
                        </div>
                        <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-4">Application Pending Review</h2>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest leading-loose mb-12">Our quality control specialists are currently auditing your content for compliance. You will receive an email verification within 48-72 hours.</p>
                        <button disabled class="px-12 py-5 bg-gray-50 dark:bg-white/2 text-gray-400 rounded-[2rem] font-black text-xs uppercase tracking-widest border border-gray-100 dark:border-white/5 cursor-not-allowed">Application In Progress</button>
                    @else
                        <h2 class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter mb-4">Begin Your Journey</h2>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest leading-loose mb-12 max-w-lg mx-auto">Once you've hit the subscriber and view milestones, we'll review your channel for professional partnership eligibility.</p>
                        
                        <form action="{{ route('user.monetization.apply') }}" method="POST">
                            @csrf
                            @if($subscriberInPercent >= 100 && $viewInPercent >= 100)
                                <button type="submit" class="px-16 py-6 bg-red-600 text-white rounded-[2.5rem] font-black text-sm uppercase tracking-[0.4em] shadow-2xl shadow-red-500/40 hover:bg-red-700 hover:-translate-y-2 transition-all active:scale-95">Apply Now</button>
                            @else
                                <div class="inline-flex items-center gap-3 px-10 py-5 bg-gray-50 dark:bg-white/2 text-gray-300 dark:text-gray-600 rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] border border-gray-100 dark:border-white/5 cursor-not-allowed">
                                    <span class="material-symbols-rounded text-lg">lock</span>
                                    Finish Goals to Unlock
                                </div>
                            @endif
                        </form>
                    @endif
                </div>
            </div>

            <!-- Benefit Perks -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 pb-20">
                <div class="text-center group">
                    <div class="w-16 h-16 rounded-2xl bg-amber-500/5 flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-rounded text-amber-500 text-3xl">ads_click</span>
                    </div>
                    <h4 class="font-black text-gray-900 dark:text-white mb-2 uppercase tracking-widest text-xs">Ad Revenue</h4>
                    <p class="text-[10px] font-bold text-gray-400 leading-relaxed uppercase tracking-widest">Share in revenue from ads on your videos.</p>
                </div>
                <div class="text-center group">
                    <div class="w-16 h-16 rounded-2xl bg-blue-500/5 flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-rounded text-blue-500 text-3xl">stars</span>
                    </div>
                    <h4 class="font-black text-gray-900 dark:text-white mb-2 uppercase tracking-widest text-xs">Fan Funding</h4>
                    <p class="text-[10px] font-bold text-gray-400 leading-relaxed uppercase tracking-widest">Unlock memberships, supers, and stickers.</p>
                </div>
                <div class="text-center group">
                    <div class="w-16 h-16 rounded-2xl bg-purple-500/5 flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-rounded text-purple-500 text-3xl">shopping_bag</span>
                    </div>
                    <h4 class="font-black text-gray-900 dark:text-white mb-2 uppercase tracking-widest text-xs">Shop & Merch</h4>
                    <p class="text-[10px] font-bold text-gray-400 leading-relaxed uppercase tracking-widest">Sell products directly on your video pages.</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        .animate-shimmer {
            animation: shimmer 2s infinite linear;
        }
    </style>
</x-app-layout>
