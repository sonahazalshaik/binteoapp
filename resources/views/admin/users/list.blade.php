@extends('admin.layouts.app')
@section('panel')
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
                <div class="overflow-x-auto scrollbar-hide"><table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('User')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Email-Mobile')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Country')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Joined At')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Balance')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($users as $user)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                    <span class="fw-bold">{{$user->fullname}}</span>
                                    <br>
                                    <span class="small">
                                    <a href="{{ route('admin.users.detail', $user->id) }}"><span>@</span>{{ $user->username }}</a>
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                    {{ $user->email }}<br>{{ $user->mobileNumber }}
                                </td>
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                    <span class="fw-bold" title="{{ @$user->country_name }}">{{ $user->country_code }}</span>
                                </td>



                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                    {{ showDateTime($user->created_at) }} <br> {{ diffForHumans($user->created_at) }}
                                </td>


                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                    <span class="fw-bold">

                                    {{ showAmount($user->balance) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                    <div class="button--group">
                                        <a href="{{ route('admin.users.detail', $user->id) }}" class="btn btn-sm btn-outline--primary">
                                            <i class="las la-desktop"></i> @lang('Details')
                                        </a>
                                        @if (request()->routeIs('admin.users.kyc.pending'))
                                        <a href="{{ route('admin.users.kyc.details', $user->id) }}" target="_blank" class="btn btn-sm btn-outline--dark">
                                            <i class="las la-user-check"></i>@lang('KYC Data')
                                        </a>
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
                @if ($users->hasPages())
                <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                    {{ paginateLinks($users) }}
                </div>
                @endif
            </div>
        </div>


    </div>
@endsection



@push('breadcrumb-plugins')
    <x-search-form placeholder="Username / Email" />
@endpush

