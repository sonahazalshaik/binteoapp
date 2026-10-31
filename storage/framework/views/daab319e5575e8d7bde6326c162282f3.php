<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'label' => null,
    'required' => false,
    'help' => null,
    'class' => '',
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
    'name',
    'label' => null,
    'required' => false,
    'help' => null,
    'class' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="mb-5 sm:mb-8 relative" x-data="{ focused: false }">
    <?php if($label): ?>
        <label for="<?php echo e($name); ?>" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/30 px-1 mb-2">
            <?php echo e($label); ?> <?php if($required): ?><span class="text-red-500">*</span><?php endif; ?>
        </label>
    <?php endif; ?>
    
    <div class="relative">
        <?php if($attributes->has('icon')): ?>
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-700 dark:text-white/80 transition-colors z-10 pointer-events-none" :class="focused ? '!text-orange-500' : ''">
                <span class="material-symbols-rounded text-xl">
                    <?php echo e($attributes->get('icon')); ?>

                </span>
            </div>
        <?php endif; ?>

        <select 
            name="<?php echo e($name); ?>" 
            id="<?php echo e($name); ?>"
            @focus="focused = true"
            @blur="focused = false"
            <?php if($required): ?> required <?php endif; ?>
            <?php echo e($attributes->merge(['class' => 'w-full h-12 sm:h-16 ' . ($attributes->has('icon') ? 'pl-12' : 'px-6') . ' pr-12 rounded-2xl border transition-all duration-300 outline-none appearance-none bg-none bg-white dark:bg-black/20 text-[13px] font-black text-slate-900 dark:text-white ' . ($errors->has($name) ? 'border-red-500 bg-red-50 dark:bg-red-900/10 focus:ring-4 focus:ring-red-500/10' : 'border-slate-200/60 dark:border-white/5 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10')])); ?>

            style="background-image: none !important; -webkit-appearance: none; -moz-appearance: none; appearance: none;"
        >
            <?php echo e($slot); ?>

        </select>
        
        <!-- Premium Chevron -->
        <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-700 dark:text-white/80">
            <span class="material-symbols-rounded text-xl">expand_more</span>
        </div>
    </div>

    <?php if($attributes->has('hint')): ?>
        <div x-show="focused" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="mt-2 text-[10px] font-bold text-orange-500 uppercase tracking-widest flex items-center gap-1">
            <span class="material-symbols-rounded text-xs">lightbulb</span>
            <?php echo e($attributes->get('hint')); ?>

        </div>
    <?php endif; ?>

    <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="mt-1 text-xs text-red-500 font-medium flex items-center gap-1">
            <span class="material-symbols-rounded text-sm">error</span>
            <?php echo e($message); ?>

        </div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

    <?php if($help): ?>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400"><?php echo e($help); ?></p>
    <?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\components\select.blade.php ENDPATH**/ ?>