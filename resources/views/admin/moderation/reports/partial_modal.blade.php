{{-- Handle Modal --}}
<div id="handleModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 b-radius--10 shadow-lg bg--white overflow-hidden">
            <div class="modal-header border-bottom-0 pt-4 px-4 pb-0 bg-transparent">
                <h5 class="modal-title fw-black text-slate-800 uppercase tracking-tighter d-flex align-items-center gap-2 m-0">
                    <span class="material-symbols-rounded text-danger text-[24px]">gavel</span>
                    Process Report
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="handleForm">
                @csrf
                <div class="modal-body p-4">
                    <div id="target-content-section" class="bg-slate-50 border border-slate-100 rounded-3 p-3 mb-4 shadow-sm">
                        <p class="text-[10px] fw-black text-slate-400 uppercase tracking-widest mb-2">Target Content</p>
                        <div class="d-flex align-items-center gap-3">
                            <img id="video-thumb" src="" class="w-16 h-10 rounded-2 object-cover shadow-sm">
                            <div class="min-w-0">
                                <h6 id="video-title" class="text-[12px] fw-black text-slate-900 uppercase line-clamp-1 mb-0"></h6>
                                <p class="text-[9px] fw-bold text-slate-400 uppercase tracking-widest m-0">Reported Video</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 border border-slate-100 rounded-3 p-3 mb-4 shadow-sm">
                        <p class="text-[10px] fw-black text-slate-400 uppercase tracking-widest mb-2">Report Violation</p>
                        <div id="report-reason" class="text-sm text-slate-700 fw-bold "></div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="text-[11px] fw-black text-slate-800 uppercase tracking-widest mb-2 d-block">Admin Decision</label>
                        <select name="status" class="form-control border-slate-200 rounded-3 shadow-none bg-white fw-bold text-slate-700 px-3 py-2">
                            @if(!Route::is('admin.moderation.reports.reels'))
                                <option value="resolved">Accept: Issue Copyright Strike & Hide</option>
                            @endif
                            <option value="age_restricted">Accept: Age Restrict Content</option>
                            <option value="warning">Accept: Issue Warning (No Strike)</option>
                            <option value="rejected">Reject Report (Keep Content)</option>
                            <option value="appeal">Move to Appeals</option>
                        </select>
                    </div>

                    <div class="form-group mb-0">
                        <label class="text-[11px] fw-black text-slate-800 uppercase tracking-widest mb-2 d-block">Admin Feedback</label>
                        <textarea name="feedback" class="form-control border-slate-200 rounded-3 shadow-none bg-white p-3 text-sm" rows="3" placeholder="Explain your decision (Optional)..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4 pt-0 d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-bold text-[11px] uppercase tracking-widest flex-grow-1 m-0" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger gradient-orange border-0 shadow-sm shadow-orange-500/20 rounded-3 px-4 py-2 fw-black text-[11px] uppercase tracking-widest flex-grow-1 m-0">Confirm Decision</button>
                </div>
            </form>
        </div>
    </div>
</div>

