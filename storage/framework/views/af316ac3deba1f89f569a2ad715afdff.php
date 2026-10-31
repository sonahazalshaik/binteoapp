<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'placeholder' => 'Search...',
    'btn' => 'btn--primary',
    'dateSearch' => 'no',
    'keySearch' => 'yes',
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
    'placeholder' => 'Search...',
    'btn' => 'btn--primary',
    'dateSearch' => 'no',
    'keySearch' => 'yes',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<form class="d-flex flex-wrap gap-2">
    <?php if($keySearch == 'yes'): ?>
        <?php if (isset($component)) { $__componentOriginal4ac0317ad52c5e8be0b5ddc295816791 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ac0317ad52c5e8be0b5ddc295816791 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-key-field','data' => ['placeholder' => ''.e($placeholder).'','btn' => ''.e($btn).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('search-key-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => ''.e($placeholder).'','btn' => ''.e($btn).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ac0317ad52c5e8be0b5ddc295816791)): ?>
<?php $attributes = $__attributesOriginal4ac0317ad52c5e8be0b5ddc295816791; ?>
<?php unset($__attributesOriginal4ac0317ad52c5e8be0b5ddc295816791); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ac0317ad52c5e8be0b5ddc295816791)): ?>
<?php $component = $__componentOriginal4ac0317ad52c5e8be0b5ddc295816791; ?>
<?php unset($__componentOriginal4ac0317ad52c5e8be0b5ddc295816791); ?>
<?php endif; ?>
    <?php endif; ?>
    <?php if($dateSearch == 'yes'): ?>
        <?php if (isset($component)) { $__componentOriginal00daea293777b493a0f7b779e8aa92fe = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal00daea293777b493a0f7b779e8aa92fe = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-date-field','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('search-date-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal00daea293777b493a0f7b779e8aa92fe)): ?>
<?php $attributes = $__attributesOriginal00daea293777b493a0f7b779e8aa92fe; ?>
<?php unset($__attributesOriginal00daea293777b493a0f7b779e8aa92fe); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal00daea293777b493a0f7b779e8aa92fe)): ?>
<?php $component = $__componentOriginal00daea293777b493a0f7b779e8aa92fe; ?>
<?php unset($__componentOriginal00daea293777b493a0f7b779e8aa92fe); ?>
<?php endif; ?>
    <?php endif; ?>

</form>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\components\search-form.blade.php ENDPATH**/ ?>