
<?php $__env->startSection('panel'); ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="<?php echo e(route('admin.storage.save.digital.ocean', @$digitalOcean->id)); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <div class="row config">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required"><?php echo app('translator')->get('Name'); ?></label>
                                    <input class="form-control" type="text" name="name" value="<?php echo e(@$digitalOcean->name); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required"><?php echo app('translator')->get('Driver'); ?></label>
                                    <input class="form-control" type="text" name="digital_ocean[driver]" value="<?php echo e(@$digitalOcean->config?->driver); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required"> <?php echo app('translator')->get('Key'); ?></label>
                                    <input class="form-control" type="text" name="digital_ocean[key]" value="<?php echo e(@$digitalOcean->config?->key); ?>" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required"><?php echo app('translator')->get('Secret'); ?></label>
                                    <input class="form-control" type="text" name="digital_ocean[secret]" value="<?php echo e(@$digitalOcean->config?->secret); ?>"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required"><?php echo app('translator')->get('Region'); ?></label>
                                    <input class="form-control" type="text" name="digital_ocean[region]" value="<?php echo e(@$digitalOcean->config?->region); ?>"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required"><?php echo app('translator')->get('Bucket'); ?></label>
                                    <input class="form-control" type="text" name="digital_ocean[bucket]" value="<?php echo e(@$digitalOcean->config?->bucket); ?>"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required"><?php echo app('translator')->get('Endpoint'); ?></label>
                                    <input class="form-control" type="text" name="digital_ocean[endpoint]" value="<?php echo e(@$digitalOcean->config?->endpoint); ?>"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required"><?php echo app('translator')->get('Available Space'); ?></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="available_space" value="<?php echo e(@$ftp->available_space); ?>"
                                            required>
                                            <div class="input-group-text">
                                                  MB
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45"><?php echo app('translator')->get('Submit'); ?></button>
                    </div>
                </form>
            </div><!-- card end -->
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('breadcrumb-plugins'); ?>
    <?php if (isset($component)) { $__componentOriginal3b9bf6c313f6db4d5c9389e5666c89a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b9bf6c313f6db4d5c9389e5666c89a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.back','data' => ['route' => ''.e(route('admin.storage.index')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('back'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => ''.e(route('admin.storage.index')).'']); ?>
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

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\storage\digital_ocean\form.blade.php ENDPATH**/ ?>