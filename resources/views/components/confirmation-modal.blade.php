<form id="swalConfirmForm" method="POST" style="display:none;">@csrf<input type="hidden" name="_method" value="DELETE"></form>

<div id="confirmationModal" class="modal custom--modal fade @if ($frontend) scale-style @endif"
    tabindex="-1" role="dialog">
    <div class="modal-dialog @if ($frontend) modal-dialog-centered @endif" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <div class="modal-body">
                    <p class="question"></p>
                    {{$slot}}
                </div>

                <div class="modal-footer">
                    <button type="button"
                        class="btn--sm btn @if ($frontend) btn--white outline @else btn--dark @endif"
                        data-bs-dismiss="modal">@lang('No')</button>
                    <button type="submit"
                        class="btn--sm btn @if ($frontend) btn--white @else btn--primary @endif">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).on('submit', 'form[data-swal-question]', function(e) {
                e.preventDefault();
                const form = this;
                window.adminSwal({
                    title: form.getAttribute('data-swal-title') || 'Are you sure?',
                    text: form.getAttribute('data-swal-question'),
                    icon: form.getAttribute('data-swal-icon') || 'warning',
                    showCancelButton: true,
                    confirmButtonText: form.getAttribute('data-swal-confirm') || 'Yes, proceed',
                    cancelButtonText: form.getAttribute('data-swal-cancel') || 'Abort',
                    confirmButtonColor: form.getAttribute('data-swal-color') || '#f97316'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const method = form.querySelector('input[name="_method"]');
                        if (!method) {
                            const m = document.createElement('input');
                            m.type = 'hidden'; m.name = '_method'; m.value = 'POST';
                            form.appendChild(m);
                        }
                        form.removeAttribute('data-swal-question');
                        form.submit();
                    }
                });
            });

            $(document).on('click', 'button[data-swal-question]', function(e) {
                e.preventDefault();
                const btn = this;
                window.adminSwal({
                    title: btn.getAttribute('data-swal-title') || 'Are you sure?',
                    text: btn.getAttribute('data-swal-question'),
                    icon: btn.getAttribute('data-swal-icon') || 'warning',
                    showCancelButton: true,
                    confirmButtonText: btn.getAttribute('data-swal-confirm') || 'Yes, proceed',
                    cancelButtonText: btn.getAttribute('data-swal-cancel') || 'Abort',
                    confirmButtonColor: btn.getAttribute('data-swal-color') || '#f97316'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = btn.closest('form');
                        if (form) {
                            const method = form.querySelector('input[name="_method"]');
                            if (!method) {
                                const m = document.createElement('input');
                                m.type = 'hidden'; m.name = '_method'; m.value = 'POST';
                                form.appendChild(m);
                            }
                            form.removeAttribute('data-swal-question');
                            form.submit();
                        }
                    }
                });
            });

            $(document).on('click', '.confirmationBtn', function() {
                let data = $(this).data();
                let method = data.method || 'POST';
                
                // Fallback for resource routes
                if (!data.method) {
                    const action = data.action.toString().toLowerCase();
                    if (action.includes('delete') || action.includes('destroy') || action.match(/\/\d+$/)) {
                        method = 'DELETE';
                    }
                }

                window.nativeSwalConfirm(
                    data.question || 'Are you sure?', 
                    data.text || 'This action cannot be undone.',
                    data.icon || 'warning'
                ).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = data.action;

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        form.appendChild(csrfToken);

                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = method;
                        form.appendChild(methodInput);

                        // Handle additional parameters
                        if (data.params) {
                            const params = typeof data.params === 'string' ? JSON.parse(data.params) : data.params;
                            for (const [key, value] of Object.entries(params)) {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = key;
                                input.value = value;
                                form.appendChild(input);
                            }
                        }

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
