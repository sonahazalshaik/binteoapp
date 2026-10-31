<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="py-10 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500">
        <div class="max-w-[900px] mx-auto px-4 sm:px-6">
            <!-- Header -->
            <div class="mb-6 sm:mb-10 lg:mb-12">
                <a href="<?php echo e(route('user.ticket.index')); ?>" class="inline-flex items-center gap-2 text-[9px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 sm:mb-6 hover:text-red-600 transition-colors">
                    <span class="material-symbols-rounded text-base sm:text-lg">arrow_back</span>
                    Support Ledger
                </a>
                <h1 class="text-[22px] sm:text-3xl lg:text-4xl font-black text-gray-900 dark:text-white tracking-tighter leading-tight">New Assistance Request</h1>
                <p class="text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5 sm:mt-2 flex items-center gap-2">
                    <span class="w-1 h-1 sm:w-1.5 sm:h-1.5 rounded-full bg-red-500"></span>
                    Typically responds within 2-4 hours
                </p>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white dark:bg-[#181818] rounded-2xl sm:rounded-[2rem] lg:rounded-[3.5rem] p-5 sm:p-8 lg:p-12 border border-gray-100 dark:border-white/5 shadow-xl sm:shadow-2xl relative overflow-hidden transition-all duration-500">
                <div class="absolute -top-24 -right-24 w-48 h-48 sm:w-64 sm:h-64 bg-red-600/5 rounded-full blur-[60px] sm:blur-[80px] pointer-events-none"></div>
                
                <form action="<?php echo e(route('user.ticket.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-5 sm:space-y-8 relative z-10" x-data="{ sending: false }" @submit="sending = true">
                    <?php echo csrf_field(); ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-2 sm:gap-y-0">
                        <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'subject','label' => 'Subject','value' => ''.e(old('subject')).'','placeholder' => 'What do you need help with?','required' => 'true','icon' => 'edit_note','hint' => 'A brief summary of your issue.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'subject','label' => 'Subject','value' => ''.e(old('subject')).'','placeholder' => 'What do you need help with?','required' => 'true','icon' => 'edit_note','hint' => 'A brief summary of your issue.']); ?>
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

                        <?php if (isset($component)) { $__componentOriginaled2cde6083938c436304f332ba96bb7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled2cde6083938c436304f332ba96bb7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['name' => 'priority','label' => 'Priority','required' => 'true','icon' => 'priority_high','hint' => 'How urgent is this request?']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'priority','label' => 'Priority','required' => 'true','icon' => 'priority_high','hint' => 'How urgent is this request?']); ?>
                            <option value="1" class="bg-white dark:bg-[#1A1A1A] text-slate-900 dark:text-white">Low</option>
                            <option value="2" class="bg-white dark:bg-[#1A1A1A] text-slate-900 dark:text-white">Medium</option>
                            <option value="3" class="bg-white dark:bg-[#1A1A1A] text-slate-900 dark:text-white">High (Urgent)</option>
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

                        <?php if (isset($component)) { $__componentOriginaled2cde6083938c436304f332ba96bb7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled2cde6083938c436304f332ba96bb7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['name' => 'status','label' => 'Initial Status','required' => 'true','icon' => 'hourglass_top','hint' => 'Starting state of this ticket.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'status','label' => 'Initial Status','required' => 'true','icon' => 'hourglass_top','hint' => 'Starting state of this ticket.']); ?>
                            <option value="0" selected class="bg-white dark:bg-[#1A1A1A] text-slate-900 dark:text-white">Pending / Open</option>
                            <option value="1" class="bg-white dark:bg-[#1A1A1A] text-slate-900 dark:text-white">Completed / Answered</option>
                            <option value="3" class="bg-white dark:bg-[#1A1A1A] text-slate-900 dark:text-white">Rejected / Closed</option>
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
                    </div>

                    <?php if (isset($component)) { $__componentOriginal4727f9fd7c3055c2cf9c658d89b16886 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.textarea','data' => ['name' => 'message','label' => 'Message','rows' => '8','placeholder' => 'Tell us exactly what happened...','required' => 'true','icon' => 'description','hint' => 'Include any details that might help us resolve your issue faster.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'message','label' => 'Message','rows' => '8','placeholder' => 'Tell us exactly what happened...','required' => 'true','icon' => 'description','hint' => 'Include any details that might help us resolve your issue faster.']); ?>
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

                    <!-- Attachments -->
                    <div class="space-y-3 sm:space-y-4">
                        <label class="px-1 text-[9px] sm:text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Attachments</label>
                        <div class="relative group cursor-pointer" x-data="{ files: [] }">
                            <input type="file" name="attachments[]" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" multiple 
                                   @change="files = Array.from($event.target.files).map(f => f.name)">
                            <div class="w-full bg-gray-50 dark:bg-black/20 border-2 border-dashed border-gray-200 dark:border-white/10 rounded-2xl sm:rounded-[2rem] lg:rounded-[2.5rem] px-4 sm:px-8 py-8 sm:py-12 flex flex-col items-center justify-center gap-3 sm:gap-4 group-hover:border-red-500 transition-all">
                                <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl bg-white dark:bg-white/10 flex items-center justify-center shadow-xl group-hover:rotate-6 transition-all">
                                    <span class="material-symbols-rounded text-2xl sm:text-3xl text-gray-400 dark:text-white/60 group-hover:text-red-500">cloud_upload</span>
                                </div>
                                <div class="text-center">
                                    <p class="text-[10px] sm:text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-widest" x-text="files.length ? files.length + ' files selected' : 'Drop files or click to upload'"></p>
                                    <p class="text-[8px] sm:text-[9px] font-bold text-gray-400 dark:text-white/40 mt-1.5 sm:mt-2 uppercase tracking-widest">JPG, PNG, PDF or DOC (Max 5)</p>
                                </div>
                                <template x-if="files.length">
                                    <div class="flex flex-wrap gap-2 justify-center mt-2">
                                        <template x-for="file in files" :key="file">
                                            <span class="px-3 py-1 bg-red-500/10 text-red-500 rounded-lg text-[8px] font-black uppercase tracking-widest border border-red-500/20" x-text="file"></span>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="pt-2 sm:pt-8 flex justify-center">
                        <button type="submit" :disabled="sending" :class="sending && 'opacity-70 pointer-events-none'" class="w-full max-w-md mx-auto h-11 sm:h-14 lg:h-16 bg-red-600 text-white rounded-xl sm:rounded-2xl lg:rounded-[2rem] font-black text-[10px] sm:text-[11px] lg:text-[12px] uppercase tracking-[0.2em] sm:tracking-[0.25em] lg:tracking-[0.4em] shadow-lg shadow-red-500/25 hover:bg-red-700 active:scale-95 transition-all flex items-center justify-center gap-2 sm:gap-3 lg:gap-4 group">
                            <span x-show="!sending" class="flex items-center gap-2 sm:gap-3 lg:gap-4">
                                Send Request
                                <span class="material-symbols-rounded text-base sm:text-lg lg:text-xl group-hover:translate-x-1.5 transition-transform duration-300">send</span>
                            </span>
                            <span x-show="sending" style="display: none;" class="flex items-center gap-2 sm:gap-3">
                                <svg class="animate-spin h-4 w-4 sm:h-5 sm:w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                Sending...
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Help Footer -->
            <div class="mt-6 sm:mt-10 lg:mt-12 text-center px-2">
                <p class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-relaxed">By submitting, you agree to our platform support guidelines.</p>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>

<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\client\ticket\create.blade.php ENDPATH**/ ?>