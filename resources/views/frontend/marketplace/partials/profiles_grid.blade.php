@forelse($marketplaces as $item)
    @include('frontend.marketplace.partials.creator_card', ['item' => $item])
@empty
    <div class="col-span-full py-40 text-center">
        <div class="w-32 h-32 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-10">
            <span class="material-symbols-rounded text-6xl text-gray-300">search_off</span>
        </div>
        <h3 class="text-2xl font-black dark:text-white uppercase mb-4">No Results Found</h3>
        <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Try adjusting your filters or search terms</p>
        <button @click="resetFilters()" class="inline-block mt-8 text-indigo-600 font-black uppercase text-[10px] tracking-widest border-b-2 border-indigo-600 pb-1">Reset Filters</button>
    </div>
@endforelse
