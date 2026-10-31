<div class="row">
    <?php $__currentLoopData = $formData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-<?php echo e(@$data->width ?? '12'); ?>">
            <div class="form-group">
                <label class="form--label"><?php echo e(__($data->name)); ?> <?php if(@$data->instruction): ?>
                        <span data-bs-toggle="tooltip" data-bs-title="<?php echo e(__($data->instruction)); ?>"><i
                                class="fas fa-info-circle"></i></span>
                        <?php endif; ?> <?php if($data->is_required == 'required' && ($data->type == 'checkbox' || $data->type == 'radio')): ?>
                            <span class="text--danger">*</span>
                        <?php endif; ?> </label>
                <?php if($data->type == 'text'): ?>
                    <input type="text" class="form-control form--control" name="<?php echo e($data->label); ?>"
                        value="<?php echo e(old($data->label)); ?>" <?php if($data->is_required == 'required'): ?> required <?php endif; ?>>
                <?php elseif($data->type == 'url'): ?>
                    <input type="url" class="form-control form--control" name="<?php echo e($data->label); ?>"
                        value="<?php echo e(old($data->label)); ?>" <?php if($data->is_required == 'required'): ?> required <?php endif; ?>>
                <?php elseif($data->type == 'email'): ?>
                    <input type="email" class="form-control form--control" name="<?php echo e($data->label); ?>"
                        value="<?php echo e(old($data->label)); ?>" <?php if($data->is_required == 'required'): ?> required <?php endif; ?>>
                <?php elseif($data->type == 'datetime'): ?>
                    <input type="datetime-local" class="form-control form--control" name="<?php echo e($data->label); ?>"
                        value="<?php echo e(old($data->label)); ?>" <?php if($data->is_required == 'required'): ?> required <?php endif; ?>>
                <?php elseif($data->type == 'date'): ?>
                    <input type="date" class="form-control form--control" name="<?php echo e($data->label); ?>"
                        value="<?php echo e(old($data->label)); ?>" <?php if($data->is_required == 'required'): ?> required <?php endif; ?>>
                <?php elseif($data->type == 'time'): ?>
                    <input type="time" class="form-control form--control" name="<?php echo e($data->label); ?>"
                        value="<?php echo e(old($data->label)); ?>" <?php if($data->is_required == 'required'): ?> required <?php endif; ?>>
                <?php elseif($data->type == 'number'): ?>
                    <input type="number" class="form-control form--control" name="<?php echo e($data->label); ?>"
                        value="<?php echo e(old($data->label)); ?>" step="any"
                        <?php if($data->is_required == 'required'): ?> required <?php endif; ?>>
                <?php elseif($data->type == 'textarea'): ?>
                    <textarea class="form-control form--control" name="<?php echo e($data->label); ?>"
                        <?php if($data->is_required == 'required'): ?> required <?php endif; ?>><?php echo e(old($data->label)); ?></textarea>
                <?php elseif($data->type == 'select'): ?>
                    <select class="form-select form--control select2" data-minimum-results-for-search="-1"
                        name="<?php echo e($data->label); ?>" <?php if($data->is_required == 'required'): ?> required <?php endif; ?>>
                        <option value=""><?php echo app('translator')->get('Select One'); ?></option>
                        <?php $__currentLoopData = $data->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($item); ?>" <?php if($item == old($data->label)): echo 'selected'; endif; ?>><?php echo e(__($item)); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                <?php elseif($data->type == 'checkbox'): ?>
                    <div class="d-flex gap-3 flex-wrap">
                        <?php $__currentLoopData = $data->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form--check">
                                <input class="form-check-input" name="<?php echo e($data->label); ?>[]" type="checkbox"
                                    value="<?php echo e($option); ?>" id="<?php echo e($data->label); ?>_<?php echo e(titleToKey($option)); ?>">
                                <label class="form-check-label"
                                    for="<?php echo e($data->label); ?>_<?php echo e(titleToKey($option)); ?>"><?php echo e($option); ?></label>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="checkbox-required-error text--danger"></div>
                <?php elseif($data->type == 'radio'): ?>
                    <div class="d-flex gap-3 flex-wrap">
                        <?php $__currentLoopData = $data->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-check">
                                <input class="form-check-input" name="<?php echo e($data->label); ?>" type="radio"
                                    value="<?php echo e($option); ?>" id="<?php echo e($data->label); ?>_<?php echo e(titleToKey($option)); ?>"
                                    <?php if($option == old($data->label)): echo 'checked'; endif; ?>>
                                <label class="form-check-label"
                                    for="<?php echo e($data->label); ?>_<?php echo e(titleToKey($option)); ?>"><?php echo e($option); ?></label>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php elseif($data->type == 'file'): ?>
                    <input type="file" class="form-control form--control" name="<?php echo e($data->label); ?>"
                        <?php if($data->is_required == 'required'): ?> required <?php endif; ?>
                        accept="<?php $__currentLoopData = explode(',', $data->extensions); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ext): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> .<?php echo e($ext); ?>, <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>">
                    <p class="note-text"> <span class="icon"> <i class="fas fa-info-circle"></i> </span>
                        <?php echo app('translator')->get('Supported mimes'); ?>: <?php echo e($data->extensions); ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->startPush('script'); ?>
    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\components\viser-form.blade.php ENDPATH**/ ?>