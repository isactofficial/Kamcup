<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreFeedRequest;
use App\Models\Feed;
use App\Models\FeedComment;
use App\Models\FeedUserJoin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $filter = $request->get('filter', 'all');

        $query = Feed::query()
            ->with(['user.profile']);

        if ($filter === 'feeds') {
            $query->whereNull('meet_date');
        } elseif ($filter === 'meets') {
            $query->whereNotNull('meet_date');
        }

        // Pre-load counts and user flags in ONE selectRaw to avoid nesting
        $extraSelect = ', 
            (SELECT COUNT(DISTINCT fl.user_id) FROM feed_likes fl WHERE fl.feed_id = feeds.id) as likes_count,
            (SELECT COUNT(*) FROM feed_comments fc WHERE fc.feed_id = feeds.id) as comments_count,
            (SELECT COUNT(DISTINCT fuj.user_id) FROM feed_user_joins fuj WHERE fuj.feed_id = feeds.id) as joins_count';

        $bindings = [];
        if ($userId) {
            $extraSelect .= ', EXISTS(SELECT 1 FROM feed_likes WHERE feed_id = feeds.id AND user_id = ?) as current_user_liked,
            EXISTS(SELECT 1 FROM feed_user_joins WHERE feed_id = feeds.id AND user_id = ?) as current_user_joined';
            $bindings = [$userId, $userId];
        } else {
            $extraSelect .= ', 0 as current_user_liked, 0 as current_user_joined';
        }

        $query->selectRaw('feeds.*' . $extraSelect, $bindings);

        $feeds = $query->latest()->paginate(10);

        $filterTitles = [
            'all' => 'Semua Aktivitas',
            'feeds' => 'Feeds',
            'meets' => 'Meets'
        ];
        $title = $filterTitles[$filter] ?? 'Feeds';
        $feedCount = $feeds->total();

        return view('User2026.feeds', compact('feeds', 'filter', 'title', 'feedCount'));
    }

    public function like(Request $request, Feed $feed)
    {
        $userId = Auth::id();

        $exists = $feed->likes()->where('user_id', $userId)->exists();

        if ($exists) {
            // Hapus via query builder — bukan model instance
            // karena tabel feed_likes tidak punya kolom 'id'
            $feed->likes()->where('user_id', $userId)->delete();
            $count = $feed->likes()->count();
            return response()->json(['liked' => false, 'count' => $count]);
        }

        $feed->likes()->create(['user_id' => $userId]);
        $count = $feed->likes()->count();
        return response()->json(['liked' => true, 'count' => $count]);
    }

    public function commentStore(Request $request, Feed $feed)
    {
        $request->validate([
            'content'   => 'required|string|max:500',
            'parent_id' => 'nullable|exists:feed_comments,id',
        ]);

        $comment = $feed->comments()->create([
            'user_id'   => Auth::id(),
            'content'   => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        $comment->load('user.profile');

        return response()->json($comment);
    }

    public function commentDelete(FeedComment $comment)  // ← FIX #1 berlaku di sini
    {
        // FIX #2: pastikan hanya pemilik komentar yang bisa hapus
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $comment->delete();

        return response()->json(['success' => true]);
    }

    public function comments(Feed $feed)
    {
        $comments = $feed->comments()
            ->whereNull('parent_id')
            ->with(['user.profile', 'children.user.profile'])
            ->latest()
            ->limit(50)
            ->get();

        return response()->json($comments);
    }

    /**
     * Store new user meet (as feed)
     */
    public function store(UserStoreFeedRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('feeds', 'public');
        }

        $feed = Feed::create($data);

        $feed->load(['user.profile', 'joinedBy']);

        return response()->json([
            'success' => true,
            'feed' => $feed,
            'message' => 'Meets berhasil dibuat dan diposting!'
        ]);
    }

    /**
     * Toggle user join meet
     */
    public function join(Request $request, Feed $feed)
    {
        $userId = auth()->id();

        $join = FeedUserJoin::where('feed_id', $feed->id)
                           ->where('user_id', $userId)
                           ->first();

        if ($join) {
            // Unjoin
            $join->delete();
            $count = $feed->joins_count - 1;
            return response()->json([
                'joined' => false,
                'count' => $count,
                'message' => 'Kamu batal ikut meets'
            ]);
        }

        // Check if meet is full
        if ($feed->meet_max_people && $feed->joins_count >= $feed->meet_max_people) {
            return response()->json([
                'error' => true,
                'message' => 'Meets sudah penuh!'
            ], 422);
        }

        // Join
        FeedUserJoin::create([
            'feed_id' => $feed->id,
            'user_id' => $userId
        ]);

        $count = $feed->joins_count + 1;
        return response()->json([
            'joined' => true,
            'count' => $count,
            'message' => 'Kamu ikut meets!'
        ]);
    }
}

