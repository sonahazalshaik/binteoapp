@extends('admin.layouts.app')

@section('panel')
<div class="max-w-7xl mx-auto pb-20 animate-in fade-in duration-700">
    
    <form method="POST">
        @csrf

        <!-- Section 1: General Setting -->
        <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30">
                <h4 class="text-lg font-bold text-slate-800">@lang('General Setting')</h4>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-6 mb-8">
                    <!-- Site Title -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Site Title') <span class="text-rose-500">*</span></label>
                        <input type="text" name="site_name" value="{{ gs('site_name') }}" required
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 outline-none transition-all">
                    </div>



                    <!-- Currency -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Currency') <span class="text-rose-500">*</span></label>
                        <input type="text" name="cur_text" value="{{ gs('cur_text') }}" required
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 outline-none transition-all">
                    </div>

                    <!-- Currency Symbol -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Currency Symbol') <span class="text-rose-500">*</span></label>
                        <input type="text" name="cur_sym" value="{{ gs('cur_sym') }}" required
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 outline-none transition-all">
                    </div>

                    <!-- App Versioning -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('App Version')</label>
                        <input type="text" name="app_version" value="{{ gs('app_version') }}" required
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 outline-none transition-all">
                    </div>

                    <!-- PPV Commission -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('PPV Creator Commission %')</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="ppv_creator_commission_percent" value="{{ gs('ppv_creator_commission_percent') ?? 70.00 }}" required
                                class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 outline-none transition-all pr-8">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">%</span>
                        </div>
                    </div>

                    <!-- PPV Teaser Duration -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('PPV Teaser Duration')</label>
                        <div class="relative">
                            <input type="number" step="1" name="ppv_teaser_duration_seconds" value="{{ gs('ppv_teaser_duration_seconds') ?? 30 }}" required
                                class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 outline-none transition-all pr-10">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">sec</span>
                        </div>
                    </div>

                    <!-- Force Update -->
                    <div x-data="{ enabled: {{ gs('force_update') ? 'true' : 'false' }} }" 
                         class="flex items-center justify-between p-4 rounded-xl border transition-all duration-300 cursor-pointer h-[46px] mt-auto"
                         :class="enabled ? 'bg-orange-50/50 border-orange-200' : 'bg-slate-50/50 border-slate-200'"
                         @click="enabled = !enabled">
                        <div>
                            <label class="text-[10px] font-bold text-slate-700 uppercase tracking-wider block pointer-events-none">@lang('Force Update')</label>
                        </div>
                        <div class="relative w-10 h-5 rounded-full p-1 transition-colors duration-300 ease-in-out shadow-inner shrink-0"
                             :class="enabled ? 'bg-orange-500' : 'bg-slate-300'">
                            <input type="checkbox" name="force_update" x-model="enabled" class="hidden">
                            <div class="w-3 h-3 bg-white rounded-full shadow-sm transform transition-transform duration-300 ease-in-out"
                                 :class="enabled ? 'translate-x-5' : 'translate-x-0'"></div>
                        </div>
                    </div>
                </div>



                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                    <!-- Registration Status Toggle -->
                    <div x-data="{ enabled: {{ gs('registration') ? 'true' : 'false' }} }" 
                         class="flex items-center justify-between p-4 rounded-xl border transition-all duration-300 cursor-pointer h-[76px]"
                         :class="enabled ? 'bg-indigo-50/50 border-indigo-200' : 'bg-slate-50/50 border-slate-200'"
                         @click="enabled = !enabled">
                        <div>
                            <label class="text-[11px] font-bold text-slate-700 uppercase tracking-wider block pointer-events-none">@lang('User Registration')</label>
                            <p class="text-[10px] text-slate-500 mt-1 font-medium pointer-events-none">Allow new users to create accounts.</p>
                        </div>
                        <div class="relative w-12 h-6 rounded-full p-1 transition-colors duration-300 ease-in-out shadow-inner shrink-0"
                             :class="enabled ? 'bg-indigo-500' : 'bg-slate-300'">
                            <input type="checkbox" name="registration" x-model="enabled" class="hidden">
                            <div class="w-4 h-4 bg-white rounded-full shadow-sm transform transition-transform duration-300 ease-in-out"
                                 :class="enabled ? 'translate-x-6' : 'translate-x-0'"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mini OTT Mode Settings — GeneralSettings input field (visible) -->
        <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden mb-8" x-data="{ miniOttMode: '{{ (gs('mini_ott_status') === null || gs('mini_ott_status') == 1) ? '1' : '0' }}' }">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" :class="miniOttMode == '1' ? 'bg-emerald-100 text-emerald-600' : 'bg-orange-100 text-orange-600'">
                    <span class="material-symbols-rounded text-xl" x-text="miniOttMode == '1' ? 'movie' : 'hourglass_empty'"></span>
                </div>
                <div class="flex-1">
                    <h4 class="text-lg font-bold text-slate-800">@lang('Mini OTT Settings')</h4>
                    <p class="text-[11px] font-medium text-slate-500">@lang('Control Mini OTT visibility across frontend view files')</p>
                </div>
                <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border" :class="miniOttMode == '1' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-orange-50 text-orange-600 border-orange-200'" x-text="miniOttMode == '1' ? 'Active' : 'Coming Soon'"></span>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Mini OTT Mode') <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select name="mini_ott_status" x-model="miniOttMode" required class="w-full bg-white border border-slate-200 rounded-lg py-3.5 px-4 pr-10 text-sm font-bold text-slate-700 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 outline-none transition-all appearance-none">
                                <option value="1">@lang('Active — Show full Mini OTT experience')</option>
                                <option value="0">@lang('Coming Soon — Show native popup & placeholder everywhere')</option>
                            </select>
                            <span class="material-symbols-rounded absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xl">expand_more</span>
                        </div>
                        <p class="text-[10px] font-medium leading-relaxed" :class="miniOttMode == '1' ? 'text-emerald-600' : 'text-orange-600'" x-text="miniOttMode == '1' ? 'View files: sidebar, mobile drawer, bottom-nav, channel Premium tab & OTT pages show full content.' : 'View files: all Mini OTT entries show Soon badge + native Android coming-soon sheet/modal (sidebar/desktop, bottom-nav, channel tab, OTT routes).'"></p>
                    </div>
                    <div class="rounded-xl border p-4 flex gap-3" :class="miniOttMode == '1' ? 'bg-emerald-50/50 border-emerald-100' : 'bg-orange-50/50 border-orange-100'">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0" :class="miniOttMode == '1' ? 'bg-emerald-500 text-white' : 'bg-orange-500 text-white'">
                            <span class="material-symbols-rounded text-lg" x-text="miniOttMode == '1' ? 'check' : 'schedule'"></span>
                        </div>
                        <div>
                            <p class="text-[11px] font-black text-slate-800 uppercase tracking-wider" x-text="miniOttMode == '1' ? 'Live Mode' : 'Placeholder Mode'"></p>
                            <p class="text-[11px] font-medium text-slate-500 mt-1 leading-relaxed" x-text="miniOttMode == '1' ? 'Clicking Mini OTT in sidebar / drawer / bottom-nav navigates to /mini-ott.' : 'Clicking Mini OTT in sidebar / drawer / bottom-nav opens native Android coming-soon modal (no navigation). Direct /mini-ott also renders native coming-soon page.'"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support Contact Information -->
        <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <span class="material-symbols-rounded text-xl">contact_support</span>
                </div>
                <h4 class="text-lg font-bold text-slate-800">@lang('Support Contact Information')</h4>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-6">
                    <!-- Support Email 1 -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Primary Support Email (Email 1)')</label>
                        <input type="email" name="email1" value="{{ @gs('support_config')->email1 }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 outline-none transition-all">
                    </div>

                    <!-- Support Email 2 -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Secondary Support Email (Email 2)')</label>
                        <input type="email" name="email2" value="{{ @gs('support_config')->email2 }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 outline-none transition-all">
                    </div>

                    <!-- Support Number -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Support Phone Number')</label>
                        <input type="text" name="number" value="{{ @gs('support_config')->number }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 outline-none transition-all">
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Monetization Settings -->
            <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden flex flex-col">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30">
                    <h4 class="text-lg font-bold text-slate-800">@lang('Monetization Settings')</h4>
                </div>
                
                <div class="p-8 space-y-6 flex-grow">
                    <!-- Minimum Subscribe -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Minimum Subscribe') <span class="text-rose-500">*</span></label>
                        <div class="flex items-center rounded-lg border border-slate-200 overflow-hidden focus-within:border-indigo-500 focus-within:ring-4 focus-within:ring-indigo-500/5 transition-all">
                            <input type="number" name="minimum_subscribe" value="{{ gs('minimum_subscribe') }}" required
                                class="w-full bg-white py-2.5 px-4 text-sm text-slate-700 outline-none">
                            <div class="bg-slate-50 px-4 py-2.5 border-l border-slate-200 shrink-0">
                                <span class="material-symbols-rounded text-slate-400 text-lg">notifications</span>
                            </div>
                        </div>
                    </div>

                    <!-- Minimum Views -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Minimum Views') <span class="text-rose-500">*</span></label>
                        <div class="flex items-center rounded-lg border border-slate-200 overflow-hidden focus-within:border-indigo-500 focus-within:ring-4 focus-within:ring-indigo-500/5 transition-all">
                            <input type="number" name="minimum_views" value="{{ gs('minimum_views') }}" required
                                class="w-full bg-white py-2.5 px-4 text-sm text-slate-700 outline-none">
                            <div class="bg-slate-50 px-4 py-2.5 border-l border-slate-200 shrink-0">
                                <span class="material-symbols-rounded text-slate-400 text-lg">visibility</span>
                            </div>
                        </div>
                    </div>

                    <!-- Required Watch Hours -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Required Watch Hours') <span class="text-rose-500">*</span></label>
                        <div class="flex items-center rounded-lg border border-slate-200 overflow-hidden focus-within:border-indigo-500 focus-within:ring-4 focus-within:ring-indigo-500/5 transition-all">
                            <input type="number" name="watch_hours" value="{{ gs('watch_hours') }}" required
                                class="w-full bg-white py-2.5 px-4 text-sm text-slate-700 outline-none">
                            <div class="bg-slate-50 px-4 py-2.5 border-l border-slate-200 shrink-0">
                                <span class="material-symbols-rounded text-slate-400 text-lg">schedule</span>
                            </div>
                        </div>
                    </div>

                    <!-- Pay for Paid Monetization -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Pay for Paid Monetization') <span class="text-rose-500">*</span></label>
                        <div class="flex items-center rounded-lg border border-slate-200 overflow-hidden focus-within:border-indigo-500 focus-within:ring-4 focus-within:ring-indigo-500/5 transition-all">
                            <input type="text" name="monetization_amount" value="{{ getAmount(gs('monetization_amount')) }}" required
                                class="w-full bg-white py-2.5 px-4 text-sm text-slate-700 outline-none">
                            <div class="bg-slate-50 px-4 py-2.5 border-l border-slate-200 shrink-0">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ gs('cur_text') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Paid Monetization Status Toggle -->
                    <div class="pt-4">
                        <div x-data="{ enabled: {{ gs('monetization_status') ? 'true' : 'false' }} }" 
                             class="flex items-center justify-between p-4 rounded-xl border transition-all duration-300 cursor-pointer"
                             :class="enabled ? 'bg-emerald-50/50 border-emerald-200' : 'bg-slate-50/50 border-slate-200'"
                             @click="enabled = !enabled">
                            <div>
                                <label class="text-[11px] font-bold text-slate-700 uppercase tracking-wider block pointer-events-none">@lang('Paid Monetization')</label>
                                <p class="text-[10px] text-slate-500 mt-1 font-medium pointer-events-none">Enable creator payouts and revenue.</p>
                            </div>
                            <div class="relative w-12 h-6 rounded-full p-1 transition-colors duration-300 ease-in-out shadow-inner shrink-0"
                                 :class="enabled ? 'bg-emerald-500' : 'bg-slate-300'">
                                <input type="checkbox" name="monetization_status" x-model="enabled" class="hidden">
                                <div class="w-4 h-4 bg-white rounded-full shadow-sm transform transition-transform duration-300 ease-in-out"
                                     :class="enabled ? 'translate-x-6' : 'translate-x-0'"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Violation & Video Config -->
            <div class="space-y-8">
                <!-- Video & Ad Settings -->
                <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30">
                        <h4 class="text-lg font-bold text-slate-800">@lang('Video & Ad Settings')</h4>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Max Upload Size (Bytes)') <span class="text-rose-500">*</span></label>
                            <input type="number" name="max_upload_size" value="{{ $siteSetting->max_upload_size }}" required
                                class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 outline-none transition-all">
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Allowed Types')</label>
                                <input type="text" name="allowed_video_types" value="{{ $siteSetting->allowed_video_types }}" required
                                    class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-indigo-500 outline-none transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Default Quality')</label>
                                <input type="text" name="default_video_quality" value="{{ $siteSetting->default_video_quality }}" required
                                    class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-indigo-500 outline-none transition-all">
                            </div>
                        </div>
                        <!-- Ads Status Toggle -->
                        <div class="pt-2">
                            <div x-data="{ enabled: {{ $siteSetting->ads_enabled ? 'true' : 'false' }} }" 
                                 class="flex items-center justify-between p-4 rounded-xl border transition-all duration-300 cursor-pointer"
                                 :class="enabled ? 'bg-indigo-50/50 border-indigo-200' : 'bg-slate-50/50 border-slate-200'"
                                 @click="enabled = !enabled">
                                <div>
                                    <label class="text-[11px] font-bold text-slate-700 uppercase tracking-wider block pointer-events-none">@lang('Platform Ads')</label>
                                    <p class="text-[10px] text-slate-500 mt-1 font-medium pointer-events-none">Show advertisements globally.</p>
                                </div>
                                <div class="relative w-12 h-6 rounded-full p-1 transition-colors duration-300 ease-in-out shadow-inner shrink-0"
                                     :class="enabled ? 'bg-indigo-500' : 'bg-slate-300'">
                                    <input type="checkbox" name="ads_enabled" x-model="enabled" class="hidden">
                                    <div class="w-4 h-4 bg-white rounded-full shadow-sm transform transition-transform duration-300 ease-in-out"
                                         :class="enabled ? 'translate-x-6' : 'translate-x-0'"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Violation Content Warning -->
                <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30">
                        <h4 class="text-lg font-bold text-slate-800">@lang('Violation Content Warning')</h4>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Title') <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" value="{{ __(old('title', gs('vc_warning')?->title)) }}" required
                                class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Description')</label>
                            <textarea name="description" rows="5" class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-indigo-500 outline-none resize-none">{{ __(old('description', gs('vc_warning')?->description)) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Bunny Stream Settings -->
        <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center">
                    <span class="material-symbols-rounded text-xl">cloud_sync</span>
                </div>
                <h4 class="text-lg font-bold text-slate-800">@lang('Bunny Stream Settings')</h4>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-6">
                    <!-- Bunny API Key -->
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Bunny Stream API Key')</label>
                        <div class="relative">
                            <input type="password" name="bunny_api_key" value="{{ gs('bunny_api_key') }}"
                                class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 outline-none transition-all pr-12">
                            <button type="button" onclick="const p = this.previousElementSibling; p.type = p.type === 'password' ? 'text' : 'password'" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <span class="material-symbols-rounded text-lg">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- CDN Hostname -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('CDN Hostname')</label>
                        <input type="text" name="bunny_cdn_hostname" value="{{ gs('bunny_cdn_hostname') }}" placeholder="e.g. vz-xxx.b-cdn.net"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                    </div>

                    <!-- Video Library ID -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Video Library ID')</label>
                        <input type="text" name="bunny_video_library_id" value="{{ gs('bunny_video_library_id') }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                    </div>

                    <!-- Video Collection ID -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Video Collection ID')</label>
                        <input type="text" name="bunny_video_collection_id" value="{{ gs('bunny_video_collection_id') }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                    </div>

                    <div class="hidden lg:block"></div>

                    <!-- Reel Library ID -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Reel Library ID')</label>
                        <input type="text" name="bunny_reel_library_id" value="{{ gs('bunny_reel_library_id') }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                    </div>

                    <!-- Reel Collection ID -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Reel Collection ID')</label>
                        <input type="text" name="bunny_reel_collection_id" value="{{ gs('bunny_reel_collection_id') }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3b: Bunny Reels Storage Settings -->
        <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center">
                    <span class="material-symbols-rounded text-xl">folder_zip</span>
                </div>
                <h4 class="text-lg font-bold text-slate-800">@lang('Bunny Reels Storage Settings (Reels Only)')</h4>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-6">
                    <!-- Storage Zone Name -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Bunny Storage Zone Name')</label>
                        <input type="text" name="bunny_reels_storage_zone" value="{{ gs('bunny_reels_storage_zone') }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                    </div>

                    <!-- Storage Access Key -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Bunny Storage Access Key')</label>
                        <div class="relative">
                            <input type="password" name="bunny_reels_storage_access_key" value="{{ gs('bunny_reels_storage_access_key') }}"
                                class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 outline-none transition-all pr-12">
                            <button type="button" onclick="const p = this.previousElementSibling; p.type = p.type === 'password' ? 'text' : 'password'" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <span class="material-symbols-rounded text-lg">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- Storage Region -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Bunny Storage Region')</label>
                        <input type="text" name="bunny_reels_storage_region" value="{{ gs('bunny_reels_storage_region') }}" placeholder="e.g. ny, sg, la (or blank for default)"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                    </div>

                    <!-- Pull Zone Host -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Bunny Reels Pull Zone Domain')</label>
                        <input type="text" name="bunny_reels_pull_zone" value="{{ gs('bunny_reels_pull_zone') }}" placeholder="e.g. reelscdn.b-cdn.net"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                    </div>

                    <!-- Reels Duration Limit -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Reels Max Duration (Seconds)')</label>
                        <input type="number" name="reels_duration_limit" value="{{ gs('reels_duration_limit') ?? 60 }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                    </div>

                    <!-- Reels Compression Size -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Reels Max Size (MB)')</label>
                        <input type="number" name="reels_compression_size" value="{{ gs('reels_compression_size') ?? 15 }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                    </div>

                    <!-- Reels Max Allowed Size (Before Compression) -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Reels Max Allowed Size (MB)')</label>
                        <input type="number" name="reels_max_upload_size" value="{{ gs('reels_max_upload_size') ?? 100 }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4: Firebase Settings -->
        <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <span class="material-symbols-rounded text-xl">notifications_active</span>
                </div>
                <h4 class="text-lg font-bold text-slate-800">@lang('Firebase Setting')</h4>
            </div>
            
            <div class="p-8">
                @php $firebase = gs('firebase_config'); @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-6">
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Firebase API Key')</label>
                        <input type="text" name="firebase_apiKey" value="{{ @$firebase->apiKey }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-yellow-500 focus:ring-4 focus:ring-yellow-500/5 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Firebase Auth Domain')</label>
                        <input type="text" name="firebase_authDomain" value="{{ @$firebase->authDomain }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-yellow-500 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Firebase Project ID')</label>
                        <input type="text" name="firebase_projectId" value="{{ @$firebase->projectId }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-yellow-500 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Firebase Storage Bucket')</label>
                        <input type="text" name="firebase_storageBucket" value="{{ @$firebase->storageBucket }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-yellow-500 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Firebase Messaging Sender ID')</label>
                        <input type="text" name="firebase_messagingSenderId" value="{{ @$firebase->messagingSenderId }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-yellow-500 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Firebase App ID')</label>
                        <input type="text" name="firebase_appId" value="{{ @$firebase->appId }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-yellow-500 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Firebase Measurement ID')</label>
                        <input type="text" name="firebase_measurementId" value="{{ @$firebase->measurementId }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-yellow-500 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5 md:col-span-3">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Firebase VAPID Key')</label>
                        <input type="text" name="firebase_vapidKey" value="{{ @$firebase->vapidKey }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-yellow-500 outline-none transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 5: Cloudflare R2 Settings -->
        <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                    <span class="material-symbols-rounded text-xl">cloud</span>
                </div>
                <h4 class="text-lg font-bold text-slate-800">@lang('Cloudflare R2 Setting')</h4>
            </div>
            
            <div class="p-8">
                @php $r2 = gs('cloudflare_config'); @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-6">
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('R2 Access Key')</label>
                        <input type="text" name="r2_access_key" value="{{ @$r2->access_key }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/5 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('R2 Secret Key')</label>
                        <div class="relative">
                            <input type="password" name="r2_secret_key" value="{{ @$r2->secret_key }}"
                                class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/5 outline-none transition-all pr-12">
                            <button type="button" onclick="const p = this.previousElementSibling; p.type = p.type === 'password' ? 'text' : 'password'" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <span class="material-symbols-rounded text-lg">visibility</span>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('R2 Bucket')</label>
                        <input type="text" name="r2_bucket" value="{{ @$r2->bucket }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('R2 Endpoint')</label>
                        <input type="text" name="r2_endpoint" value="{{ @$r2->endpoint }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5 md:col-span-3">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('R2 URL')</label>
                        <input type="text" name="r2_url" value="{{ @$r2->url }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-blue-500 outline-none transition-all">
                    </div>
                </div>
            </div>
        </div>


        <!-- Section 6: Razorpay Settings -->
        <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <span class="material-symbols-rounded text-xl">payments</span>
                </div>
                <h4 class="text-lg font-bold text-slate-800">@lang('Razorpay Setting')</h4>
            </div>
            
            <div class="p-8">
                @php $razorpay = gs('razorpay_config'); @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Razorpay Key')</label>
                        <input type="text" name="razorpay_key" value="{{ @$razorpay->key }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Razorpay Secret')</label>
                        <div class="relative">
                            <input type="password" name="razorpay_secret" value="{{ @$razorpay->secret }}"
                                class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 outline-none transition-all pr-12">
                            <button type="button" onclick="const p = this.previousElementSibling; p.type = p.type === 'password' ? 'text' : 'password'" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <span class="material-symbols-rounded text-lg">visibility</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mail Service Provider Toggle -->
        <div x-data="{ provider: '{{ gs('mail_provider') ?? 'brevo' }}' }" class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                    <span class="material-symbols-rounded text-xl">swap_horiz</span>
                </div>
                <h4 class="text-lg font-bold text-slate-800">@lang('Mail Service Provider')</h4>
            </div>
            <div class="p-8">
                <div class="space-y-1.5">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-4">@lang('Select which service to use for sending all transactional emails')</label>
                    <div class="flex gap-4 max-w-md">
                        <label class="flex-1 flex items-center justify-center gap-3 p-5 rounded-xl border-2 cursor-pointer transition-all duration-300"
                               :class="provider === 'brevo' ? 'bg-cyan-50 border-cyan-400 shadow-sm' : 'bg-slate-50 border-slate-200 hover:border-slate-300'"
                               @click="provider = 'brevo'">
                            <input type="radio" name="mail_provider" value="brevo" x-model="provider" class="hidden">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                 :class="provider === 'brevo' ? 'bg-cyan-500 text-white' : 'bg-slate-200 text-slate-500'">
                                <span class="material-symbols-rounded text-xl">mail</span>
                            </div>
                            <div>
                                <div class="text-sm font-bold" :class="provider === 'brevo' ? 'text-cyan-700' : 'text-slate-600'">Brevo</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">Sendinblue</div>
                            </div>
                        </label>
                        <label class="flex-1 flex items-center justify-center gap-3 p-5 rounded-xl border-2 cursor-pointer transition-all duration-300"
                               :class="provider === 'zepto' ? 'bg-teal-50 border-teal-400 shadow-sm' : 'bg-slate-50 border-slate-200 hover:border-slate-300'"
                               @click="provider = 'zepto'">
                            <input type="radio" name="mail_provider" value="zepto" x-model="provider" class="hidden">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                 :class="provider === 'zepto' ? 'bg-teal-500 text-white' : 'bg-slate-200 text-slate-500'">
                                <span class="material-symbols-rounded text-xl">drafts</span>
                            </div>
                            <div>
                                <div class="text-sm font-bold" :class="provider === 'zepto' ? 'text-teal-700' : 'text-slate-600'">ZeptoMail</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">Zoho Mail</div>
                            </div>
                        </label>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-3" x-show="provider === 'brevo'" x-cloak>
                        <span class="material-symbols-rounded text-xs align-text-bottom">info</span>
                        @lang('All transactional emails will be sent via Brevo (Sendinblue) API.')
                    </p>
                    <p class="text-[10px] text-slate-400 mt-3" x-show="provider === 'zepto'" x-cloak>
                        <span class="material-symbols-rounded text-xs align-text-bottom">info</span>
                        @lang('All transactional emails will be sent via ZeptoMail (Zoho) API.')
                    </p>
                </div>
            </div>
        </div>

        <!-- Section 7: Brevo Settings -->
        <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-cyan-100 text-cyan-600 flex items-center justify-center">
                    <span class="material-symbols-rounded text-xl">mail</span>
                </div>
                <h4 class="text-lg font-bold text-slate-800">@lang('Brevo (Sendinblue) Setting')</h4>
            </div>

            <div class="p-8">
                @php $brevo = gs('brevo_config'); @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-6">
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Brevo API Key')</label>
                        <div class="relative">
                            <input type="password" name="brevo_api_key" value="{{ @$brevo->api_key }}"
                                class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/5 outline-none transition-all pr-12">
                            <button type="button" onclick="const p = this.previousElementSibling; p.type = p.type === 'password' ? 'text' : 'password'" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <span class="material-symbols-rounded text-lg">visibility</span>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Sender Email')</label>
                        <input type="email" name="brevo_sender_email" value="{{ @$brevo->sender_email }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-cyan-500 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5 md:col-span-3">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Sender Name')</label>
                        <input type="text" name="brevo_sender_name" value="{{ @$brevo->sender_name }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-cyan-500 outline-none transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 8: ZeptoMail Settings -->
        <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center">
                    <span class="material-symbols-rounded text-xl">drafts</span>
                </div>
                <h4 class="text-lg font-bold text-slate-800">@lang('ZeptoMail Setting')</h4>
            </div>

            <div class="p-8">
                @php $zepto = gs('zeptomail_config'); @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('ZeptoMail API Key')</label>
                        <div class="relative">
                            <input type="password" name="zeptomail_api_key" value="{{ @$zepto->api_key }}"
                                class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 outline-none transition-all pr-12">
                            <button type="button" onclick="const p = this.previousElementSibling; p.type = p.type === 'password' ? 'text' : 'password'" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <span class="material-symbols-rounded text-lg">visibility</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 8.5: Google Analytics Settings -->
        <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center">
                    <span class="material-symbols-rounded text-xl">analytics</span>
                </div>
                <h4 class="text-lg font-bold text-slate-800">@lang('Google Analytics Settings')</h4>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-6">
                    <!-- Google Analytics ID -->
                    <div class="space-y-1.5 md:col-span-1">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Google Analytics Measurement ID')</label>
                        <input type="text" name="google_analytics_id" value="{{ gs('google_analytics_id') }}" placeholder="e.g. G-XXXXXXXXXX"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                    </div>

                    <!-- Google Analytics Embed URL -->
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Google Analytics Looker Studio Embed URL')</label>
                        <input type="text" name="google_analytics_embed_url" value="{{ gs('google_analytics_embed_url') }}" placeholder="e.g. https://lookerstudio.google.com/embed/reporting/..."
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 9: Google OAuth Settings -->
        <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center">
                    <span class="material-symbols-rounded text-xl">login</span>
                </div>
                <h4 class="text-lg font-bold text-slate-800">@lang('Google OAuth Setting')</h4>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-6">
                    <!-- Google Client ID -->
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Google Client ID')</label>
                        <input type="text" name="google_client_id" value="{{ gs('google_client_id') }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-rose-500 focus:ring-4 focus:ring-rose-500/5 outline-none transition-all">
                    </div>

                    <!-- Google Client Secret -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Google Client Secret')</label>
                        <input type="text" name="google_client_secret" value="{{ gs('google_client_secret') }}"
                            class="w-full bg-white border border-slate-200 rounded-lg py-2.5 px-4 text-sm text-slate-700 focus:border-rose-500 focus:ring-4 focus:ring-rose-500/5 outline-none transition-all">
                    </div>

                    <!-- Google Redirect URI -->
                    <div class="space-y-1.5 md:col-span-3">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">@lang('Google Redirect URI')</label>
                        <div class="flex items-center rounded-lg border border-slate-200 overflow-hidden focus-within:border-rose-500 focus-within:ring-4 focus-within:ring-rose-500/5 transition-all">
                            <input type="text" name="google_redirect_uri" value="{{ gs('google_redirect_uri') ?? url('/auth/google/callback') }}"
                                class="w-full bg-white py-2.5 px-4 text-sm text-slate-700 outline-none">
                            <div class="bg-slate-50 px-4 py-2.5 border-l border-slate-200 shrink-0">
                                <span class="material-symbols-rounded text-slate-400 text-lg">link</span>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">@lang('Default callback:') <code class="text-rose-500">{{ url('/auth/google/callback') }}</code></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Master Submit -->
        <div class="pt-10">
            <button type="submit" class="w-full h-16 rounded-[2rem] bg-indigo-600 text-white text-sm font-black uppercase tracking-[0.3em] hover:bg-indigo-700 hover:scale-[1.01] active:scale-95 transition-all flex items-center justify-center gap-4 shadow-2xl shadow-indigo-500/30 ">
                <span class="material-symbols-rounded text-2xl">published_with_changes</span>
                @lang('Submit')
            </button>
        </div>
    </form>
</div>

@push('script-lib')
<script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
@endpush

@push('style-lib')
<link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";

        $('.colorPicker').spectrum({
            color: $(this).data('color'),
            change: function (color) {
                $(this).parent().siblings('.colorCode').val(color.toHexString().replace(/^#?/, ''));
            }
        });

        $('.colorCode').on('input', function () {
            var clr = $(this).val();
            $(this).parent().find('.colorPicker').spectrum({
                color: clr,
            });
        });


    })(jQuery);
</script>
@endpush

@push('style')
<style>
    .sp-replacer { border: none !important; padding: 0 !important; margin: 0 !important; width: 100% !important; height: 100% !important; background: transparent !important; }
    .sp-preview { width: 100% !important; height: 100% !important; border: none !important; border-radius: 0 !important; margin-right: 0 !important; }
    .sp-dd { display: none !important; }
    
    select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 0.8rem;
    }
</style>
@endpush
@endsection

