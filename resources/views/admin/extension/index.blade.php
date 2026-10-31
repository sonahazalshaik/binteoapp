@extends('admin.layouts.app')
@section('title', 'Extensions')
@section('header_title', 'Extension Matrix')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[3rem] overflow-hidden shadow-2xl transition-all duration-300">
                <div class="px-6 py-8 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">System Augmentations</h3>
                        <p class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3 leading-relaxed">Expand core capabilities with third-party intelligence modules</p>
                    </div>
                </div>
                
                <div class="px-6 pt-6">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
                        <div class="w-full sm:w-auto">
                            <div class="relative group">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-orange-500 transition-colors">
                                    <span class="material-symbols-rounded text-xl">search</span>
                                </span>
                                <input type="text" name="search_table" class="w-full sm:w-80 pl-12 pr-6 h-12 rounded-xl bg-slate-50 dark:bg-black/40 border border-slate-200 dark:border-white/10 outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all text-[11px] font-bold" placeholder="Filter extensions...">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto scrollbar-hide">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-white/[0.02]">
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Intelligence Hub')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Operating State')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap text-right">@lang('Operations')</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @foreach($extensions as $extension)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-white dark:bg-black/40 border border-slate-100 dark:border-white/10 p-2 shadow-sm group-hover:scale-110 transition-transform">
                                            <img src="{{ getImage(getFilePath('extensions') .'/'. $extension->image,getFileSize('extensions')) }}" alt="{{ __($extension->name) }}" class="w-full h-full object-contain grayscale group-hover:grayscale-0 transition-all">
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[13px] font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ __($extension->name) }}</span>
                                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Augmentation Module</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php echo $extension->statusBadge; @endphp
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" class="h-10 px-4 rounded-xl border border-slate-200 dark:border-white/10 text-[10px] font-black uppercase tracking-widest flex items-center gap-2 hover:bg-slate-900 hover:text-white transition-all editBtn"
                                                data-name="{{ __($extension->name) }}"
                                                data-shortcode="{{ json_encode($extension->shortcode) }}"
                                                data-action="{{ route('admin.extensions.update', $extension->id) }}">
                                            <span class="material-symbols-rounded text-sm">tune</span>
                                            @lang('Configure')
                                        </button>
                                        <button type="button" class="h-10 w-10 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-500 flex items-center justify-center hover:bg-orange-500 hover:text-white transition-all helpBtn"
                                                data-description="{{ __($extension->description) }}"
                                                data-support="{{ __($extension->support) }}">
                                            <span class="material-symbols-rounded text-sm">help</span>
                                        </button>
                                        @if($extension->status == Status::DISABLE)
                                            <button type="button" class="h-10 px-4 rounded-xl bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-widest flex items-center gap-2 hover:bg-emerald-500 hover:text-white transition-all confirmationBtn"
                                                    data-action="{{ route('admin.extensions.status', $extension->id) }}"
                                                    data-question="@lang('Are you sure to enable this extension?')">
                                                <span class="material-symbols-rounded text-sm">play_circle</span>
                                                @lang('Enable')
                                            </button>
                                        @else
                                            <button type="button" class="h-10 px-4 rounded-xl bg-rose-500/10 text-rose-500 text-[10px] font-black uppercase tracking-widest flex items-center gap-2 hover:bg-rose-500 hover:text-white transition-all confirmationBtn"
                                                    data-action="{{ route('admin.extensions.status', $extension->id) }}"
                                                    data-question="@lang('Are you sure to disable this extension?')">
                                                <span class="material-symbols-rounded text-sm">pause_circle</span>
                                                @lang('Disable')
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- CONFIGURATION MODAL --}}
    <div id="editModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title ">@lang('Refine Intelligence'): <span class="extension-name text-orange-500"></span></h5>
                    <button type="button" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 hover:text-orange-500 transition-all" data-bs-dismiss="modal">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>
                <form method="POST">
                    @csrf
                    <div class="modal-body space-y-2">
                        <!-- Dynamically Generated Content -->
                    </div>
                    <div class="modal-footer border-t-0 pt-0">
                        <button type="submit" class="w-full h-16 rounded-2xl bg-slate-900 dark:bg-white text-white dark:text-black flex items-center justify-center gap-3 text-[11px] font-black uppercase tracking-[0.2em] hover:scale-[1.02] active:scale-95 transition-all shadow-xl " id="editBtn">
                            <span class="material-symbols-rounded text-lg">verified</span>
                            Apply Configuration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- HELP MODAL --}}
    <div id="helpModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title ">@lang('Documentation Hub')</h5>
                    <button type="button" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 hover:text-orange-500 transition-all" data-bs-dismiss="modal">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-6 rounded-3xl bg-slate-50 dark:bg-black/40 border border-slate-200 dark:border-white/10 text-[13px] font-bold text-slate-600 dark:text-white/60 leading-relaxed description-content">
                        <!-- Description Content -->
                    </div>
                    <div class="mt-8 support-content flex justify-center">
                        <!-- Support Image Content -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";

            $(document).on('click', '.editBtn',function () {
                var modal = $('#editModal');
                var shortcode = $(this).data('shortcode');

                modal.find('.extension-name').text($(this).data('name'));
                modal.find('form').attr('action', $(this).data('action'));

                var html = '';
                $.each(shortcode, function (key, item) {
                    html += `
                        <div class="mb-10">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/30 px-1 mb-2">
                                ${item.title} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input name="${key}" class="w-full px-6 h-16 rounded-2xl border border-slate-200/60 dark:border-white/5 bg-white dark:bg-black/20 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all duration-300 outline-none text-[13px] font-black text-slate-900 dark:text-white" placeholder="Enter ${item.title}..." value="${item.value}" required>
                            </div>
                        </div>`;
                })
                modal.find('.modal-body').html(html);
                modal.modal('show');
            });

            $(document).on('click', '.helpBtn',function () {
                var modal = $('#helpModal');
                var path = "{{ asset(getFilePath('extensions')) }}";
                modal.find('.description-content').html($(this).data('description'));
                modal.find('.support-content').html('');
                if ($(this).data('support') != 'na') {
                    modal.find('.support-content').append(`<img src="${path}/${$(this).data('support')}" alt="Support Documentation" class="rounded-3xl border border-white/10 shadow-2xl max-w-full">`);
                }
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush

