@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card bl--5 border--primary">
                <div class="card-body">
                    <p class="text--primary">@lang('While you are adding a new keyword, it will only add to this current language only. Please be careful on entering a keyword, please make sure there is no extra space. It needs to be exact and case-sensitive.')</p>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="overflow-x-auto scrollbar-hide"><table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Name')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Code')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Default')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Actions')</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                @forelse ($languages as $item)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <div class="user">
                                                <div class="thumb">
                                                    <img src="{{ getImage(getFilePath('language') . '/' . $item->image, getFileSize('language')) }}" alt="{{ $item->name }}" class="plugin_bg">
                                                </div>
                                                <span class="name">{{ __($item->name) }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70"><strong>{{ __($item->code) }}</strong></td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            @if ($item->is_default == Status::YES)
                                                <span class="badge badge--success">@lang('Default')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Selectable')</span>
                                            @endif
                                        </td>
                                         <td class="px-6 py-4">
                                             <div class="flex items-center gap-2">
                                                 <a href="{{ route('admin.language.key', $item->id) }}" class="h-8 px-4 rounded-xl bg-emerald-500 text-white text-[9px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-sm flex items-center gap-2">
                                                     <span class="material-symbols-rounded text-sm">translate</span> @lang('Translate')
                                                 </a>
                                                 <button class="h-8 px-4 rounded-xl bg-blue-600 text-white text-[9px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-sm flex items-center gap-2 editBtn" 
                                                         data-url="{{ route('admin.language.manage.update', $item->id) }}" 
                                                         data-lang="{{ json_encode($item->only('name', 'text_align', 'is_default', 'image')) }}" 
                                                         data-image="{{ getImage(getFilePath('language') . '/' . $item->image, getFileSize('language')) }}">
                                                     <span class="material-symbols-rounded text-sm">edit_square</span> @lang('Edit')
                                                 </button>
                                                 @if ($item->id != 1)
                                                     <button class="h-8 px-4 rounded-xl bg-rose-600 text-white text-[9px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-sm flex items-center gap-2 confirmationBtn" 
                                                             data-question="@lang('Are you sure to remove this language from this system?')" 
                                                             data-action="{{ route('admin.language.manage.delete', $item->id) }}">
                                                         <span class="material-symbols-rounded text-sm">delete</span> @lang('Remove')
                                                     </button>
                                                 @endif
                                             </div>
                                         </td>
                                    </tr>
                                @empty
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="text-muted text-center py-20 opacity-30" colspan="100%">
                                            <span class="material-symbols-rounded text-4xl mb-2">language_off</span>
                                            <p class="text-[10px] font-black uppercase tracking-widest ">{{ __($emptyMessage) }}</p>
                                        </td>
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
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 rounded-[2rem] overflow-hidden shadow-2xl">
                <div class="modal-header border-b border-slate-100 dark:border-white/5 p-6">
                    <h4 class="modal-title text-xl font-black uppercase tracking-tighter " id="createModalLabel"> @lang('Add New Language')</h4>
                    <button type="button" class="h-10 w-10 rounded-full flex items-center justify-center bg-slate-50 dark:bg-white/5 text-slate-400 hover:text-rose-500 transition-colors" data-bs-dismiss="modal" aria-label="Close">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>
                <form class="form-horizontal" method="post" action="{{ route('admin.language.manage.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-8">
                        <div class="row">
                            <div class="form-group col-12 mb-6">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1 mb-2 block"> @lang('Language Flag')</label>
                                <x-image-uploader :imagePath="getImage(null, getFileSize('language'))" :size="getFileSize('language')" class="w-100" id="imageCreate" :required="true" />
                            </div>
                        </div>
                        <div class="row form-group mb-6">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1 mb-2 block">@lang('Language Name')</label>
                            <div class="col-sm-12">
                                <input type="text" class="w-full h-12 rounded-xl bg-slate-50 dark:bg-white/5 border-slate-200 dark:border-white/10 px-4 text-sm font-bold text-slate-700 dark:text-white outline-none focus:ring-4 focus:ring-blue-500/10 transition-all" value="{{ old('name') }}" name="name" required placeholder="e.g. English">
                            </div>
                        </div>

                        <div class="row form-group mb-6">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1 mb-2 block">@lang('Language Code')</label>
                            <div class="col-sm-12">
                                <input type="text" class="w-full h-12 rounded-xl bg-slate-50 dark:bg-white/5 border-slate-200 dark:border-white/10 px-4 text-sm font-bold text-slate-700 dark:text-white outline-none focus:ring-4 focus:ring-blue-500/10 transition-all" value="{{ old('code') }}" name="code" required placeholder="e.g. en">
                            </div>
                        </div>

                        <div class="row form-group mb-0">
                            <div class="col-md-12 flex items-center justify-between bg-slate-50 dark:bg-white/5 p-4 rounded-2xl">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-700 dark:text-white/70 mb-0" for="inputName">@lang('Set as Default Language')</label>
                                <input type="checkbox" data-width="80px" data-height="30px" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('YES')" data-off="@lang('NO')" name="is_default">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer border-t border-slate-100 dark:border-white/5 p-6">
                        <button type="submit" class="w-full h-12 rounded-xl bg-blue-600 text-white text-[11px] font-black uppercase tracking-[0.2em] hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-blue-500/20 " id="btn-save" value="add">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 rounded-[2rem] overflow-hidden shadow-2xl">
                <div class="modal-header border-b border-slate-100 dark:border-white/5 p-6">
                    <h4 class="modal-title text-xl font-black uppercase tracking-tighter " id="editModalLabel">@lang('Edit Language Configuration')</h4>
                    <button type="button" class="h-10 w-10 rounded-full flex items-center justify-center bg-slate-50 dark:bg-white/5 text-slate-400 hover:text-rose-500 transition-colors" data-bs-dismiss="modal" aria-label="Close">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>
                <form method="post" class="disableSubmission" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-8">
                        <div class="form-group mb-6">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1 mb-2 block"> @lang('Language Flag')</label>
                            <x-image-uploader :imagePath="getImage(null, getFileSize('language'))" :size="getFileSize('language')" class="w-100" id="imageEdit" :required="false" />
                        </div>
                        <div class="form-group mb-6">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1 mb-2 block">@lang('Language Name')</label>
                            <div class="col-sm-12">
                                <input type="text" class="w-full h-12 rounded-xl bg-slate-50 dark:bg-white/5 border-slate-200 dark:border-white/10 px-4 text-sm font-bold text-slate-700 dark:text-white outline-none focus:ring-4 focus:ring-blue-500/10 transition-all" value="{{ old('name') }}" name="name" required>
                            </div>
                        </div>

                        <div class="form-group mt-2 flex items-center justify-between bg-slate-50 dark:bg-white/5 p-4 rounded-2xl">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-700 dark:text-white/70 mb-0" for="inputName">@lang('Default Language Status')</label>
                            <input type="checkbox" data-width="80px" data-height="30px" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('YES')" data-off="@lang('NO')" name="is_default">
                        </div>
                    </div>
                    <div class="modal-footer border-t border-slate-100 dark:border-white/5 p-6">
                        <button type="submit" class="w-full h-12 rounded-xl bg-blue-600 text-white text-[11px] font-black uppercase tracking-[0.2em] hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-blue-500/20 " id="btn-save" value="add">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="getLangModal" tabindex="-1" role="dialog" aria-labelledby="getLangModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="getLangModalLabel">@lang('Language Keywords')</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="las la-times"></i></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">@lang('All of the possible language keywords are available here. However, some keywords may be missing due to variations in the database. If you encounter any missing keywords, you can add them manually.')</p>
                    <p class="text--primary mb-3">@lang('You can import these keywords from the translate page of any language as well.')</p>
                    <div class="form-group">
                        <textarea name="" class="form-control langKeys key-added" id="langKeys" rows="25" readonly></textarea>
                        <button type="button" class="btn btn--primary w-100 h-45 mt-3 copyBtn"><i class="las la-copy"></i> <span class="text-white copy-text">@lang('Copy')</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection


@push('breadcrumb-plugins')
    <button type="button" class="h-10 px-6 rounded-full bg-blue-600 text-white text-[11px] font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-md flex items-center gap-2" data-bs-toggle="modal" data-bs-target="#createModal">
        <span class="material-symbols-rounded text-lg">add</span>
        @lang('Add New')
    </button>
    <button type="button" class="h-10 px-6 rounded-full bg-indigo-600 text-white text-[11px] font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-md flex items-center gap-2 keyBtn" data-bs-toggle="modal" data-bs-target="#getLangModal">
        <span class="material-symbols-rounded text-lg">code</span>
        @lang('Language Keywords')
    </button>
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
            $('.editBtn').on('click', function() {
                var modal = $('#editModal');
                var url = $(this).data('url');
                var lang = $(this).data('lang');

                modal.find('form').attr('action', url);
                modal.find('input[name=name]').val(lang.name);
                modal.find('select[name=text_align]').val(lang.text_align);
                modal.find('.image-upload-preview').css('background-image', `url(${$(this).data('image')})`);
                if (lang.is_default == 1) {
                    modal.find('input[name=is_default]').bootstrapToggle('on');
                } else {
                    modal.find('input[name=is_default]').bootstrapToggle('off');
                }
                modal.modal('show');
            });

            $('.keyBtn').on('click', function(e) {
                e.preventDefault();
                $.get("{{ route('admin.language.get.key') }}", {}, function(data) {
                    $('.langKeys').text(data);
                });
            });

            $('.copyBtn').on('click', function() {
                var copyText = document.getElementById("langKeys");
                copyText.select();
                document.execCommand("copy");
                $('.copy-text').text('Copied');
                setTimeout(() => {
                    $('.copy-text').text('Copy');
                }, 2000);

            });

        })(jQuery);
    </script>
@endpush

