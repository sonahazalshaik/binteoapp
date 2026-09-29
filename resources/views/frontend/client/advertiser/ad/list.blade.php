@use('App\Constants\Status')
<x-app-layout>
    <div class="py-8 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6">
            <!-- Header -->
            <div class="flex flex-col lg:flex-row items-center justify-between mb-12 gap-8">
                <div>
                    <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter">Advertising Inventory</h1>
                    <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                         <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                         Managing live distributions and analytics
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('user.advertiser.ad.create') }}" class="px-10 py-5 gradient-orange text-white rounded-[2rem] font-black text-xs uppercase tracking-[0.3em] shadow-2xl shadow-orange-500/20 hover:-translate-y-1 transition-all active:scale-95 flex items-center gap-3">
                        <span class="material-symbols-rounded text-xl text-white">add_circle</span>
                        Create Distribution
                    </a>
                </div>
            </div>

            <!-- Stats Analysis Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                @php
                    $adStats = [
                        ['Cumulative Clicks', formatNumber($totalClick ?? 0), 'mouse', 'text-blue-500', 'bg-blue-500/10'],
                        ['Total Impressions', formatNumber($totalImpression ?? 0), 'visibility', 'text-emerald-500', 'bg-emerald-500/10'],
                        ['Available Clicks', formatNumber($availableClick ?? 0), 'touch_app', 'text-amber-500', 'bg-amber-500/10'],
                        ['Ready Impressions', formatNumber($availableImpression ?? 0), 'ads_click', 'text-rose-500', 'bg-rose-500/10']
                    ];
                @endphp
                @foreach($adStats as $stat)
                <div class="bg-white dark:bg-[#181818] p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-sm transition-all duration-300 hover:shadow-xl group">
                    <div class="w-12 h-12 rounded-2xl {{ $stat[4] }} flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-rounded {{ $stat[3] }} text-2xl font-black">{{ $stat[2] }}</span>
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter mb-1">{{ $stat[1] }}</h3>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $stat[0] }}</p>
                </div>
                @endforeach
            </div>

            <!-- Main Table Card -->
            <div class="bg-white dark:bg-[#181818] rounded-[3.5rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden transition-colors duration-500">
                <div class="px-10 py-8 border-b border-gray-50 dark:border-white/5">
                    <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-[0.2em]">Live Campaigns</h2>
                </div>
                
                <!-- Desktop Table -->
                <div class="hidden xl:block">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/20 dark:bg-white/[0.01]">
                            <tr>
                                <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Protocol & Reference</th>
                                <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Distribution</th>
                                <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Remaining Quota</th>
                                <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Served Activity</th>
                                <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Protocol Status</th>
                                <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Operations</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            @forelse ($advertisements as $ad)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-all group">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-6">
                                        <div class="w-12 h-12 rounded-[1.5rem] bg-gray-50 dark:bg-white/5 flex items-center justify-center border border-gray-100 dark:border-white/5 group-hover:scale-110 transition-transform">
                                            <span class="material-symbols-rounded text-gray-400 group-hover:text-orange-500 transition-colors">featured_video</span>
                                        </div>
                                        <div>
                                            <p class="font-black text-sm text-gray-900 dark:text-white mb-1 group-hover:text-orange-500 transition-colors">{{ __($ad->title) }}</p>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">#{{ $ad->trx }}</span>
                                                <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-white/10"></span>
                                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ showDateTime($ad->created_at, 'M d, Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-100 dark:border-white/10">
                                         <span class="w-1.5 h-1.5 rounded-full gradient-orange"></span>
                                         <span class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">@php echo $ad->adTypeBadge; @endphp</span>
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="flex items-center gap-2 text-emerald-500">
                                            <span class="material-symbols-rounded text-[14px]">visibility</span>
                                            <span class="text-xs font-black">{{ formatNumber($ad->available_impression) }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-blue-500">
                                            <span class="material-symbols-rounded text-[14px]">mouse</span>
                                            <span class="text-xs font-black">{{ formatNumber($ad->available_click ?? 0) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex flex-col items-center gap-2 opacity-50 group-hover:opacity-100 transition-all">
                                        <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                                            <span class="material-symbols-rounded text-[14px]">visibility</span>
                                            <span class="text-[10px] font-black">{{ formatNumber($ad->advertisementAnalytics()->impression()->count()) }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                                            <span class="material-symbols-rounded text-[14px]">mouse</span>
                                            <span class="text-[10px] font-black">{{ formatNumber($ad->advertisementAnalytics()->click()->count()) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex flex-col items-center gap-2">
                                        @php echo $ad->statusBadge; @endphp
                                        @php echo $ad->paymentStatusBadge; @endphp
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex items-center justify-end gap-3">
                                        @if ($ad->status == Status::RUNNING)
                                            <button class="confirmationBtn w-12 h-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center hover:scale-110 shadow-xl shadow-amber-500/30 transition-all active:scale-95"
                                                @disabled($ad->payment_status != Status::PAYMENT_SUCCESS)
                                                data-action="{{ route('user.advertiser.status', $ad->id) }}"
                                                data-question="Initiate pause protocol for this campaign?">
                                                <span class="material-symbols-rounded text-2xl">pause</span>
                                            </button>
                                        @elseif($ad->status == Status::PAUSE)
                                            <button class="confirmationBtn w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center hover:scale-110 shadow-xl shadow-emerald-500/30 transition-all active:scale-95"
                                                @disabled($ad->payment_status != Status::PAYMENT_SUCCESS)
                                                data-action="{{ route('user.advertiser.status', $ad->id) }}"
                                                data-question="Resume campaign distribution?">
                                                <span class="material-symbols-rounded text-2xl">play_arrow</span>
                                            </button>
                                        @elseif($ad->payment_status == Status::PAYMENT_INITIATE)
                                            <a href="{{ route('user.deposit.index', $ad->id) }}" class="w-12 h-12 rounded-2xl bg-red-600 text-white flex items-center justify-center hover:scale-110 shadow-xl shadow-red-500/30 transition-all active:scale-95">
                                                <span class="material-symbols-rounded text-2xl">payments</span>
                                            </a>
                                        @endif
                                        <button @click="openCategoryModal(@json($ad->categories))" class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-white/5 text-gray-400 flex items-center justify-center border border-gray-100 dark:border-white/10 hover:bg-gray-900 dark:hover:bg-white hover:text-white dark:hover:text-black transition-all">
                                            <span class="material-symbols-rounded text-2xl">label</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-32 text-center">
                                    <div class="w-32 h-32 rounded-[3.5rem] bg-gray-50 dark:bg-white/2 flex items-center justify-center mx-auto mb-10">
                                         <span class="material-symbols-rounded text-7xl text-gray-100 dark:text-white/5">ad_units</span>
                                    </div>
                                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2 tracking-tight">Portfolio Empty</h3>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.3em] mb-12">Launch your first targeted distribution to see performance analytics.</p>
                                    <a href="{{ route('user.advertiser.ad.create') }}" class="inline-flex items-center gap-3 px-12 py-5 gradient-orange text-white rounded-[2.5rem] font-black text-xs uppercase tracking-widest shadow-2xl shadow-orange-500/30 transition-all hover:-translate-y-1">
                                        <span class="material-symbols-rounded">rocket_launch</span>
                                        Initialize Campaign
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile & Tablet View -->
                <div class="xl:hidden divide-y divide-gray-50 dark:divide-white/5">
                    @forelse($advertisements as $ad)
                    <div class="p-8 space-y-6">
                        <div class="flex justify-between items-start">
                            <div class="min-w-0 flex-grow">
                                <h4 class="font-black text-gray-900 dark:text-white text-base line-clamp-2 leading-tight group-hover:text-red-500 transition-colors">{{ __($ad->title) }}</h4>
                                <div class="mt-3 flex flex-wrap items-center gap-3">
                                     <div class="inline-flex items-center gap-2 px-3 py-1 bg-gray-50 dark:bg-white/5 rounded-lg border border-gray-100 dark:border-white/5">
                                         @php echo $ad->statusBadge; @endphp
                                     </div>
                                     <div class="inline-flex items-center gap-2 px-3 py-1 bg-gray-50 dark:bg-white/5 rounded-lg border border-gray-100 dark:border-white/5">
                                         @php echo $ad->adTypeBadge; @endphp
                                     </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-white/2 p-5 rounded-3xl border border-gray-100 dark:border-white/5 text-center">
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Impressions</p>
                                <div class="flex flex-col">
                                    <span class="text-lg font-black text-emerald-500">{{ formatNumber($ad->available_impression) }}</span>
                                    <span class="text-[8px] font-bold text-gray-400 uppercase opacity-40">Available</span>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-white/2 p-5 rounded-3xl border border-gray-100 dark:border-white/5 text-center">
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Clicks</p>
                                <div class="flex flex-col">
                                    <span class="text-lg font-black text-blue-500">{{ formatNumber($ad->available_click ?? 0) }}</span>
                                    <span class="text-[8px] font-bold text-gray-400 uppercase opacity-40">Available</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            @if ($ad->status == Status::RUNNING)
                                <button class="confirmationBtn flex-grow py-5 bg-amber-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] flex items-center justify-center gap-3 shadow-xl transition-all"
                                    data-action="{{ route('user.advertiser.status', $ad->id) }}"
                                    data-question="Pause campaign protocol?">
                                    <span class="material-symbols-rounded text-xl">pause</span> Pause
                                </button>
                            @elseif($ad->status == Status::PAUSE)
                                <button class="confirmationBtn flex-grow py-5 bg-emerald-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] flex items-center justify-center gap-3 shadow-xl transition-all"
                                    data-action="{{ route('user.advertiser.status', $ad->id) }}"
                                    data-question="Resume distribution?">
                                    <span class="material-symbols-rounded text-xl">play_arrow</span> Resume
                                </button>
                            @endif
                            <button @click="openCategoryModal(@json($ad->categories))" class="w-16 h-14 bg-gray-50 dark:bg-white/5 text-gray-400 rounded-2xl flex items-center justify-center border border-gray-100 dark:border-white/10">
                                <span class="material-symbols-rounded text-2xl">label</span>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="py-24 text-center">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No active distributions found</p>
                    </div>
                    @endforelse
                </div>
            </div>

            @if($advertisements->hasPages())
                <div class="mt-16">
                    {{ $advertisements->links() }}
                </div>
            @endif
        </div>

        <!-- Categories Modal (Premium Glass) -->
         <div x-data="{ open: false, items: [] }" 
              @open-category-modal.window="open = true; items = $event.detail"
              x-show="open" x-cloak class="fixed inset-0 z-[150] flex items-center justify-center p-4 sm:p-6">
            <div @click="open = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-2xl transition-opacity animate-in fade-in duration-500"></div>
            <div class="relative bg-white dark:bg-[#181818] rounded-[3.5rem] w-full max-w-lg p-12 overflow-hidden shadow-[0_40px_100px_-20px_rgba(0,0,0,0.5)] transform animate-in slide-in-from-bottom-10 duration-500 border border-gray-100 dark:border-white/5">
                <div class="flex items-center justify-between mb-10">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tighter uppercase">Distribution Targeting</h3>
                    <button @click="open = false" class="w-12 h-12 rounded-full flex items-center justify-center text-gray-400 hover:bg-gray-50 dark:hover:bg-white/2 hover:text-red-500 transition-all">
                        <span class="material-symbols-rounded tracking-widest">close</span>
                    </button>
                </div>
                <div class="flex flex-wrap gap-3">
                    <template x-for="item in items">
                        <div class="px-6 py-3 bg-gray-50 dark:bg-white/2 rounded-2xl border border-gray-100 dark:border-white/5">
                             <div class="flex items-center gap-3">
                                 <span class="w-1.5 h-1.5 rounded-full bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.5)]"></span>
                                 <span class="text-[11px] font-black text-gray-700 dark:text-gray-300 uppercase tracking-widest" x-text="item.name"></span>
                             </div>
                        </div>
                    </template>
                </div>
            </div>
         </div>
    </div>

    <x-confirmation-modal frontend="true" />

    @push('script')
    <script>
        function openCategoryModal(categories) {
            window.dispatchEvent(new CustomEvent('open-category-modal', { detail: categories }));
        }
    </script>
    @endpush
</x-app-layout>
