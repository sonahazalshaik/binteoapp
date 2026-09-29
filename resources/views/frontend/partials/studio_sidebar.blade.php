<div class="flex-1 py-0">
    <div class="flex-1 py-8">
        <nav class="px-6 space-y-1.5">
            @php
                $isCreator = auth()->check() && auth()->user()->isCreator();
                $studioItems = [
                    ['label' => 'Dashboard', 'icon' => 'grid_view', 'route' => 'studio.dashboard', 'creator_only' => false],
                    ['label' => 'Analytics', 'icon' => 'analytics', 'route' => 'studio.analytics', 'creator_only' => true],
                    ['label' => 'Content Manager', 'icon' => 'video_library', 'route' => 'studio.videos', 'creator_only' => true],
                    ['label' => 'Reels', 'icon' => 'slow_motion_video', 'route' => 'studio.reels', 'creator_only' => true],
                    ['label' => 'Duets & Remixes', 'icon' => 'groups', 'route' => 'studio.duets', 'creator_only' => true],
                    ['label' => 'Saved Audios', 'icon' => 'library_music', 'route' => 'studio.saved-audios', 'creator_only' => false],
                    ['label' => 'Monetization', 'icon' => 'monetization_on', 'route' => 'studio.monetization', 'creator_only' => true],
                    // ['label' => 'Campaigns Hub', 'icon' => 'campaign', 'route' => 'user.advertiser.dashboard', 'creator_only' => true],
                    ['label' => 'Creator Plans', 'icon' => 'card_membership', 'route' => 'user.plans.index', 'creator_only' => false],
                    ['label' => 'Purchased Plans', 'icon' => 'inventory_2', 'route' => 'user.plans.purchased', 'creator_only' => false],
                    ['label' => 'OTT Plans', 'icon' => 'movie', 'route' => 'user.ott-plans.index', 'creator_only' => false],
                    ['label' => 'Earnings', 'icon' => 'payments', 'route' => 'user.earnings', 'creator_only' => true],
                    ['label' => 'Withdrawal', 'icon' => 'account_balance', 'route' => 'user.withdraw.methods', 'creator_only' => true],
                    ['label' => 'KYC Verification', 'icon' => 'verified_user', 'route' => 'user.kyc.form', 'creator_only' => true],
                    ['label' => 'Support Desk', 'icon' => 'confirmation_number', 'route' => 'user.ticket.index', 'creator_only' => false],
                    ['label' => 'Notifications', 'icon' => 'notifications', 'route' => 'user.notifications', 'creator_only' => false],
                    ['label' => 'Transactions', 'icon' => 'receipt_long', 'route' => 'user.transactions', 'creator_only' => false],
                ];
            @endphp

            @foreach($studioItems as $item)
                @if(!$item['creator_only'] || $isCreator)
                    @include('frontend.partials.studio_sidebar_item', ['item' => $item])
                @endif
            @endforeach

            @if(auth()->check() && auth()->user()->purchasedVideos()->exists())
                <a href="{{ route('studio.purchased-videos') }}" 
                   class="flex items-center space-x-4 px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('studio.purchased-videos') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30 active-menu-item' : 'text-gray-700 dark:text-[#F1F1F1] hover:bg-gray-50 dark:hover:bg-white/5' }}">
                    <span class="material-symbols-rounded text-[22px] {{ request()->routeIs('studio.purchased-videos') ? 'text-white' : 'text-gray-400 group-hover:text-red-500' }}">shopping_bag</span>
                    <span class="font-bold text-[14px]">Purchased Videos</span>
                </a>
            @endif
        </nav>
    </div>

    </div>
</div>


