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
    <div class="py-10 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen" x-data="planPurchase()">
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="bg-white dark:bg-[#1A1A1A] rounded-[3rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden flex flex-col lg:flex-row shadow-2xl">
                <!-- Plan Left -->
                <div class="lg:w-2/5 p-12 lg:p-16 flex flex-col justify-between bg-gradient-to-br from-red-600/5 to-transparent border-r border-gray-50 dark:border-white/5">
                    <div>
                        <a href="<?php echo e(route('user.plans.index')); ?>" class="inline-flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-12 hover:text-red-600 transition-colors">
                            <span class="material-symbols-rounded text-lg">arrow_back</span>
                            Back to Plans
                        </a>
                        <div class="w-20 h-20 rounded-3xl bg-red-600 text-white flex items-center justify-center mb-8 shadow-xl shadow-red-600/20">
                            <span class="material-symbols-rounded text-4xl">workspace_premium</span>
                        </div>
                        <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter mb-4"><?php echo e($plan->name); ?></h1>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-relaxed mb-8"><?php echo e($plan->description ?? 'Elevate your channel experience with premium creator content.'); ?></p>
                    </div>

                    <div class="pt-10 border-t border-gray-100 dark:border-white/5">
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Subscription Total</div>
                        <div class="flex items-end gap-1">
                            <span class="text-5xl font-black text-gray-900 dark:text-white tracking-tight"><?php echo e(showAmount($plan->price, 0)); ?></span>
                            <span class="text-sm font-black text-gray-400 uppercase tracking-widest mb-2">/ One-time</span>
                        </div>
                    </div>
                </div>

                <!-- Plan Right -->
                <div class="lg:w-3/5 p-12 lg:p-16 bg-[#FDFDFD] dark:bg-transparent">
                    <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-[0.2em] mb-12 border-b pb-6 dark:border-white/5">Membership Inclusions</h2>
                    
                    <div class="space-y-10 mb-16">
                        <?php $__currentLoopData = [
                            ['video_library', 'Premium Video Library', 'Gain unlimited access to all high-quality content uploaded under this tier.'],
                            ['playlist_add_check', 'Curated Playlists', 'Access expert-curated collection of videos tailored for this membership.'],
                            ['high_quality', 'Max Video Quality', 'Watch content in up to 4K resolution with priority buffering.'],
                            ['support_agent', 'Direct Creator Line', 'Priority response for your inquiries within 24 business hours.']
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex gap-6">
                            <div class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 shadow-sm border border-gray-100 dark:border-white/10 flex items-center justify-center flex-shrink-0 text-red-600">
                                <span class="material-symbols-rounded text-2xl"><?php echo e($perk[0]); ?></span>
                            </div>
                            <div>
                                <h4 class="font-black text-gray-900 dark:text-white text-xs uppercase tracking-widest mb-1"><?php echo e($perk[1]); ?></h4>
                                <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-relaxed"><?php echo e($perk[2]); ?></p>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div>
                        <button @click="initiatePurchase('<?php echo e($plan->id); ?>')" 
                                :disabled="loading === '<?php echo e($plan->id); ?>'"
                                class="w-full py-6 bg-red-600 text-white rounded-[2rem] font-black text-sm uppercase tracking-[0.2em] shadow-2xl shadow-red-600/30 hover:bg-red-700 hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center gap-2 disabled:opacity-50">
                            <span x-show="loading !== '<?php echo e($plan->id); ?>'">Subscribe Now</span>
                            <span x-show="loading === '<?php echo e($plan->id); ?>'" class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Processing...
                            </span>
                        </button>
                    </div>
                    <p class="text-center text-[9px] font-black text-gray-400 uppercase tracking-widest mt-8">Cancel anytime. Safe & secure payment processing.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        function planPurchase() {
            return {
                loading: null,
                initiatePurchase(planId) {
                    this.loading = planId;
                    fetch(`/client/plans/buy/${planId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.error) {
                            Swal.fire('Error', data.error, 'error');
                            this.loading = null;
                            return;
                        }

                        if (data.redirect) {
                            Swal.fire('Success', data.message || 'Action completed successfully', 'success').then(() => {
                                window.location.href = data.redirect;
                            });
                            return;
                        }

                        if (!data.key) {
                            Swal.fire('Configuration Error', 'Razorpay Key is missing.', 'error');
                            this.loading = null;
                            return;
                        }

                        const options = {
                            key: data.key,
                            amount: data.amount,
                            currency: data.currency,
                            name: "Premium Subscription",
                            description: `Upgrade to ${data.plan_name}`,
                            order_id: data.order_id,
                            handler: (response) => {
                                this.verifyPayment(response, data.trx);
                            },
                            prefill: {
                                name: data.name,
                                email: data.email,
                                contact: data.contact
                            },
                            theme: { color: "#DC2626" },
                            modal: {
                                ondismiss: () => { this.loading = null; }
                            }
                        };
                        const rzp = new Razorpay(options);
                        rzp.open();
                    })
                    .catch(err => {
                        this.loading = null;
                        Swal.fire('Error', 'Gateway Initialization Failed.', 'error');
                    });
                },
                verifyPayment(response, trx) {
                    fetch('<?php echo e(route('user.plans.verify.payment')); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                        },
                        body: JSON.stringify({
                            razorpay_payment_id: response.razorpay_payment_id,
                            razorpay_order_id: response.razorpay_order_id,
                            razorpay_signature: response.razorpay_signature,
                            trx: trx
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Success', data.success, 'success').then(() => {
                                window.location.href = '<?php echo e(route('user.plans.purchased', ['creator_page' => 1])); ?>';
                            });
                        } else {
                            Swal.fire('Error', data.error, 'error');
                        }
                        this.loading = null;
                    })
                    .catch(err => {
                        this.loading = null;
                        Swal.fire('Error', 'Verification request failed.', 'error');
                    });
                }
            }
        }
    </script>
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
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\client\plans\show.blade.php ENDPATH**/ ?>