

<?php $__env->startSection('panel'); ?>
    <div class="row mb-none-30">
        <div class="col-xl-12">
            <div class="card">
                <form action="<?php echo e(route('admin.users.notification.single.send', $user->id)); ?>" class="notificationForm" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="via" value="email">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <?php if(gs('en')): ?>
                                    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-3 col-sm-6">
                                        <div class="notification-via mb-4 active" data-method="email">
                                            <span class="active-badge"> <i class="las la-check"></i> </span>
                                            <div class="send-via-method">
                                                <i class="las la-envelope"></i>
                                                <h5><?php echo app('translator')->get('Send Via Email'); ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <?php if(gs('sn')): ?>
                                    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-3 col-sm-6">
                                        <div class="notification-via mb-4" data-method="sms">
                                            <span class="active-badge"> <i class="las la-check"></i> </span>
                                            <div class="send-via-method">
                                                <i class="las la-mobile-alt"></i>
                                                <h5><?php echo app('translator')->get('Send Via SMS'); ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <?php if(gs('pn')): ?>
                                    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-3 col-sm-12">
                                        <div class="notification-via mb-4" data-method="push">
                                            <span class="active-badge"> <i class="las la-check"></i> </span>
                                            <div class="send-via-method">
                                                <i class="las la-bell"></i>
                                                <h5><?php echo app('translator')->get('Send Via Firebase'); ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group col-md-12 subject-wrapper">
                                <label><?php echo app('translator')->get('Subject'); ?> </label>
                                <input type="text" class="form-control" placeholder="<?php echo app('translator')->get('Subject / Title'); ?>" name="subject">
                            </div>
                            <div class="form-group col-md-12 push-notification-file d-none">
                                <label><?php echo app('translator')->get('Image (optional)'); ?> </label>
                                <input type="file" class="form-control" accept=".png,.jpg,.jpeg" name="image">
                                <small class="mt-3 text-muted"> <?php echo app('translator')->get('Supported Files'); ?>:<b><?php echo app('translator')->get('.png, .jpg, .jpeg'); ?></b> </small>
                            </div>
                            <div class="form-group col-md-12">
                                <label><?php echo app('translator')->get('Message'); ?> </label>
                                <textarea name="message" rows="10" class="form-control nicEdit"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn w-100 h-45 btn--primary"><?php echo app('translator')->get('Submit'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict"

            $('.notification-via').on('click',function () {
                $('.notification-via').removeClass('active');
                $(this).addClass('active');
                $('[name=via]').val($(this).data('method'));
                if($(this).data('method') == 'email'){
                    var nicPrev = $('.nicEdit').prev('div');
                    nicPrev.prev('div').removeClass('d-none');
                    nicPrev.removeClass('d-none');
                    $('.nicEdit').css('display','none')

                }else{
                    var nicPrev = $('.nicEdit').prev('div');
                    nicPrev.prev('div').addClass('d-none');
                    nicPrev.addClass('d-none');
                    $('.nicEdit').css('display','block')
                    $('.nicEdit').val("")
                }

                if($(this).data('method') == 'push'){
                    $('.push-notification-file').removeClass('d-none');
                }else{
                    $('.push-notification-file').addClass('d-none');
                    $('.push-notification-file [type=file]').val('');
                }

                if($(this).data('method') == 'push' || $(this).data('method') == 'email'){
                    $('.subject-wrapper').removeClass('d-none');
                }else{
                    $('.subject-wrapper').addClass('d-none')
                }
                $('.subject-wrapper').find('input').val('');
            });


            $('.notificationForm').on('submit',function (e) {
                if ($('.notification-via.active').data('method') != 'email') {
                    e.preventDefault();
                    var val = $('.nicEdit').val();
                    setTimeout(() => {
                        $('.nicEdit').val(val);
                        document.getElementsByClassName('notificationForm')[0].submit();
                    }, 1);
                }

            });

        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\users\notification_single.blade.php ENDPATH**/ ?>