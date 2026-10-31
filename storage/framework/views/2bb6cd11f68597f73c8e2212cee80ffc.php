
<?php $__env->startSection('panel'); ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="<?php echo e(route('admin.storage.save.ftp', @$ftp->id)); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <div class="row config">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required"><?php echo app('translator')->get('Name'); ?></label>
                                    <input class="form-control" type="text" name="name" value="<?php echo e(@$ftp->name); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required"><?php echo app('translator')->get('FTP Hosting Root Access Path'); ?></label>
                                    <input class="form-control" type="text" name="ftp[host_domain]" placeholder="<?php echo app('translator')->get('https://yourdomain.com/foldername'); ?>"
                                           value="<?php echo e(@$ftp->config?->host_domain); ?>" required>
                                    <small class="text-danger"><?php echo app('translator')->get('https://yourdomain.com/foldername'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required"> <?php echo app('translator')->get('Host'); ?></label>
                                    <input class="form-control" type="text" name="ftp[host]" placeholder="<?php echo app('translator')->get('Host'); ?>"
                                           value="<?php echo e(@$ftp->config?->host); ?>" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required"><?php echo app('translator')->get('Username'); ?></label>
                                    <input class="form-control" type="text" name="ftp[username]" placeholder="<?php echo app('translator')->get('Username'); ?>"
                                           value="<?php echo e(@$ftp->config?->username); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required"><?php echo app('translator')->get('Password'); ?></label>
                                    <input class="form-control" type="text" name="ftp[password]" placeholder="<?php echo app('translator')->get('Password'); ?>"
                                           value="<?php echo e(@$ftp->config?->password); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required"><?php echo app('translator')->get('Port'); ?></label>
                                    <input class="form-control" type="text" name="ftp[port]" placeholder="<?php echo app('translator')->get('Port'); ?>"
                                           value="<?php echo e(@$ftp->config?->port); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required"><?php echo app('translator')->get('Upload Root Folder'); ?></label>
                                    <input class="form-control" type="text" name="ftp[root_path]" placeholder="<?php echo app('translator')->get('/html_public/something'); ?>" value="<?php echo e(@$ftp->config?->root_path); ?>" required>
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

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\storage\ftp\form.blade.php ENDPATH**/ ?>