@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F9F9F9] dark:bg-[#0F0F0F] transition-colors duration-500">
    <div class="max-w-[1600px] mx-auto px-6 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between mb-12">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase leading-none">Purchased Assets</h1>
                <p class="text-slate-500 font-medium mt-3 text-sm uppercase tracking-widest opacity-60">Manage your exclusive premium video library</p>
            </div>
            <div class="w-16 h-16 rounded-3xl bg-orange-500/10 flex items-center justify-center text-orange-500 shadow-xl shadow-orange-500/5">
                <span class="material-symbols-rounded text-3xl">workspace_premium</span>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse($purchasedVideos as $purchase)
                @php $video = $purchase->video; @endphp
                <div class="group bg-white dark:bg-white/5 rounded-[2.5rem] border border-slate-100 dark:border-white/10 shadow-sm overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                    <a href="{{ route('videos.show', $video) }}" class="block relative aspect-video overflow-hidden">
                        @if($video->thumbnail_path)
                            <img src="{{ $video->getThumbnailUrl() }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-slate-100 dark:bg-black/40 flex items-center justify-center">
                                <span class="material-symbols-rounded text-4xl text-slate-300">movie</span>
                            </div>
                        @endif
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 transform scale-50 group-hover:scale-100 transition-transform">
                                <span class="material-symbols-rounded text-white text-3xl material-symbols-filled">play_arrow</span>
                            </div>
                        </div>

                        <div class="absolute top-4 left-4 gradient-orange text-white text-[8px] px-3 py-1.5 rounded-full font-black uppercase tracking-widest shadow-lg">
                            Owned
                        </div>
                    </a>

                    <div class="p-6">
                        <h4 class="font-black text-slate-900 dark:text-white truncate mb-2 group-hover:text-orange-500 transition-colors">{{ $video->title }}</h4>
                        <div class="flex items-center gap-2 mb-6">
                            <div class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center text-white text-[8px] font-black uppercase">
                                {{ substr($video->user->name, 0, 1) }}
                            </div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $video->user->name }}</span>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t dark:border-white/5">
                            <div class="flex flex-col">
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Purchased On</span>
                                <span class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-tighter">{{ $purchase->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Order Ref</span>
                                <span class="text-[10px] font-black text-orange-500 uppercase tracking-tighter">#{{ substr($purchase->trx, -8) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-40 text-center">
                    <div class="w-32 h-32 rounded-[3rem] bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-200 dark:text-white/5 mb-8">
                        <span class="material-symbols-rounded text-6xl">shopping_cart_off</span>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-3 tracking-tighter uppercase ">No Purchases Yet</h3>
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-widest max-w-sm mb-10 opacity-60">Unlock exclusive premium content from your favorite creators and start building your collection.</p>
                    <a href="{{ route('home') }}" class="px-10 py-5 gradient-orange text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-orange-500/20 hover:scale-105 active:scale-95 transition-all">Explore Marketplace</a>
                </div>
            @endforelse
        </div>

        @if($purchasedVideos->hasPages())
            <div class="mt-16">
                {{ $purchasedVideos->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

