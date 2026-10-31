@extends('admin.layouts.app')

@section('title', 'Refine OTT Membership Tier')
@section('header_title', 'Edit OTT Plan')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-700">
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl">
        <div class="px-10 py-8 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02]">
            <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter ">Refine Parameters</h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Adjust the configuration for: {{ $plan->name }}</p>
        </div>

        <form action="{{ route('admin.ott-plans.update', $plan->id) }}" method="POST" class="p-10 space-y-12">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <x-input name="name" label="Plan Designation" value="{{ $plan->name }}" required="true" icon="military_tech" hint="The public title of this membership tier." />
                
                <x-input type="number" name="price" label="Access Valuation" value="{{ getAmount($plan->price) }}" required="true" icon="payments" hint="The monetary requirement for this access level." />

                <div class="flex flex-col space-y-2">
                    <label class="text-[12px] font-black text-slate-900 dark:text-white uppercase leading-none flex items-center gap-2">
                        <span class="material-symbols-rounded text-[18px]">schedule</span>
                        Validity Span
                    </label>
                    <div class="relative group/select">
                        <select name="duration" required class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl pl-6 pr-14 text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:border-indigo-500 transition-all appearance-none cursor-pointer" style="-webkit-appearance: none !important; -moz-appearance: none !important; appearance: none !important; background-image: none !important;">
                            <option value="1" {{ $plan->duration == 1 ? 'selected' : '' }}>1 Month</option>
                            <option value="2" {{ $plan->duration == 2 ? 'selected' : '' }}>2 Months</option>
                            <option value="3" {{ $plan->duration == 3 ? 'selected' : '' }}>3 Months</option>
                            <option value="6" {{ $plan->duration == 6 ? 'selected' : '' }}>6 Months</option>
                            <option value="12" {{ $plan->duration == 12 ? 'selected' : '' }}>Yearly</option>
                            <option value="0" {{ $plan->duration == 0 ? 'selected' : '' }}>Lifetime</option>
                        </select>
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 group-hover/select:text-indigo-500 transition-colors">
                            <span class="material-symbols-rounded">expand_more</span>
                        </div>
                    </div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Select the duration of OTT membership</p>
                </div>

                <div class="flex items-center gap-6 p-8 bg-indigo-500/[0.03] dark:bg-white/[0.02] rounded-[2.5rem] border border-indigo-500/10 dark:border-white/5 group hover:border-indigo-500/30 transition-all">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-500 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-rounded text-2xl">workspace_premium</span>
                    </div>
                    <div class="flex-grow">
                        <p class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-tight ">OTT Premium Access</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Enable global premium video streaming privileges</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="ott_access" value="1" class="sr-only peer" {{ $plan->ott_access ? 'checked' : '' }}>
                        <div class="w-14 h-8 bg-slate-200 dark:bg-white/5 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-indigo-600 shadow-inner"></div>
                    </label>
                </div>
            </div>

            <x-textarea name="description" label="Plan Features" rows="5" icon="description" hint="Detailed information visible to users on the pricing page.">{{ $plan->description }}</x-textarea>

            <div class="pt-8 flex items-center justify-end gap-6 border-t border-slate-100 dark:border-white/5">
                <a href="{{ route('admin.ott-plans.index') }}" class="h-16 px-10 rounded-[2rem] bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-white/30 font-black text-[11px] uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-white/10 transition-all flex items-center justify-center">Cancel</a>
                <button type="submit" class="h-16 px-12 rounded-[2rem] bg-indigo-600 text-white font-black text-[12px] uppercase tracking-[0.2em] shadow-2xl shadow-indigo-600/20 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-3">
                    <span>Synchronize Tier</span>
                    <span class="material-symbols-rounded text-xl">sync</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

