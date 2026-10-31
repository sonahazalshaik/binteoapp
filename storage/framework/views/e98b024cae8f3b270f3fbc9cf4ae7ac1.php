<?php $__env->startSection('panel'); ?>
    <div class="max-w-7xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-24">
        <!-- Main Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-6 lg:px-0">
            <div>
                <h3 class="text-3xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none"><?php echo e(__($pageTitle)); ?></h3>
                <p class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3">Frontend Architecture Node</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <?php if(@$section->element && $key != 'policy_pages'): ?>
                    <?php if($section->element->modal): ?>
                        <button type="button" class="addBtn h-12 px-6 rounded-2xl bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest flex items-center gap-2 hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20 active:scale-95">
                            <span class="material-symbols-rounded text-lg">add_circle</span> Add New Item
                        </button>
                    <?php else: ?>
                        <a href="<?php echo e(route('admin.frontend.sections.element', $key)); ?>" class="h-12 px-6 rounded-2xl bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest flex items-center gap-2 hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20 active:scale-95">
                            <span class="material-symbols-rounded text-lg">add_circle</span> Add New Item
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if(!empty($templates)): ?>
                    <div class="flex items-center bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl p-1 px-3 h-12 shadow-sm">
                        <form action="<?php echo e(route('admin.frontend.import', $key)); ?>" method="post" class="flex items-center gap-2">
                            <?php echo csrf_field(); ?>
                            <select name="template_name" class="bg-transparent border-none text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-white/40 focus:ring-0 cursor-pointer h-full py-0">
                                <option value=""><?php echo app('translator')->get('Select Template'); ?></option>
                                <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($template['name']); ?>"><?php echo e(__(keyToTitle($template['name']))); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <button type="submit" class="w-8 h-8 rounded-xl bg-orange-500 text-white flex items-center justify-center hover:bg-orange-600 transition-all active:scale-90 shadow-sm">
                                <span class="material-symbols-rounded text-sm">download</span>
                            </button>
                        </form>
                    </div>
                <?php endif; ?>

                <?php if(!@$section->hide_builder): ?>
                    <a href="<?php echo e(route('admin.frontend.index')); ?>" class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-400 hover:text-rose-500 flex items-center justify-center transition-all shadow-sm active:scale-90" title="Back to Sections">
                        <span class="material-symbols-rounded text-xl">arrow_back</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <?php if(@$section->content): ?>
            <!-- Content Management Hub -->
            <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2.5rem] p-10 shadow-sm relative overflow-hidden">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-orange-500/5 rounded-full blur-3xl pointer-events-none"></div>
                
                <form action="<?php echo e(route('admin.frontend.sections.content', $key)); ?>" class="disableSubmission space-y-10 relative z-10" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="type" value="content">
                    
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                        <?php
                            $images = collect($section->content)->filter(fn($item, $k) => $k === 'images' || (is_object($item) && @$item->type === 'image') || (is_array($item) && @$item['type'] === 'image'));
                        ?>

                        <?php if($images->count() > 0): ?>
                            <!-- Visual Assets Panel -->
                            <div class="lg:col-span-4 space-y-8">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 rounded-lg bg-orange-500/10 flex items-center justify-center text-orange-500">
                                        <span class="material-symbols-rounded text-lg">image</span>
                                    </div>
                                    <h4 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">Visual Assets</h4>
                                </div>

                                <?php $__currentLoopData = $section->content; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($k == 'images'): ?>
                                        <?php $__currentLoopData = $item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $imgKey => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="space-y-3">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1"><?php echo e(__(keyToTitle(@$imgKey))); ?></label>
                                                <input type="hidden" name="has_image" value="1">
                                                <?php if (isset($component)) { $__componentOriginaldbcc027cdd3569f61821c56d10b77c01 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldbcc027cdd3569f61821c56d10b77c01 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.image-uploader','data' => ['class' => 'w-full','name' => 'image_input['.e(@$imgKey).']','imagePath' => frontendImage($key,@$content->data_values->$imgKey,@$section->content->images->$imgKey->size),'id' => 'image-upload-input'.e($loop->index).'','size' => $section->content->images->$imgKey->size,'required' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('image-uploader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-full','name' => 'image_input['.e(@$imgKey).']','imagePath' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(frontendImage($key,@$content->data_values->$imgKey,@$section->content->images->$imgKey->size)),'id' => 'image-upload-input'.e($loop->index).'','size' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($section->content->images->$imgKey->size),'required' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldbcc027cdd3569f61821c56d10b77c01)): ?>
<?php $attributes = $__attributesOriginaldbcc027cdd3569f61821c56d10b77c01; ?>
<?php unset($__attributesOriginaldbcc027cdd3569f61821c56d10b77c01); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldbcc027cdd3569f61821c56d10b77c01)): ?>
<?php $component = $__componentOriginaldbcc027cdd3569f61821c56d10b77c01; ?>
<?php unset($__componentOriginaldbcc027cdd3569f61821c56d10b77c01); ?>
<?php endif; ?>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php elseif((is_object($item) && @$item->type == 'image') || (is_array($item) && @$item['type'] == 'image')): ?>
                                        <div class="space-y-3">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1"><?php echo e(__(keyToTitle($k))); ?></label>
                                            <input type="hidden" name="has_image" value="1">
                                            <?php if (isset($component)) { $__componentOriginaldbcc027cdd3569f61821c56d10b77c01 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldbcc027cdd3569f61821c56d10b77c01 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.image-uploader','data' => ['class' => 'w-full','name' => 'image_input['.e($k).']','imagePath' => frontendImage($key,@$content->data_values->$k,@$item->size),'id' => 'image-upload-input-'.e($k).'','size' => @$item->size,'required' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('image-uploader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-full','name' => 'image_input['.e($k).']','imagePath' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(frontendImage($key,@$content->data_values->$k,@$item->size)),'id' => 'image-upload-input-'.e($k).'','size' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(@$item->size),'required' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldbcc027cdd3569f61821c56d10b77c01)): ?>
<?php $attributes = $__attributesOriginaldbcc027cdd3569f61821c56d10b77c01; ?>
<?php unset($__attributesOriginaldbcc027cdd3569f61821c56d10b77c01); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldbcc027cdd3569f61821c56d10b77c01)): ?>
<?php $component = $__componentOriginaldbcc027cdd3569f61821c56d10b77c01; ?>
<?php unset($__componentOriginaldbcc027cdd3569f61821c56d10b77c01); ?>
<?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Text Content Panel -->
                        <div class="<?php echo e($images->count() > 0 ? 'lg:col-span-8' : 'lg:col-span-12'); ?> space-y-8">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-500">
                                    <span class="material-symbols-rounded text-lg">edit_note</span>
                                </div>
                                <h4 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">Content Registry</h4>
                            </div>

                            <div class="grid grid-cols-1 gap-8">
                                <?php $__currentLoopData = $section->content; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        if (is_array($item)) { $item = (object) $item; }
                                        $type = is_object($item) ? @$item->type : $item;
                                    ?>
                                    <?php if($k != 'images' && $type != 'image'): ?>
                                        <?php if($item == 'icon'): ?>
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1"><?php echo e(__(keyToTitle($k))); ?></label>
                                                <div class="flex gap-2">
                                                    <div class="relative flex-grow">
                                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-rounded text-slate-400">category</span>
                                                        <input type="text" class="w-full h-16 pl-12 pr-4 rounded-2xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-black/20 text-sm font-bold text-slate-700 dark:text-white outline-none focus:border-orange-500 transition-all iconPicker icon" autocomplete="off" name="<?php echo e($k); ?>" value="<?php echo e(@$content->data_values->$k); ?>" required>
                                                    </div>
                                                    <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-2xl text-slate-500 border border-slate-200 dark:border-white/10" data-icon="las la-home" role="iconpicker">
                                                        <?php echo @$content->data_values->$k; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php elseif($type == 'textarea'): ?>
                                            <?php if (isset($component)) { $__componentOriginal4727f9fd7c3055c2cf9c658d89b16886 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.textarea','data' => ['name' => ''.e($k).'','label' => ''.e(__(keyToTitle($k))).'','rows' => '6','icon' => 'notes','required' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => ''.e($k).'','label' => ''.e(__(keyToTitle($k))).'','rows' => '6','icon' => 'notes','required' => 'true']); ?><?php echo e(@$content->data_values->$k); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886)): ?>
<?php $attributes = $__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886; ?>
<?php unset($__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4727f9fd7c3055c2cf9c658d89b16886)): ?>
<?php $component = $__componentOriginal4727f9fd7c3055c2cf9c658d89b16886; ?>
<?php unset($__componentOriginal4727f9fd7c3055c2cf9c658d89b16886); ?>
<?php endif; ?>
                                        <?php elseif($type == 'textarea-nic' || $type == 'textarea-ck'): ?>
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1"><?php echo e(__(keyToTitle($k))); ?></label>
                                                <textarea name="<?php echo e($k); ?>" class="ckEditor"><?php echo e(@$content->data_values->$k); ?></textarea>
                                            </div>
                                        <?php elseif($k == 'select'): ?>
                                            <?php $selectName = $item->name; ?>
                                            <?php if (isset($component)) { $__componentOriginaled2cde6083938c436304f332ba96bb7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled2cde6083938c436304f332ba96bb7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['name' => ''.e(@$selectName).'','label' => ''.e(__(keyToTitle(@$selectName))).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => ''.e(@$selectName).'','label' => ''.e(__(keyToTitle(@$selectName))).'']); ?>
                                                <?php $__currentLoopData = $item->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $selectItemKey => $selectOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($selectItemKey); ?>" <?php if(@$content->data_values->$selectName == $selectItemKey): ?> selected <?php endif; ?>><?php echo e($selectOption); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaled2cde6083938c436304f332ba96bb7c)): ?>
<?php $attributes = $__attributesOriginaled2cde6083938c436304f332ba96bb7c; ?>
<?php unset($__attributesOriginaled2cde6083938c436304f332ba96bb7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaled2cde6083938c436304f332ba96bb7c)): ?>
<?php $component = $__componentOriginaled2cde6083938c436304f332ba96bb7c; ?>
<?php unset($__componentOriginaled2cde6083938c436304f332ba96bb7c); ?>
<?php endif; ?>
                                        <?php else: ?>
                                            <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => ''.e($k).'','label' => ''.e(__(keyToTitle($k))).'','value' => @$content->data_values->$k,'required' => 'true','icon' => 'label']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => ''.e($k).'','label' => ''.e(__(keyToTitle($k))).'','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(@$content->data_values->$k),'required' => 'true','icon' => 'label']); ?>
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
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <div class="flex pt-6 border-t border-slate-100 dark:border-white/5">
                                <button type="submit" class="w-full h-16 rounded-2xl bg-orange-600 text-white text-sm font-black uppercase tracking-widest hover:bg-orange-700 active:scale-95 transition-all flex items-center justify-center gap-3 shadow-xl shadow-orange-500/20">
                                    <span class="material-symbols-rounded">check_circle</span>
                                    Commit Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <?php if(@$section->element): ?>
            <!-- Element Registry Section -->
            <div class="space-y-6">
                <div class="flex items-center justify-between px-6 lg:px-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                            <span class="material-symbols-rounded text-lg">view_list</span>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tighter ">Element Repository</h3>
                    </div>
                </div>

                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-sm transition-all duration-300">
                    <div class="overflow-x-auto scrollbar-hide">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                                    <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">#</th>
                                    <?php if(@$section->element->images): ?>
                                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Visual</th>
                                    <?php endif; ?>
                                    <?php $__currentLoopData = $section->element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($k != 'modal' && $k != 'seo' && $k != 'images'): ?>
                                            <?php if($type == 'text' || $type == 'icon'): ?>
                                                <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30"><?php echo e(__(keyToTitle($k))); ?></th>
                                            <?php elseif($k == 'select'): ?>
                                                <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30"><?php echo e(keyToTitle(@$section->element->$k->name)); ?></th>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                <?php $__empty_1 = true; $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="px-8 py-6 text-xs font-black text-slate-400"><?php echo e($loop->iteration); ?></td>
                                        <?php if(@$section->element->images): ?>
                                            <?php $firstKey = collect($section->element->images)->keys()[0]; ?>
                                            <td class="px-8 py-6">
                                                <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-white/5 overflow-hidden border border-slate-200 dark:border-white/10 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                                                    <img src="<?php echo e(frontendImage($key,@$data->data_values->$firstKey,@$section->element->images->$firstKey->size)); ?>" class="w-full h-full object-cover">
                                                </div>
                                            </td>
                                        <?php endif; ?>
                                        <?php $__currentLoopData = $section->element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($k != 'modal' && $k != 'seo' && $k != 'images'): ?>
                                                <td class="px-8 py-6">
                                                    <?php if($type == 'icon'): ?>
                                                        <span class="text-xl text-blue-500"><?php echo @$data->data_values->$k; ?></span>
                                                    <?php elseif($k == 'select'): ?>
                                                        <?php $dataVal = @$section->element->$k->name; ?>
                                                        <span class="text-[11px] font-bold text-slate-700 dark:text-white/70"><?php echo e(@$data->data_values->$dataVal); ?></span>
                                                    <?php else: ?>
                                                        <span class="text-[11px] font-bold text-slate-700 dark:text-white/70 leading-relaxed"><?php echo e(strLimit(strip_tags(__($data->data_values->$k)), 50)); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <td class="px-8 py-6 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <?php if(@$section->element->seo): ?>
                                                    <a href="<?php echo e(route('admin.frontend.sections.element.seo', [$key,$data->id])); ?>" class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center hover:bg-indigo-500 hover:text-white transition-all shadow-sm" title="SEO Settings">
                                                        <span class="material-symbols-rounded text-lg">travel_explore</span>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if($section->element->modal): ?>
                                                    <button type="button" class="updateBtn w-10 h-10 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center hover:bg-blue-500 hover:text-white transition-all shadow-sm" data-id="<?php echo e($data->id); ?>" data-all="<?php echo e(json_encode($data->data_values)); ?>" <?php if(@$section->element->images): ?> data-images="<?php echo e(json_encode(collect($section->element->images)->map(fn($v, $k) => frontendImage($key,@$data->data_values->$k,$v->size))->values())); ?>" <?php endif; ?> title="Edit Element">
                                                        <span class="material-symbols-rounded text-lg">edit</span>
                                                    </button>
                                                <?php else: ?>
                                                    <a href="<?php echo e(route('admin.frontend.sections.element', [$key, $data->id])); ?>" class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center hover:bg-blue-500 hover:text-white transition-all shadow-sm" title="Edit Element">
                                                        <span class="material-symbols-rounded text-lg">edit</span>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if($key != 'policy_pages'): ?>
                                                    <button type="button" class="confirmationBtn w-10 h-10 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all shadow-sm" data-action="<?php echo e(route('admin.frontend.remove', $data->id)); ?>" data-question="<?php echo app('translator')->get('Are you sure to remove this item?'); ?>" title="Delete Item">
                                                        <span class="material-symbols-rounded text-lg">delete</span>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="100%" class="px-8 py-24 text-center">
                                            <div class="w-24 h-24 bg-slate-50 dark:bg-white/[0.02] rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-inner">
                                                <span class="material-symbols-rounded text-slate-300 dark:text-white/10 text-5xl">folder_off</span>
                                            </div>
                                            <h5 class="text-sm font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.3em] mb-6">No elements detected in this section</h5>
                                            <?php if($key != 'policy_pages'): ?>
                                                <button type="button" class="addBtn h-12 px-8 rounded-2xl bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-xl shadow-blue-500/30 active:scale-95">
                                                    Initialize First Element
                                                </button>
                                            <?php endif; ?>>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if(@$section->element && $section->element->modal): ?>
        <!-- Add Item Modal -->
        <div id="addModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered max-w-[360px] sm:max-w-[420px] md:max-w-[600px] lg:max-w-[740px]">
                <div class="modal-content !bg-white dark:!bg-[#121212] !rounded-2xl !border-none shadow-2xl">
                    <div class="p-4 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                        <h5 class="text-sm font-black text-slate-900 dark:text-white">Add <?php echo e(__(keyToTitle($key))); ?></h5>
                        <button type="button" class="w-7 h-7 rounded-lg hover:bg-slate-100 dark:hover:bg-white/5 text-slate-400 transition-all flex items-center justify-center" data-bs-dismiss="modal">
                            <span class="material-symbols-rounded text-lg">close</span>
                        </button>
                    </div>
                    <?php
                        $ckKeys = [];
                        $otherKeys = [];
                        foreach ($section->element as $fk => $fv) {
                            if ($fk === 'modal') continue;
                            $isTypeObj = is_object($fv) || is_array($fv);
                            $ft = $isTypeObj ? (is_array($fv) ? ($fv['type'] ?? 'text') : ($fv->type ?? 'text')) : $fv;
                            if ($ft === 'textarea-nic' || $ft === 'textarea-ck') {
                                $ckKeys[] = $fk;
                            } else {
                                $otherKeys[] = $fk;
                            }
                        }
                    ?>
                    <form action="<?php echo e(route('admin.frontend.sections.content', $key)); ?>" class="disableSubmission" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="type" value="element">
                        <div class="p-4 space-y-4 max-h-[50vh] overflow-y-auto custom-scrollbar">
                            <?php $__currentLoopData = $otherKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $type = $section->element->$k;
                                    $isObj = is_object($type) || is_array($type);
                                    if ($isObj) {
                                        $type = is_array($type) ? (object) $type : $type;
                                        $fieldType = $type->type ?? 'text';
                                        $fieldLabel = $type->label ?? keyToTitle($k);
                                    } else {
                                        $fieldType = $type;
                                        $fieldLabel = keyToTitle($k);
                                    }
                                ?>
                                <?php if($fieldType == 'icon'): ?>
                                    <div class="space-y-1.5">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1"><?php echo e(__($fieldLabel)); ?></label>
                                        <div class="flex gap-2">
                                            <input type="text" class="w-full h-11 px-4 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-black/20 text-sm font-bold text-slate-700 dark:text-white iconPicker icon" autocomplete="off" name="<?php echo e($k); ?>" required>
                                            <span class="w-11 h-11 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-500 border border-slate-200 dark:border-white/10" data-icon="las la-home" role="iconpicker"><i class="las la-home"></i></span>
                                        </div>
                                    </div>
                                <?php elseif($fieldType == 'checkbox'): ?>
                                    <div class="flex items-center gap-3 py-2">
                                        <input type="hidden" name="<?php echo e($k); ?>" value="0">
                                        <input type="checkbox" name="<?php echo e($k); ?>" value="1" class="w-5 h-5 rounded-lg border-slate-300 dark:border-white/20 text-blue-600 focus:ring-blue-500">
                                        <label class="text-[10px] font-black text-slate-500 dark:text-white/50 uppercase tracking-widest select-none"><?php echo e(__($fieldLabel)); ?></label>
                                    </div>
                                <?php elseif($k == 'select'): ?>
                                    <?php if (isset($component)) { $__componentOriginaled2cde6083938c436304f332ba96bb7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled2cde6083938c436304f332ba96bb7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['name' => ''.e(@$section->element->$k->name).'','label' => ''.e(keyToTitle(@$section->element->$k->name)).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => ''.e(@$section->element->$k->name).'','label' => ''.e(keyToTitle(@$section->element->$k->name)).'']); ?>
                                        <?php $__currentLoopData = $section->element->$k->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $selectKey => $options): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($selectKey); ?>"><?php echo e($options); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaled2cde6083938c436304f332ba96bb7c)): ?>
<?php $attributes = $__attributesOriginaled2cde6083938c436304f332ba96bb7c; ?>
<?php unset($__attributesOriginaled2cde6083938c436304f332ba96bb7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaled2cde6083938c436304f332ba96bb7c)): ?>
<?php $component = $__componentOriginaled2cde6083938c436304f332ba96bb7c; ?>
<?php unset($__componentOriginaled2cde6083938c436304f332ba96bb7c); ?>
<?php endif; ?>
                                <?php elseif($k == 'images'): ?>
                                    <div class="grid grid-cols-1 gap-4">
                                        <?php $__currentLoopData = $type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $imgKey => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1"><?php echo e(__(keyToTitle(@$imgKey))); ?></label>
                                                <input type="hidden" name="has_image" value="1">
                                                <?php if (isset($component)) { $__componentOriginaldbcc027cdd3569f61821c56d10b77c01 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldbcc027cdd3569f61821c56d10b77c01 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.image-uploader','data' => ['class' => 'w-full','name' => 'image_input['.e(@$imgKey).']','imagePath' => getImage('',@$section->element->images->$imgKey->size),'id' => 'addImage'.e($loop->index).'','size' => $section->element->images->$imgKey->size]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('image-uploader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-full','name' => 'image_input['.e(@$imgKey).']','imagePath' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(getImage('',@$section->element->images->$imgKey->size)),'id' => 'addImage'.e($loop->index).'','size' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($section->element->images->$imgKey->size)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldbcc027cdd3569f61821c56d10b77c01)): ?>
<?php $attributes = $__attributesOriginaldbcc027cdd3569f61821c56d10b77c01; ?>
<?php unset($__attributesOriginaldbcc027cdd3569f61821c56d10b77c01); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldbcc027cdd3569f61821c56d10b77c01)): ?>
<?php $component = $__componentOriginaldbcc027cdd3569f61821c56d10b77c01; ?>
<?php unset($__componentOriginaldbcc027cdd3569f61821c56d10b77c01); ?>
<?php endif; ?>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php elseif($fieldType == 'textarea'): ?>
                                    <?php if (isset($component)) { $__componentOriginal4727f9fd7c3055c2cf9c658d89b16886 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.textarea','data' => ['name' => ''.e($k).'','label' => ''.e(__($fieldLabel)).'','rows' => '3','icon' => 'description','required' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => ''.e($k).'','label' => ''.e(__($fieldLabel)).'','rows' => '3','icon' => 'description','required' => 'true']); ?>
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
                                <?php else: ?>
                                    <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => ''.e($k).'','label' => ''.e(__($fieldLabel)).'','required' => 'true','icon' => 'label']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => ''.e($k).'','label' => ''.e(__($fieldLabel)).'','required' => 'true','icon' => 'label']); ?>
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
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php $__currentLoopData = $ckKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $type = $section->element->$k;
                                $isObj = is_object($type) || is_array($type);
                                $fieldLabel = $isObj ? ((is_array($type) ? ($type['label'] ?? keyToTitle($k)) : ($type->label ?? keyToTitle($k)))) : keyToTitle($k);
                            ?>
                            <div class="px-4 pb-4 space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1"><?php echo e(__($fieldLabel)); ?></label>
                                <textarea name="<?php echo e($k); ?>" class="ckEditor"></textarea>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-4 border-t border-slate-100 dark:border-white/5">
                            <button type="submit" class="w-full h-11 rounded-xl bg-blue-600 text-white text-[11px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20 active:scale-[0.98]">
                                Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Update Item Modal -->
        <div id="updateModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered max-w-[360px] sm:max-w-[420px] md:max-w-[600px] lg:max-w-[740px]">
                <div class="modal-content !bg-white dark:!bg-[#121212] !rounded-2xl !border-none shadow-2xl">
                    <div class="p-4 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                        <h5 class="text-sm font-black text-slate-900 dark:text-white">Update <?php echo e(__(keyToTitle($key))); ?></h5>
                        <button type="button" class="w-7 h-7 rounded-lg hover:bg-slate-100 dark:hover:bg-white/5 text-slate-400 transition-all flex items-center justify-center" data-bs-dismiss="modal">
                            <span class="material-symbols-rounded text-lg">close</span>
                        </button>
                    </div>
                    <?php
                        $ckKeys = [];
                        $otherKeys = [];
                        foreach ($section->element as $fk => $fv) {
                            if ($fk === 'modal') continue;
                            $isTypeObj = is_object($fv) || is_array($fv);
                            $ft = $isTypeObj ? (is_array($fv) ? ($fv['type'] ?? 'text') : ($fv->type ?? 'text')) : $fv;
                            if ($ft === 'textarea-nic' || $ft === 'textarea-ck') {
                                $ckKeys[] = $fk;
                            } else {
                                $otherKeys[] = $fk;
                            }
                        }
                    ?>
                    <form action="<?php echo e(route('admin.frontend.sections.content', $key)); ?>" class="edit-route disableSubmission" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="type" value="element">
                        <input type="hidden" name="id">
                        <div class="p-4 space-y-4 max-h-[50vh] overflow-y-auto custom-scrollbar">
                            <?php $__currentLoopData = $otherKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $type = $section->element->$k;
                                    $isObj = is_object($type) || is_array($type);
                                    if ($isObj) {
                                        $type = is_array($type) ? (object) $type : $type;
                                        $fieldType = $type->type ?? 'text';
                                        $fieldLabel = $type->label ?? keyToTitle($k);
                                    } else {
                                        $fieldType = $type;
                                        $fieldLabel = keyToTitle($k);
                                    }
                                ?>
                                <?php if($fieldType == 'icon'): ?>
                                    <div class="space-y-1.5">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1"><?php echo e(__($fieldLabel)); ?></label>
                                        <div class="flex gap-2">
                                            <input type="text" class="w-full h-11 px-4 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-black/20 text-sm font-bold text-slate-700 dark:text-white iconPicker icon" autocomplete="off" name="<?php echo e($k); ?>" required>
                                            <span class="w-11 h-11 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-500 border border-slate-200 dark:border-white/10" data-icon="las la-home" role="iconpicker"><i class="las la-home"></i></span>
                                        </div>
                                    </div>
                                <?php elseif($fieldType == 'checkbox'): ?>
                                    <div class="flex items-center gap-3 py-2">
                                        <input type="hidden" name="<?php echo e($k); ?>" value="0">
                                        <input type="checkbox" name="<?php echo e($k); ?>" value="1" class="w-5 h-5 rounded-lg border-slate-300 dark:border-white/20 text-blue-600 focus:ring-blue-500">
                                        <label class="text-[10px] font-black text-slate-500 dark:text-white/50 uppercase tracking-widest select-none"><?php echo e(__($fieldLabel)); ?></label>
                                    </div>
                                <?php elseif($k == 'select'): ?>
                                    <?php if (isset($component)) { $__componentOriginaled2cde6083938c436304f332ba96bb7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled2cde6083938c436304f332ba96bb7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['name' => ''.e(@$section->element->$k->name).'','label' => ''.e(keyToTitle(@$section->element->$k->name)).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => ''.e(@$section->element->$k->name).'','label' => ''.e(keyToTitle(@$section->element->$k->name)).'']); ?>
                                        <?php $__currentLoopData = $section->element->$k->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $selectKey => $options): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($selectKey); ?>"><?php echo e($options); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaled2cde6083938c436304f332ba96bb7c)): ?>
<?php $attributes = $__attributesOriginaled2cde6083938c436304f332ba96bb7c; ?>
<?php unset($__attributesOriginaled2cde6083938c436304f332ba96bb7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaled2cde6083938c436304f332ba96bb7c)): ?>
<?php $component = $__componentOriginaled2cde6083938c436304f332ba96bb7c; ?>
<?php unset($__componentOriginaled2cde6083938c436304f332ba96bb7c); ?>
<?php endif; ?>
                                <?php elseif($k == 'images'): ?>
                                    <div class="grid grid-cols-1 gap-4">
                                        <?php $__currentLoopData = $type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $imgKey => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1"><?php echo e(__(keyToTitle($k))); ?></label>
                                                <input type="hidden" name="has_image" value="1">
                                                <?php if (isset($component)) { $__componentOriginaldbcc027cdd3569f61821c56d10b77c01 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldbcc027cdd3569f61821c56d10b77c01 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.image-uploader','data' => ['class' => 'w-full','imagePath' => getImage('', $section->element->images->$imgKey->size),'name' => 'image_input['.e(@$imgKey).']','id' => 'updateImage'.e($loop->index).'','size' => $section->element->images->$imgKey->size,'required' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('image-uploader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-full','imagePath' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(getImage('', $section->element->images->$imgKey->size)),'name' => 'image_input['.e(@$imgKey).']','id' => 'updateImage'.e($loop->index).'','size' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($section->element->images->$imgKey->size),'required' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldbcc027cdd3569f61821c56d10b77c01)): ?>
<?php $attributes = $__attributesOriginaldbcc027cdd3569f61821c56d10b77c01; ?>
<?php unset($__attributesOriginaldbcc027cdd3569f61821c56d10b77c01); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldbcc027cdd3569f61821c56d10b77c01)): ?>
<?php $component = $__componentOriginaldbcc027cdd3569f61821c56d10b77c01; ?>
<?php unset($__componentOriginaldbcc027cdd3569f61821c56d10b77c01); ?>
<?php endif; ?>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php elseif($fieldType == 'textarea'): ?>
                                    <?php if (isset($component)) { $__componentOriginal4727f9fd7c3055c2cf9c658d89b16886 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.textarea','data' => ['name' => ''.e($k).'','label' => ''.e(__($fieldLabel)).'','rows' => '3','icon' => 'description','required' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => ''.e($k).'','label' => ''.e(__($fieldLabel)).'','rows' => '3','icon' => 'description','required' => 'true']); ?>
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
                                <?php else: ?>
                                    <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => ''.e($k).'','label' => ''.e(__($fieldLabel)).'','required' => 'true','icon' => 'label']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => ''.e($k).'','label' => ''.e(__($fieldLabel)).'','required' => 'true','icon' => 'label']); ?>
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
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php $__currentLoopData = $ckKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $type = $section->element->$k;
                                $isObj = is_object($type) || is_array($type);
                                $fieldLabel = $isObj ? ((is_array($type) ? ($type['label'] ?? keyToTitle($k)) : ($type->label ?? keyToTitle($k)))) : keyToTitle($k);
                            ?>
                            <div class="px-4 pb-4 space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1"><?php echo e(__($fieldLabel)); ?></label>
                                <textarea name="<?php echo e($k); ?>" class="ckEditor"></textarea>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-4 border-t border-slate-100 dark:border-white/5">
                            <button type="submit" class="w-full h-11 rounded-xl bg-orange-600 text-white text-[11px] font-black uppercase tracking-widest hover:bg-orange-700 transition-all shadow-lg shadow-orange-500/20 active:scale-[0.98]">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($component)) { $__componentOriginalbd5922df145d522b37bf664b524be380 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd5922df145d522b37bf664b524be380 = $attributes; } ?>
<?php $component = App\View\Components\ConfirmationModal::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirmation-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\ConfirmationModal::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd5922df145d522b37bf664b524be380)): ?>
<?php $attributes = $__attributesOriginalbd5922df145d522b37bf664b524be380; ?>
<?php unset($__attributesOriginalbd5922df145d522b37bf664b524be380); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd5922df145d522b37bf664b524be380)): ?>
<?php $component = $__componentOriginalbd5922df145d522b37bf664b524be380; ?>
<?php unset($__componentOriginalbd5922df145d522b37bf664b524be380); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
<style>
    .ck-editor__editable { min-height: 200px !important; max-height: 300px !important; border-bottom-left-radius: 1.5rem !important; border-bottom-right-radius: 1.5rem !important; }
    .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) { border-color: rgba(0,0,0,0.1) !important; }
    .dark .ck-editor__editable { background: rgba(255,255,255,0.05) !important; color: white !important; border-color: rgba(255,255,255,0.1) !important; }
    .ck.ck-toolbar { border-top-left-radius: 1.5rem !important; border-top-right-radius: 1.5rem !important; background: transparent !important; }
    .dark .ck.ck-toolbar { background: rgba(255,255,255,0.05) !important; border-color: rgba(255,255,255,0.1) !important; }

    .modal .ck.ck-dropdown__panel {
        position: fixed !important;
        z-index: 1061 !important;
    }
    .modal .ck.ck-balloon-panel {
        z-index: 1062 !important;
    }

    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('style-lib'); ?>
    <link href="<?php echo e(asset('assets/admin/css/fontawesome-iconpicker.min.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script-lib'); ?>
    <script src="<?php echo e(asset('assets/admin/js/fontawesome-iconpicker.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-build-classic@39.0.1/build/ckeditor.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";
            
            const editors = {};

            function initEditors() {
                document.querySelectorAll('.ckEditor').forEach(el => {
                    if ($(el).data('editor')) return;
                    ClassicEditor
                        .create(el)
                        .then(editor => {
                            $(el).data('editor', editor);
                            editors[el.getAttribute('name')] = editor;
                        })
                        .catch(error => { console.error(error); });
                });
            }

            initEditors();

            $(document).on('submit', 'form', function() {
                $(this).find('.ckEditor').each(function() {
                    const editor = $(this).data('editor');
                    if (editor) editor.updateSourceElement();
                });
            });

            $(document).on('click','.updateBtn', function() {
                var modal = $('#updateModal');
                modal.find('input[name=id]').val($(this).data('id'));
                var obj = $(this).data('all');
                var images = $(this).data('images');
                var imagePreviews = modal.find('.image-upload-preview');

                if (images) {
                    for (var i = 0; i < images.length; i++) {
                        $(imagePreviews[i]).css("background-image", "url(" + images[i] + ")");
                    }
                }

                $.each(obj, function(index, value) {
                    const field = modal.find('[name=' + index + ']');
                    const editor = field.data('editor') || editors[index];
                    if (editor) {
                        editor.setData(value || '');
                    } else if (field.is(':checkbox')) {
                        field.prop('checked', value == '1');
                    } else {
                        field.val(value);
                    }
                    if (field.hasClass('iconPicker')) {
                        modal.find('[role="iconpicker"]').html(value || '<i class="las la-home"></i>');
                    }
                });
                modal.modal('show');
            });

            $(document).on('click', '.addBtn', function() {
                var modal = $('#addModal');
                modal.find('form')[0].reset();
                modal.find('.ckEditor').each(function() {
                    const editor = $(this).data('editor') || editors[$(this).attr('name')];
                    if (editor) editor.setData('');
                });
                modal.find('[role="iconpicker"]').html('<i class="las la-home"></i>');
                modal.modal('show');
            });

            $(document).on('iconpickerSelected', '.iconPicker', function(e) {
                var val = '<i class="' + e.iconpickerValue + '"></i>';
                $(this).val(val);
                $(this).closest('.flex').find('[role="iconpicker"]').html(val);
            });

            $(document).on('show.bs.modal', '#addModal, #updateModal', function() {
                $(this).find('.iconPicker').each(function() {
                    if (!$(this).data('iconpicker')) {
                        $(this).iconpicker();
                    }
                });
            });

        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\frontend\section.blade.php ENDPATH**/ ?>