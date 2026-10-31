@extends('admin.layouts.app')

@section('title', 'Update Plan')
@section('header_title', 'Marketplace Plans')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-24">
    <div class="flex items-center justify-between px-6 lg:px-0">
        <div>
            <h3 class="text-3xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Update Plan</h3>
            <p class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3">Edit plan tier #{{ $userplan->id }}</p>
        </div>
        <a href="{{ route('admin.userplans.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-400 hover:text-rose-500 flex items-center justify-center transition-all shadow-sm active:scale-90">
            <span class="material-symbols-rounded text-xl">close</span>
        </a>
    </div>

    <form action="{{ route('admin.userplans.update', $userplan->id) }}" method="POST" 
          x-data="{ synching: false }" 
          @submit="synching = true"
          class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Panel: Pricing & Status -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                                <span class="material-symbols-rounded text-lg">payments</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Pricing</h3>
                        </div>

                        <div class="space-y-4">
                            <x-input type="number" name="plan_price" label="Price" value="{{ old('plan_price', $userplan->plan_price) }}" required="true" icon="currency_exchange" hint="Current cost of this plan." />
                            <x-select name="plan_duration" label="Plan Duration" required="true" icon="schedule" hint="Number of months plan stays active.">
                                <option value="1" {{ $userplan->plan_duration == 1 ? 'selected' : '' }}>1 Month</option>
                                <option value="3" {{ $userplan->plan_duration == 3 ? 'selected' : '' }}>3 Months</option>
                                <option value="6" {{ $userplan->plan_duration == 6 ? 'selected' : '' }}>6 Months</option>
                                <option value="12" {{ $userplan->plan_duration == 12 ? 'selected' : '' }}>Yearly (12 Months)</option>
                            </x-select>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-500">
                                <span class="material-symbols-rounded text-lg">star</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Status</h3>
                        </div>

                        <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5">
                            <div class="space-y-0.5">
                                <p class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-tight">Featured Plan</p>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Highlight in Marketplace</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_featured_plan" value="1" {{ $userplan->is_featured_plan ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 dark:bg-white/10 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-500"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="p-6 rounded-3xl bg-blue-50 dark:bg-white/5 border border-blue-200 dark:border-white/10 flex items-start gap-4">
                    <span class="material-symbols-rounded text-blue-500 text-lg">history</span>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">Last Updated</p>
                        <p class="text-[11px] font-medium text-slate-500 dark:text-white/30 leading-relaxed">{{ $userplan->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Plan Details -->
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                                <span class="material-symbols-rounded text-lg">military_tech</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Plan Details</h3>
                        </div>

                        <div class="space-y-6">
                            <x-input name="plan_name" label="Plan Name" value="{{ old('plan_name', $userplan->plan_name) }}" required="true" icon="label" hint="Public identifier for this membership tier." />
                            <x-textarea name="plan_content" label="Description / Perks" rows="10" required="true" icon="description" hint="Describe what users get with this plan.">{{ old('plan_content', $userplan->plan_content) }}</x-textarea>
                        </div>

                        <div class="flex pt-6 border-t border-slate-100 dark:border-white/5">
                            <button type="submit" :disabled="synching" 
                                    class="w-full h-16 rounded-2xl bg-blue-600 text-white text-sm font-bold uppercase tracking-widest hover:bg-blue-700 active:scale-95 transition-all flex items-center justify-center gap-3 disabled:opacity-50 disabled:grayscale disabled:cursor-not-allowed shadow-lg shadow-blue-500/20">
                                <span x-show="!synching" class="material-symbols-rounded">sync</span>
                                <span x-show="synching" class="material-symbols-rounded animate-spin">sync</span>
                                <span x-text="synching ? 'Updating...' : 'Update Plan'">Update Plan</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

