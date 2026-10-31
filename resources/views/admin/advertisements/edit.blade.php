@extends('admin.layouts.app')
@section('panel')
<div class="max-w-6xl mx-auto pb-24">
    <form action="{{ route('admin.advertisement.update', $advertisement->id) }}" method="post" enctype="multipart/form-data" class="space-y-8">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-7 space-y-8">
                <div class="bg-white/60 dark:bg-black/20 backdrop-blur-3xl border border-slate-200 dark:border-white/10 rounded-[2.5rem] p-8 lg:p-10 shadow-2xl">
                    <div class="space-y-6">
                        <x-input 
                            name="title" 
                            label="Campaign Title" 
                            required 
                            value="{{ $advertisement->title }}"
                            placeholder="Enter advertisement title..."
                            hint="The internal name for this ad campaign."
                        />

                        <x-select 
                            name="category_id[]" 
                            label="Target Categories" 
                            required 
                            multiple
                            class="h-40"
                            hint="Hold CTRL to select multiple categories."
                        >
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @if ($advertisement->categories->pluck('id')->contains($category->id)) selected @endif>
                                    {{ __($category->name) }}
                                </option>
                            @endforeach
                        </x-select>

                        <x-select 
                            name="ad_type" 
                            label="Campaign Goal" 
                            required
                            hint="Define the primary billing metric for this campaign."
                        >
                            <option value="1" @if ($advertisement->ad_type == Status::IMPRESSION) selected @endif>Per Impression ({{ gs('cur_sym') }}{{ showAmount(gs('per_impression_spent'), currencyFormat: false) }})</option>
                            <option value="2" @if ($advertisement->ad_type == Status::CLICK) selected @endif>Per Click ({{ gs('cur_sym') }}{{ showAmount(gs('per_click_spent'), currencyFormat: false) }})</option>
                            <option value="3" @if ($advertisement->ad_type == Status::BOTH) selected @endif>Both Metrics</option>
                        </x-select>

                        <div class="grid grid-cols-2 gap-4">
                            <x-input 
                                type="number"
                                name="impression" 
                                label="Impression Target" 
                                value="{{ $advertisement->impression }}"
                                placeholder="0"
                                :readonly="$advertisement->ad_type == Status::CLICK"
                                hint="Target impressions count."
                            />
                            <x-input 
                                type="number"
                                name="click" 
                                label="Click Target" 
                                value="{{ $advertisement->click }}"
                                placeholder="0"
                                :readonly="$advertisement->ad_type == Status::IMPRESSION"
                                hint="Target clicks count."
                            />
                        </div>

                        <x-input 
                            type="number"
                            step="any"
                            name="total_amount" 
                            label="Paid Amount ({{ gs('cur_text') }})" 
                            value="{{ getAmount($advertisement->total_amount) }}"
                            placeholder="0.00"
                            hint="Total budget allocated for this campaign."
                        />

                        <div id="click-fields" class="@if ($advertisement->ad_type == Status::IMPRESSION) hidden @endif space-y-6 pt-4 border-t border-slate-100 dark:border-white/5">
                            <x-input 
                                name="url" 
                                type="url" 
                                label="Redirect URL" 
                                value="{{ $advertisement->url }}"
                                placeholder="https://example.com"
                                hint="The destination website for user clicks."
                            />
                            <x-input 
                                name="button_label" 
                                label="CTA Button Label" 
                                value="{{ $advertisement->button_label }}"
                                placeholder="e.g. LEARN MORE"
                                hint="Text displayed on the ad's action button."
                            />
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Brand Logo</label>
                                <x-image-uploader name="logo" :imagePath="getImage(getFilePath('adLogo') . '/' . $advertisement->logo)" :size="getFileSize('adLogo')" :required="false" />
                            </div>
                        </div>

                        <x-input 
                            type="file"
                            name="ad_video" 
                            label="Update Media File" 
                            accept="video/*"
                            hint="Optional: Upload a new video file to replace the current one."
                        />

                        <div class="flex items-center justify-between p-6 bg-slate-50 dark:bg-black/40 rounded-2xl border border-slate-100 dark:border-white/5">
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1">Campaign Status</span>
                                <span class="text-xs font-bold text-slate-900 dark:text-white" id="status-label">{{ $advertisement->status == Status::RUNNING ? 'Currently Running' : 'Currently Paused' }}</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="status" class="sr-only peer" @if($advertisement->status == Status::RUNNING) checked @endif>
                                <div class="w-14 h-8 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-white/10 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-orange-500"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 space-y-8">
                <div class="bg-black rounded-[2.5rem] overflow-hidden shadow-2xl border border-white/10 sticky top-10">
                    <div class="p-6 border-b border-white/5 flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase tracking-widest text-white/40 ">Live Asset Preview</span>
                        <span class="px-3 py-1 bg-orange-500/10 text-orange-500 text-[9px] font-black uppercase tracking-widest rounded-lg">Active</span>
                    </div>
                    <div class="aspect-video">
                        <video class="video-player w-full h-full object-cover" controls>
                            <source src="{{ getAd($advertisement->ad_file, $advertisement) }}" type="video/mp4" />
                        </video>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-6 mt-12">
            <button type="submit" class="w-full sm:flex-grow h-16 rounded-[2rem] orange-gradient-primary text-white flex items-center justify-center gap-4 text-[12px] font-black uppercase tracking-widest hover:scale-[1.02] active:scale-95 transition-all shadow-xl group">
                <span class="material-symbols-rounded relative z-10">save</span>
                <span class="relative z-10">Synchronize Campaign Data</span>
            </button>
            <a href="{{ route('admin.advertisement.both') }}" class="w-full sm:w-auto h-16 px-10 rounded-[2rem] border border-slate-200 dark:border-white/10 text-[12px] font-black uppercase tracking-widest text-slate-500 flex items-center justify-center hover:bg-slate-100 dark:hover:bg-white/5 transition-all active:scale-95 shadow-sm ">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

@push('style-lib')
    <link href="{{ asset('assets/global/css/plyr.css') }}" rel="stylesheet">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/plyr.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $(document).ready(function() {
                const singleplayer = new Plyr('.video-player', {
                    autoplay: true,
                    ratio: '16:9',
                });

                const impressionField = $('[name="impression"]');
                const clickField = $('[name="click"]');
                const clickWrapper = $('#click-fields');
                const statusInput = $('input[name="status"]');
                const statusLabel = $('#status-label');

                statusInput.on('change', function() {
                    statusLabel.text(this.checked ? 'Currently Running' : 'Currently Paused');
                });

                $('[name="ad_type"]').on('change', function() {
                    const value = $(this).val();

                    if (value == '1') {
                        // Impression only
                        impressionField.prop('disabled', false).removeClass('opacity-50');
                        clickField.prop('disabled', true).addClass('opacity-50').val('0');
                        clickWrapper.addClass('hidden');
                        $('[name="url"], [name="button_label"]').prop('required', false);
                    } else if (value == '2') {
                        // Click only
                        impressionField.prop('disabled', true).addClass('opacity-50').val('0');
                        clickField.prop('disabled', false).removeClass('opacity-50');
                        clickWrapper.removeClass('hidden');
                        $('[name="url"], [name="button_label"]').prop('required', true);
                    } else if (value == '3') {
                        // Both
                        impressionField.prop('disabled', false).removeClass('opacity-50');
                        clickField.prop('disabled', false).removeClass('opacity-50');
                        clickWrapper.removeClass('hidden');
                        $('[name="url"], [name="button_label"]').prop('required', true);
                    }
                });
            });

        })(jQuery);
    </script>
@endpush

