@extends('admin.layouts.app')

@section('title', 'Refine Advertising Campaign')
@section('header_title', 'Modify Initiative')

@section('content')
<div class="max-w-4xl mx-auto space-y-12 animate-in fade-in slide-in-from-bottom-10 duration-700 pb-24">
    <!-- Header -->
    <div class="flex items-center justify-between px-6 lg:px-0">
        <div>
            <h3 class="text-xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Campaign Modification</h3>
            <p class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3 leading-relaxed">Refining parameters for #{{ $campaign->title }}</p>
        </div>
        <a href="{{ route('admin.campaign.index') }}" class="w-12 h-12 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 dark:text-white/40 hover:bg-slate-900 hover:text-white transition-all shadow-xl active:scale-90">
            <span class="material-symbols-rounded">arrow_back</span>
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white/60 dark:bg-white/5 backdrop-blur-3xl border border-white dark:border-white/10 rounded-[2rem] p-8 lg:p-12 shadow-2xl relative overflow-hidden group">
        <form action="{{ route('admin.campaign.index') }}" method="POST" class="space-y-10">
            @csrf
            
            <div class="space-y-10">
                <!-- Title -->
                <x-input 
                    name="title" 
                    label="Campaign Title" 
                    value="{{ $campaign->title }}" 
                    placeholder="E.g. Summer Launch 2024..." 
                    required 
                    hint="Give your campaign a descriptive name for easier tracking."
                />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Advertiser -->
                    <x-select name="user_id" label="Associate Advertiser" required hint="Select the account responsible for this advertising initiative.">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected($campaign->user_id == $user->id)>{{ $user->username }} ({{ $user->email }})</option>
                        @endforeach
                    </x-select>

                    <!-- Budget Allocation -->
                    <x-input 
                        type="number" 
                        step="0.01" 
                        name="total_amount" 
                        label="Total Budget Allocation ({{ gs('cur_text') }})" 
                        value="{{ $campaign->total_amount }}" 
                        placeholder="50.00" 
                        required 
                        hint="Adjust the total budget allocation for this campaign."
                    />
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-10 border-t border-slate-100 dark:border-white/5">
                <button type="submit" class="w-full h-16 rounded-[2rem] bg-slate-900 dark:bg-white text-white dark:text-black flex items-center justify-center gap-4 text-[12px] font-black uppercase tracking-[0.4em] hover:scale-[1.02] transition-all shadow-2xl active:scale-95 group overflow-hidden relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-orange-500 to-red-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <span class="material-symbols-rounded text-base relative z-10 ">published_with_changes</span>
                    <span class="relative z-10 ">Sync Campaign</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

