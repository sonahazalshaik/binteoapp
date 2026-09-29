<x-app-layout>
    <div class="min-h-screen bg-[#FDFDFD] dark:bg-[#000000] text-gray-900 dark:text-white transition-colors duration-500 pb-24"
         x-data="{ showDeleteModal: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }}, deleteReason: '', customReason: '' }">
         
        <!-- Native Android Style Header -->
        <div class="relative w-full h-auto aspect-[4/1] md:aspect-[6.2/1] bg-gray-200 dark:bg-white/5 overflow-hidden">
            <img id="cover-preview" src="{{ @$user->channel->banner ? getImage(getFilePath('cover') . '/' . @$user->channel->banner) : '' }}" 
                 class="w-full h-full object-cover {{ @$user->channel->banner ? 'opacity-100' : 'opacity-0' }}">
            
            <div id="hero-banner-placeholder" class="absolute inset-0 flex items-center justify-center opacity-20 {{ @$user->channel->banner ? 'hidden' : '' }}">
                <span class="material-symbols-rounded text-7xl">gallery_thumbnail</span>
            </div>

            <!-- Persistent Banner Edit FAB -->
            <label for="cover-input" class="absolute bottom-3 right-3 z-40 w-9 h-9 bg-white/80 dark:bg-[#1A1A1A]/80 backdrop-blur-md text-gray-900 dark:text-white rounded-xl flex items-center justify-center shadow-md cursor-pointer hover:scale-110 active:scale-95 transition-all border border-white/20">
                <span class="material-symbols-rounded text-base">edit_square</span>
            </label>
        </div>

        <div class="max-w-xl mx-auto px-6 relative -mt-12 z-40">
            <form id="profile-edit-form" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6"
                  x-data="{ 
                      usernameError: '',
                      loading: false,
                      validateUsername(value) {
                          if (value.length < 6) {
                              this.usernameError = 'At least 6 characters';
                              return;
                          }
                          if (/\s/.test(value)) {
                              this.usernameError = 'No spaces allowed';
                              return;
                          }
                          if (/[^a-z0-9_]/.test(value)) {
                              this.usernameError = 'Small letters, numbers, underscore only';
                              return;
                          }
                          this.loading = true;
                          fetch('{{ route('channels.check-availability') }}', {
                              method: 'POST',
                              headers: {
                                  'Content-Type': 'application/json',
                                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
                              },
                              body: JSON.stringify({ field: 'username', value: value })
                          })
                          .then(res => res.json())
                          .then(data => {
                              this.loading = false;
                              this.usernameError = data.error || '';
                          });
                      }
                  }">
                @csrf
                @method('patch')
                <input type="file" id="avatar-input" name="image" class="hidden" accept="image/*" onchange="openCropper(this, 'avatar-preview', {aspectRatio: 1})">
                <input type="file" id="cover-input" name="cover" class="hidden" accept="image/*" onchange="openCropper(this, 'cover-preview', {aspectRatio: 6.2})">
                
                <!-- Profile Identity Card -->
                <div class="relative overflow-hidden bg-white dark:bg-[#111111]/90 backdrop-blur-md rounded-2xl p-5 shadow-sm border border-gray-200/50 dark:border-white/5">
                    <!-- Top Brand Gradient Accent -->
                    <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-red-600 to-pink-600"></div>
                    
                    <div class="flex items-center gap-4 mt-1">
                        <!-- Left: Avatar Section -->
                        <div class="relative flex-shrink-0">
                            <div class="w-20 h-20 rounded-full overflow-hidden ring-4 ring-red-600/10 p-0.5 shadow-sm bg-gray-50 dark:bg-black">
                                <img id="avatar-preview" src="{{ $user->image ? getImage(getFilePath('userProfile') . '/' . $user->image) : asset('assets/images/avatar.png') }}" 
                                     class="w-full h-full rounded-full object-cover">
                            </div>
                            <label for="avatar-input" class="absolute bottom-0 right-0 w-7 h-7 bg-red-600 text-white rounded-full flex items-center justify-center cursor-pointer shadow-md hover:scale-110 active:scale-95 transition-all">
                                <span class="material-symbols-rounded text-sm">photo_camera</span>
                            </label>
                        </div>

                        <!-- Right: Details Section -->
                        <div class="flex-grow min-w-0">
                            <div class="flex flex-col">
                                <h1 class="text-base font-black tracking-tight text-gray-900 dark:text-white truncate mb-0">{{ $user->name }}</h1>
                                <p class="text-[11px] font-bold text-gray-400 mt-0.5">@<span x-text="($el.closest('form').username.value || '{{ $user->username }}')">{{ $user->username }}</span></p>
                                
                                <div class="mt-2.5 flex items-center gap-2">
                                    <div class="flex items-center gap-1 bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">
                                        <span class="material-symbols-rounded text-[10px]">groups</span>
                                        <span>{{ number_format($user->subscribers_count ?? 0) }} Subs</span>
                                    </div>
                                    <div class="flex items-center gap-1 bg-gray-50 dark:bg-white/5 text-gray-500 dark:text-gray-400 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">
                                        <span class="material-symbols-rounded text-[10px]">play_circle</span>
                                        <span>{{ number_format($user->videos_count ?? 0) }} Videos</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Group: Account Identity -->
                <div class="bg-white dark:bg-[#111111]/90 backdrop-blur-md rounded-2xl p-5 md:p-6 space-y-5 border border-gray-200/50 dark:border-white/5 shadow-sm">
                    <div class="flex items-center gap-2 mb-1 px-1">
                        <span class="material-symbols-rounded text-red-600 text-lg">manage_accounts</span>
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 dark:text-gray-500">Account Identity</h3>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">First Name</label>
                            <input type="text" name="firstname" value="{{ old('firstname', $user->firstname) }}" 
                                   class="w-full bg-gray-50/50 dark:bg-black/30 border border-gray-200/50 dark:border-white/5 rounded-xl px-4 py-3 font-semibold text-sm focus:border-red-600 focus:ring-4 focus:ring-red-600/10 focus:bg-white dark:focus:bg-black transition-all text-gray-900 dark:text-white" placeholder="First Name">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">Last Name</label>
                            <input type="text" name="lastname" value="{{ old('lastname', $user->lastname) }}" 
                                   class="w-full bg-gray-50/50 dark:bg-black/30 border border-gray-200/50 dark:border-white/5 rounded-xl px-4 py-3 font-semibold text-sm focus:border-red-600 focus:ring-4 focus:ring-red-600/10 focus:bg-white dark:focus:bg-black transition-all text-gray-900 dark:text-white" placeholder="Last Name">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">Username Handle</label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-red-600 font-bold text-sm">@</div>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}" 
                                   @input.debounce.500ms="validateUsername($event.target.value)"
                                   class="w-full bg-gray-50/50 dark:bg-black/30 border border-gray-200/50 dark:border-white/5 rounded-xl pl-10 pr-4 py-3 font-semibold text-sm focus:border-red-600 focus:ring-4 focus:ring-red-600/10 focus:bg-white dark:focus:bg-black transition-all text-gray-900 dark:text-white">
                        </div>
                        
                        <div class="mt-1 flex items-center gap-2 px-1">
                            <p x-show="usernameError" x-text="usernameError" class="text-[9px] font-black text-rose-500 uppercase tracking-widest" style="display: none;"></p>
                            <template x-if="loading">
                                <svg class="animate-spin h-3 w-3 text-red-600" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </template>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">Location / Country</label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-red-600 z-10 pointer-events-none">
                                <span class="material-symbols-rounded text-base">location_on</span>
                            </div>
                            <select name="country_name" 
                                    class="w-full bg-gray-50/50 dark:bg-black/30 border border-gray-200/50 dark:border-white/5 rounded-xl pl-10 pr-10 py-3 font-semibold text-sm focus:border-red-600 focus:ring-4 focus:ring-red-600/10 focus:bg-white dark:focus:bg-black transition-all appearance-none cursor-pointer text-gray-900 dark:text-white">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->country }}" {{ old('country_name', $user->country_name) == $country->country ? 'selected' : '' }}>
                                        {{ $country->country }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                <span class="material-symbols-rounded text-base">expand_more</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">About / Channel Bio</label>
                        <textarea name="description" rows="3" 
                                  class="w-full bg-gray-50/50 dark:bg-black/30 border border-gray-200/50 dark:border-white/5 rounded-xl px-4 py-3 font-semibold text-sm focus:border-red-600 focus:ring-4 focus:ring-red-600/10 focus:bg-white dark:focus:bg-black transition-all resize-none leading-relaxed text-gray-900 dark:text-white" 
                                  placeholder="Your story goes here...">{{ old('description', $user->description) }}</textarea>
                    </div>
                </div>

                <!-- Settings Group: Social Ecosystem -->
                <div class="bg-white dark:bg-[#111111]/90 backdrop-blur-md rounded-2xl p-5 md:p-6 space-y-5 border border-gray-200/50 dark:border-white/5 shadow-sm">
                    <div class="flex items-center gap-2 mb-1 px-1">
                        <span class="material-symbols-rounded text-blue-600 text-lg">hub</span>
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 dark:text-gray-500">Social Connections</h3>
                    </div>

                    @php
                        $socials = [
                            ['facebook', 'Facebook', 'text-blue-600', '<svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>'],
                            ['twitter', 'Twitter', 'text-slate-900 dark:text-white', '<svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.045 4.126H5.078z"/></svg>'],
                            ['instagram', 'Instagram', 'text-pink-600', '<svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>'],
                            ['youtube', 'YouTube', 'text-red-600', '<svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186c-.275-1.037-1.091-1.854-2.128-2.128C19.505 3.5 12 3.5 12 3.5s-7.505 0-9.37.558c-1.037.275-1.853 1.091-2.128 2.128C0 8.053 0 12 0 12s0 3.947.502 5.814c.275 1.037 1.091 1.854 2.128 2.128C4.495 20.5 12 20.5 12 20.5s7.505 0 9.37-.558c1.037-.275 1.853-1.091 2.128-2.128.502-1.867.502-5.814.502-5.814s0-3.947-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>']
                        ];
                    @endphp

                    @foreach($socials as $social)
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">{{ $social[1] }} Link</label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 {{ $social[2] }} shrink-0">
                                    {!! $social[3] !!}
                                </div>
                                <input type="url" name="social_links[{{ $social[0] }}]" 
                                       value="{{ old('social_links.' . $social[0], @$user->social_links[$social[0]]) }}" 
                                       class="w-full bg-gray-50/50 dark:bg-black/30 border border-gray-200/50 dark:border-white/5 rounded-xl pl-12 pr-4 py-3 font-semibold text-sm focus:border-red-600 focus:ring-4 focus:ring-red-600/10 focus:bg-white dark:focus:bg-black transition-all text-gray-900 dark:text-white" 
                                       placeholder="https://{{ $social[0] }}.com/...">
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Global Action Button (FAB Style) -->
                <div class="sticky bottom-6 md:static flex justify-center w-full px-2 md:px-0 z-50 md:mt-8">
                    <button type="submit" id="submit-btn" class="w-full md:max-w-md max-w-xs py-3.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-black uppercase tracking-widest text-[10px] md:text-xs shadow-md shadow-red-600/20 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2.5 group">
                        <span id="btn-icon" class="material-symbols-rounded text-base">cloud_upload</span>
                        <span id="btn-loader" class="animate-spin material-symbols-rounded text-base" style="display: none;">progress_activity</span>
                        <span id="btn-text">Save All Changes</span>
                    </button>
                </div>
            </form>

            <!-- Danger Zone -->
            <div class="mt-8 bg-white dark:bg-[#111111]/90 backdrop-blur-md rounded-2xl p-5 md:p-6 border border-rose-500/10 dark:border-rose-500/20 bg-rose-500/[0.02] space-y-4 shadow-sm">
                <div class="flex items-center gap-2 mb-1 px-1">
                    <span class="material-symbols-rounded text-rose-500 text-lg">dangerous</span>
                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-rose-500/80">Danger Zone</h3>
                </div>

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <h4 class="text-xs font-bold text-gray-900 dark:text-white">Request Account Deletion</h4>
                        <p class="text-[11px] text-gray-400 leading-relaxed">This will submit a request to delete your channel, videos, comments, and personal details permanently. This action is irreversible.</p>
                    </div>
                    <button type="button" @click="showDeleteModal = true"
                            class="px-5 py-2.5 bg-rose-500/10 hover:bg-rose-500 hover:text-white text-rose-500 rounded-xl font-bold uppercase tracking-widest text-[9px] transition-all border border-rose-500/20 shrink-0 self-start md:self-auto">
                        Delete Account
                    </button>
                </div>
            </div>

            <div class="mt-8 text-center pb-12">
                <a href="{{ route('home') }}" class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400 hover:text-red-600 transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-rounded text-sm">west</span>
                    Return to Feed
                </a>
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
             class="bg-white dark:bg-[#111111] border border-gray-200/50 dark:border-white/5 rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-5 transform transition-all max-h-[85vh] overflow-y-auto scrollbar-hide"
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
                    <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">Delete Account</h3>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Termination Request</p>
                </div>
            </div>

            <!-- Error display if userDeletion bag has error -->
            @if($errors->userDeletion->any())
                <div class="bg-rose-50 dark:bg-rose-950/20 text-rose-500 px-4 py-2.5 rounded-xl text-xs font-semibold border border-rose-500/10">
                    @foreach($errors->userDeletion->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
                @csrf
                @method('delete')

                <div class="space-y-2">
                    <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">Why are you leaving?</label>
                    <div class="space-y-2.5">
                        @php
                            $reasons = [
                                'Privacy concerns',
                                'Too busy / too distracting',
                                'Created a second account',
                                'Too many notifications/emails',
                                'Other'
                            ];
                        @endphp
                        @foreach($reasons as $reason)
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="reason" value="{{ $reason }}" x-model="deleteReason"
                                       class="w-4 h-4 rounded-full border-gray-300 text-rose-600 focus:ring-rose-500 bg-gray-50 dark:bg-black/30 dark:border-white/5">
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">{{ $reason }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Custom reason textarea -->
                <div x-show="deleteReason === 'Other'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-1.5" x-cloak>
                    <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">Please describe your reason</label>
                    <textarea name="custom_reason" rows="3" x-model="customReason"
                              class="w-full bg-gray-50/50 dark:bg-black/30 border border-gray-200/50 dark:border-white/5 rounded-xl px-4 py-3 font-semibold text-xs focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 focus:bg-white dark:focus:bg-black transition-all resize-none leading-relaxed text-gray-900 dark:text-white"
                              placeholder="Type details..."></textarea>
                </div>

                <div class="space-y-1.5 pt-2" x-data="{ showPassword: false }">
                    <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">Confirm Password</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="password" required
                               class="w-full bg-gray-50/50 dark:bg-black/30 border border-gray-200/50 dark:border-white/5 rounded-xl pl-4 pr-10 py-3 font-semibold text-xs focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 focus:bg-white dark:focus:bg-black transition-all text-gray-900 dark:text-white"
                               placeholder="Enter your current password">
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                            <span class="material-symbols-rounded text-sm" x-text="showPassword ? 'visibility_off' : 'visibility'">visibility</span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-white/5">
                    <button type="button" @click="showDeleteModal = false"
                            class="px-5 py-3 text-[9px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
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

    <script>
        const profileForm = document.getElementById('profile-edit-form');
        const submitBtn = document.getElementById('submit-btn');
        const btnIcon = document.getElementById('btn-icon');
        const btnLoader = document.getElementById('btn-loader');
        const btnText = document.getElementById('btn-text');

        if (profileForm) {
            profileForm.addEventListener('submit', function(e) {
                // Only trigger if not already loading
                if (submitBtn.disabled) return;

                // Visual feedback
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
                
                if (btnIcon) btnIcon.style.display = 'none';
                if (btnLoader) btnLoader.style.display = 'inline-block';
                if (btnText) btnText.innerText = 'Syncing...';

                // Toast Notification
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: "Syncing",
                        text: "Updating your profile information...",
                        icon: "info",
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                    });
                }
            });
        }

        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(previewId);
                    if (preview) {
                        preview.src = e.target.result;
                        preview.classList.remove('opacity-0');
                        preview.classList.add('opacity-100');
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
