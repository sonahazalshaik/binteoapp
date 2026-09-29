@extends('admin.layouts.app')

@section('title', 'Manage Pages')

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">@lang('Frontend Pages')</h2>
                    <p class="text-slate-500 dark:text-slate-400 font-medium mt-1">@lang('Create and manage dynamic pages for your website.')</p>
                </div>
                
                <button type="button" 
                        class="addBtn inline-flex items-center gap-2 px-6 py-3.5 bg-orange-500 hover:bg-orange-600 text-white text-[13px] font-bold rounded-2xl transition-all shadow-xl shadow-orange-500/20 active:scale-95">
                    <span class="material-symbols-rounded text-[20px]">add_circle</span>
                    @lang('Create New Page')
                </button>
            </div>

            <!-- Table Card -->
            <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
                <div class="overflow-x-auto scrollbar-hide">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-white/[0.02]">
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Page Name')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Slug')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap text-right">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($pData as $data)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-orange-500/10 flex items-center justify-center text-orange-500">
                                                <span class="material-symbols-rounded text-[18px]">description</span>
                                            </div>
                                            <span class="text-[13px] font-bold text-slate-700 dark:text-white/70">{{ __($data->name) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 bg-slate-100 dark:bg-white/5 rounded-lg text-[11px] font-mono text-slate-500 dark:text-slate-400">
                                            /{{ $data->slug }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.frontend.manage.section', $data->id) }}" 
                                               class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white transition-all shadow-sm"
                                               title="@lang('Manage Sections')">
                                                <span class="material-symbols-rounded text-[20px]">layers</span>
                                            </a>
                                            <a href="{{ route('admin.frontend.manage.pages.seo', $data->id) }}" 
                                               class="w-10 h-10 flex items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white transition-all shadow-sm"
                                               title="@lang('SEO Setting')">
                                                <span class="material-symbols-rounded text-[20px]">search</span>
                                            </a>
                                            @if($data->is_default == Status::NO)
                                                <button type="button" 
                                                        class="editBtn w-10 h-10 flex items-center justify-center rounded-xl bg-orange-500/10 text-orange-500 hover:bg-orange-500 hover:text-white transition-all shadow-sm"
                                                        data-id="{{ $data->id }}"
                                                        data-name="{{ $data->name }}"
                                                        data-slug="{{ $data->slug }}"
                                                        title="@lang('Edit Page')">
                                                    <span class="material-symbols-rounded text-[20px]">edit</span>
                                                </button>
                                                <button type="button" 
                                                        class="confirmationBtn w-10 h-10 flex items-center justify-center rounded-xl bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-sm"
                                                        data-action="{{ route('admin.frontend.manage.pages.delete', $data->id) }}"
                                                        data-question="@lang('Are you sure to remove this page?')"
                                                        title="@lang('Delete Page')">
                                                    <span class="material-symbols-rounded text-[20px]">delete</span>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center py-20" colspan="100%">
                                        <div class="flex flex-col items-center">
                                            <span class="material-symbols-rounded text-5xl text-slate-200 dark:text-white/10 mb-4">folder_off</span>
                                            <span class="text-slate-400 dark:text-white/30 font-bold">{{ __($emptyMessage) }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Add/Edit Page Modal --}}
    <div id="pageModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 rounded-[2rem] overflow-hidden bg-white dark:bg-[#121212] shadow-2xl">
                <div class="modal-header border-0 bg-slate-50 dark:bg-white/[0.02] px-8 py-6">
                    <h5 class="modal-title text-xl font-black text-slate-900 dark:text-white tracking-tight"> @lang('Add New Page')</h5>
                    <button type="button" class="close text-slate-400 hover:text-slate-600 transition-colors" data-bs-dismiss="modal">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>
                <form action="{{ route('admin.frontend.manage.pages.save') }}" method="POST" class="disableSubmission">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body px-8 py-6 space-y-6">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="text-[11px] font-black uppercase tracking-wider text-slate-400 dark:text-white/30">@lang('Page Name')</label>
                                <button type="button" class="buildSlug text-[10px] font-bold text-orange-500 hover:text-orange-600 transition-colors flex items-center gap-1">
                                    <span class="material-symbols-rounded text-[14px]">link</span>
                                    @lang('Generate Slug')
                                </button>
                            </div>
                            <input type="text" class="w-full px-4 py-3 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-slate-700 dark:text-white/80 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all outline-none" name="name" required placeholder="@lang('e.g. About Us')">
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="text-[11px] font-black uppercase tracking-wider text-slate-400 dark:text-white/30">@lang('Slug')</label>
                                <div class="slug-verification d-none"></div>
                            </div>
                            <input type="text" class="w-full px-4 py-3 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-slate-700 dark:text-white/80 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all outline-none" name="slug" required placeholder="@lang('e.g. about-us')">
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-8 py-6 pt-0">
                        <button type="submit" class="w-full py-4 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-2xl transition-all shadow-xl shadow-orange-500/20 active:scale-95 disabled:opacity-50">@lang('Save Page')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";

            const modal = $('#pageModal');
            const form = modal.find('form');
            const initialAction = form.attr('action');

            $('.addBtn').on('click', function () {
                modal.find('.modal-title').text("@lang('Add New Page')");
                form.attr('action', initialAction);
                form.find('[name=id]').val('');
                form.find('input[type=text]').val('');
                $('.slug-verification').addClass('d-none');
                modal.modal('show');
            });

            $('.editBtn').on('click', function () {
                modal.find('.modal-title').text("@lang('Edit Page')");
                form.attr('action', "{{ route('admin.frontend.manage.pages.update') }}");
                form.find('[name=id]').val($(this).data('id'));
                form.find('[name=name]').val($(this).data('name'));
                form.find('[name=slug]').val($(this).data('slug'));
                $('.slug-verification').addClass('d-none');
                modal.modal('show');
            });

            $('.buildSlug').on('click', function () {
                let title = form.find('[name=name]').val();
                form.find('[name=slug]').val(title).trigger('input');
            });

            $('[name=slug]').on('input', function () {
                let slug = $(this).val().toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
                $(this).val(slug);
                
                if (slug) {
                    $('.slug-verification').removeClass('d-none').html('<small class="text-orange-500 flex items-center gap-1"><span class="material-symbols-rounded text-[14px] animate-spin">progress_activity</span> @lang("Verifying...")</small>');
                    
                    $.get("{{ route('admin.frontend.manage.pages.check.slug') }}", { 
                        slug: slug, 
                        id: form.find('[name=id]').val() 
                    }, function (response) {
                        if (!response.exists) {
                            $('.slug-verification').html('<small class="text-emerald-500 flex items-center gap-1"><span class="material-symbols-rounded text-[14px]">check_circle</span> @lang("Available")</small>');
                            form.find('[type=submit]').prop('disabled', false);
                        } else {
                            $('.slug-verification').html('<small class="text-red-500 flex items-center gap-1"><span class="material-symbols-rounded text-[14px]">cancel</span> @lang("Already exists")</small>');
                            form.find('[type=submit]').prop('disabled', true);
                        }
                    });
                } else {
                    $('.slug-verification').addClass('d-none');
                }
            });

        })(jQuery);
    </script>
@endpush

