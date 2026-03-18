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
        $feeds = Feed::withCount(['likes', 'comments', 'joinedBy as joins_count'])
            ->with('user:id,name')
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

        $feed->delete();

        return redirect()->route('admin.feeds.index')->with('success', 'Feed berhasil dihapus!');
    }
}

