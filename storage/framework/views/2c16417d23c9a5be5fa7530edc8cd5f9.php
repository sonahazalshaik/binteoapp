
<?php $__env->startSection('panel'); ?>
    <div class="submitRequired bg--warning form-change-alert d-none"><i class="fas fa-exclamation-triangle"></i> <?php echo app('translator')->get('You\'ve to click on the submit button to apply the changes'); ?></div>
    <div class="row mb-none-30">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg--primary d-flex justify-content-between">
                    <h5 class="text-white"><?php echo app('translator')->get('Data Form for Advertiser'); ?></h5>
                    <button type="button" class="btn btn-sm btn-outline-light float-end form-generate-btn"> <i class="la la-fw la-plus"></i><?php echo app('translator')->get('Add New'); ?></button>
                </div>
                <div class="card-body">
                    <form method="post">
                        <?php echo csrf_field(); ?>
                        <?php if (isset($component)) { $__componentOriginalf488000d75b568a9c1bd8bc1c7264734 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf488000d75b568a9c1bd8bc1c7264734 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.generated-form','data' => ['form' => $form]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('generated-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['form' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($form)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf488000d75b568a9c1bd8bc1c7264734)): ?>
<?php $attributes = $__attributesOriginalf488000d75b568a9c1bd8bc1c7264734; ?>
<?php unset($__attributesOriginalf488000d75b568a9c1bd8bc1c7264734); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf488000d75b568a9c1bd8bc1c7264734)): ?>
<?php $component = $__componentOriginalf488000d75b568a9c1bd8bc1c7264734; ?>
<?php unset($__componentOriginalf488000d75b568a9c1bd8bc1c7264734); ?>
<?php endif; ?>
                        <button type="submit" class="w-full sm:w-auto h-16 px-12 rounded-[2rem] orange-gradient-primary text-white font-black uppercase tracking-[0.2em] text-[12px] hover:scale-105 transition-all shadow-2xl active:scale-95 "><?php echo app('translator')->get('Submit'); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php if (isset($component)) { $__componentOriginal851f25776ae37501cefb50d6bb92a196 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal851f25776ae37501cefb50d6bb92a196 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form-generator-modal','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form-generator-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal851f25776ae37501cefb50d6bb92a196)): ?>
<?php $attributes = $__attributesOriginal851f25776ae37501cefb50d6bb92a196; ?>
<?php unset($__attributesOriginal851f25776ae37501cefb50d6bb92a196); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal851f25776ae37501cefb50d6bb92a196)): ?>
<?php $component = $__componentOriginal851f25776ae37501cefb50d6bb92a196; ?>
<?php unset($__componentOriginal851f25776ae37501cefb50d6bb92a196); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\advertiser\setting.blade.php ENDPATH**/ ?>