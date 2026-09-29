<x-app-layout>
    <div class="py-12 px-4 sm:px-6 relative overflow-hidden bg-white dark:bg-[#0F0F0F] text-slate-900 dark:text-white">
        {{-- Soft Glows --}}
        <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] rounded-full bg-orange-100/30 blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-[10%] -right-[10%] w-[40%] h-[40%] rounded-full bg-blue-50/30 blur-[100px] pointer-events-none"></div>

        <div class="max-w-4xl mx-auto relative z-10">
            <div class="relative py-4 md:py-6">
                {{-- Iridescent Floating Orbs - Optimized for Mobile --}}
                <div class="absolute -top-10 -left-10 w-32 h-32 bg-gradient-to-br from-orange-400/20 to-pink-500/20 rounded-full blur-[40px] animate-orb-float"></div>
                <div class="absolute top-1/2 -right-16 w-40 h-40 bg-gradient-to-tl from-blue-400/20 to-purple-500/20 rounded-full blur-[50px] animate-orb-float-slow hidden sm:block"></div>
                <div class="absolute -bottom-10 left-1/2 -translate-x-1/2 w-48 h-48 bg-gradient-to-tr from-emerald-400/10 to-teal-500/10 rounded-full blur-[60px] animate-orb-pulse"></div>

                {{-- Main Content Shell --}}
                <div class="relative group">
                    <div class="absolute -inset-0.5 md:-inset-1 bg-gradient-to-r from-orange-500 via-pink-500 to-blue-600 rounded-[2rem] md:rounded-[2.5rem] opacity-20 group-hover:opacity-40 transition duration-1000 blur-md md:blur-lg"></div>
                    <div class="relative bg-white/60 dark:bg-slate-900/40 backdrop-blur-3xl border border-white/50 dark:border-white/10 rounded-[2rem] md:rounded-[2.5rem] overflow-hidden shadow-2xl">
                        {{-- Content Header Accent --}}
                        <div class="h-1.5 md:h-2 w-full bg-gradient-to-r from-orange-500 via-pink-500 to-blue-600"></div>
                        
                        <div class="p-6 md:p-10 lg:p-12">
                            <div class="flex flex-col items-center justify-center text-center gap-3 md:gap-4 mb-8 md:mb-12 pb-6 md:pb-8 border-b border-slate-100 dark:border-white/5">
                                <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-orange-500 to-pink-500 rounded-2xl flex items-center justify-center shrink-0 shadow-lg shadow-orange-500/20">
                                    <span class="material-symbols-rounded text-white md:text-3xl">policy</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-50 border border-orange-100 text-orange-600 mb-2 md:mb-3">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-ping"></span>
                                        <span class="text-[7px] md:text-[8px] font-black uppercase tracking-[0.3em]">Policy Update</span>
                                    </div>
                                    <h1 class="text-xl md:text-3xl font-black tracking-tight uppercase">
                                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 via-pink-500 to-blue-600">{{ __($pageTitle) }}</span>
                                    </h1>
                                </div>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none 
                                        prose-headings:text-slate-900 dark:prose-headings:text-white prose-headings:font-black prose-headings:tracking-tight prose-headings:uppercase prose-headings:text-[10px] md:prose-headings:text-xs prose-headings:tracking-[0.3em] prose-headings:text-orange-500
                                        prose-h3:text-lg md:prose-h3:text-xl prose-h3:mt-8
                                        prose-p:text-slate-600 dark:prose-p:text-slate-400 prose-p:font-medium prose-p:leading-relaxed prose-p:mb-6 prose-p:text-sm
                                        prose-strong:text-slate-900 dark:prose-strong:text-white prose-strong:font-black
                                        prose-ul:list-none prose-ul:pl-0 prose-li:relative prose-li:pl-6 prose-li:mb-2 prose-li:text-sm prose-li:font-medium prose-li:text-slate-500 dark:prose-li:text-slate-400
                                        prose-li:before:content-[''] prose-li:before:absolute prose-li:before:left-0 prose-li:before:top-2 prose-li:before:w-1.5 prose-li:before:h-1.5 prose-li:before:bg-orange-500 prose-li:before:rounded-full">
                                
                                @php echo $policy->data_values->content @endphp

                            </div>
                        </div>

                        {{-- Footer Badge --}}
                        <div class="bg-slate-50/50 dark:bg-white/5 border-t border-slate-100 dark:border-white/10 p-6 flex flex-col sm:flex-row items-center justify-between gap-6">
                            <span class="text-[9px] md:text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 text-center sm:text-left">End of Document • Secure & Encrypted</span>
                            <a href="{{ route('home') }}" class="px-6 py-2.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl font-black text-[9px] uppercase tracking-widest hover:scale-105 active:scale-95 transition-all">
                                Back to Home
                            </a>
                        </div>
                    </div>
                </div>

                {{-- 3D Floating Objects - Hidden on smallest mobile --}}
                <div class="absolute -top-8 -right-4 md:-top-12 md:-right-8 w-20 h-20 md:w-32 md:h-32 z-30 animate-float hidden xsm:block">
                    <img src="{{ frontendImage('about', '3d_icons.png') }}" alt="3D" class="w-full h-full object-contain drop-shadow-2xl opacity-80">
                </div>
            </div>

            <div class="mt-12 text-center" x-data="{ showModal: false }">
                <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">
                    Need help?
                    <button @click="showModal = true" class="text-slate-900 dark:text-white underline decoration-slate-300 dark:decoration-white/20 underline-offset-4 hover:decoration-slate-900 dark:hover:decoration-white transition-all cursor-pointer">Contact Support</button>
                </p>

                <div x-show="showModal" x-cloak class="fixed inset-0 z-[10000] flex items-center justify-center p-4">
                    <div @click="showModal = false" class="absolute inset-0 bg-black/60 backdrop-blur-md"></div>
                    <div x-show="showModal"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         class="relative w-full max-w-sm bg-white dark:bg-[#121212] rounded-[2rem] overflow-hidden shadow-2xl border border-gray-100 dark:border-white/10 p-8 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-orange-500/10 flex items-center justify-center text-orange-500 mx-auto mb-5">
                            <span class="material-symbols-rounded text-3xl">headset_mic</span>
                        </div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-widest mb-2">Contact Support</h3>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.15em] mb-8">Choose how to reach us</p>
                        <div class="grid grid-cols-2 gap-3">
                            <a href="mailto:support@of2on.tube" class="px-4 py-4 bg-orange-500 text-white rounded-2xl font-black text-[9px] uppercase tracking-widest hover:bg-orange-600 active:scale-[0.97] transition-all flex flex-col items-center justify-center gap-2 shadow-lg shadow-orange-500/20 leading-tight text-center">
                                <span class="material-symbols-rounded text-2xl">mail</span>
                                Email Support
                            </a>
                            @auth
                            <a href="{{ route('user.ticket.create') }}" class="px-4 py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl font-black text-[9px] uppercase tracking-widest hover:bg-slate-800 dark:hover:bg-slate-100 active:scale-[0.97] transition-all flex flex-col items-center justify-center gap-2 shadow-lg leading-tight text-center">
                                <span class="material-symbols-rounded text-2xl">support_agent</span>
                                Submit a Ticket
                            </a>
                            @else
                            <a href="{{ route('login') }}" class="px-4 py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl font-black text-[9px] uppercase tracking-widest hover:bg-slate-800 dark:hover:bg-slate-100 active:scale-[0.97] transition-all flex flex-col items-center justify-center gap-2 shadow-lg leading-tight text-center">
                                <span class="material-symbols-rounded text-2xl">support_agent</span>
                                Submit a Ticket
                            </a>
                            @endauth
                        </div>
                        <button @click="showModal = false" class="mt-6 text-[9px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-900 dark:hover:text-white transition-all cursor-pointer">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');
    
    body {
        font-family: 'Inter', sans-serif;
    }

    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fade-in-up 1.2s cubic-bezier(0.23, 1, 0.32, 1) forwards;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float-slow {
        0%, 100% { transform: translate(0, 0) rotate(0); }
        50% { transform: translate(15px, -15px) rotate(-5deg); }
    }
    @keyframes orb-float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
    }
    .animate-orb-float {
        animation: orb-float 10s ease-in-out infinite;
    }

    @keyframes orb-float-slow {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(-40px, 40px) scale(1.2); }
    }
    .animate-orb-float-slow {
        animation: orb-float-slow 15s ease-in-out infinite;
    }

    @keyframes orb-pulse {
        0%, 100% { opacity: 0.2; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.3); }
    }
    .animate-orb-pulse {
        animation: orb-pulse 8s ease-in-out infinite;
    }
</style>


