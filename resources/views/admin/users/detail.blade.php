@extends('admin.layouts.app')

@section('panel')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* GLOBAL & RESET */
    .user-audit-hub * { box-sizing: border-box; }
    .user-audit-hub { 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        color: #1e293b; 
        padding: 20px; 
        max-width: 100vw; 
        overflow-x: hidden; /* Prevent horizontal page shake */
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
    .btn-info { background: #6366f1; }
    
    /* LAYOUT */
    .audit-layout { display: flex; gap: 25px; width: 100%; align-items: start; }
    .audit-sidebar { 
        width: 340px; 
        flex-shrink: 0; 
        position: sticky; 
        top: 90px; 
        height: calc(100vh - 110px); 
        overflow-y: auto;
        padding-right: 5px;
    }
    .audit-sidebar::-webkit-scrollbar { width: 4px; }
    .audit-sidebar::-webkit-scrollbar-track { background: transparent; }
    .audit-sidebar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
    
    .audit-main { 
        flex: 1; 
        min-width: 0; 
        height: calc(100vh - 180px); 
        overflow-y: auto; 
        padding-right: 5px;
    } 
    .audit-main::-webkit-scrollbar { width: 6px; }
    .audit-main::-webkit-scrollbar-track { background: transparent; }
    .audit-main::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 6px; }
    
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
    .upill.kv { background: #3b82f6; }
    .upill.kn { background: #f59e0b; }
    .upill.creator { background: #8b5cf6; }
    .quick-metric-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 30px; }
    .q-metric { background: #f8fafc; padding: 15px 5px; border-radius: 18px; border: 1px solid #f1f5f9; }
    .q-metric .v { display: block; font-size: 1.1rem; font-weight: 800; color: #0f172a; }
    .q-metric .l { font-size: 0.6rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; }
    .balance-display { background: #f1f5f9; padding: 25px; border-radius: 20px; margin-bottom: 30px; }
    .balance-display label { display: block; font-size: 0.6rem; font-weight: 900; color: #64748b; margin-bottom: 6px; text-transform: uppercase; }
    .balance-val { font-size: 1.75rem; font-weight: 900; color: #10b981; letter-spacing: -1px; }
    .sidebar-nav-stack { display: flex; flex-direction: column; gap: 10px; }
    .snav-btn { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 14px; border-radius: 16px; border: 2px solid #f1f5f9; background: #fff; font-weight: 800; font-size: 0.8rem; cursor: pointer; color: #475569; text-decoration: none !important; }
    .snav-btn:hover { border-color: #3b82f6; color: #3b82f6; }

    /* TAB SYSTEM */
    .tab-container { background: #fff; border-radius: 25px; border: 1px solid #f1f5f9; position: relative; width: 100%; overflow: hidden; }
    .tab-navigation { 
        background: #f8fafc; 
        padding: 15px 15px 5px 15px; 
        border-bottom: 1px solid #f1f5f9; 
        border-radius: 25px 25px 0 0; 
        width: 100%;
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

    .tab-scroll::-webkit-scrollbar { height: 6px !important; display: block !important; }
    .tab-scroll::-webkit-scrollbar-track { background: #e2e8f0 !important; border-radius: 10px !important; }
    .tab-scroll::-webkit-scrollbar-thumb { background: #ff571a !important; border-radius: 10px !important; }

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
    .tab-content { padding: 30px; width: 100%; min-height: 400px; }

    /* OVERVIEW BLOCKS */
    .overview-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 20px; }
    .info-card { background: #f8fafc; border-radius: 20px; padding: 25px; border: 1px solid #f1f5f9; overflow: hidden; }
    .card-title { font-size: 0.7rem; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
    .card-title span { color: #3b82f6; }
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .info-box label { display: block; font-size: 0.6rem; font-weight: 900; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px; }
    .info-box div { font-size: 0.85rem; font-weight: 700; color: #1e293b; word-break: break-all; }
    .status-list { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .status-item { display: flex; align-items: center; gap: 10px; font-size: 0.8rem; font-weight: 700; color: #475569; padding: 12px; border-radius: 12px; background: #fff; border: 1px solid #f1f5f9; }
    .status-item span { font-size: 1.2rem; }
    .status-item.success { color: #10b981; }
    .status-item.danger { color: #ef4444; opacity: 0.8; }
    .metric-tiles { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    @media (max-width: 640px) { .metric-tiles { grid-template-columns: repeat(2, 1fr); } }
    .tile { padding: 35px 20px; border-radius: 25px; text-align: center; }
    .tile.blue { background: rgba(59, 130, 246, 0.05); color: #3b82f6; }
    .tile.purple { background: rgba(168, 85, 247, 0.05); color: #a855f7; }
    .tile.emerald { background: rgba(16, 185, 129, 0.05); color: #10b981; }
    .tile.rose { background: rgba(239, 68, 68, 0.05); color: #ef4444; }
    .tile.amber { background: rgba(245, 158, 11, 0.05); color: #f59e0b; }
    .tile .v { font-size: 2rem; font-weight: 900; line-height: 1; }
    .tile .l { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; opacity: 0.7; margin-top: 10px; }

    /* FORMS */
    .identity-form .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .identity-form .full-width { grid-column: span 2; }
    .f-group label { display: block; font-size: 0.65rem; font-weight: 900; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px; }
    .f-group input, .f-group select, .f-group textarea { width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #f1f5f9; background: #fff; font-size: 0.85rem; font-weight: 700; color: #1e293b; transition: all 0.2s; }
    .f-group input:focus { border-color: #3b82f6; outline: none; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
    .save-btn { margin-top: 30px; background: #0f172a; color: #fff; border: none; padding: 15px 30px; border-radius: 12px; font-weight: 800; font-size: 0.8rem; cursor: pointer; transition: all 0.2s; }

    /* TABLES */
    .table-wrap { border-radius: 20px; border: 1px solid #f1f5f9; overflow-x: auto; width: 100%; }
    .audit-table { width: 100%; border-collapse: collapse; min-width: 600px; }
    .audit-table th { text-align: left; background: #f8fafc; padding: 12px 20px; font-size: 0.65rem; font-weight: 900; color: #94a3b8; text-transform: uppercase; border-bottom: 1px solid #f1f5f9; }
    .audit-table td { padding: 12px 20px; font-size: 0.85rem; border-bottom: 1px solid #f8fafc; }

    /* TIMELINE */
    .timeline { display: flex; flex-direction: column; gap: 15px; }
    .timeline-item { display: flex; align-items: center; gap: 20px; background: #f8fafc; padding: 20px; border-radius: 20px; border: 1px solid #f1f5f9; }
    .tl-icon { width: 50px; height: 50px; border-radius: 15px; background: #fff; display: flex; align-items: center; justify-content: center; border: 1px solid #f1f5f9; flex-shrink: 0; }
    .tl-icon.blue { color: #3b82f6; }
    .tl-icon.orange { color: #f97316; }
    .tl-icon.faint { color: #94a3b8; }
    .tl-body { flex: 1; min-width: 0; }
    .tl-subject { font-size: 0.85rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .tl-date { font-size: 0.75rem; color: #64748b; font-weight: 600; }
    .tl-stamp { font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; flex-shrink: 0; }

    /* KYC VAULT */
    .kyc-vault-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
    .kv-list { background: #fff; border-radius: 20px; border: 1px solid #f1f5f9; padding: 20px; }
    .kv-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px dashed #f1f5f9; font-size: 0.85rem; gap: 10px; }
    .kv-row:last-child { border: none; }
    .kv-row span { color: #64748b; font-weight: 600; white-space: nowrap; }
    .kv-row strong { color: #0f172a; font-weight: 800; text-align: right; word-break: break-all; }
    .ev-item { background: #fff; border-radius: 20px; border: 1px solid #f1f5f9; padding: 15px; text-align: center; margin-bottom: 20px; }
    .ev-item img { width: 100%; border-radius: 12px; margin-bottom: 10px; }

    /* CONTENT GRIDS */
    .content-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; }
    .content-grid.reels-grid { grid-template-columns: repeat(4, 1fr); }
    .content-card { background: #f8fafc; border-radius: 20px; overflow: hidden; border: 1px solid #f1f5f9; transition: transform 0.2s; position: relative; }
    .content-card:hover { transform: translateY(-5px); }
    .c-thumb { aspect-ratio: 16/9; overflow: hidden; background: #000; position: relative; }
    .c-thumb.shorts-ratio { aspect-ratio: 9/16; }
    .c-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .c-meta { padding: 15px; }
    .c-title { font-size: 0.85rem; font-weight: 800; color: #0f172a; margin-bottom: 8px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; }
    .c-foot { display: flex; justify-content: space-between; align-items: center; font-size: 0.7rem; color: #64748b; font-weight: 700; }
    .view-link { position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.9); padding: 5px; border-radius: 8px; color: #0f172a; display: flex; align-items: center; opacity: 0; transition: opacity 0.2s; }
    .content-card:hover .view-link { opacity: 1; }

    /* SUBS */
    .sub-item { display: flex; align-items: center; gap: 20px; padding: 20px; background: #f8fafc; border-radius: 20px; border: 1px solid #f1f5f9; margin-bottom: 12px; }
    .si-icon { width: 45px; height: 45px; border-radius: 12px; background: rgba(59, 130, 246, 0.1); color: #3b82f6; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .si-body { flex: 1; min-width: 0; }
    .si-name { font-size: 0.9rem; font-weight: 800; color: #0f172a; }
    .si-expiry { font-size: 0.7rem; color: #64748b; font-weight: 700; }
    .si-price { font-size: 1rem; font-weight: 900; color: #10b981; }
    .si-type { font-size: 0.6rem; font-weight: 900; background: #3b82f6; color: #fff; padding: 2px 8px; border-radius: 6px; margin-left: 8px; vertical-align: middle; }
    .si-type.ott { background: #8b5cf6; }

    /* UPDATED BREAKPOINTS FOR MOBILE & TABLET */
    @media screen and (max-width: 1024px) {
        .audit-layout { flex-direction: column; }
        .audit-sidebar { width: 100%; position: relative; top: 0; height: auto; overflow-y: visible; padding-right: 0; }
        .audit-main { width: 100%; height: auto; overflow-y: visible; padding-right: 0; }
        .overview-grid, .kyc-vault-grid { grid-template-columns: 1fr; }
        .ch-content { grid-template-columns: 1fr; }
        .content-grid.reels-grid { grid-template-columns: repeat(3, 1fr); }
    }
    
    @media screen and (max-width: 768px) {
        .user-audit-hub { padding: 10px; }
        .info-grid, .status-list, .identity-form .form-grid { grid-template-columns: 1fr; }
        .metric-tiles { grid-template-columns: 1fr; } /* Stack tiles on small screens */
        .identity-form .full-width { grid-column: span 1; }
        .audit-header { padding: 1rem; flex-direction: column; align-items: flex-start; }
        .tab-content { padding: 15px; }
        .tab-navigation { padding: 10px 10px 20px 10px; }
        .tab-trigger { padding: 10px 15px; font-size: 0.65rem; }
        .tile { padding: 20px; }
        .quick-metric-grid { grid-template-columns: repeat(3, 1fr); }
        .content-grid.reels-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    }
    
    .empty-msg { text-align: center; padding: 60px 20px; color: #94a3b8; font-weight: 800; font-style: ; }
    .animate-in { animation: hubFade 0.3s ease-out; }
    @keyframes hubFade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="user-audit-hub">
    <div class="audit-header">
        <div class="user-summary">
            <div class="status-indicator {{ $user->status == 'active' ? 'active' : 'banned' }}"></div>
            <span class="username">@ {{ $user->username }}</span>
            <span class="badge">{{ $user->status }}</span>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.users.login', $user->id) }}" target="_blank" class="audit-btn btn-primary">
                <span class="material-symbols-rounded">login</span> Login
            </a>
            @if($user->status == 'active')
                <button type="button" @click="$dispatch('open-ban-modal', { id: {{ $user->id }}, name: '{{ $user->username }}' })" class="audit-btn btn-danger">
                    <span class="material-symbols-rounded">block</span> Ban
                </button>
            @else
                <form action="{{ route('admin.users.status', $user->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="audit-btn btn-success">
                        <span class="material-symbols-rounded">verified_user</span> Unban
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="audit-layout" x-data="{ currentTab: 'overview' }">
        <aside class="audit-sidebar">
            <div class="profile-card">
                <div class="avatar-ring">
                    <div class="avatar-box">
                        @php
                            $userAvatar = @$user->channel->avatar ? getFilePath('channelAvatar').'/'.$user->channel->avatar : ($user->image ? getFilePath('userProfile').'/'.$user->image : null);
                            $avatarUrl = $userAvatar ? getImage($userAvatar) : null;
                            $hasCustomAvatar = $userAvatar && !str_contains($avatarUrl, 'default.png') && !str_contains($avatarUrl, 'avatar.png');
                            // Letters must work even when firstname/lastname are empty
                            // (legacy rows only have `name`): derive from fullname.
                            $displayName = trim($user->fullname ?? '');
                            $nameWords = preg_split('/\s+/', $displayName, -1, PREG_SPLIT_NO_EMPTY);
                            if (count($nameWords) >= 2) {
                                $initials = strtoupper(substr($nameWords[0], 0, 1) . substr(end($nameWords), 0, 1));
                                $firstLine = $nameWords[0];
                                $secondLine = end($nameWords);
                            } else {
                                $initials = strtoupper(substr($displayName !== '' ? $displayName : 'U', 0, 2));
                                $firstLine = $displayName !== '' ? $displayName : 'User';
                                $secondLine = '';
                            }
                        @endphp

                        @if($hasCustomAvatar)
                            <img src="{{ $avatarUrl }}" alt="Avatar">
                        @else
                            <div class="avatar-fallback" style="display: flex; flex-direction: column; justify-content: center; align-items: center; line-height: 1.2; padding: 10px;">
                                <div style="font-size: 1.8rem; margin-bottom: 4px;">{{ $initials }}</div>
                                <div style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; opacity: 0.9; letter-spacing: 0.5px;">{{ $firstLine }}</div>
                                @if($secondLine)<div style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; opacity: 0.9; letter-spacing: 0.5px;">{{ $secondLine }}</div>@endif
                            </div>
                        @endif
                    </div>
                </div>
                
                <h2 class="user-fullname">{{ $user->fullname }}</h2>
                <p class="user-email">{{ $user->email }}</p>

                <div class="user-pill-group">
                    <span class="upill {{ $user->kv ? 'kv' : 'kn' }}">{{ $user->kv ? 'KYC Verified' : 'KYC Pending' }}</span>
                    @if($user->isCreator()) <span class="upill creator">Creator</span> @endif
                </div>

                <div class="quick-metric-grid">
                    <div class="q-metric"><span class="v">{{ number_format($widget['totalVideos']) }}</span><span class="l">Videos</span></div>
                    <div class="q-metric"><span class="v">{{ number_format($widget['totalSubscriber']) }}</span><span class="l">Subs</span></div>
                    <div class="q-metric"><span class="v">{{ number_format($widget['totalTickets']) }}</span><span class="l">Tkt</span></div>
                </div>

                <div class="balance-display">
                    <label>Liquid Balance</label>
                    <div class="balance-val">{{ showAmount($user->balance) }}</div>
                </div>

                <div class="sidebar-nav-stack">
                    <button @click="$dispatch('open-wallet-modal', { id: {{ $user->id }}, name: '{{ $user->username }}' })" class="snav-btn">
                        <span class="material-symbols-rounded">account_balance_wallet</span> Adjust Balance
                    </button>
                    <button @click="$dispatch('open-plan-modal', { id: {{ $user->id }}, name: '{{ $user->username }}' })" class="snav-btn">
                        <span class="material-symbols-rounded">workspace_premium</span> Update Plan
                    </button>
                </div>
            </div>
        </aside>

        <main class="audit-main">
            <div class="tab-container">
                <nav class="tab-navigation">
                    <div class="tab-scroll">
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'overview' }" @click="currentTab = 'overview'">Overview</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'identity' }" @click="currentTab = 'identity'">Identity</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'videos' }" @click="currentTab = 'videos'">Videos</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'reels' }" @click="currentTab = 'reels'">Reels</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'playlists' }" @click="currentTab = 'playlists'">Playlists</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'watchlater' }" @click="currentTab = 'watchlater'">Watch Later</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'liked' }" @click="currentTab = 'liked'">Liked</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'video-likes' }" @click="currentTab = 'video-likes'">Video Likes</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'video-comments' }" @click="currentTab = 'video-comments'">Video Comments</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'reel-comments' }" @click="currentTab = 'reel-comments'">Reel Comments</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'history' }" @click="currentTab = 'history'">History</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'earnings' }" @click="currentTab = 'earnings'">Earnings</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'channel' }" @click="currentTab = 'channel'">Channel</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'subscribers' }" @click="currentTab = 'subscribers'">Subscribers</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'transactions' }" @click="currentTab = 'transactions'">Transactions</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'subs' }" @click="currentTab = 'subs'">Subscriptions</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'market' }" @click="currentTab = 'market'">Market</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'kyc' }" @click="currentTab = 'kyc'">KYC</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'support' }" @click="currentTab = 'support'">Support</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'notifications' }" @click="currentTab = 'notifications'">Notifications</button>
                        <button class="tab-trigger" :class="{ 'active': currentTab === 'logs' }" @click="currentTab = 'logs'">Logs</button>
                    </div>
                </nav>

                <div class="tab-content">
                    <div x-show="currentTab === 'overview'" class="tab-pane animate-in">
                        <div class="overview-grid">
                            <div class="info-card">
                                <h4 class="card-title"><span class="material-symbols-rounded">badge</span> Core Identity</h4>
                                <div class="info-grid">
                                    <div class="info-box"><label>Full Name</label><div>{{ $user->fullname }}</div></div>
                                    <div class="info-box"><label>Email Axis</label><div>{{ $user->email }}</div></div>
                                    <div class="info-box"><label>Mobile No.</label><div>{{ $user->mobile ?? 'N/A' }}</div></div>
                                    <div class="info-box"><label>Country</label><div>{{ $user->country_name ?: 'N/A' }}</div></div>
                                    <div class="info-box"><label>Registration</label><div>{{ $user->created_at->format('d M, Y') }}</div></div>
                                    <div class="info-box"><label>Last Sync</label><div>{{ \Carbon\Carbon::parse($user->loginLogs()->latest()->first()?->created_at)->diffForHumans() }}</div></div>
                                </div>
                            </div>
                            <div class="info-card">
                                <h4 class="card-title"><span class="material-symbols-rounded">verified_user</span> Verification Matrix</h4>
                                <div class="status-list">
                                    <div class="status-item {{ $user->ev ? 'success' : 'danger' }}"><span class="material-symbols-rounded">{{ $user->ev ? 'verified' : 'cancel' }}</span> Email Axis</div>
                                    <div class="status-item {{ $user->kv ? 'success' : 'danger' }}"><span class="material-symbols-rounded">{{ $user->kv ? 'verified' : 'cancel' }}</span> KYC Portfolio</div>
                                </div>
                            </div>
                        </div>

                        <div class="metric-tiles mt-4">
                            <div class="tile blue"><div class="v">{{ number_format($user->videos()->sum('views_count')) }}</div><div class="l">Views</div></div>
                            <div class="tile purple"><div class="v">{{ number_format($user->likes()->count()) }}</div><div class="l">Video Likes</div></div>
                            <div class="tile emerald"><div class="v">{{ number_format($widget['totalComments']) }}</div><div class="l">Video Comments</div></div>
                            <div class="tile rose"><div class="v">{{ number_format($user->reelLikes()->count()) }}</div><div class="l">Reel Likes</div></div>
                            <div class="tile amber"><div class="v">{{ number_format($user->reelComments()->count()) }}</div><div class="l">Reel Comments</div></div>
                        </div>

                        <div class="overview-grid mt-4">
                            <div class="info-card">
                                <h4 class="card-title"><span class="material-symbols-rounded">account_balance</span> Financial</h4>
                                <div class="kv-list">
                                    <div class="kv-row"><span>Deposited</span> <strong>{{ showAmount($totalDeposit) }}</strong></div>
                                    <div class="kv-row"><span>Withdrawn</span> <strong>{{ showAmount($totalWithdrawals) }}</strong></div>
                                    <div class="kv-row"><span>TRX Logs</span> <strong>{{ $totalTransaction }}</strong></div>
                                </div>
                            </div>
                            <div class="info-card">
                                <h4 class="card-title"><span class="material-symbols-rounded">insights</span> Audit</h4>
                                <div class="kv-list">
                                    <div class="kv-row"><span>Videos</span> <strong>{{ $widget['totalVideos'] }}</strong></div>
                                    <div class="kv-row"><span>Tickets</span> <strong>{{ $widget['totalTickets'] }}</strong></div>
                                    <div class="kv-row"><span>Joined</span> <strong>{{ $user->created_at->diffForHumans() }}</strong></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-show="currentTab === 'identity'" class="tab-pane animate-in" x-cloak>
                        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-10" enctype="multipart/form-data" 
                              x-data="{ synching: false }" @submit="synching = true">
                            @csrf
                            
                            <!-- Personal Information Card -->
                            <div class="bg-slate-50 dark:bg-white/[0.02] rounded-[2rem] border border-slate-100 dark:border-white/5 p-8">
                                <div class="flex items-center gap-4 mb-8">
                                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center">
                                        <span class="material-symbols-rounded">fingerprint</span>
                                    </div>
                                    <h4 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tighter">Personal Information</h4>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">First Name</label>
                                        <input type="text" name="firstname" value="{{ $user->firstname }}" required class="w-full h-12 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 text-xs font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">Last Name</label>
                                        <input type="text" name="lastname" value="{{ $user->lastname }}" required class="w-full h-12 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 text-xs font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">Username</label>
                                        <input type="text" value="{{ $user->username }}" disabled class="w-full h-12 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 text-xs font-bold text-slate-400 dark:text-white/40 focus:ring-2 focus:ring-blue-500 transition-all outline-none cursor-not-allowed">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">Email Address</label>
                                        <input type="email" name="email" value="{{ $user->email }}" required class="w-full h-12 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 text-xs font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">Mobile Number</label>
                                        <input type="text" name="mobile" value="{{ $user->mobile }}" required class="w-full h-12 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 text-xs font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                                    </div>
                                </div>
                            </div>

                            <!-- Address Information Card -->
                            <div class="bg-slate-50 dark:bg-white/[0.02] rounded-[2rem] border border-slate-100 dark:border-white/5 p-8">
                                <div class="flex items-center gap-4 mb-8">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center">
                                        <span class="material-symbols-rounded">public</span>
                                    </div>
                                    <h4 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tighter">Address Information</h4>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">Country</label>
                                        <select name="country" required class="w-full h-12 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 text-xs font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none cursor-pointer">
                                            @foreach($countries as $k => $c) 
                                                <option value="{{ $k }}" {{ @$user->country_code == $k ? 'selected' : '' }}>{{ $c->country }}</option> 
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">City</label>
                                        <input type="text" name="city" value="{{ $user->city }}" class="w-full h-12 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 text-xs font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">State</label>
                                        <input type="text" name="state" value="{{ $user->state }}" class="w-full h-12 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 text-xs font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">ZIP / Postal Code</label>
                                        <input type="text" name="zip" value="{{ $user->zip }}" class="w-full h-12 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 text-xs font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none">
                                    </div>
                                    <div class="space-y-2 md:col-span-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">Street Address</label>
                                        <input type="text" name="address" value="{{ $user->address }}" class="w-full h-12 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 text-xs font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none">
                                    </div>
                                    <div class="space-y-2 md:col-span-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">About / Biography</label>
                                        <textarea name="description" rows="3" class="w-full bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl p-6 text-xs font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none">{{ $user->description }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Branding & Channel Information Card -->
                            <div class="bg-slate-50 dark:bg-white/[0.02] rounded-[2rem] border border-slate-100 dark:border-white/5 p-8 mt-8">
                                <div class="flex items-center gap-4 mb-8">
                                    <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-500 flex items-center justify-center">
                                        <span class="material-symbols-rounded">palette</span>
                                    </div>
                                    <h4 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tighter">Branding & Channel</h4>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-2 md:col-span-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">Channel Name</label>
                                        <input type="text" name="channel_name" value="{{ $user->channel->name ?? '' }}" class="w-full h-12 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 text-xs font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-purple-500 transition-all outline-none">
                                    </div>
                                    <div class="space-y-2 md:col-span-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">Channel Description</label>
                                        <textarea name="channel_description" rows="3" class="w-full bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl p-6 text-xs font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-purple-500 transition-all outline-none">{{ $user->channel->description ?? '' }}</textarea>
                                    </div>
                                    <div class="space-y-2 md:col-span-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">Profile Picture (Global Avatar)</label>
                                        <input type="file" name="image" accept="image/jpeg, image/png, image/jpg" class="w-full bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-xs font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-purple-500 transition-all outline-none">
                                    </div>
                                    <div class="space-y-2 md:col-span-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">Channel Avatar</label>
                                        <input type="file" name="channel_avatar" accept="image/jpeg, image/png, image/jpg" class="w-full bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-xs font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-purple-500 transition-all outline-none">
                                    </div>
                                    <div class="space-y-2 md:col-span-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">Channel Banner (Cover Image)</label>
                                        <input type="file" name="channel_banner" accept="image/jpeg, image/png, image/jpg" class="w-full bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-xs font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-purple-500 transition-all outline-none">
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-4">
                                <button type="submit" class="h-14 px-10 rounded-2xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[10px] font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-xl flex items-center gap-3" :disabled="synching">
                                    <span x-text="synching ? 'Synchronizing Node...' : 'Commit Profile Sync'">Commit Profile Sync</span>
                                    <span x-show="!synching" class="material-symbols-rounded">sync_alt</span>
                                    <span x-show="synching" class="material-symbols-rounded animate-spin">sync</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div x-show="currentTab === 'videos'" class="tab-pane animate-in" x-cloak>
                        <div class="content-grid">
                            @forelse($user->videos()->latest()->take(30)->get() as $v)
                                <div class="content-card">
                                    <div class="c-thumb">
                                        <img src="{{ $v->getThumbnailUrl() }}">
                                        <a href="{{ route('videos.show', $v->slug) }}" target="_blank" class="view-link"><span class="material-symbols-rounded">open_in_new</span></a>
                                    </div>
                                    <div class="c-meta">
                                        <div class="c-title">{{ $v->title }}</div>
                                        <div class="c-foot">
                                            <span>{{ formatNumber($v->views_count) }} views</span>
                                            <div class="flex gap-2">
                                                <a href="{{ route('admin.videos.edit', $v->id) }}"><i class="material-symbols-rounded" style="font-size: 1.2rem;">edit</i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-msg">No videos found.</div>
                            @endforelse
                        </div>
                    </div>

                    <div x-show="currentTab === 'reels'" class="tab-pane animate-in" x-cloak>
                        <div class="content-grid reels-grid">
                            @forelse($user->reels()->latest()->get() as $r)
                                <div class="content-card">
                                    <div class="c-thumb shorts-ratio">
                                        <img src="{{ $r->getThumbnailUrl() }}">
                                        <a href="{{ route('reels.show', $r->slug) }}" target="_blank" class="view-link"><span class="material-symbols-rounded">open_in_new</span></a>
                                    </div>
                                    <div class="c-meta">
                                        <div class="c-title">{{ $r->title }}</div>
                                        <div class="c-foot"><span>{{ formatNumber($r->views_count) }} views</span></div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-msg">No reels data.</div>
                            @endforelse
                        </div>
                    </div>

                    <div x-show="currentTab === 'playlists'" class="tab-pane animate-in" x-cloak>
                        <div class="content-grid">
                            @forelse($user->playlists()->withCount('videos')->get() as $pl)
                                <div class="content-card">
                                    <div class="c-thumb"><img src="{{ $pl->videos()->first()?->getThumbnailUrl() ?? getImage(null) }}"></div>
                                    <div class="c-meta">
                                        <div class="c-title">{{ $pl->title }}</div>
                                        <div class="c-foot"><span>{{ $pl->videos_count }} videos</span></div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-msg">No playlists.</div>
                            @endforelse
                        </div>
                    </div>

                    <div x-show="currentTab === 'watchlater'" class="tab-pane animate-in" x-cloak>
                        <div class="content-grid">
                            @forelse($user->watchLaters()->get() as $v)
                                <div class="content-card">
                                    <div class="c-thumb"><img src="{{ $v->getThumbnailUrl() }}"></div>
                                    <div class="c-meta">
                                        <div class="c-title">{{ $v->title }}</div>
                                        <div class="c-foot"><span>{{ formatNumber($v->views_count) }} views</span></div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-msg">Empty queue.</div>
                            @endforelse
                        </div>
                    </div>

                    <div x-show="currentTab === 'liked'" class="tab-pane animate-in" x-cloak>
                        <div class="content-grid">
                            @forelse($user->likes()->with('video')->latest()->take(20)->get() as $lk)
                                <div class="content-card">
                                    <div class="c-thumb"><img src="{{ optional($lk->video)->getThumbnailUrl() ?? getImage(null) }}"></div>
                                    <div class="c-meta">
                                        <div class="c-title">{{ optional($lk->video)->title ?: 'Video Deleted' }}</div>
                                        <div class="c-foot"><span>Reaction Recorded</span></div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-msg">No liked videos.</div>
                            @endforelse
                        </div>
                    </div>

                    <div x-show="currentTab === 'video-likes'" class="tab-pane animate-in" x-cloak>
                        <div class="timeline">
                            @forelse(\App\Models\Like::whereIn('video_id', $user->videos()->pluck('id'))->with(['user', 'video'])->latest()->take(50)->get() as $lk)
                                <div class="timeline-item">
                                    <div class="tl-icon red"><span class="material-symbols-rounded">thumb_up</span></div>
                                    <div class="tl-body">
                                        <div class="tl-subject">{{ optional($lk->user)->fullname ?: 'Deleted User' }} <span class="text-slate-400">liked</span> {{ optional($lk->video)->title ?: 'Video Deleted' }}</div>
                                        <div class="tl-date">{{ optional($lk->video)->views_count ? formatNumber($lk->video->views_count) . ' views' : '' }}</div>
                                    </div>
                                    <div class="tl-stamp">{{ $lk->created_at->diffForHumans() }}</div>
                                </div>
                            @empty
                                <div class="empty-msg">No video likes.</div>
                            @endforelse
                        </div>
                    </div>

                    <div x-show="currentTab === 'video-comments'" class="tab-pane animate-in" x-cloak>
                        <div class="timeline">
                            @forelse(\App\Models\Comment::whereIn('video_id', $user->videos()->pluck('id'))->with(['user', 'video'])->latest()->take(50)->get() as $cm)
                                <div class="timeline-item">
                                    <div class="tl-icon blue"><span class="material-symbols-rounded">comment</span></div>
                                    <div class="tl-body">
                                        <div class="tl-subject">{{ optional($cm->user)->fullname ?: 'Deleted User' }} on {{ optional($cm->video)->title ?: 'Video Deleted' }}</div>
                                        <div class="tl-date">{{ Str::limit($cm->content, 120) }}</div>
                                    </div>
                                    <div class="tl-stamp">{{ $cm->created_at->diffForHumans() }}</div>
                                </div>
                            @empty
                                <div class="empty-msg">No video comments.</div>
                            @endforelse
                        </div>
                    </div>

                    <div x-show="currentTab === 'reel-comments'" class="tab-pane animate-in" x-cloak>
                        <div class="timeline">
                            @forelse(\App\Models\ReelComment::whereIn('reel_id', $user->reels()->pluck('id'))->with(['user', 'reel'])->latest()->take(50)->get() as $rc)
                                <div class="timeline-item">
                                    <div class="tl-icon purple"><span class="material-symbols-rounded">forum</span></div>
                                    <div class="tl-body">
                                        <div class="tl-subject">{{ optional($rc->user)->fullname ?: 'Deleted User' }} on {{ optional($rc->reel)->title ?: 'Reel Deleted' }}</div>
                                        <div class="tl-date">{{ Str::limit($rc->content, 120) }}</div>
                                    </div>
                                    <div class="tl-stamp">{{ $rc->created_at->diffForHumans() }}</div>
                                </div>
                            @empty
                                <div class="empty-msg">No reel comments.</div>
                            @endforelse
                        </div>
                    </div>

                    <div x-show="currentTab === 'history'" class="tab-pane animate-in" x-cloak>
                        <div class="timeline">
                            @forelse($user->watchHistories()->with('video')->latest()->take(30)->get() as $h)
                                <div class="timeline-item">
                                    <div class="tl-icon blue"><span class="material-symbols-rounded">history</span></div>
                                    <div class="tl-body">
                                        <div class="tl-subject">{{ optional($h->video)->title ?: 'Video Deleted' }}</div>
                                        <div class="tl-date">Watched for {{ $h->progress_seconds }}s</div>
                                    </div>
                                    <div class="tl-stamp">{{ \Carbon\Carbon::parse($h->last_watched_at)->diffForHumans() }}</div>
                                </div>
                            @empty
                                <div class="empty-msg">No history.</div>
                            @endforelse
                        </div>
                    </div>

                    <div x-show="currentTab === 'earnings'" class="tab-pane animate-in" x-cloak>
                        <div class="metric-tiles mb-5">
                            <div class="tile emerald"><div class="v">{{ showAmount($user->videoEarnings()->sum('estimated_revenue')) }}</div><div class="l">Est. Revenue</div></div>
                            <div class="tile blue"><div class="v">{{ number_format($user->videoEarnings()->sum('ad_impressions')) }}</div><div class="l">Impressions</div></div>
                            <div class="tile purple"><div class="v">{{ number_format($user->videoEarnings()->sum('ad_clicks')) }}</div><div class="l">Clicks</div></div>
                        </div>
                        <div class="table-wrap">
                            <table class="audit-table">
                                <thead><tr><th>Asset</th><th>Imp</th><th>Clk</th><th>Revenue</th><th>Sync</th></tr></thead>
                                <tbody>
                                    @forelse($user->videoEarnings()->with('video')->latest()->take(20)->get() as $e)
                                        <tr>
                                            <td class="bold">{{ optional($e->video)->title ?: 'Video Deleted' }}</td>
                                            <td>{{ number_format($e->ad_impressions) }}</td>
                                            <td>{{ number_format($e->ad_clicks) }}</td>
                                            <td class="text-green bold">{{ showAmount($e->estimated_revenue) }}</td>
                                            <td>{{ $e->date->format('d M, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center py-4">No earnings.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div x-show="currentTab === 'channel'" class="tab-pane animate-in" x-cloak>
                        @if($user->channel)
                            <div class="creator-hub">
                                <style>
                                    .ch-hero { position: relative; height: 180px; border-radius: 20px; overflow: hidden; background: #0f172a; margin-bottom: 60px; }
                                    .ch-banner { width: 100%; height: 100%; object-fit: cover; opacity: 0.7; }
                                    .ch-avatar-wrap { position: absolute; bottom: -40px; left: 30px; width: 110px; height: 110px; padding: 5px; background: #fff; border-radius: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
                                    .ch-avatar { width: 100%; height: 100%; border-radius: 25px; object-fit: cover; }
                                    .ch-content { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
                                    .ch-main-info { background: #f8fafc; border-radius: 25px; padding: 30px; border: 1px solid #f1f5f9; }
                                    .ch-name { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 10px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
                                    .ch-desc { font-size: 0.9rem; color: #475569; line-height: 1.6; margin-bottom: 25px; }
                                    .ch-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 25px; }
                                    .ch-stat-box { background: #fff; padding: 15px; border-radius: 18px; border: 1px solid #f1f5f9; text-align: center; }
                                    .ch-stat-val { display: block; font-size: 1.2rem; font-weight: 800; color: #0f172a; }
                                    .ch-stat-lbl { font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; }
                                    .social-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
                                    .social-pill { display: flex; align-items: center; gap: 10px; padding: 12px 15px; background: #fff; border-radius: 15px; border: 1px solid #f1f5f9; text-decoration: none !important; transition: all 0.2s; }
                                    .social-pill:hover { border-color: #3b82f6; background: #f0f7ff; }
                                    .social-pill span { font-size: 0.8rem; font-weight: 700; color: #1e293b; }
                                    .ch-side { display: flex; flex-direction: column; gap: 20px; }
                                    .ch-meta-card { background: #fff; border-radius: 20px; border: 1px solid #f1f5f9; padding: 20px; }
                                    .ch-meta-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px dashed #f1f5f9; font-size: 0.8rem; }
                                    .ch-meta-val { color: #0f172a; font-weight: 800; }
                                </style>

                                <div class="ch-hero">
                                    @if($user->channel->banner)
                                        <img src="{{ getImage(getFilePath('channelBanner').'/'.$user->channel->banner) }}" class="ch-banner">
                                    @else
                                        <div class="ch-banner" style="background: linear-gradient(135deg, #6366f1, #a855f7);"></div>
                                    @endif
                                    <div class="ch-avatar-wrap">
                                        @if($user->channel->avatar)
                                            <img src="{{ getImage(getFilePath('channelAvatar').'/'.$user->channel->avatar) }}" class="ch-avatar">
                                        @else
                                            <div class="ch-avatar" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #6366f1, #a855f7); color: #fff; font-weight: 900; font-size: 2rem; text-transform: uppercase;">{{ strtoupper(substr($user->channel->name ?? 'C', 0, 1)) }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="ch-content">
                                    <div class="ch-main-info">
                                        <h2 class="ch-name">
                                            {{ $user->channel->name }}
                                            <span class="badge {{ $user->channel->is_active ? 'btn-success' : 'btn-danger' }}" style="color:#fff;">
                                                {{ $user->channel->is_active ? 'Verified' : 'Inactive' }}
                                            </span>
                                        </h2>
                                        <p class="ch-desc">{{ $user->channel->description ?: 'No channel description provided by the creator.' }}</p>
                                        
                                        <div class="ch-stats">
                                            <div class="ch-stat-box"><span class="ch-stat-val">{{ number_format($user->channel->videos()->count()) }}</span><span class="ch-stat-lbl">Videos</span></div>
                                            <div class="ch-stat-box"><span class="ch-stat-val">{{ number_format($user->channel->subscribers_count) }}</span><span class="ch-stat-lbl">Subs</span></div>
                                            <div class="ch-stat-box"><span class="ch-stat-val">{{ number_format($user->channel->memberships()->count()) }}</span><span class="ch-stat-lbl">Tiers</span></div>
                                        </div>

                                        <h4 class="card-title mt-4"><span class="material-symbols-rounded">link</span> Connectivity</h4>
                                        <div class="social-grid">
                                            @php 
                                                $socials = $user->channel->social_links ?? []; 
                                                $icons = [
                                                    'facebook' => 'facebook',
                                                    'twitter'  => 'x',
                                                    'instagram'=> 'photo_camera',
                                                    'youtube'  => 'play_circle',
                                                    'website'  => 'language',
                                                    'tiktok'   => 'music_note'
                                                ];
                                            @endphp
                                            @forelse($socials as $key => $link)
                                                @if($link)
                                                    <a href="{{ $link }}" target="_blank" class="social-pill">
                                                        <span class="material-symbols-rounded">{{ $icons[strtolower($key)] ?? 'link' }}</span>
                                                        <span>{{ ucfirst($key) }}</span>
                                                    </a>
                                                @endif
                                            @empty
                                                <div class="empty-msg" style="grid-column: span 2; padding: 20px;">No social connections.</div>
                                            @endforelse
                                        </div>
                                    </div>

                                    <div class="ch-side">
                                        <div class="ch-meta-card">
                                            <h4 class="card-title">Audit Metadata</h4>
                                            <div class="ch-meta-row"><span class="ch-meta-lbl">Created</span> <span class="ch-meta-val">{{ $user->channel->created_at->format('M d, Y') }}</span></div>
                                            <div class="ch-meta-row"><span class="ch-meta-lbl">Last Update</span> <span class="ch-meta-val">{{ $user->channel->updated_at->diffForHumans() }}</span></div>
                                            <div class="ch-meta-row"><span class="ch-meta-lbl">Monetized</span> <span class="ch-meta-val">{{ $user->monetization_status ? 'YES' : 'NO' }}</span></div>
                                        </div>

                                        <a href="{{ route('channels.show', $user->channel) }}" target="_blank" class="snav-btn" style="background: #0f172a; color: #fff; border: none;">
                                            <span class="material-symbols-rounded">visibility</span> Live Preview
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="empty-msg">No channel established for this user node.</div>
                        @endif
                    </div>

                    <div x-show="currentTab === 'subscribers'" class="tab-pane animate-in" x-cloak>
                        <div class="table-wrap">
                            <table class="audit-table">
                                <thead><tr><th>Follower</th><th>Joined</th><th>Pulse</th></tr></thead>
                                <tbody>
                                    @forelse($user->subscribers()->with('user')->latest()->take(50)->get() as $s)
                                        <tr>
                                            <td class="bold">
                                                @if($s->user)
                                                    @ {{ $s->user->username }}
                                                @else
                                                    Anonymous #{{ $s->user_id }}
                                                @endif
                                            </td>
                                            <td>{{ $s->created_at->format('d M, Y') }}</td>
                                            <td><span class="badge">{{ $s->is_notification ? 'ALERT' : 'MUTE' }}</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center py-4">No subscribers.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div x-show="currentTab === 'transactions'" class="tab-pane animate-in" x-cloak>
                        <div class="metric-tiles mb-5">
                            <div class="tile emerald"><div class="v">{{ showAmount($totalDeposit) }}</div><div class="l">Deposits</div></div>
                            <div class="tile rose"><div class="v">{{ showAmount($totalWithdrawals) }}</div><div class="l">Withdrawals</div></div>
                            <div class="tile blue"><div class="v">{{ $totalTransaction }}</div><div class="l">TRX Logs</div></div>
                        </div>
                        <div class="table-wrap">
                            <table class="audit-table">
                                <thead><tr><th>TRX</th><th>Amount</th><th>Post</th><th>Date</th></tr></thead>
                                <tbody>
                                    @forelse($transactions as $t)
                                        <tr><td class="bold">#{{ $t->trx }}</td><td class="{{ $t->trx_type == '+' ? 'text-green' : 'text-red' }} bold">{{ $t->trx_type }}{{ showAmount($t->amount) }}</td><td>{{ showAmount($t->post_balance) }}</td><td>{{ $t->created_at->format('d/m/y H:i') }}</td></tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center py-4">No TRX.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div x-show="currentTab === 'subs'" class="tab-pane animate-in" x-cloak>
                        <div class="subs-grid">
                            <h4 class="card-title">Creator & Access Plans</h4>
                            @forelse($userPlans as $p)
                                <div class="sub-item">
                                    <div class="si-icon"><span class="material-symbols-rounded">workspace_premium</span></div>
                                    <div class="si-body">
                                        <div class="si-name">{{ $p->plan->name }} <span class="si-type">BASE</span></div>
                                        <div class="si-expiry">{{ $p->expired_date ? $p->expired_date->format('M d, Y') : 'Life-time' }}</div>
                                    </div>
                                    <div class="si-price">{{ showAmount($p->price) }}</div>
                                </div>
                            @empty
                                <p class="text-center py-4 text-gray-400">No base plans active.</p>
                            @endforelse

                            <h4 class="card-title mt-5">OTT & Premium Network</h4>
                            @forelse($ottPlans as $op)
                                <div class="sub-item">
                                    <div class="si-icon"><span class="material-symbols-rounded">movie</span></div>
                                    <div class="si-body">
                                        <div class="si-name">{{ $op->ottPlan->name }} <span class="si-type ott">OTT</span></div>
                                        <div class="si-expiry">Ends {{ $op->end_date->format('M d, Y') }}</div>
                                    </div>
                                    <div class="si-price">{{ showAmount($op->amount) }}</div>
                                </div>
                            @empty
                                <p class="text-center py-4 text-gray-400">No OTT subscriptions active.</p>
                            @endforelse
                        </div>
                    </div>

                    <div x-show="currentTab === 'market'" class="tab-pane animate-in" x-cloak>
                        <div class="table-wrap">
                            <table class="audit-table">
                                <thead><tr><th>Asset</th><th>Amt</th><th>Consumer</th><th>Date</th></tr></thead>
                                <tbody>
                                    @forelse($user->saleVideos()->with(['video', 'user'])->latest()->take(20)->get() as $s)
                                        <tr><td>{{ optional($s->video)->title ?: 'Video Deleted' }}</td><td class="text-green bold">{{ showAmount($s->amount) }}</td><td>@ {{ $s->user->username }}</td><td>{{ $s->created_at->format('d M, Y') }}</td></tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center py-4">No sales.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div x-show="currentTab === 'kyc'" class="tab-pane animate-in" x-cloak>
                        @if($kycSubmission)
                            <div class="kyc-vault-grid">
                                <div class="kv-info">
                                    <h4 class="card-title">Identity Credentials</h4>
                                    <div class="kv-list">
                                        <div class="kv-row"><span>Legal Name</span> <strong>{{ $kycSubmission->full_name }}</strong></div>
                                        <div class="kv-row"><span>Date of Birth</span> <strong>{{ showDateTime($kycSubmission->date_of_birth, 'd M, Y') }}</strong></div>
                                        <div class="kv-row"><span>ID Type</span> <strong>{{ strtoupper($kycSubmission->id_type) }}</strong></div>
                                        <div class="kv-row"><span>ID Number</span> <strong>{{ $kycSubmission->id_number }}</strong></div>
                                    </div>
                                    <h4 class="card-title mt-4">Financial Settlement</h4>
                                    <div class="kv-list">
                                        <div class="kv-row"><span>Bank Name</span> <strong>{{ $kycSubmission->bank_name }}</strong></div>
                                        <div class="kv-row"><span>Account No.</span> <strong>{{ $kycSubmission->account_number }}</strong></div>
                                    </div>
                                </div>
                                <div class="kv-evidence">
                                    <h4 class="card-title">Evidence Vault</h4>
                                    @if($kycSubmission->selfie_image)
                                        <div class="ev-item"><img src="{{ getImage($kycSubmission->selfie_image) }}"><span class="ev-label">Biometric Selfie</span></div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="empty-msg">No KYC portfolio found.</div>
                        @endif
                    </div>

                    <div x-show="currentTab === 'support'" class="tab-pane animate-in" x-cloak>
                        <div class="timeline">
                            @forelse($user->tickets()->latest()->get() as $tk)
                                <div class="timeline-item">
                                    <div class="tl-icon faint"><span class="material-symbols-rounded">support_agent</span></div>
                                    <div class="tl-body">
                                        <div class="tl-subject">#{{ $tk->ticket }} - {{ $tk->subject }}</div>
                                        <div class="tl-date">@php echo $tk->statusBadge @endphp</div>
                                    </div>
                                    <a href="{{ route('admin.ticket.view', $tk->id) }}" class="tl-link">Audit</a>
                                </div>
                            @empty
                                <div class="empty-msg">No tickets.</div>
                            @endforelse
                        </div>
                    </div>

                    <div x-show="currentTab === 'notifications'" class="tab-pane animate-in" x-cloak>
                        <div class="timeline">
                            @forelse($user->userNotifications()->latest()->take(20)->get() as $n)
                                <div class="timeline-item">
                                    <div class="tl-icon orange"><span class="material-symbols-rounded">notifications</span></div>
                                    <div class="tl-body"><div class="tl-subject">{{ $n->subject }}</div><div class="tl-date">{{ $n->message }}</div></div>
                                    <div class="tl-stamp">{{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}</div>
                                </div>
                            @empty
                                <div class="empty-msg">No notifications.</div>
                            @endforelse
                        </div>
                    </div>

                    <div x-show="currentTab === 'logs'" class="tab-pane animate-in" x-cloak>
                        <div class="timeline">
                            @forelse($user->loginLogs()->latest()->take(20)->get() as $l)
                                <div class="timeline-item">
                                    <div class="tl-icon faint"><span class="material-symbols-rounded">lan</span></div>
                                    <div class="tl-body"><div class="tl-subject">{{ $l->user_ip }}</div><div class="tl-date">{{ $l->browser }} | {{ $l->os }}</div></div>
                                    <div class="tl-stamp">{{ \Carbon\Carbon::parse($l->created_at)->diffForHumans() }}</div>
                                </div>
                            @empty
                                <div class="empty-msg">No logs.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@include('admin.users.partials.modals')
@endsection

