@extends('admin.layouts.app')

@section('title', 'User Registration')
@section('header_title', 'Register New User')

@section('content')
<div class="max-w-6xl mx-auto">
    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" 
          x-data="{ loading: false }" @submit="loading = true" class="space-y-6 pb-20">
        @csrf

        <!-- Header Block -->
        <div class="bg-white dark:bg-[#121212] rounded-xl p-4 border border-slate-200 dark:border-white/10 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4 animate-in fade-in slide-in-from-top-4 duration-700">
            <div class="flex items-center gap-4 text-center md:text-left flex-col md:flex-row">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                    <span class="material-symbols-rounded text-4xl">person_add</span>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tighter leading-none">User Registration</h3>
                    <p class="text-[10px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest mt-3">Register a new user account on the platform</p>
                </div>
            </div>
            <a href="{{ route('admin.users.all') }}" class="h-11 px-8 rounded-lg bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-500 dark:text-white/40 flex items-center gap-3 text-[10px] font-black uppercase tracking-widest hover:bg-slate-100 dark:hover:bg-white/10 transition-all ">
                <span class="material-symbols-rounded">arrow_back</span> Return to Hub
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Credentials Card -->
            <div class="bg-white dark:bg-[#121212] rounded-xl border border-slate-200 dark:border-white/10 shadow-sm p-4 animate-in fade-in slide-in-from-left-4 duration-700 delay-100">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-blue-500/10 text-blue-500 flex items-center justify-center">
                        <span class="material-symbols-rounded">key</span>
                    </div>
                    <div>
                        <h4 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tighter">Login Credentials</h4>
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Username</label>
                        <input type="text" name="username" placeholder="e.g. johndoe_official" required class="w-full h-11 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg px-6 text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Password</label>
                        <input type="password" name="password" placeholder="••••••••••••" required class="w-full h-11 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg px-6 text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                    </div>
                </div>
            </div>

            <!-- Identity Card -->
            <div class="bg-white dark:bg-[#121212] rounded-xl border border-slate-200 dark:border-white/10 shadow-sm p-4 animate-in fade-in slide-in-from-right-4 duration-700 delay-200">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-rose-500/10 text-rose-500 flex items-center justify-center">
                        <span class="material-symbols-rounded">badge</span>
                    </div>
                    <div>
                        <h4 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tighter">Personal Details</h4>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">First Name</label>
                        <input type="text" name="firstname" placeholder="John" required class="w-full h-11 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg px-6 text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-rose-500 transition-all outline-none">
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Last Name</label>
                        <input type="text" name="lastname" placeholder="Doe" required class="w-full h-11 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg px-6 text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-rose-500 transition-all outline-none">
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Email Address</label>
                    <input type="email" name="email" placeholder="john@example.com" required class="w-full h-11 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg px-6 text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-rose-500 transition-all outline-none">
                </div>
            </div>
        </div>

        <!-- Branding & Media -->
        <div class="bg-white dark:bg-[#121212] rounded-xl border border-slate-200 dark:border-white/10 shadow-sm p-4 animate-in fade-in slide-in-from-bottom-4 duration-700 delay-300">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-10 h-10 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center">
                    <span class="material-symbols-rounded">palette</span>
                </div>
                <div>
                    <h4 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tighter">Creator Profile</h4>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="lg:col-span-1 space-y-6">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Profile Avatar</label>
                        <div class="relative group h-40 rounded-xl border-2 border-dashed border-slate-200 dark:border-white/10 flex flex-col items-center justify-center bg-slate-50 dark:bg-white/[0.02] hover:bg-slate-100 dark:hover:bg-white/5 transition-all cursor-pointer overflow-hidden">
                            <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="previewImage(this, 'avatar_preview')">
                            <div id="avatar_preview_placeholder" class="text-center">
                                <span class="material-symbols-rounded text-base text-slate-300 dark:text-white/10">add_a_photo</span>
                                <p class="text-[8px] font-black text-slate-400 uppercase mt-2">Upload Photo</p>
                            </div>
                            <img id="avatar_preview" class="hidden absolute inset-0 w-full h-full object-cover">
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Channel Display Name</label>
                            <input type="text" name="channel_name" placeholder="Creator Channel Title" class="w-full h-11 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg px-6 text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-amber-500 transition-all outline-none">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">User Role / Mode</label>
                            <select name="creator_status" class="w-full h-11 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg px-6 text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-amber-500 transition-all outline-none cursor-pointer">
                                <option value="0">Consumer Node (Regular)</option>
                                <option value="1">Broadcaster Node (Creator)</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Phone Number</label>
                        <input type="text" name="mobile" placeholder="+1 234 567 890" required class="w-full h-11 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg px-6 text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-amber-500 transition-all outline-none">
                    </div>
                </div>
            </div>
        </div>

        <!-- Global Settings -->
        <div class="bg-white dark:bg-[#121212] rounded-xl border border-slate-200 dark:border-white/10 shadow-sm p-4 animate-in fade-in slide-in-from-bottom-4 duration-700 delay-400">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-10 h-10 rounded-lg bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                    <span class="material-symbols-rounded">settings_suggest</span>
                </div>
                <div>
                    <h4 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tighter">Access Controls</h4>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5 flex items-center justify-between group">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Email Audit</span>
                        <span class="text-[8px] font-bold text-slate-400 uppercase mt-1">Status Verified</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="ev" value="1" class="sr-only peer" checked>
                        <div class="w-12 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-white/10 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                    </label>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5 flex items-center justify-between group">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Mobile Audit</span>
                        <span class="text-[8px] font-bold text-slate-400 uppercase mt-1">Identity Verified</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="sv" value="1" class="sr-only peer" checked>
                        <div class="w-12 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-white/10 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-500"></div>
                    </label>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5 flex items-center justify-between group">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">KYC Portal</span>
                        <span class="text-[8px] font-bold text-slate-400 uppercase mt-1">Bypass Pending</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="kv" value="1" class="sr-only peer">
                        <div class="w-12 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-white/10 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-rose-500"></div>
                    </label>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5 flex items-center justify-between group">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Node State</span>
                        <span class="text-[8px] font-bold text-slate-400 uppercase mt-1">Access Control</span>
                    </div>
                    <select name="status" class="bg-transparent text-[10px] font-black uppercase text-slate-900 dark:text-white outline-none cursor-pointer">
                        <option value="active" class="bg-white dark:bg-[#121212]">Active</option>
                        <option value="banned" class="bg-white dark:bg-[#121212]">Blocked</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Location Metadata -->
        <div class="bg-white dark:bg-[#121212] rounded-xl border border-slate-200 dark:border-white/10 shadow-sm p-4 animate-in fade-in slide-in-from-bottom-4 duration-700 delay-500">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-10 h-10 rounded-lg bg-indigo-500/10 text-indigo-500 flex items-center justify-center">
                    <span class="material-symbols-rounded">location_on</span>
                </div>
                <div>
                    <h4 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tighter">Location Details</h4>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2 space-y-3">
                    <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Street Address</label>
                    <input type="text" name="address" placeholder="123 Discovery Way" class="w-full h-11 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg px-6 text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none">
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">City</label>
                    <input type="text" name="city" placeholder="e.g. Mumbai" class="w-full h-11 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg px-6 text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none">
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest px-1">Country</label>
                    <select name="country" required class="w-full h-11 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg px-6 text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none cursor-pointer">
                        <option value="" disabled selected>Select Country...</option>
                        @foreach($countries as $key => $country)
                            <option value="{{ $key }}">{{ $country->country }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Global Action Hub -->
        <div class="flex items-center justify-between pt-10">
            <div class="hidden md:block">
                <p class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-widest leading-none">Ready for Deployment</p>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-2">New node will be indexed across the network instantly.</p>
            </div>
            <button type="submit" :disabled="loading" class="h-12 px-16 rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[11px] font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-2xl shadow-slate-900/20 dark:shadow-white/5 flex items-center gap-4 disabled:opacity-50 disabled:cursor-wait">
                <span x-text="loading ? 'Processing Protocol...' : 'Confirm Registration'">Confirm Registration</span>
                <span x-show="!loading" class="material-symbols-rounded">rocket_launch</span>
                <span x-show="loading" class="material-symbols-rounded animate-spin">sync</span>
            </button>
        </div>
    </form>
</div>

<script>
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
            document.getElementById(previewId).classList.remove('hidden');
            document.getElementById(previewId + '_placeholder').classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection



