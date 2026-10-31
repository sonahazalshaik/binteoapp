
<?php $__env->startSection('panel'); ?>
    <div class="row mb-none-30">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h6><?php echo app('translator')->get('Insert Sitemap XML'); ?></h6>
                </div>
                <form method="post">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <div class="form-group custom-css">
                            <textarea class="form-control sitemapEditor" rows="10" name="sitemap"><?php echo e($fileContent); ?></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45"><?php echo app('translator')->get('Submit'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('style'); ?>
<style>
    .CodeMirror{
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
        line-height: 1.3;
        height: 500px;
    }
    .CodeMirror-linenumbers{
      padding: 0 8px;
    }
    .custom-css p, .custom-css li, .custom-css span{
      color: white;
    }
  </style>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('style-lib'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/admin/css/codemirror.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/admin/css/monokai.min.css')); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script-lib'); ?>
    <script src="<?php echo e(asset('assets/admin/js/codemirror.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/admin/js/xml.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/admin/js/sublime.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script'); ?>
<script>
    "use strict";
    var editor = CodeMirror.fromTextArea(document.getElementsByClassName("sitemapEditor")[0], {
        lineNumbers: true,
        mode: "text/xml",
        theme: "monokai",
        keyMap: "sublime",
        showCursorWhenSelecting: true,
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\setting\sitemap.blade.php ENDPATH**/ ?>