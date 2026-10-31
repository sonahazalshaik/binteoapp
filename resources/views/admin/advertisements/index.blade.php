@extends('admin.layouts.app')
@section('title', 'Advertisements')
@section('header_title', 'Advertisement Manager')

@section('content')
<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
    @include('admin.components.header-toolbar', [
        'title' => $pageTitle ?? 'Advertisement Overview',
        'items' => $advertisements,
        'createRoute' => route('admin.advertisement.create'),
        'createLabel' => 'Initialize Advertisement'
    ])
    <div class="px-6 pt-6">
        @include('admin.components.table-toolbar', ['showBulkActions' => true, 'bulkActions' => ['delete']])
    </div>
    
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                        </label>
                    </th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">@lang('Advertiser')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">@lang('Campaign Meta')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">@lang('Impressions')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">@lang('Clicks')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">@lang('Budget')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">@lang('Status')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right truncate">@lang('Action')</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($advertisements as $advertisement)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                        <td class="px-6 py-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" value="{{ $advertisement->id }}" class="bulk-select-item w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                            </label>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[12px] font-black text-slate-900 dark:text-white uppercase leading-none mb-1">{{ __($advertisement->user->fullname) }}</div>
                            <a href="{{ route('admin.users.detail', $advertisement->user_id) }}" class="text-[10px] font-bold text-slate-500 dark:text-white/50 tracking-tight"><span>@</span>{{ $advertisement->user->username }}</a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[11px] font-black text-slate-900 dark:text-white mb-1">{{ __($advertisement->title) }}</div>
                            @if ($advertisement->url)
                                <a href="{{ $advertisement->url }}" target="_blank" class="text-[10px] font-bold text-blue-500 hover:text-blue-600 truncate max-w-[150px] inline-block"><i class="las la-external-link-alt mr-1"></i>Link</a>
                            @else
                                <span class="text-[10px] font-bold text-slate-400 ">No URL</span>
                            @endif
                            <div class="mt-2">
                                @php echo $advertisement->adTypeBadge; @endphp
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[13px] font-black text-slate-900 dark:text-white">{{ formatNumber($advertisement->impression) }}</div>
                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Avail: <span class="text-emerald-500">{{ formatNumber($advertisement->available_impression) }}</span></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[13px] font-black text-slate-900 dark:text-white">{{ formatNumber($advertisement->click) }}</div>
                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Avail: <span class="text-emerald-500">{{ formatNumber($advertisement->available_click) }}</span></div>
                        </td>
                        <td class="px-6 py-4 text-[12px] font-black text-emerald-600 dark:text-emerald-400">{{ showAmount($advertisement->total_amount) }}</td>
                        <td class="px-6 py-4">
                            @php echo $advertisement->statusBadge; @endphp
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.advertisement.edit', $advertisement->id) }}" class="btn btn-sm btn-outline--warning">
                                    <i class="las la-pencil-alt"></i>@lang('Edit')
                                </a>
                                @if ($advertisement->status == \App\Constants\Status::RUNNING)
                                    <button class="confirmationBtn btn btn-sm btn-outline--danger" @if($advertisement->payment_status != \App\Constants\Status::PAYMENT_SUCCESS) disabled @endif data-action="{{ route('admin.advertisement.status', $advertisement->id) }}" data-question="@lang('Are you sure want to pause this advertisement?')">
                                        <i class="las la-pause"></i>@lang('Pause')
                                    </button>
                                @elseif($advertisement->status == \App\Constants\Status::PAUSE)
                                    <button class="confirmationBtn btn btn-sm btn-outline--success" @if($advertisement->payment_status != \App\Constants\Status::PAYMENT_SUCCESS) disabled @endif data-action="{{ route('admin.advertisement.status', $advertisement->id) }}" data-question="@lang('Are you sure want to run this advertisement?')">
                                        <i class="las la-play"></i>@lang('Run')
                                    </button>
                                @endif
                                <button class="btn btn-sm btn-outline--danger confirmationBtn" 
                                        data-action="{{ route('admin.advertisement.delete', $advertisement->id) }}" 
                                        data-method="DELETE"
                                        data-question="@lang('Are you sure you want to delete this advertisement?')">
                                    <i class="las la-trash"></i>@lang('Delete')
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-10 py-32 text-center text-slate-400 font-black uppercase tracking-widest text-[9px] opacity-20">{{ __($emptyMessage ?? 'No advertisements found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($advertisements->hasPages())
        <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
            {{ paginateLinks($advertisements) }}
        </div>
    @endif
</div>
<x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <div class="flex items-center gap-3">
        <x-search-form placeholder="Username / Title" />
    </div>
@endpush

