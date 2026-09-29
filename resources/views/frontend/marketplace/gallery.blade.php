@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white dark:bg-[#0A0A0A] transition-colors duration-500 pb-20 pt-20">
    <div class="max-w-7xl mx-auto px-6">
        <!-- Premium Header -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-8 mb-16">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-full text-[10px] font-black uppercase tracking-[0.2em] mb-6">
                    <span class="material-symbols-rounded text-sm">photo_library</span>
                    Creative Portfolios
                </div>
                <h1 class="text-4xl md:text-6xl font-black tracking-tight dark:text-white">Master Collection</h1>
                <p class="text-gray-400 font-medium mt-4 text-lg">Browse our entire network of professional creator assets and visual portfolios.</p>
            </div>
            <a href="{{ route('marketplace.index') }}" class="group relative flex items-center gap-3 px-8 py-3.5 bg-white dark:bg-white/5 border border-indigo-100 dark:border-white/10 rounded-full text-xs font-black uppercase tracking-[0.2em] text-slate-800 dark:text-white shadow-xl shadow-indigo-500/5 hover:shadow-indigo-500/20 hover:border-indigo-500/50 transition-all duration-500 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <span class="material-symbols-rounded text-lg relative z-10 group-hover:-translate-x-1.5 transition-transform duration-500 text-indigo-500">arrow_back</span>
                <span class="relative z-10">Back to Marketplace</span>
            </a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8">
            @foreach($galleryItems as $gallery)
                <a href="{{ route('marketplace.portfolio.short', ['slug' => $gallery->marketplace->slug]) }}" 
                   class="group relative aspect-[3/4] rounded-[2rem] md:rounded-[3rem] overflow-hidden bg-gray-100 dark:bg-white/5 shadow-xl border border-gray-100 dark:border-white/5 transition-all duration-700 block">
                    <!-- High Fidelity Image -->
                    <img src="{{ $gallery->photoUrl() }}" 
                         alt="{{ $gallery->marketplace->name }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000 ease-out">

                    <!-- Permanent Information Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex flex-col justify-end p-4 md:p-10">
                        <div class="flex flex-col">
                            <div class="flex items-center gap-2 md:gap-3 mb-3 md:mb-4">
                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full overflow-hidden border-2 border-white/20 flex-shrink-0 bg-gray-100 dark:bg-white/5">
                                    @if($gallery->marketplace->image)
                                        <img src="{{ $gallery->marketplace->photoUrl() }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center" style="background: {{ $gallery->marketplace->getAvatarColor() }}">
                                            <span class="text-[8px] md:text-[10px] font-black text-white ">{{ $gallery->marketplace->getInitials() }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[8px] md:text-[10px] font-black uppercase tracking-widest text-indigo-400 truncate">Creator</p>
                                    <p class="text-xs md:text-sm font-bold text-white truncate">{{ $gallery->marketplace->name }}</p>
                                </div>
                            </div>
                            
                            <div class="h-px bg-white/10 w-full mb-3 md:mb-6"></div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-[7px] md:text-[9px] font-black text-white/40 uppercase tracking-[0.1em] md:tracking-[0.2em] mb-1">Portfolio</span>
                                    <span class="text-[9px] md:text-xs font-bold text-white uppercase">{{ ucfirst($gallery->marketplace->type) }}</span>
                                </div>
                                <div class="w-8 h-8 md:w-12 md:h-12 rounded-xl md:rounded-2xl bg-white/10 backdrop-blur-xl border border-white/10 flex items-center justify-center text-white group-hover:bg-white group-hover:text-black transition-all">
                                    <span class="material-symbols-rounded text-base md:text-xl">arrow_forward</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Custom Pagination -->
        <div class="mt-20">
            {{ $galleryItems->links() }}
        </div>
    </div>
</div>

<style>
    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }
    .page-item .page-link {
        padding: 1rem 1.5rem;
        border-radius: 1rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.05);
        color: #94a3b8;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.1em;
        transition: all 0.3s ease;
    }
    .page-item.active .page-link {
        background: #4f46e5;
        color: white;
        box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.3);
    }
    .page-item:hover .page-link {
        border-color: #4f46e5;
        color: #4f46e5;
    }
</style>
@endsection

