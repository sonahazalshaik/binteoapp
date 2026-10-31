
<?php $__env->startSection('panel'); ?>
    <div class="row mb-none-30">
        <form method="POST">
            <?php echo csrf_field(); ?>
            <div class="col-lg-12 col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group ">
                                    <label><?php echo app('translator')->get('Video Sell Charge'); ?></label> <span title="<?php echo app('translator')->get('Set the percentage to charge from video sales. Set to 0 for no charge.'); ?>"><i class="las la-info-circle"></i></span>
                                    <div class="input-group">
                                        <input class="form-control" name="video_sell_charge" type="number" step="any" value="<?php echo e(getAmount(gs('video_sell_charge'))); ?>" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group ">
                                    <label><?php echo app('translator')->get('Playlist Sell Charge'); ?></label> <span title="<?php echo app('translator')->get('Set the percentage to charge from playlists sales. Set to 0 for no charge.'); ?>"><i class="las la-info-circle"></i></span>
                                    <div class="input-group">
                                        <input class="form-control" name="playlist_sell_charge" type="number" step="any" value="<?php echo e(getAmount(gs('playlist_sell_charge'))); ?>" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group ">
                                    <label><?php echo app('translator')->get('Monthly Plan Sell Charge'); ?></label> <span title="<?php echo app('translator')->get('Set the percentage to charge from monthly subscription. Set to 0 for no charge.'); ?>"><i class="las la-info-circle"></i></span>
                                    <div class="input-group">
                                        <input class="form-control" name="plan_sell_charge" type="number" step="any" value="<?php echo e(getAmount(gs('plan_sell_charge'))); ?>" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-8">
                    <button type="submit" class="w-full h-12 rounded-xl bg-blue-600 text-white text-[11px] font-black uppercase tracking-[0.3em] hover:bg-blue-700 hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-blue-500/20 ">
                        <?php echo app('translator')->get('Synchronize Financial Protocols'); ?>
                    </button>
                </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('script-lib'); ?>
    <script src="<?php echo e(asset('assets/admin/js/spectrum.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('style-lib'); ?>
    <link href="<?php echo e(asset('assets/admin/css/spectrum.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";


            $('.colorPicker').spectrum({
                color: $(this).data('color'),
                change: function(color) {
                    $(this).parent().siblings('.colorCode').val(color.toHexString().replace(/^#?/, ''));
                }
            });

            $('.colorCode').on('input', function() {
                var clr = $(this).val();
                $(this).parents('.input-group').find('.colorPicker').spectrum({
                    color: clr,
                });
            });
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\setting\charge.blade.php ENDPATH**/ ?>