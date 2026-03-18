@extends('layouts.master_nav')

@section('title', '{{ $title ?? "Feeds" }} - KAMCUP')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
@if(request()->routeIs('user2026.feeds'))
<style>
.navbar, footer { display: none !important; }
.content { padding-top: 0 !important; }
.main-wrapper { min-height: 100vh; }
</style>
@endif

<style>
/* ── Layout ──────────────────────────────────────────────── */
.feeds-wrapper { 
    display: flex;
    height: calc(100vh - 56px);
    overflow: hidden;
    background: #f0f2f5;
    font-family: 'Segoe UI', system-ui, sans-serif;
}

/* ── Sidebar ─────────────────────────────────────────────── */
.feeds-sidebar {
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

/* ── Main ────────────────────────────────────────────────── */
.feeds-main {
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

.back-button {
    margin-bottom: 24px;
}

.feeds-section {
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

.section-divider {
    height: 1px;
    background: #e8eaed;
    margin: 24px 0;
}

/* ── Grid ────────────────────────────────────────────────── */
.feeds-grid {
    max-width: 680px;
    margin: 0 auto;
    padding: 0 1rem 3rem;
}

.feeds-grid > .feed-card {
    margin-bottom: 1.25rem;
}

.feeds-pagination {
    margin-top: 1.5rem;
    display: flex;
    justify-content: center;
}

/* ── Empty ───────────────────────────────────────────────── */
.empty-state {
    text-align: center;
    padding: 48px 24px;
    background: #fff;
    border-radius: 18px;
    border: 1px solid #e8ecef;
}

/* ── Feed Card ── */
.feed-card {
    background: #fff;
    border-radius: 18px;
    margin-bottom: 1.25rem;
    border: 1px solid #e8ecef;
    overflow: hidden;
    transition: box-shadow 0.25s ease, transform 0.25s ease;
    animation: fadeSlideUp 0.4s ease both;
}
.feed-card:hover { 
    box-shadow: 0 8px 30px rgba(0,0,0,0.09); 
    transform: translateY(-2px); 
}
@keyframes fadeSlideUp { 
    from { opacity: 0; transform: translateY(18px); } 
    to { opacity: 1; transform: translateY(0); } 
}

.meets-badge {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #fff;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    box-shadow: 0 2px 8px rgba(16,185,129,0.3);
}

.meets-info {
    background: linear-gradient(135deg, rgba(16,185,129,0.05), rgba(5,150,105,0.05));
    border: 1px solid rgba(16,185,129,0.15);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    margin: 0.75rem 0;
}

.meets-date { font-weight: 600; color: #059669; margin-bottom: 0.25rem; }
.meets-details { font-size: 0.85rem; color: #6b7280; }
.btn-ikut-group { display: flex; gap: 0.5rem; align-items: center; flex: 1; justify-content: flex-end; }
.btn-ikut {
    flex: 1;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: #fff;
    border: none;
    padding: 0.4rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.82rem;
    transition: all 0.2s;
    white-space: nowrap;
}
.btn-ikut:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59,130,246,0.4); color: #fff; }
.btn-ikut.joined { background: linear-gradient(135deg, #eab308, #ca8a04); }
.btn-ikut:disabled { opacity: 0.6; cursor: not-allowed; }
.meets-full { background: linear-gradient(135deg, #ef4444, #dc2626) !important; }
.meets-count { font-size: 0.8rem; color: #6b7280; font-weight: 500; white-space: nowrap; }
.feed-card-header { display: flex; align-items: center; gap: 0.75rem; padding: 1rem 1.25rem 0.75rem; }
.user-avatar { width: 42px; height: 42px; border-radius: 50%; object-fit: cover; flex-shrink: 0; background: #e0e0e0; }
.feed-brand-avatar { width: 42px; height: 42px; background: linear-gradient(135deg, #cb2786, #00617a); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-family: 'Sora', sans-serif; font-size: 0.9rem; flex-shrink: 0; }
.feed-brand-info .brand-name { font-family: 'Sora', sans-serif; font-size: 0.9rem; font-weight: 700; color: #0d1117; margin: 0; line-height: 1.2; }
.feed-brand-info .feed-time { font-size: 0.78rem; color: #9ca3af; margin: 0; }
.feed-badge { margin-left: auto; font-size: 0.7rem; font-weight: 600; background: rgba(203,39,134,0.1); color: #cb2786; padding: 0.2rem 0.65rem; border-radius: 50px; letter-spacing: 0.3px; }
.feed-img-wrap { position: relative; overflow: hidden; }
.feed-img-wrap img { width: 100%; max-height: 420px; object-fit: cover; display: block; transition: transform 0.4s ease; }
.feed-card:hover .feed-img-wrap img { transform: scale(1.02); }
.feed-body { padding: 1rem 1.25rem 0.5rem; }
.feed-title { font-family: 'Sora', sans-serif; font-size: 1.05rem; font-weight: 700; color: #00617a; margin-bottom: 0.5rem; line-height: 1.35; letter-spacing: -0.2px; }
.feed-content { font-size: 0.92rem; color: #374151; line-height: 1.65; margin: 0; }
.feed-action-bar {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.6rem 0.75rem;
    border-top: 1px solid #f3f4f6;
    margin-top: 0.75rem;
}
.feed-action-bar.meets-action-bar { justify-content: space-between; }
.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: none;
    border: none;
    padding: 0.5rem 0.85rem;
    border-radius: 50px;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
    font-size: 0.85rem;
    font-weight: 500;
    color: #6b7280;
    font-family: 'DM Sans', sans-serif;
}
.action-btn i { font-size: 1rem; }
.action-btn:hover { background-color: #fdf2f8; color: #cb2786; }
.action-btn.liked { color: #cb2786; background-color: #fdf2f8; }
.action-btn-share { margin-left: auto; }
.feeds-empty {
    text-align: center;
    padding: 4rem 1rem;
    background: #fff;
    border-radius: 18px;
    border: 1px solid #e8ecef;
}
.feeds-empty i { font-size: 3rem; color: #d1d5db; margin-bottom: 1rem; display: block; }
.feeds-empty h3 { font-family: 'Sora', sans-serif; font-size: 1.1rem; color: #374151; margin-bottom: 0.5rem; }
.feeds-empty p { font-size: 0.88rem; color: #9ca3af; margin: 0; }

/* Avatar fallback */
.avatar-fallback {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg, #cb2786, #00617a);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-family: 'Sora', sans-serif;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.modal-content { border: none; border-radius: 20px; overflow: hidden; }
.modal-dialog { max-width: 760px; }

/* Responsive */
@media (max-width: 768px) {
    .feeds-wrapper {
        flex-direction: column;
        height: auto;
    }
    .feeds-sidebar {
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
    .feeds-main {
        padding: 16px;
    }
    .modal-split { flex-direction: column; }
}
</style>
@endpush

@section('content')
<div class="feeds-wrapper">
    <!-- Sidebar -->
    <aside class="feeds-sidebar">
        <div class="sidebar-header">
            <h2 class="sidebar-title">Feeds</h2>
        </div>

        <!-- Search -->
        <div class="sidebar-search">
            <div class="search-wrap">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="feedSearch" placeholder="Cari feeds..." class="search-input">
            </div>
        </div>

        <!-- Filters -->
        <div class="sidebar-section">
            <p class="sidebar-label">FILTER</p>
            <nav class="category-nav">
                <a href="{{ route('user2026.feeds') }}" 
                   class="category-item {{ $filter == 'all' ? 'active' : '' }}">
                    <span class="category-icon"><i class="fas fa-border-all"></i></span>
                    <span>Semua</span>
                </a>
                <a href="{{ route('user2026.feeds', ['filter' => 'feeds']) }}" 
                   class="category-item {{ $filter == 'feeds' ? 'active' : '' }}">
                    <span class="category-icon"><i class="fas fa-rss"></i></span>
                    <span>Feeds</span>
                </a>
                <a href="{{ route('user2026.feeds', ['filter' => 'meets']) }}" 
                   class="category-item {{ $filter == 'meets' ? 'active' : '' }}">
                    <span class="category-icon"><i class="fas fa-calendar-plus"></i></span>
                    <span>Meets</span>
                </a>
            </nav>
        </div>

        <!-- Create Button -->
        <div class="sidebar-footer">
            <button class="create-btn btn-create-meets" data-bs-toggle="modal" data-bs-target="#create-meets-modal">
                <i class="fas fa-calendar-plus"></i>
                <span>Buat Meets</span>
            </button>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="feeds-main">
        <!-- Top Bar -->
        <div class="main-topbar">
            <div class="topbar-left">
                <h1 class="topbar-title">{{ $title }}</h1>
                <span class="topbar-count">{{ $feedCount }} feeds</span>
            </div>
        </div>

        <!-- Back Button -->
        <div class="back-button">
            <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary btn-lg shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Profile
            </a>
        </div>

        <!-- Feeds Section -->
        <section class="feeds-section">
            <div class="section-header">
                @if($filter == 'all')
                    <i class="fas fa-rss" style="color: #cb2786;"></i>
                @elseif($filter == 'feeds')
                    <i class="fas fa-newspaper" style="color: #00617a;"></i>
                @else
                    <i class="fas fa-calendar-plus" style="color: #10b981;"></i>
                @endif
                <h3>{{ $title }} Feeds</h3>
            </div>

            @if($feeds->isNotEmpty())
            <div class="feeds-grid" id="feeds-stream">
                @forelse ($feeds as $feed)
                <article class="feed-card" 
                         id="feed-{{ $feed->id }}" 
                         data-feed-id="{{ $feed->id }}"
                         data-title="{{ strtolower($feed->title ?? $feed->content ?? $feed->meet_description ?? '') }}"
                         data-type="{{ $feed->meet_date ? 'meets' : 'feeds' }}">

                    <div class="feed-card-header">
                        @if($feed->user)
                            @if($feed->user->profile?->avatar)
                                {{-- Avatar ada di storage, tampilkan. Kalau gagal load, sembunyikan saja --}}
                                <img src="{{ asset('storage/' . $feed->user->profile->avatar) }}" 
                                     alt="{{ $feed->user->name }}" 
                                     class="user-avatar"
                                     onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="avatar-fallback" style="display:none;">
                                    {{ strtoupper(substr($feed->user->name, 0, 1)) }}
                                </div>
                            @else
                                {{-- Tidak ada avatar, langsung tampilkan inisial --}}
                                <div class="avatar-fallback">
                                    {{ strtoupper(substr($feed->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="feed-brand-info">
                                <p class="brand-name">{{ $feed->user->name }}</p>
                                <p class="feed-time">{{ $feed->created_at->diffForHumans() }}</p>
                            </div>
                        @else
                            <div class="feed-brand-avatar">KC</div>
                            <div class="feed-brand-info">
                                <p class="brand-name">KAMCUP</p>
                                <p class="feed-time">{{ $feed->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="feed-badge">Official</span>
                        @endif
                        @if($feed->meet_date)
                            <span class="meets-badge">MEETS</span>
                        @endif
                    </div>

                    @if($feed->image)
                    <div class="feed-img-wrap">
                        <img src="{{ asset('storage/' . $feed->image) }}" 
                             alt="{{ $feed->title ?? 'Meets' }}" 
                             loading="lazy"
                             onerror="this.onerror=null; this.closest('.feed-img-wrap').style.display='none';">
                    </div>
                    @endif

                    <div class="feed-body">
                        @if($feed->title)
                        <h2 class="feed-title">{{ $feed->title }}</h2>
                        @endif
                        @if($feed->meet_date)
                            <div class="meets-info">
                                <div class="meets-date">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ \Carbon\Carbon::parse($feed->meet_date)->translatedFormat('d F Y') }} • {{ $feed->meet_location }}
                                </div>
                                <div class="meets-details">
                                    <i class="fas fa-users me-1"></i>
                                    Max {{ $feed->meet_max_people }} orang • {{ $feed->joins_count ?? 0 }} sudah ikut
                                </div>
                            </div>
                            @if($feed->meet_description)
                            <p class="feed-content">{{ $feed->meet_description }}</p>
                            @endif
                        @else
                            @if($feed->content)
                            <p class="feed-content">{{ $feed->content }}</p>
                            @endif
                        @endif
                    </div>

                    <div class="feed-action-bar {{ $feed->meet_date ? 'meets-action-bar' : '' }}">
                        <button class="action-btn like-btn {{ $feed->current_user_liked ? 'liked' : '' }}" data-feed-id="{{ $feed->id }}" title="Suka">
                            <i class="{{ $feed->current_user_liked ? 'fas' : 'far' }} fa-heart"></i>
                            <span class="like-count">{{ $feed->likes_count }}</span>
                        </button>

                        <button class="action-btn comment-toggle" data-bs-toggle="modal" data-bs-target="#feedCommentModal"
                                data-feed-id="{{ $feed->id }}" data-feed-title="{{ $feed->title }}" data-feed-image="{{ $feed->image }}" title="Komentar">
                            <i class="far fa-comment"></i>
                            <span class="comment-count">{{ $feed->comments_count }}</span>
                        </button>

                        <button class="action-btn action-btn-share share-btn" data-url="{{ route('user2026.feeds') }}#feed-{{ $feed->id }}" title="Bagikan">
                            <i class="fas fa-share-nodes"></i>
                        </button>

                        @if($feed->meet_date)
                            <div class="btn-ikut-group">
                                <span class="meets-count">{{ $feed->joins_count ?? 0 }} ikut</span>
                                <button class="btn-ikut {{ $feed->current_user_joined ? 'joined' : '' }}"
                                        data-feed-id="{{ $feed->id }}"
                                        {{ ($feed->meet_max_people && ($feed->joins_count ?? 0) >= $feed->meet_max_people) ? 'disabled title="Penuh"' : '' }}>
                                    @if($feed->current_user_joined)
                                        <i class="fas fa-check me-1"></i>Batal Ikut
                                    @else
                                        <i class="fas fa-user-plus me-1"></i>Ikut
                                    @endif
                                </button>
                            </div>
                        @endif
                    </div>
                </article>
                @empty
                @endforelse
            </div>

            <div class="feeds-pagination">
                {{ $feeds->appends(request()->query())->links() }}
            </div>
            @else
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-rss fa-3x"></i></div>
                <p class="empty-title">Belum ada {{ strtolower($title) }}</p>
                <p class="empty-sub">Buat yang pertama atau ganti filter!</p>
                <button class="btn-create-meets mt-4" data-bs-toggle="modal" data-bs-target="#create-meets-modal">
                    <i class="fas fa-plus me-2"></i> Buat Meets
                </button>
            </div>
            @endif
        </section>
    </main>
</div>

{{-- CREATE MEETS MODAL --}}
<div class="modal fade" id="create-meets-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-calendar-plus text-primary me-2"></i>Buat Meets Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="create-meets-form">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Judul Meets <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required maxlength="255" placeholder="Ex: Latihan Volleyball Malam ini">
                        <div class="form-text">Judul singkat dan menarik</div>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Meets <span class="text-danger">*</span></label>
                            <input type="date" name="meet_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Lokasi Meets <span class="text-danger">*</span></label>
                            <input type="text" name="meet_location" class="form-control" required placeholder="Ex: Lapangan ABC">
                        </div>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah Maks Orang <span class="text-danger">*</span></label>
                            <input type="number" name="meet_max_people" class="form-control" required min="2" max="1000" placeholder="12">
                            <div class="form-text">Min 2 orang</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Foto (Opsional)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <div class="form-text">Maks 2MB</div>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="meet_description" class="form-control" required rows="4" maxlength="2000" placeholder="Detail kegiatan, persyaratan, kontak WA, dll..."></textarea>
                        <div class="form-text small">Semua field wajib (*) - Meets akan tampil di feeds semua user</div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-5" id="submit-meets-btn">
                        <i class="fas fa-calendar-plus me-2"></i>Posting Meets
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- COMMENT MODAL --}}
<div class="modal fade" id="feedCommentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Comment modal structure unchanged -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/locale/id.min.js"></script>
<script>
moment.locale('id');
$(document).ready(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // Feed Search
    const searchInput = document.getElementById('feedSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            document.querySelectorAll('.feed-card').forEach(card => {
                const title = card.dataset.title || '';
                const type = card.dataset.type || '';
                const match = title.includes(query) || type.includes(query);
                card.style.display = match ? '' : 'none';
            });
        });
    }

    // Create Meets form
    $('#create-meets-form').on('submit', function (e) {
        e.preventDefault();
        const $form = $(this);
        const $btn = $('#submit-meets-btn');
        const original = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Posting...');
        
        const formData = new FormData(this);
        $.ajax({
            url: '{{ route("user2026.feeds.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: (resp) => {
                $('#create-meets-modal').modal('hide');
                $form[0].reset();
                showToast('Meets berhasil diposting!', 'success');
                const newHtml = buildFeedHtml(resp.feed);
                $('#feeds-stream').prepend(newHtml).scrollTop(0);
            },
            error: (xhr) => showToast(xhr.responseJSON?.message || 'Gagal membuat meets', 'error'),
            complete: () => $btn.prop('disabled', false).html(original)
        });
    });

    // Join meets
    $(document).on('click', '.btn-ikut:not(:disabled)', function () {
        const $btn = $(this);
        const feedId = $btn.data('feed-id');
        const $group = $btn.closest('.btn-ikut-group');
        const $count = $group.find('.meets-count');
        $btn.prop('disabled', true);
        
        $.post(`/feeds/${feedId}/join`)
            .done((data) => {
                $btn.toggleClass('joined', data.joined)
                     .html(data.joined ? '<i class="fas fa-check me-1"></i>Batal Ikut' : '<i class="fas fa-user-plus me-1"></i>Ikut');
                $count.text(data.count + ' ikut');
                showToast(data.message, data.joined ? 'success' : 'info');
            })
            .fail((xhr) => showToast(xhr.responseJSON?.message || 'Gagal', 'error'))
            .always(() => $btn.prop('disabled', false));
    });

    // Like
    $(document).on('click', '.like-btn', function () {
        const $btn = $(this);
        const $icon = $btn.find('i');
        const $count = $btn.find('.like-count');
        const feedId = $btn.data('feed-id');

        $.post(`/feeds/${feedId}/like`)
            .done((data) => {
                $btn.toggleClass('liked', data.liked);
                $icon.toggleClass('far fas', data.liked);
                $count.text(data.count);
            })
            .fail(() => showToast('Gagal suka/unlike', 'error'));
    });

    // Toast
    function showToast(msg, type = 'info') {
        const colors = { success: '#10b981', error: '#ef4444', info: '#3b82f6' };
        $('<div class="toast-notification">' + msg + '</div>').css({
            position: 'fixed', bottom: '2rem', left: '50%', transform: 'translateX(-50%)',
            background: colors[type], color: 'white', padding: '.75rem 1.5rem', borderRadius: '50px',
            fontWeight: 600, zIndex: 9999, boxShadow: '0 8px 25px rgba(0,0,0,.2)'
        }).appendTo('body').fadeOut(3500);
    }

    // Build feed card HTML — FIXED: tidak pakai default-avatar.svg, pakai inisial sebagai fallback
    function buildFeedHtml(feed) {
        const user = feed.user || null;
        const hasUser = !!user;
        const timeAgo = moment(feed.created_at).fromNow();
        const isMeets = !!feed.meet_date;
        const joined = feed.current_user_joined;
        const liked = feed.current_user_liked;
        const isFull = feed.meet_max_people && feed.joins_count >= feed.meet_max_people;

        let headerHtml = '';
        if (hasUser) {
            const initial = user.name ? user.name.charAt(0).toUpperCase() : '?';
            if (user.profile?.avatar) {
                // Ada avatar — tampilkan img, kalau gagal load sembunyikan dan tampilkan fallback inisial
                headerHtml = `
                    <img src="/storage/${user.profile.avatar}" alt="${user.name}" class="user-avatar"
                         onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="avatar-fallback" style="display:none;">${initial}</div>`;
            } else {
                // Tidak ada avatar — langsung inisial, TIDAK request gambar apapun
                headerHtml = `<div class="avatar-fallback">${initial}</div>`;
            }
            headerHtml += `
                <div class="feed-brand-info">
                    <p class="brand-name">${user.name}</p>
                    <p class="feed-time">${timeAgo}</p>
                </div>`;
        } else {
            headerHtml = `
                <div class="feed-brand-avatar">KC</div>
                <div class="feed-brand-info">
                    <p class="brand-name">KAMCUP</p>
                    <p class="feed-time">${timeAgo}</p>
                </div>
                <span class="feed-badge">Official</span>`;
        }

        if (isMeets) headerHtml += '<span class="meets-badge">MEETS</span>';

        // Feed image — hanya kalau ada, kalau gagal load sembunyikan wrapper
        let imageHtml = feed.image 
            ? `<div class="feed-img-wrap">
                 <img src="/storage/${feed.image}" alt="${feed.title || 'Meets'}" loading="lazy"
                      onerror="this.onerror=null; this.closest('.feed-img-wrap').style.display='none';">
               </div>` 
            : '';

        let bodyHtml = '';
        if (feed.title) bodyHtml += `<h2 class="feed-title">${feed.title}</h2>`;
        
        if (isMeets) {
            bodyHtml += `
                <div class="meets-info">
                    <div class="meets-date">
                        <i class="fas fa-calendar me-1"></i>
                        ${moment(feed.meet_date).format('DD MMMM YYYY')} • ${feed.meet_location}
                    </div>
                    <div class="meets-details">
                        <i class="fas fa-users me-1"></i>
                        Max ${feed.meet_max_people} orang • ${feed.joins_count || 0} sudah ikut
                    </div>
                </div>`;
            if (feed.meet_description) bodyHtml += `<p class="feed-content">${feed.meet_description}</p>`;
        } else if (feed.content) {
            bodyHtml += `<p class="feed-content">${feed.content}</p>`;
        }

        const joinBtnText = joined ? '<i class="fas fa-check me-1"></i>Batal Ikut' : '<i class="fas fa-user-plus me-1"></i>Ikut';
        const joinBtnClass = joined ? 'joined' : '';
        const joinDisabled = isFull ? 'disabled title="Penuh"' : '';

        let actionHtml = `
            <button class="action-btn like-btn ${liked ? 'liked' : ''}" data-feed-id="${feed.id}" title="Suka">
                <i class="${liked ? 'fas' : 'far'} fa-heart"></i>
                <span class="like-count">${feed.likes_count || 0}</span>
            </button>
            <button class="action-btn comment-toggle" data-bs-toggle="modal" data-bs-target="#feedCommentModal"
                    data-feed-id="${feed.id}" data-feed-title="${feed.title || ''}" title="Komentar">
                <i class="far fa-comment"></i>
                <span class="comment-count">${feed.comments_count || 0}</span>
            </button>
            <button class="action-btn action-btn-share share-btn" data-url="${window.location.href}#feed-${feed.id}" title="Bagikan">
                <i class="fas fa-share-nodes"></i>
            </button>`;

        if (isMeets) {
            actionHtml += `
                <div class="btn-ikut-group">
                    <span class="meets-count">${feed.joins_count || 0} ikut</span>
                    <button class="btn-ikut ${joinBtnClass}" data-feed-id="${feed.id}" ${joinDisabled}>
                        ${joinBtnText}
                    </button>
                </div>`;
        }

        return `
            <article class="feed-card" 
                     id="feed-${feed.id}" 
                     data-feed-id="${feed.id}"
                     data-title="${(feed.title || feed.meet_description || '').toLowerCase()}"
                     data-type="${isMeets ? 'meets' : 'feeds'}">
                <div class="feed-card-header">
                    ${headerHtml}
                </div>
                ${imageHtml}
                <div class="feed-body">
                    ${bodyHtml}
                </div>
                <div class="feed-action-bar ${isMeets ? 'meets-action-bar' : ''}">
                    ${actionHtml}
                </div>
            </article>`;
    }
});
</script>
@endpush