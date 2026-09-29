<x-app-layout>
    <div class="py-4 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500">
        <div class="max-w-4xl mx-auto px-3 sm:px-4">
            <!-- Header Section -->
            <div class="mb-6 flex flex-row items-center justify-between gap-3">
                <div>
                    <h1 class="text-lg font-black text-gray-900 dark:text-white tracking-tight">KYC</h1>
                    <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500">Verification &amp; Payout Center</p>
                </div>
                
                @if($user->kv != 1 && $user->kv != 2)
                <a href="{{ route('user.kyc.form') }}" class="px-5 py-2.5 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl text-[9px] font-black text-white uppercase tracking-widest flex items-center gap-1.5 active:scale-95 transition-all shrink-0">
                    Submit <span class="material-symbols-rounded text-sm">east</span>
                </a>
                @endif
            </div>

            <!-- Dashboard Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                
                <!-- Verification Status Card (Main) -->
                <div class="lg:col-span-12">
                    <div class="relative bg-white dark:bg-[#181818] rounded-2xl border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden p-4">
                        <div class="flex flex-row items-center gap-3">
                            <!-- Status Visual -->
                            <div class="relative shrink-0">
                                @if($user->kv == 1)
                                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border-2 border-emerald-500/20 flex items-center justify-center text-emerald-500">
                                        <span class="material-symbols-rounded text-2xl">verified_user</span>
                                    </div>
                                @elseif($user->kv == 2)
                                    <div class="w-14 h-14 rounded-2xl bg-orange-500/10 border-2 border-orange-500/20 flex items-center justify-center text-orange-500">
                                        <span class="material-symbols-rounded text-2xl">hourglass_empty</span>
                                    </div>
                                @else
                                    <div class="w-14 h-14 rounded-2xl bg-rose-500/10 border-2 border-rose-500/20 flex items-center justify-center text-rose-500">
                                        <span class="material-symbols-rounded text-2xl">gpp_maybe</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Status Text -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-0.5">
                                    <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase">
                                        @if($user->kv == 1) Verified
                                        @elseif($user->kv == 2) Under Review
                                        @else Not Verified
                                        @endif
                                    </h2>
                                    <span class="w-1.5 h-1.5 rounded-full {{ $user->kv == 1 ? 'bg-emerald-500' : ($user->kv == 2 ? 'bg-orange-500' : 'bg-rose-500') }}"></span>
                                </div>
                                <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 leading-snug">
                                    @if($user->kv == 1) Your account is fully verified.
                                    @elseif($user->kv == 2) Documents under review.
                                    @else Complete identity verification to enable payouts.
                                    @endif
                                </p>
                                
                                @if($submission && $submission->status == 2)
                                <div class="mt-3 p-3 bg-rose-500/5 border border-rose-500/10 rounded-xl">
                                    <p class="text-[9px] font-black text-rose-500 uppercase tracking-widest mb-1">Rejected</p>
                                    <p class="text-xs font-bold text-gray-600 dark:text-gray-300">"{{ $submission->admin_feedback }}"</p>
                                    <a href="{{ route('user.kyc.form') }}" class="mt-2 inline-flex items-center gap-1 text-[9px] font-black uppercase text-rose-500">
                                        Update Documents <span class="material-symbols-rounded text-xs">edit</span>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($submission)
                <!-- Identity Details (Left) -->
                <div class="lg:col-span-7 space-y-4">
                    <div class="bg-white dark:bg-[#181818] rounded-2xl border border-gray-100 dark:border-white/5 shadow-sm p-4">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-[10px] font-black text-gray-900 dark:text-white uppercase tracking-widest">Identity</span>
                            <div class="h-[1px] flex-1 bg-gray-100 dark:border-white/5"></div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Full Name</p>
                                <p class="text-sm font-black text-gray-900 dark:text-white">{{ $submission->full_name }}</p>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Date of Birth</p>
                                <p class="text-sm font-black text-gray-900 dark:text-white">{{ $submission->date_of_birth->format('M d, Y') }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Residential Address</p>
                                <p class="text-sm font-black text-gray-900 dark:text-white">{{ $submission->address }}, {{ $submission->city }}, {{ $submission->state }} - {{ $submission->postal_code }}</p>
                            </div>
                        </div>

                        @if($submission->id_document_front || $submission->id_document_back || $submission->selfie_image)
                        <div class="mt-4 grid grid-cols-3 gap-2">
                            @if($submission->id_document_front)
                            <div>
                                <p class="text-[7px] font-black text-gray-400 uppercase tracking-widest text-center mb-1">ID Front</p>
                                <a href="{{ \App\Helpers\ImageHelper::getPhotoUrl($submission->id_document_front) }}" target="_blank" class="block aspect-[4/3] rounded-xl overflow-hidden border border-gray-100 dark:border-white/5">
                                    <img src="{{ \App\Helpers\ImageHelper::getPhotoUrl($submission->id_document_front) }}" class="w-full h-full object-cover">
                                </a>
                            </div>
                            @endif
                            @if($submission->id_document_back)
                            <div>
                                <p class="text-[7px] font-black text-gray-400 uppercase tracking-widest text-center mb-1">ID Back</p>
                                <a href="{{ \App\Helpers\ImageHelper::getPhotoUrl($submission->id_document_back) }}" target="_blank" class="block aspect-[4/3] rounded-xl overflow-hidden border border-gray-100 dark:border-white/5">
                                    <img src="{{ \App\Helpers\ImageHelper::getPhotoUrl($submission->id_document_back) }}" class="w-full h-full object-cover">
                                </a>
                            </div>
                            @endif
                            @if($submission->selfie_image)
                            <div>
                                <p class="text-[7px] font-black text-gray-400 uppercase tracking-widest text-center mb-1">Selfie</p>
                                <a href="{{ \App\Helpers\ImageHelper::getPhotoUrl($submission->selfie_image) }}" target="_blank" class="block aspect-[4/3] rounded-xl overflow-hidden border border-gray-100 dark:border-white/5">
                                    <img src="{{ \App\Helpers\ImageHelper::getPhotoUrl($submission->selfie_image) }}" class="w-full h-full object-cover">
                                </a>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Banking Details (Right) -->
                <div class="lg:col-span-5 space-y-4">
                    <div class="bg-white dark:bg-[#181818] rounded-2xl border border-gray-100 dark:border-white/5 shadow-sm p-4">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2 flex-1">
                                <span class="text-[10px] font-black text-orange-500 uppercase tracking-widest">Bank</span>
                                <div class="h-[1px] flex-1 bg-orange-500/10"></div>
                            </div>
                            <a href="{{ route('user.kyc.bank.edit') }}" class="flex items-center gap-1 px-3 py-1.5 bg-gray-50 dark:bg-white/5 rounded-lg text-[9px] font-black text-gray-400 hover:text-orange-500 uppercase tracking-widest transition-all shrink-0">
                                <span class="material-symbols-rounded text-xs">edit</span>
                                Edit
                            </a>
                        </div>

                        <div class="space-y-2">
                            <div class="p-3 rounded-xl bg-gray-50 dark:bg-white/[0.02] border border-gray-100 dark:border-white/5">
                                <span class="block text-[8px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Bank Name</span>
                                <span class="block text-sm font-black text-gray-900 dark:text-white">{{ $submission->bank_name }}</span>
                            </div>
                            <div class="p-3 rounded-xl bg-gray-50 dark:bg-white/[0.02] border border-gray-100 dark:border-white/5">
                                <span class="block text-[8px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Account Holder</span>
                                <span class="block text-sm font-black text-gray-900 dark:text-white">{{ $submission->account_holder_name }}</span>
                            </div>
                            <div class="p-3 rounded-xl bg-gray-50 dark:bg-white/[0.02] border border-gray-100 dark:border-white/5">
                                <span class="block text-[8px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Account Number</span>
                                <span class="block text-sm font-black text-gray-900 dark:text-white tracking-widest">{{ $submission->account_number }}</span>
                            </div>
                            <div class="p-3 rounded-xl bg-orange-500/5 border border-orange-500/10">
                                <span class="block text-[8px] font-black text-orange-500 uppercase tracking-widest mb-0.5">IFSC / Code</span>
                                <span class="block text-sm font-black text-orange-600 tracking-widest">{{ $submission->ifsc_code }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Security Badge -->
                    <div class="bg-gradient-to-tr from-gray-900 to-slate-800 p-3 rounded-2xl text-white shadow-sm relative overflow-hidden">
                        <span class="material-symbols-rounded absolute right-3 top-1/2 -translate-y-1/2 text-4xl opacity-10">security</span>
                        <p class="text-[10px] font-black uppercase tracking-widest text-orange-500">Encrypted</p>
                        <p class="text-[11px] font-medium leading-snug opacity-80 mt-0.5">All identity data is encrypted and stored securely.</p>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
