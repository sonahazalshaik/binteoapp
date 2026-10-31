@extends('admin.layouts.app')
@section('title', 'Plans')
@section('header_title', 'Monthly Plans')

@section('content')
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
        @include('admin.components.header-toolbar', ['title' => 'Plans'])
        <div class="px-6 pt-6">
            @include('admin.components.table-toolbar')
        </div>
        <div class="overflow-x-auto scrollbar-hide" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
            <table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Name')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('User')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Price')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Videos')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Playlist')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Status')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                @forelse ($plans as $plan)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">{{ __($plan->name) }}</td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            {{ __($plan->user?->fullname) }} <br>
                                            <a href="{{ route('admin.users.detail', $plan->user_id) }}">
                                                <span>@</span>{{ $plan->user?->username }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            {{ showAmount($plan->price) }}
                                        </td>

                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <a href="{{ route('admin.plan.videos.list', $plan->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                                {{ $plan->videos_count }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            {{ $plan->playlists_count }}
                                        </td>

                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            @php
                                                echo $plan->statusBadge;
                                            @endphp
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <div class="button--group">
        <a href="{{ Route::has('admin.plan.show') ? route('admin.plan.show', $plan->id ?? 0) : (Route::has('admin.plan.detail') ? route('admin.plan.detail', $plan->id ?? 0) : 'javascript:void(0)') }}" class="btn btn-sm btn-outline--info" title="@lang('View Details')">
            <i class="las la-desktop"></i>
        </a>
        <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ Route::has('admin.plan.destroy') ? route('admin.plan.destroy', $plan->id ?? 0) : (Route::has('admin.plan.delete') ? route('admin.plan.delete', $plan->id ?? 0) : 'javascript:void(0)') }}" data-question="@lang('Are you sure you want to delete this record?')" title="@lang('Delete')">
            <i class="las la-trash"></i>
        </button>
        
                                                <button class="btn btn-sm btn-outline--primary editBtn"
                                                    data-plan="{{ $plan }}"
                                                    data-action="{{ route('admin.plan.update', $plan->id) }}" title="@lang('Edit')">
                                                    <i class="las la-pencil-alt"></i>
                                                </button>

                                                @if (@$plan->status)
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                                        data-action="{{ route('admin.plan.status', $plan->id) }}"
                                                        data-question="@lang('Are you sure want to disable this plan?')" title="@lang('Disable')">
                                                        <i class="las la-eye-slash"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--success confirmationBtn"
                                                        data-action="{{ route('admin.plan.status', $plan->id) }}"
                                                        data-question="@lang('Are you sure want to enable this plan?')" title="@lang('Enable')">
                                                        <i class="las la-eye"></i>
                                                    </button>
                                                @endif

                                                <a href="{{ route('admin.plan.videos.list', $plan->id) }}" class="btn btn-sm btn-outline--info" title="@lang('Video List')">
                                                    <i class="las la-video"></i>
                                                </a>
                                                
                                                <a href="{{ route('admin.plan.playlist.list', $plan->id) }}" class="btn btn-sm btn-outline--dark" title="@lang('Playlist List')">
                                                    <i class="las la-list"></i>
                                                </a>
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
                @if ($plans->hasPages())
                    <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                        @php echo paginateLinks($plans) @endphp
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>

    <div id="createModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <form method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form--label">@lang('Name')</label>
                            <input type="text" class="form-control" name="name" required
                                placeholder="@lang('Enter plan name')">
                        </div>
                        <div class="form-group">
                            <label class="form--label">@lang('Price')</label>
                            <div class="input-group">
                                <input class="form-control" name="price" type="number" placeholder="@lang('Enter Price')"
                                    step="any" required>
                                <span class="input-group-text btn--base border-0">{{ __(gs('cur_text')) }}</span>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder='Title/username' />
@endpush

@push('script')
    <script>
        (function($) {

            "use strict";

            $('.editBtn').on('click', function() {
                var modal = $('#createModal');
                var data = $(this).data('plan');

                const url = $(this).data('action');
                modal.find('form').attr('action', url);

                modal.find('form').attr('action', url);
                modal.find('.modal-title').text("@lang('Edit Plan')");
                modal.find('[name="name"]').val(data.name);
                modal.find('[name="price"]').val(parseFloat(data.price).toFixed(2));
                modal.modal('show');
            });
        })(jQuery);
    </script>
    <style>
        #createModal .modal-body {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }
    </style>
@endpush

