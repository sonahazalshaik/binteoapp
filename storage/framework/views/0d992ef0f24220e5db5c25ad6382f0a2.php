<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Admin - Identity Re-Provisioning</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?php echo e(asset('assets/js/validation.js')); ?>"></script>
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-black text-white antialiased h-screen overflow-hidden selection:bg-orange-500/30 selection:text-orange-200">
    <?php echo $__env->make('partials.notify', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Ambient Dynamic Background -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[20%] -left-[10%] w-[70vw] h-[70vw] bg-orange-700/40 rounded-full blur-[120px] mix-blend-screen opacity-50 animate-pulse" style="animation-duration: 8s;"></div>
        <div class="absolute top-[40%] -right-[20%] w-[80vw] h-[80vw] bg-orange-600/30 rounded-full blur-[140px] mix-blend-screen opacity-50 animate-pulse" style="animation-duration: 12s; animation-delay: 2s;"></div>
        <div class="absolute -bottom-[30%] left-[20%] w-[60vw] h-[60vw] bg-orange-900/40 rounded-full blur-[100px] mix-blend-screen opacity-50 animate-pulse" style="animation-duration: 10s; animation-delay: 1s;"></div>
        
        <!-- Texture overlay -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/noise-pattern-with-subtle-cross-lines.png')] opacity-[0.03]"></div>
    </div>

    <!-- Main Container -->
    <div class="relative z-10 w-full h-full flex flex-col justify-end sm:justify-center items-center p-0 sm:p-6 pb-0">
        
        <!-- Elevated Floating Auth Sheet (M3 Bottom Sheet Style) -->
        <div class="w-full sm:max-w-[420px] bg-white/[0.03] backdrop-blur-3xl border-t border-white/10 sm:border sm:rounded-[2.5rem] rounded-t-[2.5rem] p-6 sm:p-8 shadow-[0_-20px_60px_-15px_rgba(0,0,0,0.5)] sm:shadow-2xl relative overflow-hidden transition-all flex flex-col max-h-[100dvh] sm:h-auto">
            
            <!-- Orange Top Edge Accent -->
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-orange-600 via-orange-500 to-orange-600"></div>

            <div class="mx-auto w-12 h-1.5 bg-white/20 rounded-full mb-6 sm:hidden flex-shrink-0"></div> <!-- Handle bar -->

            <!-- Header -->
            <div class="text-center mb-6 flex-shrink-0">
                <div class="relative inline-flex items-center justify-center w-14 h-14 rounded-[1.25rem] bg-gradient-to-tr from-orange-900/50 to-orange-600/20 text-orange-500 mb-4 border border-orange-500/20 shadow-[0_0_30px_rgba(249,115,22,0.2)]">
                    <span class="material-symbols-rounded text-[28px] drop-shadow-lg">lock_open</span>
                </div>
                <h2 class="text-2xl font-black text-white tracking-tight leading-none mb-2">New Password</h2>
                <p class="text-[14px] font-medium text-white/50">Create your new password</p>
            </div>

            <form method="POST" action="<?php echo e(route('admin.password.store')); ?>" class="space-y-4 overflow-y-auto pb-4 scrollbar-hide flex-grow px-1">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="token" value="<?php echo e($request->route('token')); ?>">

                <!-- Admin Email -->
                <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'email','type' => 'email','label' => 'Email Address','required' => true,'autocomplete' => 'username','placeholder' => 'admin@example.com','value' => ''.e(old('email', $request->email)).'','hint' => 'Confirm your administrative email address.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'email','type' => 'email','label' => 'Email Address','required' => true,'autocomplete' => 'username','placeholder' => 'admin@example.com','value' => ''.e(old('email', $request->email)).'','hint' => 'Confirm your administrative email address.']); ?>
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

                <!-- New Admin Password -->
                <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'password','type' => 'password','label' => 'New Password','required' => true,'autofocus' => true,'autocomplete' => 'new-password','placeholder' => '••••••••','hint' => 'Set a strong new administrative password.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'password','type' => 'password','label' => 'New Password','required' => true,'autofocus' => true,'autocomplete' => 'new-password','placeholder' => '••••••••','hint' => 'Set a strong new administrative password.']); ?>
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

                <!-- Confirm Password -->
                <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'password_confirmation','type' => 'password','label' => 'Confirm Password','required' => true,'autocomplete' => 'new-password','placeholder' => '••••••••','hint' => 'Verify your new password selection.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'password_confirmation','type' => 'password','label' => 'Confirm Password','required' => true,'autocomplete' => 'new-password','placeholder' => '••••••••','hint' => 'Verify your new password selection.']); ?>
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

                <!-- Submit Action -->
                <button class="relative w-full h-[56px] flex items-center justify-center bg-gradient-to-r from-orange-600 to-orange-500 overflow-hidden rounded-2xl text-white font-bold text-[16px] shadow-[0_8px_25px_rgba(249,115,22,0.3)] hover:shadow-[0_10px_35px_rgba(249,115,22,0.4)] active:scale-[0.98] transition-all group border border-orange-500/50 mt-2">
                    <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></span>
                    <span class="flex items-center gap-2">Reset Password <span class="material-symbols-rounded text-lg">done_all</span></span>
                </button>
            </form>

            <div class="mt-4 text-center flex-shrink-0">
                <a href="<?php echo e(route('admin.login')); ?>" class="text-[13px] font-bold text-white/30 hover:text-white/70 transition-colors flex justify-center items-center gap-1">
                    <span class="material-symbols-rounded text-[16px]">arrow_back</span>
                    Return to sign in
                </a>
            </div>
        </div>
    </div>

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</body>
</html>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\auth\reset-password.blade.php ENDPATH**/ ?>