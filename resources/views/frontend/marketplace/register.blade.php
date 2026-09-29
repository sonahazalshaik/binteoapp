@extends('layouts.app')

@section('content')
<style>
    /* Force hide global layout components */
    #navigation-header, 
    #desktop-sidebar, 
    footer, 
    .lg\:hidden.fixed.bottom-0,
    #mobile-sidebar-wrapper {
        display: none !important;
    }

    /* Reset main layout constraints */
    main {
        margin-left: 0 !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        min-height: 100vh !important;
    }

    /* Scrollbar refinement */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>

<div class="min-h-screen bg-[#fafafa] dark:bg-[#0A0A0A] font-sans text-slate-900 dark:text-white flex flex-col">
    <!-- Top Utility Nav -->
    <div class="bg-white dark:bg-[#111] border-b border-slate-100 dark:border-white/5 px-6 py-3 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center gap-6">
            <a href="javascript:history.back()" class="flex items-center gap-2 text-[10px] font-black text-slate-400 hover:text-slate-900 transition-all uppercase tracking-widest">
                <span class="material-symbols-rounded text-lg">arrow_back</span>
                Back
            </a>
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-[10px] font-black text-slate-400 hover:text-slate-900 transition-all uppercase tracking-widest">
                <span class="material-symbols-rounded text-lg">home</span>
                Home
            </a>
        </div>
        <a href="{{ route('marketplace.index') }}" class="flex items-center gap-2 text-[10px] font-black text-rose-500 hover:text-rose-600 transition-all uppercase tracking-widest">
            <span class="material-symbols-rounded text-lg">close</span>
            Cancel
        </a>
    </div>

    <div class="flex-1 flex flex-col items-center justify-start py-6 sm:py-8 px-4 sm:px-6 overflow-y-auto custom-scrollbar" x-data="registrationForm()">
        <div class="w-full max-w-sm sm:max-w-xl md:max-w-2xl lg:max-w-3xl pb-16 lg:pb-0">
            <!-- Brand Header -->
            <div class="text-center mb-4 sm:mb-5">
                <div class="w-8 h-8 sm:w-9 sm:h-9 bg-gradient-to-br from-orange-500 to-rose-600 rounded-lg sm:rounded-xl flex items-center justify-center mx-auto mb-2 shadow-lg shadow-rose-500/30">
                    <span class="material-symbols-rounded text-white text-base sm:text-lg">shopping_bag</span>
                </div>
                <h1 class="text-lg sm:text-xl font-black tracking-tight mb-0.5">Join {{ gs('site_name') }} Marketplace</h1>
                <p class="text-slate-400 font-medium text-[10px] sm:text-[11px]">Create your creator account and start monetizing</p>
            </div>

            <!-- Stepper -->
            <div class="flex items-center justify-center mb-6 sm:mb-8 max-w-sm mx-auto px-6 sm:px-10">
                <div class="relative w-full flex items-center justify-between">
                    <!-- Unified Background Track (More Visible) -->
                    <div class="absolute top-1/2 -translate-y-1/2 left-0 w-full h-[2px] bg-slate-300/50 dark:bg-white/10 rounded-full z-0"></div>
                    
                    <!-- Unified Dynamic Progress Track (High Contrast) -->
                    <div class="absolute top-1/2 -translate-y-1/2 left-0 h-[2px] bg-gradient-to-r from-orange-500 via-rose-500 to-rose-600 shadow-[0_0_15px_rgba(244,63,94,0.4)] transition-all duration-700 rounded-full z-10"
                         x-bind:style="'width: ' + (step == 1 ? '0%' : (step == 2 ? '50%' : '100%'))"></div>

                    <!-- Step 1 Badge -->
                    <div class="relative z-20">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center text-[10px] sm:text-[11px] font-black transition-all duration-500"
                             x-bind:class="step >= 1 ? 'bg-gradient-to-br from-orange-500 to-rose-600 text-white shadow-lg shadow-rose-500/30 ring-2 ring-white dark:ring-[#151515]' : 'bg-white dark:bg-slate-800 text-slate-400 border border-slate-200 dark:border-white/10'">
                            1
                        </div>
                    </div>

                    <!-- Step 2 Badge -->
                    <div class="relative z-20">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center text-[10px] sm:text-[11px] font-black transition-all duration-500"
                             x-bind:class="step >= 2 ? 'bg-gradient-to-br from-orange-500 to-rose-600 text-white shadow-lg shadow-rose-500/30 ring-2 ring-white dark:ring-[#151515]' : 'bg-white dark:bg-slate-800 text-slate-400 border border-slate-200 dark:border-white/10'">
                            2
                        </div>
                    </div>

                    <!-- Step 3 Badge -->
                    <div class="relative z-20">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center text-[10px] sm:text-[11px] font-black transition-all duration-500"
                             x-bind:class="step >= 3 ? 'bg-gradient-to-br from-orange-500 to-rose-600 text-white shadow-lg shadow-rose-500/30 ring-2 ring-white dark:ring-[#151515]' : 'bg-white dark:bg-slate-800 text-slate-400 border border-slate-200 dark:border-white/10'">
                            3
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration Card -->
            <div class="register-form bg-white dark:bg-[#151515] rounded-xl sm:rounded-2xl lg:rounded-3xl shadow-[0_10px_60px_rgba(0,0,0,0.04)] border border-slate-100 dark:border-white/5 p-3 sm:p-4 lg:p-6">
                <form action="{{ route('marketplace.register') }}" method="POST" id="registrationForm" novalidate @submit.prevent="handleSubmit($event)">
                    @csrf
                    
                    <!-- Step 1: Personal Information -->
                    <div x-show="step == 1" x-ref="step1" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="text-center mb-6">
                            <h2 class="text-lg sm:text-xl font-black text-slate-900 dark:text-white tracking-tight">Personal Information</h2>
                            <p class="text-slate-400 font-medium text-[10px] tracking-[0.2em] uppercase mt-1">Step 01: Basic Details</p>
                        </div>

                        <div class="grid grid-cols-2 gap-x-3 sm:gap-x-6">
                            <div class="space-y-1">
                                <x-input name="name" maxlength="60" label="Full Name" placeholder="Full name" required="true" icon="person" hint="Enter your name as it appears on official documents. Only letters, spaces, dots, hyphens and apostrophes allowed." x-model="form.name" x-bind:class="isFieldInvalid('name') ? 'border-red-500 ring-4 ring-red-500/10' : ''" />
                                <p x-show="errors.name" x-text="errors.name" class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-1" style="display: none;"></p>
                            </div>
                            <div class="space-y-1">
                                <x-input name="email" label="Email Address" type="email" placeholder="Email" required="true" icon="mail" hint="Primary channel for system notifications." x-model="form.email" x-bind:class="isFieldInvalid('email') ? 'border-red-500 ring-4 ring-red-500/10' : ''" @input.debounce.500ms="checkAvailability('email', $event.target.value)" />
                                <p x-show="errors.email" x-text="errors.email" class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-1" style="display: none;"></p>
                            </div>
                            
                            <div x-data="{ show: false }">
                                <x-input type="password" x-bind:type="show ? 'text' : 'password'" name="password" label="Password" placeholder="Password" required="true" icon="lock" hint="Minimum 8 characters with high entropy recommended." x-model="form.password" x-bind:class="isFieldInvalid('password') ? 'border-red-500 ring-4 ring-red-500/10' : ''">
                                    <x-slot name="append">
                                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-orange-500 transition-colors">
                                            <span class="material-symbols-rounded text-xl" x-text="show ? 'visibility' : 'visibility_off'"></span>
                                        </button>
                                    </x-slot>
                                </x-input>

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

                            <div x-data="{ show: false }">
                                <x-input type="password" x-bind:type="show ? 'text' : 'password'" name="password_confirmation" label="Confirm Password" placeholder="Confirm" required="true" icon="verified_user" hint="Repeat your access protocol to verify accuracy." x-model="form.password_confirmation" x-bind:class="isFieldInvalid('password_confirmation') ? 'border-red-500 ring-4 ring-red-500/10' : ''">
                                    <x-slot name="append">
                                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-orange-500 transition-colors">
                                            <span class="material-symbols-rounded text-xl" x-text="show ? 'visibility' : 'visibility_off'"></span>
                                        </button>
                                    </x-slot>
                                </x-input>
                            </div>

                            <div class="space-y-1">
                                <x-input name="number" label="Phone Number" placeholder="9876543210" required="true" icon="phone" hint="Enter 10 digits starting with 6-9 or 11 digits starting with 0." x-model="form.number" x-bind:class="isFieldInvalid('number') ? 'border-red-500 ring-4 ring-red-500/10' : ''" @input="form.number = String(form.number).replace(/\D/g, '').substring(0, 11)" @input.debounce.500ms="checkAvailability('number', $event.target.value)" />
                                <p x-show="errors.number" x-text="errors.number" class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-1" style="display: none;"></p>
                                <p x-show="form.number && !validateNumber(form.number) && !errors.number" class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-1">Enter 10 digits starting with 6-9 or 11 digits starting with 0</p>
                                <p x-show="form.number" class="px-1 text-[9px] font-bold uppercase tracking-widest"
                                   :class="validateNumber(form.number) ? 'text-emerald-500' : 'text-rose-500'">
                                    <span x-text="digitCount"></span> / 11 digits
                                    <span>• <span x-text="11 - digitCount"></span> left</span>
                                </p>
                            </div>
                            <div class="space-y-1">
                                <x-input name="location" label="Location" placeholder="Location" required="true" icon="public" hint="Primary operating region for taxation and logistics. Only letters, spaces, commas, dots, hyphens." x-model="form.location" x-bind:class="isFieldInvalid('location') ? 'border-red-500 ring-4 ring-red-500/10' : ''" />
                                <p x-show="errors.location" x-text="errors.location" class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-1" style="display: none;"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Professional Details -->
                    <div x-show="step == 2" x-ref="step2" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        <div class="text-center mb-6">
                            <h2 class="text-lg sm:text-xl font-black text-slate-900 dark:text-white tracking-tight">Professional Profile</h2>
                            <p class="text-slate-400 font-medium text-[10px] tracking-[0.2em] uppercase mt-1">Step 02: Experience & Skills</p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-x-3 sm:gap-x-6">
                            <x-select name="type" label="Core Profession" required="true" icon="work" hint="Primary role within the marketplace ecosystem." x-model="form.type">
                                <option value="Actor">Actor</option>
                                <option value="Actress">Actress</option>
                                <option value="Director">Director</option>
                                <option value="Producer">Producer</option>
                                <option value="Executive Producer">Executive Producer</option>
                                <option value="Screenwriter">Screenwriter</option>
                                <option value="Dialogue Writer">Dialogue Writer</option>
                                <option value="Cinematographer (DOP)">Cinematographer (DOP)</option>
                                <option value="Editor">Editor</option>
                                <option value="Colorist">Colorist</option>
                                <option value="VFX Artist">VFX Artist</option>
                                <option value="Motion Graphics Designer">Motion Graphics Designer</option>
                                <option value="Thumbnail Designer">Thumbnail Designer</option>
                                <option value="Music Director">Music Director</option>
                                <option value="Singer">Singer</option>
                                <option value="Rap Artist">Rap Artist</option>
                                <option value="Lyricist">Lyricist</option>
                                <option value="Sound Designer">Sound Designer</option>
                                <option value="Background Score Composer">Background Score Composer</option>
                                <option value="Choreographer">Choreographer</option>
                                <option value="Dance Crew">Dance Crew</option>
                                <option value="Makeup Artist">Makeup Artist</option>
                                <option value="Costume Designer">Costume Designer</option>
                                <option value="Stylist">Stylist</option>
                                <option value="Photographer">Photographer</option>
                                <option value="Casting Director">Casting Director</option>
                                <option value="Assistant Director">Assistant Director</option>
                                <option value="Production Manager">Production Manager</option>
                                <option value="Line Producer">Line Producer</option>
                                <option value="Short Film Creator">Short Film Creator</option>
                                <option value="Reel Creator">Reel Creator</option>
                                <option value="YouTuber">YouTuber</option>
                                <option value="Influencer">Influencer</option>
                                <option value="Brand Collaborator">Brand Collaborator</option>
                                <option value="OTT Partner">OTT Partner</option>
                                <option value="Distributor">Distributor</option>
                                <option value="Investor">Investor</option>
                                <option value="Event Organizer">Event Organizer</option>
                                <option value="Studio Owner">Studio Owner</option>
                                <option value="Acting Trainer">Acting Trainer</option>
                                <option value="Film School">Film School</option>
                                <option value="Voice Over Artist">Voice Over Artist</option>
                                <option value="Dub Artist">Dub Artist</option>
                                <option value="Anchor / Host">Anchor / Host</option>
                                <option value="Meme Creator">Meme Creator</option>
                                <option value="Marketing Partner">Marketing Partner</option>
                            </x-select>

                            <div x-data="{ 
                                experience_months: '',
                                expError: '',
                                updateExperience() {
                                    this.expError = '';
                                    if (this.experience_months && this.experience_months.length > 4) {
                                        this.experience_months = this.experience_months.slice(0,4);
                                    }
                                    const months = parseInt(this.experience_months);
                                    if (isNaN(months) || months <= 0) {
                                        form.years_of_experience = '';
                                        return;
                                    }
                                    if (months > 1200) {
                                        this.expError = 'Experience cannot exceed 100 years.';
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
                                }
                            }">
                                <x-input type="number" name="experience_months" max="1200" label="Prior Experience (in Months)" placeholder="e.g. 8" required="true" icon="history_edu" hint="Enter your experience in months. We will convert it into years automatically." x-model="experience_months" @input="updateExperience()" x-bind:class="isFieldInvalid('years_of_experience') || expError ? 'border-red-500 ring-4 ring-red-500/10' : ''">
                                </x-input>
                                <input type="hidden" name="years_of_experience" x-bind:value="form.years_of_experience">
                                <p x-show="expError" x-text="expError" class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-1 mt-1" style="display: none;"></p>
                                
                                <!-- Real-time Dynamic Conversion Preview Badge -->
                                <div class="mt-1.5 pl-1" x-show="form.years_of_experience">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-indigo-500/10 text-indigo-500 rounded-lg text-[8px] font-black uppercase tracking-wider border border-indigo-500/20 ">
                                        <span class="material-symbols-rounded text-[10px]">auto_awesome</span>
                                        Converted: <span x-text="form.years_of_experience" class="font-extrabold text-slate-800 dark:text-white"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <x-textarea name="bio" label="About You (Bio)" rows="4" placeholder="Tell us about your professional journey..." required="true" icon="description" hint="Brief summary highlighting key achievements and specialization." x-model="form.bio" x-bind:class="isFieldInvalid('bio') ? 'border-red-500 ring-4 ring-red-500/10' : ''" />

                        <div class="mb-10" x-data="skillManager()">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/30 px-1 mb-2">Your Skills</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-6 pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                                    <span class="material-symbols-rounded">psychology</span>
                                </div>
                                <input type="text" x-model="newSkill" @keydown.enter.prevent="addSkill" placeholder="Inject new skill..." 
                                       class="w-full h-12 pl-14 pr-32 rounded-2xl border border-slate-200/60 dark:border-white/5 bg-white dark:bg-black/20 outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all text-[12px] font-black text-slate-900 dark:text-white">
                                <button type="button" @click="addSkill" class="absolute right-2.5 top-2.5 bottom-2.5 px-5 bg-slate-900 dark:bg-orange-500 text-white rounded-xl font-black text-[9px] uppercase tracking-widest shadow-lg active:scale-95 transition-all">Add</button>
                            </div>
                            <input type="hidden" name="skills" x-bind:value="skills.join(',')">
                            <div class="flex flex-wrap gap-1.5 mt-3">
                                <template x-for="skill in skills" :key="skill">
                                    <span class="px-3 py-1.5 bg-orange-500/10 text-orange-600 rounded-xl text-[9px] font-black flex items-center gap-1.5 uppercase tracking-widest border border-orange-500/20 group">
                                        <span x-text="skill"></span>
                                        <button type="button" @click="removeSkill(skill)" class="hover:text-red-500 transition-colors">
                                            <span class="material-symbols-rounded text-sm">close</span>
                                        </button>
                                    </span>
                                </template>
                                <template x-if="skills.length === 0">
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest px-1 ">No competencies indexed yet.</p>
                                </template>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-x-3 sm:gap-x-6">
                            <div class="space-y-1">
                                <x-input type="url" name="portfolio_url" label="Digital Portfolio" placeholder="https://portfolio.com" icon="link" hint="External link to verified creative works." x-model="form.portfolio_url" x-bind:class="isFieldInvalid('portfolio_url') ? 'border-red-500 ring-4 ring-red-500/10' : ''" />
                                <p x-show="form.portfolio_url && !validateUrl(form.portfolio_url)" class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-1">Enter a valid URL (e.g. https://portfolio.com)</p>
                            </div>
                            <div class="space-y-1">
                                <x-input type="url" name="website_url" label="Official Domain" placeholder="https://website.com" icon="language" hint="Authorized business or personal website." x-model="form.website_url" x-bind:class="isFieldInvalid('website_url') ? 'border-red-500 ring-4 ring-red-500/10' : ''" />
                                <p x-show="form.website_url && !validateUrl(form.website_url)" class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-1">Enter a valid URL (e.g. https://website.com)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Finalize Account -->
                    <div x-show="step == 3" x-ref="step3" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        <div class="text-center mb-6">
                            <h2 class="text-lg sm:text-xl font-black text-slate-900 dark:text-white tracking-tight">Finalize Account</h2>
                            <p class="text-slate-400 font-medium text-[10px] tracking-[0.2em] uppercase mt-1">Step 03: Business Details & Agreements</p>
                        </div>
                        
                        <div class="space-y-4">
                            <x-input name="business_name" label="Business Name" placeholder="Brand name" required="true" icon="business" hint="Legal name of your brand or agency." x-model="form.business_name" x-bind:class="isFieldInvalid('business_name') ? 'border-red-500 ring-4 ring-red-500/10' : ''" />
                            
                            <x-select name="business_type" label="Business Type" required="true" icon="category" hint="Select the category that best describes your operation." x-model="form.business_type">
                                <option value="">Select business type</option>
                                <option value="individual">Individual Creator</option>
                                <option value="studio">Small Studio</option>
                                <option value="production">Production Company</option>
                                <option value="agency">Agency</option>
                                <option value="freelancer">Freelancer</option>
                                <option value="other">Other</option>
                            </x-select>

                            <div x-show="form.business_type === 'other'" x-transition class="pt-2">
                                <x-input name="other_business_type" label="Specify Business Type" placeholder="e.g. Talent Manager" required="true" icon="edit" hint="Please specify your exact business type." x-model="form.other_business_type" x-bind:class="isFieldInvalid('other_business_type') ? 'border-red-500 ring-4 ring-red-500/10' : ''" />
                            </div>

                            <div class="space-y-3 bg-slate-50 dark:bg-white/5 p-4 rounded-xl border border-slate-100 dark:border-white/5">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="agree_terms" x-model="agreeTerms" required class="w-4 h-4 rounded-md border-slate-200 dark:border-white/10 text-orange-500 focus:ring-orange-500/20 transition-all cursor-pointer">
                                    </div>
                                    <span class="text-[9px] font-black text-slate-500 group-hover:text-slate-900 dark:group-hover:text-white transition-all uppercase tracking-widest leading-loose">
                                    @php
                                        $allPolicies = getContent('policy_pages.element');
                                        $termsPolicy = $allPolicies->filter(fn($p) => str_contains(strtolower($p->data_values->title), 'terms'))->first();
                                        $privacyPolicy = $allPolicies->filter(fn($p) => str_contains(strtolower($p->data_values->title), 'privacy'))->first();
                                        
                                        $termsLink = $termsPolicy ? route('policy.pages', [$termsPolicy->id, slug($termsPolicy->data_values->title)]) : 'javascript:void(0)';
                                        $privacyLink = $privacyPolicy ? route('policy.pages', [$privacyPolicy->id, slug($privacyPolicy->data_values->title)]) : 'javascript:void(0)';
                                    @endphp
                                    <label class="form-check-label text-[9px] text-gray-500 dark:text-gray-400" for="terms">
                                        I agree to the <a href="javascript:void(0)" onclick="showPremiumTerms('{{ $termsLink }}', 'Terms of Service')" class="text-orange-500 hover:underline">Terms of Service</a> & <a href="javascript:void(0)" onclick="showPremiumTerms('{{ $privacyLink }}', 'Privacy Policy')" class="text-orange-500 hover:underline">Privacy Policy</a> *
                                    </label>
                                    </span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="agree_creator_terms" x-model="agreeCreatorTerms" required class="w-4 h-4 rounded-md border-slate-200 dark:border-white/10 text-orange-500 focus:ring-orange-500/20 transition-all cursor-pointer">
                                    </div>
                                    <span class="text-[9px] font-black text-slate-500 group-hover:text-slate-900 dark:group-hover:text-white transition-all uppercase tracking-widest leading-loose">
                                        I agree to the <a href="javascript:void(0)" onclick="showPremiumTerms('{{ route('marketplace.terms') }}', 'Marketplace Terms & Conditions')" class="text-orange-500 hover:underline">Marketplace Terms & Conditions</a> *
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between mt-6 pt-6 border-t border-slate-50 dark:border-white/5 gap-3">
                        <button type="button" @click="prevStep" x-show="step > 1" 
                                class="order-2 sm:order-1 px-6 py-3 rounded-xl border border-slate-100 dark:border-white/5 text-slate-400 font-black text-[10px] uppercase tracking-[0.2em] hover:bg-slate-50 dark:hover:bg-white/5 transition-all text-center">
                            Previous
                        </button>
                        <div x-show="step == 1" class="hidden sm:block"></div>
                        
                        <button type="button" @click="nextStep" x-show="step < 3" 
                                x-bind:disabled="step == 1 ? !isStep1Valid : (step == 2 ? !isStep2Valid : false)"
                                x-bind:class="(step == 1 && !isStep1Valid) || (step == 2 && !isStep2Valid) ? 'opacity-50 cursor-not-allowed grayscale' : 'hover:scale-[1.02] active:scale-95'"
                                class="order-1 sm:order-2 px-10 sm:px-14 py-4 bg-[#ff3b30] text-white rounded-xl font-black text-[11px] uppercase tracking-[0.2em] shadow-xl shadow-rose-500/20 transition-all text-center">
                            Next
                        </button>
                        
                        <button type="submit" x-show="step == 3" 
                                x-bind:disabled="!isStep3Valid" 
                                x-bind:class="!isStep3Valid ? 'opacity-50 cursor-not-allowed grayscale' : 'hover:scale-[1.02] active:scale-95'" 
                                class="order-1 sm:order-2 px-10 sm:px-14 py-4 bg-[#ff3b30] text-white rounded-xl font-black text-[11px] uppercase tracking-[0.2em] shadow-xl shadow-rose-500/20 transition-all text-center">
                            Create Account
                        </button>
                    </div>
                </form>
            </div>
            
            {{-- Extra Mobile Spacing --}}
            <div class="lg:hidden h-24"></div>
        </div>
    </div>
</div>

<style>
    /* Compact input overrides for registration form */
    .register-form .mb-10 { margin-bottom: 1rem !important; }
    
    .register-form input:not([type="checkbox"]) {
        font-weight: 700 !important;
        padding-right: 0.5rem !important;
    }

    .register-form input[name="password"], 
    .register-form input[name="password_confirmation"] {
        padding-right: 2.5rem !important;
    }

    @media (max-width: 1024px) {
        .register-form input.w-full.h-16,
        .register-form input[id] { 
            height: 3rem !important; 
            font-size: 0.8rem !important; 
            padding-top: 0.5rem !important; 
            padding-bottom: 0.5rem !important; 
        }
        .register-form .rounded-2xl { border-radius: 0.75rem !important; }
        .register-form .text-xl.material-symbols-rounded.absolute { font-size: 1rem !important; }
        .register-form .text-[13px] { font-size: 0.75rem !important; }
        .register-form label.block.text-[10px] { font-size: 8px !important; margin-bottom: 4px !important; }
        .register-form .mt-2.text-[10px] { font-size: 8px !important; }
        .register-form .pl-12 { padding-left: 2rem !important; }
        .register-form .pl-14 { padding-left: 3.5rem !important; }
        .register-form .left-4 { left: 0.75rem !important; }
    }

    @media (min-width: 1025px) {
        .register-form input.w-full.h-16,
        .register-form input[id] { height: 3.25rem !important; font-size: 0.85rem !important; }
        .register-form .rounded-2xl { border-radius: 0.85rem !important; }
        .register-form .text-xl.material-symbols-rounded.absolute { font-size: 1.1rem !important; }
        .register-form .text-[13px] { font-size: 0.85rem !important; }
        .register-form label.block.text-[10px] { font-size: 9px !important; }
        .register-form .mt-2.text-[10px] { font-size: 9px !important; }
        .register-form .pl-12 { padding-left: 2.25rem !important; }
        .register-form .pl-14 {padding-left: 3.5rem !important; }
        .register-form .left-4 { left: 0.85rem !important; }
    }
    .form-select-premium-compact {
        @apply w-full h-11 px-4 rounded-xl bg-slate-50 dark:bg-black/20 border border-slate-100 dark:border-white/5 outline-none focus:ring-4 focus:ring-purple-500/5 focus:border-purple-500/50 transition-all text-sm font-medium dark:text-white appearance-none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1rem;
    }
    .form-textarea-premium-compact {
        @apply w-full p-4 rounded-xl bg-slate-50 dark:bg-black/20 border border-slate-100 dark:border-white/5 outline-none focus:ring-4 focus:ring-purple-500/5 focus:border-purple-500/50 transition-all text-sm font-medium dark:text-white;
    }
</style>

<script>
    function registrationForm() {
        return {
            step: 1,
            agreeTerms: false,
            agreeCreatorTerms: false,
            form: {
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                number: '',
                location: '',
                type: 'actor',
                years_of_experience: '',
                bio: '',
                portfolio_url: '',
                website_url: '',
                business_name: '',
                business_type: '',
                other_business_type: ''
            },
            errors: {},
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
            get digitCount() {
                return String(this.form.number || '').replace(/\D/g, '').length;
            },
            validateName(name) {
                const v = String(name || '').trim();
                if (v.length < 2 || v.length > 60) return false;
                // Only letters, spaces, dots, hyphens, apostrophes, at least one letter
                const re = /^(?=.*[a-zA-Z])[a-zA-Z\s\.\'\-]+$/;
                return re.test(v);
            },
            validateLocation(loc) {
                const v = String(loc || '').trim();
                if (v.length < 2 || v.length > 255) return false;
                // Must contain at least one letter, allow letters, spaces, commas, dots, hyphens, apostrophes
                const re = /^(?=.*[a-zA-Z])[a-zA-Z\s\.,\'\-]+$/;
                return re.test(v);
            },
            validateEmail(email) {
                if (!email) return false;
                if (email.length > 255) return false;
                const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                if (!re.test(String(email).toLowerCase())) return false;
                
                // Block common typos instantly (same list as user-register + marketplace backend)
                const blockedDomains = ['gmasil.com', 'gsail.com', 'gmial.com', 'gmai.com', 'yaho.com', 'hotmial.com'];
                const parts = email.split('@');
                if (parts.length !== 2) return false;
                const domain = parts[1].toLowerCase();
                if (blockedDomains.includes(domain)) return false;
                
                return true;
            },
            validateNumber(number) {
                const v = String(number || '').trim();
                if (!v) return false;
                // Indian format (same as user-register): 11 digits starting with 0, or 10 digits starting with 6-9
                return /^(0\d{10}|[6-9]\d{9})$/.test(v);
            },
            validateUrl(url) {
                const v = String(url || '').trim();
                if (!v) return true; // optional fields
                if (/\s/.test(v)) return false;
                const withProto = /^[a-zA-Z][a-zA-Z0-9+.-]*:\/\//.test(v) ? v : 'https://' + v;
                try {
                    const u = new URL(withProto);
                    return (u.protocol === 'http:' || u.protocol === 'https:') && u.hostname.includes('.');
                } catch (e) {
                    return false;
                }
            },
            isFieldInvalid(field) {
                const value = this.form[field];
                if (value === undefined || value === null) return false;
                
                // Only show validation errors if the user has touched the field or tried to submit
                if (!value && field !== 'type' && field !== 'years_of_experience') return false; 

                if (field === 'name') {
                    if (value.length > 0 && !this.validateName(value)) return true;
                    return !!this.errors.name;
                }
                if (field === 'location') {
                    if (value.length > 0 && !this.validateLocation(value)) return true;
                    return !!this.errors.location;
                }
                if (field === 'email') {
                    if (value.length > 0 && !this.validateEmail(value)) return true;
                    return !!this.errors.email;
                }
                if (field === 'number') {
                    if (value && String(value).trim() !== '' && !this.validateNumber(value)) return true;
                    return !!this.errors.number;
                }
                if (field === 'portfolio_url' || field === 'website_url') {
                    if (value && String(value).trim() !== '' && !this.validateUrl(value)) return true;
                    return !!this.errors[field];
                }
                if (field === 'password') {
                    if (value.length === 0) return false;
                    const hasUpper = /[A-Z]/.test(value);
                    const hasLower = /[a-z]/.test(value);
                    const hasNumber = /[0-9]/.test(value);
                    const hasSymbol = /[!@#$%^&*(),.?":{}|<>]/.test(value);
                    return value.length < 8 || !hasUpper || !hasLower || !hasNumber || !hasSymbol;
                }
                if (field === 'password_confirmation') return value.length > 0 && value !== this.form.password;
                
                return false;
            },
            checkAvailability(field, value) {
                if (!value) return;
                if (field === 'email' && !this.validateEmail(value)) return;
                if (field === 'number' && !this.validateNumber(value)) {
                    this.errors.number = 'Enter 10 digits starting with 6-9 or 11 digits starting with 0.';
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
            },
            get isStep1Valid() {
                const p = this.form.password;
                const hasComplexity = p.length >= 8 && /[A-Z]/.test(p) && /[a-z]/.test(p) && /[0-9]/.test(p) && /[!@#$%^&*(),.?":{}|<>]/.test(p);
                return this.validateName(this.form.name) && 
                       !this.errors.name &&
                       this.validateEmail(this.form.email) && 
                       !this.errors.email &&
                       hasComplexity && 
                        this.form.password === this.form.password_confirmation &&
                        this.validateNumber(this.form.number) &&
                        !this.errors.number &&
                       this.validateLocation(this.form.location) &&
                       !this.errors.location;
            },
            get isStep2Valid() {
                return this.form.type && 
                       this.form.years_of_experience && 
                       this.form.bio.trim() !== '' &&
                       this.validateUrl(this.form.portfolio_url) &&
                       this.validateUrl(this.form.website_url);
            },
            get isStep3Valid() {
                const isTypeValid = this.form.business_type === 'other' ? this.form.other_business_type.trim() !== '' : this.form.business_type !== '';
                return this.form.business_name.trim() !== '' && 
                       isTypeValid && 
                       this.agreeTerms && 
                       this.agreeCreatorTerms;
            },
            nextStep() {
                if (this.step === 1) {
                    // sync errors for name/email/location before checking
                    this.errors.name = this.validateName(this.form.name) ? '' : 'Please enter a valid name (letters, spaces, dots, hyphens only, min 2 chars).';
                    if (!this.validateEmail(this.form.email)) this.errors.email = this.errors.email || 'Please enter a valid email address.';
                    this.errors.location = this.validateLocation(this.form.location) ? '' : 'Please enter a valid location (letters required, no numbers/symbols).';
                    if (!this.validateNumber(this.form.number)) this.errors.number = 'Enter 10 digits starting with 6-9 or 11 digits starting with 0.';
                    // clear valid ones
                    if (this.validateName(this.form.name)) this.errors.name = '';
                    if (this.validateLocation(this.form.location)) this.errors.location = '';
                    if (!this.isStep1Valid) {
                        this.showErrorAlert();
                        return;
                    }
                }
                if (this.step === 2 && !this.isStep2Valid) {
                    this.showErrorAlert();
                    return;
                }
                if (this.step < 3) this.step++;
            },
            prevStep() {
                if (this.step > 1) this.step--;
            },
            validateAllSteps() {
                const errors = [];
                if (!this.validateName(this.form.name)) errors.push('• Name is invalid (use only letters, spaces, dots, hyphens, apostrophes, min 2 chars)');
                if (!this.validateEmail(this.form.email)) errors.push('• Email is invalid or domain is misspelled');
                if (this.errors.email) errors.push('• Email: ' + this.errors.email);
                if (!this.validateLocation(this.form.location)) errors.push('• Location is invalid (letters required, 2-255 chars)');
                if (this.errors.location) errors.push('• Location: ' + this.errors.location);
                if (this.errors.name) errors.push('• Name: ' + this.errors.name);
                if (this.errors.number) errors.push('• Phone: ' + this.errors.number);
                if (!this.form.number.trim()) errors.push('• Phone number is required');
                else if (!this.validateNumber(this.form.number)) errors.push('• Phone number is invalid (10 digits starting 6-9, or 11 digits starting 0)');
                const p = this.form.password;
                const hasComplexity = p.length >= 8 && /[A-Z]/.test(p) && /[a-z]/.test(p) && /[0-9]/.test(p) && /[!@#$%^&*(),.?":{}|<>]/.test(p);
                if (!hasComplexity) errors.push('• Password must be 8+ chars with uppercase, lowercase, number and symbol');
                if (this.form.password !== this.form.password_confirmation) errors.push('• Passwords do not match');
                if (!this.form.type) errors.push('• Profession is required');
                if (!this.form.years_of_experience) errors.push('• Experience is required');
                if (!this.form.bio.trim()) errors.push('• Bio is required');
                if (this.form.portfolio_url && !this.validateUrl(this.form.portfolio_url)) errors.push('• Portfolio link is not a valid URL (e.g. https://portfolio.com)');
                if (this.form.website_url && !this.validateUrl(this.form.website_url)) errors.push('• Website link is not a valid URL (e.g. https://website.com)');
                if (!this.form.business_name.trim()) errors.push('• Business name is required');
                if (this.form.business_type === 'other' ? !this.form.other_business_type.trim() : !this.form.business_type) errors.push('• Business type is required');
                if (!this.agreeTerms) errors.push('• You must accept Terms of Service & Privacy Policy');
                if (!this.agreeCreatorTerms) errors.push('• You must accept Marketplace Terms & Conditions');
                return errors;
            },
            showErrorAlert() {
                const details = this.validateAllSteps();
                const html = details.length ? `<div class="text-left text-[11px] leading-relaxed font-bold text-slate-600 dark:text-slate-300"><ul class="list-none space-y-1">${details.slice(0,8).map(e=>`<li>${e}</li>`).join('')}</ul></div>` : 'Please ensure all fields are correctly filled, including a valid name, email and location.';
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: html,
                    text: details.length ? undefined : 'Please ensure all fields are correctly filled, including a valid name, email and location.',
                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                    confirmButtonColor: '#ff3b30',
                    customClass: {
                        popup: 'rounded-[2.5rem] border border-slate-100 dark:border-white/5 shadow-2xl'
                    }
                });
            },
            handleSubmit(e) {
                // Force validate step 1 fields even if user bypassed Next
                if (!this.validateName(this.form.name)) this.errors.name = 'Invalid name: only letters, spaces, dots, hyphens, apostrophes allowed (min 2 chars).';
                else this.errors.name = '';
                if (!this.validateEmail(this.form.email)) this.errors.email = this.errors.email || 'Invalid email address.';
                if (!this.validateLocation(this.form.location)) this.errors.location = 'Invalid location: must contain letters, 2-255 chars.';
                else if (this.validateLocation(this.form.location) && this.errors.location && this.errors.location.includes('Invalid location')) this.errors.location = '';
                if (!this.validateNumber(this.form.number)) this.errors.number = 'Invalid phone number: 10 digits starting 6-9, or 11 digits starting 0.';

                if (!this.isStep1Valid || !this.isStep2Valid || !this.isStep3Valid) {
                    // Jump to first invalid step for UX
                    if (!this.isStep1Valid) this.step = 1;
                    else if (!this.isStep2Valid) this.step = 2;
                    this.showErrorAlert();
                    return;
                }
                Swal.fire({
                    html: `
                        <div class='flex flex-col items-center justify-center p-6'>
                            <div class='w-16 h-16 border-4 border-orange-500/20 border-t-orange-500 rounded-full animate-spin mb-6'></div>
                            <h3 class='text-xl font-black text-slate-900 dark:text-white uppercase tracking-widest mb-2'>Please Wait</h3>
                            <p class='text-xs font-bold text-slate-500 uppercase tracking-widest leading-relaxed'>Your creator profile is being created...</p>
                        </div>
                    `,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                    customClass: {
                        popup: 'rounded-[2.5rem] border border-slate-100 dark:border-white/5 shadow-2xl'
                    }
                });
                e.target.submit();
            }
        }
    }
    function skillManager() {
        return {
            newSkill: '',
            skills: [],
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
    function showPremiumTerms(url, title) {
        Swal.fire({
            title: `<span class="uppercase tracking-[0.2em] font-black text-xs text-slate-400 dark:text-white/40">${title}</span>`,
            html: `
                <div id="terms-loader" class="flex flex-col items-center justify-center py-20">
                    <div class="w-12 h-12 border-4 border-orange-500/20 border-t-orange-500 rounded-full animate-spin mb-6"></div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Authenticating...</p>
                </div>
                <div id="terms-content" class="text-left text-[13px] leading-relaxed max-h-[65vh] overflow-y-auto px-2 sm:px-6 custom-scrollbar hidden dark:text-slate-300 font-medium"></div>
            `,
            width: window.innerWidth < 640 ? '95%' : '800px',
            showConfirmButton: true,
            confirmButtonText: 'I Understand',
            confirmButtonColor: '#ff3b30',
            background: document.documentElement.classList.contains('dark') ? '#111' : '#ffffff',
            customClass: {
                popup: 'rounded-[3rem] border border-slate-100 dark:border-white/5 shadow-2xl overflow-hidden',
                confirmButton: 'rounded-2xl px-12 py-4 text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-rose-500/20'
            },
            didOpen: () => {
                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        // Try to find the main content, otherwise just take the body
                        const content = doc.querySelector('.content-area') || doc.querySelector('main') || doc.querySelector('.policy-details') || doc.body;
                        
                        document.getElementById('terms-loader').classList.add('hidden');
                        const contentDiv = document.getElementById('terms-content');
                        contentDiv.innerHTML = content.innerHTML;
                        contentDiv.classList.remove('hidden');

                        // Clean up internal links and images if any
                        contentDiv.querySelectorAll('a').forEach(a => a.setAttribute('target', '_blank'));
                    })
                    .catch(err => {
                        console.error('Error fetching terms:', err);
                        document.getElementById('terms-loader').innerHTML = `
                            <div class="w-16 h-16 bg-rose-500/10 rounded-2xl flex items-center justify-center mb-6">
                                <span class="material-symbols-rounded text-rose-500 text-3xl">error</span>
                            </div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-relaxed">Failed to initialize content. <br>Please verify your connection.</p>
                        `;
                    });
            }
        });
    }
</script>
@endsection

