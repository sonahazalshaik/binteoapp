@extends('admin.layouts.app')

@section('title', 'Marketplace Inquiries')
@section('header_title', 'Inquiries')

@section('content')
<div class="max-w-7xl mx-auto animate-in fade-in slide-in-from-bottom-8 duration-700">
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl transition-all duration-500">
        @include('admin.components.header-toolbar', [
            'title' => 'Contact Inquiries',
            'items' => $contacts ?? collect(),
            'createRoute' => route('admin.marketplace.contacts.create'),
            'createLabel' => 'Add Inquiry'
        ])

        <div class="px-6 pt-6">
            @include('admin.components.table-toolbar')
        </div>

    <!-- Main Grid View -->
    <div class="px-6 pb-20">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            @forelse($contacts as $item)
            <div class="bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden relative group">
                <!-- Top Header Decor -->
                <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-emerald-500 via-cyan-500 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="p-8">
                    <!-- Top Bar -->
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-6 h-6 rounded-lg border-2 border-slate-200 dark:border-white/10 flex items-center justify-center">
                            <span class="material-symbols-rounded text-emerald-500 text-sm opacity-0 group-hover:opacity-100">mark_email_read</span>
                        </div>
                         <div class="px-3 py-1 rounded-lg bg-orange-500/10 text-orange-500 text-[8px] font-black uppercase tracking-widest border border-orange-500/10">TALENT INQUIRY</div>
                    </div>

                    <!-- Middle Section: Visual and Info -->
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 sm:gap-8 text-center sm:text-left mb-8">
                        <!-- Sender Icon -->
                        <div class="relative flex-shrink-0">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-[1.5rem] p-1 bg-gradient-to-tr from-slate-200 to-slate-100 dark:from-white/10 dark:to-white/5 shadow-inner">
                                <div class="w-full h-full rounded-[1.2rem] overflow-hidden border-4 border-white dark:border-[#1a1a1a] shadow-xl relative bg-emerald-500/5 flex items-center justify-center">
                                    <span class="material-symbols-rounded text-emerald-500 text-3xl sm:text-4xl">contact_mail</span>
                                </div>
                            </div>
                        </div>

                        <!-- Data Section -->
                        <div class="flex-grow min-w-0 w-full">
                            <h4 class="text-lg font-black text-slate-900 dark:text-white truncate uppercase tracking-tighter leading-none mb-2 group-hover:text-emerald-500 transition-colors">{{ $item->name }}</h4>
                             <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mb-4">
                                <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-white/5 text-[9px] font-black text-slate-500 dark:text-white/30 uppercase tracking-widest max-w-full truncate">{{ $item->email }}</span>
                            </div>

                            <div class="p-4 bg-slate-50 dark:bg-white/[0.02] rounded-2xl border border-slate-100 dark:border-white/5">
                                <span class="block text-[7px] text-slate-400 font-black uppercase tracking-widest leading-none mb-2">Subject Signal</span>
                                <span class="block text-[11px] font-black text-slate-800 dark:text-white leading-tight uppercase tracking-tight">{{ $item->subject }}</span>
                                <div class="mt-3 pt-3 border-t border-slate-100 dark:border-white/5">
                                    <span class="block text-[7px] text-slate-400 font-black uppercase tracking-widest leading-none mb-2">Message Body</span>
                                    <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 line-clamp-2 leading-relaxed">{{ $item->message }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Core Operations Row -->
                    <div class="grid grid-cols-2 gap-3">
                         <a href="{{ route('admin.marketplace.contacts.show', $item->id) }}" class="h-12 rounded-2xl bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-white/40 flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all shadow-sm ">
                            <span class="material-symbols-rounded text-lg">visibility</span> View
                        </a>
                        <a href="{{ route('admin.marketplace.contacts.edit', $item->id) }}" class="h-12 rounded-2xl bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-white/40 flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest hover:bg-amber-500 hover:text-white transition-all shadow-sm ">
                            <span class="material-symbols-rounded text-lg">edit_note</span> Edit
                        </a>
                         <form action="{{ route('admin.marketplace.contacts.destroy', $item->id) }}" method="POST" class="col-span-2" data-swal-question="Immediately delete this inquiry?">
                             @csrf
                             @method('DELETE')
                             <button type="submit" class="w-full h-12 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all shadow-sm text-[9px] font-black uppercase tracking-widest ">
                                <span class="material-symbols-rounded text-lg mr-2">delete</span> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-40 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[3.5rem]">
                 <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-6xl">chat_bubble_outline</span>
                 <p class="mt-6 text-[11px] font-black text-slate-400 uppercase tracking-widest opacity-40">No communication signals detected in archives</p>
            </div>
            @endforelse
        </div>

        @if($contacts->hasPages())
        <div class="mt-12 p-8 border-t border-slate-100 dark:border-white/10 bg-slate-50/50 dark:bg-white/[0.01]">
            {{ $contacts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

