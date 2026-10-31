<?php $__env->startSection('title', 'Create Portfolio'); ?>
<?php $__env->startSection('header_title', 'Marketplace Portfolios'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-24">
    <div class="flex items-center justify-between px-6 lg:px-0">
        <div>
            <h3 class="text-3xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Create Portfolio</h3>
            <p class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3">Add a new portfolio entry with multiple images</p>
        </div>
        <a href="<?php echo e(route('admin.marketplace.portfolio.index')); ?>" class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-400 hover:text-rose-500 flex items-center justify-center transition-all shadow-sm active:scale-90">
            <span class="material-symbols-rounded text-xl">close</span>
        </a>
    </div>

    <form action="<?php echo e(route('admin.marketplace.portfolio.store')); ?>" method="POST" enctype="multipart/form-data" 
          x-data="{ 
            synching: false, 
            open: false, 
            search: '', 
            selectedId: '<?php echo e(old('marketplace_id')); ?>', 
            selectedName: '<?php echo e(old('marketplace_id') ? $talents->firstWhere('id', old('marketplace_id'))?->name : 'Select Creator...'); ?>',
            previews: [],
            handleFiles(event) {
                this.previews = [];
                Array.from(event.target.files).forEach((file) => {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        this.previews.push({ url: e.target.result, file: file });
                    };
                    reader.readAsDataURL(file);
                });
            },
            removeFile(index) {
                this.previews.splice(index, 1);
                const dt = new DataTransfer();
                this.previews.forEach(p => dt.items.add(p.file));
                this.$refs.fileInput.files = dt.files;
            },
            selectTalent(id, name) {
                this.selectedId = id;
                this.selectedName = name;
                this.open = false;
                this.search = '';
            }
          }" 
          @submit="synching = true"
          class="space-y-8">
        <?php echo csrf_field(); ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Panel: User & Title -->
            <div class="lg:col-span-4 space-y-6">
                <!-- User Selection Card -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-500">
                                <span class="material-symbols-rounded text-lg">person</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Creator</h3>
                        </div>

                        <div class="space-y-2" @click.away="open = false">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Marketplace Creator</label>
                            <input type="hidden" name="marketplace_id" :value="selectedId" required>
                            
                            <div class="relative">
                                <button type="button" 
                                        @click="open = !open"
                                        class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-5 flex items-center justify-between text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                                    <span x-text="selectedName" class="truncate">Select Creator...</span>
                                    <span class="material-symbols-rounded text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
                                </button>

                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     class="absolute z-[100] mt-2 w-full bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl overflow-hidden"
                                     x-cloak>
                                    
                                    <div class="p-3 border-b border-slate-100 dark:border-white/5">
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-rounded text-slate-400 text-sm">search</span>
                                            <input type="text" x-model="search" placeholder="Search creator..." 
                                                   class="w-full h-10 bg-slate-50 dark:bg-black/20 border-none rounded-xl pl-10 pr-4 text-xs font-bold text-slate-700 dark:text-white focus:ring-1 focus:ring-indigo-500/50 transition-all">
                                        </div>
                                    </div>

                                    <div class="max-h-60 overflow-y-auto p-2 space-y-1">
                                        <?php $__currentLoopData = $talents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $talent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <button type="button"
                                                    x-show="search === '' || '<?php echo e(strtolower($talent->name)); ?>'.includes(search.toLowerCase()) || '<?php echo e(strtolower($talent->type)); ?>'.includes(search.toLowerCase())"
                                                    @click="selectTalent('<?php echo e($talent->id); ?>', '<?php echo e($talent->name); ?>')"
                                                    class="w-full text-left p-3 rounded-xl hover:bg-indigo-600 group transition-all flex items-center justify-between"
                                                    :class="selectedId == '<?php echo e($talent->id); ?>' ? 'bg-indigo-500/10' : ''">
                                                <div class="flex flex-col">
                                                    <span class="text-[11px] font-bold group-hover:text-white transition-colors"
                                                          :class="selectedId == '<?php echo e($talent->id); ?>' ? 'text-indigo-500' : 'text-slate-900 dark:text-white'"><?php echo e($talent->name); ?></span>
                                                    <span class="text-[9px] font-medium text-slate-400 dark:text-white/30 group-hover:text-white/60 transition-colors mt-0.5"><?php echo e($talent->type); ?></span>
                                                </div>
                                                <span class="material-symbols-rounded text-indigo-500 group-hover:text-white transition-all text-sm" x-show="selectedId == '<?php echo e($talent->id); ?>'">check</span>
                                            </button>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Title & Description -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                                <span class="material-symbols-rounded text-lg">edit_note</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Details</h3>
                        </div>

                        <div class="space-y-4">
                            <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'title','label' => 'Portfolio Title','placeholder' => 'e.g. Summer Campaign Shoot','required' => 'true','icon' => 'label','hint' => 'A catchy title for this portfolio entry.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'title','label' => 'Portfolio Title','placeholder' => 'e.g. Summer Campaign Shoot','required' => 'true','icon' => 'label','hint' => 'A catchy title for this portfolio entry.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginal4727f9fd7c3055c2cf9c658d89b16886 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.textarea','data' => ['name' => 'description','label' => 'Description','placeholder' => 'Describe this portfolio entry...','rows' => '6','icon' => 'description','hint' => 'Optional description of the work.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'description','label' => 'Description','placeholder' => 'Describe this portfolio entry...','rows' => '6','icon' => 'description','hint' => 'Optional description of the work.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886)): ?>
<?php $attributes = $__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886; ?>
<?php unset($__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4727f9fd7c3055c2cf9c658d89b16886)): ?>
<?php $component = $__componentOriginal4727f9fd7c3055c2cf9c658d89b16886; ?>
<?php unset($__componentOriginal4727f9fd7c3055c2cf9c658d89b16886); ?>
<?php endif; ?>
                        </div>

                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" name="status" value="1" class="sr-only" checked>
                                    <div class="block bg-slate-200 dark:bg-white/10 w-12 h-7 rounded-full transition-colors toggle-bg"></div>
                                    <div class="dot absolute left-1 top-1 bg-white w-5 h-5 rounded-full transition-transform toggle-dot shadow-sm"></div>
                                </div>
                                <span class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-widest">Active & Visible</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Image Upload -->
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-8">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-500">
                                    <span class="material-symbols-rounded text-lg">photo_library</span>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Portfolio Images</h3>
                            </div>
                        </div>

                        <!-- Upload Drop Zone -->
                        <div class="relative group" x-show="previews.length === 0">
                            <input type="file" name="images[]" multiple accept="image/*" x-ref="fileInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="handleFiles($event)" required>
                            <div class="w-full aspect-video bg-slate-50 dark:bg-white/[0.02] border-2 border-dashed border-slate-200 dark:border-white/10 rounded-2xl flex flex-col items-center justify-center gap-3 group-hover:border-indigo-500 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-500/5 transition-all">
                                <div class="w-16 h-16 rounded-2xl bg-white dark:bg-black/40 shadow-sm flex items-center justify-center text-indigo-500">
                                    <span class="material-symbols-rounded text-3xl">cloud_upload</span>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">Click or drag images to upload</p>
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Supported: JPG, PNG, GIF</p>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Grid -->
                        <div x-show="previews.length > 0" x-cloak>
                            <div class="flex justify-between items-center mb-4">
                                <p class="text-sm font-bold text-slate-500" x-text="previews.length + ' files selected'"></p>
                                <button type="button" @click="$refs.fileInput.click()" class="text-xs font-bold text-indigo-500 uppercase tracking-widest hover:text-indigo-600 transition-colors">Add More</button>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                <template x-for="(preview, index) in previews" :key="index">
                                    <div class="relative group aspect-square rounded-2xl overflow-hidden border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5">
                                        <img :src="preview.url" class="w-full h-full object-cover">
                                        <button type="button" @click.prevent="removeFile(index)" class="absolute top-2 right-2 w-8 h-8 rounded-full bg-red-600/90 backdrop-blur-sm text-white flex items-center justify-center hover:bg-red-700 hover:scale-110 transition-all shadow-lg opacity-0 group-hover:opacity-100">
                                            <span class="material-symbols-rounded text-sm">close</span>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="flex pt-4">
                            <button type="submit" :disabled="synching || !selectedId || previews.length === 0" 
                                    class="w-full h-16 rounded-2xl bg-indigo-600 text-white text-sm font-bold uppercase tracking-widest hover:bg-indigo-700 active:scale-95 transition-all flex items-center justify-center gap-3 disabled:opacity-50 disabled:grayscale disabled:cursor-not-allowed shadow-lg shadow-indigo-500/20">
                                <span x-show="!synching" class="material-symbols-rounded">check_circle</span>
                                <span x-show="synching" class="material-symbols-rounded animate-spin">sync</span>
                                <span x-text="synching ? 'Saving...' : 'Create Portfolio'">Create Portfolio</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    input:checked ~ .toggle-bg { background-color: #6366f1; }
    input:checked ~ .dot { transform: translateX(100%); }
</style>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\marketplace\portfolio\create.blade.php ENDPATH**/ ?>