

<?php $__env->startSection('title', 'Create New Channel'); ?>
<?php $__env->startSection('header_title', 'Channel Management'); ?>

<?php $__env->startSection('content'); ?>
<form action="<?php echo e(route('admin.channels.store')); ?>" method="POST" enctype="multipart/form-data"
      x-data="{ avatarPreview: null, bannerPreview: null }">
    <?php echo csrf_field(); ?>
    <div class="max-w-5xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-700 pb-24">
        <div class="bg-white dark:bg-[#121212] rounded-[3rem] p-10 border border-slate-200 dark:border-white/10 shadow-2xl relative overflow-hidden">
            <!-- Decorative Glow -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-orange-500/5 blur-[100px] -mr-48 -mt-48 rounded-full"></div>
            
            <div class="relative z-10">
                <div class="mb-12">
                    <h3 class="text-3xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Channel Registration</h3>
                    <p class="text-[10px] font-black text-slate-500 dark:text-white/30 uppercase tracking-[0.4em] mt-3 leading-relaxed">Set up the primary details and branding for this new channel</p>
                </div>

                <div class="space-y-2">
                    <!-- Section 1: Core Identity -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8">
                        <?php if (isset($component)) { $__componentOriginaled2cde6083938c436304f332ba96bb7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled2cde6083938c436304f332ba96bb7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['name' => 'user_id','label' => 'Assign Owner (User)','required' => true,'hint' => 'Select the primary account responsible for this channel.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'user_id','label' => 'Assign Owner (User)','required' => true,'hint' => 'Select the primary account responsible for this channel.']); ?>
                            <option value="">Select User...</option>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>">@<span><?php echo e($user->username); ?></span> (<?php echo e($user->fullname); ?>)</option>
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

                        <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'name','label' => 'Channel Name','placeholder' => 'Ex: Crystal HD Network','required' => true,'hint' => 'Enter a unique and recognizable name for the channel.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'name','label' => 'Channel Name','placeholder' => 'Ex: Crystal HD Network','required' => true,'hint' => 'Enter a unique and recognizable name for the channel.']); ?>
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
                    </div>

                    <!-- Section 2: Visual Branding -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                        <div class="space-y-4">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/30 px-1 mb-2">Channel Avatar</label>
                            <div class="relative group h-40">
                                <input type="file" name="avatar" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                       @change="openCropper($event.target, null, {aspectRatio: 1}, (file, url) => { avatarPreview = url; })">
                                <div class="w-full h-full bg-slate-50 dark:bg-black/40 border-4 border-dashed border-slate-200 dark:border-white/10 rounded-[2.5rem] flex items-center px-8 gap-6 group-hover:border-orange-500/50 transition-all overflow-hidden">
                                    <template x-if="avatarPreview">
                                        <img :src="avatarPreview" class="w-full h-full object-cover absolute inset-0">
                                    </template>
                                    <template x-if="!avatarPreview">
                                        <div class="flex items-center gap-6">
                                            <div class="w-16 h-16 rounded-2xl bg-orange-500/10 flex items-center justify-center text-orange-500 shadow-lg">
                                                <span class="material-symbols-rounded text-3xl">account_circle</span>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[11px] font-black text-slate-600 dark:text-white/80 uppercase tracking-widest leading-none">Upload Avatar</span>
                                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-2 leading-none ">512x512 recommended</span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/30 px-1 mb-2">Channel Banner</label>
                            <div class="relative group h-40">
                                <input type="file" name="banner" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                       @change="openCropper($event.target, null, {aspectRatio: 6.2}, (file, url) => { bannerPreview = url; })">
                                <div class="w-full h-full bg-slate-50 dark:bg-black/40 border-4 border-dashed border-slate-200 dark:border-white/10 rounded-[2.5rem] flex items-center px-8 gap-6 group-hover:border-blue-500/50 transition-all overflow-hidden">
                                    <template x-if="bannerPreview">
                                        <img :src="bannerPreview" class="w-full h-full object-cover absolute inset-0">
                                    </template>
                                    <template x-if="!bannerPreview">
                                        <div class="flex items-center gap-6">
                                            <div class="w-16 h-16 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 shadow-lg">
                                                <span class="material-symbols-rounded text-3xl">image</span>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[11px] font-black text-slate-600 dark:text-white/80 uppercase tracking-widest leading-none">Upload Banner</span>
                                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-2 leading-none ">1920x480 recommended</span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Descriptive Narrative -->
                    <?php if (isset($component)) { $__componentOriginal4727f9fd7c3055c2cf9c658d89b16886 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.textarea','data' => ['name' => 'description','label' => 'Channel Description','placeholder' => 'Enter the mission and content focus of this channel...','rows' => '5','hint' => 'A compelling description helps attract more subscribers.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'description','label' => 'Channel Description','placeholder' => 'Enter the mission and content focus of this channel...','rows' => '5','hint' => 'A compelling description helps attract more subscribers.']); ?>
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

                    <!-- Section 4: Social Linkage Matrix -->
                    <div class="pt-8 space-y-8">
                        <div class="flex items-center gap-4">
                            <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-[0.3em] ">Social Framework</span>
                            <div class="h-[1px] flex-grow bg-slate-100 dark:bg-white/5"></div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8">
                            <?php $__currentLoopData = ['Facebook', 'Twitter', 'Instagram', 'YouTube']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['type' => 'url','name' => 'social_links['.e(strtolower($social)).']','label' => ''.e($social).' URL','placeholder' => 'https://'.e(strtolower($social)).'.com/...','hint' => 'Connect the channel\'s official '.e($social).' presence.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'url','name' => 'social_links['.e(strtolower($social)).']','label' => ''.e($social).' URL','placeholder' => 'https://'.e(strtolower($social)).'.com/...','hint' => 'Connect the channel\'s official '.e($social).' presence.']); ?>
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
                </div>

                <!-- Footer Actions -->
                <div class="mt-16 flex flex-col sm:flex-row gap-6">
                    <button type="submit" class="flex-grow h-20 rounded-[2.5rem] orange-gradient-primary text-white text-[12px] font-black uppercase tracking-[0.3em] shadow-2xl shadow-orange-500/30 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-4">
                        <span class="material-symbols-rounded text-2xl">sensors</span>
                        Create Channel
                    </button>
                    <a href="<?php echo e(route('admin.channels.index')); ?>" class="h-20 px-12 rounded-[2.5rem] bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-white/40 text-[11px] font-black uppercase tracking-[0.2em] hover:bg-slate-200 dark:hover:bg-white/10 transition-all flex items-center justify-center border border-transparent">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\channels\create.blade.php ENDPATH**/ ?>