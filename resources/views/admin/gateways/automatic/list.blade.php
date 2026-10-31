@extends('admin.layouts.app')
@section('panel')
@push('topBar')
@include('admin.gateways.top_bar')
@endpush
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
        @include('admin.components.header-toolbar', ['title' => 'Automatic Gateways'])
        <div class="px-6 pt-6">
            @include('admin.components.table-toolbar')
        </div>
        <div class="overflow-x-auto scrollbar-hide"><table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Gateway')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Supported Currency')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Enabled Currency')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Status')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($gateways->sortBy('alias') as $k=>$gateway)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                        {{__($gateway->name)}}
                                    </td>

                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                        {{ collect($gateway->supported_currencies)->count() }}
                                    </td>
                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                        {{ $gateway->currencies->count() }}
                                    </td>


                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                        @php
                                            echo $gateway->statusBadge
                                        @endphp
                                    </td>
                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                        <div class="button--group">
                                            <a href="{{ route('admin.gateway.automatic.edit', $gateway->alias) }}" class="btn btn-sm btn-outline--primary editGatewayBtn">
                                                <i class="la la-pencil"></i>@lang('Edit')
                                            </a>


                                            @if($gateway->status == Status::DISABLE)
                                                <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn" data-question="@lang('Are you sure to enable this gateway?')" data-action="{{ route('admin.gateway.automatic.status',$gateway->id) }}">
                                                    <i class="la la-eye"></i>@lang('Enable')
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-outline--danger ms-1 confirmationBtn" data-question="@lang('Are you sure to disable this gateway?')" data-action="{{ route('admin.gateway.automatic.status',$gateway->id) }}">
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
    <div class="d-inline">
        <div class="input-group justify-content-end">
            <input type="text" name="search_table" class="form-control bg--white" placeholder="@lang('Search')...">
            <button class="btn btn--primary input-group-text"><i class="fas fa-search"></i></button>
        </div>
    </div>
@endpush

