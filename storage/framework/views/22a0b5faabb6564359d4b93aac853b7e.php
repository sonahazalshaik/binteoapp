

<?php $__env->startSection('panel'); ?>
    <div class="space-y-10 pb-10">
        <!-- System Alerts -->
        <!-- <div class="ffmpegAlert d-none"></div>
        <?php if(!gs('is_storage')): ?>
            <div class="storageAlert d-none"></div>
            <div class="storage">
                <div class="p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-lg bg-red-500 text-white flex items-center justify-center flex-shrink-0">
                        <i class="las la-bell"></i>
                    </div>
                    <div>
                        <h5 class="text-sm font-bold text-red-600"><?php echo app('translator')->get('Video Upload Disabled In Storage'); ?></h5>
                        <p class="text-xs text-red-500/80 mt-0.5"><?php echo app('translator')->get('Uploading videos to storage is currently disabled. Otherwise, videos will be uploaded to your local storage.'); ?> <?php echo app('translator')->get('you can'); ?> <a href="<?php echo e(route('admin.setting.system.configuration')); ?>" class="font-bold underline"><?php echo app('translator')->get('Enable Storage'); ?></a> <?php echo app('translator')->get('it in the settings'); ?>.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?> -->

        <!-- DASHBOARD SECTION -->
        <div>
            <div class="flex items-center justify-between mb-6 px-1">
                <h4 class="text-xl font-bold text-slate-800 dark:text-white"><?php echo app('translator')->get('Dashboard'); ?></h4>
                <a href="<?php echo e(route('cron')); ?>" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-lg text-indigo-600 text-xs font-bold hover:bg-indigo-100 transition-all">
                    <i class="las la-server"></i> <?php echo app('translator')->get('Cron Setup'); ?>
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <?php if (isset($component)) { $__componentOriginal44b5b1e416e9198f4e5a352966672bee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44b5b1e416e9198f4e5a352966672bee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.native-card','data' => ['title' => 'Total Users','value' => ''.e($widget['total_users']).'','icon' => 'las la-users','color' => 'purple','link' => ''.e(route('admin.users.all')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total Users','value' => ''.e($widget['total_users']).'','icon' => 'las la-users','color' => 'purple','link' => ''.e(route('admin.users.all')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $attributes = $__attributesOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $component = $__componentOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__componentOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal44b5b1e416e9198f4e5a352966672bee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44b5b1e416e9198f4e5a352966672bee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.native-card','data' => ['title' => 'Active Users','value' => ''.e($widget['verified_users']).'','icon' => 'las la-user-check','color' => 'success','link' => ''.e(route('admin.users.active')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Active Users','value' => ''.e($widget['verified_users']).'','icon' => 'las la-user-check','color' => 'success','link' => ''.e(route('admin.users.active')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $attributes = $__attributesOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $component = $__componentOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__componentOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal44b5b1e416e9198f4e5a352966672bee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44b5b1e416e9198f4e5a352966672bee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.native-card','data' => ['title' => 'Online Users','value' => ''.e($widget['online_users']).'','icon' => 'las la-broadcast-tower','color' => 'info','link' => ''.e(route('admin.users.active')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Online Users','value' => ''.e($widget['online_users']).'','icon' => 'las la-broadcast-tower','color' => 'info','link' => ''.e(route('admin.users.active')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $attributes = $__attributesOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $component = $__componentOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__componentOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
                <?php
                    $watchValue = $widget['total_watch_time'] < 3600 ? round($widget['total_watch_time'] / 60) . ' Mins' : round($widget['total_watch_time'] / 3600) . ' Hrs';
                ?>
                <?php if (isset($component)) { $__componentOriginal44b5b1e416e9198f4e5a352966672bee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44b5b1e416e9198f4e5a352966672bee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.native-card','data' => ['title' => 'Total Watch Time','value' => ''.e($watchValue).'','icon' => 'las la-stopwatch','color' => 'primary','link' => ''.e(route('admin.analytics.advanced.videos')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total Watch Time','value' => ''.e($watchValue).'','icon' => 'las la-stopwatch','color' => 'primary','link' => ''.e(route('admin.analytics.advanced.videos')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $attributes = $__attributesOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $component = $__componentOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__componentOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal44b5b1e416e9198f4e5a352966672bee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44b5b1e416e9198f4e5a352966672bee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.native-card','data' => ['title' => 'Revenue Summary','value' => ''.e(showAmount($widget['estimated_revenue'], 0)).'','icon' => 'las la-hand-holding-usd','color' => 'warning','link' => ''.e(route('admin.analytics.advanced.revenue')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Revenue Summary','value' => ''.e(showAmount($widget['estimated_revenue'], 0)).'','icon' => 'las la-hand-holding-usd','color' => 'warning','link' => ''.e(route('admin.analytics.advanced.revenue')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $attributes = $__attributesOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $component = $__componentOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__componentOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
            </div>
        </div>

        <!-- PAYMENTS & WITHDRAWALS SECTION -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-10">
            <!-- Payments -->
            <div>
                <div class="mb-6 px-1">
                    <h4 class="text-xl font-bold text-slate-800 dark:text-white"><?php echo app('translator')->get('Payments'); ?></h4>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php if (isset($component)) { $__componentOriginal44b5b1e416e9198f4e5a352966672bee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44b5b1e416e9198f4e5a352966672bee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.native-card','data' => ['title' => 'Total Payment','value' => ''.e(showAmount($deposit['total_deposit_amount'] + $deposit['total_deposit_charge'])).'','icon' => 'las la-hand-holding-usd','color' => 'success','link' => ''.e(route('admin.deposit.list')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total Payment','value' => ''.e(showAmount($deposit['total_deposit_amount'] + $deposit['total_deposit_charge'])).'','icon' => 'las la-hand-holding-usd','color' => 'success','link' => ''.e(route('admin.deposit.list')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $attributes = $__attributesOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $component = $__componentOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__componentOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal44b5b1e416e9198f4e5a352966672bee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44b5b1e416e9198f4e5a352966672bee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.native-card','data' => ['title' => 'Pending Payments','value' => ''.e($deposit['total_deposit_pending']).'','icon' => 'las la-spinner','color' => 'warning','link' => ''.e(route('admin.deposit.pending')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Pending Payments','value' => ''.e($deposit['total_deposit_pending']).'','icon' => 'las la-spinner','color' => 'warning','link' => ''.e(route('admin.deposit.pending')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $attributes = $__attributesOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $component = $__componentOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__componentOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal44b5b1e416e9198f4e5a352966672bee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44b5b1e416e9198f4e5a352966672bee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.native-card','data' => ['title' => 'Rejected Payments','value' => ''.e($deposit['total_deposit_rejected']).'','icon' => 'las la-ban','color' => 'danger','link' => ''.e(route('admin.deposit.rejected')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Rejected Payments','value' => ''.e($deposit['total_deposit_rejected']).'','icon' => 'las la-ban','color' => 'danger','link' => ''.e(route('admin.deposit.rejected')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $attributes = $__attributesOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $component = $__componentOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__componentOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal44b5b1e416e9198f4e5a352966672bee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44b5b1e416e9198f4e5a352966672bee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.native-card','data' => ['title' => 'Payments Charge','value' => ''.e(showAmount($deposit['total_deposit_charge'])).'','icon' => 'las la-percentage','color' => 'primary','link' => ''.e(route('admin.deposit.list')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Payments Charge','value' => ''.e(showAmount($deposit['total_deposit_charge'])).'','icon' => 'las la-percentage','color' => 'primary','link' => ''.e(route('admin.deposit.list')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $attributes = $__attributesOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $component = $__componentOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__componentOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
                </div>
            </div>

            <!-- Withdrawals -->
            <div>
                <div class="mb-6 px-1">
                    <h4 class="text-xl font-bold text-slate-800 dark:text-white"><?php echo app('translator')->get('Withdrawals'); ?></h4>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php if (isset($component)) { $__componentOriginal44b5b1e416e9198f4e5a352966672bee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44b5b1e416e9198f4e5a352966672bee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.native-card','data' => ['title' => 'Total Withdrawn','value' => ''.e(showAmount($withdrawals['total_withdraw_amount'] + $withdrawals['total_withdraw_charge'])).'','icon' => 'las la-wallet','color' => 'success','link' => ''.e(route('admin.withdraw.data.all')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total Withdrawn','value' => ''.e(showAmount($withdrawals['total_withdraw_amount'] + $withdrawals['total_withdraw_charge'])).'','icon' => 'las la-wallet','color' => 'success','link' => ''.e(route('admin.withdraw.data.all')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $attributes = $__attributesOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $component = $__componentOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__componentOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal44b5b1e416e9198f4e5a352966672bee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44b5b1e416e9198f4e5a352966672bee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.native-card','data' => ['title' => 'Pending Withdrawals','value' => ''.e($withdrawals['total_withdraw_pending']).'','icon' => 'las la-spinner','color' => 'warning','link' => ''.e(route('admin.withdraw.data.pending')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Pending Withdrawals','value' => ''.e($withdrawals['total_withdraw_pending']).'','icon' => 'las la-spinner','color' => 'warning','link' => ''.e(route('admin.withdraw.data.pending')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $attributes = $__attributesOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $component = $__componentOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__componentOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal44b5b1e416e9198f4e5a352966672bee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44b5b1e416e9198f4e5a352966672bee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.native-card','data' => ['title' => 'Rejected Withdrawals','value' => ''.e($withdrawals['total_withdraw_rejected']).'','icon' => 'las la-times-circle','color' => 'danger','link' => ''.e(route('admin.withdraw.data.rejected')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Rejected Withdrawals','value' => ''.e($withdrawals['total_withdraw_rejected']).'','icon' => 'las la-times-circle','color' => 'danger','link' => ''.e(route('admin.withdraw.data.rejected')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $attributes = $__attributesOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $component = $__componentOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__componentOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal44b5b1e416e9198f4e5a352966672bee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44b5b1e416e9198f4e5a352966672bee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.native-card','data' => ['title' => 'Withdrawal Charge','value' => ''.e(showAmount($withdrawals['total_withdraw_charge'])).'','icon' => 'las la-percent','color' => 'primary','link' => ''.e(route('admin.withdraw.data.all')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Withdrawal Charge','value' => ''.e(showAmount($withdrawals['total_withdraw_charge'])).'','icon' => 'las la-percent','color' => 'primary','link' => ''.e(route('admin.withdraw.data.all')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $attributes = $__attributesOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $component = $__componentOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__componentOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
                </div>
            </div>
        </div>

        <!-- VIDEOS SECTION -->
        <div>
            <div class="mb-6 px-1">
                <h4 class="text-xl font-bold text-slate-800 dark:text-white"><?php echo app('translator')->get('Videos'); ?></h4>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <?php if (isset($component)) { $__componentOriginal44b5b1e416e9198f4e5a352966672bee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44b5b1e416e9198f4e5a352966672bee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.native-card','data' => ['title' => 'Total Videos','value' => ''.e($widget['total_videos']).'','icon' => 'las la-video','color' => 'primary','link' => ''.e(route('admin.videos.index_legacy')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total Videos','value' => ''.e($widget['total_videos']).'','icon' => 'las la-video','color' => 'primary','link' => ''.e(route('admin.videos.index_legacy')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $attributes = $__attributesOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $component = $__componentOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__componentOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal44b5b1e416e9198f4e5a352966672bee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44b5b1e416e9198f4e5a352966672bee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.native-card','data' => ['title' => 'Uploads Today','value' => ''.e($widget['uploads_today']).'','icon' => 'las la-cloud-upload-alt','color' => 'info','link' => ''.e(route('admin.videos.index_legacy')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Uploads Today','value' => ''.e($widget['uploads_today']).'','icon' => 'las la-cloud-upload-alt','color' => 'info','link' => ''.e(route('admin.videos.index_legacy')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $attributes = $__attributesOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $component = $__componentOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__componentOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal44b5b1e416e9198f4e5a352966672bee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44b5b1e416e9198f4e5a352966672bee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.native-card','data' => ['title' => 'Free Videos','value' => ''.e($widget['free_videos']).'','icon' => 'las la-file-video','color' => 'success','link' => ''.e(route('admin.videos.free')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Free Videos','value' => ''.e($widget['free_videos']).'','icon' => 'las la-file-video','color' => 'success','link' => ''.e(route('admin.videos.free')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $attributes = $__attributesOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $component = $__componentOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__componentOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal44b5b1e416e9198f4e5a352966672bee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44b5b1e416e9198f4e5a352966672bee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.native-card','data' => ['title' => 'Stock Videos','value' => ''.e($widget['stock_videos']).'','icon' => 'las la-hand-holding-usd','color' => 'danger','link' => ''.e(route('admin.videos.stock')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Stock Videos','value' => ''.e($widget['stock_videos']).'','icon' => 'las la-hand-holding-usd','color' => 'danger','link' => ''.e(route('admin.videos.stock')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $attributes = $__attributesOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $component = $__componentOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__componentOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal44b5b1e416e9198f4e5a352966672bee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44b5b1e416e9198f4e5a352966672bee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.native-card','data' => ['title' => 'Regular Videos','value' => ''.e($widget['regular_videos']).'','icon' => 'las la-video','color' => 'warning','link' => ''.e(route('admin.videos.regular')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Regular Videos','value' => ''.e($widget['regular_videos']).'','icon' => 'las la-video','color' => 'warning','link' => ''.e(route('admin.videos.regular')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $attributes = $__attributesOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $component = $__componentOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__componentOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                <?php if (isset($component)) { $__componentOriginal44b5b1e416e9198f4e5a352966672bee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44b5b1e416e9198f4e5a352966672bee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.native-card','data' => ['title' => 'Shorts & Reels','value' => ''.e($widget['total_reels']).'','icon' => 'las la-play','color' => 'primary','link' => ''.e(route('admin.reels.index')).'','viewMore' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Shorts & Reels','value' => ''.e($widget['total_reels']).'','icon' => 'las la-play','color' => 'primary','link' => ''.e(route('admin.reels.index')).'','viewMore' => 'true']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $attributes = $__attributesOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $component = $__componentOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__componentOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal44b5b1e416e9198f4e5a352966672bee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44b5b1e416e9198f4e5a352966672bee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.native-card','data' => ['title' => 'Public Videos','value' => ''.e($widget['public_videos']).'','icon' => 'las la-file-video','color' => 'success','link' => ''.e(route('admin.videos.public')).'','viewMore' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Public Videos','value' => ''.e($widget['public_videos']).'','icon' => 'las la-file-video','color' => 'success','link' => ''.e(route('admin.videos.public')).'','viewMore' => 'true']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $attributes = $__attributesOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $component = $__componentOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__componentOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal44b5b1e416e9198f4e5a352966672bee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44b5b1e416e9198f4e5a352966672bee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.native-card','data' => ['title' => 'Private Videos','value' => ''.e($widget['private_videos']).'','icon' => 'las la-video-slash','color' => 'danger','link' => ''.e(route('admin.videos.private')).'','viewMore' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Private Videos','value' => ''.e($widget['private_videos']).'','icon' => 'las la-video-slash','color' => 'danger','link' => ''.e(route('admin.videos.private')).'','viewMore' => 'true']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $attributes = $__attributesOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $component = $__componentOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__componentOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal44b5b1e416e9198f4e5a352966672bee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44b5b1e416e9198f4e5a352966672bee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.native-card','data' => ['title' => 'Draft Videos','value' => ''.e($widget['draft_videos']).'','icon' => 'las la-edit','color' => 'warning','link' => ''.e(route('admin.videos.draft')).'','viewMore' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Draft Videos','value' => ''.e($widget['draft_videos']).'','icon' => 'las la-edit','color' => 'warning','link' => ''.e(route('admin.videos.draft')).'','viewMore' => 'true']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $attributes = $__attributesOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__attributesOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44b5b1e416e9198f4e5a352966672bee)): ?>
<?php $component = $__componentOriginal44b5b1e416e9198f4e5a352966672bee; ?>
<?php unset($__componentOriginal44b5b1e416e9198f4e5a352966672bee); ?>
<?php endif; ?>
            </div>
        </div>
    </div>

    <?php echo $__env->make('admin.partials.cron_modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb-plugins'); ?>
    <a href="<?php echo e(route('cron')); ?>" target="_blank" class="btn btn-outline--primary btn-sm">
        <i class="las la-server"></i><?php echo app('translator')->get('Cron Setup'); ?>
    </a>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('script-lib'); ?>
    <script src="<?php echo e(asset('assets/global/js/vendor/apexcharts.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/global/js/vendor/chart.js.2.8.0.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/global/js/moment.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/global/js/daterangepicker.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/global/js/charts.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('style-lib'); ?>
    <link type="text/css" href="<?php echo e(asset('assets/global/css/daterangepicker.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        "use strict";



        const start = moment().subtract(14, 'days');
        const end = moment();

        const dateRangeOptions = {
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                'Last 30 Days': [moment().subtract(30, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                    'month')],
                'Last 6 Months': [moment().subtract(6, 'months').startOf('month'), moment().endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
            },
            maxDate: moment()
        }

        const changeDatePickerText = (element, startDate, endDate) => {
            $(element).html(startDate.format('MMMM D, YYYY') + ' - ' + endDate.format('MMMM D, YYYY'));
        }

        let dwChart = barChart(
            document.querySelector("#dwChartArea"),
            `<?php echo e(__(gs('cur_text'))); ?>`,
            [{
                    name: 'Payments',
                    data: []
                },
                {
                    name: 'Withdrawn',
                    data: []
                }
            ],
            [],
        );

        let trxChart = lineChart(
            document.querySelector("#transactionChartArea"),
            [{
                    name: "Plus Transactions",
                    data: []
                },
                {
                    name: "Minus Transactions",
                    data: []
                }
            ],
            []
        );


        const depositWithdrawChart = (startDate, endDate) => {

            const data = {
                start_date: startDate.format('YYYY-MM-DD'),
                end_date: endDate.format('YYYY-MM-DD')
            }

            const url = `<?php echo e(route('admin.chart.deposit.withdraw')); ?>`;

            $.get(url, data,
                function(data, status) {
                    if (status == 'success') {
                        dwChart.updateSeries(data.data);
                        dwChart.updateOptions({
                            xaxis: {
                                categories: data.created_on,
                            }
                        });
                    }
                }
            );
        }

        const transactionChart = (startDate, endDate) => {

            const data = {
                start_date: startDate.format('YYYY-MM-DD'),
                end_date: endDate.format('YYYY-MM-DD')
            }

            const url = `<?php echo e(route('admin.chart.transaction')); ?>`;


            $.get(url, data,
                function(data, status) {
                    if (status == 'success') {


                        trxChart.updateSeries(data.data);
                        trxChart.updateOptions({
                            xaxis: {
                                categories: data.created_on,
                            }
                        });
                    }
                }
            );
        }



        $('#dwDatePicker').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('#dwDatePicker span',
            start, end));
        $('#trxDatePicker').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('#trxDatePicker span',
            start, end));

        changeDatePickerText('#dwDatePicker span', start, end);
        changeDatePickerText('#trxDatePicker span', start, end);

        depositWithdrawChart(start, end);
        transactionChart(start, end);

        $('#dwDatePicker').on('apply.daterangepicker', (event, picker) => depositWithdrawChart(picker.startDate, picker
            .endDate));
        $('#trxDatePicker').on('apply.daterangepicker', (event, picker) => transactionChart(picker.startDate, picker
            .endDate));

        piChart(
            document.getElementById('userBrowserChart'),
            <?php echo json_encode(@$chart['user_browser_counter']->keys(), 15, 512) ?>,
            <?php echo json_encode(@$chart['user_browser_counter']->flatten(), 15, 512) ?>
        );

        piChart(
            document.getElementById('userOsChart'),
            <?php echo json_encode(@$chart['user_os_counter']->keys(), 15, 512) ?>,
            <?php echo json_encode(@$chart['user_os_counter']->flatten(), 15, 512) ?>
        );

        piChart(
            document.getElementById('userCountryChart'),
            <?php echo json_encode(@$chart['user_country_counter']->keys(), 15, 512) ?>,
            <?php echo json_encode(@$chart['user_country_counter']->flatten(), 15, 512) ?>
        );





        const ffmpeg = "<?php echo e(gs('ffmpeg_status')); ?>";
        const storage = "<?php echo e(gs('is_storage')); ?>";




        if (storage == 1) {

            $(document).ready(function() {

                const redirectUrl = "<?php echo e(route('admin.storage.index')); ?>"
                $.ajax({
                    type: "get",
                    url: "<?php echo e(route('admin.check.space')); ?>",

                    success: function(response) {


                        if (response.status == true) {
                            $('.storageAlert')
                                .removeClass('d-none')
                                .html(`
                                    <div class="custom-alert alert alert--danger" role="alert">
                                        <span class="alert__icon">
                                            <i class="far fa-bell"></i>
                                        </span>
                                        <div class="alert__content">
                                            <h5 class="alert-heading"><?php echo app('translator')->get('Storage Limit Reached'); ?></h5>
                                            <p><?php echo app('translator')->get('Unable to upload the file as the selected storage location has reached its capacity. Please free up space or upgrade your storage. <a href="${redirectUrl}">Click here</a>'); ?></p>
                                        </div>
                                    </div>
                                `);
                        } else {
                            $('.storageAlert').addClass('d-none').html('');
                        }

                    }
                });
            });
        }




        if (ffmpeg == 1) {

            $(document).ready(function() {

                const redirectUrl = "<?php echo e(route('admin.setting.system.configuration')); ?>"
                $.ajax({
                    type: "get",
                    url: "<?php echo e(route('admin.setting.check.ffmpeg')); ?>",

                    success: function(response) {
                        if (response.status == 'error') {
                            $('.ffmpegAlert').removeClass('d-none').html(`
                                <div class="custom-alert alert alert--danger" role="alert">
                                    <span class="alert__icon">
                                        <i class="far fa-bell"></i>
                                    </span>
                                    <div class="alert__content">
                                        <h5 class="alert-heading"><?php echo app('translator')->get('FFmpeg is required'); ?></h5>
                                        <p><?php echo app('translator')->get('FFmpeg is essential for processing and converting video files on this platform. Without FFmpeg, video format conversions will not work properly. If you do not use FFmpeg, you can <a href="${redirectUrl}">Disable FFmpeg</a> it in the settings.'); ?></p>
                                    </div>
                                </div>
                            `);
                        } else {
                            $('.ffmpegAlert').addClass('d-none').html('');
                        }

                    }
                });
            });
        }
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('style'); ?>
    <style>
        .apexcharts-menu {
            min-width: 120px !important;
        }

        .custom-alert {
            align-items: flex-start;
            gap: 16px;
            padding: 16px;
        }

        .custom-alert.alert--danger {
            background-color: rgb(235 34 34 / 10%);
            border-left: 5px solid #eb2222;
        }

        .custom-alert .alert__icon {
            height: 36px;
            width: 36px;
            background: rgb(235 34 34 / 10%);
            color: #eb2222;
            border-radius: 50%;
            display: grid;
            place-content: center;
            flex-shrink: 0;
        }

        .alert__content {
            flex: 1;
        }

        .alert-heading {
            margin-bottom: 6px;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\dashboard.blade.php ENDPATH**/ ?>