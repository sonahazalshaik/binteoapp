<x-app-layout>
    <div class="py-20 bg-white dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500 overflow-hidden relative">
        <!-- Abstract Glow Background -->
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-red-600/5 blur-[120px] rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-[#FF416C]/5 blur-[120px] rounded-full translate-y-1/2 -translate-x-1/2"></div>

        <div class="max-w-xl mx-auto px-6 relative z-10 text-center">
            <!-- Header Section -->
            <div class="flex flex-col items-center mb-16">
                <div class="w-20 h-20 bg-gradient-to-tr from-[#FF4B2B] to-[#FF416C] rounded-[2rem] flex items-center justify-center shadow-2xl shadow-red-200 dark:shadow-none mb-10 transform -rotate-6">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 18v1m9-9h1m-18 0h1m3.343-5.657l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707.707M12 7a5 5 0 110 10 5 5 0 010-10z"></path>
                    </svg>
                </div>
                <h1 class="text-5xl font-black text-[#2D2D2D] dark:text-white mb-6 tracking-tight leading-none">Binteo <span class="text-red-600">Premium</span></h1>
                <p class="text-gray-400 dark:text-gray-500 font-bold text-base max-w-sm mx-auto leading-relaxed">The ultimate streaming experience. No ads, just pure entertainment.</p>
            </div>

            <!-- Billing Toggle (Android Pill style) -->
            <div x-data="{ billing: 'monthly' }" class="flex items-center justify-center space-x-6 mb-16">
                <span :class="billing === 'monthly' ? 'text-gray-900 dark:text-white font-black' : 'text-gray-400 dark:text-gray-600'" class="text-sm transition-all duration-300">Monthly</span>
                <button @click="billing = billing === 'monthly' ? 'yearly' : 'monthly'" 
                        class="w-16 h-9 bg-gray-100 dark:bg-white/5 rounded-full relative p-1.5 transition-all border border-gray-100 dark:border-white/5 active:scale-95 shadow-inner">
                    <div :class="billing === 'yearly' ? 'translate-x-[1.75rem] bg-red-600' : 'translate-x-0 bg-gray-400'" 
                         class="w-6 h-6 rounded-full shadow-lg transition-all duration-500 transform"></div>
                </button>
                <div class="flex items-center space-x-3">
                    <span :class="billing === 'yearly' ? 'text-gray-900 dark:text-white font-black' : 'text-gray-400 dark:text-gray-600'" class="text-sm transition-all duration-300">Yearly</span>
                    <span class="bg-red-50 dark:bg-red-500/10 text-[10px] font-black text-red-600 px-3 py-1 rounded-xl uppercase tracking-widest animate-pulse">Save 20%</span>
                </div>
            </div>

            <!-- Pricing Card (Premium 3D feel) -->
            <div class="bg-white dark:bg-[#1A1A1A] border border-gray-100 dark:border-white/5 rounded-[3.5rem] p-12 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.1)] dark:shadow-none relative overflow-hidden group hover:scale-[1.02] transition-all duration-500">
                <div class="absolute top-0 right-0 w-32 h-32 bg-red-600/5 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-700"></div>
                
                <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-3">Premium Pass</h3>
                <p class="text-gray-400 text-[11px] font-black uppercase tracking-[0.3em] mb-10">All formats & exclusive series</p>
                
                <div class="mb-12">
                    <div class="flex items-baseline justify-center space-x-2">
                        <span class="text-5xl font-black text-gray-900 dark:text-white tracking-tighter">₹249</span>
                        <span class="text-gray-400 dark:text-gray-500 font-bold text-lg">/month</span>
                    </div>
                    <p class="text-green-500 text-xs font-black mt-4 uppercase tracking-widest">7 Days Free Trial</p>
                </div>

                <a href="#" class="block w-full bg-[#2D2D2D] dark:bg-white text-white dark:text-black py-6 rounded-[2rem] font-black text-sm uppercase tracking-widest shadow-2xl hover:bg-red-600 hover:text-white transition-all duration-300 active:scale-95 mb-12">
                    Start My Trial
                </a>

                <div class="text-left space-y-6">
                    @foreach(['4K (Ultra HD) Streaming', 'Offline Viewing', 'No Advertisements', 'Exclusive Binteo Originals', 'High Fidelity Audio'] as $feature)
                    <div class="flex items-center space-x-4">
                        <div class="w-6 h-6 rounded-full bg-green-50 dark:bg-green-500/10 flex items-center justify-center text-green-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-[13px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest">{{ $feature }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <p class="mt-12 text-gray-400 dark:text-gray-600 text-[10px] font-bold uppercase tracking-[0.4em]">Cancel anytime • Secure checkout</p>
        </div>
    </div>
</x-app-layout>
