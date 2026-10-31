@extends('admin.layouts.app')
@section('title', 'Resolutions')
@section('header_title', 'Video Resolutions')

@section('content')
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
        @include('admin.components.header-toolbar', ['title' => 'Resolutions'])
        <div class="px-6 pt-6">
            @include('admin.components.table-toolbar')
        </div>
        <div class="overflow-x-auto scrollbar-hide" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
            <table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Label')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Width')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Height')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Status')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap text-right">@lang('Actions')</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                @forelse ($resolutions as $resolution)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">{{ __($resolution->resolution_label) }}</td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">{{ $resolution->width }}</td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">{{ $resolution->height }}</td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            @php
                                                echo $resolution->statusBadge;
                                            @endphp
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <div class="button--group justify-end">
                                                <button class="btn btn-sm btn-outline--primary editBtn" data-action="{{ route('admin.resolutions.save', $resolution->id) }}" data-resolution="{{ $resolution }}">
                                                    <i class="las la-pencil-alt"></i>@lang('Edit')
                                                </button>

                                                @if (@$resolution->status)
                                                    <button class="btn btn-sm btn-outline--warning confirmationBtn"
                                                            data-action="{{ route('admin.resolutions.status', $resolution->id) }}"
                                                            data-question="@lang('Are you sure you want to disable this resolution?')">
                                                        <i class="las la-eye-slash"></i>@lang('Disable')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--success confirmationBtn"
                                                            data-action="{{ route('admin.resolutions.status', $resolution->id) }}"
                                                            data-question="@lang('Are you sure you want to enable this resolution?')">
                                                        <i class="las la-eye"></i>@lang('Enable')
                                                    </button>
                                                @endif

                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" 
                                                        data-action="{{ route('admin.resolutions.delete', $resolution->id) }}" 
                                                        data-method="DELETE"
                                                        data-question="@lang('Are you sure you want to delete this resolution?')">
                                                    <i class="las la-trash"></i>@lang('Delete')
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
            </div><!-- card end -->
        </div>
    </div>


    {{-- NEW MODAL --}}
    <div class="modal fade" id="createModal" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bg-white dark:bg-[#121212] rounded-[2.5rem] shadow-2xl overflow-hidden ring-1 ring-white/5">
                <div class="modal-header border-0 bg-slate-50 dark:bg-white/[0.02] px-8 py-6">
                    <h4 class="modal-title text-[13px] font-black uppercase tracking-[0.3em] text-slate-900 dark:text-white " id="createModalLabel"> @lang('Add New')</h4>
                    <button class="w-10 h-10 rounded-xl bg-white dark:bg-white/5 text-slate-400 hover:text-rose-500 transition-all flex items-center justify-center" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>
                <form class="form-horizontal" method="post" action="">
                    @csrf
                    <div class="modal-body p-10 space-y-8">
                        <x-input name="resolution_label" label="Resolution Identifier" required="true" icon="label" hint="Example: 1080p, 4K, 720p HD." />

                        <div class="grid grid-cols-2 gap-8">
                            <x-input type="number" name="width" label="Width (px)" required="true" icon="width" hint="Horizontal pixel count." />
                            <x-input type="number" name="height" label="Height (px)" required="true" icon="height" hint="Vertical pixel count." />
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-10 pt-0">
                        <button class="w-full h-16 rounded-2xl orange-gradient-primary text-white text-[12px] font-black uppercase tracking-[0.2em] shadow-xl shadow-orange-500/20 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-3" id="btn-save" type="submit" value="add">
                            <span class="material-symbols-rounded">check_circle</span>
                            Commit Resolution
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder='Label' />
    <button class="btn btn-sm btn-outline--primary createBtn" type="button"><i class="las la-plus"></i>@lang('Add New')</button>
@endpush

@push('style')
    <style>
        .key-added {
            pointer-events: unset !important;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.createBtn').on('click', function() {
                var modal = $('#createModal');

                const url = "{{ route('admin.resolutions.save') }}"
                modal.find('form').attr('action', url);
                modal.find('.modal-title').text("@lang('Add Resulotions')");

                modal.find('[name="resolution_label"]').val('');
                modal.find('[name="width"]').val('');
                modal.find('[name="height"]').val('');

                modal.modal('show');
            });

            $('.editBtn').on('click', function() {
                var modal = $('#createModal');
                var resolution = $(this).data('resolution');
                var url = $(this).data('action');
                modal.find('form').attr('action', url);
                modal.find('.modal-title').text("@lang('Edit Resulotions')");
                modal.find('[name="resolution_label"]').val(resolution.resolution_label);
                modal.find('[name="width"]').val(resolution.width);
                modal.find('[name="height"]').val(resolution.height);
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush

