
<?php $__env->startSection('panel'); ?>
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card bl--5 border--primary">
                <div class="card-body">
                    <p class="text--primary"><?php echo app('translator')->get('While you are adding a new keyword, it will only add to this current language only. Please be careful on entering a keyword, please make sure there is no extra space. It needs to be exact and case-sensitive.'); ?></p>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="overflow-x-auto scrollbar-hide"><table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Name'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Code'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Default'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                <?php $__empty_1 = true; $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <div class="user">
                                                <div class="thumb">
                                                    <img src="<?php echo e(getImage(getFilePath('language') . '/' . $item->image, getFileSize('language'))); ?>" alt="<?php echo e($item->name); ?>" class="plugin_bg">
                                                </div>
                                                <span class="name"><?php echo e(__($item->name)); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70"><strong><?php echo e(__($item->code)); ?></strong></td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php if($item->is_default == Status::YES): ?>
                                                <span class="badge badge--success"><?php echo app('translator')->get('Default'); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge--warning"><?php echo app('translator')->get('Selectable'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                         <td class="px-6 py-4">
                                             <div class="flex items-center gap-2">
                                                 <a href="<?php echo e(route('admin.language.key', $item->id)); ?>" class="h-8 px-4 rounded-xl bg-emerald-500 text-white text-[9px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-sm flex items-center gap-2">
                                                     <span class="material-symbols-rounded text-sm">translate</span> <?php echo app('translator')->get('Translate'); ?>
                                                 </a>
                                                 <button class="h-8 px-4 rounded-xl bg-blue-600 text-white text-[9px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-sm flex items-center gap-2 editBtn" 
                                                         data-url="<?php echo e(route('admin.language.manage.update', $item->id)); ?>" 
                                                         data-lang="<?php echo e(json_encode($item->only('name', 'text_align', 'is_default', 'image'))); ?>" 
                                                         data-image="<?php echo e(getImage(getFilePath('language') . '/' . $item->image, getFileSize('language'))); ?>">
                                                     <span class="material-symbols-rounded text-sm">edit_square</span> <?php echo app('translator')->get('Edit'); ?>
                                                 </button>
                                                 <?php if($item->id != 1): ?>
                                                     <button class="h-8 px-4 rounded-xl bg-rose-600 text-white text-[9px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-sm flex items-center gap-2 confirmationBtn" 
                                                             data-question="<?php echo app('translator')->get('Are you sure to remove this language from this system?'); ?>" 
                                                             data-action="<?php echo e(route('admin.language.manage.delete', $item->id)); ?>">
                                                         <span class="material-symbols-rounded text-sm">delete</span> <?php echo app('translator')->get('Remove'); ?>
                                                     </button>
                                                 <?php endif; ?>
                                             </div>
                                         </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="text-muted text-center py-20 opacity-30" colspan="100%">
                                            <span class="material-symbols-rounded text-4xl mb-2">translate</span>
                                            <p class="text-[10px] font-black uppercase tracking-widest "><?php echo e(__($emptyMessage)); ?></p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
            </div><!-- card end -->
        </div>
    </div>



    
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 rounded-[2rem] overflow-hidden shadow-2xl">
                <div class="modal-header border-b border-slate-100 dark:border-white/5 p-6">
                    <h4 class="modal-title text-xl font-black uppercase tracking-tighter " id="createModalLabel"> <?php echo app('translator')->get('Add New Language'); ?></h4>
                    <button type="button" class="h-10 w-10 rounded-full flex items-center justify-center bg-slate-50 dark:bg-white/5 text-slate-400 hover:text-rose-500 transition-colors" data-bs-dismiss="modal" aria-label="Close">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>
                <form class="form-horizontal" method="post" action="<?php echo e(route('admin.language.manage.store')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body p-8">
                        <div class="row">
                            <div class="form-group col-12 mb-6">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1 mb-2 block"> <?php echo app('translator')->get('Language Flag'); ?></label>
                                <?php if (isset($component)) { $__componentOriginaldbcc027cdd3569f61821c56d10b77c01 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldbcc027cdd3569f61821c56d10b77c01 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.image-uploader','data' => ['imagePath' => getImage(null, getFileSize('language')),'size' => getFileSize('language'),'class' => 'w-100','id' => 'imageCreate','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('image-uploader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['imagePath' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(getImage(null, getFileSize('language'))),'size' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(getFileSize('language')),'class' => 'w-100','id' => 'imageCreate','required' => true]); ?>
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
                        </div>
                        <div class="row form-group mb-6">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1 mb-2 block"><?php echo app('translator')->get('Language Name'); ?></label>
                            <div class="col-sm-12">
                                <input type="text" class="w-full h-12 rounded-xl bg-slate-50 dark:bg-white/5 border-slate-200 dark:border-white/10 px-4 text-sm font-bold text-slate-700 dark:text-white outline-none focus:ring-4 focus:ring-blue-500/10 transition-all" value="<?php echo e(old('name')); ?>" name="name" required placeholder="e.g. English">
                            </div>
                        </div>

                        <div class="row form-group mb-6">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1 mb-2 block"><?php echo app('translator')->get('Language Code'); ?></label>
                            <div class="col-sm-12">
                                <input type="text" class="w-full h-12 rounded-xl bg-slate-50 dark:bg-white/5 border-slate-200 dark:border-white/10 px-4 text-sm font-bold text-slate-700 dark:text-white outline-none focus:ring-4 focus:ring-blue-500/10 transition-all" value="<?php echo e(old('code')); ?>" name="code" required placeholder="e.g. en">
                            </div>
                        </div>

                        <div class="row form-group mb-0">
                            <div class="col-md-12 flex items-center justify-between bg-slate-50 dark:bg-white/5 p-4 rounded-2xl">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-700 dark:text-white/70 mb-0" for="inputName"><?php echo app('translator')->get('Set as Default Language'); ?></label>
                                <input type="checkbox" data-width="80px" data-height="30px" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="<?php echo app('translator')->get('YES'); ?>" data-off="<?php echo app('translator')->get('NO'); ?>" name="is_default">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer border-t border-slate-100 dark:border-white/5 p-6">
                        <button type="submit" class="w-full h-12 rounded-xl bg-blue-600 text-white text-[11px] font-black uppercase tracking-[0.2em] hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-blue-500/20 " id="btn-save" value="add"><?php echo app('translator')->get('Submit'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 rounded-[2rem] overflow-hidden shadow-2xl">
                <div class="modal-header border-b border-slate-100 dark:border-white/5 p-6">
                    <h4 class="modal-title text-xl font-black uppercase tracking-tighter " id="editModalLabel"><?php echo app('translator')->get('Edit Language Configuration'); ?></h4>
                    <button type="button" class="h-10 w-10 rounded-full flex items-center justify-center bg-slate-50 dark:bg-white/5 text-slate-400 hover:text-rose-500 transition-colors" data-bs-dismiss="modal" aria-label="Close">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>
                <form method="post" class="disableSubmission" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body p-8">
                        <div class="form-group mb-6">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1 mb-2 block"> <?php echo app('translator')->get('Language Flag'); ?></label>
                            <?php if (isset($component)) { $__componentOriginaldbcc027cdd3569f61821c56d10b77c01 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldbcc027cdd3569f61821c56d10b77c01 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.image-uploader','data' => ['imagePath' => getImage(null, getFileSize('language')),'size' => getFileSize('language'),'class' => 'w-100','id' => 'imageEdit','required' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('image-uploader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['imagePath' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(getImage(null, getFileSize('language'))),'size' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(getFileSize('language')),'class' => 'w-100','id' => 'imageEdit','required' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
                        <div class="form-group mb-6">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1 mb-2 block"><?php echo app('translator')->get('Language Name'); ?></label>
                            <div class="col-sm-12">
                                <input type="text" class="w-full h-12 rounded-xl bg-slate-50 dark:bg-white/5 border-slate-200 dark:border-white/10 px-4 text-sm font-bold text-slate-700 dark:text-white outline-none focus:ring-4 focus:ring-blue-500/10 transition-all" value="<?php echo e(old('name')); ?>" name="name" required>
                            </div>
                        </div>

                        <div class="form-group mt-2 flex items-center justify-between bg-slate-50 dark:bg-white/5 p-4 rounded-2xl">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-700 dark:text-white/70 mb-0" for="inputName"><?php echo app('translator')->get('Default Language Status'); ?></label>
                            <input type="checkbox" data-width="80px" data-height="30px" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="<?php echo app('translator')->get('YES'); ?>" data-off="<?php echo app('translator')->get('NO'); ?>" name="is_default">
                        </div>
                    </div>
                    <div class="modal-footer border-t border-slate-100 dark:border-white/5 p-6">
                        <button type="submit" class="w-full h-12 rounded-xl bg-blue-600 text-white text-[11px] font-black uppercase tracking-[0.2em] hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-blue-500/20 " id="btn-save" value="add"><?php echo app('translator')->get('Submit'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="getLangModal" tabindex="-1" role="dialog" aria-labelledby="getLangModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="getLangModalLabel"><?php echo app('translator')->get('Language Keywords'); ?></h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="las la-times"></i></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3"><?php echo app('translator')->get('All of the possible language keywords are available here. However, some keywords may be missing due to variations in the database. If you encounter any missing keywords, you can add them manually.'); ?></p>
                    <p class="text--primary mb-3"><?php echo app('translator')->get('You can import these keywords from the translate page of any language as well.'); ?></p>
                    <div class="form-group">
                        <textarea name="" class="form-control langKeys key-added" id="langKeys" rows="25" readonly></textarea>
                        <button type="button" class="btn btn--primary w-100 h-45 mt-3 copyBtn"><i class="las la-copy"></i> <span class="text-white copy-text"><?php echo app('translator')->get('Copy'); ?></span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

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


<?php $__env->startPush('breadcrumb-plugins'); ?>
    <button type="button" class="h-10 px-6 rounded-full bg-blue-600 text-white text-[11px] font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-md flex items-center gap-2" data-bs-toggle="modal" data-bs-target="#createModal">
        <span class="material-symbols-rounded text-lg">add</span>
        <?php echo app('translator')->get('Add New'); ?>
    </button>
    <button type="button" class="h-10 px-6 rounded-full bg-indigo-600 text-white text-[11px] font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-md flex items-center gap-2 keyBtn" data-bs-toggle="modal" data-bs-target="#getLangModal">
        <span class="material-symbols-rounded text-lg">code</span>
        <?php echo app('translator')->get('Language Keywords'); ?>
    </button>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('style'); ?>
    <style>
        .key-added {
            pointer-events: unset !important;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";
            $('.editBtn').on('click', function() {
                var modal = $('#editModal');
                var url = $(this).data('url');
                var lang = $(this).data('lang');

                modal.find('form').attr('action', url);
                modal.find('input[name=name]').val(lang.name);
                modal.find('select[name=text_align]').val(lang.text_align);
                modal.find('.image-upload-preview').css('background-image', `url(${$(this).data('image')})`);
                if (lang.is_default == 1) {
                    modal.find('input[name=is_default]').bootstrapToggle('on');
                } else {
                    modal.find('input[name=is_default]').bootstrapToggle('off');
                }
                modal.modal('show');
            });

            $('.keyBtn').on('click', function(e) {
                e.preventDefault();
                $.get("<?php echo e(route('admin.language.get.key')); ?>", {}, function(data) {
                    $('.langKeys').text(data);
                });
            });

            $('.copyBtn').on('click', function() {
                var copyText = document.getElementById("langKeys");
                copyText.select();
                document.execCommand("copy");
                $('.copy-text').text('Copied');
                setTimeout(() => {
                    $('.copy-text').text('Copy');
                }, 2000);

            });

        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\language\lang.blade.php ENDPATH**/ ?>