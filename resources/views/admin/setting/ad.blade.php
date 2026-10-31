@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12">
            <form method="POST">
                @csrf
                
                <!-- Page Header & Global Switch -->
                <div class="mb-10 flex flex-col md:flex-row items-center justify-between gap-6 bg-white dark:bg-[#1A1A1A] p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-sm">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center shrink-0">
                            <span class="material-symbols-rounded text-3xl">campaign</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Advertisement Engine</h2>
                            <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mt-1">Configure global ad delivery and monetization parameters</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="form-group mb-0">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2 px-2 text-center">Ads Module Mode</label>
                            <select name="ads_module" class="form-control select2 h-14 rounded-2xl border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-white/5 px-6 font-bold text-sm" required>
                                <option value="0" @selected(gs('ads_module') == 0)>@lang('Standard Mode')</option>
                                <option value="1" @selected(gs('ads_module') == 1)>@lang('Advanced Mode')</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                    
                    <!-- Ad Frequency & Delivery -->
                    <div class="xl:col-span-1 space-y-8">
                        <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] p-10 border border-gray-100 dark:border-white/5 shadow-sm h-full">
                            <div class="flex items-center gap-4 mb-10">
                                <div class="w-12 h-12 rounded-xl bg-orange-500/10 text-orange-500 flex items-center justify-center">
                                    <span class="material-symbols-rounded">speed</span>
                                </div>
                                <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-widest">Delivery Rules</h3>
                            </div>

                            <div class="space-y-8">
                                <div class="form-group">
                                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3 block px-2">Ads per minute</label>
                                    <div class="relative">
                                        <input class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 outline-none transition-all" name="ad_views" type="number" step="any" value="{{ gs('ad_config')?->ad_views ?? 0 }}" required>
                                        <span class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-black uppercase">Views</span>
                                    </div>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-2 px-2">How many ads to show in a single minute</p>
                                </div>

                                <div class="form-group">
                                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3 block px-2">Minute</label>
                                    <div class="relative">
                                        <input class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 outline-none transition-all" name="per_minute" type="number" value="{{ gs('ad_config')?->per_minute ?? 0 }}" required>
                                        <span class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-black uppercase">Min</span>
                                    </div>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-2 px-2">Ad playback frequency in minutes</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Revenue & Payout Config -->
                    <div class="xl:col-span-2">
                        <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] p-10 border border-gray-100 dark:border-white/5 shadow-sm h-full">
                            <div class="flex items-center justify-between mb-10">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                                        <span class="material-symbols-rounded">payments</span>
                                    </div>
                                    <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-widest">Revenue Distribution</h3>
                                </div>
                                <span class="px-4 py-1.5 bg-emerald-500/10 text-emerald-500 rounded-full text-[10px] font-black uppercase tracking-widest border border-emerald-500/20">Active Currency: {{ gs('cur_text') }}</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
                                <!-- Spent Section -->
                                <div class="space-y-8">
                                    <div class="pb-4 border-b border-gray-100 dark:border-white/5">
                                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Ads Pricing</h4>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3 block px-2">Spent Per Click</label>
                                        <div class="relative">
                                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 font-bold">{{ gs('cur_sym') }}</span>
                                            <input class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 rounded-2xl pl-12 pr-6 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none transition-all" name="per_click_spent" type="number" step="any" value="{{ getAmount(gs('per_click_spent')) }}" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3 block px-2">Spent Per Impression</label>
                                        <div class="relative">
                                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 font-bold">{{ gs('cur_sym') }}</span>
                                            <input class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 rounded-2xl pl-12 pr-6 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none transition-all" name="per_impression_spent" type="number" step="any" value="{{ getAmount(gs('per_impression_spent')) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Earn Section -->
                                <div class="space-y-8">
                                    <div class="pb-4 border-b border-gray-100 dark:border-white/5">
                                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Earn from Ads</h4>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3 block px-2">Earn Per Click</label>
                                        <div class="relative">
                                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 font-bold">{{ gs('cur_sym') }}</span>
                                            <input class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 rounded-2xl pl-12 pr-6 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none transition-all" name="per_click_earn" type="number" step="any" value="{{ getAmount(gs('per_click_earn')) }}" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3 block px-2">Earn Per Impression</label>
                                        <div class="relative">
                                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 font-bold">{{ gs('cur_sym') }}</span>
                                            <input class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 rounded-2xl pl-12 pr-6 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none transition-all" name="per_impression_earn" type="number" step="any" value="{{ getAmount(gs('per_impression_earn')) }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Advanced Settings Section -->
                @if (gs('ads_module'))
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8 animate-in slide-in-from-bottom-5 duration-700">
                    <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] p-10 border border-gray-100 dark:border-white/5 shadow-sm">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-500 flex items-center justify-center">
                                <span class="material-symbols-rounded">analytics</span>
                            </div>
                            <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-widest">Advanced Metrics</h3>
                        </div>
                        <div class="space-y-6">
                            <div class="form-group">
                                <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3 block px-2">Cost Per Reach Unit</label>
                                <div class="relative">
                                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 font-bold">{{ gs('cur_sym') }}</span>
                                    <input class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 rounded-2xl pl-12 pr-6 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all" name="ad_reach" type="number" step="any" value="{{ getAmount(gs('ad_reach')) }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3 block px-2">Cost Per Engagement</label>
                                <div class="relative">
                                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 font-bold">{{ gs('cur_sym') }}</span>
                                    <input class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 rounded-2xl pl-12 pr-6 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all" name="ad_engagement" type="number" step="any" value="{{ getAmount(gs('ad_engagement')) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] p-10 border border-gray-100 dark:border-white/5 shadow-sm">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center">
                                <span class="material-symbols-rounded">verified</span>
                            </div>
                            <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-widest">Trust & Safety</h3>
                        </div>
                        <div class="form-group">
                            <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-4 block px-2">Ad Approval Protocol</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="cursor-pointer">
                                    <input type="radio" name="ads_auto_approve" value="1" @checked(gs('ads_auto_approve') == \App\Constants\Status::YES) class="peer hidden">
                                    <div class="p-6 rounded-[1.5rem] border-2 border-gray-100 dark:border-white/5 peer-checked:border-blue-500 peer-checked:bg-blue-500/5 transition-all text-center">
                                        <span class="material-symbols-rounded text-2xl block mb-2 text-gray-400 peer-checked:text-blue-500">bolt</span>
                                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">Auto Approve</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="ads_auto_approve" value="0" @checked(gs('ads_auto_approve') == \App\Constants\Status::NO) class="peer hidden">
                                    <div class="p-6 rounded-[1.5rem] border-2 border-gray-100 dark:border-white/5 peer-checked:border-amber-500 peer-checked:bg-amber-500/5 transition-all text-center">
                                        <span class="material-symbols-rounded text-2xl block mb-2 text-gray-400 peer-checked:text-amber-500">person_search</span>
                                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">Manual Audit</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Submit Action -->
                <div class="mt-12 mb-20">
                    <button type="submit" class="w-full md:w-auto px-20 py-6 bg-gray-900 dark:bg-white text-white dark:text-black rounded-[2rem] font-black text-sm uppercase tracking-[0.3em] shadow-2xl hover:scale-[1.02] transition-all active:scale-95 flex items-center justify-center gap-4 group">
                        <span class="material-symbols-rounded text-xl group-hover:rotate-12 transition-transform">save</span>
                        Save System Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('style')
<style>
    .select2-container--default .select2-selection--single {
        background-color: transparent !important;
        border: none !important;
        height: 100% !important;
        padding: 0 1rem !important;
        display: flex !important;
        align-items: center !important;
        font-weight: 800 !important;
    }
    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 0 !important;
        color: inherit !important;
    }
</style>
@endpush
