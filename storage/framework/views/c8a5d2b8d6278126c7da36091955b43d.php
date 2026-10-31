

<?php $__env->startPush('style'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script'); ?>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
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
        /* Font-size hierarchy: Section headings > Field labels.
           Field labels from x-input come at 10px; shrink them on this page
           so the section headings (12-14px) stand out clearly. */
        #channel-create-form label.block.text-\[10px\] {
            font-size: 9px !important;
            color: rgb(15 23 42) !important; /* slate-900 - dark in light mode */
        }
        @media (min-width: 640px) {
            #channel-create-form label.block.text-\[10px\] {
                font-size: 10px !important;
            }
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

            <form action="<?php echo e(route('channels.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-5 relative z-10" 
                  x-data="{ 
                      avatarPreview: null,
                      agreed: false,
                      username: '<?php echo e(old('username')); ?>',
                      mobile: '<?php echo e(old('mobile')); ?>',
                      channel_name: '<?php echo e(old('channel_name', auth()->user()->name)); ?>',
                      errors: { 
                          username: '<?php echo e(addslashes($errors->first('username', ''))); ?>', 
                          mobile: '<?php echo e(addslashes($errors->first('mobile', ''))); ?>', 
                          channel_name: '<?php echo e(addslashes($errors->first('channel_name', ''))); ?>',
                          city: '<?php echo e(addslashes($errors->first('city', ''))); ?>',
                          state: '<?php echo e(addslashes($errors->first('state', ''))); ?>',
                          zip: '<?php echo e(addslashes($errors->first('zip', ''))); ?>'
                      },
                      loading: { username: false, mobile: false, channel_name: false },
                      city: '<?php echo e(old('city')); ?>',
                      state: '<?php echo e(old('state')); ?>',
                      zip: '<?php echo e(old('zip')); ?>',
                      get hasAnyError() {
                          return this.errors.username || this.errors.mobile || this.errors.channel_name || this.errors.city || this.errors.state || this.errors.zip;
                      },
                      get isLoading() {
                          return this.loading.username || this.loading.mobile || this.loading.channel_name;
                      },
                      get canSubmit() {
                          return this.agreed 
                              && !this.hasAnyError 
                              && !this.isLoading
                              && this.username 
                              && this.mobile 
                              && this.mobile.length === 10 
                              && this.channel_name;
                      },
                      validateField(field, value) {
                          console.log(`[Channel Create] Validating ${field}:`, value);
                          this[field] = value;

                          if (field === 'city' || field === 'state') {
                              if (value && /[^a-zA-Z\s]/.test(value)) {
                                  this.errors[field] = (field === 'city' ? 'City' : 'State') + ' can only contain letters and spaces';
                              } else {
                                  this.errors[field] = '';
                              }
                              return;
                          }

                          if (field === 'zip') {
                              if (value && /\D/.test(value)) {
                                  this.errors.zip = 'Zip code can only contain numbers';
                              } else if (value && (value.length < 4 || value.length > 10)) {
                                  this.errors.zip = 'Zip code must be between 4 and 10 digits';
                              } else {
                                  this.errors.zip = '';
                              }
                              return;
                          }

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
                              fetch('<?php echo e(route('channels.check-availability')); ?>', {
                                  method: 'POST',
                                  headers: {
                                      'Content-Type': 'application/json',
                                      'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
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
                <?php echo csrf_field(); ?>
                
                <!-- Section: Basic Information -->
                <div class="space-y-3 sm:space-y-4">
                    <div class="flex items-center gap-2 sm:gap-3 mb-1.5">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-blue-600/10 text-blue-600 flex items-center justify-center shadow-sm">
                            <span class="material-symbols-rounded text-base sm:text-lg">face</span>
                        </div>
                        <div>
                            <h3 class="text-xs sm:text-sm font-black text-gray-900 dark:text-white uppercase tracking-[0.2em]">Channel Identity</h3>
                            <p class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Basic channel details</p>
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
                                <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'channel_name','label' => 'Channel Name','value' => ''.e(old('channel_name', auth()->user()->name)).'','required' => 'true','icon' => 'badge','hint' => 'The public name of your channel.','@input.debounce.500ms' => 'validateField(\'channel_name\', $event.target.value)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'channel_name','label' => 'Channel Name','value' => ''.e(old('channel_name', auth()->user()->name)).'','required' => 'true','icon' => 'badge','hint' => 'The public name of your channel.','@input.debounce.500ms' => 'validateField(\'channel_name\', $event.target.value)']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                                <div class="flex items-center gap-2 mt-1">
                                    <p x-show="errors.channel_name" x-text="errors.channel_name" class="text-[9px] sm:text-[10px] font-bold text-rose-500 uppercase tracking-widest" style="display: none;"></p>
                                    <template x-if="loading.channel_name">
                                        <svg class="animate-spin h-3 w-3 text-blue-500" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    </template>
                                </div>
                            </div>
                            
                            <div class="space-y-1">
                                <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'username','label' => 'Username','value' => ''.e(old('username')).'','required' => 'true','icon' => 'alternate_email','hint' => 'Must be unique. This will be your channel URL.','@input.debounce.500ms' => '$event.target.value = $event.target.value.toLowerCase().replace(/\s/g, \'\'); username = $event.target.value; validateField(\'username\', $event.target.value)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'username','label' => 'Username','value' => ''.e(old('username')).'','required' => 'true','icon' => 'alternate_email','hint' => 'Must be unique. This will be your channel URL.','@input.debounce.500ms' => '$event.target.value = $event.target.value.toLowerCase().replace(/\s/g, \'\'); username = $event.target.value; validateField(\'username\', $event.target.value)']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
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
                            <h3 class="text-xs sm:text-sm font-black text-gray-900 dark:text-white uppercase tracking-[0.2em]">Contact & Location</h3>
                            <p class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Verification and outreach</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 sm:gap-x-6 gap-y-2 sm:gap-y-3">
                        <div class="space-y-1">
                            <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'mobile','label' => 'Mobile Number','value' => ''.e(old('mobile')).'','required' => 'true','icon' => 'call','hint' => 'Primary contact (10 digits).','maxlength' => '10','inputmode' => 'numeric','pattern' => '[0-9]{10}','@input' => 'mobile = $event.target.value.replace(/\D/g, \'\').substring(0, 10); $event.target.value = mobile; validateField(\'mobile\', mobile)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'mobile','label' => 'Mobile Number','value' => ''.e(old('mobile')).'','required' => 'true','icon' => 'call','hint' => 'Primary contact (10 digits).','maxlength' => '10','inputmode' => 'numeric','pattern' => '[0-9]{10}','@input' => 'mobile = $event.target.value.replace(/\D/g, \'\').substring(0, 10); $event.target.value = mobile; validateField(\'mobile\', mobile)']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                            <div class="flex items-center gap-2 mt-1">
                                <p x-show="errors.mobile" x-text="errors.mobile" class="text-[9px] sm:text-[10px] font-bold text-rose-500 uppercase tracking-widest" style="display: none;"></p>
                                <template x-if="loading.mobile">
                                    <svg class="animate-spin h-3 w-3 text-orange-500" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </template>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <?php if (isset($component)) { $__componentOriginaled2cde6083938c436304f332ba96bb7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled2cde6083938c436304f332ba96bb7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['name' => 'country','label' => 'Country','required' => 'true','icon' => 'public','hint' => 'Your primary location.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'country','label' => 'Country','required' => 'true','icon' => 'public','hint' => 'Your primary location.']); ?>
                                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($country->country); ?>" data-code="<?php echo e($country->dial_code); ?>" <?php echo e($code == 'US' ? 'selected' : ''); ?>>
                                        <?php echo e($country->country); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaled2cde6083938c436304f332ba96bb7c)): ?>
<?php $attributes = $__attributesOriginaled2cde6083938c436304f332ba96bb7c; ?>
<?php unset($__attributesOriginaled2cde6083938c436304f332ba96bb7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaled2cde6083938c436304f332ba96bb7c)): ?>
<?php $component = $__componentOriginaled2cde6083938c436304f332ba96bb7c; ?>
<?php unset($__componentOriginaled2cde6083938c436304f332ba96bb7c); ?>
<?php endif; ?>
                            <input type="hidden" name="country_code" value="1">
                        </div>
                    </div>

                    <?php if (isset($component)) { $__componentOriginal4727f9fd7c3055c2cf9c658d89b16886 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.textarea','data' => ['name' => 'address','label' => 'Address','value' => ''.e(old('address')).'','icon' => 'home','rows' => '4','hint' => 'Physical address for verification.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'address','label' => 'Address','value' => ''.e(old('address')).'','icon' => 'home','rows' => '4','hint' => 'Physical address for verification.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886)): ?>
<?php $attributes = $__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886; ?>
<?php unset($__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4727f9fd7c3055c2cf9c658d89b16886)): ?>
<?php $component = $__componentOriginal4727f9fd7c3055c2cf9c658d89b16886; ?>
<?php unset($__componentOriginal4727f9fd7c3055c2cf9c658d89b16886); ?>
<?php endif; ?>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-3">
                        <div class="col-span-1 md:col-span-1 space-y-1">
                            <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'city','label' => 'City','value' => ''.e(old('city')).'','hint' => 'Your city','@input' => 'validateField(\'city\', $event.target.value)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'city','label' => 'City','value' => ''.e(old('city')).'','hint' => 'Your city','@input' => 'validateField(\'city\', $event.target.value)']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                            <p x-show="errors.city" x-text="errors.city" class="text-[9px] sm:text-[10px] font-bold text-rose-500 uppercase tracking-widest mt-1" style="display: none;"></p>
                        </div>
                        <div class="col-span-1 md:col-span-1 space-y-1">
                            <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'state','label' => 'State','value' => ''.e(old('state')).'','hint' => 'Your state','@input' => 'validateField(\'state\', $event.target.value)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'state','label' => 'State','value' => ''.e(old('state')).'','hint' => 'Your state','@input' => 'validateField(\'state\', $event.target.value)']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                            <p x-show="errors.state" x-text="errors.state" class="text-[9px] sm:text-[10px] font-bold text-rose-500 uppercase tracking-widest mt-1" style="display: none;"></p>
                        </div>
                        <div class="col-span-2 md:col-span-2 w-full space-y-1">
                            <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'zip','label' => 'Zip Code','value' => ''.e(old('zip')).'','hint' => 'Your postal code','maxlength' => '10','inputmode' => 'numeric','@input' => '$event.target.value = $event.target.value.replace(/\D/g, \'\'); zip = $event.target.value; validateField(\'zip\', zip)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'zip','label' => 'Zip Code','value' => ''.e(old('zip')).'','hint' => 'Your postal code','maxlength' => '10','inputmode' => 'numeric','@input' => '$event.target.value = $event.target.value.replace(/\D/g, \'\'); zip = $event.target.value; validateField(\'zip\', zip)']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                            <p x-show="errors.zip" x-text="errors.zip" class="text-[9px] sm:text-[10px] font-bold text-rose-500 uppercase tracking-widest mt-1" style="display: none;"></p>
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
                                :disabled="!canSubmit"
                                :class="!canSubmit ? 'opacity-50 grayscale cursor-not-allowed scale-95' : 'hover:scale-[1.02] shadow-orange-600/20'"
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
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\channels\create.blade.php ENDPATH**/ ?>