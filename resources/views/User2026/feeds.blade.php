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
}
.action-btn.active {
    background-color: rgba(203, 39, 134, 0.1);
    color: #cb2786;
}
.comment-section {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}
.comment-section.show {
    max-height: 1000px;
}
.comment-form {
    border-top: 1px solid #eee;
    padding-top: 1rem;
    margin-top: 1rem;
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
.reply-btn {
    background: none;
    border: none;
    color: #6c757d;
    font-size: 0.8rem;
    cursor: pointer;
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
            <div class="card feed-card">
                @if ($feed->image)
                <img src="{{ asset('storage/' . $feed->image) }}" class="feed-image" alt="{{ $feed->title }}">
                @endif
                <div class="card-body p-4">
                    @if ($feed->title)
                    <h5 class="card-title fw-bold mb-3" style="color: #00617a;">{{ $feed->title }}</h5>
                    @endif
                    <p class="card-text">{{ $feed->content }}</p>
                    <div class="feed-actions">
                        <button class="action-btn like-btn {{ $feed->likedBy(auth()->user()) ? 'liked' : '' }}" data-feed-id="{{ $feed->id }}">
                            <i class="far fa-heart"></i>
                            <span class="like-count">{{ $feed->likeCount() }}</span>
                        </button>
                        <button class="action-btn comment-toggle" data-feed-id="{{ $feed->id }}">
                            <i class="far fa-comment"></i>
                            <span>{{ $feed->comments->count() }}</span>
                        </button>
                        <button class="action-btn share-btn" data-url="{{ route('user2026.feeds') }}#feed-{{ $feed->id }}">
                            <i class="fas fa-share-alt"></i>
                            Bagikan
                        </button>
                    </div>

                    <div class="comment-section" id="comments-{{ $feed->id }}">
                        <div class="comment-form">
                            <div class="input-group">
                                <input type="text" class="form-control comment-input" placeholder="Tulis komentar...">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                        <div class="comments-list mt-3">
                            @foreach ($feed->comments->whereNull('parent_id') as $comment)
                            <div class="comment" data-comment-id="{{ $comment->id }}">
                                <img src="{{ $comment->user->profile?->avatar ?? asset('assets/img/default-avatar.png') }}" alt="{{ $comment->user->name }}" class="comment-avatar">
                                <div class="comment-content">
                                    <div class="comment-header">
                                        <span class="comment-user">{{ $comment->user->name }}</span>
                                        <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="mb-1">{{ $comment->content }}</p>
                                    <button class="reply-btn">Balas</button>
                                    @if ($comment->user_id === auth()->id())
                                    <button class="reply-btn text-danger delete-comment" data-comment-id="{{ $comment->id }}">Hapus</button>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Like functionality
    $('.like-btn').click(function() {
        const btn = $(this);
        const feedId = btn.data('feed-id');
        
        $.post(`/feeds/${feedId}/like`, {})
        .done(function(data) {
            btn.toggleClass('liked', data.liked);
            btn.find('.like-count').text(data.count);
            const icon = btn.find('i');
            icon.toggleClass('far fas', data.liked);
        });
    });

    // Toggle comments
    $('.comment-toggle').click(function() {
        const feedId = $(this).data('feed-id');
        $(`#comments-${feedId}`).toggleClass('show');
        $(this).toggleClass('active');
    });

    // Comment form
    $('.comment-input').on('keypress', function(e) {
        if (e.key === 'Enter') {
            const input = $(this);
            const feedId = input.closest('.comment-section').attr('id').replace('comments-', '');
            const content = input.val().trim();
            
            if (content) {
                $.post(`/feeds/${feedId}/comments`, { content: content })
                .done(function(comment) {
                    const commentHtml = `
                        <div class="comment" data-comment-id="${comment.id}">
                            <img src="${comment.user.profile ? comment.user.profile.avatar : '{{ asset('assets/img/default-avatar.png') }}'}" alt="${comment.user.name}" class="comment-avatar">
                            <div class="comment-content">
                                <div class="comment-header">
                                    <span class="comment-user">${comment.user.name}</span>
                                    <span class="comment-time">${moment().fromNow()}</span>
                                </div>
                                <p class="mb-1">${comment.content}</p>
                                <button class="reply-btn text-danger delete-comment" data-comment-id="${comment.id}">Hapus</button>
                            </div>
                        </div>
                    `;
                    $(`#comments-${feedId} .comments-list`).prepend(commentHtml);
                    input.val('');
                });
            }
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
                    $(`[data-comment-id="${commentId}"]`).remove();
                }
            });
        }
    });

    // Share
    $('.share-btn').click(function() {
        const url = $(this).data('url');
        if (navigator.share) {
            navigator.share({
                title: 'KAMCUP Feed',
                url: window.location.origin + '/' + url
            });
        } else {
            navigator.clipboard.writeText(window.location.origin + '/' + url);
            alert('Link disalin ke clipboard!');
        }
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
@endpush
@endsection
