
<?php $__env->startSection('panel'); ?>
    <?php
        $sessionData = session('SEND_NOTIFICATION') ?? [];
        $viaName     = $sessionData['via'] ?? 'email';
        $viaText     = @$sessionData['via'] == 'push' ? 'Push notification ' : ucfirst($viaName);
    ?>

    <?php if(empty(!$sessionData)): ?>
        <div class="notification-data-and-loader">
            <div class="row  mb-4 justify-content-center">
                <div class="col-sm-7">
                    <div class="row gy-4 justify-content-center">
                        <div class="col-sm-6">
                            <?php if (isset($component)) { $__componentOriginal433d6a5be5b58ac8aa6a74031c6196f9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal433d6a5be5b58ac8aa6a74031c6196f9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.widget','data' => ['link' => 'javascript:void(0)','style' => '6','icon' => 'fas fa-list','title' => $viaText . ' should be sent','value' => ''.e(@$sessionData['total_user']).'','bg' => 'primary','viewMoreIcon' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['link' => 'javascript:void(0)','style' => '6','icon' => 'fas fa-list','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($viaText . ' should be sent'),'value' => ''.e(@$sessionData['total_user']).'','bg' => 'primary','viewMoreIcon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal433d6a5be5b58ac8aa6a74031c6196f9)): ?>
<?php $attributes = $__attributesOriginal433d6a5be5b58ac8aa6a74031c6196f9; ?>
<?php unset($__attributesOriginal433d6a5be5b58ac8aa6a74031c6196f9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal433d6a5be5b58ac8aa6a74031c6196f9)): ?>
<?php $component = $__componentOriginal433d6a5be5b58ac8aa6a74031c6196f9; ?>
<?php unset($__componentOriginal433d6a5be5b58ac8aa6a74031c6196f9); ?>
<?php endif; ?>
                        </div>
                        <div class="col-sm-6">
                            <?php if (isset($component)) { $__componentOriginal433d6a5be5b58ac8aa6a74031c6196f9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal433d6a5be5b58ac8aa6a74031c6196f9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.widget','data' => ['link' => 'javascript:void(0)','style' => '6','icon' => 'fa-solid fa-envelope-circle-check','title' => $viaText . ' has been sent','value' => ''.e(@$sessionData['total_sent']).'','bg' => 'success','viewMoreIcon' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['link' => 'javascript:void(0)','style' => '6','icon' => 'fa-solid fa-envelope-circle-check','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($viaText . ' has been sent'),'value' => ''.e(@$sessionData['total_sent']).'','bg' => 'success','viewMoreIcon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal433d6a5be5b58ac8aa6a74031c6196f9)): ?>
<?php $attributes = $__attributesOriginal433d6a5be5b58ac8aa6a74031c6196f9; ?>
<?php unset($__attributesOriginal433d6a5be5b58ac8aa6a74031c6196f9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal433d6a5be5b58ac8aa6a74031c6196f9)): ?>
<?php $component = $__componentOriginal433d6a5be5b58ac8aa6a74031c6196f9; ?>
<?php unset($__componentOriginal433d6a5be5b58ac8aa6a74031c6196f9); ?>
<?php endif; ?>
                        </div>
                        <div class="col-sm-6">
                            <?php if (isset($component)) { $__componentOriginal433d6a5be5b58ac8aa6a74031c6196f9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal433d6a5be5b58ac8aa6a74031c6196f9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.widget','data' => ['link' => 'javascript:void(0)','style' => '6','icon' => 'fa-solid fa-paper-plane','title' => $viaText . ' has yet to be sent','viewMoreIcon' => false,'value' => ''.e(@$sessionData['total_user'] - @$sessionData['total_sent']).'','bg' => 'warning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['link' => 'javascript:void(0)','style' => '6','icon' => 'fa-solid fa-paper-plane','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($viaText . ' has yet to be sent'),'viewMoreIcon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'value' => ''.e(@$sessionData['total_user'] - @$sessionData['total_sent']).'','bg' => 'warning']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal433d6a5be5b58ac8aa6a74031c6196f9)): ?>
<?php $attributes = $__attributesOriginal433d6a5be5b58ac8aa6a74031c6196f9; ?>
<?php unset($__attributesOriginal433d6a5be5b58ac8aa6a74031c6196f9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal433d6a5be5b58ac8aa6a74031c6196f9)): ?>
<?php $component = $__componentOriginal433d6a5be5b58ac8aa6a74031c6196f9; ?>
<?php unset($__componentOriginal433d6a5be5b58ac8aa6a74031c6196f9); ?>
<?php endif; ?>
                        </div>
                        <div class="col-sm-6">
                            <?php if (isset($component)) { $__componentOriginal433d6a5be5b58ac8aa6a74031c6196f9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal433d6a5be5b58ac8aa6a74031c6196f9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.widget','data' => ['link' => 'javascript:void(0)','style' => '6','icon' => 'fas fa-envelope','title' => $viaText . ' per batch','value' => ''.e(@$sessionData['batch']).'','bg' => 'primary','viewMoreIcon' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['link' => 'javascript:void(0)','style' => '6','icon' => 'fas fa-envelope','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($viaText . ' per batch'),'value' => ''.e(@$sessionData['batch']).'','bg' => 'primary','viewMoreIcon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal433d6a5be5b58ac8aa6a74031c6196f9)): ?>
<?php $attributes = $__attributesOriginal433d6a5be5b58ac8aa6a74031c6196f9; ?>
<?php unset($__attributesOriginal433d6a5be5b58ac8aa6a74031c6196f9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal433d6a5be5b58ac8aa6a74031c6196f9)): ?>
<?php $component = $__componentOriginal433d6a5be5b58ac8aa6a74031c6196f9; ?>
<?php unset($__componentOriginal433d6a5be5b58ac8aa6a74031c6196f9); ?>
<?php endif; ?>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body p-5 text-center">
                                    <div class="coaling-loader flex-column d-flex justify-content-center">
                                        <div class="countdown">
                                            <div class="coaling-time">
                                                <span class="coaling-time-count"><?php echo e(@$sessionData['cooling_time']); ?></span>
                                            </div>
                                            <div class="svg-count">
                                                <svg viewBox="0 0 100 100">
                                                    <circle r="45" cx="50" cy="50" id="animate-circle"></circle>
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="mt-2">
                                            <?php echo app('translator')->get("$viaText will be sent again with a"); ?> <span class="coaling-time-count"></span>
                                            <?php echo app('translator')->get(' second delay. Avoid closing or refreshing the browser.'); ?>
                                        </p>
                                        <p class="text--primary">
                                            <?php echo app('translator')->get(' ' . @$sessionData['total_sent'] . ' out of ' . @$sessionData['total_user'] . ' ' . $viaName . ' were successfully transmitted'); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row <?php if(empty(!$sessionData)): ?> d-none <?php endif; ?>">
        <div class="col-xl-12">
            <div class="card">
                <form class="notify-form" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="via" value="<?php echo e($viaName); ?>">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <?php if(gs('en')): ?>
                                        <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-3 col-sm-6">
                                            <div class="notification-via mb-4  <?php if($viaName == 'email'): ?> active <?php endif; ?> " data-method="email">
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
                                            <div class="notification-via mb-4 <?php if($viaName == 'sms'): ?> active <?php endif; ?> " data-method="sms">
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
                                            <div class="notification-via mb-4 <?php if($viaName == 'push'): ?> active <?php endif; ?>" data-method="push">
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Being Sent To'); ?> </label>
                                    <select class="form-control select2" name="being_sent_to" required data-minimum-results-for-search="1">
                                        <?php $__currentLoopData = $notifyToUser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $toUser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key); ?>" <?php if(old('being_sent_to', @$sessionData['being_sent_to']) == $key): echo 'selected'; endif; ?>><?php echo e(__($toUser)); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <small class="text--info d-none userCountText"> <i class="las la-info-circle"></i> <strong
                                            class="userCount">0</strong> <?php echo app('translator')->get('active users found to send the notification'); ?></small>
                                </div>
                                <div class="input-append">
                                </div>
                            </div>
                            <div class="form-group col-md-12 subject-wrapper">
                                <label><?php echo app('translator')->get('Subject'); ?> <span class="text--danger">*</span> </label>
                                <input type="text" class="form-control" placeholder="<?php echo app('translator')->get('Subject / Title'); ?>" name="subject"
                                    value="<?php echo e(old('subject', @$sessionData['subject'])); ?>">
                            </div>
                            <div class="form-group col-md-12 push-notification-file d-none">
                                <label><?php echo app('translator')->get('Image (optional)'); ?> </label>
                                <input type="file" class="form-control" name="image" accept=".png,.jpg,.jpeg">
                                <small class="mt-3 text-muted"> <?php echo app('translator')->get('Supported Files'); ?>:<b><?php echo app('translator')->get('.png, .jpg, .jpeg'); ?></b> </small>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Message'); ?> <span class="text--danger">*</span> </label>
                                    <textarea class="form-control nicEdit" id="nicEdit" name="message" rows="10"><?php echo e(old('message', @$sessionData['message'])); ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4 start-from-col">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Start Form'); ?> </label>
                                            <input class="form-control" name="start" value="<?php echo e(old('start', @$sessionData['start'])); ?>"
                                                type="number" placeholder="<?php echo app('translator')->get('Start form user id. e.g. 1'); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 per-batch-col">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Per Batch'); ?> </label>
                                            <div class="input-group">
                                                <input class="form-control" name="batch" value="<?php echo e(old('batch', @$sessionData['batch'])); ?>"
                                                    type="number" placeholder="<?php echo app('translator')->get('How many user'); ?>" required>
                                                <span class="input-group-text">
                                                    <?php echo app('translator')->get('User'); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 cooling-period-col">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Cooling Period'); ?> </label>
                                            <div class="input-group">
                                                <input class="form-control" name="cooling_time"
                                                    value="<?php echo e(old('cooling_time', @$sessionData['batch'])); ?>" type="number"
                                                    placeholder="<?php echo app('translator')->get('Waiting time'); ?>" required>
                                                <span class="input-group-text">
                                                    <?php echo app('translator')->get('Seconds'); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn w-100 h-45 btn--primary me-2" type="submit"><?php echo app('translator')->get('Submit'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('script'); ?>
    <script>
        let formSubmit = false;

        (function($) {
            "use strict"


            $('select[name=being_sent_to]').on('change', function(e) {
                let methodName = $(this).val();
                if (!methodName) return;
                getUserCount(methodName);
                methodName = methodName.toUpperCase();

                if (methodName == 'SELECTEDUSERS') {
                    $('.input-append').html(`
                    <div class="form-group" id="user_list_wrapper">
                        <label class="required"><?php echo app('translator')->get('Select User'); ?></label>
                        <select name="user[]"  class="form-control" id="user_list" required multiple >
                            <option disabled><?php echo app('translator')->get('Select One'); ?></option>
                        </select>
                    </div>
                    `);
                    fetchUserList();
                    return;
                }
                if (methodName == 'TOPDEPOSITEDUSERS') {
                    $('.input-append').html(`
                    <div class="form-group">
                        <label class="required"><?php echo app('translator')->get('Number Of Top Deposited User'); ?></label>
                        <input class="form-control" type="number" name="number_of_top_deposited_user" >
                    </div>
                    `);
                    return;
                }

                if (methodName == 'NOTLOGINUSERS') {
                    $('.input-append').html(`
                    <div class="form-group">
                        <label class="required"><?php echo app('translator')->get('Number Of Days'); ?></label>
                        <div class="input-group">
                            <input class="form-control" value="<?php echo e(old('number_of_days', @$sessionData['number_of_days'])); ?>" type="number" name="number_of_days" >
                            <span class="input-group-text"><?php echo app('translator')->get('Days'); ?></span>
                        </div>
                    </div>
                    `);
                    return;
                }

                $('.input-append').empty();

            }).change();

            function fetchUserList() {

                $('.row #user_list').select2({
                    ajax: {
                        url: "<?php echo e(route('admin.users.list')); ?>",
                        type: "get",
                        dataType: 'json',
                        delay: 1000,
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page,
                            };
                        },
                        processResults: function(response, params) {
                            params.page = params.page || 1;
                            let data = response.users.data;
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.email,
                                        id: item.id
                                    }
                                }),
                                pagination: {
                                    more: response.more
                                }
                            };
                        },
                        cache: false,
                    },
                    dropdownParent: $('.input-append #user_list_wrapper')
                });

            }


            function getUserCount(methodName) {
                var methodNameUpper = methodName.toUpperCase();
                if (methodNameUpper == 'SELECTEDUSERS' || methodNameUpper == 'ALLUSERS' || methodNameUpper == 'TOPDEPOSITEDUSERS' ||
                    methodNameUpper == 'NOTLOGINUSERS') {
                    $('.userCount').text(0);
                    $('.userCountText').addClass('d-none');
                    return;
                }
                var route = "<?php echo e(route('admin.users.segment.count', ':methodName')); ?>"
                route = route.replace(':methodName', methodName)
                $.get(route, function(response) {
                    $('.userCount').text(response);
                    $('.userCountText').removeClass('d-none');
                });
            }

            $('.notification-via').on('click', function() {

                $('.notification-via').removeClass('active');
                $(this).addClass('active');
                $('[name=via]').val($(this).data('method'));

                if ($(this).data('method') == 'email') {
                    var nicPrev = $('.nicEdit').prev('div');
                    nicPrev.prev('div').removeClass('d-none');
                    nicPrev.removeClass('d-none');
                    $('.nicEdit').css('display', 'none')

                } else {
                    var nicPrev = $('.nicEdit').prev('div');
                    nicPrev.prev('div').addClass('d-none');
                    nicPrev.addClass('d-none');
                    $('.nicEdit').css('display', 'block')
                    $('.nicEdit').val("")
                }

                if ($(this).data('method') == 'push') {
                    $('.push-notification-file').removeClass('d-none');
                } else {
                    $('.push-notification-file').addClass('d-none');
                    $('.push-notification-file [type=file]').val('');
                }

                if ($(this).data('method') == 'push' || $(this).data('method') == 'email') {
                    $('.subject-wrapper').removeClass('d-none');
                } else {
                    $('.subject-wrapper').addClass('d-none')
                }
                $('.subject-wrapper').find('input').val('');
            });

            $(".notify-form").on("submit", function(e) {
                formSubmit = true;
            });

            <?php if(empty(!$sessionData)): ?>
                $(document).ready(function() {
                    const coalingTimeOut = setTimeout(() => {
                        let coalingTime = Number("<?php echo e($sessionData['cooling_time']); ?>");

                        $("#animate-circle").css({
                            "animation": `countdown ${coalingTime}s linear infinite forwards`
                        });

                        let $coalingCountElement = $('.coaling-time-count');
                        let $coalingLoaderElement = $(".coaling-loader");

                        $coalingCountElement.text(coalingTime);

                        const coalingIntVal = setInterval(function() {
                            coalingTime--;
                            $coalingCountElement.text(coalingTime);
                            if (coalingTime <= 0) {
                                formSubmit = true;
                                $("#animate-circle").css({
                                    "animation": `unset`
                                });
                                clearInterval(coalingIntVal);
                                clearTimeout(coalingTimeOut);
                                $(".notify-form").submit();
                            }
                        }, 1000);

                    }, 1000);
                });
            <?php endif; ?>

        })(jQuery);

        <?php if(!empty(@$sessionData) && @request()->email_sent && @request()->email_sent = 'yes'): ?>
            window.addEventListener('beforeunload', function(event) {
                if (!formSubmit) {
                    event.preventDefault();
                    event.returnValue = '';
                    var confirmationMessage = 'Are you sure you want to leave this page?';
                    (event || window.event).returnValue = confirmationMessage;
                    return confirmationMessage;
                }
            });
        <?php endif; ?>
    </script>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('style'); ?>
    <style>
        .countdown {
            position: relative;
            height: 100px;
            width: 100px;
            text-align: center;
            margin: 0 auto;
        }

        .coaling-time {
            color: yellow;
            position: absolute;
            z-index: 999999;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 30px;
        }

        .coaling-loader svg {
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            transform: rotateY(-180deg) rotateZ(-90deg);
            position: relative;
            z-index: 1;
        }

        .coaling-loader svg circle {
            stroke-dasharray: 314px;
            stroke-dashoffset: 0px;
            stroke-linecap: round;
            stroke-width: 6px;
            stroke: #4634ff;
            fill: transparent;

        }

        .coaling-loader .svg-count {
            width: 100px;
            height: 100px;
            position: relative;
            z-index: 1;
        }

        .coaling-loader .svg-count::before {
            content: '';
            position: absolute;
            outline: 5px solid #f3f3f9;
            z-index: -1;
            width: calc(100% - 16px);
            height: calc(100% - 16px);
            left: 8px;
            top: 8px;
            z-index: -1;
            border-radius: 100%
        }

        .coaling-time-count {
            color: #4634ff;
        }

        @keyframes countdown {
            from {
                stroke-dashoffset: 0px;
            }

            to {
                stroke-dashoffset: 314px;
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\users\notification_all.blade.php ENDPATH**/ ?>