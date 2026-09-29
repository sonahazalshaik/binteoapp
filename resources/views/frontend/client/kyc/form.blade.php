<x-app-layout>
    <div class="py-4 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500" 
         x-data="{ 
            step: 1, 
            totalSteps: 4,
            selfieCaptured: false,
            idType: 'passport',
            errors: {},
            previews: {
                id_front: null,
                id_back: null
            },
            kycStream: null,
            loading: { id_number: false, account_number: false },
            validateInput(name, value) {
                this.errors[name] = null;
                
                const optionalFields = ['address', 'city', 'state', 'zip', 'branch'];
                if (!value) {
                    if (!optionalFields.includes(name)) {
                        this.errors[name] = 'This field is required';
                    }
                    return;
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
                    }
                }

                if (name === 'ifsc') {
                    if (!/^[A-Z]{4}0[A-Z0-9]{6}$/.test(value)) {
                        this.errors[name] = 'Invalid IFSC format (e.g. HDFC0001234)';
                    }
                }

                if (name === 'branch' && value) {
                    if (!/^[A-Za-z0-9,\-\s]{2,100}$/.test(value)) {
                        this.errors[name] = 'Invalid branch format';
                    }
                }

                if (name === 'zip') {
                    if (!/^[a-zA-Z0-9\s]{3,10}$/.test(value)) {
                        this.errors[name] = 'Invalid ZIP/Postal code format';
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

                    if (isValidFormat) {
                        this.checkAvailability(name, value);
                    }
                }

                if (name === 'account_number') {
                    if (!/^\d{9,18}$/.test(value)) {
                        this.errors[name] = 'Must be 9-18 digits';
                    } else {
                        this.checkAvailability(name, value);
                    }
                }
            },
            checkAvailability(field, value) {
                this.loading[field] = true;
                fetch('{{ route('user.kyc.check-availability') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ field: field, value: value })
                })
                .then(res => res.json())
                .then(data => {
                    this.loading[field] = false;
                    if (data.error) {
                        this.errors[field] = data.error;
                    }
                });
            },
            nextStep() {
                if (this.validateStep()) {
                    if (this.step < this.totalSteps) {
                        this.step++;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                }
            },
            prevStep() {
                if (this.step > 1) {
                    this.step--;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            },
            validateStep() {
                this.errors = {};
                const stepEl = document.getElementById(`step${this.step}`);
                const inputs = stepEl.querySelectorAll('input[required], select[required], textarea[required]');
                let isValid = true;

                inputs.forEach(input => {
                    if (!input.value || (input.type === 'file' && input.files.length === 0)) {
                        this.errors[input.name] = 'This field is required';
                        isValid = false;
                    }
                });

                if (!isValid) return false;

                // Step Specific Strict Validation
                if (this.step === 2) {
                    const idNumberInput = document.getElementsByName('id_number')[0];
                    const idNumber = idNumberInput.value.toUpperCase();
                    
                    if (this.idType === 'pan_card') {
                        if (!/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test(idNumber)) {
                            this.errors['id_number'] = 'PAN: 5 Letters, 4 Digits, 1 Letter';
                            isValid = false;
                        }
                    } else if (this.idType === 'aadhaar') {
                        if (!/^[2-9]{1}[0-9]{11}$/.test(idNumber)) {
                            this.errors['id_number'] = 'Aadhaar: 12 digits (starts with 2-9)';
                            isValid = false;
                        }
                    } else if (this.idType === 'passport') {
                        if (!/^[A-Z]{1}[0-9]{7}$/.test(idNumber)) {
                            this.errors['id_number'] = 'Passport: 1 Letter, 7 Digits';
                            isValid = false;
                        }
                    } else if (this.idType === 'driving_license') {
                        if (!/^[A-Z]{2}[0-9]{2}[0-9]{4}[0-9]{7}$/.test(idNumber)) {
                            this.errors['id_number'] = 'DL: State(2), RTO(2), Year(4), ID(7)';
                            isValid = false;
                        }
                    } else if (this.idType === 'voter_id') {
                        if (!/^[A-Z]{3}[0-9]{7}$/.test(idNumber)) {
                            this.errors['id_number'] = 'Voter ID: 3 Letters, 7 Digits';
                            isValid = false;
                        }
                    }
                }

                if (this.step === 3 && !this.selfieCaptured) {
                    this.errors['selfie'] = 'Live verification photo is required';
                    isValid = false;
                }

                if (this.step === 4) {
                    const accNo = document.getElementsByName('account_number')[0].value;
                    const ifsc = document.getElementsByName('ifsc')[0].value.toUpperCase();

                    if (!/^[0-9]{9,18}$/.test(accNo)) {
                        this.errors['account_number'] = 'Account number must be 9-18 digits';
                        isValid = false;
                    }
                    if (!/^[A-Z]{4}0[A-Z0-9]{6}$/.test(ifsc)) {
                        this.errors['ifsc'] = 'Invalid IFSC format (e.g. HDFC0001234)';
                        isValid = false;
                    }
                    const bankName = document.getElementsByName('bank_name')[0].value;
                    const holderName = document.getElementsByName('account_holder')[0].value;
                    if (!/^[A-Za-z\s]{2,100}$/.test(bankName)) {
                        this.errors['bank_name'] = 'Invalid bank name';
                        isValid = false;
                    }
                    if (!/^[A-Za-z.'\s]{2,100}$/.test(holderName)) {
                        this.errors['account_holder'] = 'Invalid holder name';
                        isValid = false;
                    }
                }

                return isValid;
            },
            submitForm() {
                if (this.validateStep()) {
                    Swal.fire({
                        html: `
                            <div class='flex flex-col items-center justify-center p-6'>
                                <div class='w-16 h-16 border-4 border-orange-500/20 border-t-orange-500 rounded-full animate-spin mb-2'></div>
                                <h3 class='text-xl font-black text-slate-900 dark:text-white uppercase tracking-widest mb-2'>Submitting Audit</h3>
                                <p class='text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-relaxed opacity-70'>Please wait while we encrypt and dispatch your identity data...</p>
                            </div>
                        `,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                        customClass: {
                            popup: 'rounded-[3rem] border border-slate-100 dark:border-white/5 shadow-2xl'
                        }
                    });
                    document.getElementById('kycForm').submit();
                }
            },
            handleImagePreview(event, type) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.previews[type] = e.target.result;
                        delete this.errors[type];
                    };
                    reader.readAsDataURL(file);
                }
            },
            async startCamera() {
                const video = document.getElementById('video');
                try {
                    if (this.kycStream) return;
                    this.kycStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
                    if(video) {
                        video.srcObject = this.kycStream;
                        video.classList.remove('hidden');
                    }
                } catch (err) {
                    console.error('Camera error:', err);
                    alert('Unable to access camera. Please ensure you have granted permission.');
                }
            },
            stopCamera() {
                if (this.kycStream) {
                    this.kycStream.getTracks().forEach(track => track.stop());
                    this.kycStream = null;
                }
            },
            captureSelfie() {
                const video = document.getElementById('video');
                const canvas = document.getElementById('canvas');
                const capturedImage = document.getElementById('capturedImage');
                const selfieDataInput = document.getElementById('selfie_image_data');
                const snapBtn = document.getElementById('snapBtn');
                const retakeBtn = document.getElementById('retakeBtn');

                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                const data = canvas.toDataURL('image/jpeg');
                selfieDataInput.value = data;
                capturedImage.src = data;
                capturedImage.classList.remove('hidden');
                video.classList.add('hidden');
                snapBtn.classList.add('hidden');
                retakeBtn.classList.remove('hidden');
                this.selfieCaptured = true;
                delete this.errors['selfie'];
            },
            retakeSelfie() {
                const video = document.getElementById('video');
                const capturedImage = document.getElementById('capturedImage');
                const selfieDataInput = document.getElementById('selfie_image_data');
                const snapBtn = document.getElementById('snapBtn');
                const retakeBtn = document.getElementById('retakeBtn');

                selfieDataInput.value = '';
                capturedImage.classList.add('hidden');
                video.classList.remove('hidden');
                snapBtn.classList.remove('hidden');
                retakeBtn.classList.add('hidden');
                this.selfieCaptured = false;
            }
         }" 
         x-init="$watch('step', value => { if(value === 3) startCamera(); else stopCamera(); })">
        
        <div class="max-w-3xl mx-auto px-4">
            <!-- Header -->
            <div class="mb-2 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <a href="{{ route('user.kyc.data') }}" class="inline-flex items-center gap-2 text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 hover:text-orange-500 transition-all">
                        <span class="material-symbols-rounded text-lg">arrow_back</span>
                        Back to Status
                    </a>
                    <h1 class="text-xl md:text-2xl font-black text-gray-900 dark:text-white tracking-tighter">Identity Verification</h1>
                    <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                        Premium Secure Audit Protocol
                    </p>
                </div>
                
                <!-- Step Indicator -->
                <div class="flex items-center gap-3 bg-white dark:bg-white/5 p-4 rounded-3xl border border-gray-100 dark:border-white/5 shadow-sm">
                    <template x-for="i in totalSteps" :key="i">
                        <div class="step-dot w-3 h-3 rounded-full transition-all duration-500" 
                             :class="{ 
                                'bg-orange-500 shadow-lg shadow-orange-500/30 scale-125': i <= step, 
                                'bg-gray-200 dark:bg-white/10': i > step,
                                'ring-4 ring-orange-500/20': i === step
                             }"></div>
                    </template>
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-2" x-text="`Step ${step} of 4`"></span>
                </div>
            </div>

            <form action="{{ route('user.kyc.submit') }}" method="POST" enctype="multipart/form-data" id="kycForm" @submit.prevent="submitForm()" class="space-y-6 pb-20">
                @csrf
                <input type="hidden" name="selfie_image_data" id="selfie_image_data">

                <!-- Step 1: Personal Info -->
                <div class="kyc-step" id="step1" x-show="step === 1" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="bg-white dark:bg-[#181818] rounded-2xl p-4 sm:p-6 border border-gray-100 dark:border-white/5 shadow-sm relative overflow-hidden transition-all duration-500">
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-orange-600/5 rounded-full blur-[100px]"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-2">
                                <div class="w-8 h-8 rounded-lg bg-orange-600 text-white flex items-center justify-center shadow-xl shadow-orange-500/20">
                                    <span class="material-symbols-rounded">person</span>
                                </div>
                                <h2 class="text-sm font-black text-gray-900 dark:text-white tracking-tighter uppercase">Personal Details</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="flex items-center justify-between px-4">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Full Name (As per ID)</span>
                                        <span class="text-[7px] font-black text-rose-500 uppercase">Required</span>
                                    </label>
                                    <input type="text" name="full_name" required placeholder="John Doe" 
                                           @input="$event.target.value = $event.target.value.replace(/[0-9]/g, ''); validateInput('full_name', $event.target.value)"
                                           :class="errors.full_name ? 'border-rose-500 ring-2 ring-rose-500/10' : ''"
                                           class="w-full px-2 py-3 bg-gray-50 dark:bg-white/2 border border-gray-100 dark:border-white/5 rounded-md text-[10px] font-bold focus:ring-2 focus:ring-orange-500 outline-none transition-all uppercase">
                                    <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest px-4 mt-1 opacity-70">Letters only (A-Z). Numbers are automatically blocked.</p>
                                    <p x-show="errors.full_name" class="text-[9px] font-bold text-rose-500 px-4 mt-1" x-text="errors.full_name"></p>
                                </div>
                                <div class="space-y-1">
                                    <label class="flex items-center justify-between px-4">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Date of Birth</span>
                                        <span class="text-[7px] font-black text-rose-500 uppercase">Required</span>
                                    </label>
                                    <input type="date" name="dob" required 
                                           :class="errors.dob ? 'border-rose-500 ring-2 ring-rose-500/10' : ''"
                                           class="w-full px-2 py-3 bg-gray-50 dark:bg-white/2 border border-gray-100 dark:border-white/5 rounded-md text-[10px] font-bold focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                                    <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest px-4 mt-1 opacity-70">Select your date of birth as recorded on your ID</p>
                                    <p x-show="errors.dob" class="text-[9px] font-bold text-rose-500 px-4 mt-1" x-text="errors.dob"></p>
                                </div>
                                <div class="md:col-span-2 space-y-1">
                                    <label class="flex items-center justify-between px-4">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Full Address</span>
                                        <span class="text-[7px] font-black text-gray-400 uppercase">Optional</span>
                                    </label>
                                    <textarea name="address" placeholder="Residential Address" rows="2" 
                                              @input="validateInput('address', $event.target.value)"
                                              :class="errors.address ? 'border-rose-500 ring-2 ring-rose-500/10' : ''"
                                              class="w-full px-2 py-3 bg-gray-50 dark:bg-white/2 border border-gray-100 dark:border-white/5 rounded-md text-[10px] font-bold focus:ring-2 focus:ring-orange-500 outline-none transition-all resize-none"></textarea>
                                    <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest px-4 mt-1 opacity-70">Enter your complete residential or mailing address</p>
                                    <p x-show="errors.address" class="text-[9px] font-bold text-rose-500 px-4 mt-1" x-text="errors.address" style="display: none;"></p>
                                </div>
                                <div class="space-y-1">
                                    <label class="flex items-center justify-between px-4">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">City</span>
                                        <span class="text-[7px] font-black text-gray-400 uppercase">Optional</span>
                                    </label>
                                    <input type="text" name="city" placeholder="London" 
                                           @input="$event.target.value = $event.target.value.replace(/[0-9]/g, ''); validateInput('city', $event.target.value)"
                                           :class="errors.city ? 'border-rose-500 ring-2 ring-rose-500/10' : ''"
                                           class="w-full px-2 py-3 bg-gray-50 dark:bg-white/2 border border-gray-100 dark:border-white/5 rounded-md text-[10px] font-bold focus:ring-2 focus:ring-orange-500 outline-none transition-all uppercase">
                                    <p x-show="errors.city" class="text-[9px] font-bold text-rose-500 px-4 mt-1" x-text="errors.city" style="display: none;"></p>
                                </div>
                                <div class="space-y-1">
                                    <label class="flex items-center justify-between px-4">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">State / Province</span>
                                        <span class="text-[7px] font-black text-gray-400 uppercase">Optional</span>
                                    </label>
                                    <input type="text" name="state" placeholder="Greater London" 
                                           @input="$event.target.value = $event.target.value.replace(/[0-9]/g, ''); validateInput('state', $event.target.value)"
                                           :class="errors.state ? 'border-rose-500 ring-2 ring-rose-500/10' : ''"
                                           class="w-full px-2 py-3 bg-gray-50 dark:bg-white/2 border border-gray-100 dark:border-white/5 rounded-md text-[10px] font-bold focus:ring-2 focus:ring-orange-500 outline-none transition-all uppercase">
                                    <p x-show="errors.state" class="text-[9px] font-bold text-rose-500 px-4 mt-1" x-text="errors.state" style="display: none;"></p>
                                </div>
                                <div class="space-y-1">
                                    <label class="flex items-center justify-between px-4">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Postal / ZIP Code</span>
                                        <span class="text-[7px] font-black text-gray-400 uppercase">Optional</span>
                                    </label>
                                    <input type="text" name="zip" placeholder="W1A 1AA" 
                                           @input="$event.target.value = $event.target.value.toUpperCase().replace(/[^A-Z0-9\s]/g, '').slice(0, 10); validateInput('zip', $event.target.value)"
                                           :class="errors.zip ? 'border-rose-500 ring-2 ring-rose-500/10' : ''"
                                           class="w-full px-2 py-3 bg-gray-50 dark:bg-white/2 border border-gray-100 dark:border-white/5 rounded-md text-[10px] font-bold focus:ring-2 focus:ring-orange-500 outline-none transition-all uppercase tracking-widest">
                                    <p x-show="errors.zip" class="text-[10px] font-bold text-rose-500 px-4" x-text="errors.zip"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: ID Verification -->
                <div class="kyc-step" id="step2" x-show="step === 2" x-cloak x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="bg-white dark:bg-[#181818] rounded-2xl p-4 sm:p-6 border border-gray-100 dark:border-white/5 shadow-sm relative overflow-hidden transition-all duration-500">
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-orange-600/5 rounded-full blur-[100px]"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-2">
                                <div class="w-8 h-8 rounded-lg bg-orange-600 text-white flex items-center justify-center shadow-xl shadow-orange-500/20">
                                    <span class="material-symbols-rounded">badge</span>
                                </div>
                                <h2 class="text-sm font-black text-gray-900 dark:text-white tracking-tighter uppercase">Identity Documents</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="flex items-center justify-between px-4">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Document Type</span>
                                        <span class="text-[7px] font-black text-rose-500 uppercase">Required</span>
                                    </label>
                                    <select name="id_type" x-model="idType" required class="w-full px-2 py-3 bg-gray-50 dark:bg-white/2 border border-gray-100 dark:border-white/5 rounded-md text-[10px] font-bold focus:ring-2 focus:ring-orange-500 outline-none transition-all appearance-none cursor-pointer uppercase">
                                        <option value="passport">International Passport</option>
                                        <option value="aadhaar">Aadhaar Card</option>
                                        <option value="driving_license">Driving License</option>
                                        <option value="pan_card">PAN Card</option>
                                        <option value="voter_id">Voter ID</option>
                                    </select>
                                </div>
                                <div class="space-y-1">
                                    <label class="flex items-center justify-between px-4">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">ID / Document Number</span>
                                        <span class="text-[7px] font-black text-rose-500 uppercase">Required</span>
                                    </label>
                                    <input type="text" name="id_number" required 
                                           :maxlength="idType === 'aadhaar' ? 12 : (idType === 'driving_license' ? 15 : (idType === 'passport' ? 8 : 10))"
                                           @input.debounce.500ms="validateInput('id_number', $event.target.value)"
                                           :placeholder="idType === 'aadhaar' ? '234567890123' : (idType === 'pan_card' ? 'ABCDE1234F' : (idType === 'passport' ? 'K1234567' : 'Enter ID Number'))" 
                                           :class="errors.id_number ? 'border-rose-500 ring-2 ring-rose-500/10' : ''"
                                           class="w-full px-3 py-3.5 bg-gray-50 dark:bg-white/2 border border-gray-100 dark:border-white/5 rounded-md text-[10px] font-bold focus:ring-2 focus:ring-orange-500 outline-none transition-all uppercase tracking-widest">
                                    <div class="flex items-center gap-2 mt-1 px-4">
                                        <p x-show="errors.id_number" class="text-[10px] font-bold text-rose-500" x-text="errors.id_number"></p>
                                        <template x-if="loading.id_number">
                                            <svg class="animate-spin h-3 w-3 text-orange-500" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        </template>
                                    </div>
                                </div>

                                <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4">
                                    <!-- Front Side -->
                                    <div class="image-upload-wrapper group relative">
                                        <label :class="errors.id_front ? 'border-rose-500 ring-2 ring-rose-500/10' : ''" 
                                               class="block h-40 bg-gray-50 dark:bg-white/2 border-2 border-dashed border-gray-200 dark:border-white/10 rounded-2xl cursor-pointer hover:border-orange-500/50 transition-all relative overflow-hidden">
                                            <input type="file" name="id_front" accept="image/*" class="hidden" required @change="handleImagePreview($event, 'id_front')">
                                            
                                            <div class="absolute inset-0 flex flex-col items-center justify-center gap-3 p-6 text-center" x-show="!previews.id_front">
                                                <div class="w-14 h-14 rounded-2xl bg-white dark:bg-white/5 flex items-center justify-center text-gray-400 shadow-sm group-hover:scale-110 transition-transform">
                                                    <span class="material-symbols-rounded text-2xl">upload_file</span>
                                                </div>
                                                <span class="text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-widest">Upload Front Side</span>
                                                <p class="text-[10px] font-bold text-rose-500 uppercase tracking-tighter">Required Phase</p>
                                            </div>

                                            <template x-if="previews.id_front">
                                                <div class="absolute inset-0 w-full h-full">
                                                    <img :src="previews.id_front" class="w-full h-full object-cover">
                                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                        <span class="text-[10px] font-black text-white uppercase tracking-widest">Replace Image</span>
                                                    </div>
                                                </div>
                                            </template>
                                        </label>
                                        <p x-show="errors.id_front" class="text-[10px] font-bold text-rose-500 mt-2 px-4" x-text="errors.id_front"></p>
                                    </div>

                                    <!-- Back Side -->
                                    <div class="image-upload-wrapper group relative">
                                        <label class="block h-40 bg-gray-50 dark:bg-white/2 border-2 border-dashed border-gray-200 dark:border-white/10 rounded-2xl cursor-pointer hover:border-orange-500/50 transition-all relative overflow-hidden">
                                            <input type="file" name="id_back" accept="image/*" class="hidden" @change="handleImagePreview($event, 'id_back')">
                                            
                                            <div class="absolute inset-0 flex flex-col items-center justify-center gap-3 p-6 text-center" x-show="!previews.id_back">
                                                <div class="w-14 h-14 rounded-2xl bg-white dark:bg-white/5 flex items-center justify-center text-gray-400 shadow-sm group-hover:scale-110 transition-transform">
                                                    <span class="material-symbols-rounded text-2xl">upload_file</span>
                                                </div>
                                                <span class="text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-widest">Upload Back Side</span>
                                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Optional Document</p>
                                            </div>

                                            <template x-if="previews.id_back">
                                                <div class="absolute inset-0 w-full h-full">
                                                    <img :src="previews.id_back" class="w-full h-full object-cover">
                                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                        <span class="text-[10px] font-black text-white uppercase tracking-widest">Replace Image</span>
                                                    </div>
                                                </div>
                                            </template>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Selfie Capture -->
                <div class="kyc-step" id="step3" x-show="step === 3" x-cloak x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="bg-white dark:bg-[#181818] rounded-2xl p-4 sm:p-6 border border-gray-100 dark:border-white/5 shadow-sm relative overflow-hidden transition-all duration-500">
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-red-600/5 rounded-full blur-[100px]"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-2">
                                <div class="w-8 h-8 rounded-lg bg-red-600 text-white flex items-center justify-center shadow-xl shadow-red-500/20">
                                    <span class="material-symbols-rounded">photo_camera</span>
                                </div>
                                <h2 class="text-sm font-black text-gray-900 dark:text-white tracking-tighter uppercase">Live Verification</h2>
                            </div>

                            <div class="max-w-[500px] mx-auto text-center space-y-8">
                                <div :class="errors.selfie ? 'border-rose-500 ring-4 ring-rose-500/10' : ''"
                                     class="relative aspect-square rounded-[3rem] bg-black overflow-hidden border-8 border-white dark:border-white/5 shadow-2xl group">
                                    <video id="video" class="w-full h-full object-cover grayscale-0 transition-all duration-700" autoplay playsinline></video>
                                    <canvas id="canvas" class="hidden"></canvas>
                                    <img id="capturedImage" class="absolute inset-0 w-full h-full object-cover hidden scale-105 transition-transform duration-700">
                                    
                                    <!-- Camera Overlay -->
                                    <div class="absolute inset-0 pointer-events-none border-[40px] border-black/40 rounded-[3rem] flex items-center justify-center">
                                        <div class="w-full h-full border-2 border-dashed border-white/30 rounded-full"></div>
                                    </div>
                                    
                                    <div class="absolute bottom-8 left-0 right-0 flex justify-center">
                                        <button type="button" id="snapBtn" @click="captureSelfie()" class="w-16 h-16 rounded-full bg-white shadow-xl flex items-center justify-center group-active:scale-90 transition-all pointer-events-auto">
                                            <div class="w-12 h-12 rounded-full border-4 border-red-600"></div>
                                        </button>
                                        <button type="button" id="retakeBtn" @click="retakeSelfie()" class="hidden px-8 py-3 bg-white/20 backdrop-blur-xl text-white rounded-2xl font-black text-[10px] uppercase tracking-widest border border-white/20 pointer-events-auto hover:bg-white/30 transition-all">Retake Photo</button>
                                    </div>
                                </div>
                                <p x-show="errors.selfie" class="text-[10px] font-bold text-rose-500 uppercase" x-text="errors.selfie"></p>

                                <div class="p-6 bg-red-600/5 border border-red-500/10 rounded-2xl">
                                    <p class="text-[11px] font-bold text-gray-600 dark:text-gray-400 uppercase tracking-widest leading-relaxed">
                                        Ensure your face is clearly visible within the frame and well-lit. <span class="text-rose-500">Required Phase</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Bank Details -->
                <div class="kyc-step" id="step4" x-show="step === 4" x-cloak x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="bg-white dark:bg-[#181818] rounded-2xl p-4 sm:p-6 border border-gray-100 dark:border-white/5 shadow-sm relative overflow-hidden transition-all duration-500">
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-orange-600/5 rounded-full blur-[100px]"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-2">
                                <div class="w-8 h-8 rounded-lg bg-orange-600 text-white flex items-center justify-center shadow-xl shadow-orange-500/20">
                                    <span class="material-symbols-rounded">account_balance</span>
                                </div>
                                <h2 class="text-sm font-black text-gray-900 dark:text-white tracking-tighter uppercase">Settlement Account</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="flex items-center justify-between px-4">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Bank Name</span>
                                        <span class="text-[7px] font-black text-rose-500 uppercase">Required</span>
                                    </label>
                                    <input type="text" name="bank_name" required placeholder="HDFC Bank" 
                                           @input="$event.target.value = $event.target.value.replace(/[0-9]/g, ''); validateInput('bank_name', $event.target.value)"
                                           :class="errors.bank_name ? 'border-rose-500 ring-2 ring-rose-500/10' : ''"
                                           class="w-full px-3 py-3.5 bg-gray-50 dark:bg-white/2 border border-gray-100 dark:border-white/5 rounded-md text-[10px] font-bold focus:ring-2 focus:ring-orange-500 outline-none transition-all uppercase">
                                    <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest px-4 mt-1 opacity-70">Official name (Letters Only). Digits are not allowed.</p>
                                    <p x-show="errors.bank_name" class="text-[9px] font-bold text-rose-500 px-4 mt-1" x-text="errors.bank_name"></p>
                                </div>
                                <div class="space-y-1">
                                    <label class="flex items-center justify-between px-4">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Account Holder Name</span>
                                        <span class="text-[7px] font-black text-rose-500 uppercase">Required</span>
                                    </label>
                                    <input type="text" name="account_holder" required placeholder="JOHN DOE" 
                                           @input="$event.target.value = $event.target.value.replace(/[^A-Za-z.'\s]/g, ''); validateInput('account_holder', $event.target.value)"
                                           :class="errors.account_holder ? 'border-rose-500 ring-2 ring-rose-500/10' : ''"
                                           class="w-full px-3 py-3.5 bg-gray-50 dark:bg-white/2 border border-gray-100 dark:border-white/5 rounded-md text-[10px] font-bold focus:ring-2 focus:ring-orange-500 outline-none transition-all uppercase">
                                    <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest px-4 mt-1 opacity-70">Letters, dots (.) and apostrophes (') only. No numbers.</p>
                                    <p x-show="errors.account_holder" class="text-[9px] font-bold text-rose-500 px-4 mt-1" x-text="errors.account_holder"></p>
                                </div>
                                <div class="space-y-1">
                                    <label class="flex items-center justify-between px-4">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Account Number</span>
                                        <span class="text-[7px] font-black text-rose-500 uppercase">Required</span>
                                    </label>
                                    <input type="text" name="account_number" required placeholder="501002345678" 
                                           maxlength="18"
                                           @input.debounce.500ms="validateInput('account_number', $event.target.value)"
                                           :class="errors.account_number ? 'border-rose-500 ring-2 ring-rose-500/10' : ''"
                                           class="w-full px-3 py-3.5 bg-gray-50 dark:bg-white/2 border border-gray-100 dark:border-white/5 rounded-md text-[10px] font-bold focus:ring-2 focus:ring-orange-500 outline-none transition-all uppercase tracking-widest">
                                    <div class="flex items-center gap-2 mt-1 px-4">
                                        <p x-show="errors.account_number" class="text-[10px] font-bold text-rose-500" x-text="errors.account_number"></p>
                                        <template x-if="loading.account_number">
                                            <svg class="animate-spin h-3 w-3 text-orange-500" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        </template>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <label class="flex items-center justify-between px-4">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">IFSC / Routing Code</span>
                                        <span class="text-[7px] font-black text-rose-500 uppercase">Required</span>
                                    </label>
                                    <input type="text" name="ifsc" required placeholder="HDFC0001234" 
                                           maxlength="11"
                                           @input="$event.target.value = $event.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 11); validateInput('ifsc', $event.target.value)"
                                           :class="errors.ifsc ? 'border-rose-500 ring-2 ring-rose-500/10' : ''"
                                           class="w-full px-3 py-3.5 bg-gray-50 dark:bg-white/2 border border-gray-100 dark:border-white/5 rounded-md text-[10px] font-bold focus:ring-2 focus:ring-orange-500 outline-none transition-all uppercase tracking-widest">
                                    <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest px-4 mt-1 opacity-70">Alphanumeric (A-Z, 0-9). e.g. HDFC0001234</p>
                                    <p x-show="errors.ifsc" class="text-[9px] font-bold text-rose-500 px-4 mt-1" x-text="errors.ifsc"></p>
                                </div>
                                <div class="md:col-span-2 space-y-1">
                                    <label class="flex items-center justify-between px-4">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Branch Name (Optional)</span>
                                        <span class="text-[7px] font-black text-gray-400 uppercase">Optional</span>
                                    </label>
                                    <input type="text" name="branch" placeholder="Main Street Branch" 
                                           @input="validateInput('branch', $event.target.value)"
                                           :class="errors.branch ? 'border-rose-500 ring-2 ring-rose-500/10' : ''"
                                           class="w-full px-3 py-3.5 bg-gray-50 dark:bg-white/2 border border-gray-100 dark:border-white/5 rounded-md text-[10px] font-bold focus:ring-2 focus:ring-orange-500 outline-none transition-all uppercase">
                                    <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest px-4 mt-1 opacity-70">Letters, numbers, spaces, hyphens(-) and commas(,) allowed</p>
                                    <p x-show="errors.branch" class="text-[9px] font-bold text-rose-500 px-4 mt-1" x-text="errors.branch"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Navigation -->
                <div class="fixed bottom-0 left-0 right-0 z-[100] p-4 md:relative md:p-0 md:z-auto bg-white/80 dark:bg-[#0F0F0F]/80 backdrop-blur-xl md:bg-transparent border-t border-gray-100 dark:border-white/5 md:border-t-0">
                    <div class="max-w-3xl mx-auto flex items-center justify-between gap-4">
                        <button type="button" @click="prevStep()" x-show="step > 1" x-cloak class="flex-1 md:flex-none px-6 py-2.5 bg-white dark:bg-white/5 text-gray-900 dark:text-white rounded-xl font-black text-[10px] uppercase tracking-widest border border-gray-200 dark:border-white/10 shadow-sm hover:bg-gray-50 dark:hover:bg-white/10 transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-rounded text-lg">west</span>
                            <span class="hidden sm:inline">Previous Phase</span>
                            <span class="sm:hidden">Back</span>
                        </button>
                        
                        <button type="button" @click="nextStep()" x-show="step < 4" class="flex-1 md:ml-auto md:flex-none px-8 py-2.5 bg-gradient-to-r from-orange-500 to-red-600 text-white rounded-xl font-black text-[10px] uppercase tracking-[0.2em] shadow-lg shadow-orange-500/40 hover:scale-[1.02] transition-all active:scale-95 flex items-center justify-center gap-2">
                            Proceed to Next
                            <span class="material-symbols-rounded">east</span>
                        </button>

                        <button type="submit" x-show="step === 4" x-cloak class="flex-1 md:ml-auto md:flex-none px-8 py-2.5 bg-gradient-to-r from-red-600 to-rose-700 text-white rounded-xl font-black text-[10px] uppercase tracking-[0.2em] shadow-lg shadow-red-500/40 hover:scale-[1.02] transition-all active:scale-95 flex items-center justify-center gap-2">
                            Submit Audit
                            <span class="material-symbols-rounded text-lg">verified</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('style')
    <style>
        [x-cloak] { display: none !important; }
        .step-dot.active { transform: scale(1.3); }
        
        /* Hide the global mobile bottom navigation to avoid overlap */
        .lg\:hidden.fixed.bottom-0.left-0.right-0.z-\[90\] {
            display: none !important;
        }
        
        @media (max-width: 768px) {
            .kyc-step { margin-bottom: 80px; }
        }

        /* Dark mode only (this page): fields use an invalid dark:bg-white/2 class
           (not a real Tailwind step, so it never compiles) and carry no dark text
           color — force proper dark fields with readable text + placeholders. */
        .dark #kycForm input:not([type="hidden"]):not([type="file"]),
        .dark #kycForm textarea,
        .dark #kycForm select { color: #ffffff !important; background-color: rgba(255,255,255,0.05) !important; }
        .dark #kycForm input::placeholder,
        .dark #kycForm textarea::placeholder { color: rgba(255,255,255,0.35) !important; opacity: 1 !important; }
    </style>
    @endpush
</x-app-layout>
