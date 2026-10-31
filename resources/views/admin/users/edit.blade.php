@extends('admin.layouts.app')

@section('title', 'Edit User Profile')
@section('header_title', 'Update User')

@section('content')
<div class="max-w-[1600px] mx-auto animate-in fade-in slide-in-from-bottom-10 duration-1000">
    
    <!-- Header Summary Card -->
    <div class="relative bg-white dark:bg-[#121212] rounded-xl border border-slate-200 dark:border-white/10 shadow-2xl overflow-hidden mb-6">
        <div class="p-5 flex flex-col lg:flex-row items-center gap-4">
            <div class="relative">
                <div class="w-24 h-24 rounded-xl p-1 bg-gradient-to-tr from-slate-200 to-slate-100 dark:from-white/10 dark:to-white/5 shadow-2xl">
                    <div class="w-full h-full rounded-[1.8rem] overflow-hidden border-4 border-white dark:border-[#121212] shadow-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center font-black text-slate-300 text-4xl ">
                        @if($user->image)
                            <img src="{{ getImage(getFilePath('userProfile').'/'.$user->image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-orange-500 to-amber-400 text-white font-black uppercase ">
                                {{ substr($user->firstname, 0, 1) }}{{ substr($user->lastname, 0, 1) }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="absolute -bottom-1 -right-1 w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/30">
                    <span class="material-symbols-rounded text-sm">verified</span>
                </div>
            </div>

            <div class="flex-grow text-center lg:text-left">
                <div class="flex flex-col lg:flex-row lg:items-center gap-4 mb-3">
                    <h2 class="text-base font-black text-slate-800 dark:text-white uppercase tracking-tighter">{{ $user->fullname }}</h2>
                    <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-white/5 text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest">@ {{ $user->username }}</span>
                </div>
                <div class="flex flex-wrap justify-center lg:justify-start gap-5">
                    <div>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Balance</p>
                        <p class="text-sm font-black text-emerald-500 ">{{ showAmount($user->balance) }}</p>
                    </div>
                    <div>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Joined Date</p>
                        <p class="text-sm font-black text-slate-700 dark:text-white/80 ">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Account Status</p>
                        <p class="text-sm font-black {{ $user->status == 'active' ? 'text-blue-500' : 'text-rose-500' }} ">{{ $user->status == 'active' ? 'Active' : 'Blocked' }}</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <a href="{{ route('admin.users.detail', $user->id) }}" class="h-10 px-6 rounded-xl bg-slate-900 text-white flex items-center gap-2 text-[9px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-xl ">
                    <span class="material-symbols-rounded text-sm">visibility</span> View Details
                </a>
                <a href="{{ route('admin.users.login', $user->id) }}" target="_blank" class="h-10 px-6 rounded-xl bg-orange-500 text-white flex items-center gap-2 text-[9px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-xl ">
                    <span class="material-symbols-rounded text-sm">login</span> Login As User
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 pb-24">
        @csrf
        
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
            
            <!-- Left Column: Branding Media -->
            <div class="xl:col-span-1 space-y-6">
                <div class="bg-white dark:bg-[#1a1a1a] rounded-xl border border-slate-200 dark:border-white/10 shadow-2xl p-5">
                    <h4 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tighter mb-8 border-l-4 border-emerald-500 pl-4">Profile Media</h4>
                    
                    <div class="space-y-5">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Profile Avatar</label>
                            <div class="relative group cursor-pointer h-36 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center overflow-hidden transition-all hover:border-emerald-500/50">
                                <img id="profile-preview" src="{{ $user->image ? getImage(getFilePath('userProfile').'/'.$user->image) : '' }}" class="absolute inset-0 w-full h-full object-cover {{ $user->image ? 'opacity-50' : 'opacity-0' }} group-hover:opacity-10 transition-opacity">
                                <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer z-10" accept="image/*" onchange="openCropper(this, 'profile-preview', {aspectRatio: 1})">
                                <div class="relative z-0 text-center">
                                    <span class="material-symbols-rounded text-sm text-slate-300">add_a_photo</span>
                                    <p class="text-[8px] font-black text-slate-400 uppercase mt-2">Change Avatar</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="bg-white dark:bg-[#1a1a1a] rounded-xl border border-slate-200 dark:border-white/10 shadow-2xl p-5">
                    <h4 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tighter mb-8 border-l-4 border-blue-500 pl-4">Channel Banner</h4>
                    <div class="space-y-4">
                        <div class="relative group cursor-pointer h-40 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center overflow-hidden transition-all hover:border-blue-500/50">
                            <img id="banner-preview" src="{{ @$user->channel->banner ? getImage(getFilePath('channelBanner').'/'.$user->channel->banner) : '' }}" class="absolute inset-0 w-full h-full object-cover {{ @$user->channel->banner ? 'opacity-50' : 'opacity-0' }} group-hover:opacity-10 transition-opacity">
                            <input type="file" name="channel_banner" class="absolute inset-0 opacity-0 cursor-pointer z-10" accept="image/*" onchange="openCropper(this, 'banner-preview', {aspectRatio: 6.2})">
                            <div class="relative z-0 text-center">
                                <span class="material-symbols-rounded text-base text-slate-300">panorama</span>
                                <p class="text-[9px] font-black text-slate-400 uppercase mt-4">Update Banner Image</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Personal & System Configuration -->
            <div class="xl:col-span-2 space-y-6">
                <div class="bg-white dark:bg-[#1a1a1a] rounded-xl border border-slate-200 dark:border-white/10 shadow-2xl p-4">
                    <!-- Personal Info -->
                    <div class="space-y-5">
                        <div class="flex items-center gap-4">
                            <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest bg-slate-100 dark:bg-white/5 px-4 py-1.5 rounded-lg border border-slate-200 dark:border-white/5">01. Personal Details</span>
                            <div class="h-[1px] flex-grow bg-slate-100 dark:bg-white/5"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">First Name</label>
                                <input type="text" name="firstname" value="{{ $user->firstname }}" required class="w-full h-11 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg px-6 text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:border-emerald-500 transition-all">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Last Name</label>
                                <input type="text" name="lastname" value="{{ $user->lastname }}" required class="w-full h-11 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg px-6 text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:border-emerald-500 transition-all">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Email Address</label>
                                <input type="email" name="email" value="{{ $user->email }}" required class="w-full h-11 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg px-6 text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:border-emerald-500 transition-all">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Mobile Number</label>
                                <input type="text" name="mobile" value="{{ $user->mobile }}" required class="w-full h-11 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg px-6 text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:border-emerald-500 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Channel Info -->
                    <div class="mt-8 space-y-5">
                        <div class="flex items-center gap-4">
                            <span class="text-[11px] font-black text-orange-500 uppercase tracking-widest bg-orange-500/5 px-4 py-1.5 rounded-lg border border-orange-500/10">02. Channel Settings</span>
                            <div class="h-[1px] flex-grow bg-orange-500/10"></div>
                        </div>
                        <div class="grid grid-cols-1 gap-5">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Channel Name</label>
                                <input type="text" name="channel_name" value="{{ @$user->channel->name }}" class="w-full h-11 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg px-6 text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:border-orange-500 transition-all">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Channel Description</label>
                                <textarea name="channel_description" class="w-full h-28 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg p-4 text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:border-orange-500 transition-all resize-none">{{ @$user->channel->description }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Geographic Info -->
                    <div class="mt-8 space-y-5">
                        <div class="flex items-center gap-4">
                            <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest bg-slate-100 dark:bg-white/5 px-4 py-1.5 rounded-lg border border-slate-200 dark:border-white/5">03. Address Information</span>
                            <div class="h-[1px] flex-grow bg-slate-100 dark:bg-white/5"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2 space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Street Address</label>
                                <input type="text" name="address" value="{{ $user->address }}" class="w-full h-11 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg px-6 text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:border-emerald-500 transition-all">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">City</label>
                                <input type="text" name="city" value="{{ $user->city }}" class="w-full h-11 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg px-6 text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:border-emerald-500 transition-all">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Country</label>
                                <select name="country" required class="w-full h-11 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg px-6 text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:border-emerald-500 transition-all appearance-none cursor-pointer">
                                    @foreach($countries as $key => $country)
                                        <option value="{{ $key }}" {{ $user->country_code == $key ? 'selected' : '' }}>{{ $country->country }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Security Settings -->
                    <div class="mt-8 space-y-5">
                        <div class="flex items-center gap-4">
                            <span class="text-[11px] font-black text-orange-500 uppercase tracking-widest bg-orange-500/5 px-4 py-1.5 rounded-lg border border-orange-500/10">04. Security Settings</span>
                            <div class="h-[1px] flex-grow bg-orange-500/10"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @php $unlocked = $user->getUnlockedPassword(); @endphp
                            @if($unlocked)
                            <div class="space-y-3" x-data="{ show: false }">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Existing Password (Unlocked)</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" value="{{ $unlocked }}" readonly class="w-full h-11 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg pl-6 pr-12 text-sm font-bold text-slate-400 dark:text-white/40 focus:outline-none transition-all cursor-not-allowed" placeholder="••••••••">
                                    <button type="button" @click="show = !show" class="absolute right-4 top-0 h-11 flex items-center text-slate-400 hover:text-orange-500 transition-colors">
                                        <span class="material-symbols-rounded" x-text="show ? 'visibility_off' : 'visibility'"></span>
                                    </button>
                                </div>
                            </div>
                            @endif

                            <div class="space-y-3" x-data="{ show: false }">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">New Password (Leave empty to keep current)</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" name="password" class="w-full h-11 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg pl-6 pr-12 text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:border-orange-500 transition-all" placeholder="••••••••">
                                    <button type="button" @click="show = !show" class="absolute right-4 top-0 h-11 flex items-center text-slate-400 hover:text-orange-500 transition-colors">
                                        <span class="material-symbols-rounded" x-text="show ? 'visibility_off' : 'visibility'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Status -->
                    <div class="mt-8 space-y-5">
                        <div class="flex items-center gap-4">
                            <span class="text-[11px] font-black text-rose-500 uppercase tracking-widest bg-rose-500/5 px-4 py-1.5 rounded-lg border border-rose-500/10">05. Account Settings</span>
                            <div class="h-[1px] flex-grow bg-rose-500/10"></div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="p-5 rounded-lg bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5 flex items-center justify-between group">
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Email Verified</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="ev" value="1" class="sr-only peer" {{ $user->ev ? 'checked' : '' }}>
                                    <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-white/10 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                                </label>
                            </div>
                            <div class="p-5 rounded-lg bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5 flex items-center justify-between group">
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Mobile Verified</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="sv" value="1" class="sr-only peer" {{ $user->sv ? 'checked' : '' }}>
                                    <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-white/10 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-500"></div>
                                </label>
                            </div>
                            <div class="p-5 rounded-lg bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5 flex items-center justify-between group">
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Creator Mode</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="creator_status" value="1" class="sr-only peer" {{ $user->creator_status ? 'checked' : '' }}>
                                    <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-white/10 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-orange-500"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-10 border-t border-slate-100 dark:border-white/5 flex justify-end">
                        <button type="submit" class="h-12 px-16 rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-black uppercase tracking-widest text-[11px] hover:scale-105 transition-all shadow-2xl active:scale-95 flex items-center justify-center gap-4 group">
                            <span>Update Profile</span>
                            <span class="material-symbols-rounded text-base">sync</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection



