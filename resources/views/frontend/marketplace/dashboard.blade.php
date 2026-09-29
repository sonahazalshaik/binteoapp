@extends('layouts.app')

@section('content')
@php
    $unreadCount = \App\Models\MarketMessage::where('marketplace_id', $client->id)
        ->where('is_read', 0)
        ->count();
@endphp
<div id="marketplace-dashboard" class="min-h-screen bg-[#F8FAFC] dark:bg-[#080808] transition-colors duration-500" 
     x-data="{ 
        tab: (() => { const p = new URLSearchParams(window.location.search).get('tab'); if (p && ['dashboard','profile','gallery','portfolio','services','messages','contacts','plans','transactions'].includes(p)) return p; const h = window.location.hash ? window.location.hash.substring(1) : ''; if (h && ['dashboard','profile','gallery','portfolio','services','messages','contacts','plans','transactions'].includes(h)) return h; return 'dashboard'; })(), 
        sidebarOpen: false,
        unreadCount: {{ $unreadCount }},
        activeChat: null,
        markMessagesRead() {
            if (this.unreadCount > 0) {
                this.unreadCount = 0;
                fetch('{{ route('marketplace.messages.read') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
            }
        },
        ...planPurchase(), 
        ...transactionInvoice(),
        editService: { open: false, id: null, name: '', brief: '', img: '' } 
     }" 
     x-init="
        if(!['dashboard', 'profile', 'gallery', 'portfolio', 'services', 'messages', 'contacts', 'plans', 'transactions'].includes(tab)) tab = 'dashboard';
        $watch('tab', value => { const url = new URL(window.location.href); url.searchParams.set('tab', value); window.history.replaceState({}, '', url); });
        const planId = new URLSearchParams(window.location.search).get('plan_id');
        if (planId) { setTimeout(() => { initiatePurchase(planId); }, 500); }
        if (unreadCount > 0 && typeof Swal !== 'undefined') {
            setTimeout(() => {
                Swal.fire({
                    title: 'New Message!',
                    text: 'You have ' + unreadCount + ' unread message' + (unreadCount > 1 ? 's' : '') + ' on your marketplace profile.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#e04f3e',
                    confirmButtonText: 'View Messages',
                    cancelButtonText: 'Dismiss',
                    background: document.documentElement.classList.contains('dark') ? '#1a1d24' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#0f172a'
                }).then((result) => {
                    if (result.isConfirmed) {
                        tab = 'messages';
                        markMessagesRead();
                    }
                });
            }, 600);
        }
     "
     x-effect="const nav = document.querySelector('.lg\\:hidden.fixed.bottom-0'); if(nav) nav.style.display = (tab === 'messages' && activeChat) ? 'none' : '';">
    
    <div class="flex h-[100dvh] overflow-hidden relative">
        <!-- Desktop Sidebar (Hidden on Mobile) -->
        <aside class="hidden lg:flex flex-col w-72 bg-white dark:bg-[#111] border-r border-slate-200 dark:border-white/5 shrink-0">
            <div class="p-8">
                <h1 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">My Dashboard</h1>
            </div>
            <nav class="flex-grow px-4 space-y-1 overflow-y-auto custom-scrollbar">
                <a href="{{ route('home') }}" class="w-full flex items-center gap-4 px-6 py-4 rounded-2xl font-bold text-sm text-slate-500 hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-all group mb-4">
                    <span class="material-symbols-rounded text-xl opacity-50 group-hover:opacity-100">home</span>
                    Back to Home
                </a>

                @foreach([
                    ['dashboard', 'Dashboard', 'dashboard'],
                    ['profile', 'Profile', 'person'],
                    ['gallery', 'Gallery', 'photo_library'],
                    ['portfolio', 'Portfolio', 'work'],
                    ['services', 'Services', 'design_services'],
                    ['messages', 'Messages', 'forum'],
                    ['contacts', 'Contact List', 'mail'],
                    ['plans', 'Plans', 'card_membership'],
                    ['transactions', 'Transactions', 'account_balance_wallet']
                ] as $item)
                <a href="{{ route('marketplace.dashboard', ['tab' => $item[0]]) }}" 
                   @click="if('{{ $item[0] }}' === 'messages') markMessagesRead();"
                   :class="tab === '{{ $item[0] }}' ? 'bg-slate-100 dark:bg-white/5 text-slate-900 dark:text-white' : 'text-slate-500 hover:bg-slate-50 dark:hover:bg-white/[0.02]'"
                   class="w-full flex items-center justify-between px-6 py-4 rounded-2xl font-bold text-sm transition-all group">
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-rounded text-xl" :class="tab === '{{ $item[0] }}' ? 'text-red-600' : 'opacity-50 group-hover:opacity-100'">{{ $item[2] }}</span>
                        {{ $item[1] }}
                    </div>
                    @if($item[0] === 'messages')
                        <span x-show="unreadCount > 0" x-text="unreadCount" class="bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full"></span>
                    @endif
                </a>
                @endforeach

                <a href="{{ route('marketplace.portfolio', $client->slug) }}" target="_blank" class="w-full flex items-center gap-4 px-6 py-4 rounded-2xl font-bold text-sm text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-all group mt-4">
                    <span class="material-symbols-rounded text-xl">visibility</span>
                    View Live Profile
                </a>
            </nav>
            <div class="p-6 border-t border-slate-100 dark:border-white/5">
                <form action="{{ route('marketplace.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-4 px-6 py-4 rounded-2xl text-rose-600 font-bold text-sm hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-all">
                        <span class="material-symbols-rounded text-xl">logout</span>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Mobile Drawer (Alpine Controlled) -->
        <template x-teleport="body">
            <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-[9999] lg:hidden">
                <div x-show="sidebarOpen" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="sidebarOpen = false"
                     class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

                <aside x-show="sidebarOpen"
                       x-transition:enter="transition ease-out duration-300"
                       x-transition:enter-start="-translate-x-full"
                       x-transition:enter-end="translate-x-0"
                       x-transition:leave="transition ease-in duration-200"
                       x-transition:leave-start="translate-x-0"
                       x-transition:leave-end="-translate-x-full"
                       class="absolute inset-y-0 left-0 w-72 bg-white dark:bg-[#111] shadow-2xl flex flex-col">
                    <div class="p-8 flex items-center justify-between">
                        <h1 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">My Dashboard</h1>
                        <button @click="sidebarOpen = false" class="lg:hidden text-slate-400"><span class="material-symbols-rounded">close</span></button>
                    </div>
                    <nav class="flex-grow px-4 space-y-1 overflow-y-auto">
                        <a href="{{ route('home') }}" class="w-full flex items-center gap-4 px-6 py-4 rounded-2xl font-bold text-sm text-slate-500">
                            <span class="material-symbols-rounded text-xl">home</span>
                            Back to Home
                        </a>
                        @foreach([
                            ['dashboard', 'Dashboard', 'dashboard'],
                            ['profile', 'Profile', 'person'],
                            ['gallery', 'Gallery', 'photo_library'],
                            ['portfolio', 'Portfolio', 'work'],
                            ['services', 'Services', 'design_services'],
                            ['messages', 'Messages', 'forum'],
                            ['contacts', 'Contact List', 'mail'],
                            ['plans', 'Plans', 'card_membership'],
                            ['transactions', 'Transactions', 'account_balance_wallet']
                        ] as $item)
                        <a href="{{ route('marketplace.dashboard', ['tab' => $item[0]]) }}" 
                           @click="sidebarOpen = false; if('{{ $item[0] }}' === 'messages') markMessagesRead();"
                           :class="tab === '{{ $item[0] }}' ? 'bg-slate-100 dark:bg-white/5 text-slate-900 dark:text-white' : 'text-slate-500'"
                           class="w-full flex items-center justify-between px-6 py-4 rounded-2xl font-bold text-sm transition-all">
                            <div class="flex items-center gap-4">
                                <span class="material-symbols-rounded text-xl" :class="tab === '{{ $item[0] }}' ? 'text-red-600' : 'opacity-50'">{{ $item[2] }}</span>
                                {{ $item[1] }}
                            </div>
                            @if($item[0] === 'messages')
                                <span x-show="unreadCount > 0" x-text="unreadCount" class="bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full"></span>
                            @endif
                        </a>
                        @endforeach
                    </nav>
                </aside>
            </div>
        </template>

        <!-- Main Content -->
        <main class="flex-grow flex flex-col min-w-0 bg-[#F8FAFC] dark:bg-[#080808]">
            <!-- Header (Premium Cinematic Redesign) -->
            <header x-show="!(tab === 'messages' && activeChat)" 
                    class="sticky top-0 z-[40] min-h-[5rem] md:h-24 bg-white/70 dark:bg-[#080808]/70 backdrop-blur-2xl border-b border-slate-200/50 dark:border-white/5 flex items-center justify-between px-4 md:px-12 shrink-0"
                    style="padding-top: max(1rem, env(safe-area-inset-top)); padding-bottom: 1rem;">
                <div class="flex items-center gap-4 md:gap-6">
                    <button @click="sidebarOpen = true" class="lg:hidden w-10 h-10 md:w-12 md:h-12 rounded-xl md:rounded-2xl bg-slate-900 dark:bg-white/5 flex items-center justify-center text-white dark:text-white transition-all active:scale-90 shrink-0">
                        <span class="material-symbols-rounded">menu</span>
                    </button>
                    <div>
                        <h2 class="text-lg md:text-2xl font-black text-slate-900 dark:text-white capitalize tracking-tighter" x-text="tab"></h2>
                        <div class="flex items-center gap-2 mt-0.5 md:mt-1">
                            <div class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                            <span class="text-[8px] md:text-[9px] font-black text-slate-400 uppercase tracking-widest hidden sm:inline-block">Neural Station Active</span>
                            <span class="text-[8px] md:text-[9px] font-black text-slate-400 uppercase tracking-widest sm:hidden">Active</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-4 md:gap-8">

                    <div class="relative" x-data="{ profileOpen: false }">
                        <button @click="profileOpen = !profileOpen" class="flex items-center gap-4 pl-8 border-l border-slate-200 dark:border-white/10 outline-none group/profile">
                            <div class="text-right hidden sm:block">
                                <p class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $client->name }}</p>
                                <p class="text-[9px] font-bold text-orange-500 uppercase tracking-widest">{{ $client->type ?? 'Individual' }}</p>
                            </div>
                            <div class="relative group">
                                <div class="absolute inset-0 bg-gradient-to-tr from-orange-500 to-rose-600 blur-lg opacity-40 group-hover:opacity-100 transition-opacity rounded-2xl"></div>
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-slate-800 to-black p-0.5 relative">
                                    <div class="w-full h-full rounded-2xl bg-white dark:bg-orange-500 flex items-center justify-center text-slate-900 dark:text-white font-black text-xs overflow-hidden">
                                        @if($client->image)
                                            <img src="{{ $client->photoUrl() }}" class="w-full h-full object-cover">
                                        @else
                                            {{ $client->getInitials() }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </button>

                        <!-- Profile Dropdown -->
                        <div x-show="profileOpen" 
                             @click.outside="profileOpen = false"
                             x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-4"
                             class="absolute right-0 mt-4 w-64 bg-white dark:bg-[#111] rounded-[2rem] shadow-2xl border border-slate-100 dark:border-white/5 p-4 z-[50]">
                            
                            <div class="flex flex-col gap-1">
                                <a href="{{ route('marketplace.portfolio', $client->slug) }}" target="_blank" class="flex items-center gap-4 p-4 rounded-2xl hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-all group">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-500">
                                        <span class="material-symbols-rounded">visibility</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[11px] font-black uppercase tracking-widest text-slate-900 dark:text-white">Live Profile</span>
                                        <span class="text-[8px] font-bold text-slate-400 uppercase">View your public portfolio</span>
                                    </div>
                                </a>

                                <button @click="tab = 'profile'; profileOpen = false" class="w-full flex items-center gap-4 p-4 rounded-2xl hover:bg-orange-50 dark:hover:bg-orange-500/10 transition-all group text-left">
                                    <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                                        <span class="material-symbols-rounded">settings</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[11px] font-black uppercase tracking-widest text-slate-900 dark:text-white">Account Settings</span>
                                        <span class="text-[8px] font-bold text-slate-400 uppercase">Update your details</span>
                                    </div>
                                </button>

                                <div class="h-px bg-slate-100 dark:bg-white/5 my-2"></div>

                                <form action="{{ route('marketplace.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-4 p-4 rounded-2xl hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-all group text-left">
                                        <div class="w-10 h-10 rounded-xl bg-rose-500/10 flex items-center justify-center text-rose-500">
                                            <span class="material-symbols-rounded">logout</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[11px] font-black uppercase tracking-widest text-rose-600">Sign Out</span>
                                            <span class="text-[8px] font-bold text-rose-400/60 uppercase">End neural session</span>
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-grow overflow-y-auto custom-scrollbar relative flex flex-col" 
                 :class="tab === 'messages' ? 'p-0 overflow-hidden' : 'p-4 pb-32 md:p-8 md:pb-8'">
                @include('frontend.marketplace.sections.dashboard')
                @include('frontend.marketplace.sections.profile')
                @include('frontend.marketplace.sections.gallery')
                @include('frontend.marketplace.sections.portfolio')
                @include('frontend.marketplace.sections.services')
                @include('frontend.marketplace.sections.messages')
                @include('frontend.marketplace.sections.contacts')
                @include('frontend.marketplace.sections.plans')
                @include('frontend.marketplace.sections.transactions')
            </div>
        </main>
    </div>

    <!-- Modals & Scripts -->
    @include('frontend.marketplace.sections.scripts')
    @include('frontend.marketplace.sections.modals')

</div>

<style>
    /* Page-scoped visibility fix (dashboard page only).
       The layout globally forces `.material-symbols-rounded` to `color: inherit`,
       which beats Tailwind's text-color utilities and leaves icons dark-on-dark.
       Light mode restores originals; dark mode renders icons white. */
    html:not(.dark) #marketplace-dashboard span.material-symbols-rounded.text-slate-400 { color: #94a3b8 !important; }
    html:not(.dark) #marketplace-dashboard .group:focus-within span.material-symbols-rounded.group-focus-within\:text-indigo-500 { color: #6366f1 !important; }
    html:not(.dark) #marketplace-dashboard .group:hover span.material-symbols-rounded.group-hover\:text-red-600 { color: #dc2626 !important; }
    html:not(.dark) #marketplace-dashboard span.material-symbols-rounded.text-slate-200 { color: #e2e8f0 !important; }
    html:not(.dark) #marketplace-dashboard span.material-symbols-rounded.text-slate-900 { color: #0f172a !important; }
    html:not(.dark) #marketplace-dashboard span.material-symbols-rounded.text-white { color: #ffffff !important; }
    html:not(.dark) #marketplace-dashboard span.material-symbols-rounded.text-rose-500 { color: #f43f5e !important; }
    html:not(.dark) #marketplace-dashboard span.material-symbols-rounded.text-indigo-500 { color: #6366f1 !important; }
    html:not(.dark) #marketplace-dashboard span.material-symbols-rounded.text-red-600 { color: #dc2626 !important; }
    html:not(.dark) #marketplace-dashboard span.material-symbols-rounded.text-blue-500 { color: #3b82f6 !important; }
    html:not(.dark) #marketplace-dashboard span.material-symbols-rounded.text-emerald-500 { color: #10b981 !important; }
    html:not(.dark) #marketplace-dashboard span.material-symbols-rounded.text-orange-500 { color: #f97316 !important; }
    html:not(.dark) #marketplace-dashboard span.material-symbols-rounded.text-purple-500 { color: #a855f7 !important; }
    .dark #marketplace-dashboard span.material-symbols-rounded.text-slate-400 { color: rgba(255,255,255,0.7) !important; }
    .dark #marketplace-dashboard span.material-symbols-rounded.text-slate-200 { color: #ffffff !important; }
    .dark #marketplace-dashboard span.material-symbols-rounded.text-slate-900 { color: #ffffff !important; }
    .dark #marketplace-dashboard span.material-symbols-rounded.text-white { color: #ffffff !important; }
    .dark #marketplace-dashboard span.material-symbols-rounded.text-rose-500 { color: #ffffff !important; }
    .dark #marketplace-dashboard span.material-symbols-rounded.text-red-600 { color: #ffffff !important; }
    .dark #marketplace-dashboard span.material-symbols-rounded.text-blue-500 { color: #ffffff !important; }
    .dark #marketplace-dashboard span.material-symbols-rounded.text-emerald-500 { color: #ffffff !important; }
    .dark #marketplace-dashboard span.material-symbols-rounded.text-orange-500 { color: #ffffff !important; }
    .dark #marketplace-dashboard span.material-symbols-rounded.text-purple-500 { color: #ffffff !important; }
    /* Teleported modals render outside the page root, so these two rules are
       intentionally unscoped (this style tag only loads on this page). */
    html:not(.dark) button.w-10.h-10.rounded-full.bg-slate-50 > span.material-symbols-rounded { color: #64748b !important; }
    .dark button.w-10.h-10.rounded-full.bg-slate-50 > span.material-symbols-rounded { color: rgba(255,255,255,0.7) !important; }
    /* Profile inputs/textareas carry no text color: force readable text + placeholders in dark mode */
    .dark #marketplace-dashboard input { color: #ffffff !important; }
    .dark #marketplace-dashboard textarea { color: #ffffff !important; }
    .dark #marketplace-dashboard input::placeholder { color: rgba(255,255,255,0.35) !important; opacity: 1 !important; }
    .dark #marketplace-dashboard textarea::placeholder { color: rgba(255,255,255,0.35) !important; opacity: 1 !important; }
    /* Field icons use top-1/2 of their wrapper, so any hint/error text injected
       below the input stretches the wrapper and drags the icon down while typing.
       Pin them to the input's vertical center instead (py-2 inputs ≈ 34px tall). */
    #marketplace-dashboard span.material-symbols-rounded.absolute.top-1\/2 { top: 17px !important; }
</style>
@endsection
