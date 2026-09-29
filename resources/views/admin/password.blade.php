@extends('admin.layouts.app')
@section('panel')

    <div class="space-y-8">
        <!-- Security Header Section -->
        <div class="relative overflow-hidden bg-slate-900 rounded-[2.5rem] p-8 border border-white/5 shadow-2xl">
            <!-- Ambient Glows -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-orange-500/10 rounded-full blur-[100px] -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-rose-500/10 rounded-full blur-[80px] -ml-24 -mb-24"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                <!-- Icon Section -->
                <div class="w-24 h-24 rounded-[2rem] bg-orange-500/20 border border-orange-500/30 flex items-center justify-center text-orange-500 shadow-2xl backdrop-blur-sm">
                    <span class="material-symbols-rounded text-5xl">shield_lock</span>
                </div>

                <!-- Title Info -->
                <div class="text-center md:text-left space-y-2">
                    <h2 class="text-3xl font-black text-white tracking-tight uppercase leading-none">@lang('Security Vault')</h2>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mt-4">@lang('Manage your administrative credentials and access control')</p>
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mt-4">
                        <div class="flex items-center gap-2 px-4 py-1.5 bg-emerald-500/10 text-emerald-500 rounded-xl text-[10px] font-black uppercase tracking-widest border border-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            @lang('Secure Session Active')
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="ml-auto hidden xl:flex items-center gap-6 pr-4">
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">@lang('Last Updated')</p>
                        <p class="text-lg font-black text-white tracking-tight">{{ $admin->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- User Summary Sidebar -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                        <span class="material-symbols-rounded text-6xl">account_circle</span>
                    </div>
                    
                    <div class="flex flex-col items-center text-center mb-8">
                        <div class="w-24 h-24 rounded-[2rem] bg-slate-50 p-1 border border-slate-100 shadow-inner mb-4 overflow-hidden">
                            <img src="{{ getImage(getFilePath('adminProfile').'/'. $admin->image,getFileSize('adminProfile'))}}" 
                                 class="w-full h-full object-cover rounded-[1.8rem]" alt="Admin">
                        </div>
                        <h4 class="text-lg font-black text-slate-900 uppercase leading-none">{{ $admin->name }}</h4>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">{{ $admin->email }}</p>
                    </div>

                    <div class="space-y-4 pt-6 border-t border-slate-50">
                        <div class="flex justify-between items-center px-2">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">@lang('Access Level')</span>
                            @if($admin->role == 'admin')
                            <span class="px-3 py-1 bg-orange-500/10 text-orange-500 rounded-lg text-[9px] font-black uppercase tracking-widest border border-orange-500/20">@lang('Administrator')</span>
                            @endif
                        </div>
                        <div class="flex justify-between items-center px-2">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">@lang('System Role')</span>
                            <span class="text-[10px] font-bold text-slate-700 uppercase tracking-widest">{{ $admin->role }}</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.profile') }}" class="flex items-center justify-between p-6 bg-slate-900 rounded-3xl text-white shadow-xl shadow-slate-900/10 group hover:scale-[1.02] transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center backdrop-blur-md">
                            <span class="material-symbols-rounded text-2xl">person</span>
                        </div>
                        <div>
                            <p class="text-xs font-black uppercase tracking-widest">@lang('Identity Hub')</p>
                            <p class="text-[10px] font-bold opacity-60 uppercase tracking-widest mt-0.5">@lang('Profile Information')</p>
                        </div>
                    </div>
                    <span class="material-symbols-rounded group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
            </div>

            <!-- Change Password Form -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden">
                    <h5 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-8 flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        @lang('Credential Update')
                    </h5>

                    <form action="{{ route('admin.password.update') }}" method="POST" class="space-y-8">
                        @csrf
                        
                        <div class="grid grid-cols-1 gap-8">
                            <!-- Current Password -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">@lang('Current Security Key')</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-rounded text-slate-400">lock_open</span>
                                    <input class="w-full bg-slate-50 border-slate-100 rounded-2xl py-4 pl-12 pr-6 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all outline-none" 
                                           type="password" name="old_password" required placeholder="@lang('Enter Current Password')">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- New Password -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">@lang('New Password')</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-rounded text-slate-400">key</span>
                                        <input class="w-full bg-slate-50 border-slate-100 rounded-2xl py-4 pl-12 pr-6 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all outline-none" 
                                               type="password" name="password" required placeholder="@lang('Create New Password')">
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">@lang('Confirm New Password')</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-rounded text-slate-400">verified_user</span>
                                        <input class="w-full bg-slate-50 border-slate-100 rounded-2xl py-4 pl-12 pr-6 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all outline-none" 
                                               type="password" name="password_confirmation" required placeholder="@lang('Repeat New Password')">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 flex items-start gap-4">
                            <div class="w-10 h-10 rounded-2xl bg-orange-500/10 flex items-center justify-center text-orange-500 shrink-0">
                                <span class="material-symbols-rounded text-xl">info</span>
                            </div>
                            <p class="text-[10px] font-bold text-slate-500 leading-relaxed uppercase tracking-widest">
                                @lang('Ensure your new password contains a mix of characters, numbers, and symbols for maximum security. Changing your password will not terminate your current session.')
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4 border-t border-slate-50">
                            <button type="submit" class="w-full md:w-auto px-12 py-4 bg-orange-500 text-white rounded-2xl text-xs font-black uppercase tracking-[0.2em] hover:bg-orange-600 active:scale-95 transition-all shadow-xl shadow-orange-500/20 flex items-center justify-center gap-3">
                                <span class="material-symbols-rounded">security_update_good</span>
                                @lang('Revoke & Update Credentials')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{route('admin.profile')}}" class="flex items-center gap-2 px-6 py-2.5 bg-white border border-slate-200 rounded-xl text-slate-600 text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm">
        <span class="material-symbols-rounded text-lg">person</span>
        @lang('Identity Hub')
    </a>
@endpush

