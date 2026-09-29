@extends('admin.layouts.app')
@section('title', 'Categories')
@section('header_title', 'Categories')

@section('content')
    <div x-data="{ 
        selectAll: false, 
        toggleAll() { 
            const checkboxes = document.querySelectorAll('.bulk-select-item');
            checkboxes.forEach(cb => { cb.checked = this.selectAll; });
        }
    }" class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
        @include('admin.components.header-toolbar', [
            'title' => 'Categories',
            'createRoute' => route('admin.category.create'),
            'createLabel' => 'Add Category'
        ])
    <div class="px-6 pt-6">
        @include('admin.components.table-toolbar', ['showBulkActions' => false])
    </div>

    <!-- Table View -->
    <div class="overflow-x-auto scrollbar-hide" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Name')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Slug')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Content Stats')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Status')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap text-right">@lang('Actions')</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse ($categories as $category)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-xl">
                                    <span class="material-symbols-rounded">{{ $category->icon ?? 'category' }}</span>
                                </div>
                                <span class="text-[11px] font-bold text-slate-700 dark:text-white/70">{{ __($category->name) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">{{ __($category->slug) }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-1.5" title="Videos">
                                    <span class="material-symbols-rounded text-sm text-slate-400">movie</span>
                                    <span class="text-[10px] font-black text-slate-600 dark:text-white/50">{{ $category->videos_count }}</span>
                                </div>
                                <div class="flex items-center gap-1.5" title="Reels">
                                    <span class="material-symbols-rounded text-sm text-slate-400">slow_motion_video</span>
                                    <span class="text-[10px] font-black text-slate-600 dark:text-white/50">{{ $category->reels_count }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                            @php echo $category->statusBadge; @endphp
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.category.edit', $category->id) }}" class="btn btn-sm btn-outline--primary">
                                    <i class="las la-pencil-alt"></i>@lang('Edit')
                                </a>

                                @if ($category->status)
                                    <button class="btn btn-sm btn-outline--warning confirmationBtn"
                                        data-method="POST"
                                        data-action="{{ route('admin.category.status', $category->id) }}"
                                        data-question="@lang('Are you sure want to disable this category?')">
                                        <i class="las la-eye-slash"></i>@lang('Disable')
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-outline--success confirmationBtn"
                                        data-method="POST"
                                        data-action="{{ route('admin.category.status', $category->id) }}"
                                        data-question="@lang('Are you sure want to enable this category?')">
                                        <i class="las la-eye"></i>@lang('Enable')
                                    </button>
                                @endif

                                <button class="btn btn-sm btn-outline--danger confirmationBtn" 
                                        data-action="{{ route('admin.category.delete', $category->id) }}" 
                                        data-method="DELETE"
                                        data-question="@lang('Are you sure you want to delete this category?')">
                                    <i class="las la-trash"></i>@lang('Delete')
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-muted text-center py-20" colspan="100%">
                            <div class="flex flex-col items-center gap-2">
                                <span class="material-symbols-rounded text-4xl opacity-20">category</span>
                                <span class="text-[11px] font-black uppercase tracking-widest opacity-40">No categories detected</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- App View (Grid) -->
    <div class="px-6 pb-6" x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse ($categories as $category)
                <div class="bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] p-6 border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-xl transition-all duration-300 group relative overflow-hidden">
                    <!-- Status Indicator -->
                    <div class="absolute top-6 right-6">
                        @php echo $category->statusBadge; @endphp
                    </div>

                    <div class="flex flex-col items-center text-center">
                        <div class="w-20 h-20 rounded-3xl bg-slate-50 dark:bg-white/5 flex items-center justify-center text-3xl mb-4 group-hover:scale-110 transition-transform duration-500">
                            <span class="material-symbols-rounded text-4xl">{{ $category->icon ?? 'category' }}</span>
                        </div>
                        
                        <h4 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tighter mb-1">{{ __($category->name) }}</h4>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-6">{{ __($category->slug) }}</p>

                        <!-- Stats Row -->
                        <div class="grid grid-cols-2 gap-4 w-full mb-8">
                            <div class="bg-slate-50 dark:bg-white/[0.02] rounded-2xl p-4 border border-slate-100 dark:border-white/5">
                                <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Videos</span>
                                <span class="block text-xl font-black text-slate-700 dark:text-white tracking-tighter">{{ $category->videos_count }}</span>
                            </div>
                            <div class="bg-slate-50 dark:bg-white/[0.02] rounded-2xl p-4 border border-slate-100 dark:border-white/5">
                                <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Reels</span>
                                <span class="block text-xl font-black text-slate-700 dark:text-white tracking-tighter">{{ $category->reels_count }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2 w-full mt-auto">
                            <a href="{{ route('admin.category.edit', $category->id) }}" class="flex-1 h-12 rounded-2xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/60 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all">
                                <span class="material-symbols-rounded">edit</span>
                            </a>
                            @if ($category->status)
                                <button class="flex-1 h-12 rounded-2xl bg-amber-500/10 text-amber-500 border border-amber-500/20 flex items-center justify-center hover:bg-amber-500 hover:text-white transition-all confirmationBtn"
                                    data-method="POST"
                                    data-action="{{ route('admin.category.status', $category->id) }}"
                                    data-question="@lang('Are you sure want to disable this category?')">
                                    <span class="material-symbols-rounded">visibility_off</span>
                                </button>
                            @else
                                <button class="flex-1 h-12 rounded-2xl bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 flex items-center justify-center hover:bg-emerald-500 hover:text-white transition-all confirmationBtn"
                                    data-method="POST"
                                    data-action="{{ route('admin.category.status', $category->id) }}"
                                    data-question="@lang('Are you sure want to enable this category?')">
                                    <span class="material-symbols-rounded">visibility</span>
                                </button>
                            @endif
                            <button class="flex-1 h-12 rounded-2xl bg-rose-500/10 text-rose-500 border border-rose-500/20 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all confirmationBtn" 
                                    data-action="{{ route('admin.category.delete', $category->id) }}" 
                                    data-method="DELETE"
                                    data-question="@lang('Are you sure you want to delete this category?')">
                                <span class="material-symbols-rounded">delete</span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-40 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[3.5rem]">
                    <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-6xl">category</span>
                    <p class="mt-6 text-[11px] font-black text-slate-400 uppercase tracking-widest">No categories detected</p>
                </div>
            @endforelse
        </div>
    </div>
                </div>
                @if ($categories->hasPages())
                    <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                        {{ paginateLinks($categories) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>


    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
@endpush

@push('style')
    <style>
        .key-added {
            pointer-events: unset !important;
        }
    </style>
@endpush



@push('style-lib')
    <link href="{{ asset('assets/admin/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/fontawesome-iconpicker.js') }}"></script>
@endpush


@push('script')
    <script>
        (function($) {
            "use strict";
            // Modal logic removed as it's now using separate pages
        })(jQuery);
    </script>
@endpush


