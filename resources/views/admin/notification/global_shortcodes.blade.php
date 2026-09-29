<div class="col-md-12">
    <div class="bg-white/60 dark:bg-white/[0.03] backdrop-blur-xl border border-white/20 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-xl mb-8">
        <div class="px-8 py-6 border-b border-slate-100 dark:border-white/5 flex items-center justify-between bg-slate-50/50 dark:bg-white/[0.02]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-500/10 flex items-center justify-center">
                    <span class="material-symbols-rounded text-indigo-500 text-xl">variable</span>
                </div>
                <div>
                    <h5 class="text-sm font-black uppercase tracking-widest text-slate-800 dark:text-white">@lang('Dynamic Shortcodes')</h5>
                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mt-0.5">Global Template Variables</p>
                </div>
            </div>
        </div>
        <div class="p-0">
            <div class="table-responsive">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/30 dark:bg-transparent">
                            <th class="px-8 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Short Code')</th>
                            <th class="px-8 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Description')</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                            <td class="px-8 py-4">
                                <span class="px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[11px] font-black tracking-wider">@{{fullname}}</span>
                            </td>
                            <td class="px-8 py-4 text-[11px] font-bold text-slate-600 dark:text-white/50">@lang('Full Name of User')</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                            <td class="px-8 py-4">
                                <span class="px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[11px] font-black tracking-wider">@{{username}}</span>
                            </td>
                            <td class="px-8 py-4 text-[11px] font-bold text-slate-600 dark:text-white/50">@lang('Username of User')</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                            <td class="px-8 py-4">
                                <span class="px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[11px] font-black tracking-wider">@{{message}}</span>
                            </td>
                            <td class="px-8 py-4 text-[11px] font-bold text-slate-600 dark:text-white/50">@lang('Message')</td>
                        </tr>
                        @foreach(gs('global_shortcodes') as $shortCode => $codeDetails)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                            <td class="px-8 py-4">
                                <span class="px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[11px] font-black tracking-wider">@{{@php echo $shortCode @endphp}}</span>
                            </td>
                            <td class="px-8 py-4 text-[11px] font-bold text-slate-600 dark:text-white/50">{{ __($codeDetails) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

