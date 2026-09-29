@extends('admin.layouts.app')
@section('title', 'Advance Ads')
@section('header_title', 'Ad Network')

@section('content')
    @php
        use Carbon\Carbon;
    @endphp

    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
        @include('admin.components.header-toolbar', [
            'title' => $pageTitle ?? 'Advance Ads',
            'items' => $advertisements,
            'createRoute' => route('admin.advertisement.create'),
            'createLabel' => 'Initialize Advertisement'
        ])
        <div class="px-6 pt-6">
            @include('admin.components.table-toolbar')
        </div>
        <div class="overflow-x-auto scrollbar-hide"><table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('User')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Ad Title')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Campaign Title')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Ad Reached')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Ad Engagement')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Daily Budget | Total Costs')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Ad Schedule Type')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Ad Type')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Status')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                @forelse ($advertisements as $advertisement)
                                    @php
                                        $totalAdDays = 0;

                                        if ($advertisement->schedule_type == 1) {
                                            $start = Carbon::parse($advertisement->start_date);
                                            $end = Carbon::parse($advertisement->end_date);

                                            $totalAdDays += $start->diffInDays($end) + 1;
                                        }

                                        if ($advertisement->schedule_type == 2 && $advertisement->schedules) {
                                            foreach ($advertisement->schedules as $schedule) {
                                                $start = Carbon::parse($schedule->custom_start_date);
                                                $end = Carbon::parse($schedule->custom_end_date);

                                                $totalAdDays += $start->diffInDays($end) + 1;
                                            }
                                        }

                                    @endphp


                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            {{ __($advertisement->user?->fullname) }} <br>

                                            <a
                                                href="{{ route('admin.users.detail', $advertisement->user_id) }}"><span>@</span>{{ $advertisement->user?->username }}</a>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <div class="campaign-item">
                                                <div class="campaign-item__content">
                                                    <p class="campaign-item__title ">{{ __($advertisement->title) }}</p>

                                                    <small class="text--primary">@lang('For '){{ round($totalAdDays) }}
                                                        @lang('Days')</small>


                                                </div>
                                            </div>
                                        </td>


                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            {{ __($advertisement->campaign?->title) }}
                                        </td>

                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            {{ formatNumber($advertisement->ad_reached) }} <br>
                                            <small class="text--success">@lang('Ads Reached'):
                                                {{ formatNumber($advertisement->adReaches()->count()) }} </small>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            {{ formatNumber($advertisement->ad_engagement) }} <br>
                                            <small class="text--success">@lang('Ads Engagement'):
                                                {{ formatNumber($advertisement->advertisementAnalytics()->count()) }}
                                            </small>

                                        </td>


                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            {{ showAmount($advertisement->daily_costs) }} <br>
                                            {{ showAmount($advertisement->total_amount) }}
                                        </td>

                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            @if ($advertisement->schedule_type == 1)
                                                @lang('Daily')
                                            @elseif ($advertisement->schedule_type == 2)
                                                @lang('Custom')
                                            @endif
                                        </td>



                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            @php
                                                echo $advertisement->adTypeBadge;
                                            @endphp

                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            @php
                                                echo $advertisement->statusBadge;
                                            @endphp
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <div class="button--group">
                                                <a href="{{ route('admin.advance.ads.detail', $advertisement->id) }}" class="btn btn-sm btn-outline--primary">
                                                    <i class="las la-desktop"></i>@lang('Detail')
                                                </a>

                                                @if($advertisement->status == Status::ADVERTISEMENT_PENDING)
                                                    <form action="{{ route('admin.advance.ads.approved', $advertisement->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline--success">
                                                            <i class="las la-check"></i>@lang('Approve')
                                                        </button>
                                                    </form>
                                                    <button type="button" @click="$dispatch('open-ad-reject-modal', { id: {{ $advertisement->id }}, title: '{{ $advertisement->title }}' })" class="btn btn-sm btn-outline--danger">
                                                        <i class="las la-times"></i>@lang('Reject')
                                                    </button>
                                                @endif

                                                @if ($advertisement->status == Status::RUNNING)
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                                        data-action="{{ route('admin.advance.ads.status', $advertisement->id) }}"
                                                        data-question="@lang('Are you sure want to pause this advertisement?')">
                                                        <i class="las la-pause"></i>@lang('Pause')
                                                    </button>
                                                @elseif($advertisement->status == Status::PAUSE)
                                                    <button class="btn btn-sm btn-outline--success confirmationBtn"
                                                        data-action="{{ route('admin.advance.ads.status', $advertisement->id) }}"
                                                        data-question="@lang('Are you sure want to start this advertisement?')">
                                                        <i class="las la-play"></i>@lang('Running')
                                                    </button>
                                                @endif

                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" 
                                                        data-action="{{ route('admin.advance.ads.delete', $advertisement->id) }}" 
                                                        data-method="DELETE"
                                                        data-question="@lang('Are you sure you want to delete this advertisement?')">
                                                    <i class="las la-trash"></i>@lang('Delete')
                                                </button>
                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage ?? 'No data found') }}</td>
                                    </tr>
                                @endforelse



                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($advertisements->hasPages())
                    <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                        {{ paginateLinks($advertisements) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
<!-- Ad Rejection Modal -->
<div x-data="{ 
        show: false, 
        adId: '', 
        adTitle: '',
        init() {
            window.addEventListener('open-ad-reject-modal', (e) => {
                this.adId = e.detail.id;
                this.adTitle = e.detail.title;
                this.show = true;
            });
        }
    }" 
    x-show="show" 
    class="fixed inset-0 z-[100] flex items-center justify-center" 
    x-cloak>
    
    <!-- Backdrop -->
    <div x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 backdrop-blur-0"
         x-transition:enter-end="opacity-100 backdrop-blur-sm"
         class="absolute inset-0 bg-black/60" 
         @click="show = false"></div>

    <!-- Modal Content -->
    <div x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-8"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         class="relative w-full max-w-md bg-white dark:bg-[#1a1a1a] rounded-[2rem] shadow-2xl border border-white/20 dark:border-white/10 overflow-hidden z-10 m-4">
        
        <form :action="`{{ url('admin/advance-ads/reject') }}/${adId}`" method="POST">
            @csrf
            <!-- Header -->
            <div class="h-24 bg-gradient-to-r from-rose-500 to-red-600 flex items-center px-8 relative overflow-hidden">
                <div class="absolute inset-0 bg-white/10 mix-blend-overlay"></div>
                <div class="relative z-10">
                    <h3 class="text-xl font-black text-white uppercase tracking-tighter shadow-sm">Reject Ad</h3>
                    <p class="text-[10px] font-bold text-white/80 uppercase tracking-widest mt-1">Title: <span x-text="adTitle"></span></p>
                </div>
            </div>

            <!-- Body -->
            <div class="p-8 space-y-6">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-widest ml-2">Rejection Reason</label>
                    <textarea name="message" required class="w-full h-32 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl p-4 text-xs font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" placeholder="Provide the reason for rejection..."></textarea>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02] flex gap-3">
                <button type="button" @click="show = false" class="flex-1 h-11 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-white/60 text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-white/10 transition-all ">
                    Cancel
                </button>
                <button type="submit" class="flex-1 h-11 rounded-xl bg-rose-500 text-white text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 transition-all shadow-lg shadow-rose-500/20 active:scale-95 flex items-center justify-center gap-2">
                    <span class="material-symbols-rounded text-sm">cancel</span> Reject
                </button>
            </div>
        </form>
    </div>
</div>
@endsection




@push('breadcrumb-plugins')
    <div class="flex items-center gap-3">
        <x-search-form placeholder="Username / Title" />
    </div>
@endpush

