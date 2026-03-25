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

    /* ===================== CHAT LAYOUT FIX ===================== */
    /* Main chat area harus full height dan flex column */
    .main-chat-area {
        display: flex;
        flex-direction: column;
        height: 100vh;
        overflow: hidden;
    }

    /* Chat view harus flex column dan mengisi sisa ruang */
    #chat-view {
        display: none;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
    }
    #chat-view.chat-view-visible {
        display: flex !important;
    }
    #chat-view.chat-view-hidden {
        display: none !important;
    }

    /* Chat box — area pesan yang bisa di-scroll */
    #chat-box {
        flex: 1 1 auto;
        overflow-y: auto;
        overflow-x: hidden;
        display: flex;
        flex-direction: column;
        padding: 16px;
        min-height: 0; /* PENTING: tanpa ini flex child tidak bisa scroll */
    }

    #chat-messages-inner {
        margin-top: auto;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    /* Chat input wrapper — selalu di bawah */
    .chat-input-wrapper {
        flex-shrink: 0;
        position: relative;
    }
    /* =========================================================== */

    .chat-msg-container {
        display: flex; gap: 12px; padding: 6px 0;
    }
    .chat-msg-author { font-weight: 700; color: #333; margin-right: 8px; }
    .chat-msg-time { font-size: 0.7rem; color: #999; }
    .chat-msg-text { color: #444; font-size: 0.95rem; line-height: 1.5; }

    /* Reply thread style (Discord-like) */
    .reply-thread {
        background: #f0f4f8;
        border-left: 3px solid var(--teman-accent);
        border-radius: 6px;
        padding: 6px 10px;
        margin-bottom: 6px;
        font-size: 0.8rem;
        color: #666;
        cursor: pointer;
        max-width: 100%;
        overflow: hidden;
    }
    .reply-thread:hover { background: #e8eef3; }
    .reply-thread .reply-author { font-weight: 700; color: var(--teman-accent); margin-right: 6px; }
    .reply-thread .reply-text { color: #555; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* Context menu untuk reply */
    .chat-context-menu {
        position: fixed;
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 6px 0;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        z-index: 9999;
        min-width: 160px;
    }
    .chat-context-menu-item {
        padding: 8px 16px;
        cursor: pointer;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background 0.15s;
    }
    .chat-context-menu-item:hover { background: #f8f9fa; }

    /* Reply preview bar (di atas input) */
    .reply-preview-bar {
        background: #f0f4f8;
        border-left: 3px solid var(--teman-accent);
        border-radius: 6px;
        padding: 8px 12px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.82rem;
    }
    .reply-preview-bar .reply-preview-text {
        color: #555;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        flex: 1;
    }
    .reply-preview-bar .btn-cancel-reply {
        background: none;
        border: none;
        color: #999;
        cursor: pointer;
        padding: 0 4px;
        font-size: 1rem;
        line-height: 1;
    }
    .reply-preview-bar .btn-cancel-reply:hover { color: #333; }

    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #dee2e6; border-radius: 10px; }

    .hover-accent:hover { color: var(--teman-accent); }
    .cursor-pointer { cursor: pointer; }
    .transition-all { transition: all 0.2s; }

    /* Animate */
    .animate-fade-in { animation: fadeIn 0.2s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }

    /* Responsive Design */
    @media (max-width: 768px) {
        .friends-app-container {
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

        .dm-list-bar.mobile-visible { transform: translateX(0); }
        .dm-list-bar.mobile-hidden { transform: translateX(-100%); }

        .chat-header .chat-header-content { display: none !important; }

        .mobile-chat-header {
            padding: 12px 16px !important;
            background: white !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 100 !important;
            display: flex !important;
            border-bottom: 1px solid #e9ecef;
        }

        .x-small { font-size: 0.75rem; }
    }

    @media (max-width: 480px) {
        .chat-msg-text { font-size: 0.85rem !important; }
        .dm-list-bar .btn { font-size: 0.85rem !important; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const userId = {{ Auth::id() }};
    const authUserName = @json(Auth::user()->name);
    const authUserPhoto = @json(Auth::user()->profile?->profile_photo);

    const sidebarSearchInput = document.getElementById('user-search-input');
    const searchResults = document.getElementById('search-results-container');
    const welcomeView = document.getElementById('welcome-view');
    const chatView = document.getElementById('chat-view');

    const isMobile = () => window.innerWidth <= 768;

    // ===================== SIDEBAR (MOBILE) =====================
    function showSidebar() {
        const bar = document.querySelector('.dm-list-bar');
        if (isMobile() && bar) {
            bar.classList.remove('mobile-hidden');
            bar.classList.add('mobile-visible');
            chatView.classList.add('chat-view-hidden');
            chatView.classList.remove('chat-view-visible');
        }
    }
    function hideSidebar() {
        const bar = document.querySelector('.dm-list-bar');
        if (isMobile() && bar) {
            bar.classList.remove('mobile-visible');
            bar.classList.add('mobile-hidden');
            chatView.classList.remove('chat-view-hidden');
            chatView.classList.add('chat-view-visible');
        }
    }
    window.showSidebar = showSidebar;
    window.hideSidebar = hideSidebar;

    if (isMobile()) showSidebar();

    // ===================== NAVIGATION =====================
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

    window.showAllFriends = function () {
        setActiveTab('tab-semua');
        welcomeView.classList.remove('d-none');
        hideChatView();
        document.getElementById('main-view-title').innerText = 'Teman';
        document.getElementById('all-friends-view').classList.remove('d-none');
        document.getElementById('requests-view').classList.add('d-none');
        document.getElementById('search-view').classList.add('d-none');
    };

    window.showRequests = function () {
        setActiveTab('tab-tertunda');
        welcomeView.classList.remove('d-none');
        hideChatView();
        document.getElementById('main-view-title').innerText = 'Permintaan Pertemanan';
        document.getElementById('requests-view').classList.remove('d-none');
        document.getElementById('all-friends-view').classList.add('d-none');
        document.getElementById('search-view').classList.add('d-none');
    };

    window.showAddFriend = function () {
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

    window.closeAddFriend = function () { window.showAllFriends(); };

    window.closeChat = function () {
        currentFriendId = null;
        hideChatView();
        welcomeView.classList.remove('d-none');
        window.showAllFriends();
    };

    window.toggleMutualList = function () {
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

    // ===================== SIDEBAR SEARCH =====================
    sidebarSearchInput.addEventListener('input', function () {
        const query = this.value.trim().toLowerCase();
        document.querySelectorAll('.friend-item-sidebar').forEach(el => {
            const name = el.querySelector('.fw-bold').innerText.toLowerCase();
            el.style.display = name.includes(query) ? '' : 'none';
        });
    });

    // ===================== ADD FRIEND SEARCH =====================
    let addFriendTimeout = null;

    document.getElementById('add-friend-input').addEventListener('input', function () {
        const query = this.value.trim();
        clearTimeout(addFriendTimeout);
        if (query.length < 3) { searchResults.innerHTML = ''; return; }
        addFriendTimeout = setTimeout(async () => {
            searchResults.innerHTML = '<div class="col-12 text-center p-5"><div class="spinner-border text-accent"></div></div>';
            try {
                const response = await fetch('/user/teman/search?query=' + encodeURIComponent(query));
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
            const photo = (u.profile && u.profile.profile_photo) ? '/storage/' + u.profile.profile_photo : null;
            return `
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-4 text-center border h-100 transition-all">
                        ${photo
                            ? `<img src="${photo}" class="rounded-circle mb-2 object-fit-cover" style="width:50px;height:50px;" alt="">`
                            : `<div class="mx-auto mb-2 rounded-circle bg-accent d-flex align-items-center justify-content-center text-white fw-bold" style="width:50px;height:50px;">${u.name[0].toUpperCase()}</div>`
                        }
                        <div class="fw-bold text-dark small text-truncate">${u.name}</div>
                        <div class="text-muted x-small mb-3">ID: #${u.id}</div>
                        ${getResButton(u)}
                    </div>
                </div>`;
        }).join('');
    }

    function getResButton(u) {
        if (u.friendship_status === 'none') return `
            <button type="button" onclick="addFriend(this, ${u.id})" class="btn btn-sm btn-accent rounded-pill px-4 mt-2 mb-2">Tambah Teman</button>`;
        if (u.friendship_status === 'pending') return `<button class="btn btn-outline-secondary btn-sm w-100 rounded-pill x-small disabled">${u.is_sender ? 'Menunggu...' : 'Terima?'}</button>`;
        return `<button class="btn btn-outline-success btn-sm w-100 rounded-pill x-small disabled"><i class="fas fa-check me-1"></i> Sudah Teman</button>`;
    }

    window.addFriend = async function (btn, userId) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengirim...';
        try {
            const res = await fetch('/user/teman/' + userId + '/add', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            if (res.ok) {
                const query = document.getElementById('add-friend-input').value.trim();
                const response = await fetch('/user/teman/search?query=' + encodeURIComponent(query));
                renderResults(await response.json());
            }
        } catch (e) {
            console.error(e);
            btn.disabled = false;
            btn.innerHTML = 'Tambah Teman';
        }
    };

    // ===================== CHAT STATE =====================
    const chatBox = document.getElementById('chat-box');
    const chatInput = document.getElementById('chat-input');
    let currentFriendId = null;
    let currentFriendName = '';
    let currentFriendPhoto = '';
    let currentReplyMessageId = null;
    let currentReplyMessage = null;

    // ===================== OPEN CHAT =====================
    window.openChat = async function (id, name, avatar = '', bio = '', joinDate = 'Jan 01, 2026', mutualCount = 0, mutuals = []) {
        currentFriendId = id;
        currentFriendName = name;
        currentFriendPhoto = avatar;

        welcomeView.classList.add('d-none');
        showChatViewFn();
        chatInput.placeholder = 'Kirim pesan ke @' + name;

        // Mobile: update header & hide sidebar
        updateMobileChatHeader(name, avatar);
        if (isMobile()) hideSidebar();

        // Highlight active friend in sidebar
        document.querySelectorAll('.friend-item-sidebar').forEach(el => {
            el.classList.remove('bg-white', 'shadow-sm');
            const nameEl = el.querySelector('.fw-bold');
            if (nameEl && nameEl.innerText === name) el.classList.add('bg-white', 'shadow-sm');
        });

        // Update right sidebar info
        document.getElementById('member-sidebar').style.display = 'flex';
        document.getElementById('member-name-sidebar').innerText = name;
        document.getElementById('member-bio-sidebar').innerText = bio || 'Pemain KAMCUP yang siap bertanding!';
        document.getElementById('member-since-sidebar').innerText = joinDate;
        document.getElementById('mutual-count').innerText = mutualCount;

        // Avatar di right sidebar
        const avatarContainer = document.getElementById('member-avatar-container');
        if (avatar) {
            avatarContainer.innerHTML = `<img src="${avatar}" class="rounded-circle object-fit-cover shadow-sm border border-4 border-white" style="width:92px;height:92px;" alt="">`;
        } else {
            avatarContainer.innerHTML = `<div class="rounded-circle bg-accent d-flex align-items-center justify-content-center text-white fw-bold fs-2 shadow-sm border border-4 border-white" style="width:92px;height:92px;">${name[0].toUpperCase()}</div>`;
        }

        // Mutual communities
        const mutualContainer = document.getElementById('mutual-list-container');
        if (mutuals && mutuals.length > 0) {
            mutualContainer.innerHTML = mutuals.map(c => `
                <div class="d-flex align-items-center mb-2">
                    <div class="bg-accent-light rounded-2 me-2 d-flex align-items-center justify-content-center" style="width:28px;height:28px;">
                        ${c.image
                            ? `<img src="${c.image}" class="rounded-2" style="width:28px;height:28px;object-fit:cover;">`
                            : `<i class="fas fa-users text-accent small"></i>`}
                    </div>
                    <span class="small fw-bold text-dark text-truncate">${c.name}</span>
                </div>`).join('');
        } else {
            mutualContainer.innerHTML = '<div class="text-muted x-small p-2 text-center">Tidak ada komunitas yang sama.</div>';
        }
        mutualContainer.classList.add('d-none');
        document.getElementById('mutual-chevron').style.transform = 'rotate(0deg)';

        // Actions (hapus teman) — pakai form POST + method DELETE yang valid
        document.getElementById('chat-actions-dropdown').innerHTML = `
            <form id="remove-friend-form-${id}" onsubmit="removeFriend(event, ${id})">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-sm btn-outline-danger px-3 rounded-pill">Hapus Teman</button>
            </form>`;

        // Load messages
        const msgContainer = document.getElementById('chat-messages-inner');
        msgContainer.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-accent"></div></div>';
        try {
            const res = await fetch('/user/teman/' + id + '/messages');
            const msgs = await res.json();
            msgContainer.innerHTML = '';
            msgs.forEach(m => appendMsg(m));
            scrollToBottom();
        } catch (e) {
            console.error('Error loading messages:', e);
            msgContainer.innerHTML = '<div class="text-center p-5 text-muted">Gagal memuat pesan.</div>';
        }
    };

    // ===================== HAPUS TEMAN (fix agar tidak error) =====================
    window.removeFriend = async function (e, friendId) {
        e.preventDefault();
        if (!confirm('Hapus teman ini?')) return;
        try {
            const res = await fetch('/user/teman/' + friendId + '/remove', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ _method: 'DELETE' })
            });
            if (res.ok) {
                // Redirect ke halaman teman setelah hapus
                window.location.reload();
            } else {
                showErrorNotification('Gagal menghapus teman. Silakan coba lagi.');
            }
        } catch (e) {
            console.error(e);
            showErrorNotification('Terjadi kesalahan. Silakan coba lagi.');
        }
    };

    // ===================== MOBILE CHAT HEADER =====================
    function updateMobileChatHeader(name, avatar) {
        const chatHeader = document.querySelector('.chat-header');
        if (!chatHeader) return;
        const existing = chatHeader.querySelector('.mobile-chat-header');
        if (existing) existing.remove();

        if (isMobile()) {
            const desktopContent = chatHeader.querySelector('.chat-header-content');
            if (desktopContent) desktopContent.style.display = 'none';

            const avatarHtml = avatar
                ? `<img src="${avatar}" class="rounded-circle object-fit-cover" style="width:38px;height:38px;" alt="">`
                : `<div class="rounded-circle bg-accent-light text-accent d-flex align-items-center justify-content-center fw-bold" style="width:38px;height:38px;">${name[0]?.toUpperCase() || 'U'}</div>`;

            const mobileHeader = document.createElement('div');
            mobileHeader.className = 'mobile-chat-header d-flex align-items-center w-100';
            mobileHeader.innerHTML = `
                <button class="btn btn-sm btn-outline-secondary me-3" onclick="window.showSidebar()">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div class="d-flex align-items-center flex-grow-1">
                    <div class="position-relative me-2">${avatarHtml}</div>
                    <div>
                        <div class="fw-bold text-dark" style="font-size:0.95rem;">${name}</div>
                        <div class="small text-muted" style="font-size:0.78rem;">Online</div>
                    </div>
                </div>`;
            chatHeader.appendChild(mobileHeader);
        } else {
            const desktopContent = chatHeader.querySelector('.chat-header-content');
            if (desktopContent) desktopContent.style.display = 'block';
            const headerName = document.getElementById('chat-header-name');
            if (headerName) headerName.textContent = name;
        }
    }

    // ===================== REPLY FEATURE =====================
    // Context menu element (dibuat sekali, reused)
    let contextMenuEl = null;

    function getOrCreateContextMenu() {
        if (!contextMenuEl) {
            contextMenuEl = document.createElement('div');
            contextMenuEl.className = 'chat-context-menu';
            contextMenuEl.id = 'chat-context-menu';
            document.body.appendChild(contextMenuEl);
        }
        return contextMenuEl;
    }

    function hideContextMenu() {
        if (contextMenuEl) contextMenuEl.style.display = 'none';
    }

    document.addEventListener('click', function (e) {
        if (contextMenuEl && !contextMenuEl.contains(e.target)) hideContextMenu();
    });

    window.showReplyContextMenu = function (e, m) {
        e.preventDefault();
        e.stopPropagation();

        const menu = getOrCreateContextMenu();
        menu.innerHTML = `
            <div class="chat-context-menu-item" id="ctx-reply-btn">
                <i class="fas fa-reply text-accent"></i> Balas Pesan
            </div>`;
        menu.style.display = 'block';

        // Posisi menu — hindari keluar layar
        const x = Math.min(e.clientX, window.innerWidth - 180);
        const y = Math.min(e.clientY, window.innerHeight - 80);
        menu.style.left = x + 'px';
        menu.style.top = y + 'px';

        document.getElementById('ctx-reply-btn').onclick = function () {
            setReply(m);
            hideContextMenu();
        };
    };

    function setReply(m) {
        currentReplyMessageId = m.id;
        const senderName = m.sender ? m.sender.name : (m.sender_id == userId ? authUserName : currentFriendName);
        const previewText = (m.message || '[Gambar]').substring(0, 60) + ((m.message || '').length > 60 ? '...' : '');
        currentReplyMessage = { senderName, previewText };

        // Hapus reply bar lama jika ada
        const oldBar = document.getElementById('reply-preview-bar');
        if (oldBar) oldBar.remove();

        // Buat reply bar baru di atas input
        const inputWrapper = document.querySelector('.chat-input-wrapper');
        const bar = document.createElement('div');
        bar.id = 'reply-preview-bar';
        bar.className = 'reply-preview-bar mx-3 mb-0 mt-2';
        bar.innerHTML = `
            <div style="flex:1;overflow:hidden;">
                <span class="fw-bold text-accent" style="font-size:0.8rem;">Membalas ${senderName}</span>
                <div class="reply-preview-text">${previewText}</div>
            </div>
            <button class="btn-cancel-reply" onclick="clearReplyState()" title="Batal balas">
                <i class="fas fa-times"></i>
            </button>`;
        inputWrapper.insertBefore(bar, inputWrapper.firstChild);

        chatInput.focus();
    }

    window.clearReplyState = function () {
        currentReplyMessageId = null;
        currentReplyMessage = null;
        const bar = document.getElementById('reply-preview-bar');
        if (bar) bar.remove();
        chatInput.placeholder = 'Kirim pesan ke @' + currentFriendName;
    };

    // ===================== SCROLL HELPER =====================
    function scrollToBottom() {
        setTimeout(() => { chatBox.scrollTop = chatBox.scrollHeight; }, 50);
    }

    // ===================== APPEND MESSAGE =====================
    function appendMsg(m) {
        const isMe = parseInt(m.sender_id) === parseInt(userId);
        const container = document.getElementById('chat-messages-inner');
        const item = document.createElement('div');
        item.className = 'chat-msg-container animate-fade-in';
        if (m.id) item.dataset.messageId = m.id;

        // Context menu (klik kanan / long press)
        item.addEventListener('contextmenu', function (e) { window.showReplyContextMenu(e, m); });
        let pressTimer;
        item.addEventListener('touchstart', function (e) {
            pressTimer = setTimeout(() => { window.showReplyContextMenu(e.touches[0] || e, m); }, 600);
        }, { passive: true });
        item.addEventListener('touchend', function () { clearTimeout(pressTimer); });
        item.addEventListener('touchmove', function () { clearTimeout(pressTimer); });

        const time = new Date(m.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        // Tentukan nama & foto pengirim
        let senderName, senderPhoto;
        if (parseInt(m.sender_id) === parseInt(userId)) {
            // Pesan dari saya (auth user)
            senderName = authUserName;
            senderPhoto = authUserPhoto;
        } else {
            // Pesan dari teman
            senderName = (m.sender && m.sender.name) ? m.sender.name : currentFriendName;
            senderPhoto = (m.sender && m.sender.profile && m.sender.profile.profile_photo)
                ? m.sender.profile.profile_photo
                : currentFriendPhoto;
        }

        // Buat avatar HTML (foto jika ada, fallback initial)
        function makeAvatar(photo, name, isMe) {
            const colorClass = isMe ? 'bg-pink-light text-pink' : 'bg-accent-light text-accent';
            if (photo) {
                // Jika photo sudah ada leading slash atau http, pakai langsung, else prepend /storage/
                const src = photo.startsWith('http') || photo.startsWith('/')
                    ? photo
                    : '/storage/' + photo;
                return `<img src="${src}" class="rounded-circle object-fit-cover border" style="width:38px;height:38px;flex-shrink:0;" alt="">`;
            }
            return `<div class="rounded-circle ${colorClass} d-flex align-items-center justify-content-center fw-bold" style="width:38px;height:38px;font-size:0.8rem;flex-shrink:0;">${(name[0] || 'U').toUpperCase()}</div>`;
        }

        // Reply thread HTML
        let replyHtml = '';
        if (m.reply_to) {
            const replySender = m.reply_to.sender ? m.reply_to.sender.name : 'Unknown';
            const replyText = m.reply_to.image_path && !m.reply_to.message
                ? '[Gambar]'
                : (m.reply_to.message || '').substring(0, 60) + ((m.reply_to.message || '').length > 60 ? '...' : '');
            replyHtml = `
                <div class="reply-thread">
                    <span class="reply-author"><i class="fas fa-reply me-1" style="font-size:0.7rem;"></i>${replySender}</span>
                    <span class="reply-text">${replyText}</span>
                </div>`;
        }

        // Message content HTML
        let messageContent = '';
        if (m.image_path) {
            const imageSrc = m.image_path.startsWith('data:') || m.image_path.startsWith('http') || m.image_path.startsWith('/')
                ? m.image_path
                : '/storage/' + m.image_path;
            messageContent = `<img src="${imageSrc}" alt="Gambar" class="rounded-3 shadow-sm mt-1" style="max-width:240px;max-height:240px;object-fit:cover;cursor:pointer;" onclick="window.open('${imageSrc}','_blank')">`;
            if (m.message) {
                messageContent = `<div class="mb-1">${m.message}</div>` + messageContent;
            }
        } else {
            messageContent = m.message || '';
        }

        item.innerHTML = `
            <div class="avatar-sm flex-shrink-0">
                ${makeAvatar(senderPhoto, senderName, isMe)}
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <div class="d-flex align-items-center mb-1 gap-2">
                    <span class="chat-msg-author small ${isMe ? 'text-pink' : 'text-accent'}">${senderName}</span>
                    <span class="chat-msg-time">${time}</span>
                </div>
                ${replyHtml}
                <div class="chat-msg-text">${messageContent}</div>
            </div>`;

        container.appendChild(item);
    }

    // ===================== CHAT FORM SUBMIT =====================
    const imageUpload = document.getElementById('image-upload');
    const imagePreviewContainer = document.getElementById('image-preview-container');
    const imagePreview = document.getElementById('image-preview');
    const removeImageBtn = document.getElementById('remove-image-btn');
    let selectedImage = null;

    document.getElementById('attach-image-btn').addEventListener('click', () => imageUpload.click());

    imageUpload.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;
        if (!file.type.startsWith('image/')) {
            showErrorNotification('File yang dipilih bukan gambar.');
            imageUpload.value = '';
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
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
    });

    removeImageBtn.addEventListener('click', () => {
        selectedImage = null;
        imagePreview.src = '';
        imagePreviewContainer.classList.add('d-none');
        imageUpload.value = '';
    });

    // Emoji picker
    const emojiBtn = document.getElementById('emoji-btn');
    const emojiPickerContainer = document.getElementById('emoji-picker-container');
    const emojiGrid = document.querySelector('.emoji-grid');

    const emojis = [
        '😀','😃','😄','😁','😆','😅','😂','🤣','😊','😇','🙂','🙃','😉','😌','😍','🥰',
        '😘','😗','😙','😚','😋','😛','😜','🤪','😝','🤑','🤗','🤭','🤫','🤔','🤐','🤨',
        '😐','😑','😶','😏','😒','🙄','😬','🤥','😔','😪','🤤','😴','😷','🤒','🤕','🤢',
        '🤮','🤧','🥵','🥶','🥴','😵','🤯','🤠','🥳','😎','🤓','🧐','😕','😟','🙁','☹️',
        '😮','😯','😲','😳','🥺','😦','😧','😨','😰','😥','😢','😭','😱','😖','😣','😞',
        '😓','😩','😫','🥱','😤','😡','😠','🤬','😈','👿','💀','☠️','💩','🤡','👻',
        '❤️','🧡','💛','💚','💙','💜','🖤','🤍','🤎','💔','❣️','💕','💞','💓','💗','💖',
        '👍','👎','👌','✌️','🤞','🤟','🤘','🤙','👋','💪','🙏','👏','🤝'
    ];

    emojis.forEach(emoji => {
        const btn = document.createElement('button');
        btn.className = 'btn btn-sm btn-light p-1';
        btn.style.fontSize = '20px';
        btn.textContent = emoji;
        btn.addEventListener('click', () => {
            chatInput.value += emoji;
            chatInput.focus();
            emojiPickerContainer.classList.add('d-none');
        });
        emojiGrid.appendChild(btn);
    });

    emojiBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        emojiPickerContainer.classList.toggle('d-none');
    });

    document.addEventListener('click', (e) => {
        if (!emojiPickerContainer.contains(e.target) && e.target !== emojiBtn) {
            emojiPickerContainer.classList.add('d-none');
        }
    });

    // Form submit
    document.getElementById('chat-form').onsubmit = async function (e) {
        e.preventDefault();
        if (!currentFriendId) return;
        const text = chatInput.value.trim();
        if (!text && !selectedImage) return;

        chatInput.value = '';

        if (selectedImage) {
            // Optimistic update dulu
            const optimistic = {
                sender_id: userId,
                message: text,
                created_at: new Date().toISOString(),
                image_path: selectedImage
            };
            appendMsg(optimistic);
            scrollToBottom();

            try {
                const formData = new FormData();
                formData.append('image', dataURLtoFile(selectedImage, 'chat-image.png'));
                if (text) formData.append('message', text);

                const res = await fetch('/user/teman/' + currentFriendId + '/send-image', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });

                if (!res.ok) {
                    document.querySelector('#chat-messages-inner .chat-msg-container:last-child')?.remove();
                    chatInput.value = text;
                    showErrorNotification('Gagal mengirim gambar (' + res.status + ').');
                    return;
                }
            } catch (err) {
                console.error(err);
                document.querySelector('#chat-messages-inner .chat-msg-container:last-child')?.remove();
                chatInput.value = text;
                showErrorNotification('Gagal mengirim gambar. Periksa koneksi.');
                return;
            }

            // Clear image
            selectedImage = null;
            imagePreview.src = '';
            imagePreviewContainer.classList.add('d-none');
            imageUpload.value = '';

        } else {
            // Optimistic update teks
            const optimistic = {
                sender_id: userId,
                message: text,
                created_at: new Date().toISOString()
            };
            // Jika ada reply, tambahkan info reply ke optimistic (untuk tampilan)
            if (currentReplyMessageId && currentReplyMessage) {
                optimistic.reply_to = {
                    sender: { name: currentReplyMessage.senderName },
                    message: currentReplyMessage.previewText
                };
            }
            appendMsg(optimistic);
            scrollToBottom();

            try {
                const payload = { message: text };
                if (currentReplyMessageId) payload.reply_message_id = currentReplyMessageId;

                const res = await fetch('/user/teman/' + currentFriendId + '/messages', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });

                if (!res.ok) {
                    document.querySelector('#chat-messages-inner .chat-msg-container:last-child')?.remove();
                    chatInput.value = text;
                    return;
                }
            } catch (err) {
                console.error(err);
                document.querySelector('#chat-messages-inner .chat-msg-container:last-child')?.remove();
                chatInput.value = text;
                return;
            }

            window.clearReplyState();
        }
    };

    // ===================== LARAVEL ECHO (REALTIME) =====================
    if (typeof Echo !== 'undefined') {
        Echo.private('user.' + userId)
            .listen('.PrivateMessageSent', (e) => {
                if (currentFriendId && parseInt(e.user.id) === parseInt(currentFriendId)) {
                    if (parseInt(e.user.id) === parseInt(userId)) return; // skip own messages
                    appendMsg({
                        sender_id: e.user.id,
                        message: e.message,
                        created_at: e.created_at,
                        image_path: e.image_path || null
                    });
                    scrollToBottom();
                }
            });
    }

    // ===================== HELPERS =====================
    function showErrorNotification(message) {
        const el = document.createElement('div');
        el.className = 'alert alert-danger alert-dismissible fade show position-fixed';
        el.style.cssText = 'top:20px;right:20px;z-index:9999;min-width:280px;';
        el.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 5000);
    }

    function showSuccessNotification(message) {
        const el = document.createElement('div');
        el.className = 'alert alert-success alert-dismissible fade show position-fixed';
        el.style.cssText = 'top:20px;right:20px;z-index:9999;min-width:280px;';
        el.innerHTML = `<i class="fas fa-check-circle me-2"></i>${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 3000);
    }

    function dataURLtoFile(dataurl, filename) {
        const arr = dataurl.split(',');
        const mime = arr[0].match(/:(.*?);/)[1];
        const bstr = atob(arr[1]);
        let n = bstr.length;
        const u8arr = new Uint8Array(n);
        while (n--) u8arr[n] = bstr.charCodeAt(n);
        return new File([u8arr], filename, { type: mime });
    }

});
</script>
@endpush
