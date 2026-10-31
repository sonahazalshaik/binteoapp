@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-[#0F0F0F] pt-20 pb-24 transition-colors duration-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-20">
            <h1 class="text-4xl sm:text-6xl font-black text-gray-900 dark:text-white uppercase tracking-tighter mb-6">{{ $pageTitle }}</h1>
            <p class="text-gray-500 dark:text-gray-400 font-bold text-sm tracking-[0.3em] uppercase">Select your path to exclusive content</p>
        </div>

        @if(false)
        <!-- Plans Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 sm:gap-12">
            @forelse($plans as $plan)
                <div class="relative group">
                    <!-- Glass Background -->
                    <div class="absolute inset-0 bg-blue-600/5 dark:bg-red-600/5 rounded-[3rem] blur-2xl group-hover:bg-blue-600/10 dark:group-hover:bg-red-600/10 transition-all duration-700 -z-10"></div>
                    
                    <div class="bg-white/80 dark:bg-[#1E1E1E]/80 backdrop-blur-xl rounded-[3rem] p-10 border border-gray-100 dark:border-white/5 shadow-2xl shadow-gray-200/50 dark:shadow-none hover:translate-y-[-10px] transition-all duration-500 relative overflow-hidden h-full flex flex-col">
                        
                        <!-- Price Badge -->
                        <div class="absolute top-0 right-0 py-4 px-8 bg-red-600 text-white rounded-bl-[2rem] font-black text-lg shadow-lg">
                            ${{ number_format($plan->price, 0) }}
                        </div>

                        <h3 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tight mb-4 pr-16">{{ $plan->name }}</h3>
                        
                        <div class="mb-8">
                            <span class="text-[10px] font-black bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400 px-3 py-1.5 rounded-full uppercase tracking-widest">
                                Valid for {{ $plan->duration }} Days
                            </span>
                        </div>

                        <div class="flex-grow">
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium leading-relaxed">
                                {!! nl2br(e($plan->description)) ?? 'Unlock exclusive creator content, ad-free streaming, and priority access to new releases.' !!}
                            </p>
                        </div>

                        <div class="mt-12 pt-8 border-t border-gray-100 dark:border-white/5">
                            <a href="#" class="block w-full py-5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-2xl text-center font-black uppercase tracking-widest text-[13px] hover:bg-black dark:hover:bg-gray-100 transition-all active:scale-95 shadow-xl shadow-gray-200/50 dark:shadow-none">
                                Subscribe Now
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-32 text-center">
                    <svg class="w-20 h-20 mx-auto text-gray-300 dark:text-gray-700 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <h3 class="text-xl font-bold text-gray-400 uppercase tracking-widest">No Active Tiers Detected</h3>
                </div>
            @endforelse
        </div>

        <div class="mt-20">
            {{ $plans->links() }}
        </div>
        @endif
        
        <div class="py-24 text-center rounded-[4rem] border-4 border-dashed border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/[0.01]">
            <div class="w-24 h-24 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-rounded text-5xl text-gray-300 dark:text-white/10">rocket_launch</span>
            </div>
            <h3 class="text-xl font-black text-gray-400 uppercase tracking-widest">Free Access Active</h3>
            <p class="text-xs font-bold text-gray-500 mt-2">All creator features are currently open for our early partners.</p>
        </div>
    </div>
</div>
@endsection
