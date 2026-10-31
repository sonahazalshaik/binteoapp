@extends('admin.layouts.app')

@section('title', 'Identity Verification')
@section('header_title', 'Identity Verification')

@section('content')
<div class="max-w-5xl mx-auto" 
     x-data="{ 
        idType: 'passport',
        errors: {},
        loading: { id_number: false, account_number: false },
        validateInput(name, value) {
            this.errors[name] = null;
            const optionalFields = ['address', 'city', 'state', 'zip', 'branch', 'selfie', 'id_back'];
            if (!value && !optionalFields.includes(name)) {
                this.errors[name] = 'This field is required';
                return;
            }
            if (!value) return;

            if (name === 'dob') {
                if (!/^\d{4}-\d{2}-\d{2}$/.test(value)) {
                    this.errors[name] = 'Enter a valid date of birth';
                } else if (new Date(value) > new Date()) {
                    this.errors[name] = 'Date of birth cannot be in the future';
                }
            }
            if (name === 'zip') {
                if (!/^[A-Za-z0-9][A-Za-z0-9\s-]{2,11}$/.test(value)) {
                    this.errors[name] = 'Invalid postal code format';
                }
            }
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
        validateFile(name, input) {
            this.errors[name] = null;
            const file = input.files && input.files[0];
            const requiredFiles = ['id_front'];
            if (!file) {
                if (requiredFiles.includes(name)) {
                    this.errors[name] = 'This file is required';
                }
                return;
            }
            const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                this.errors[name] = 'Only JPG, PNG, WEBP or GIF images allowed';
            } else if (file.size > 5 * 1024 * 1024) {
                this.errors[name] = 'File size must be under 5MB';
            }
        },
        changedIdType() {
            this.errors.id_type = null;
            this.errors.id_number = null;
            this.validateInput('id_type', this.idType);
            const idEl = document.querySelector('[name=id_number]');
            this.validateInput('id_number', idEl ? idEl.value : '');
        },
        validateForm() {
            ['user_id', 'full_name', 'dob', 'id_type', 'id_number', 'bank_name', 'account_holder', 'account_number', 'ifsc'].forEach((name) => {
                const el = document.querySelector('[name=' + name + ']');
                this.validateInput(name, el ? el.value : '');
            });
            const front = document.querySelector('[name=id_front]');
            if (front) this.validateFile('id_front', front);
            return !Object.values(this.errors).some(v => v !== null);
        },
        submitForm(event) {
            if (!this.validateForm()) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please correct the highlighted fields before submitting.',
                    confirmButtonColor: '#f43f5e',
                });
            }
        },
        checkAvailability(field, value) {
            const userId = document.getElementsByName('user_id')[0].value;
            if(!userId) return;
            this.loading[field] = true;
            fetch('{{ route('admin.users.kyc.check-availability') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
    <form action="{{ route('admin.users.kyc.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="kycForm" novalidate @submit="submitForm($event)">
        @csrf
        
        <!-- User Selection Section -->
        <div class="bg-white dark:bg-[#121212] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm p-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-500 flex items-center justify-center">
                    <span class="material-symbols-rounded">person_add</span>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">Target Identity</h3>
                    <p class="text-[10px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest mt-1">Select the verified node for manual onboarding</p>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Select User (Channel Owners) <span class="text-rose-500">*</span></label>
                <select name="user_id" id="user_select" @change="validateInput('user_id', $event.target.value)" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-rose-500 transition-all outline-none" required>
                    <option value="" disabled selected>Search by username or email...</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" 
                                data-email="{{ $user->email }}" 
                                data-image="{{ $user->image ? getImage(getFilePath('userProfile').'/'.$user->image) : '' }}"
                                data-initials="{{ substr($user->firstname, 0, 1) }}{{ substr($user->lastname, 0, 1) }}">
                            {{ $user->username }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                <p x-show="errors.user_id" class="text-[10px] font-bold text-rose-500 px-4 mt-1" x-text="errors.user_id"></p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Personal Information -->
            <div class="bg-white dark:bg-[#121212] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm p-8 animate-in fade-in slide-in-from-left-4 duration-700 delay-100">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-500 flex items-center justify-center">
                        <span class="material-symbols-rounded">badge</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">Identity Details</h3>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Full Legal Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="full_name" @input="validateInput('full_name', $event.target.value)" :class="errors.full_name ? 'border-rose-500 ring-2 ring-rose-500/10' : ''" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all outline-none" placeholder="As per official documents" required>
                        <p x-show="errors.full_name" class="text-[10px] font-bold text-rose-500 px-4 mt-1" x-text="errors.full_name"></p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Date of Birth <span class="text-rose-500">*</span></label>
                        <input type="date" name="dob" @input="validateInput('dob', $event.target.value)" :class="errors.dob ? 'border-rose-500 ring-2 ring-rose-500/10' : ''" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all outline-none" required>
                        <p x-show="errors.dob" class="text-[10px] font-bold text-rose-500 px-4 mt-1" x-text="errors.dob"></p>
                        <p x-show="errors.dob" class="text-[10px] font-bold text-rose-500 px-4 mt-1" x-text="errors.dob"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Document Type <span class="text-rose-500">*</span></label>
                            <select name="id_type" x-model="idType" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all outline-none" required>
                                <option value="passport">International Passport</option>
                                <option value="aadhaar">Aadhaar Card</option>
                                <option value="driving_license">Driving License</option>
                                <option value="pan_card">PAN Card</option>
                                <option value="voter_id">Voter ID</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">ID / Document Number <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="id_number" @input.debounce.500ms="validateInput('id_number', $event.target.value)" :class="errors.id_number ? 'border-rose-500 ring-2 ring-rose-500/10' : ''" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all outline-none uppercase tracking-widest" placeholder="Document ID#" required>
                                <div x-show="loading.id_number" class="absolute right-4 top-1/2 -translate-y-1/2">
                                    <svg class="animate-spin h-4 w-4 text-blue-500" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>
                            </div>
                            <p x-show="errors.id_number" class="text-[10px] font-bold text-rose-500 px-4 mt-1" x-text="errors.id_number"></p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Full Address</label>
                        <textarea name="address" @input="validateInput('address', $event.target.value)" rows="2" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl p-6 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all outline-none" placeholder="Complete mailing address"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">City</label>
                            <input type="text" name="city" @input="validateInput('city', $event.target.value)" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all outline-none" placeholder="e.g. London">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">State / Province</label>
                            <input type="text" name="state" @input="validateInput('state', $event.target.value)" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all outline-none" placeholder="e.g. Greater London">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Postal / ZIP Code</label>
                        <input type="text" name="zip" @input="validateInput('zip', $event.target.value)" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all outline-none" placeholder="e.g. W1A 1AA">
                    </div>
                </div>
            </div>

            <!-- Banking Information -->
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
                        <input type="text" name="bank_name" @input="validateInput('bank_name', $event.target.value)" :class="errors.bank_name ? 'border-rose-500 ring-2 ring-rose-500/10' : ''" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-emerald-500 transition-all outline-none" placeholder="Official Bank Title" required>
                        <p x-show="errors.bank_name" class="text-[10px] font-bold text-rose-500 px-4 mt-1" x-text="errors.bank_name"></p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Account Holder Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="account_holder" @input="validateInput('account_holder', $event.target.value)" :class="errors.account_holder ? 'border-rose-500 ring-2 ring-rose-500/10' : ''" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-emerald-500 transition-all outline-none" placeholder="As per bank records" required>
                        <p x-show="errors.account_holder" class="text-[10px] font-bold text-rose-500 px-4 mt-1" x-text="errors.account_holder"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Account Number <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="account_number" @input.debounce.500ms="validateInput('account_number', $event.target.value)" :class="errors.account_number ? 'border-rose-500 ring-2 ring-rose-500/10' : ''" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-emerald-500 transition-all outline-none tracking-widest" placeholder="Account ID" required>
                                <div x-show="loading.account_number" class="absolute right-4 top-1/2 -translate-y-1/2">
                                    <svg class="animate-spin h-4 w-4 text-emerald-500" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>
                            </div>
                            <p x-show="errors.account_number" class="text-[10px] font-bold text-rose-500 px-4 mt-1" x-text="errors.account_number"></p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">IFSC / Routing Code <span class="text-rose-500">*</span></label>
                            <input type="text" name="ifsc" @input="validateInput('ifsc', $event.target.value)" :class="errors.ifsc ? 'border-rose-500 ring-2 ring-rose-500/10' : ''" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-emerald-500 transition-all outline-none uppercase tracking-widest" placeholder="Bank Routing Code" required>
                            <p x-show="errors.ifsc" class="text-[10px] font-bold text-rose-500 px-4 mt-1" x-text="errors.ifsc"></p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Branch Name (Optional)</label>
                        <input type="text" name="branch" @input="validateInput('branch', $event.target.value)" class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-emerald-500 transition-all outline-none" placeholder="e.g. Main Street Branch">
                    </div>
                </div>
            </div>
        </div>


        <!-- Document Uploads -->
        <div class="bg-white dark:bg-[#121212] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm p-8 animate-in fade-in slide-in-from-bottom-4 duration-700 delay-300">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center">
                    <span class="material-symbols-rounded">cloud_upload</span>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">Media Verification</h3>
                    <p class="text-[10px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest mt-1">Upload high-resolution scans for digital audit</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Upload Front Side <span class="text-rose-500">*</span></label>
                    <div class="relative group h-48 rounded-3xl border-2 border-dashed border-slate-200 dark:border-white/10 flex flex-col items-center justify-center bg-slate-50/50 dark:bg-white/[0.02] hover:bg-slate-100 dark:hover:bg-white/5 transition-all cursor-pointer overflow-hidden">
                        <input type="file" name="id_front" class="absolute inset-0 opacity-0 cursor-pointer z-10" required onchange="previewImage(this, 'preview_front')">
                        <div id="preview_front_placeholder" class="flex flex-col items-center">
                            <span class="material-symbols-rounded text-4xl text-slate-300 dark:text-white/10 mb-2">upload_file</span>
                            <span class="text-[9px] font-black text-slate-400 uppercase">Select File</span>
                        </div>
                        <img id="preview_front" class="hidden absolute inset-0 w-full h-full object-cover">
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Upload Back Side</label>
                    <div class="relative group h-48 rounded-3xl border-2 border-dashed border-slate-200 dark:border-white/10 flex flex-col items-center justify-center bg-slate-50/50 dark:bg-white/[0.02] hover:bg-slate-100 dark:hover:bg-white/5 transition-all cursor-pointer overflow-hidden">
                        <input type="file" name="id_back" class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="previewImage(this, 'preview_back')">
                        <div id="preview_back_placeholder" class="flex flex-col items-center">
                            <span class="material-symbols-rounded text-4xl text-slate-300 dark:text-white/10 mb-2">upload_file</span>
                            <span class="text-[9px] font-black text-slate-400 uppercase">Select File</span>
                        </div>
                        <img id="preview_back" class="hidden absolute inset-0 w-full h-full object-cover">
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Live Verification</label>
                    <div class="relative group h-48 rounded-3xl border-2 border-dashed border-emerald-500/20 flex flex-col items-center justify-center bg-emerald-500/5 hover:bg-emerald-500/10 transition-all cursor-pointer overflow-hidden">
                        <input type="file" name="selfie" class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="previewImage(this, 'preview_selfie')">
                        <div id="preview_selfie_placeholder" class="flex flex-col items-center">
                            <span class="material-symbols-rounded text-4xl text-emerald-500/30 mb-2">photo_camera</span>
                            <span class="text-[9px] font-black text-emerald-500 uppercase">Select File</span>
                        </div>
                        <img id="preview_selfie" class="hidden absolute inset-0 w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between py-8">
            <a href="{{ route('admin.users.kyc.all') }}" class="h-14 px-8 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-500 dark:text-white/40 flex items-center gap-3 text-[11px] font-black uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-white/10 transition-all ">
                <span class="material-symbols-rounded">arrow_back</span> Abort
            </a>
            <button type="submit" :disabled="Object.values(errors).some(v => v !== null)" :class="Object.values(errors).some(v => v !== null) ? 'opacity-50 cursor-not-allowed' : ''" class="h-14 px-12 rounded-2xl bg-rose-500 text-white flex items-center gap-3 text-[11px] font-black uppercase tracking-widest shadow-xl shadow-rose-500/30 hover:bg-rose-600 hover:scale-105 active:scale-95 transition-all ">
                Commit Onboarding <span class="material-symbols-rounded">done_all</span>
            </button>
        </div>
    </form>
</div>

    </form>
</div>

@push('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 56px !important;
        border-radius: 16px !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
        background-color: #f8fafc !important;
        display: flex;
        align-items: center;
        padding-left: 12px;
    }
    .dark .select2-container--default .select2-selection--single {
        background-color: rgba(255,255,255,0.05) !important;
        border-color: rgba(255,255,255,0.1) !important;
        color: white !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #0f172a !important;
        font-weight: 700;
        font-size: 14px;
    }
    .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: white !important;
    }
    .select2-dropdown {
        border-radius: 16px !important;
        border: 1px solid rgba(255,255,255,0.1) !important;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    .dark .select2-dropdown {
        background-color: #1a1a1a !important;
    }
    .select2-results__option--highlighted[aria-selected] {
        background-color: #3b82f6 !important;
        color: white !important;
    }
    .select2-results__option--highlighted[aria-selected] * {
        color: white !important;
    }
</style>
@endpush

@push('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#user_select').select2({
            width: '100%',
            templateResult: formatUser,
            templateSelection: formatUserSelection
        });
    });

    function formatUser(user) {
        if (!user.id) return user.text;
        const img = $(user.element).data('image');
        const initials = $(user.element).data('initials');
        const email = $(user.element).data('email');
        
        let avatarHtml = '';
        if (img) {
            avatarHtml = `<img src="${img}" class="w-8 h-8 rounded-lg object-cover">`;
        } else {
            avatarHtml = `<div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-rose-500 to-orange-400 text-white flex items-center justify-center text-[10px] font-black uppercase ">${initials}</div>`;
        }

        return $(`<div class="flex items-center gap-3 py-1">
            ${avatarHtml}
            <div>
                <div class="text-[11px] font-black uppercase text-slate-900 dark:text-white ">${user.text.split('(')[0]}</div>
                <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest">${email}</div>
            </div>
        </div>`);
    }

    function formatUserSelection(user) {
        if (!user.id) return user.text;
        return user.text.split('(')[0];
    }

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
@endpush
@endsection

