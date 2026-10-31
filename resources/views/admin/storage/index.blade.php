@extends('admin.layouts.app')
@section('title', 'Storage')
@section('header_title', 'Storage')

@section('content')
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
                <div class="overflow-x-auto scrollbar-hide"><table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Name')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Stroge Type')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Available Space')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Status')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                @forelse($storages as $k=>$storage)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            {{ __($storage->name) }}
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            @php
                                                echo $storage->storageType;
                                            @endphp
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            {{ $storage->available_space }} <span>MB</span>
                                        </td>

                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            @php
                                                echo $storage->statusBadge;
                                            @endphp
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <div class="button--group">


                                                @php
                                                    $url = '#';
                                                    if (@$storage->type == Status::WASABI_SERVER) {
                                                        $url = route('admin.storage.wasabi.form', @$storage->id);
                                                    } elseif (@$storage->type == Status::DIGITAL_OCEAN_SERVER) {
                                                        $url = route('admin.storage.digital.ocean.form', @$storage->id);
                                                    } elseif (@$storage->type == Status::FTP_SERVER) {
                                                        $url = route('admin.storage.ftp.form', @$storage->id);
                                                    }
                                                @endphp

                                                <a href="{{ $url }}"
                                                    class="btn btn-sm btn-outline--primary editGatewayBtn">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </a>

                                                <button class="btn btn-sm btn-outline--success checkBtn"
                                                    data-storage={{ $storage }}
                                                    data-action={{ route('admin.storage.check.config', $storage->id) }}>
                                                    <i class="las la-check"></i>
                                                    @lang('Check')</button>

                                                @if ($storage->status == Status::DISABLE)
                                                    <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn"
                                                        data-question="@lang('Are you sure to enable this storage?')"
                                                        data-action="{{ route('admin.storage.status', $storage->id) }}">
                                                        <i class="la la-eye"></i>@lang('Enable')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--danger ms-1 confirmationBtn"
                                                        data-question="@lang('Are you sure to disable this storage?')"
                                                        data-action="{{ route('admin.storage.status', $storage->id) }}">
                                                        <i class="la la-eye-slash"></i>@lang('Disable')
                                                    </button>
                                                @endif
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

    <x-confirmation-modal />
@endsection
@push('breadcrumb-plugins')
    <x-search-form />
    <div class="button--group">
                                                <a class="btn btn-sm btn-outline--primary " href="{{ route('admin.storage.wasabi.form') }}">@lang('Wasabi')</a>
                                                <a class="btn btn-sm btn-outline--danger " href="{{ route('admin.storage.digital.ocean.form') }}">@lang('Digital Ocean')</a>
                                                <a class="btn btn-sm btn-outline--success " href="{{ route('admin.storage.ftp.form') }}">@lang('FTP')</a>
                                            </div>
@endpush



@push('script')
    <script>
        $('.checkBtn').on('click', function() {
            let storage = $(this).data('storage');
            let action = $(this).data('action');
            const btn = $(this);


            btn.prop('disabled', true);
            btn.html(`<i class="las la-spinner la-spin"></i> @lang('Checking...')`);

            $.ajax({
                type: "get",
                url: action,
                success: function(response) {
                    if (response.status === 'success') {
                        btn.html(`<i class="las la-check"></i> @lang('Connected')`);
                        btn.removeClass('btn-outline--danger').addClass('btn-outline--success');
                        notify('success', response.message);
                    } else {
                        btn.html(`<i class="las la-times"></i> @lang('Failed')`);
                        btn.removeClass('btn-outline--success').addClass('btn-outline--danger');
                        notify('error', response.message);
                    }
                    btn.prop('disabled', false);
                }

            });
        });
    </script>
@endpush

