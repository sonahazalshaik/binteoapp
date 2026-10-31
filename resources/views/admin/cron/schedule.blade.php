@extends('admin.layouts.app')
@section('panel')

<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
    <div class="px-6 py-8 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
        <div>
            <h3 class="text-xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Cron Schedules</h3>
            <p class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3 leading-relaxed">Manage execution intervals for system automated tasks</p>
        </div>
        <button class="h-12 px-8 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-black flex items-center justify-center gap-3 text-[10px] font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-xl addSchedule">
            <span class="material-symbols-rounded text-lg">add_circle</span>
            Add New
        </button>
    </div>

    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Name')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Interval')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Status')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right">@lang('Actions')</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse ($schedules as $schedule)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                        <td class="px-6 py-4">
                            <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ __($schedule->name) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[11px] font-bold text-slate-700 dark:text-white/70">{{ __($schedule->interval) }} @lang('Seconds')</span>
                        </td>
                        <td class="px-6 py-4"> @php echo $schedule->statusBadge; @endphp </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" class="btn btn-sm btn-outline--primary updateSchedule"
                                    data-id="{{ $schedule->id }}" data-name="{{ $schedule->name }}" data-interval="{{ $schedule->interval }}">
                                    <i class="las la-pen"></i> @lang('Edit')
                                </button>

                                @if (!$schedule->status)
                                    <button type="button" class="btn btn-sm btn-outline--success confirmationBtn"
                                        data-action="{{ route('admin.cron.schedule.status', $schedule->id) }}"
                                        data-question="@lang('Are you sure to enable this schedule?')">
                                        <i class="la la-eye"></i> @lang('Enable')
                                    </button>
                                @else
                                    <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                        data-action="{{ route('admin.cron.schedule.status', $schedule->id) }}"
                                        data-question="@lang('Are you sure to disable this schedule?')">
                                        <i class="la la-eye-slash"></i> @lang('Disable')
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-10 py-32 text-center text-slate-400 font-black uppercase tracking-widest text-[9px] opacity-20">{{ __($emptyMessage ?? 'No schedules detected') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($schedules->hasPages())
        <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
            {{ paginateLinks($schedules) }}
        </div>
    @endif
</div>

<x-confirmation-modal />

<!-- Premium Modal for Schedule Management -->
<div class="modal" id="addSchedule" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ">@lang('Add Cron Schedule')</h4>
                <button type="button" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 hover:text-orange-500 transition-all" data-bs-dismiss="modal">
                    <span class="material-symbols-rounded">close</span>
                </button>
            </div>
            <form class="disableSubmission resetForm" method="post" action="{{route('admin.cron.schedule.store')}}">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body space-y-2">
                    <x-input name="name" label="Schedule Name" placeholder="E.g. 5 Minutes" required hint="Enter a descriptive name for this execution interval." />
                    <x-input type="number" name="interval" label="Interval (Seconds)" placeholder="300" required hint="Define the duration in seconds between executions." />
                </div>
                <div class="modal-footer border-t-0 pt-4">
                    <button type="submit" class="w-full h-14 rounded-2xl bg-slate-900 dark:bg-white text-white dark:text-black flex items-center justify-center gap-3 text-[11px] font-black uppercase tracking-[0.2em] hover:scale-[1.02] active:scale-95 transition-all shadow-xl ">
                        <span class="material-symbols-rounded text-lg">verified</span>
                        Save Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('breadcrumb-plugins')
    <x-back route="{{route('admin.cron.index')}}" />
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.updateSchedule').on('click', function() {
                let title = "@lang('Refine Schedule')";
                var modal = $('#addSchedule');
                let id = $(this).data('id');
                let name = $(this).data('name');
                let interval = $(this).data('interval');
                modal.find('input[name=id]').val(id);
                modal.find('input[name=name]').val(name);
                modal.find('input[name=interval]').val(interval);
                modal.find('.modal-title').text(title)
                modal.modal('show');
            });

            $('.addSchedule').on('click', function() {
                let title = "@lang('New Schedule')";
                let modal = $('#addSchedule');
                $('.resetForm').trigger('reset');
                modal.find('input[name=id]').val('');
                modal.find('.modal-title').text(title)
                modal.modal('show');
            })
        })(jQuery);
    </script>
@endpush

