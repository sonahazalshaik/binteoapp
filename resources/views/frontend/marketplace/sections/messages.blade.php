<!-- Tab: Messages -->
<div x-show="tab === 'messages'" x-cloak x-transition class="flex flex-col flex-1 w-full h-full min-h-0 bg-[#F8FAFC] dark:bg-[#080808]">
    <div class="bg-transparent lg:bg-white lg:dark:bg-[#111] lg:rounded-[2rem] lg:border border-slate-200 dark:border-white/5 lg:shadow-sm overflow-hidden flex flex-1 w-full lg:h-[70vh] lg:min-h-[500px] lg:max-h-[800px]">
        
        <!-- Sidebar: Contact List -->
        <div class="w-full md:w-80 border-r border-slate-100 dark:border-white/5 flex flex-col bg-slate-50 dark:bg-black/20 shrink-0" :class="activeChat ? 'hidden md:flex' : 'flex'">
            <div class="hidden md:block p-5 border-b border-slate-100 dark:border-white/5">
                <h2 class="text-lg font-black text-slate-900 dark:text-white tracking-tighter">Messages</h2>
            </div>
            
            <div class="flex-1 overflow-y-auto custom-scrollbar p-2 space-y-1">
                @forelse($messages as $chatKey => $userMessages)
                    @php 
                        $isMarketplaceChat = str_starts_with($chatKey, 'm_');
                        $targetId = (int) substr($chatKey, 2);
                        if ($isMarketplaceChat) {
                            $otherParty = \App\Models\MarketPlace::find($targetId);
                            $senderName = $otherParty ? $otherParty->name : 'Marketplace User';
                            $senderInitials = $otherParty ? $otherParty->getInitials() : 'MU';
                            $senderImage = $otherParty ? $otherParty->photoUrl() : null;
                        } else {
                            $otherParty = \App\Models\User::find($targetId);
                            $senderName = $otherParty ? $otherParty->fullname : 'User';
                            $senderInitials = $otherParty ? strtoupper(substr($otherParty->firstname ?? 'U', 0, 1) . substr($otherParty->lastname ?? '', 0, 1)) : 'U';
                            $senderImage = ($otherParty && $otherParty->image) ? getImage(getFilePath('userProfile') . '/' . $otherParty->image, getFileSize('userProfile'), true) : null;
                        }
                        $hasUnread = $userMessages->contains(function($m) use ($client) {
                            return (int)$m->marketplace_id === (int)$client->id && $m->is_read == 0;
                        });
                    @endphp
                    <button @click="activeChat = '{{ $chatKey }}'; markMessagesRead();" 
                            class="w-full flex items-center gap-3 p-3 rounded-2xl transition-all text-left group relative"
                            :class="activeChat === '{{ $chatKey }}' ? 'bg-red-50 dark:bg-white/10' : 'hover:bg-slate-100 dark:hover:bg-white/5'">
                        <div class="w-12 h-12 rounded-full bg-[#e04f3e]/10 text-[#e04f3e] flex items-center justify-center font-bold text-sm shrink-0 overflow-hidden relative">
                            @if($senderImage)
                                <img src="{{ $senderImage }}" class="w-full h-full object-cover">
                            @else
                                {{ $senderInitials }}
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5">
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $senderName }}</h4>
                                @if($hasUnread)
                                    <span class="w-2 h-2 rounded-full bg-red-500 shrink-0 animate-pulse"></span>
                                @endif
                            </div>
                            <p class="text-[11px] truncate mt-0.5 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors {{ $hasUnread ? 'font-bold text-slate-900 dark:text-white' : 'text-slate-500' }}">{{ $userMessages->last()->message }}</p>
                        </div>
                        <div class="text-[9px] font-bold text-slate-400 shrink-0">
                            {{ $userMessages->last()->created_at->shortAbsoluteDiffForHumans() }}
                        </div>
                    </button>
                @empty
                    <div class="p-8 text-center text-slate-400">
                        <span class="material-symbols-rounded text-4xl mb-3 opacity-30">forum</span>
                        <p class="text-[10px] font-bold uppercase tracking-widest">No messages</p>
                        <p class="text-[10px] mt-1 opacity-70">You don't have any conversations yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Chat Area -->
        <div class="flex-1 flex flex-col min-h-0 overflow-hidden bg-transparent md:bg-white md:dark:bg-[#111]" :class="activeChat ? 'flex' : 'hidden md:flex'">
            
            <template x-if="!activeChat">
                <div class="flex-1 flex flex-col items-center justify-center text-slate-400 p-8 text-center">
                    <div class="w-24 h-24 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center mb-6 shadow-inner">
                        <span class="material-symbols-rounded text-5xl opacity-30">chat_bubble</span>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tighter">Your Messages</h3>
                    <p class="text-xs mt-2 max-w-xs leading-relaxed">Select a conversation from the sidebar to view messages or reply to users.</p>
                </div>
            </template>

            @foreach($messages as $chatKey => $userMessages)
                <div x-show="activeChat === '{{ $chatKey }}'" x-data="chatPanel_{{ $chatKey }}()" class="flex-1 flex flex-col min-h-0 overflow-hidden h-full" style="display: none;">
                    <!-- Chat Header -->
                    <div class="p-4 md:p-5 border-b border-slate-200/50 dark:border-white/5 flex items-center justify-between bg-white/70 dark:bg-black/20 backdrop-blur-xl shrink-0 sticky top-0 z-10">
                        <div class="flex items-center gap-3">
                            <button @click="activeChat = null" class="md:hidden w-10 h-10 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-500 hover:text-slate-900 dark:hover:text-white transition-all">
                                <span class="material-symbols-rounded text-[18px]">arrow_back</span>
                            </button>
                            @php 
                                $isMarketplaceChat = str_starts_with($chatKey, 'm_');
                                $targetId = (int) substr($chatKey, 2);
                                if ($isMarketplaceChat) {
                                    $otherParty = \App\Models\MarketPlace::find($targetId);
                                    $senderName = $otherParty ? $otherParty->name : 'Marketplace User';
                                    $senderInitials = $otherParty ? $otherParty->getInitials() : 'MU';
                                    $senderImage = $otherParty ? $otherParty->photoUrl() : null;
                                    $profileUrl = $otherParty ? route('marketplace.portfolio', $otherParty->id) : '#';
                                } else {
                                    $otherParty = \App\Models\User::find($targetId);
                                    $senderName = $otherParty ? $otherParty->fullname : 'User';
                                    $senderInitials = $otherParty ? strtoupper(substr($otherParty->firstname ?? 'U', 0, 1) . substr($otherParty->lastname ?? '', 0, 1)) : 'U';
                                    $senderImage = ($otherParty && $otherParty->image) ? getImage(getFilePath('userProfile') . '/' . $otherParty->image, getFileSize('userProfile'), true) : null;
                                    $profileUrl = $otherParty ? route('channels.show_by_username', $otherParty->username ?? '') : '#';
                                }
                            @endphp
                            <div class="w-10 h-10 rounded-full bg-[#e04f3e]/10 text-[#e04f3e] flex items-center justify-center font-bold text-xs shrink-0 overflow-hidden">
                                @if($senderImage)
                                    <img src="{{ $senderImage }}" class="w-full h-full object-cover">
                                @else
                                    {{ $senderInitials }}
                                @endif
                            </div>
                            <div>
                                <h3 class="font-bold text-sm text-slate-900 dark:text-white">{{ $senderName }}</h3>
                            </div>
                        </div>
                        <a href="{{ $profileUrl }}" target="_blank" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-500 hover:text-[#e04f3e] hover:bg-red-50 dark:hover:bg-red-500/10 transition-all" title="View Profile">
                            <span class="material-symbols-rounded text-[16px]">person</span>
                        </a>
                    </div>

                    <!-- Messages List -->
                    <div id="chat-messages-{{ $chatKey }}" class="flex-1 overflow-y-auto p-4 md:p-6 space-y-5 custom-scrollbar bg-[#F8FAFC] dark:bg-transparent">
                        @foreach($userMessages as $msg)
                            @php
                                $isOwnMarketplace = (int)$msg->sender_marketplace_id === (int)$client->id || ($msg->sender_type === 'marketplace' && (int)$msg->marketplace_id === (int)$client->id);
                            @endphp
                            @if($isOwnMarketplace)
                                <!-- My Reply -->
                                <div class="flex justify-end items-center gap-1" id="msg-{{ $msg->id }}">
                                    <div class="bg-[#e04f3e] text-white p-3.5 md:p-4 rounded-2xl rounded-tr-sm max-w-[85%] md:max-w-[70%] shadow-md flex flex-col">
                                        <p class="text-[13px] leading-relaxed" id="msg-text-{{ $msg->id }}">{{ $msg->message }}</p>
                                        <div class="flex items-center justify-end gap-1.5 mt-1">
                                            <span class="text-[9px] text-red-200 font-medium tracking-wide">{{ $msg->created_at->format('h:i A') }}</span>
                                            <!-- Three dots menu -->
                                            <div class="relative shrink-0" x-data="{ open: false }">
                                                <button @click="open = !open" class="w-5 h-5 rounded-full flex items-center justify-center text-white/70 hover:text-white hover:bg-black/10 transition-all">
                                                    <span class="material-symbols-rounded text-[15px]">more_vert</span>
                                                </button>
                                                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" class="absolute right-0 bottom-full mb-1 bg-white dark:bg-[#1A1A1A] rounded-xl shadow-lg border border-slate-100 dark:border-white/10 py-1 min-w-[120px] z-30" style="display: none;">
                                                    <button @click="editMessage({{ $msg->id }}, '{{ addslashes($msg->message) }}'); open = false;" class="w-full flex items-center gap-2.5 px-3.5 py-2 text-[12px] font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors text-left">
                                                        <span class="material-symbols-rounded text-[15px] text-indigo-500">edit</span> Edit
                                                    </button>
                                                    <button @click="deleteMessage({{ $msg->id }}); open = false;" class="w-full flex items-center gap-2.5 px-3.5 py-2 text-[12px] font-semibold text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors text-left">
                                                        <span class="material-symbols-rounded text-[15px]">delete</span> Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- User Message -->
                                <div class="flex justify-start items-center gap-1" id="msg-{{ $msg->id }}">
                                    <div class="bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-800 dark:text-slate-200 p-3.5 md:p-4 rounded-2xl rounded-tl-sm max-w-[85%] md:max-w-[70%] shadow-sm flex flex-col relative pr-4">
                                        <p class="text-[13px] leading-relaxed" id="msg-text-{{ $msg->id }}">{{ $msg->message }}</p>
                                        <div class="flex items-center justify-start mt-1">
                                            <span class="text-[9px] text-slate-400 font-medium tracking-wide">{{ $msg->created_at->format('h:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Input Area -->
                    <div class="p-3 md:p-4 border-t border-slate-200/50 dark:border-white/5 bg-white/70 dark:bg-[#111]/70 backdrop-blur-xl shrink-0 pb-6 md:pb-4">
                        <form @submit.prevent="submitMessage" class="flex flex-col gap-2">
                            <div class="flex items-center justify-between px-2" x-show="editId" style="display: none;">
                                <span class="text-xs font-bold text-indigo-600 flex items-center gap-1"><span class="material-symbols-rounded text-[14px]">edit</span> Editing message</span>
                                <button type="button" @click="editId = null; msg = '';" class="text-xs text-slate-400 hover:text-slate-600 uppercase tracking-widest font-bold">Cancel</button>
                            </div>
                            <div class="flex gap-2 items-center">
                                <textarea x-ref="msgInput" x-model="msg" @keydown.enter.prevent="submitMessage" name="message" rows="1" placeholder="Write your reply..." data-no-hint="true" class="flex-1 px-5 py-3 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-full text-[13px] focus:outline-none focus:border-red-500 focus:ring-1 ring-red-500 text-slate-900 dark:text-white transition-all shadow-sm resize-none custom-scrollbar min-h-[44px] max-h-[120px]"></textarea>
                                <button type="submit" :disabled="isSending" class="w-[44px] h-[44px] flex items-center justify-center bg-slate-900 dark:bg-[#e04f3e] hover:bg-slate-800 dark:hover:bg-red-600 disabled:opacity-50 text-white rounded-full shadow-md transition-all shrink-0 active:scale-95 group">
                                    <span class="material-symbols-rounded group-hover:-translate-y-0.5 group-hover:translate-x-0.5 transition-transform text-[20px]" x-show="!isSending && !editId">send</span>
                                    <span class="material-symbols-rounded group-hover:-translate-y-0.5 group-hover:translate-x-0.5 transition-transform text-[20px]" x-show="!isSending && editId" style="display: none;">save</span>
                                    <i class="fas fa-spinner fa-spin" x-show="isSending" style="display: none;"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
@foreach($messages as $chatKey => $userMessages)
function chatPanel_{{ $chatKey }}() {
    return {
        msg: '',
        isSending: false,
        editId: null,
        deleteMessage(id) {
            const doDelete = () => {
                fetch('/marketplace/message/delete/' + id, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(res => res.json()).then(data => {
                    if(data.status === 'success') {
                        document.getElementById('msg-'+id).remove();
                    }
                });
            };

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Delete Message?',
                    text: "This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e04f3e',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Delete',
                    background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#0f172a'
                }).then((result) => {
                    if (result.isConfirmed) doDelete();
                });
            } else {
                if (confirm('Delete this message?')) doDelete();
            }
        },
        editMessage(id, text) {
            this.editId = id;
            this.msg = text;
            this.$refs.msgInput.focus();
        },
        submitMessage() {
            if (!this.msg.trim() || this.isSending) return;
            this.isSending = true;
            if (this.editId) {
                fetch('/marketplace/message/update/' + this.editId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message: this.msg })
                }).then(res => res.json()).then(data => {
                    this.isSending = false;
                    if (data.status === 'success') {
                        document.getElementById('msg-text-'+this.editId).innerText = data.data.message;
                        this.msg = '';
                        this.editId = null;
                    }
                }).catch(() => this.isSending = false);
            } else {
                fetch('{{ route("marketplace.message.reply", $client->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message: this.msg, user_id: '{{ $chatKey }}' })
                }).then(res => res.json()).then(data => {
                    this.isSending = false;
                    if (data.status === 'success') {
                        this.msg = '';
                        var container = document.getElementById('chat-messages-{{ $chatKey }}');
                        var msgId = data.message.id;
                        var safeText = data.message.message.replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        var html = '<div class="flex justify-end items-center gap-1" id="msg-' + msgId + '">'
                            + '<div class="bg-[#e04f3e] text-white p-3.5 md:p-4 rounded-2xl rounded-tr-sm max-w-[85%] md:max-w-[70%] shadow-md flex flex-col">'
                            + '<p class="text-[13px] leading-relaxed" id="msg-text-' + msgId + '">' + safeText + '</p>'
                            + '<div class="flex items-center justify-end gap-1.5 mt-1">'
                            + '<span class="text-[9px] text-red-200 font-medium tracking-wide">Just now</span>'
                            + '<div class="relative shrink-0">'
                            + '<button onclick="toggleMsgMenu(this)" class="w-5 h-5 rounded-full flex items-center justify-center text-white/70 hover:text-white hover:bg-black/10 transition-all">'
                            + '<span class="material-symbols-rounded text-[15px]">more_vert</span>'
                            + '</button>'
                            + '<div class="msg-dropdown hidden absolute right-0 bottom-full mb-1 bg-white dark:bg-[#1A1A1A] rounded-xl shadow-lg border border-slate-100 dark:border-white/10 py-1 min-w-[120px] z-30">'
                            + '<button onclick="editMsgFromDom(' + msgId + '); closeMsgMenus();" class="w-full flex items-center gap-2.5 px-3.5 py-2 text-[12px] font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors text-left">'
                            + '<span class="material-symbols-rounded text-[15px] text-indigo-500">edit</span> Edit'
                            + '</button>'
                            + '<button onclick="deleteMsgFromDom(' + msgId + '); closeMsgMenus();" class="w-full flex items-center gap-2.5 px-3.5 py-2 text-[12px] font-semibold text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors text-left">'
                            + '<span class="material-symbols-rounded text-[15px]">delete</span> Delete'
                            + '</button>'
                            + '</div>'
                            + '</div>'
                            + '</div>'
                            + '</div>'
                            + '</div>';
                        container.insertAdjacentHTML('beforeend', html);
                        container.scrollTop = container.scrollHeight;
                    }
                }).catch(() => this.isSending = false);
            }
        }
    };
}
@endforeach

// Global helpers for dynamically injected messages
function editMsgFromDom(msgId) {
    var msgEl = document.getElementById('msg-text-' + msgId);
    if (!msgEl) return;
    var text = msgEl.innerText;
    // Find the closest Alpine chat panel component
    var chatDiv = msgEl.closest('[x-data]');
    if (chatDiv && chatDiv.__x) {
        chatDiv.__x.$data.editId = msgId;
        chatDiv.__x.$data.msg = text;
        chatDiv.querySelector('[x-ref="msgInput"]').focus();
    } else if (chatDiv && window.Alpine) {
        // Alpine v3
        var data = Alpine.$data(chatDiv);
        data.editId = msgId;
        data.msg = text;
        chatDiv.querySelector('[x-ref="msgInput"]').focus();
    }
}

function deleteMsgFromDom(msgId) {
    const doDelete = () => {
        var csrfToken = document.querySelector('meta[name="csrf-token"]');
        var token = csrfToken ? csrfToken.content : '';
        fetch('/marketplace/message/delete/' + msgId, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
        }).then(function(res) { return res.json(); }).then(function(data) {
            if (data.status === 'success') {
                var el = document.getElementById('msg-' + msgId);
                if (el) el.remove();
            }
        });
    };

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete Message?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e04f3e',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Delete',
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#0f172a'
        }).then((result) => {
            if (result.isConfirmed) doDelete();
        });
    } else {
        if (confirm('Delete this message?')) doDelete();
    }
}

function toggleMsgMenu(btn) {
    closeMsgMenus();
    var dropdown = btn.parentElement.querySelector('.msg-dropdown');
    if (dropdown) dropdown.classList.toggle('hidden');
}

function closeMsgMenus() {
    document.querySelectorAll('.msg-dropdown').forEach(function(el) {
        el.classList.add('hidden');
    });
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.msg-dropdown') && !e.target.closest('[onclick*="toggleMsgMenu"]')) {
        closeMsgMenus();
    }
});
</script>

