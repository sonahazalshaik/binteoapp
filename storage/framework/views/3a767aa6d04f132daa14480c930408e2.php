
<?php $__env->startSection('panel'); ?>
<?php $__env->startPush('topBar'); ?>
  <?php echo $__env->make('admin.notification.top_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>
<div class="row">
    <?php echo $__env->make('admin.notification.global_template_nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('admin.notification.global_shortcodes', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="col-md-12">
        <div class="card mt-5">
            <div class="card-body">
                <form action="<?php echo e(route('admin.setting.notification.global.sms.update')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('SMS Sent From'); ?> </label>
                                <input class="form-control" placeholder="<?php echo app('translator')->get('SMS Sent From'); ?>" name="sms_from" value="<?php echo e(gs('sms_from')); ?>" required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('SMS Body'); ?> </label>
                                <textarea class="form-control" rows="4" placeholder="<?php echo app('translator')->get('SMS Body'); ?>" name="sms_template" required><?php echo e(gs('sms_template')); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8">
                        <button type="submit" class="w-full h-12 rounded-xl bg-blue-600 text-white text-[11px] font-black uppercase tracking-[0.3em] hover:bg-blue-700 hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-blue-500/20 ">
                            <?php echo app('translator')->get('Submit'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div><!-- card end -->
    </div>

</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\notification\global_sms_template.blade.php ENDPATH**/ ?>