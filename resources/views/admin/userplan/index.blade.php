@extends('admin.layouts.app')
@section('title', 'Market Plans')
@section('header_title', 'Market Plans')

@section('content')
<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
        @include('admin.components.header-toolbar', [
            'title' => 'Market Plans',
            'createRoute' => route('admin.userplans.create'),
            'createLabel' => 'Add Market Plan'
        ])
        <div class="px-6 pt-6">
            @include('admin.components.table-toolbar')
        </div>
        <div class="overflow-x-auto scrollbar-hide"><table class="w-full text-left border-collapse">
                        <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('S.No')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Plan Name')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Price')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Duration (Months)')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Content')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse ($userplans as $userplan)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">{{ __($userplan->plan_name) }}</td>
                                {{-- Changed from showAmount to number_format to remove the currency symbol --}}
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">{{ number_format($userplan->plan_price, 2) }} {{ __($general->cur_text) }}</td>
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                    @if ($userplan->plan_duration == 1)
                                        @lang('1 Month')
                                    @elseif ($userplan->plan_duration == 12)
                                        @lang('Yearly')
                                    @else
                                        {{ $userplan->plan_duration }} @lang('Months')
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">{{ Str::limit(__($userplan->plan_content), 50) }}</td>
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.userplans.edit', $userplan->id) }}" class="btn btn-sm btn-outline--info" title="@lang('Manage Plan')">
                                            <i class="las la-cog"></i> @lang('Manage')
                                        </a>
                                        <button class="btn btn-sm btn-outline--primary editBtn"
                                            data-userplan="{{ json_encode($userplan) }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#userplanModal">
                                            <i class="las la-pencil-alt"></i> @lang('Edit')
                                        </button>
                                        <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                            data-action="{{ route('admin.userplans.destroy', $userplan->id) }}"
                                            data-method="POST"
                                            data-question="@lang('Are you sure you want to delete this market plan?')">
                                            <i class="las la-trash-alt"></i> @lang('Delete')
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                <td class="text-muted text-center" colspan="100%">
                                    {{ __($emptyMessage) }}
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($userplans->hasPages())
                <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                    {{ paginateLinks($userplans) }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- CREATE/EDIT MODAL --}}
<div class="modal fade" id="userplanModal" tabindex="-1" role="dialog" aria-labelledby="userplanModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="userplanModalLabel"></h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="las la-times"></i></button>
            </div>
            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id">

                    <div class="form-group">
                        <label>@lang('Plan Name (Tier Name)')</label>
                        <input type="text" class="form-control" name="plan_name" required placeholder="Ex: Elite Creator">
                    </div>

                    <div class="form-group">
                        <label>@lang('Price') ({{ gs('cur_text') }})</label>
                        <input type="number" step="0.01" class="form-control" name="plan_price" required placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label>@lang('Plan Duration (in months)')</label>
                        <select name="plan_duration" class="form-control" required>
                            <option value="">@lang('Select Duration')</option>
                            <option value="1">@lang('1 Month')</option>
                            <option value="3">@lang('3 Months')</option>
                            <option value="6">@lang('6 Months')</option>
                            <option value="12">@lang('Yearly')</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>@lang('Plan Features & Benefits')</label>
                        <textarea name="plan_content" class="form-control" rows="5" required placeholder="Enter features line by line..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>@lang('Marketplace Featured Status')</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured_plan" value="1" id="isFeaturedPlan">
                            <label class="form-check-label" for="isFeaturedPlan">@lang('Show profile at the top of the marketplace')</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>@lang('Contact Form Access')</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="contact_access" value="1" id="contactAccess">
                            <label class="form-check-label" for="contactAccess">@lang('Allow users to contact this creator via profile tab')</label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary w-100 h-45" id="btn-save"
                        value="add">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    {{-- Changed the link to a button to open the modal --}}
    <button type="button" class="btn btn-sm btn-outline--primary" data-bs-toggle="modal"
        data-bs-target="#userplanModal" id="createBtn">
        <i class="las la-plus"></i> @lang('Add New Market Plan')
    </button>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            let modal = $('#userplanModal');

            // Handle "Add New" button click
            $('#createBtn').on('click', function() {
                modal.find('.modal-title').text('@lang('Add New Market Plan')');
                // Set the form action to the store route for new records
                modal.find('form').attr('action', '{{ route('admin.userplans.store') }}');
                modal.find('input[name=id]').val('');
                modal.find('form')[0].reset();
            });

            // Handle "Edit" button click
            $('.editBtn').on('click', function() {
                const userplan = $(this).data('userplan');

                modal.find('.modal-title').text('@lang('Edit') ' + userplan.plan_name);
                // Set the form action to the update route for existing records
                let actionUrl = `{{ route('admin.userplans.update', ':id') }}`.replace(':id', userplan.id);
                modal.find('form').attr('action', actionUrl);
                modal.find('input[name=id]').val(userplan.id);
                modal.find('input[name=plan_name]').val(userplan.plan_name);
                modal.find('input[name=plan_price]').val(parseFloat(userplan.plan_price));
                // Updated to select the correct duration option
                modal.find('select[name=plan_duration]').val(userplan.plan_duration);
                modal.find('textarea[name=plan_content]').val(userplan.plan_content);
                modal.find('input[name=is_featured_plan]').prop('checked', userplan.is_featured_plan == 1);
                modal.find('input[name=contact_access]').prop('checked', userplan.contact_access == 1);
                modal.modal('show');
            });

            // Handle delete confirmation
            // The listener in index.blade.php was causing duplicate popups. 
            // We now rely solely on the global listener in the x-confirmation-modal component.

        })(jQuery);
    </script>
    <style>
        #userplanModal .modal-body {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }
    </style>
@endpush

