<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'imagePath' => '',
    'size' => '',
    'name' => 'image',
    'id' => 'image-upload',
    'required' => true,
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
    'imagePath' => '',
    'size' => '',
    'name' => 'image',
    'id' => 'image-upload',
    'required' => true,
    'class' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="image-upload-wrapper <?php echo e($class); ?>">
    <div class="image-upload-preview-wrapper relative group">
        
        <div class="image-upload-preview w-full h-[250px] rounded-[2rem] bg-slate-100 dark:bg-white/5 border-2 border-dashed border-slate-200 dark:border-white/10 flex items-center justify-center overflow-hidden transition-all duration-500 group-hover:border-orange-500/50" 
             style="background-image: url('<?php echo e($imagePath); ?>'); background-size: cover; background-position: center;">
            
            
            <?php if(!$imagePath || str_contains($imagePath, 'placeholder')): ?>
            <div class="text-center opacity-40 group-hover:opacity-100 transition-opacity">
                <span class="material-symbols-rounded text-5xl mb-2 text-slate-400">cloud_upload</span>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Click to Upload</p>
            </div>
            <?php else: ?>
            
            <img src="<?php echo e($imagePath); ?>" alt="" class="preview-existing-img absolute inset-0 w-full h-full object-fill" onerror="this.remove()">
            <?php endif; ?>

            
            <div class="absolute inset-x-0 bottom-0 bg-black/40 backdrop-blur-md p-4 flex items-center justify-center gap-4 transition-all duration-300">
                <label for="<?php echo e($id); ?>" class="w-10 h-10 rounded-xl bg-white text-slate-900 flex items-center justify-center cursor-pointer hover:scale-110 active:scale-95 transition-all shadow-lg">
                    <span class="material-symbols-rounded text-xl">edit</span>
                </label>
                <?php if($imagePath && !str_contains($imagePath, 'placeholder') && !str_contains($imagePath, 'default.png')): ?>
                <button type="button" class="w-10 h-10 rounded-xl bg-rose-500 text-white flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-lg remove-image">
                    <span class="material-symbols-rounded text-xl">delete</span>
                </button>
                <?php endif; ?>
            </div>
        </div>

        
        <input type="file" name="<?php echo e($name); ?>" id="<?php echo e($id); ?>" class="hidden image-upload-input" accept=".png, .jpg, .jpeg" <?php if($required && !$imagePath): ?> required <?php endif; ?>>
        <input type="hidden" name="should_remove_<?php echo e($name); ?>" value="0" class="remove-image-flag">
    </div>

    
    <?php if($size): ?>
    <div class="mt-4 flex items-center justify-between px-2">
        <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Recommended Size</span>
        <span class="text-[9px] font-black uppercase tracking-widest text-orange-500 bg-orange-500/10 px-3 py-1 rounded-full border border-orange-500/20"><?php echo e($size); ?></span>
    </div>
    <?php endif; ?>
</div>

<?php if (! $__env->hasRenderedOnce('7c79c72b-35ef-41dc-98d5-11954998823d')): $__env->markAsRenderedOnce('7c79c72b-35ef-41dc-98d5-11954998823d'); ?>
<?php $__env->startPush('script'); ?>
<script>
    (function($){
        "use strict";

        $(document).on('change', '.image-upload-input', function() {
            const file = this.files[0];
            const wrapper = $(this).closest('.image-upload-wrapper');
            const removeFlag = wrapper.find('.remove-image-flag');
            
            if (file) {
                removeFlag.val('0');
                const reader = new FileReader();
                const preview = wrapper.find('.image-upload-preview');
                reader.onload = function(e) {
                    preview.find('.preview-existing-img').remove();
                    preview.css('background-image', `url(${e.target.result})`);
                    preview.find('.text-center').addClass('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        $(document).on('click', '.remove-image', function(e) {
            e.preventDefault();
            const $btn = $(this);
            const wrapper = $btn.closest('.image-upload-wrapper');
            const preview = wrapper.find('.image-upload-preview');
            const input = wrapper.find('.image-upload-input');
            const removeFlag = wrapper.find('.remove-image-flag');

            const performRemoval = () => {
                preview.css('background-image', 'none');
                preview.find('.preview-existing-img').remove();
                preview.find('.text-center').removeClass('hidden');
                input.val('');
                removeFlag.val('1');
                $btn.fadeOut(300, function() {
                    $(this).addClass('hidden');
                });
            };

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Remove Image?',
                    text: "This will clear the current image. You must save changes to commit.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, remove it!',
                    cancelButtonText: 'Cancel',
                    background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                }).then((result) => {
                    if (result.isConfirmed) {
                        performRemoval();
                        Swal.fire({
                            title: 'Removed!',
                            text: 'Image cleared from preview.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false,
                            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                        });
                    }
                });
            } else {
                if (confirm('Are you sure you want to remove this image?')) {
                    performRemoval();
                }
            }
        });
    })(jQuery);
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>

<?php $__env->startPush('style'); ?>
<style>
    .image-upload-preview {
        background-repeat: no-repeat;
    }
</style>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\components\image-uploader.blade.php ENDPATH**/ ?>