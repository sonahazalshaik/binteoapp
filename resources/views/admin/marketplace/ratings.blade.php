@extends('admin.layouts.app')

@section('title', 'Marketplace Ratings')
@section('header_title', 'Marketplace Ratings')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-700" x-data="{ showAddModal: false }">
    
    <!-- Total Rating Stats Breakdown -->
    <div class="bg-white dark:bg-[#121212] p-6 lg:p-8 rounded-3xl border border-slate-200 dark:border-white/10 shadow-lg max-w-3xl">
        <div class="flex flex-col md:flex-row items-start md:items-center gap-8 w-full">
            <!-- Left: Stars + Score -->
            <div class="shrink-0">
                <div class="text-[13px] font-bold text-slate-900 dark:text-white mb-1.5 uppercase tracking-widest">Platform Average</div>
                <div class="flex items-center gap-1 mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="material-symbols-rounded text-[22px] {{ $i <= round($avgRating) ? 'text-yellow-400 drop-shadow-md' : 'text-slate-300 dark:text-white/20' }}">star</span>
                    @endfor
                </div>
                <div class="flex items-baseline gap-2">
                    <div class="text-3xl font-black text-slate-900 dark:text-white">{{ number_format($avgRating, 1) }}</div>
                    <span class="text-slate-400 font-bold text-sm">/ 5</span>
                    <span class="text-[11px] text-slate-500 font-bold uppercase tracking-widest ml-1.5 bg-slate-50 dark:bg-black/20 px-2 py-1 rounded shadow-sm border border-slate-100 dark:border-white/5">{{ $totalRatingsCount }} entries</span>
                </div>
            </div>

            <!-- Right: Breakdown bars -->
            <div class="flex-1 border-t md:border-t-0 md:border-l border-slate-200 dark:border-white/10 pt-4 md:pt-0 md:pl-8 space-y-2.5 w-full">
                @foreach([5, 4, 3, 2, 1] as $star)
                    @php 
                        $percentage = $totalRatingsCount > 0 ? ($breakdown[$star] / $totalRatingsCount) * 100 : 0;
                    @endphp
                    <div class="flex items-center gap-2 text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-widest">
                        <div class="w-4 text-right">{{ $star }}</div>
                        <span class="material-symbols-rounded text-[11px] text-yellow-400">star</span>
                        <div class="w-full h-2 bg-slate-100 dark:bg-white/5 rounded-full overflow-hidden" style="min-width: 100px;">
                            <div class="h-full bg-yellow-400 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                        </div>
                        <div class="w-6 text-left text-[10px] text-slate-500">{{ $breakdown[$star] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl transition-all duration-500">
        @include('admin.components.header-toolbar', [
            'title' => 'Marketplace Ratings Management',
            'items' => $ratings,
            'createRoute' => route('admin.marketplace.ratings.create'),
            'createLabel' => 'Add New Rating'
        ])

        <div class="px-6 pt-6">
            @include('admin.components.table-toolbar')
        </div>

        <!-- Desktop Table View -->
        <div x-show="$store.viewMode && $store.viewMode.mode === 'table'" x-cloak class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-white/[0.02]">
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30">User</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30">Talent</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30 text-center">Rating</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30">Review</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 dark:text-white/30 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($ratings as $index => $item)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-white/[0.01] transition-all duration-300 group">
                        <td class="px-8 py-6">
                            @if($item->user)
                                <a href="{{ route('admin.users.detail', $item->user->id) }}" class="flex items-center gap-5 group/user">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-white/5 overflow-hidden border border-slate-200 dark:border-white/10 shrink-0 group-hover/user:border-indigo-500 transition-colors">
                                        @if($item->user?->image)
                                            <img src="{{ getImage(getFilePath('userProfile').'/'.$item->user->image, getFileSize('userProfile')) }}" alt="User" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-indigo-500/10 text-indigo-500 font-black text-sm uppercase">{{ strtoupper(substr($item->user->fullname ?? 'U', 0, 1)) }}</div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-[13px] font-bold text-slate-900 dark:text-white tracking-tight uppercase leading-none mb-1 group-hover/user:text-indigo-500 transition-colors">{{ $item->user->fullname ?? 'Unknown' }}</p>
                                        <p class="text-[9px] font-bold text-slate-400 dark:text-white/30 tracking-widest uppercase">{{ $item->user->username ?? '' }}</p>
                                    </div>
                                </a>
                            @else
                                <span class="text-[13px] font-black text-slate-500">Admin</span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-5">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 overflow-hidden border border-slate-200 dark:border-white/10 shrink-0">
                                    @if($item->marketplace?->image)
                                        <img src="{{ $item->marketplace?->photoUrl() ?? '' }}" alt="Talent" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center font-black text-sm uppercase text-white" style="background: {{ $item->marketplace ? $item->marketplace->getAvatarColor() : '#64748b' }}">{{ $item->marketplace ? $item->marketplace->getInitials() : '?' }}</div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[13px] font-bold text-slate-900 dark:text-white tracking-tight uppercase leading-none mb-1">{{ $item->marketplace?->name ?? 'Deleted Talent' }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 dark:text-white/30 tracking-widest uppercase">{{ $item->marketplace?->type ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex flex-col items-center gap-1">
                                <div class="flex items-center gap-0.5 text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $item->rating)
                                            <span class="material-symbols-rounded text-sm">star</span>
                                        @else
                                            <span class="material-symbols-rounded text-sm text-slate-300 dark:text-white/10">star</span>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-[10px] font-black text-slate-500 dark:text-white/50 tracking-widest">{{ $item->rating }}.0</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-[11px] font-medium text-slate-500 dark:text-white/60 line-clamp-2 max-w-[250px]" title="{{ $item->review }}">
                                {{ $item->review ?? 'No review text provided.' }}
                            </p>
                            <p class="text-[8px] font-bold text-slate-400 dark:text-white/30 uppercase tracking-widest mt-2">{{ showDateTime($item->created_at, 'M d, Y') }}</p>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-3">
                                 <a href="{{ route('admin.marketplace.ratings.edit', $item->id ?? 0) }}" class="h-10 px-6 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/30 flex items-center gap-2 text-[9px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all active:scale-90 border border-transparent shadow-sm" {{ !isset($item->id) ? 'disabled' : '' }}>
                                    <span class="material-symbols-rounded text-lg">settings_suggest</span>
                                    Edit
                                 </a>
                                 <form action="{{ route('admin.marketplace.ratings.delete', $item->id ?? 0) }}" method="POST" data-swal-question="Immediately delete this rating?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-10 h-10 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all active:scale-90 shadow-sm" {{ !isset($item->id) ? 'disabled' : '' }}>
                                        <span class="material-symbols-rounded text-xl">delete_forever</span>
                                    </button>
                                 </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-10 py-40 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[2rem] m-8">
                            <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-6xl">star_half</span>
                            <p class="mt-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.5em] opacity-30">{{ $emptyMessage ?? 'No Ratings Found' }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- App View (Grid Mode) -->
        <div x-show="!$store.viewMode || $store.viewMode.mode === 'app'" x-cloak class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                @forelse($ratings as $index => $item)
                <div class="bg-slate-50/50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-[2rem] p-6 group hover:border-indigo-500/50 transition-all duration-500 shadow-sm hover:shadow-2xl flex flex-col h-full">
                    <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-200 dark:border-white/10">
                        <div class="flex items-center gap-3">
                            @if($item->user)
                                <a href="{{ route('admin.users.detail', $item->user->id) }}" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-white/5 overflow-hidden border border-slate-200 dark:border-white/10 shrink-0 hover:border-indigo-500 transition-colors block">
                                    @if($item->user?->image)
                                        <img src="{{ getImage(getFilePath('userProfile').'/'.$item->user->image, getFileSize('userProfile')) }}" alt="User" class="w-full h-full object-cover">
                                    @else
                                        <span class="w-full h-full flex items-center justify-center bg-indigo-500/10 text-indigo-500 font-black text-xs uppercase">{{ strtoupper(substr($item->user->fullname ?? 'U', 0, 1)) }}</span>
                                    @endif
                                </a>
                                <div>
                                    <a href="{{ route('admin.users.detail', $item->user->id) }}" class="text-[12px] font-bold text-slate-900 dark:text-white uppercase leading-none hover:text-indigo-500 transition-colors block">{{ $item->user->fullname ?? 'Unknown' }}</a>
                                    <p class="text-[8px] text-slate-400 uppercase mt-0.5">{{ showDateTime($item->created_at, 'M d, Y') }}</p>
                                </div>
                            @else
                                <div class="w-8 h-8 rounded-full bg-indigo-500/10 text-indigo-500 flex items-center justify-center font-black text-xs">A</div>
                                <div>
                                    <p class="text-[12px] font-bold text-slate-900 dark:text-white uppercase leading-none">Admin Entry</p>
                                    <p class="text-[8px] text-slate-400 uppercase mt-0.5">{{ showDateTime($item->created_at, 'M d, Y') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="flex gap-1 text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="material-symbols-rounded text-sm {{ $i <= $item->rating ? '' : 'text-slate-300 dark:text-white/10' }}">star</span>
                            @endfor
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 mb-4 bg-white dark:bg-white/5 p-3 rounded-xl border border-slate-100 dark:border-white/10">
                        @if($item->marketplace?->image)
                            <img src="{{ $item->marketplace?->photoUrl() ?? '' }}" alt="Talent" class="w-10 h-10 rounded-lg object-cover">
                        @else
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center font-black text-sm uppercase text-white shrink-0" style="background: {{ $item->marketplace ? $item->marketplace->getAvatarColor() : '#64748b' }}">{{ $item->marketplace ? $item->marketplace->getInitials() : '?' }}</div>
                        @endif
                        <div>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-0.5">Rated Talent</p>
                            <p class="text-[13px] font-bold text-slate-900 dark:text-white uppercase leading-none">{{ $item->marketplace?->name ?? 'Deleted' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex-1 mb-6">
                        <p class="text-sm text-slate-600 dark:text-white/60 ">"{{ $item->review ?? 'No review text provided.' }}"</p>
                    </div>

                    <div class="mt-auto pt-4 border-t border-slate-100 dark:border-white/5 flex justify-end gap-2">
                        <a href="{{ route('admin.marketplace.ratings.edit', $item->id ?? 0) }}" class="w-10 h-10 rounded-xl bg-white dark:bg-white/5 text-slate-400 hover:text-indigo-500 flex items-center justify-center transition-all border border-slate-100 dark:border-white/10" {{ !isset($item->id) ? 'disabled' : '' }}>
                            <span class="material-symbols-rounded text-lg">edit</span>
                        </a>
                        <form action="{{ route('admin.marketplace.ratings.delete', $item->id ?? 0) }}" method="POST" data-swal-question="Delete this rating?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-10 h-10 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm" {{ !isset($item->id) ? 'disabled' : '' }}>
                                <span class="material-symbols-rounded text-xl">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-32 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[3rem]">
                    <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-6xl">star_half</span>
                    <p class="mt-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.5em] ">No Ratings Found</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="px-8 pb-8">
            @if($ratings->hasPages())
                {{ paginateLinks($ratings) }}
            @endif
        </div>
    </div>
</div>
@endsection

