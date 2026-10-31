<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'link' => null,
    'title' => null,
    'value' => null,
    'icon' => '',
    'bg' => 'primary',
    'type'=>1
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
    'type'=>1
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<a href="<?php echo e($link); ?>" class="widget-eight bg--<?php echo e($bg); ?> <?php if($type == 2): ?> style-two <?php endif; ?>">
    <div class="widget-eight__description">
        <p class="widget-eight__content-title"><?php echo e(__($title)); ?></p>
        <h3 class="widget-eight__content-amount"><?php echo e($value); ?></h3>
    </div>
    <span class="widget-eight__content-icon">
        <span class="icon">
            <i class="<?php echo e($icon); ?>"></i>
        </span>
    </span>
</a>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\components\widget-7.blade.php ENDPATH**/ ?>