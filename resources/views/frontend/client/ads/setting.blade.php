<x-app-layout>
    <div class="py-10 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen" x-data="adSettings()">
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="mb-12">
                <a href="{{ route('studio.video.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 hover:text-red-600 transition-colors">
                    <span class="material-symbols-rounded text-lg">arrow_back</span>
                    Back to Content
                </a>
                <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Ad Timing Setup</h1>
                <p class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2">{{ $video?->title ?? 'N/A' }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <!-- Video Preview -->
                <div class="space-y-6">
                    <div class="bg-black rounded-[2.5rem] overflow-hidden shadow-2xl aspect-video relative group">
                        <video id="adSetupPlayer" class="w-full h-full" controls crossorigin playsinline
                               poster="{{ getImage(getFilePath('thumbnail') . '/' . $video->thumb_image) }}">
                            @foreach ($video?->videoFiles ?? [] as $file)
                                <source src="{{ getVideo($file->file_name, $video) }}" type="video/mp4" size="{{ $file->quality }}" />
                            @endforeach
                        </video>
                    </div>

                    <div class="bg-white dark:bg-[#1A1A1A] p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-sm">
                        <div class="flex items-center gap-4 mb-6 p-4 bg-amber-500/10 border border-amber-500/10 rounded-2xl">
                            <span class="material-symbols-rounded text-amber-500">info</span>
                            <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest leading-relaxed">Play the video and pause at the exact moment you want an ad to appear, then click "Add Ad Break".</p>
                        </div>
                        <button @click="addDuration" class="w-full py-5 bg-gray-900 dark:bg-white text-white dark:text-black rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:opacity-90 transition-all active:scale-95 flex items-center justify-center gap-3">
                            <span class="material-symbols-rounded">add_circle</span>
                            Add Ad Break
                        </button>
                    </div>
                </div>

                <!-- Ad Timings List -->
                <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] p-10 border border-gray-100 dark:border-white/5 shadow-sm h-fit">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-widest mb-8 flex items-center gap-3">
                        <span class="material-symbols-rounded text-red-600">schedule</span>
                        Configured Breaks
                    </h3>

                    <form action="{{ route('user.ad.play.duration', $video->slug) }}" method="POST">
                        @csrf
                        <div class="space-y-4 mb-10">
                            <template x-for="(d, index) in durations" :key="index">
                                <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-white/2 rounded-2xl border border-gray-100 dark:border-white/5 group">
                                    <div class="w-10 h-10 rounded-xl bg-white dark:bg-white/5 flex items-center justify-center">
                                        <span class="material-symbols-rounded text-gray-400 text-lg">timer</span>
                                    </div>
                                    <div class="flex-grow">
                                        <input type="text" name="play_durations[]" :value="d" readonly class="text-sm font-black text-gray-900 dark:text-white bg-transparent border-none outline-none w-full cursor-default">
                                    </div>
                                    <button type="button" @click="removeDuration(index)" class="w-8 h-8 rounded-lg text-gray-400 hover:bg-red-500/10 hover:text-red-500 transition-all flex items-center justify-center">
                                        <span class="material-symbols-rounded text-lg">close</span>
                                    </button>
                                </div>
                            </template>

                            <div x-show="durations.length === 0" class="py-12 text-center border-2 border-dashed border-gray-100 dark:border-white/5 rounded-[2rem]">
                                <span class="material-symbols-rounded text-4xl text-gray-200 dark:text-white/5 mb-3">timer_off</span>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">No ad breaks added yet</p>
                            </div>
                        </div>

                        <div x-show="durations.length > 0">
                            <button type="submit" class="w-full py-4 bg-red-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-red-500/20 hover:bg-red-700 transition-all active:scale-95">Save Timings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('style-lib')
        <link href="{{ asset('assets/global/css/plyr.css') }}" rel="stylesheet">
    @endpush

    @push('script-lib')
        <script src="{{ asset('assets/global/js/plyr.js') }}"></script>
    @endpush

    @push('script')
    <script>
        function adSettings() {
            return {
                durations: @json($video?->adPlayDurations?->pluck('play_duration')?->map(fn($d) => round($d/60, 2)) ?? []),
                player: null,
                adminInterval: parseInt("{{ gs('ad_config')?->per_minute ?? 1 }}"),
                adsPerInterval: parseInt("{{ gs('ad_config')?->ad_views ?? 1 }}"),

                init() {
                    this.player = new Plyr('#adSetupPlayer', {
                        controls: ['play-large', 'play', 'progress', 'current-time', 'duration', 'settings'],
                        ratio: '16:9'
                    });
                },

                addDuration() {
                    const currentTime = this.player.currentTime;
                    const minutes = Math.floor(currentTime / 60);
                    const seconds = (currentTime % 60).toFixed(0);
                    const formatted = `${minutes}.${seconds.padStart(2, '0')}`;
                    
                    // Validation logic from legacy
                    const intervalBlock = Math.floor(minutes / this.adminInterval);
                    const adsInBlock = this.durations.filter(d => {
                        const m = Math.floor(parseFloat(d));
                        return Math.floor(m / this.adminInterval) === intervalBlock;
                    }).length;

                    if (adsInBlock < this.adsPerInterval) {
                        this.durations.push(formatted);
                    } else {
                        notify('error', 'Maximum ads reached for this time interval');
                    }
                },

                removeDuration(index) {
                    this.durations.splice(index, 1);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
