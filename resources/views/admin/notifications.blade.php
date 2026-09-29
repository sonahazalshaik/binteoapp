@extends('admin.layouts.app')
@section('panel')
    <div class="max-w-5xl mx-auto pb-12 space-y-6">
        
        <!-- Header & Stats (Optional but makes it look premium) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 px-1">
            <div>
                <h4 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">@lang('Notifications')</h4>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">@lang('Stay updated with system alerts')</p>
            </div>
            
            <!-- Replaced Breadcrumb Plugins with inline action buttons to look more native to the page -->
            <div class="flex items-center gap-3">
                @if ($hasUnread)
                    <a href="{{ route('admin.notifications.read.all') }}" class="h-10 px-5 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-500/20 flex items-center justify-center gap-2 rounded-xl font-bold text-xs uppercase tracking-widest transition-all">
                        <i class="las la-check-double text-base"></i>
                        <span>@lang('Mark All Read')</span>
                    </a>
                @endif
                @if ($hasNotification)
                    <button class="h-10 px-5 bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-500/20 flex items-center justify-center gap-2 rounded-xl font-bold text-xs uppercase tracking-widest transition-all confirmationBtn" data-method="POST" data-action="{{ route('admin.notifications.delete.all') }}" data-question="@lang('Are you sure to delete all notifications?')">
                        <i class="las la-trash-alt text-base"></i>
                        <span>@lang('Clear All')</span>
                    </button>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-[#121212] rounded-[2rem] border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden">
            @forelse($notifications as $notification)
                @php $isUnread = $notification->is_read == Status::NO; @endphp
                <div class="group relative flex flex-col sm:flex-row sm:items-center justify-between p-5 sm:p-6 gap-4 border-b border-slate-100 dark:border-white/5 last:border-b-0 hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-all {{ $isUnread ? 'bg-indigo-50/30 dark:bg-indigo-500/[0.02]' : '' }}">
                    
                    @if($isUnread)
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500 rounded-r-full"></div>
                    @endif

                    <a href="{{ route('admin.notification.read', $notification->id) }}" class="flex items-start gap-4 flex-1 min-w-0 group-hover:pl-1 transition-all duration-300">
                        <!-- Icon -->
                        <div class="flex-shrink-0 w-12 h-12 rounded-2xl flex items-center justify-center shadow-sm {{ $isUnread ? 'bg-indigo-500 text-white shadow-indigo-500/30' : 'bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400' }}">
                            <i class="las la-bell text-2xl {{ $isUnread ? 'animate-pulse' : '' }}"></i>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1 min-w-0 pt-0.5">
                            <h6 class="text-sm sm:text-base font-bold truncate pr-4 {{ $isUnread ? 'text-slate-900 dark:text-white' : 'text-slate-700 dark:text-slate-300' }}">
                                {{ __($notification->title) }}
                            </h6>
                            <div class="flex items-center gap-2 mt-1.5 text-xs font-medium text-slate-400">
                                <i class="las la-clock text-sm"></i>
                                <span>{{ diffForHumans($notification->created_at) }}</span>
                            </div>
                        </div>
                    </a>

                    <!-- Actions -->
                    <div class="flex items-center shrink-0 pl-16 sm:pl-0">
                        <button type="button" class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-all confirmationBtn" data-method="POST" data-question="@lang('Are you sure to delete the notification?')" data-action="{{ route('admin.notifications.delete.single',$notification->id) }}" title="Delete">
                            <i class="las la-trash text-xl"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-24 text-center px-4">
                    <div class="w-24 h-24 rounded-[3rem] bg-slate-50 dark:bg-white/5 flex items-center justify-center text-slate-300 dark:text-white/10 mb-6 relative">
                        <i class="las la-bell-slash text-5xl"></i>
                        <div class="absolute -right-2 -bottom-2 w-8 h-8 rounded-full bg-white dark:bg-[#121212] flex items-center justify-center">
                            <div class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                        </div>
                    </div>
                    <h5 class="text-xl font-black text-slate-800 dark:text-white mb-2 tracking-tight">@lang('All caught up!')</h5>
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-widest max-w-xs">@lang('You have no new notifications to review at this time.')</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="mt-8 flex justify-center">
                {{ paginateLinks($notifications) }}
            </div>
        @endif
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <!-- We moved the action buttons into the main panel for a better integrated look -->
@endpush
