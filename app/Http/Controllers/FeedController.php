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
    public function index()
    {
        $userId = auth()->id();

        $feeds = Feed::withLikesCount()
            ->withCommentsCount()
            ->withJoinsCount()
            ->when($userId, function ($query) use ($userId) {
                return $query->with([
                    'likes' => function ($q) use ($userId) {
                        $q->where('user_id', $userId);
                    },
                    'joinedBy' => function ($q) use ($userId) {
                        $q->where('user_id', $userId);
                    }
                ]);
            })
            ->latest()
            ->paginate(10);

        return view('User2026.feeds', compact('feeds'));
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

