<?php $__env->startSection('title', 'Edit Channel Plan: ' . $plan->name); ?>
<?php $__env->startSection('header_title', 'Edit Channel Plan'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-700">
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl">
        <div class="px-10 py-8 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02] flex items-center justify-between">
            <div>
                <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">Modify Plan: <?php echo e($plan->name); ?></h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Update parameters for this channel subscription tier</p>
            </div>
            <span class="px-4 py-1.5 rounded-full <?php echo e($plan->status ? 'bg-emerald-500/10 text-emerald-500' : 'bg-slate-100 text-slate-400'); ?> text-[10px] font-black uppercase tracking-widest">
                <?php echo e($plan->status ? 'Active' : 'Disabled'); ?>

            </span>
        </div>

        <form action="<?php echo e(route('admin.channel-plans.update', $plan->id)); ?>" method="POST" class="p-10 space-y-10">
            <?php echo csrf_field(); ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'name','label' => 'Plan Name','value' => ''.e($plan->name).'','required' => 'true','icon' => 'workspace_premium','hint' => 'Title visible to creators and fans.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'name','label' => 'Plan Name','value' => ''.e($plan->name).'','required' => 'true','icon' => 'workspace_premium','hint' => 'Title visible to creators and fans.']); ?>
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
                
                <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['type' => 'number','step' => '0.01','name' => 'default_price','label' => 'Suggested Price (₹)','value' => ''.e($plan->default_price).'','required' => 'true','icon' => 'payments','hint' => 'Recommended starting price.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'number','step' => '0.01','name' => 'default_price','label' => 'Suggested Price (₹)','value' => ''.e($plan->default_price).'','required' => 'true','icon' => 'payments','hint' => 'Recommended starting price.']); ?>
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

                <div class="flex flex-col space-y-2">
                    <label class="text-[12px] font-black text-slate-900 dark:text-white uppercase leading-none flex items-center gap-2">
                        <span class="material-symbols-rounded text-[18px]">calendar_month</span>
                        Duration (Months)
                    </label>
                    <div class="relative group/select">
                        <select name="duration_months" required class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl pl-6 pr-14 text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:border-amber-500 transition-all appearance-none cursor-pointer">
                            <option value="1" <?php echo e($plan->duration_months == 1 ? 'selected' : ''); ?>>1 Month (30 Days)</option>
                            <option value="2" <?php echo e($plan->duration_months == 2 ? 'selected' : ''); ?>>2 Months (60 Days)</option>
                            <option value="3" <?php echo e($plan->duration_months == 3 ? 'selected' : ''); ?>>3 Months (90 Days)</option>
                            <option value="6" <?php echo e($plan->duration_months == 6 ? 'selected' : ''); ?>>6 Months (180 Days)</option>
                            <option value="12" <?php echo e($plan->duration_months == 12 ? 'selected' : ''); ?>>12 Months / 1 Year (365 Days)</option>
                            <option value="24" <?php echo e($plan->duration_months == 24 ? 'selected' : ''); ?>>24 Months / 2 Years (730 Days)</option>
                        </select>
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 group-hover/select:text-amber-500 transition-colors">
                            <span class="material-symbols-rounded">expand_more</span>
                        </div>
                    </div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Standard subscription period</p>
                </div>

                <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['type' => 'number','name' => 'sort_order','label' => 'Display Order','value' => ''.e($plan->sort_order).'','icon' => 'sort','hint' => 'Sequence priority in Creator Studio selection.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'number','name' => 'sort_order','label' => 'Display Order','value' => ''.e($plan->sort_order).'','icon' => 'sort','hint' => 'Sequence priority in Creator Studio selection.']); ?>
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

            <?php if (isset($component)) { $__componentOriginal4727f9fd7c3055c2cf9c658d89b16886 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.textarea','data' => ['name' => 'description','label' => 'Plan Description & Perks','value' => ''.e($plan->description).'','rows' => '4','icon' => 'description','hint' => 'Explains what this plan offers.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'description','label' => 'Plan Description & Perks','value' => ''.e($plan->description).'','rows' => '4','icon' => 'description','hint' => 'Explains what this plan offers.']); ?>
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

            <div class="pt-8 flex items-center justify-end gap-6 border-t border-slate-100 dark:border-white/5">
                <a href="<?php echo e(route('admin.channel-plans.index')); ?>" class="h-16 px-10 rounded-[2rem] bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-white/30 font-black text-[11px] uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-white/10 transition-all flex items-center justify-center">Cancel</a>
                <button type="submit" class="h-16 px-12 rounded-[2rem] bg-gradient-to-r from-amber-500 to-orange-500 text-white font-black text-[12px] uppercase tracking-[0.2em] shadow-2xl shadow-orange-500/20 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-3">
                    <span>Save Changes</span>
                    <span class="material-symbols-rounded text-xl">save</span>
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\channel_plans\edit.blade.php ENDPATH**/ ?>