
<?php $__env->startSection('panel'); ?>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $updates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $update): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="update-card">
                        <h5><?php echo app('translator')->get('Version'); ?> <?php echo e($update->version); ?> | <?php echo app('translator')->get('Uploaded'); ?>: <?php echo e($update->created_at->format('Y-m-d')); ?></h5>
                        <hr>
                        <ul>
                            <?php $__currentLoopData = $update->update_log; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e(__($log)); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty-list-area text-center">
                        <div class="empty-list-icon">
                            <img src="<?php echo e(getImage('assets/images/empty_list.png')); ?>" alt="empty">
                        </div>
                        <h5><?php echo app('translator')->get('No update log found yet!'); ?></h5>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('style'); ?>
    <style>
        .empty-list-area .empty-list-icon img{
            width: 130px;
            margin-bottom: 20px
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\system\update_log.blade.php ENDPATH**/ ?>