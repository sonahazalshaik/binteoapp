@extends('admin.layouts.app')
@section('panel')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card overflow-hidden">
                <div class="card-header bg--primary d-flex justify-content-between align-items-center">
                    <h5 class="text-white">KYC Portfolio for {{ $user->fullname }}</h5>
                    @if($submission)
                        {!! $submission->status_badge !!}
                    @endif
                </div>
                <div class="card-body">
                    @if ($submission)
                        <div class="row gy-4">
                            <!-- Personal Info -->
                            <div class="col-md-6">
                                <div class="card border--primary">
                                    <div class="card-header bg--primary py-2">
                                        <h6 class="text-white mb-0">Personal Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span>Full Name</span>
                                                <span class="fw-bold">{{ $submission->full_name }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span>Date of Birth</span>
                                                <span class="fw-bold">{{ $submission->date_of_birth->format('d M, Y') }}</span>
                                            </li>
                                            <li class="list-group-item">
                                                <span class="d-block mb-1">Address</span>
                                                <span class="fw-bold">{{ $submission->address }}, {{ $submission->city }}, {{ $submission->state }} {{ $submission->postal_code }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Bank Details -->
                            <div class="col-md-6">
                                <div class="card border--dark">
                                    <div class="card-header bg--dark py-2">
                                        <h6 class="text-white mb-0">Financial Settlement Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span>Bank Name</span>
                                                <span class="fw-bold">{{ $submission->bank_name }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span>Account Holder</span>
                                                <span class="fw-bold">{{ $submission->account_holder_name }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span>Account Number</span>
                                                <span class="fw-bold">{{ $submission->account_number }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span>IFSC / Routing</span>
                                                <span class="fw-bold text--primary">{{ $submission->ifsc_code }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Identity Verification -->
                            <div class="col-12">
                                <div class="card border--info">
                                    <div class="card-header bg--info py-2">
                                        <h6 class="text-white mb-0">Identity Documents ({{ strtoupper(str_replace('_', ' ', $submission->id_type)) }}) - ID: {{ $submission->id_number }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row gy-3">
                                            @if($submission->id_document_front)
                                            <div class="col-md-4">
                                                <p class="small fw-bold mb-1">Front Side</p>
                                                <a href="{{ $submission->id_document_front }}" target="_blank">
                                                    <img src="{{ $submission->id_document_front }}" class="img-fluid rounded border shadow-sm">
                                                </a>
                                            </div>
                                            @endif
                                            @if($submission->id_document_back)
                                            <div class="col-md-4">
                                                <p class="small fw-bold mb-1">Back Side</p>
                                                <a href="{{ $submission->id_document_back }}" target="_blank">
                                                    <img src="{{ $submission->id_document_back }}" class="img-fluid rounded border shadow-sm">
                                                </a>
                                            </div>
                                            @endif
                                            @if($submission->selfie_image)
                                            <div class="col-md-4">
                                                <p class="small fw-bold mb-1">Live Capture Selfie</p>
                                                <a href="{{ $submission->selfie_image }}" target="_blank">
                                                    <img src="{{ $submission->selfie_image }}" class="img-fluid rounded border shadow-sm">
                                                </a>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="las la-folder-open display-1 text--muted"></i>
                            <h5 class="text-center">@lang('KYC data not found')</h5>
                        </div>
                    @endif

                    @if ($user->kv == Status::KYC_UNVERIFIED && $user->kyc_rejection_reason)
                        <div class="mt-4 p-3 bg--danger-light border border--danger rounded">
                            <h6>@lang('Rejection Reason')</h6>
                            <p class="mb-0">{{ $user->kyc_rejection_reason }}</p>
                        </div>
                    @endif

                    @if ($user->kv == Status::KYC_PENDING && $submission)
                        <div class="d-flex flex-wrap justify-content-end mt-4 pt-4 border-top gap-3">
                            <button type="button" 
                                    class="h-12 px-6 rounded-2xl bg-rose-500/10 text-rose-600 flex items-center justify-center gap-2 hover:bg-rose-500 hover:text-white transition-all shadow-sm group reject-kyc" 
                                    data-action="{{ route('admin.users.kyc.reject', $submission->id) }}">
                                <span class="material-symbols-rounded text-lg">cancel</span>
                                <span class="text-[10px] font-black uppercase tracking-widest ">Reject Documents</span>
                            </button>
                            <button type="button" 
                                    class="h-12 px-6 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center gap-2 hover:bg-emerald-500 hover:text-white transition-all shadow-sm group approve-kyc" 
                                    data-action="{{ route('admin.users.kyc.approve', $submission->id) }}">
                                <span class="material-symbols-rounded text-lg">check_circle</span>
                                <span class="text-[10px] font-black uppercase tracking-widest ">Approve Portfolio</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@push('script')
<script>
    (function($) {
        "use strict";

        // Pre-configure AJAX with CSRF
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const handleKycAction = (url, method, data = {}) => {
            return $.ajax({
                url: url,
                method: method,
                data: {
                    _token: '{{ csrf_token() }}',
                    ...data
                },
                success: function(response) {
                    if (response.success) {
                        notify('success', response.message);
                        setTimeout(() => {
                            window.location.href = window.location.href; // Forced reload
                        }, 800);
                    } else {
                        window.adminSwal({
                            icon: 'error',
                            title: 'Action Failed',
                            text: response.message || 'The server rejected this request.',
                            customClass: { popup: 'rounded-[2.5rem] border border-white/10 shadow-2xl' }
                        });
                    }
                },
                error: function(xhr) {
                    const error = xhr.responseJSON ? xhr.responseJSON.message : 'System level failure (Check CSRF or Route)';
                    notify('error', error);
                }
            });
        };

        // Approve Action
        $(document).on('click', '.approve-kyc', function(e) {
            e.preventDefault();
            const url = $(this).data('action');
            
            window.adminSwal({
                title: 'Confirm KYC Approval',
                text: "Are you sure you want to verify this user's identity? This action grants creator permissions.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Yes, Approve It!',
                cancelButtonText: 'Abort'
            }).then((result) => {
                if (result.isConfirmed) {
                    handleKycAction(url, 'POST');
                }
            });
        });

        // Reject Action
        $(document).on('click', '.reject-kyc', function(e) {
            e.preventDefault();
            const url = $(this).data('action');

            window.adminSwal({
                title: 'Reject KYC Submission',
                text: "Provide a reason for identity rejection. This will be sent to the user.",
                input: 'textarea',
                inputPlaceholder: 'Type rejection reason here...',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Confirm Rejection',
                cancelButtonText: 'Cancel',
                customClass: {
                    input: 'rounded-2xl border-slate-200 dark:border-white/10 dark:bg-white/5 font-bold text-sm'
                },
                inputAttributes: {
                    'autocapitalize': 'off',
                    'autocorrect': 'off'
                },
                preConfirm: (reason) => {
                    if (!reason) {
                        Swal.showValidationMessage('A rejection reason is mandatory');
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    handleKycAction(url, 'POST', { reason: result.value });
                }
            });
        });

    })(jQuery);
</script>
@endpush
@endsection

