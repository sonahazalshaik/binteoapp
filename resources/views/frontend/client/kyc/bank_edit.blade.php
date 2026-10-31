<x-app-layout>
    <div class="py-8 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500"
         x-data="{
            errors: {},
            validateInput(name, value) {
                this.errors[name] = null;
                if (!value && name !== 'branch') {
                    this.errors[name] = 'This field is required';
                    return;
                }
                if (name === 'bank_name' || name === 'account_holder') {
                    const regex = name === 'bank_name' ? /^[A-Za-z\s]{2,100}$/ : /^[A-Za-z.'\s]{2,100}$/;
                    if (!regex.test(value)) {
                        this.errors[name] = name === 'bank_name' ? 'Invalid bank name format' : 'Invalid holder name format';
                    }
                }
                if (name === 'account_number') {
                    if (!/^[0-9]{9,18}$/.test(value)) {
                        this.errors[name] = 'Must be 9-18 digits';
                    }
                }
                if (name === 'ifsc') {
                    if (!/^[A-Z]{4}0[A-Z0-9]{6}$/.test(value)) {
                        this.errors[name] = 'Invalid format (e.g. HDFC0001234)';
                    }
                }
                if (name === 'branch' && value) {
                    if (!/^[A-Za-z0-9,\-\s]{2,100}$/.test(value)) {
                        this.errors[name] = 'Invalid branch format';
                    }
                }
            },
            validateForm() {
                this.errors = {};
                let isValid = true;
                const accNo = document.getElementsByName('account_number')[0].value;
                const ifsc = document.getElementsByName('ifsc')[0].value.toUpperCase();
                const bankName = document.getElementsByName('bank_name')[0].value;
                const holderName = document.getElementsByName('account_holder')[0].value;

                if (!/^[0-9]{9,18}$/.test(accNo)) {
                    this.errors.account_number = 'Account number must be 9-18 digits';
                    isValid = false;
                }
                if (!/^[A-Z]{4}0[A-Z0-9]{6}$/.test(ifsc)) {
                    this.errors.ifsc = 'Invalid IFSC format';
                    isValid = false;
                }
                if (!/^[A-Za-z\s]{2,100}$/.test(bankName)) {
                    this.errors.bank_name = 'Invalid bank name';
                    isValid = false;
                }
                if (!/^[A-Za-z.'\s]{2,100}$/.test(holderName)) {
                    this.errors.account_holder = 'Invalid holder name';
                    isValid = false;
                }
                return isValid;
            },
            submitForm() {
                if (!this.validateForm()) return;
                Swal.fire({
                    html: `
                        <div class='flex flex-col items-center justify-center p-6'>
                            <div class='w-16 h-16 border-4 border-orange-500/20 border-t-orange-500 rounded-full animate-spin mb-6'></div>
                            <h3 class='text-xl font-black text-slate-900 dark:text-white uppercase tracking-widest mb-2'>Updating Banking</h3>
                            <p class='text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-relaxed opacity-70'>Please wait while we secure your payout data...</p>
                        </div>
                    `,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                    customClass: {
                        popup: 'rounded-[3rem] border border-slate-100 dark:border-white/5 shadow-2xl'
                    }
                });
                this.$refs.bankForm.submit();
            }
         }">
        <div class="max-w-[800px] mx-auto px-4 sm:px-6">
            <!-- Header Section -->
            <div class="mb-12">
                <a href="{{ route('user.kyc.data') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-gray-400 hover:text-orange-500 uppercase tracking-[0.2em] transition-colors mb-6 group">
                    <span class="material-symbols-rounded text-lg group-hover:-translate-x-1 transition-transform">west</span>
                    Back to KYC Hub
                </a>
                <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter">UPDATE BANKING</h1>
                <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                    Secure Payout Management
                </p>
            </div>

            <div class="bg-white dark:bg-[#181818] rounded-[3.5rem] border border-gray-100 dark:border-white/5 shadow-2xl overflow-hidden">
                <div class="p-8 sm:p-12">
                    <form action="{{ route('user.kyc.bank.update') }}" method="POST" x-ref="bankForm" @submit.prevent="submitForm()" class="space-y-8">
                        @csrf
                        
                        <!-- Bank Info Section -->
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                             <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Bank Name</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name', $submission->bank_name) }}" required
                                    @input="$event.target.value = $event.target.value.replace(/[0-9]/g, ''); validateInput('bank_name', $event.target.value)"
                                    :class="errors.bank_name ? 'border-rose-500 ring-2 ring-rose-500/10' : 'border-gray-200 dark:border-white/5'"
                                    class="w-full bg-gray-50 dark:bg-white/[0.03] border rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all outline-none"
                                    placeholder="e.g. HDFC Bank">
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest px-2 mt-1 opacity-70">Official name (Letters Only). Digits are not allowed.</p>
                                <p x-show="errors.bank_name" class="text-[10px] font-bold text-rose-500 px-2 mt-1" x-text="errors.bank_name" x-cloak></p>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Account Holder Name</label>
                                <input type="text" name="account_holder" value="{{ old('account_holder', $submission->account_holder_name) }}" required
                                    @input="$event.target.value = $event.target.value.replace(/[^A-Za-z.'\s]/g, ''); validateInput('account_holder', $event.target.value)"
                                    :class="errors.account_holder ? 'border-rose-500 ring-2 ring-rose-500/10' : 'border-gray-200 dark:border-white/5'"
                                    class="w-full bg-gray-50 dark:bg-white/[0.03] border rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all outline-none"
                                    placeholder="Name as per bank records">
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest px-2 mt-1 opacity-70">Letters, dots (.) and apostrophes (') only. No numbers.</p>
                                <p x-show="errors.account_holder" class="text-[10px] font-bold text-rose-500 px-2 mt-1" x-text="errors.account_holder" x-cloak></p>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Account Number</label>
                                <input type="text" name="account_number" value="{{ old('account_number', $submission->account_number) }}" required
                                    @input="$event.target.value = $event.target.value.replace(/[^0-9]/g, '').slice(0, 18); validateInput('account_number', $event.target.value)"
                                    :class="errors.account_number ? 'border-rose-500 ring-2 ring-rose-500/10' : 'border-gray-200 dark:border-white/5'"
                                    class="w-full bg-gray-50 dark:bg-white/[0.03] border rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all outline-none tracking-widest"
                                    placeholder="Enter account number">
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest px-2 mt-1 opacity-70">Numbers only (0-9). Length: 9 to 18 digits.</p>
                                <p x-show="errors.account_number" class="text-[10px] font-bold text-rose-500 px-2 mt-1" x-text="errors.account_number" x-cloak></p>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">IFSC / Routing Code</label>
                                <input type="text" name="ifsc" value="{{ old('ifsc', $submission->ifsc_code) }}" required
                                    @input="$event.target.value = $event.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 11); validateInput('ifsc', $event.target.value)"
                                    :class="errors.ifsc ? 'border-rose-500 ring-2 ring-rose-500/10' : 'border-gray-200 dark:border-white/5'"
                                    class="w-full bg-gray-50 dark:bg-white/[0.03] border rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all outline-none uppercase tracking-widest"
                                    placeholder="e.g. HDFC0001234">
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest px-2 mt-1 opacity-70">Alphanumeric (A-Z, 0-9). e.g. HDFC0001234</p>
                                <p x-show="errors.ifsc" class="text-[10px] font-bold text-rose-500 px-2 mt-1" x-text="errors.ifsc" x-cloak></p>
                            </div>

                            <div class="md:col-span-2 space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Branch Name (Optional)</label>
                                <input type="text" name="branch" value="{{ old('branch', $submission->branch_name) }}"
                                    @input="validateInput('branch', $event.target.value)"
                                    :class="errors.branch ? 'border-rose-500 ring-2 ring-rose-500/10' : 'border-gray-200 dark:border-white/5'"
                                    class="w-full bg-gray-50 dark:bg-white/[0.03] border rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all outline-none"
                                    placeholder="e.g. Downtown Branch">
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest px-2 mt-1 opacity-70">Letters, numbers, spaces, hyphens(-) and commas(,) allowed</p>
                                <p x-show="errors.branch" class="text-[10px] font-bold text-rose-500 px-2 mt-1" x-text="errors.branch" x-cloak></p>
                            </div>
                        </div>

                        <!-- Info Note -->
                        <div class="p-6 bg-orange-500/5 border border-orange-500/10 rounded-3xl flex items-start gap-4">
                            <span class="material-symbols-rounded text-orange-500">info</span>
                            <p class="text-[11px] font-bold text-gray-600 dark:text-gray-400 leading-relaxed">
                                <span class="text-orange-500 font-black uppercase">Note:</span> Updating your bank details will require a new administrative review to ensure payout security. Your KYC status will return to <span class="text-orange-500 uppercase">Under Review</span> during this period.
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row items-center gap-4 pt-6">
                            <button type="submit" class="w-full sm:w-auto px-12 py-5 bg-gradient-to-r from-orange-500 to-red-600 text-white text-[11px] font-black uppercase tracking-[0.2em] rounded-3xl shadow-2xl shadow-orange-500/20 hover:scale-105 active:scale-95 transition-all">
                                Update Banking
                            </button>
                            <a href="{{ route('user.kyc.data') }}" class="text-[10px] font-black text-gray-400 hover:text-gray-600 dark:hover:text-white uppercase tracking-widest transition-colors px-6">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
