@extends('admin.layouts.app')
@section('title', 'Campaigns')
@section('header_title', 'Campaign System')

@section('content')
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
        @include('admin.components.header-toolbar', [
            'title' => 'Campaign Management',
            'items' => $campaigns,
            'createRoute' => Route::has('admin.campaign.create') ? route('admin.campaign.create') : 'javascript:void(0)',
            'createLabel' => 'New Campaign'
        ])
        <div class="px-6 pt-6">
            @include('admin.components.table-toolbar')
        </div>
        <div class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-white/[0.02]">
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Title')</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Slug')</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Total Amount')</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Available Amount')</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Payment Status')</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Status')</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right whitespace-nowrap">@lang('Actions')</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse ($campaigns as $campaign)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                            <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">{{ __($campaign->title) }}</td>
                            <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">{{ __($campaign->slug) }}</td>
                            <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                {{ showAmount($campaign->total_amount ) }}
                            </td>
                            <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                {{ showAmount($campaign->available_amount) }}
                            </td>
                            <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                @php echo $campaign->campaignPaymentStatus; @endphp
                            </td>
                            <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                @php echo $campaign->campaignStatus; @endphp
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.campaign.detail', $campaign->id) }}" class="btn btn-sm btn-outline--primary" title="@lang('Detail')">
                                        <i class="las la-desktop"></i>
                                    </a>
                                    @if (@$campaign->status)
                                        <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                            data-action="{{ route('admin.campaign.status', $campaign->id) }}"
                                            data-question="@lang('Are you sure want to disable this campaign?')">
                                            <i class="las la-eye-slash"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-outline--success confirmationBtn"
                                            data-action="{{ route('admin.campaign.status', $campaign->id) }}"
                                            data-question="@lang('Are you sure want to enable this campaign?')">
                                            <i class="las la-eye"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-sm btn-outline--danger confirmationBtn" 
                                            data-action="{{ Route::has('admin.campaigns.destroy') ? route('admin.campaigns.destroy', $campaign->id ?? 0) : (Route::has('admin.campaigns.delete') ? route('admin.campaigns.delete', $campaign->id ?? 0) : 'javascript:void(0)') }}" 
                                            data-question="@lang('Are you sure you want to delete this record?')" 
                                            title="@lang('Delete')">
                                        <i class="las la-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                            <td class="px-10 py-32 text-center text-slate-400 font-black uppercase tracking-widest text-[9px] opacity-20" colspan="100%">{{ __($emptyMessage ?? 'No campaigns found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($campaigns->hasPages())
            <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                {{ paginateLinks($campaigns) }}
            </div>
        @endif
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder='Name' />
@endpush

