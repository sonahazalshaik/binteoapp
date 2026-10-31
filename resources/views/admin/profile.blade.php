@extends('admin.layouts.app')
@section('panel')

    <div class="space-y-8">
        <!-- Profile Header Section -->
        <div class="relative overflow-hidden bg-slate-900 rounded-[2.5rem] p-8 border border-white/5 shadow-2xl">
            <!-- Ambient Glows -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-orange-500/10 rounded-full blur-[100px] -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-500/10 rounded-full blur-[80px] -ml-24 -mb-24"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                <!-- Avatar Section -->
                <div class="relative group">
                    <div class="w-32 h-32 rounded-[2.5rem] bg-white/10 p-1 border border-white/20 shadow-2xl overflow-hidden transform group-hover:scale-105 transition-all duration-500">
                        <img src="{{ getImage(getFilePath('adminProfile').'/'. $admin->image,getFileSize('adminProfile'))}}" 
                             class="w-full h-full object-cover rounded-[2.2rem]" alt="Profile">
                    </div>
                    <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-emerald-500 rounded-2xl flex items-center justify-center text-white border-4 border-slate-900 shadow-xl">
                        <span class="material-symbols-rounded text-xl">verified</span>
                    </div>
                </div>

                <!-- User Info -->
                <div class="text-center md:text-left space-y-2">
                    <h2 class="text-3xl font-black text-white tracking-tight uppercase leading-none">{{ $admin->name }}</h2>
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mt-4">
                        @if($admin->role == 'admin')
                        <span class="px-4 py-1.5 bg-orange-500/20 text-orange-500 rounded-xl text-[10px] font-black uppercase tracking-widest border border-orange-500/20">
                            @lang('Administrator')
                        </span>
                        @endif
                        <span class="px-4 py-1.5 bg-white/5 text-slate-400 rounded-xl text-[10px] font-black uppercase tracking-widest border border-white/5">
                            {{ $admin->username }}
                        </span>
                        <div class="flex items-center gap-2 px-4 py-1.5 bg-emerald-500/10 text-emerald-500 rounded-xl text-[10px] font-black uppercase tracking-widest border border-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            @lang('Active Session')
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="ml-auto hidden xl:flex items-center gap-6 pr-4">
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">@lang('Member Since')</p>
                        <p class="text-lg font-black text-white tracking-tight">{{ $admin->created_at->format('M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Details Card -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                        <span class="material-symbols-rounded text-6xl">badge</span>
                    </div>
                    <h5 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                        @lang('Identity Details')
                    </h5>
                    
                    <div class="space-y-6">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">@lang('Full Name')</p>
                            <p class="text-sm font-bold text-slate-700">{{ $admin->name }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">@lang('Email Address')</p>
                            <p class="text-sm font-bold text-slate-700">{{ $admin->email }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">@lang('System Role')</p>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <p class="text-sm font-bold text-slate-700 uppercase tracking-widest text-[10px]">{{ $admin->role }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.password') }}" class="flex items-center justify-between p-6 bg-gradient-to-r from-orange-500 to-orange-600 rounded-3xl text-white shadow-lg shadow-orange-500/20 group hover:scale-[1.02] transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center backdrop-blur-md">
                            <span class="material-symbols-rounded text-2xl">key</span>
                        </div>
                        <div>
                            <p class="text-xs font-black uppercase tracking-widest">@lang('Security Settings')</p>
                            <p class="text-[10px] font-bold opacity-80 uppercase tracking-widest mt-0.5">@lang('Update Password')</p>
                        </div>
                    </div>
                    <span class="material-symbols-rounded group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
            </div>

            <!-- Edit Form Card -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden">
                    <h5 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-8 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        @lang('Edit Profile Information')
                    </h5>

                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Image Upload Section -->
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">@lang('Profile Picture')</label>
                                <div class="bg-slate-50 p-6 rounded-[2rem] border border-slate-100 relative group">
                                    <x-image-uploader image="{{ $admin->image }}" class="w-full !rounded-3xl" type="adminProfile" :required=false />
                                    <div class="absolute inset-0 bg-slate-900/40 rounded-[2rem] opacity-0 group-hover:opacity-100 flex items-center justify-center pointer-events-none transition-all duration-300 backdrop-blur-sm">
                                        <span class="text-white text-[10px] font-black uppercase tracking-widest">@lang('Click to change image')</span>
                                    </div>
                                </div>
                                <p class="text-[9px] text-slate-400 ml-4">@lang('Supported: jpg, jpeg, png. Maximum size: 2MB.')</p>
                            </div>

                            <!-- Input Fields Section -->
                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">@lang('Full Display Name')</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-rounded text-slate-400">person</span>
                                        <input class="w-full bg-slate-50 border-slate-100 rounded-2xl py-4 pl-12 pr-6 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all outline-none" 
                                               type="text" name="name" value="{{ $admin->name }}" required placeholder="@lang('Your Name')">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">@lang('Contact Email Address')</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-rounded text-slate-400">alternate_email</span>
                                        <input class="w-full bg-slate-50 border-slate-100 rounded-2xl py-4 pl-12 pr-6 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all outline-none" 
                                               type="email" name="email" value="{{ $admin->email }}" required placeholder="@lang('Email Address')">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">@lang('Admin Username')</label>
                                    <div class="relative opacity-60">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-rounded text-slate-400">lock</span>
                                        <input class="w-full bg-slate-100 border-slate-200 rounded-2xl py-4 pl-12 pr-6 text-sm font-bold text-slate-400 cursor-not-allowed" 
                                               type="text" value="{{ $admin->username }}" disabled>
                                    </div>
                                    <p class="text-[8px] text-slate-400 ml-4">@lang('Username cannot be changed for security reasons.')</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4 border-t border-slate-50">
                            <button type="submit" class="w-full md:w-auto px-12 py-4 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-[0.2em] hover:bg-slate-800 active:scale-95 transition-all shadow-xl shadow-slate-900/10 flex items-center justify-center gap-3">
                                <span class="material-symbols-rounded">save</span>
                                @lang('Update Profile Session')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{route('admin.password')}}" class="flex items-center gap-2 px-6 py-2.5 bg-white border border-slate-200 rounded-xl text-slate-600 text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm">
        <span class="material-symbols-rounded text-lg">lock</span>
        @lang('Security Vault')
    </a>
@endpush

@push('style')
    <style>
        .image-upload-wrapper {
            @apply !bg-transparent !border-0 !p-0;
        }
        .image-upload-preview {
            @apply !rounded-[1.5rem] !shadow-none !border-0;
        }
    </style>
@endpush

