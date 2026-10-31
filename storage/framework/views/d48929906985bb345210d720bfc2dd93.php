

<?php $__env->startSection('title', \App\Models\Setting::get('site_title', 'Member Profile')); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid pb-5">
        <!-- Results Header -->
        <div class="card shadow-sm border-0 mb-4 overflow-hidden" style="border-radius: 15px;">
            <div class="card-body p-3 p-md-4">
                <div class="row align-items-center">
                    <div class="col-12 col-md-6 mb-3 mb-md-0 d-flex align-items-center">
                        <a href="<?php echo e(route('admin.members.index')); ?>"
                            class="btn btn-light rounded-circle shadow-sm me-3 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div>
                            <h5 class="mb-1 fw-bold text-dark">Member Profile</h5>
                            <p class="text-muted small mb-0">Manage member details and activities</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 text-md-end">
                        <div class="d-flex justify-content-md-end gap-2 overflow-auto scrollbar-hidden pb-1">
                            <a href="<?php echo e(route('public.profile.view', $member->code)); ?>" target="_blank"
                                class="btn btn-info rounded-pill px-3 px-md-4 fw-bold shadow-sm flex-grow-1 flex-md-grow-0 text-nowrap">
                                <i class="bi bi-eye me-1 me-md-2"></i> <span class="small">View Public Profile</span>
                            </a>
                            <a href="<?php echo e(route('admin.members.edit', $member->id)); ?>"
                                class="btn btn-primary rounded-pill px-3 px-md-4 fw-bold shadow-sm flex-grow-1 flex-md-grow-0 text-nowrap">
                                <i class="bi bi-pencil me-1 me-md-2"></i> <span class="small">Edit Profile</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Sidebar: Profile Stats -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
                    <div class="card-body text-center py-5">
                        <div class="position-relative d-inline-block mb-3">
                            <div class="rounded-circle border border-4 border-white shadow-lg overflow-hidden"
                                style="width: 140px; height: 140px; background-color: #f8f9fa;">
                                <?php if($member->profile_pic): ?>
                                    <img src="<?php echo e(\App\Helpers\ImageHelper::getPhotoUrl($member->profile_pic)); ?>"
                                        class="w-100 h-100 object-fit-cover" alt="Profile">
                                <?php else: ?>
                                    <div
                                        class="w-100 h-100 d-flex align-items-center justify-content-center bg-white text-muted">
                                        <i class="bi bi-person-fill display-2 opacity-25"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="position-absolute bottom-0 end-0 bg-success rounded-circle shadow-sm d-flex align-items-center justify-content-center"
                                style="width: 32px; height: 32px; border: 2px solid #fff;">
                                <i class="bi bi-check-circle-fill text-white"></i>
                            </div>
                        </div>
                        <h4 class="text-dark fw-bold mb-1"><?php echo e($member->name); ?></h4>
                        <span class="badge bg-light text-dark mb-3" style="font-size: 0.85rem;"><?php echo e($member->code); ?></span>

                        <div class="d-flex justify-content-center gap-2">
                            <?php if($member->is_approved): ?>
                                <span class="badge rounded-pill bg-success px-3">Approved</span>
                            <?php else: ?>
                                <span class="badge rounded-pill bg-danger px-3">Pending</span>
                            <?php endif; ?>

                            <?php if($member->is_featured): ?>
                                <span class="badge rounded-pill bg-warning text-dark px-3">Featured</span>
                            <?php endif; ?>

                            <?php if($member->is_blocked): ?>
                                <span class="badge rounded-pill bg-dark px-3">Blocked</span>
                            <?php endif; ?>

                            <?php if($member->email_verified_at): ?>
                                <span class="badge rounded-pill bg-info px-3"><i class="bi bi-envelope-check-fill me-1"></i>
                                    Email Verified</span>
                            <?php else: ?>
                                <span class="badge rounded-pill bg-warning text-dark px-3"><i
                                        class="bi bi-envelope-exclamation me-1"></i> Email Unverified</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-body bg-white py-4 px-4 border-top">
                        <div class="row g-2 text-center">
                            <div class="col-4 col-md">
                                <div
                                    class="p-2 border rounded-4 bg-light shadow-sm h-100 d-flex flex-column justify-content-center">
                                    <div class="fw-bold text-dark fs-6"><?php echo e($member->profileViews()->count()); ?></div>
                                    <div class="text-muted smaller" style="font-size: 0.6rem;">Views</div>
                                </div>
                            </div>
                            <div class="col-4 col-md">
                                <div
                                    class="p-2 border rounded-4 bg-light shadow-sm h-100 d-flex flex-column justify-content-center">
                                    <div class="fw-bold text-dark fs-6"><?php echo e($member->contactViews()->count()); ?></div>
                                    <div class="text-muted smaller" style="font-size: 0.6rem;">Contact</div>
                                </div>
                            </div>
                            <div class="col-4 col-md">
                                <div
                                    class="p-2 border rounded-4 bg-light shadow-sm h-100 d-flex flex-column justify-content-center">
                                    <div class="fw-bold text-dark fs-6"><?php echo e($member->interestsSent()->count()); ?></div>
                                    <div class="text-muted smaller" style="font-size: 0.6rem;">Sent</div>
                                </div>
                            </div>
                            <div class="col-6 col-md">
                                <div
                                    class="p-2 border rounded-4 bg-light shadow-sm h-100 d-flex flex-column justify-content-center">
                                    <div class="fw-bold text-dark fs-6"><?php echo e($member->interestsReceived()->count()); ?></div>
                                    <div class="text-muted smaller" style="font-size: 0.6rem;">Received</div>
                                </div>
                            </div>
                            <div class="col-6 col-md">
                                <a href="<?php echo e(route('admin.matchmaking.index', ['find_match_for' => $member->id])); ?>"
                                    class="text-decoration-none p-2 border rounded-4 bg-light shadow-sm h-100 d-flex flex-column justify-content-center hover-shadow transition-all">
                                    <div class="fw-bold text-dark fs-6">
                                        <?php
                                            $matchCount = \App\Models\Member::matching($member)->count();
                                        ?>
                                        <?php echo e($matchCount); ?>

                                    </div>
                                    <div class="text-muted smaller" style="font-size: 0.6rem;">Matches</div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="card-body border-top p-4">
                        <div class="d-flex gap-2 mb-3">
                            <?php if($member->is_approved): ?>
                                <button class="btn btn-outline-danger shadow-sm flex-grow-1 fw-bold rounded-pill py-2 small"
                                    onclick="individualAction('<?php echo e(route('admin.members.toggle_status', [$member->id, 'unapprove'])); ?>')">
                                    <i class="bi bi-x-circle"></i> Unapprove
                                </button>
                            <?php else: ?>
                                <button class="btn btn-outline-success shadow-sm flex-grow-1 fw-bold rounded-pill py-2 small"
                                    onclick="individualAction('<?php echo e(route('admin.members.toggle_status', [$member->id, 'approve'])); ?>')">
                                    <i class="bi bi-check-circle"></i> Approve
                                </button>
                            <?php endif; ?>

                            <button
                                class="btn <?php echo e($member->is_featured ? 'btn-warning' : 'btn-outline-warning text-dark'); ?> shadow-sm flex-grow-1 fw-bold rounded-pill py-2 small"
                                onclick="individualAction('<?php echo e(route('admin.members.toggle_featured', [$member->id, $member->is_featured ? 'unfeatured' : 'featured'])); ?>')">
                                <i class="bi <?php echo e($member->is_featured ? 'bi-star-fill' : 'bi-star'); ?>"></i>
                                <?php echo e($member->is_featured ? 'Unfeature' : 'Feature'); ?>

                            </button>
                        </div>

                        <div class="d-flex gap-2 mb-3">
                            <button
                                class="btn <?php echo e($member->is_blocked ? 'btn-dark' : 'btn-outline-dark'); ?> shadow-sm flex-grow-1 fw-bold rounded-pill py-2 small"
                                onclick="individualAction('<?php echo e(route('admin.members.toggle_block', [$member->id, $member->is_blocked ? 'unblock' : 'block'])); ?>')">
                                <i class="bi <?php echo e($member->is_blocked ? 'bi-unlock' : 'bi-slash-circle'); ?>"></i>
                                <?php echo e($member->is_blocked ? 'Unblock Member' : 'Block Member'); ?>

                            </button>
                        </div>

                        <div class="d-flex gap-2 mb-3">
                            <button
                                class="btn <?php echo e($member->email_verified_at ? 'btn-success' : 'btn-outline-success'); ?> shadow-sm flex-grow-1 fw-bold rounded-pill py-2 small"
                                onclick="individualAction('<?php echo e(route('admin.members.toggle_email_verification', [$member->id, $member->email_verified_at ? 'unverify' : 'verify'])); ?>')">
                                <i
                                    class="bi <?php echo e($member->email_verified_at ? 'bi-patch-check-fill' : 'bi-envelope-check'); ?>"></i>
                                <?php echo e($member->email_verified_at ? 'Verified Email' : 'Verify Email'); ?>

                            </button>
                        </div>

                        <button class="btn btn-light shadow-sm w-100 fw-bold rounded-pill text-danger py-2 border-0"
                            onclick="confirmDelete()">
                            <i class="bi bi-trash me-2"></i> Delete Member
                        </button>
                    </div>
                </div>

                <!-- Hidden Individual Action Form -->
                <form id="individualActionForm" method="POST" style="display: none;">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="_method" id="individualActionMethod" value="POST">
                </form>

                <!-- Profile Completion -->
                <div class="card border-0 shadow-sm mt-4" style="border-radius: 16px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-dark small text-uppercase opacity-75">Profile Completion</span>
                            <span class="fw-bold text-primary">85%</span>
                        </div>
                        <div class="progress" style="height: 6px; border-radius: 3px; background-color: #f0f0f0;">
                            <div class="progress-bar bg-primary rounded-pill" style="width: 85%;"></div>
                        </div>
                        <p class="text-muted small mt-2 mb-0">Overall profile health and visibility score.</p>
                    </div>
                </div>
            </div>

            <!-- Main Content: Detailed Info -->
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
                    <div class="card-header bg-white border-0 py-3 px-3">
                        <div class="tabs-scroll-wrapper" id="tabsWrapper">
                            <button class="nav-scroll-btn nav-scroll-left" id="scrollLeftBtn">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <div class="nav nav-tabs border-0 flex-nowrap nav-tabs-scroll" id="profileTabs">
                                <button class="nav-tab active" data-bs-toggle="pill" data-bs-target="#basicInfo">BASIC
                                    INFO</button>
                                <button class="nav-tab" data-bs-toggle="pill"
                                    data-bs-target="#religiousInfo">RELIGIOUS</button>
                                <button class="nav-tab" data-bs-toggle="pill"
                                    data-bs-target="#educationInfo">EDUCATION</button>
                                <button class="nav-tab" data-bs-toggle="pill"
                                    data-bs-target="#locationInfo">LOCATION</button>
                                <button class="nav-tab" data-bs-toggle="pill"
                                    data-bs-target="#preferencesInfo">PREFERENCES</button>
                                <button class="nav-tab" data-bs-toggle="pill" data-bs-target="#uploadsInfo">UPLOADS</button>
                                <button class="nav-tab" data-bs-toggle="pill"
                                    data-bs-target="#membershipInfo">MEMBERSHIP</button>
                                <button class="nav-tab" data-bs-toggle="pill"
                                    data-bs-target="#paymentsInfo">PAYMENTS</button>
                                <button class="nav-tab" data-bs-toggle="pill"
                                    data-bs-target="#messagesInfo">MESSAGES</button>
                                <button class="nav-tab" data-bs-toggle="pill"
                                    data-bs-target="#shortlistInfo">SHORTLIST</button>
                                <button class="nav-tab" data-bs-toggle="pill" data-bs-target="#blockedInfo">BLOCKED</button>
                                <button class="nav-tab" data-bs-toggle="pill" data-bs-target="#ignoredInfo">IGNORED</button>
                                <button class="nav-tab" data-bs-toggle="pill"
                                    data-bs-target="#activityLog">ACTIVITY</button>
                                <button class="nav-tab" data-bs-toggle="pill" data-bs-target="#contactViews">CONTACT
                                    VIEWS</button>
                            </div>
                            <button class="nav-scroll-btn nav-scroll-right" id="scrollRightBtn">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-4 pt-2">
                        <div class="tab-content" id="profileTabsContent">
                            <!-- Basic Info Tab -->
                            <div class="tab-pane fade show active" id="basicInfo">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Full Name</label>
                                            <span class="text-dark fw-bold"><?php echo e($member->name); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Email ID</label>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-dark fw-bold"><?php echo e($member->email); ?></span>
                                                <?php if($member->email_verified_at): ?>
                                                    <i class="bi bi-patch-check-fill text-success"
                                                        title="Email Verified on <?php echo e($member->email_verified_at->format('d M Y')); ?>"></i>
                                                <?php else: ?>
                                                    <i class="bi bi-exclamation-circle-fill text-warning"
                                                        title="Email Not Verified"></i>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div
                                            class="p-3 rounded-4 bg-light border-0 d-flex justify-content-between align-items-center">
                                            <div>
                                                <label
                                                    class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                    style="font-size: 0.65rem;">Mobile Number</label>
                                                <span class="text-dark fw-bold"><?php echo e($member->mobile ?? 'N/A'); ?></span>
                                            </div>
                                            <a href="tel:<?php echo e($member->mobile); ?>"
                                                class="btn btn-white btn-sm rounded-circle shadow-sm border"
                                                style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-telephone text-success"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Gender</label>
                                            <span class="text-dark fw-bold"><?php echo e($member->gender); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Age / DOB</label>
                                            <span class="text-dark fw-bold"><?php echo e($member->age ?? 'N/A'); ?> Yrs
                                                (<?php echo e(optional($member->dob)->format('d M Y') ?? 'N/A'); ?>)</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Profile Created By</label>
                                            <span
                                                class="text-dark fw-bold"><?php echo e($member->profile_created_by ?? 'Self'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Marital Status</label>
                                            <span class="text-dark fw-bold"><?php echo e($member->marital_status ?? 'N/A'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Joining Date</label>
                                            <span
                                                class="text-dark fw-bold"><?php echo e($member->created_at->format('d M Y, h:i A')); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Birth Place</label>
                                            <span class="text-dark fw-bold"><?php echo e($member->birth_place ?? 'N/A'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Birth Time</label>
                                            <span class="text-dark fw-bold"><?php echo e($member->birth_time ?? 'N/A'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Brothers</label>
                                            <span class="text-dark fw-bold"><?php echo e($member->brothers_count ?? 'N/A'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Sisters</label>
                                            <span class="text-dark fw-bold"><?php echo e($member->sisters_count ?? 'N/A'); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Physical Attributes Table -->
                                <!-- Lifestyle Highlights -->
                                <div class="mt-4">
                                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2"><i
                                            class="bi bi-stars me-2"></i>Lifestyle Highlights</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        <?php if($member->food_habit): ?>
                                            <div class="trait-badge <?php echo e($member->food_habit == 'veg' ? 'veg' : 'non-veg'); ?>">
                                                <i class="bi bi-leaf"></i>
                                                <?php echo e(strtoupper($member->food_habit)); ?>

                                            </div>
                                        <?php endif; ?>
                                        <div class="trait-badge <?php echo e($member->smoking_habit == 'no' ? 'clean' : 'warning'); ?>">
                                            <i class="bi bi-slash-circle"></i>
                                            <?php echo e($member->smoking_habit == 'no' ? 'Non-Smoker' : 'Smoker'); ?>

                                        </div>
                                        <div
                                            class="trait-badge <?php echo e($member->drinking_habit == 'no' ? 'clean' : 'warning'); ?>">
                                            <i class="bi bi-cup-straw"></i>
                                            <?php echo e($member->drinking_habit == 'no' ? 'Teetotaler' : 'Occasional'); ?>

                                        </div>
                                        <div class="trait-badge info">
                                            <i class="bi bi-rulers"></i>
                                            <?php
                                                $totalInches = $member->height / 2.54;
                                                $feet = floor($totalInches / 12);
                                                $inches = round($totalInches % 12);
                                            ?>
                                            <?php echo e($feet); ?>ft <?php echo e($inches); ?>in / <?php echo e($member->weight ?? '-'); ?>kg
                                        </div>
                                        <?php if($member->body_type): ?>
                                            <div class="trait-badge info">
                                                <i class="bi bi-person-check"></i>
                                                <?php echo e(ucfirst($member->body_type)); ?>

                                            </div>
                                        <?php endif; ?>
                                        <div class="trait-badge secondary">
                                            <i class="bi bi-translate"></i>
                                            <?php echo e(optional($member->motherTongue)->name ?? 'N/A'); ?>

                                        </div>
                                        <div class="trait-badge secondary">
                                            <i class="bi bi-person-plus"></i>
                                            Added by <?php echo e(ucfirst($member->profile_created_by)); ?>

                                        </div>
                                    </div>
                                </div>

                                <!-- About Me -->
                                <div class="mt-4">
                                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2"><i
                                            class="bi bi-quote me-2"></i>About Me</h6>
                                    <div class="p-3 bg-light rounded-4">
                                        <p class="text-dark mb-0"><?php echo e($member->about_me ?: 'No description provided.'); ?></p>
                                        <?php if($member->about_me_status == 0 && $member->about_me): ?>
                                            <small class="text-warning"><i class="bi bi-clock me-1"></i>Content pending
                                                approval</small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Religious Info Tab -->
                            <div class="tab-pane fade" id="religiousInfo">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Religion</label>
                                            <span class="text-dark fw-bold"><?php echo e($member->religion->name ?? 'N/A'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Caste</label>
                                            <span class="text-dark fw-bold"><?php echo e($member->caste->name ?? 'N/A'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Sub Caste</label>
                                            <span class="text-dark fw-bold"><?php echo e($member->subCaste->name ?? 'N/A'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Gotra</label>
                                            <span class="text-dark fw-bold"><?php echo e($member->gotra->name ?? 'N/A'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Mother Gotra</label>
                                            <span class="text-dark fw-bold"><?php echo e($member->mother_gotra ?? 'N/A'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Intercaste Marriage</label>
                                            <span
                                                class="text-dark fw-bold"><?php echo e($member->is_intercaste ? 'Yes' : 'No'); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Horoscope & Astro Details -->
                                <div class="mt-4">
                                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2"><i
                                            class="bi bi-stars me-2"></i>Horoscope & Astro Details</h6>
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle border">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="small fw-bold text-muted">Detail</th>
                                                    <th class="small fw-bold text-muted">Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="small">Star</td>
                                                    <td class="fw-bold"><?php echo e($member->star->name ?? 'N/A'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="small">Rasi</td>
                                                    <td class="fw-bold"><?php echo e($member->rasi->name ?? 'N/A'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="small">Dosh</td>
                                                    <td class="fw-bold"><?php echo e($member->dosh->name ?? 'N/A'); ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Christian Specific Fields -->
                                <?php if($member->religion && $member->religion->name == 'Christian'): ?>
                                    <div class="mt-4">
                                        <h6 class="fw-bold mb-3 text-primary border-bottom pb-2"><i
                                                class="bi bi-church me-2"></i>Christian Details</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="p-3 rounded-4 bg-light border-0">
                                                    <label
                                                        class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                        style="font-size: 0.65rem;">Church Name</label>
                                                    <span class="text-dark fw-bold"><?php echo e($member->church_name ?? 'N/A'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="p-3 rounded-4 bg-light border-0">
                                                    <label
                                                        class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                        style="font-size: 0.65rem;">Baptism Date</label>
                                                    <span
                                                        class="text-dark fw-bold"><?php echo e(optional($member->baptism_date)->format('d M Y') ?? 'N/A'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Education Info Tab -->
                            <div class="tab-pane fade" id="educationInfo">
                                <div class="row g-4">
                                    <div class="col-md-7">
                                        <div class="prof-tree-container">
                                            
                                            <div class="tree-node">
                                                <div class="node-icon bg-info text-white">
                                                    <i class="bi bi-mortarboard-fill"></i>
                                                </div>
                                                <div class="node-content">
                                                    <span class="node-label">EDUCATION</span>
                                                    <h6 class="node-title h6 fw-bold mb-0">
                                                        <?php echo e(optional($member->education)->name ?? 'N/A'); ?>

                                                    </h6>
                                                    <?php if($member->education_details): ?>
                                                        <p class="node-sub small text-muted mb-0">
                                                            <?php echo e($member->education_details); ?>

                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            
                                            <div class="tree-node">
                                                <div class="node-icon bg-primary text-white">
                                                    <i class="bi bi-person-workspace"></i>
                                                </div>
                                                <div class="node-content">
                                                    <span class="node-label">PROFESSION</span>
                                                    <h6 class="node-title h6 fw-bold mb-0">
                                                        <?php echo e(optional($member->occupation)->name ?? 'N/A'); ?>

                                                    </h6>
                                                </div>
                                            </div>

                                            
                                            <div class="tree-node">
                                                <div class="node-icon bg-dark text-white">
                                                    <i class="bi bi-building"></i>
                                                </div>
                                                <div class="node-content">
                                                    <span class="node-label">SECTOR & DETAILS</span>
                                                    <h6 class="node-title h6 fw-bold text-muted mb-0"
                                                        style="font-size: 0.85rem;">
                                                        <?php echo e(optional($member->occupationType)->name ?? 'General'); ?>

                                                    </h6>
                                                    <?php if($member->occupation_details): ?>
                                                        <div
                                                            class="node-desc mt-2 p-3 bg-light rounded-4 small border-start border-4 border-primary">
                                                            <?php echo e($member->occupation_details); ?>

                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            
                                            <?php if($member->annual_income_id): ?>
                                                <div class="tree-node last">
                                                    <div class="node-icon bg-success text-white">
                                                        <i class="bi bi-cash-stack"></i>
                                                    </div>
                                                    <div class="node-content">
                                                        <span class="node-label">ANNUAL INCOME</span>
                                                        <h6 class="node-title h6 fw-bold text-success mb-0">
                                                            <?php echo e(optional($member->annualIncome)->range); ?>

                                                        </h6>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="p-4 rounded-4 bg-light h-100">
                                            <h6 class="fw-bold mb-3 border-bottom pb-2">Secondary Info</h6>
                                            <div class="mb-3">
                                                <label
                                                    class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                    style="font-size: 0.65rem;">Mother Tongue</label>
                                                <span
                                                    class="text-dark fw-bold"><?php echo e($member->motherTongue->name ?? 'N/A'); ?></span>
                                            </div>
                                            <div>
                                                <label
                                                    class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                    style="font-size: 0.65rem;">Employment Details Score</label>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress flex-grow-1" style="height: 4px;">
                                                        <div class="progress-bar bg-success"
                                                            style="width: <?php echo e($member->occupation_details ? '100%' : '20%'); ?>">
                                                        </div>
                                                    </div>
                                                    <span
                                                        class="small fw-bold"><?php echo e($member->occupation_details ? 'High' : 'Low'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Location Info Tab -->
                            <div class="tab-pane fade" id="locationInfo">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Country</label>
                                            <span class="text-dark fw-bold"><?php echo e($member->country->name ?? 'N/A'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">State</label>
                                            <span class="text-dark fw-bold"><?php echo e($member->state->name ?? 'N/A'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">City</label>
                                            <span class="text-dark fw-bold"><?php echo e($member->city->name ?? 'N/A'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Residential Address</label>
                                            <span class="text-dark fw-bold"><?php echo e($member->address ?? 'N/A'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Preferences Info Tab -->
                            <div class="tab-pane fade" id="preferencesInfo">
                                <h6 class="fw-bold mb-3 text-primary border-bottom pb-2"><i
                                        class="bi bi-heart me-2"></i>Partner
                                    Preferences</h6>
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Preferred Marital Status</label>
                                            <span
                                                class="text-dark fw-bold"><?php echo e(!empty($member->pref_marital_status) ? str_replace(',', ', ', $member->pref_marital_status) : 'N/A'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Preferred Age Range</label>
                                            <span
                                                class="text-dark fw-bold"><?php echo e((!empty($member->pref_age_from) ? $member->pref_age_from : 'N/A')); ?>

                                                - <?php echo e((!empty($member->pref_age_to) ? $member->pref_age_to : 'N/A')); ?>

                                                Years</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Preferred Height Range</label>
                                            <span
                                                class="text-dark fw-bold"><?php echo e($member->pref_height_from ? floor($member->pref_height_from / 12) . "'" . ($member->pref_height_from % 12) . '"' : 'N/A'); ?>

                                                -
                                                <?php echo e($member->pref_height_to ? floor($member->pref_height_to / 12) . "'" . ($member->pref_height_to % 12) . '"' : 'N/A'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Preferred Mother Tongue</label>
                                            <span class="text-dark fw-bold">
                                                <?php
                                                    $mTongues = \App\Models\MotherTongue::whereIn('id', explode(',', $member->pref_mother_tongue_id ?? ''))->pluck('name')->join(', ');
                                                ?>
                                                <?php echo e(!empty($mTongues) ? $mTongues : 'N/A'); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Preferred Physical Status</label>
                                            <span
                                                class="text-dark fw-bold"><?php echo e(!empty($member->pref_physical_status) ? str_replace(',', ', ', $member->pref_physical_status) : 'N/A'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Preferred Complexion</label>
                                            <span
                                                class="text-dark fw-bold"><?php echo e(!empty($member->pref_complexion) ? str_replace(',', ', ', $member->pref_complexion) : 'N/A'); ?></span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Preferred Eating Habit</label>
                                            <span
                                                class="text-dark fw-bold"><?php echo e(!empty($member->pref_food_habit) ? str_replace(',', ', ', $member->pref_food_habit) : 'N/A'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Preferred Smoking Habit</label>
                                            <span
                                                class="text-dark fw-bold"><?php echo e(!empty($member->pref_smoking_habit) ? str_replace(',', ', ', $member->pref_smoking_habit) : 'N/A'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Preferred Drinking Habit</label>
                                            <span
                                                class="text-dark fw-bold"><?php echo e(!empty($member->pref_drinking_habit) ? str_replace(',', ', ', $member->pref_drinking_habit) : 'N/A'); ?></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Preferred Education</label>
                                            <span class="text-dark fw-bold">
                                                <?php
                                                    $educations = \App\Models\Education::whereIn('id', explode(',', $member->pref_education_id ?? ''))->pluck('name')->join(', ');
                                                ?>
                                                <?php echo e(!empty($educations) ? $educations : 'N/A'); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Preferred Occupation</label>
                                            <span class="text-dark fw-bold">
                                                <?php
                                                    $occupations = \App\Models\Occupation::whereIn('id', explode(',', $member->pref_occupation_id ?? ''))->pluck('name')->join(', ');
                                                ?>
                                                <?php echo e(!empty($occupations) ? $occupations : 'N/A'); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Preferred Annual Income</label>
                                            <span class="text-dark fw-bold">
                                                <?php
                                                    $incomes = \App\Models\AnnualIncome::whereIn('id', explode(',', $member->pref_annual_income_id ?? ''))->pluck('range')->join(', ');
                                                ?>
                                                <?php echo e(!empty($incomes) ? $incomes : 'N/A'); ?>

                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Preferred Religion</label>
                                            <span class="text-dark fw-bold">
                                                <?php
                                                    $religions = \App\Models\Religion::whereIn('id', explode(',', $member->pref_religion_id ?? ''))->pluck('name')->join(', ');
                                                ?>
                                                <?php echo e(!empty($religions) ? $religions : 'N/A'); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Preferred Caste</label>
                                            <span class="text-dark fw-bold">
                                                <?php
                                                    $castes = \App\Models\Caste::whereIn('id', explode(',', $member->pref_caste_id ?? ''))->pluck('name')->join(', ');
                                                ?>
                                                <?php echo e(!empty($castes) ? $castes : 'N/A'); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Preferred Star</label>
                                            <span class="text-dark fw-bold">
                                                <?php
                                                    $stars = \App\Models\Star::whereIn('id', explode(',', $member->pref_star_id ?? ''))->pluck('name')->join(', ');
                                                ?>
                                                <?php echo e(!empty($stars) ? $stars : 'N/A'); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Preferred Rasi</label>
                                            <span class="text-dark fw-bold">
                                                <?php
                                                    $rasis = \App\Models\Rasi::whereIn('id', explode(',', $member->pref_rasi_id ?? ''))->pluck('name')->join(', ');
                                                ?>
                                                <?php echo e(!empty($rasis) ? $rasis : 'N/A'); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Preferred Dosh</label>
                                            <span class="text-dark fw-bold">
                                                <?php
                                                    $doshes = \App\Models\Dosh::whereIn('id', explode(',', $member->pref_dosh_id ?? ''))->pluck('name')->join(', ');
                                                ?>
                                                <?php echo e(!empty($doshes) ? $doshes : 'N/A'); ?>

                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Preferred Country</label>
                                            <span class="text-dark fw-bold">
                                                <?php
                                                    $countries = \App\Models\Country::whereIn('id', explode(',', $member->pref_country_id ?? ''))->pluck('name')->join(', ');
                                                ?>
                                                <?php echo e(!empty($countries) ? $countries : 'N/A'); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Preferred State</label>
                                            <span class="text-dark fw-bold">
                                                <?php
                                                    $states = \App\Models\State::whereIn('id', explode(',', $member->pref_state_id ?? ''))->pluck('name')->join(', ');
                                                ?>
                                                <?php echo e(!empty($states) ? $states : 'N/A'); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 rounded-4 bg-light border-0">
                                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block opacity-75"
                                                style="font-size: 0.65rem;">Preferred City</label>
                                            <span class="text-dark fw-bold">
                                                <?php
                                                    $cities = \App\Models\City::whereIn('id', explode(',', $member->pref_city_id ?? ''))->pluck('name')->join(', ');
                                                ?>
                                                <?php echo e(!empty($cities) ? $cities : 'N/A'); ?>

                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Partner Expectation -->
                                <div class="mt-4">
                                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2"><i
                                            class="bi bi-quote me-2"></i>Partner Expectation</h6>
                                    <div class="p-3 bg-light rounded-4">
                                        <p class="text-dark mb-0">
                                            <?php echo e($member->partner_expectation ?: 'No expectations specified.'); ?>

                                        </p>
                                        <?php if($member->partner_expectation_status == 0 && $member->partner_expectation): ?>
                                            <small class="text-warning"><i class="bi bi-clock me-1"></i>Content pending
                                                approval</small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Uploads & Approvals Tab -->
                            <div class="tab-pane fade" id="uploadsInfo">
                                <div class="row g-4">
                                    <!-- Profile Picture -->
                                    <div class="col-12 col-md-6">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-header bg-white border-0 fw-bold small text-uppercase py-3">
                                                Profile Picture</div>
                                            <div class="card-body text-center">
                                                <?php if($member->profile_pic): ?>
                                                    <div class="position-relative d-inline-block mb-3">
                                                        <img src="<?php echo e(\App\Helpers\ImageHelper::getPhotoUrl($member->profile_pic)); ?>"
                                                            class="rounded-3 shadow-sm border p-1"
                                                            style="width: 150px; height: 150px; object-fit: cover;">
                                                        <?php if($member->profile_pic_status == 1): ?>
                                                            <span
                                                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success"><i
                                                                    class="bi bi-check-lg"></i></span>
                                                        <?php elseif($member->profile_pic_status == 2): ?>
                                                            <span
                                                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><i
                                                                    class="bi bi-x-lg"></i></span>
                                                        <?php else: ?>
                                                            <span
                                                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark"><i
                                                                    class="bi bi-clock"></i></span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <span
                                                            class="badge <?php echo e($member->profile_pic_status == 1 ? 'bg-success' : ($member->profile_pic_status == 2 ? 'bg-danger' : 'bg-warning text-dark')); ?> mb-2">
                                                            <?php echo e($member->profile_pic_status == 1 ? 'Approved' : ($member->profile_pic_status == 2 ? 'Rejected' : 'Pending Review')); ?>

                                                        </span>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="py-5 text-muted"> <i
                                                            class="bi bi-image display-4 opacity-25"></i>
                                                        <p class="small mt-2">No profile picture uploaded</p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Horoscope -->
                                    <div class="col-12 col-md-6">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-header bg-white border-0 fw-bold small text-uppercase py-3">
                                                Horoscope</div>
                                            <div class="card-body text-center">
                                                <?php if($member->horoscope_image): ?>
                                                    <div class="mb-3">
                                                        <?php
                                                            $isPdf = \Illuminate\Support\Str::endsWith(strtolower($member->horoscope_image), '.pdf');
                                                        ?>
                                                        <?php if($isPdf): ?>
                                                            <div class="d-flex flex-column align-items-center justify-content-center p-3 border rounded-3 bg-light mx-auto"
                                                                style="width: 150px; height: 150px;">
                                                                <i
                                                                    class="bi bi-file-earmark-pdf-fill text-danger display-4 mb-2"></i>
                                                                <a href="<?php echo e(\App\Helpers\ImageHelper::getPhotoUrl($member->horoscope_image)); ?>"
                                                                    target="_blank" class="btn btn-sm btn-danger rounded-pill px-3">
                                                                    View PDF
                                                                </a>
                                                            </div>
                                                        <?php else: ?>
                                                            <a href="<?php echo e(\App\Helpers\ImageHelper::getPhotoUrl($member->horoscope_image)); ?>"
                                                                target="_blank">
                                                                <img src="<?php echo e(\App\Helpers\ImageHelper::getPhotoUrl($member->horoscope_image)); ?>"
                                                                    class="rounded-3 shadow-sm border p-1"
                                                                    style="width: 150px; height: 150px; object-fit: cover;">
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <span
                                                            class="badge <?php echo e($member->horoscope_status == 1 ? 'bg-success' : ($member->horoscope_status == 2 ? 'bg-danger' : 'bg-warning text-dark')); ?> mb-2">
                                                            <?php echo e($member->horoscope_status == 1 ? 'Approved' : ($member->horoscope_status == 2 ? 'Rejected' : 'Pending Review')); ?>

                                                        </span>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="py-5 text-muted"> <i
                                                            class="bi bi-stars display-4 opacity-25"></i>
                                                        <p class="small mt-2">No horoscope uploaded</p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Gallery Photos (Photo 2 - Photo 8) -->
                                    <div class="col-12">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-white border-0 fw-bold small text-uppercase py-3">
                                                Photo Gallery</div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <?php
                                                        $galleryPhotos = ['photo_2', 'photo_3', 'photo_4', 'photo_5', 'photo_6', 'photo_7', 'photo_8'];
                                                        $hasGallery = false;
                                                    ?>

                                                    <?php $__currentLoopData = $galleryPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($member->$photo): ?>
                                                            <?php
                                                                $hasGallery = true;
                                                                $statusKey = $photo . '_status';
                                                                $status = $member->$statusKey;
                                                            ?>
                                                            <div class="col-6 col-sm-4 col-md-3">
                                                                <div class="position-relative">
                                                                    <a href="<?php echo e(\App\Helpers\ImageHelper::getPhotoUrl($member->$photo)); ?>"
                                                                        target="_blank">
                                                                        <img src="<?php echo e(\App\Helpers\ImageHelper::getPhotoUrl($member->$photo)); ?>"
                                                                            class="img-fluid rounded-3 shadow-sm border w-100"
                                                                            style="height: 150px; object-fit: cover;">
                                                                    </a>
                                                                    <div class="position-absolute top-0 end-0 m-2">
                                                                        <?php if($status == 1): ?>
                                                                            <span class="badge rounded-pill bg-success shadow-sm"
                                                                                title="Approved"><i class="bi bi-check-lg"></i></span>
                                                                        <?php elseif($status == 2): ?>
                                                                            <span class="badge rounded-pill bg-danger shadow-sm"
                                                                                title="Rejected"><i class="bi bi-x-lg"></i></span>
                                                                        <?php else: ?>
                                                                            <span
                                                                                class="badge rounded-pill bg-warning text-dark shadow-sm"
                                                                                title="Pending"><i class="bi bi-clock"></i></span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                <div class="text-center mt-2">
                                                                    <small
                                                                        class="text-muted fw-bold"><?php echo e(ucfirst(str_replace('_', ' ', $photo))); ?></small>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                    <?php if(!$hasGallery): ?>
                                                        <div class="col-12 text-center py-4 text-muted">
                                                            <i class="bi bi-images display-5 opacity-25"></i>
                                                            <p class="small mt-2 mb-0">No additional photos uploaded</p>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Identity Documents -->
                                    <div class="col-12">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-white border-0 fw-bold small text-uppercase py-3">
                                                Identity Document</div>
                                            <div class="card-body">
                                                <?php if($member->document_image): ?>
                                                    <?php
                                                        $isPdf = \Illuminate\Support\Str::endsWith(strtolower($member->document_image), '.pdf');
                                                    ?>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <?php if($isPdf): ?>
                                                            <div class="d-flex flex-column align-items-center justify-content-center p-2 border rounded-3 bg-light"
                                                                style="width: 100px; height: 100px;">
                                                                <i class="bi bi-file-earmark-pdf-fill text-danger fs-1"></i>
                                                            </div>
                                                        <?php else: ?>
                                                            <a href="<?php echo e(\App\Helpers\ImageHelper::getPhotoUrl($member->document_image)); ?>"
                                                                target="_blank" class="d-block flex-shrink-0">
                                                                <img src="<?php echo e(\App\Helpers\ImageHelper::getPhotoUrl($member->document_image)); ?>"
                                                                    class="rounded-3 shadow-sm border"
                                                                    style="width: 100px; height: 100px; object-fit: cover;">
                                                            </a>
                                                        <?php endif; ?>
                                                        <div>
                                                            <h6 class="fw-bold text-dark mb-1">Uploaded Document</h6>
                                                            <?php if($isPdf): ?>
                                                                <p class="text-muted small mb-2">PDF Document</p>
                                                                <a href="<?php echo e(\App\Helpers\ImageHelper::getPhotoUrl($member->document_image)); ?>"
                                                                    target="_blank" class="btn btn-sm btn-danger rounded-pill px-3">
                                                                    <i class="bi bi-file-earmark-pdf me-1"></i> View PDF
                                                                </a>
                                                            <?php else: ?>
                                                                <p class="text-muted small mb-2">Click image to view full size</p>
                                                            <?php endif; ?>
                                                            <div class="mt-2">
                                                                <span
                                                                    class="badge <?php echo e($member->document_status == 1 ? 'bg-success' : ($member->document_status == 2 ? 'bg-danger' : 'bg-warning text-dark')); ?>">
                                                                    <?php echo e($member->document_status == 1 ? 'Approved' : ($member->document_status == 2 ? 'Rejected' : 'Pending Review')); ?>

                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="text-center py-3 text-muted">
                                                        <i class="bi bi-file-earmark-person display-6 opacity-25"></i>
                                                        <p class="small mt-2 mb-0">No identity document uploaded</p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Membership Info Tab -->
                            <div class="tab-pane fade" id="membershipInfo">
                                <h6 class="fw-bold mb-3">Available Membership Plans</h6>
                                <div class="row g-3 mb-4">
                                    <?php
                                        $plans = \App\Models\MembershipPlan::all();
                                    ?>
                                    <?php $__empty_1 = true; $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="card border-0 shadow-sm h-100">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <div>
                                                            <h6 class="fw-bold text-dark mb-1"><?php echo e($plan->name); ?></h6>
                                                            <span
                                                                class="badge bg-primary bg-opacity-10 text-primary"><?php echo e($plan->duration_days); ?>

                                                                Days</span>
                                                        </div>
                                                        <div class="text-end">
                                                            <div class="fw-bold text-primary fs-5">
                                                                ₹<?php echo e(number_format($plan->price)); ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="small text-muted mb-3">
                                                        <div class="mb-1"><i
                                                                class="bi bi-address-book me-1"></i><?php echo e($plan->contact_limit); ?>

                                                            Contact Views</div>
                                                        <div class="mb-1"><i
                                                                class="bi bi-chat-dots me-1"></i><?php echo e($plan->chat_access ? 'Live Chat' : 'No Chat'); ?>

                                                        </div>
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <span
                                                            class="badge bg-light text-dark border"><?php echo e($plan->currency); ?></span>
                                                        <span class="badge bg-success bg-opacity-10 text-success">Active</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="col-12 text-center py-4 text-muted">
                                            <i class="bi bi-credit-card display-4 opacity-25"></i>
                                            <p class="small mt-2 mb-0">No membership plans available</p>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <h6 class="fw-bold mb-3">Membership History</h6>
                                <div class="table-responsive mb-4">
                                    <table class="table table-hover align-middle border">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-3 small fw-bold text-muted">ID</th>
                                                <th class="small fw-bold text-muted">Plan</th>
                                                <th class="small fw-bold text-muted">Validity</th>
                                                <th class="small fw-bold text-muted">Amount</th>
                                                <th class="small fw-bold text-muted">Status</th>
                                                <th class="small fw-bold text-muted">Invoice</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__empty_1 = true; $__currentLoopData = $member->memberships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td class="ps-3">
                                                        <div class="small fw-bold text-primary">
                                                            #<?php echo e(str_pad($ms->id, 5, '0', STR_PAD_LEFT)); ?></div>
                                                        <div class="smaller text-muted">
                                                            <?php echo e($ms->subscribed_by == 'admin' ? 'Offline' : 'Online'); ?>

                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-light text-dark border fw-normal"><?php echo e($ms->plan->name ?? 'N/A'); ?></span>
                                                    </td>
                                                    <td class="small">
                                                        <?php if($ms->start_date && $ms->end_date): ?>
                                                            <div class="fw-bold text-success">
                                                                <?php echo e($ms->start_date->format('d M, Y')); ?> -
                                                                <?php echo e($ms->end_date->format('d M, Y')); ?>

                                                            </div>
                                                            <div class="smaller text-muted" style="font-size: 0.7rem;">
                                                                <?php echo e(\Carbon\Carbon::parse($ms->start_date)->diffInDays($ms->end_date)); ?>

                                                                Days Validity
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="text-muted">-</div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="small fw-bold text-dark">
                                                        INR <?php echo e(number_format($ms->amount, 2)); ?>

                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-<?php echo e($ms->status == 'active' ? 'success' : 'secondary'); ?> bg-opacity-10 text-<?php echo e($ms->status == 'active' ? 'success' : 'secondary'); ?> rounded-pill px-3">
                                                            <?php echo e(ucfirst($ms->status)); ?>

                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo e(route('admin.subscriptions.receipt', ['id' => $ms->id, 'type' => 'offline'])); ?>"
                                                            class="btn btn-sm btn-light text-primary" target="_blank">
                                                            <i class="bi bi-file-earmark-pdf"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="6" class="text-center py-4 text-muted small">No membership
                                                        records found</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                            <!-- Payments Info Tab -->
                            <div class="tab-pane fade" id="paymentsInfo">
                                <h6 class="fw-bold mb-3">Payment Transactions</h6>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle border">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-3 small fw-bold text-muted">Transaction ID</th>
                                                <th class="small fw-bold text-muted">Plan</th>
                                                <th class="small fw-bold text-muted">Amount</th>
                                                <th class="small fw-bold text-muted">Validity</th>
                                                <th class="small fw-bold text-muted">Date</th>
                                                <th class="small fw-bold text-muted">Status</th>
                                                <th class="small fw-bold text-muted">Invoice</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__empty_1 = true; $__currentLoopData = $member->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td class="ps-3">
                                                        <div class="small fw-bold text-dark">
                                                            <?php echo e($pay->razorpay_payment_id ?? $pay->transaction_id); ?>

                                                        </div>
                                                        <div class="smaller text-muted">Order: <?php echo e($pay->razorpay_order_id); ?>

                                                        </div>
                                                    </td>
                                                    <td><?php echo e($pay->plan->name ?? 'N/A'); ?></td>
                                                    <td class="small fw-bold text-dark"><?php echo e($pay->currency); ?>

                                                        <?php echo e(number_format($pay->amount, 2)); ?>

                                                    </td>
                                                    <td class="small">
                                                        <?php if($pay->membership && $pay->membership->start_date && $pay->membership->end_date): ?>
                                                            <div class="text-success fw-bold">
                                                                <?php echo e($pay->membership->start_date->format('d M')); ?> -
                                                                <?php echo e($pay->membership->end_date->format('d M, Y')); ?>

                                                            </div>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="small"><?php echo e($pay->created_at->format('d M Y, h:i A')); ?></td>
                                                    <td>
                                                        <span
                                                            class="badge bg-<?php echo e($pay->status == 'success' ? 'success' : 'danger'); ?> bg-opacity-10 text-<?php echo e($pay->status == 'success' ? 'success' : 'danger'); ?> rounded-pill px-3">
                                                            <?php echo e(ucfirst($pay->status)); ?>

                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if($pay->status == 'success'): ?>
                                                            <a href="<?php echo e(route('admin.subscriptions.receipt', ['id' => $pay->id, 'type' => 'online'])); ?>"
                                                                class="btn btn-sm btn-light text-primary" target="_blank">
                                                                <i class="bi bi-file-earmark-pdf"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="text-muted small">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="7" class="text-center py-4 text-muted small">No payment
                                                        transactions found</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Messages Info Tab -->
                            <div class="tab-pane fade" id="messagesInfo">
                                <h6 class="fw-bold mb-3">Message Recipients</h6>
                                <div class="row g-3">
                                    <?php $__empty_1 = true; $__currentLoopData = $member->sentMessages->unique('receiver_id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="card border-0 shadow-sm h-100">
                                                <div class="card-body text-center">
                                                    <div class="position-relative d-inline-block mb-3">
                                                        <?php if($message->receiver->profile_pic): ?>
                                                            <img src="<?php echo e(\App\Helpers\ImageHelper::getPhotoUrl($message->receiver->profile_pic)); ?>"
                                                                class="rounded-circle border"
                                                                style="width: 60px; height: 60px; object-fit: cover;">
                                                        <?php else: ?>
                                                            <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center"
                                                                style="width: 60px; height: 60px;">
                                                                <i class="bi bi-person-fill text-muted"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <h6 class="fw-bold text-dark mb-1"><?php echo e($message->receiver->name); ?></h6>
                                                    <span
                                                        class="badge bg-light text-dark mb-2"><?php echo e($message->receiver->code); ?></span>
                                                    <div class="small text-muted mb-2">
                                                        <div><?php echo e($message->receiver->age ?? 'N/A'); ?> Yrs •
                                                            <?php echo e($message->receiver->city->name ?? 'N/A'); ?>

                                                        </div>
                                                        <div><?php echo e($message->receiver->religion->name ?? 'N/A'); ?> •
                                                            <?php echo e($message->receiver->caste->name ?? 'N/A'); ?>

                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <a href="<?php echo e(route('admin.members.show', $message->receiver->id)); ?>"
                                                            class="btn btn-outline-primary btn-sm rounded-pill">
                                                            <i class="bi bi-eye"></i> View Profile
                                                        </a>
                                                        <a href="<?php echo e(route('public.profile.view', $message->receiver->code)); ?>"
                                                            target="_blank" class="btn btn-outline-info btn-sm rounded-pill">
                                                            <i class="bi bi-link-45deg"></i> Public View
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="col-12 text-center py-4 text-muted">
                                            <i class="bi bi-chat-dots display-4 opacity-25"></i>
                                            <p class="small mt-2 mb-0">No messages sent yet</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Shortlist Info Tab -->
                            <div class="tab-pane fade" id="shortlistInfo">
                                <h6 class="fw-bold mb-3">Shortlisted Profiles</h6>
                                <div class="row g-3">
                                    <?php $__empty_1 = true; $__currentLoopData = $member->shortlists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shortlist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php if($shortlist->receiver): ?>
                                            <div class="col-md-6 col-lg-4">
                                                <div class="card border-0 shadow-sm h-100">
                                                    <div class="card-body text-center">
                                                        <div class="position-relative d-inline-block mb-3">
                                                            <?php if($shortlist->receiver->profile_pic): ?>
                                                                <img src="<?php echo e(\App\Helpers\ImageHelper::getPhotoUrl($shortlist->receiver->profile_pic)); ?>"
                                                                    class="rounded-circle border"
                                                                    style="width: 60px; height: 60px; object-fit: cover;">
                                                            <?php else: ?>
                                                                <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center"
                                                                    style="width: 60px; height: 60px;">
                                                                    <i class="bi bi-person-fill text-muted"></i>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <h6 class="fw-bold text-dark mb-1"><?php echo e($shortlist->receiver->name); ?></h6>
                                                        <span
                                                            class="badge bg-light text-dark mb-2"><?php echo e($shortlist->receiver->code); ?></span>
                                                        <div class="small text-muted mb-2">
                                                            <div><?php echo e($shortlist->receiver->age ?? 'N/A'); ?> Yrs •
                                                                <?php echo e($shortlist->receiver->city->name ?? 'N/A'); ?>

                                                            </div>
                                                            <div><?php echo e($shortlist->receiver->religion->name ?? 'N/A'); ?> •
                                                                <?php echo e($shortlist->receiver->caste->name ?? 'N/A'); ?>

                                                            </div>
                                                        </div>
                                                        <a href="<?php echo e(route('admin.members.show', $shortlist->receiver->id)); ?>"
                                                            class="btn btn-primary btn-sm rounded-pill w-100">
                                                            <i class="bi bi-eye me-1"></i> View Profile
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="col-12 text-center py-4 text-muted">
                                            <i class="bi bi-bookmark-heart display-4 opacity-25"></i>
                                            <p class="small mt-2 mb-0">No shortlisted profiles</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Blocked Info Tab -->
                            <div class="tab-pane fade" id="blockedInfo">
                                <h6 class="fw-bold mb-3">Blocked Profiles</h6>
                                <div class="row g-3">
                                    <?php $__empty_1 = true; $__currentLoopData = $member->blockedProfiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blocked): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php if($blocked->receiver): ?>
                                            <div class="col-md-6 col-lg-4">
                                                <div class="card border-0 shadow-sm h-100">
                                                    <div class="card-body text-center">
                                                        <div class="position-relative d-inline-block mb-3">
                                                            <?php if($blocked->receiver->profile_pic): ?>
                                                                <img src="<?php echo e(\App\Helpers\ImageHelper::getPhotoUrl($blocked->receiver->profile_pic)); ?>"
                                                                    class="rounded-circle border"
                                                                    style="width: 60px; height: 60px; object-fit: cover;">
                                                            <?php else: ?>
                                                                <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center"
                                                                    style="width: 60px; height: 60px;">
                                                                    <i class="bi bi-person-fill text-muted"></i>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <h6 class="fw-bold text-dark mb-1"><?php echo e($blocked->receiver->name); ?></h6>
                                                        <span
                                                            class="badge bg-light text-dark mb-2"><?php echo e($blocked->receiver->code); ?></span>
                                                        <div class="small text-muted mb-2">
                                                            <div><?php echo e($blocked->receiver->age ?? 'N/A'); ?> Yrs •
                                                                <?php echo e($blocked->receiver->city->name ?? 'N/A'); ?>

                                                            </div>
                                                            <div><?php echo e($blocked->receiver->religion->name ?? 'N/A'); ?> •
                                                                <?php echo e($blocked->receiver->caste->name ?? 'N/A'); ?>

                                                            </div>
                                                        </div>
                                                        <a href="<?php echo e(route('admin.members.show', $blocked->receiver->id)); ?>"
                                                            class="btn btn-primary btn-sm rounded-pill w-100">
                                                            <i class="bi bi-eye me-1"></i> View Profile
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="col-12 text-center py-4 text-muted">
                                            <i class="bi bi-slash-circle display-4 opacity-25"></i>
                                            <p class="small mt-2 mb-0">No blocked profiles</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Ignored Info Tab -->
                            <div class="tab-pane fade" id="ignoredInfo">
                                <h6 class="fw-bold mb-3">Ignored Profiles</h6>
                                <div class="row g-3">
                                    <?php $__empty_1 = true; $__currentLoopData = $member->ignoredProfiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ignored): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php if($ignored->receiver): ?>
                                            <div class="col-md-6 col-lg-4">
                                                <div class="card border-0 shadow-sm h-100">
                                                    <div class="card-body text-center">
                                                        <div class="position-relative d-inline-block mb-3">
                                                            <?php if($ignored->receiver->profile_pic): ?>
                                                                <img src="<?php echo e(\App\Helpers\ImageHelper::getPhotoUrl($ignored->receiver->profile_pic)); ?>"
                                                                    class="rounded-circle border"
                                                                    style="width: 60px; height: 60px; object-fit: cover;">
                                                            <?php else: ?>
                                                                <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center"
                                                                    style="width: 60px; height: 60px;">
                                                                    <i class="bi bi-person-fill text-muted"></i>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <h6 class="fw-bold text-dark mb-1"><?php echo e($ignored->receiver->name); ?></h6>
                                                        <span
                                                            class="badge bg-light text-dark mb-2"><?php echo e($ignored->receiver->code); ?></span>
                                                        <div class="small text-muted mb-2">
                                                            <div><?php echo e($ignored->receiver->age ?? 'N/A'); ?> Yrs •
                                                                <?php echo e($ignored->receiver->city->name ?? 'N/A'); ?>

                                                            </div>
                                                            <div><?php echo e($ignored->receiver->religion->name ?? 'N/A'); ?> •
                                                                <?php echo e($ignored->receiver->caste->name ?? 'N/A'); ?>

                                                            </div>
                                                        </div>
                                                        <a href="<?php echo e(route('admin.members.show', $ignored->receiver->id)); ?>"
                                                            class="btn btn-primary btn-sm rounded-pill w-100">
                                                            <i class="bi bi-eye me-1"></i> View Profile
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="col-12 text-center py-4 text-muted">
                                            <i class="bi bi-eye-slash display-4 opacity-25"></i>
                                            <p class="small mt-2 mb-0">No ignored profiles</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Activity Tab -->
                            <div class="tab-pane fade" id="activityLog">
                                <h6 class="fw-bold mb-3">Recent Activity</h6>
                                <div class="list-group list-group-flush mt-2">
                                    <?php $__empty_1 = true; $__currentLoopData = $member->profileViews->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $view): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="list-group-item px-0 py-3 border-0 border-bottom">
                                            <div class="d-flex align-items-center">
                                                <?php if($view->viewer): ?>
                                                    <a href="<?php echo e(route('admin.members.show', $view->viewer->id)); ?>"
                                                        class="bg-info bg-opacity-10 p-2 rounded-circle me-3 d-flex align-items-center justify-content-center text-decoration-none">
                                                        <i class="bi bi-eye text-info"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <div
                                                        class="bg-info bg-opacity-10 p-2 rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-eye text-info"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <div class="fw-bold text-dark small">
                                                        Profile Viewed by
                                                        <?php if($view->viewer): ?>
                                                            <a href="<?php echo e(route('admin.members.show', $view->viewer->id)); ?>"
                                                                class="text-primary text-decoration-none"><?php echo e($view->viewer->name); ?></a>
                                                        <?php else: ?>
                                                            Unknown
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="text-muted" style="font-size: 0.75rem;">
                                                        <?php echo e($view->created_at->diffForHumans()); ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="text-center py-4 text-muted">
                                            <i class="bi bi-eye-slash display-4 opacity-25"></i>
                                            <p class="small mt-2 mb-0">No profile views yet</p>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <h6 class="fw-bold mb-3 mt-4">Interest Activity</h6>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle border">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="small fw-bold text-muted">Type</th>
                                                <th class="small fw-bold text-muted">Partner</th>
                                                <th class="small fw-bold text-muted">Date</th>
                                                <th class="small fw-bold text-muted">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__empty_1 = true; $__currentLoopData = $member->interestsReceived->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $interest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td class="small">Received</td>
                                                    <td class="small fw-bold">
                                                        <?php if($interest->sender): ?>
                                                            <a href="<?php echo e(route('admin.members.show', $interest->sender->id)); ?>"
                                                                class="text-primary text-decoration-none"><?php echo e($interest->sender->name); ?></a>
                                                        <?php else: ?>
                                                            N/A
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="small"><?php echo e($interest->created_at->format('d M Y')); ?></td>
                                                    <td><span class="badge bg-success bg-opacity-10 text-success">Active</span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="4" class="text-center py-4 text-muted small">No interest
                                                        activity</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Contact Views Tab -->
                            <div class="tab-pane fade" id="contactViews">
                                <h6 class="fw-bold mb-3">Contact Information Viewed By</h6>
                                <div class="list-group list-group-flush mt-2">
                                    <?php $__empty_1 = true; $__currentLoopData = $member->contactViews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="list-group-item px-0 py-3 border-0 border-bottom">
                                            <div class="d-flex align-items-center">
                                                <?php if($cv->viewer): ?>
                                                    <a href="<?php echo e(route('admin.members.show', $cv->viewer->id)); ?>"
                                                        class="bg-success bg-opacity-10 p-2 rounded-circle me-3 d-flex align-items-center justify-content-center text-decoration-none"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="bi bi-telephone-fill text-success"></i>
                                                    </a>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-bold text-dark small">
                                                            Contact details viewed by
                                                            <a href="<?php echo e(route('admin.members.show', $cv->viewer->id)); ?>"
                                                                class="text-primary text-decoration-none"><?php echo e($cv->viewer->name); ?></a>
                                                            <span
                                                                class="badge bg-light text-dark ms-2"><?php echo e($cv->viewer->code); ?></span>
                                                        </div>
                                                        <div class="text-muted d-flex align-items-center gap-2"
                                                            style="font-size: 0.75rem;">
                                                            <span><i
                                                                    class="bi bi-clock me-1"></i><?php echo e($cv->created_at->format('d M Y, h:i A')); ?></span>
                                                            <span class="text-secondary opacity-50">•</span>
                                                            <span><?php echo e($cv->created_at->diffForHumans()); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="text-end">
                                                        <a href="<?php echo e(route('admin.members.show', $cv->viewer->id)); ?>"
                                                            class="btn btn-sm btn-light rounded-pill px-3">View Profile</a>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="bg-secondary bg-opacity-10 p-2 rounded-circle me-3 d-flex align-items-center justify-content-center"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="bi bi-telephone-x text-secondary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark small">Contact details viewed by Unknown
                                                            Member</div>
                                                        <div class="text-muted" style="font-size: 0.75rem;">
                                                            <?php echo e($cv->created_at->diffForHumans()); ?>

                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="text-center py-5 text-muted bg-light rounded-4">
                                            <i class="bi bi-telephone-x display-4 opacity-25"></i>
                                            <p class="small mt-2 mb-0">No one has viewed this member's contact information yet.
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .nav-pills .btn {
            transition: all 0.2s ease-in-out;
        }

        .nav-pills .btn.active {
            background-color: #ff0073 !important;
            color: white !important;
            box-shadow: 0 4px 15px rgba(255, 0, 115, 0.3) !important;
        }

        .scrollbar-hidden::-webkit-scrollbar {
            display: none;
        }

        /* Tabs Scroll Wrapper */
        .tabs-scroll-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            background: #f8f9fa;
            border-radius: 12px;
            overflow: hidden;
        }

        .nav-scroll-btn {
            background: #0d6efd;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 3;
            flex-shrink: 0;
        }

        .nav-scroll-btn:hover {
            background: #0b5ed7;
            color: #fff;
            transform: scale(1.1);
        }

        .nav-scroll-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
            transform: none;
        }

        .nav-tabs-scroll {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            -ms-overflow-style: none;
            scrollbar-width: thin;
            scrollbar-color: #ff0073 #f8f9fa;
            padding: 0 8px;
            scroll-behavior: smooth;
            flex: 1;
            scroll-padding-left: 8px;
            scroll-padding-right: 8px;
        }

        .nav-tabs-scroll::-webkit-scrollbar {
            height: 4px;
        }

        .nav-tabs-scroll::-webkit-scrollbar-track {
            background: #f8f9fa;
            border-radius: 2px;
        }

        .nav-tabs-scroll::-webkit-scrollbar-thumb {
            background: #ff0073;
            border-radius: 2px;
        }

        .nav-tabs-scroll::-webkit-scrollbar-thumb:hover {
            background: #e60066;
        }

        .nav-tab {
            padding: 8px 12px;
            border: none;
            background: none;
            font-weight: 700;
            font-size: 0.75rem;
            color: #64748b;
            position: relative;
            white-space: nowrap;
            flex-shrink: 0;
            transition: all 0.2s;
            border-radius: 0;
            min-width: fit-content;
        }

        .nav-tab:hover {
            color: #ff0073;
            background: rgba(255, 0, 115, 0.05);
        }

        .nav-tab.active {
            color: #ff0073;
            background: rgba(255, 0, 115, 0.1);
        }

        .nav-tab.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            background: #ff0073;
            border-radius: 10px;
        }

        /* Desktop Tab Distribution */
        @media (min-width: 768px) {
            .tabs-scroll-wrapper {
                justify-content: center;
                background: transparent;
            }

            .nav-tabs-scroll {
                justify-content: flex-start;
                flex-wrap: nowrap;
            }

            .nav-tab {
                flex: none;
                text-align: center;
                max-width: none;
            }
        }

        /* Independent Scrolling for Sidebar and Main Content */
        @media (min-width: 992px) {
            .col-lg-4 {
                max-height: 80vh;
                overflow-y: auto;
            }

            .col-lg-8 {
                max-height: 80vh;
                overflow-y: auto;
            }
        }

        /* Professional Tree Layout (Admin Context) */
        .prof-tree-container {
            display: flex;
            flex-direction: column;
            position: relative;
            padding-left: 10px;
        }

        .tree-node {
            display: flex;
            gap: 20px;
            padding-bottom: 30px;
            position: relative;
        }

        .tree-node::before {
            content: '';
            position: absolute;
            left: 17.5px;
            top: 36px;
            bottom: -5px;
            width: 2px;
            background: #e9ecef;
            z-index: 1;
        }

        .tree-node.last::before {
            display: none;
        }

        .node-icon {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
            position: relative;
            z-index: 2;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .node-content {
            padding-top: 2px;
            flex-grow: 1;
        }

        .node-label {
            display: block;
            font-size: 0.65rem;
            font-weight: 800;
            color: #adb5bd;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }

        .node-title {
            margin-bottom: 0;
            color: #212529;
        }

        .node-sub {
            font-size: 0.85rem;
            margin-top: 4px;
            color: #6c757d;
        }

        .node-desc {
            font-style: normal;
            line-height: 1.6;
        }

        /* Trait Badges (Admin) */
        .trait-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
            border: 1px solid transparent;
        }

        .trait-badge.veg {
            background: #f0fdf4;
            color: #16a34a;
            border-color: rgba(22, 163, 74, 0.1);
        }

        .trait-badge.non-veg {
            background: #fef2f2;
            color: #ef4444;
            border-color: rgba(239, 68, 68, 0.1);
        }

        .trait-badge.clean {
            background: #f0f9ff;
            color: #0ea5e9;
            border-color: rgba(14, 165, 233, 0.1);
        }

        .trait-badge.warning {
            background: #fffbeb;
            color: #d97706;
            border-color: rgba(217, 119, 6, 0.1);
        }

        .trait-badge.info {
            background: #f8fafc;
            color: #475569;
            border-color: #e2e8f0;
        }

        .trait-badge.secondary {
            background: #f5f3ff;
            color: #7c3aed;
            border-color: rgba(124, 58, 237, 0.1);
        }
    </style>

    <script>
        // Tab Scroll Helper
        document.addEventListener('DOMContentLoaded', function () {
            const tabsContainer = document.getElementById('profileTabs');
            const wrapper = document.getElementById('tabsWrapper');
            const scrollLeftBtn = document.getElementById('scrollLeftBtn');
            const scrollRightBtn = document.getElementById('scrollRightBtn');

            function updateScrollButtons() {
                const isAtStart = tabsContainer.scrollLeft <= 0;
                const isAtEnd = tabsContainer.scrollLeft + tabsContainer.clientWidth >= tabsContainer.scrollWidth - 10;

                scrollLeftBtn.disabled = isAtStart;
                scrollRightBtn.disabled = isAtEnd;
            }

            function scrollTabs(direction) {
                const scrollAmount = 150;
                const currentScroll = tabsContainer.scrollLeft;
                const newScroll = currentScroll + (direction * scrollAmount);

                // Ensure we don't scroll past boundaries
                const maxScroll = tabsContainer.scrollWidth - tabsContainer.clientWidth;
                const clampedScroll = Math.max(0, Math.min(maxScroll, newScroll));

                tabsContainer.scrollTo({
                    left: clampedScroll,
                    behavior: 'smooth'
                });
            }

            scrollLeftBtn.addEventListener('click', () => scrollTabs(-1));
            scrollRightBtn.addEventListener('click', () => scrollTabs(1));

            tabsContainer.addEventListener('scroll', updateScrollButtons);
            window.addEventListener('resize', updateScrollButtons);
            updateScrollButtons();

            // Auto-center tab on click for mobile
            document.querySelectorAll('.nav-tab').forEach(tab => {
                tab.addEventListener('click', function () {
                    if (window.innerWidth < 768) {
                        this.scrollIntoView({
                            behavior: 'smooth',
                            inline: 'center',
                            block: 'nearest'
                        });
                    }
                });
            });
        });

        document.querySelectorAll('[data-bs-toggle="pill"]').forEach(pill => {
            pill.addEventListener('click', function (e) {
                e.preventDefault();
                const target = this.getAttribute('data-bs-target');

                // Update active state of buttons
                document.querySelectorAll('.nav-tab').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');

                // Show target tab
                const tabContent = document.querySelector('#profileTabsContent');
                tabContent.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('show', 'active');
                });
                document.querySelector(target).classList.add('show', 'active');
            });
        });

        function individualAction(url, method = 'POST', confirmMsg = null) {
            if (confirmMsg) {
                window.adminSwal({
                    title: 'Are you sure?',
                    text: confirmMsg,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('individualActionForm');
                        form.action = url;
                        document.getElementById('individualActionMethod').value = method;
                        form.submit();
                    }
                });
                return;
            }
            const form = document.getElementById('individualActionForm');
            form.action = url;
            document.getElementById('individualActionMethod').value = method;
            form.submit();
        }

        function confirmDelete() {
            window.adminSwal({
                title: 'Are you sure?',
                text: 'You want to permanently delete this member? This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "<?php echo e(route('admin.members.destroy', $member->id)); ?>";
                    form.innerHTML = `
                                                                                            <?php echo csrf_field(); ?>
                                                                                            <?php echo method_field('DELETE'); ?>
                                                                                        `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function showViewsModal() {
            const modal = new bootstrap.Modal(document.getElementById('viewsModal'));
            modal.show();
        }
    </script>

    <!-- Views Modal -->
    <div class="modal fade" id="viewsModal" tabindex="-1" aria-labelledby="viewsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="viewsModalLabel">
                        <i class="bi bi-eye me-2 text-info"></i>Profile Viewers
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4" style="max-height: 60vh; overflow-y: auto;">
                    <div class="row g-3">
                        <?php $__empty_1 = true; $__currentLoopData = $member->profileViews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $view): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="col-6 col-md-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="position-relative d-inline-block mb-2">
                                            <?php if($view->viewer->profile_pic): ?>
                                                <img src="<?php echo e(\App\Helpers\ImageHelper::getPhotoUrl($view->viewer->profile_pic)); ?>"
                                                    class="rounded-circle border"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center"
                                                    style="width: 50px; height: 50px;">
                                                    <i class="bi bi-person-fill text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-2 small"><?php echo e($view->viewer->name); ?></h6>
                                        <a href="<?php echo e(route('admin.members.show', $view->viewer->id)); ?>"
                                            class="btn btn-primary btn-sm rounded-pill w-100">
                                            <i class="bi bi-eye me-1"></i> View Profile
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-12 text-center py-4 text-muted">
                                <i class="bi bi-eye-slash display-4 opacity-25"></i>
                                <p class="small mt-2 mb-0">No profile views yet</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sort Category Modal -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\users\dummy.blade.php ENDPATH**/ ?>