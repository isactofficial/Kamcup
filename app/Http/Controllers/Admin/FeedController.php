<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeedRequest;
use App\Models\Feed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FeedController extends Controller
{
    public function index()
    {
        $feeds = Feed::selectRaw('feeds.*, 
                (SELECT COUNT(DISTINCT fl.user_id) FROM feed_likes fl WHERE fl.feed_id = feeds.id) as likes_count,
                (SELECT COUNT(*) FROM feed_comments fc WHERE fc.feed_id = feeds.id) as comments_count,
                (SELECT COUNT(DISTINCT fuj.user_id) FROM feed_user_joins fuj WHERE fuj.feed_id = feeds.id) as joins_count')
            ->with('user:id,name')
            ->whereNull('community_id') // Filter out community feeds
            ->latest()
            ->paginate(15);

        return view('Dashboard2026.feeds', compact('feeds'));
    }


    public function create()
    {
        return view('Dashboard2026.feeds.create');
    }

    public function store(StoreFeedRequest $request)
    {
        $data = $request->only(['title', 'content']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('feeds', 'public');
        }

        Feed::create($data);

        return redirect()->route('admin.feeds.index')->with('success', 'Feed berhasil ditambahkan!');
    }

    public function destroy(Feed $feed)
    {
        if ($feed->image) {
            Storage::disk('public')->delete($feed->image);
        }

        // Hapus child comments (replies) dulu
        $feed->comments()->each(function ($comment) {
            $comment->children()->delete();
        });
        // Baru hapus parent comments
        $feed->comments()->delete();

        $feed->delete();

        return redirect()->route('admin.feeds.index')->with('success', 'Feed berhasil dihapus!');
    }
}

