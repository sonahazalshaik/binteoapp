@use('App\Constants\Status')
<x-app-layout>
    <div class="py-10 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-12 gap-6">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Advanced Campaign Management</h1>
                    <p class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2">Performance-based advertising controls</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('user.advertiser.ad.create') }}" class="px-8 py-4 gradient-orange text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-orange-500/20 hover:opacity-90 transition-all active:scale-95 flex items-center gap-2">
                        <span class="material-symbols-rounded text-lg">add_circle</span>
                        New Ad
                    </a>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                @foreach([
                    ['Total Campaigns', $totalCampaign ?? 0, 'inventory_2', 'text-blue-500', 'bg-blue-500/10'],
                    ['Active Ad Sets', $totalAds ?? 0, 'ads_click', 'text-emerald-500', 'bg-emerald-500/10'],
                    ['Daily Burn Rate', gs('cur_sym').showAmount($totalDailyBudget ?? 0, currencyFormat: false), 'trending_up', 'text-amber-500', 'bg-amber-500/10'],
                    ['Total Lifetime Cost', gs('cur_sym').showAmount($totalCosts ?? 0, currencyFormat: false), 'payments', 'text-rose-500', 'bg-rose-500/10']
                ] as $stat)
                <div class="bg-white dark:bg-[#1A1A1A] p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-sm group">
                    <div class="w-12 h-12 rounded-2xl {{ $stat[4] }} flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-rounded {{ $stat[3] }} text-2xl font-normal">{{ $stat[2] }}</span>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight mb-1">{{ $stat[1] }}</h3>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $stat[0] }}</p>
                </div>
                @endforeach
            </div>

            <div class="bg-white dark:bg-[#1A1A1A] rounded-[3rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="hidden md:block">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50 dark:bg-white/2 border-b border-gray-100 dark:border-white/5">
                            <tr>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Ad Title</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Campaign</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Reach / Engagement</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Daily / Total Cost</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Geotargeting</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            @forelse ($advertisements as $ad)
                            <tr class="hover:bg-gray-50/30 dark:hover:bg-white/2 transition-colors">
                                <td class="px-8 py-6">
                                    <div>
                                        <p class="font-black text-sm text-gray-900 dark:text-white mb-1">{{ __($ad->title) }}</p>
                                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ showDateTime($ad->created_at, 'd M Y') }}</p>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-xs font-bold text-gray-600 dark:text-gray-400">{{ __($ad->campaign?->title ?? 'N/A') }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col items-center gap-1">
                                        <div class="flex items-center gap-2" title="Theoretical Reach">
                                            <span class="text-xs font-black text-emerald-500">{{ formatNumber($ad->ad_reached) }}</span>
                                            <span class="text-[9px] font-bold text-gray-400 uppercase">Est. Reach</span>
                                        </div>
                                        <div class="flex items-center gap-2" title="Actual Performance">
                                            <span class="text-[10px] font-black text-gray-500">{{ formatNumber($ad->advertisementAnalytics()->count()) }}</span>
                                            <span class="text-[8px] font-bold text-gray-400 uppercase">Actual Eng.</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-xs font-black text-gray-900 dark:text-white">{{ showAmount($ad->daily_costs) }} / day</span>
                                        <span class="text-[9px] font-bold text-gray-400 uppercase">Limit: {{ showAmount($ad->total_amount) }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex justify-center">
                                        <button @click="openCountryModal(@json($ad->countries))" class="px-4 py-2 bg-gray-50 dark:bg-white/5 rounded-xl text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest border border-gray-100 dark:border-white/5 hover:border-orange-500 transition-colors">
                                            Show Regions
                                        </button>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('user.advertiser.ad.analytics', $ad->id) }}" class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all shadow-lg shadow-emerald-500/5" title="View Detailed Analytics">
                                            <span class="material-symbols-rounded text-lg">monitoring</span>
                                        </a>
                                        @if ($ad->status == Status::RUNNING)
                                            <button class="confirmationBtn w-9 h-9 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center hover:bg-amber-600 hover:text-white transition-all shadow-sm"
                                                data-action="{{ route('user.advertiser.status', $ad->id) }}"
                                                data-question="Are you sure want to pause this advertisement?">
                                                <span class="material-symbols-rounded text-lg">pause</span>
                                            </button>
                                        @elseif($ad->status == Status::PAUSE)
                                            <button class="confirmationBtn w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all shadow-sm"
                                                data-action="{{ route('user.advertiser.status', $ad->id) }}"
                                                data-question="Are you sure want to resume this advertisement?">
                                                <span class="material-symbols-rounded text-lg">play_arrow</span>
                                            </button>
                                        @endif
                                        @if ($ad->status == Status::ADVERTISEMENT_REJECTED)
                                            <button @click="openRejectModal('{{ addslashes($ad->reject_reason) }}')" class="w-9 h-9 rounded-xl bg-orange-500/10 text-orange-600 flex items-center justify-center hover:bg-orange-600 hover:text-white transition-all" title="View Rejection Reason">
                                                <span class="material-symbols-rounded text-lg">info</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-24 text-center">
                                     <span class="material-symbols-rounded text-6xl text-gray-200 dark:text-white/5 mb-6">dynamic_feed</span>
                                    <p class="text-sm font-black text-gray-400 uppercase tracking-widest">No advanced ad sets found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="md:hidden divide-y divide-gray-50 dark:divide-white/5">
                    @forelse($advertisements as $ad)
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-start">
                            <div class="min-w-0">
                                <h4 class="font-black text-gray-900 dark:text-white text-sm line-clamp-1 truncate">{{ __($ad->title) }}</h4>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1">{{ __($ad->campaign?->title ?? 'N/A') }}</p>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="text-xs font-black text-gray-900 dark:text-white">{{ showAmount($ad->daily_costs) }}/d</span>
                                <span class="text-[8px] font-bold text-gray-400 uppercase">Budget: {{ showAmount($ad->total_amount) }}</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('user.advertiser.ad.analytics', $ad->id) }}" class="py-3 bg-gray-50 dark:bg-white/5 text-gray-600 dark:text-gray-400 rounded-xl font-black text-[9px] uppercase tracking-widest flex items-center justify-center gap-2 border border-gray-100 dark:border-white/5">
                                <span class="material-symbols-rounded text-base">monitoring</span> Analytics
                            </a>
                            @if ($ad->status == Status::RUNNING)
                                <button class="confirmationBtn py-3 bg-amber-500 text-white rounded-xl font-black text-[9px] uppercase tracking-widest flex items-center justify-center gap-2"
                                    data-action="{{ route('user.advertiser.status', $ad->id) }}"
                                    data-question="Pause this advertisement?">
                                    <span class="material-symbols-rounded text-base">pause</span> Pause
                                </button>
                            @else
                                <button class="confirmationBtn py-3 bg-emerald-500 text-white rounded-xl font-black text-[9px] uppercase tracking-widest flex items-center justify-center gap-2"
                                    data-action="{{ route('user.advertiser.status', $ad->id) }}"
                                    data-question="Resume this advertisement?">
                                    <span class="material-symbols-rounded text-base">play_arrow</span> Resume
                                </button>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="py-16 text-center">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No advanced ads yet</p>
                    </div>
                    @endforelse
                </div>
            </div>

            @if($advertisements->hasPages())
            <div class="mt-12">
                {{ $advertisements->links() }}
            </div>
            @endif
        </div>

        <!-- Geo / Rejection Modals -->
        <div x-data="{ open: false, title: '', items: [], type: 'geo' }" 
             @open-geo-modal.window="open = true; type='geo'; title='Target Regions'; items = $event.detail"
             @open-reject-modal.window="open = true; type='reject'; title='Rejection Reason'; reason = $event.detail"
             x-show="open" x-cloak class="fixed inset-0 z-[150] flex items-center justify-center p-6 sm:p-0">
            <div @click="open = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xl transition-opacity"></div>
            <div class="relative bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] w-full max-w-md p-10 overflow-hidden shadow-2xl">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-widest" x-text="title"></h3>
                    <button @click="open = false" class="text-gray-400 hover:text-orange-500 transition-colors">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>
                
                <div x-show="type === 'geo'" class="flex flex-wrap gap-2">
                    <template x-for="item in items">
                        <span class="px-4 py-2 bg-gray-50 dark:bg-white/5 rounded-xl text-[10px] font-black text-gray-600 dark:text-gray-400 border border-gray-100 dark:border-white/5 uppercase tracking-widest" x-text="item.country"></span>
                    </template>
                </div>

                <div x-show="type === 'reject'" class="p-6 bg-orange-500/5 rounded-2xl border border-orange-500/10">
                    <p class="text-sm font-bold text-orange-500/80 leading-relaxed" x-text="reason"></p>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal frontend="true" />

    @push('script')
    <script>
        function openCountryModal(countries) {
            window.dispatchEvent(new CustomEvent('open-geo-modal', { detail: countries }));
        }
        function openRejectModal(reason) {
            window.dispatchEvent(new CustomEvent('open-reject-modal', { detail: reason }));
        }
    </script>
    @endpush
</x-app-layout>
