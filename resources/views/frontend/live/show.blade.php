<x-app-layout>
    <div class="py-6 bg-white dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500">
        <div class="max-w-[1700px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-8rem)]">
                
                <!-- Main Stream Area -->
                <div class="flex-1 flex flex-col min-w-0">
                    <div class="flex-1 bg-black rounded-[2rem] overflow-hidden relative shadow-lg group">
                        
                        <!-- Simulated Video Stream -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900">
                            @if($stream->is_live)
                                <div class="w-20 h-20 bg-red-600/20 rounded-full flex items-center justify-center animate-pulse mb-6">
                                    <svg class="w-10 h-10 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg>
                                </div>
                                <h2 class="text-2xl font-black text-white px-8 text-center">{{ $stream->title }}</h2>
                                <p class="text-sm font-bold text-gray-400 mt-2">Waiting for broadcast signal...</p>
                                
                                @if(auth()->check() && auth()->id() === $stream->user_id)
                                    <div class="mt-8 bg-white/10 backdrop-blur-md px-6 py-4 rounded-2xl border border-white/20">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Stream Key (Keep Secret)</p>
                                        <div class="flex items-center space-x-4">
                                            <code class="text-white font-mono text-sm">{{ $stream->stream_key }}</code>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <h1 class="text-white text-3xl font-black">Stream Offline</h1>
                            @endif
                        </div>

                        <!-- Stream Overlay Info -->
                        <div class="absolute top-6 left-6 flex items-center space-x-3">
                            <span class="px-3 py-1 bg-red-600 text-white rounded-lg text-[10px] font-black uppercase tracking-widest animate-pulse">Live</span>
                            <span class="px-3 py-1 bg-black/50 backdrop-blur-md text-white rounded-lg text-[10px] font-bold flex items-center space-x-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span x-text="viewersCount">0</span>
                            </span>
                        </div>
                    </div>

                    <!-- Channel Ribbon -->
                    <div class="mt-6 flex items-center justify-between bg-white dark:bg-[#1A1A1A] p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-white/5">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-red-500 to-orange-500 p-[2px]">
                                <div class="w-full h-full bg-white dark:bg-[#1A1A1A] rounded-full flex items-center justify-center text-red-500 font-black text-lg">
                                    {{ substr($stream->user->channel->name ?? $stream->user->name, 0, 1) }}
                                </div>
                            </div>
                            <div>
                                <h3 class="font-black text-gray-900 dark:text-white leading-tight">{{ $stream->user->channel->name ?? $stream->user->name }}</h3>
                                <p class="text-[10px] uppercase font-bold text-gray-400">{{ $stream->user->channel->subscribers_count ?? 0 }} subscribers</p>
                            </div>
                        </div>
                        <button class="px-8 py-2.5 bg-red-600 text-white rounded-xl font-black text-xs transition-transform active:scale-95 shadow-md">Subscribe</button>
                    </div>
                </div>

                <!-- Live Chat Sidebar -->
                <div class="w-full lg:w-96 flex flex-col bg-white dark:bg-[#1A1A1A] rounded-[2rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden" 
                     x-data="liveChat({{ $stream->id }}, {{ auth()->check() ? 'true' : 'false' }})">
                    <!-- Chat Header -->
                    <div class="h-16 flex items-center justify-between px-6 border-b border-gray-50 dark:border-white/5 shrink-0">
                        <h3 class="font-black text-sm text-gray-900 dark:text-white uppercase tracking-widest">Live Chat</h3>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                    </div>

                    <!-- Chat Messages -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-box">
                        <template x-for="msg in messages" :key="msg.id">
                            <div class="flex items-start space-x-3 hover:bg-gray-50 dark:hover:bg-white/5 p-2 rounded-xl transition-colors">
                                <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-white/10 flex items-center justify-center font-black text-xs text-gray-500 shrink-0" x-text="msg.user.avatar"></div>
                                <div class="min-w-0">
                                    <div class="flex items-baseline space-x-2">
                                        <span class="text-xs font-black text-gray-700 dark:text-gray-300" x-text="msg.user.name"></span>
                                        <span class="text-[9px] font-bold text-gray-400" x-text="msg.created_at"></span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 break-words mt-0.5" x-text="msg.message"></p>
                                </div>
                            </div>
                        </template>
                        <!-- Auto-scroll anchor -->
                        <div x-ref="bottom"></div>
                    </div>

                    <!-- Chat Input -->
                    <div class="p-4 border-t border-gray-50 dark:border-white/5 shrink-0 bg-gray-50/50 dark:bg-white/2">
                        @auth
                            <form @submit.prevent="sendMessage" class="flex items-center space-x-2">
                                <input type="text" x-model="newMessage" placeholder="Chat as {{ auth()->user()->name }}..." class="flex-1 bg-white dark:bg-[#0F0F0F] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-red-500 focus:border-red-500 transition-colors">
                                <button type="submit" class="p-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors" :disabled="!newMessage.trim()">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                </button>
                            </form>
                        @else
                            <div class="text-center py-2">
                                <a href="{{ route('login') }}" class="text-xs font-black text-red-600 uppercase tracking-widest hover:underline">Sign in to chat</a>
                            </div>
                        @endauth
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Live Chat Alpine Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('liveChat', (streamId, isAuth) => ({
                messages: @json($messages->map(fn($m) => [
                    'id' => $m->id, 'message' => $m->message, 'user' => ['name' => $m->user->name, 'avatar' => substr($m->user->name,0,1)], 'created_at' => $m->created_at->format('H:i')
                ])),
                newMessage: '',
                viewersCount: 0,
                
                init() {
                    this.scrollToBottom();

                    if (window.Echo) {
                        // Connect to Presence Channel via Reverb
                        window.Echo.join(`live-stream.${streamId}`)
                            .here((users) => {
                                this.viewersCount = users.length;
                            })
                            .joining((user) => {
                                this.viewersCount++;
                            })
                            .leaving((user) => {
                                this.viewersCount--;
                            })
                            .listen('StreamMessageSent', (e) => {
                                this.messages.push(e);
                                this.$nextTick(() => this.scrollToBottom());
                            });
                    }
                },

                scrollToBottom() {
                    const el = document.getElementById('chat-box');
                    el.scrollTop = el.scrollHeight;
                },

                sendMessage() {
                    if (!this.newMessage.trim() || !isAuth) return;
                    
                    const msg = this.newMessage;
                    this.newMessage = '';

                    fetch(`/live/${streamId}/chat`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ message: msg })
                    });
                }
            }));
        });
    </script>
</x-app-layout>
