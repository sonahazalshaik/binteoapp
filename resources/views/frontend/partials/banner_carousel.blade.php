@if(isset($banners) && $banners->count() > 0)
<div x-data="{ 
    active: 0, 
    count: {{ $banners->count() }},
    progress: 0,
    timer: null,
    start() {
        if (this.timer) clearInterval(this.timer);
        this.timer = setInterval(() => {
            this.progress += 1;
            if (this.progress >= 100) {
                this.active = (this.active + 1) % this.count;
                this.progress = 0;
            }
        }, 50);
    },
    init() {
        if (this.count > 1) this.start();
    },
    next() { this.active = (this.active + 1) % this.count; this.start(); },
    prev() { this.active = (this.active - 1 + this.count) % this.count; this.start(); },
    goto(i) { this.active = i; this.start(); }
}" class="w-full relative group">

    <!-- Sponsored Badge -->
    <div class="absolute top-2 left-2 z-10 bg-black/60 backdrop-blur-md px-2 py-1 rounded text-[8px] font-black text-white uppercase tracking-[0.2em] flex items-center gap-1 border border-white/10">
        <span class="material-symbols-rounded text-[10px]">ads_click</span> Sponsored
    </div>

    <div class="relative aspect-[3/1] rounded-xl overflow-hidden border border-gray-100 dark:border-white/5 shadow-sm">
        <div class="flex h-full transition-transform duration-700 ease-in-out" :style="`width: ${count * 100}%; transform: translateX(-${active * (100 / count)}%)`">
            @foreach($banners as $index => $banner)
                <div class="h-full flex-shrink-0 relative group/item" style="width: {{ 100 / $banners->count() }}%">
                    <a href="{{ route('banner.redirect', $banner->slug) }}" target="_blank" class="block w-full h-full">
                        <img src="{{ getImage($banner->image) }}" alt="{{ $banner->title ?? 'Ad' }}" class="w-full h-full object-cover">
                    </a>
                    <a href="{{ route('banner.redirect', $banner->slug) }}" target="_blank" class="absolute bottom-2 right-2 bg-black/40 hover:bg-black/60 backdrop-blur-sm text-[8px] text-white px-2 py-1 rounded-md transition-all flex items-center gap-1 z-20">
                        <span class="material-symbols-rounded text-[10px]">open_in_new</span> Visit
                    </a>
                </div>
            @endforeach
        </div>


    </div>

    <!-- Indicators -->
    @if($banners->count() > 1)
        <div class="flex gap-2 mt-3 justify-center px-4">
            @foreach($banners as $index => $banner)
                <button @click="goto({{ $index }})" 
                        class="flex-1 h-1.5 rounded-full bg-gray-200 dark:bg-white/10 overflow-hidden relative transition-all duration-300"
                        :class="active === {{ $index }} ? 'opacity-100' : 'opacity-40'">
                    <div class="absolute inset-y-0 left-0 bg-orange-500 transition-all duration-100 ease-linear"
                         :style="active === {{ $index }} ? `width: ${progress}%` : 'width: 0%'"></div>
                </button>
            @endforeach
        </div>
    @endif
</div>
@endif
