@extends('layouts.app')

@section('content')
<div id="auth-register" class="w-full max-w-[440px] px-4 py-8 lg:py-12 animate-in fade-in zoom-in duration-500">
    <!-- Register Card -->
    <div class="bg-white dark:bg-[#1A1A1A] border border-gray-100 dark:border-white/5 rounded-[2.5rem] shadow-2xl shadow-black/10 overflow-visible relative group">
        
        <!-- Premium Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-orange-600 via-orange-500 to-orange-600"></div>

        <div class="p-6 sm:p-10">
            <!-- Header -->
            <div class="text-center mb-6">
                <h1 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tighter ">Sign Up</h1>
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.25em] mt-1">Create your account</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6" 
                  x-data="{ 
                      loading: false,
                      form: { firstname: '', lastname: '', email: '', mobile: '', country_name: '{{ old('country_name', 'United States') }}', password: '', password_confirmation: '' },
                      countryOpen: false,
                      countrySearch: '',
                      countries: @js($countries),
                      get filteredCountries() {
                          if (!this.countrySearch) return this.countries;
                          return Object.values(this.countries).filter(c => 
                              c.country.toLowerCase().includes(this.countrySearch.toLowerCase())
                          );
                      },
                      isNameInvalid(val) {
                          if (!val) return false;
                          if (val.trim().length < 2) return true;
                          if (val.length > 13) return true;
                          const re = /^[a-zA-Z\s]+$/;
                          return !re.test(val);
                      },
                      isMobileInvalid(val) {
                          if (!val) return false;
                          const re = /^(0\d{10}|[6-9]\d{9})$/;
                          return !re.test(val);
                      },
                      errors: { email: '', mobile: '' },
                      validateEmail(email) {
                          if (!email) return false;
                          if (email.length > 100) return false;
                          const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                          if (!re.test(String(email).toLowerCase())) return false;
                          
                          // Block common typos instantly
                          const blockedDomains = ['gmasil.com', 'gsail.com', 'gmial.com', 'gmai.com', 'yaho.com', 'hotmial.com'];
                          const domain = email.split('@')[1].toLowerCase();
                          if (blockedDomains.includes(domain)) return false;
                          
                          return true;
                      },
                      checkAvailability(field, value) {
                          if (!value) return;
                          
                          this.errors[field] = '';
                          fetch('{{ route('register.check-availability') }}', {
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
                      isPasswordInvalid(val) {
                          if (!val) return false;
                          if (val.length > 64) return true;
                          const hasLetter = /[a-z]/.test(val);
                          const hasCapital = /[A-Z]/.test(val);
                          const hasNumber = /[0-9]/.test(val);
                          const hasSymbol = /[!@#$%^&*(),.?\x22:{}|<>]/.test(val);
                          return val.length < 8 || !hasLetter || !hasCapital || !hasNumber || !hasSymbol;
                      },
                      isCountryInvalid(val) {
                          if (!val) return true;
                          const validCountries = Object.values(this.countries).map(c => c.country);
                          return !validCountries.includes(val);
                      }
                  }" 
                  @submit="loading = true">
                @csrf

                <!-- Name Section -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-gray-400 dark:text-white/20 uppercase tracking-widest px-1">First Name</label>
                        <div class="relative group">
                            <div class="absolute left-4 top-0 h-12 flex items-center pointer-events-none">
                                <span class="material-symbols-rounded text-gray-400 group-focus-within:text-orange-500 transition-colors text-xl">person</span>
                            </div>
                            <input type="text" name="firstname" value="{{ old('firstname') }}" required autofocus x-model="form.firstname" maxlength="13"
                                   :class="(form.firstname && isNameInvalid(form.firstname)) ? 'border-rose-500 ring-4 ring-rose-500/10' : ''"
                                   class="w-full h-12 bg-gray-50 dark:bg-white/5 border @error('firstname') border-rose-500 @else border-transparent @enderror focus:border-orange-500/50 rounded-xl pl-12 pr-6 text-sm font-bold text-gray-900 dark:text-white placeholder:text-gray-400 transition-all outline-none focus:ring-4 focus:ring-orange-500/10"
                                   placeholder="John">
                            <div x-show="form.firstname && isNameInvalid(form.firstname)" class="mt-1 flex items-center gap-1 text-rose-500 px-1 text-[9px] font-bold uppercase tracking-tight" style="display: none;">2-13 letters only</div>
                            @error('firstname')
                                <div class="mt-1 flex items-center gap-1 text-rose-500">
                                    <span class="material-symbols-rounded text-xs">error</span>
                                    <span class="text-[9px] font-bold uppercase tracking-tight">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-gray-400 dark:text-white/20 uppercase tracking-widest px-1">Last Name</label>
                        <div class="relative group">
                            <div class="absolute left-4 top-0 h-12 flex items-center pointer-events-none">
                                <span class="material-symbols-rounded text-gray-400 group-focus-within:text-orange-500 transition-colors text-xl">person</span>
                            </div>
                            <input type="text" name="lastname" value="{{ old('lastname') }}" required x-model="form.lastname" maxlength="13"
                                   :class="(form.lastname && isNameInvalid(form.lastname)) ? 'border-rose-500 ring-4 ring-rose-500/10' : ''"
                                   class="w-full h-12 bg-gray-50 dark:bg-white/5 border @error('lastname') border-rose-500 @else border-transparent @enderror focus:border-orange-500/50 rounded-xl pl-12 pr-6 text-sm font-bold text-gray-900 dark:text-white placeholder:text-gray-400 transition-all outline-none focus:ring-4 focus:ring-orange-500/10"
                                   placeholder="Doe">
                            <div x-show="form.lastname && isNameInvalid(form.lastname)" class="mt-1 flex items-center gap-1 text-rose-500 px-1 text-[9px] font-bold uppercase tracking-tight" style="display: none;">2-13 letters only</div>
                            @error('lastname')
                                <div class="mt-1 flex items-center gap-1 text-rose-500">
                                    <span class="material-symbols-rounded text-xs">error</span>
                                    <span class="text-[9px] font-bold uppercase tracking-tight">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Email & Phone Section -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-gray-400 dark:text-white/20 uppercase tracking-widest px-1">Email Address</label>
                        <div class="relative group">
                            <div class="absolute left-4 top-0 h-12 flex items-center pointer-events-none">
                                <span class="material-symbols-rounded text-gray-400 group-focus-within:text-orange-500 transition-colors text-xl">alternate_email</span>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required x-model="form.email"
                                   @input.debounce.300ms="checkAvailability('email', $event.target.value)"
                                   :class="(errors.email || (form.email && !validateEmail(form.email))) ? 'border-rose-500 ring-4 ring-rose-500/10' : ''"
                                   class="w-full h-12 bg-gray-50 dark:bg-white/5 border @error('email') border-rose-500 @else border-transparent @enderror focus:border-orange-500/50 rounded-xl pl-12 pr-6 text-sm font-bold text-gray-900 dark:text-white placeholder:text-gray-400 transition-all outline-none focus:ring-4 focus:ring-orange-500/10"
                                   placeholder="your@email.com">
                            <div x-show="errors.email" x-text="errors.email" class="mt-1 flex items-center gap-1 text-rose-500 px-1 text-[9px] font-bold uppercase tracking-tight" style="display: none;"></div>
                            <div x-show="!errors.email && form.email && !validateEmail(form.email)" class="mt-1 flex items-center gap-1 text-rose-500 px-1 text-[9px] font-bold uppercase tracking-tight" style="display: none;">Invalid email format</div>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-gray-400 dark:text-white/20 uppercase tracking-widest px-1">Phone Number</label>
                        <div class="relative group">
                            <div class="absolute left-4 top-0 h-12 flex items-center pointer-events-none">
                                <span class="material-symbols-rounded text-gray-400 group-focus-within:text-orange-500 transition-colors text-xl">call</span>
                            </div>
                            <input type="text" name="mobile" value="{{ old('mobile') }}" required x-model="form.mobile"
                                   @input="form.mobile = form.mobile.replace(/\D/g, '').substring(0, 11); checkAvailability('mobile', form.mobile)"
                                   maxlength="11"
                                   :class="(errors.mobile || isMobileInvalid(form.mobile)) ? 'border-rose-500 ring-4 ring-rose-500/10' : ''"
                                   class="w-full h-12 bg-gray-50 dark:bg-white/5 border @error('mobile') border-rose-500 @else border-transparent @enderror focus:border-orange-500/50 rounded-xl pl-12 pr-6 text-sm font-bold text-gray-900 dark:text-white placeholder:text-gray-400 transition-all outline-none focus:ring-4 focus:ring-orange-500/10"
                                   placeholder="9876543210">
                            <div x-show="errors.mobile" x-text="errors.mobile" class="mt-1 flex items-center gap-1 text-rose-500 px-1 text-[9px] font-bold uppercase tracking-tight" style="display: none;"></div>
                            <div x-show="form.mobile && isMobileInvalid(form.mobile)" class="mt-1 flex items-center gap-1 text-rose-500 px-1 text-[9px] font-bold uppercase tracking-tight" style="display: none;">Enter 10 digits starting with 6-9 or 11 digits starting with 0</div>
                        </div>
                    </div>
                </div>

                <!-- Location Section (Custom Searchable Dropdown) -->
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-gray-400 dark:text-white/20 uppercase tracking-widest px-1">Location / Country</label>
                    <div class="relative">
                        <!-- Trigger -->
                        <div @click="countryOpen = !countryOpen" 
                             class="w-full h-12 bg-gray-50 dark:bg-white/5 border border-transparent focus-within:border-orange-500/50 rounded-xl pl-12 pr-10 flex items-center cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-white/10 outline-none focus-within:ring-4 focus-within:ring-orange-500/10">
                            
                            <div class="absolute left-4 flex items-center pointer-events-none">
                                <span class="material-symbols-rounded text-gray-400 text-xl" :class="countryOpen ? 'text-orange-500' : ''">public</span>
                            </div>

                            <span class="text-sm font-bold text-gray-900 dark:text-white" x-text="form.country_name || 'Select Country'"></span>
                            
                            <div class="absolute right-4 flex items-center pointer-events-none">
                                <span class="material-symbols-rounded text-gray-400 text-xl transition-transform duration-300" :class="countryOpen ? 'rotate-180 text-orange-500' : ''">expand_more</span>
                            </div>
                        </div>

                        <!-- Hidden Input for Form Data -->
                        <input type="hidden" name="country_name" x-model="form.country_name">

                        <!-- Dropdown Content -->
                        <div x-show="countryOpen" 
                             @click.outside="countryOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-[#1E1E1E] border border-gray-100 dark:border-white/10 rounded-2xl shadow-2xl z-[100] overflow-hidden">
                            
                            <!-- Search Box -->
                            <div class="p-2 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-black/20">
                                <div class="relative">
                                    <span class="material-symbols-rounded absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">search</span>
                                    <input type="text" x-model="countrySearch" placeholder="Search countries..." 
                                           class="w-full bg-white dark:bg-white/5 border-none rounded-lg pl-9 pr-4 py-2 text-xs font-bold text-gray-900 dark:text-white placeholder:text-gray-500 focus:ring-1 focus:ring-orange-500/50 outline-none">
                                </div>
                            </div>

                            <!-- Options List -->
                            <div class="max-h-[240px] overflow-y-auto custom-scrollbar p-1">
                                <template x-for="c in filteredCountries" :key="c.country">
                                    <div @click="form.country_name = c.country; countryOpen = false; countrySearch = ''" 
                                         class="px-4 py-2.5 rounded-xl text-xs font-bold cursor-pointer transition-all flex items-center justify-between group"
                                         :class="form.country_name === c.country ? 'bg-orange-500 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5'">
                                        <span x-text="c.country"></span>
                                        <span x-show="form.country_name === c.country" class="material-symbols-rounded text-sm">check</span>
                                    </div>
                                </template>
                                
                                <template x-if="filteredCountries.length === 0">
                                    <div class="px-4 py-8 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        No matches found
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div x-show="form.country_name && isCountryInvalid(form.country_name)" class="mt-1 flex items-center gap-1 text-rose-500 px-1 text-[9px] font-bold uppercase tracking-tight" style="display: none;">Please select a valid country from the list</div>
                        @error('country_name')
                            <div class="mt-1 flex items-center gap-1 text-rose-500">
                                <span class="material-symbols-rounded text-xs">error</span>
                                <span class="text-[9px] font-bold uppercase tracking-tight">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Password Section -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between px-1">
                            <label class="text-[10px] font-black text-gray-400 dark:text-white/20 uppercase tracking-widest">Password</label>
                        </div>
                        <div class="relative group" x-data="{ show: false }">
                            <div class="absolute left-4 top-0 h-12 flex items-center pointer-events-none">
                                <span class="material-symbols-rounded text-gray-400 group-focus-within:text-orange-500 transition-colors text-xl">lock</span>
                            </div>
                            <input :type="show ? 'text' : 'password'" name="password" required x-model="form.password"
                                   :class="isPasswordInvalid(form.password) ? 'border-rose-500 ring-4 ring-rose-500/10' : ''"
                                   class="w-full h-12 bg-gray-50 dark:bg-white/5 border @error('password') border-rose-500 @else border-transparent @enderror focus:border-orange-500/50 rounded-xl pl-12 pr-12 text-sm font-bold text-gray-900 dark:text-white placeholder:text-gray-400 transition-all outline-none focus:ring-4 focus:ring-orange-500/10"
                                   placeholder="••••••••">
                            <div x-show="isPasswordInvalid(form.password)" class="mt-1 flex items-center gap-1 text-rose-500 px-1 text-[7px] font-bold uppercase tracking-tight" style="display: none;">Min. 8 chars, 1 Upper, 1 Lower, 1 Number, 1 Symbol</div>
                            @error('password')
                                <div class="mt-1 flex items-center gap-1 text-rose-500">
                                    <span class="material-symbols-rounded text-xs">error</span>
                                    <span class="text-[9px] font-bold uppercase tracking-tight text-wrap">{{ $message }}</span>
                                </div>
                            @enderror
                            <div class="absolute right-4 top-0 h-12 flex items-center">
                                <button type="button" @click="show = !show" class="text-gray-400 hover:text-orange-500 transition-colors">
                                    <span class="material-symbols-rounded text-xl" x-text="show ? 'visibility_off' : 'visibility'"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-gray-400 dark:text-white/20 uppercase tracking-widest px-1">Confirm Password</label>
                        <div class="relative group" x-data="{ show: false }">
                            <div class="absolute left-4 top-0 h-12 flex items-center pointer-events-none">
                                <span class="material-symbols-rounded text-gray-400 group-focus-within:text-orange-500 transition-colors text-xl">verified_user</span>
                            </div>
                            <input :type="show ? 'text' : 'password'" name="password_confirmation" required x-model="form.password_confirmation"
                                   :class="(form.password_confirmation && form.password_confirmation !== form.password) ? 'border-rose-500 ring-4 ring-rose-500/10' : ''"
                                   class="w-full h-12 bg-gray-50 dark:bg-white/5 border border-transparent focus:border-orange-500/50 rounded-xl pl-12 pr-12 text-sm font-bold text-gray-900 dark:text-white placeholder:text-gray-400 transition-all outline-none focus:ring-4 focus:ring-orange-500/10"
                                   placeholder="••••••••">
                            <div class="absolute right-4 top-0 h-12 flex items-center">
                                <button type="button" @click="show = !show" class="text-gray-400 hover:text-orange-500 transition-colors">
                                    <span class="material-symbols-rounded text-xl" x-text="show ? 'visibility_off' : 'visibility'"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" 
                        :disabled="loading || !form.password || !form.email || !form.mobile || !form.firstname || !form.lastname || isNameInvalid(form.firstname) || isNameInvalid(form.lastname) || errors.email || errors.mobile || !validateEmail(form.email) || isMobileInvalid(form.mobile) || isPasswordInvalid(form.password) || isCountryInvalid(form.country_name) || (form.password_confirmation !== form.password)" 
                        class="relative w-full h-14 bg-gradient-to-r from-orange-600 to-orange-500 rounded-xl text-white font-black uppercase tracking-[0.2em] shadow-lg shadow-orange-500/20 hover:scale-[1.01] active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed transition-all overflow-hidden group/btn text-sm">
                    <div class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover/btn:translate-x-[100%] transition-transform duration-1000"></div>
                    
                    <span x-show="!loading" class="relative flex items-center justify-center gap-3">
                        Sign Up
                        <span class="material-symbols-rounded text-lg">check_circle</span>
                    </span>

                    <span x-show="loading" class="relative flex items-center justify-center gap-3" x-cloak>
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Creating Account...
                    </span>
                </button>
            </form>

            <!-- Social Logins -->
            <div class="mt-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="h-px bg-gray-100 dark:bg-white/5 flex-grow"></div>
                    <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Or</span>
                    <div class="h-px bg-gray-100 dark:bg-white/5 flex-grow"></div>
                </div>
                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('frontend.googlePage') }}" class="h-12 bg-gray-50 dark:bg-white/5 border border-transparent hover:border-gray-200 dark:hover:border-white/10 rounded-xl flex items-center justify-center gap-2 transition-all active:scale-95 group/social">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" class="w-5 h-5 group-hover/social:scale-110 transition-transform">
                        <span class="text-[10px] font-black text-gray-900 dark:text-white uppercase tracking-widest">Sign up with Google</span>
                    </a>
                </div>
            </div>

            <!-- Footer Link -->
            <div class="mt-6 text-center">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-orange-500 hover:text-orange-400 transition-colors ml-1 font-black">Login</a>
                </p>
            </div>
        </div>
    </div>

</div>

    {{-- Mobile Bottom Spacer --}}
    <div class="lg:hidden h-24"></div>

<style>
    /* Page-scoped icon visibility fix (this page only).
       The layout globally forces `.material-symbols-rounded` to `color: inherit`,
       which beats Tailwind's text-color utilities and leaves icons dark-on-dark
       in dark mode. Light mode restores the original colors; dark mode renders
       the icons white as designed. */
    html:not(.dark) #auth-register span.material-symbols-rounded.text-gray-400 { color: #9ca3af !important; }
    html:not(.dark) #auth-register .group:focus-within span.material-symbols-rounded.group-focus-within\:text-orange-500 { color: #f97316 !important; }
    html:not(.dark) #auth-register span.material-symbols-rounded.text-white { color: #ffffff !important; }
    html:not(.dark) #auth-register span.material-symbols-rounded.text-orange-500 { color: #f97316 !important; }
    .dark #auth-register span.material-symbols-rounded.text-gray-400 { color: rgba(255,255,255,0.7) !important; }
    .dark #auth-register span.material-symbols-rounded.text-white { color: #ffffff !important; }
    .dark #auth-register span.material-symbols-rounded.text-orange-500 { color: #ffffff !important; }
</style>
@endsection

