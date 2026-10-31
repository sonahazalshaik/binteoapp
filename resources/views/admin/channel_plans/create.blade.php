@extends('admin.layouts.app')

@section('title', 'Create Channel Plan Tier')
@section('header_title', 'New Channel Plan')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-700">
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl">
        <div class="px-10 py-8 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02]">
            <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">Channel Plan Tier Setup</h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Configure a new subscription tier that creators can offer to their subscribers</p>
        </div>

        <form action="{{ route('admin.channel-plans.store') }}" method="POST" class="p-10 space-y-10">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <x-input name="name" label="Plan Name" placeholder="e.g. 1 Month Pass, 3 Months Pass" required="true" icon="workspace_premium" hint="Title visible to creators in their Studio and fans on the channel." />
                
                <x-input type="number" step="0.01" name="default_price" label="Suggested Starting Price (₹)" placeholder="49.00" required="true" icon="payments" hint="Default price recommended to creators (creators can customize this)." />

                <div class="flex flex-col space-y-2">
                    <label class="text-[12px] font-black text-slate-900 dark:text-white uppercase leading-none flex items-center gap-2">
                        <span class="material-symbols-rounded text-[18px]">calendar_month</span>
                        Duration (Months)
                    </label>
                    <div class="relative group/select">
                        <select name="duration_months" required class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl pl-6 pr-14 text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:border-amber-500 transition-all appearance-none cursor-pointer">
                            <option value="1">1 Month (30 Days)</option>
                            <option value="2">2 Months (60 Days)</option>
                            <option value="3">3 Months (90 Days)</option>
                            <option value="6">6 Months (180 Days)</option>
                            <option value="12">12 Months / 1 Year (365 Days)</option>
                            <option value="24">24 Months / 2 Years (730 Days)</option>
                        </select>
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 group-hover/select:text-amber-500 transition-colors">
                            <span class="material-symbols-rounded">expand_more</span>
                        </div>
                    </div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Standard subscription period</p>
                </div>

                <x-input type="number" name="sort_order" label="Display Order" placeholder="1" icon="sort" hint="Sequence priority in Creator Studio selection." />
            </div>

            <x-textarea name="description" label="Plan Description & Perks" placeholder="Describe the access perks granted to subscribers of this channel plan..." rows="4" icon="description" hint="Explains what this plan offers (e.g. exclusive 4K premium videos, badges)." />

            <div class="pt-8 flex items-center justify-end gap-6 border-t border-slate-100 dark:border-white/5">
                <a href="{{ route('admin.channel-plans.index') }}" class="h-16 px-10 rounded-[2rem] bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-white/30 font-black text-[11px] uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-white/10 transition-all flex items-center justify-center">Cancel</a>
                <button type="submit" class="h-16 px-12 rounded-[2rem] bg-gradient-to-r from-amber-500 to-orange-500 text-white font-black text-[12px] uppercase tracking-[0.2em] shadow-2xl shadow-orange-500/20 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-3">
                    <span>Publish Plan Tier</span>
                    <span class="material-symbols-rounded text-xl">loyalty</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
