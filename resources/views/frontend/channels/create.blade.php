@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-[#0F0F0F] pb-12 lg:pb-6">
    <style>
        #channel-create-form .mb-10 { margin-bottom: 0.75rem !important; }
        #channel-create-form input, #channel-create-form select {
            height: 2.75rem !important; /* h-11 */
            border-radius: 0.75rem !important; /* rounded-xl */
        }
        #channel-create-form textarea[name="address"] {
            height: 7.5rem !important;
            border-radius: 0.75rem !important;
            padding-top: 0.75rem !important;
            padding-bottom: 0.75rem !important;
        }
        /* Page-specific fix for http://127.0.0.1:8000/channels/create only: labels must be dark in light mode */
        #channel-create-form label.block.text-\[10px\] {
            color: rgb(15 23 42) !important; /* slate-900 - dark in light mode */
        }
        html.dark #channel-create-form label.block.text-\[10px\],
        .dark #channel-create-form label.block.text-\[10px\] {
            color: rgba(255,255,255,0.3) !important; /* keep dark:text-white/30 in dark mode */
        }
    </style>
    <div class="max-w-3xl mx-auto px-4 pt-16 sm:pt-20 lg:pt-8">
        <!-- Form Card -->
        <div id="channel-create-form" class="bg-white dark:bg-[#1A1A1A] rounded-2xl sm:rounded-3xl border border-gray-100 dark:border-white/5 shadow-2xl overflow-hidden p-4 sm:p-5 lg:p-6 mb-6 relative">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-600/5 rounded-full blur-[100px] pointer-events-none"></div>
            
            <!-- Header Section (Moved inside card) -->
            <div class="mb-4 sm:mb-5 text-center relative z-10">
                <div class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-blue-600/10 text-blue-600 mb-2 sm:mb-3 transition-transform hover:scale-110 duration-300">
                    <span class="material-symbols-rounded text-xl sm:text-2xl">video_call</span>
                </div>
                <h1 class="text-lg sm:text-xl font-black text-gray-900 dark:text-white tracking-tight mb-1">Launch Your Channel</h1>
                <p class="text-[9px] sm:text-[10px] text-gray-500 dark:text-gray-400 font-medium ">Complete your profile to start creating and sharing content</p>
            </div>

            <form action="{{ route('channels.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-5 relative z-10" 
                  x-data="{ 
                      avatarPreview: null,
                      agreed: false,
                      username: '{{ old('username') }}',
                      mobile: '{{ old('mobile') }}',
                      channel_name: '{{ old('channel_name', auth()->user()->name) }}',
                      errors: { username: '', mobile: '', channel_name: '' },
                      loading: { username: false, mobile: false, channel_name: false },
                      validateField(field, value) {
                          console.log(`[Channel Create] Validating ${field}:`, value);
                          this[field] = value;
                          if (!value) {
                              this.errors[field] = field.replace('_', ' ') + ' is required';
                              return;
                          }
                          this.errors[field] = '';
                          
                          if (field === 'mobile') {
                              value = value.replace(/\D/g, '').substring(0, 10);
                              this.mobile = value;
                              if (value.length !== 10) {
                                  this.errors.mobile = 'Mobile number must be exactly 10 digits';
                                  return;
                              }
                          }

                          if (field === 'username') {
                              if (/\s/.test(value)) {
                                  this.errors.username = 'No spaces allowed';
                                  return;
                              }
                          }

                          if (field === 'username' || field === 'mobile' || field === 'channel_name') {
                              this.loading[field] = true;
                              fetch('{{ route('channels.check-availability') }}', {
                                  method: 'POST',
                                  headers: {
                                      'Content-Type': 'application/json',
                                      'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                  },
                                  body: JSON.stringify({ field: field, value: value })
                              })
                              .then(res => res.json())
                              .then(data => {
                                  this.loading[field] = false;
                                  if (data.error) {
                                      this.errors[field] = data.error;
                                  } else {
                                      this.errors[field] = '';
                                  }
                              })
                              .catch((err) => {
                                  console.error(`[Channel Create] Error validating ${field}:`, err);
                                  this.loading[field] = false;
                              });
                          }
                      }
                  }"
                  onsubmit="Swal.fire({title: 'Creating Channel...', text: 'Initializing your broadcast frequency', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});">
                @csrf
                
                <!-- Section: Basic Information -->
                <div class="space-y-3 sm:space-y-4">
                    <div class="flex items-center gap-2 sm:gap-3 mb-1.5">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-blue-600/10 text-blue-600 flex items-center justify-center shadow-sm">
                            <span class="material-symbols-rounded text-base sm:text-lg">face</span>
                        </div>
                        <div>
                            <h3 class="text-[9px] sm:text-[10px] font-black text-gray-900 dark:text-white uppercase tracking-[0.2em]">Channel Identity</h3>
                            <p class="text-[7px] sm:text-[8px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Basic channel details</p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:gap-4">
                        <div class="flex items-center justify-center sm:justify-start">
                            <div class="relative group">
                                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl sm:rounded-2xl bg-gray-50 dark:bg-white/5 border-2 sm:border-[3px] border-white dark:border-[#1A1A1A] shadow-lg overflow-hidden relative transition-all group-hover:scale-105 duration-500">
                                    <template x-if="avatarPreview">
                                        <img :src="avatarPreview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!avatarPreview">
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <span class="material-symbols-rounded text-2xl sm:text-3xl">add_a_photo</span>
                                        </div>
                                    </template>
                                    <label for="avatar_input" class="absolute inset-0 bg-black/60 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-all cursor-pointer backdrop-blur-sm">
                                        <span class="material-symbols-rounded text-white text-xl sm:text-2xl">upload</span>
                                        <span class="text-[6px] sm:text-[7px] font-black text-white uppercase mt-0.5 sm:mt-1 tracking-widest">Update Photo</span>
                                    </label>
                                </div>
                                <input type="file" id="avatar_input" name="image" class="hidden" accept="image/*" 
                                       @change="openCropper($event.target, null, {aspectRatio: 1}, (file, url) => { avatarPreview = url; })">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 sm:gap-x-6 gap-y-2 sm:gap-y-3">
                            <div class="space-y-1">
                                <x-input name="channel_name" label="Channel Name" value="{{ old('channel_name', auth()->user()->name) }}" required="true" icon="badge" hint="The public name of your channel." 
                                         @input.debounce.500ms="validateField('channel_name', $event.target.value)" />
                                <div class="flex items-center gap-2 mt-1">
                                    <p x-show="errors.channel_name" x-text="errors.channel_name" class="text-[9px] sm:text-[10px] font-bold text-rose-500 uppercase tracking-widest" style="display: none;"></p>
                                    <template x-if="loading.channel_name">
                                        <svg class="animate-spin h-3 w-3 text-blue-500" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    </template>
                                </div>
                            </div>
                            
                            <div class="space-y-1">
                                <x-input name="username" label="Username" value="{{ old('username') }}" required="true" icon="alternate_email" hint="Must be unique. This will be your channel URL." 
                                         @input.debounce.500ms="validateField('username', $event.target.value)" />
                                <div class="flex items-center gap-2 mt-1">
                                    <p x-show="errors.username" x-text="errors.username" class="text-[9px] sm:text-[10px] font-bold text-rose-500 uppercase tracking-widest" style="display: none;"></p>
                                    <template x-if="loading.username">
                                        <svg class="animate-spin h-3 w-3 text-blue-500" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Contact Details -->
                <div class="space-y-3 sm:space-y-4 pt-4 sm:pt-5 border-t border-gray-50 dark:border-white/5">
                    <div class="flex items-center gap-2 sm:gap-3 mb-1.5">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-orange-600/10 text-orange-600 flex items-center justify-center shadow-sm">
                            <span class="material-symbols-rounded text-base sm:text-lg">contact_page</span>
                        </div>
                        <div>
                            <h3 class="text-[9px] sm:text-[10px] font-black text-gray-900 dark:text-white uppercase tracking-[0.2em]">Contact & Location</h3>
                            <p class="text-[7px] sm:text-[8px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Verification and outreach</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 sm:gap-x-6 gap-y-2 sm:gap-y-3">
                        <div class="space-y-1">
                            <x-input name="mobile" label="Mobile Number" value="{{ old('mobile') }}" required="true" icon="phone" hint="Primary contact (10 digits)." 
                                     maxlength="10" inputmode="numeric" pattern="[0-9]{10}"
                                     @input="mobile = $event.target.value.replace(/\D/g, '').substring(0, 10); $event.target.value = mobile; validateField('mobile', mobile)" />
                            <div class="flex items-center gap-2 mt-1">
                                <p x-show="errors.mobile" x-text="errors.mobile" class="text-[9px] sm:text-[10px] font-bold text-rose-500 uppercase tracking-widest" style="display: none;"></p>
                                <template x-if="loading.mobile">
                                    <svg class="animate-spin h-3 w-3 text-orange-500" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </template>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <x-select name="country" label="Country" required="true" icon="public" hint="Your primary location.">
                                @foreach($countries as $code => $country)
                                    <option value="{{ $country->country }}" data-code="{{ $country->dial_code }}" {{ $code == 'US' ? 'selected' : '' }}>
                                        {{ $country->country }}
                                    </option>
                                @endforeach
                            </x-select>
                            <input type="hidden" name="country_code" value="1">
                        </div>
                    </div>

                    <x-textarea name="address" label="Address" value="{{ old('address') }}" icon="home" rows="4" hint="Physical address for verification." />

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-3">
                        <div class="col-span-1 md:col-span-1">
                            <x-input name="city" label="City" value="{{ old('city') }}" hint="Your city" />
                        </div>
                        <div class="col-span-1 md:col-span-1">
                            <x-input name="state" label="State" value="{{ old('state') }}" hint="Your state" />
                        </div>
                        <div class="col-span-2 md:col-span-2 w-full">
                            <x-input name="zip" label="Zip Code" value="{{ old('zip') }}" hint="Your postal code" />
                        </div>
                    </div>
                </div>

                <!-- Section: Compliance -->
                <div class="pt-4 sm:pt-5 border-t border-gray-50 dark:border-white/5">
                    <label class="flex items-center gap-2 sm:gap-3 cursor-pointer group">
                        <div class="relative flex items-center justify-center">
                            <input type="checkbox" x-model="agreed" class="h-4 w-4 opacity-0 absolute cursor-pointer z-10" required>
                            <div class="h-4 w-4 rounded-md border-[1.5px] transition-all flex items-center justify-center"
                                 :class="agreed ? 'bg-orange-500 border-orange-500' : 'border-gray-200 dark:border-white/10'">
                                <span class="material-symbols-rounded text-white text-[8px] sm:text-[10px] transition-opacity"
                                      :class="agreed ? 'opacity-100' : 'opacity-0'">check</span>
                            </div>
                        </div>
                        <span class="text-[7px] sm:text-[8px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest group-hover:text-gray-900 dark:group-hover:text-white transition-colors">
                            I agree to the <a href="#" class="text-orange-500 hover:underline">Terms & Conditions</a>
                        </span>
                    </label>

                    <!-- Submit Button -->
                    <div class="pt-5 sm:pt-6 flex flex-col items-center gap-2 sm:gap-3">
                        <button type="submit" 
                                :disabled="!agreed || errors.username || errors.mobile || errors.channel_name || !username || !mobile || mobile.length !== 10 || !channel_name"
                                :class="(!agreed || errors.username || errors.mobile || errors.channel_name || !username || !mobile || mobile.length !== 10 || !channel_name) ? 'opacity-50 grayscale cursor-not-allowed scale-95' : 'hover:scale-[1.02] shadow-orange-600/20'"
                                class="w-full sm:w-auto px-6 h-10 sm:h-12 flex items-center justify-center gradient-orange text-white font-black text-[8px] sm:text-[9px] uppercase tracking-[0.2em] rounded-xl shadow-lg active:scale-[0.98] transition-all group min-w-[160px] sm:min-w-[200px]">
                            <span class="material-symbols-rounded mr-2 text-sm sm:text-base group-hover:translate-x-1 transition-transform duration-500">rocket_launch</span>
                            Create Channel
                        </button>
                        <p class="text-center text-[7px] text-gray-400 font-bold uppercase tracking-widest opacity-60">
                            Your broadcast signal will be initialized immediately.
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Update hidden country_code when country changes
    document.getElementById('country').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        document.querySelector('input[name="country_code"]').value = option.dataset.code;
    });
</script>
@endsection

