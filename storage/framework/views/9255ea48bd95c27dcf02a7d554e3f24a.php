
<?php $__env->startSection('panel'); ?>
    <?php $__env->startPush('topBar'); ?>
        <?php echo $__env->make('admin.gateways.top_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php $__env->stopPush(); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="<?php echo e(route('admin.gateway.automatic.update', $gateway->code)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <input type="hidden" name="alias" value="<?php echo e($gateway->alias); ?>">
                    <input type="hidden" name="description" value="<?php echo e($gateway->description); ?>">


                    <div class="card-body">
                        <div class="payment-method-item block-item">
                            <div class="row align-items-center gy-1">
                                <div class="col-lg-8 col-sm-6">
                                    <h3><?php echo e(__($gateway->name)); ?></h3>
                                </div>
                                <div class="col-lg-4 col-sm-6">
                                    <?php if(count($supportedCurrencies) > 0): ?>
                                        <div class="input-group d-flex flex-nowrap justify-content-sm-end">
                                            <select class="newCurrencyVal ">
                                                <option value=""><?php echo app('translator')->get('Select currency'); ?></option>
                                                <?php $__empty_1 = true; $__currentLoopData = $supportedCurrencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency => $symbol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <option value="<?php echo e($currency); ?>" data-symbol="<?php echo e($symbol); ?>"><?php echo e(__($currency)); ?> </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <option value=""><?php echo app('translator')->get('No available currency support'); ?></option>
                                                <?php endif; ?>

                                            </select>
                                            <button type="button" class="btn btn--primary input-group-text newCurrencyBtn" data-crypto="<?php echo e($gateway->crypto); ?>" data-name="<?php echo e($gateway->name); ?>"><?php echo app('translator')->get('Add new'); ?></button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="gateway-body">
                                <div class="gateway-thumb">
                                    <div class="thumb">
                                        <?php if (isset($component)) { $__componentOriginaldbcc027cdd3569f61821c56d10b77c01 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldbcc027cdd3569f61821c56d10b77c01 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.image-uploader','data' => ['image' => ''.e($gateway->image).'','class' => 'w-100','type' => 'gateway','required' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('image-uploader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => ''.e($gateway->image).'','class' => 'w-100','type' => 'gateway','required' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
                                    <?php if($gateway->code < 1000 && $gateway->extra): ?>
                                        <div class="payment-method-body mt-2">
                                            <h4 class="mb-3 payment-method-body-title"><?php echo app('translator')->get('Configurations'); ?></h4>
                                            <div class="row">
                                                <?php $__currentLoopData = $gateway->extra; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $param): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="form-group col-lg-6">
                                                        <label><?php echo e(__(@$param->title)); ?></label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" value="<?php echo e(route($param->value)); ?>" readonly>
                                                            <button type="button" class="copyInput input-group-text" title="<?php echo app('translator')->get('Copy'); ?>"><i class="fas fa-copy"></i></button>
                                                        </div>
                                                        <?php if($key == 'cron'): ?>
                                                            <small><i class="las la-info-circle"></i> <?php echo app('translator')->get('Set the URL to your server\'s cron job to validate the payment.'); ?></small>
                                                        <?php endif; ?>

                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="payment-method-body mt-2">
                                        <h4 class="mb-3 payment-method-body-title"><?php echo app('translator')->get('Global Setting for'); ?> <?php echo e(__($gateway->name)); ?></h4>
                                        <div class="row">
                                            <?php $__currentLoopData = $parameters->where('global', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $param): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="form-group col-xl-6 col-lg-12 col-md-6">
                                                    <label><?php echo e(__(@$param->title)); ?></label>
                                                    <input type="text" class="form-control" name="global[<?php echo e($key); ?>]" value="<?php echo e(@$param->value); ?>" required>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- payment-method-item start -->

                        <?php if(isset($gateway->currencies)): ?>
                            <?php $__currentLoopData = $gateway->currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gatewayCurrency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <input type="hidden" name="currency[<?php echo e($currencyIndex); ?>][symbol]" value="<?php echo e($gatewayCurrency->symbol); ?>">
                                <div class="payment-method-item block-item child--item">
                                    <div class="payment-method-header">
                                        <div class="content">
                                            <div class="d-flex justify-content-between">
                                                <h4 class="mb-3"><?php echo e(__($gateway->name)); ?> - <?php echo e(__($gatewayCurrency->currency)); ?></h4>
                                                <div class="remove-btn">
                                                    <button type="button" class="btn btn--danger confirmationBtn" data-question="<?php echo app('translator')->get('Are you sure to delete this gateway currency?'); ?>" data-action="<?php echo e(route('admin.gateway.automatic.remove', $gatewayCurrency->id)); ?>">
                                                        <i class="la la-trash-o me-2"></i><?php echo app('translator')->get('Remove'); ?>
                                                    </button>
                                                </div>


                                            </div>
                                            <div class="form-group payment-method-title-input">
                                                <input type="text" class="form-control" name="currency[<?php echo e($currencyIndex); ?>][name]" value="<?php echo e($gatewayCurrency->name); ?>" required>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="payment-method-body">
                                        <div class="row g-4">
                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="card border border--primary">
                                                    <h5 class="card-header bg--primary"><?php echo app('translator')->get('Range'); ?></h5>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Minimum Amount'); ?></label>
                                                            <div class="input-group">
                                                                <input type="number" step="any" class="form-control minAmount" name="currency[<?php echo e($currencyIndex); ?>][min_amount]" value="<?php echo e(getAmount($gatewayCurrency->min_amount)); ?>" required>
                                                                <div class="input-group-text"><?php echo e(__(gs('cur_text'))); ?></div>
                                                            </div>
                                                            <span class="min-amount-error-message text--danger"></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Maximum Amount'); ?></label>
                                                            <div class="input-group">
                                                                <input type="number" step="any" class="form-control maxAmount" name="currency[<?php echo e($currencyIndex); ?>][max_amount]" value="<?php echo e(getAmount($gatewayCurrency->max_amount)); ?>" required>
                                                                <div class="input-group-text"><?php echo e(__(gs('cur_text'))); ?></div>
                                                            </div>
                                                            <span class="max-amount-error-message text--danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="card border border--primary">
                                                    <h5 class="card-header bg--primary"><?php echo app('translator')->get('Charge'); ?></h5>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Fixed Charge'); ?></label>
                                                            <div class="input-group">
                                                                <input type="number" step="any" class="form-control" name="currency[<?php echo e($currencyIndex); ?>][fixed_charge]" value="<?php echo e(getAmount($gatewayCurrency->fixed_charge)); ?>" required>
                                                                <div class="input-group-text"><?php echo e(__(gs('cur_text'))); ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Percent Charge'); ?></label>
                                                            <div class="input-group">
                                                                <input type="number" step="any" class="form-control" name="currency[<?php echo e($currencyIndex); ?>][percent_charge]" value="<?php echo e(getAmount($gatewayCurrency->percent_charge)); ?>" required>
                                                                <div class="input-group-text">%</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="card border border--primary">
                                                    <h5 class="card-header bg--primary"><?php echo app('translator')->get('Currency'); ?></h5>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label><?php echo app('translator')->get('Currency'); ?></label>
                                                                    <input type="text" name="currency[<?php echo e($currencyIndex); ?>][currency]" class="form-control border-radius-5 " value="<?php echo e($gatewayCurrency->currency); ?>" readonly>
                                                                </div>

                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label><?php echo app('translator')->get('Symbol'); ?></label>
                                                                    <input type="text" name="currency[<?php echo e($currencyIndex); ?>][symbol]" class="form-control border-radius-5 symbl" value="<?php echo e($gatewayCurrency->symbol); ?>" data-crypto="<?php echo e($gateway->crypto); ?>" required>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Rate'); ?></label>
                                                            <div class="input-group">
                                                                <div class="input-group-text">1 <?php echo e(gs('cur_text')); ?> =</div>
                                                                <input type="number" step="any" class="form-control" name="currency[<?php echo e($currencyIndex); ?>][rate]" value="<?php echo e(getAmount($gatewayCurrency->rate)); ?>" required>
                                                                <div class="input-group-text"><span class="currency_symbol"><?php echo e(__($gatewayCurrency->baseSymbol())); ?></span></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <?php if($parameters->where('global', false)->count() != 0): ?>
                                                <?php
                                                    $globalParameters = json_decode($gatewayCurrency->gateway_parameter);
                                                ?>
                                                <div class="col-lg-12">
                                                    <div class="card border border--dark mt-4">
                                                        <h5 class="card-header bg--dark"><?php echo app('translator')->get('Configuration'); ?></h5>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <?php $__currentLoopData = $parameters->where('global', false); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $param): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label><?php echo e(__($param->title)); ?></label>
                                                                            <input type="text" class="form-control" name="currency[<?php echo e($currencyIndex); ?>][param][<?php echo e($key); ?>]" value="<?php echo e($globalParameters->$key); ?>" required>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php $currencyIndex++ ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>

                        <!-- payment-method-item end -->


                        <!-- **new payment-method-item start -->
                        <div class="payment-method-item child--item newMethodCurrency d-none">
                            <input disabled type="hidden" name="currency[<?php echo e($currencyIndex); ?>][symbol]" class="currencySymbol">
                            <div class="payment-method-header">
                                <div class="content">
                                    <div class="d-flex justify-content-between">
                                        <div class="form-group">
                                            <h4 class="mb-3" id="payment_currency_name"><?php echo app('translator')->get('Name'); ?></h4>
                                            <input disabled type="text" class="form-control" name="currency[<?php echo e($currencyIndex); ?>][name]" required>
                                        </div>
                                        <div class="remove-btn">
                                            <button type="button" class="btn btn-danger newCurrencyRemove">
                                                <i class="la la-trash-o me-2"></i><?php echo app('translator')->get('Remove'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="payment-method-body">
                                <div class="row g-4">
                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                        <div class="card border border--primary">
                                            <h5 class="card-header bg--primary"><?php echo app('translator')->get('Range'); ?></h5>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Minimum Amount'); ?></label>
                                                    <div class="input-group">
                                                        <div class="input-group-text"><?php echo e(__(gs('cur_text'))); ?></div>
                                                        <input disabled type="number" step="any" class="form-control minAmount" name="currency[<?php echo e($currencyIndex); ?>][min_amount]" required>
                                                    </div>
                                                    <span class="min-amount-error-message text--danger"></span>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Maximum Amount'); ?></label>
                                                    <div class="input-group">
                                                        <div class="input-group-text"><?php echo e(__(gs('cur_text'))); ?></div>
                                                        <input disabled type="number" step="any" class="form-control maxAmount" name="currency[<?php echo e($currencyIndex); ?>][max_amount]" required>
                                                    </div>
                                                    <span class="max-amount-error-message text--danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                        <div class="card border border--primary">
                                            <h5 class="card-header bg--primary"><?php echo app('translator')->get('Charge'); ?></h5>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Fixed Charge'); ?></label>
                                                    <div class="input-group">
                                                        <div class="input-group-text"><?php echo e(__(gs('cur_text'))); ?></div>
                                                        <input disabled type="number" step="any" class="form-control" name="currency[<?php echo e($currencyIndex); ?>][fixed_charge]" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Percent Charge'); ?></label>
                                                    <div class="input-group">
                                                        <div class="input-group-text">%</div>
                                                        <input disabled type="number" step="any" class="form-control" name="currency[<?php echo e($currencyIndex); ?>][percent_charge]" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                        <div class="card border border--primary">
                                            <h5 class="card-header bg--primary"><?php echo app('translator')->get('Currency'); ?></h5>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Currency'); ?></label>
                                                            <input disabled type="step" class="form-control currencyText border-radius-5" name="currency[<?php echo e($currencyIndex); ?>][currency]" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Symbol'); ?></label>
                                                            <input disabled type="text" name="currency[<?php echo e($currencyIndex); ?>][symbol]" class="form-control border-radius-5 symbl" ata-crypto="<?php echo e($gateway->crypto); ?>" disabled>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Rate'); ?></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            1 <?php echo e(__(gs('cur_text'))); ?> =
                                                        </span>
                                                        <input disabled type="number" step="any" class="form-control" name="currency[<?php echo e($currencyIndex); ?>][rate]" required>
                                                        <div class="input-group-text"><span class="currency_symbol"></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if($parameters->where('global', false)->count() != 0): ?>
                                        <div class="col-lg-12">
                                            <div class="card border border--dark mt-4">
                                                <h5 class="card-header bg--dark"><?php echo app('translator')->get('Configuration'); ?></h5>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <?php $__currentLoopData = $parameters->where('global', false); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $param): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label><?php echo e(__($param->title)); ?></label>
                                                                    <input disabled type="text" class="form-control" name="currency[<?php echo e($currencyIndex); ?>][param][<?php echo e($key); ?>]" required>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                        <!-- **new payment-method-item end -->
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="w-full sm:w-auto h-16 px-12 rounded-[2rem] orange-gradient-primary text-white font-black uppercase tracking-[0.2em] text-[12px] hover:scale-105 transition-all shadow-2xl active:scale-95 ">
                            <?php echo app('translator')->get('Submit'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php if (isset($component)) { $__componentOriginalbd5922df145d522b37bf664b524be380 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd5922df145d522b37bf664b524be380 = $attributes; } ?>
<?php $component = App\View\Components\ConfirmationModal::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirmation-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\ConfirmationModal::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd5922df145d522b37bf664b524be380)): ?>
<?php $attributes = $__attributesOriginalbd5922df145d522b37bf664b524be380; ?>
<?php unset($__attributesOriginalbd5922df145d522b37bf664b524be380); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd5922df145d522b37bf664b524be380)): ?>
<?php $component = $__componentOriginalbd5922df145d522b37bf664b524be380; ?>
<?php unset($__componentOriginalbd5922df145d522b37bf664b524be380); ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>





<?php $__env->startPush('breadcrumb-plugins'); ?>
    <?php if (isset($component)) { $__componentOriginal3b9bf6c313f6db4d5c9389e5666c89a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b9bf6c313f6db4d5c9389e5666c89a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.back','data' => ['route' => ''.e(route('admin.gateway.automatic.index')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('back'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => ''.e(route('admin.gateway.automatic.index')).'']); ?>
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

            $('.newCurrencyBtn').on('click', function() {
                var form = $('.newMethodCurrency');
                var getCurrencySelected = $('.newCurrencyVal').find(':selected').val();
                var currency = $(this).data('crypto') == 1 ? 'USD' : `${getCurrencySelected}`;
                if (!getCurrencySelected) return;
                form.find('input').removeAttr('disabled');
                var symbol = $('.newCurrencyVal').find(':selected').data('symbol');
                form.find('.currencyText').val(getCurrencySelected);
                form.find('.currency_symbol').text(currency);
                $('#payment_currency_name').text(`${$(this).data('name')} - ${getCurrencySelected}`);
                form.removeClass('d-none');
                $('html, body').animate({
                    scrollTop: $('html, body').height()
                }, 'slow');

                $('.newCurrencyRemove').on('click', function() {
                    form.find('input').val('');
                    form.remove();
                });
            });

            $('.symbl').on('input', function() {
                var curText = $(this).data('crypto') == 1 ? 'USD' : $(this).val();
                $(this).parents('.payment-method-body').find('.currency_symbol').text(curText);
            });

            $('.copyInput').on('click', function(e) {
                var copybtn = $(this);
                var input = copybtn.closest('.input-group').find('input');
                if (input && input.select) {
                    input.select();
                    try {
                        document.execCommand('SelectAll')
                        document.execCommand('Copy', false, null);
                        input.blur();
                        notify('success', `Copied: ${copybtn.closest('.input-group').find('input').val()}`);
                    } catch (err) {
                        window.adminSwal({title:'Copy',text:'Please press Ctrl/Cmd + C to copy',icon:'info'});
                    }
                }
            });


            let minAmountValue;
            let maxAmountValue;
            let hasError = false;

            function validateInput() {
                let input = $(this);
                let container = input.closest('.card-body');
                let minAmount = container.find('.minAmount');
                let maxAmount = container.find('.maxAmount');
                let minAmountErrorMessage = container.find('.min-amount-error-message');
                let maxAmountErrorMessage = container.find('.max-amount-error-message');

                minAmountValue = Number(minAmount.val());
                maxAmountValue = Number(maxAmount.val());

                if (!minAmountValue) {
                    minAmountErrorMessage.text('<?php echo app('translator')->get('Minimum amount field is required'); ?>');
                    maxAmountErrorMessage.empty();
                    return;
                }

                if (!maxAmountValue) {
                    maxAmountErrorMessage.text('<?php echo app('translator')->get('Maximum amount field is required'); ?>');
                    minAmountErrorMessage.empty();
                    return;
                }

                if (minAmountValue >= maxAmountValue) {
                    minAmountErrorMessage.text('<?php echo app('translator')->get('Minimum amount should be less than maximum amount'); ?>');
                    maxAmountErrorMessage.empty();
                    hasError = true;
                    return;
                }
                minAmountErrorMessage.empty();
                maxAmountErrorMessage.empty();
                hasError = false;
            }


            $(document).on('input', '.minAmount, .maxAmount', validateInput);

            $('form').on('submit', function(e) {
                validateInput();
                if (hasError) {
                    e.preventDefault();
                }
            });

        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\gateways\automatic\edit.blade.php ENDPATH**/ ?>