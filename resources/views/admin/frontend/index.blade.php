@extends('admin.layouts.app')

@section('title', 'Frontend Sections')

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">@lang('Content Management')</h2>
                    <p class="text-slate-500 dark:text-slate-400 font-medium mt-1">@lang('Manage and customize each section of your website.')</p>
                </div>
                
                <div class="relative group max-w-sm w-full">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="material-symbols-rounded text-slate-400 group-focus-within:text-orange-500 transition-colors">search</span>
                    </div>
                    <input type="text" 
                           class="searchInput block w-full pl-12 pr-4 py-3.5 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl text-slate-700 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all shadow-sm" 
                           placeholder="@lang('Search sections...')">
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
                <div class="overflow-x-auto scrollbar-hide">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-white/[0.02]">
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Section Name')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap text-center">@lang('Status')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap text-right">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @php $sections = getPageSections(true); $hasSections = false; @endphp
                            @foreach ($sections as $k => $secs)
                                @if ($secs['builder'] && !@$secs['hide_builder'])
                                    @php $hasSections = true; @endphp
                                    <tr class="searchItem hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="px-6 py-5">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-2xl bg-orange-500/10 flex items-center justify-center text-orange-500 group-hover:scale-110 transition-transform duration-500">
                                                    <span class="material-symbols-rounded text-2xl">layers</span>
                                                </div>
                                                <div>
                                                    <h6 class="text-[14px] font-bold text-slate-700 dark:text-white/80 section-name">{{ __($secs['name']) }}</h6>
                                                    <p class="text-[11px] text-slate-400 font-medium">@lang('Manage') {{ strtolower(__($secs['name'])) }} @lang('content')</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-500 uppercase tracking-wider">
                                                @lang('Active')
                                            </span>
                                        </td>
                                         <td class="px-6 py-5 text-right">
                                             <a href="{{ route('admin.frontend.sections', $k) }}" 
                                                class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 text-white text-[11px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-orange-500/20 hover:scale-105 active:scale-95 ">
                                                 <span class="material-symbols-rounded text-[18px]">edit_square</span>
                                                 @lang('Edit Content')
                                             </a>
                                         </td>
                                    </tr>
                                @endif
                            @endforeach
                            @if(!$hasSections)
                                <tr>
                                    <td colspan="100%" class="py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center mb-4">
                                                <span class="material-symbols-rounded text-4xl text-slate-300 dark:text-white/20">dashboard_customize</span>
                                            </div>
                                            <h5 class="text-slate-400 dark:text-white/30 font-bold">@lang('No manageable sections available.')</h5>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            <tr class="emptyArea d-none">
                                <td colspan="100%" class="py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center mb-4">
                                            <span class="material-symbols-rounded text-4xl text-slate-300 dark:text-white/20">search_off</span>
                                        </div>
                                        <h5 class="text-slate-400 dark:text-white/30 font-bold">@lang('No sections found matching your search')</h5>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            var searchInput = $('.searchInput');
            var searchItems = $('.searchItem');
            var emptyArea = $('.emptyArea');

            searchInput.on('input', function() {
                var query = $(this).val().toLowerCase().trim();
                var foundCount = 0;

                searchItems.each(function() {
                    var sectionName = $(this).find('.section-name').text().toLowerCase();
                    if (sectionName.indexOf(query) >= 0) {
                        $(this).show();
                        foundCount++;
                    } else {
                        $(this).hide();
                    }
                });

                if (foundCount === 0) {
                    emptyArea.removeClass('d-none');
                } else {
                    emptyArea.addClass('d-none');
                }
            });

        })(jQuery);
    </script>
@endpush

