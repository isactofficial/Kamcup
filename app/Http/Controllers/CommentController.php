<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Article $article)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = $article->comments()->create([
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);

        // Load the user relationship for the response
        $comment->load('user');

        // Return JSON for AJAX requests (check both Content-Type and X-Requested-With)
        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil ditambahkan',
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user' => [
                        'name' => $comment->user->name,
                        'id' => $comment->user->id,
                    ],
                    'created_at' => $comment->created_at->diffForHumans(),
                    'formatted_date' => $comment->created_at->diffForHumans(),
                ]
            ], 201);
        }

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan');
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
            'content' => $request->content,
        ]);

        // Return JSON for AJAX requests (check both Content-Type and X-Requested-With)
        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil diperbarui',
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Komentar berhasil diperbarui');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        // Return JSON for AJAX requests (check both Content-Type and X-Requested-With)
        if (request()->expectsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil dihapus'
            ]);
        }

        return redirect()->back()->with('success', 'Komentar berhasil dihapus');
    }
} 