

<?php $__env->startSection('content'); ?>
<div class="min-h-[calc(100vh-80px)] bg-gray-50 dark:bg-[#0F0F0F] pt-12 pb-24 transition-colors duration-500">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        
        <!-- Header -->
        <div class="mb-10 text-center">
            <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white uppercase tracking-tighter mb-4"><?php echo e($pageTitle); ?></h1>
            <p class="text-gray-500 font-medium text-sm tracking-widest uppercase">Establish your creative identity</p>
        </div>

        <div class="bg-white dark:bg-[#1E1E1E] rounded-[2rem] sm:rounded-[3rem] shadow-2xl p-6 sm:p-12 border border-gray-100 dark:border-white/5 relative overflow-hidden" x-data="createChannelForm()">
            <!-- Decorative elements -->
            <div class="absolute -top-32 -right-32 w-64 h-64 bg-red-600/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-32 -left-32 w-64 h-64 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

            <form method="POST" action="<?php echo e(route('channels.store')); ?>" class="relative z-10 space-y-8">
                <?php echo csrf_field(); ?>

                <!-- Basic Info -->
                <div>
                    <h2 class="text-xs font-black text-red-600 uppercase tracking-widest mb-6">Tell us about your brand</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Channel Name -->
                        <div class="relative group">
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Your Channel Name</label>
                            <input type="text" name="channel_name" required value="<?php echo e(old('channel_name')); ?>"
                                class="w-full bg-gray-50 dark:bg-[#121212] border-2 border-transparent rounded-2xl h-14 px-4 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-[#1A1A1A] focus:border-red-500 focus:ring-0 transition-all font-bold placeholder-gray-300 dark:placeholder-gray-700 shadow-sm"
                                placeholder="Choose a name for your channel">
                            <?php $__errorArgs = ['channel_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-500 mt-1 block px-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Username -->
                        <div class="relative group">
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Choose a Username</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 font-bold">@</div>
                                <input type="text" name="username" required value="<?php echo e(old('username')); ?>"
                                    class="w-full bg-gray-50 dark:bg-[#121212] border-2 border-transparent rounded-2xl h-14 pl-10 pr-4 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-[#1A1A1A] focus:border-red-500 focus:ring-0 transition-all font-bold placeholder-gray-300 dark:placeholder-gray-700 shadow-sm"
                                    placeholder="your_unique_handle">
                            </div>
                            <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-500 mt-1 block px-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                    </div>
                </div>

                <div class="h-px bg-gray-100 dark:bg-white/5"></div>

                <!-- Location & Contact -->
                <div>
                    <h2 class="text-xs font-black text-red-600 uppercase tracking-widest mb-6">Where are you located?</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Country -->
                        <div class="relative group">
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Select Your Country</label>
                            <select name="country" required @change="updateMobileCode($event)"
                                class="w-full bg-gray-50 dark:bg-[#121212] border-2 border-transparent rounded-2xl h-14 px-4 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-[#1A1A1A] focus:border-red-500 focus:ring-0 transition-all font-bold shadow-sm appearance-none cursor-pointer">
                                <option value="" disabled selected>Select from list</option>
                                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($country->country); ?>" data-code="<?php echo e($country->code); ?>" data-dial="<?php echo e($country->dial_code); ?>"><?php echo e($country->country); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="absolute inset-y-0 right-4 top-11 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>

                        <!-- Mobile -->
                        <div class="relative group">
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Your Mobile Number</label>
                            
                            <input type="hidden" name="mobile_code" x-model="mobile_code">
                            <input type="hidden" name="country_code" x-model="country_code">
                            
                            <div class="relative flex border-2 border-transparent rounded-2xl overflow-hidden shadow-sm focus-within:border-red-500 transition-colors">
                                <div class="bg-gray-100 dark:bg-[#1A1A1A] px-4 flex items-center justify-center font-black text-gray-500 border-r border-gray-200 dark:border-white/5" x-text="'+' + mobile_code">
                                    +1
                                </div>
                                <input type="text" name="mobile" required value="<?php echo e(old('mobile')); ?>"
                                    class="w-full bg-gray-50 dark:bg-[#121212] border-none h-14 px-4 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-[#121212] focus:ring-0 font-bold placeholder-gray-300 dark:placeholder-gray-700"
                                    placeholder="555-123-4567">
                            </div>
                            <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-500 mt-1 block px-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Address -->
                        <div class="relative group md:col-span-2">
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Full Street Address</label>
                            <input type="text" name="address" value="<?php echo e(old('address')); ?>"
                                class="w-full bg-gray-50 dark:bg-[#121212] border-2 border-transparent rounded-2xl h-14 px-4 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-[#1A1A1A] focus:border-red-500 focus:ring-0 transition-all font-bold placeholder-gray-300 dark:placeholder-gray-700 shadow-sm"
                                placeholder="Street, Building, Apartment">
                        </div>

                        <!-- City & State -->
                        <div class="relative group">
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">City / Town</label>
                            <input type="text" name="city" value="<?php echo e(old('city')); ?>"
                                class="w-full bg-gray-50 dark:bg-[#121212] border-2 border-transparent rounded-2xl h-14 px-4 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-[#1A1A1A] focus:border-red-500 focus:ring-0 transition-all font-bold shadow-sm"
                                placeholder="Enter city">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-6">
                            <div class="relative group">
                                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">State/Province</label>
                                <input type="text" name="state" value="<?php echo e(old('state')); ?>"
                                    class="w-full bg-gray-50 dark:bg-[#121212] border-2 border-transparent rounded-2xl h-14 px-4 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-[#1A1A1A] focus:border-red-500 focus:ring-0 transition-all font-bold shadow-sm"
                                    placeholder="State">
                            </div>
                            <div class="relative group">
                                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Zip Code</label>
                                <input type="text" name="zip" value="<?php echo e(old('zip')); ?>"
                                    class="w-full bg-gray-50 dark:bg-[#121212] border-2 border-transparent rounded-2xl h-14 px-4 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-[#1A1A1A] focus:border-red-500 focus:ring-0 transition-all font-bold shadow-sm"
                                    placeholder="Zip">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full h-16 bg-red-600 hover:bg-red-700 text-white rounded-2xl font-black uppercase tracking-widest text-sm shadow-xl shadow-red-200/50 dark:shadow-red-900/20 transition-all active:scale-95 flex items-center justify-center space-x-3 gap-2">
                        <span>Launch My Channel</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </button>
                    <p class="text-center text-xs font-bold text-gray-400 mt-6 tracking-widest uppercase">© <?php echo e(date('Y')); ?> <?php echo e($siteSettings->site_name); ?> Platform. All Rights Reserved.</p>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    function createChannelForm() {
        return {
            mobile_code: '1',
            country_code: 'US',
            updateMobileCode(event) {
                const option = event.target.options[event.target.selectedIndex];
                this.mobile_code = option.dataset.dial || '';
                this.country_code = option.dataset.code || '';
            }
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\client\channels\create.blade.php ENDPATH**/ ?>