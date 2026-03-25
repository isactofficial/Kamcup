@extends('layouts.master_nav')

@section('title', 'Teman & Chat')

@section('content')
<div class="friends-app-container container-fluid p-0 overflow-hidden">
    <div class="d-flex h-100">
        <!-- DM LIST BAR (Sidebar) -->
        <div class="dm-list-bar d-flex flex-column h-100 border-end">
            <!-- Back Button for App Mode -->
            <div class="px-3 pt-3">
                <a href="{{ route('profile.index') }}" class="btn btn-sm w-100 text-start text-accent hover-bg-light fw-bold mb-2">
                    <i class="fas fa-chevron-left me-2"></i> Kembali ke Profile
                </a>
            </div>
            <!-- Search / Top Section -->
            <div class="p-3 shadow-sm border-bottom">
                <div class="search-box position-relative">
                    <input type="text" id="user-search-input" class="form-control form-control-sm border-0 bg-light rounded-pill ps-4" placeholder="Cari atau mulai percakapan">
                    <i class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted small"></i>
                </div>
            </div>

            <!-- Friends & Requests Navigation -->
            <div class="flex-grow-1 overflow-auto custom-scrollbar px-2 py-3">
                <div class="nav-item-friends mb-1" id="sidebar-all-btn" onclick="showAllFriends()">
                    <i class="fas fa-user-friends me-3 text-accent"></i> Semua Teman
                </div>

                @if($pendingRequests->count() > 0)
                <div class="nav-item-friends mb-1 text-pink" onclick="showRequests()">
                    <i class="fas fa-envelope-open-text me-3"></i> Permintaan <span class="badge bg-danger ms-auto rounded-pill">{{ $pendingRequests->count() }}</span>
                </div>
                @endif

                <div class="mt-4 px-3 mb-2 text-uppercase x-small fw-bold text-muted d-flex justify-content-between">
                    PESAN LANGSUNG 
                </div>

                <div id="friends-list" class="d-flex flex-column gap-1 px-1">
                    @foreach($friends as $friend)
                    <div class="friend-item-sidebar d-flex align-items-center p-2 rounded-3 cursor-pointer" 
                        onclick="openChat({{ $friend->id }}, '{{ $friend->name }}', '{{ $friend->profile->profile_photo ? asset('storage/' . $friend->profile->profile_photo) : '' }}', '{{ str_replace(["\r", "\n"], ' ', $friend->profile->description ?? 'Pemain KAMCUP yang siap bertanding!') }}', '{{ $friend->member_since }}', {{ $friend->mutual_communities_count }}, {{ json_encode($friend->mutual_communities) }})">
                        <div class="avatar-sm me-3 position-relative">
                            @if($friend->profile && $friend->profile->profile_photo)
                                <img src="{{ asset('storage/' . $friend->profile->profile_photo) }}" class="rounded-circle object-fit-contain bg-light" style="width: 35px; height: 35px;" alt="">
                            @else
                                <div class="placeholder-avatar rounded-circle bg-accent-light d-flex align-items-center justify-content-center text-accent fw-bold text-uppercase" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                    {{ substr($friend->name, 0, 1) }}
                                </div>
                            @endif
                            <span class="status-dot online"></span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-bold text-dark text-truncate small">{{ $friend->name }}</div>
                            <div class="text-muted x-small text-truncate">Klik untuk chat...</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- User Status Bottom -->
            <div class="user-status-strip d-flex align-items-center p-3 border-top mt-auto bg-white">
                <div class="avatar-sm-circle me-3">
                    @if(Auth::user()->profile && Auth::user()->profile->profile_photo)
                        <img src="{{ asset('storage/' . Auth::user()->profile->profile_photo) }}" class="rounded-circle object-fit-contain bg-light" style="width: 35px; height: 35px;" alt="">
                    @else
                        <div class="rounded-circle bg-pink-light d-flex align-items-center justify-content-center text-pink fw-bold" style="width: 35px; height: 45px;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-bold text-dark small text-truncate">{{ Auth::user()->name }}</div>
                    <div class="text-muted x-small text-truncate">Online</div>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT AREA -->
        <div class="main-chat-area flex-grow-1 d-flex flex-column bg-white">

            <!-- Views -->
            <div class="flex-grow-1 overflow-hidden" style="position:relative;">
                <!-- FRIENDS DASHBOARD (Landing View) -->
                <div id="welcome-view" class="flex-grow-1 d-flex flex-column p-4 animate-fade-in">
                    <div class="d-flex align-items-center gap-3 mb-4 border-bottom pb-3">
                        <h5 class="fw-bold text-dark mb-0" id="main-view-title">Teman</h5>
                        <div class="btn-group-friends ms-2">
                            <button class="btn-friends active" id="tab-semua" onclick="showAllFriends()">Semua</button>
                            <button class="btn-friends" id="tab-tertunda" onclick="showRequests()">Tertunda <span class="badge bg-danger rounded-pill ms-1">{{ $pendingRequests->count() }}</span></button>
                            <button class="btn-friends btn-add-friend" id="tab-tambah" onclick="showAddFriend()">Tambah Teman</button>
                        </div>
                    </div>

                    <!-- All Friends View -->
                    <div id="all-friends-view">
                        @forelse($friends as $friend)
                        <div class="d-flex align-items-center p-3 border rounded-4 hover-light mb-2 transition-all cursor-pointer" 
                            onclick="openChat({{ $friend->id }}, '{{ $friend->name }}', '{{ $friend->profile->profile_photo ? asset('storage/' . $friend->profile->profile_photo) : '' }}', '{{ str_replace(["\r", "\n"], ' ', $friend->profile->description ?? 'Pemain KAMCUP yang siap bertanding!') }}', '{{ $friend->member_since }}', {{ $friend->mutual_communities_count }}, {{ json_encode($friend->mutual_communities) }})">
                            <div class="me-3 position-relative">
                                @if($friend->profile && $friend->profile->profile_photo)
                                    <img src="{{ asset('storage/' . $friend->profile->profile_photo) }}" class="rounded-circle object-fit-contain bg-light" style="width: 45px; height: 45px;" alt="">
                                @else
                                    <div class="rounded-circle bg-accent-light d-flex align-items-center justify-content-center text-accent fw-bold" style="width:45px;height:45px;">{{ strtoupper(substr($friend->name, 0, 1)) }}</div>
                                @endif
                                <span class="status-dot online"></span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark">{{ $friend->name }}</div>
                                <div class="text-muted small">Online</div>
                            </div>
                            <button class="btn btn-sm btn-outline-accent rounded-pill px-3" style="font-size:0.75rem;border-color:var(--teman-accent);color:var(--teman-accent);">Chat</button>
                        </div>
                        @empty
                        <div id="empty-friends-state" class="flex-grow-1 d-flex flex-column align-items-center justify-content-center text-center opacity-75 py-5">
                            <div class="mb-4 text-accent" style="font-size:4rem;"><i class="fas fa-ghost"></i></div>
                            <h5 class="fw-bold">Belum ada teman</h5>
                            <p class="text-muted small">Klik "Tambah Teman" untuk mulai!</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Requests View -->
                    <div id="requests-view" class="d-none">
                        <h6 class="text-muted text-uppercase small fw-bold mb-3">Permintaan Masuk — {{ $pendingRequests->count() }}</h6>
                        @foreach($pendingRequests as $request)
                        <div class="d-flex align-items-center p-3 border rounded-4 hover-light mb-2 transition-all">
                             <div class="avatar-md me-3">
                                <div class="rounded-circle bg-accent-light d-flex align-items-center justify-content-center text-accent fw-bold fs-5" style="width: 45px; height: 45px;">
                                    {{ strtoupper(substr($request->sender->name, 0, 1)) }}
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark">{{ $request->sender->name }}</div>
                                <div class="text-muted small">Ingin berteman denganmu</div>
                            </div>
                            <div class="d-flex gap-2">
                                <form action="{{ route('user2026.teman.accept', $request->id) }}" method="POST">
                                    @csrf
                                    <button class="btn-action bg-success text-white"><i class="fas fa-check"></i></button>
                                </form>
                                <form action="{{ route('user2026.teman.remove', $request->sender->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-action bg-danger text-white"><i class="fas fa-times"></i></button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Add Friend View -->
                    <div id="search-view" class="d-none">
                        <div class="d-flex align-items-center mb-4">
                            <h6 class="fw-bold mb-0">Tambah Teman</h6>
                            <button class="btn btn-sm btn-light rounded-pill ms-auto px-3" onclick="closeAddFriend()"><i class="fas fa-times me-1"></i> Tutup</button>
                        </div>
                        <div class="bg-light rounded-4 p-3 mb-4 border">
                            <p class="text-muted small mb-2">Cari teman berdasarkan nama atau username.</p>
                            <div class="d-flex gap-2">
                                <input type="text" id="add-friend-input" class="form-control border-0 bg-white rounded-3 shadow-sm" placeholder="Cari nama pengguna...">
                            </div>
                        </div>
                        <div id="search-results-container" class="row g-3"></div>
                    </div>

                </div>

                <!-- CHAT VIEW -->
                <div id="chat-view" class="chat-view-hidden" style="position:absolute; inset:0; flex-direction:column; height:100%;">
                    <!-- Chat Header -->
                    <div class="chat-header">
                        <div class="chat-header-content">
                            <!-- Desktop header content (hidden on mobile) -->
                            <div class="d-none d-md-flex align-items-center justify-content-between p-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <div class="rounded-circle bg-accent-light text-accent d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">?</div>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold" id="chat-header-name">Select a friend</h6>
                                        <small class="text-muted">Click on a friend to start chatting</small>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-ellipsis-v"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex-grow-1 overflow-auto p-4 d-flex flex-column custom-scrollbar" id="chat-box">
                        <div id="chat-messages-inner" class="mt-auto d-flex flex-column">
                            <!-- Messages -->
                        </div>
                    </div>
                    
                    <div class="chat-input-wrapper p-3 ">
                        <div class="bg-light rounded-4 p-2 d-flex align-items-center gap-2 border">
                            <button class="btn btn-sm btn-light rounded-circle text-accent" id="attach-image-btn">
                                <i class="fas fa-plus-circle fs-5"></i>
                            </button>
                            <input type="file" id="image-upload" accept="image/*" style="display: none;">
                            <form id="chat-form" class="flex-grow-1">
                                <input type="text" id="chat-input" class="form-control bg-transparent border-0 shadow-none" placeholder="Tulis pesan..." autocomplete="off">
                            </form>
                            <div class="d-flex gap-2 text-muted px-2">
                                <button class="btn btn-sm btn-light rounded-circle text-accent" id="emoji-btn">
                                    <i class="far fa-smile fs-5"></i>
                                </button>
                                <button type="submit" form="chat-form" class="btn btn-accent btn-sm rounded-pill px-3 ms-2">Kirim</button>
                            </div>
                        </div>
                        
                        <!-- Image Preview Area -->
                        <div id="image-preview-container" class="mt-2 d-none">
                            <div class="bg-white rounded-3 p-2 border position-relative">
                                <img id="image-preview" src="" alt="Preview" class="rounded-2" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                <button type="button" id="remove-image-btn" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle" style="width: 24px; height: 24px; padding: 0;">
                                    <i class="fas fa-times" style="font-size: 10px;"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Emoji Picker Container -->
                        <div id="emoji-picker-container" class="position-absolute d-none" style="bottom: 80px; left: 20px; z-index: 1000;">
                            <div class="bg-white rounded-3 border shadow-lg p-3">
                                <div class="emoji-grid d-flex flex-wrap gap-2" style="max-width: 300px; max-height: 200px; overflow-y: auto;">
                                    <!-- Emojis will be populated by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MEMBER INFO (Sidebar Right) -->
        <div class="member-info-bar d-none d-xl-flex flex-column h-100 border-start overflow-auto custom-scrollbar shadow-sm" id="member-sidebar" style="background-color: #ffffff; width: 340px; border-left: 1px solid #eee; color: #333;">
             <!-- Banner Overlay -->
             <div class="profile-banner-top w-100" style="height: 100px; background-color: var(--teman-accent); position: relative; overflow: hidden;">
                <div style="position: absolute; inset: 0; background: linear-gradient(rgba(0,0,0,0.1), transparent);"></div>
             </div>
             
             <div class="px-3" style="margin-top: -45px;">
                <div class="avatar-container-outer mb-3 position-relative" style="width: 90px;">
                    <div id="member-avatar-container" class="rounded-circle p-1" style="width: 92px; height: 92px; background-color: #ffffff;">
                        <div class="rounded-circle bg-accent d-flex align-items-center justify-content-center text-white fw-bold fs-2 h-100 w-100 shadow-sm border border-3 border-white">?</div>
                    </div>
                    <span class="status-dot online me-1 border-4" style="width: 24px; height: 24px; bottom: 4px; right: 4px; border-color: #ffffff !important;"></span>
                </div>

                <div class="p-3 pt-0 rounded-4 mb-3 text-center text-xl-start">
                    <h5 class="fw-bold text-dark mb-0" id="member-name-sidebar">Pilih Teman</h5>
                    <p class="text-muted small mb-0">Member KAMCUP</p>
                </div>

                <div class="p-3 rounded-4 mb-3 border bg-light shadow-sm-hover transition-all" style="background-color: #fcfcfd;">
                    <h6 class="text-uppercase x-small fw-bold text-muted mb-2" style="letter-spacing: 0.8px;">Member Since</h6>
                    <p id="member-since-sidebar" class="small mb-0 fw-medium text-dark">Join date...</p>
                </div>

                <div class="p-3 rounded-4 mb-3 border bg-light shadow-sm-hover transition-all cursor-pointer" style="background-color: #fcfcfd;" onclick="toggleMutualList()">
                    <div class="d-flex justify-content-between align-items-center mb-0">
                        <h6 class="text-uppercase x-small fw-bold text-muted mb-0" style="letter-spacing: 0.8px;">Komunitas Bersama — <span id="mutual-count" class="text-accent">0</span></h6>
                        <i class="fas fa-chevron-down text-muted small transition-all" id="mutual-chevron"></i>
                    </div>
                    <div id="mutual-list-container" class="mt-3 d-none animate-fade-in border-top pt-3">
                        <!-- JS Populate -->
                    </div>
                </div>

                <div class="p-3 rounded-4 mb-3 border bg-light shadow-sm-hover transition-all" style="background-color: #fcfcfd;">
                    <h6 class="text-uppercase x-small fw-bold text-muted mb-2" style="letter-spacing: 0.8px;">Biodata</h6>
                    <p class="small mb-0 text-dark opacity-75" id="member-bio-sidebar" style="line-height: 1.5;">Klik teman untuk liat profile.</p>
                </div>

                <div class="mt-auto pt-3 border-top py-3 d-flex flex-column gap-2" id="chat-actions-dropdown">
                    <!-- JS Actions Inject -->
                </div>
             </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    :root {
        --teman-accent: #00617a;
        --teman-accent-light: #00617a15;
        --teman-pink: #cb2786;
        --teman-pink-light: #cb278615;
        --teman-bg: #f8f9fa;
        --teman-border: rgba(0,0,0,0.08);
    }

    html, body {
        height: 100% !important;
        overflow: hidden !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .friends-app-container {
        height: 100vh !important;
        background-color: white;
        font-family: 'Poppins', sans-serif;
    }

    /* Hide Global Elements */
    .navbar, footer, #chatbot-bubble-container { display: none !important; }
    .content { padding-top: 0 !important; height: 100vh; }
    .main-wrapper { height: 100vh !important; }

    .dm-list-bar { width: 300px; background-color: var(--teman-bg); }
    .text-accent { color: var(--teman-accent); }
    .bg-accent { background-color: var(--teman-accent); }
    .bg-accent-light { background-color: var(--teman-accent-light); }
    .text-pink { color: var(--teman-pink); }
    .bg-pink-light { background-color: var(--teman-pink-light); }
    .btn-accent { background-color: var(--teman-accent); color: white; }
    .btn-accent:hover { background-color: #004d61; color: white; }
    
    .x-small { font-size: 0.75rem; }
    
    .nav-item-friends {
        padding: 10px 16px;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        color: #555;
        font-weight: 500;
        transition: 0.2s;
    }
    .nav-item-friends:hover { background: #e9ecef; }
    .nav-item-friends.active { background: white; box-shadow: 0 4px 6px -2px rgba(0,0,0,0.05); color: var(--teman-accent); }

    .friend-item-sidebar:hover { background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    
    .status-dot {
        position: absolute; bottom: 0; right: 0;
        width: 12px; height: 12px; border-radius: 50%;
        border: 2px solid var(--teman-bg);
    }
    .status-dot.online { background-color: #23a55a; }

    .btn-friends {
        background: none; border: 0; color: #666;
        font-weight: 600; font-size: 0.9rem; padding: 4px 12px; border-radius: 6px;
    }
    .btn-friends.active { background: var(--teman-accent-light); color: var(--teman-accent); }
    .btn-friends.btn-add-friend { background: #23a55a; color: white; margin-left: auto; }

    .btn-action {
        width: 32px; height: 32px; border-radius: 8px; border: 0;
        display: flex; align-items: center; justify-content: center; transition: 0.2s;
    }
    .btn-action:hover { transform: scale(1.1); }

    /* Messages */
    #chat-box {
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        overflow-y: auto;
        min-height: 0;
    }

    .chat-msg-container {
        display: flex; gap: 12px; padding: 8px 0;
    }
    .chat-msg-author { font-weight: 700; color: #333; margin-right: 8px; }
    .chat-msg-time { font-size: 0.7rem; color: #999; }
    .chat-msg-text { color: #444; font-size: 0.95rem; }

    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #dee2e6; border-radius: 10px; }
    
    .hover-accent:hover { color: var(--teman-accent); }
    .cursor-pointer { cursor: pointer; }
    .transition-all { transition: all 0.2s; }

    /* Chat view toggle */
    #chat-view.chat-view-hidden { display: none !important; pointer-events: none; }
    #chat-view.chat-view-visible { display: flex !important; }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .friends-app-container {
            grid-template-columns: 350px 1fr;
        }
    }
    
    @media (max-width: 992px) {
        .friends-app-container {
            grid-template-columns: 300px 1fr;
        }
        
        .dm-list-bar {
            width: 300px !important;
        }
    }
    
    @media (max-width: 768px) {
        .friends-app-container {
            grid-template-columns: 1fr !important;
            height: 100vh !important;
            height: 100dvh !important;
        }

        .dm-list-bar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100vh !important;
            height: 100dvh !important;
            z-index: 1050 !important;
            transition: transform 0.3s ease-in-out;
            transform: translateX(-100%);
        }

        .dm-list-bar.mobile-visible {
            transform: translateX(0);
        }

        .dm-list-bar.mobile-hidden {
            transform: translateX(-100%);
        }

        #chat-view {
            margin-left: 0 !important;
        }
        
        /* Mobile chat adjustments */
        .chat-header {
            padding: 0 !important;
            background: white !important;
            border-bottom: 1px solid #e9ecef !important;
        }
        
        /* Hide desktop header on mobile */
        .chat-header .chat-header-content {
            display: none !important;
        }
        
        .mobile-chat-header {
            padding: 12px 16px !important;
            background: white !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 100 !important;
            display: flex !important;
        }
        
        .mobile-profile-info .fw-bold {
            font-size: 1rem !important;
            margin: 0 !important;
        }
        
        .mobile-profile-info .small {
            font-size: 0.8rem !important;
            margin: 0 !important;
        }
        
        /* Mobile search box */
        .search-box input {
            font-size: 0.9rem !important;
            padding: 10px 16px 10px 40px !important;
            height: auto !important;
        }
        
        /* Mobile friend items */
        .friend-item-sidebar {
            padding: 12px !important;
        }
        
        .friend-item-sidebar .avatar-sm {
            width: 40px !important;
            height: 40px !important;
            font-size: 0.9rem !important;
        }
        
        /* Mobile chat messages */
        .chat-msg-text {
            font-size: 0.9rem !important;
            line-height: 1.4 !important;
        }
        
        /* Mobile chat input */
        .chat-input-container {
            padding: 12px 16px !important;
        }
        
        .chat-input-container input {
            font-size: 0.9rem !important;
            padding: 10px 16px !important;
        }
        
        /* Mobile navigation items */
        .nav-item-friends {
            padding: 12px 16px !important;
            font-size: 0.9rem !important;
        }
        
        /* Mobile status indicators */
        .status-dot {
            width: 12px !important;
            height: 12px !important;
        }
        
        /* Mobile badges */
        .badge {
            font-size: 0.75rem !important;
            padding: 4px 8px !important;
        }
        
        /* Mobile mutual communities */
        .mutual-communities {
            font-size: 0.8rem !important;
        }
        
        .mutual-communities .badge {
            padding: 2px 6px !important;
        }
        
        /* Mobile empty states */
        .empty-state {
            padding: 40px 20px !important;
        }
        
        .empty-state h5 {
            font-size: 1.1rem !important;
        }
        
        .empty-state p {
            font-size: 0.9rem !important;
        }
        
        /* Mobile friend request items */
        .request-item {
            padding: 12px !important;
        }
        
        .request-item .avatar-sm {
            width: 40px !important;
            height: 40px !important;
            font-size: 0.9rem !important;
        }
        
        .request-item .btn {
            padding: 6px 12px !important;
            font-size: 0.8rem !important;
        }
        
        /* Mobile profile info in chat */
        .profile-info {
            padding: 16px !important;
        }
        
        .profile-info .avatar-sm {
            width: 50px !important;
            height: 50px !important;
            font-size: 1rem !important;
        }
        
        .profile-info h6 {
            font-size: 0.9rem !important;
        }
        
        .profile-info small {
            font-size: 0.8rem !important;
        }
        
        /* Mobile scrollbars */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px !important;
        }
        
        /* Mobile safe area handling */
        @supports (padding: max(0px)) {
            .dm-list-bar {
                padding-top: max(8px, env(safe-area-inset-top)) !important;
            }
            
            .chat-header {
                padding-top: max(12px, env(safe-area-inset-top)) !important;
            }
        }
    }
    
    /* Small mobile devices */
    @media (max-width: 480px) {
        .dm-list-bar .btn {
            font-size: 0.85rem !important;
            padding: 6px 10px !important;
        }
        
        .search-box input {
            font-size: 0.85rem !important;
            padding: 8px 14px 8px 36px !important;
        }
        
        .friend-item-sidebar {
            padding: 10px !important;
        }
        
        .friend-item-sidebar .avatar-sm {
            width: 36px !important;
            height: 36px !important;
            font-size: 0.8rem !important;
        }
        
        .chat-header {
            padding: 10px 12px !important;
            font-size: 0.9rem !important;
        }
        
        .chat-msg-text {
            font-size: 0.85rem !important;
        }
        
        .chat-input-container {
            padding: 10px 12px !important;
        }
        
        .chat-input-container input {
            font-size: 0.85rem !important;
            padding: 8px 12px !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userId = {{ Auth::id() }};
        const sidebarSearchInput = document.getElementById('user-search-input');
        const searchResults = document.getElementById('search-results-container');
        const welcomeView = document.getElementById('welcome-view');
        const chatView = document.getElementById('chat-view');
        const dmListBar = document.querySelector('.dm-list-bar');
        
        // Responsive functionality
        const isMobile = window.innerWidth <= 768;
        const isTablet = window.innerWidth <= 992;
        
        // Responsive sidebar functionality
        function showSidebar() {
            const dmListBar = document.querySelector('.dm-list-bar');
            const chatView = document.getElementById('chat-view');
            
            if (isMobile && dmListBar) {
                dmListBar.classList.remove('mobile-hidden');
                dmListBar.classList.add('mobile-visible');
                chatView.classList.add('chat-view-hidden');
                chatView.classList.remove('chat-view-visible');
            }
        }
        
        function hideSidebar() {
            const dmListBar = document.querySelector('.dm-list-bar');
            const chatView = document.getElementById('chat-view');
            
            if (isMobile && dmListBar) {
                dmListBar.classList.remove('mobile-visible');
                dmListBar.classList.add('mobile-hidden');
                chatView.classList.remove('chat-view-hidden');
                chatView.classList.add('chat-view-visible');
            }
        }
        
        // Make functions globally accessible
        window.showSidebar = showSidebar;
        window.hideSidebar = hideSidebar;
        
        // Add responsive back button to chat header (mobile only)
        if (isMobile) {
            // Initially show sidebar on mobile
            showSidebar();
        }
        
        // Update mobile chat header when chat is opened
        function updateMobileChatHeader(name, avatar = '', bio = '', joinDate = 'Jan 01, 2026', mutualCount = 0, mutuals = []) {
            const chatHeader = document.querySelector('.chat-header');
            if (!chatHeader) return;
            
            // Clear any existing headers
            const existingMobileHeader = chatHeader.querySelector('.mobile-chat-header');
            if (existingMobileHeader) {
                existingMobileHeader.remove();
            }
            
            if (isMobile) {
                // Mobile: Create and show mobile header
                const desktopContent = chatHeader.querySelector('.chat-header-content');
                if (desktopContent) {
                    desktopContent.style.display = 'none';
                }
                
                const mobileHeader = document.createElement('div');
                mobileHeader.className = 'mobile-chat-header d-flex align-items-center w-100';
                
                const avatarHtml = avatar 
                    ? `<img src="${avatar}" alt="${name}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">`
                    : `<div class="rounded-circle bg-accent-light text-accent d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">${name[0]?.toUpperCase() || 'U'}</div>`;
                
                mobileHeader.innerHTML = `
                    <button class="mobile-back-btn btn btn-sm btn-outline-secondary me-3" onclick="window.showSidebar()">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <div class="mobile-profile-info d-flex align-items-center flex-grow-1">
                        ${avatarHtml}
                        <div class="ms-3">
                            <div class="fw-bold text-dark">${name}</div>
                            <div class="small text-muted">Online</div>
                        </div>
                    </div>
                `;
                
                chatHeader.appendChild(mobileHeader);
            } else {
                // Desktop: Show desktop header and update name
                const desktopContent = chatHeader.querySelector('.chat-header-content');
                if (desktopContent) {
                    desktopContent.style.display = 'block';
                }
                
                const headerName = document.getElementById('chat-header-name');
                if (headerName) {
                    headerName.textContent = name;
                }
            }
        }
        
        window.toggleMutualList = function() {
            const container = document.getElementById('mutual-list-container');
            const chevron = document.getElementById('mutual-chevron');
            if (container.classList.contains('d-none')) {
                container.classList.remove('d-none');
                chevron.style.transform = 'rotate(180deg)';
            } else {
                container.classList.add('d-none');
                chevron.style.transform = 'rotate(0deg)';
            }
        };

        const chatBox = document.getElementById('chat-box');
        const chatInput = document.getElementById('chat-input');
        
        let currentFriendId = null;
        let currentFriendName = '';
        let addFriendTimeout = null;

        // --- SIDEBAR SEARCH: filter friends list only (client-side) ---
        sidebarSearchInput.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            document.querySelectorAll('.friend-item-sidebar').forEach(el => {
                const name = el.querySelector('.fw-bold').innerText.toLowerCase();
                el.style.display = name.includes(query) ? '' : 'none';
            });
        });

        // --- NAVIGATION ---
        function setActiveTab(tabId) {
            ['tab-semua', 'tab-tertunda', 'tab-tambah'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.classList.remove('active');
            });
            const active = document.getElementById(tabId);
            if (active) active.classList.add('active');
        }

        function hideChatView() {
            chatView.classList.remove('chat-view-visible');
            chatView.classList.add('chat-view-hidden');
        }
        function showChatViewFn() {
            chatView.classList.remove('chat-view-hidden');
            chatView.classList.add('chat-view-visible');
        }

        window.showAllFriends = function() {
            setActiveTab('tab-semua');
            welcomeView.classList.remove('d-none');
            hideChatView();
            document.getElementById('main-view-title').innerText = 'Teman';
            document.getElementById('all-friends-view').classList.remove('d-none');
            document.getElementById('requests-view').classList.add('d-none');
            document.getElementById('search-view').classList.add('d-none');
        };

        window.closeChat = function() {
            currentFriendId = null;
            hideChatView();
            welcomeView.classList.remove('d-none');
            showAllFriends();
            document.getElementById('member-sidebar').style.opacity = '0.5';
        };

        window.showRequests = function() {
            setActiveTab('tab-tertunda');
            welcomeView.classList.remove('d-none');
            hideChatView();
            document.getElementById('main-view-title').innerText = 'Permintaan Pertemanan';
            document.getElementById('requests-view').classList.remove('d-none');
            document.getElementById('all-friends-view').classList.add('d-none');
            document.getElementById('search-view').classList.add('d-none');
        };

        window.showAddFriend = function() {
            setActiveTab('tab-tambah');
            welcomeView.classList.remove('d-none');
            hideChatView();
            document.getElementById('main-view-title').innerText = 'Tambah Teman';
            document.getElementById('search-view').classList.remove('d-none');
            document.getElementById('all-friends-view').classList.add('d-none');
            document.getElementById('requests-view').classList.add('d-none');
            document.getElementById('search-results-container').innerHTML = '';
            setTimeout(() => document.getElementById('add-friend-input').focus(), 100);
        };

        window.closeAddFriend = function() {
            showAllFriends();
        };

        // --- ADD FRIEND SEARCH (global search API) ---
        document.getElementById('add-friend-input').addEventListener('input', function() {
            const query = this.value.trim();
            clearTimeout(addFriendTimeout);
            if (query.length < 3) { searchResults.innerHTML = ''; return; }
            addFriendTimeout = setTimeout(async () => {
                searchResults.innerHTML = '<div class="col-12 text-center p-5"><div class="spinner-border text-accent"></div></div>';
                try {
                    const response = await fetch(`/user/teman/search?query=${encodeURIComponent(query)}`);
                    const users = await response.json();
                    renderResults(users);
                } catch (e) { console.error(e); }
            }, 500);
        });

        function renderResults(users) {
            if (users.length === 0) {
                searchResults.innerHTML = '<div class="col-12 text-center p-5 text-muted">Tidak ada user ditemukan.</div>';
                return;
            }
            searchResults.innerHTML = users.map(u => {
                const photo = (u.profile && u.profile.profile_photo) ? `/storage/${u.profile.profile_photo}` : null;
                const bio = (u.profile && u.profile.description) ? u.profile.description.replace(/[\r\n]/g, ' ') : 'Pemain KAMCUP yang siap bertanding!';
                
                return `
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-4 text-center border h-100 transition-all">
                        ${photo ? 
                            `<img src="${photo}" class="rounded-circle mb-2 object-fit-contain bg-white" style="width:50px;height:50px;" alt="">` :
                            `<div class="mx-auto mb-2" style="width:50px;height:50px;background:var(--teman-accent);border-radius:12px;display:flex;align-items:center;justify-content:center;color:white;font-weight:bold;"> ${u.name[0].toUpperCase()} </div>`
                        }
                        <div class="fw-bold text-dark small text-truncate">${u.name}</div>
                        <div class="text-muted x-small mb-3">ID: #${u.id}</div>
                        ${getResButton(u)}
                    </div>
                </div>
            `}).join('');
        }

        function getResButton(u) {
            if (u.friendship_status === 'none') return `
                <form action="/user/teman/${u.id}/add" method="POST" onsubmit="return false;" class="add-friend-form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="button" onclick="addFriend(this, ${u.id})" class="btn btn-sm btn-accent rounded-pill px-4 mt-2 mb-2">Tambah Teman</button>
                </form>
            `;
            if (u.friendship_status === 'pending') return `<button class="btn btn-outline-secondary btn-sm w-100 rounded-pill x-small disabled">${u.is_sender ? 'Menunggu...' : 'Terima?'}</button>`;
            return `<button class="btn btn-outline-success btn-sm w-100 rounded-pill x-small disabled"><i class="fas fa-check me-1"></i> Sudah Teman</button>`;
        }

        window.addFriend = async function(btn, userId) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengirim...';
            try {
                const res = await fetch(`/user/teman/${userId}/add`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                if (res.ok) {
                    // Refresh search results
                    const query = document.getElementById('add-friend-input').value.trim();
                    const response = await fetch(`/user/teman/search?query=${encodeURIComponent(query)}`);
                    renderResults(await response.json());
                }
            } catch(e) {
                console.error(e);
                btn.disabled = false;
                btn.innerHTML = 'Tambah Teman'; // Revert button text on error
            }
        };

        // --- CHAT ---
        window.openChat = async function(id, name, avatar = '', bio = '', joinDate = 'Jan 01, 2026', mutualCount = 0, mutuals = []) {
            currentFriendId = id;
            currentFriendName = name;
            welcomeView.classList.add('d-none');
            showChatViewFn();
            chatInput.placeholder = `Kirim pesan ke @${name}`;
            
            // Update mobile chat header with profile info
            updateMobileChatHeader(name, avatar, bio, joinDate, mutualCount, mutuals);
            
            // On mobile, hide sidebar when opening chat
            if (isMobile) {
                hideSidebar();
            }
            
            // Highlight active friend in sidebar
            document.querySelectorAll('.friend-item-sidebar').forEach(el => el.classList.remove('bg-white', 'shadow-sm', 'text-dark'));
            const items = document.querySelectorAll('.friend-item-sidebar');
            items.forEach(item => {
                const nameEl = item.querySelector('.fw-bold');
                if(nameEl && nameEl.innerText === name) item.classList.add('bg-white', 'shadow-sm');
            });

            // Side info update
            document.getElementById('member-sidebar').style.display = 'flex';
            document.getElementById('member-name-sidebar').innerText = name;
            document.getElementById('member-bio-sidebar').innerText = bio || 'Pemain KAMCUP yang siap bertanding!';
            document.getElementById('member-since-sidebar').innerText = joinDate;
            document.getElementById('mutual-count').innerText = mutualCount;
            
            // Populate mutual list
            const container = document.getElementById('mutual-list-container');
            if (mutuals && mutuals.length > 0) {
                container.innerHTML = mutuals.map(c => `
                    <div class="d-flex align-items-center mb-2 animate-fade-in">
                        <div class="bg-accent-light rounded-2 me-2 d-flex align-items-center justify-content-center" style="width:28px;height:28px;">
                            ${c.image ? 
                                `<img src="${c.image}" class="rounded-2" style="width:28px;height:28px;object-fit:cover;">` : 
                                `<i class="fas fa-users text-accent small"></i>`
                            }
                        </div>
                        <span class="small fw-bold text-dark text-truncate">${c.name}</span>
                    </div>
                `).join('');
            } else {
                container.innerHTML = '<div class="text-muted x-small p-2 text-center">Tidak ada komunitas yang sama.</div>';
            }

            // Reset dropdown state
            container.classList.add('d-none');
            document.getElementById('mutual-chevron').style.transform = 'rotate(0deg)';

            const avatarContainer = document.getElementById('member-avatar-container');
            if (avatar) {
                // Check if avatar is already a full URL or just a path
                const avatarSrc = avatar.startsWith('http') ? avatar : `/storage/${avatar}`;
                avatarContainer.innerHTML = `<img src="${avatarSrc}" class="rounded-circle object-fit-contain shadow-sm h-100 w-100 border border-4 border-white bg-light" style="background:#f8f9fa;" alt="">`;
            } else {
                avatarContainer.innerHTML = `<div class="rounded-circle bg-accent d-flex align-items-center justify-content-center text-white fw-bold fs-2 h-100 w-100 shadow-sm border border-4 border-white">${name[0].toUpperCase()}</div>`;
            }
            
            document.getElementById('chat-actions-dropdown').innerHTML = `
                <form action="/user/teman/${id}/remove" method="POST" onsubmit="return confirm('Hapus teman?')">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-sm btn-outline-danger px-3 rounded-pill h6 mb-0">Hapus Teman</button>
                </form>
            `;

            const msgContainer = document.getElementById('chat-messages-inner');
            msgContainer.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-accent"></div></div>';
            
            try {
                const res = await fetch(`/user/teman/${id}/messages`);
                const msgs = await res.json();
                msgContainer.innerHTML = '';
                msgs.forEach(m => appendMsg(m));
                chatBox.scrollTop = chatBox.scrollHeight;
            } catch(e) {}
        };

        // --- CHAT FORM SUBMISSION ---
        document.getElementById('chat-form').onsubmit = async (e) => {
            e.preventDefault();
            const text = chatInput.value.trim();
            if (!text && !selectedImage) return;
            
            chatInput.value = '';
            
            // Optimistic update for own messages
            const now = new Date();
            let messageData = {
                sender_id: userId,
                message: text,
                created_at: now.toISOString(),
                image_path: null
            };
            
            // If there's an image, handle it differently
            if (selectedImage) {
                // Show optimistic update with image
                messageData.image_path = selectedImage; // Use base64 for optimistic update
                
                // Send real image
                try {
                    const formData = new FormData();
                    formData.append('image', dataURLtoFile(selectedImage, 'chat-image.png'));
                    if (text) {
                        formData.append('message', text);
                    }
                    
                    const res = await fetch(`/user/teman/${currentFriendId}/send-image`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData
                    });
                    
                    if (!res.ok) {
                        console.error('Failed to send image:', res.status);
                        // Try to get error details
                        const errorText = await res.text();
                        console.error('Error response:', errorText);
                        
                        // Remove optimistic message if failed
                        const lastMessage = document.querySelector('#chat-messages-inner .chat-msg-container:last-child');
                        if (lastMessage) {
                            lastMessage.remove();
                        }
                        chatInput.value = text;
                        
                        // Show specific error based on status code
                        let errorMessage = 'Gagal mengirim gambar. Silakan coba lagi.';
                        if (res.status === 413) {
                            errorMessage = 'Ukuran file terlalu besar. Maksimal 2MB.';
                        } else if (res.status === 422) {
                            errorMessage = 'File tidak valid. Pastikan file adalah gambar dan ukuran tidak lebih dari 2MB.';
                        } else if (res.status === 500) {
                            errorMessage = 'Server sedang bermasalah. Silakan coba lagi nanti.';
                        } else if (res.status === 403) {
                            errorMessage = 'Tidak memiliki izin untuk mengirim gambar.';
                        } else {
                            errorMessage = `Gagal mengirim gambar (${res.status}). Silakan coba lagi.`;
                        }
                        
                        showErrorNotification(errorMessage);
                        return;
                    }
                    
                    // Check if response is JSON
                    const contentType = res.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const errorText = await res.text();
                        console.error('Non-JSON response:', errorText);
                        
                        // Remove optimistic message if failed
                        const lastMessage = document.querySelector('#chat-messages-inner .chat-msg-container:last-child');
                        if (lastMessage) {
                            lastMessage.remove();
                        }
                        chatInput.value = text;
                        
                        // Show user-friendly error
                        showErrorNotification('Server mengembalikan response tidak valid. Silakan coba lagi.');
                        return;
                    }
                    
                    const result = await res.json();
                    
                    // Check if upload was successful
                    if (result.success && result.message) {
                        // Update optimistic message with real image path
                        const lastMessage = document.querySelector('#chat-messages-inner .chat-msg-container:last-child .chat-msg-text img');
                        if (lastMessage && result.message.image_path) {
                            lastMessage.src = `/storage/${result.message.image_path}`;
                        }
                        
                        // Show success notification
                        showSuccessNotification('Gambar berhasil dikirim!');
                    } else {
                        console.error('Upload failed:', result.message);
                        
                        // Remove optimistic message if failed
                        const lastMessage = document.querySelector('#chat-messages-inner .chat-msg-container:last-child');
                        if (lastMessage) {
                            lastMessage.remove();
                        }
                        chatInput.value = text;
                        
                        // Show specific error message
                        let errorMessage = 'Gagal mengirim gambar. Silakan coba lagi.';
                        if (result.errors && result.errors.image) {
                            const errorText = result.errors.image[0];
                            
                            // Translate technical errors to user-friendly messages
                            if (errorText.includes('may not be greater than')) {
                                errorMessage = 'Ukuran file terlalu besar. Maksimal 2MB.';
                            } else if (errorText.includes('must be an image')) {
                                errorMessage = 'File yang dipilih bukan gambar. Silakan pilih file gambar.';
                            } else if (errorText.includes('required')) {
                                errorMessage = 'File gambar wajib diisi.';
                            } else {
                                errorMessage = `Error: ${errorText}`;
                            }
                        } else if (result.message) {
                            errorMessage = result.message;
                        }
                        
                        showErrorNotification(errorMessage);
                        return;
                    }
                    
                } catch(e) {
                    console.error('Error sending image:', e);
                    // Remove optimistic message if failed
                    const lastMessage = document.querySelector('#chat-messages-inner .chat-msg-container:last-child');
                    if (lastMessage) {
                        lastMessage.remove();
                    }
                    chatInput.value = text;
                    
                    // Show user-friendly error
                    showErrorNotification('Gagal mengirim gambar. Periksa koneksi internet Anda.');
                    return;
                }
                
                // Clear image preview
                selectedImage = null;
                imagePreview.src = '';
                imagePreviewContainer.classList.add('d-none');
                imageUpload.value = '';
            } else {
                // Send text message
                try {
                    const res = await fetch(`/user/teman/${currentFriendId}/messages`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ message: text })
                    });
                    
                    if (!res.ok) {
                        console.error('Failed to send message:', res.status);
                        // Remove optimistic message if failed
                        const lastMessage = document.querySelector('#chat-messages-inner .chat-msg-container:last-child');
                        if (lastMessage) {
                            lastMessage.remove();
                        }
                        chatInput.value = text;
                        return;
                    }
                } catch(e) {
                    console.error('Error sending message:', e);
                    // Remove optimistic message if failed
                    const lastMessage = document.querySelector('#chat-messages-inner .chat-msg-container:last-child');
                    if (lastMessage) {
                        lastMessage.remove();
                    }
                    chatInput.value = text;
                    return;
                }
            }
            
            // Show optimistic update
            appendMsg(messageData);
            chatBox.scrollTop = chatBox.scrollHeight;
        };

        // --- IMAGE UPLOAD FUNCTIONALITY ---
        const attachImageBtn = document.getElementById('attach-image-btn');
        const imageUpload = document.getElementById('image-upload');
        const imagePreviewContainer = document.getElementById('image-preview-container');
        const imagePreview = document.getElementById('image-preview');
        const removeImageBtn = document.getElementById('remove-image-btn');
        let selectedImage = null;

        // Open file picker when + button is clicked
        attachImageBtn.addEventListener('click', () => {
            imageUpload.click();
        });

        // Handle image selection
        imageUpload.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                // Check file size (2MB = 2 * 1024 * 1024 bytes)
                const maxSize = 2 * 1024 * 1024;
                if (file.size > maxSize) {
                    showErrorNotification('Ukuran file terlalu besar. Maksimal 2MB.');
                    imageUpload.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    selectedImage = e.target.result;
                    imagePreview.src = selectedImage;
                    imagePreviewContainer.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            } else if (file) {
                // For non-image files, still show error but more gentle
                showErrorNotification('File yang dipilih bukan gambar. Silakan pilih file gambar.');
                imageUpload.value = '';
            }
        });

        // Remove image preview
        removeImageBtn.addEventListener('click', () => {
            selectedImage = null;
            imagePreview.src = '';
            imagePreviewContainer.classList.add('d-none');
            imageUpload.value = '';
        });

        // --- EMOJI PICKER FUNCTIONALITY ---
        const emojiBtn = document.getElementById('emoji-btn');
        const emojiPickerContainer = document.getElementById('emoji-picker-container');
        const emojiGrid = document.querySelector('.emoji-grid');

        // Common emojis
        const emojis = [
            '😀', '😃', '😄', '😁', '😆', '😅', '😂', '🤣', '😊', '😇',
            '🙂', '🙃', '😉', '😌', '😍', '🥰', '😘', '😗', '😙', '😚',
            '😋', '😛', '😜', '🤪', '😝', '🤑', '🤗', '🤭', '🤫', '🤔',
            '🤐', '🤨', '😐', '😑', '😶', '😏', '😒', '🙄', '😬', '🤥',
            '😌', '😔', '😪', '🤤', '😴', '😷', '🤒', '🤕', '🤢', '🤮',
            '🤧', '🥵', '🥶', '🥴', '😵', '🤯', '🤠', '🥳', '😎', '🤓',
            '🧐', '😕', '😟', '🙁', '☹️', '😮', '😯', '😲', '😳', '🥺',
            '😦', '😧', '😨', '😰', '😥', '😢', '😭', '😱', '😖', '😣',
            '😞', '😓', '😩', '😫', '🥱', '😤', '😡', '😠', '🤬', '😈',
            '👿', '💀', '☠️', '💩', '🤡', '👹', '👺', '👻', '👽', '👾',
            '🤖', '❤️', '🧡', '💛', '💚', '💙', '💜', '🖤', '🤍', '🤎',
            '💔', '❣️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '👍',
            '👎', '👌', '✌️', '🤞', '🤟', '🤘', '🤙', '👈', '👉', '👆',
            '👇', '☝️', '✋', '🤚', '🖐️', '🖖', '👋', '🤙', '💪', '🙏'
        ];

        // Populate emoji grid
        emojis.forEach(emoji => {
            const emojiBtn = document.createElement('button');
            emojiBtn.className = 'btn btn-sm btn-light p-2';
            emojiBtn.style.fontSize = '20px';
            emojiBtn.textContent = emoji;
            emojiBtn.addEventListener('click', () => {
                chatInput.value += emoji;
                chatInput.focus();
                emojiPickerContainer.classList.add('d-none');
            });
            emojiGrid.appendChild(emojiBtn);
        });

        // Toggle emoji picker
        emojiBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            emojiPickerContainer.classList.toggle('d-none');
        });

        // Close emoji picker when clicking outside
        document.addEventListener('click', (e) => {
            if (!emojiPickerContainer.contains(e.target) && e.target !== emojiBtn) {
                emojiPickerContainer.classList.add('d-none');
            }
        });

        // Helper function to show error notification
        function showErrorNotification(message) {
            const notification = $(`
                <div class="alert alert-danger alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999; min-width: 250px;">
                    <i class="fas fa-exclamation-triangle me-2"></i>${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
            $('body').append(notification);
            setTimeout(() => notification.fadeOut(500, () => notification.remove()), 5000);
        }

        // Helper function to show success notification
        function showSuccessNotification(message) {
            const notification = $(`
                <div class="alert alert-success alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999; min-width: 250px;">
                    <i class="fas fa-check-circle me-2"></i>${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
            $('body').append(notification);
            setTimeout(() => notification.fadeOut(500, () => notification.remove()), 3000);
        }

        // Helper function to convert dataURL to File
        function dataURLtoFile(dataurl, filename) {
            const arr = dataurl.split(',');
            const mime = arr[0].match(/:(.*?);/)[1];
            const bstr = atob(arr[1]);
            let n = bstr.length;
            const u8arr = new Uint8Array(n);
            while(n--){
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new File([u8arr], filename, {type:mime});
        }

        // Update appendMsg to handle real images
        function appendMsg(m) {
            const isMe = m.sender_id === userId;
            const container = document.getElementById('chat-messages-inner');
            const item = document.createElement('div');
            item.className = 'chat-msg-container animate-fade-in mb-2';
            
            const time = new Date(m.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const senderName = m.sender_id == userId ? '{{ Auth::user()->name }}' : currentFriendName;
            const senderInitial = senderName[0].toUpperCase();
            
            // Handle message content with images
            let messageContent = '';
            
            if (m.image_path) {
                // Real image from backend
                const imageSrc = m.image_path.startsWith('data:') ? m.image_path : `/storage/${m.image_path}`;
                messageContent = `<img src="${imageSrc}" alt="Image" class="rounded-2" style="max-width: 200px; max-height: 200px; object-fit: cover;">`;
                
                if (m.message) {
                    messageContent = `<div>${m.message}</div><div class="mt-2">${messageContent}</div>`;
                }
            } else if (m.message) {
                // Text only message
                messageContent = m.message;
            }
            
            item.innerHTML = `
                <div class="avatar-sm">
                    <div class="rounded-circle ${isMe ? 'bg-pink-light text-pink' : 'bg-accent-light text-accent'} d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; font-size: 0.8rem;"> ${senderInitial} </div>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center mb-1">
                        <span class="chat-msg-author small ${isMe ? 'text-pink' : 'text-accent'}">${senderName}</span>
                        <span class="chat-msg-time ms-2">${time}</span>
                    </div>
                    <div class="chat-msg-text">${messageContent}</div>
                </div>
            `;
            container.appendChild(item);
        }

        if (typeof Echo !== 'undefined') {
            Echo.private(`user.${userId}`)
                .listen('.PrivateMessageSent', (e) => {
                    if (currentFriendId && parseInt(e.user.id) === parseInt(currentFriendId)) {
                        // Don't show own messages (already shown via optimistic update)
                        if (parseInt(e.user.id) === userId) {
                            return;
                        }
                        
                        // Only show other person's messages
                        appendMsg({
                            sender_id: e.user.id,
                            message: e.message,
                            created_at: e.created_at
                        });
                        chatBox.scrollTop = chatBox.scrollHeight;
                    }
                });
        }
    });
</script>
@endpush
