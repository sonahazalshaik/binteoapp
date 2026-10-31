@extends('admin.layouts.app')

@section('title', 'Create Advertisement')
@section('header_title', 'Advertisements')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-24">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-[12px] font-black text-slate-900 dark:text-white uppercase tracking-tight">New Campaign</h3>
            <p class="text-[9px] font-bold text-slate-500 dark:text-white/30 uppercase tracking-widest mt-2 leading-relaxed">Set up your advertisement campaign details and budget</p>
        </div>
        <a href="{{ route('admin.advertisement.both') }}" class="w-12 h-12 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-400 hover:text-orange-600 flex items-center justify-center transition-all active:scale-90 shadow-sm">
            <span class="material-symbols-rounded">close</span>
        </a>
    </div>

    <form action="{{ route('admin.advertisement.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        <div class="bg-white/60 dark:bg-black/20 backdrop-blur-3xl border border-slate-200 dark:border-white/10 rounded-[2rem] p-4 lg:p-12 shadow-2xl transition-all duration-500">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Title -->
                <div class="md:col-span-2">
                    <x-input 
                        name="title" 
                        label="Campaign Title" 
                        required 
                        placeholder="Enter advertisement title..."
                        hint="Tip: Use a catchy title to improve your campaign's CTR."
                    />
                </div>

                <!-- Categories -->
                <div class="md:col-span-2">
                    <x-select 
                        name="category_id[]" 
                        label="Target Categories" 
                        required 
                        multiple
                        class="h-48"
                        hint="Hold CTRL (Windows) or CMD (Mac) to select multiple categories for targeting."
                    >
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </x-select>
                </div>

                <!-- Video Upload -->
                <div class="md:col-span-1">
                    <x-input 
                        type="file"
                        name="ad_video" 
                        label="Campaign Video Asset" 
                        required 
                        accept="video/*"
                        hint="High-quality vertical or horizontal video files are supported."
                    />
                </div>

                <!-- Logo Upload -->
                <div class="md:col-span-1">
                    <x-input 
                        type="file"
                        name="logo" 
                        label="Brand Logo (Optional)" 
                        accept="image/*"
                        hint="A clear, transparent PNG logo works best for overlays."
                    />
                </div>

                <!-- Ad Type -->
                <div class="md:col-span-1">
                    <x-select 
                        name="ad_type" 
                        label="Campaign Goal" 
                        required
                        hint="Choose how you want to be billed: by impressions or by engagement."
                    >
                        <option value="1">CPM (Based on Impressions)</option>
                        <option value="2">CPC (Based on Clicks)</option>
                        <option value="3">Combined (Both Metrics)</option>
                    </x-select>
                </div>

                <!-- URL -->
                <div class="md:col-span-1">
                    <x-input 
                        type="url"
                        name="url" 
                        label="Destination Link (URL)" 
                        placeholder="https://example.com"
                        hint="The landing page where viewers will be redirected upon interaction."
                    />
                </div>

                 <!-- Impression Value -->
                 <div class="md:col-span-1">
                    <x-input 
                        type="number"
                        name="impression" 
                        label="Maximum Impressions" 
                        placeholder="0"
                        hint="The total number of times your ad will be shown."
                    />
                </div>

                <!-- Click Value -->
                <div class="md:col-span-1">
                    <x-input 
                        type="number"
                        name="click" 
                        label="Maximum Clicks" 
                        placeholder="0"
                        hint="The total number of interactions you are targeting."
                    />
                </div>

                <!-- Button Label -->
                <div class="md:col-span-1">
                    <x-input 
                        name="button_label" 
                        label="Action Button Label" 
                        placeholder="e.g. SHOP NOW"
                        hint="The text that will appear on the call-to-action button."
                    />
                </div>

                <!-- Total Amount -->
                <div class="md:col-span-1">
                    <x-input 
                        type="number"
                        step="any"
                        name="total_amount" 
                        label="Campaign Budget ($)" 
                        placeholder="0.00"
                        hint="Set your total budget for this specific campaign."
                    />
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-6">
            <button type="submit" class="w-full sm:flex-grow h-16 rounded-[2rem] orange-gradient-primary text-white flex items-center justify-center gap-4 text-[12px] font-black uppercase tracking-widest hover:scale-[1.02] active:scale-95 transition-all shadow-xl group">
                <span class="material-symbols-rounded relative z-10">check_circle</span>
                <span class="relative z-10">Launch Campaign</span>
            </button>
            <a href="{{ route('admin.advertisement.both') }}" class="w-full sm:w-auto h-16 px-10 rounded-[2rem] border border-slate-200 dark:border-white/10 text-[12px] font-black uppercase tracking-widest text-slate-500 flex items-center justify-center hover:bg-slate-100 dark:hover:bg-white/5 transition-all active:scale-95 shadow-sm ">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

