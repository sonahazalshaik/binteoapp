@extends('admin.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-24">
    <!-- Header -->
    <div class="flex items-center justify-between px-6 lg:px-0">
        <div>
            <h3 class="text-3xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Add Method</h3>
            <p class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3">Creating a new payout channel for users</p>
        </div>
        <a href="{{ route('admin.withdraw.method.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-400 hover:text-rose-500 flex items-center justify-center transition-all shadow-sm active:scale-90">
            <span class="material-symbols-rounded text-xl">close</span>
        </a>
    </div>

    <form action="{{ route('admin.withdraw.method.store') }}" method="POST" enctype="multipart/form-data" 
          x-data="{ synching: false }" 
          @submit="synching = true"
          class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Panel: Identity & Schedule -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Method Image -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm group">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-tight">Method Image</h3>
                            <span class="material-symbols-rounded text-blue-500">image</span>
                        </div>
                        <x-image-uploader :required="true" />
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest text-center">Payout Method Logo</p>
                    </div>
                </div>

                <!-- Withdraw Schedule -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-500">
                                <span class="material-symbols-rounded text-lg">calendar_month</span>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-tight">Withdraw Schedule</h3>
                        </div>

                        <div class="space-y-4">
                            <x-select name="schedule_type" label="Frequency" required="true">
                                <option value="">@lang('Select One')</option>
                                <option value="daily">@lang('Daily')</option>
                                <option value="weekly">@lang('Weekly')</option>
                                <option value="monthly">@lang('Monthly')</option>
                            </x-select>
                            <div class="schedule"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Core Parameters -->
            <div class="lg:col-span-8 space-y-6">
                <!-- General Settings -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                                <span class="material-symbols-rounded text-lg">settings</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">General Settings</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <x-input name="name" label="Method Name" value="{{ old('name') }}" required="true" icon="label" hint="The name shown to users." />
                            </div>
                            <x-input name="currency" label="Currency Symbol" value="{{ old('currency') }}" required="true" icon="currency_exchange" hint="e.g. USD, INR" />
                            <x-input type="number" step="any" name="rate" label="Conversion Rate" value="{{ old('rate') }}" required="true" icon="analytics" hint="1 {{ gs('cur_text') }} = ?" />
                        </div>
                    </div>
                </div>

                <!-- Withdrawal Limits -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Withdrawal Limits
                            </h4>
                            <div class="space-y-4">
                                <x-input type="number" step="any" name="min_limit" label="Minimum Amount" value="{{ old('min_limit') }}" required="true" icon="south" />
                                <x-input type="number" step="any" name="max_limit" label="Maximum Amount" value="{{ old('max_limit') }}" required="true" icon="north" />
                            </div>
                        </div>
                        <div class="space-y-6">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                Transaction Fees
                            </h4>
                            <div class="space-y-4">
                                <x-input type="number" step="any" name="fixed_charge" label="Fixed Charge" value="{{ old('fixed_charge') }}" required="true" icon="toll" />
                                <x-input type="number" step="any" name="percent_charge" label="Percent Charge (%)" value="{{ old('percent_charge') }}" required="true" icon="percent" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Instructions -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <x-textarea name="instruction" label="User Instructions" rows="4" icon="menu_book" hint="Guidance for the user when withdrawing.">{{ old('instruction') }}</x-textarea>
                </div>

                <!-- Required User Data -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-500">
                                <span class="material-symbols-rounded text-lg">fact_check</span>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-tight">Required User Data</h3>
                        </div>
                        <button type="button" class="h-10 px-6 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-black text-[9px] font-black uppercase tracking-widest flex items-center gap-2 hover:scale-105 transition-all shadow-lg form-generate-btn">
                            <span class="material-symbols-rounded text-sm">add_circle</span> Add Field
                        </button>
                    </div>
                    <div class="bg-slate-50 dark:bg-black/20 rounded-2xl p-6 border border-slate-100 dark:border-white/5">
                        <x-generated-form />
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-4">
                    <button type="submit" :disabled="synching" 
                            class="w-full h-16 rounded-2xl bg-blue-600 text-white text-sm font-bold uppercase tracking-widest hover:bg-blue-700 active:scale-95 transition-all flex items-center justify-center gap-3 disabled:opacity-50 shadow-lg shadow-blue-500/20">
                        <span x-show="!synching" class="material-symbols-rounded">add_circle</span>
                        <span x-show="synching" class="material-symbols-rounded animate-spin">sync</span>
                        <span x-text="synching ? 'Creating...' : 'Add New Method'">Add New Method</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
<x-form-generator-modal />
@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";

            var schedules = {
                weekly: `<div class="space-y-2 mt-4">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">@lang('Withdraw Day')</label>
                                <select name="schedule" class="w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 text-xs font-bold text-slate-700 dark:text-white outline-none focus:border-blue-500 transition-all" required>
                                    <option value="">@lang('Select One')</option>
                                    @foreach(workingDays() as $day)
                                        <option value="{{ $day }}">{{ __($day) }}</option>
                                    @endforeach
                                </select>
                            </div>`,

                monthly: `<div class="space-y-2 mt-4">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">@lang('Withdraw Date')</label>
                                    <select name="schedule" class="w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 text-xs font-bold text-slate-700 dark:text-white outline-none focus:border-blue-500 transition-all" required>
                                        <option value="">@lang('Select One')</option>
                                        @foreach(monthlySchedule() as $key => $value)
                                            <option value="{{ $key }}">{{ __($value) }}</option>
                                        @endforeach
                                    </select>
                                </div>`
            };

            $('select[name=schedule_type]').on('change', function () {
                var value = $(this).val();
                if(!value || value == 'daily'){
                    $('.schedule').empty();
                    return;
                }
                $('.schedule').html(schedules[value]);
            }).change();

            $('input[name=currency]').on('input', function () {
                $('.currency_symbol').text($(this).val());
            });

            @if(old('schedule_type'))
                $('select[name=schedule_type]').val('{{ old('schedule_type') }}').change();
                @if(old('schedule'))
                    $('select[name=schedule]').val('{{ old('schedule') }}');
                @endif
            @endif

        })(jQuery);
    </script>
@endpush

