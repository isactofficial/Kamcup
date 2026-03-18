@extends('layouts.master_nav')

@section('title', 'Feeds - KAMCUP')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
<style>
/* ===== BASE ===== */
*, *::before, *::after { box-sizing: border-box; }

body {
    font-family: 'DM Sans', sans-serif;
    background-color: #f4f6f8;
}

/* ===== PAGE HEADER ===== */
.feeds-hero {
    background: #fff;
    border-bottom: 1px solid #e8ecef;
    padding: 2rem 0 0;
    margin-bottom: 2rem;
}
.feeds-hero-inner {
    max-width: 680px;
    margin: 0 auto;
    padding: 0 1rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
}
.feeds-hero-icon {
    width: 52px;
    height: 52px;
    background: linear-gradient(135deg, #cb2786, #00617a);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.25rem;
    flex-shrink: 0;
}
.feeds-hero-text h1 {
    font-family: 'Sora', sans-serif;
    font-size: 1.4rem;
    font-weight: 700;
    color: #0d1117;
    margin: 0 0 2px;
    letter-spacing: -0.3px;
}
.feeds-hero-text p {
    font-size: 0.88rem;
    color: #6b7280;
    margin: 0;
}
.btn-back {
    margin-left: auto;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 1.1rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
    background-color: #00617a;
    color: #fff;
    border: none;
    text-decoration: none;
    transition: opacity 0.2s;
}
.btn-back:hover { opacity: 0.85; color: #fff; }

/* ===== FEED STREAM ===== */
.feeds-stream {
    max-width: 680px;
    margin: 0 auto;
    padding: 0 1rem 3rem;
}

/* ===== FEED CARD ===== */
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

/* Stagger animation */
.feed-card:nth-child(1) { animation-delay: 0.05s; }
.feed-card:nth-child(2) { animation-delay: 0.1s; }
.feed-card:nth-child(3) { animation-delay: 0.15s; }
.feed-card:nth-child(4) { animation-delay: 0.2s; }
.feed-card:nth-child(5) { animation-delay: 0.25s; }

/* Card header (brand bar) */
.feed-card-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.25rem 0.75rem;
}
.feed-brand-avatar {
    width: 42px;
    height: 42px;
    background: linear-gradient(135deg, #cb2786, #00617a);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-family: 'Sora', sans-serif;
    font-size: 0.9rem;
    flex-shrink: 0;
}
.feed-brand-info .brand-name {
    font-family: 'Sora', sans-serif;
    font-size: 0.9rem;
    font-weight: 700;
    color: #0d1117;
    margin: 0;
    line-height: 1.2;
}
.feed-brand-info .feed-time {
    font-size: 0.78rem;
    color: #9ca3af;
    margin: 0;
}
.feed-badge {
    margin-left: auto;
    font-size: 0.7rem;
    font-weight: 600;
    background: rgba(203,39,134,0.1);
    color: #cb2786;
    padding: 0.2rem 0.65rem;
    border-radius: 50px;
    letter-spacing: 0.3px;
}

/* Feed image */
.feed-img-wrap {
    position: relative;
    overflow: hidden;
}
.feed-img-wrap img {
    width: 100%;
    max-height: 420px;
    object-fit: cover;
    display: block;
    transition: transform 0.4s ease;
}
.feed-card:hover .feed-img-wrap img {
    transform: scale(1.02);
}

/* Feed body */
.feed-body {
    padding: 1rem 1.25rem 0.5rem;
}
.feed-title {
    font-family: 'Sora', sans-serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: #00617a;
    margin-bottom: 0.5rem;
    line-height: 1.35;
    letter-spacing: -0.2px;
}
.feed-content {
    font-size: 0.92rem;
    color: #374151;
    line-height: 1.65;
    margin: 0;
}

/* ===== ACTION BAR ===== */
.feed-action-bar {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.6rem 0.75rem;
    border-top: 1px solid #f3f4f6;
    margin-top: 0.75rem;
}
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
.action-btn:hover {
    background-color: #fdf2f8;
    color: #cb2786;
}
.action-btn.liked {
    color: #cb2786;
    background-color: #fdf2f8;
}
.action-btn.liked i.far { display: none; }
.action-btn:not(.liked) i.fas.fa-heart { display: none; }

.action-btn-share {
    margin-left: auto;
}

/* ===== SEPARATOR ===== */
.feeds-empty {
    text-align: center;
    padding: 4rem 1rem;
    background: #fff;
    border-radius: 18px;
    border: 1px solid #e8ecef;
}
.feeds-empty i {
    font-size: 3rem;
    color: #d1d5db;
    margin-bottom: 1rem;
    display: block;
}
.feeds-empty h3 {
    font-family: 'Sora', sans-serif;
    font-size: 1.1rem;
    color: #374151;
    margin-bottom: 0.5rem;
}
.feeds-empty p {
    font-size: 0.88rem;
    color: #9ca3af;
    margin: 0;
}

/* ===== PAGINATION ===== */
.feeds-pagination {
    margin-top: 1.5rem;
    display: flex;
    justify-content: center;
}

/* ===== MODAL ===== */
.modal-content {
    border: none;
    border-radius: 20px;
    overflow: hidden;
}
.modal-dialog { max-width: 760px; }

.modal-split {
    display: flex;
    min-height: 500px;
    max-height: 90vh;
}
.modal-split-image {
    flex: 1.1;
    background: #0d1117;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.modal-split-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.modal-split-image.no-image {
    flex: 0;
    display: none;
}
.modal-split-right {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
    min-height: 0;
}

/* Modal header */
.modal-post-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-shrink: 0;
}
.modal-brand-avatar {
    width: 38px;
    height: 38px;
    background: linear-gradient(135deg, #cb2786, #00617a);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 0.8rem;
    font-family: 'Sora', sans-serif;
    flex-shrink: 0;
}
.modal-post-title {
    font-family: 'Sora', sans-serif;
    font-size: 0.88rem;
    font-weight: 700;
    color: #0d1117;
    margin: 0;
}
.modal-post-sub {
    font-size: 0.75rem;
    color: #9ca3af;
    margin: 0;
}
.modal-close-btn {
    margin-left: auto;
    background: none;
    border: none;
    color: #9ca3af;
    font-size: 1.2rem;
    cursor: pointer;
    padding: 0.25rem;
    line-height: 1;
    transition: color 0.2s;
}
.modal-close-btn:hover { color: #374151; }

/* Comments scroll area */
.modal-comments-body {
    flex: 1;
    overflow-y: auto;
    padding: 0.75rem 1.25rem;
    min-height: 0;
}
.modal-comments-body::-webkit-scrollbar { width: 4px; }
.modal-comments-body::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }

/* Comment item */
.comment-item {
    display: flex;
    gap: 0.65rem;
    padding: 0.65rem 0;
    border-bottom: 1px solid #f9fafb;
    animation: fadeSlideUp 0.25s ease both;
}
.comment-item:last-child { border-bottom: none; }
.comment-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}
.comment-right { flex: 1; min-width: 0; }
.comment-bubble {
    background: #f9fafb;
    border-radius: 12px;
    padding: 0.55rem 0.85rem;
    display: inline-block;
    max-width: 100%;
}
.comment-username {
    font-size: 0.8rem;
    font-weight: 700;
    color: #00617a;
    display: block;
    margin-bottom: 0.15rem;
    font-family: 'Sora', sans-serif;
}
.comment-text {
    font-size: 0.85rem;
    color: #374151;
    margin: 0;
    word-break: break-word;
    line-height: 1.5;
}
.comment-meta {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    margin-top: 0.3rem;
    padding-left: 0.1rem;
}
.comment-time-label {
    font-size: 0.72rem;
    color: #9ca3af;
}
.comment-action-btn {
    font-size: 0.72rem;
    font-weight: 600;
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    transition: color 0.2s;
}
.btn-reply { color: #6b7280; }
.btn-reply:hover { color: #00617a; }
.btn-delete { color: #f87171; }
.btn-delete:hover { color: #dc2626; }

/* No comments */
.no-comments {
    text-align: center;
    padding: 2.5rem 1rem;
    color: #9ca3af;
    font-size: 0.88rem;
}
.no-comments i { font-size: 2rem; display: block; margin-bottom: 0.75rem; color: #d1d5db; }

/* Input bar */
.modal-input-bar {
    border-top: 1px solid #f3f4f6;
    padding: 0.85rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.65rem;
    flex-shrink: 0;
    background: #fff;
}
.modal-input-bar input {
    flex: 1;
    border: 1.5px solid #e5e7eb;
    border-radius: 50px;
    padding: 0.55rem 1rem;
    font-size: 0.88rem;
    font-family: 'DM Sans', sans-serif;
    outline: none;
    transition: border-color 0.2s;
    background: #f9fafb;
}
.modal-input-bar input:focus {
    border-color: #cb2786;
    background: #fff;
}
.modal-send-btn {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #cb2786;
    border: none;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: opacity 0.2s, transform 0.15s;
    flex-shrink: 0;
}
.modal-send-btn:hover { opacity: 0.85; transform: scale(1.08); }
.modal-send-btn:disabled { opacity: 0.5; cursor: not-allowed; }

/* ===== RESPONSIVE ===== */
@media (max-width: 640px) {
    .modal-split { flex-direction: column; max-height: unset; }
    .modal-split-image { max-height: 220px; min-height: 0; }
    .modal-dialog { margin: 0.5rem; max-width: 100%; }
    .modal-content { border-radius: 14px; }
    .modal-split-right { height: 60vh; }
    .feeds-hero-text p { display: none; }
}
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="feeds-hero">
    <div class="feeds-hero-inner">
        <div class="feeds-hero-icon">
            <i class="fas fa-rss"></i>
        </div>
        <div class="feeds-hero-text">
            <h1>Feeds KAMCUP</h1>
            <p>Update terbaru dari admin KAMCUP</p>
        </div>
        <a href="{{ route('profile.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

{{-- Feed Stream --}}
<div class="feeds-stream">

    @forelse ($feeds as $feed)
    <article class="feed-card" id="feed-{{ $feed->id }}">

        {{-- Card Header: Brand identity --}}
        <div class="feed-card-header">
            <div class="feed-brand-avatar">KC</div>
            <div class="feed-brand-info">
                <p class="brand-name">KAMCUP</p>
                <p class="feed-time">{{ $feed->created_at->diffForHumans() }}</p>
            </div>
            <span class="feed-badge">Official</span>
        </div>

        {{-- Image --}}
        @if ($feed->image)
        <div class="feed-img-wrap">
            <img
                src="{{ asset('storage/' . $feed->image) }}"
                alt="{{ $feed->title ?? 'Feed KAMCUP' }}"
                loading="lazy"
            >
        </div>
        @endif

        {{-- Body --}}
        <div class="feed-body">
            @if ($feed->title)
            <h2 class="feed-title">{{ $feed->title }}</h2>
            @endif
            @if ($feed->content)
            <p class="feed-content">{{ $feed->content }}</p>
            @endif
        </div>

        {{-- Action Bar --}}
        <div class="feed-action-bar">
            <button
                class="action-btn like-btn {{ $feed->likedBy(auth()->user()) ? 'liked' : '' }}"
                data-feed-id="{{ $feed->id }}"
                title="Suka"
            >
                <i class="far fa-heart"></i>
                <i class="fas fa-heart"></i>
                <span class="like-count">{{ $feed->likes_count }}</span>
            </button>

            <button
                class="action-btn comment-toggle"
                data-bs-toggle="modal"
                data-bs-target="#feedCommentModal"
                data-feed-id="{{ $feed->id }}"
                data-feed-title="{{ $feed->title }}"
                data-feed-image="{{ $feed->image }}"
                title="Komentar"
            >
                <i class="far fa-comment"></i>
                <span class="comment-count">{{ $feed->comments_count }}</span>
            </button>

            <button
                class="action-btn action-btn-share share-btn"
                data-url="{{ route('user2026.feeds') }}#feed-{{ $feed->id }}"
                title="Bagikan"
            >
                <i class="fas fa-share-nodes"></i>
            </button>
        </div>

    </article>
    @empty
    <div class="feeds-empty">
        <i class="fas fa-rss"></i>
        <h3>Belum ada feeds</h3>
        <p>Admin akan segera mengupdate feeds terbaru.</p>
    </div>
    @endforelse

    <div class="feeds-pagination">
        {{ $feeds->appends(request()->query())->links() }}
    </div>
</div>

{{-- ===== COMMENT MODAL (Instagram Split Layout) ===== --}}
<div class="modal fade" id="feedCommentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-split">

                {{-- Left: Image Panel --}}
                <div class="modal-split-image" id="modal-image-panel">
                    <img id="modal-feed-image" src="" alt="Feed">
                </div>

                {{-- Right: Comments Panel --}}
                <div class="modal-split-right">

                    {{-- Header --}}
                    <div class="modal-post-header">
                        <div class="modal-brand-avatar">KC</div>
                        <div>
                            <p class="modal-post-title" id="modal-feed-title">KAMCUP</p>
                            <p class="modal-post-sub">Feed resmi</p>
                        </div>
                        <button class="modal-close-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fas fa-xmark"></i>
                        </button>
                    </div>

                    {{-- Comments list --}}
                    <div class="modal-comments-body" id="modal-comments-list">
                        <div class="no-comments">
                            <i class="fas fa-spinner fa-spin"></i>
                            Memuat komentar...
                        </div>
                    </div>

                    {{-- Input bar --}}
                    <div class="modal-input-bar">
                        <input
                            type="text"
                            id="modal-comment-input"
                            placeholder="Tulis komentar..."
                            maxlength="500"
                            autocomplete="off"
                        >
                        <button class="modal-send-btn" id="modal-comment-send" type="button">
                            <i class="fas fa-paper-plane" style="font-size:0.85rem;"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    let currentFeedId = null;

    /* ---------- Like ---------- */
    $(document).on('click', '.like-btn', function() {
        const $btn = $(this);
        const feedId = $btn.data('feed-id');
        $.post(`/feeds/${feedId}/like`, {})
            .done(function(data) {
                $btn.toggleClass('liked', data.liked);
                $btn.find('.like-count').text(data.count);
            })
            .fail(function() { alert('Gagal like/unlike'); });
    });

    /* ---------- Update comment badge ---------- */
    function updateCommentCounts(feedId, delta) {
        const $span = $(`.comment-toggle[data-feed-id="${feedId}"] .comment-count`);
        $span.text(parseInt($span.text() || 0) + delta);
    }

    /* ---------- Modal open ---------- */
    $('#feedCommentModal').on('show.bs.modal', function(e) {
        const trigger = $(e.relatedTarget);
        currentFeedId = trigger.data('feed-id');
        const title  = trigger.data('feed-title') || 'Feed KAMCUP';
        const image  = trigger.data('feed-image');

        $('#modal-feed-title').text(title);

        if (image) {
            $('#modal-image-panel').removeClass('no-image').show();
            $('#modal-feed-image').attr('src', `/storage/${image}`);
        } else {
            $('#modal-image-panel').addClass('no-image').hide();
        }

        loadComments();
    });

    function loadComments() {
        $('#modal-comments-list').html(`
            <div class="no-comments">
                <i class="fas fa-spinner fa-spin"></i>
                Memuat komentar...
            </div>
        `);
        $.get(`/feeds/${currentFeedId}/comments`)
            .done(function(comments) {
                if (!comments.length) {
                    $('#modal-comments-list').html(`
                        <div class="no-comments">
                            <i class="far fa-comment-dots"></i>
                            Belum ada komentar. Jadilah yang pertama!
                        </div>
                    `);
                } else {
                    $('#modal-comments-list').html(comments.map(buildCommentHtml).join(''));
                }
            })
            .fail(function() {
                $('#modal-comments-list').html('<div class="no-comments" style="color:#f87171;">Gagal memuat komentar.</div>');
            });
    }

    /* ---------- Post comment ---------- */
    $('#modal-comment-send').click(postComment);
    $('#modal-comment-input').on('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); postComment(); }
    });

    function postComment() {
        const content = $('#modal-comment-input').val().trim();
        if (!content || !currentFeedId) return;

        $('#modal-comment-send').prop('disabled', true);
        $.post(`/feeds/${currentFeedId}/comments`, { content })
            .done(function(comment) {
                $('#modal-comments-list .no-comments').remove();
                $('#modal-comments-list').prepend(buildCommentHtml(comment));
                $('#modal-comment-input').val('');
                updateCommentCounts(currentFeedId, 1);
            })
            .fail(function() { alert('Gagal posting komentar'); })
            .always(function() { $('#modal-comment-send').prop('disabled', false); });
    }

    /* ---------- Delete comment ---------- */
    $(document).on('click', '.btn-delete', function() {
        const commentId = $(this).data('comment-id');
        if (!confirm('Hapus komentar ini?')) return;
        $.ajax({ url: `/feeds/${commentId}/comment`, type: 'DELETE' })
            .done(function() {
                $(`[data-comment-id="${commentId}"]`).fadeOut(250, function() { $(this).remove(); });
                updateCommentCounts(currentFeedId, -1);
            })
            .fail(function() { alert('Gagal hapus komentar'); });
    });

    /* ---------- Build comment HTML ---------- */
    function buildCommentHtml(comment) {
        const avatar = comment.user.profile?.avatar
            ? `/storage/${comment.user.profile.avatar}`
            : '{{ asset("assets/img/default-avatar.png") }}';
        const time = moment(comment.created_at).fromNow();
        const isOwner = comment.user_id == {{ auth()->id() }};

        return `
        <div class="comment-item" data-comment-id="${comment.id}">
            <img src="${avatar}" alt="${comment.user.name}" class="comment-avatar">
            <div class="comment-right">
                <div class="comment-bubble">
                    <span class="comment-username">${comment.user.name}</span>
                    <p class="comment-text">${comment.content}</p>
                </div>
                <div class="comment-meta">
                    <span class="comment-time-label">${time}</span>
                    <button class="comment-action-btn btn-reply" data-parent-id="${comment.id}">Balas</button>
                    ${isOwner ? `<button class="comment-action-btn btn-delete" data-comment-id="${comment.id}">Hapus</button>` : ''}
                </div>
            </div>
        </div>`;
    }

    /* ---------- Share ---------- */
    $(document).on('click', '.share-btn', function() {
        const url = $(this).data('url');
        const fullUrl = window.location.origin + '/' + url;
        if (navigator.share) {
            navigator.share({ title: 'KAMCUP Feed', url: fullUrl });
        } else {
            navigator.clipboard.writeText(fullUrl).then(() => {
                // small toast
                const toast = $('<div>')
                    .text('Link disalin!')
                    .css({
                        position:'fixed', bottom:'1.5rem', left:'50%',
                        transform:'translateX(-50%)',
                        background:'#0d1117', color:'#fff',
                        padding:'0.5rem 1.25rem', borderRadius:'50px',
                        fontSize:'0.85rem', fontWeight:600, zIndex:9999,
                        boxShadow:'0 4px 20px rgba(0,0,0,0.25)'
                    })
                    .appendTo('body');
                setTimeout(() => toast.fadeOut(300, () => toast.remove()), 2000);
            });
        }
    });
});
</script>
@endpush
@endsection