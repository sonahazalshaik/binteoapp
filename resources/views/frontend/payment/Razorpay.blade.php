@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#0F0F0F] flex items-center justify-center py-20 px-6">
    <div class="w-full max-w-md animate-in zoom-in-95 duration-700">
        <div class="bg-white/5 backdrop-blur-3xl p-12 rounded-[4rem] border border-white/10 shadow-3xl text-center relative overflow-hidden">
            <div class="absolute -top-32 -left-32 w-64 h-64 bg-red-600/10 rounded-full blur-[100px]"></div>
            
            <span class="material-symbols-rounded text-6xl text-red-600 mb-6">payments</span>
            <h2 class="text-3xl font-black text-white uppercase tracking-tighter mb-10">Secure Gateway</h2>
            
            <div class="space-y-6 mb-12">
                <div class="flex justify-between items-center py-4 border-b border-white/5">
                    <span class="text-[10px] font-black text-white/40 uppercase tracking-widest">Payable Net</span>
                    <span class="text-xl font-black text-white">₹{{ showAmount($deposit->final_amount) }} {{ __($deposit->method_currency) }}</span>
                </div>
                <div class="flex justify-between items-center py-4 border-b border-white/5">
                    <span class="text-[10px] font-black text-white/40 uppercase tracking-widest">Credit Expected</span>
                    <span class="text-xl font-black text-white">₹{{ showAmount($deposit->amount) }} INR</span>
                </div>
            </div>

            <form action="{{ $data->url }}" method="{{ $data->method }}" class="mt-10">
                <input type="hidden" custom="{{ $data->custom }}" name="hidden">
                <script src="{{ $data->checkout_js }}"
                        @foreach($data->val as $key=>$value)
                            data-{{ $key }}="{{ $value }}"
                        @endforeach >
                </script>
            </form>
            
            <p class="mt-10 text-[9px] font-black text-white/20 uppercase tracking-[0.4em]">Encrypted Military-Grade Transaction</p>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";
            // Style the Razorpay button
            setTimeout(function() {
                $('.razorpay-payment-button').addClass("w-full py-5 bg-red-600 text-white rounded-[1.5rem] font-black uppercase tracking-[0.3em] text-xs shadow-2xl hover:scale-[1.02] active:scale-95 transition-all outline-none border-none");
            }, 500);
        })(jQuery);
    </script>
@endpush
