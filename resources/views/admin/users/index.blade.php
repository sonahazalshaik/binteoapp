@extends('admin.layouts.app')

@section('title', 'User Management')
@section('header_title', 'User List')

@section('content')
<div x-data="{ 
    selectAll: false, 
    toggleAll() { 
        const checkboxes = document.querySelectorAll('.bulk-select-item');
        checkboxes.forEach(cb => { 
            cb.checked = this.selectAll; 
            cb.dispatchEvent(new Event('change', { bubbles: true }));
        });
    }
}" class="bg-white dark:bg-[#121212] rounded-[2rem] overflow-hidden border border-slate-200 dark:border-white/10 shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-700">
    @include('admin.components.header-toolbar', [
        'createRoute' => route('admin.users.create'),
        'createLabel' => 'Add User'
    ])

    <div class="px-6 pt-6">
        @include('admin.components.table-toolbar', [
            'showBulkActions' => true,
            'module' => 'users',
            'bulkRoute' => route('admin.users.bulk'),
            'bulkActions' => ['delete'],
            'exportTotal' => count($users)
        ])
    </div>

    <!-- Table View -->
    <div class="overflow-x-auto table-responsive" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="selectAllHeader" x-model="selectAll" @change="toggleAll()" class="w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                        </label>
                    </th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">User Info</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Contact Details</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Wallet Balance</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Location</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate">Joined At</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                    <td class="px-6 py-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" class="bulk-select-item w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500" value="{{ $user->id }}">
                        </label>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-100 dark:bg-white/5 flex items-center justify-center font-bold text-slate-400">
                                @if(@$user->channel->avatar)
                                    <img src="{{ getImage($user->channel->avatar) }}" class="w-full h-full object-cover">
                                @elseif($user->image)
                                    <img src="{{ getImage(getFilePath('userProfile').'/'.$user->image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-orange-500 to-amber-400 text-white text-[10px] font-black uppercase ">
                                        {{ substr($user->firstname, 0, 1) }}{{ substr($user->lastname, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-[11px] font-black text-slate-900 dark:text-white uppercase leading-none">{{ $user->fullname }}</h4>
                                <p class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest mt-1">@ {{ $user->username }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                        {{ $user->email }}<br><span class="text-[9px] text-slate-400">+{{ $user->dial_code }}{{ $user->mobile }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-[11px] font-black text-emerald-500 ">{{ showAmount($user->balance) }}</span>
                    </td>
                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                        <span class="font-bold uppercase text-[9px] bg-slate-100 dark:bg-white/5 px-2 py-0.5 rounded" title="{{ $user->country_name }}">{{ $user->country_code }}</span>
                    </td>
                    <td class="px-6 py-4 text-[10px] font-bold text-slate-500 dark:text-white/40 ">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>

                    <td class="px-6 py-4 text-right">
                        <div class="grid grid-cols-2 gap-2 w-max ml-auto">
                             <a href="{{ route('admin.users.detail', $user->id) }}" class="h-8 px-3 rounded-xl bg-emerald-500 text-white flex items-center justify-center gap-2 hover:scale-105 transition-all shadow-sm group" title="Intelligence Hub">
                                 <span class="material-symbols-rounded text-sm">visibility</span>
                                 <span class="text-[9px] font-black uppercase tracking-tighter hidden xl:block">View</span>
                            </a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="h-8 px-3 rounded-xl bg-blue-500 text-white flex items-center justify-center gap-2 hover:scale-105 transition-all shadow-sm group" title="Refine Identity">
                                <span class="material-symbols-rounded text-sm">edit</span>
                                <span class="text-[9px] font-black uppercase tracking-tighter hidden xl:block">Edit</span>
                            </a>
                            <button x-data @click="$dispatch('open-wallet-modal', { id: {{ $user->id }}, name: '{{ $user->username }}' })" class="h-8 px-3 rounded-xl bg-cyan-500 text-white flex items-center justify-center gap-2 hover:scale-105 transition-all shadow-sm group" title="Manage Balance">
                                <span class="material-symbols-rounded text-sm">account_balance_wallet</span>
                                <span class="text-[9px] font-black uppercase tracking-tighter hidden xl:block">Wallet</span>
                            </button>
                            @php
                                $activeOtt = $user->ottSubscriptions()->where('status', 1)->where('end_date', '>', now())->latest()->first();
                                $activeCreator = $user->purchasedPlans()->where('expired_date', '>', now())->latest()->first();
                                $ottId = $activeOtt->ott_plan_id ?? '';
                                $creatorId = $activeCreator->plan_id ?? '';
                                $ottExpire = $activeOtt && $activeOtt->end_date ? \Carbon\Carbon::parse($activeOtt->end_date)->format('M d, Y') : '';
                                $creatorExpire = $activeCreator && $activeCreator->expired_date ? \Carbon\Carbon::parse($activeCreator->expired_date)->format('M d, Y') : '';
                            @endphp
                            <button x-data @click="$dispatch('open-plan-modal', { id: {{ $user->id }}, name: '{{ $user->username }}', ottId: '{{ $ottId }}', creatorId: '{{ $creatorId }}', ottExpire: '{{ $ottExpire }}', creatorExpire: '{{ $creatorExpire }}' })" class="h-8 px-3 rounded-xl bg-orange-500 text-white flex items-center justify-center gap-2 hover:scale-105 transition-all shadow-sm group" title="Subscription Control">
                                <span class="material-symbols-rounded text-sm">workspace_premium</span>
                                <span class="text-[9px] font-black uppercase tracking-tighter hidden xl:block">Plan</span>
                            </button>
                             @if($user->status == 'active')
                            <button x-data @click="$dispatch('open-ban-modal', { id: {{ $user->id }}, name: '{{ $user->username }}' })" class="h-8 px-3 rounded-xl bg-rose-500 text-white flex items-center justify-center gap-2 hover:scale-105 transition-all shadow-sm group" title="Restrict Node">
                                <span class="material-symbols-rounded text-sm">block</span>
                                <span class="text-[9px] font-black uppercase tracking-tighter hidden xl:block">Block</span>
                            </button>
                            @else
                            <form action="{{ route('admin.users.status', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="h-8 px-3 rounded-xl bg-emerald-600 text-white flex items-center justify-center gap-2 hover:scale-105 transition-all shadow-sm group" title="Restore Node">
                                    <span class="material-symbols-rounded text-sm">check_circle</span>
                                    <span class="text-[9px] font-black uppercase tracking-tighter hidden xl:block">Unblock</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="py-20 text-center opacity-20 text-[11px] font-black uppercase tracking-widest" colspan="100%">No nodes detected in current segment.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Main Grid View (App View) -->
    <div class="px-4 sm:px-6 pb-6" x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mt-6">
            @forelse($users as $user)
            <div class="bg-white dark:bg-[#1a1a1a] rounded-[1.5rem] sm:rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden relative group">
                <!-- Top Header Decor -->
                <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-emerald-500 via-blue-500 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="p-5 sm:p-8">
                    <!-- Top Bar -->
                    <div class="flex justify-between items-start mb-6">
                        <label class="w-6 h-6 rounded-lg border-2 border-slate-200 dark:border-white/10 flex items-center justify-center cursor-pointer hover:border-emerald-500 transition-colors">
                            <input type="checkbox" class="bulk-select-item w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500" value="{{ $user->id }}">
                        </label>
                        <div class="flex items-center gap-2">
                             @if($user->monetization_status == 1)
                                <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center shadow-lg shadow-emerald-500/10" title="Verified Partner">
                                    <span class="material-symbols-rounded text-sm fill-1">verified</span>
                                </div>
                             @endif
                             <a href="{{ route('admin.users.login', $user->id) }}" target="_blank" class="h-10 px-4 rounded-2xl bg-orange-500/5 text-orange-500 border border-orange-500/10 flex items-center gap-2 hover:bg-orange-500 hover:text-white transition-all shadow-sm group/login " title="Secure Entry">
                                <span class="material-symbols-rounded text-lg group-hover/login:translate-x-1 transition-transform">login</span>
                                <span class="text-[8px] font-black uppercase tracking-widest">Login As</span>
                             </a>
                        </div>
                    </div>

                    <!-- Profile Section -->
                    <div class="flex items-center gap-6 relative">
                        <div class="relative group">
                            <div class="w-24 h-24 rounded-[1.8rem] overflow-hidden border-4 border-white dark:border-[#1a1a1a] shadow-xl relative bg-slate-100 dark:bg-white/5 flex items-center justify-center">
                                @if(@$user->channel->avatar)
                                    <img src="{{ getImage($user->channel->avatar) }}" class="w-full h-full object-cover">
                                @elseif($user->image)
                                    <img src="{{ getImage(getFilePath('userProfile').'/'.$user->image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-orange-500 to-amber-400 text-white text-3xl font-black uppercase ">
                                        {{ substr($user->firstname, 0, 1) }}{{ substr($user->lastname, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <!-- Dynamic Status Badge -->
                            <div class="absolute -bottom-1 -right-1 w-8 h-8 rounded-xl {{ $user->status == 'active' ? 'bg-emerald-500 shadow-emerald-500/30' : 'bg-rose-500 shadow-rose-500/30' }} text-white flex items-center justify-center shadow-lg border-2 border-white dark:border-[#121212]">
                                <span class="material-symbols-rounded text-sm">{{ $user->status == 'active' ? 'check_circle' : 'block' }}</span>
                            </div>
                        </div>

                        <div class="flex-grow min-w-0">
                            <!-- Status Badge -->
                            <div class="mb-3 flex gap-2">
                                @if($user->status == 'active')
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-500/10 text-emerald-500 text-[7px] font-black uppercase tracking-widest border border-emerald-500/20 flex items-center gap-1">
                                        <span class="w-1 h-1 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-md bg-rose-500/10 text-rose-500 text-[7px] font-black uppercase tracking-widest border border-rose-500/20 flex items-center gap-1">
                                        <span class="w-1 h-1 rounded-full bg-rose-500"></span>
                                        Banned
                                    </span>
                                @endif
                            </div>
                            <h4 class="text-lg font-black text-slate-900 dark:text-white truncate uppercase tracking-tighter mb-1">{{ $user->fullname }}</h4>
                            <div class="flex items-center gap-2">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">@ {{ $user->username }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Metadata Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                        <div class="bg-slate-50 dark:bg-white/[0.02] p-3 rounded-xl border border-slate-100 dark:border-white/5">
                            <span class="block text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Wallet Balance</span>
                            <span class="block text-[11px] font-black text-emerald-500 ">{{ showAmount($user->balance) }}</span>
                        </div>
                        <div class="bg-slate-50 dark:bg-white/[0.02] p-3 rounded-xl border border-slate-100 dark:border-white/5">
                            <span class="block text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Joined Date</span>
                            <span class="block text-[9px] font-black text-slate-700 dark:text-white/60 ">{{ $user->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <!-- Action Grid -->
                    <div class="mt-8 pt-8 border-t border-slate-100 dark:border-white/5 grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <a href="{{ route('admin.users.detail', $user->id) }}" class="h-14 rounded-2xl bg-emerald-500/5 text-emerald-500 border border-emerald-500/10 flex flex-col items-center justify-center gap-1 hover:bg-emerald-500 hover:text-white transition-all shadow-sm group/btn" title="View Profile">
                            <span class="material-symbols-rounded text-lg group-hover/btn:scale-110 transition-transform">visibility</span>
                            <span class="text-[7px] font-black uppercase tracking-tighter">Details</span>
                        </a>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="h-14 rounded-2xl bg-blue-500/5 text-blue-500 border border-blue-500/10 flex flex-col items-center justify-center gap-1 hover:bg-blue-500 hover:text-white transition-all shadow-sm group/btn" title="Edit Profile">
                            <span class="material-symbols-rounded text-lg group-hover/btn:scale-110 transition-transform">edit</span>
                            <span class="text-[7px] font-black uppercase tracking-tighter">Edit</span>
                        </a>
                        <button x-data @click="$dispatch('open-wallet-modal', { id: {{ $user->id }}, name: '{{ $user->username }}' })" class="h-14 rounded-2xl bg-cyan-500/5 text-cyan-500 border border-cyan-500/10 flex flex-col items-center justify-center gap-1 hover:bg-cyan-500 hover:text-white transition-all shadow-sm group/btn" title="Manage Wallet">
                            <span class="material-symbols-rounded text-lg group-hover/btn:scale-110 transition-transform">account_balance_wallet</span>
                            <span class="text-[7px] font-black uppercase tracking-tighter">Wallet</span>
                        </button>
                        @php
                            $activeOtt = $user->ottSubscriptions()->where('status', 1)->where('end_date', '>', now())->latest()->first();
                            $activeCreator = $user->purchasedPlans()->where('expired_date', '>', now())->latest()->first();
                            $ottId = $activeOtt->ott_plan_id ?? '';
                            $creatorId = $activeCreator->plan_id ?? '';
                            $ottExpire = $activeOtt && $activeOtt->end_date ? \Carbon\Carbon::parse($activeOtt->end_date)->format('M d, Y') : '';
                            $creatorExpire = $activeCreator && $activeCreator->expired_date ? \Carbon\Carbon::parse($activeCreator->expired_date)->format('M d, Y') : '';
                        @endphp
                        <button x-data @click="$dispatch('open-plan-modal', { id: {{ $user->id }}, name: '{{ $user->username }}', ottId: '{{ $ottId }}', creatorId: '{{ $creatorId }}', ottExpire: '{{ $ottExpire }}', creatorExpire: '{{ $creatorExpire }}' })" class="h-14 rounded-2xl bg-orange-500/5 text-orange-500 border border-orange-500/10 flex flex-col items-center justify-center gap-1 hover:bg-orange-500 hover:text-white transition-all shadow-sm group/btn" title="Plan Control">
                            <span class="material-symbols-rounded text-lg group-hover/btn:scale-110 transition-transform">workspace_premium</span>
                            <span class="text-[7px] font-black uppercase tracking-tighter">Plan</span>
                        </button>
                        @if($user->status == 'active')
                        <button x-data @click="$dispatch('open-ban-modal', { id: {{ $user->id }}, name: '{{ $user->username }}' })" class="h-14 rounded-2xl bg-rose-50/5 text-rose-500 border border-rose-500/10 flex flex-col items-center justify-center gap-1 hover:bg-rose-500 hover:text-white transition-all shadow-sm group/btn" title="Block User">
                            <span class="material-symbols-rounded text-lg group-hover/btn:scale-110 transition-transform">block</span>
                            <span class="text-[7px] font-black uppercase tracking-tighter">Block</span>
                        </button>
                        @else
                        <form action="{{ route('admin.users.status', $user->id) }}" method="POST" class="h-14">
                            @csrf
                            <button type="submit" class="w-full h-full rounded-2xl bg-emerald-500/5 text-emerald-500 border border-emerald-500/10 flex flex-col items-center justify-center gap-1 hover:bg-emerald-500 hover:text-white transition-all shadow-sm group/btn" title="Unblock User">
                                <span class="material-symbols-rounded text-lg group-hover/btn:scale-110 transition-transform">check_circle</span>
                                <span class="text-[7px] font-black uppercase tracking-tighter">Unblock</span>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-40 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[3.5rem]">
                 <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-6xl">person_off</span>
                 <p class="mt-6 text-[11px] font-black text-slate-400 uppercase tracking-widest ">No active nodes detected.</p>
            </div>
            @endforelse
        </div>
    </div>

    <div class="p-8 border-t border-slate-100 dark:border-white/10 bg-slate-50/50 dark:bg-white/[0.01]">
        {{ $users->links() }}
    </div>
</div>

@include('admin.users.partials.modals')
@endsection

