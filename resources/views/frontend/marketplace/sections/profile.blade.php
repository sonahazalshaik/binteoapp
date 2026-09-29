<div x-show="tab === 'profile'" x-cloak x-transition class="animate-in fade-in slide-in-from-bottom-4 duration-700"
     x-data="{ showDeleteModal: {{ $errors->any() && (old('reason') || old('password')) ? 'true' : 'false' }}, deleteReason: '{{ old('reason', '') }}', customReason: '{{ old('custom_reason', '') }}' }">
    <div class="max-w-2xl mx-auto bg-white dark:bg-[#111] rounded-xl border border-slate-200 dark:border-white/5 shadow-sm overflow-hidden">
        <!-- Section Header -->
        <div class="px-5 md:px-6 py-4 bg-slate-50/50 dark:bg-white/[0.02] border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">Profile Settings</h2>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Manage your professional identity</p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-500 flex items-center justify-center">
                <span class="material-symbols-rounded text-[18px]">manage_accounts</span>
            </div>
        </div>

        <form action="{{ route('marketplace.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-5 md:p-6 space-y-6" onsubmit="showServiceLoader('Synchronizing profile data...')"
              x-data="{ imagePreview: null, coverPreview: null }">
            @csrf
            
            <!-- Media & Branding Section -->
            <div class="space-y-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-px flex-grow bg-slate-100 dark:bg-white/5"></div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Media & Branding</span>
                    <div class="h-px flex-grow bg-slate-100 dark:bg-white/5"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                    <!-- Profile Image -->
                    <div>
                        <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 ml-1 mb-1.5 block">Profile Avatar</label>
                        <div class="relative group flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full overflow-hidden bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 shrink-0 relative">
                                <template x-if="imagePreview">
                                    <img :src="imagePreview" class="absolute inset-0 w-full h-full object-cover">
                                </template>
                                <template x-if="!imagePreview">
                                    @if($client->image)
                                        <img src="{{ $client->photoUrl() }}" class="absolute inset-0 w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400">
                                            <span class="material-symbols-rounded">person</span>
                                        </div>
                                    @endif
                                </template>
                            </div>
                            <input type="file" name="image" accept="image/*" @change="const file = $event.target.files[0]; if (file) { imagePreview = URL.createObjectURL(file); }" class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-[10px] file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 dark:file:bg-indigo-500/10 dark:file:text-indigo-400 dark:hover:file:bg-indigo-500/20 transition-all cursor-pointer">
                        </div>
                    </div>

                    <!-- Cover Image -->
                    <div>
                        <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 ml-1 mb-1.5 block">Cover Photo</label>
                        <div class="relative group flex flex-col gap-2">
                            <input type="file" name="cover_image" accept="image/*" @change="const file = $event.target.files[0]; if (file) { coverPreview = URL.createObjectURL(file); }" class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-[10px] file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 dark:file:bg-indigo-500/10 dark:file:text-indigo-400 dark:hover:file:bg-indigo-500/20 transition-all cursor-pointer">
                            
                            <!-- Cover Preview (Live) -->
                            <template x-if="coverPreview">
                                <div class="h-12 w-full rounded-lg overflow-hidden border border-slate-200 dark:border-white/10 opacity-70 mt-1">
                                    <img :src="coverPreview" class="w-full h-full object-cover">
                                </div>
                            </template>

                            <!-- Cover Preview (Existing) -->
                            <template x-if="!coverPreview">
                                @if(!empty($client->cover_image))
                                    <div class="h-12 w-full rounded-lg overflow-hidden border border-slate-200 dark:border-white/10 opacity-70 mt-1">
                                        <img src="{{ $client->coverUrl() }}" class="w-full h-full object-cover" onerror="this.style.display='none'">
                                    </div>
                                @endif
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Section -->
            <div class="space-y-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-px flex-grow bg-slate-100 dark:bg-white/5"></div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Personal Details</span>
                    <div class="h-px flex-grow bg-slate-100 dark:bg-white/5"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                    <div>
                        <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 ml-1 mb-1.5 block">Display Name</label>
                        <div class="relative group">
                            <span class="material-symbols-rounded absolute left-3 text-[18px] top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">person</span>
                            <input type="text" name="name" maxlength="60" value="{{ $client->name }}" class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-medium focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 ml-1 mb-1.5 block">Location</label>
                        <div class="relative group">
                            <span class="material-symbols-rounded absolute left-3 text-[18px] top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">public</span>
                            <input type="text" name="location" value="{{ $client->location }}" class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-medium focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none" placeholder="e.g. Mumbai, India">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Professional Section -->
            <div class="space-y-6 pt-4">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-px flex-grow bg-slate-100 dark:bg-white/5"></div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Professional Identity</span>
                    <div class="h-px flex-grow bg-slate-100 dark:bg-white/5"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                    <div>
                        <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 ml-1 mb-1.5 block">Core Profession</label>
                        <div class="relative group">
                            <span class="material-symbols-rounded absolute left-3 text-[18px] top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">work</span>
                            <select name="type" class="w-full pl-9 pr-8 py-2 bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-medium text-slate-800 dark:text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none appearance-none cursor-pointer">
                                @php
                                    $professions = [
                                        "Actor", "Actress", "Director", "Producer", "Executive Producer", 
                                        "Screenwriter", "Dialogue Writer", "Cinematographer (DOP)", "Editor", 
                                        "Colorist", "VFX Artist", "Motion Graphics Designer", "Thumbnail Designer", 
                                        "Music Director", "Singer", "Rap Artist", "Lyricist", "Sound Designer", 
                                        "Background Score Composer", "Choreographer", "Dance Crew", "Makeup Artist", 
                                        "Costume Designer", "Stylist", "Photographer", "Casting Director", 
                                        "Assistant Director", "Production Manager", "Line Producer", 
                                        "Short Film Creator", "Reel Creator", "YouTuber", "Influencer", 
                                        "Brand Collaborator", "OTT Partner", "Distributor", "Investor", 
                                        "Event Organizer", "Studio Owner", "Acting Trainer", "Film School", 
                                        "Voice Over Artist", "Dub Artist", "Anchor / Host", "Meme Creator", 
                                        "Marketing Partner"
                                    ];
                                @endphp
                                @foreach($professions as $prof)
                                    <option value="{{ $prof }}" {{ $client->type == $prof ? 'selected' : '' }}>{{ $prof }}</option>
                                @endforeach
                            </select>
                            <span class="material-symbols-rounded absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                        </div>
                    </div>
                    
                    @php
                        $rawExp = $client->years_of_experience ?? '';
                        $yearsOfExp = is_array($rawExp) ? implode(' ', $rawExp) : (string)$rawExp;
                        $initialMonths = '';
                        if (preg_match('/(\d+(\.\d+)?)\s*month/i', $yearsOfExp, $matches)) {
                            $initialMonths = round($matches[1]);
                        } elseif (preg_match('/(\d+(\.\d+)?)\s*year/i', $yearsOfExp, $matches)) {
                            $initialMonths = round($matches[1] * 12);
                        } else {
                            $cleanNum = trim(str_replace('+', '', $yearsOfExp));
                            if (is_numeric($cleanNum)) {
                                $initialMonths = round((float)$cleanNum * 12);
                            }
                        }
                    @endphp
                    <div x-data="{ 
                        experience_months: '{{ $initialMonths }}',
                        years_of_experience: '{{ $yearsOfExp }}',
                        expError: '',
                        updateExperience() {
                            this.expError = '';
                            if (this.experience_months && this.experience_months.length > 4) {
                                this.experience_months = this.experience_months.slice(0,4);
                            }
                            const months = parseInt(this.experience_months);
                            if (isNaN(months) || months <= 0) {
                                this.years_of_experience = '';
                                return;
                            }
                            if (months > 1200) {
                                this.expError = 'Experience cannot exceed 100 years.';
                                this.years_of_experience = '';
                                return;
                            }
                            if (months < 12) {
                                this.years_of_experience = months + (months == 1 ? ' month' : ' months');
                            } else {
                                const years = (months / 12).toFixed(1);
                                const yearsStr = years.endsWith('.0') ? Math.round(months / 12) : years;
                                this.years_of_experience = yearsStr + (yearsStr == 1 ? ' year' : ' years');
                            }
                        }
                    }">
                        <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 ml-1 mb-1.5 block">Prior Experience (in Months)</label>
                        <div class="relative group">
                            <span class="material-symbols-rounded absolute left-3 text-[18px] top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">history_edu</span>
                            <input type="number" name="experience_months" max="1200" x-model="experience_months" @input="updateExperience()" class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-medium focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none" :class="expError ? '!border-red-500 focus:!ring-red-500/20' : ''" placeholder="e.g. 8">
                        </div>
                        <input type="hidden" name="years_of_experience" x-bind:value="years_of_experience">
                        
                        <p x-show="expError" x-text="expError" x-cloak class="text-[10px] text-red-500 font-semibold mt-1 ml-1"></p>
                        
                        <!-- Real-time Dynamic Conversion Preview Badge -->
                        <div class="mt-3 pl-1" x-show="years_of_experience">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-500/10 text-indigo-500 rounded-lg text-[9px] font-black uppercase tracking-wider border border-indigo-500/20 ">
                                <span class="material-symbols-rounded text-xs">auto_awesome</span>
                                Converted: <span x-text="years_of_experience" class="font-extrabold text-slate-800 dark:text-white"></span>
                            </span>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 ml-1 mb-1.5 block">Skills (Comma Separated)</label>
                        <div class="relative group">
                            <span class="material-symbols-rounded absolute left-3 text-[18px] top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">psychology</span>
                            <input type="text" name="skills" value="{{ is_array($client->skills) ? implode(', ', $client->skills) : '' }}" class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-medium focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none" placeholder="e.g. Video Editing, UI Design">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dynamic Statistics Section -->
            <div class="space-y-6 pt-4">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-px flex-grow bg-slate-100 dark:bg-white/5"></div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Performance Metrics</span>
                    <div class="h-px flex-grow bg-slate-100 dark:bg-white/5"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                    <div>
                        <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 ml-1 mb-1.5 block">Projects Delivered</label>
                        <div class="relative group">
                            <span class="material-symbols-rounded absolute left-3 text-[18px] top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">task_alt</span>
                            <input type="number" name="projects_count" value="{{ $client->projects_count }}" class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-medium focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none" min="0">
                        </div>
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 ml-1 mb-1.5 block">Satisfaction Rate (%)</label>
                        <div class="relative group">
                            <span class="material-symbols-rounded absolute left-3 text-[18px] top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">star_rate</span>
                            <input type="number" name="satisfaction_rate" value="{{ $client->satisfaction_rate }}" class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-medium focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none" min="0" max="100" step="0.1">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Section -->
            <div class="space-y-6 pt-4">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-px flex-grow bg-slate-100 dark:bg-white/5"></div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Business Presence</span>
                    <div class="h-px flex-grow bg-slate-100 dark:bg-white/5"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                    <div>
                        <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 ml-1 mb-1.5 block">Business Name</label>
                        <div class="relative group">
                            <span class="material-symbols-rounded absolute left-3 text-[18px] top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">business</span>
                            <input type="text" name="business_name" value="{{ $client->business_name }}" class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-medium focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none">
                        </div>
                    </div>

                    @php
                        $predefinedBusinessTypes = ['individual', 'studio', 'production', 'agency', 'freelancer', 'other'];
                        $isCustomBusiness = $client->business_type && !in_array($client->business_type, $predefinedBusinessTypes);
                        $currentSelectValue = $isCustomBusiness ? 'other' : $client->business_type;
                        $customValue = $isCustomBusiness ? $client->business_type : '';
                    @endphp
                    <div x-data="{ 
                        businessType: '{{ $currentSelectValue }}',
                        customBusinessType: '{{ addslashes($customValue) }}'
                    }">
                        <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 ml-1 mb-1.5 block">Business Type</label>
                        <div class="relative group">
                            <span class="material-symbols-rounded absolute left-3 text-[18px] top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">category</span>
                            <select name="business_type" x-model="businessType" class="w-full pl-9 pr-8 py-2 bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-medium text-slate-800 dark:text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none appearance-none cursor-pointer">
                                <option value="">Select Business Type</option>
                                <option value="individual">Individual Creator</option>
                                <option value="studio">Small Studio</option>
                                <option value="production">Production Company</option>
                                <option value="agency">Agency</option>
                                <option value="freelancer">Freelancer</option>
                                <option value="other">Other</option>
                            </select>
                            <span class="material-symbols-rounded absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                        </div>

                        <div x-show="businessType === 'other'" x-transition class="mt-4 relative group">
                            <span class="material-symbols-rounded absolute left-3 text-[18px] top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">edit</span>
                            <input type="text" name="other_business_type" x-model="customBusinessType" :required="businessType === 'other'" class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-medium focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none" placeholder="Specify Business Type">
                        </div>
                    </div>
                    
                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 ml-1 mb-1.5 block">Website URL</label>
                            <div x-data="{ val: '{{ $client->website_url }}' }">
                                <div class="relative group">
                                    <span class="material-symbols-rounded absolute left-3 text-[18px] top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors" :class="val.length > 0 && !val.match(/^https?:\/\//) ? '!text-red-400' : ''">language</span>
                                    <input type="url" name="website_url" x-model="val" class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-medium focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none" :class="val.length > 0 && !val.match(/^https?:\/\//) ? '!border-red-500 focus:!border-red-500 focus:!ring-red-500/20' : ''" placeholder="https://">
                                </div>
                                <p x-show="val.length > 0 && !val.match(/^https?:\/\//)" x-cloak class="text-[10px] text-red-500 font-semibold mt-1 ml-1">Must start with http:// or https://</p>
                            </div>
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 ml-1 mb-1.5 block">Facebook Profile</label>
                            <div x-data="{ val: '{{ $client->facebook_link }}' }">
                                <div class="relative group">
                                    <i class="fab fa-facebook-f absolute left-4 text-[15px] top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors" :class="val.length > 0 && !val.match(/^https?:\/\//) ? '!text-red-400' : ''"></i>
                                    <input type="url" name="facebook_link" x-model="val" class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-medium focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none" :class="val.length > 0 && !val.match(/^https?:\/\//) ? '!border-red-500 focus:!border-red-500 focus:!ring-red-500/20' : ''" placeholder="https://facebook.com/...">
                                </div>
                                <p x-show="val.length > 0 && !val.match(/^https?:\/\//)" x-cloak class="text-[10px] text-red-500 font-semibold mt-1 ml-1">Must start with http:// or https://</p>
                            </div>
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 ml-1 mb-1.5 block">Instagram Profile</label>
                            <div x-data="{ val: '{{ $client->instagram_link }}' }">
                                <div class="relative group">
                                    <i class="fab fa-instagram absolute left-4 text-[15px] top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors" :class="val.length > 0 && !val.match(/^https?:\/\//) ? '!text-red-400' : ''"></i>
                                    <input type="url" name="instagram_link" x-model="val" class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-medium focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none" :class="val.length > 0 && !val.match(/^https?:\/\//) ? '!border-red-500 focus:!border-red-500 focus:!ring-red-500/20' : ''" placeholder="https://instagram.com/...">
                                </div>
                                <p x-show="val.length > 0 && !val.match(/^https?:\/\//)" x-cloak class="text-[10px] text-red-500 font-semibold mt-1 ml-1">Must start with http:// or https://</p>
                            </div>
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 ml-1 mb-1.5 block">Twitter Profile</label>
                            <div x-data="{ val: '{{ $client->twitter_link }}' }">
                                <div class="relative group">
                                    <i class="fab fa-twitter absolute left-4 text-[15px] top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors" :class="val.length > 0 && !val.match(/^https?:\/\//) ? '!text-red-400' : ''"></i>
                                    <input type="url" name="twitter_link" x-model="val" class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-medium focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none" :class="val.length > 0 && !val.match(/^https?:\/\//) ? '!border-red-500 focus:!border-red-500 focus:!ring-red-500/20' : ''" placeholder="https://twitter.com/...">
                                </div>
                                <p x-show="val.length > 0 && !val.match(/^https?:\/\//)" x-cloak class="text-[10px] text-red-500 font-semibold mt-1 ml-1">Must start with http:// or https://</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 ml-1 mb-1.5 block">Professional Bio</label>
                    <div class="relative group">
                        <span class="material-symbols-rounded absolute left-3 text-[18px] top-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors">description</span>
                        <textarea name="more_info" rows="5" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-medium focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none resize-none">{{ $client->more_info }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Section -->
            <div class="pt-4 flex justify-end">
                <button type="submit" class="group relative px-6 py-2.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-lg font-bold text-xs tracking-wide shadow hover:bg-indigo-600 dark:hover:bg-indigo-600 hover:text-white dark:hover:text-white transition-all active:scale-95">
                    Save Profile Changes
                    <div class="absolute inset-0 rounded-xl bg-indigo-500 opacity-0 group-hover:opacity-10 transition-opacity"></div>
                </button>
            </div>
        </form>
    </div>

    <!-- Danger Zone Card -->
    <div class="max-w-2xl mx-auto mt-8 bg-white dark:bg-[#111] rounded-xl border border-rose-500/10 dark:border-rose-500/20 bg-rose-500/[0.02] shadow-sm overflow-hidden p-5 md:p-6">
        <div class="flex items-center gap-2 mb-2 px-1">
            <span class="material-symbols-rounded text-rose-500 text-lg">dangerous</span>
            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-rose-500/80">Danger Zone</h3>
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <h4 class="text-xs font-bold text-slate-900 dark:text-white">Request Account Deletion</h4>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">This will submit a request to delete your professional marketplace profile, portfolios, galleries, and services permanently. This action is irreversible.</p>
            </div>
            <button type="button" @click="showDeleteModal = true"
                    class="px-5 py-2.5 bg-rose-500/10 hover:bg-rose-500 hover:text-white text-rose-500 rounded-xl font-bold uppercase tracking-widest text-[9px] transition-all border border-rose-500/20 shrink-0 self-start md:self-auto">
                Delete Account
            </button>
        </div>
    </div>

<!-- Native Modal -->
<div x-show="showDeleteModal" 
     class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     x-cloak>
    
    <div @click.away="showDeleteModal = false" 
         class="bg-white dark:bg-[#111] border border-slate-200/50 dark:border-white/5 rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-5 transform transition-all max-h-[85vh] overflow-y-auto scrollbar-hide"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-rose-500/10 text-rose-500 flex items-center justify-center">
                <span class="material-symbols-rounded">no_accounts</span>
            </div>
            <div>
                <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tight">Delete Profile</h3>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Termination Request</p>
            </div>
        </div>

        @if($errors->any() && (old('reason') || old('password')))
            <div class="bg-rose-50 dark:bg-rose-950/20 text-rose-500 px-4 py-2.5 rounded-xl text-xs font-semibold border border-rose-500/10">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('marketplace.profile.delete') }}" class="space-y-4" onsubmit="showServiceLoader('Submitting deletion request...')">
            @csrf

            <div class="space-y-2">
                <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">Why are you leaving?</label>
                <div class="space-y-2.5">
                    @php
                        $reasons = [
                            'Privacy concerns',
                            'Created a second profile',
                            'Marketplace is too busy',
                            'Other'
                        ];
                    @endphp
                    @foreach($reasons as $reason)
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="radio" name="reason" value="{{ $reason }}" x-model="deleteReason"
                                   class="w-4 h-4 rounded-full border-slate-300 text-rose-600 focus:ring-rose-500 bg-slate-50 dark:bg-black/30 dark:border-white/5">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">{{ $reason }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div x-show="deleteReason === 'Other'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="space-y-1.5" x-cloak>
                <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">Please describe your reason</label>
                <textarea name="custom_reason" rows="3" x-model="customReason"
                          class="w-full bg-slate-50/50 dark:bg-black/30 border border-slate-200/50 dark:border-white/5 rounded-xl px-4 py-3 font-semibold text-xs focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 focus:bg-white dark:focus:bg-black transition-all resize-none leading-relaxed text-slate-900 dark:text-white"
                          placeholder="Type details..."></textarea>
            </div>

            <div class="space-y-1.5 pt-2" x-data="{ showPassword: false }">
                <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">Confirm Password</label>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" name="password" required
                           class="w-full bg-slate-50/50 dark:bg-black/30 border border-slate-200/50 dark:border-white/5 rounded-xl pl-4 pr-10 py-3 font-semibold text-xs focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 focus:bg-white dark:focus:bg-black transition-all text-slate-900 dark:text-white"
                           placeholder="Enter your current password">
                    <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">
                        <span class="material-symbols-rounded text-sm" x-text="showPassword ? 'visibility_off' : 'visibility'">visibility</span>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-white/5">
                <button type="button" @click="showDeleteModal = false"
                        class="px-5 py-3 text-[9px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-[9px] font-black uppercase tracking-widest shadow-md shadow-rose-600/20 active:scale-95 transition-all">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>
</div>

