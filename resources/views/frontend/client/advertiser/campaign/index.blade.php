@use('App\Constants\Status')
<x-app-layout>
    <div class="py-10 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen" x-data="campaignManager()">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-12 gap-6">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Campaign Inventory</h1>
                    <p class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2">Group your ads and manage budgets</p>
                </div>
                <button @click="openCreateModal()" class="px-8 py-4 bg-red-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-red-500/20 hover:bg-red-700 transition-all active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-rounded text-lg">add_circle</span>
                    New Campaign
                </button>
            </div>

            <!-- Campaign Grid -->
            <div class="bg-white dark:bg-[#1A1A1A] rounded-[3rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="hidden md:block">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50 dark:bg-white/2 border-b border-gray-100 dark:border-white/5">
                            <tr>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Campaign Details</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Ads Count</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Budget Metrics</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Payment</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            @forelse ($campaigns as $campaign)
                            <tr class="hover:bg-gray-50/30 dark:hover:bg-white/2 transition-colors">
                                <td class="px-8 py-6">
                                    <div class="min-w-0">
                                        <p class="font-black text-sm text-gray-900 dark:text-white mb-1">{{ __($campaign->title) }}</p>
                                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">ID: {{ $campaign->id }}</p>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col items-center gap-2">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" @change="toggleStatus({{ $campaign->id }})" class="sr-only peer" {{ $campaign->status == Status::ENABLE ? 'checked' : '' }}>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none dark:bg-white/5 peer-checked:bg-emerald-500 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                        </label>
                                        @php echo $campaign->campaignStatus; @endphp
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="px-4 py-1.5 bg-gray-50 dark:bg-white/5 rounded-xl text-xs font-black text-gray-600 dark:text-gray-400 border border-gray-100 dark:border-white/5">
                                        {{ $campaign->advertisements->count() }} Ads
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-xs font-black text-gray-900 dark:text-white" title="Total Budget">{{ showAmount($campaign->total_amount + $campaign->hold_amount) }}</span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[8px] font-bold text-gray-400 uppercase">Avail: {{ showAmount($campaign->available_amount) }}</span>
                                            <span class="w-1 h-1 bg-gray-300 dark:bg-white/10 rounded-full"></span>
                                            <span class="text-[8px] font-bold text-amber-500 uppercase">Hold: {{ showAmount($campaign->hold_amount) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex justify-center">
                                        @php echo $campaign->campaignPaymentStatus; @endphp
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="openEditModal(@json($campaign))" class="w-9 h-9 rounded-xl bg-gray-50 dark:bg-white/5 text-gray-400 flex items-center justify-center hover:bg-gray-900 dark:hover:bg-white hover:text-white dark:hover:text-black transition-all">
                                            <span class="material-symbols-rounded text-lg">edit</span>
                                        </button>
                                        <a href="{{ route('user.advertiser.ad.create', $campaign->slug) }}" class="w-9 h-9 rounded-xl bg-blue-500/10 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all" title="Manage Ads">
                                            <span class="material-symbols-rounded text-lg">ads_click</span>
                                        </a>
                                        @if ($campaign->payment_status != Status::PAYMENT_SUCCESS)
                                            <a href="{{ route('user.advertiser.campaign.gateways', $campaign->id) }}" class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all shadow-lg shadow-emerald-500/10">
                                                <span class="material-symbols-rounded text-lg">payments</span>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-24 text-center">
                                    <span class="material-symbols-rounded text-7xl text-gray-200 dark:text-white/5 mb-6">inventory_2</span>
                                    <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">No Campaigns Found</h3>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-10">Create a campaign to start grouping your advertisements.</p>
                                    <button @click="openCreateModal()" class="inline-flex items-center gap-3 px-10 py-5 bg-red-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-red-500/20 hover:bg-red-700 transition-all">
                                        <span class="material-symbols-rounded">add_circle</span>
                                        Initialize Campaign
                                    </button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="md:hidden divide-y divide-gray-50 dark:divide-white/5">
                    @forelse($campaigns as $campaign)
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-start">
                            <div class="min-w-0">
                                <h4 class="font-black text-gray-900 dark:text-white text-sm line-clamp-1 truncate">{{ __($campaign->title) }}</h4>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1">Total Budget: {{ showAmount($campaign->total_amount + $campaign->hold_amount) }}</p>
                            </div>
                            @php echo $campaign->campaignPaymentStatus; @endphp
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('user.advertiser.ad.create', $campaign->slug) }}" class="flex-grow py-3 bg-blue-600 text-white rounded-xl font-black text-[9px] uppercase tracking-widest flex items-center justify-center gap-2">
                                <span class="material-symbols-rounded text-base">ads_click</span>
                                Ad Set
                            </a>
                             @if ($campaign->payment_status != Status::PAYMENT_SUCCESS)
                                <a href="{{ route('user.advertiser.campaign.gateways', $campaign->id) }}" class="flex-grow py-3 bg-emerald-600 text-white rounded-xl font-black text-[9px] uppercase tracking-widest flex items-center justify-center gap-2">
                                    <span class="material-symbols-rounded text-base">payments</span>
                                    Pay
                                </a>
                             @endif
                             <button @click="openEditModal(@json($campaign))" class="w-12 h-10 bg-gray-50 dark:bg-white/5 text-gray-400 rounded-xl flex items-center justify-center">
                                <span class="material-symbols-rounded">edit</span>
                             </button>
                        </div>
                    </div>
                    @empty
                    <div class="py-16 text-center">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No campaigns yet</p>
                    </div>
                    @endforelse
                </div>
            </div>

            @if($campaigns->hasPages())
            <div class="mt-12">
                {{ $campaigns->links() }}
            </div>
            @endif
        </div>

        <!-- Create/Edit Modal (Alpine.js) -->
        <div x-show="modalOpen" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div @click="modalOpen = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xl transition-opacity"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative inline-block align-bottom bg-white dark:bg-[#1A1A1A] rounded-[3rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                    <div class="px-10 py-10">
                        <div class="flex items-center justify-between mb-10">
                            <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-widest" x-text="editMode ? 'Edit Campaign' : 'Initialize Campaign'"></h3>
                            <button @click="modalOpen = false" class="text-gray-400 hover:text-red-500 transition-colors">
                                <span class="material-symbols-rounded">close</span>
                            </button>
                        </div>

                        <form :action="formAction" method="POST">
                            @csrf
                            <div class="space-y-8">
                                <div>
                                    <div class="flex justify-between items-center mb-3">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Campaign Title</label>
                                        <button type="button" @click="generateSlug()" class="text-[9px] font-black text-red-600 hover:text-red-700 uppercase tracking-widest flex items-center gap-1">
                                            <span class="material-symbols-rounded text-sm">link</span> Make Slug
                                        </button>
                                    </div>
                                    <input type="text" name="title" x-model="formData.title" required class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none">
                                </div>

                                <div>
                                    <div class="flex justify-between items-center mb-3">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Slug (Unique Name)</label>
                                        <div x-show="slugVerifying" class="text-[8px] font-bold text-blue-500 uppercase flex items-center gap-1">
                                            <span class="material-symbols-rounded text-xs animate-spin font-normal">progress_activity</span> Verifying
                                        </div>
                                        <div x-show="!slugVerifying && formData.slug && slugExists" class="text-[8px] font-bold text-red-500 uppercase">Slug Taken</div>
                                        <div x-show="!slugVerifying && formData.slug && !slugExists" class="text-[8px] font-bold text-emerald-500 uppercase">Available</div>
                                    </div>
                                    <input type="text" name="slug" x-model="formData.slug" @input.debounce.500ms="checkSlug()" required class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none">
                                </div>

                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 block">Total Budget ({{ gs('cur_text') }})</label>
                                    <div class="relative">
                                        <input type="number" name="total_budget" x-model="formData.total_budget" :readonly="editMode && formData.payment_status == 1" required class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 rounded-2xl pl-6 pr-20 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none read-only:bg-gray-100 dark:read-only:bg-white/2">
                                        <span class="absolute right-6 top-1/2 -translate-y-1/2 text-xs font-black text-gray-400 uppercase">{{ gs('cur_text') }}</span>
                                    </div>
                                </div>

                                <div x-show="editMode && formData.payment_status == 1">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 block">Recharge Budget (Add Amount)</label>
                                    <div class="relative">
                                        <input type="number" name="add_budget" class="w-full bg-emerald-500/5 border border-emerald-500/10 rounded-2xl pl-6 pr-20 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                                        <span class="absolute right-6 top-1/2 -translate-y-1/2 text-xs font-black text-emerald-600 uppercase">{{ gs('cur_text') }}</span>
                                    </div>
                                    <p class="text-[9px] font-bold text-emerald-600/60 uppercase mt-3">This amount will be added to your existing campaign balance.</p>
                                </div>

                                <button type="submit" :disabled="slugExists" class="w-full py-5 bg-red-600 text-white rounded-[1.5rem] font-black text-sm uppercase tracking-[0.2em] shadow-xl shadow-red-500/20 hover:bg-red-700 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span x-text="editMode ? 'Update Campaign' : 'Initialize Inventory'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
    <script>
        function campaignManager() {
            return {
                modalOpen: false,
                editMode: false,
                formAction: "{{ route('user.advertiser.campaign.save') }}",
                formData: {
                    title: '',
                    slug: '',
                    total_budget: '',
                    payment_status: 0
                },
                slugVerifying: false,
                slugExists: false,

                openCreateModal() {
                    this.editMode = false;
                    this.formAction = "{{ route('user.advertiser.campaign.save') }}";
                    this.formData = { title: '', slug: '', total_budget: '', payment_status: 0 };
                    this.modalOpen = true;
                },

                openEditModal(campaign) {
                    this.editMode = true;
                    this.formAction = "{{ route('user.advertiser.campaign.save') }}/" + campaign.id;
                    this.formData = {
                        title: campaign.title,
                        slug: campaign.slug,
                        total_budget: parseFloat(campaign.total_amount).toFixed(0),
                        payment_status: campaign.payment_status
                    };
                    this.modalOpen = true;
                },

                generateSlug() {
                    this.formData.slug = this.formData.title.toLowerCase()
                        .replace(/[^\w ]+/g, '')
                        .replace(/ +/g, '-');
                    this.checkSlug();
                },

                checkSlug() {
                    if (!this.formData.slug) return;
                    this.slugVerifying = true;
                    $.get("{{ route('user.advertiser.campaign.check.slug') }}", { slug: this.formData.slug }, (res) => {
                        this.slugExists = res.exists;
                        this.slugVerifying = false;
                    });
                },

                toggleStatus(id) {
                    $.post("{{ route('user.advertiser.campaign.status') }}/" + id, {
                        _token: "{{ csrf_token() }}"
                    }, (res) => {
                        if (res.status == 'success') {
                            location.reload();
                        } else {
                            notify('error', res.message);
                        }
                    });
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
