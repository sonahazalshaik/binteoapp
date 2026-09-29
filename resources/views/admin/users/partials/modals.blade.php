<!-- Custom Modal Container Styles -->
<style>
    .custom-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(8px);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        animation: fadeInModal 0.2s ease-out;
    }

    .custom-modal-content {
        background: #fff;
        width: 100%;
        max-width: 450px;
        border-radius: 30px;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        animation: slideUpModal 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
    }

    .modal-header-accent {
        height: 100px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 0 40px;
        color: #fff;
        position: relative;
    }
    .modal-header-accent::after {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.1);
        mix-blend-mode: overlay;
    }

    .modal-title-main { font-size: 1.25rem; font-weight: 900; text-transform: uppercase; margin: 0; font-style: ; letter-spacing: -0.5px; }
    .modal-subtitle-main { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; margin: 5px 0 0; opacity: 0.8; letter-spacing: 1px; }

    .modal-body-main { padding: 40px; }
    .modal-footer-main { 
        padding: 25px 40px; 
        background: #f8fafc; 
        border-top: 1px solid #f1f5f9;
        display: flex;
        gap: 12px;
    }

    .modal-form-group { margin-bottom: 25px; }
    .modal-form-group:last-child { margin-bottom: 0; }
    .modal-label-main { display: block; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: #94a3b8; margin-bottom: 10px; letter-spacing: 0.5px; }
    
    .modal-input-main, .modal-select-main, .modal-textarea-main {
        width: 100%;
        padding: 15px;
        border-radius: 14px;
        border: 2px solid #f1f5f9;
        background: #f8fafc;
        font-family: inherit;
        font-size: 0.85rem;
        font-weight: 700;
        color: #1e293b;
        transition: all 0.2s;
    }
    .modal-input-main:focus, .modal-select-main:focus, .modal-textarea-main:focus {
        outline: none; border-color: #6366f1; background: #fff;
    }

    .modal-btn-cancel {
        flex: 1; padding: 14px; border-radius: 12px; border: 2px solid #e2e8f0;
        background: #fff; color: #64748b; font-weight: 800; cursor: pointer;
        font-size: 0.75rem; text-transform: uppercase; transition: all 0.2s;
    }
    .modal-btn-cancel:hover { background: #f8fafc; color: #0f172a; border-color: #cbd5e1; }

    .modal-btn-submit {
        flex: 2; padding: 14px; border-radius: 12px; border: none;
        color: #fff; font-weight: 800; cursor: pointer;
        font-size: 0.75rem; text-transform: uppercase; transition: all 0.2s;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .modal-btn-submit:hover { transform: translateY(-1px); filter: brightness(1.1); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

    .radio-pill-group { display: flex; gap: 10px; margin-bottom: 20px; }
    .radio-pill { flex: 1; position: relative; cursor: pointer; }
    .radio-pill input { position: absolute; opacity: 0; width: 0; height: 0; }
    .radio-pill-content {
        height: 50px; border-radius: 14px; border: 2px solid #f1f5f9;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #94a3b8;
        transition: all 0.2s;
    }
    .radio-pill input:checked + .radio-pill-content.type-add { border-color: #10b981; background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .radio-pill input:checked + .radio-pill-content.type-sub { border-color: #ef4444; background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    .modal-info-box {
        padding: 15px; border-radius: 14px; margin-top: 20px;
        display: flex; gap: 10px; align-items: flex-start;
    }
    .info-box-orange { background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); color: #b45309; }
    .info-box-red { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #b91c1c; }
    .modal-info-box i { font-size: 1.1rem; }
    .modal-info-box p { margin: 0; font-size: 0.65rem; font-weight: 700; line-height: 1.4; text-transform: uppercase; }

    @keyframes fadeInModal { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUpModal { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    [x-cloak] { display: none !important; }
</style>

<!-- Plan Assignment Modal -->
<div x-data="{ 
        show: false, userId: '', userName: '', planType: 'ott',
        activeOttId: '', activeCreatorId: '', ottExpire: '', creatorExpire: '',
        init() { 
            window.addEventListener('open-plan-modal', (e) => { 
                this.userId = e.detail.id; 
                this.userName = e.detail.name; 
                this.activeOttId = e.detail.ottId;
                this.activeCreatorId = e.detail.creatorId;
                this.ottExpire = e.detail.ottExpire;
                this.creatorExpire = e.detail.creatorExpire;
                this.show = true; 
                this.planType = 'ott'; 
            }); 
        }
    }" 
    x-show="show" x-cloak class="custom-modal-backdrop">
    
    <div class="custom-modal-content" @click.away="show = false">
        <form :action="`{{ url('admin/users/add-plan') }}/${userId}`" method="POST">
            @csrf
            <div class="modal-header-accent" style="background: linear-gradient(135deg, #f59e0b, #ea580c);">
                <h3 class="modal-title-main">Assign Premium Node</h3>
                <p class="modal-subtitle-main">Active Targeting: <span x-text="userName"></span></p>
            </div>

            <div class="modal-body-main">
                <div class="radio-pill-group">
                    <label class="radio-pill">
                        <input type="radio" name="plan_type_selection" value="ott" x-model="planType">
                        <div class="radio-pill-content type-add" :class="planType == 'ott' ? 'border-emerald-500 bg-emerald-50 text-emerald-500' : ''">
                            <span class="material-symbols-rounded">live_tv</span> OTT Plans
                        </div>
                    </label>
                    <label class="radio-pill">
                        <input type="radio" name="plan_type_selection" value="creator" x-model="planType">
                        <div class="radio-pill-content type-sub" :class="planType == 'creator' ? 'border-orange-500 bg-orange-50 text-orange-500' : ''">
                            <span class="material-symbols-rounded">stars</span> Creator Plans
                        </div>
                    </label>
                </div>

                <div class="modal-form-group">
                    <label class="modal-label-main">Select Access Tier</label>
                    
                    <div x-show="planType == 'ott'">
                        <div x-show="activeOttId && ottExpire" class="mb-3 p-3 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-800 text-xs flex justify-between items-center">
                            <span><strong class="font-black uppercase tracking-wider text-[10px]">Current OTT Plan Active:</strong></span>
                            <span class="font-bold opacity-80" x-text="'Valid until ' + ottExpire"></span>
                        </div>
                        <select name="plan_id" class="modal-select-main" :required="planType == 'ott'" :disabled="planType != 'ott'">
                            <option value="" disabled selected>-- Choose OTT Plan --</option>
                            @foreach(\App\Models\OttPlan::where('status', 1)->get() as $plan)
                                <option value="{{ $plan->id }}" x-text="activeOttId == '{{ $plan->id }}' ? '{{ $plan->name }} ({{ showAmount($plan->price) }} - {{ $plan->duration }} Days) [Subscribed]' : '{{ $plan->name }} ({{ showAmount($plan->price) }} - {{ $plan->duration }} Days)'"></option>
                            @endforeach
                        </select>
                    </div>

                    <div x-show="planType == 'creator'">
                        <div x-show="activeCreatorId && creatorExpire" class="mb-3 p-3 bg-orange-50 border border-orange-100 rounded-xl text-orange-800 text-xs flex justify-between items-center">
                            <span><strong class="font-black uppercase tracking-wider text-[10px]">Current Creator Plan Active:</strong></span>
                            <span class="font-bold opacity-80" x-text="'Valid until ' + creatorExpire"></span>
                        </div>
                        <select name="plan_id" class="modal-select-main" :required="planType == 'creator'" :disabled="planType != 'creator'">
                            <option value="" disabled selected>-- Choose Creator Plan --</option>
                            @foreach(\App\Models\Plan::active()->get() as $plan)
                                <option value="{{ $plan->id }}" x-text="activeCreatorId == '{{ $plan->id }}' ? '{{ $plan->name }} ({{ showAmount($plan->price) }} - {{ $plan->duration }} Days) [Subscribed]' : '{{ $plan->name }} ({{ showAmount($plan->price) }} - {{ $plan->duration }} Days)'"></option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-info-box info-box-orange">
                    <i class="material-symbols-rounded">info</i>
                    <p>Overwrites current active subscription and resets the expiration cycle for this node.</p>
                </div>
            </div>

            <div class="modal-footer-main">
                <button type="button" @click="show = false" class="modal-btn-cancel">Abort</button>
                <button type="submit" class="modal-btn-submit" style="background: #f59e0b;">
                    <span class="material-symbols-rounded">workspace_premium</span> Commit Plan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Wallet/Balance Modal -->
<div x-data="{ 
        show: false, userId: '', userName: '',
        init() { window.addEventListener('open-wallet-modal', (e) => { this.userId = e.detail.id; this.userName = e.detail.name; this.show = true; }); }
    }" 
    x-show="show" x-cloak class="custom-modal-backdrop">
    
    <div class="custom-modal-content" @click.away="show = false">
        <form :action="`{{ url('admin/users/add-sub-balance') }}/${userId}`" method="POST">
            @csrf
            <div class="modal-header-accent" style="background: linear-gradient(135deg, #0ea5e9, #2563eb);">
                <h3 class="modal-title-main">Manage Financial Vault</h3>
                <p class="modal-subtitle-main">Direct Injection: <span x-text="userName"></span></p>
            </div>

            <div class="modal-body-main">
                <div class="radio-pill-group">
                    <label class="radio-pill">
                        <input type="radio" name="type" value="1" checked>
                        <div class="radio-pill-content type-add">
                            <span class="material-symbols-rounded">add_circle</span> Add Funds
                        </div>
                    </label>
                    <label class="radio-pill">
                        <input type="radio" name="type" value="2">
                        <div class="radio-pill-content type-sub">
                            <span class="material-symbols-rounded">remove_circle</span> Subtract
                        </div>
                    </label>
                </div>

                <div class="modal-form-group">
                    <label class="modal-label-main">Currency Amount</label>
                    <input type="number" step="any" name="amount" required class="modal-input-main" placeholder="0.00">
                </div>

                <div class="modal-form-group">
                    <label class="modal-label-main">Protocol Remark</label>
                    <textarea name="remark" required class="modal-textarea-main" rows="3" placeholder="Enter reason for adjustment..."></textarea>
                </div>
            </div>

            <div class="modal-footer-main">
                <button type="button" @click="show = false" class="modal-btn-cancel">Cancel</button>
                <button type="submit" class="modal-btn-submit" style="background: #2563eb;">
                    <span class="material-symbols-rounded">account_balance_wallet</span> Update Balance
                </button>
            </div>
        </form>
    </div>
</div>

<!-- KYC Rejection Modal -->
<div x-data="{ 
        show: false, userId: '', userName: '',
        init() { window.addEventListener('open-kyc-reject-modal', (e) => { this.userId = e.detail.id; this.userName = e.detail.name; this.show = true; }); }
    }" 
    x-show="show" x-cloak class="custom-modal-backdrop">
    
    <div class="custom-modal-content" @click.away="show = false">
        <form :action="`{{ url('admin/users/kyc-reject') }}/${userId}`" method="POST">
            @csrf
            <div class="modal-header-accent" style="background: linear-gradient(135deg, #ef4444, #991b1b);">
                <h3 class="modal-title-main">Reject Identity Protocol</h3>
                <p class="modal-subtitle-main">Declining: <span x-text="userName"></span></p>
            </div>

            <div class="modal-body-main">
                <div class="modal-form-group">
                    <label class="modal-label-main">Reason for Rejection</label>
                    <textarea name="reason" required class="modal-textarea-main" rows="4" placeholder="Provide detailed feedback..."></textarea>
                </div>

                <div class="modal-info-box info-box-red">
                    <i class="material-symbols-rounded">warning</i>
                    <p>The user will be restricted from monetization until valid documents are provided.</p>
                </div>
            </div>

            <div class="modal-footer-main">
                <button type="button" @click="show = false" class="modal-btn-cancel">Abort</button>
                <button type="submit" class="modal-btn-submit" style="background: #ef4444;">
                    <span class="material-symbols-rounded">cancel</span> Reject KYC
                </button>
            </div>
        </form>
    </div>
</div>

<!-- User Ban Modal -->
<div x-data="{ 
        show: false, userId: '', userName: '',
        init() { window.addEventListener('open-ban-modal', (e) => { this.userId = e.detail.id; this.userName = e.detail.name; this.show = true; }); }
    }" 
    x-show="show" x-cloak class="custom-modal-backdrop">
    
    <div class="custom-modal-content" @click.away="show = false">
        <form :action="`{{ url('admin/users/status') }}/${userId}`" method="POST">
            @csrf
            <div class="modal-header-accent" style="background: #1e293b;">
                <h3 class="modal-title-main">Restrict Node Access</h3>
                <p class="modal-subtitle-main">Blocking: <span x-text="userName"></span></p>
            </div>

            <div class="modal-body-main">
                <div class="modal-form-group">
                    <label class="modal-label-main">Violation Details</label>
                    <textarea name="reason" required class="modal-textarea-main" rows="4" placeholder="Enter reason for restriction..."></textarea>
                </div>
            </div>

            <div class="modal-footer-main">
                <button type="button" @click="show = false" class="modal-btn-cancel">Cancel</button>
                <button type="submit" class="modal-btn-submit" style="background: #1e293b;">
                    <span class="material-symbols-rounded">block</span> Execute Restriction
                </button>
            </div>
        </form>
    </div>
</div>

