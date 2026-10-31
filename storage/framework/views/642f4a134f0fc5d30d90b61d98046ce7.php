
<?php $__env->startSection('panel'); ?>
<div class="max-w-6xl mx-auto pb-24">
    <form action="<?php echo e(route('admin.advertisement.update', $advertisement->id)); ?>" method="post" enctype="multipart/form-data" class="space-y-8">
        <?php echo csrf_field(); ?>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-7 space-y-8">
                <div class="bg-white/60 dark:bg-black/20 backdrop-blur-3xl border border-slate-200 dark:border-white/10 rounded-[2.5rem] p-8 lg:p-10 shadow-2xl">
                    <div class="space-y-6">
                        <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'title','label' => 'Campaign Title','required' => true,'value' => ''.e($advertisement->title).'','placeholder' => 'Enter advertisement title...','hint' => 'The internal name for this ad campaign.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'title','label' => 'Campaign Title','required' => true,'value' => ''.e($advertisement->title).'','placeholder' => 'Enter advertisement title...','hint' => 'The internal name for this ad campaign.']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['name' => 'category_id[]','label' => 'Target Categories','required' => true,'multiple' => true,'class' => 'h-40','hint' => 'Hold CTRL to select multiple categories.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'category_id[]','label' => 'Target Categories','required' => true,'multiple' => true,'class' => 'h-40','hint' => 'Hold CTRL to select multiple categories.']); ?>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>" <?php if($advertisement->categories->pluck('id')->contains($category->id)): ?> selected <?php endif; ?>>
                                    <?php echo e(__($category->name)); ?>

                                </option>
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

                        <?php if (isset($component)) { $__componentOriginaled2cde6083938c436304f332ba96bb7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled2cde6083938c436304f332ba96bb7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['name' => 'ad_type','label' => 'Campaign Goal','required' => true,'hint' => 'Define the primary billing metric for this campaign.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'ad_type','label' => 'Campaign Goal','required' => true,'hint' => 'Define the primary billing metric for this campaign.']); ?>
                            <option value="1" <?php if($advertisement->ad_type == Status::IMPRESSION): ?> selected <?php endif; ?>>Per Impression (<?php echo e(gs('cur_sym')); ?><?php echo e(showAmount(gs('per_impression_spent'), currencyFormat: false)); ?>)</option>
                            <option value="2" <?php if($advertisement->ad_type == Status::CLICK): ?> selected <?php endif; ?>>Per Click (<?php echo e(gs('cur_sym')); ?><?php echo e(showAmount(gs('per_click_spent'), currencyFormat: false)); ?>)</option>
                            <option value="3" <?php if($advertisement->ad_type == Status::BOTH): ?> selected <?php endif; ?>>Both Metrics</option>
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

                        <div class="grid grid-cols-2 gap-4">
                            <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['type' => 'number','name' => 'impression','label' => 'Impression Target','value' => ''.e($advertisement->impression).'','placeholder' => '0','readonly' => $advertisement->ad_type == Status::CLICK,'hint' => 'Target impressions count.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'number','name' => 'impression','label' => 'Impression Target','value' => ''.e($advertisement->impression).'','placeholder' => '0','readonly' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($advertisement->ad_type == Status::CLICK),'hint' => 'Target impressions count.']); ?>
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
                            <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['type' => 'number','name' => 'click','label' => 'Click Target','value' => ''.e($advertisement->click).'','placeholder' => '0','readonly' => $advertisement->ad_type == Status::IMPRESSION,'hint' => 'Target clicks count.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'number','name' => 'click','label' => 'Click Target','value' => ''.e($advertisement->click).'','placeholder' => '0','readonly' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($advertisement->ad_type == Status::IMPRESSION),'hint' => 'Target clicks count.']); ?>
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
                        </div>

                        <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['type' => 'number','step' => 'any','name' => 'total_amount','label' => 'Paid Amount ('.e(gs('cur_text')).')','value' => ''.e(getAmount($advertisement->total_amount)).'','placeholder' => '0.00','hint' => 'Total budget allocated for this campaign.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'number','step' => 'any','name' => 'total_amount','label' => 'Paid Amount ('.e(gs('cur_text')).')','value' => ''.e(getAmount($advertisement->total_amount)).'','placeholder' => '0.00','hint' => 'Total budget allocated for this campaign.']); ?>
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

                        <div id="click-fields" class="<?php if($advertisement->ad_type == Status::IMPRESSION): ?> hidden <?php endif; ?> space-y-6 pt-4 border-t border-slate-100 dark:border-white/5">
                            <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'url','type' => 'url','label' => 'Redirect URL','value' => ''.e($advertisement->url).'','placeholder' => 'https://example.com','hint' => 'The destination website for user clicks.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'url','type' => 'url','label' => 'Redirect URL','value' => ''.e($advertisement->url).'','placeholder' => 'https://example.com','hint' => 'The destination website for user clicks.']); ?>
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
                            <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'button_label','label' => 'CTA Button Label','value' => ''.e($advertisement->button_label).'','placeholder' => 'e.g. LEARN MORE','hint' => 'Text displayed on the ad\'s action button.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'button_label','label' => 'CTA Button Label','value' => ''.e($advertisement->button_label).'','placeholder' => 'e.g. LEARN MORE','hint' => 'Text displayed on the ad\'s action button.']); ?>
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
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Brand Logo</label>
                                <?php if (isset($component)) { $__componentOriginaldbcc027cdd3569f61821c56d10b77c01 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldbcc027cdd3569f61821c56d10b77c01 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.image-uploader','data' => ['name' => 'logo','imagePath' => getImage(getFilePath('adLogo') . '/' . $advertisement->logo),'size' => getFileSize('adLogo'),'required' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('image-uploader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'logo','imagePath' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(getImage(getFilePath('adLogo') . '/' . $advertisement->logo)),'size' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(getFileSize('adLogo')),'required' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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

                        <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['type' => 'file','name' => 'ad_video','label' => 'Update Media File','accept' => 'video/*','hint' => 'Optional: Upload a new video file to replace the current one.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'file','name' => 'ad_video','label' => 'Update Media File','accept' => 'video/*','hint' => 'Optional: Upload a new video file to replace the current one.']); ?>
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

                        <div class="flex items-center justify-between p-6 bg-slate-50 dark:bg-black/40 rounded-2xl border border-slate-100 dark:border-white/5">
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1">Campaign Status</span>
                                <span class="text-xs font-bold text-slate-900 dark:text-white" id="status-label"><?php echo e($advertisement->status == Status::RUNNING ? 'Currently Running' : 'Currently Paused'); ?></span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="status" class="sr-only peer" <?php if($advertisement->status == Status::RUNNING): ?> checked <?php endif; ?>>
                                <div class="w-14 h-8 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-white/10 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-orange-500"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 space-y-8">
                <div class="bg-black rounded-[2.5rem] overflow-hidden shadow-2xl border border-white/10 sticky top-10">
                    <div class="p-6 border-b border-white/5 flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase tracking-widest text-white/40 ">Live Asset Preview</span>
                        <span class="px-3 py-1 bg-orange-500/10 text-orange-500 text-[9px] font-black uppercase tracking-widest rounded-lg">Active</span>
                    </div>
                    <div class="aspect-video">
                        <video class="video-player w-full h-full object-cover" controls>
                            <source src="<?php echo e(getAd($advertisement->ad_file, $advertisement)); ?>" type="video/mp4" />
                        </video>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-6 mt-12">
            <button type="submit" class="w-full sm:flex-grow h-16 rounded-[2rem] orange-gradient-primary text-white flex items-center justify-center gap-4 text-[12px] font-black uppercase tracking-widest hover:scale-[1.02] active:scale-95 transition-all shadow-xl group">
                <span class="material-symbols-rounded relative z-10">save</span>
                <span class="relative z-10">Synchronize Campaign Data</span>
            </button>
            <a href="<?php echo e(route('admin.advertisement.both')); ?>" class="w-full sm:w-auto h-16 px-10 rounded-[2rem] border border-slate-200 dark:border-white/10 text-[12px] font-black uppercase tracking-widest text-slate-500 flex items-center justify-center hover:bg-slate-100 dark:hover:bg-white/5 transition-all active:scale-95 shadow-sm ">
                Cancel
            </a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style-lib'); ?>
    <link href="<?php echo e(asset('assets/global/css/plyr.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script-lib'); ?>
    <script src="<?php echo e(asset('assets/global/js/plyr.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";

            $(document).ready(function() {
                const singleplayer = new Plyr('.video-player', {
                    autoplay: true,
                    ratio: '16:9',
                });

                const impressionField = $('[name="impression"]');
                const clickField = $('[name="click"]');
                const clickWrapper = $('#click-fields');
                const statusInput = $('input[name="status"]');
                const statusLabel = $('#status-label');

                statusInput.on('change', function() {
                    statusLabel.text(this.checked ? 'Currently Running' : 'Currently Paused');
                });

                $('[name="ad_type"]').on('change', function() {
                    const value = $(this).val();

                    if (value == '1') {
                        // Impression only
                        impressionField.prop('disabled', false).removeClass('opacity-50');
                        clickField.prop('disabled', true).addClass('opacity-50').val('0');
                        clickWrapper.addClass('hidden');
                        $('[name="url"], [name="button_label"]').prop('required', false);
                    } else if (value == '2') {
                        // Click only
                        impressionField.prop('disabled', true).addClass('opacity-50').val('0');
                        clickField.prop('disabled', false).removeClass('opacity-50');
                        clickWrapper.removeClass('hidden');
                        $('[name="url"], [name="button_label"]').prop('required', true);
                    } else if (value == '3') {
                        // Both
                        impressionField.prop('disabled', false).removeClass('opacity-50');
                        clickField.prop('disabled', false).removeClass('opacity-50');
                        clickWrapper.removeClass('hidden');
                        $('[name="url"], [name="button_label"]').prop('required', true);
                    }
                });
            });

        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\advertisements\edit.blade.php ENDPATH**/ ?>