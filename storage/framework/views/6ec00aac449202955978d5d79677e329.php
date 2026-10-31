

<?php $__env->startSection('title', 'Refine Advertising Campaign'); ?>
<?php $__env->startSection('header_title', 'Modify Initiative'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto space-y-12 animate-in fade-in slide-in-from-bottom-10 duration-700 pb-24">
    <!-- Header -->
    <div class="flex items-center justify-between px-6 lg:px-0">
        <div>
            <h3 class="text-xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Campaign Modification</h3>
            <p class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3 leading-relaxed">Refining parameters for #<?php echo e($campaign->title); ?></p>
        </div>
        <a href="<?php echo e(route('admin.campaign.index')); ?>" class="w-12 h-12 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 dark:text-white/40 hover:bg-slate-900 hover:text-white transition-all shadow-xl active:scale-90">
            <span class="material-symbols-rounded">arrow_back</span>
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white/60 dark:bg-white/5 backdrop-blur-3xl border border-white dark:border-white/10 rounded-[2rem] p-8 lg:p-12 shadow-2xl relative overflow-hidden group">
        <form action="<?php echo e(route('admin.campaign.index')); ?>" method="POST" class="space-y-10">
            <?php echo csrf_field(); ?>
            
            <div class="space-y-10">
                <!-- Title -->
                <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'title','label' => 'Campaign Title','value' => ''.e($campaign->title).'','placeholder' => 'E.g. Summer Launch 2024...','required' => true,'hint' => 'Give your campaign a descriptive name for easier tracking.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'title','label' => 'Campaign Title','value' => ''.e($campaign->title).'','placeholder' => 'E.g. Summer Launch 2024...','required' => true,'hint' => 'Give your campaign a descriptive name for easier tracking.']); ?>
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Advertiser -->
                    <?php if (isset($component)) { $__componentOriginaled2cde6083938c436304f332ba96bb7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled2cde6083938c436304f332ba96bb7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['name' => 'user_id','label' => 'Associate Advertiser','required' => true,'hint' => 'Select the account responsible for this advertising initiative.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'user_id','label' => 'Associate Advertiser','required' => true,'hint' => 'Select the account responsible for this advertising initiative.']); ?>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php if($campaign->user_id == $user->id): echo 'selected'; endif; ?>><?php echo e($user->username); ?> (<?php echo e($user->email); ?>)</option>
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

                    <!-- Budget Allocation -->
                    <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['type' => 'number','step' => '0.01','name' => 'total_amount','label' => 'Total Budget Allocation ('.e(gs('cur_text')).')','value' => ''.e($campaign->total_amount).'','placeholder' => '50.00','required' => true,'hint' => 'Adjust the total budget allocation for this campaign.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'number','step' => '0.01','name' => 'total_amount','label' => 'Total Budget Allocation ('.e(gs('cur_text')).')','value' => ''.e($campaign->total_amount).'','placeholder' => '50.00','required' => true,'hint' => 'Adjust the total budget allocation for this campaign.']); ?>
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
            </div>

            <!-- Submit Button -->
            <div class="pt-10 border-t border-slate-100 dark:border-white/5">
                <button type="submit" class="w-full h-16 rounded-[2rem] bg-slate-900 dark:bg-white text-white dark:text-black flex items-center justify-center gap-4 text-[12px] font-black uppercase tracking-[0.4em] hover:scale-[1.02] transition-all shadow-2xl active:scale-95 group overflow-hidden relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-orange-500 to-red-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <span class="material-symbols-rounded text-base relative z-10 ">published_with_changes</span>
                    <span class="relative z-10 ">Sync Campaign</span>
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\campaign\edit.blade.php ENDPATH**/ ?>