<ul class="nav nav-tabs mb-4 topTap breadcrumb-nav" role="tablist">
    <button class="breadcrumb-nav-close"><i class="las la-times"></i></button>
    <li class="nav-item <?php echo e(menuActive(['admin.gateway.automatic.index','admin.gateway.automatic.edit'])); ?>" role="presentation">
        <a href="<?php echo e(route('admin.gateway.automatic.index')); ?>" class="nav-link text-dark" type="button">
            <i class="las la-credit-card"></i> <?php echo app('translator')->get('Automatic Gateway'); ?>
        </a>
    </li>
    <li class="nav-item <?php echo e(menuActive(['admin.gateway.manual.index','admin.gateway.manual.edit','admin.gateway.manual.create'])); ?>" role="presentation">
        <a href="<?php echo e(route('admin.gateway.manual.index')); ?>" class="nav-link text-dark" type="button">
            <i class="las la-wallet"></i> <?php echo app('translator')->get('Manual Gateway'); ?>
        </a>
    </li>
</ul>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\gateways\top_bar.blade.php ENDPATH**/ ?>