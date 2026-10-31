@extends('admin.layouts.app')
@section('panel')
    <div class="max-w-[1600px] mx-auto space-y-10 pb-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="space-y-8">
                <!-- Basic Information Card -->
                <div class="bg-white/60 dark:bg-black/20 backdrop-blur-3xl border border-slate-200 dark:border-white/10 rounded-[2.5rem] p-8 lg:p-12 shadow-2xl transition-all duration-500">
                    <div class="flex items-center justify-between mb-10 pb-6 border-b border-slate-100 dark:border-white/5">
                        <h5 class="text-[14px] font-black text-slate-900 dark:text-white uppercase tracking-tighter">@lang('Basic Information')</h5>
                        <span class="px-4 py-1.5 rounded-xl bg-orange-500/10 text-orange-500 text-[10px] font-black uppercase tracking-widest">ID: #{{ $advertisement->id }}</span>
                    </div>

                    <div class="space-y-8">
                        <x-input 
                            name="campaign_title" 
                            label="Campaign Title" 
                            value="{{ $advertisement->campaign?->title }}" 
                            readonly 
                            hint="The associated marketing campaign for this ad."
                        />
                        
                        <x-input 
                            name="title" 
                            label="Advertisement Title" 
                            value="{{ $advertisement->title }}" 
                            readonly 
                            hint="The specific name given to this advertisement."
                        />

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <x-input 
                                name="daily_costs" 
                                label="Daily Budget ({{ gs('cur_text') }})" 
                                value="{{ getAmount($advertisement->daily_costs) }}" 
                                readonly 
                                hint="Daily maximum spend limit."
                            />
                            <x-input 
                                name="total_budget" 
                                label="Total Budget ({{ gs('cur_text') }})" 
                                value="{{ getAmount($advertisement->total_amount) }}" 
                                readonly 
                                hint="Overall allocated budget for the campaign."
                            />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <x-input 
                                name="ad_reached" 
                                label="Daily Reach Target" 
                                value="{{ formatNumber($advertisement->ad_reached) }}" 
                                readonly 
                                hint="Target impressions per day."
                            />
                            <x-input 
                                name="ad_engagement" 
                                label="Daily Engagement Target" 
                                value="{{ formatNumber($advertisement->ad_engagement) }}" 
                                readonly 
                                hint="Target interactions per day."
                            />
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Target Countries</label>
                            <div class="flex flex-wrap gap-2 p-4 bg-slate-50 dark:bg-black/40 rounded-2xl border border-slate-100 dark:border-white/5">
                                @forelse ($advertisement->countries as $country)
                                    <span class="px-3 py-1.5 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-[11px] font-bold text-slate-600 dark:text-white/60">
                                        {{ $country->country }}
                                    </span>
                                @empty
                                    <span class="text-[11px] font-bold text-slate-400 ">Global Targeting</span>
                                @endforelse
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <x-input 
                                name="start_date" 
                                label="Campaign Launch" 
                                value="{{ $advertisement->start_date ? showDateTime($advertisement->start_date, 'Y-m-d') : 'N/A' }}" 
                                readonly 
                            />
                            <x-input 
                                name="end_date" 
                                label="Campaign Expiry" 
                                value="{{ $advertisement->end_date ? showDateTime($advertisement->end_date, 'Y-m-d') : 'N/A' }}" 
                                readonly 
                            />
                        </div>
                    </div>
                </div>

                @if($advertisement->status == Status::ADVERTISEMENT_REJECTED)
                    <div class="bg-red-500/5 border border-red-500/10 rounded-[2.5rem] p-8 lg:p-10 shadow-xl">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="material-symbols-rounded text-red-500">error</span>
                            <h5 class="text-[12px] font-black text-red-500 uppercase tracking-widest">Rejection Protocol History</h5>
                        </div>
                        <x-textarea 
                            name="reject_reason" 
                            label="Rejection Context" 
                            readonly 
                            rows="4" 
                            value="{{ $advertisement->reject_reason }}"
                        />
                    </div>
                @endif
            </div>

            <div class="space-y-8">
                <!-- Media Assets Card -->
                <div class="bg-black rounded-[3rem] overflow-hidden shadow-2xl border border-white/10 group/preview relative">
                    <div class="absolute inset-0 bg-gradient-to-b from-black/60 to-transparent z-10 pointer-events-none opacity-0 group-hover/preview:opacity-100 transition-opacity p-8">
                        <h4 class="text-[10px] font-black text-white uppercase tracking-[0.2em] ">Media Asset Control</h4>
                    </div>
                    <video class="video-player w-full aspect-video object-cover" controls>
                        @if ($advertisement->video)
                            <source src="{{ getVideo($advertisement->video->videoFiles()->first()?->file_name, $advertisement->video) }}" type="video/mp4" />
                        @else
                            <source src="{{ getAd($advertisement->ad_file, $advertisement) }}" type="video/mp4" />
                        @endif
                    </video>
                </div>

                <!-- Ad CTA Details -->
                <div class="bg-white/60 dark:bg-black/20 backdrop-blur-3xl border border-slate-200 dark:border-white/10 rounded-[2.5rem] p-8 lg:p-12 shadow-2xl">
                    <div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-100 dark:border-white/5">
                        <span class="w-10 h-10 rounded-xl bg-orange-500 text-white flex items-center justify-center shadow-lg shadow-orange-500/20">
                            <span class="material-symbols-rounded">touch_app</span>
                        </span>
                        <h5 class="text-[14px] font-black text-slate-900 dark:text-white uppercase tracking-tighter">@lang('Interactive Elements')</h5>
                    </div>
                    
                    <div class="space-y-8">
                        <div class="form-group">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1 mb-3 block">Campaign Branding Logo</label>
                            <x-image-uploader name="logo" :imagePath="getImage(getFilePath('adLogo') . '/' . $advertisement->logo)" :size="getFileSize('adLogo')" :required="false" readonly />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <x-input 
                                name="button_label" 
                                label="CTA Button Label" 
                                value="{{ $advertisement->button_label }}" 
                                readonly 
                                hint="The text displayed on the interactive overlay."
                            />
                            <x-input 
                                name="redirect_url" 
                                label="Destination Link" 
                                value="{{ $advertisement->url }}" 
                                readonly 
                                hint="Directs users to this specific URL."
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-white dark:bg-[#121212] rounded-[3rem] border-none shadow-2xl overflow-hidden">
                <div class="p-8 sm:p-12">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-red-500/10 text-red-500 flex items-center justify-center">
                                <span class="material-symbols-rounded">block</span>
                            </div>
                            <h5 class="text-[16px] font-black text-slate-900 dark:text-white uppercase tracking-tighter">@lang('Reject Campaign')</h5>
                        </div>
                        <button type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors" data-bs-dismiss="modal">
                            <span class="material-symbols-rounded">close</span>
                        </button>
                    </div>

                    <form action="{{ route('admin.advance.ads.reject', $advertisement->id) }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 leading-relaxed">
                                You are about to reject this advertisement campaign. Please provide a detailed reason for the advertiser to review.
                            </p>

                            <x-textarea 
                                name="message" 
                                label="Reason for Rejection" 
                                rows="5" 
                                required 
                                placeholder="Explain why this ad was rejected..."
                                hint="The advertiser will receive this feedback in their dashboard."
                            />
                        </div>
                        <div class="mt-10 flex gap-4">
                            <button type="submit" class="flex-grow h-16 rounded-2xl bg-red-500 text-white font-black uppercase tracking-widest text-[12px] shadow-xl shadow-red-500/20 active:scale-95 transition-all">@lang('Confirm Rejection')</button>
                            <button type="button" class="px-8 h-16 rounded-2xl bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-white/40 font-black uppercase tracking-widest text-[12px]" data-bs-dismiss="modal">@lang('Abort')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <div class="flex gap-4">
        @if ($advertisement->status == Status::ADVERTISEMENT_PENDING)
            <button type="button" class="h-12 px-8 rounded-xl border border-red-500/20 text-red-500 font-black uppercase tracking-widest text-[10px] hover:bg-red-500 hover:text-white transition-all shadow-sm active:scale-95" data-bs-toggle="modal" data-bs-target="#rejectModal">
                <span class="flex items-center gap-2">
                    <span class="material-symbols-rounded text-lg">close</span>
                    @lang('Reject')
                </span>
            </button>
            <button type="button" data-action="{{ route('admin.advance.ads.approved', $advertisement->id) }}"
                data-question="@lang('Are you sure want to approve this advertisement ?')" class="h-12 px-8 rounded-xl bg-emerald-500 text-white font-black uppercase tracking-widest text-[10px] shadow-lg shadow-emerald-500/20 active:scale-95 transition-all confirmationBtn">
                <span class="flex items-center gap-2">
                    <span class="material-symbols-rounded text-lg">check</span>
                    @lang('Approved')
                </span>
            </button>
        @endif
    </div>
@endpush

@push('style-lib')
    <link href="{{ asset('assets/global/css/plyr.css') }}" rel="stylesheet">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/plyr.js') }}"></script>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            const singleplayer = new Plyr('.video-player', {
                autoplay: true,
                ratio: '16:9',
            });
        });
    </script>
@endpush

