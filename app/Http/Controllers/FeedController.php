<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        
        $feeds = Feed::withLikesCount()
            ->withCommentsCount()
            ->when($userId, function ($query) use ($userId) {
                return $query->with(['likes' => function($q) use ($userId) {
                    $q->where('user_id', $userId);
                }]);
            })
            ->latest()
            ->paginate(10);

        return view('User2026.feeds', compact('feeds'));
    }

    public function like(Request $request, Feed $feed)
    {
        $user = Auth::user();

        $like = $feed->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $count = $feed->likes()->count();
            return response()->json(['liked' => false, 'count' => $count]);
        }

        $feed->likes()->create(['user_id' => $user->id]);
        $count = $feed->likes()->count();
        return response()->json(['liked' => true, 'count' => $count]);
    }

    public function commentStore(Request $request, Feed $feed)
    {
        $request->validate([
            'content' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:feed_comments,id'
        ]);

        $comment = $feed->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id
        ]);

        $comment->load('user.profile');

        return response()->json($comment);
    }

    public function commentDelete(FeedComment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403);
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
}

