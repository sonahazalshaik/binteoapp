@extends('admin.layouts.app')

@section('panel')
<!-- Load Base Design System -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* GLOBAL & RESET */
    .user-audit-hub * { box-sizing: border-box; }
    .user-audit-hub { font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b; padding: 20px; max-width: 100%; overflow-x: hidden; }
    
    /* SCROLLBAR VISIBILITY - CONSTRAINED TO PREVENT PAGE BLOWOUT */
    .tab-container { background: #fff; border-radius: 25px; border: 1px solid #f1f5f9; position: relative; max-width: 100%; overflow: hidden; }
    .tab-navigation { 
        background: #f8fafc; 
        padding: 15px 15px 5px 15px; 
        border-bottom: 1px solid #f1f5f9; 
        border-radius: 25px 25px 0 0; 
        max-width: 100%;
        overflow: hidden;
    }
    
    .tab-scroll { 
        display: flex !important; 
        gap: 10px; 
        overflow-x: auto !important; 
        overflow-y: hidden !important;
        padding-bottom: 15px !important; 
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: #ff571a #e2e8f0;
    }

    /* FORCED WEBKIT VISIBILITY */
    .tab-scroll::-webkit-scrollbar { 
        height: 8px !important; 
        display: block !important; 
        background-color: #e2e8f0 !important;
    }
    .tab-scroll::-webkit-scrollbar-track { 
        background: #e2e8f0 !important; 
        border-radius: 10px !important;
    }
    .tab-scroll::-webkit-scrollbar-thumb { 
        background: #ff571a !important; 
        border-radius: 10px !important;
        border: 2px solid #e2e8f0 !important;
    }

    /* HEADER */
    .audit-header { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 1.5rem; border-radius: 20px; margin-bottom: 25px; border: 1px solid #f1f5f9; flex-wrap: wrap; gap: 15px; }
    .user-summary { display: flex; align-items: center; gap: 12px; }
    .status-indicator { width: 10px; height: 10px; border-radius: 50%; }
    .status-indicator.active { background: #10b981; box-shadow: 0 0 10px rgba(16, 185, 129, 0.4); }
    .status-indicator.banned { background: #ef4444; }
    .username { font-weight: 800; font-size: 0.9rem; color: #0f172a; }
    .badge { font-size: 0.65rem; font-weight: 900; background: #f1f5f9; padding: 4px 10px; border-radius: 10px; color: #64748b; text-transform: uppercase; }
    .header-actions { display: flex; gap: 10px; flex-wrap: wrap; }
    .audit-btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 12px; text-decoration: none !important; font-weight: 800; font-size: 0.75rem; border: none; cursor: pointer; color: #fff !important; transition: all 0.2s; }
    .btn-primary { background: #3b82f6; }
    .btn-danger { background: #ef4444; }
    .btn-success { background: #10b981; }
    .btn-warning { background: #f59e0b; }
    
    /* LAYOUT */
    .audit-layout { display: flex; gap: 25px; width: 100%; align-items: start; }
    .audit-sidebar { width: 340px; flex-shrink: 0; position: sticky; top: 20px; }
    .audit-main { flex: 1; min-width: 0; }
    
    /* SIDEBAR CARD */
    .profile-card { background: #fff; border-radius: 25px; padding: 40px 30px; text-align: center; border: 1px solid #f1f5f9; }
    .avatar-ring { width: 130px; height: 130px; margin: 0 auto 20px; padding: 6px; border-radius: 40px; background: #f8fafc; border: 1px solid #f1f5f9; }
    .avatar-box { width: 100%; height: 100%; border-radius: 35px; overflow: hidden; }
    .avatar-box img { width: 100%; height: 100%; object-fit: cover; }
    .avatar-fallback { width: 100%; height: 100%; background: #ff571a; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: 900; }
    .user-fullname { font-size: 1.5rem; font-weight: 800; margin: 0 0 5px; color: #0f172a; }
    .user-email { font-size: 0.85rem; color: #64748b; margin-bottom: 20px; overflow: hidden; text-overflow: ellipsis; }
    .user-pill-group { display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; margin-bottom: 30px; }
    .upill { font-size: 0.65rem; font-weight: 900; padding: 5px 12px; border-radius: 20px; color: #fff; text-transform: uppercase; }
    .upill.featured { background: linear-gradient(135deg, #f59e0b, #ea580c); }
    .upill.type { background: #6366f1; }
    .quick-metric-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 30px; }
    .q-metric { background: #f8fafc; padding: 15px 5px; border-radius: 18px; border: 1px solid #f1f5f9; }
    .q-metric .v { display: block; font-size: 1.1rem; font-weight: 800; color: #0f172a; }
    .q-metric .l { font-size: 0.6rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; }
    .sidebar-nav-stack { display: flex; flex-direction: column; gap: 10px; }
    .snav-btn { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 14px; border-radius: 16px; border: 2px solid #f1f5f9; background: #fff; font-weight: 800; font-size: 0.8rem; cursor: pointer; color: #475569; text-decoration: none !important; }
    .snav-btn:hover { border-color: #3b82f6; color: #3b82f6; }

    /* TAB TRIGGERS */
    .tab-trigger { 
        padding: 12px 20px; 
        border-radius: 12px; 
        border: none; 
        background: none; 
        font-weight: 800; 
        font-size: 0.7rem; 
        color: #64748b; 
        white-space: nowrap; 
        cursor: pointer; 
        text-transform: uppercase; 
        transition: all 0.2s; 
    }
    .tab-trigger.active { background: #fff; color: #3b82f6; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    .tab-content { padding: 30px; }

    /* OVERVIEW BLOCKS */
    .overview-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 20px; }
    .info-card { background: #f8fafc; border-radius: 20px; padding: 25px; border: 1px solid #f1f5f9; }
    .card-title { font-size: 0.7rem; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
    .card-title span { color: #3b82f6; }
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .info-box label { display: block; font-size: 0.6rem; font-weight: 900; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px; }
    .info-box div { font-size: 0.85rem; font-weight: 700; color: #1e293b; }

    /* CONTENT GRIDS */
    .content-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; }
    .content-card { background: #f8fafc; border-radius: 20px; overflow: hidden; border: 1px solid #f1f5f9; position: relative; }
    .c-thumb { aspect-ratio: 4/3; overflow: hidden; background: #000; }
    .c-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .c-meta { padding: 15px; }
    .c-title { font-size: 0.85rem; font-weight: 800; color: #0f172a; margin-bottom: 8px; }

    /* BREAKPOINTS */
    @media screen and (max-width: 1024px) {
        .audit-layout { flex-direction: column; }
        .audit-sidebar { width: 100%; position: relative; top: 0; }
        .overview-grid { grid-template-columns: 1fr; }
        .tab-trigger { padding: 10px 15px; font-size: 0.65rem; }
        .tab-navigation { padding: 10px 10px 20px 10px; }
    }
    
    .empty-msg { text-align: center; padding: 60px 20px; color: #94a3b8; font-weight: 800; }
    .animate-in { animation: hubFade 0.3s ease-out; }
    @keyframes hubFade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    /* LISTS */
    .kv-list { background: #fff; border-radius: 20px; border: 1px solid #f1f5f9; padding: 20px; }
    .kv-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px dashed #f1f5f9; font-size: 0.85rem; }
    .kv-row:last-child { border: none; }
    .kv-row span { color: #64748b; font-weight: 600; }
    .kv-row strong { color: #0f172a; font-weight: 800; }
</style>

<div class="user-audit-hub">
    <!-- Action Header -->
    <div class="audit-header">
        <div class="user-summary">
            <div class="status-indicator {{ $marketplace->status ? 'active' : 'banned' }}"></div>
            <span class="username">@ {{ $marketplace->name }}</span>
            <span class="badge">{{ $marketplace->type }}</span>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.marketplace.login.as.talent', $marketplace->id) }}" target="_blank" class="audit-btn btn-primary">
                <span class="material-symbols-rounded">login</span> Login As Talent
            </a>
            <a href="{{ route('admin.marketplace.toggle-featured', $marketplace->id) }}" class="audit-btn {{ $marketplace->is_featured ? 'btn-warning' : 'btn-info' }}">
                <span class="material-symbols-rounded">{{ $marketplace->is_featured ? 'star' : 'star_outline' }}</span> 
                {{ $marketplace->is_featured ? 'Unfeature' : 'Feature' }}
            </a>
            <form action="{{ route('admin.marketplace.status', $marketplace->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="audit-btn {{ $marketplace->status ? 'btn-danger' : 'btn-success' }}">
                    <span class="material-symbols-rounded">{{ $marketplace->status ? 'block' : 'check_circle' }}</span> 
                    {{ $marketplace->status ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
        </div>
    </div>

    <div class="audit-layout" x-data="{ currentTab: 'overview' }">
        <!-- Profile Column -->
        <aside class="audit-sidebar">
            <div class="profile-card">
                <div class="avatar-ring">
                    <div class="avatar-box">
                        @if($marketplace->image)
                            <img src="{{ $marketplace->photoUrl() }}" alt="Avatar">
                        @else
                            <div class="avatar-fallback">{{ substr($marketplace->name, 0, 1) }}</div>
                        @endif
                    </div>
                </div>
                
                <h2 class="user-fullname">{{ $marketplace->name }}</h2>
                <p class="user-email">{{ $marketplace->email }}</p>

                <div class="user-pill-group">
                    <span class="upill type">{{ $marketplace->type }}</span>
                    @if($marketplace->is_featured) <span class="upill featured">Featured Talent</span> @endif
                </div>

                <div class="quick-metric-grid">
                    <div class="q-metric"><span class="v">{{ number_format($marketplace->projects_count) }}</span><span class="l">Projects</span></div>
                    <div class="q-metric"><span class="v">{{ $marketplace->rating }}★</span><span class="l">Rating</span></div>
                    <div class="q-metric"><span class="v">{{ $marketplace->satisfaction_rate }}%</span><span class="l">Satisfaction</span></div>
                </div>

                <div class="sidebar-nav-stack">
                    <a href="{{ route('admin.marketplace.edit', $marketplace->id) }}" class="snav-btn">
                        <span class="material-symbols-rounded">edit</span> Edit Profile
                    </a>
                    <form action="{{ route('admin.marketplace.destroy', $marketplace->id) }}" method="POST" data-swal-question="Delete this profile?">
                        @csrf @method('DELETE')
                        <button type="submit" class="snav-btn" style="color: #ef4444;">
                            <span class="material-symbols-rounded">delete</span> Delete Talent
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Content Column -->
        <main class="audit-main">
            <div class="tab-container">
                <nav class="tab-navigation">
                    <div class="tab-scroll">
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'overview' }" @click="currentTab = 'overview'">Overview</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'services' }" @click="currentTab = 'services'">Services</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'gallery' }" @click="currentTab = 'gallery'">Gallery</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'contacts' }" @click="currentTab = 'contacts'">Inquiries</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'business' }" @click="currentTab = 'business'">Business</button>
                    </div>
                </nav>

                <div class="tab-content">
                    <!-- Overview -->
                    <div x-show="currentTab === 'overview'" class="tab-pane animate-in">
                        <div class="overview-grid">
                            <div class="info-card">
                                <h4 class="card-title"><span class="material-symbols-rounded">person</span> Profile Intel</h4>
                                <div class="info-grid">
                                    <div class="info-box"><label>Full Name</label><div>{{ $marketplace->name }}</div></div>
                                    <div class="info-box"><label>Email</label><div>{{ $marketplace->email }}</div></div>
                                    <div class="info-box"><label>Mobile</label><div>{{ $marketplace->number ?? 'N/A' }}</div></div>
                                    <div class="info-box"><label>Location</label><div>{{ $marketplace->location ?? 'N/A' }}</div></div>
                                    <div class="info-box"><label>Experience</label><div>{{ is_array($marketplace->years_of_experience) ? implode(', ', $marketplace->years_of_experience) : ($marketplace->years_of_experience ?? 'N/A') }}</div></div>
                                    <div class="info-box"><label>Rating</label><div>{{ $marketplace->rating }} / 5.0</div></div>
                                </div>
                                <h4 class="card-title mt-4"><span class="material-symbols-rounded">description</span> About</h4>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $marketplace->more_info ?: 'No bio provided.' }}</p>
                            </div>
                            <div class="info-card">
                                <h4 class="card-title"><span class="material-symbols-rounded">link</span> Connectivity</h4>
                                <div class="kv-list">
                                    <div class="kv-row"><span>Portfolio</span> <strong><a href="{{ $marketplace->portfolio_url }}" target="_blank">View Link</a></strong></div>
                                    <div class="kv-row"><span>Website</span> <strong><a href="{{ $marketplace->website_url }}" target="_blank">Visit Site</a></strong></div>
                                    <div class="kv-row"><span>Facebook</span> <strong><a href="{{ $marketplace->facebook_link }}" target="_blank">Connect</a></strong></div>
                                    <div class="kv-row"><span>Instagram</span> <strong><a href="{{ $marketplace->instagram_link }}" target="_blank">View</a></strong></div>
                                    <div class="kv-row"><span>Twitter</span> <strong><a href="{{ $marketplace->twitter_link }}" target="_blank">Feed</a></strong></div>
                                </div>
                            </div>
                        </div>

                        <div class="info-card mt-4">
                            <h4 class="card-title"><span class="material-symbols-rounded">psychology</span> Skills & Expertise</h4>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $skills = is_array($marketplace->skills) ? $marketplace->skills : explode(',', $marketplace->skills);
                                @endphp
                                @forelse($skills as $skill)
                                    @if(trim($skill))
                                        <span class="badge">{{ trim($skill) }}</span>
                                    @endif
                                @empty
                                    <span class="text-gray-400 ">No skills listed.</span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Services -->
                    <div x-show="currentTab === 'services'" class="tab-pane animate-in">
                        <div class="content-grid">
                            @forelse($marketplace->services as $service)
                                <div class="content-card">
                                    <div class="c-thumb">
                                        <img src="{{ $service->photoUrl() }}">
                                    </div>
                                    <div class="c-meta">
                                        <div class="c-title">{{ $service->title }}</div>
                                        <div class="flex justify-between text-xs font-bold text-green-600">
                                            <span>Starting At</span>
                                            <span>{{ showAmount($service->price) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-msg">No services published.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Gallery -->
                    <div x-show="currentTab === 'gallery'" class="tab-pane animate-in">
                        <div class="content-grid">
                            @forelse($marketplace->galleries as $gallery)
                                <div class="content-card">
                                    <div class="c-thumb">
                                        <img src="{{ $gallery->photoUrl() }}">
                                    </div>
                                    <div class="c-meta">
                                        <div class="c-title">{{ $gallery->title ?: 'Portfolio Asset' }}</div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-msg">No gallery items.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Inquiries -->
                    <div x-show="currentTab === 'contacts'" class="tab-pane animate-in">
                        <div class="table-wrap">
                            <table class="audit-table">
                                <thead><tr><th>Sender</th><th>Subject</th><th>Email</th><th>Date</th></tr></thead>
                                <tbody>
                                    @forelse($marketplace->contacts as $contact)
                                        <tr>
                                            <td class="bold">{{ $contact->name }}</td>
                                            <td>{{ $contact->subject }}</td>
                                            <td>{{ $contact->email }}</td>
                                            <td>{{ $contact->created_at->format('d M, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center py-4">No inquiries.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Business -->
                    <div x-show="currentTab === 'business'" class="tab-pane animate-in">
                        <div class="info-card">
                            <h4 class="card-title">Business Identity</h4>
                            <div class="kv-list">
                                <div class="kv-row"><span>Entity Name</span> <strong>{{ $marketplace->business_name ?? 'Individual' }}</strong></div>
                                <div class="kv-row"><span>Entity Type</span> <strong>{{ $marketplace->business_type ?? 'N/A' }}</strong></div>
                                <div class="kv-row"><span>Status</span> <strong>{{ $marketplace->status ? 'ACTIVE' : 'INACTIVE' }}</strong></div>
                                <div class="kv-row"><span>Registered</span> <strong>{{ $marketplace->created_at->format('M d, Y') }}</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

