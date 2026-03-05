<?php

use App\Http\Controllers\CommentController;
use App\Models\Article;
use Illuminate\Support\Facades\Route;

// Test route untuk debug comment submission
Route::get('/test-comment', function () {
    // Get first article
    $article = Article::first();
    
    if (!$article) {
        return response()->json(['error' => 'No article found'], 404);
    }
    
    return view('test-comment', ['article' => $article]);
})->middleware(['auth'])->name('test.comment');

// Test POST comment
Route::post('/test-comment-submit', function () {
    $request = request();
    
    dd([
        'method' => $request->method(),
        'expects_json' => $request->expectsJson(),
        'has_ajax_header' => $request->header('X-Requested-With') === 'XMLHttpRequest',
        'headers' => $request->header(),
        'content' => $request->input('content'),
        'csrf_token' => $request->input('_token'),
    ]);
})->middleware(['auth'])->name('test.comment.submit');
