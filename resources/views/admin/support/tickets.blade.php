@extends('admin.layouts.app')

@section('content')
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
        @include('admin.components.header-toolbar', [
            'title' => 'Support Tickets'
        ])
        <div class="px-6 pt-6">
            @include('admin.components.table-toolbar')
        </div>
        <div class="overflow-x-auto scrollbar-hide" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
            <table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Subject')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Submitted By')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Status')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Priority')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Last Reply')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($items as $item)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                        <a href="{{ route('admin.ticket.view', $item->id) }}" class="fw-bold"> [@lang('Ticket')#{{ $item->ticket }}] {{ strLimit($item->subject,30) }} </a>
                                    </td>

                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                        @if($item->user_id)
                                        <a href="{{ route('admin.users.detail', $item->user_id)}}"> {{@$item->user->fullname}}</a>
                                        @else
                                            <p class="fw-bold"> {{$item->name}}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                        @php echo $item->statusBadge; @endphp
                                    </td>
                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                        @if($item->priority == \App\Constants\Status::PRIORITY_LOW)
                                            <span class="badge badge--dark">@lang('Low')</span>
                                        @elseif($item->priority == \App\Constants\Status::PRIORITY_MEDIUM)
                                            <span class="badge  badge--warning">@lang('Medium')</span>
                                        @elseif($item->priority == \App\Constants\Status::PRIORITY_HIGH)
                                            <span class="badge badge--danger">@lang('High')</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                        {{ diffForHumans($item->last_reply) }}
                                    </td>

                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                        <div class="flex flex-wrap gap-2 w-max">
                                            <button type="button" class="px-3 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 shadow-sm openHub" data-id="{{ $item->id }}" data-ticket="{{ $item->ticket }}">
                                                <span class="material-symbols-rounded text-sm">reply</span> @lang('Reply')
                                            </button>
                                            <button type="button" class="px-3 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 shadow-sm openHub" data-id="{{ $item->id }}" data-ticket="{{ $item->ticket }}" data-tab="status">
                                                <span class="material-symbols-rounded text-sm">settings</span> @lang('Status')
                                            </button>
                                            <a href="{{ route('admin.ticket.view', $item->id) }}" class="px-3 h-8 rounded-lg bg-blue-500 text-white flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 shadow-sm">
                                                <span class="material-symbols-rounded text-sm">visibility</span> @lang('Details')
                                            </a>
                                            <button class="px-3 h-8 rounded-lg bg-rose-600 text-white flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest transition-all hover:scale-105 shadow-sm confirmationBtn" data-action="{{ Route::has('admin.ticket.destroy') ? route('admin.ticket.destroy', $item->id ?? 0) : (Route::has('admin.ticket.delete') ? route('admin.ticket.delete', $item->id ?? 0) : 'javascript:void(0)') }}" data-method="POST" data-question="@lang('Are you sure you want to delete this record?')" title="@lang('Delete')">
                                                <span class="material-symbols-rounded text-sm">delete</span> @lang('Delete')
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>

                    <!-- App View -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-6" x-show="$store.viewMode && $store.viewMode.mode === 'app'" style="display: none;">
                        @forelse($items as $item)
                            <div class="group bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-3xl p-6 hover:shadow-xl hover:border-primary-500/30 transition-all duration-300">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <span class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-widest block mb-1">Ticket #{{ $item->ticket }}</span>
                                        <a href="{{ route('admin.ticket.view', $item->id) }}" class="text-[14px] font-black text-slate-800 dark:text-white leading-tight group-hover:text-primary-500 transition-colors">
                                            {{ strLimit($item->subject, 40) }}
                                        </a>
                                    </div>
                                    <div class="ml-4">
                                        @php echo $item->statusBadge; @endphp
                                    </div>
                                </div>

                                <div class="space-y-3 mb-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-500">
                                            <i class="las la-user"></i>
                                        </div>
                                        <div>
                                            <span class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest block">Submitted By</span>
                                            @if($item->user_id)
                                                <a href="{{ route('admin.users.detail', $item->user_id)}}" class="text-[11px] font-bold text-slate-700 dark:text-white/70">{{@$item->user->fullname}}</a>
                                            @else
                                                <span class="text-[11px] font-bold text-slate-700 dark:text-white/70">{{$item->name}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center bg-slate-50 dark:bg-white/[0.02] rounded-2xl p-3">
                                        <div>
                                            <span class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest block">Priority</span>
                                            @if($item->priority == \App\Constants\Status::PRIORITY_LOW)
                                                <span class="badge badge--dark">@lang('Low')</span>
                                            @elseif($item->priority == \App\Constants\Status::PRIORITY_MEDIUM)
                                                <span class="badge badge--warning">@lang('Medium')</span>
                                            @elseif($item->priority == \App\Constants\Status::PRIORITY_HIGH)
                                                <span class="badge badge--danger">@lang('High')</span>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <span class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest block">Last Reply</span>
                                            <span class="text-[11px] font-bold text-slate-700 dark:text-white/70">{{ diffForHumans($item->last_reply) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-2 pt-4 border-t border-slate-100 dark:border-white/5">
                                    <button type="button" class="flex-1 h-10 px-3 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center gap-2 hover:bg-emerald-500 hover:text-white transition-all openHub" data-id="{{ $item->id }}" data-ticket="{{ $item->ticket }}">
                                        <i class="las la-reply text-lg"></i>
                                        <span class="text-[9px] font-black uppercase tracking-widest">Reply</span>
                                    </button>
                                    <button type="button" class="flex-1 h-10 px-3 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center gap-2 hover:bg-amber-500 hover:text-white transition-all openHub" data-id="{{ $item->id }}" data-ticket="{{ $item->ticket }}" data-tab="status">
                                        <i class="las la-cog text-lg"></i>
                                        <span class="text-[9px] font-black uppercase tracking-widest">Status</span>
                                    </button>
                                    <a href="{{ route('admin.ticket.view', $item->id) }}" class="flex-1 inline-flex justify-center items-center gap-2 h-10 px-3 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-white text-[9px] font-black uppercase tracking-widest hover:bg-primary-500 hover:text-white transition-all">
                                        <i class="las la-desktop text-lg"></i> Details
                                    </a>
                                    <button class="flex-1 h-10 px-3 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center gap-2 hover:bg-rose-500 hover:text-white transition-all confirmationBtn" data-action="{{ Route::has('admin.ticket.destroy') ? route('admin.ticket.destroy', $item->id ?? 0) : (Route::has('admin.ticket.delete') ? route('admin.ticket.delete', $item->id ?? 0) : 'javascript:void(0)') }}" data-method="POST" data-question="@lang('Are you sure you want to delete this record?')" title="@lang('Delete')">
                                        <i class="las la-trash text-lg"></i>
                                        <span class="text-[9px] font-black uppercase tracking-widest">Delete</span>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full flex flex-col items-center justify-center p-12 text-center">
                                <div class="w-24 h-24 bg-slate-100 dark:bg-white/5 rounded-full flex items-center justify-center mb-4">
                                    <i class="las la-inbox text-4xl text-slate-400 dark:text-white/20"></i>
                                </div>
                                <h4 class="text-lg font-black text-slate-700 dark:text-white uppercase tracking-widest mb-1">{{ __($emptyMessage) }}</h4>
                                <p class="text-[11px] font-bold text-slate-400 dark:text-white/40">No records found for this query.</p>
                            </div>
                        @endforelse
                    </div>

                </div>
                @if ($items->hasPages())
                <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                    {{ paginateLinks($items) }}
                </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>
    <x-confirmation-modal />

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- ADMIN SUPPORT HUB MODAL                                --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div id="adminHubModal" x-data="adminTicketHub()" x-show="open" x-cloak
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closeHub()"></div>
        <div class="relative w-full max-w-2xl max-h-[90vh] bg-white dark:bg-[#141414] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-2xl flex flex-col overflow-hidden"
             x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             @click.away="closeHub()">

            {{-- Header --}}
            <div class="flex items-center justify-between px-8 py-5 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02] flex-shrink-0">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-500/10 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-rounded text-xl text-emerald-500">hub</span>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-base font-black text-slate-800 dark:text-white tracking-tight truncate" x-text="'Ticket #' + ticketNumber"></h3>
                        <p class="text-[9px] font-black uppercase tracking-widest truncate text-slate-400" x-text="ticketUser"></p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    {{-- Status Dropdown --}}
                    <div class="relative">
                        <button @click="statusOpen = !statusOpen" class="h-9 px-4 rounded-xl text-[9px] font-black uppercase tracking-widest flex items-center gap-2 transition-all border"
                                :class="statusBtnClass">
                            <span x-text="statusLabel"></span>
                            <span class="material-symbols-rounded text-sm">expand_more</span>
                        </button>
                        <div x-show="statusOpen" @click.away="statusOpen = false" x-transition
                             class="absolute right-0 top-full mt-2 w-44 bg-white dark:bg-[#1A1A1A] border border-slate-200 dark:border-white/10 rounded-2xl shadow-xl overflow-hidden z-50">
                            <button @click="updateStatus(0); statusOpen = false" class="w-full px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 transition-colors flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Open
                            </button>
                            <button @click="updateStatus(1); statusOpen = false" class="w-full px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span> Answered
                            </button>
                            <button @click="updateStatus(2); statusOpen = false" class="w-full px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-400"></span> Customer Reply
                            </button>
                            <button @click="updateStatus(3); statusOpen = false" class="w-full px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-slate-400 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span> Closed
                            </button>
                            <button @click="updateStatus(4); statusOpen = false" class="w-full px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span> Rejected
                            </button>
                        </div>
                    </div>
                    {{-- Notification Toggle --}}
                    <button @click="notif = !notif" class="w-9 h-9 rounded-xl flex items-center justify-center transition-all"
                            :class="notif ? 'bg-emerald-500/10 text-emerald-500' : 'bg-slate-100 dark:bg-white/5 text-slate-400'" title="Toggle notifications">
                        <span class="material-symbols-rounded text-lg" x-text="notif ? 'notifications_active' : 'notifications_off'"></span>
                    </button>
                    {{-- Close --}}
                    <button @click="closeHub()" class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 hover:bg-red-500 hover:text-white transition-all">
                        <span class="material-symbols-rounded text-lg">close</span>
                    </button>
                </div>
            </div>

            {{-- Loading --}}
            <template x-if="loading">
                <div class="flex-1 flex items-center justify-center py-20">
                    <div class="text-center">
                        <div class="w-12 h-12 border-4 border-slate-200 dark:border-white/10 border-t-emerald-500 rounded-full animate-spin mx-auto mb-4"></div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Loading conversation...</p>
                    </div>
                </div>
            </template>

            {{-- Messages --}}
            <div x-ref="messagesContainer" x-show="!loading" class="flex-1 overflow-y-auto px-8 py-6 space-y-4 scroll-smooth" style="max-height: 50vh;">
                <template x-for="msg in messages" :key="msg.id">
                    <div class="flex gap-3" :class="msg.is_admin ? 'flex-row-reverse' : ''">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" :class="msg.is_admin ? 'bg-emerald-500 text-white' : 'bg-blue-500 text-white'">
                            <span class="material-symbols-rounded text-lg" x-text="msg.is_admin ? 'support_agent' : 'person'"></span>
                        </div>
                        <div class="max-w-[75%] min-w-0">
                            <div class="rounded-2xl px-5 py-3 shadow-sm" :class="msg.is_admin ? 'bg-emerald-500 text-white rounded-tr-md' : 'bg-slate-100 dark:bg-white/5 rounded-tl-md'">
                                <p class="text-[13px] font-semibold leading-relaxed whitespace-pre-line break-words" x-text="msg.message"></p>
                            </div>
                            <template x-if="msg.attachments && msg.attachments.length > 0">
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <template x-for="att in msg.attachments" :key="att.id">
                                        <div class="group/att relative">
                                            <template x-if="att.is_image && att.image_url">
                                                <div class="w-28 aspect-video rounded-xl overflow-hidden bg-slate-100 dark:bg-white/5 border border-slate-100 dark:border-white/10 mb-1">
                                                    <img :src="att.image_url" class="w-full h-full object-cover cursor-zoom-in" @click="window.open(att.image_url, '_blank')">
                                                </div>
                                            </template>
                                            <a :href="att.url" class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 dark:bg-white/5 rounded-lg border border-slate-100 dark:border-white/10 text-[9px] font-black text-slate-500 uppercase tracking-widest hover:border-emerald-500 transition-all">
                                                <span class="material-symbols-rounded text-sm">download</span> File
                                            </a>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <p class="text-[9px] font-bold mt-1 px-1 uppercase tracking-widest" :class="msg.is_admin ? 'text-emerald-300' : 'text-slate-400'" x-text="msg.sender + ' · ' + msg.created_at"></p>
                        </div>
                    </div>
                </template>
                <template x-if="!loading && messages.length === 0">
                    <div class="text-center py-12">
                        <span class="material-symbols-rounded text-5xl text-slate-200 dark:text-white/5">forum</span>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-3">No messages yet</p>
                    </div>
                </template>
            </div>

            {{-- Reply Box --}}
            <div x-show="!loading && ticketStatus < 3" class="px-8 py-5 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02] flex-shrink-0">
                <form @submit.prevent="sendReply()" class="flex items-end gap-3">
                    <div class="flex-1">
                        <textarea x-model="replyMessage" rows="2" placeholder="Admin reply..." class="w-full bg-white dark:bg-[#1A1A1A] border border-slate-200 dark:border-white/10 rounded-2xl px-5 py-3 text-sm font-bold text-slate-800 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none resize-none" :disabled="sending" @keydown.ctrl.enter="sendReply()"></textarea>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <div class="relative">
                            <input type="file" x-ref="hubFiles" @change="handleFiles($event)" multiple accept="image/*,.pdf,.doc,.docx" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="w-11 h-11 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 hover:text-emerald-500 transition-colors cursor-pointer">
                                <span class="material-symbols-rounded text-xl">attach_file</span>
                            </div>
                        </div>
                        <button type="submit" :disabled="sending || !replyMessage.trim()" class="w-11 h-11 rounded-xl bg-emerald-500 text-white flex items-center justify-center hover:bg-emerald-600 transition-all disabled:opacity-40 disabled:cursor-not-allowed shadow-lg shadow-emerald-500/30">
                            <span class="material-symbols-rounded text-xl" x-text="sending ? 'hourglass_top' : 'send'"></span>
                        </button>
                    </div>
                </form>
                <template x-if="selectedFiles.length > 0">
                    <div class="flex flex-wrap gap-2 mt-3">
                        <template x-for="(file, idx) in selectedFiles" :key="idx">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-widest">
                                <span x-text="file.name.substring(0, 15)"></span>
                                <button type="button" @click="removeFile(idx)" class="hover:text-red-500">&times;</button>
                            </span>
                        </template>
                    </div>
                </template>
            </div>

            {{-- Terminal notice --}}
            <div x-show="!loading && ticketStatus >= 3" class="px-8 py-5 border-t border-slate-100 dark:border-white/5 bg-amber-500/5 flex-shrink-0">
                <div class="flex items-center justify-center gap-3">
                    <span class="material-symbols-rounded text-amber-500" x-text="ticketStatus == 3 ? 'lock' : 'cancel'"></span>
                    <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest" x-text="ticketStatus == 3 ? 'Ticket closed — change status to reply' : 'Ticket rejected — change status to reply'"></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Search here..." />
@endpush

@push('script')
<script>
function adminTicketHub() {
    return {
        open: false, loading: false, sending: false, notif: false, statusOpen: false,
        ticketId: null, ticketNumber: '', ticketStatus: 1, ticketUser: '',
        messages: [], replyMessage: '', selectedFiles: [],

        get statusLabel() {
            return { 0: 'Open', 1: 'Answered', 2: 'Customer Reply', 3: 'Closed', 4: 'Rejected' }[this.ticketStatus] || 'Unknown';
        },
        get statusColor() {
            return { 0: 'text-emerald-500', 1: 'text-blue-500', 2: 'text-blue-400', 3: 'text-gray-400', 4: 'text-rose-500' }[this.ticketStatus] || 'text-slate-400';
        },
        get statusBtnClass() {
            const map = {
                0: 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                1: 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                2: 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                3: 'bg-slate-100 dark:bg-white/5 text-slate-500 border-slate-200 dark:border-white/10',
                4: 'bg-rose-500/10 text-rose-500 border-rose-500/20',
            };
            return map[this.ticketStatus] || map[3];
        },

        async openHub(id, ticket, autoStatus = false) {
            this.ticketId = id; this.ticketNumber = ticket;
            this.open = true; this.loading = true; this.statusOpen = autoStatus;
            this.messages = []; this.replyMessage = ''; this.selectedFiles = [];
            try {
                const res = await fetch(`/admin/ticket/hub-data/${id}`, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) throw new Error('Failed');
                const data = await res.json();
                this.messages = data.messages || [];
                this.ticketStatus = data.ticket?.status ?? 1;
                this.ticketNumber = data.ticket?.ticket || ticket;
                this.ticketUser = data.ticket?.user || '';
                this.$nextTick(() => this.scrollToBottom());
            } catch (e) { console.error('Hub load error:', e); }
            finally { this.loading = false; }
        },

        closeHub() { this.open = false; this.ticketId = null; this.messages = []; },

        async sendReply() {
            if (!this.replyMessage.trim() || this.sending) return;
            this.sending = true;
            try {
                const fd = new FormData();
                fd.append('message', this.replyMessage);
                fd.append('_token', '{{ csrf_token() }}');
                this.selectedFiles.forEach(f => fd.append('attachments[]', f));
                const res = await fetch(`/admin/ticket/reply/${this.ticketId}`, { method: 'POST', body: fd });
                if (res.ok) {
                    this.replyMessage = ''; this.selectedFiles = [];
                    if (this.$refs.hubFiles) this.$refs.hubFiles.value = '';
                    await this.openHub(this.ticketId, this.ticketNumber);
                }
            } catch (e) { console.error('Reply error:', e); }
            finally { this.sending = false; }
        },

        async updateStatus(status) {
            try {
                const fd = new FormData();
                fd.append('status', status);
                fd.append('_token', '{{ csrf_token() }}');
                const res = await fetch(`/admin/ticket/hub-status/${this.ticketId}`, { method: 'POST', body: fd });
                if (res.ok) { this.ticketStatus = status; }
            } catch (e) { console.error('Status update error:', e); }
        },

        handleFiles(e) { this.selectedFiles = Array.from(e.target.files).slice(0, 4); },
        removeFile(idx) { this.selectedFiles.splice(idx, 1); },
        scrollToBottom() { const c = this.$refs.messagesContainer; if (c) c.scrollTop = c.scrollHeight; }
    };
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.openHub').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id, ticket = this.dataset.ticket, isStatus = this.dataset.tab === 'status';
            const hubEl = document.getElementById('adminHubModal');
            if (hubEl && hubEl._x_dataStack) {
                hubEl._x_dataStack[0].openHub(id, ticket, isStatus);
            }
        });
    });
});
</script>
@endpush
