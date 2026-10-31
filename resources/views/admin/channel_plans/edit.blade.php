@extends('admin.layouts.app')

@section('title', 'Edit Channel Plan: ' . $plan->name)
@section('header_title', 'Edit Channel Plan')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-700">
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl">
        <div class="px-10 py-8 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02] flex items-center justify-between">
            <div>
                <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">Modify Plan: {{ $plan->name }}</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Update parameters for this channel subscription tier</p>
            </div>
            <span class="px-4 py-1.5 rounded-full {{ $plan->status ? 'bg-emerald-500/10 text-emerald-500' : 'bg-slate-100 text-slate-400' }} text-[10px] font-black uppercase tracking-widest">
                {{ $plan->status ? 'Active' : 'Disabled' }}
            </span>
        </div>

        <form action="{{ route('admin.channel-plans.update', $plan->id) }}" method="POST" class="p-10 space-y-10">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <x-input name="name" label="Plan Name" value="{{ $plan->name }}" required="true" icon="workspace_premium" hint="Title visible to creators and fans." />
                
                <x-input type="number" step="0.01" name="default_price" label="Suggested Price (₹)" value="{{ $plan->default_price }}" required="true" icon="payments" hint="Recommended starting price." />

                <div class="flex flex-col space-y-2">
                    <label class="text-[12px] font-black text-slate-900 dark:text-white uppercase leading-none flex items-center gap-2">
                        <span class="material-symbols-rounded text-[18px]">calendar_month</span>
                        Duration (Months)
                    </label>
                    <div class="relative group/select">
                        <select name="duration_months" required class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl pl-6 pr-14 text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:border-amber-500 transition-all appearance-none cursor-pointer">
                            <option value="1" {{ $plan->duration_months == 1 ? 'selected' : '' }}>1 Month (30 Days)</option>
                            <option value="2" {{ $plan->duration_months == 2 ? 'selected' : '' }}>2 Months (60 Days)</option>
                            <option value="3" {{ $plan->duration_months == 3 ? 'selected' : '' }}>3 Months (90 Days)</option>
                            <option value="6" {{ $plan->duration_months == 6 ? 'selected' : '' }}>6 Months (180 Days)</option>
                            <option value="12" {{ $plan->duration_months == 12 ? 'selected' : '' }}>12 Months / 1 Year (365 Days)</option>
                            <option value="24" {{ $plan->duration_months == 24 ? 'selected' : '' }}>24 Months / 2 Years (730 Days)</option>
                        </select>
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 group-hover/select:text-amber-500 transition-colors">
                            <span class="material-symbols-rounded">expand_more</span>
                        </div>
                    </div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Standard subscription period</p>
                </div>

                <x-input type="number" name="sort_order" label="Display Order" value="{{ $plan->sort_order }}" icon="sort" hint="Sequence priority in Creator Studio selection." />
            </div>

            <x-textarea name="description" label="Plan Description & Perks" value="{{ $plan->description }}" rows="4" icon="description" hint="Explains what this plan offers." />

            <div class="pt-8 flex items-center justify-end gap-6 border-t border-slate-100 dark:border-white/5">
                <a href="{{ route('admin.channel-plans.index') }}" class="h-16 px-10 rounded-[2rem] bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-white/30 font-black text-[11px] uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-white/10 transition-all flex items-center justify-center">Cancel</a>
                <button type="submit" class="h-16 px-12 rounded-[2rem] bg-gradient-to-r from-amber-500 to-orange-500 text-white font-black text-[12px] uppercase tracking-[0.2em] shadow-2xl shadow-orange-500/20 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-3">
                    <span>Save Changes</span>
                    <span class="material-symbols-rounded text-xl">save</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
