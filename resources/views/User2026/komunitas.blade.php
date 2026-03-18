@extends('layouts.master_nav')

@section('title', 'Komunitas')

@section('content')
<div class="komunitas-wrapper">

    <!-- Sidebar -->
    <aside class="komunitas-sidebar">
        <div class="sidebar-header">
            <h2 class="sidebar-title">Jelajahi</h2>
        </div>

        <!-- Search -->
        <div class="sidebar-search">
            <div class="search-wrap">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="communitySearch" placeholder="Cari komunitas..." class="search-input">
            </div>
        </div>

        <!-- Categories -->
        <div class="sidebar-section">
            <p class="sidebar-label">KATEGORI</p>
            <nav class="category-nav">
                <a href="{{ route('user2026.komunitas') }}" 
                   class="category-item {{ !$category || $category == 'Semua' ? 'active' : '' }}">
                    <span class="category-icon"><i class="fas fa-border-all"></i></span>
                    <span>Semua</span>
                </a>
                @foreach(['Voli', 'Futsal', 'Bulutangkis', 'Tenis meja', 'Kasti'] as $cat)
                    @php
                        $icons = [
                            'Voli' => 'fa-volleyball-ball',
                            'Futsal' => 'fa-futbol',
                            'Bulutangkis' => 'fa-shuttle-space',
                            'Tenis meja' => 'fa-table-tennis-paddle-ball',
                            'Kasti' => 'fa-baseball',
                        ];
                        $icon = $icons[$cat] ?? 'fa-circle';
                    @endphp
                    <a href="{{ route('user2026.komunitas', ['category' => $cat]) }}" 
                       class="category-item {{ $category == $cat ? 'active' : '' }}">
                        <span class="category-icon"><i class="fas {{ $icon }}"></i></span>
                        <span>{{ $cat }}</span>
                    </a>
                @endforeach
            </nav>
        </div>

        <!-- Create Button -->
        <div class="sidebar-footer">
            <a href="{{ route('user2026.komunitas.create') }}" class="create-btn">
                <i class="fas fa-plus"></i>
                <span>Buat Komunitas</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="komunitas-main">

        <!-- Top Bar -->
        <div class="main-topbar">
            <div class="topbar-left">
                <h1 class="topbar-title">
                    {{ $category && $category !== 'Semua' ? $category : 'Semua Komunitas' }}
                </h1>
                @php $total = $communities->count(); @endphp
                <span class="topbar-count">{{ $total }} komunitas</span>
            </div>
        </div>

        <!-- Back Button -->
        <div class="back-button mb-4">
            <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary btn-lg shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Profile
            </a>
        </div>

        @php
            $joined = $communities->filter(fn($c) => $c->members->contains(Auth::id()));
            $notJoined = $communities->filter(fn($c) => !$c->members->contains(Auth::id()));
        @endphp

        <!-- Joined Communities -->
        @if($joined->isNotEmpty())
        <section class="community-section">
            <div class="section-header">
                <i class="fas fa-check-circle" style="color: #2ecc71;"></i>
                <h3>Komunitas Saya</h3>
                <span class="section-count">{{ $joined->count() }}</span>
            </div>
            <div class="community-grid">
                @foreach($joined as $index => $community)
                    @include('User2026.partials.community_card', ['community' => $community, 'index' => $index, 'isJoined' => true])
                @endforeach
            </div>
        </section>
        <div class="section-divider"></div>
        @endif

        <!-- Other Communities -->
        <section class="community-section">
            <div class="section-header">
                <i class="fas fa-compass" style="color: #cb2786;"></i>
                <h3>{{ $joined->isNotEmpty() ? 'Cari Komunitas Lain' : 'Temukan Komunitas' }}</h3>
                @if($notJoined->isNotEmpty())
                    <span class="section-count">{{ $notJoined->count() }}</span>
                @endif
            </div>

            @if($notJoined->isNotEmpty())
                <div class="community-grid">
                    @foreach($notJoined as $index => $community)
                        @include('User2026.partials.community_card', ['community' => $community, 'index' => $index, 'isJoined' => false])
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-search"></i></div>
                    <p class="empty-title">Tidak ada komunitas ditemukan</p>
                    <p class="empty-sub">Coba kategori lain atau buat komunitas baru</p>
                </div>
            @endif
        </section>

        @if($communities->isEmpty())
        <div class="empty-state full">
            <div class="empty-icon"><i class="fas fa-users"></i></div>
            <p class="empty-title">Belum ada komunitas</p>
            <p class="empty-sub">Jadilah yang pertama membuat komunitas di KAMCUP!</p>
            <a href="{{ route('user2026.komunitas.create') }}" class="create-btn inline">
                <i class="fas fa-plus"></i> Buat Komunitas
            </a>
        </div>
        @endif

    </main>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
<style>
.navbar, footer { display: none !important; }
.content { padding-top: 0 !important; }
.main-wrapper { min-height: 100vh; }
.komunitas-wrapper { min-height: 100vh; }
</style>
<style>
/* ── Layout ──────────────────────────────────────────────── */
.komunitas-wrapper {
    display: flex;
    height: calc(100vh - 56px);
    overflow: hidden;
    background: #f0f2f5;
    font-family: 'Segoe UI', system-ui, sans-serif;
}

/* ── Sidebar ─────────────────────────────────────────────── */
.komunitas-sidebar {
    width: 260px;
    min-width: 260px;
    background: #ffffff;
    border-right: 1px solid #e8eaed;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    overflow-x: hidden;
}

.sidebar-header {
    padding: 20px 16px 8px;
}

.sidebar-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1a1a2e;
    margin: 0;
}

.sidebar-search {
    padding: 8px 12px 12px;
}

.search-wrap {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
    font-size: 0.8rem;
}

.search-input {
    width: 100%;
    background: #f0f2f5;
    border: none;
    border-radius: 8px;
    padding: 8px 12px 8px 32px;
    font-size: 0.875rem;
    color: #333;
    outline: none;
    transition: background 0.2s;
}

.search-input:focus {
    background: #e8eaed;
}

.sidebar-section {
    padding: 0 8px;
    flex: 1;
}

.sidebar-label {
    font-size: 0.7rem;
    font-weight: 700;
    color: #999;
    letter-spacing: 1px;
    padding: 8px 8px 4px;
    margin: 0;
}

.category-nav {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.category-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 10px;
    border-radius: 8px;
    color: #555;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.15s ease;
    cursor: pointer;
}

.category-item:hover {
    background: #f8f0f6;
    color: #cb2786;
    text-decoration: none;
}

.category-item.active {
    background: #cb278615;
    color: #cb2786;
    font-weight: 600;
}

.category-icon {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    background: #f0f2f5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    color: #666;
    flex-shrink: 0;
    transition: all 0.15s;
}

.category-item.active .category-icon,
.category-item:hover .category-icon {
    background: #cb278620;
    color: #cb2786;
}

.sidebar-footer {
    padding: 12px;
    border-top: 1px solid #e8eaed;
    margin-top: auto;
}

.create-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 10px 16px;
    background: #cb2786;
    color: #fff !important;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
    border: none;
}

.create-btn:hover {
    background: #a81e6e;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(203,39,134,0.3);
    text-decoration: none;
    color: #fff !important;
}

/* ── Main ────────────────────────────────────────────────── */
.komunitas-main {
    flex: 1;
    overflow-y: auto;
    padding: 24px 28px;
}

.main-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
}

.topbar-left {
    display: flex;
    align-items: baseline;
    gap: 10px;
}

.topbar-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #1a1a2e;
    margin: 0;
}

.topbar-count {
    font-size: 0.8rem;
    color: #999;
    font-weight: 500;
    background: #f0f2f5;
    padding: 2px 8px;
    border-radius: 20px;
}

/* ── Section ─────────────────────────────────────────────── */
.community-section {
    margin-bottom: 24px;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 14px;
}

.section-header h3 {
    font-size: 0.85rem;
    font-weight: 700;
    color: #555;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin: 0;
}

.section-count {
    font-size: 0.75rem;
    background: #f0f2f5;
    color: #888;
    padding: 1px 7px;
    border-radius: 20px;
    font-weight: 600;
}

.section-divider {
    height: 1px;
    background: #e8eaed;
    margin: 8px 0 28px;
}

/* ── Grid ────────────────────────────────────────────────── */
.community-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 12px;
}

/* ── Empty State ─────────────────────────────────────────── */
.empty-state {
    text-align: center;
    padding: 48px 24px;
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e8eaed;
}

.empty-icon {
    font-size: 2.5rem;
    color: #ddd;
    margin-bottom: 12px;
}

.empty-title {
    font-weight: 600;
    color: #555;
    margin: 0 0 4px;
}

.empty-sub {
    font-size: 0.875rem;
    color: #999;
    margin: 0;
}

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 768px) {
    .komunitas-wrapper {
        flex-direction: column;
        height: auto;
    }

    .komunitas-sidebar {
        width: 100%;
        min-width: unset;
        border-right: none;
        border-bottom: 1px solid #e8eaed;
        max-height: 220px;
    }

    .category-nav {
        flex-direction: row;
        flex-wrap: nowrap;
        overflow-x: auto;
        padding-bottom: 4px;
    }

    .category-item {
        white-space: nowrap;
        flex-shrink: 0;
    }

    .komunitas-main {
        padding: 16px;
    }

    .community-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('communitySearch');
    if (!searchInput) return;

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        document.querySelectorAll('.community-card-item').forEach(card => {
            const name = card.dataset.name || '';
            const category = card.dataset.category || '';
            const match = name.includes(query) || category.includes(query);
            card.closest('.community-grid > div') 
                ? card.closest('.community-grid > div').style.display = match ? '' : 'none'
                : card.style.display = match ? '' : 'none';
        });
    });
});
</script>
@endpush