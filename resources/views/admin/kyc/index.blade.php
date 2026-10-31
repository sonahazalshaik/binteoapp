@extends('admin.layouts.app')

@section('title', 'KYC Management')
@section('header_title', $pageTitle)

@section('content')
<div x-data="{ 
    selectAll: false, 
    toggleAll() { 
        const checkboxes = document.querySelectorAll('.bulk-select-item');
        checkboxes.forEach(cb => { cb.checked = this.selectAll; });
    }
}" class="bg-white dark:bg-[#121212] rounded-[2rem] overflow-hidden border border-slate-200 dark:border-white/10 shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-700">
    @include('admin.components.header-toolbar', [
        'title' => $pageTitle ?? 'KYC Audit Registry',
        'items' => $submissions,
        'createRoute' => route('admin.users.kyc.create'),
        'createLabel' => 'Create KYC'
    ])

    <div class="px-6 pt-6">
        @include('admin.components.table-toolbar', ['showBulkActions' => false, 'module' => 'kyc', 'exportTotal' => count($submissions)])
    </div>

    <!-- Table View -->
    <div class="overflow-x-auto" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Digital Identity</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Identity Media</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Document Info</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right">Audit Decisions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($submissions as $submission)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-100 dark:bg-white/5 flex items-center justify-center font-bold text-slate-400">
                                @if(@$submission->user->image)
                                    <img src="{{ getImage(getFilePath('userProfile').'/'.$submission->user->image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-rose-500 to-orange-400 text-white text-[10px] font-black uppercase ">
                                        {{ substr($submission->user->firstname, 0, 1) }}{{ substr($submission->user->lastname, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-[11px] font-black text-slate-900 dark:text-white uppercase leading-none">{{ $submission->full_name }}</h4>
                                <p class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest mt-1">@ {{ $submission->user->username }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            @if($submission->id_document_front)
                            <div class="flex flex-col items-center gap-1.5">
                                <a href="{{ \App\Helpers\ImageHelper::getPhotoUrl($submission->id_document_front) }}" target="_blank" class="w-12 h-12 rounded-lg overflow-hidden border border-slate-200 dark:border-white/10 group/img relative">
                                    <img src="{{ \App\Helpers\ImageHelper::getPhotoUrl($submission->id_document_front) }}" class="w-full h-full object-cover group-hover/img:scale-125 transition-transform">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                        <span class="material-symbols-rounded text-white text-xs">zoom_in</span>
                                    </div>
                                </a>
                                <span class="text-[7px] font-black text-slate-400 uppercase tracking-tighter">FRONT</span>
                            </div>
                            @endif
                            @if($submission->id_document_back)
                            <div class="flex flex-col items-center gap-1.5">
                                <a href="{{ \App\Helpers\ImageHelper::getPhotoUrl($submission->id_document_back) }}" target="_blank" class="w-12 h-12 rounded-lg overflow-hidden border border-slate-200 dark:border-white/10 group/img relative">
                                    <img src="{{ \App\Helpers\ImageHelper::getPhotoUrl($submission->id_document_back) }}" class="w-full h-full object-cover group-hover/img:scale-125 transition-transform">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                        <span class="material-symbols-rounded text-white text-xs">zoom_in</span>
                                    </div>
                                </a>
                                <span class="text-[7px] font-black text-slate-400 uppercase tracking-tighter">BACK</span>
                            </div>
                            @endif
                            @if($submission->selfie_image)
                            <div class="flex flex-col items-center gap-1.5">
                                <a href="{{ \App\Helpers\ImageHelper::getPhotoUrl($submission->selfie_image) }}" target="_blank" class="w-12 h-12 rounded-lg overflow-hidden border border-emerald-500/30 group/img relative">
                                    <img src="{{ \App\Helpers\ImageHelper::getPhotoUrl($submission->selfie_image) }}" class="w-full h-full object-cover group-hover/img:scale-125 transition-transform">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                        <span class="material-symbols-rounded text-white text-xs">face</span>
                                    </div>
                                </a>
                                <span class="text-[7px] font-black text-emerald-500 uppercase tracking-tighter">SELFIE</span>
                            </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-[10px] font-black text-blue-500 uppercase leading-none mb-1">{{ str_replace('_', ' ', $submission->id_type) }}</p>
                        <p class="text-[9px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest">{{ $submission->id_number }}</p>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end items-center gap-4">
                            <div class="text-right">
                                @php echo $submission->status_badge @endphp
                                <p class="text-[8px] font-bold text-slate-400 mt-1 uppercase">{{ $submission->created_at->diffForHumans() }}</p>
                            </div>
                            
                            @if($submission->status == 0)
                            <div class="flex gap-2 kyc-actions">
                                <button type="button" 
                                        data-action="{{ route('admin.users.kyc.approve', $submission->id) }}" 
                                        class="h-10 px-4 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center gap-2 hover:bg-emerald-500 hover:text-white transition-all shadow-sm group approve-kyc">
                                    <span class="material-symbols-rounded text-sm">check_circle</span>
                                    <span class="text-[9px] font-black uppercase tracking-widest ">Approve</span>
                                </button>
                                <button type="button" 
                                        data-action="{{ route('admin.users.kyc.reject', $submission->id) }}" 
                                        class="h-10 px-4 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center gap-2 hover:bg-rose-500 hover:text-white transition-all shadow-sm group reject-kyc">
                                    <span class="material-symbols-rounded text-sm">cancel</span>
                                    <span class="text-[9px] font-black uppercase tracking-widest ">Reject</span>
                                </button>
                            </div>
                            @endif

                            <div class="flex gap-1 border-l border-slate-100 dark:border-white/5 pl-4">
                                <a href="{{ route('admin.users.kyc.edit', $submission->id) }}" class="h-10 px-4 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center gap-2 hover:bg-amber-500 hover:text-white transition-all shadow-sm group" title="Edit KYC">
                                    <span class="material-symbols-rounded text-sm">edit</span>
                                    <span class="text-[9px] font-black uppercase tracking-tighter hidden xl:block">Edit</span>
                                </a>
                                <a href="{{ route('admin.users.kyc.details', $submission->user_id) }}" class="h-10 px-4 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 flex items-center justify-center gap-2 hover:opacity-80 transition-all shadow-sm group" title="Full Portfolio">
                                    <span class="material-symbols-rounded text-sm">visibility</span>
                                    <span class="text-[9px] font-black uppercase tracking-tighter hidden xl:block">Review</span>
                                </a>
                                <button type="button" 
                                        data-action="{{ route('admin.users.kyc.delete', $submission->id) }}" 
                                        class="h-10 px-4 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center gap-2 hover:bg-rose-500 hover:text-white transition-all shadow-sm group delete-kyc" 
                                        title="Purge Entry">
                                    <span class="material-symbols-rounded text-sm">delete</span>
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="py-20 text-center opacity-20 text-[11px] font-black uppercase tracking-widest" colspan="100%">No audit requests detected.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Main Grid View (App View) -->
    <div class="px-6 pb-6" x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 mt-6">
            @forelse($submissions as $submission)
            <div class="bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden relative group">
                <!-- Top Header Decor -->
                <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-orange-500 via-red-500 to-rose-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="p-8">
                    <!-- Top Bar -->
                    <div class="flex justify-between items-start mb-6">
                        <div class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-white/5 text-[8px] font-black uppercase tracking-widest text-slate-400">
                            ID REF: #{{ $submission->id }}
                        </div>
                        <div class="flex items-center gap-2">
                             @php echo $submission->status_badge @endphp
                        </div>
                    </div>

                    <!-- Profile Section -->
                    <div class="flex items-center gap-6 relative mb-8">
                        <div class="relative group">
                            <div class="w-20 h-20 rounded-[1.5rem] overflow-hidden border-4 border-white dark:border-[#1a1a1a] shadow-xl relative bg-slate-100 dark:bg-white/5 flex items-center justify-center">
                                @if(@$submission->user->image)
                                    <img src="{{ getImage(getFilePath('userProfile').'/'.$submission->user->image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-rose-500 to-orange-400 text-white text-2xl font-black uppercase ">
                                        {{ substr($submission->user->firstname, 0, 1) }}{{ substr($submission->user->lastname, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex-grow min-w-0">
                            <h4 class="text-lg font-black text-slate-900 dark:text-white truncate uppercase tracking-tighter mb-1">{{ $submission->full_name }}</h4>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest ">@ {{ $submission->user->username }}</span>
                        </div>
                    </div>

                    <!-- Media Grid (Quick View) -->
                    <div class="grid grid-cols-3 gap-3 mb-4">
                        @if($submission->id_document_front)
                        <div class="flex flex-col gap-2">
                            <a href="{{ \App\Helpers\ImageHelper::getPhotoUrl($submission->id_document_front) }}" target="_blank" class="aspect-square rounded-2xl overflow-hidden border border-slate-100 dark:border-white/5 group/media relative">
                                <img src="{{ \App\Helpers\ImageHelper::getPhotoUrl($submission->id_document_front) }}" class="w-full h-full object-cover group-hover/media:scale-110 transition-transform">
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover/media:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="material-symbols-rounded text-white text-sm">zoom_in</span>
                                </div>
                            </a>
                            <span class="text-[7px] font-black text-slate-400 uppercase text-center tracking-widest">FRONT</span>
                        </div>
                        @endif
                        @if($submission->id_document_back)
                        <div class="flex flex-col gap-2">
                            <a href="{{ \App\Helpers\ImageHelper::getPhotoUrl($submission->id_document_back) }}" target="_blank" class="aspect-square rounded-2xl overflow-hidden border border-slate-100 dark:border-white/5 group/media relative">
                                <img src="{{ \App\Helpers\ImageHelper::getPhotoUrl($submission->id_document_back) }}" class="w-full h-full object-cover group-hover/media:scale-110 transition-transform">
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover/media:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="material-symbols-rounded text-white text-sm">zoom_in</span>
                                </div>
                            </a>
                            <span class="text-[7px] font-black text-slate-400 uppercase text-center tracking-widest">BACK</span>
                        </div>
                        @endif
                        @if($submission->selfie_image)
                        <div class="flex flex-col gap-2">
                            <a href="{{ \App\Helpers\ImageHelper::getPhotoUrl($submission->selfie_image) }}" target="_blank" class="aspect-square rounded-2xl overflow-hidden border-emerald-500/20 group/media relative">
                                <img src="{{ \App\Helpers\ImageHelper::getPhotoUrl($submission->selfie_image) }}" class="w-full h-full object-cover group-hover/media:scale-110 transition-transform">
                                <div class="absolute inset-0 bg-emerald-600/60 opacity-0 group-hover/media:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="material-symbols-rounded text-white text-sm">face</span>
                                </div>
                            </a>
                            <span class="text-[7px] font-black text-emerald-500 uppercase text-center tracking-widest">SELFIE</span>
                        </div>
                        @endif
                    </div>

                    <!-- Metadata Row -->
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="bg-slate-50 dark:bg-white/[0.02] p-4 rounded-2xl border border-slate-100 dark:border-white/5">
                            <span class="block text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Document</span>
                            <span class="block text-[10px] font-black text-slate-900 dark:text-white uppercase">{{ str_replace('_', ' ', $submission->id_type) }}</span>
                        </div>
                        <div class="bg-slate-50 dark:bg-white/[0.02] p-4 rounded-2xl border border-slate-100 dark:border-white/5">
                            <span class="block text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">ID Number</span>
                            <span class="block text-[10px] font-black text-blue-500 ">{{ $submission->id_number }}</span>
                        </div>
                    </div>

                    <!-- Quick Audit Actions -->
                    @if($submission->status == 0)
                    <div class="grid grid-cols-2 gap-3 mb-4 kyc-actions">
                        <button type="button" 
                                data-action="{{ route('admin.users.kyc.approve', $submission->id) }}" 
                                class="w-full h-12 rounded-xl bg-emerald-500 text-white font-black text-[9px] uppercase tracking-widest shadow-xl shadow-emerald-500/20 hover:scale-[1.02] transition-all approve-kyc">
                            Approve KYC
                        </button>
                        <button type="button" 
                                data-action="{{ route('admin.users.kyc.reject', $submission->id) }}" 
                                class="w-full h-12 rounded-xl bg-rose-500 text-white font-black text-[9px] uppercase tracking-widest shadow-xl shadow-rose-500/20 hover:scale-[1.02] transition-all reject-kyc">
                            Reject KYC
                        </button>
                    </div>
                    @endif

                    <!-- Details Link -->
                    <div class="flex gap-3">
                        <a href="{{ route('admin.users.kyc.edit', $submission->id) }}" class="flex-grow h-14 rounded-2xl bg-amber-500/10 text-amber-600 flex items-center justify-center gap-3 hover:bg-amber-500 hover:text-white transition-all shadow-xl group border border-amber-500/20">
                            <span class="text-[9px] font-black uppercase tracking-[0.2em] ">Edit</span>
                            <span class="material-symbols-rounded text-sm">edit</span>
                        </a>
                        <a href="{{ route('admin.users.kyc.details', $submission->user_id) }}" class="flex-grow h-14 rounded-2xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 flex items-center justify-center gap-3 hover:opacity-90 transition-all shadow-xl group">
                            <span class="text-[9px] font-black uppercase tracking-[0.2em] ">Review</span>
                            <span class="material-symbols-rounded text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </a>
                        <button type="button" 
                                data-action="{{ route('admin.users.kyc.delete', $submission->id) }}" 
                                class="w-14 h-14 rounded-2xl bg-rose-500/10 text-rose-600 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all border border-rose-500/20 shadow-xl delete-kyc" 
                                title="Purge Record">
                            <span class="material-symbols-rounded text-xl">delete</span>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-40 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[3.5rem]">
                 <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-6xl">find_in_page</span>
                 <p class="mt-6 text-[11px] font-black text-slate-400 uppercase tracking-widest ">No audit requests detected.</p>
            </div>
            @endforelse
        </div>
    </div>

    <div class="p-8 border-t border-slate-100 dark:border-white/10 bg-slate-50/50 dark:bg-white/[0.01]">
        {{ $submissions->links() }}
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

        // Delete Action
        $(document).on('click', '.delete-kyc', function(e) {
            e.preventDefault();
            const url = $(this).data('action');

            window.adminSwal({
                title: 'Purge Identity Record?',
                text: "Warning: This will permanently delete this verification portfolio and reset the user's KYC status. This action is irreversible.",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Yes, Purge It!',
                cancelButtonText: 'Abort'
            }).then((result) => {
                if (result.isConfirmed) {
                    handleKycAction(url, 'POST');
                }
            });
        });

    })(jQuery);
</script>
@endpush
@endsection

