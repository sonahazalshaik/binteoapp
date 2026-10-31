<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['placeholder' => 'Search...', 'btn' => 'btn--primary']));

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

foreach (array_filter((['placeholder' => 'Search...', 'btn' => 'btn--primary']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<div class="input-group w-auto flex-fill">
    <input type="search" name="search" class="form-control bg--white" placeholder="<?php echo e(__($placeholder)); ?>" value="<?php echo e(request()->search); ?>">
    <button class="btn <?php echo e($btn); ?>" type="submit"><i class="la la-search"></i></button>
</div>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\components\search-key-field.blade.php ENDPATH**/ ?>