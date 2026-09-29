<x-app-layout>
    <div class="py-6 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-6">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter">Support Desk</h1>
                    <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Our specialists are ready to help you
                    </p>
                </div>
                <a href="{{ route('user.ticket.create') }}" class="px-6 py-3 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg transition-all active:scale-95 flex items-center gap-2" style="background: linear-gradient(135deg, oklch(0.65 0.25 30), oklch(0.75 0.2 35), oklch(0.55 0.15 25)); box-shadow: 0 10px 20px -5px oklch(0.65 0.25 30 / 0.3);">
                    <span class="material-symbols-rounded text-lg">add_comment</span>
                    New Request
                </a>
            </div>

            <!-- Filter Tabs -->
            <div class="mb-10" x-data="{ currentStatus: '{{ request('status', 'all') }}' }">
                <p class="text-sm font-bold text-gray-400 dark:text-gray-500 mb-4 px-2">Filter by Status</p>
                <div id="ticketTabsContainer" class="flex items-center gap-3 overflow-x-auto pb-4 no-scrollbar cursor-grab active:cursor-grabbing select-none">
                    <button type="button" @click="filterTickets('all')" 
                       class="ticket-tab flex-shrink-0 px-6 py-3 rounded-full flex items-center gap-3 transition-all font-bold text-xs uppercase tracking-widest cursor-pointer"
                       :class="currentStatus === 'all' ? 'text-white shadow-lg shadow-orange-500/20' : 'bg-white dark:bg-white/5 text-gray-500 dark:text-white/70 border border-gray-100 dark:border-white/5 hover:bg-gray-100 dark:hover:bg-white/10'"
                       :style="currentStatus === 'all' ? 'background: linear-gradient(135deg, oklch(0.65 0.25 30), oklch(0.75 0.2 35), oklch(0.55 0.15 25))' : ''">
                        <span>All</span>
                        <span id="count-all" class="px-2 py-0.5 rounded-full text-[10px] font-black" :class="currentStatus === 'all' ? 'bg-white/20 text-white' : 'bg-gray-100 dark:bg-white/10 text-gray-400 dark:text-white/60'">{{ $counts['all'] ?? $supports->total() }}</span>
                    </button>
                    
                    @php
                        $tabStatuses = [
                            ['Pending', '0', 'pending'],
                            ['Answered', '1', 'answered'],
                            ['In Progress', '2', 'in_progress'],
                            ['Closed', '3', 'closed'],
                            ['Rejected', '4', 'rejected'],
                        ];
                    @endphp

                    @foreach($tabStatuses as $tab)
                        <button type="button" @click="filterTickets('{{ $tab[1] }}')" 
                           class="ticket-tab flex-shrink-0 px-6 py-3 rounded-full flex items-center gap-3 transition-all font-bold text-xs uppercase tracking-widest cursor-pointer"
                           :class="currentStatus === '{{ $tab[1] }}' ? 'text-white shadow-lg shadow-orange-500/20' : 'bg-white dark:bg-white/5 text-gray-500 dark:text-white/70 border border-gray-100 dark:border-white/5 hover:bg-gray-100 dark:hover:bg-white/10'"
                           :style="currentStatus === '{{ $tab[1] }}' ? 'background: linear-gradient(135deg, oklch(0.65 0.25 30), oklch(0.75 0.2 35), oklch(0.55 0.15 25))' : ''">
                            <span>{{ $tab[0] }}</span>
                            <span id="count-{{ $tab[2] }}" class="px-2 py-0.5 rounded-full text-[10px] font-black" :class="currentStatus === '{{ $tab[1] }}' ? 'bg-white/20 text-white' : 'bg-gray-100 dark:bg-white/10 text-gray-400 dark:text-white/60'">
                                {{ $counts[$tab[2]] ?? 0 }}
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Ticket List Container -->
            <div id="ticketContainer" class="relative min-h-[200px]">
                @include('frontend.client.ticket.partials.ticket_list', ['supports' => $supports])
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- SUPPORT HUB MODAL                                      --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div id="ticketHubModal" x-data="ticketHub()" x-show="open" x-cloak
         class="fixed inset-0 z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" @click="closeHub()"></div>
        <div class="relative w-full max-w-xl h-[85vh] sm:h-[480px] md:h-[520px] bg-white dark:bg-[#121212] rounded-t-[2.5rem] sm:rounded-[2.5rem] border-t sm:border border-gray-100 dark:border-white/10 shadow-2xl flex flex-col overflow-hidden"
             x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0" x-transition:enter-end="translate-y-0 sm:scale-100 sm:opacity-100"
             x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0 sm:scale-100 sm:opacity-100" x-transition:leave-end="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0"
             @click.away="closeHub()">

            {{-- Mobile Drag Handle --}}
            <div class="h-1.5 w-12 bg-gray-200 dark:bg-white/20 rounded-full mx-auto mt-3 mb-1 sm:hidden"></div>

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 sm:px-8 py-4 sm:py-5 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/[0.02] flex-shrink-0">
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="w-11 h-11 rounded-2xl bg-blue-500/10 dark:bg-blue-500/20 text-blue-500 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-rounded text-2xl">hub</span>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-base sm:text-lg font-black text-gray-900 dark:text-white tracking-tight truncate" x-text="'#' + ticketNumber"></h3>
                        <p class="text-[10px] font-black uppercase tracking-widest truncate" :class="statusColor" x-text="statusLabel"></p>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                    <button @click="notif = !notif" class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all"
                            :class="notif ? 'bg-blue-500/10 text-blue-500 dark:bg-blue-500/20 dark:text-blue-400' : 'bg-gray-100 dark:bg-white/10 text-gray-500 dark:text-white/70'" title="Toggle notifications">
                        <span class="material-symbols-rounded text-xl" x-text="notif ? 'notifications_active' : 'notifications_off'"></span>
                    </button>
                    <button @click="closeHub()" class="w-10 h-10 rounded-2xl bg-gray-100 dark:bg-white/10 flex items-center justify-center text-gray-500 dark:text-white/70 hover:bg-red-500 hover:text-white dark:hover:bg-red-500 transition-all">
                        <span class="material-symbols-rounded text-xl">close</span>
                    </button>
                </div>
            </div>

            {{-- Loading --}}
            <template x-if="loading">
                <div class="flex-1 flex items-center justify-center py-20">
                    <div class="text-center">
                        <div class="w-12 h-12 border-4 border-gray-200 dark:border-white/10 border-t-blue-500 rounded-full animate-spin mx-auto mb-4"></div>
                        <p class="text-[10px] font-black text-gray-400 dark:text-white/40 uppercase tracking-widest">Loading conversation...</p>
                    </div>
                </div>
            </template>

            {{-- Messages --}}
            <div x-ref="messagesContainer" x-show="!loading" class="flex-1 overflow-y-auto px-5 sm:px-8 py-5 space-y-4 scroll-smooth">
                <template x-for="msg in messages" :key="msg.id">
                    <div class="flex gap-3" :class="msg.is_admin ? '' : 'flex-row-reverse'">
                        <div class="w-9 h-9 rounded-2xl flex items-center justify-center flex-shrink-0" :class="msg.is_admin ? 'bg-red-500 text-white' : 'bg-blue-600 text-white'">
                            <span class="material-symbols-rounded text-lg" x-text="msg.is_admin ? 'support_agent' : 'person'"></span>
                        </div>
                        <div class="max-w-[80%] sm:max-w-[75%] min-w-0">
                            <div class="rounded-3xl px-5 py-3.5 shadow-sm" :class="msg.is_admin ? 'bg-gray-100 dark:bg-white/10 text-gray-900 dark:text-white rounded-tl-sm' : 'bg-blue-600 text-white rounded-tr-sm'">
                                <p class="text-[13px] font-semibold leading-relaxed whitespace-pre-line break-words" x-text="msg.message"></p>
                            </div>
                            <template x-if="msg.attachments && msg.attachments.length > 0">
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <template x-for="att in msg.attachments" :key="att.id">
                                        <div class="group/att relative">
                                            <template x-if="att.is_image && att.image_url">
                                                <div class="w-28 aspect-video rounded-xl overflow-hidden bg-gray-100 dark:bg-white/5 border border-gray-100 dark:border-white/10 mb-1">
                                                    <img :src="att.image_url" class="w-full h-full object-cover cursor-zoom-in" @click="window.open(att.image_url, '_blank')">
                                                </div>
                                            </template>
                                            <a :href="att.url" class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-50 dark:bg-white/10 rounded-lg border border-gray-100 dark:border-white/10 text-[9px] font-black text-gray-700 dark:text-white/80 uppercase tracking-widest hover:border-blue-500 transition-all">
                                                <span class="material-symbols-rounded text-sm">download</span> File
                                            </a>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <p class="text-[9.5px] font-bold mt-1.5 px-1 uppercase tracking-widest" :class="msg.is_admin ? 'text-gray-400 dark:text-white/40' : 'text-blue-500 dark:text-blue-300'" x-text="msg.sender + ' · ' + msg.created_at"></p>
                        </div>
                    </div>
                </template>
                <template x-if="!loading && messages.length === 0">
                    <div class="text-center py-12">
                        <span class="material-symbols-rounded text-5xl text-gray-200 dark:text-white/10">forum</span>
                        <p class="text-[10px] font-black text-gray-400 dark:text-white/40 uppercase tracking-widest mt-3">No messages yet</p>
                    </div>
                </template>
            </div>

            {{-- Reply Box --}}
            <div x-show="!loading && ticketStatus < 3" class="px-5 sm:px-8 py-4 sm:py-5 border-t border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/[0.02] flex-shrink-0">
                <form @submit.prevent="sendReply()" class="flex items-center gap-3">
                    <div class="flex-1">
                        <textarea x-model="replyMessage" rows="1" placeholder="Type your reply..." class="w-full bg-white dark:bg-white/10 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-xs sm:text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none resize-none" :disabled="sending" @keydown.ctrl.enter="sendReply()"></textarea>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <div class="relative">
                            <input type="file" x-ref="hubFiles" @change="handleFiles($event)" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="w-11 h-11 rounded-2xl bg-gray-100 dark:bg-white/10 flex items-center justify-center text-gray-500 dark:text-white/80 hover:text-blue-500 dark:hover:text-blue-400 transition-colors cursor-pointer">
                                <span class="material-symbols-rounded text-xl">attach_file</span>
                            </div>
                        </div>
                        <button type="submit" :disabled="sending || !replyMessage.trim()" class="w-11 h-11 rounded-2xl bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 active:scale-95 transition-all disabled:opacity-40 disabled:cursor-not-allowed shadow-lg shadow-blue-500/25">
                            <span class="material-symbols-rounded text-xl" x-text="sending ? 'hourglass_top' : 'send'"></span>
                        </button>
                    </div>
                </form>
                <template x-if="selectedFiles.length > 0">
                    <div class="flex flex-wrap gap-2 mt-3">
                        <template x-for="(file, idx) in selectedFiles" :key="idx">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-lg text-[9px] font-black uppercase tracking-widest">
                                <span x-text="file.name.substring(0, 15)"></span>
                                <button type="button" @click="removeFile(idx)" class="hover:text-red-500">&times;</button>
                            </span>
                        </template>
                    </div>
                </template>
            </div>

            {{-- Terminal notice (Closed/Rejected) --}}
            <div x-show="!loading && ticketStatus >= 3" class="px-8 py-5 border-t border-gray-100 dark:border-white/5 bg-amber-500/5 flex-shrink-0">
                <div class="flex items-center justify-center gap-3">
                    <span class="material-symbols-rounded text-amber-500" x-text="ticketStatus == 3 ? 'lock' : 'cancel'"></span>
                    <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest" x-text="ticketStatus == 3 ? 'This ticket is closed' : 'This ticket has been rejected'"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
    function ticketHub() {
        return {
            open: false, loading: false, sending: false, notif: false,
            ticketId: null, ticketNumber: '', ticketStatus: 1,
            messages: [], replyMessage: '', selectedFiles: [],

            get statusLabel() {
                return { 0: 'Open', 1: 'Answered', 2: 'Customer Reply', 3: 'Closed' }[this.ticketStatus] || 'Unknown';
            },
            get statusColor() {
                return { 0: 'text-emerald-500', 1: 'text-blue-500', 2: 'text-blue-400', 3: 'text-gray-400' }[this.ticketStatus] || 'text-gray-400';
            },

            async openHub(id, ticket) {
                this.ticketId = id; this.ticketNumber = ticket;
                this.open = true; this.loading = true;
                document.body.style.overflow = 'hidden';
                this.messages = []; this.replyMessage = ''; this.selectedFiles = [];
                try {
                    const res = await fetch(`/client/ticket/hub-data/${id}`, { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) throw new Error('Failed');
                    const data = await res.json();
                    this.messages = data.messages || [];
                    this.ticketStatus = data.ticket?.status ?? 1;
                    this.ticketNumber = data.ticket?.ticket || ticket;
                    this.$nextTick(() => this.scrollToBottom());
                } catch (e) { console.error('Hub load error:', e); }
                finally { this.loading = false; }
            },

            closeHub() { 
                this.open = false; 
                this.ticketId = null; 
                this.messages = []; 
                document.body.style.overflow = '';
            },

            async sendReply() {
                if (!this.replyMessage.trim() || this.sending) return;
                this.sending = true;
                try {
                    const fd = new FormData();
                    fd.append('message', this.replyMessage);
                    fd.append('_token', '{{ csrf_token() }}');
                    this.selectedFiles.forEach(f => fd.append('attachments[]', f));
                    const res = await fetch(`/client/ticket/reply/${this.ticketId}`, { method: 'POST', body: fd });
                    if (res.ok) {
                        this.replyMessage = ''; this.selectedFiles = [];
                        if (this.$refs.hubFiles) this.$refs.hubFiles.value = '';
                        await this.openHub(this.ticketId, this.ticketNumber);
                    }
                } catch (e) { console.error('Reply error:', e); }
                finally { this.sending = false; }
            },

            handleFiles(e) {
                const allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                const files = Array.from(e.target.files || []);
                const invalid = files.filter(f => !allowed.includes(f.type));
                if (invalid.length > 0) {
                    Swal.fire({ icon: 'error', title: 'Invalid File', text: 'Only images (jpg, png, webp, gif) are allowed.', confirmButtonColor: '#ef4444' });
                    e.target.value = '';
                    return;
                }
                if (files.length > 4) {
                    Swal.fire({ icon: 'error', title: 'Too Many Files', text: 'Maximum 4 files can be uploaded.', confirmButtonColor: '#ef4444' });
                    e.target.value = '';
                    return;
                }
                this.selectedFiles = files;
            },
            removeFile(idx) { this.selectedFiles.splice(idx, 1); },
            scrollToBottom() { const c = this.$refs.messagesContainer; if (c) c.scrollTop = c.scrollHeight; }
        };
    }

    async function filterTickets(status) {
        const container = document.getElementById('ticketContainer');
        if (!container) return;

        // Alpine state update if Alpine is mounted
        const filterComponent = document.querySelector('[x-data*="currentStatus"]');
        if (filterComponent && filterComponent._x_dataStack) {
            filterComponent._x_dataStack[0].currentStatus = status;
        }

        // Visual loading state
        container.style.opacity = '0.5';

        try {
            const url = new URL(window.location.origin + '/client/ticket');
            if (status !== 'all') {
                url.searchParams.set('status', status);
            }

            const response = await fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Failed to fetch filtered tickets');
            const contentType = response.headers.get('content-type') || '';
            if (!contentType.includes('application/json')) throw new Error('Expected JSON, got: ' + contentType);

            const data = await response.json();
            container.innerHTML = data.html;

            // Update tab counts from server (backend returns counts with every response)
            if (data.counts) {
                const map = { all: 'count-all', pending: 'count-pending', answered: 'count-answered', in_progress: 'count-in_progress', closed: 'count-closed', rejected: 'count-rejected' };
                for (const [key, elId] of Object.entries(map)) {
                    const el = document.getElementById(elId);
                    if (el && data.counts[key] !== undefined) el.textContent = data.counts[key];
                }
            }

            // Update URL without page reload
            window.history.pushState({}, '', url.toString());

            // Re-bind click event for dynamic elements
            bindOpenHubButtons();

        } catch (error) {
            console.error('Filter error:', error);
        } finally {
            container.style.opacity = '1';
        }
    }

    function bindOpenHubButtons() {
        document.querySelectorAll('.openHub').forEach(btn => {
            btn.onclick = function() {
                const id = this.dataset.id, ticket = this.dataset.ticket;
                const hubEl = document.getElementById('ticketHubModal');
                if (hubEl && hubEl._x_dataStack) {
                    hubEl._x_dataStack[0].openHub(id, ticket);
                }
            };
        });
    }

    function initDesktopTabScroll() {
        const slider = document.getElementById('ticketTabsContainer');
        if (!slider) return;

        let isDown = false;
        let startX;
        let scrollLeft;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.add('cursor-grabbing');
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });

        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.classList.remove('cursor-grabbing');
        });

        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.classList.remove('cursor-grabbing');
        });

        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2;
            slider.scrollLeft = scrollLeft - walk;
        });

        slider.addEventListener('wheel', (e) => {
            if (e.deltaY !== 0) {
                e.preventDefault();
                slider.scrollLeft += e.deltaY;
            }
        }, { passive: false });
    }

    document.addEventListener('DOMContentLoaded', function() {
        bindOpenHubButtons();
        initDesktopTabScroll();

        // Pagination clicks -> AJAX JSON (never full-page JSON pretty-print)
        const ticketContainer = document.getElementById('ticketContainer');
        if (ticketContainer) {
            ticketContainer.addEventListener('click', async (e) => {
                const link = e.target.closest('.pagination-container a[href]');
                if (!link) return;
                e.preventDefault();
                const pageUrl = new URL(link.href, window.location.origin);
                window.history.pushState({}, '', pageUrl.toString());
                ticketContainer.style.opacity = '0.5';
                try {
                    const res = await fetch(pageUrl.toString(), {
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    });
                    const ct = res.headers.get('content-type') || '';
                    if (!res.ok || !ct.includes('application/json')) throw new Error('Expected JSON, got: ' + ct);
                    const data = await res.json();
                    ticketContainer.innerHTML = data.html;
                    bindOpenHubButtons();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } catch (err) {
                    console.error('Ticket pagination failed, falling back to full load:', err);
                    window.location.href = pageUrl.toString();
                } finally {
                    ticketContainer.style.opacity = '1';
                }
            });
        }
    });
    </script>

</x-app-layout>
