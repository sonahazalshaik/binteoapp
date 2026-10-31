<ul class="nav nav-tabs mb-4 topTap breadcrumb-nav" role="tablist">
    <button class="breadcrumb-nav-close"><i class="las la-times"></i></button>
    <li class="nav-item <?php echo e(menuActive(['admin.setting.notification.global.email','admin.setting.notification.global.sms','admin.setting.notification.global.push'])); ?>" role="presentation">
        <a href="<?php echo e(route('admin.setting.notification.global.email')); ?>" class="nav-link text-dark" type="button">
            <i class="las la-globe"></i> <?php echo app('translator')->get('Global Template'); ?>
        </a>
    </li>
    <li class="nav-item <?php echo e(menuActive('admin.setting.notification.email')); ?>" role="presentation">
        <a href="<?php echo e(route('admin.setting.notification.email')); ?>" class="nav-link text-dark" type="button">
            <i class="las la-envelope"></i> <?php echo app('translator')->get('Email Setting'); ?>
        </a>
    </li>
    <li class="nav-item <?php echo e(menuActive('admin.setting.notification.sms')); ?>" role="presentation">
        <a href="<?php echo e(route('admin.setting.notification.sms')); ?>" class="nav-link text-dark" type="button">
            <i class="las la-sms"></i> <?php echo app('translator')->get('SMS Setting'); ?>
        </a>
    </li>
    <li class="nav-item <?php echo e(menuActive('admin.setting.notification.push')); ?>" role="presentation">
        <a href="<?php echo e(route('admin.setting.notification.push')); ?>" class="nav-link text-dark" type="button">
            <i class="las la-bell"></i> <?php echo app('translator')->get('Push Notification Setting'); ?>
        </a>
    </li>
    <li class="nav-item <?php echo e(menuActive(['admin.setting.notification.templates','admin.setting.notification.template.edit'])); ?>" role="presentation">
        <a href="<?php echo e(route('admin.setting.notification.templates')); ?>" class="nav-link text-dark" type="button">
            <i class="las la-list"></i> <?php echo app('translator')->get('Notification Templates'); ?>
        </a>
    </li>
</ul>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\notification\top_bar.blade.php ENDPATH**/ ?>