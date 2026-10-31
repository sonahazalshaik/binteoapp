<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'link'       => null,
    'title'      => null,
    'value'      => null,
    'icon'       => '',
    'bg'         => 'primary',
    'outline'    => false,
    'heading'    => null,
    'subheading' => null,
    'viewMoreIcon'   => true
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
    'link'       => null,
    'title'      => null,
    'value'      => null,
    'icon'       => '',
    'bg'         => 'primary',
    'outline'    => false,
    'heading'    => null,
    'subheading' => null,
    'viewMoreIcon'   => true
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<a href="<?php echo e($link); ?>">
    <div class="widget-seven bg--<?php echo e($bg); ?> <?php if($outline): ?> outline <?php endif; ?>">
        <div class="widget-seven__content">
            <span class="widget-seven__content-icon">
                <span class="icon">
                    <i class="<?php echo e($icon); ?>"></i>
                </span>
            </span>
            <div class="widget-seven__description">
                <?php if($title): ?>
                <p class="widget-seven__content-title"><?php echo e(__($title)); ?></p>
                <?php endif; ?>
                <h3 class="widget-seven__content-amount"><?php echo e($value || $value === "0" || $value === 0 ? $value : __($heading)); ?></h3>
                <?php if($subheading): ?>
                <p class="widget-seven__content-subheading"><?php echo e(__($subheading)); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php if($viewMoreIcon): ?>
        <span class="widget-seven__arrow">
            <i class="fas fa-chevron-right"></i>
        </span>
        <?php endif; ?>
    </div>
</a>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\components\widget-6.blade.php ENDPATH**/ ?>