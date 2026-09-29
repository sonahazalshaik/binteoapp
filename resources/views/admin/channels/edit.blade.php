@extends('admin.layouts.app')

@section('title', 'Refine Frequency')
@section('header_title', 'Channel Management')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-24">
    <div class="flex items-center justify-between px-6 lg:px-0">
        <div>
            <h3 class="text-3xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Refine Frequency</h3>
            <p class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3">Edit channel branding for #{{ $channel->id }}</p>
        </div>
        <a href="{{ route('admin.channels.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-400 hover:text-rose-500 flex items-center justify-center transition-all shadow-sm active:scale-90">
            <span class="material-symbols-rounded text-xl">close</span>
        </a>
    </div>

    <form action="{{ route('admin.channels.update', $channel) }}" method="POST" enctype="multipart/form-data" 
          x-data="{ 
            synching: false, 
            avatarPreview: '{{ $channel->avatar ? getImage(getFilePath('channelAvatar') . '/' . $channel->avatar) : '' }}',
            bannerPreview: '{{ $channel->banner ? getImage(getFilePath('channelBanner') . '/' . $channel->banner) : '' }}',
            country: '{{ $channel->user->country_name }}',
            countryCode: '{{ $channel->user->country_code ?? '91' }}'
          }" 
          @submit="synching = true"
          class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Panel: Identity & Visuals -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Owner Info -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                                <span class="material-symbols-rounded text-lg">person</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Owner Identity</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Username</p>
                                <p class="text-xs font-bold text-slate-700 dark:text-white">@<span>{{ $channel->user->username }}</span></p>
                            </div>
                            <div class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Full Name</p>
                                <p class="text-xs font-bold text-slate-700 dark:text-white">{{ $channel->user->fullname }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Avatar Preview -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm group">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-tight">Channel Avatar</h3>
                            <span class="material-symbols-rounded text-orange-500">account_circle</span>
                        </div>

                        <div class="relative aspect-square rounded-2xl border-2 border-dashed border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-white/[0.02] overflow-hidden flex flex-col items-center justify-center transition-all hover:border-orange-500/50 group/zone">
                            <img x-show="avatarPreview" :src="avatarPreview" class="w-full h-full object-cover transition-all group-hover/zone:scale-105">
                            <div x-show="!avatarPreview" class="flex flex-col items-center justify-center text-slate-300 dark:text-white/10">
                                <span class="material-symbols-rounded text-5xl">account_circle</span>
                                <p class="text-[9px] font-black uppercase tracking-widest mt-2 text-slate-400">No Avatar</p>
                            </div>
                            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 group-hover/zone:opacity-100 transition-all flex flex-col items-center justify-center text-white gap-2">
                                <span class="material-symbols-rounded text-3xl">add_a_photo</span>
                                <p class="text-[10px] font-bold uppercase tracking-widest">Update Avatar</p>
                            </div>
                            <input type="file" name="avatar" accept="image/*"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                   @change="openCropper($event.target, null, {aspectRatio: 1}, (file, url) => { avatarPreview = url; })">
                        </div>
                    </div>
                </div>

                <!-- Banner Preview -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm group">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-tight">Channel Banner</h3>
                            <span class="material-symbols-rounded text-blue-500">image</span>
                        </div>

                        <div class="relative aspect-[6.2/1] rounded-2xl border-2 border-dashed border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-white/[0.02] overflow-hidden flex flex-col items-center justify-center transition-all hover:border-blue-500/50 group/zone">
                            <img x-show="bannerPreview" :src="bannerPreview" class="w-full h-full object-cover transition-all group-hover/zone:scale-105">
                            <div x-show="!bannerPreview" class="flex flex-col items-center justify-center text-slate-300 dark:text-white/10">
                                <span class="material-symbols-rounded text-5xl">image</span>
                                <p class="text-[9px] font-black uppercase tracking-widest mt-2 text-slate-400">No Banner</p>
                            </div>
                            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 group-hover/zone:opacity-100 transition-all flex flex-col items-center justify-center text-white gap-2">
                                <span class="material-symbols-rounded text-3xl">landscape</span>
                                <p class="text-[10px] font-bold uppercase tracking-widest">Update Banner</p>
                            </div>
                            <input type="file" name="banner" accept="image/*"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                   @change="openCropper($event.target, null, {aspectRatio: 6.2}, (file, url) => { bannerPreview = url; })">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Channel Configuration -->
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                                <span class="material-symbols-rounded text-lg">settings_input_antenna</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Broadcast Parameters</h3>
                        </div>

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <x-input name="name" label="Channel Name" value="{{ $channel->name }}" required="true" icon="label" hint="Update the recognizable name for the channel." />
                                
                                <div class="space-y-2">
                                    <x-select name="country" label="Operational Base" required="true" icon="public" hint="The channel's primary location.">
                                        @foreach($countries as $country)
                                            <option value="{{ $country->country }}" data-code="{{ $country->dial_code }}" {{ $channel->user->country_name == $country->country ? 'selected' : '' }}>
                                                {{ $country->country }}
                                            </option>
                                        @endforeach
                                    </x-select>
                                    <input type="hidden" name="country_code" :value="countryCode">
                                </div>
                            </div>
                            
                            <x-textarea name="description" label="Signal Description" placeholder="Define the mission and content focus of this broadcast frequency..." rows="6" icon="description" hint="A compelling description attracts more subscribers.">{{ $channel->description }}</x-textarea>
                        </div>

                        <!-- Social Framework -->
                        <div class="pt-8 space-y-6 border-t border-slate-100 dark:border-white/5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-500">
                                    <span class="material-symbols-rounded text-lg">hub</span>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Social Linkage</h3>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                @php $links = $channel->social_links ?? []; @endphp
                                @foreach(['Facebook', 'Twitter', 'Instagram', 'YouTube'] as $social)
                                    <x-input 
                                        type="url" 
                                        name="social_links[{{ strtolower($social) }}]" 
                                        label="{{ $social }} URL" 
                                        value="{{ @$links[strtolower($social)] }}" 
                                        placeholder="https://{{ strtolower($social) }}.com/..." 
                                        icon="link"
                                    />
                                @endforeach
                            </div>
                        </div>

                        <div class="flex pt-6 border-t border-slate-100 dark:border-white/5">
                            <button type="submit" :disabled="synching" 
                                    class="w-full h-16 rounded-2xl bg-orange-600 text-white text-sm font-bold uppercase tracking-widest hover:bg-orange-700 active:scale-95 transition-all flex items-center justify-center gap-3 disabled:opacity-50 disabled:grayscale disabled:cursor-not-allowed shadow-lg shadow-orange-500/20">
                                <span x-show="!synching" class="material-symbols-rounded text-xl">save_as</span>
                                <span x-show="synching" class="material-symbols-rounded animate-spin">sync</span>
                                <span x-text="synching ? 'Optimizing Signal...' : 'Apply Parameters'">Apply Parameters</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('script')
<script>
    (function($){
        "use strict";
        $('select[name="country"]').on('change', function() {
            const code = $(this).find(':selected').data('code');
            // Sync with Alpine state
            const alpineData = document.querySelector('[x-data]').__x.$data;
            alpineData.countryCode = code;
        });
    })(jQuery);
</script>
@endpush

