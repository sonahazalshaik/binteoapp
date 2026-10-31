@php $miniOttSoonGlobal = gs('mini_ott_status') !== null && (int) gs('mini_ott_status') === 0; @endphp
@if($miniOttSoonGlobal)
<div id="mini-ott-comingsoon-modal" class="fixed inset-0 z-[100000] hidden" aria-hidden="true" style="z-index: 100000;">
    {{-- Backdrop --}}
    <div id="mini-ott-backdrop" class="absolute inset-0 bg-black/60 backdrop-blur-md opacity-0 transition-opacity duration-300"></div>
    {{-- Container --}}
    <div class="absolute inset-0 flex items-end lg:items-center justify-center p-0 lg:p-4">
        {{-- Sheet / Dialog --}}
        <div id="mini-ott-sheet" class="w-full lg:max-w-[520px] bg-white dark:bg-[#1E1E1E] rounded-t-[32px] lg:rounded-[32px] border border-slate-100 dark:border-white/[0.06] shadow-[0_-10px_40px_rgba(0,0,0,0.15)] lg:shadow-[0_12px_40px_rgba(0,0,0,0.4)] translate-y-full lg:translate-y-4 lg:scale-95 opacity-0 transition-all duration-300 ease-out max-h-[85vh] overflow-hidden flex flex-col">
            {{-- Drag handle --}}
            <div class="flex justify-center pt-3 pb-1 shrink-0">
                <div class="w-9 h-1 rounded-full bg-slate-200 dark:bg-white/15"></div>
            </div>
            {{-- Top gradient --}}
            <div class="absolute inset-x-0 top-0 h-[120px] bg-gradient-to-b from-orange-500/[0.07] to-transparent pointer-events-none rounded-t-[32px]"></div>
            {{-- Close --}}
            <button onclick="window.closeMiniOttComingSoon()" class="absolute top-4 right-4 w-9 h-9 rounded-full bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-600 dark:text-white/70 hover:bg-slate-200 dark:hover:bg-white/10 active:scale-95 transition-all z-10">
                <span class="material-symbols-rounded text-[18px]">close</span>
            </button>
            {{-- Content --}}
            <div class="overflow-y-auto px-6 md:px-7 pt-2 pb-6">
                <div class="flex justify-center mb-4">
                    <div class="relative">
                        <div class="absolute inset-0 bg-[#ff571a]/20 rounded-[22px] blur-[14px]"></div>
                        <div class="relative w-[72px] h-[72px] rounded-[22px] bg-orange-50 dark:bg-[#ff571a]/15 border border-orange-100 dark:border-[#ff571a]/20 flex items-center justify-center">
                            <div class="w-10 h-10 rounded-[14px] bg-[#ff571a] flex items-center justify-center shadow-md">
                                <span class="material-symbols-rounded text-[22px] text-white material-symbols-filled">movie</span>
                            </div>
                            <div class="absolute -top-1 -right-1 w-6 h-6 rounded-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 flex items-center justify-center border-2 border-white dark:border-[#1E1E1E] shadow-md">
                                <span class="material-symbols-rounded text-[12px]">hourglass_empty</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 mb-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-[9px] font-black uppercase tracking-[0.18em]">Coming Soon</span>
                    </div>
                    <h3 class="text-[20px] font-black text-slate-900 dark:text-white tracking-tight leading-none">Mini OTT <span class="text-[#ff571a]">Coming Soon</span></h3>
                    <p class="text-[11px] font-bold text-slate-400 dark:text-white/30 uppercase tracking-[0.14em] mt-1.5">Premium • Originals • Ad-free</p>
                    <p class="text-[12px] font-medium text-slate-600 dark:text-white/60 leading-relaxed mt-3">We’re crafting a native streaming experience — exclusive originals, curated collections and cinema-grade playback. Hang tight.</p>
                </div>
                <div class="flex items-center justify-center gap-1.5 mt-4 flex-wrap">
                    <span class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-[10px] font-bold text-slate-600 dark:text-white/60">Exclusive</span>
                    <span class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-[10px] font-bold text-slate-600 dark:text-white/60">4K Ready</span>
                    <span class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-[10px] font-bold text-slate-600 dark:text-white/60">Ad-free</span>
                </div>
                <div class="mt-5 grid grid-cols-1 gap-2">
                    <button onclick="window.closeMiniOttComingSoon(); window.location.href='{{ route('home') }}'" class="w-full h-[48px] rounded-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-black text-[11px] uppercase tracking-widest flex items-center justify-center gap-2 active:scale-[0.98] transition-all">
                        <span class="material-symbols-rounded text-[16px]">home</span> Back to Home
                    </button>
                    <button onclick="window.closeMiniOttComingSoon(); window.location.href='{{ route('trending') }}'" class="w-full h-[44px] rounded-full bg-orange-50 dark:bg-[#ff571a]/15 border border-orange-100 dark:border-[#ff571a]/20 text-[#ff571a] font-black text-[11px] uppercase tracking-widest flex items-center justify-center gap-1 active:scale-[0.98] transition-all">
                        <span class="material-symbols-rounded text-[14px]">explore</span> Explore Trending
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
window.showMiniOttComingSoon = function() {
    if(navigator.vibrate) try{navigator.vibrate(30)}catch(e){}
    const modal = document.getElementById('mini-ott-comingsoon-modal');
    const backdrop = document.getElementById('mini-ott-backdrop');
    const sheet = document.getElementById('mini-ott-sheet');
    if(!modal || !backdrop || !sheet) return;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(()=>{
        backdrop.classList.remove('opacity-0');
        backdrop.classList.add('opacity-100');
        sheet.classList.remove('translate-y-full','lg:translate-y-4','lg:scale-95','opacity-0');
        sheet.classList.add('translate-y-0','lg:translate-y-0','lg:scale-100','opacity-100');
    });
};
window.closeMiniOttComingSoon = function() {
    const modal = document.getElementById('mini-ott-comingsoon-modal');
    const backdrop = document.getElementById('mini-ott-backdrop');
    const sheet = document.getElementById('mini-ott-sheet');
    if(!modal || !backdrop || !sheet) return;
    backdrop.classList.remove('opacity-100');
    backdrop.classList.add('opacity-0');
    sheet.classList.remove('translate-y-0','lg:translate-y-0','lg:scale-100','opacity-100');
    sheet.classList.add('translate-y-full','lg:translate-y-4','lg:scale-95','opacity-0');
    setTimeout(()=>{
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    },300);
};
document.addEventListener('DOMContentLoaded', ()=>{
    const m = document.getElementById('mini-ott-comingsoon-modal');
    if(!m) return;
    m.addEventListener('click', (e)=>{
        if(e.target.id === 'mini-ott-comingsoon-modal' || e.target.id === 'mini-ott-backdrop') window.closeMiniOttComingSoon();
    });
    document.addEventListener('keydown', (e)=>{
        if(e.key === 'Escape') window.closeMiniOttComingSoon();
    });
});
</script>
@endif
