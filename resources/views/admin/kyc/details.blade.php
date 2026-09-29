@extends('admin.layouts.app')

@section('title', 'Audit Identity Submission')
@section('header_title', 'KYC Review')

@section('content')
<div class="max-w-[1200px] mx-auto animate-in fade-in slide-in-from-bottom-10 duration-1000">
    
    <!-- Profile Header Card -->
    <div class="relative bg-white dark:bg-[#121212] rounded-[3rem] border border-slate-200 dark:border-white/10 shadow-2xl overflow-hidden mb-8">
        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-600"></div>
        
        <div class="p-10 flex flex-col lg:flex-row items-center gap-10">
            <!-- Profile Avatar -->
            <div class="relative">
                <div class="w-40 h-40 rounded-[2.5rem] p-1 bg-gradient-to-tr from-slate-200 to-slate-100 dark:from-white/10 dark:to-white/5 shadow-2xl">
                    <div class="w-full h-full rounded-[2.1rem] overflow-hidden border-4 border-white dark:border-[#121212] shadow-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center font-black text-slate-300 text-4xl ">
                        @if($user->image)
                            <img src="{{ getImage(getFilePath('userProfile').'/'.$user->image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-rose-500 to-orange-400 text-white font-black uppercase ">
                                {{ substr($user->firstname, 0, 1) }}{{ substr($user->lastname, 0, 1) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Profile Info -->
            <div class="flex-grow text-center lg:text-left min-w-0">
                <div class="flex flex-col lg:flex-row lg:items-center gap-4 mb-4">
                    <h2 class="text-3xl font-black text-slate-800 dark:text-white uppercase tracking-tighter">{{ $submission->full_name }}</h2>
                    <div class="flex items-center gap-2">
                        <span class="px-4 py-1 rounded-xl bg-slate-100 dark:bg-white/5 text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-[0.2em]">@ {{ $user->username }}</span>
                        @php echo $submission->status_badge @endphp
                    </div>
                </div>
                
                <div class="flex flex-wrap justify-center lg:justify-start gap-8 mb-8">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-rounded text-slate-300 dark:text-white/20 text-sm">mail</span>
                        <span class="text-xs font-bold text-slate-600 dark:text-white/60">{{ $user->email }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-rounded text-slate-300 dark:text-white/20 text-sm">badge</span>
                        <span class="text-xs font-black text-blue-500 uppercase ">{{ str_replace('_', ' ', $submission->id_type) }}: {{ $submission->id_number }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-rounded text-slate-300 dark:text-white/20 text-sm">event</span>
                        <span class="text-xs font-bold text-slate-600 dark:text-white/60">Audit Entry: {{ $submission->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                <!-- Quick Action Bar -->
                <div class="flex flex-wrap justify-center lg:justify-start gap-3">
                    <a href="{{ route('admin.users.kyc.edit', $submission->id) }}" class="px-8 h-11 rounded-2xl bg-blue-500 text-white flex items-center gap-3 text-[9px] font-black uppercase tracking-widest hover:opacity-90 transition-all shadow-lg shadow-blue-500/20 ">
                        <span class="material-symbols-rounded text-sm">edit</span> Manual Override
                    </a>
                    @if($submission->status == 0)
                    <button type="button" class="confirmationBtn px-8 h-11 rounded-2xl bg-emerald-500 text-white flex items-center gap-3 text-[9px] font-black uppercase tracking-widest hover:opacity-90 transition-all shadow-lg shadow-emerald-500/20 " 
                        data-question="Are you sure to approve this submission?" 
                        data-action="{{ route('admin.users.kyc.approve', $user->id) }}">
                        <span class="material-symbols-rounded text-sm">verified</span> Approve Submission
                    </button>
                    <button type="button" class="px-8 h-11 rounded-2xl bg-rose-500 text-white flex items-center gap-3 text-[9px] font-black uppercase tracking-widest hover:opacity-90 transition-all shadow-lg shadow-rose-500/20 "
                        data-bs-toggle="modal" data-bs-target="#kycRejectionModal">
                        <span class="material-symbols-rounded text-sm">block</span> Reject Submission
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Identity Documents -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white dark:bg-[#1a1a1a] rounded-[3rem] border border-slate-200 dark:border-white/10 shadow-xl p-10">
                <div class="flex items-center gap-4 mb-10">
                    <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-[0.2em] bg-slate-100 dark:bg-white/5 px-4 py-1.5 rounded-lg border border-slate-200 dark:border-white/5">01. Identity Evidence</span>
                    <div class="h-[1px] flex-grow bg-slate-100 dark:bg-white/5"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @if($submission->id_document_front)
                    <div class="space-y-4">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Document Front Side</p>
                        <a href="{{ getImage($submission->id_document_front) }}" target="_blank" class="block aspect-video rounded-[2rem] overflow-hidden border-4 border-white dark:border-[#121212] shadow-2xl hover:scale-[1.02] transition-transform">
                            <img src="{{ getImage($submission->id_document_front) }}" class="w-full h-full object-cover">
                        </a>
                    </div>
                    @endif
                    @if($submission->id_document_back)
                    <div class="space-y-4">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Document Back Side</p>
                        <a href="{{ getImage($submission->id_document_back) }}" target="_blank" class="block aspect-video rounded-[2rem] overflow-hidden border-4 border-white dark:border-[#121212] shadow-2xl hover:scale-[1.02] transition-transform">
                            <img src="{{ getImage($submission->id_document_back) }}" class="w-full h-full object-cover">
                        </a>
                    </div>
                    @endif
                    @if($submission->selfie_image)
                    <div class="md:col-span-2 space-y-4">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Live Verification Selfie</p>
                        <a href="{{ getImage($submission->selfie_image) }}" target="_blank" class="block aspect-square max-w-[400px] mx-auto rounded-[3rem] overflow-hidden border-8 border-white dark:border-[#121212] shadow-2xl hover:scale-[1.02] transition-transform">
                            <img src="{{ getImage($submission->selfie_image) }}" class="w-full h-full object-cover">
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Settlement Account & Metadata -->
        <div class="space-y-8">
            <div class="bg-white dark:bg-[#1a1a1a] rounded-[3rem] border border-slate-200 dark:border-white/10 shadow-xl p-10">
                <div class="flex items-center gap-4 mb-8">
                    <span class="text-[11px] font-black text-emerald-500 uppercase tracking-[0.2em] bg-emerald-500/5 px-4 py-1.5 rounded-lg border border-emerald-500/10">02. Settlement Hub</span>
                    <div class="h-[1px] flex-grow bg-emerald-500/10"></div>
                </div>

                <div class="space-y-6">
                    <div class="p-5 rounded-2xl bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5">
                        <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Financial Institution</span>
                        <span class="block text-sm font-black text-slate-800 dark:text-white uppercase ">{{ $submission->bank_name }}</span>
                    </div>
                    <div class="p-5 rounded-2xl bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5">
                        <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Account Holder Designation</span>
                        <span class="block text-sm font-black text-slate-800 dark:text-white uppercase ">{{ $submission->account_holder_name }}</span>
                    </div>
                    <div class="p-5 rounded-2xl bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5">
                        <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Account Routing Vector</span>
                        <span class="block text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest ">{{ $submission->account_number }}</span>
                    </div>
                    <div class="p-5 rounded-2xl bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5">
                        <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">IFSC / Global Code</span>
                        <span class="block text-sm font-black text-emerald-500 uppercase tracking-widest ">{{ $submission->ifsc_code }}</span>
                    </div>
                </div>
            </div>

            @if($submission->admin_feedback)
            <div class="bg-rose-500 p-8 rounded-[2.5rem] text-white shadow-xl shadow-rose-500/20">
                <h4 class="text-[9px] font-black uppercase tracking-[0.2em] mb-4 opacity-80 ">Audit Signal Feedback</h4>
                <p class="text-xs font-bold leading-relaxed ">"{{ $submission->admin_feedback }}"</p>
            </div>
            @endif
        </div>
    </div>
</div>

<div id="kycRejectionModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content bg-white dark:bg-[#121212] border-0 rounded-[2.5rem] overflow-hidden shadow-2xl">
            <div class="modal-header border-b border-slate-100 dark:border-white/5 p-8">
                <h5 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tighter">Reject Identity Audit</h5>
                <button type="button" class="close text-slate-400 hover:text-rose-500 transition-colors" data-bs-dismiss="modal" aria-label="Close">
                    <span class="material-symbols-rounded">close</span>
                </button>
            </div>
            <form action="{{ route('admin.users.kyc.reject', $user->id) }}" method="POST">
                @csrf
                <div class="modal-body p-8">
                    <div class="p-6 bg-blue-500/5 border border-blue-500/10 rounded-2xl mb-8">
                        <p class="text-[10px] font-bold text-slate-600 dark:text-white/60 leading-relaxed ">
                            Provide a precise reason for rejection. This signal will be transmitted to the user to guide their re-submission process.
                        </p>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Audit Feedback</label>
                        <textarea class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-[1.5rem] p-6 text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:border-rose-500 transition-all resize-none" name="reason" rows="4" required placeholder="Enter rejection details..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-t border-slate-100 dark:border-white/5 p-8">
                    <button type="submit" class="w-full h-14 rounded-2xl bg-rose-500 text-white font-black uppercase tracking-widest text-[10px] hover:scale-105 transition-all shadow-xl ">Submit Rejection Signal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<x-confirmation-modal />
@endsection

@push('style')
<style>
    .modal-backdrop { background-color: rgba(0,0,0,0.8); backdrop-filter: blur(8px); }
</style>
@endpush

