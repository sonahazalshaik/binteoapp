
<?php $__env->startSection('panel'); ?>
    <?php
        $purchased = $user
            ->deposits()
            ->where('is_monetization', Status::YES)
            ->exists();

    ?>
    <div class="row gy-3 ">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex flex-wrap justify-content-between">
                    <h5 class="card-title "><?php echo app('translator')->get('Subscriber & Views'); ?></h5>

                    <h5><?php echo app('translator')->get('Monetiztion Type'); ?>: <?php if($purchased): ?>
                            <span class="badge badge--success"><?php echo app('translator')->get('Paid'); ?> </span>
                        <?php else: ?>
                            <span class="badge badge--warning"> <?php echo app('translator')->get('Free'); ?> </span>
                        <?php endif; ?>
                    </h5>

                    <h5><?php echo app('translator')->get('Monetization Status'); ?>: <?php
                        echo $user->monetizationStep;
                    ?> </h5>
                </div>
                <div class="card-body text-center">
                    <div class="row gy-3">

                        <div class="col-xxl-6 col-sm-6">
                            <?php if (isset($component)) { $__componentOriginal433d6a5be5b58ac8aa6a74031c6196f9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal433d6a5be5b58ac8aa6a74031c6196f9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.widget','data' => ['type' => '2','value' => ''.e(gs('minimum_subscribe')).'','title' => 'Need Subscribers','style' => '7','link' => 'javascript:void(0)','icon' => 'las la-user-friends','bg' => 'indigo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => '2','value' => ''.e(gs('minimum_subscribe')).'','title' => 'Need Subscribers','style' => '7','link' => 'javascript:void(0)','icon' => 'las la-user-friends','bg' => 'indigo']); ?>
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


                        <div class="col-xxl-6 col-sm-6">
                            <?php if (isset($component)) { $__componentOriginal433d6a5be5b58ac8aa6a74031c6196f9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal433d6a5be5b58ac8aa6a74031c6196f9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.widget','data' => ['type' => '2','value' => ''.e(gs('minimum_views')).'','title' => 'Need Views','style' => '7','link' => 'javascript:void(0)','icon' => 'las la-eye','bg' => '8']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => '2','value' => ''.e(gs('minimum_views')).'','title' => 'Need Views','style' => '7','link' => 'javascript:void(0)','icon' => 'las la-eye','bg' => '8']); ?>
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


                        <div class="col-xxl-6 col-sm-6">
                            <?php if (isset($component)) { $__componentOriginal433d6a5be5b58ac8aa6a74031c6196f9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal433d6a5be5b58ac8aa6a74031c6196f9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.widget','data' => ['type' => '2','value' => ''.e($totalSubscriber).'','title' => 'Get Subscribers','style' => '7','link' => 'javascript:void(0)','icon' => 'las la-users','bg' => '17']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => '2','value' => ''.e($totalSubscriber).'','title' => 'Get Subscribers','style' => '7','link' => 'javascript:void(0)','icon' => 'las la-users','bg' => '17']); ?>
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
                        <div class="col-xxl-6 col-sm-6">
                            <?php if (isset($component)) { $__componentOriginal433d6a5be5b58ac8aa6a74031c6196f9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal433d6a5be5b58ac8aa6a74031c6196f9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.widget','data' => ['type' => '2','value' => ''.e($totalViews).'','title' => 'Get Views','style' => '7','link' => 'javascript:void(0)','icon' => 'las la-chart-line','bg' => '6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => '2','value' => ''.e($totalViews).'','title' => 'Get Views','style' => '7','link' => 'javascript:void(0)','icon' => 'las la-chart-line','bg' => '6']); ?>
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

                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header  d-flex flex-wrap justify-content-between">
                    <h5 class="card-title"><?php echo app('translator')->get('Subscriber & Views Progress'); ?></h5>
                    <div class="border p-1 cursor-pointer rounded chart-title-text" id="dataPicker">
                        <i class="la la-calendar"></i>&nbsp;
                        <span></span> <i class="la la-caret-down"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="overViewChart"></div>
                </div>
            </div>
        </div>
    </div>
    <?php if (isset($component)) { $__componentOriginalbd5922df145d522b37bf664b524be380 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd5922df145d522b37bf664b524be380 = $attributes; } ?>
<?php $component = App\View\Components\ConfirmationModal::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirmation-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\ConfirmationModal::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd5922df145d522b37bf664b524be380)): ?>
<?php $attributes = $__attributesOriginalbd5922df145d522b37bf664b524be380; ?>
<?php unset($__attributesOriginalbd5922df145d522b37bf664b524be380); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd5922df145d522b37bf664b524be380)): ?>
<?php $component = $__componentOriginalbd5922df145d522b37bf664b524be380; ?>
<?php unset($__componentOriginalbd5922df145d522b37bf664b524be380); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb-plugins'); ?>

    <?php if($user->monetization_status == Status::MONETIZATION_APPLYING): ?>
        <button class="btn btn-sm btn-outline--success confirmationBtn" <?php if($user->monetization_status == Status::MONETIZATION_APPROVED): ?> disabled <?php endif; ?>
            data-action="<?php echo e(route('admin.users.monetization.approve', $user->id)); ?>" data-question="<?php echo app('translator')->get('Are you sure you want to approve this monetization request'); ?>?"><i
                class="las la-check-double"></i><?php echo app('translator')->get('Approved'); ?></button>

        <button class="btn btn-sm btn-outline--danger  confirmationBtn" <?php if($user->monetization_status == Status::MONETIZATION_CANCEL): ?> disabled <?php endif; ?>
            data-action="<?php echo e(route('admin.users.monetization.reject', $user->id)); ?>" data-question="<?php echo app('translator')->get('Are you sure you want to reject this monetization request'); ?>?"><i
                class="las la-times"></i><?php echo app('translator')->get('Reject'); ?></button>
    <?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal3b9bf6c313f6db4d5c9389e5666c89a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b9bf6c313f6db4d5c9389e5666c89a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.back','data' => ['route' => ''.e(route('admin.users.all')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('back'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => ''.e(route('admin.users.all')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b9bf6c313f6db4d5c9389e5666c89a5)): ?>
<?php $attributes = $__attributesOriginal3b9bf6c313f6db4d5c9389e5666c89a5; ?>
<?php unset($__attributesOriginal3b9bf6c313f6db4d5c9389e5666c89a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b9bf6c313f6db4d5c9389e5666c89a5)): ?>
<?php $component = $__componentOriginal3b9bf6c313f6db4d5c9389e5666c89a5; ?>
<?php unset($__componentOriginal3b9bf6c313f6db4d5c9389e5666c89a5); ?>
<?php endif; ?>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script-lib'); ?>
    <script src="<?php echo e(asset('assets/global/js/vendor/apexcharts.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/global/js/moment.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/global/js/daterangepicker.min.js')); ?>"></script>
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

        const overViewChart = (startDate, endDate) => {

            const data = {
                start_date: startDate.format('YYYY-MM-DD'),
                end_date: endDate.format('YYYY-MM-DD')
            }

            const url = <?php echo json_encode(route('admin.users.monetization.chart', $user->id), 512) ?>;


            $.get(url, data, function(data, status) {

                if (status == 'success') {

                    renderProgressChart(data)

                }
            });
        }

        let chart;

        function renderProgressChart(data) {
            var options = {
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        dataLabels: {
                            position: 'top'
                        }
                    }
                },
                dataLabels: {

                    enabled: true,
                    style: {
                        colors: ['#000']
                    },
                    formatter: function(val, opts) {
                        const seriesIndex = opts.seriesIndex;
                        return (seriesIndex === 0 ? val + " subscribers" : val + " views");
                    }
                },
                series: [{
                    name: 'Subscribers',
                    data: [data.actualSubscribers, data.targetSubscribers]
                }, {
                    name: 'Views',
                    data: [data.actualViews, data.targetViews]
                }],
                xaxis: {
                    categories: ['Actual', 'Target'],
                    max: Math.max(data.targetSubscribers, data.actualSubscribers, data.targetViews, data.actualViews) *
                        1.2
                },
                colors: ['#00E396', '#008FFB'],
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    y: {
                        formatter: function(val, {
                            seriesIndex
                        }) {
                            return seriesIndex === 0 ? `${val} subscribers` : `${val} views`;
                        }
                    }
                }
            };

            if (chart) {
                chart.destroy();
            }

            chart = new ApexCharts(document.querySelector(".overViewChart"), options);
            chart.render();
        }

        $('#dataPicker').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('#dataPicker span', start,
            end));

        changeDatePickerText('#dataPicker span', start, end);
        overViewChart(start, end);

        $('#dataPicker').on('apply.daterangepicker', (event, picker) => overViewChart(picker.startDate, picker.endDate));
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\users\monetization_detail.blade.php ENDPATH**/ ?>