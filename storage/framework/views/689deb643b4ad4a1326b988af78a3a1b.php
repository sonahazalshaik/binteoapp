<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'link' => null,
    'title' => null,
    'value' => null,
    'icon' => '',
    'bg' => 'primary',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'link' => null,
    'title' => null,
    'value' => null,
    'icon' => '',
    'bg' => 'primary',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="widget-six bg--white p-3 rounded-2 box-shadow3">
    <div class="widget-six__top">
        <i class="<?php echo e($icon); ?> bg--<?php echo e($bg); ?> text--white b-radius--5"></i>
        <p><?php echo e(__($title)); ?></p>
    </div>
    <div class="widget-six__bottom mt-3">
        <h4 class="widget-six__number"><?php echo e($value); ?></h4>
        <a href="<?php echo e($link); ?>" class="widget-six__btn"><span class="text--small"><?php echo app('translator')->get('View All'); ?></span><i class="las la-arrow-right"></i></a>
    </div>
</div>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\components\widget-5.blade.php ENDPATH**/ ?>