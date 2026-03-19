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
                    <input type="text" id="user-search-input" class="form-control form-control-sm border-0 bg-light rounded-pill ps-4" placeholder="Cari teman baru...">
                    <i class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted small"></i>
                </div>
            </div>

            <!-- Friends & Requests Navigation -->
            <div class="flex-grow-1 overflow-auto custom-scrollbar px-2 py-3">
                <div class="nav-item-friends mb-1 active" onclick="closeChat()">
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
                    <div class="friend-item-sidebar d-flex align-items-center p-2 rounded-3 cursor-pointer" onclick="openChat({{ $friend->id }}, '{{ $friend->name }}')">
                        <div class="avatar-sm me-3 position-relative">
                            <div class="placeholder-avatar rounded-circle bg-accent-light d-flex align-items-center justify-content-center text-accent fw-bold text-uppercase">
                                {{ substr($friend->name, 0, 1) }}
                            </div>
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
                    <div class="rounded-circle bg-pink-light d-flex align-items-center justify-content-center text-pink fw-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-bold text-dark small text-truncate">{{ Auth::user()->name }}</div>
                    <div class="text-muted x-small text-truncate">Online</div>
                </div>
                <div class="d-flex gap-3 text-muted">
                    <i class="fas fa-cog cursor-pointer hover-accent"></i>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT AREA -->
        <div class="main-chat-area flex-grow-1 d-flex flex-column bg-white">
            <!-- Header -->
            <div class="chat-header p-3 border-bottom d-flex align-items-center bg-white">
                <div class="d-flex align-items-center" id="header-content">
                    <i class="fas fa-at fs-5 text-accent me-2"></i>
                    <h6 class="fw-bold mb-0 text-dark" id="chat-friend-name">Teman</h6>
                </div>
                <div class="ms-auto d-flex gap-3 text-muted fs-5">
                    <i class="fas fa-phone-alt cursor-pointer hover-accent"></i>
                    <i class="fas fa-video cursor-pointer hover-accent"></i>
                    <i class="fas fa-users-cog cursor-pointer hover-accent"></i>
                </div>
            </div>

            <!-- Views -->
            <div class="flex-grow-1 overflow-hidden" style="position:relative;">
                <!-- FRIENDS DASHBOARD (Landing View) -->
                <div id="welcome-view" class="flex-grow-1 d-flex flex-column p-4 animate-fade-in">
                    <div class="d-flex align-items-center gap-4 mb-4 border-bottom pb-3">
                        <h5 class="fw-bold text-dark mb-0"><i class="fas fa-user-friends me-2 text-accent"></i> Teman</h5>
                        <div class="btn-group-friends">
                            <button class="btn-friends active">Online</button>
                            <button class="btn-friends">Semua</button>
                            <button class="btn-friends" onclick="showRequests()">Tertunda <span class="badge bg-danger rounded-pill ms-1">{{ $pendingRequests->count() }}</span></button>
                            <button class="btn-friends btn-add-friend" onclick="document.getElementById('user-search-input').focus()">Tambah Teman</button>
                        </div>
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

                    <!-- Search View Section (Integrated in Welcome) -->
                    <div id="search-view" class="d-none">
                        <h6 class="text-muted mb-3">HASIL PENCARIAN</h6>
                        <div id="search-results-container" class="row g-3">
                            <!-- JS Inject -->
                        </div>
                    </div>

                    <!-- Default Empty State -->
                    <div id="empty-friends-state" class="flex-grow-1 d-flex flex-column align-items-center justify-content-center text-center opacity-75">
                        <div class="mb-4 text-accent" style="font-size: 5rem;">
                            <i class="fas fa-ghost"></i>
                        </div>
                        <h5 class="fw-bold">Belum ada aktivitas</h5>
                        <p class="text-muted small">Cari teman baru untuk mulai mengobrol!</p>
                    </div>
                </div>

                <!-- CHAT VIEW -->
                <div id="chat-view" class="d-none flex-column" style="position:absolute; inset:0; display:flex !important; flex-direction:column; height:100%;">
                    <div class="flex-grow-1 overflow-auto p-4 d-flex flex-column custom-scrollbar" id="chat-box">
                        <div id="chat-messages-inner" class="mt-auto d-flex flex-column">
                            <!-- Messages -->
                        </div>
                    </div>
                    
                    <div class="chat-input-wrapper p-3 ">
                        <div class="bg-light rounded-4 p-2 d-flex align-items-center gap-2 border">
                            <button class="btn btn-sm btn-light rounded-circle text-accent"><i class="fas fa-plus-circle fs-5"></i></button>
                            <form id="chat-form" class="flex-grow-1">
                                <input type="text" id="chat-input" class="form-control bg-transparent border-0 shadow-none" placeholder="Tulis pesan..." autocomplete="off">
                            </form>
                            <div class="d-flex gap-2 text-muted px-2">
                                <i class="far fa-smile fs-5 cursor-pointer hover-accent"></i>
                                <button type="submit" form="chat-form" class="btn btn-accent btn-sm rounded-pill px-3 ms-2">Kirim</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MEMBER INFO (Sidebar Right) -->
        <div class="member-info-bar d-none d-xl-flex flex-column h-100 p-4 border-start" id="member-sidebar" style="background-color: #fafbfc; width: 300px;">
             <div class="text-center">
                <div class="avatar-lg-circle mx-auto mb-3">
                    <div class="rounded-circle bg-accent d-flex align-items-center justify-content-center text-white fw-bold fs-2" style="width: 90px; height: 90px;" id="member-avatar-text">?</div>
                </div>
                <h5 class="fw-bold text-dark mb-1" id="member-name-sidebar">Pilih Teman</h5>
                <p class="text-muted small mb-4">Anggota KAMCUP</p>
                <div class="dropdown mb-4" id="chat-actions-dropdown">
                    <!-- JS Inject context menu (Unfriend, etc) -->
                </div>
                <hr>
                <div class="text-start mt-4">
                    <h6 class="x-small fw-bold text-muted text-uppercase mb-2">Catatan</h6>
                    <textarea class="form-control bg-white border x-small p-2 rounded-3 mb-4" rows="3" placeholder="Tambah catatan..."></textarea>
                    
                    <h6 class="x-small fw-bold text-muted text-uppercase mb-2">Tentang</h6>
                    <p class="text-muted x-small">Pemain KAMCUP yang siap bertanding!</p>
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
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userId = {{ Auth::id() }};
        const searchInput = document.getElementById('user-search-input');
        const searchResults = document.getElementById('search-results-container');
        const welcomeView = document.getElementById('welcome-view');
        const chatView = document.getElementById('chat-view');
        const requestsView = document.getElementById('requests-view');
        const searchView = document.getElementById('search-view');
        const chatBox = document.getElementById('chat-box');
        const chatInput = document.getElementById('chat-input');
        const headerName = document.getElementById('chat-friend-name');
        
        let currentFriendId = null;
        let searchTimeout = null;

        // --- NAVIGATION ---
        window.closeChat = function() {
            currentFriendId = null;
            chatView.classList.add('d-none');
            welcomeView.classList.remove('d-none');
            requestsView.classList.add('d-none');
            searchView.classList.add('d-none');
            headerName.innerText = 'Teman';
            document.getElementById('member-sidebar').style.opacity = '0.5';
        };

        window.showRequests = function() {
            welcomeView.classList.remove('d-none');
            requestsView.classList.remove('d-none');
            searchView.classList.add('d-none');
            chatView.classList.add('d-none');
            headerName.innerText = 'Permintaan Pertemanan';
        };

        // --- SEARCH ---
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            if (query.length < 3) return;

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                showSearchDashboard(query);
            }, 500);
        });

        async function showSearchDashboard(query) {
            welcomeView.classList.remove('d-none');
            searchView.classList.remove('d-none');
            requestsView.classList.add('d-none');
            chatView.classList.add('d-none');
            
            searchResults.innerHTML = '<div class="col-12 text-center p-5"><div class="spinner-border text-accent"></div></div>';
            
            try {
                const response = await fetch(`/user2026/teman/search?query=${encodeURIComponent(query)}`);
                const users = await response.json();
                renderResults(users);
            } catch (e) { console.error(e); }
        }

        function renderResults(users) {
            if (users.length === 0) {
                searchResults.innerHTML = '<div class="col-12 text-center p-5 text-muted">Tidak ada user ditemukan.</div>';
                return;
            }
            searchResults.innerHTML = users.map(u => `
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-4 text-center border h-100 transition-all">
                        <div class="avatar-md mx-auto mb-2" style="width: 50px; height: 50px; background: var(--teman-accent); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-weight:bold;"> ${u.name[0].toUpperCase()} </div>
                        <div class="fw-bold text-dark small">${u.name}</div>
                        <div class="text-muted x-small mb-3">ID: #${u.id}</div>
                        ${getResButton(u)}
                    </div>
                </div>
            `).join('');
        }

        function getResButton(u) {
            if (u.friendship_status === 'none') return `<button onclick="addFriend(${u.id})" class="btn btn-accent btn-sm w-100 rounded-pill x-small">Tambah Teman</button>`;
            return `<button class="btn btn-outline-secondary btn-sm w-100 rounded-pill x-small disabled">${u.friendship_status}</button>`;
        }

        window.addFriend = async function(id) {
            try {
                await fetch(`/user2026/teman/${id}/add`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                alert('Permintaan Terkirim!');
                showSearchDashboard(searchInput.value);
            } catch(e) {}
        };

        // --- CHAT ---
        window.openChat = async function(id, name) {
            currentFriendId = id;
            welcomeView.classList.add('d-none');
            chatView.classList.remove('d-none');
            headerName.innerText = name;
            chatInput.placeholder = `Kirim pesan ke @${name}`;
            
            // Highlight active friend in sidebar
            document.querySelectorAll('.friend-item-sidebar').forEach(el => el.classList.remove('bg-white', 'shadow-sm'));
            const items = document.querySelectorAll('.friend-item-sidebar');
            items.forEach(item => {
                if(item.innerText.includes(name)) item.classList.add('bg-white', 'shadow-sm');
            });

            // Side info update
            document.getElementById('member-sidebar').style.opacity = '1';
            document.getElementById('member-name-sidebar').innerText = name;
            document.getElementById('member-avatar-text').innerText = name[0].toUpperCase();
            
            document.getElementById('chat-actions-dropdown').innerHTML = `
                <form action="/user2026/teman/${id}/remove" method="POST" onsubmit="return confirm('Hapus teman?')">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-sm btn-outline-danger px-3 rounded-pill h6 mb-0">Hapus Teman</button>
                </form>
            `;

            const msgContainer = document.getElementById('chat-messages-inner');
            msgContainer.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-accent"></div></div>';
            
            try {
                const res = await fetch(`/user2026/teman/${id}/messages`);
                const msgs = await res.json();
                msgContainer.innerHTML = '';
                msgs.forEach(m => appendMsg(m));
                chatBox.scrollTop = chatBox.scrollHeight;
            } catch(e) {}
        };

        function appendMsg(m) {
            const isMe = m.sender_id === userId;
            const container = document.getElementById('chat-messages-inner');
            const item = document.createElement('div');
            item.className = 'chat-msg-container animate-fade-in mb-2';
            
            const time = new Date(m.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const senderName = m.sender_id == userId ? '{{ Auth::user()->name }}' : headerName.innerText;
            const senderInitial = senderName[0].toUpperCase();
            
            item.innerHTML = `
                <div class="avatar-sm">
                    <div class="rounded-circle ${isMe ? 'bg-pink-light text-pink' : 'bg-accent-light text-accent'} d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; font-size: 0.8rem;"> ${senderInitial} </div>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center mb-1">
                        <span class="chat-msg-author small ${isMe ? 'text-pink' : 'text-accent'}">${senderName}</span>
                        <span class="chat-msg-time ms-2">${time}</span>
                    </div>
                    <div class="chat-msg-text">${m.message}</div>
                </div>
            `;
            container.appendChild(item);
        }

        document.getElementById('chat-form').onsubmit = async (e) => {
            e.preventDefault();
            const text = chatInput.value.trim();
            if (!text || !currentFriendId) return;
            chatInput.value = '';

            // Optimistic update — show message instantly
            const now = new Date();
            appendMsg({
                sender_id: userId,
                message: text,
                created_at: now.toISOString()
            });
            chatBox.scrollTop = chatBox.scrollHeight;
            
            try {
                const res = await fetch(`/user2026/teman/${currentFriendId}/messages`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ message: text })
                });
                if (!res.ok) {
                    console.error('Failed to send message:', res.status);
                }
            } catch(e) {
                console.error('Error sending message:', e);
            }
        };

        if (typeof Echo !== 'undefined') {
            Echo.private(`user.${userId}`)
                .listen('PrivateMessageSent', (e) => {
                    if (currentFriendId && e.message.sender_id === currentFriendId) {
                        appendMsg(e.message);
                        chatBox.scrollTop = chatBox.scrollHeight;
                    }
                });
        }
    });
</script>
@endpush
