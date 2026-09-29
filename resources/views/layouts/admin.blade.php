<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Dashboard - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('assets/js/validation.js') }}"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F8FAFC] dark:bg-[#0F172A] text-slate-900 dark:text-slate-100 antialiased" x-data="{ sidebarOpen: true }">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-[#1E293B] border-r border-slate-200 dark:border-slate-800 transition-transform duration-300 lg:static lg:translate-x-0"
               :class="{ '-translate-x-full': !sidebarOpen }">
            
            <div class="flex flex-col h-full">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-between h-16 px-6 border-b border-slate-200 dark:border-slate-800">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-rounded text-white text-xl">play_circle</span>
                        </div>
                        <span class="text-xl font-bold tracking-tight">Admin<span class="text-red-600">Hub</span></span>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto custom-scrollbar">
                    <p class="px-2 mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Core</p>
                    
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-500' : 'hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400' }}">
                        <span class="material-symbols-rounded">dashboard</span>
                        <span class="font-bold text-sm">Dashboard</span>
                    </a>

                    <div class="pt-4 px-2 mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Content Ecosystem</div>

                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-all">
                        <span class="material-symbols-rounded">group</span>
                        <span class="font-medium text-sm">User Management</span>
                    </a>

                    <a href="{{ route('admin.channels.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-all">
                        <span class="material-symbols-rounded">tv_signin</span>
                        <span class="font-medium text-sm">Channel Oversight</span>
                    </a>

                    <a href="{{ route('admin.videos.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-all">
                        <span class="material-symbols-rounded">movie</span>
                        <span class="font-medium text-sm">Video Library</span>
                    </a>

                    <a href="{{ route('admin.comments.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-all">
                        <span class="material-symbols-rounded">forum</span>
                        <span class="font-medium text-sm">Community Comments</span>
                    </a>

                    <a href="{{ route('admin.category.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-all">
                        <span class="material-symbols-rounded">category</span>
                        <span class="font-medium text-sm">Content Categories</span>
                    </a>

                    <div class="pt-4 px-2 mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Monetization & Market</div>

                    <a href="{{ route('admin.monetization.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-all">
                        <span class="material-symbols-rounded">payments</span>
                        <span class="font-medium text-sm">Revenue Control</span>
                    </a>

                    <a href="{{ route('admin.plans.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-all">
                        <span class="material-symbols-rounded">loyalty</span>
                        <span class="font-medium text-sm">Member Tiers</span>
                    </a>

                    <a href="{{ route('admin.marketplace.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-all">
                        <span class="material-symbols-rounded">handshake</span>
                        <span class="font-medium text-sm">Talent Marketplace</span>
                    </a>

                    <div class="pt-4 px-2 mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Ad Infrastructure</div>

                    <a href="{{ route('admin.advertiser.pending') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-all">
                        <span class="material-symbols-rounded">person_search</span>
                        <span class="font-medium text-sm">Advertiser Requests</span>
                    </a>

                    <a href="{{ route('admin.advertisement.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-all">
                        <span class="material-symbols-rounded">campaign</span>
                        <span class="font-medium text-sm">Global Ad Campaigns</span>
                    </a>

                    <div class="pt-4 px-2 mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Financial Ledger</div>

                    <a href="{{ route('admin.deposit.list') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-all">
                        <span class="material-symbols-rounded">account_balance_wallet</span>
                        <span class="font-medium text-sm">Funding Log</span>
                    </a>

                    <a href="{{ route('admin.withdraw.data.all') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-all">
                        <span class="material-symbols-rounded">outbox</span>
                        <span class="font-medium text-sm">Payout Requests</span>
                    </a>

                    <a href="{{ route('admin.report.transaction') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-all">
                        <span class="material-symbols-rounded">analytics</span>
                        <span class="font-medium text-sm">Transaction Reports</span>
                    </a>

                    <div class="pt-4 px-2 mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Service & System</div>
                    
                    <a href="{{ route('admin.ticket.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-all">
                        <span class="material-symbols-rounded">support_agent</span>
                        <span class="font-medium text-sm">Support Tickets</span>
                    </a>

                    <a href="{{ route('admin.setting.general') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-all">
                        <span class="material-symbols-rounded">settings_suggest</span>
                        <span class="font-medium text-sm">Core Configuration</span>
                    </a>

                    <a href="{{ route('admin.extensions.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-all">
                        <span class="material-symbols-rounded">extension</span>
                        <span class="font-medium text-sm">Module Extensions</span>
                    </a>
                </nav>

                <!-- Sidebar Footer -->
                <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50">
                        <div class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center text-white font-bold shrink-0">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">System Administrator</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            
            <!-- Topbar -->
            <header class="h-16 bg-white dark:bg-[#1E293B] border-b border-slate-200 dark:border-slate-800 px-6 flex items-center justify-between shrink-0">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                    <span class="material-symbols-rounded">menu</span>
                </button>

                <div class="flex items-center gap-4">
                    <button class="relative p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors text-slate-400">
                        <span class="material-symbols-rounded">notifications</span>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-[#1E293B]"></span>
                    </button>
                    <div class="h-8 w-[1px] bg-slate-200 dark:border-slate-800"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-red-600 transition-colors">
                            <span class="material-symbols-rounded">logout</span>
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Page Body -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8 custom-scrollbar">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
