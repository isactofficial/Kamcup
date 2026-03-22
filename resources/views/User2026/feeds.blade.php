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

.sidebar-header { padding: 20px 16px 8px; }
.sidebar-title { font-size: 1.1rem; font-weight: 700; color: #1a1a2e; margin: 0; }
.sidebar-search { padding: 8px 12px 12px; }
.search-wrap { position: relative; }
.search-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #999; font-size: 0.8rem; }
.search-input { width: 100%; background: #f0f2f5; border: none; border-radius: 8px; padding: 8px 12px 8px 32px; font-size: 0.875rem; color: #333; outline: none; transition: background 0.2s; }
.search-input:focus { background: #e8eaed; }
.sidebar-section { padding: 0 8px; flex: 1; }
.sidebar-label { font-size: 0.7rem; font-weight: 700; color: #999; letter-spacing: 1px; padding: 8px 8px 4px; margin: 0; }
.category-nav { display: flex; flex-direction: column; gap: 2px; }
.category-item { display: flex; align-items: center; gap: 10px; padding: 9px 10px; border-radius: 8px; color: #555; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.15s ease; cursor: pointer; }
.category-item:hover { background: #f8f0f6; color: #cb2786; text-decoration: none; }
.category-item.active { background: #cb278615; color: #cb2786; font-weight: 600; }
.category-icon { width: 28px; height: 28px; border-radius: 6px; background: #f0f2f5; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; color: #666; flex-shrink: 0; transition: all 0.15s; }
.category-item.active .category-icon, .category-item:hover .category-icon { background: #cb278620; color: #cb2786; }
.sidebar-footer { padding: 12px; border-top: 1px solid #e8eaed; margin-top: auto; }

/* ── Main ────────────────────────────────────────────────── */
.feeds-main { flex: 1; overflow-y: auto; padding: 24px 28px; }
.main-topbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
.topbar-left { display: flex; align-items: baseline; gap: 10px; }
.topbar-title { font-size: 1.4rem; font-weight: 700; color: #1a1a2e; margin: 0; }
.topbar-count { font-size: 0.8rem; color: #999; font-weight: 500; background: #f0f2f5; padding: 2px 8px; border-radius: 20px; }
.back-button { margin-bottom: 24px; }
.feeds-section { margin-bottom: 24px; }
.section-header { display: flex; align-items: center; gap: 8px; margin-bottom: 14px; }
.section-header h3 { font-size: 0.85rem; font-weight: 700; color: #555; text-transform: uppercase; letter-spacing: 0.8px; margin: 0; }

/* ── Grid ────────────────────────────────────────────────── */
.feeds-grid { max-width: 680px; margin: 0 auto; padding: 0 1rem 3rem; }
.feeds-grid > .feed-card { margin-bottom: 1.25rem; }
.feeds-pagination { margin-top: 1.5rem; display: flex; justify-content: center; }

/* ── Feed Card ── */
.feed-card { background: #fff; border-radius: 18px; margin-bottom: 1.25rem; border: 1px solid #e8ecef; overflow: hidden; transition: box-shadow 0.25s ease, transform 0.25s ease; animation: fadeSlideUp 0.4s ease both; }
.feed-card:hover { box-shadow: 0 8px 30px rgba(0,0,0,0.09); transform: translateY(-2px); }
@keyframes fadeSlideUp { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }

.meets-badge { background: linear-gradient(135deg, #10b981, #059669); color: #fff; font-size: 0.65rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 50px; letter-spacing: 0.5px; text-transform: uppercase; box-shadow: 0 2px 8px rgba(16,185,129,0.3); }
.meets-info { background: linear-gradient(135deg, rgba(16,185,129,0.05), rgba(5,150,105,0.05)); border: 1px solid rgba(16,185,129,0.15); border-radius: 12px; padding: 0.75rem 1rem; margin: 0.75rem 0; }
.meets-date { font-weight: 600; color: #059669; margin-bottom: 0.25rem; }
.meets-details { font-size: 0.85rem; color: #6b7280; }
.btn-ikut-group { display: flex; gap: 0.5rem; align-items: center; flex: 1; justify-content: flex-end; }
.btn-ikut { flex: 1; background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: #fff; border: none; padding: 0.4rem 1rem; border-radius: 50px; font-weight: 600; font-size: 0.82rem; transition: all 0.2s; white-space: nowrap; }
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
.feed-action-bar { display: flex; align-items: center; gap: 0.25rem; padding: 0.6rem 0.75rem; border-top: 1px solid #f3f4f6; margin-top: 0.75rem; }
.feed-action-bar.meets-action-bar { justify-content: space-between; }
.action-btn { display: inline-flex; align-items: center; gap: 0.4rem; background: none; border: none; padding: 0.5rem 0.85rem; border-radius: 50px; cursor: pointer; transition: background 0.2s, color 0.2s; font-size: 0.85rem; font-weight: 500; color: #6b7280; font-family: 'DM Sans', sans-serif; }
.action-btn i { font-size: 1rem; }
.action-btn:hover { background-color: #fdf2f8; color: #cb2786; }
.action-btn.liked { color: #cb2786; background-color: #fdf2f8; }
.action-btn.saved { color: #f59e0b; background-color: #fef3c7; }
.action-btn-share { margin-left: auto; }
.avatar-fallback { width: 42px; height: 42px; border-radius: 50%; background: linear-gradient(135deg, #cb2786, #00617a); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-family: 'Sora', sans-serif; font-size: 0.9rem; flex-shrink: 0; }
.feeds-empty { text-align: center; padding: 4rem 1rem; background: #fff; border-radius: 18px; border: 1px solid #e8ecef; }
.feeds-empty i { font-size: 3rem; color: #d1d5db; margin-bottom: 1rem; display: block; }
.feeds-empty h3 { font-family: 'Sora', sans-serif; font-size: 1.1rem; color: #374151; margin-bottom: 0.5rem; }
.feeds-empty p { font-size: 0.88rem; color: #9ca3af; margin: 0; }

/* ═══════════════════════════════════════════════════════════
   INSTAGRAM-STYLE COMMENT MODAL
   ═══════════════════════════════════════════════════════════ */
#feedCommentModal .modal-dialog {
    max-width: 960px;
    margin: 1.5rem auto;
    height: calc(100vh - 3rem);
}

#feedCommentModal .modal-content {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: row;
}

/* Left: image / post preview */
.ig-modal-left {
    width: 55%;
    background: #fafafa;
    display: flex;
    align-items: stretch;
    justify-content: center;
    flex-shrink: 0;
    position: relative;
    border-right: 1px solid #efefef;
    overflow-y: auto;
}

.ig-modal-left img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    background: #000;
}

/* No-image state: show post content card instead */
.ig-modal-no-img {
    display: flex;
    flex-direction: column;
    width: 100%;
    padding: 32px 28px;
    background: #fafafa;
}

.ig-modal-no-img-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}

.ig-modal-no-img-title {
    font-family: 'Sora', sans-serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: #00617a;
    line-height: 1.35;
    margin: 0 0 10px;
}

.ig-modal-no-img-text {
    font-size: 0.92rem;
    color: #374151;
    line-height: 1.7;
    margin: 0;
    white-space: pre-wrap;
    word-break: break-word;
}

.ig-modal-no-img-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: linear-gradient(135deg, #cb278615, #00617a15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #cb2786;
    margin-bottom: 20px;
}

.ig-modal-meets-info {
    background: linear-gradient(135deg, rgba(16,185,129,0.07), rgba(5,150,105,0.07));
    border: 1px solid rgba(16,185,129,0.2);
    border-radius: 12px;
    padding: 12px 16px;
    margin-top: 14px;
}

.ig-modal-meets-date {
    font-weight: 600;
    color: #059669;
    font-size: 0.875rem;
    margin-bottom: 4px;
}

.ig-modal-meets-detail {
    font-size: 0.82rem;
    color: #6b7280;
}

/* Right: comment panel */
.ig-modal-right {
    width: 45%;
    display: flex;
    flex-direction: column;
    background: #fff;
    border-left: 1px solid #efefef;
}

/* Right header */
.ig-panel-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 16px;
    border-bottom: 1px solid #efefef;
    flex-shrink: 0;
}

.ig-panel-header .close-btn {
    margin-left: auto;
    background: none;
    border: none;
    cursor: pointer;
    color: #666;
    font-size: 1.1rem;
    padding: 4px;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s;
}

.ig-panel-header .close-btn:hover { background: #f0f0f0; }

.ig-panel-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

.ig-panel-avatar-fallback {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #cb2786, #00617a);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 0.8rem;
    flex-shrink: 0;
}

.ig-panel-username {
    font-weight: 700;
    font-size: 0.9rem;
    color: #0d1117;
    margin: 0;
}

.ig-panel-title {
    font-size: 0.78rem;
    color: #9ca3af;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 180px;
}

/* Caption row in comment panel */
.ig-caption-row {
    display: flex;
    gap: 10px;
    padding: 12px 16px 10px;
    border-bottom: 1px solid #f5f5f5;
    flex-shrink: 0;
}

.ig-caption-text {
    font-size: 0.875rem;
    color: #262626;
    line-height: 1.5;
    margin: 0;
    flex: 1;
}

.ig-caption-text strong { font-weight: 700; margin-right: 4px; }

/* Action bar (like, comment, share count) */
.ig-action-row {
    display: flex;
    gap: 6px;
    padding: 4px 16px 0;
    flex-shrink: 0;
}

.ig-action-icon {
    background: none;
    border: none;
    cursor: pointer;
    color: #262626;
    font-size: 1.3rem;
    padding: 6px;
    border-radius: 50%;
    transition: color 0.15s, transform 0.15s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ig-action-icon:hover { transform: scale(1.1); }
.ig-action-icon.liked { color: #ed4956; }
.ig-action-icon-share { margin-left: auto; }

.ig-likes-row {
    padding: 4px 16px 8px;
    font-size: 0.875rem;
    font-weight: 700;
    color: #262626;
    flex-shrink: 0;
}

/* Comments list */
.ig-comments-list {
    flex: 1;
    overflow-y: auto;
    padding: 0 16px;
    scrollbar-width: thin;
    scrollbar-color: #dbdbdb transparent;
}

.ig-comments-list::-webkit-scrollbar { width: 4px; }
.ig-comments-list::-webkit-scrollbar-thumb { background: #dbdbdb; border-radius: 4px; }

/* Single comment item */
.ig-comment-item {
    display: flex;
    gap: 10px;
    padding: 10px 0;
    animation: fadeIn 0.25s ease;
}

@keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }

.ig-comment-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    margin-top: 2px;
}

.ig-comment-avatar-fb {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #cb2786, #00617a);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 0.7rem;
    flex-shrink: 0;
    margin-top: 2px;
}

.ig-comment-body { flex: 1; }

.ig-comment-bubble {
    background: #f7f7f7;
    border-radius: 12px;
    padding: 8px 12px;
    display: inline-block;
    max-width: 100%;
}

.ig-comment-username {
    font-size: 0.8rem;
    font-weight: 700;
    color: #262626;
    margin: 0 0 2px;
}

.ig-comment-text {
    font-size: 0.875rem;
    color: #262626;
    margin: 0;
    line-height: 1.45;
    word-break: break-word;
}

.ig-comment-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 4px;
    padding-left: 2px;
}

.ig-comment-time { font-size: 0.73rem; color: #8e8e8e; }

.ig-comment-like-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 0.73rem;
    color: #8e8e8e;
    font-weight: 600;
    padding: 0;
    transition: color 0.15s;
}

.ig-comment-like-btn:hover { color: #ed4956; }
.ig-comment-like-btn.liked { color: #ed4956; }

.ig-reply-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 0.73rem;
    color: #8e8e8e;
    font-weight: 600;
    padding: 0;
    transition: color 0.15s;
}

.ig-reply-btn:hover { color: #cb2786; }

.ig-delete-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 0.73rem;
    color: #8e8e8e;
    padding: 0;
    transition: color 0.15s;
}

.ig-delete-btn:hover { color: #ef4444; }

/* Replies */
.ig-replies-list {
    margin-top: 6px;
    padding-left: 4px;
    border-left: 2px solid #f0f0f0;
}

.ig-view-replies-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 0.75rem;
    color: #8e8e8e;
    font-weight: 600;
    padding: 4px 0;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 4px;
    transition: color 0.15s;
}

.ig-view-replies-btn:hover { color: #262626; }
.ig-view-replies-btn .line { height: 1px; width: 24px; background: #dbdbdb; }

/* Loading spinner */
.ig-comments-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 24px;
    color: #8e8e8e;
    font-size: 0.8rem;
}

/* Reply indicator banner */
.ig-reply-indicator {
    display: none;
    align-items: center;
    gap: 8px;
    padding: 6px 16px;
    background: #fdf2f8;
    font-size: 0.8rem;
    color: #cb2786;
    font-weight: 500;
    flex-shrink: 0;
    border-top: 1px solid #f5e6f3;
}

.ig-reply-indicator.show { display: flex; }

.ig-reply-indicator .cancel-reply {
    margin-left: auto;
    background: none;
    border: none;
    cursor: pointer;
    color: #8e8e8e;
    font-size: 0.9rem;
    padding: 0;
}

/* Input area */
.ig-input-area {
    border-top: 1px solid #efefef;
    padding: 10px 14px 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    flex-shrink: 0;
}

.ig-input-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    margin-bottom: 2px;
}

.ig-input-avatar-fb {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #cb2786, #00617a);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 0.7rem;
    flex-shrink: 0;
    margin-bottom: 2px;
}

.ig-input-wrap { flex: 1; position: relative; }

.ig-comment-input {
    width: 100%;
    border: 1px solid #dbdbdb;
    border-radius: 22px;
    padding: 10px 52px 10px 16px;
    font-size: 0.875rem;
    color: #262626;
    outline: none;
    resize: none;
    overflow-y: hidden;
    min-height: 42px;
    max-height: 120px;
    line-height: 1.5;
    transition: border-color 0.2s, background 0.2s;
    font-family: inherit;
    background: #fafafa;
    display: block;
    box-sizing: border-box;
}

.ig-comment-input:focus { border-color: #a0a0a0; background: #fff; }
.ig-comment-input::placeholder { color: #8e8e8e; }

.ig-send-btn {
    position: absolute;
    right: 12px;
    bottom: 50%;
    transform: translateY(50%);
    background: none;
    border: none;
    cursor: pointer;
    color: #0095f6;
    font-size: 0.875rem;
    font-weight: 700;
    padding: 0;
    transition: opacity 0.15s;
    opacity: 0.35;
    pointer-events: none;
    white-space: nowrap;
}

.ig-send-btn.active { opacity: 1; pointer-events: auto; }
.ig-send-btn:hover { opacity: 0.7; }

/* Empty comments */
.ig-no-comments {
    text-align: center;
    padding: 32px 16px;
    color: #8e8e8e;
}

.ig-no-comments i { font-size: 2rem; display: block; margin-bottom: 8px; }
.ig-no-comments p { font-size: 0.875rem; margin: 0; }

/* ═══════════════════════════════════════════════════════════
   SHARE MODAL (Instagram-style bottom sheet feel)
   ═══════════════════════════════════════════════════════════ */
#shareModal .modal-dialog {
    max-width: 420px;
    margin: auto auto 0;
    align-self: flex-end;
}

#shareModal .modal-content {
    border: none;
    border-radius: 20px 20px 0 0;
    overflow: hidden;
}

@media (min-height: 500px) {
    #shareModal .modal-dialog {
        margin: auto;
        align-self: center;
    }
    #shareModal .modal-content {
        border-radius: 20px;
    }
}

.share-handle {
    width: 36px;
    height: 4px;
    background: #dbdbdb;
    border-radius: 2px;
    margin: 12px auto 0;
}

.share-title {
    font-size: 1rem;
    font-weight: 700;
    text-align: center;
    padding: 12px 0 16px;
    color: #262626;
}

.share-link-box {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f5f5f5;
    border-radius: 12px;
    padding: 10px 14px;
    margin: 0 16px 16px;
}

.share-link-text {
    flex: 1;
    font-size: 0.82rem;
    color: #555;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    border: none;
    background: none;
    outline: none;
}

.share-copy-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 0.82rem;
    font-weight: 700;
    color: #0095f6;
    padding: 0;
    white-space: nowrap;
    transition: opacity 0.15s;
}

.share-copy-btn:hover { opacity: 0.7; }
.share-copy-btn.copied { color: #10b981; }

.share-options {
    display: flex;
    justify-content: space-around;
    padding: 0 16px 16px;
}

.share-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    padding: 10px;
    border-radius: 12px;
    transition: background 0.15s;
    border: none;
    background: none;
    text-decoration: none;
}

.share-option:hover { background: #f5f5f5; }

.share-option-icon {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #fff;
}

.share-option-label { font-size: 0.72rem; color: #555; font-weight: 500; }

/* Toast */
.ig-toast {
    position: fixed;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%) translateY(20px);
    padding: 10px 20px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.875rem;
    z-index: 9999;
    box-shadow: 0 8px 25px rgba(0,0,0,.2);
    opacity: 0;
    transition: all 0.3s ease;
    pointer-events: none;
    white-space: nowrap;
}

.ig-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
.ig-toast.hide { opacity: 0; transform: translateX(-50%) translateY(20px); }

/* Responsive */
@media (max-width: 768px) {
    .feeds-wrapper { flex-direction: column; height: auto; }
    .feeds-sidebar { width: 100%; min-width: unset; border-right: none; border-bottom: 1px solid #e8eaed; max-height: 220px; }
    .category-nav { flex-direction: row; flex-wrap: nowrap; overflow-x: auto; padding-bottom: 4px; }
    .category-item { white-space: nowrap; flex-shrink: 0; }
    .feeds-main { padding: 16px; }

    #feedCommentModal .modal-dialog { max-width: 100%; margin: 0; height: 100vh; border-radius: 0; }
    #feedCommentModal .modal-content { flex-direction: column; border-radius: 0; }
    .ig-modal-left { width: 100%; height: 45vh; }
    .ig-modal-right { width: 100%; height: 55vh; }
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
        <div class="sidebar-search">
            <div class="search-wrap">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="feedSearch" placeholder="Cari feeds..." class="search-input">
            </div>
        </div>
        <div class="sidebar-section">
            <p class="sidebar-label">FILTER</p>
            <nav class="category-nav">
                <a href="{{ route('user2026.feeds') }}" class="category-item {{ $filter == 'all' ? 'active' : '' }}">
                    <span class="category-icon"><i class="fas fa-border-all"></i></span>
                    <span>Semua</span>
                </a>
                <a href="{{ route('user2026.feeds', ['filter' => 'feeds']) }}" class="category-item {{ $filter == 'feeds' ? 'active' : '' }}">
                    <span class="category-icon"><i class="fas fa-rss"></i></span>
                    <span>Feeds</span>
                </a>
                <a href="{{ route('user2026.feeds', ['filter' => 'meets']) }}" class="category-item {{ $filter == 'meets' ? 'active' : '' }}">
                    <span class="category-icon"><i class="fas fa-calendar-plus"></i></span>
                    <span>Meets</span>
                </a>
                <a href="{{ route('user2026.saved') }}" class="category-item {{ request()->routeIs('user2026.saved') ? 'active' : '' }}">
                    <span class="category-icon"><i class="fas fa-bookmark"></i></span>
                    <span>Tersimpan</span>
                </a>
            </nav>
        </div>
        <div class="sidebar-footer">
            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#create-meets-modal">
                <i class="fas fa-calendar-plus me-2"></i>Buat Meets
            </button>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="feeds-main">
        <div class="main-topbar">
            <div class="topbar-left">
                <h1 class="topbar-title">{{ $title }}</h1>
                <span class="topbar-count">{{ $feedCount }} feeds</span>
            </div>
        </div>

        <div class="back-button">
            <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary btn-lg shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Profile
            </a>
        </div>

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
                            @if($feed->user->profile?->profile_photo)
                                <img src="{{ asset('storage/' . $feed->user->profile->profile_photo) }}"
                                     alt="{{ $feed->user->name }}"
                                     class="user-avatar"
                                     onerror="this.onerror=null; this.src='{{ asset('assets/img/profile-placeholder.png') }}';">
                            @else
                                <img src="{{ asset('assets/img/profile-placeholder.png') }}"
                                     alt="{{ $feed->user->name }}"
                                     class="user-avatar">
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
                        <!-- Like -->
                        <button class="action-btn like-btn {{ $feed->current_user_liked ? 'liked' : '' }}"
                                data-feed-id="{{ $feed->id }}" title="Suka">
                            <i class="{{ $feed->current_user_liked ? 'fas' : 'far' }} fa-heart"></i>
                            <span class="like-count">{{ $feed->likes_count }}</span>
                        </button>

                        <!-- Save -->
                        <button class="action-btn save-btn {{ $feed->current_user_saved ? 'saved' : '' }}"
                                data-feed-id="{{ $feed->id }}" title="Simpan">
                            <i class="{{ $feed->current_user_saved ? 'fas' : 'far' }} fa-bookmark"></i>
                            <span class="save-count">{{ $feed->saves_count ?? 0 }}</span>
                        </button>

                        <!-- Comment -->
                        <button class="action-btn comment-toggle"
                                data-feed-id="{{ $feed->id }}"
                                data-feed-title="{{ $feed->title ?? '' }}"
                                data-feed-image="{{ $feed->image ?? '' }}"
                                data-feed-author="{{ $feed->user->name ?? 'KAMCUP' }}"
                                data-feed-author-photo="{{ $feed->user->profile->profile_photo ?? '' }}"
                                data-feed-caption="{{ $feed->content ?? $feed->meet_description ?? '' }}"
                                data-feed-likes="{{ $feed->likes_count }}"
                                data-feed-liked="{{ $feed->current_user_liked ? 'true' : 'false' }}"
                                data-meet-date="{{ $feed->meet_date ? \Carbon\Carbon::parse($feed->meet_date)->translatedFormat('d F Y') : '' }}"
                                data-meet-location="{{ $feed->meet_location ?? '' }}"
                                data-meet-max="{{ $feed->meet_max_people ?? '' }}"
                                title="Komentar">
                            <i class="far fa-comment"></i>
                            <span class="comment-count">{{ $feed->comments_count }}</span>
                        </button>

                        <!-- Share -->
                        <button class="action-btn action-btn-share share-btn"
                                data-url="{{ url('/') }}#feed-{{ $feed->id }}"
                                data-title="{{ $feed->title ?? 'Cek feed ini di KAMCUP!' }}"
                                title="Bagikan">
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
            <div class="feeds-empty">
                <i class="fas fa-rss fa-3x"></i>
                <h3>Belum ada {{ strtolower($title) }}</h3>
                <p>Buat yang pertama atau ganti filter!</p>
            </div>
            @endif
        </section>
    </main>
</div>

{{-- ═══════════════════════════════════════════════════════════
     CREATE MEETS MODAL
     ═══════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="create-meets-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border:none; border-radius:20px; overflow:hidden;">
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
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Foto (Opsional)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="meet_description" class="form-control" required rows="4" maxlength="2000" placeholder="Detail kegiatan, persyaratan, kontak WA, dll..."></textarea>
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

{{-- ═══════════════════════════════════════════════════════════
     INSTAGRAM-STYLE COMMENT MODAL
     ═══════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="feedCommentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- LEFT: Post Image / Content -->
            <div class="ig-modal-left" id="ig-modal-img-wrap">
                <div class="ig-modal-no-img">
                    <div class="ig-modal-no-img-icon"><i class="fas fa-newspaper"></i></div>
                    <p class="ig-modal-no-img-text" style="color:#9ca3af;text-align:center;">Pilih postingan untuk dilihat</p>
                </div>
            </div>

            <!-- RIGHT: Comment Panel -->
            <div class="ig-modal-right">

                <!-- Header: author info -->
                <div class="ig-panel-header">
                    <div id="ig-panel-author-avatar"></div>
                    <div>
                        <p class="ig-panel-username" id="ig-panel-author-name">—</p>
                        <p class="ig-panel-title" id="ig-panel-post-title">—</p>
                    </div>
                    <button class="close-btn" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Caption row -->
                <div class="ig-caption-row" id="ig-caption-row" style="display:none;">
                    <div id="ig-caption-avatar"></div>
                    <p class="ig-caption-text" id="ig-caption-text"></p>
                </div>

                <!-- Action icons -->
                <div class="ig-action-row">
                    <button class="ig-action-icon" id="ig-modal-like-btn" title="Suka">
                        <i class="far fa-heart"></i>
                    </button>
                    <button class="ig-action-icon" id="ig-modal-comment-focus-btn" title="Komentar">
                        <i class="far fa-comment"></i>
                    </button>
                    <button class="ig-action-icon ig-action-icon-share" id="ig-modal-share-btn" title="Bagikan">
                        <i class="fas fa-share-nodes"></i>
                    </button>
                </div>

                <!-- Likes count -->
                <div class="ig-likes-row">
                    <span id="ig-modal-likes-count">0</span> suka
                </div>

                <!-- Comments list -->
                <div class="ig-comments-list" id="ig-comments-list">
                    <div class="ig-comments-loading">
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                        <span>Memuat komentar...</span>
                    </div>
                </div>

                <!-- Reply indicator -->
                <div class="ig-reply-indicator" id="ig-reply-indicator">
                    <i class="fas fa-reply"></i>
                    <span id="ig-reply-label">Membalas <strong></strong></span>
                    <button class="cancel-reply" id="ig-cancel-reply"><i class="fas fa-times"></i></button>
                </div>

                <!-- Input area -->
                <div class="ig-input-area">
                    <div id="ig-my-avatar"></div>
                    <div class="ig-input-wrap">
                        <textarea class="ig-comment-input"
                                  id="ig-comment-input"
                                  placeholder="Tambah komentar..."
                                  rows="1"></textarea>
                        <button class="ig-send-btn" id="ig-send-btn">Kirim</button>
                    </div>
                </div>

            </div><!-- /.ig-modal-right -->
        </div><!-- /.modal-content -->
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     SHARE MODAL
     ═══════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="shareModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="share-handle"></div>
            <div class="share-title">Bagikan</div>

            <div class="share-link-box">
                <input type="text" class="share-link-text" id="share-link-text" readonly>
                <button class="share-copy-btn" id="share-copy-btn">Salin</button>
            </div>

            <div class="share-options">
                <a class="share-option" id="share-wa" href="#" target="_blank" rel="noopener">
                    <div class="share-option-icon" style="background: #25D366;">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <span class="share-option-label">WhatsApp</span>
                </a>
                <a class="share-option" id="share-twitter" href="#" target="_blank" rel="noopener">
                    <div class="share-option-icon" style="background: #000;">
                        <i class="fab fa-x-twitter"></i>
                    </div>
                    <span class="share-option-label">X / Twitter</span>
                </a>
                <a class="share-option" id="share-fb" href="#" target="_blank" rel="noopener">
                    <div class="share-option-icon" style="background: #1877F2;">
                        <i class="fab fa-facebook"></i>
                    </div>
                    <span class="share-option-label">Facebook</span>
                </a>
                <button class="share-option" id="share-copy-native">
                    <div class="share-option-icon" style="background: #6b7280;">
                        <i class="fas fa-link"></i>
                    </div>
                    <span class="share-option-label">Salin Link</span>
                </button>
            </div>

            <div style="padding: 0 16px 20px;">
                <button class="btn btn-light w-100 rounded-pill fw-semibold" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast container -->
<div class="ig-toast" id="ig-toast"></div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/locale/id.min.js"></script>
<script>
moment.locale('id');

$(document).ready(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    /* ─────────────────────────────────────────────────────────
       HELPERS
       ───────────────────────────────────────────────────────── */
    const myUserId  = {{ auth()->id() ?? 'null' }};
    const myName    = @json(auth()->user()?->name ?? 'Kamu');
    const myPhoto   = @json(auth()->user()?->profile?->profile_photo ?? '');

    function showToast(msg, type = 'info') {
        const colors = { success: '#10b981', error: '#ef4444', info: '#3b82f6', warning: '#f59e0b' };
        const $t = $('#ig-toast');
        $t.text(msg).css('background', colors[type] || colors.info).addClass('show');
        setTimeout(() => $t.removeClass('show').addClass('hide'), 2500);
        setTimeout(() => $t.removeClass('hide'), 2900);
    }

    function avatarHtml(photo, name, size = 36, cls = '') {
        const initial = (name || '?').charAt(0).toUpperCase();
        if (photo) {
            return `<img src="/storage/${photo}" alt="${name}"
                         style="width:${size}px;height:${size}px;border-radius:50%;object-fit:cover;flex-shrink:0;"
                         onerror="this.onerror=null;this.src='/assets/img/profile-placeholder.png';" class="${cls}">`;
        }
        return `<div style="width:${size}px;height:${size}px;border-radius:50%;background:linear-gradient(135deg,#cb2786,#00617a);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:${Math.round(size*0.38)}px;flex-shrink:0;" class="${cls}">${initial}</div>`;
    }

    /* ─────────────────────────────────────────────────────────
       FEED SEARCH
       ───────────────────────────────────────────────────────── */
    document.getElementById('feedSearch')?.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        document.querySelectorAll('.feed-card').forEach(card => {
            const match = (card.dataset.title || '').includes(q) || (card.dataset.type || '').includes(q);
            card.style.display = match ? '' : 'none';
        });
    });

    /* ─────────────────────────────────────────────────────────
       LIKE (feed card)
       ───────────────────────────────────────────────────────── */
    $(document).on('click', '.like-btn', function () {
        const $btn   = $(this);
        const feedId = $btn.data('feed-id');
        $.post(`/feeds/${feedId}/like`)
            .done(data => {
                $btn.toggleClass('liked', data.liked);
                $btn.find('i').toggleClass('far', !data.liked).toggleClass('fas', data.liked);
                $btn.find('.like-count').text(data.count);

                // Sync ig modal if open
                if (+$('#feedCommentModal').data('feed-id') === +feedId) {
                    $('#ig-modal-like-btn').toggleClass('liked', data.liked)
                        .find('i').toggleClass('far', !data.liked).toggleClass('fas', data.liked);
                    $('#ig-modal-likes-count').text(data.count);
                }
            })
            .fail(() => showToast('Gagal like/unlike', 'error'));
    });

    /* ─────────────────────────────────────────────────────────
       SAVE FEED
       ───────────────────────────────────────────────────────── */
    $(document).on('click', '.save-btn', function () {
        const $btn   = $(this);
        const feedId = $btn.data('feed-id');
        $.post(`/feeds/${feedId}/save`)
            .done(data => {
                $btn.toggleClass('saved', data.saved);
                $btn.find('i').toggleClass('far', !data.saved).toggleClass('fas', data.saved);
                $btn.find('.save-count').text(data.count);
                showToast(data.message, data.saved ? 'success' : 'info');
            })
            .fail(() => showToast('Gagal simpan/hapus simpanan', 'error'));
    });

    /* ─────────────────────────────────────────────────────────
       JOIN MEETS
       ───────────────────────────────────────────────────────── */
    $(document).on('click', '.btn-ikut:not(:disabled)', function () {
        const $btn   = $(this);
        const feedId = $btn.data('feed-id');
        $btn.prop('disabled', true);
        $.post(`/feeds/${feedId}/join`)
            .done(data => {
                $btn.toggleClass('joined', data.joined)
                    .html(data.joined ? '<i class="fas fa-check me-1"></i>Batal Ikut' : '<i class="fas fa-user-plus me-1"></i>Ikut');
                $btn.closest('.btn-ikut-group').find('.meets-count').text(data.count + ' ikut');
                showToast(data.message, data.joined ? 'success' : 'info');
            })
            .fail(xhr => showToast(xhr.responseJSON?.message || 'Gagal', 'error'))
            .always(() => $btn.prop('disabled', false));
    });

    /* ─────────────────────────────────────────────────────────
       CREATE MEETS
       ───────────────────────────────────────────────────────── */
    $('#create-meets-form').on('submit', function (e) {
        e.preventDefault();
        const $btn = $('#submit-meets-btn');
        const orig = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Posting...');
        $.ajax({
            url: '{{ route("user2026.feeds.store") }}',
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: resp => {
                $('#create-meets-modal').modal('hide');
                this.reset();
                showToast('Meets berhasil diposting!', 'success');
                $('#feeds-stream').prepend(buildFeedHtml(resp.feed));
            },
            error: xhr => showToast(xhr.responseJSON?.message || 'Gagal membuat meets', 'error'),
            complete: () => $btn.prop('disabled', false).html(orig)
        });
    });

    /* ═══════════════════════════════════════════════════════════
       INSTAGRAM-STYLE COMMENT MODAL
       ═══════════════════════════════════════════════════════════ */
    let currentFeedId   = null;
    let replyToId       = null;
    let replyToName     = null;

    // Open modal via comment-toggle button
    $(document).on('click', '.comment-toggle', function () {
        const $btn = $(this);
        currentFeedId = +$btn.data('feed-id');
        replyToId     = null;
        replyToName   = null;

        const title     = $btn.data('feed-title') || '';
        const image     = $btn.data('feed-image') || '';
        const author    = $btn.data('feed-author') || 'KAMCUP';
        const photo     = $btn.data('feed-author-photo') || '';
        const caption   = $btn.data('feed-caption') || '';
        const likes     = +$btn.data('feed-likes') || 0;
        const liked     = $btn.data('feed-liked') === 'true';
        const meetDate  = $btn.data('meet-date') || '';
        const meetLoc   = $btn.data('meet-location') || '';
        const meetMax   = $btn.data('meet-max') || '';

        // Store feed id on modal for sync
        $('#feedCommentModal').data('feed-id', currentFeedId);

        // Image atau konten post
        const $imgWrap = $('#ig-modal-img-wrap');
        if (image) {
            $imgWrap.html(`<img src="/storage/${image}" alt="${escapeHtml(title)}"
                                style="width:100%;height:100%;object-fit:contain;background:#000;"
                                onerror="this.onerror=null; $(this).closest('#ig-modal-img-wrap').html(buildNoImgContent('${escapeHtml(title).replace(/'/g,"\\'")}','${escapeHtml(caption).replace(/'/g,"\\'")}','${meetDate}','${escapeHtml(meetLoc)}','${meetMax}'));">`);
        } else {
            $imgWrap.html(buildNoImgContent(title, caption, meetDate, meetLoc, meetMax));
        }

        // Author header
        $('#ig-panel-author-avatar').html(avatarHtml(photo, author, 36));
        $('#ig-panel-author-name').text(author);
        $('#ig-panel-post-title').text(title || 'Feed');

        // Caption
        if (caption) {
            $('#ig-caption-avatar').html(avatarHtml(photo, author, 32));
            $('#ig-caption-text').html(`<strong>${escapeHtml(author)}</strong> ${escapeHtml(caption)}`);
            $('#ig-caption-row').show();
        } else {
            $('#ig-caption-row').hide();
        }

        // Like state
        const $likeBtnModal = $('#ig-modal-like-btn');
        $likeBtnModal.toggleClass('liked', liked)
            .find('i').toggleClass('far', !liked).toggleClass('fas', liked);
        $('#ig-modal-likes-count').text(likes);

        // My avatar in input
        $('#ig-my-avatar').html(avatarHtml(myPhoto, myName, 32));

        // Reset reply state & input
        resetReply();
        $('#ig-comment-input').val('').css('height', '42px');
        $('#ig-send-btn').removeClass('active');

        // Show modal then load comments
        $('#feedCommentModal').modal('show');
        loadComments(currentFeedId);
    });

    /* ── Load comments ── */
    function loadComments(feedId) {
        const $list = $('#ig-comments-list');
        $list.html(`<div class="ig-comments-loading">
            <div class="spinner-border spinner-border-sm" role="status"></div>
            <span>Memuat komentar...</span>
        </div>`);

        $.get(`/feeds/${feedId}/comments`)
            .done(comments => renderComments(comments))
            .fail(() => {
                $list.html(`<div class="ig-no-comments">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>Gagal memuat komentar</p>
                </div>`);
            });
    }

    function renderComments(comments) {
        const $list = $('#ig-comments-list');
        if (!comments || comments.length === 0) {
            $list.html(`<div class="ig-no-comments">
                <i class="far fa-comment-dots"></i>
                <p>Belum ada komentar.<br>Jadilah yang pertama!</p>
            </div>`);
            return;
        }
        $list.empty();
        comments.forEach(c => $list.append(buildCommentEl(c, false)));
        $list.scrollTop(0);
    }

    function buildCommentEl(c, isReply) {
        const photo   = c.user?.profile?.profile_photo || '';
        const name    = c.user?.name || 'User';
        const timeAgo = moment(c.created_at).fromNow();
        const isOwn   = c.user_id === myUserId;
        const replies = c.children || [];

        // Replies always start COLLAPSED — harus klik "Lihat N balasan" dulu
        const repliesHtml = !isReply
            ? `<div class="ig-replies-list" id="replies-${c.id}" style="display:none;">
                ${replies.map(r => buildCommentEl(r, true)).join('')}
               </div>`
            : '';

        // Toggle button — tampil hanya kalau ada balasan dan ini bukan reply
        const viewRepliesBtn = (!isReply && replies.length > 0)
            ? `<button class="ig-view-replies-btn" data-comment-id="${c.id}" data-count="${replies.length}" data-open="0">
                <span class="line"></span>
                <span class="label">Lihat ${replies.length} balasan</span>
                <span class="line"></span>
               </button>`
            : '';

        return `
        <div class="ig-comment-item" id="comment-${c.id}" data-comment-id="${c.id}" data-user-id="${c.user_id}">
            ${avatarHtml(photo, name, 32, 'ig-comment-avatar')}
            <div class="ig-comment-body">
                <div class="ig-comment-bubble">
                    <p class="ig-comment-username">${name}</p>
                    <p class="ig-comment-text">${escapeHtml(c.content)}</p>
                </div>
                <div class="ig-comment-meta">
                    <span class="ig-comment-time">${timeAgo}</span>
                    ${!isReply ? `<button class="ig-reply-btn" data-comment-id="${c.id}" data-comment-name="${name}">Balas</button>` : ''}
                    ${isOwn ? `<button class="ig-delete-btn" data-comment-id="${c.id}" title="Hapus"><i class="fas fa-trash-alt"></i></button>` : ''}
                </div>
                ${repliesHtml}
                ${viewRepliesBtn}
            </div>
        </div>`;
    }

    /* ── Helper: no-image left panel HTML ── */
    function buildNoImgContent(title, caption, meetDate, meetLoc, meetMax) {
        const isMeets = !!meetDate;
        const icon    = isMeets ? 'fas fa-calendar-plus' : 'fas fa-newspaper';

        let meetsBlock = '';
        if (isMeets) {
            meetsBlock = `<div class="ig-modal-meets-info">
                <div class="ig-modal-meets-date"><i class="fas fa-calendar me-1"></i>${meetDate}${meetLoc ? ' • ' + escapeHtml(meetLoc) : ''}</div>
                ${meetMax ? `<div class="ig-modal-meets-detail"><i class="fas fa-users me-1"></i>Maks ${meetMax} orang</div>` : ''}
            </div>`;
        }

        return `<div class="ig-modal-no-img">
            <div class="ig-modal-no-img-icon"><i class="${icon}"></i></div>
            ${title ? `<h2 class="ig-modal-no-img-title">${escapeHtml(title)}</h2>` : ''}
            ${caption ? `<p class="ig-modal-no-img-text">${escapeHtml(caption)}</p>` : ''}
            ${meetsBlock}
        </div>`;
    }

    function escapeHtml(str) {
        return (str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    /* ── Reply btn ── */
    $(document).on('click', '.ig-reply-btn', function () {
        replyToId   = +$(this).data('comment-id');
        replyToName = $(this).data('comment-name');

        const $ind = $('#ig-reply-indicator');
        $ind.find('strong').text(replyToName);
        $ind.addClass('show');

        $('#ig-comment-input').focus().val(`@${replyToName} `).trigger('input');
    });

    /* ── Cancel reply ── */
    $('#ig-cancel-reply').on('click', resetReply);
    function resetReply() {
        replyToId   = null;
        replyToName = null;
        $('#ig-reply-indicator').removeClass('show');
    }

    /* ── View replies toggle ── */
    $(document).on('click', '.ig-view-replies-btn', function () {
        const $btn   = $(this);
        const cId    = $btn.data('comment-id');
        const $rList = $(`#replies-${cId}`);
        const isOpen = $btn.data('open') === 1 || $btn.data('open') === '1';
        const count  = $btn.data('count');

        if (isOpen) {
            $rList.slideUp(180);
            $btn.data('open', 0).find('.label').text(`Lihat ${count} balasan`);
        } else {
            $rList.slideDown(180);
            $btn.data('open', 1).find('.label').text('Sembunyikan balasan');
        }
    });

    /* ── Input auto-resize & send button activation ── */
    $('#ig-comment-input').on('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        const hasText = this.value.trim().length > 0;
        $('#ig-send-btn').toggleClass('active', hasText);
    });

    $('#ig-comment-input').on('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if ($(this).val().trim()) submitComment();
        }
    });

    /* ── Modal like button ── */
    $('#ig-modal-like-btn').on('click', function () {
        if (!currentFeedId) return;
        // Delegate to the card's like button to keep in sync
        $(`.like-btn[data-feed-id="${currentFeedId}"]`).trigger('click');
    });

    /* ── Modal share button ── */
    $('#ig-modal-share-btn').on('click', function () {
        if (!currentFeedId) return;
        openShareModal(window.location.origin + window.location.pathname + `#feed-${currentFeedId}`, 'Feed KAMCUP');
    });

    /* ── Focus on comment input ── */
    $('#ig-modal-comment-focus-btn').on('click', function () {
        $('#ig-comment-input').focus();
    });

    /* ── Send button ── */
    $('#ig-send-btn').on('click', submitComment);

    function submitComment() {
        const $input  = $('#ig-comment-input');
        const content = $input.val().trim();
        if (!content || !currentFeedId) return;

        const $btn = $('#ig-send-btn');
        $btn.prop('disabled', true);

        const payload = { content };
        if (replyToId) payload.parent_id = replyToId;

        $.post(`/feeds/${currentFeedId}/comments`, payload)
            .done(comment => {
                $btn.prop('disabled', false);
                comment.children = comment.children || [];

                if (replyToId) {
                    // Append reply under parent, auto-open the replies list
                    const $rList    = $(`#replies-${replyToId}`);
                    const $toggle   = $(`.ig-view-replies-btn[data-comment-id="${replyToId}"]`);
                    if ($rList.length) {
                        $rList.append(buildCommentEl(comment, true));
                        // Always open after sending reply
                        $rList.slideDown(180);
                        const newCount = $rList.children('.ig-comment-item').length;
                        $toggle.data('count', newCount).data('open', 1).show()
                               .find('.label').text('Sembunyikan balasan');
                    }
                    resetReply();
                    $input.val('').css('height', '42px').trigger('input');
                } else {
                    // Prepend new comment
                    const $list  = $('#ig-comments-list');
                    const $empty = $list.find('.ig-no-comments');
                    if ($empty.length) $empty.remove();
                    $list.prepend(buildCommentEl(comment, false));
                    $input.val('').css('height', '42px').trigger('input');
                }

                // Update comment count on card
                const $cardCommentCount = $(`.comment-toggle[data-feed-id="${currentFeedId}"] .comment-count`);
                $cardCommentCount.text(+$cardCommentCount.text() + 1);
            })
            .fail(xhr => {
                showToast(xhr.responseJSON?.message || 'Gagal mengirim komentar', 'error');
                $btn.prop('disabled', false);
            });
    }

    /* ── Delete comment ── */
    $(document).on('click', '.ig-delete-btn', function () {
        const $deleteBtn = $(this);
        const cId   = $deleteBtn.data('comment-id');
        const $item = $(`#comment-${cId}`);
        if (!confirm('Hapus komentar ini?')) return;

        $deleteBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: `/feeds/${cId}/comment`,
            type: 'POST',
            data: { _method: 'DELETE', _token: $('meta[name="csrf-token"]').attr('content') }
        })
        .done(() => {
            $item.fadeOut(220, function () {
                // Cek apakah ini reply — jika iya, update count toggle btn parent
                const $repliesList = $(this).closest('.ig-replies-list');
                if ($repliesList.length) {
                    const parentId  = $repliesList.attr('id').replace('replies-', '');
                    const $toggle   = $(`.ig-view-replies-btn[data-comment-id="${parentId}"]`);
                    const newCount  = Math.max(0, ($toggle.data('count') || 1) - 1);
                    $toggle.data('count', newCount).find('.label').text(
                        $toggle.data('open') == 1 ? 'Sembunyikan balasan' : `Lihat ${newCount} balasan`
                    );
                    if (newCount === 0) $toggle.hide();
                }

                $(this).remove();

                // Update comment count badge on card
                const $count = $(`.comment-toggle[data-feed-id="${currentFeedId}"] .comment-count`);
                $count.text(Math.max(0, +$count.text() - 1));

                // Empty state
                if (!$('#ig-comments-list .ig-comment-item').length) {
                    $('#ig-comments-list').html(`<div class="ig-no-comments">
                        <i class="far fa-comment-dots"></i>
                        <p>Belum ada komentar.<br>Jadilah yang pertama!</p>
                    </div>`);
                }
            });
            showToast('Komentar dihapus', 'info');
        })
        .fail(xhr => {
            $deleteBtn.prop('disabled', false).html('<i class="fas fa-trash-alt"></i>');
            showToast(xhr.responseJSON?.message || 'Gagal menghapus komentar', 'error');
        });
    });

    /* ── Reset state on close ── */
    $('#feedCommentModal').on('hidden.bs.modal', function () {
        currentFeedId = null;
        replyToId     = null;
        resetReply();
        $('#ig-comment-input').val('').css('height', 'auto').trigger('input');
        $('#ig-comments-list').html('');
    });

    /* ═══════════════════════════════════════════════════════════
       SHARE
       ═══════════════════════════════════════════════════════════ */
    $(document).on('click', '.share-btn', function () {
        const url   = $(this).data('url') || window.location.href;
        const title = $(this).data('title') || 'Feed KAMCUP';
        openShareModal(url, title);
    });

    function openShareModal(url, title) {
        const encoded = encodeURIComponent(url);
        const text    = encodeURIComponent(title + ' — ' + url);

        $('#share-link-text').val(url);
        $('#share-copy-btn').text('Salin').removeClass('copied');

        $('#share-wa').attr('href', `https://wa.me/?text=${text}`);
        $('#share-twitter').attr('href', `https://twitter.com/intent/tweet?url=${encoded}&text=${encodeURIComponent(title)}`);
        $('#share-fb').attr('href', `https://www.facebook.com/sharer/sharer.php?u=${encoded}`);

        $('#shareModal').modal('show');
    }

    $('#share-copy-btn, #share-copy-native').on('click', function () {
        const url = $('#share-link-text').val();
        navigator.clipboard.writeText(url).then(() => {
            $('#share-copy-btn').text('Disalin!').addClass('copied');
            showToast('Link disalin!', 'success');
            setTimeout(() => $('#shareModal').modal('hide'), 800);
        }).catch(() => {
            $('#share-link-text').select();
            document.execCommand('copy');
            showToast('Link disalin!', 'success');
        });
    });

    /* ═══════════════════════════════════════════════════════════
       BUILD FEED HTML (for newly created meets)
       ═══════════════════════════════════════════════════════════ */
    function buildFeedHtml(feed) {
        const user    = feed.user || null;
        const timeAgo = moment(feed.created_at).fromNow();
        const isMeets = !!feed.meet_date;
        const liked   = feed.current_user_liked;
        const isFull  = feed.meet_max_people && (feed.joins_count || 0) >= feed.meet_max_people;

        let headerHtml = '';
        if (user) {
            const photo = user.profile?.profile_photo || '';
            headerHtml += photo
                ? `<img src="/storage/${photo}" alt="${user.name}" class="user-avatar" onerror="this.onerror=null;this.src='/assets/img/profile-placeholder.png';">`
                : `<img src="/assets/img/profile-placeholder.png" alt="${user.name}" class="user-avatar">`;
            headerHtml += `<div class="feed-brand-info"><p class="brand-name">${user.name}</p><p class="feed-time">${timeAgo}</p></div>`;
        } else {
            headerHtml = `<div class="feed-brand-avatar">KC</div><div class="feed-brand-info"><p class="brand-name">KAMCUP</p><p class="feed-time">${timeAgo}</p></div><span class="feed-badge">Official</span>`;
        }
        if (isMeets) headerHtml += '<span class="meets-badge">MEETS</span>';

        const imageHtml = feed.image
            ? `<div class="feed-img-wrap"><img src="/storage/${feed.image}" alt="${feed.title||'Meets'}" loading="lazy" onerror="this.onerror=null;this.closest('.feed-img-wrap').style.display='none';"></div>`
            : '';

        let bodyHtml = feed.title ? `<h2 class="feed-title">${feed.title}</h2>` : '';
        if (isMeets) {
            bodyHtml += `<div class="meets-info">
                <div class="meets-date"><i class="fas fa-calendar me-1"></i>${moment(feed.meet_date).format('DD MMMM YYYY')} • ${feed.meet_location}</div>
                <div class="meets-details"><i class="fas fa-users me-1"></i>Max ${feed.meet_max_people} orang • 0 sudah ikut</div>
            </div>`;
            if (feed.meet_description) bodyHtml += `<p class="feed-content">${feed.meet_description}</p>`;
        } else if (feed.content) {
            bodyHtml += `<p class="feed-content">${feed.content}</p>`;
        }

        const userPhoto  = user?.profile?.profile_photo || '';
        const userName   = user?.name || 'KAMCUP';
        const caption    = feed.content || feed.meet_description || '';

        const meetDateFmt = isMeets ? moment(feed.meet_date).format('DD MMMM YYYY') : '';

        let actionHtml = `
            <button class="action-btn like-btn${liked?' liked':''}" data-feed-id="${feed.id}">
                <i class="${liked?'fas':'far'} fa-heart"></i>
                <span class="like-count">${feed.likes_count||0}</span>
            </button>
            <button class="action-btn comment-toggle"
                    data-feed-id="${feed.id}"
                    data-feed-title="${feed.title||''}"
                    data-feed-image="${feed.image||''}"
                    data-feed-author="${userName}"
                    data-feed-author-photo="${userPhoto}"
                    data-feed-caption="${caption}"
                    data-feed-likes="${feed.likes_count||0}"
                    data-feed-liked="${liked?'true':'false'}"
                    data-meet-date="${meetDateFmt}"
                    data-meet-location="${feed.meet_location||''}"
                    data-meet-max="${feed.meet_max_people||''}">
                <i class="far fa-comment"></i>
                <span class="comment-count">0</span>
            </button>
            <button class="action-btn action-btn-share share-btn"
                    data-url="${window.location.origin}${window.location.pathname}#feed-${feed.id}"
                    data-title="${feed.title||'Feed KAMCUP'}">
                <i class="fas fa-share-nodes"></i>
            </button>`;

        if (isMeets) {
            actionHtml += `<div class="btn-ikut-group">
                <span class="meets-count">0 ikut</span>
                <button class="btn-ikut" data-feed-id="${feed.id}" ${isFull?'disabled':''}>
                    <i class="fas fa-user-plus me-1"></i>Ikut
                </button>
            </div>`;
        }

        return `<article class="feed-card" id="feed-${feed.id}" data-feed-id="${feed.id}"
                          data-title="${(feed.title||feed.meet_description||'').toLowerCase()}"
                          data-type="${isMeets?'meets':'feeds'}">
            <div class="feed-card-header">${headerHtml}</div>
            ${imageHtml}
            <div class="feed-body">${bodyHtml}</div>
            <div class="feed-action-bar ${isMeets?'meets-action-bar':''}">${actionHtml}</div>
        </article>`;
    }
    
});
</script>
@endpush