<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Synchronization Required</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #000; overflow: hidden; }
        .gradient-text {
            background: linear-gradient(135deg, #FF8A00 0%, #FF5200 50%, #E52E71 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        .glow {
            box-shadow: 0 0 50px rgba(255, 82, 0, 0.2);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">
    <!-- Background Accents -->
    <div class="fixed top-[-10%] left-[-10%] w-[40%] h-[40%] bg-orange-600/10 blur-[120px] rounded-full"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-rose-600/10 blur-[120px] rounded-full"></div>

    <div class="relative w-full max-w-lg text-center animate-in fade-in zoom-in duration-1000">
        <!-- Icon Container -->
        <div class="relative inline-block mb-12">
            <div class="absolute inset-0 bg-orange-600/20 blur-3xl rounded-full animate-pulse"></div>
            <div class="relative w-32 h-32 bg-white/5 border border-white/10 backdrop-blur-2xl rounded-[2.5rem] flex items-center justify-center animate-float glow">
                <span class="material-symbols-rounded text-6xl text-orange-500 fill-1">sync</span>
            </div>
        </div>

        <!-- Text Content -->
        <h1 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter leading-none mb-6">
            System <br> 
            <span class="gradient-text">Synchronization</span> <br>
            Required
        </h1>
        
        <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.3em] mb-12 leading-relaxed max-w-sm mx-auto opacity-60">
            A critical platform update has been deployed. Please synchronize your session to continue using our premium services.
        </p>

        <!-- Action Button -->
        <a href="{{ route('update.now') }}" 
           class="group relative inline-flex items-center gap-4 px-12 py-5 bg-white text-black rounded-full overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-2xl shadow-white/10">
            <div class="absolute inset-0 bg-gradient-to-r from-orange-500 to-rose-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <span class="relative z-10 text-[10px] font-black uppercase tracking-[0.2em] group-hover:text-white transition-colors">Update & Sync Now</span>
            <span class="relative z-10 material-symbols-rounded text-lg group-hover:text-white group-hover:translate-x-1 transition-all">bolt</span>
        </a>

        <!-- Footer Info -->
        <div class="mt-20 flex flex-col items-center gap-4">
            <div class="h-[1px] w-12 bg-white/10"></div>
            <p class="text-[8px] font-black text-white/20 uppercase tracking-[0.5em]">Platform v{{ gs('app_version') }}</p>
        </div>
    </div>

    <!-- Prevent UI interaction behind wall -->
    <div class="fixed inset-0 z-[-1] pointer-events-none"></div>
</body>
</html>

