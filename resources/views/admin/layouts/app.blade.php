<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ darkMode: localStorage.getItem('admin_theme') === 'dark', sideBarOpen: false }" 
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>{{ $pageTitle ?? 'Admin' }} - {{ gs('site_name') }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ siteFavicon() }}">
    
    <!-- Design Tokens -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
    <!-- Cropper.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    
    <!-- Core Assets -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/validation.js') }}"></script>
    
    @php
        $settings = file_get_contents(resource_path('views/admin/setting/settings.json'));
        $settings = json_decode($settings);

        $routesData = [];
        foreach (\Illuminate\Support\Facades\Route::getRoutes() as $route) {
            $name = $route->getName();
            if (strpos($name, 'admin') !== false) {
                $routeData = [
                    $name => url($route->uri()),
                ];
                $routesData[] = $routeData;
            }
        }
    @endphp

    @stack('style-lib')
    @stack('style')
    
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Outfit', sans-serif; -webkit-tap-highlight-color: transparent; }
        
        /* Premium Scrollbar Management */
        .scrollbar-hide::-webkit-scrollbar:vertical { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .scrollbar-hide::-webkit-scrollbar:horizontal { display: block; height: 6px; }
        
        .native-glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
        }
        .dark .native-glass {
            background: rgba(10, 10, 10, 0.7);
        }
        
        .sidebar-premium-dark {
            background: #18181b !important; /* Slate Graphite */
            border-right: 1px solid rgba(255,255,255,0.05);
        }
        .sidebar-orange-gradient {
            background: #18181b !important;
            border-right: 1px solid rgba(255,255,255,0.05);
        }
        .orange-gradient-primary {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%) !important;
        }

        /* Premium Scrollbar Global */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.1);
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #f97316;
        }

        /* Table Horizontal Scroll Enforcement */
        .table-responsive, .table-wrapper, .overflow-x-auto {
            overflow-x: auto !important;
            scrollbar-width: thin;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 8px; /* Space for scrollbar */
        }

        /* Specific Table Scrolling UI */
        .card .table-responsive::-webkit-scrollbar {
            height: 8px;
        }
        .card .table-responsive::-webkit-scrollbar-thumb {
            background: rgba(249, 115, 22, 0.2);
        }
        .card .table-responsive::-webkit-scrollbar-thumb:hover {
            background: rgba(249, 115, 22, 0.5);
        }

        /* Fluid Animations */
        .page-enter { animation: native-slide-up 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes native-slide-up {
            from { opacity: 0; transform: translateY(20px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Modal Polyfill Styles */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 1000;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            overflow-y: auto;
            padding: 1rem;
        }
        .modal.show {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            animation: fadeIn 0.2s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .modal-dialog {
            width: 100%;
            max-width: 650px;
            margin: 2rem auto;
            animation: slideDown 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }
        .modal-dialog.modal-lg {
            max-width: 1000px;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .modal-content {
            background: white;
            border-radius: 1.5rem;
            padding: 1.5rem;
            width: 100%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(0,0,0,0.05);
        }
        .dark .modal-content {
            background: #121212;
            border-color: rgba(255,255,255,0.1);
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }
        .dark .modal-header { border-bottom-color: rgba(255,255,255,0.1); }
        .modal-title { font-weight: 800; font-size: 1.25rem; }
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(0,0,0,0.1);
        }
        .dark .modal-footer { border-top-color: rgba(255,255,255,0.1); }
        .form-group { margin-bottom: 1.25rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.875rem; color: #475569; }
        .dark .form-group label { color: #cbd5e1; }
        .form-control { width: 100%; padding: 0.75rem 1rem; border-radius: 0.75rem; border: 1px solid #e2e8f0; background: #f8fafc; font-size: 0.875rem; outline: none; transition: all 0.2s; }
        .form-control:focus { border-color: #f97316; box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.2); }
        .dark .form-control { border-color: rgba(255,255,255,0.1); background: rgba(255,255,255,0.05); color: white; }

        /* Global App View Transformation for Legacy Tables */
        .app-view-active .overflow-x-auto { overflow: visible !important; }
        .app-view-active table { display: block; width: 100%; border: none; }
        .app-view-active thead { display: none; }
        .app-view-active tbody { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.25rem; padding: 0.5rem; border: none !important; }
        
        /* Empty State Handling */
        .app-view-active tbody:has(td[colspan="100%"]) { display: flex; align-items: center; justify-content: center; min-height: 200px; }
        .app-view-active td[colspan="100%"] { display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; width: 100% !important; text-align: center !important; border: none !important; padding: 3rem !important; background: transparent !important; opacity: 0.5; font-style: ; font-weight: 900; letter-spacing: 0.1em; text-transform: uppercase; }
        .app-view-active td[colspan="100%"]::before { content: 'inbox'; font-family: 'Material Symbols Rounded'; font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }
        
        .app-view-active tr:not(:has(td[colspan="100%"])) { display: flex; flex-direction: column; gap: 0.75rem; background: white; border: 1px solid rgba(0,0,0,0.05); padding: 1.5rem; border-radius: 1.5rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); transition: transform 0.2s; }
        .dark .app-view-active tr:not(:has(td[colspan="100%"])) { background: #121212; border-color: rgba(255,255,255,0.05); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.2); }
        .app-view-active tr:not(:has(td[colspan="100%"])):hover { transform: translateY(-2px); }
        
        .app-view-active td:not([colspan="100%"]) { display: flex; flex-direction: column; gap: 0.25rem; border: none !important; padding: 0 !important; width: 100%; text-align: left !important; white-space: normal !important; align-items: flex-start !important; }
        .app-view-active td:not([colspan="100%"]) .button--group { width: 100%; justify-content: flex-start; }
        .app-view-active td:not([colspan="100%"]):last-child { margin-top: 0.5rem; padding-top: 1rem !important; border-top: 1px dashed rgba(0,0,0,0.1) !important; flex-direction: row; flex-wrap: wrap; }
        .dark .app-view-active td:not([colspan="100%"]):last-child { border-top-color: rgba(255,255,255,0.1) !important; }
        
        /* Specific adjustments for badges and buttons inside cards */
        .app-view-active td:not([colspan="100%"]) .badge { align-self: flex-start; }
        .app-view-active td:not([colspan="100%"]) img { border-radius: 0.75rem; max-width: 100%; height: auto; }


        /* Legacy Button Polyfills - Ultra Premium Native App Look */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0 1.5rem;
            height: 3rem;
            font-size: 0.75rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-style: ;
            border-radius: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: 1px solid transparent;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            white-space: nowrap;
        }
        .btn::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.2), transparent);
            transform: translateX(-100%);
            transition: transform 0.5s;
        }
        .btn:hover::after { transform: translateX(100%); }
        .btn:active { transform: scale(0.95); }
        .btn-sm { height: 2.25rem; padding: 0 1rem; font-size: 0.65rem; border-radius: 0.75rem; }
        .btn-lg { height: 3.5rem; padding: 0 2rem; font-size: 0.85rem; border-radius: 1.25rem; }
        
        /* Primary */
        .btn--primary, .btn-primary { 
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); 
            color: white !important; 
            box-shadow: 0 10px 15px -3px rgba(249, 115, 22, 0.3); 
            border: 1px solid rgba(255,255,255,0.1);
        }
        .btn--primary:hover, .btn-primary:hover { box-shadow: 0 15px 25px -5px rgba(249, 115, 22, 0.4); transform: translateY(-2px); }
        
        .btn-outline--primary { border-color: rgba(249, 115, 22, 0.3); color: #f97316; background: rgba(249, 115, 22, 0.05); }
        .btn-outline--primary:hover { background: #f97316; color: white !important; border-color: #f97316; box-shadow: 0 10px 15px -3px rgba(249, 115, 22, 0.3); transform: translateY(-2px); }
        
        /* Danger */
        .btn--danger, .btn-danger { 
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); 
            color: white !important; 
            box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.3); 
            border: 1px solid rgba(255,255,255,0.1);
        }
        .btn--danger:hover, .btn-danger:hover { box-shadow: 0 15px 25px -5px rgba(239, 68, 68, 0.4); transform: translateY(-2px); }
        .btn-outline--danger { border-color: rgba(239, 68, 68, 0.3); color: #ef4444; background: rgba(239, 68, 68, 0.05); }
        .btn-outline--danger:hover { background: #ef4444; color: white !important; border-color: #ef4444; box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.3); transform: translateY(-2px); }
        
        /* Success */
        .btn--success, .btn-success { 
            background: linear-gradient(135deg, #10b981 0%, #059669 100%); 
            color: white !important; 
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);
            border: 1px solid rgba(255,255,255,0.1);
        }
        .btn--success:hover, .btn-success:hover { box-shadow: 0 15px 25px -5px rgba(16, 185, 129, 0.4); transform: translateY(-2px); }
        .btn-outline--success { border-color: rgba(16, 185, 129, 0.3); color: #10b981; background: rgba(16, 185, 129, 0.05); }
        .btn-outline--success:hover { background: #10b981; color: white !important; border-color: #10b981; box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3); transform: translateY(-2px); }
        
        /* Info */
        .btn--info, .btn-info { 
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); 
            color: white !important; 
            box-shadow: 0 10px 15px -3px rgba(14, 165, 233, 0.3);
            border: 1px solid rgba(255,255,255,0.1);
        }
        .btn--info:hover, .btn-info:hover { box-shadow: 0 15px 25px -5px rgba(14, 165, 233, 0.4); transform: translateY(-2px); }
        .btn-outline--info { border-color: rgba(14, 165, 233, 0.3); color: #0ea5e9; background: rgba(14, 165, 233, 0.05); }
        .btn-outline--info:hover { background: #0ea5e9; color: white !important; border-color: #0ea5e9; box-shadow: 0 10px 15px -3px rgba(14, 165, 233, 0.3); transform: translateY(-2px); }
        
        /* Dark */
        .btn--dark { 
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); 
            color: white !important; 
            box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.3);
            border: 1px solid rgba(255,255,255,0.1);
        }
        .btn--dark:hover { box-shadow: 0 15px 25px -5px rgba(15, 23, 42, 0.4); transform: translateY(-2px); }
        .dark .btn--dark { 
            background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%); 
            color: #000000 !important; 
            box-shadow: 0 10px 15px -3px rgba(255, 255, 255, 0.1);
        }
        
        .button--group { display: flex; gap: 0.75rem; flex-wrap: wrap; }

        /* Legacy Cards that wrap Forms globally */
        .card { background: #ffffff; border-radius: 2rem; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 1.5rem; transition: all 0.3s; }
        .dark .card { background: #121212; border-color: rgba(255,255,255,0.1); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2); }
        .card-body { padding: 2rem; }
        .card-header { padding: 1.5rem 2rem; border-bottom: 1px solid #e2e8f0; font-weight: 900; font-size: 1.25rem; font-style: ; text-transform: uppercase; letter-spacing: -0.05em; display: flex; align-items: center; justify-content: space-between; }
        .dark .card-header { border-bottom-color: rgba(255,255,255,0.1); }
        .card-footer { padding: 1.5rem 2rem; border-top: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: flex-end; gap: 1rem; }
        .dark .card-footer { border-top-color: rgba(255,255,255,0.1); background: rgba(255,255,255,0.02); }
        .card-title { font-weight: 900; margin: 0; }
        
        /* Search Intelligence Dropdown Styling */
        .search-list li { border-bottom: 1px solid rgba(255,255,255,0.05); }
        .search-list li:last-child { border-bottom: none; }
        .search-list .search-list-link { display: flex; flex-direction: column; gap: 0.25rem; padding: 1.25rem 2rem; transition: all 0.2s; color: rgba(255,255,255,0.4); text-decoration: none; font-size: 0.65rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em; }
        .search-list .search-list-link:hover { background: rgba(255,255,255,0.05); }
        .search-list .search-list-link .text-color--3 { color: white !important; font-size: 0.85rem; font-style: ; text-transform: none; letter-spacing: normal; margin-top: 2px; }
        .search-list .active .search-list-link { background: rgba(255,255,255,0.08); }

    </style>
</head>
<body x-bind:class="{ 'app-view-active': $store.viewMode && $store.viewMode.mode === 'app' }" class="bg-[#f2f4f7] dark:bg-[#050505] text-slate-900 dark:text-white antialiased selection:bg-orange-500/30 transition-colors duration-500 overflow-hidden select-none">
    
    <div class="flex h-screen overflow-hidden relative">
        
        <!-- Native Side Drawer (Desktop & Tablet) -->
        <aside :class="sideBarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed inset-y-0 left-0 z-50 w-[90vw] max-w-80 lg:w-80 sidebar-orange-gradient flex flex-col flex-shrink-0 transition-all duration-700 cubic-bezier(0.4, 0, 0.2, 1) transform lg:relative lg:translate-x-0 shadow-[0_20px_50px_rgba(255,100,0,0.3)]">
            
            <!-- App Branding -->
            <div class="px-8 py-10 flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 group">
                    <div class="w-12 h-12 gradient-orange rounded-[1.25rem] p-[1.5px] shadow-xl group-active:scale-90 transition-all overflow-hidden">
                        <div class="w-full h-full bg-white rounded-[18px] flex items-center justify-center p-1.5">
                            <img src="{{ siteLogo() }}" class="w-full h-full object-contain" alt="{{ gs('site_name') }}">
                        </div>
                    </div>
                    <div>
                        <h1 class="font-black text-xl tracking-tighter leading-none text-white uppercase ">{{ gs('site_name') }}</h1>
                        <p class="text-[9px] text-white/50 font-black uppercase tracking-[0.2em] mt-1.5">Core Intelligence</p>
                    </div>
                </a>
                <button @click="sideBarOpen = false" class="lg:hidden w-12 h-12 rounded-2xl bg-gradient-to-tr from-orange-400 to-rose-600 flex items-center justify-center text-white shadow-xl shadow-orange-500/40 active:scale-90 transition-all">
                    <span class="material-symbols-rounded font-black text-2xl">close</span>
                </button>
            </div>

            <!-- Navigation Partial -->
            <div class="flex-grow overflow-y-auto">
                @include('admin.partials.sidenav')
            </div>

            <!-- Profile Footer (Native View) -->
            <div class="p-6">
                <div class="p-5 rounded-[2.5rem] bg-black/10 backdrop-blur-md border border-white/10 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center border border-white/20">
                            <span class="material-symbols-rounded text-white text-lg">admin_panel_settings</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[11px] font-black text-white truncate max-w-[100px]">{{ Auth::guard('admin')->user()?->name ?? 'Admin' }}</span>
                            <span class="text-[8px] font-bold text-white/40 uppercase tracking-widest">Admin</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center text-white/60 hover:text-rose-400 active:scale-90 transition-all">
                            <span class="material-symbols-rounded text-xl">logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        
        <!-- Sidebar Backdrop (Mobile/Tablet) -->
        <div x-show="sideBarOpen" 
             @click="sideBarOpen = false" 
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="lg:hidden fixed inset-0 z-40 bg-black/60 backdrop-blur-sm" 
             x-cloak></div>

        <!-- Main Viewport -->
        <main class="flex-grow flex flex-col overflow-hidden relative">
            
            <div id="global-loader" class="absolute inset-0 z-[9999] flex items-center justify-center bg-white/40 dark:bg-black/40 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300">
                <div class="flex flex-col items-center">
                    <div class="relative">
                        <div class="w-20 h-20 border-4 border-rose-500/20 border-t-rose-500 rounded-full animate-spin"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="material-symbols-rounded text-rose-500 animate-pulse">sync</span>
                        </div>
                    </div>
                    <p class="mt-6 text-[10px] font-black uppercase tracking-[0.3em] text-slate-800 dark:text-white animate-pulse">Synchronizing Data</p>
                </div>
            </div>

            <!-- Integrated Premium Native Header -->
            @include('admin.partials.navbar')

            <!-- Scrollable Page Container -->
            <div class="flex-grow overflow-y-auto p-6 sm:p-10 lg:p-12 pb-32 lg:pb-12">
                <div id="live-content-area" class="max-w-[1600px] mx-auto page-enter">
                    @yield('content')
                    @yield('panel')
                </div>
            </div>

            {{-- 
            <!-- Mobile Bottom Tab Bar (Native iOS/Android Style) -->
            <nav class="lg:hidden fixed bottom-6 left-6 right-6 h-20 bg-slate-900/90 dark:bg-black/80 backdrop-blur-3xl rounded-[2.5rem] border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.3)] z-50 flex items-center justify-around px-6">
                <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-1 group active:scale-90 transition-transform">
                    <span class="material-symbols-rounded {{ request()->routeIs('admin.dashboard') ? 'text-orange-500 fill-1 scale-110' : 'text-white/40' }} transition-all text-2xl">grid_view</span>
                    <span class="text-[8px] font-black uppercase tracking-widest {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-white/20' }}">Home</span>
                </a>
                <a href="{{ route('admin.users.all') }}" class="flex flex-col items-center gap-1 group active:scale-90 transition-transform">
                    <span class="material-symbols-rounded {{ request()->routeIs('admin.users.*') ? 'text-orange-500 fill-1 scale-110' : 'text-white/40' }} transition-all text-2xl">group</span>
                    <span class="text-[8px] font-black uppercase tracking-widest {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-white/20' }}">Users</span>
                </a>
                <!-- Center Action Button -->
                <div class="relative -top-8">
                    <button @click="sideBarOpen = true" class="w-16 h-16 rounded-full bg-gradient-to-tr from-orange-400 to-rose-600 shadow-2xl flex items-center justify-center text-white active:scale-90 transition-all border-4 border-[#f2f4f7] dark:border-[#050505]">
                        <span class="material-symbols-rounded text-3xl font-black">add</span>
                    </button>
                </div>
                <a href="{{ route('admin.videos.index') }}" class="flex flex-col items-center gap-1 group active:scale-90 transition-transform">
                    <span class="material-symbols-rounded {{ request()->routeIs('admin.videos.*') ? 'text-orange-500 fill-1 scale-110' : 'text-white/40' }} transition-all text-2xl">movie</span>
                    <span class="text-[8px] font-black uppercase tracking-widest {{ request()->routeIs('admin.videos.*') ? 'text-white' : 'text-white/20' }}">Media</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="flex flex-col items-center gap-1 group active:scale-90 transition-transform">
                    <span class="material-symbols-rounded {{ request()->routeIs('admin.settings.*') ? 'text-orange-500 fill-1 scale-110' : 'text-white/40' }} transition-all text-2xl">settings</span>
                    <span class="text-[8px] font-black uppercase tracking-widest {{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-white/20' }}">Hub</span>
                </a>
            </nav>
            --}}

        </main>
    </div>

    <!-- Background Decoration -->
    <div class="fixed top-0 right-0 w-[500px] h-[500px] bg-orange-500/5 blur-[150px] -z-10 rounded-full animate-pulse"></div>
    <div class="fixed bottom-0 left-0 w-[300px] h-[300px] bg-rose-500/5 blur-[120px] -z-10 rounded-full animate-pulse"></div>

    @include('partials.notify')

    <!-- Legacy Scripts Polyfill -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    @stack('script-lib')
    <script>
        // Minimal Bootstrap Modal Polyfill for Tailwind
        $.fn.modal = function(action) {
            if (action === 'show') {
                this.addClass('show');
                $('body').css('overflow', 'hidden');
            } else if (action === 'hide') {
                this.removeClass('show');
                $('body').css('overflow', '');
            }
            return this;
        };
        
        $(document).on('click', '[data-bs-dismiss="modal"]', function() {
            $(this).closest('.modal').modal('hide');
        });

        // Global Select2 Initializer
        $(document).ready(function() {
            $('.select2').each(function () {
                if (!$(this).hasClass('select2-hidden-accessible')) $(this).select2();
            });
            $('.select2-auto-tokenize').each(function () {
                if (!$(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2({
                        tags: true,
                        tokenSeparators: [',']
                    });
                }
            });
        });
    </script>
    <!-- SweetAlert2 -->
    <script>
        window.notify = function(status, message) {
            Swal.fire({
                title: status.charAt(0).toUpperCase() + status.slice(1),
                text: message,
                icon: status,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: localStorage.getItem('admin_theme') === 'dark' ? '#121212' : '#ffffff',
                color: localStorage.getItem('admin_theme') === 'dark' ? '#ffffff' : '#000000',
            });
        };

        // Global SweetAlert2 Override for Premium UX
        window.alert = function(message) {
            window.adminSwal({ title: 'Intelligence Report', text: message, icon: 'info' });
        };

        const nativeConfirm = window.confirm;
        window.confirm = function(message) {
            return nativeConfirm(message);
        };
        
        // Global Admin SweetAlert Helper
        window.adminSwal = function(options = {}) {
            const isDark = localStorage.getItem('admin_theme') === 'dark';
            const defaults = {
                background: isDark ? '#121212' : '#ffffff',
                color: isDark ? '#ffffff' : '#000000',
                confirmButtonColor: '#f97316',
                cancelButtonColor: '#1e293b',
                customClass: {
                    popup: 'rounded-[2.5rem] border border-white/10 shadow-2xl shadow-black/50',
                    title: 'font-black uppercase tracking-tighter',
                    confirmButton: 'rounded-2xl px-8 py-3 font-black uppercase tracking-widest text-[10px]',
                    cancelButton: 'rounded-2xl px-8 py-3 font-black uppercase tracking-widest text-[10px]'
                }
            };
            const merged = { ...defaults, ...options };
            if (options.customClass) {
                merged.customClass = { ...defaults.customClass, ...options.customClass };
            }
            return Swal.fire(merged);
        };

        // Custom Global Confirm Helper
        window.nativeSwalConfirm = function(title, text, icon = 'warning') {
            return window.adminSwal({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Abort'
            });
        };

        window.showPublishingLoader = function(type = 'video') {
            Swal.fire({
                html: `
                    <div class="flex flex-col items-center justify-center p-6">
                        <div class="w-16 h-16 border-4 border-orange-500/20 border-t-orange-500 rounded-full animate-spin mb-6"></div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-widest mb-2">Please Wait</h3>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest leading-relaxed">The ${type} is publishing...</p>
                    </div>
                `,
                showConfirmButton: false,
                allowOutsideClick: false,
                background: localStorage.getItem('admin_theme') === 'dark' ? '#121212' : '#ffffff',
                customClass: {
                    popup: 'rounded-[2.5rem] border border-slate-100 dark:border-white/10 shadow-2xl'
                }
            });
        };

        // Unified SweetAlert2 Action Form Handler
        $(document).on('submit', '.swal-action-form', function(e) {
            e.preventDefault();
            const form = this;
            const title = $(form).data('swal-title') || 'Are you sure?';
            const text = $(form).data('swal-text') || 'This action cannot be undone.';
            const icon = $(form).data('swal-icon') || 'warning';

            window.nativeSwalConfirm(title, text, icon).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
    <!-- Search & Command Intelligence -->
    <script>
        "use strict";
        var routes = @json($routesData);
        var settingsData = Object.assign({}, @json($settings), @json($sideBarLinks));

        $(document).on('click', '.navbar-search .search-list', function(event){
            event.stopPropagation();
        });

        function getEmptyMessage(){
            return `<li class="text-muted">
                    <div class="empty-search text-center p-10">
                        <span class="material-symbols-rounded text-5xl text-slate-200 mb-4 block">search_off</span>
                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em]">No Intelligence Found</p>
                    </div>
                </li>`
        }
    </script>
    <script src="{{ asset('assets/admin/js/search.js') }}"></script>
    @include('partials.firebase_admin_script')

    <!-- Image Cropper Modal -->
    <div id="cropper-modal" class="fixed inset-0 z-[9999999] hidden flex items-center justify-center p-4 bg-black/95">
        <div class="w-full max-w-2xl bg-white dark:bg-[#121212] rounded-[2.5rem] shadow-2xl border border-slate-200 dark:border-white/10 overflow-hidden">
            <div class="flex items-center justify-between px-8 py-5 border-b border-slate-100 dark:border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-500/10 text-orange-500 flex items-center justify-center">
                        <span class="material-symbols-rounded">crop</span>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tight ">Crop Image</h3>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Adjust and align</p>
                    </div>
                </div>
                <button type="button" onclick="closeCropper()" class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-white/5 text-slate-400 transition-colors flex items-center justify-center">
                    <span class="material-symbols-rounded">close</span>
                </button>
            </div>
            <div class="p-6 bg-slate-50 dark:bg-black/40">
                <div class="max-h-[60vh] overflow-hidden rounded-2xl">
                    <img id="cropper-image" src="" alt="To crop" class="max-w-full block">
                </div>
            </div>
            <div class="flex items-center justify-between px-8 py-5 border-t border-slate-100 dark:border-white/5">
                <div class="flex items-center gap-2">
                    <button type="button" onclick="cropper.rotate(-90)" class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 flex items-center justify-center hover:scale-110 active:scale-95 transition-all">
                        <span class="material-symbols-rounded">rotate_left</span>
                    </button>
                    <button type="button" onclick="cropper.rotate(90)" class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 flex items-center justify-center hover:scale-110 active:scale-95 transition-all">
                        <span class="material-symbols-rounded">rotate_right</span>
                    </button>
                    <button type="button" onclick="cropper.setDragMode('move')" class="ml-4 w-12 h-12 rounded-2xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 flex items-center justify-center hover:scale-110 active:scale-95 transition-all">
                        <span class="material-symbols-rounded">open_with</span>
                    </button>
                    <button type="button" onclick="cropper.setDragMode('crop')" class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 flex items-center justify-center hover:scale-110 active:scale-95 transition-all">
                        <span class="material-symbols-rounded">crop_free</span>
                    </button>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="closeCropper()" class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">
                        Cancel
                    </button>
                    <button type="button" id="crop-apply-btn" class="px-10 py-4 orange-gradient-primary text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                        <span class="material-symbols-rounded text-sm">check</span>
                        Apply Crop
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let cropper;
        const cropperModal = document.getElementById('cropper-modal');
        const cropperImage = document.getElementById('cropper-image');
        let currentInput;
        let currentPreview;
        let onCropDone;

        window.openCropper = function(input, previewId, options = {}, callback = null) {
            if (!input.files || !input.files[0]) return;
            
            currentInput = input;
            currentPreview = document.getElementById(previewId);
            onCropDone = callback;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                cropperImage.src = e.target.result;
                cropperModal.classList.remove('hidden');
                
                if (cropper) cropper.destroy();
                
                const defaultOptions = {
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 0.8,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                };
                
                cropper = new Cropper(cropperImage, { ...defaultOptions, ...options });
            };
            reader.readAsDataURL(input.files[0]);
        };

        function closeCropper() {
            cropperModal.classList.add('hidden');
            if (cropper) cropper.destroy();
        }

        document.getElementById('crop-apply-btn').addEventListener('click', function() {
            if (!cropper) return;
            
            const canvas = cropper.getCroppedCanvas({
                maxWidth: 4096,
                maxHeight: 4096,
            });
            
            canvas.toBlob((blob) => {
                const fileName = currentInput.files[0].name;
                const file = new File([blob], fileName, { type: 'image/jpeg' });
                
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                currentInput.files = dataTransfer.files;
                
                if (currentPreview) {
                    currentPreview.src = URL.createObjectURL(blob);
                    currentPreview.classList.remove('opacity-0');
                    currentPreview.classList.add('opacity-100');
                }
                
                if (onCropDone) onCropDone(file, URL.createObjectURL(blob));
                
                closeCropper();
                
                window.notify('success', 'Image cropped successfully!');
            }, 'image/jpeg', 0.9);
        });
    </script>

    @stack('script')
</body>
</html>

