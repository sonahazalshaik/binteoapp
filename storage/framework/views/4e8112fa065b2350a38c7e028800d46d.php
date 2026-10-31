
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
                    <form action="<?php echo e(route('admin.setting.notification.global.email.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Email Sent From - Name'); ?> </label>
                                    <input type="text" class="form-control " placeholder="<?php echo app('translator')->get('Email address'); ?>" name="email_from_name" value="<?php echo e(gs('email_from_name')); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Email Sent From - Email'); ?> </label>
                                    <input type="text" class="form-control " placeholder="<?php echo app('translator')->get('Email address'); ?>" name="email_from" value="<?php echo e(gs('email_from')); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Email Body'); ?> </label>
                                    <textarea name="email_template" rows="10" class="form-control emailTemplateEditor" id="htmlInput" placeholder="<?php echo app('translator')->get('Your email template'); ?>"><?php echo e(gs('email_template')); ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="previewContainer">
                                    <label>&nbsp;</label>
                                    <iframe id="iframePreview"></iframe>
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
<?php $__env->startPush('style'); ?>
    <style>
        #iframePreview {
            width: 100%;
            height: 400px;
            border: none;
        }

        .emailTemplateEditor {
            height: 400px;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        var iframe = document.getElementById('iframePreview');
        $(".emailTemplateEditor").on('input', function() {
            var htmlContent = document.getElementById('htmlInput').value;
            iframe.src = 'data:text/html;charset=utf-8,' + encodeURIComponent(htmlContent);
        }).trigger('input');
    </script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\notification\global_email_template.blade.php ENDPATH**/ ?>