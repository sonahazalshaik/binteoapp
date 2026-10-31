
<?php $__env->startPush('topBar'); ?>
  <?php echo $__env->make('admin.notification.top_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('panel'); ?>
    <?php echo $__env->make('admin.notification.template.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('admin.notification.template.shortcodes', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


    <form action="<?php echo e(route('admin.setting.notification.template.update',['email',$template->id])); ?>" method="post">
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header bg--primary">
                        <h5 class="card-title text-white"><?php echo app('translator')->get('Email Template'); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Subject'); ?></label>
                                    <input type="text" class="form-control" placeholder="<?php echo app('translator')->get('Email subject'); ?>" name="subject" value="<?php echo e($template->subject); ?>" required/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Status'); ?> <span class="text--danger">*</span></label>
                                    <input type="checkbox" data-height="46px" data-width="100%" data-onstyle="-success"
                                       data-offstyle="-danger" data-bs-toggle="toggle" data-on="<?php echo app('translator')->get('Send Email'); ?>"
                                       data-off="<?php echo app('translator')->get("Don't Send"); ?>" name="email_status"
                                       <?php if($template->email_status): ?> checked <?php endif; ?>>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Email Sent From - Name'); ?></label>
                                    <input type="text" class="form-control" name="email_sent_from_name" value="<?php echo e($template->email_sent_from_name); ?>">
                                    <small class="text-primary"><i><i class="las la-info-circle"></i> <?php echo app('translator')->get('Make the field empty if you want to use global template\'s name as email sent from name.'); ?></i></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Email Sent From - Email'); ?></label>
                                    <input type="text" class="form-control" name="email_sent_from_address" value="<?php echo e($template->email_sent_from_address); ?>">
                                    <small class="text-primary"><i><i class="las la-info-circle"></i> <?php echo app('translator')->get('Make the field empty if you want to use global template\'s email as email sent from.'); ?></i></small>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Message'); ?> <span class="text--danger">*</span></label>
                                    <textarea name="email_body" rows="10" class="form-control nicEdit" placeholder="<?php echo app('translator')->get('Your message using short-codes'); ?>"><?php echo e($template->email_body); ?></textarea>
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

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\notification\template\email.blade.php ENDPATH**/ ?>