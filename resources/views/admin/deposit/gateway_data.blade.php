<div class="space-y-4">
	@foreach($details as $k => $val)
		<div class="bg-slate-50 dark:bg-white/[0.02] p-5 rounded-2xl border border-slate-100 dark:border-white/5">
			@if(is_object($val) || is_array($val))
				<h6 class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] mb-4">{{ keyToTitle($k) }}</h6>
				<div class="pl-4 border-l-2 border-slate-200 dark:border-white/10 space-y-4">
					@include('admin.deposit.gateway_data',['details'=>$val])
				</div>
			@else
				<span class="block text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] mb-2">{{ keyToTitle($k) }}</span>
				<p class="text-[13px] font-bold text-slate-700 dark:text-white/80 leading-relaxed">{{ @$val }}</p>
			@endif
		</div>
	@endforeach
</div>
