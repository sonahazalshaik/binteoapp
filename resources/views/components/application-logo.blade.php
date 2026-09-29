<div {{ $attributes->merge(['class' => 'flex flex-col items-center gap-2 text-center group cursor-pointer']) }}>
    <div class="relative">
        <!-- Glow Layer -->
        <div class="absolute -inset-2 bg-gradient-to-r from-cyan-500 to-purple-600 rounded-full blur-xl opacity-40 group-hover:opacity-80 transition-opacity duration-500"></div>
        
        <div class="relative w-16 h-16 md:w-20 md:h-20 rounded-full bg-gradient-to-tr from-cyan-500 to-purple-600 shadow-2xl flex items-center justify-center overflow-hidden p-1 group-hover:rotate-[360deg] transition-transform duration-1000">
            <div class="w-full h-full rounded-full bg-[#0A0D14] flex items-center justify-center overflow-hidden border border-white/10">
                <img src="{{ siteLogo() }}" class="w-full h-full object-fill p-2.5 contrast-125 brightness-110" alt="{{ gs('site_name') }}">
            </div>
        </div>
    </div>
    
    <div class="flex flex-col items-center">
        <h2 class="text-2xl md:text-3xl font-black tracking-tighter leading-none">
            <span class="text-white">{{ substr(gs('site_name'), 0, strpos(gs('site_name'), ' ')) }}</span><span class="bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">{{ substr(gs('site_name'), strpos(gs('site_name'), ' ')) }}</span>
        </h2>
        <p class="text-[9px] md:text-[10px] font-black text-cyan-500/50 uppercase tracking-[0.4em] mt-1 leading-none">Cyber Stream Engine</p>
    </div>
</div>

