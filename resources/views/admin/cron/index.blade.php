@extends('admin.layouts.app')
@section('title', 'Cron Jobs')
@section('header_title', 'Scheduled Tasks')

@section('content')

<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
    <div class="px-6 py-8 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
        <div>
            <h3 class="text-xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Intelligence Automations</h3>
            <p class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3 leading-relaxed">Monitor and manage the autonomous operation logic</p>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{route('admin.cron.schedule')}}" class="h-12 px-8 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-white/40 flex items-center justify-center gap-3 text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-white/10 transition-all border border-transparent">
                <span class="material-symbols-rounded text-lg">clock_loader_40</span>
                Schedules
            </a>
            <button class="h-12 px-8 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-black flex items-center justify-center gap-3 text-[10px] font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-xl addCron">
                <span class="material-symbols-rounded text-lg">add_circle</span>
                New Cron
            </button>
        </div>
    </div>

    <div class="px-6 pt-6">
        @include('admin.components.table-toolbar')
    </div>

    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Job Identity')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Interval')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Execution Matrix')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('State')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap text-right">@lang('Operations')</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse ($crons as $cron)
                    @php
                        $dateTime = now()->parse($cron->next_run);
                        $formattedDateTime = showDateTime($dateTime,'Y-m-d\TH:i');
                    @endphp
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-[12px] font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ __($cron->name) }} @if($cron->logs->where('error','!=',null)->count()) <i class="las la-exclamation-triangle text-red-500 animate-pulse"></i> @endif</span>
                                <code class="text-[9px] text-orange-500 font-bold uppercase mt-1">{{ __($cron->alias) }}</code>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[11px] font-bold text-slate-700 dark:text-white/70">{{ __($cron->schedule->name) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-[8px] font-black text-slate-400 uppercase">Next:</span>
                                    <span class="text-[10px] font-bold text-slate-600 dark:text-white/60">@if($cron->next_run) {{ __($cron->next_run) }} @else -- @endif</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[8px] font-black text-slate-400 uppercase">Last:</span>
                                    <span class="text-[10px] font-bold text-slate-600 dark:text-white/60">@if($cron->last_run) {{ __($cron->last_run) }} @else -- @endif</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if($cron->is_running)
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                                    <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">Active</span>
                                @else
                                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Paused</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="dropdown inline-block">
                                <button class="h-10 px-4 rounded-xl border border-slate-200 dark:border-white/10 text-[10px] font-black uppercase tracking-widest flex items-center gap-2 hover:bg-slate-50 dark:hover:bg-white/5 transition-all" data-bs-toggle="dropdown">
                                    <span class="material-symbols-rounded text-sm">settings_input_component</span>
                                    Manage
                                </button>
                                <div class="dropdown-menu p-2 bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl">
                                    <a href="{{ route('cron') }}?alias={{$cron->alias}}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-white/60 hover:bg-emerald-500/10 hover:text-emerald-500 transition-all">
                                        <i class="las la-check-circle text-lg"></i> Run Now
                                    </a>
                                    @if($cron->is_running)
                                        <a href="{{ route('admin.cron.schedule.pause', $cron->id) }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-white/60 hover:bg-orange-500/10 hover:text-orange-500 transition-all">
                                            <i class="las la-pause text-lg"></i> Pause
                                        </a>
                                    @else
                                        <a href="{{ route('admin.cron.schedule.pause', $cron->id) }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-white/60 hover:bg-emerald-500/10 hover:text-emerald-500 transition-all">
                                            <i class="las la-play text-lg"></i> Play
                                        </a>
                                    @endif
                                    <a href="#" data-id="{{ $cron->id }}" data-name="{{ $cron->name }}" data-url="{{ $cron->url }}" data-next_run="{{ $formattedDateTime }}" data-cron_schedule_id="{{ $cron->cron_schedule_id }}" data-default="{{ $cron->is_default }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-white/60 hover:bg-blue-500/10 hover:text-blue-500 transition-all updateCron">
                                        <i class="las la-pen text-lg"></i> Edit
                                    </a>
                                    <a href="{{ route('admin.cron.schedule.logs', $cron->id) }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-white/60 hover:bg-slate-100 dark:hover:bg-white/5 transition-all">
                                        <i class="las la-history text-lg"></i> Logs
                                    </a>
                                    @if(!$cron->is_default)
                                        <a href="javascript:void(0)" data-action="{{ route('admin.cron.delete', $cron->id) }}" data-question="@lang('Are you sure to delete this cron?')" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest text-red-500 hover:bg-red-500/10 transition-all confirmationBtn">
                                            <i class="las la-trash text-lg"></i> Delete
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-10 py-32 text-center text-slate-400 font-black uppercase tracking-widest text-[9px] opacity-20">No intelligence tasks active</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($crons->hasPages())
        <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
            {{ paginateLinks($crons) }}
        </div>
    @endif
</div>

<x-confirmation-modal />

<!-- Premium Add/Edit Modals -->
<div class="modal" id="addCron" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ">@lang('Deploy New Logic')</h4>
                <button type="button" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 hover:text-orange-500 transition-all" data-bs-dismiss="modal">
                    <span class="material-symbols-rounded">close</span>
                </button>
            </div>
            <form class="disableSubmission resetForm" method="post" action="{{route('admin.cron.store')}}">
                @csrf
                <div class="modal-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8">
                        <x-input name="name" label="Job Identity" placeholder="E.g. Video Processing" required hint="Enter a unique name for this automated task." />
                        <x-input type="datetime-local" name="next_run" label="Next Run" required hint="Define the next scheduled execution timestamp." />
                        <x-select name="cron_schedule_id" label="Execution Schedule" required hint="Select the predefined interval for this task.">
                            @foreach($schedules as $schedule)
                                <option value="{{ $schedule->id }}">{{ $schedule->name }}</option>
                            @endforeach
                        </x-select>
                        <x-input name="url" label="Target URL" placeholder="https://domain.com/cron/task" required hint="The absolute URL that triggers this automation." />
                    </div>
                </div>
                <div class="modal-footer border-t-0 pt-4">
                    <button type="submit" class="w-full h-14 rounded-2xl bg-slate-900 dark:bg-white text-white dark:text-black flex items-center justify-center gap-3 text-[11px] font-black uppercase tracking-[0.2em] hover:scale-[1.02] active:scale-95 transition-all shadow-xl ">
                        <span class="material-symbols-rounded text-lg">deployed_code</span>
                        Initialize Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="updateCron" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ">@lang('Refine Task Logic')</h4>
                <button type="button" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 hover:text-orange-500 transition-all" data-bs-dismiss="modal">
                    <span class="material-symbols-rounded">close</span>
                </button>
            </div>
            <form class="resetForm" method="post" action="{{route('admin.cron.update')}}">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8">
                        <x-input name="name" label="Job Identity" required hint="Update the name of this automated task." />
                        <x-input type="datetime-local" name="next_run" label="Next Run" required hint="Modify the next scheduled execution timestamp." />
                        <x-select name="cron_schedule_id" label="Execution Schedule" required hint="Modify the execution interval for this task.">
                            @foreach($schedules as $schedule)
                                <option value="{{ $schedule->id }}">{{ $schedule->name }}</option>
                            @endforeach
                        </x-select>
                        <div class="urlGroup">
                            <x-input name="url" label="Target URL" hint="Update the trigger URL for this automation." />
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-t-0 pt-4">
                    <button type="submit" class="w-full h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center gap-3 text-[11px] font-black uppercase tracking-[0.2em] hover:scale-[1.02] active:scale-95 transition-all shadow-xl ">
                        <span class="material-symbols-rounded text-lg">save_as</span>
                        Update Logic
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.addCron').on('click', function() {
                let modal = $('#addCron');
                $('.resetForm').trigger('reset');
                modal.modal('show');
            });

            $('.updateCron').on('click', function(e) {
                e.preventDefault();
                var modal = $('#updateCron');
                let id = $(this).data('id');
                let name = $(this).data('name');
                let next_run = $(this).data('next_run');
                let cron_schedule_id = $(this).data('cron_schedule_id');
                let isDefault = $(this).data('default');
                
                if(isDefault){
                    modal.find('[name=url]').attr('required', false);
                    $('.urlGroup').hide();
                }else{
                    modal.find('[name=url]').attr('required', true);
                    modal.find('[name=url]').val($(this).data('url'));
                    $('.urlGroup').show();
                }
                
                modal.find('input[name=id]').val(id);
                modal.find('input[name=name]').val(name);
                modal.find('input[name=next_run]').val(next_run);
                modal.find('select[name=cron_schedule_id]').val(cron_schedule_id);
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush

