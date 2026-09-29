<div class="row">
    <div class="col-md-12">
        <div class="card overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive table-responsive--sm">
                    <table class="table align-items-center table--light">
                        <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Short Code')</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Description')</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                            @foreach($template->shortcodes as $shortcode => $key)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                {{-- blade-formatter-disable --}}
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70"><span class="short-codes">@php echo "{{". $shortcode ."}}"  @endphp</span></td>
                                {{-- blade-formatter-enable --}}
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">{{ __($key) }}</td>
                            </tr>
                            @endforeach
                            @foreach(gs('global_shortcodes') as $shortCode => $codeDetails)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                {{-- blade-formatter-disable --}}
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70"><span class="short-codes">@{{@php echo $shortCode @endphp}}</span></td>
                                {{-- blade-formatter-enable --}}
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">{{ __($codeDetails) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- card end -->

    </div>
</div>

