@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-10 animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-24">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-6 lg:px-0">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 rounded-3xl bg-blue-500/10 flex items-center justify-center text-blue-500 shadow-xl border border-blue-500/20">
                <span class="material-symbols-rounded text-3xl">support_agent</span>
            </div>
            <div>
                <div class="flex items-center gap-3">
                    @php echo $ticket->statusBadge; @endphp
                    <span class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em]">Ref: #{{ $ticket->ticket }}</span>
                </div>
                <h3 class="text-2xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none mt-2">{{ $ticket->subject }}</h3>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            @if ($ticket->status != Status::TICKET_CLOSE)
                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.ticket.close', $ticket->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="replayTicket" value="2">
                        <button type="submit" class="h-12 px-6 rounded-2xl bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-widest flex items-center gap-2 hover:bg-emerald-500 hover:text-white transition-all shadow-sm border border-emerald-500/20">
                            <span class="material-symbols-rounded text-lg">check_circle</span> Mark Completed
                        </button>
                    </form>
                    <button class="h-12 px-6 rounded-2xl bg-rose-500/10 text-rose-500 text-[10px] font-black uppercase tracking-widest flex items-center gap-2 hover:bg-rose-500 hover:text-white transition-all shadow-sm border border-rose-500/20" data-bs-toggle="modal" data-bs-target="#DelModal">
                        <span class="material-symbols-rounded text-lg">cancel</span> Mark Rejected
                    </button>
                </div>
            @endif
            <a href="{{ route('admin.ticket.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-400 hover:text-rose-500 flex items-center justify-center transition-all shadow-sm">
                <span class="material-symbols-rounded text-xl">close</span>
            </a>
        </div>
    </div>

    <!-- Reply Input Section -->
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/5 rounded-[3rem] p-10 shadow-2xl relative overflow-hidden ring-1 ring-white/5">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500/5 rounded-full blur-[100px] pointer-events-none"></div>
        
        <form action="{{ route('admin.ticket.reply', $ticket->id) }}" enctype="multipart/form-data" method="post" class="relative z-10 space-y-8 disableSubmission">
            @csrf
            <x-textarea name="message" label="Your Resolution" placeholder="Draft your response here..." rows="5" required="true" icon="history_edu" hint="Provide a clear and professional resolution to the client's inquiry." />
            
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 pt-8 border-t border-slate-100 dark:border-white/5">
                <div class="flex-grow space-y-6">
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-[10px] font-black text-slate-500 dark:text-white/30 uppercase tracking-[0.2em] ml-2">
                            <span class="w-1 h-1 rounded-full bg-blue-500"></span>
                            Supporting Artifacts
                        </label>
                        <button type="button" class="h-10 px-6 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/60 text-[9px] font-black uppercase tracking-widest flex items-center gap-2 hover:bg-blue-600 hover:text-white transition-all addAttachment">
                            <span class="material-symbols-rounded text-sm">attach_file</span> Add File
                        </button>
                    </div>
                    <p class="text-[9px] font-bold text-blue-500/60 uppercase tracking-widest ml-2 ">Max 5 files | {{ convertToReadableSize(ini_get('upload_max_filesize')) }} each | JPG, PNG, PDF, DOCX</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 fileUploadsContainer"></div>
                </div>

                <button class="h-20 px-12 rounded-[2.5rem] orange-gradient-primary text-white text-[12px] font-black uppercase tracking-[0.3em] shadow-2xl shadow-orange-500/40 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-4 shrink-0" type="submit" name="replayTicket" value="1">
                    <span class="material-symbols-rounded text-2xl group-hover:animate-spin">send</span>
                    Dispatch Reply
                </button>
            </div>
        </form>
    </div>

    <!-- Communication History -->
    <div class="space-y-8">
        <h4 class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] ml-6">Thread Chronology</h4>
        
        <div class="space-y-6">
            @foreach ($messages as $message)
                @if ($message->admin_id == 0)
                    <!-- Client Message -->
                    <div class="flex flex-col gap-4 animate-in slide-in-from-left-4 duration-500">
                        <div class="flex items-center gap-4 ml-4">
                            <div class="w-10 h-10 rounded-2xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400">
                                <span class="material-symbols-rounded text-xl">person</span>
                            </div>
                            <div>
                                <h5 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-wider">{{ $ticket->fullname }}</h5>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ showDateTime($message->created_at, 'l, dS F Y @ h:i a') }}</p>
                            </div>
                        </div>
                        <div class="max-w-4xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 p-8 rounded-[2.5rem] rounded-tl-none shadow-sm relative group">
                            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">{{ $message->message }}</p>
                            
                            @if ($message->attachments->count() > 0)
                                <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach ($message->attachments as $k => $attachment)
                                        <div class="group/attachment relative">
                                            @if($attachment->is_image)
                                                <div class="aspect-video rounded-2xl overflow-hidden bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 mb-2">
                                                    <img src="{{ getImage($attachment->attachment) }}" class="w-full h-full object-cover group-hover/attachment:scale-110 transition-all duration-500 cursor-zoom-in" onclick="window.open(this.src, '_blank')">
                                                </div>
                                            @endif
                                            <a href="{{ $attachment->download_url }}" class="flex items-center gap-3 h-10 px-4 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5 text-[9px] font-black text-slate-500 dark:text-white/40 uppercase tracking-widest hover:border-blue-500/50 transition-all">
                                                <span class="material-symbols-rounded text-sm">description</span>
                                                <span class="truncate">File {{ ++$k }}</span>
                                                <span class="material-symbols-rounded text-sm ml-auto opacity-0 group-hover/attachment:opacity-100 transition-opacity">download</span>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <button class="absolute -{{ $message->admin_id == 0 ? 'right' : 'left' }}-4 -top-4 w-10 h-10 rounded-full bg-rose-500 text-white opacity-0 group-hover:opacity-100 transition-all shadow-xl hover:scale-110 flex items-center justify-center confirmationBtn" data-question="@lang('Are you sure to delete this message?')" data-action="{{ route('admin.ticket.message.delete', $message->id) }}" data-method="POST">
                                <span class="material-symbols-rounded text-lg">delete</span>
                            </button>
                        </div>
                    </div>
                @else
                    <!-- Staff Message -->
                    <div class="flex flex-col items-end gap-4 animate-in slide-in-from-right-4 duration-500">
                        <div class="flex items-center gap-4 mr-4">
                            <div class="text-right">
                                <h5 class="text-[11px] font-black text-blue-500 uppercase tracking-wider">{{ $message?->admin?->name }} <span class="ml-2 text-[9px] font-black bg-blue-500/10 text-blue-500 px-2 py-0.5 rounded-lg border border-blue-500/20">STAFF</span></h5>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ showDateTime($message->created_at, 'l, dS F Y @ h:i a') }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 border border-blue-500/20">
                                <span class="material-symbols-rounded text-xl">verified_user</span>
                            </div>
                        </div>
                        <div class="max-w-4xl bg-blue-600/5 dark:bg-blue-600/10 border border-blue-500/20 p-8 rounded-[2.5rem] rounded-tr-none shadow-sm relative group">
                            <p class="text-sm text-slate-700 dark:text-blue-100/80 leading-relaxed">{{ $message->message }}</p>
                            
                            @if ($message->attachments->count() > 0)
                                <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach ($message->attachments as $k => $attachment)
                                        <div class="group/attachment relative">
                                            @if($attachment->is_image)
                                                <div class="aspect-video rounded-2xl overflow-hidden bg-blue-500/5 border border-blue-500/20 mb-2">
                                                    <img src="{{ getImage($attachment->attachment) }}" class="w-full h-full object-cover group-hover/attachment:scale-110 transition-all duration-500 cursor-zoom-in" onclick="window.open(this.src, '_blank')">
                                                </div>
                                            @endif
                                            <a href="{{ $attachment->download_url }}" class="flex items-center gap-3 h-10 px-4 rounded-xl bg-blue-500/10 border border-blue-500/20 text-[9px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest hover:bg-blue-500 hover:text-white transition-all">
                                                <span class="material-symbols-rounded text-sm">description</span>
                                                <span class="truncate">File {{ ++$k }}</span>
                                                <span class="material-symbols-rounded text-sm ml-auto opacity-0 group-hover/attachment:opacity-100 transition-opacity">download</span>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <button class="absolute -left-4 -top-4 w-10 h-10 rounded-full bg-rose-500 text-white opacity-0 group-hover:opacity-100 transition-all shadow-xl hover:scale-110 flex items-center justify-center confirmationBtn" data-question="@lang('Are you sure to delete this message?')" data-action="{{ route('admin.ticket.message.delete', $message->id) }}" data-method="POST">
                                <span class="material-symbols-rounded text-lg">delete</span>
                            </button>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>




    <div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Close Support Ticket!')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('Are you want to close this support ticket?')</p>
                </div>
                <div class="modal-footer">
                    <form method="post" action="{{ route('admin.ticket.close', $ticket->id) }}">
                        @csrf
                        <input type="hidden" name="replayTicket" value="2">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal"> @lang('No') </button>
                        <button type="submit" class="btn btn--primary"> @lang('Yes') </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection




@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.ticket.index') }}" />
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.delete-message').on('click', function(e) {
                $('.message_id').val($(this).data('id'));
            })
            var fileAdded = 0;
            $('.addAttachment').on('click', function() {
                fileAdded++;
                if (fileAdded == 5) {
                    $(this).attr('disabled',true)
                }
                $(".fileUploadsContainer").append(`
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 removeFileInput">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="file" name="attachments[]" class="form-control" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
                            <button type="button" class="input-group-text removeFile bg--danger border--danger"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                </div>
                `)
            });

            $(document).on('click', '.removeFile', function() {
                $('.addAttachment').removeAttr('disabled',true)
                fileAdded--;
                $(this).closest('.removeFileInput').remove();
            });
        })(jQuery);
    </script>
@endpush

