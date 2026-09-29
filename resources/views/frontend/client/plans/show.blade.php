<x-app-layout>
    <div class="py-10 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen">
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="bg-white dark:bg-[#1A1A1A] rounded-[3rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden flex flex-col lg:flex-row shadow-2xl">
                <!-- Plan Left -->
                <div class="lg:w-2/5 p-12 lg:p-16 flex flex-col justify-between bg-gradient-to-br from-red-600/5 to-transparent border-r border-gray-50 dark:border-white/5">
                    <div>
                        <a href="{{ route('user.plans.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-12 hover:text-red-600 transition-colors">
                            <span class="material-symbols-rounded text-lg">arrow_back</span>
                            Back to Plans
                        </a>
                        <div class="w-20 h-20 rounded-3xl bg-red-600 text-white flex items-center justify-center mb-8 shadow-xl shadow-red-600/20">
                            <span class="material-symbols-rounded text-4xl">workspace_premium</span>
                        </div>
                        <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter mb-4">{{ $plan->name }}</h1>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-relaxed mb-8">{{ $plan->alias ?? 'Elevate your channel experience with premium creator content.' }}</p>
                    </div>

                    <div class="pt-10 border-t border-gray-100 dark:border-white/5">
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Subscription Total</div>
                        <div class="flex items-end gap-1">
                            <span class="text-5xl font-black text-gray-900 dark:text-white tracking-tight">{{ showAmount($plan->price) }}</span>
                            <span class="text-sm font-black text-gray-400 uppercase tracking-widest mb-2">/ Month</span>
                        </div>
                    </div>
                </div>

                <!-- Plan Right -->
                <div class="lg:w-3/5 p-12 lg:p-16 bg-[#FDFDFD] dark:bg-transparent">
                    <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-[0.2em] mb-12 border-b pb-6 dark:border-white/5">Membership Inclusions</h2>
                    
                    <div class="space-y-10 mb-16">
                        @foreach([
                            ['video_library', 'Premium Video Library', 'Gain unlimited access to all high-quality content uploaded under this tier.'],
                            ['playlist_add_check', 'Curated Playlists', 'Access expert-curated collection of videos tailored for this membership.'],
                            ['high_quality', 'Max Video Quality', 'Watch content in up to 4K resolution with priority buffering.'],
                            ['support_agent', 'Direct Creator Line', 'Priority response for your inquiries within 24 business hours.']
                        ] as $perk)
                        <div class="flex gap-6">
                            <div class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 shadow-sm border border-gray-100 dark:border-white/10 flex items-center justify-center flex-shrink-0 text-red-600">
                                <span class="material-symbols-rounded text-2xl">{{ $perk[0] }}</span>
                            </div>
                            <div>
                                <h4 class="font-black text-gray-900 dark:text-white text-xs uppercase tracking-widest mb-1">{{ $perk[1] }}</h4>
                                <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-relaxed">{{ $perk[2] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <form action="#" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-6 bg-red-600 text-white rounded-[2rem] font-black text-sm uppercase tracking-[0.2em] shadow-2xl shadow-red-600/30 hover:bg-red-700 hover:-translate-y-1 transition-all active:scale-95">Subscribe Now</button>
                    </form>
                    <p class="text-center text-[9px] font-black text-gray-400 uppercase tracking-widest mt-8">Cancel anytime. Safe & secure payment processing.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
