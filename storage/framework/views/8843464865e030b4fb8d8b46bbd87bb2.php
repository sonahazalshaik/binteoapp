<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'link' => null,
    'title' => null,
    'value' => null,
    'bg' => 'primary',
    'color' => 'white',
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
    'bg' => 'primary',
    'color' => 'white',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="widget-two box--shadow2 b-radius--5 bg--<?php echo e($bg); ?> has-link">
    <?php if($link): ?>
        <a href="<?php echo e($link); ?>" class="item-link"></a>
    <?php endif; ?>

    <div class="widget-two__content">
        <h2 class="text-<?php echo e($color); ?>"><?php echo e($value); ?></h2>
        <p class="text-<?php echo e($color); ?>"><?php echo e(__($title)); ?></p>
    </div>
</div>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\components\widget-4.blade.php ENDPATH**/ ?>