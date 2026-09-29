<x-app-layout>
    <div class="bg-white dark:bg-[#0F0F0F] min-h-screen flex items-start transition-colors duration-500" x-data="{ 
        videoPreview: null,
        isUploading: false,
        progress: 0,
        isProcessing: false,
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.videoPreview = URL.createObjectURL(file);
            }
        },
        submitForm(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();

            this.isUploading = true;
            this.progress = 0;

            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    this.progress = Math.round((e.loaded * 100) / e.total);
                    if (this.progress === 100) {
                        this.isProcessing = true;
                    }
                }
            });

            xhr.onreadystatechange = () => {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200 || xhr.status === 201) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            window.location.href = response.redirect;
                        } catch (e) {
                            window.location.href = '{{ route('home') }}';
                        }
                    } else {
                        alert('Upload failed. Please try again.');
                        this.isUploading = false;
                        this.isProcessing = false;
                    }
                }
            };

            xhr.open('POST', form.action);
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.send(formData);
        }
    }">
        <div class="max-w-3xl mx-auto px-3 sm:px-4 w-full">
            <div class="bg-white dark:bg-[#1A1A1A] rounded-[1rem] md:rounded-[1.25rem] shadow-[0_10px_25px_-8px_rgba(0,0,0,0.06)] dark:shadow-none border border-gray-100 dark:border-white/5 overflow-hidden transition-all duration-500">
                <div class="flex flex-col md:flex-row">
                    <!-- Preview Section (Premium App style) -->
                    <div class="md:w-[30%] bg-gray-50 dark:bg-white/2 p-3 md:p-4 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-gray-100 dark:border-white/5 relative">
                        <template x-if="!videoPreview">
                            <div class="text-center group">
                                <div class="w-8 h-8 md:w-10 md:h-10 bg-white dark:bg-[#1A1A1A] rounded-[0.5rem] md:rounded-[0.75rem] shadow-md dark:shadow-none border border-gray-50 dark:border-white/5 flex items-center justify-center mx-auto mb-2 transition-transform group-hover:scale-110 duration-500">
                                    <svg class="w-3 h-3 md:w-4 md:h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xs md:text-sm font-black text-gray-900 dark:text-white leading-tight">Video Content</h3>
                                <p class="text-gray-400 dark:text-gray-600 text-[7px] font-black uppercase tracking-[0.2em] mt-0.5">Studio Preview</p>
                            </div>
                        </template>
                        <template x-if="videoPreview">
                            <div class="w-full h-full flex flex-col justify-center">
                                <video :src="videoPreview" controls class="w-full rounded-[0.75rem] md:rounded-[1rem] shadow-md dark:shadow-none border border-white dark:border-[#2D2D2D] bg-black"></video>
                                <button @click="videoPreview = null; $refs.videoInput.value = ''" class="mt-2 text-[8px] font-black text-red-600 uppercase tracking-widest hover:text-red-700 transition-colors text-center">Change Video</button>
                            </div>
                        </template>

                        <!-- Uploading/Processing Overlay -->
                        <div x-show="isUploading" x-transition class="absolute inset-0 bg-white/95 dark:bg-[#1A1A1A]/95 backdrop-blur-xl z-20 flex flex-col items-center justify-center p-6 md:p-8 text-center" style="display: none;">
                            <template x-if="!isProcessing">
                                <div class="w-full max-w-[200px]">
                                    <div class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-2 tabular-nums" x-text="progress + '%'"></div>
                                    <div class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.3em] mb-4">Transmitting Data</div>
                                    <div class="w-full bg-gray-100 dark:bg-white/5 h-2.5 rounded-full overflow-hidden shadow-inner">
                                        <div class="bg-red-600 h-full rounded-full transition-all duration-300" :style="'width: ' + progress + '%'"></div>
                                    </div>
                                </div>
                            </template>
                            <template x-if="isProcessing">
                                <div class="flex flex-col items-center">
                                    <div class="w-12 h-12 md:w-14 md:h-14 border-3 md:border-4 border-red-600 border-t-transparent rounded-full animate-spin mb-4"></div>
                                    <h3 class="text-lg md:text-xl font-black text-gray-900 dark:text-white tracking-tight">Finishing Up</h3>
                                    <p class="text-gray-400 dark:text-gray-500 font-bold mt-1 text-xs max-w-[180px]">Optimizing meta-data and generating shards...</p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Modern Form Section -->
                    <div class="flex-1 p-4 md:p-5 bg-white dark:bg-transparent">
                        <div class="mb-4">
                            <div class="flex items-center space-x-1.5 mb-1">
                                <span class="w-1 h-1 bg-red-600 rounded-full animate-pulse"></span>
                                <h2 class="text-[8px] font-black text-red-600 uppercase tracking-[0.4em]">Creator Studio</h2>
                            </div>
                            <h2 class="text-lg md:text-xl font-black text-[#2D2D2D] dark:text-white leading-none tracking-tight">New Creation</h2>
                        </div>

                        <form @submit="submitForm" id="uploadForm" action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            
                            <div class="space-y-1.5">
                                <label class="text-[8px] uppercase font-black text-gray-400 dark:text-gray-600 tracking-widest ms-2">Master Title</label>
                                <input id="title" name="title" type="text" placeholder="Your masterpiece title..." class="block w-full bg-gray-50 dark:bg-white/5 border-transparent dark:border-white/5 rounded-[0.75rem] md:rounded-[1rem] px-4 py-3 focus:ring-2 focus:ring-red-600 dark:text-white font-black text-sm transition-all placeholder-gray-300 dark:placeholder-gray-700" required />
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[8px] uppercase font-black text-gray-400 dark:text-gray-600 tracking-widest ms-2">Insight Description</label>
                                <textarea id="description" name="description" rows="2" placeholder="Tell the world what this is about..." class="block w-full bg-gray-50 dark:bg-white/5 border-transparent dark:border-white/5 rounded-[0.75rem] md:rounded-[1rem] px-4 py-3 focus:ring-2 focus:ring-red-600 dark:text-white font-medium resize-none transition-all placeholder-gray-300 dark:placeholder-gray-700"></textarea>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="relative">
                                    <input @change="handleFileSelect" x-ref="videoInput" id="video" name="video" type="file" accept="video/mp4" class="hidden" required />
                                    <button type="button" @click="$refs.videoInput.click()" class="w-full bg-[#1A1A1A] dark:bg-white text-white dark:text-black py-2.5 md:py-3 rounded-lg md:rounded-xl text-[8px] uppercase font-black tracking-widest transition-all active:scale-95 shadow-md shadow-gray-200 dark:shadow-none">
                                        Link Master File
                                    </button>
                                </div>

                                <div class="relative">
                                    <input x-ref="thumbnailInput" id="thumbnail" name="thumbnail" type="file" accept="image/*" class="hidden" />
                                    <button type="button" @click="$refs.thumbnailInput.click()" class="w-full bg-white dark:bg-white/5 border border-gray-100 dark:border-white/10 text-gray-900 dark:text-gray-300 py-2.5 md:py-3 rounded-lg md:rounded-xl text-[8px] uppercase font-black tracking-widest transition-all active:scale-95 shadow-sm">
                                        Frame Preview
                                    </button>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-50 dark:border-white/5 flex flex-col sm:flex-row items-center justify-between gap-3">
                                <div class="flex items-center space-x-1.5">
                                    <div class="flex -space-x-1">
                                        @foreach([1,2,3] as $i)
                                        <div class="w-5 h-5 rounded-full border border-white dark:border-[#1A1A1A] bg-gray-200 dark:bg-white/10 flex items-center justify-center text-[6px] font-black">{{ $i }}</div>
                                        @endforeach
                                    </div>
                                    <span class="text-[8px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-widest">3-step verification</span>
                                </div>
                                <button type="submit" class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white px-6 md:px-8 py-2.5 md:py-3 rounded-lg md:rounded-xl font-black text-[9px] uppercase tracking-[0.2em] transition-all transform hover:translate-y-[-1px] active:translate-y-[1px] shadow-[0_10px_20px_-6px_rgba(220,38,38,0.25)]">
                                    Go Live Now
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <p class="text-center mt-8 text-gray-400 dark:text-gray-700 text-[9px] font-black uppercase tracking-[0.5em]">Binteo Stream Architecture © 2024</p>
        </div>
    </div>
</x-app-layout>
