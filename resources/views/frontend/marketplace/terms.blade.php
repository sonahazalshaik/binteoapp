@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#fafafa] dark:bg-[#0A0A0A] py-12 px-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-[#151515] rounded-[2.5rem] shadow-[0_10px_60px_rgba(0,0,0,0.04)] border border-slate-100 dark:border-white/5 p-10 md:p-16">
            <div class="flex items-center gap-4 mb-12">
                <div class="w-12 h-12 bg-orange-500/10 rounded-2xl flex items-center justify-center">
                    <span class="material-symbols-rounded text-orange-500">gavel</span>
                </div>
                <div>
                    <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white">
                        {{ __($terms->data_values->title) }}
                    </h1>
                    <p class="text-slate-400 font-medium text-xs uppercase tracking-widest mt-1">
                        Professional Standards & Agreements
                    </p>
                </div>
            </div>

            <div class="prose prose-slate dark:prose-invert max-w-none 
                        prose-headings:font-black prose-headings:tracking-tight 
                        prose-h3:text-xl prose-h3:text-slate-900 dark:prose-h3:text-white prose-h3:mt-8
                        prose-h4:text-sm prose-h4:font-black prose-h4:uppercase prose-h4:tracking-widest prose-h4:text-slate-700 dark:prose-h4:text-slate-200
                        prose-p:text-slate-500 dark:prose-p:text-slate-400 prose-p:leading-relaxed prose-p:font-medium
                        prose-li:text-slate-500 dark:prose-li:text-slate-400 prose-li:text-sm prose-li:font-medium
                        prose-strong:text-slate-900 dark:prose-strong:text-white">
                
                {!! $terms->data_values->content !!}

                <div class="pt-10 mt-12 border-t border-slate-50 dark:border-white/5 flex flex-col sm:flex-row items-center justify-between gap-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-center sm:text-left">
                        © {{ date('Y') }} {{ gs('site_name') }} Marketplace Protocol
                    </p>
                    <a href="{{ route('marketplace.register') }}" class="px-8 py-3 bg-slate-900 dark:bg-white text-white dark:text-black rounded-xl font-black text-[10px] uppercase tracking-widest hover:scale-105 transition-all">
                        Back to Registration
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
