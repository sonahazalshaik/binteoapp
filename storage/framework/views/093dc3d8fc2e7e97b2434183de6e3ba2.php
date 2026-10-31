<?php $__env->startSection('title', 'Edit Identity Verification'); ?>
<?php $__env->startSection('header_title', 'Edit Identity Verification'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto"
     x-data="{ 
        idType: '<?php echo e($submission->id_type); ?>',
        errors: {},
        loading: { id_number: false, account_number: false },
        validateInput(name, value) {
            this.errors[name] = null;
            const optionalFields = ['address', 'city', 'state', 'zip', 'branch', 'selfie', 'id_back', 'admin_feedback'];
            if (!value && !optionalFields.includes(name)) {
                this.errors[name] = 'This field is required';
                return;
            }
            if (!value) return;

            if (name === 'bank_name' || name === 'full_name' || name === 'city' || name === 'state') {
                if (!/^[A-Za-z\s]{2,100}$/.test(value)) {
                    this.errors[name] = 'Invalid format (Letters and spaces only)';
                }
            }
            if (name === 'account_holder') {
                if (!/^[A-Za-z.'\s]{2,100}$/.test(value)) {
                    this.errors[name] = 'Invalid format (Dots and apostrophes allowed)';
                }
            }
            if (name === 'account_number') {
                if (!/^\d{9,18}$/.test(value)) {
                    this.errors[name] = 'Must be 9-18 digits';
                } else {
                    this.checkAvailability(name, value);
                }
            }
            if (name === 'ifsc') {
                if (!/^[A-Z]{4}0[A-Z0-9]{6}$/.test(value)) {
                    this.errors[name] = 'Invalid IFSC format (e.g. HDFC0001234)';
                }
            }
            if (name === 'id_number') {
                const val = value.toUpperCase();
                let isValidFormat = false;
                if (this.idType === 'pan_card') {
                    if (!/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test(val)) {
                        this.errors[name] = 'Invalid PAN (e.g. ABCDE1234F)';
                    } else isValidFormat = true;
                } else if (this.idType === 'aadhaar') {
                    if (!/^[2-9]{1}[0-9]{11}$/.test(val)) {
                        this.errors[name] = 'Invalid Aadhaar (12 digits, starts 2-9)';
                    } else isValidFormat = true;
                } else if (this.idType === 'passport') {
                    if (!/^[A-Z]{1}[0-9]{7}$/.test(val)) {
                        this.errors[name] = 'Invalid Passport (e.g. K1234567)';
                    } else isValidFormat = true;
                } else if (this.idType === 'driving_license') {
                    if (!/^[A-Z]{2}[0-9]{2}[0-9]{4}[0-9]{7}$/.test(val)) {
                        this.errors[name] = 'Invalid DL (e.g. AP0120191234567)';
                    } else isValidFormat = true;
                } else if (this.idType === 'voter_id') {
                    if (!/^[A-Z]{3}[0-9]{7}$/.test(val)) {
                        this.errors[name] = 'Invalid Voter ID (e.g. ABC1234567)';
                    } else isValidFormat = true;
                }
                if (isValidFormat) this.checkAvailability(name, value);
            }
        },
        checkAvailability(field, value) {
            const userId = '<?php echo e($submission->user_id); ?>';
            this.loading[field] = true;
            fetch('<?php echo e(route('admin.users.kyc.check-availability')); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({ field: field, value: value, user_id: userId })
            })
            .then(res => res.json())
            .then(data => {
                this.loading[field] = false;
                if (data.error) this.errors[field] = data.error;
            });
        }
     }">
    <form action="<?php echo e(route('admin.users.kyc.update', $submission->id)); ?>" method="POST" enctype="multipart/form-data" class="space-y-8" id="kycForm">
        <?php echo csrf_field(); ?>
        
        <!-- User Context Section -->
        <div class="bg-white dark:bg-[#121212] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm p-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-rose-500 to-orange-400 text-white flex items-center justify-center overflow-hidden">
                        <?php if($submission->user->image): ?>
                            <img src="<?php echo e(getImage(getFilePath('userProfile').'/'.$submission->user->image)); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <span class="text-2xl font-black uppercase "><?php echo e(substr($submission->user->firstname, 0, 1)); ?><?php echo e(substr($submission->user->lastname, 0, 1)); ?></span>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter"><?php echo e($submission->user->username); ?></h3>
                        <p class="text-[10px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest mt-1">Modifying verified node: #<?php echo e($submission->id); ?></p>
                    </div>
                </div>
                <div class="text-right">
                    <?php echo $submission->status_badge ?>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-2">Submitted: <?php echo e($submission->created_at->format('M d, Y')); ?></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Personal Details -->
            <div class="bg-white dark:bg-[#121212] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm p-8 animate-in fade-in slide-in-from-left-4 duration-700 delay-100">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-500 flex items-center justify-center">
                        <span class="material-symbols-rounded">badge</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">Personal Details</h3>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Full Name (As per ID) <span class="text-rose-500">*</span></label>
                        <input type="text" name="full_name" value="<?php echo e($submission->full_name); ?>" @input="validateInput('full_name', $event.target.value)" :class="errors.full_name ? 'border-rose-500 ring-2 ring-rose-500/10' : ''" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all outline-none" required>
                        <p x-show="errors.full_name" class="text-[10px] font-bold text-rose-500 px-4 mt-1" x-text="errors.full_name"></p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Date of Birth <span class="text-rose-500">*</span></label>
                        <input type="date" name="dob" value="<?php echo e($submission->date_of_birth ? $submission->date_of_birth->format('Y-m-d') : ''); ?>" @input="validateInput('dob', $event.target.value)" :class="errors.dob ? 'border-rose-500 ring-2 ring-rose-500/10' : ''" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all outline-none" required>
                        <p x-show="errors.dob" class="text-[10px] font-bold text-rose-500 px-4 mt-1" x-text="errors.dob"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Document Type <span class="text-rose-500">*</span></label>
                            <select name="id_type" x-model="idType" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all outline-none" required>
                                <option value="passport" <?php echo e($submission->id_type == 'passport' ? 'selected' : ''); ?>>International Passport</option>
                                <option value="aadhaar" <?php echo e($submission->id_type == 'aadhaar' ? 'selected' : ''); ?>>Aadhaar Card</option>
                                <option value="driving_license" <?php echo e($submission->id_type == 'driving_license' ? 'selected' : ''); ?>>Driving License</option>
                                <option value="pan_card" <?php echo e($submission->id_type == 'pan_card' ? 'selected' : ''); ?>>PAN Card</option>
                                <option value="voter_id" <?php echo e($submission->id_type == 'voter_id' ? 'selected' : ''); ?>>Voter ID</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">ID / Document Number <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="id_number" value="<?php echo e($submission->id_number); ?>" @input.debounce.500ms="validateInput('id_number', $event.target.value)" :class="errors.id_number ? 'border-rose-500 ring-2 ring-rose-500/10' : ''" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all outline-none uppercase tracking-widest" required>
                                <div x-show="loading.id_number" class="absolute right-4 top-1/2 -translate-y-1/2">
                                    <svg class="animate-spin h-4 w-4 text-blue-500" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>
                            </div>
                            <p x-show="errors.id_number" class="text-[10px] font-bold text-rose-500 px-4 mt-1" x-text="errors.id_number"></p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Full Address</label>
                        <textarea name="address" @input="validateInput('address', $event.target.value)" rows="2" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl p-6 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all outline-none"><?php echo e($submission->address); ?></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">City</label>
                            <input type="text" name="city" value="<?php echo e($submission->city); ?>" @input="validateInput('city', $event.target.value)" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">State / Province</label>
                            <input type="text" name="state" value="<?php echo e($submission->state); ?>" @input="validateInput('state', $event.target.value)" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Postal / ZIP Code</label>
                        <input type="text" name="zip" value="<?php echo e($submission->postal_code); ?>" @input="validateInput('zip', $event.target.value)" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                    </div>
                </div>
            </div>

            <!-- Settlement Account -->
            <div class="bg-white dark:bg-[#121212] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm p-8 animate-in fade-in slide-in-from-right-4 duration-700 delay-200">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                        <span class="material-symbols-rounded">account_balance</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">Settlement Account</h3>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Bank Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="bank_name" value="<?php echo e($submission->bank_name); ?>" @input="validateInput('bank_name', $event.target.value)" :class="errors.bank_name ? 'border-rose-500 ring-2 ring-rose-500/10' : ''" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-emerald-500 transition-all outline-none" required>
                        <p x-show="errors.bank_name" class="text-[10px] font-bold text-rose-500 px-4 mt-1" x-text="errors.bank_name"></p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Account Holder Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="account_holder" value="<?php echo e($submission->account_holder_name); ?>" @input="validateInput('account_holder', $event.target.value)" :class="errors.account_holder ? 'border-rose-500 ring-2 ring-rose-500/10' : ''" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-emerald-500 transition-all outline-none" required>
                        <p x-show="errors.account_holder" class="text-[10px] font-bold text-rose-500 px-4 mt-1" x-text="errors.account_holder"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Account Number <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="account_number" value="<?php echo e($submission->account_number); ?>" @input.debounce.500ms="validateInput('account_number', $event.target.value)" :class="errors.account_number ? 'border-rose-500 ring-2 ring-rose-500/10' : ''" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-emerald-500 transition-all outline-none tracking-widest" required>
                                <div x-show="loading.account_number" class="absolute right-4 top-1/2 -translate-y-1/2">
                                    <svg class="animate-spin h-4 w-4 text-emerald-500" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>
                            </div>
                            <p x-show="errors.account_number" class="text-[10px] font-bold text-rose-500 px-4 mt-1" x-text="errors.account_number"></p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">IFSC / Routing Code <span class="text-rose-500">*</span></label>
                            <input type="text" name="ifsc" value="<?php echo e($submission->ifsc_code); ?>" @input="validateInput('ifsc', $event.target.value)" :class="errors.ifsc ? 'border-rose-500 ring-2 ring-rose-500/10' : ''" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-emerald-500 transition-all outline-none uppercase tracking-widest" required>
                            <p x-show="errors.ifsc" class="text-[10px] font-bold text-rose-500 px-4 mt-1" x-text="errors.ifsc"></p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Branch Name (Optional)</label>
                        <input type="text" name="branch" value="<?php echo e($submission->branch_name); ?>" @input="validateInput('branch', $event.target.value)" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-emerald-500 transition-all outline-none">
                    </div>

                    <!-- Audit Control -->
                    <div class="pt-8 border-t border-slate-100 dark:border-white/5 space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Audit Decision</label>
                            <select name="status" class="w-full h-14 bg-slate-100 dark:bg-white/10 border-transparent rounded-2xl px-6 text-sm font-black uppercase focus:ring-2 focus:ring-rose-500 transition-all outline-none cursor-pointer">
                                <option value="0" <?php echo e($submission->status == 0 ? 'selected' : ''); ?>>Pending Review</option>
                                <option value="1" <?php echo e($submission->status == 1 ? 'selected' : ''); ?>>Verified / Approved</option>
                                <option value="2" <?php echo e($submission->status == 2 ? 'selected' : ''); ?>>Rejected / Flagged</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Administrative Feedback</label>
                            <textarea name="admin_feedback" @input="validateInput('admin_feedback', $event.target.value)" rows="3" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl p-6 text-sm font-bold focus:ring-2 focus:ring-rose-500 transition-all outline-none" placeholder="Reason for rejection or internal notes..."><?php echo e($submission->admin_feedback); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Identity Documents -->
        <div class="bg-white dark:bg-[#121212] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm p-8 animate-in fade-in slide-in-from-bottom-4 duration-700 delay-300">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center">
                    <span class="material-symbols-rounded">cloud_upload</span>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">Identity Documents</h3>
                    <p class="text-[10px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest mt-1">Update high-resolution scans for digital audit</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Upload Front Side</label>
                    <div class="relative group h-48 rounded-3xl border-2 border-dashed border-slate-200 dark:border-white/10 flex flex-col items-center justify-center bg-slate-50/50 dark:bg-white/[0.02] hover:bg-slate-100 dark:hover:bg-white/5 transition-all cursor-pointer overflow-hidden">
                        <input type="file" name="id_front" class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="previewImage(this, 'preview_front')">
                        <div id="preview_front_placeholder" class="flex flex-col items-center <?php echo e($submission->id_document_front ? 'hidden' : ''); ?>">
                            <span class="material-symbols-rounded text-4xl text-slate-300 dark:text-white/10 mb-2">upload_file</span>
                            <span class="text-[9px] font-black text-slate-400 uppercase">Change File</span>
                        </div>
                        <img id="preview_front" src="<?php echo e($submission->id_document_front ? \App\Helpers\ImageHelper::getPhotoUrl($submission->id_document_front) : ''); ?>" class="<?php echo e($submission->id_document_front ? '' : 'hidden'); ?> absolute inset-0 w-full h-full object-cover">
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Upload Back Side</label>
                    <div class="relative group h-48 rounded-3xl border-2 border-dashed border-slate-200 dark:border-white/10 flex flex-col items-center justify-center bg-slate-50/50 dark:bg-white/[0.02] hover:bg-slate-100 dark:hover:bg-white/5 transition-all cursor-pointer overflow-hidden">
                        <input type="file" name="id_back" class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="previewImage(this, 'preview_back')">
                        <div id="preview_back_placeholder" class="flex flex-col items-center <?php echo e($submission->id_document_back ? 'hidden' : ''); ?>">
                            <span class="material-symbols-rounded text-4xl text-slate-300 dark:text-white/10 mb-2">upload_file</span>
                            <span class="text-[9px] font-black text-slate-400 uppercase">Change File</span>
                        </div>
                        <img id="preview_back" src="<?php echo e($submission->id_document_back ? \App\Helpers\ImageHelper::getPhotoUrl($submission->id_document_back) : ''); ?>" class="<?php echo e($submission->id_document_back ? '' : 'hidden'); ?> absolute inset-0 w-full h-full object-cover">
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Live Verification</label>
                    <div class="relative group h-48 rounded-3xl border-2 border-dashed border-emerald-500/20 flex flex-col items-center justify-center bg-emerald-500/5 hover:bg-emerald-500/10 transition-all cursor-pointer overflow-hidden">
                        <input type="file" name="selfie" class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="previewImage(this, 'preview_selfie')">
                        <div id="preview_selfie_placeholder" class="flex flex-col items-center <?php echo e($submission->selfie_image ? 'hidden' : ''); ?>">
                            <span class="material-symbols-rounded text-4xl text-emerald-500/30 mb-2">photo_camera</span>
                            <span class="text-[9px] font-black text-emerald-500 uppercase">Change File</span>
                        </div>
                        <img id="preview_selfie" src="<?php echo e($submission->selfie_image ? \App\Helpers\ImageHelper::getPhotoUrl($submission->selfie_image) : ''); ?>" class="<?php echo e($submission->selfie_image ? '' : 'hidden'); ?> absolute inset-0 w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between py-8">
            <a href="<?php echo e(route('admin.users.kyc.all')); ?>" class="h-14 px-8 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-500 dark:text-white/40 flex items-center gap-3 text-[11px] font-black uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-white/10 transition-all ">
                <span class="material-symbols-rounded">arrow_back</span> Cancel
            </a>
            <button type="submit" :disabled="Object.values(errors).some(v => v !== null)" :class="Object.values(errors).some(v => v !== null) ? 'opacity-50 cursor-not-allowed' : ''" class="h-14 px-12 rounded-2xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 flex items-center gap-3 text-[11px] font-black uppercase tracking-widest shadow-xl hover:scale-105 active:scale-95 transition-all ">
                Save KYC <span class="material-symbols-rounded">save</span>
            </button>
        </div>
    </form>
</div>

<?php $__env->startPush('script'); ?>
<script>
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(`#${previewId}`).attr('src', e.target.result).removeClass('hidden');
                $(`#${previewId}_placeholder`).addClass('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\kyc\edit.blade.php ENDPATH**/ ?>