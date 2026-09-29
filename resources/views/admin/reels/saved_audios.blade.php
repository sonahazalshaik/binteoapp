@extends('admin.layouts.app')
@section('title', 'Saved Audios')
@section('header_title', 'Saved Audio Management')

@section('content')
<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] shadow-sm transition-all duration-300">
    @include('admin.components.header-toolbar', [
        'title' => $pageTitle,
        'items' => $savedAudios,
        'createRoute' => null
    ])

    <div class="px-6 pt-6">
        @include('admin.components.table-toolbar', ['showBulkActions' => false])
    </div>

    <div class="overflow-x-auto scrollbar-hide px-6">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Audio Track')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('User / Creator')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">@lang('Saved At')</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right">@lang('Action')</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($savedAudios as $saved)
                    @php $music = $saved->reelMusic; @endphp
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-white/5 flex items-center justify-center overflow-hidden shrink-0">
                                    @if($music->cover_image)
                                        <img src="{{ getImage(getFilePath('reelMusic') . '/' . $music->cover_image) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="material-symbols-rounded text-slate-400">music_note</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-slate-900 dark:text-white uppercase tracking-tight">{{ $music->title }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $music->artist ?? 'Original Audio' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-sm text-slate-900 dark:text-white">{{ $saved->user->fullname }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ '@' . $saved->user->username }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold text-slate-500">{{ $saved->created_at->format('M d, Y H:i') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.reels.saved-audios.destroy', $saved->id) }}" data-method="DELETE" data-question="@lang('Remove this saved audio entry?')">
                                <span class="material-symbols-rounded text-sm">delete</span>@lang('Remove')
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td class="text-muted text-center py-10" colspan="100%">@lang('No saved audio entries found')</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($savedAudios->hasPages())
        <div class="card-footer py-4 px-6 border-t border-slate-100 dark:border-white/5">{{ paginateLinks($savedAudios) }}</div>
    @endif
</div>

<x-confirmation-modal />
@endsection
