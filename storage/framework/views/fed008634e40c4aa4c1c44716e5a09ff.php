
<?php $__env->startSection('panel'); ?>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="mt-3">
                        <h6 class="mb-4">
                            <?php echo app('translator')->get('First of all, you have to create an in-app purchase product non-consumable in the Play Store. we assume that you already created some non-consumable products in the Play Store console now we will show the process of how you can set enough necessary processes to verify in-app purchases'); ?>
                        </h6>
                        <h6 class="mb-2 ">
                            <?php echo app('translator')->get('1. Enable APIs in Google Cloud Console'); ?>
                        </h6>
                        <div class="ms-3 mb-4">
                            <ul>
                                <li class="my-1">
                                    <i class="las la-dot-circle text--primary"></i>
                                    <?php echo app('translator')->get('Go to '); ?> <a href="https://console.cloud.google.com/" target="_blank" class="text--primary">
                                        <?php echo app('translator')->get('Google Cloud Console'); ?> <i class="fas fa-external-link-alt text--small"></i></a>
                                    <?php echo app('translator')->get(' and create a new app, or select one'); ?>
                                </li>
                                <li class="my-1">
                                    <i class="las la-dot-circle text--primary"></i>
                                    <?php echo app('translator')->get('Now go to the '); ?>
                                    <a href="https://console.cloud.google.com/apis/library/androidpublisher.googleapis.com" target="_blank"
                                        class="text--primary"> <?php echo app('translator')->get('Google Play Android Developer API'); ?> <i class="fas fa-external-link-alt text--small"></i>
                                    </a>
                                    <?php echo app('translator')->get('page and click on the enable button'); ?>
                                </li>
                                <li class="my-1"><i class="las la-dot-circle text--primary"></i>
                                    <?php echo app('translator')->get('Go to the'); ?>
                                    <a href="https://console.cloud.google.com/apis/library/playdeveloperreporting.googleapis.com" target="_blank"
                                        class="text--primary"> <?php echo app('translator')->get(' Google Play Developer Reporting API'); ?> <i class="fas fa-external-link-alt text--small"></i>
                                    </a>
                                    <?php echo app('translator')->get(' page and click on the enable button'); ?>
                                </li>
                            </ul>
                        </div>
                        <h6 class="mb-2"><?php echo app('translator')->get('2. Create a Service Account in the Google Cloud Console'); ?></h6>
                        <div class="ms-3 mb-4">
                            <ul>
                                <li class="my-1">
                                    <i class="las la-dot-circle text--primary"></i>
                                    <?php echo app('translator')->get('Go to the '); ?> <span class="fw-bold"><?php echo app('translator')->get('Google Cloud console'); ?> <i class="fas fa-arrow-right"></i> <?php echo app('translator')->get('IAM & Admin'); ?> <i
                                            class="fas fa-arrow-right"></i>
                                        <a href="https://console.cloud.google.com/projectselector2/iam-admin/serviceaccounts?supportedpurview=project"
                                            target="_blank" class="text--primary"><?php echo app('translator')->get('Service Accounts'); ?> <i class="fas fa-external-link-alt text--small"></i>
                                        </a>
                                    </span>
                                    <?php echo app('translator')->get('page. Please use the same Google Cloud Project you used in the previous steps. Click the Create Service Account button'); ?>
                                </li>
                                <li class="my-1">
                                    <i class="las la-dot-circle text--primary"></i>
                                    <?php echo app('translator')->get('Then a new popup will appear, just enter your service account name then a service account will auto-generate. Just copy the service id(email id) and click the create and continue button'); ?>
                                </li>
                                <li class="my-1">
                                    <i class="las la-dot-circle text--primary"></i>
                                    <?php echo app('translator')->get('Now a new window will be visible, just click on the select a roll drop-down button. Select 2 roles Pub/Sub Admin and Monitoring Viewer. Click on the continue button, and then the done button'); ?>
                                </li>
                                <li class="my-1">
                                    <i class="las la-dot-circle text--primary"></i>
                                    <?php echo app('translator')->get('Find the newly created account in the list and the actions click manage keys. Create a new JSON key and save it locally on your computer. And Upload it to the admin panel'); ?>
                                </li>
                            </ul>
                        </div>
                        <h6 class="mb-2 "><?php echo app('translator')->get('3. Grant Permissions in the Google Play Console'); ?></h6>
                        <div class="ms-3">
                            <ul>
                                <li class="my-1">
                                    <i class="las la-dot-circle text--primary"></i> <?php echo app('translator')->get('Go to the'); ?>
                                    <a href="https://play.google.com/console/u/0/developers/users-and-permissions" target="_blank"
                                        class="text--primary"><?php echo app('translator')->get('Users and Permissions'); ?> <i class="fas fa-external-link-alt text--small"></i>
                                    </a>
                                    <?php echo app('translator')->get('page in the Google Play Console and click Invite new users'); ?>
                                </li>
                                <li class="my-1">
                                    <i class="las la-dot-circle text--primary"></i>
                                    <?php echo app('translator')->get("Enter the email of the service account you've created in section 2 of this guide and make sure you select your app properly"); ?>
                                </li>
                                <li class="my-1">
                                    <i class="las la-dot-circle text--primary"></i>
                                    <?php echo app('translator')->get('Check on below mentioned permission and click on apply button'); ?>
                                    <ul class="ms-4 mt-2">
                                        <li class="my-1"> <i class="las la-check-circle text--primary"></i> <?php echo app('translator')->get('View app information (read only)'); ?></li>
                                        <li class="my-1"><i class="las la-check-circle text--primary"></i> <?php echo app('translator')->get('View financial data'); ?></li>
                                        <li class="my-1"><i class="las la-check-circle text--primary"></i> <?php echo app('translator')->get('Manage orders subscriptions'); ?></li>
                                        <li class="my-1"><i class="las la-check-circle text--primary"></i> <?php echo app('translator')->get('Manage store presence'); ?></li>
                                    </ul>
                                    <p class="fw-bold my-1">
                                        <?php echo app('translator')->get('Note: It takes at least 24 hours for changes to take effect but there is a hacking way. Just check this '); ?>
                                        <a href="https://stackoverflow.com/questions/43536904/google-play-developer-api-the-current-user-has-insufficient-permissions-to-pe/60691844#60691844"
                                            target="_blank" class="text--primary"><?php echo app('translator')->get('link'); ?> <i class="fas fa-external-link-alt text--small"></i>
                                        </a>
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="appPurchaseModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo app('translator')->get('Update Google Pay Credential'); ?></h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="mt-2"><?php echo app('translator')->get('File'); ?></label>
                            <input type="file" class="form-control" name="file" accept=".json" required>
                            <small class="mt-3 text-muted"><?php echo app('translator')->get('Supported Files: .json'); ?></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--primary w-100 h-45" type="submit"><?php echo app('translator')->get('Update'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb-plugins'); ?>
    <button class="btn btn-outline--primary updateBtn btn-sm" data-bs-toggle="modal" data-bs-target="#appPurchaseModal" type="button"><i
            class="las la-upload"></i><?php echo app('translator')->get('Update File'); ?></button>
    <a href="<?php echo e(route('admin.setting.app.purchase.file.download')); ?>"
        class="btn btn-outline--info updateBtn btn-sm  <?php if(!$fileExists): ?> disabled <?php endif; ?>" <?php if(!$fileExists): echo 'disabled'; endif; ?>>
        <i class="las la-download"></i><?php echo app('translator')->get('Download File'); ?>
    </a>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\setting\in_app_purchase\google.blade.php ENDPATH**/ ?>