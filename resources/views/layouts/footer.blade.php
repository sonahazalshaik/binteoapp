<footer class="hidden lg:block bg-white dark:bg-[#0F0F0F] border-t border-gray-100 dark:border-white/5 py-12 transition-colors duration-500 {{ request()->routeIs('reels.index') || request()->routeIs('marketplace.dashboard') || request()->routeIs('login') || request()->routeIs('register') || request()->routeIs('password.*') ? '' : 'lg:ml-72' }}">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-center space-y-6 md:space-y-0">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-gradient-to-tr from-[#FF4B2B] to-[#FF416C] rounded-xl p-[1.5px] shadow-xl shadow-red-200/50 overflow-hidden">
                    <div class="w-full h-full bg-white rounded-[11px] flex items-center justify-center p-1.5">
                        <img src="{{ siteLogo() }}" class="w-full h-full object-contain" alt="{{ gs('site_name') }}">
                    </div>
                </div>
                <span class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">{{ gs('site_name') }}</span>
            </div>

            <div class="text-[11px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-[0.4em]">
                &copy; {{ date('Y') }} {{ gs('site_name') }}. Native OTT Experience.
            </div>

            <div class="flex space-x-8 text-[11px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-600">
                <a href="{{ route('marketplace.terms') }}" class="hover:text-red-600 dark:hover:text-white transition-all">Marketplace Terms</a>
                @php
                    $footerPolicies = getContent('policy_pages.element')->filter(function($p) {
                        $title = strtolower($p->data_values->title);
                        return str_contains($title, 'terms') || str_contains($title, 'privacy');
                    });
                @endphp
                @foreach($footerPolicies as $policy)
                    <a href="{{ route('policy.pages', [$policy->id, slug($policy->data_values->title)]) }}" class="hover:text-red-600 dark:hover:text-white transition-all">
                        {{ __($policy->data_values->title) }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</footer>
