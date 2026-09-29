@extends('admin.layouts.app')

@section('title', 'Create New Channel')
@section('header_title', 'Channel Management')

@section('content')
<form action="{{ route('admin.channels.store') }}" method="POST" enctype="multipart/form-data"
      x-data="{ avatarPreview: null, bannerPreview: null }">
    @csrf
    <div class="max-w-5xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-700 pb-24">
        <div class="bg-white dark:bg-[#121212] rounded-[3rem] p-10 border border-slate-200 dark:border-white/10 shadow-2xl relative overflow-hidden">
            <!-- Decorative Glow -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-orange-500/5 blur-[100px] -mr-48 -mt-48 rounded-full"></div>
            
            <div class="relative z-10">
                <div class="mb-12">
                    <h3 class="text-3xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Channel Registration</h3>
                    <p class="text-[10px] font-black text-slate-500 dark:text-white/30 uppercase tracking-[0.4em] mt-3 leading-relaxed">Set up the primary details and branding for this new channel</p>
                </div>

                <div class="space-y-2">
                    <!-- Section 1: Core Identity -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8">
                        <x-select name="user_id" label="Assign Owner (User)" required hint="Select the primary account responsible for this channel.">
                            <option value="">Select User...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">@<span>{{ $user->username }}</span> ({{ $user->fullname }})</option>
                            @endforeach
                        </x-select>

                        <x-input 
                            name="name" 
                            label="Channel Name" 
                            placeholder="Ex: Crystal HD Network" 
                            required 
                            hint="Enter a unique and recognizable name for the channel."
                        />
                    </div>

                    <!-- Section 2: Visual Branding -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                        <div class="space-y-4">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/30 px-1 mb-2">Channel Avatar</label>
                            <div class="relative group h-40">
                                <input type="file" name="avatar" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                       @change="openCropper($event.target, null, {aspectRatio: 1}, (file, url) => { avatarPreview = url; })">
                                <div class="w-full h-full bg-slate-50 dark:bg-black/40 border-4 border-dashed border-slate-200 dark:border-white/10 rounded-[2.5rem] flex items-center px-8 gap-6 group-hover:border-orange-500/50 transition-all overflow-hidden">
                                    <template x-if="avatarPreview">
                                        <img :src="avatarPreview" class="w-full h-full object-cover absolute inset-0">
                                    </template>
                                    <template x-if="!avatarPreview">
                                        <div class="flex items-center gap-6">
                                            <div class="w-16 h-16 rounded-2xl bg-orange-500/10 flex items-center justify-center text-orange-500 shadow-lg">
                                                <span class="material-symbols-rounded text-3xl">account_circle</span>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[11px] font-black text-slate-600 dark:text-white/80 uppercase tracking-widest leading-none">Upload Avatar</span>
                                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-2 leading-none ">512x512 recommended</span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/30 px-1 mb-2">Channel Banner</label>
                            <div class="relative group h-40">
                                <input type="file" name="banner" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                       @change="openCropper($event.target, null, {aspectRatio: 6.2}, (file, url) => { bannerPreview = url; })">
                                <div class="w-full h-full bg-slate-50 dark:bg-black/40 border-4 border-dashed border-slate-200 dark:border-white/10 rounded-[2.5rem] flex items-center px-8 gap-6 group-hover:border-blue-500/50 transition-all overflow-hidden">
                                    <template x-if="bannerPreview">
                                        <img :src="bannerPreview" class="w-full h-full object-cover absolute inset-0">
                                    </template>
                                    <template x-if="!bannerPreview">
                                        <div class="flex items-center gap-6">
                                            <div class="w-16 h-16 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 shadow-lg">
                                                <span class="material-symbols-rounded text-3xl">image</span>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[11px] font-black text-slate-600 dark:text-white/80 uppercase tracking-widest leading-none">Upload Banner</span>
                                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-2 leading-none ">1920x480 recommended</span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Descriptive Narrative -->
                    <x-textarea 
                        name="description" 
                        label="Channel Description" 
                        placeholder="Enter the mission and content focus of this channel..." 
                        rows="5" 
                        hint="A compelling description helps attract more subscribers."
                    />

                    <!-- Section 4: Social Linkage Matrix -->
                    <div class="pt-8 space-y-8">
                        <div class="flex items-center gap-4">
                            <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-[0.3em] ">Social Framework</span>
                            <div class="h-[1px] flex-grow bg-slate-100 dark:bg-white/5"></div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8">
                            @foreach(['Facebook', 'Twitter', 'Instagram', 'YouTube'] as $social)
                                <x-input 
                                    type="url" 
                                    name="social_links[{{ strtolower($social) }}]" 
                                    label="{{ $social }} URL" 
                                    placeholder="https://{{ strtolower($social) }}.com/..." 
                                    hint="Connect the channel's official {{ $social }} presence."
                                />
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="mt-16 flex flex-col sm:flex-row gap-6">
                    <button type="submit" class="flex-grow h-20 rounded-[2.5rem] orange-gradient-primary text-white text-[12px] font-black uppercase tracking-[0.3em] shadow-2xl shadow-orange-500/30 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-4">
                        <span class="material-symbols-rounded text-2xl">sensors</span>
                        Create Channel
                    </button>
                    <a href="{{ route('admin.channels.index') }}" class="h-20 px-12 rounded-[2.5rem] bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-white/40 text-[11px] font-black uppercase tracking-[0.2em] hover:bg-slate-200 dark:hover:bg-white/10 transition-all flex items-center justify-center border border-transparent">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

