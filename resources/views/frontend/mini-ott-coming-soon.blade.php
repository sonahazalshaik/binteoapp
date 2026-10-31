<x-app-layout>
    {{-- Native Android M3 Coming Soon - Mini OTT — follows channels/show card UI --}}
    <div class="bg-[#F7F7F7] dark:bg-[#0F0F0F] min-h-[calc(100dvh-56px)] lg:min-h-[calc(100vh-80px)] flex flex-col transition-colors duration-500 pb-24 lg:pb-6">
        
        {{-- M3 Top App Bar — removed in desktop view only as requested --}}
        <div class="w-full max-w-[560px] mx-auto px-4 pt-3 md:pt-4 lg:hidden">
            <div class="flex items-center gap-2.5">
                <a href="{{ route('home') }}" class="w-9 h-9 rounded-full bg-white dark:bg-white/5 border border-slate-100 dark:border-white/10 flex items-center justify-center text-slate-700 dark:text-white active:scale-95 transition-transform shadow-sm">
                    <span class="material-symbols-rounded text-[18px]">arrow_back</span>
                </a>
                <div class="flex-1">
                    <p class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-[0.18em]">Premium Experience</p>
                    <h2 class="text-[14px] font-black text-slate-900 dark:text-white tracking-tight leading-none">Mini OTT</h2>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-orange-500 text-white text-[9px] font-black uppercase tracking-widest shadow-sm">Soon</span>
            </div>
        </div>

        {{-- Center Surface — follows channels/show premium card UI --}}
        <div class="flex-1 flex items-center justify-center px-4 py-6 md:py-8">
            <div class="w-full max-w-[520px]">
                <div class="relative overflow-hidden bg-white dark:bg-[#1E1E1E] rounded-[32px] border border-slate-100 dark:border-white/[0.06] shadow-[0_8px_32px_rgba(0,0,0,0.08)] dark:shadow-[0_12px_40px_rgba(0,0,0,0.3)] text-center overflow-hidden">
                    <div class="h-1 w-full bg-gradient-to-r from-[#ff571a] via-[#ff8a1a] to-[#ff571a] opacity-90"></div>
                    <div class="absolute inset-x-0 top-0 h-[100px] bg-gradient-to-b from-orange-500/[0.06] to-transparent pointer-events-none"></div>
                    <div class="relative z-10 p-6 md:p-8">
                        <div class="relative w-[72px] h-[72px] mx-auto mb-4">
                            <div class="absolute inset-0 bg-[#ff571a]/20 rounded-[22px] blur-[14px]"></div>
                            <div class="relative w-full h-full rounded-[22px] bg-orange-50 dark:bg-[#ff571a]/15 border border-orange-100 dark:border-[#ff571a]/20 flex items-center justify-center">
                                <div class="w-10 h-10 rounded-[14px] bg-[#ff571a] flex items-center justify-center shadow-md">
                                    <span class="material-symbols-rounded text-[22px] text-white material-symbols-filled">movie</span>
                                </div>
                                <div class="absolute -top-1 -right-1 w-6 h-6 rounded-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 flex items-center justify-center border-2 border-white dark:border-[#1E1E1E]">
                                    <span class="material-symbols-rounded text-[12px]">hourglass_empty</span>
                                </div>
                            </div>
                        </div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 mb-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-[9px] font-black uppercase tracking-[0.18em]">Coming Soon</span>
                        </div>
                        <h1 class="text-[20px] font-black text-slate-900 dark:text-white tracking-tight leading-none">Mini OTT <span class="text-[#ff571a]">Coming Soon</span></h1>
                        <p class="text-[11px] md:text-[12px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-[0.18em] mt-2">Premium • Originals • Ad-free</p>
                        <p class="text-[12px] font-medium text-slate-500 dark:text-white/50 leading-relaxed mt-1.5 max-w-[380px] mx-auto">This channel’s premium catalogue will be available when Mini OTT launches. Stay tuned — curated originals & ad-free playback are on the way.</p>
                        <div class="flex items-center justify-center gap-1.5 mt-4 flex-wrap">
                            <span class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-[10px] font-bold text-slate-600 dark:text-white/60">Exclusive</span>
                            <span class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-[10px] font-bold text-slate-600 dark:text-white/60">4K Ready</span>
                            <span class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-[10px] font-bold text-slate-600 dark:text-white/60">Ad-free</span>
                        </div>
                        <div class="mt-5 flex flex-col sm:flex-row gap-2 justify-center">
                            <a href="{{ route('home') }}" class="h-10 px-6 rounded-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-black text-[11px] uppercase tracking-widest flex items-center justify-center gap-1.5 active:scale-[0.98] transition-all">
                                <span class="material-symbols-rounded text-[16px]">home</span> Back to Home
                            </a>
                            <a href="{{ route('trending') }}" class="h-10 px-6 rounded-full bg-orange-50 dark:bg-[#ff571a]/15 border border-orange-100 dark:border-[#ff571a]/20 text-[#ff571a] font-black text-[11px] uppercase tracking-widest flex items-center justify-center gap-1.5 active:scale-[0.98] transition-all">
                                <span class="material-symbols-rounded text-[16px]">explore</span> Explore Trending
                            </a>
                        </div>
                    </div>
                </div>

                <p class="text-center text-[10px] font-bold text-slate-400 dark:text-white/25 uppercase tracking-[0.16em] mt-4 px-6">
                    Tip: pull down to refresh on launch day
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
