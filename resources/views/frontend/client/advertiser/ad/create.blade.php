<x-app-layout>
    <div class="py-8 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500" x-data="adCreator()">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6">
            <!-- Header -->
            <div class="mb-12">
                <a href="{{ route('user.advertiser.ad.list') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 hover:text-orange-500 transition-all">
                    <span class="material-symbols-rounded text-lg">arrow_back</span>
                    Inventory Center
                </a>
                <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter">Campaign Architecture</h1>
                <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                     <span class="w-1.5 h-1.5 rounded-full gradient-orange shadow-[0_0_8px_rgba(249,115,22,0.5)]"></span>
                     Configure target distributions and creatives
                </p>
            </div>

            <form :action="formAction" method="POST" enctype="multipart/form-data" id="adForm">
                @csrf
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-12">
                    <!-- Construction Tools -->
                    <div class="xl:col-span-8 space-y-10">
                        
                        <!-- Premium Video Creative Studio -->
                        <div class="bg-white dark:bg-[#181818] p-10 sm:p-16 rounded-[4rem] border border-gray-100 dark:border-white/5 shadow-sm relative overflow-hidden transition-all duration-500">
                             <!-- Background Texture -->
                            <div class="absolute -top-24 -right-24 w-80 h-80 bg-orange-600/5 rounded-full blur-[100px]"></div>
                            
                            <div class="relative z-10">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.4em] mb-10 block">Visual Creative (MP4/WebM)</label>
                                
                                <div class="relative">
                                    <label for="ad_video" class="block w-full border-4 border-dashed border-gray-100 dark:border-white/5 rounded-[3rem] p-16 text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-white/[0.03] transition-all group overflow-hidden relative">
                                        <input type="file" id="ad_video" name="ad_video" class="hidden" accept="video/*" @change="handleVideoUpload($event)">
                                        
                                        <div x-show="!uploading && !uploadComplete" class="space-y-6">
                                            <div class="w-20 h-20 rounded-[2rem] gradient-orange text-white flex items-center justify-center mx-auto shadow-2xl shadow-orange-500/30 group-hover:scale-110 transition-transform">
                                                <span class="material-symbols-rounded text-4xl">cloud_upload</span>
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tighter">Inject Video Creative</h4>
                                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-3">High-Definition content up to 50MB</p>
                                            </div>
                                        </div>

                                        <div x-show="uploading" class="space-y-8 py-4">
                                            <div class="relative w-40 h-40 mx-auto">
                                                <svg class="w-full h-full transform -rotate-90">
                                                    <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="8" fill="transparent" class="text-gray-100 dark:text-white/5" />
                                                    <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="8" fill="transparent" :style="`stroke-dasharray: 440; stroke-dashoffset: ${440 - (440 * uploadProgress / 100)}`" class="text-orange-600 transition-all duration-500" />
                                                </svg>
                                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                                    <span class="text-2xl font-black text-gray-900 dark:text-white" x-text="`${uploadProgress}%`"></span>
                                                    <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Processing</span>
                                                </div>
                                            </div>
                                            <p class="text-[10px] font-black text-orange-600 uppercase tracking-[0.4em] animate-pulse">Establishing secure upload link...</p>
                                        </div>

                                        <div x-show="uploadComplete" class="space-y-6">
                                             <div class="w-20 h-20 rounded-[2rem] bg-emerald-500 text-white flex items-center justify-center mx-auto shadow-2xl shadow-emerald-500/30">
                                                <span class="material-symbols-rounded text-4xl">verified</span>
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-black text-emerald-500 uppercase tracking-tighter">Creative Authorized</h4>
                                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-2">Buffer encrypted and ready for distribution</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Configuration Intelligence -->
                        <div class="bg-white dark:bg-[#181818] p-12 sm:p-16 rounded-[4rem] border border-gray-100 dark:border-white/5 shadow-2xl space-y-12 transition-all relative overflow-hidden">
                             <div class="absolute -top-24 -left-24 w-64 h-64 bg-blue-600/5 rounded-full blur-[80px] pointer-events-none"></div>
                             
                             <div class="grid grid-cols-1 md:grid-cols-2 gap-10 relative z-10">
                                 <x-input name="title" label="Campaign Brand Designation" placeholder="Project Name" required="true" icon="campaign" hint="Define a high-visibility name for your distribution campaign." />

                                 <div class="space-y-2">
                                     <label class="px-1 text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] block">Target Audience Segments</label>
                                     <div class="premium-form-container relative group">
                                         <div class="absolute inset-y-0 left-0 flex items-center pl-6 pointer-events-none text-slate-400 group-focus-within:text-orange-500 z-20">
                                             <span class="material-symbols-rounded">groups</span>
                                         </div>
                                         <select name="category_id[]" multiple required class="select2-modern w-full">
                                             @foreach ($categories as $category)
                                                 <option value="{{ $category->id }}">{{ __($category->name) }}</option>
                                             @endforeach
                                         </select>
                                         <div x-show="focused" class="mt-2 text-[10px] font-bold text-orange-500 uppercase tracking-widest flex items-center gap-1 px-1">
                                            <span class="material-symbols-rounded text-xs">lightbulb</span>
                                            Identify target demographics for optimized reach.
                                        </div>
                                     </div>
                                 </div>
                             </div>

                             <div class="space-y-8 relative z-10">
                                 <label class="px-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.4em] block text-center">Bidding Optimization Model</label>
                                 <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                     <label class="cursor-pointer group relative">
                                         <input type="radio" name="ad_type" value="1" x-model="adType" class="hidden peer">
                                         <div class="p-8 bg-gray-50 dark:bg-white/2 border-2 border-transparent rounded-[3rem] text-center transition-all peer-checked:border-orange-500 peer-checked:bg-orange-500/5 group-hover:-translate-y-2 group-hover:shadow-2xl">
                                             <div class="w-14 h-14 rounded-2xl bg-white dark:bg-white/5 flex items-center justify-center mx-auto mb-6 group-hover:rotate-6 transition-all shadow-sm">
                                                 <span class="material-symbols-rounded text-3xl text-gray-400 peer-checked:text-orange-500">visibility</span>
                                             </div>
                                             <p class="text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-widest mb-1">Capped Impression</p>
                                             <p class="text-[9px] font-bold text-gray-400 opacity-60 uppercase tracking-tight">Visibility-centric optimization</p>
                                         </div>
                                     </label>
                                     <label class="cursor-pointer group relative">
                                         <input type="radio" name="ad_type" value="2" x-model="adType" class="hidden peer">
                                         <div class="p-8 bg-gray-50 dark:bg-white/2 border-2 border-transparent rounded-[3rem] text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-500/5 group-hover:-translate-y-2 group-hover:shadow-2xl">
                                             <div class="w-14 h-14 rounded-2xl bg-white dark:bg-white/5 flex items-center justify-center mx-auto mb-6 group-hover:rotate-6 transition-all shadow-sm">
                                                 <span class="material-symbols-rounded text-3xl text-gray-400 peer-checked:text-blue-500">touch_app</span>
                                             </div>
                                             <p class="text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-widest mb-1">Per Click Action</p>
                                             <p class="text-[9px] font-bold text-gray-400 opacity-60 uppercase tracking-tight">Engagement focused rewards</p>
                                         </div>
                                     </label>
                                     <label class="cursor-pointer group relative">
                                         <input type="radio" name="ad_type" value="3" x-model="adType" class="hidden peer">
                                         <div class="p-8 bg-gray-50 dark:bg-white/2 border-2 border-transparent rounded-[3rem] text-center transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-500/5 group-hover:-translate-y-2 group-hover:shadow-2xl">
                                             <div class="w-14 h-14 rounded-2xl bg-white dark:bg-white/5 flex items-center justify-center mx-auto mb-6 group-hover:rotate-6 transition-all shadow-sm">
                                                 <span class="material-symbols-rounded text-3xl text-gray-400 peer-checked:text-emerald-500">auto_awesome</span>
                                             </div>
                                             <p class="text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-widest mb-1">Hybrid protocol</p>
                                             <p class="text-[9px] font-bold text-gray-400 opacity-60 uppercase tracking-tight">Maximized cross-metric reach</p>
                                         </div>
                                     </label>
                                 </div>
                             </div>
                        </div>

                        <!-- Objective Precision -->
                        <div class="bg-white dark:bg-[#181818] p-12 sm:p-16 rounded-[4rem] border border-gray-100 dark:border-white/5 shadow-2xl space-y-12 transition-all relative overflow-hidden">
                             <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-emerald-600/5 rounded-full blur-[80px] pointer-events-none"></div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 relative z-10">
                                <x-input type="number" name="impression" label="Distribution Quota (Views)" x-model="metrics.impression" :readonly="adType == '2'" icon="eye_tracking" hint="Define total requested impressions for this campaign cycle." />
                                <x-input type="number" name="click" label="Interaction Quota (Clicks)" x-model="metrics.click" :readonly="adType == '1'" icon="ads_click" hint="Define total requested clicks for high-intent distribution." />
                            </div>

                            <div x-show="adType == '2' || adType == '3'" x-collapse class="space-y-10 pt-4 relative z-10">
                                <div class="p-0.5 w-full bg-blue-500/10 rounded-full"></div>
                                <div class="grid grid-cols-1 gap-10">
                                    <x-input type="url" name="url" label="Click-Through Destination" placeholder="https://external-brand-page.com" required="true" icon="link" hint="Authorized destination URL for engagement redirect." />
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                        <x-input name="button_label" label="CTA Action Protocol" placeholder="Purchase Now" required="true" icon="smart_button" hint="Define the high-conversion text for the call-to-action." />

                                        <div class="space-y-4">
                                            <label class="px-1 text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] block">Corporate Identity (Logo)</label>
                                            <div class="relative group">
                                                <input type="file" name="logo" id="logo_upload" class="hidden" accept="image/*" @change="logoSelected = true">
                                                <label for="logo_upload" class="flex items-center justify-between w-full h-16 bg-blue-500/5 border border-blue-500/10 rounded-2xl px-8 text-[13px] font-black text-gray-400 cursor-pointer hover:bg-blue-500/10 transition-all">
                                                    <span x-text="logoSelected ? 'Asset Synchronized' : 'Attach Brand Mark'"></span>
                                                    <span class="material-symbols-rounded text-blue-500">image</span>
                                                </label>
                                                <div class="mt-2 text-[10px] font-bold text-blue-500 uppercase tracking-widest flex items-center gap-1 px-1">
                                                    <span class="material-symbols-rounded text-xs">info</span>
                                                    SVG or PNG with transparent alpha channel preferred.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Clearance Sidebar -->
                    <div class="xl:col-span-4">
                        <div class="sticky top-8 space-y-8">
                            <div class="bg-gray-900 dark:bg-white rounded-[4rem] text-white dark:text-black p-12 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.4)] relative overflow-hidden transition-colors duration-500">
                                <!-- Design Glow -->
                                <div class="absolute -top-32 -right-32 w-80 h-80 bg-red-600/20 rounded-full blur-[100px] pointer-events-none"></div>
                                
                                <h3 class="text-2xl font-black tracking-tighter uppercase mb-2">Order Ledger</h3>
                                <p class="text-[9px] font-bold opacity-40 uppercase tracking-[0.3em] mb-12">Bidding Protocol Calculation</p>
                                
                                <div class="space-y-8 mb-12">
                                    <div class="flex justify-between items-start group">
                                        <div class="flex-grow">
                                            <p class="text-[10px] font-black uppercase tracking-widest opacity-40 group-hover:opacity-100 transition-opacity">Impressions</p>
                                            <p class="text-[11px] font-bold opacity-60 mt-1" x-text="`Quota: ${metrics.impression}`"></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[11px] font-black" x-text="`${currency}${(metrics.impression * pricing.per_impression).toFixed(2)}`"></p>
                                            <p class="text-[9px] font-black text-orange-500 uppercase mt-1" x-text="`${currency}${(metrics.impression * pricing.per_impression).toFixed(2)}`"></p>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-start group">
                                        <div class="flex-grow">
                                            <p class="text-[10px] font-black uppercase tracking-widest opacity-40 group-hover:opacity-100 transition-opacity">Goal Actions</p>
                                            <p class="text-[11px] font-bold opacity-60 mt-1" x-text="`Quota: ${metrics.click}`"></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[11px] font-black" x-text="`${currency}${(metrics.click * pricing.per_click).toFixed(2)}`"></p>
                                            <p class="text-[9px] font-black text-orange-500 uppercase mt-1" x-text="`${currency}${(metrics.click * pricing.per_click).toFixed(2)}`"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-10 bg-white/5 dark:bg-gray-100 rounded-[2.5rem] border border-white/10 dark:border-gray-200 mb-12 text-center">
                                    <p class="text-[10px] font-black uppercase tracking-[0.5em] opacity-40 mb-3">Investment Total</p>
                                    <span class="text-5xl font-black tracking-tighter" x-text="`${currency}${totalCost()}`"></span>
                                </div>

                                <button type="submit" :disabled="!uploadComplete" 
                                        class="w-full py-6 gradient-orange text-white rounded-[2rem] font-black text-xs uppercase tracking-[0.4em] shadow-2xl shadow-orange-500/40 hover:-translate-y-2 transition-all active:scale-95 disabled:opacity-20 disabled:cursor-not-allowed">
                                    Initiate Deployment
                                </button>
                                
                                <p x-show="!uploadComplete" class="text-center text-[9px] font-black opacity-30 mt-8 uppercase tracking-[0.3em]">Protocol locked until creative upload</p>
                            </div>
                            
                            <div class="bg-white dark:bg-[#181818] p-10 rounded-[3rem] border border-gray-100 dark:border-white/5 shadow-sm transition-colors duration-500">
                                <div class="flex items-center gap-6">
                                    <div class="w-14 h-14 rounded-[1.5rem] bg-emerald-500/10 text-emerald-500 flex items-center justify-center shadow-xl shadow-emerald-500/10">
                                        <span class="material-symbols-rounded text-3xl">verified_user</span>
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-widest">Quality Audit Layer</p>
                                        <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-tight">Verified distribution within 4 hours of deployment.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('style')
    <style>
        .select2-container--default .select2-selection--multiple {
            background-color: transparent !important;
            border: none !important;
            min-height: 58px !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #ef4444 !important;
            color: white !important;
            border: none !important;
            border-radius: 0.75rem !important;
            padding: 4px 12px 4px 24px !important;
            font-size: 10px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            margin-top: 10px !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white !important;
            position: absolute !important;
            left: 8px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
        }
    </style>
    @endpush

    @push('style-lib')
        <link href="{{ asset('assets/global/css/select2.min.css') }}" rel="stylesheet">
    @endpush

    @push('script-lib')
        <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    @endpush

    @push('script')
    <script>
        function adCreator() {
            return {
                adType: '1',
                metrics: { impression: 0, click: 0 },
                pricing: {
                    per_impression: parseFloat("{{ gs('per_impression_spent') }}"),
                    per_click: parseFloat("{{ gs('per_click_spent') }}")
                },
                currency: "{{ gs('cur_sym') }}",
                uploading: false,
                uploadProgress: 0,
                uploadComplete: false,
                logoSelected: false,
                formAction: "", 

                init() {
                    $('.select2-modern').select2({ placeholder: 'Select Target Audiences' });
                },

                handleVideoUpload(e) {
                    const file = e.target.files[0];
                    if(!file) return;

                    this.uploading = true;
                    this.uploadProgress = 0;
                    
                    let formData = new FormData();
                    formData.append('video', file);

                    let interval = setInterval(() => {
                        if(this.uploadProgress < 95) this.uploadProgress += (Math.random() * 5);
                        this.uploadProgress = Math.min(95, Math.ceil(this.uploadProgress));
                    }, 400);

                    $.ajax({
                        url: "{{ route('user.advertiser.upload.ad.video') }}",
                        method: "POST",
                        data: formData,
                        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                        processData: false,
                        contentType: false,
                        success: (res) => {
                            clearInterval(interval);
                            this.uploadProgress = 100;
                            setTimeout(() => {
                                this.uploading = false;
                                this.uploadComplete = true;
                                this.formAction = "{{ route('user.advertiser.processed.checkout', '') }}/" + res.data.advertisement.id;
                            }, 500);
                        },
                        error: () => {
                            clearInterval(interval);
                            this.uploading = false;
                            alert('Protocol failure: Check file size and network connectivity.');
                        }
                    });
                },

                totalCost() {
                    let cost = (this.metrics.impression * this.pricing.per_impression) + (this.metrics.click * this.pricing.per_click);
                    return cost.toFixed(2);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
