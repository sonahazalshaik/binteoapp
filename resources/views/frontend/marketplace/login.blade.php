@extends('layouts.app')

@section('content')
<div id="marketplace-login" class="min-h-screen flex flex-col items-center justify-center px-6 py-8 md:py-12 bg-slate-50 dark:bg-[#0A0A0A] transition-colors duration-500">
    <div class="w-full max-w-[380px] animate-in slide-in-from-bottom-10 duration-700">
        <!-- M3 Surface Card -->
        <div class="bg-white dark:bg-[#151515] p-4 md:p-6 rounded-[3rem] border border-slate-100 dark:border-white/5 shadow-2xl relative overflow-hidden">
            <div class="absolute -top-32 -right-32 w-64 h-64 bg-red-600/5 dark:bg-red-600/10 rounded-full blur-[100px]"></div>
            
            <div class="text-center mb-6">
                <div class="w-12 h-12 bg-red-50 dark:bg-red-600/10 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-rounded text-2xl text-red-600">account_circle</span>
                </div>
                <h1 class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Account Login</h1>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-2 tracking-tight">Access your professional talent dashboard</p>
            </div>

            <form action="{{ route('marketplace.login') }}" method="POST" class="space-y-4 md:space-y-5 relative z-10" x-data="{ loading: false }" @submit="loading = true">
                @csrf
                <x-input type="email" name="email" label="Email Address" placeholder="Enter your email" required="true" icon="mail" hint="Your registered talent account email." />

                <x-input type="password" name="password" label="Password" placeholder="Enter your password" required="true" icon="lock" hint="Your secure account password." />

                <div class="pt-2">
                    <button type="submit" x-bind:disabled="loading" class="w-full h-14 bg-red-600 text-white rounded-xl font-black text-[11px] uppercase tracking-[0.3em] shadow-2xl shadow-red-500/30 hover:bg-red-700 active:scale-95 transition-all flex items-center justify-center gap-3 disabled:opacity-70 disabled:cursor-not-allowed">
                        <span class="material-symbols-rounded" x-show="!loading">login</span>
                        <span class="material-symbols-rounded animate-spin" x-show="loading" style="display: none;">progress_activity</span>
                        <span x-text="loading ? 'Authenticating...' : 'Sign In'">Sign In</span>
                    </button>
                    <div class="flex items-center justify-between mt-6 px-2">
                        <a href="{{ route('marketplace.register') }}" class="text-[10px] font-black text-slate-400 hover:text-red-600 uppercase tracking-widest transition-colors">Initialize Registration</a>
                        <a href="#" class="text-[10px] font-black text-slate-400 hover:text-red-600 uppercase tracking-widest transition-colors">Reset Access</a>
                    </div>

                </div>
            </form>
        </div>
        
        <!-- Bottom Hint -->
        <div class="mt-8 flex items-center justify-center gap-2 opacity-40">
            <span class="w-1 h-1 bg-slate-400 rounded-full"></span>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Safe & Secure Access</p>
            <span class="w-1 h-1 bg-slate-400 rounded-full"></span>
        </div>
    </div>
</div>

<style>
    /* Page-scoped icon visibility fix (this page only).
       The layout globally forces `.material-symbols-rounded` to `color: inherit`,
       which beats Tailwind's text-color utilities and leaves icons dark-on-dark
       in dark mode. Light mode restores the original colors; dark mode renders
       the icons white as designed. */
    html:not(.dark) #marketplace-login span.material-symbols-rounded.text-red-600 { color: #dc2626 !important; }
    html:not(.dark) #marketplace-login span.material-symbols-rounded.text-rose-500 { color: #f43f5e !important; }
    html:not(.dark) #marketplace-login span.material-symbols-rounded.text-orange-500 { color: #f97316 !important; }
    .dark #marketplace-login span.material-symbols-rounded.text-red-600 { color: #ffffff !important; }
    .dark #marketplace-login span.material-symbols-rounded.text-rose-500 { color: #ffffff !important; }
    .dark #marketplace-login span.material-symbols-rounded.text-orange-500 { color: #ffffff !important; }
</style>

{{-- Pending Deletion Modal --}}
@if(session('deletion_pending'))
<div x-data="{ show: true }" x-show="show" class="fixed inset-0 z-[100000] flex items-center lg:items-start justify-center px-4 py-24 lg:p-4 overflow-y-auto">
    <div class="fixed inset-0 bg-black/80 backdrop-blur-md" @click="show = false"></div>
    <div class="relative w-full max-w-md lg:mt-24 bg-white dark:bg-[#1A1A1A] border border-gray-100 dark:border-white/5 rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in-95 duration-300 my-auto lg:my-0 flex flex-col">
        <div class="h-1.5 bg-gradient-to-r from-rose-500 to-orange-600 shrink-0"></div>
        <div class="p-6 sm:p-10 text-center overflow-y-auto max-h-[85vh] scrollbar-hide">
            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-rose-500/10 rounded-2xl sm:rounded-3xl flex items-center justify-center mx-auto mb-6 sm:mb-8 shadow-inner">
                <span class="material-symbols-rounded text-rose-500 text-3xl sm:text-4xl">no_accounts</span>
            </div>
            <h3 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tighter mb-4">Deletion Pending</h3>
            <p class="text-[9px] sm:text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest leading-relaxed sm:leading-loose mb-6 sm:mb-8">
                Your request to terminate this marketplace talent profile is pending administrator approval. All services, portfolios, galleries, and dashboard access are scheduled for permanent deletion.
                @if(session('deletion_reason'))
                    <br><span class="text-rose-500 mt-2 block">Reason Selected: {{ session('deletion_reason') }}</span>
                @endif
            </p>
            
            <div class="p-4 sm:p-6 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 mb-6 sm:mb-8 text-left">
                <p class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3 sm:mb-4 text-center">To cancel this request, please contact support:</p>
                <div class="flex flex-col gap-2.5">
                    @if(@gs('support_config')->email1)
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-rounded text-orange-500 text-base">mail</span>
                            <a href="mailto:{{ gs('support_config')->email1 }}" class="text-[10px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 hover:text-orange-500 transition-colors break-all">{{ gs('support_config')->email1 }}</a>
                        </div>
                    @endif
                    @if(@gs('support_config')->email2)
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-rounded text-orange-500 text-base">mail</span>
                            <a href="mailto:{{ gs('support_config')->email2 }}" class="text-[10px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 hover:text-orange-500 transition-colors break-all">{{ gs('support_config')->email2 }}</a>
                        </div>
                    @endif
                </div>
            </div>

            <button @click="show = false" class="relative w-full h-12 sm:h-14 bg-gradient-to-r from-orange-600 to-orange-500 rounded-xl text-white font-black uppercase tracking-[0.2em] shadow-lg shadow-orange-500/20 hover:scale-[1.01] active:scale-95 transition-all overflow-hidden group/btn text-[9px] sm:text-[10px] shrink-0">
                <div class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover/btn:translate-x-[100%] transition-transform duration-1000"></div>
                <span class="relative flex items-center justify-center gap-2">
                    Dismiss Notice
                </span>
            </button>
        </div>
    </div>
</div>
@endif
@endsection



