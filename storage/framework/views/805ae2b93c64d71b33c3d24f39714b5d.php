
<?php $__env->startPush('topBar'); ?>
  <?php echo $__env->make('admin.notification.top_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('panel'); ?>
    <?php echo $__env->make('admin.notification.template.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('admin.notification.template.shortcodes', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


    <form action="<?php echo e(route('admin.setting.notification.template.update',['sms',$template->id])); ?>" method="post">
        <?php echo csrf_field(); ?>
        <div class="row">

            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header bg--primary">
                        <h5 class="card-title text-white"><?php echo app('translator')->get('SMS Template'); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('SMS Sent From'); ?></label>
                                    <input type="text" class="form-control" name="sms_sent_from" value="<?php echo e($template->sms_sent_from); ?>">
                                    <small class="text-primary"><i><i class="las la-info-circle"></i> <?php echo app('translator')->get('Make the field empty if you want to use global template\'s name as sms sent from name.'); ?></i></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Status'); ?> <span class="text--danger">*</span></label>
                                    <input type="checkbox" data-height="46px" data-width="100%" data-onstyle="-success"
                                       data-offstyle="-danger" data-bs-toggle="toggle" data-on="<?php echo app('translator')->get('Send SMS'); ?>"
                                       data-off="<?php echo app('translator')->get("Don't Send"); ?>" name="sms_status"
                                       <?php if($template->sms_status): ?> checked <?php endif; ?>>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Message'); ?></label>
                                    <textarea name="sms_body" rows="10" class="form-control" placeholder="<?php echo app('translator')->get('Your message using short-codes'); ?>" required><?php echo e($template->sms_body); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn--primary w-100 h-45"><?php echo app('translator')->get('Submit'); ?></button>
                    </div>
                </div>
            </div>

        </div>
    </form>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('breadcrumb-plugins'); ?>
    <?php if (isset($component)) { $__componentOriginal3b9bf6c313f6db4d5c9389e5666c89a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b9bf6c313f6db4d5c9389e5666c89a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.back','data' => ['route' => ''.e(route('admin.setting.notification.templates')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('back'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => ''.e(route('admin.setting.notification.templates')).'']); ?>
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

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\notification\template\sms.blade.php ENDPATH**/ ?>