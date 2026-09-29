@props([
    'imagePath' => '',
    'size' => '',
    'name' => 'image',
    'id' => 'image-upload',
    'required' => true,
    'class' => '',
])

<div class="image-upload-wrapper {{ $class }}">
    <div class="image-upload-preview-wrapper relative group">
        {{-- Actual Preview Div --}}
        <div class="image-upload-preview w-full h-[250px] rounded-[2rem] bg-slate-100 dark:bg-white/5 border-2 border-dashed border-slate-200 dark:border-white/10 flex items-center justify-center overflow-hidden transition-all duration-500 group-hover:border-orange-500/50" 
             style="background-image: url('{{ $imagePath }}'); background-size: cover; background-position: center;">
            
            {{-- Placeholder if no image --}}
            @if(!$imagePath || str_contains($imagePath, 'placeholder'))
            <div class="text-center opacity-40 group-hover:opacity-100 transition-opacity">
                <span class="material-symbols-rounded text-5xl mb-2 text-slate-400">cloud_upload</span>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Click to Upload</p>
            </div>
            @else
            {{-- Existing image as <img> (same rendering as list pages, reliable for long signed R2 URLs) --}}
            <img src="{{ $imagePath }}" alt="" class="preview-existing-img absolute inset-0 w-full h-full object-fill" onerror="this.remove()">
            @endif

            {{-- Action Buttons --}}
            <div class="absolute inset-x-0 bottom-0 bg-black/40 backdrop-blur-md p-4 flex items-center justify-center gap-4 transition-all duration-300">
                <label for="{{ $id }}" class="w-10 h-10 rounded-xl bg-white text-slate-900 flex items-center justify-center cursor-pointer hover:scale-110 active:scale-95 transition-all shadow-lg">
                    <span class="material-symbols-rounded text-xl">edit</span>
                </label>
                @if($imagePath && !str_contains($imagePath, 'placeholder') && !str_contains($imagePath, 'default.png'))
                <button type="button" class="w-10 h-10 rounded-xl bg-rose-500 text-white flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-lg remove-image">
                    <span class="material-symbols-rounded text-xl">delete</span>
                </button>
                @endif
            </div>
        </div>

        {{-- File Input --}}
        <input type="file" name="{{ $name }}" id="{{ $id }}" class="hidden image-upload-input" accept=".png, .jpg, .jpeg" @if($required && !$imagePath) required @endif>
        <input type="hidden" name="should_remove_{{ $name }}" value="0" class="remove-image-flag">
    </div>

    {{-- Info Footer --}}
    @if($size)
    <div class="mt-4 flex items-center justify-between px-2">
        <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Recommended Size</span>
        <span class="text-[9px] font-black uppercase tracking-widest text-orange-500 bg-orange-500/10 px-3 py-1 rounded-full border border-orange-500/20">{{ $size }}</span>
    </div>
    @endif
</div>

@once
@push('script')
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
@endpush
@endonce

@push('style')
<style>
    .image-upload-preview {
        background-repeat: no-repeat;
    }
</style>
@endpush
