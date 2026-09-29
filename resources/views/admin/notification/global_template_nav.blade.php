<div class="col-12 mb-10">
    <div class="flex flex-wrap items-center gap-4">
        <a href="{{ route('admin.setting.notification.global.email') }}" 
           class="flex-1 min-w-[200px] h-20 rounded-[2rem] flex items-center justify-between px-8 transition-all duration-500 group {{ menuActive('admin.setting.notification.global.email') ? 'bg-blue-600 text-white shadow-xl shadow-blue-500/20' : 'bg-white dark:bg-white/[0.03] text-slate-400 dark:text-white/20 border border-slate-100 dark:border-white/5 hover:border-blue-500/50' }}">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center {{ menuActive('admin.setting.notification.global.email') ? 'bg-white/20' : 'bg-slate-50 dark:bg-white/5' }}">
                    <span class="material-symbols-rounded text-2xl {{ menuActive('admin.setting.notification.global.email') ? 'text-white' : 'text-slate-400' }}">mail</span>
                </div>
                <h5 class="text-[11px] font-black uppercase tracking-widest">@lang('Email Template')</h5>
            </div>
            @if(menuActive('admin.setting.notification.global.email'))
                <span class="material-symbols-rounded text-xl animate-pulse">check_circle</span>
            @endif
        </a>

        <a href="{{ route('admin.setting.notification.global.sms') }}" 
           class="flex-1 min-w-[200px] h-20 rounded-[2rem] flex items-center justify-between px-8 transition-all duration-500 group {{ menuActive('admin.setting.notification.global.sms') ? 'bg-orange-600 text-white shadow-xl shadow-orange-500/20' : 'bg-white dark:bg-white/[0.03] text-slate-400 dark:text-white/20 border border-slate-100 dark:border-white/5 hover:border-orange-500/50' }}">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center {{ menuActive('admin.setting.notification.global.sms') ? 'bg-white/20' : 'bg-slate-50 dark:bg-white/5' }}">
                    <span class="material-symbols-rounded text-2xl {{ menuActive('admin.setting.notification.global.sms') ? 'text-white' : 'text-slate-400' }}">sms</span>
                </div>
                <h5 class="text-[11px] font-black uppercase tracking-widest">@lang('SMS Template')</h5>
            </div>
            @if(menuActive('admin.setting.notification.global.sms'))
                <span class="material-symbols-rounded text-xl animate-pulse">check_circle</span>
            @endif
        </a>

        <a href="{{ route('admin.setting.notification.global.push') }}" 
           class="flex-1 min-w-[200px] h-20 rounded-[2rem] flex items-center justify-between px-8 transition-all duration-500 group {{ menuActive('admin.setting.notification.global.push') ? 'bg-purple-600 text-white shadow-xl shadow-purple-500/20' : 'bg-white dark:bg-white/[0.03] text-slate-400 dark:text-white/20 border border-slate-100 dark:border-white/5 hover:border-purple-500/50' }}">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center {{ menuActive('admin.setting.notification.global.push') ? 'bg-white/20' : 'bg-slate-50 dark:bg-white/5' }}">
                    <span class="material-symbols-rounded text-2xl {{ menuActive('admin.setting.notification.global.push') ? 'text-white' : 'text-slate-400' }}">notifications_active</span>
                </div>
                <h5 class="text-[11px] font-black uppercase tracking-widest">@lang('Push Template')</h5>
            </div>
            @if(menuActive('admin.setting.notification.global.push'))
                <span class="material-symbols-rounded text-xl animate-pulse">check_circle</span>
            @endif
        </a>
    </div>
</div>
