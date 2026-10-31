<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'help' => null,
    'class' => '',
    'errorBag' => null,
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
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'help' => null,
    'class' => '',
    'errorBag' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="mb-5 sm:mb-8">
    <?php if($label): ?>
        <label for="<?php echo e($name); ?>" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/30 px-1 mb-2">
            <?php echo e($label); ?> <?php if($required): ?><span class="text-red-500">*</span><?php endif; ?>
        </label>
    <?php endif; ?>
    
    <div class="relative" x-data="{ focused: false, showPassword: false }">
        <div class="relative">
            <?php if($attributes->has('icon')): ?>
                <div class="absolute left-4 top-0 h-12 sm:h-16 flex items-center text-gray-400 dark:text-gray-300 transition-colors z-10 pointer-events-none" :class="focused ? '!text-orange-500' : ''">
                    <?php if(str_starts_with($attributes->get('icon'), 'la ')): ?>
                        <i class="<?php echo e($attributes->get('icon')); ?> text-xl"></i>
                    <?php else: ?>
                        <span class="material-symbols-rounded text-xl">
                            <?php echo e($attributes->get('icon')); ?>

                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <input 
                :type="showPassword ? 'text' : '<?php echo e($type); ?>'" 
                name="<?php echo e($name); ?>" 
                id="<?php echo e($name); ?>"
                value="<?php echo e(old($name, $value)); ?>"
                placeholder="<?php echo e($placeholder); ?>"
                @focus="focused = true"
                @blur="focused = false"
                <?php if($required): ?> required <?php endif; ?>
                <?php echo e($attributes->merge(['class' => 'w-full h-12 sm:h-16 ' . ($attributes->has('icon') ? 'pl-12' : 'px-6') . ' pr-12 rounded-2xl border transition-all duration-300 outline-none text-[13px] font-black text-slate-900 dark:text-white ' . ($errors->getBag($errorBag ?? 'default')->has($name) ? 'border-red-500 bg-red-50 dark:bg-red-900/10 focus:ring-4 focus:ring-red-500/10' : 'border-slate-200/60 dark:border-white/5 bg-white dark:bg-black/20 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10')])); ?>

            >

            <?php if($type === 'password'): ?>
                <button type="button" @click="showPassword = !showPassword" tabindex="-1" class="absolute right-4 top-0 h-12 sm:h-16 flex items-center text-slate-400 hover:text-orange-500 transition-colors focus:outline-none">
                    <span class="material-symbols-rounded text-xl" x-text="showPassword ? 'visibility_off' : 'visibility'">visibility</span>
                </button>
            <?php endif; ?>

            <?php if(isset($append)): ?>
                <?php echo e($append); ?>

            <?php endif; ?>
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

        <?php if($errors->getBag($errorBag ?? 'default')->has($name)): ?>
            <div class="mt-1 text-xs text-red-500 font-medium flex items-center gap-1">
                <span class="material-symbols-rounded text-sm">error</span>
                <?php echo e($errors->getBag($errorBag ?? 'default')->first($name)); ?>

            </div>
        <?php endif; ?>
    </div>

    <?php if($help): ?>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400"><?php echo e($help); ?></p>
    <?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\components\input.blade.php ENDPATH**/ ?>