@extends('admin.layouts.app')

@section('title', 'Update Plan')
@section('header_title', 'Marketplace Plans')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-24">
    <div class="flex items-center justify-between px-6 lg:px-0">
        <div>
            <h3 class="text-3xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Update Creator Plan</h3>
            <p class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3">Edit plan tier #{{ $plan->id }}</p>
        </div>
        <a href="{{ route('admin.plans.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-400 hover:text-rose-500 flex items-center justify-center transition-all shadow-sm active:scale-90">
            <span class="material-symbols-rounded text-xl">close</span>
        </a>
    </div>

    <form action="{{ route('admin.plans.update', $plan->id) }}" method="POST" 
          x-data="{ synching: false }" @submit="synching = true"
          class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Panel: Pricing & Access -->
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
                            <x-input type="number" step="1" name="price" label="Price" value="{{ old('price', $plan->price) }}" required="true" icon="currency_exchange" />
                            
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Duration</label>
                                <div class="relative group/select">
                                    <select name="duration" required class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl pl-5 pr-12 text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all appearance-none cursor-pointer">
                                        <option value="1" {{ $plan->duration == 1 ? 'selected' : '' }}>1 Month</option>
                                        <option value="2" {{ $plan->duration == 2 ? 'selected' : '' }}>2 Months</option>
                                        <option value="3" {{ $plan->duration == 3 ? 'selected' : '' }}>3 Months</option>
                                        <option value="6" {{ $plan->duration == 6 ? 'selected' : '' }}>6 Months</option>
                                        <option value="12" {{ $plan->duration == 12 ? 'selected' : '' }}>Yearly</option>
                                        <option value="0" {{ $plan->duration == 0 ? 'selected' : '' }}>Lifetime</option>
                                    </select>
                                    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                        <span class="material-symbols-rounded">expand_more</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Toggles Card -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm space-y-4">
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5">
                        <div class="space-y-0.5">
                            <p class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-tight">Premium Access</p>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Watch/Upload Premium</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="video_access" value="1" {{ $plan->video_access ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 dark:bg-white/10 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5">
                        <div class="space-y-0.5">
                            <p class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-tight">Contact Access</p>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Message Talents</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="contact_access" value="1" {{ $plan->contact_access ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 dark:bg-white/10 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5">
                        <div class="space-y-0.5">
                            <p class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-tight">Priority Status</p>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Feature in Marketplace</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_featured_plan" value="1" {{ $plan->is_featured_plan ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 dark:bg-white/10 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-500"></div>
                        </label>
                    </div>
                </div>

                <div class="p-6 rounded-3xl bg-blue-50 dark:bg-white/5 border border-blue-200 dark:border-white/10 flex items-start gap-4">
                    <span class="material-symbols-rounded text-blue-500 text-lg">history</span>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">Last Updated</p>
                        <p class="text-[11px] font-medium text-slate-500 dark:text-white/30 leading-relaxed">{{ $plan->updated_at->diffForHumans() }}</p>
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
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Plan Info</h3>
                        </div>

                        <div class="space-y-6">
                            <x-input name="name" label="Plan Name" value="{{ old('name', $plan->name) }}" required="true" icon="label" />
                            <x-textarea name="description" label="Features & Narrative" rows="10" required="true" icon="description">{{ old('description', $plan->description) }}</x-textarea>
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

