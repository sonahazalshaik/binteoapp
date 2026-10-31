<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['form' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['form' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="form-field__wrapper">
    <div class="addedField simple_with_drop">
        <?php if($form): ?>
            <?php $__currentLoopData = $form->form_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $formData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="form-field-wrapper" id="<?php echo e($loop->index); ?>">
                    <input type="hidden" name="form_generator[is_required][]" value="<?php echo e($formData->is_required); ?>">
                    <input type="hidden" name="form_generator[extensions][]" value="<?php echo e($formData->extensions); ?>">
                    <input type="hidden" name="form_generator[options][]" value="<?php echo e(implode(',', $formData->options)); ?>">
                    <input type="hidden" name="form_generator[form_width][]" value="<?php echo e(@$formData->width); ?>">
                    <input type="hidden" name="form_generator[form_label][]" class="form-control" value="<?php echo e($formData->name); ?>">
                    <input type="hidden" name="form_generator[instruction][]" class="form-control" value="<?php echo e(@$formData->instruction); ?>">
                    <input type="hidden" name="form_generator[form_type][]" class="form-control" value="<?php echo e($formData->type); ?>">
                    <?php
                        $jsonData = json_encode([
                            'type' => $formData->type,
                            'is_required' => $formData->is_required,
                            'instruction' => @$formData->instruction,
                            'label' => $formData->name,
                            'extensions' => explode(',', $formData->extensions) ?? 'null',
                            'options' => $formData->options,
                            'width' => @$formData->width,
                            'old_id' => '',
                        ]);
                    ?>

                    <div class="form-field">
                        <div class="form-field__item d-flex align-items-center gap-2">
                            <div class="me-1">
                                <i class="las la-braille"></i>
                            </div>
                            <div>
                                <p class="title"><?php echo app('translator')->get('Name'); ?></p>
                                <p class="value"><?php echo e(__(@$formData->name)); ?></p>
                            </div>
                        </div>
                        <div class="form-field__item">
                            <p class="title"><?php echo app('translator')->get('Type'); ?></p>
                            <p class="value"><?php echo e(__(ucfirst($formData->type))); ?></p>
                        </div>
                        <div class="form-field__item">
                            <p class="title"><?php echo app('translator')->get('Width'); ?></p>
                            <p class="value">
                                <?php if(@$formData->width == '12'): ?>
                                    <?php echo app('translator')->get('100%'); ?>
                                <?php elseif(@$formData->width == '6'): ?>
                                    <?php echo app('translator')->get('50%'); ?>
                                <?php elseif(@$formData->width == '4'): ?>
                                    <?php echo app('translator')->get('33%'); ?>
                                <?php elseif(@$formData->width == '3'): ?>
                                    <?php echo app('translator')->get('25%'); ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="form-field__item">
                            <p class="value">
                                <?php if($formData->is_required == 'required'): ?>
                                    <span class="badge badge--success"><?php echo app('translator')->get('Required'); ?></span>
                                <?php else: ?>
                                    <span class="badge badge--dark"><?php echo app('translator')->get('Optional'); ?></span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="form-field__item">
                            <button type="button" class="btn btn--primary btn-sm editFormData" data-form_item="<?php echo e($jsonData); ?>" data-update_id="<?php echo e($loop->index); ?>"><i class="las la-pen me-0"></i></button>
                            <button type="button" class="btn btn--danger btn-sm removeFormData"><i class="las la-times me-0"></i></button>
                        </div>
                    </div>

                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('style'); ?>
    <style>
        .form-field {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #cdcdcd;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            cursor: grab;
            background: #fff;
        }

        .form-field .title {
            font-size: 15px;
            font-weight: 600;
        }

        .form-field .form-field__item {
            min-width: 170px;
            text-align: left;
        }

        .addedField.simple_with_drop.ui-sortable {
            min-width: 900px;
        }

        .form-field .form-field__item:last-child {
            text-align: right;
        }

        .submitRequired{
            cursor: unset;
        }
        .form-field__wrapper{
            overflow-x: auto;
            margin-bottom: 10px;
        }

    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script-lib'); ?>
    <script src="<?php echo e(asset('assets/admin/js/jquery-ui.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script'); ?>
    <script>
        "use strict"
        var formGenerator = new FormGenerator();
        <?php if($form): ?>
            formGenerator.totalField = <?php echo e($form ? count((array) $form->form_data) : 0); ?>

        <?php endif; ?>
        $(".simple_with_drop").sortable({
            stop: function(event, ui) {
                var start = ui.item.data('start');
                var end = ui.item.index();
                if (start !== end) {
                    $('.submitRequired').removeClass('d-none');
                }
            },
            start: function(event, ui) {
                ui.item.data('start', ui.item.index());
            }
        });
    </script>

    <script src="<?php echo e(asset('assets/global/js/form_actions.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\components\generated-form.blade.php ENDPATH**/ ?>