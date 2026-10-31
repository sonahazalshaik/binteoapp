<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    function planPurchase() {
        return {
            loading: null,
            initiatePurchase(planId) {
                console.log('Alpine: initiatePurchase called for', planId);
                this.loading = planId;
                fetch('<?php echo e(route('marketplace.plan.buy', ['id' => ':id'])); ?>'.replace(':id', planId), {
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
                        Swal.fire('Configuration Error', 'Razorpay Key is missing in .env.', 'error');
                        this.loading = null;
                        return;
                    }

                    const options = {
                        key: data.key,
                        amount: data.amount,
                        currency: data.currency,
                        name: "Marketplace Talent Upgrade",
                        description: `Subscribe to ${data.plan_name}`,
                        order_id: data.order_id,
                        handler: (response) => {
                            this.verifyPayment(response, data.trx);
                        },
                        prefill: {
                            name: data.name,
                            email: data.email,
                            contact: data.contact
                        },
                        theme: { color: "#EF4444" },
                        modal: {
                            ondismiss: () => { 
                                this.loading = null; 
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Payment Cancelled',
                                    text: 'You cancelled the payment process. Your subscription has not been updated.',
                                    confirmButtonColor: '#EF4444'
                                });
                            }
                        }
                    };
                    const rzp = new Razorpay(options);
                    rzp.open();
                })
                .catch(err => {
                    console.error(err);
                    this.loading = null;
                    Swal.fire('Error', 'Gateway Initialization Failed', 'error');
                });
            },
            verifyPayment(response, trx) {
                fetch('<?php echo e(route('marketplace.plan.verify')); ?>', {
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
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error', data.error, 'error');
                    }
                    this.loading = null;
                });
            }
        }
    }

    function uploadPortfolioImage(input) {
        if (input.files && input.files[0]) {
            Swal.fire({
                html: `
                    <div class="flex flex-col items-center justify-center p-6">
                        <div class="w-16 h-16 border-4 border-red-500/20 border-t-red-500 rounded-full animate-spin mb-6"></div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-widest mb-2">Uploading</h3>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest leading-relaxed text-center">Please wait while your masterpiece is being uploaded to the gallery...</p>
                    </div>
                `,
                showConfirmButton: false,
                allowOutsideClick: false,
                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                customClass: {
                    popup: 'rounded-[2.5rem] border border-slate-100 dark:border-white/5 shadow-2xl'
                }
            });
            input.form.submit();
        }
    }

    function showServiceLoader(message = 'Processing your service request...') {
        Swal.fire({
            html: `
                <div class="flex flex-col items-center justify-center p-6">
                    <div class="w-16 h-16 border-4 border-indigo-500/20 border-t-indigo-500 rounded-full animate-spin mb-6"></div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-widest mb-2">Please Wait</h3>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest leading-relaxed text-center">${message}</p>
                </div>
            `,
            showConfirmButton: false,
            allowOutsideClick: false,
            background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
            customClass: {
                popup: 'rounded-[2.5rem] border border-slate-100 dark:border-white/5 shadow-2xl'
            }
        });
    }

    function handlePortfolioSubmit(form, message) {
        // Guard against double-click / double-submit (first click wins)
        if (form.dataset.submitted === '1') return false;
        form.dataset.submitted = '1';
        // Inline button loader: immediate feedback on the clicked button
        var btn = form.querySelector('[data-submit-btn]');
        if (btn) {
            btn.disabled = true;
            btn.classList.add('opacity-70', 'pointer-events-none');
            btn.innerHTML = '<span class="inline-block w-4 h-4 border-2 border-white/40 border-t-white rounded-full animate-spin align-middle"></span> <span class="align-middle">Saving...</span>';
        }
        showServiceLoader(message || 'Publishing your portfolio...');
        return true;
    }
</script>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\marketplace\sections\scripts.blade.php ENDPATH**/ ?>