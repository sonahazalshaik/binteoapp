@extends('admin.layouts.app')
@section('title', 'Notifications')
@section('header_title', 'Notification Templates')

@section('content')
@push('topBar')
  @include('admin.notification.top_bar')
@endpush
    <div x-data="{ 
        selectAll: false, 
        toggleAll() { 
            const checkboxes = document.querySelectorAll('.bulk-select-item');
            checkboxes.forEach(cb => { cb.checked = this.selectAll; });
        }
    }" class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
        <div class="px-6 pt-6">
            @include('admin.components.table-toolbar', ['showBulkActions' => false])
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto scrollbar-hide" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-white/[0.02]">
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Template Name')</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Subject Line')</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap text-right">@lang('Configurations')</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($templates as $template)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-black text-slate-700 dark:text-white uppercase tracking-tighter">{{ __($template->name) }}</span>
                                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">System Trigger</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">{{ __($template->subject) }}</td>
                             <td class="px-6 py-4 text-right">
                                 <div class="flex justify-end items-center gap-3">
                                     <!-- Email -->
                                     <div class="flex items-center group/item">
                                         <a href="{{ route('admin.setting.notification.template.edit', ['email',$template->id]) }}" class="h-8 px-4 rounded-l-xl bg-orange-500 text-white text-[9px] font-black uppercase tracking-widest flex items-center hover:scale-105 transition-all shadow-sm">@lang('Email')</a>
                                         <div class="h-8 w-8 rounded-r-xl {{ $template->email_status == Status::ENABLE ? 'bg-emerald-500 text-white' : 'bg-slate-200 dark:bg-white/10 text-slate-400' }} flex items-center justify-center text-[10px] shadow-sm">
                                             <span class="material-symbols-rounded text-sm">{{ $template->email_status == Status::ENABLE ? 'check' : 'close' }}</span>
                                         </div>
                                     </div>
                                     <!-- SMS -->
                                     <div class="flex items-center group/item">
                                         <a href="{{ route('admin.setting.notification.template.edit', ['sms',$template->id]) }}" class="h-8 px-4 rounded-l-xl bg-blue-600 text-white text-[9px] font-black uppercase tracking-widest flex items-center hover:scale-105 transition-all shadow-sm">@lang('SMS')</a>
                                         <div class="h-8 w-8 rounded-r-xl {{ $template->sms_status == Status::ENABLE ? 'bg-emerald-500 text-white' : 'bg-slate-200 dark:bg-white/10 text-slate-400' }} flex items-center justify-center text-[10px] shadow-sm">
                                             <span class="material-symbols-rounded text-sm">{{ $template->sms_status == Status::ENABLE ? 'check' : 'close' }}</span>
                                         </div>
                                     </div>
                                     <!-- Push -->
                                     <div class="flex items-center group/item">
                                         <a href="{{ route('admin.setting.notification.template.edit', ['push',$template->id]) }}" class="h-8 px-4 rounded-l-xl bg-emerald-500 text-white text-[9px] font-black uppercase tracking-widest flex items-center hover:scale-105 transition-all shadow-sm">@lang('Push')</a>
                                         <div class="h-8 w-8 rounded-r-xl {{ $template->push_status == Status::ENABLE ? 'bg-emerald-500 text-white' : 'bg-slate-200 dark:bg-white/10 text-slate-400' }} flex items-center justify-center text-[10px] shadow-sm">
                                             <span class="material-symbols-rounded text-sm">{{ $template->push_status == Status::ENABLE ? 'check' : 'close' }}</span>
                                         </div>
                                     </div>
                                 </div>
                             </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="px-6 py-20 text-center opacity-30">
                                <span class="material-symbols-rounded text-4xl mb-2">notification_important</span>
                                <p class="text-[10px] font-black uppercase tracking-widest ">No templates detected</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- App View (Grid) -->
        <div class="px-6 pb-6" x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pt-6">
                @forelse($templates as $template)
                    <div class="bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] p-6 border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-xl transition-all duration-300 relative overflow-hidden group">
                        <div class="flex flex-col h-full">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 rounded-2xl bg-orange-500/10 text-orange-600 flex items-center justify-center">
                                    <span class="material-symbols-rounded">mail</span>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-[13px] font-black text-slate-900 dark:text-white uppercase tracking-tighter truncate leading-none mb-1">{{ __($template->name) }}</h4>
                                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Active Template</span>
                                </div>
                            </div>

                            <div class="bg-slate-50 dark:bg-white/[0.02] rounded-2xl p-4 mb-6 border border-slate-100 dark:border-white/5 flex-grow">
                                <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-2">Subject Header</span>
                                <p class="text-[11px] font-bold text-slate-700 dark:text-white/70 leading-relaxed">{{ __($template->subject) }}</p>
                            </div>

                            <div class="grid grid-cols-3 gap-2 mt-auto">
                                <a href="{{ route('admin.setting.notification.template.edit', ['email',$template->id]) }}" class="h-14 rounded-2xl border-2 {{ $template->email_status == Status::ENABLE ? 'border-orange-500 text-orange-500' : 'border-slate-100 dark:border-white/5 text-slate-300' }} flex flex-col items-center justify-center hover:bg-orange-500 hover:text-white transition-all">
                                    <span class="material-symbols-rounded text-lg">alternate_email</span>
                                    <span class="text-[8px] font-black uppercase tracking-widest mt-1">Email</span>
                                </a>
                                <a href="{{ route('admin.setting.notification.template.edit', ['sms',$template->id]) }}" class="h-14 rounded-2xl border-2 {{ $template->sms_status == Status::ENABLE ? 'border-blue-500 text-blue-500' : 'border-slate-100 dark:border-white/5 text-slate-300' }} flex flex-col items-center justify-center hover:bg-blue-500 hover:text-white transition-all">
                                    <span class="material-symbols-rounded text-lg">sms</span>
                                    <span class="text-[8px] font-black uppercase tracking-widest mt-1">SMS</span>
                                </a>
                                <a href="{{ route('admin.setting.notification.template.edit', ['push',$template->id]) }}" class="h-14 rounded-2xl border-2 {{ $template->push_status == Status::ENABLE ? 'border-emerald-500 text-emerald-500' : 'border-slate-100 dark:border-white/5 text-slate-300' }} flex flex-col items-center justify-center hover:bg-emerald-500 hover:text-white transition-all">
                                    <span class="material-symbols-rounded text-lg">notifications_active</span>
                                    <span class="text-[8px] font-black uppercase tracking-widest mt-1">Push</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center opacity-30">
                        <span class="material-symbols-rounded text-4xl mb-2">notification_important</span>
                        <p class="text-[10px] font-black uppercase tracking-widest ">No templates detected</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
@push('style')
    <style>
        i.fas.fa-circle {
            font-size: 12px;
        }
        .btn-group button{
            padding: 0px 15px;
        }
        .btn-group span{
            width: 34px;
            font-size: 10px;
            line-height: 24px;
        }
        .table td{
            white-space: unset;
        }

        .action-btns{
            display: flex;
            justify-content: flex-end;
            gap: 4px;
            row-gap: 5px;
            flex-wrap: wrap;
        }
    </style>
@endpush


