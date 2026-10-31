

<?php $__env->startSection('panel'); ?>
<?php $__env->startPush('topBar'); ?>
<?php echo $__env->make('admin.gateways.top_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <form action="<?php echo e(route('admin.gateway.manual.update', $method->code)); ?>" method="POST"
                      enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <div class="payment-method-item">
                            <div class="gateway-body">
                                <div class="gateway-thumb">
                                    <div class="thumb">
                                        <?php if (isset($component)) { $__componentOriginaldbcc027cdd3569f61821c56d10b77c01 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldbcc027cdd3569f61821c56d10b77c01 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.image-uploader','data' => ['image' => ''.e($method->image).'','class' => 'w-100','type' => 'gateway','required' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('image-uploader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => ''.e($method->image).'','class' => 'w-100','type' => 'gateway','required' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldbcc027cdd3569f61821c56d10b77c01)): ?>
<?php $attributes = $__attributesOriginaldbcc027cdd3569f61821c56d10b77c01; ?>
<?php unset($__attributesOriginaldbcc027cdd3569f61821c56d10b77c01); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldbcc027cdd3569f61821c56d10b77c01)): ?>
<?php $component = $__componentOriginaldbcc027cdd3569f61821c56d10b77c01; ?>
<?php unset($__componentOriginaldbcc027cdd3569f61821c56d10b77c01); ?>
<?php endif; ?>
                                    </div>
                                </div>
                                <div class="gateway-content">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Gateway Name'); ?></label>
                                                <input type="text" class="form-control" name="name" value="<?php echo e($method->name); ?>" required/>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Currency'); ?></label>
                                                <input type="text" name="currency" class="form-control border-radius-5" value="<?php echo e(@$method->singleCurrency->currency); ?>" required/>
                                            </div>
                                        </div>
                                        <div class="col-xl-5 col-md-12">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Rate'); ?></label>
                                                <div class="input-group">
                                                    <div class="input-group-text">1 <?php echo e(__(gs('cur_text'))); ?>=</div>
                                                    <input type="number" step="any" class="form-control" name="rate" value="<?php echo e(getAmount(@$method->singleCurrency->rate)); ?>" required/>
                                                    <span class="currency_symbol input-group-text"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="payment-method-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="card border border--primary mt-3">
                                            <h5 class="card-header bg--primary"><?php echo app('translator')->get('Range'); ?></h5>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Minimum Amount'); ?></label>
                                                    <div class="input-group">
                                                        <input type="number" step="any" class="form-control" name="min_limit" value="<?php echo e(getAmount(@$method->singleCurrency->min_amount)); ?>" required>
                                                        <div class="input-group-text"><?php echo e(__(gs('cur_text'))); ?></div>
                                                    </div>
                                                    <span class="min-limit-error-message text--danger"></span>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Maximum Amount'); ?></label>
                                                    <div class="input-group">
                                                        <input type="number" step="any" class="form-control" name="max_limit" value="<?php echo e(getAmount(@$method->singleCurrency->max_amount)); ?>" required>
                                                        <div class="input-group-text"><?php echo e(__(gs('cur_text'))); ?></div>
                                                    </div>
                                                    <span class="max-limit-error-message text--danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="card border border--primary mt-3">
                                            <h5 class="card-header bg--primary"><?php echo app('translator')->get('Charge'); ?></h5>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Fixed Charge'); ?></label>
                                                    <div class="input-group">
                                                        <input type="number" step="any" class="form-control" name="fixed_charge" value="<?php echo e(getAmount(@$method->singleCurrency->fixed_charge)); ?>" required/>
                                                        <div class="input-group-text"><?php echo e(__(gs('cur_text'))); ?></div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Percent Charge'); ?></label>
                                                    <div class="input-group">
                                                        <input type="number" step="any" class="form-control" name="percent_charge" value="<?php echo e(getAmount(@$method->singleCurrency->percent_charge)); ?>" required>
                                                        <div class="input-group-text">%</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="card border border--primary mt-3">

                                            <h5 class="card-header bg--primary"><?php echo app('translator')->get('Deposit Instruction'); ?></h5>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <textarea rows="8" class="form-control border-radius-5 nicEdit" name="instruction"><?php echo e(__(@$method->description)); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="submitRequired bg--warning form-change-alert d-none mt-3"><i class="fas fa-exclamation-triangle"></i> <?php echo app('translator')->get('You\'ve to click on the submit button to apply the changes'); ?></div>
                                        <div class="card border border--primary mt-3">
                                            <div class="card-header bg--primary d-flex justify-content-between">
                                                <h5 class="text-white"><?php echo app('translator')->get('User Data'); ?></h5>
                                                <button type="button" class="btn btn-sm btn-outline-light float-end form-generate-btn"> <i class="la la-fw la-plus"></i><?php echo app('translator')->get('Add New'); ?></button>
                                            </div>
                                            <div class="card-body">
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="w-full sm:w-auto h-16 px-12 rounded-[2rem] orange-gradient-primary text-white font-black uppercase tracking-[0.2em] text-[12px] hover:scale-105 transition-all shadow-2xl active:scale-95 "><?php echo app('translator')->get('Submit'); ?></button>
                    </div>
                </form>
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



<?php $__env->startPush('breadcrumb-plugins'); ?>
    <?php if (isset($component)) { $__componentOriginal3b9bf6c313f6db4d5c9389e5666c89a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b9bf6c313f6db4d5c9389e5666c89a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.back','data' => ['route' => ''.e(route('admin.gateway.manual.index')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('back'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => ''.e(route('admin.gateway.manual.index')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b9bf6c313f6db4d5c9389e5666c89a5)): ?>
<?php $attributes = $__attributesOriginal3b9bf6c313f6db4d5c9389e5666c89a5; ?>
<?php unset($__attributesOriginal3b9bf6c313f6db4d5c9389e5666c89a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b9bf6c313f6db4d5c9389e5666c89a5)): ?>
<?php $component = $__componentOriginal3b9bf6c313f6db4d5c9389e5666c89a5; ?>
<?php unset($__componentOriginal3b9bf6c313f6db4d5c9389e5666c89a5); ?>
<?php endif; ?>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>

    (function($) {
            "use strict";

            $('input[name=currency]').on('input', function() {
                $('.currency_symbol').text($(this).val());
            });
            $('.currency_symbol').text($('input[name=currency]').val());

            <?php if(old('currency')): ?>
                $('input[name=currency]').trigger('input');
            <?php endif; ?>

            let minLimit = $('[name=min_limit]');
            let maxLimit = $('[name=max_limit]');
            let rateErrorMessage = $('.rate-error-message');
            let minLimitErrorMessage = $('.min-limit-error-message');
            let maxLimitErrorMessage = $('.max-limit-error-message');
            let minLimitValue;
            let maxLimitValue;
            let hasError = false;

            function validateInput() {
                minLimitValue = Number(minLimit.val());
                maxLimitValue = Number(maxLimit.val());

                if (minLimitValue && maxLimitValue && minLimitValue >= maxLimitValue) {
                    minLimitErrorMessage.text('Minimum amount should be less than maximum amount');
                    maxLimitErrorMessage.empty();
                    hasError = true;
                    return;
                }
                hasError = false;
                emptyErrorMessage();
            }

            minLimit.on('input', validateInput);
            maxLimit.on('input', validateInput);

            function emptyErrorMessage() {
                minLimitErrorMessage.empty();
                maxLimitErrorMessage.empty();
            }

            $('form').on('submit', function(e) {
                validateInput();
                if (hasError) {
                    e.preventDefault();
                }
            });

        })(jQuery)

    </script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\gateways\manual\edit.blade.php ENDPATH**/ ?>