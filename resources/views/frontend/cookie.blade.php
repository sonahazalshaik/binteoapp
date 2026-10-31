<x-app-layout>
    <div class="py-20 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen">
        <div class="max-w-[1000px] mx-auto px-6">
            <div class="text-center mb-16">
                 <div class="w-20 h-20 rounded-[2rem] bg-red-500/10 flex items-center justify-center mx-auto mb-8">
                    <span class="material-symbols-rounded text-4xl text-red-600">cookie</span>
                </div>
                <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-4">{{ __($pageTitle) }}</h1>
                <p class="text-gray-500 dark:text-gray-400 font-bold text-xs uppercase tracking-[0.2em]">Privacy & Transparency Policy</p>
            </div>

            <div class="bg-white dark:bg-[#1A1A1A] rounded-[3rem] p-12 border border-gray-100 dark:border-white/5 shadow-sm text-gray-700 dark:text-gray-300 leading-[1.8] font-medium article-content">
                @php
                    echo $cookie->data_values->description;
                @endphp
            </div>
            
            <div class="mt-12 text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-red-500 transition-colors">
                    <span class="material-symbols-rounded text-lg">arrow_back</span>
                    Return to Home
                </a>
            </div>
        </div>
    </div>
    
    @push('style')
    <style>
        .article-content h1, .article-content h2, .article-content h3 {
            color: #111;
            font-weight: 900;
            margin-top: 2.5rem;
            margin-bottom: 1.25rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .dark .article-content h1, .dark .article-content h2, .dark .article-content h3 {
            color: #fff;
        }
        .article-content p {
            margin-bottom: 1.5rem;
        }
    </style>
    @endpush
</x-app-layout>
