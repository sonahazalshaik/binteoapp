

<?php $__env->startSection('title', 'Refine Frequency'); ?>
<?php $__env->startSection('header_title', 'Channel Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-24">
    <div class="flex items-center justify-between px-6 lg:px-0">
        <div>
            <h3 class="text-3xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Refine Frequency</h3>
            <p class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3">Edit channel branding for #<?php echo e($channel->id); ?></p>
        </div>
        <a href="<?php echo e(route('admin.channels.index')); ?>" class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-400 hover:text-rose-500 flex items-center justify-center transition-all shadow-sm active:scale-90">
            <span class="material-symbols-rounded text-xl">close</span>
        </a>
    </div>

    <form action="<?php echo e(route('admin.channels.update', $channel)); ?>" method="POST" enctype="multipart/form-data" 
          x-data="{ 
            synching: false, 
            avatarPreview: '<?php echo e($channel->avatar ? getImage(getFilePath('channelAvatar') . '/' . $channel->avatar) : ''); ?>',
            bannerPreview: '<?php echo e($channel->banner ? getImage(getFilePath('channelBanner') . '/' . $channel->banner) : ''); ?>',
            country: '<?php echo e($channel->user->country_name); ?>',
            countryCode: '<?php echo e($channel->user->country_code ?? '91'); ?>'
          }" 
          @submit="synching = true"
          class="space-y-8">
        <?php echo csrf_field(); ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Panel: Identity & Visuals -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Owner Info -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                                <span class="material-symbols-rounded text-lg">person</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Owner Identity</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Username</p>
                                <p class="text-xs font-bold text-slate-700 dark:text-white">@<span><?php echo e($channel->user->username); ?></span></p>
                            </div>
                            <div class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Full Name</p>
                                <p class="text-xs font-bold text-slate-700 dark:text-white"><?php echo e($channel->user->fullname); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Avatar Preview -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm group">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-tight">Channel Avatar</h3>
                            <span class="material-symbols-rounded text-orange-500">account_circle</span>
                        </div>

                        <div class="relative aspect-square rounded-2xl border-2 border-dashed border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-white/[0.02] overflow-hidden flex flex-col items-center justify-center transition-all hover:border-orange-500/50 group/zone">
                            <img x-show="avatarPreview" :src="avatarPreview" class="w-full h-full object-cover transition-all group-hover/zone:scale-105">
                            <div x-show="!avatarPreview" class="flex flex-col items-center justify-center text-slate-300 dark:text-white/10">
                                <span class="material-symbols-rounded text-5xl">account_circle</span>
                                <p class="text-[9px] font-black uppercase tracking-widest mt-2 text-slate-400">No Avatar</p>
                            </div>
                            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 group-hover/zone:opacity-100 transition-all flex flex-col items-center justify-center text-white gap-2">
                                <span class="material-symbols-rounded text-3xl">add_a_photo</span>
                                <p class="text-[10px] font-bold uppercase tracking-widest">Update Avatar</p>
                            </div>
                            <input type="file" name="avatar" accept="image/*"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                   @change="openCropper($event.target, null, {aspectRatio: 1}, (file, url) => { avatarPreview = url; })">
                        </div>
                    </div>
                </div>

                <!-- Banner Preview -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm group">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-tight">Channel Banner</h3>
                            <span class="material-symbols-rounded text-blue-500">image</span>
                        </div>

                        <div class="relative aspect-[6.2/1] rounded-2xl border-2 border-dashed border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-white/[0.02] overflow-hidden flex flex-col items-center justify-center transition-all hover:border-blue-500/50 group/zone">
                            <img x-show="bannerPreview" :src="bannerPreview" class="w-full h-full object-cover transition-all group-hover/zone:scale-105">
                            <div x-show="!bannerPreview" class="flex flex-col items-center justify-center text-slate-300 dark:text-white/10">
                                <span class="material-symbols-rounded text-5xl">image</span>
                                <p class="text-[9px] font-black uppercase tracking-widest mt-2 text-slate-400">No Banner</p>
                            </div>
                            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 group-hover/zone:opacity-100 transition-all flex flex-col items-center justify-center text-white gap-2">
                                <span class="material-symbols-rounded text-3xl">landscape</span>
                                <p class="text-[10px] font-bold uppercase tracking-widest">Update Banner</p>
                            </div>
                            <input type="file" name="banner" accept="image/*"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                   @change="openCropper($event.target, null, {aspectRatio: 6.2}, (file, url) => { bannerPreview = url; })">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Channel Configuration -->
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                                <span class="material-symbols-rounded text-lg">settings_input_antenna</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Broadcast Parameters</h3>
                        </div>

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'name','label' => 'Channel Name','value' => ''.e($channel->name).'','required' => 'true','icon' => 'label','hint' => 'Update the recognizable name for the channel.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'name','label' => 'Channel Name','value' => ''.e($channel->name).'','required' => 'true','icon' => 'label','hint' => 'Update the recognizable name for the channel.']); ?>
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
                                
                                <div class="space-y-2">
                                    <?php if (isset($component)) { $__componentOriginaled2cde6083938c436304f332ba96bb7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled2cde6083938c436304f332ba96bb7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['name' => 'country','label' => 'Operational Base','required' => 'true','icon' => 'public','hint' => 'The channel\'s primary location.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'country','label' => 'Operational Base','required' => 'true','icon' => 'public','hint' => 'The channel\'s primary location.']); ?>
                                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($country->country); ?>" data-code="<?php echo e($country->dial_code); ?>" <?php echo e($channel->user->country_name == $country->country ? 'selected' : ''); ?>>
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
                                    <input type="hidden" name="country_code" :value="countryCode">
                                </div>
                            </div>
                            
                            <?php if (isset($component)) { $__componentOriginal4727f9fd7c3055c2cf9c658d89b16886 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.textarea','data' => ['name' => 'description','label' => 'Signal Description','placeholder' => 'Define the mission and content focus of this broadcast frequency...','rows' => '6','icon' => 'description','hint' => 'A compelling description attracts more subscribers.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'description','label' => 'Signal Description','placeholder' => 'Define the mission and content focus of this broadcast frequency...','rows' => '6','icon' => 'description','hint' => 'A compelling description attracts more subscribers.']); ?><?php echo e($channel->description); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886)): ?>
<?php $attributes = $__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886; ?>
<?php unset($__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4727f9fd7c3055c2cf9c658d89b16886)): ?>
<?php $component = $__componentOriginal4727f9fd7c3055c2cf9c658d89b16886; ?>
<?php unset($__componentOriginal4727f9fd7c3055c2cf9c658d89b16886); ?>
<?php endif; ?>
                        </div>

                        <!-- Social Framework -->
                        <div class="pt-8 space-y-6 border-t border-slate-100 dark:border-white/5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-500">
                                    <span class="material-symbols-rounded text-lg">hub</span>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Social Linkage</h3>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <?php $links = $channel->social_links ?? []; ?>
                                <?php $__currentLoopData = ['Facebook', 'Twitter', 'Instagram', 'YouTube']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['type' => 'url','name' => 'social_links['.e(strtolower($social)).']','label' => ''.e($social).' URL','value' => ''.e(@$links[strtolower($social)]).'','placeholder' => 'https://'.e(strtolower($social)).'.com/...','icon' => 'link']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'url','name' => 'social_links['.e(strtolower($social)).']','label' => ''.e($social).' URL','value' => ''.e(@$links[strtolower($social)]).'','placeholder' => 'https://'.e(strtolower($social)).'.com/...','icon' => 'link']); ?>
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
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <div class="flex pt-6 border-t border-slate-100 dark:border-white/5">
                            <button type="submit" :disabled="synching" 
                                    class="w-full h-16 rounded-2xl bg-orange-600 text-white text-sm font-bold uppercase tracking-widest hover:bg-orange-700 active:scale-95 transition-all flex items-center justify-center gap-3 disabled:opacity-50 disabled:grayscale disabled:cursor-not-allowed shadow-lg shadow-orange-500/20">
                                <span x-show="!synching" class="material-symbols-rounded text-xl">save_as</span>
                                <span x-show="synching" class="material-symbols-rounded animate-spin">sync</span>
                                <span x-text="synching ? 'Optimizing Signal...' : 'Apply Parameters'">Apply Parameters</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script>
    (function($){
        "use strict";
        $('select[name="country"]').on('change', function() {
            const code = $(this).find(':selected').data('code');
            // Sync with Alpine state
            const alpineData = document.querySelector('[x-data]').__x.$data;
            alpineData.countryCode = code;
        });
    })(jQuery);
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\channels\edit.blade.php ENDPATH**/ ?>