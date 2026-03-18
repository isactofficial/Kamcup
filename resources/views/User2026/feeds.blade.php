@extends('layouts.master_nav')

@section('title', 'Feeds - KAMCUP')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
<style>
.feed-card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 15px;
    margin-bottom: 1.5rem;
    overflow: hidden;
    transition: all 0.3s ease;
}
.feed-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
.feed-image {
    height: 250px;
    object-fit: cover;
    width: 100%;
}
.feed-actions {
    display: flex;
    gap: 1rem;
    padding: 1rem 0;
    border-top: 1px solid #eee;
}
.action-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: none;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
}
.action-btn:hover {
    background-color: rgba(203, 39, 134, 0.1);
    color: #cb2786;
}
.action-btn.liked {
    color: #cb2786;
    background-color: rgba(203, 39, 134, 0.1);
}
.comment {
    display: flex;
    gap: 0.75rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f8f9fa;
}
.comment:last-child {
    border-bottom: none;
}
.comment-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}
.comment-content {
    flex: 1;
}
.comment-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.25rem;
}
.comment-user {
    font-weight: 600;
    color: #00617a;
}
.comment-time {
    font-size: 0.8rem;
    color: #6c757d;
}
.reply-btn, .delete-comment {
    background: none;
    border: none;
    color: #6c757d;
    font-size: 0.8rem;
    cursor: pointer;
    margin-right: 0.5rem;
}
.delete-comment {
    color: #dc3545 !important;
}
.modal-header-feed {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}
.modal-comment-form {
    border-top: 1px solid #dee2e6;
    padding-top: 1rem;
}
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold mb-3" style="color: #cb2786;">
                    <i class="fas fa-rss me-3"></i>Feeds KAMCUP
                </h1>
                <p class="lead text-muted mb-4">Update terbaru dari admin KAMCUP</p>
                <a href="{{ route('profile.index') }}" class="btn btn-lg px-5" style="background-color: #00617a; color: #fff; border-radius: 50px; font-weight: 600;">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Profile
                </a>
            </div>

            @forelse ($feeds as $feed)
            <div class="card feed-card" id="feed-{{ $feed->id }}">
                @if ($feed->image)
                <img src="{{ asset('storage/' . $feed->image) }}" class="feed-image" alt="{{ $feed->title }}" data-feed-id="{{ $feed->id }}">
                @endif
                <div class="card-body p-4">
                    @if ($feed->title)
                    <h5 class="card-title fw-bold mb-3" style="color: #00617a;">{{ $feed->title }}</h5>
                    @endif
                    <p class="card-text">{{ $feed->content }}</p>
                    <div class="feed-actions">
                        <button class="action-btn like-btn {{ $feed->likedBy(auth()->user()) ? 'liked' : '' }}" data-feed-id="{{ $feed->id }}">
                            <i class="far fa-heart"></i>
                            <span class="like-count">{{ $feed->likes_count }}</span>
                        </button>
                        <button class="action-btn comment-toggle" data-bs-toggle="modal" data-bs-target="#feedCommentModal" data-feed-id="{{ $feed->id }}" data-feed-title="{{ $feed->title }}" data-feed-image="{{ $feed->image }}">
                            <i class="far fa-comment"></i>
                            <span class="comment-count">{{ $feed->comments_count }}</span>
                        </button>
                        <button class="action-btn share-btn" data-url="{{ route('user2026.feeds') }}#feed-{{ $feed->id }}">
                            <i class="fas fa-share-alt"></i>
                            Bagikan
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fas fa-rss fa-5x text-muted mb-4"></i>
                <h3 class="text-muted">Belum ada feeds</h3>
                <p class="text-muted">Admin akan segera mengupdate feeds terbaru.</p>
            </div>
            @endforelse

            <div class="d-flex justify-content-center">
                {{ $feeds->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Instagram-style Comment Modal -->
<div class="modal fade" id="feedCommentModal" tabindex="-1" aria-labelledby="feedCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-start">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="modal-feed-preview p-3 border-bottom">
                    <img id="modal-feed-image" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;" alt="Feed preview">
                    <h6 id="modal-feed-title" class="mt-2 fw-bold" style="color: #00617a;"></h6>
                </div>
                <div id="modal-comments-list" class="p-3" style="max-height: 400px; overflow-y: auto;">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading komentar...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-0">
                <div class="modal-comment-form p-3">
                    <div class="input-group">
                        <input type="text" id="modal-comment-input" class="form-control" placeholder="Tulis komentar..." maxlength="500">
                        <button class="btn btn-primary" id="modal-comment-send" type="button">
                            <i class="fas fa-paper-plane"></i>
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
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let currentFeedId = null;

    // Like functionality
    $(document).on('click', '.like-btn', function() {
        const $btn = $(this);
        const feedId = $btn.data('feed-id');
        
        $.post(`/feeds/${feedId}/like`, {})
            .done(function(data) {
                $btn.toggleClass('liked', data.liked);
                $btn.find('.like-count').text(data.count);
                const $icon = $btn.find('i');
                $icon.removeClass('far fas').addClass(data.liked ? 'fas' : 'far');
            })
            .fail(function(xhr) {
                console.error('Like failed:', xhr);
                alert('Gagal like/unlike');
            });
    });

    // Update comment count on all buttons when modal updates count
    function updateCommentCounts(feedId, delta = 0) {
        $(`.comment-toggle[data-feed-id="${feedId}"] .comment-count`).text(parseInt($(`.comment-toggle[data-feed-id="${feedId}"] .comment-count`).text()) + delta);
    }

    // Modal events
    $('#feedCommentModal').on('show.bs.modal', function (e) {
        const trigger = $(e.relatedTarget);
        currentFeedId = trigger.data('feed-id');
        const title = trigger.data('feed-title') || 'Feed';
        const image = trigger.data('feed-image');
        
        $('#modal-feed-title').text(title);
        if (image) {
            $('#modal-feed-image').show().attr('src', `/storage/${image}`);
        } else {
            $('#modal-feed-image').hide();
        }

        // Load comments
        $('#modal-comments-list').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading komentar...</p>
            </div>
        `);
        
        $.get(`/feeds/${currentFeedId}/comments`)
            .done(function(comments) {
                let html = '';
                if (comments.length === 0) {
                    html = '<div class="text-center py-4 text-muted">Belum ada komentar</div>';
                } else {
                    comments.forEach(comment => {
                        html += buildCommentHtml(comment);
                    });
                }
                $('#modal-comments-list').html(html);
            })
            .fail(function() {
                $('#modal-comments-list').html('<div class="text-center py-4 text-danger">Gagal memuat komentar</div>');
            });
    });

    $('#modal-comment-send').click(function() {
        const content = $('#modal-comment-input').val().trim();
        if (!content || !currentFeedId) return;

        $.post(`/feeds/${currentFeedId}/comments`, { content: content })
            .done(function(comment) {
                // Add to top
                $('#modal-comments-list').prepend(buildCommentHtml(comment));
                $('#modal-comment-input').val('');
                updateCommentCounts(currentFeedId, 1);
            })
            .fail(function(xhr) {
                console.error('Comment failed:', xhr);
                alert('Gagal posting komentar');
            });
    });

    $('#modal-comment-input').on('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            $('#modal-comment-send').click();
        }
    });

    // Delete comment
    $(document).on('click', '.delete-comment', function() {
        const commentId = $(this).data('comment-id');
        if (confirm('Hapus komentar ini?')) {
            $.ajax({
                url: `/feeds/${commentId}/comment`,
                type: 'DELETE',
                success: function() {
                    $(`[data-comment-id="${commentId}"]`).fadeOut(300, function() {
                        $(this).remove();
                    });
                    updateCommentCounts(currentFeedId, -1);
                },
                error: function() {
                    alert('Gagal hapus komentar');
                }
            });
        }
    });

    function buildCommentHtml(comment) {
        const avatar = comment.user.profile?.avatar ? `/storage/${comment.user.profile.avatar}` : '{{ asset("assets/img/default-avatar.png") }}';
        const time = moment(comment.created_at).fromNow();
        let html = `
            <div class="comment" data-comment-id="${comment.id}">
                <img src="${avatar}" alt="${comment.user.name}" class="comment-avatar">
                <div class="comment-content">
                    <div class="comment-header">
                        <span class="comment-user">${comment.user.name}</span>
                        <span class="comment-time">${time}</span>
                    </div>
                    <p class="mb-1">${comment.content}</p>
        `;
        html += `    <button class="reply-btn" data-parent-id="${comment.id}">Balas</button>`;
        if (comment.user_id == {{ auth()->id() }}) {
            html += ` <button class="delete-comment" data-comment-id="${comment.id}">Hapus</button>`;
        }
        html += `
                </div>
            </div>
        `;
        return html;
    }

    // Share
    $(document).on('click', '.share-btn', function() {
        const url = $(this).data('url');
        if (navigator.share) {
            navigator.share({
                title: 'KAMCUP Feed',
                url: window.location.origin + '/' + url
            });
        } else {
            navigator.clipboard.writeText(window.location.origin + '/' + url).then(() => {
                alert('Link disalin!');
            });
        }
    });
});
</script>
@endpush
@endsection
