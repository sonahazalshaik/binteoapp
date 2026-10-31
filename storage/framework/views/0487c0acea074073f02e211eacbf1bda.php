
<?php $__env->startSection('panel'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form method="post">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <ul class="list-group">
                            <li
                                class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0"><?php echo app('translator')->get('User Registration'); ?></p>
                                    <p class="mb-0">
                                        <small><?php echo app('translator')->get('If you disable this module, no one can register on this system.'); ?></small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                        data-offstyle="-danger" data-bs-toggle="toggle" data-height="35"
                                        data-on="<?php echo app('translator')->get('Enable'); ?>" data-off="<?php echo app('translator')->get('Disable'); ?>" name="registration"
                                        <?php if(gs('registration')): ?> checked <?php endif; ?>>
                                </div>
                            </li>
                            <li
                                class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0"><?php echo app('translator')->get('Force SSL'); ?></p>
                                    <p class="mb-0">
                                        <small><?php echo app('translator')->get('By enabling'); ?> <span class="fw-bold"><?php echo app('translator')->get('Force SSL (Secure Sockets Layer)'); ?></span>
                                            <?php echo app('translator')->get('the system will force a visitor that he/she must have to visit in secure mode. Otherwise, the site will be loaded in secure mode.'); ?></small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                        data-offstyle="-danger" data-bs-toggle="toggle" data-height="35"
                                        data-on="<?php echo app('translator')->get('Enable'); ?>" data-off="<?php echo app('translator')->get('Disable'); ?>" name="force_ssl"
                                        <?php if(gs('force_ssl')): ?> checked <?php endif; ?>>
                                </div>
                            </li>
                            <li
                                class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0"><?php echo app('translator')->get('Agree Policy'); ?></p>
                                    <p class="mb-0">
                                        <small><?php echo app('translator')->get('If you enable this module, that means a user must have to agree with your system\'s'); ?> <a
                                                href="<?php echo e(route('admin.frontend.sections', 'policy_pages')); ?>"><?php echo app('translator')->get('policies'); ?></a>
                                            <?php echo app('translator')->get('during registration.'); ?></small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                        data-offstyle="-danger" data-bs-toggle="toggle" data-height="35"
                                        data-on="<?php echo app('translator')->get('Enable'); ?>" data-off="<?php echo app('translator')->get('Disable'); ?>" name="agree"
                                        <?php if(gs('agree')): ?> checked <?php endif; ?>>
                                </div>
                            </li>

                            <li
                                class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0"><?php echo app('translator')->get('Force Secure Password'); ?></p>
                                    <p class="mb-0">
                                        <small><?php echo app('translator')->get('By enabling this module, a user must set a secure password while signing up or changing the password.'); ?></small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                        data-offstyle="-danger" data-bs-toggle="toggle" data-height="35"
                                        data-on="<?php echo app('translator')->get('Enable'); ?>" data-off="<?php echo app('translator')->get('Disable'); ?>" name="secure_password"
                                        <?php if(gs('secure_password')): ?> checked <?php endif; ?>>
                                </div>
                            </li>



                            <li
                                class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0"><?php echo app('translator')->get('Email Verification'); ?></p>
                                    <p class="mb-0">
                                        <small>
                                            <?php echo app('translator')->get('If you enable'); ?> <span class="fw-bold"><?php echo app('translator')->get('Email Verification'); ?></span>,
                                            <?php echo app('translator')->get('users have to verify their email to access the dashboard. A 6-digit verification code will be sent to their email to be verified.'); ?>
                                            <br>
                                            <span class="fw-bold"><i><?php echo app('translator')->get('Note'); ?>:</i></span> <i><?php echo app('translator')->get('Make sure that the'); ?>
                                                <span class="fw-bold"><?php echo app('translator')->get('Email Notification'); ?> </span> <?php echo app('translator')->get('module is enabled'); ?></i>
                                        </small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                        data-offstyle="-danger" data-bs-toggle="toggle" data-height="35"
                                        data-on="<?php echo app('translator')->get('Enable'); ?>" data-off="<?php echo app('translator')->get('Disable'); ?>" name="ev"
                                        <?php if(gs('ev')): ?> checked <?php endif; ?>>
                                </div>
                            </li>

                            <li
                                class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0"><?php echo app('translator')->get('Email Notification'); ?></p>
                                    <p class="mb-0">
                                        <small><?php echo app('translator')->get('If you enable this module, the system will send emails to users where needed. Otherwise, no email will be sent.'); ?> <code><?php echo app('translator')->get('So be sure before disabling this module that, the system doesn\'t need to send any emails.'); ?></code></small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                        data-offstyle="-danger" data-bs-toggle="toggle" data-height="35"
                                        data-on="<?php echo app('translator')->get('Enable'); ?>" data-off="<?php echo app('translator')->get('Disable'); ?>" name="en"
                                        <?php if(gs('en')): ?> checked <?php endif; ?>>
                                </div>
                            </li>





                            <li
                                class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0"><?php echo app('translator')->get('Push Notification'); ?></p>
                                    <p class="mb-0">
                                        <small>
                                            <?php echo app('translator')->get('If you enable this module, the system will send push notifications to users. Otherwise, no push notification will be sent.'); ?>
                                            <a href="<?php echo e(route('admin.setting.notification.push')); ?>"><?php echo app('translator')->get('Setting here'); ?></a>
                                        </small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                        data-offstyle="-danger" data-bs-toggle="toggle" data-height="35"
                                        data-on="<?php echo app('translator')->get('Enable'); ?>" data-off="<?php echo app('translator')->get('Disable'); ?>" name="pn"
                                        <?php if(gs('pn')): ?> checked <?php endif; ?>>
                                </div>
                            </li>

                            <li
                                class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0"><?php echo app('translator')->get('Language Option'); ?></p>
                                    <p class="mb-0">
                                        <small><?php echo app('translator')->get('If you enable this module, users can change the language according to their needs.'); ?></small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                        data-offstyle="-danger" data-bs-toggle="toggle" data-height="35"
                                        data-on="<?php echo app('translator')->get('Enable'); ?>" data-off="<?php echo app('translator')->get('Disable'); ?>" name="multi_language"
                                        <?php if(gs('multi_language')): ?> checked <?php endif; ?>>
                                </div>
                            </li>


                            <li
                                class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0"><?php echo app('translator')->get('FFmpeg '); ?></p>
                                    <p class="mb-0">
                                        <small><?php echo app('translator')->get('If you enable this module, the video will upload multiple resoulution.'); ?></small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                        data-offstyle="-danger" data-bs-toggle="toggle" data-height="35"
                                        data-on="<?php echo app('translator')->get('Enable'); ?>" data-off="<?php echo app('translator')->get('Disable'); ?>" name="ffmpeg_status"
                                        <?php if(gs('ffmpeg_status')): ?> checked <?php endif; ?>>
                                </div>
                            </li>

                            <li
                                class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0"><?php echo app('translator')->get('Storage System'); ?></p>
                                    <p class="mb-0">
                                        <small><?php echo app('translator')->get('If you enable this module, the video will upload your storage.'); ?></small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                        data-offstyle="-danger" data-bs-toggle="toggle" data-height="35"
                                        data-on="<?php echo app('translator')->get('Enable'); ?>" data-off="<?php echo app('translator')->get('Disable'); ?>" name="is_storage"
                                        <?php if(gs('is_storage')): ?> checked <?php endif; ?>>
                                </div>
                            </li>

                            <li
                                class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0"><?php echo app('translator')->get('Playlist Sell'); ?></p>
                                    <p class="mb-0">
                                        <small><?php echo app('translator')->get('Enable this to allow selling playlists individually.'); ?></small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                        data-offstyle="-danger" data-bs-toggle="toggle" data-height="35"
                                        data-on="<?php echo app('translator')->get('Enable'); ?>" data-off="<?php echo app('translator')->get('Disable'); ?>"
                                        name="is_playlist_sell" <?php if(gs('is_playlist_sell')): ?> checked <?php endif; ?>>
                                </div>
                            </li>

                            <li
                                class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0"><?php echo app('translator')->get('Monthly Subscription'); ?></p>
                                    <p class="mb-0">
                                        <small><?php echo app('translator')->get('Enable this to offer users a monthly subscription plan.'); ?></small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                        data-offstyle="-danger" data-bs-toggle="toggle" data-height="35"
                                        data-on="<?php echo app('translator')->get('Enable'); ?>" data-off="<?php echo app('translator')->get('Disable'); ?>" name="is_monthly_subscription"
                                        <?php if(gs('is_monthly_subscription')): ?> checked <?php endif; ?>>
                                </div>
                            </li>

                        </ul>
                    </div>
                    <div class="card-footer bg-slate-50/50 dark:bg-white/[0.02] border-t border-slate-100 dark:border-white/5 p-8">
                        <button type="submit" class="w-full h-12 rounded-2xl bg-orange-600 text-white text-[13px] font-black uppercase tracking-[0.2em] hover:scale-[1.02] transition-all shadow-lg shadow-orange-500/20 "><?php echo app('translator')->get('Commit Intelligence Updates'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
    <style>
        .toggle.btn-lg {
            height: 37px !important;
            min-height: 37px !important;
        }

        .toggle-handle {
            width: 25px !important;
            padding: 0;
        }

        .form-group {
            width: 125px;
            margin-bottom: 0;
            flex-shrink: 0
        }

        .list-group-item:hover {
            background-color: #F7F7F7
        }
    </style>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\setting\configuration.blade.php ENDPATH**/ ?>