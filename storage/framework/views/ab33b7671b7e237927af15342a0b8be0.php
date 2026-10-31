

<?php $__currentLoopData = $formData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="form-group">
        <div class="justify-content-between d-flex flex-wrap">
            <label class="form-label <?php echo e($data->is_required == 'required' ? 'required' : null); ?>"><?php echo e(__($data->name)); ?></label>
            <?php if($data->type == 'file'): ?>
                <?php $__currentLoopData = $userData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                    <?php if($data->name == $file->name && $file->type == 'file' && $file->value): ?>
                        <a href="<?php echo e(route('user.withdraw.download.attachment', encrypt($file->value))); ?>"><?php echo app('translator')->get('Download attachment'); ?></a> 
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
        <?php if($data->type == 'text'): ?>
            <input type="text"
                class="form-control form--control"
                name="<?php echo e($data->label); ?>"
                <?php if($data->is_required == 'required'): ?> required <?php endif; ?>

                <?php $__currentLoopData = $userData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                    <?php if($data->name == $text->name && $text->type == 'text'): ?>
                        value="<?php echo e(old($data->label) ?? $text->value); ?>"
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            >
        <?php elseif($data->type == 'textarea'): ?>
            <?php
                $textareaVal = null;
                foreach($userData as $textarea){ 
                    if($data->name == $textarea->name && $textarea->type == 'textarea'){ 
                        $textareaVal = old($data->label) ?? $textarea->value;
                    }
                }
            ?>
            <textarea
                class="form-control form--control"
                name="<?php echo e($data->label); ?>"
                <?php if($data->is_required == 'required'): ?> required <?php endif; ?>
            ><?php echo e($textareaVal); ?></textarea>
        <?php elseif($data->type == 'select'): ?>
            <select
                class="form-control form--control form-select"
                name="<?php echo e($data->label); ?>"
                <?php if($data->is_required == 'required'): ?> required <?php endif; ?>
            >
                <option value=""><?php echo app('translator')->get('Select One'); ?></option>
                <?php $__currentLoopData = $data->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($item); ?>"
                        <?php $__currentLoopData = $userData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $select): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($item == $select->value && $select->type == 'select'): ?>
                               <?php if(true): echo 'selected'; endif; ?>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    >
                        <?php echo e(__($item)); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        <?php elseif($data->type == 'checkbox'): ?>
            <?php $__currentLoopData = $data->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                <div class="form-check">
                    <input
                        class="form-check-input exclude"
                        name="<?php echo e($data->label); ?>[]"
                        type="checkbox"
                        value="<?php echo e($option); ?>"
                        id="<?php echo e($data->label); ?>_<?php echo e(titleToKey($option)); ?>"
                        <?php $__currentLoopData = $userData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $checkbox): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>  
                            <?php if(gettype($checkbox->value) == 'array'): ?>
                                <?php $__currentLoopData = $checkbox->value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $checkboxVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($option == $checkboxVal && $checkbox->type == 'checkbox'): ?>
                                        <?php if(true): echo 'checked'; endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    >
                    <label class="form-check-label" for="<?php echo e($data->label); ?>_<?php echo e(titleToKey($option)); ?>"><?php echo e($option); ?></label>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php elseif($data->type == 'radio'): ?>
            <?php $__currentLoopData = $data->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="form-check">
                    <input
                    class="form-check-input exclude"
                    name="<?php echo e($data->label); ?>"
                    type="radio"
                    value="<?php echo e($option); ?>"
                    id="<?php echo e($data->label); ?>_<?php echo e(titleToKey($option)); ?>"
                    <?php $__currentLoopData = $userData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $radio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                        <?php if($option == $radio->value && $radio->type == 'radio'): ?>
                            <?php if(true): echo 'checked'; endif; ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    >
                    <label class="form-check-label" for="<?php echo e($data->label); ?>_<?php echo e(titleToKey($option)); ?>"><?php echo e($option); ?></label>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php elseif($data->type == 'file'): ?>
            <input
                type="file"
                class="form-control form--control"
                name="<?php echo e($data->label); ?>"

                <?php if($data->type == 'file'): ?>
                    <?php $__currentLoopData = $userData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                        <?php if($data->name == $file->name && $file->type == 'file' && !$file->value && $data->is_required == 'required'): ?>
                            required
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

                accept="<?php $__currentLoopData = explode(',',$data->extensions); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ext): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> .<?php echo e($ext); ?>, <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>"
            >
            <pre class="text--base mt-1"><?php echo app('translator')->get('Supported mimes'); ?>: <?php echo e($data->extensions); ?></pre>
        <?php endif; ?>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\components\withdraw-form.blade.php ENDPATH**/ ?>