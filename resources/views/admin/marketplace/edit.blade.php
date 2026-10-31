@extends('admin.layouts.app')

@section('title', 'Update Creator')
@section('header_title', 'Marketplace Directory')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-24">
    <div class="flex items-center justify-between px-6 lg:px-0">
        <div>
            <h3 class="text-3xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Update Profile</h3>
            <p class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3">Edit creator profile for #{{ $marketplace->id }}</p>
        </div>
        <a href="{{ route('admin.marketplace.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-400 hover:text-rose-500 flex items-center justify-center transition-all shadow-sm active:scale-90">
            <span class="material-symbols-rounded text-xl">close</span>
        </a>
    </div>

    <form action="{{ route('admin.marketplace.update', $marketplace->id) }}" method="POST" enctype="multipart/form-data" 
          x-data="adminCreatorForm()" @submit="if($event.target.checkValidity()) { setTimeout(() => { if(!$event.defaultPrevented) synching = true; }, 50) }"
          class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Panel: Identity & Status -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Profile Image Card -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm group">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-tight">Profile Photo</h3>
                            <span class="material-symbols-rounded text-blue-500">account_circle</span>
                        </div>

                        <div class="relative aspect-square rounded-2xl border-2 border-dashed border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-white/[0.02] overflow-hidden flex flex-col items-center justify-center transition-all hover:border-blue-500/50 group/zone shadow-inner">
                            <div class="absolute inset-0 w-full h-full">
                                <img :src="imagePreview" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 group-hover/zone:opacity-100 transition-all flex flex-col items-center justify-center text-white gap-2">
                                    <span class="material-symbols-rounded text-3xl">add_a_photo</span>
                                    <p class="text-[10px] font-bold uppercase tracking-widest">Change Photo</p>
                                </div>
                            </div>
                            <input type="file" name="image" accept=".png, .jpg, .jpeg"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                   @change="const file = $event.target.files[0]; if (file) { if(file.size > 2 * 1024 * 1024) { window.adminSwal({icon: 'error', title: 'Validation Error', text: 'The image field must not be greater than 2048 kilobytes.', confirmButtonColor: '#ff3b30'}); $event.target.value = ''; return; } imagePreview = URL.createObjectURL(file); }">
                        </div>

                        @if($marketplace->image)
                            <div class="bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-white/5 rounded-xl p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest">R2 Image URL</span>
                                    <button type="button" @click="copyUrl('{{ $marketplace->image }}')" class="text-[9px] font-black text-blue-500 hover:text-blue-600 uppercase tracking-widest flex items-center gap-1">
                                        <span class="material-symbols-rounded text-sm">content_copy</span> Copy
                                    </button>
                                </div>
                                <p class="text-[10px] font-mono text-slate-500 dark:text-white/40 break-all leading-relaxed">{{ $marketplace->image }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Cover Image Card -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm group">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-tight">Cover Photo</h3>
                            <span class="material-symbols-rounded text-blue-500">wallpaper</span>
                        </div>

                        <div class="relative w-full h-24 rounded-2xl border-2 border-dashed border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-white/[0.02] overflow-hidden flex flex-col items-center justify-center transition-all hover:border-blue-500/50 group/zone shadow-inner">
                            <div class="absolute inset-0 w-full h-full">
                                <img :src="coverPreview" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 group-hover/zone:opacity-100 transition-all flex flex-col items-center justify-center text-white gap-2">
                                    <span class="material-symbols-rounded text-xl">add_photo_alternate</span>
                                    <p class="text-[10px] font-bold uppercase tracking-widest">Change Cover</p>
                                </div>
                            </div>
                            <input type="file" name="cover_image" accept=".png, .jpg, .jpeg"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                   @change="const file = $event.target.files[0]; if (file) { if(file.size > 2 * 1024 * 1024) { window.adminSwal({icon: 'error', title: 'Validation Error', text: 'The cover image field must not be greater than 2048 kilobytes.', confirmButtonColor: '#ff3b30'}); $event.target.value = ''; return; } coverPreview = URL.createObjectURL(file); }">
                        </div>

                        @if($marketplace->cover_image)
                            <div class="bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-white/5 rounded-xl p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest">R2 Cover URL</span>
                                    <button type="button" @click="copyUrl('{{ $marketplace->cover_image }}')" class="text-[9px] font-black text-blue-500 hover:text-blue-600 uppercase tracking-widest flex items-center gap-1">
                                        <span class="material-symbols-rounded text-sm">content_copy</span> Copy
                                    </button>
                                </div>
                                <p class="text-[10px] font-mono text-slate-500 dark:text-white/40 break-all leading-relaxed">{{ $marketplace->cover_image }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Category & Experience Card -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                                <span class="material-symbols-rounded text-lg">category</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Classification</h3>
                        </div>

                        <div class="space-y-4">
                            <x-select name="type" label="Category" required="true">
                                <option value="actor" {{ $marketplace->type == 'actor' ? 'selected' : '' }}>Actor</option>
                                <option value="influencer" {{ $marketplace->type == 'influencer' ? 'selected' : '' }}>Influencer</option>
                                <option value="investor" {{ $marketplace->type == 'investor' ? 'selected' : '' }}>Investor</option>
                            </x-select>
                            
                            <div x-data="{ 
                                experience_months: @php
                                    $expStr = old('years_of_experience', is_array($marketplace->years_of_experience) ? ($marketplace->years_of_experience[0] ?? '') : $marketplace->years_of_experience);
                                    $expMonths = '';
                                    if (str_contains(strtolower($expStr), 'year')) {
                                        $expMonths = floatval($expStr) * 12;
                                    } elseif (str_contains(strtolower($expStr), 'month')) {
                                        $expMonths = floatval($expStr);
                                    } else {
                                        $expMonths = floatval($expStr);
                                    }
                                    echo json_encode($expMonths ? $expMonths : '');
                                @endphp,
                                updateExperience() {
                                    const months = parseInt(this.experience_months);
                                    if (isNaN(months) || months <= 0) {
                                        form.years_of_experience = '';
                                        return;
                                    }
                                    if (months < 12) {
                                        form.years_of_experience = months + (months == 1 ? ' month' : ' months');
                                    } else {
                                        const years = (months / 12).toFixed(1);
                                        const yearsStr = years.endsWith('.0') ? Math.round(months / 12) : years;
                                        form.years_of_experience = yearsStr + (yearsStr == 1 ? ' year' : ' years');
                                    }
                                },
                                init() {
                                    if(this.experience_months) this.updateExperience();
                                }
                            }">
                                <x-input type="number" name="experience_months" label="Prior Experience (in Months)" placeholder="e.g. 8" required="true" icon="history_edu" hint="Enter your experience in months. We will convert it into years automatically." x-model="experience_months" @input="updateExperience()">
                                </x-input>
                                <input type="hidden" name="years_of_experience" x-bind:value="form.years_of_experience">
                                
                                <!-- Real-time Dynamic Conversion Preview Badge -->
                                <div class="mt-1.5 pl-1" x-show="form.years_of_experience">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-indigo-500/10 text-indigo-500 rounded-lg text-[8px] font-black uppercase tracking-wider border border-indigo-500/20 ">
                                        <span class="material-symbols-rounded text-[10px]">auto_awesome</span>
                                        Converted: <span x-text="form.years_of_experience" class="font-extrabold text-slate-800 dark:text-white"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Card -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5">
                        <div class="space-y-0.5">
                            <p class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-tight">Featured Profile</p>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Pin to Marketplace Top</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" {{ $marketplace->is_featured ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 dark:bg-white/10 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-500"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Personal & Business Details -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Personal Info Card -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                                <span class="material-symbols-rounded text-lg">person</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Personal Info</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-input name="name" label="Full Name" :value="old('name', $marketplace->name)" required="true" icon="badge" x-model="form.name" x-bind:class="isFieldInvalid('name') ? 'border-red-500 ring-4 ring-red-500/10' : ''" />
                            <div class="space-y-1">
                                <x-input type="email" name="email" label="Email Address" :value="old('email', $marketplace->email)" required="true" icon="mail" x-model="form.email" x-bind:class="isFieldInvalid('email') ? 'border-red-500 ring-4 ring-red-500/10' : ''" @input.debounce.500ms="checkAvailability('email', $event.target.value)" />
                                <p x-show="errors.email" x-text="errors.email" class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-1" style="display: none;"></p>
                            </div>
                            <div class="space-y-1">
                                <x-input name="number" label="Phone Number" :value="old('number', $marketplace->number)" placeholder="+1 (555) 000-0000" icon="phone" x-model="form.number" x-bind:class="isFieldInvalid('number') ? 'border-red-500 ring-4 ring-red-500/10' : ''" @input.debounce.500ms="checkAvailability('number', $event.target.value)" />
                                <p x-show="errors.number" x-text="errors.number" class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-1" style="display: none;"></p>
                            </div>
                            <x-input name="location" label="Location" :value="old('location', $marketplace->location)" placeholder="Mumbai, India" icon="location_on" x-model="form.location" x-bind:class="isFieldInvalid('location') ? 'border-red-500 ring-4 ring-red-500/10' : ''" />
                            
                            <div class="md:col-span-2">
                                <div x-data="{ show: false }">
                                    <x-input type="password" x-bind:type="show ? 'text' : 'password'" name="password" label="Account Password (Leave blank to keep unchanged)" icon="key" x-model="form.password" x-bind:class="isFieldInvalid('password') ? 'border-red-500 ring-4 ring-red-500/10' : ''">
                                        <x-slot name="append">
                                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-500 transition-colors">
                                                <span class="material-symbols-rounded text-xl" x-text="show ? 'visibility' : 'visibility_off'"></span>
                                            </button>
                                        </x-slot>
                                    </x-input>
                                </div>
                                
                                <!-- Real-time Dynamic Password Prevalidation Checklist -->
                                <div class="mt-3 p-3 bg-slate-50 dark:bg-white/[0.02] rounded-xl border border-slate-100 dark:border-white/5 space-y-1.5" x-show="form.password.length > 0" x-transition>
                                    <p class="text-[9px] font-black text-slate-450 dark:text-slate-400 uppercase tracking-widest mb-1.5">Password Requirements Checklist:</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 text-[9px] font-black uppercase tracking-wider">
                                        <div class="flex items-center gap-2 transition-all duration-300" :class="passwordRequirements.length ? 'text-emerald-500' : 'text-slate-400 dark:text-slate-600'">
                                            <span class="material-symbols-rounded text-sm" x-text="passwordRequirements.length ? 'check_circle' : 'pending'"></span>
                                            <span>Min 8 Characters</span>
                                        </div>
                                        <div class="flex items-center gap-2 transition-all duration-300" :class="passwordRequirements.uppercase ? 'text-emerald-500' : 'text-slate-400 dark:text-slate-600'">
                                            <span class="material-symbols-rounded text-sm" x-text="passwordRequirements.uppercase ? 'check_circle' : 'pending'"></span>
                                            <span>One Uppercase (A-Z)</span>
                                        </div>
                                        <div class="flex items-center gap-2 transition-all duration-300" :class="passwordRequirements.lowercase ? 'text-emerald-500' : 'text-slate-400 dark:text-slate-600'">
                                            <span class="material-symbols-rounded text-sm" x-text="passwordRequirements.lowercase ? 'check_circle' : 'pending'"></span>
                                            <span>One Lowercase (a-z)</span>
                                        </div>
                                        <div class="flex items-center gap-2 transition-all duration-300" :class="passwordRequirements.number ? 'text-emerald-500' : 'text-slate-400 dark:text-slate-600'">
                                            <span class="material-symbols-rounded text-sm" x-text="passwordRequirements.number ? 'check_circle' : 'pending'"></span>
                                            <span>One Number (0-9)</span>
                                        </div>
                                        <div class="flex items-center gap-2 transition-all duration-300" :class="passwordRequirements.symbol ? 'text-emerald-500' : 'text-slate-400 dark:text-slate-600'">
                                            <span class="material-symbols-rounded text-sm" x-text="passwordRequirements.symbol ? 'check_circle' : 'pending'"></span>
                                            <span>One Special Icon</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6" x-data="skillManager({{ json_encode(is_array($marketplace->skills) ? $marketplace->skills : explode(',', $marketplace->skills)) }})">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/30 px-1 mb-2">Talent Competencies (Skills)</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-6 pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                    <span class="material-symbols-rounded">psychology</span>
                                </div>
                                <input type="text" x-model="newSkill" @keydown.enter.prevent="addSkill" placeholder="Add a skill and press Enter..." 
                                       class="w-full h-16 pl-14 pr-32 rounded-2xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-white/[0.02] outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all text-[13px] font-bold text-slate-900 dark:text-white">
                                <button type="button" @click="addSkill" class="absolute right-3 top-3 bottom-3 px-6 bg-slate-900 dark:bg-blue-600 text-white rounded-xl font-bold text-[10px] uppercase tracking-widest shadow-lg active:scale-95 transition-all">Add Skill</button>
                            </div>
                            <input type="hidden" name="skills" x-bind:value="skills.join(',')">
                            <div class="flex flex-wrap gap-2 mt-4">
                                <template x-for="skill in skills" :key="skill">
                                    <span class="px-4 py-2 bg-blue-500/10 text-blue-600 rounded-xl text-[10px] font-bold flex items-center gap-2 uppercase tracking-widest border border-blue-500/20 group">
                                        <span x-text="skill"></span>
                                        <button type="button" @click="removeSkill(skill)" class="hover:text-red-500 transition-colors">
                                            <span class="material-symbols-rounded text-sm">close</span>
                                        </button>
                                    </span>
                                </template>
                                <template x-if="skills.length === 0">
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest px-1 ">No skills defined yet.</p>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Business & Social Card -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                                <span class="material-symbols-rounded text-lg">business_center</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Business & Social</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-input name="business_name" label="Business Name" :value="old('business_name', $marketplace->business_name)" placeholder="Agency Name" icon="business" />
                            <x-select name="business_type" label="Business Type">
                                <option value="" {{ $marketplace->business_type == '' ? 'selected' : '' }}>Select Type</option>
                                <option value="individual" {{ $marketplace->business_type == 'individual' ? 'selected' : '' }}>Individual</option>
                                <option value="agency" {{ $marketplace->business_type == 'agency' ? 'selected' : '' }}>Agency</option>
                                <option value="company" {{ $marketplace->business_type == 'company' ? 'selected' : '' }}>Company</option>
                            </x-select>
                            <x-input type="url" name="portfolio_url" label="Portfolio Link" :value="old('portfolio_url', $marketplace->portfolio_url)" placeholder="https://..." icon="link" />
                            <x-input type="url" name="website_url" label="Website URL" :value="old('website_url', $marketplace->website_url)" placeholder="https://..." icon="language" />
                            <x-input type="url" name="facebook_link" label="Facebook Link" :value="old('facebook_link', $marketplace->facebook_link)" placeholder="https://..." icon="la la-facebook-f" />
                            <x-input type="url" name="instagram_link" label="Instagram URL" :value="old('instagram_link', $marketplace->instagram_link)" placeholder="https://..." icon="la la-instagram" />
                            <x-input type="url" name="twitter_link" label="Twitter Link" :value="old('twitter_link', $marketplace->twitter_link)" placeholder="https://..." icon="la la-twitter" />
                        </div>

                        <x-textarea name="more_info" label="Bio / Description" placeholder="Update talent description..." rows="6" icon="description">{{ old('more_info', $marketplace->more_info) }}</x-textarea>

                        <div class="flex pt-6 border-t border-slate-100 dark:border-white/5">
                            <button type="submit" :disabled="synching" 
                                    class="w-full h-16 rounded-2xl bg-blue-600 text-white text-sm font-bold uppercase tracking-widest hover:bg-blue-700 active:scale-95 transition-all flex items-center justify-center gap-3 disabled:opacity-50 disabled:grayscale disabled:cursor-not-allowed shadow-lg shadow-blue-500/20">
                                <span x-show="!synching" class="material-symbols-rounded">sync</span>
                                <span x-show="synching" class="material-symbols-rounded animate-spin">sync</span>
                                <span x-text="synching ? 'Updating Profile...' : 'Update Creator Profile'">Update Creator Profile</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('script')
<script>
    function adminCreatorForm() {
        return {
            synching: false, 
            imagePreview: @js($marketplace->photoUrl()),
            coverPreview: @js($marketplace->coverUrl()),
            copyUrl(url) {
                navigator.clipboard.writeText(url).then(() => {
                    window.adminSwal({ icon: 'success', title: 'Copied', text: 'R2 URL copied to clipboard.', confirmButtonColor: '#10b981' });
                });
            },
            form: {
                name: @json(old('name', $marketplace->name)),
                email: @json(old('email', $marketplace->email)),
                password: '',
                number: @json(old('number', $marketplace->number)),
                location: @json(old('location', $marketplace->location)),
                years_of_experience: @json(old('years_of_experience', is_array($marketplace->years_of_experience) ? ($marketplace->years_of_experience[0] ?? '') : $marketplace->years_of_experience)),
                originalEmail: @json($marketplace->email),
                originalNumber: @json($marketplace->number)
            },
            errors: {
                email: '',
                number: ''
            },
            get passwordRequirements() {
                const p = this.form.password || '';
                return {
                    length: p.length >= 8,
                    uppercase: /[A-Z]/.test(p),
                    lowercase: /[a-z]/.test(p),
                    number: /[0-9]/.test(p),
                    symbol: /[!@#$%^&*(),.?":{}|<>]/.test(p)
                };
            },
            validateEmail(email) {
                const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                return re.test(String(email).toLowerCase());
            },
            isFieldInvalid(field) {
                const value = this.form[field];
                if (value === undefined || value === null) return false;
                
                if (!value && field !== 'type') return false; 

                if (field === 'email') {
                    if (value.length > 0 && !this.validateEmail(value)) return true;
                    return !!this.errors.email;
                }
                if (field === 'number') {
                    return !!this.errors.number;
                }
                if (field === 'password') {
                    if (value.length === 0) return false;
                    const req = this.passwordRequirements;
                    return !req.length || !req.uppercase || !req.lowercase || !req.number || !req.symbol;
                }
                
                return false;
            },
            checkAvailability(field, value) {
                if (!value) {
                    this.errors[field] = '';
                    return;
                }
                if (field === 'email' && !this.validateEmail(value)) return;
                
                // Allow original values for edit form
                if (field === 'email' && value === this.form.originalEmail) {
                    this.errors[field] = '';
                    return;
                }
                if (field === 'number' && value === this.form.originalNumber) {
                    this.errors[field] = '';
                    return;
                }
                
                this.errors[field] = '';
                fetch('{{ route('marketplace.check-availability') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ field: field, value: value })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        this.errors[field] = data.error;
                    } else {
                        this.errors[field] = '';
                    }
                });
            }
        }
    }

    function skillManager(initialSkills = []) {
        return {
            newSkill: '',
            skills: initialSkills.filter(s => s && s.trim() !== ''),
            addSkill() {
                if (this.newSkill.trim() && !this.skills.includes(this.newSkill.trim())) {
                    this.skills.push(this.newSkill.trim());
                    this.newSkill = '';
                }
            },
            removeSkill(skill) {
                this.skills = this.skills.filter(s => s !== skill);
            }
        }
    }
</script>
@endpush

