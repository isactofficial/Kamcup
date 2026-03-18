<?php

namespace App\Models;

use App\Models\FeedComment;
use App\Models\FeedLike;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'community_id',
        'title',
        'content',
        'image',
    ];

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(FeedComment::class);
    }

    public function likes()
    {
        return $this->hasMany(FeedLike::class);
    }

    public function likedBy(User $user)
    {
        return $this->likes->isNotEmpty();
    }

    public function likeCount()
    {
        return $this->likes_count ?? $this->likes->count();
    }

    public function scopeWithLikesCount($query)
    {
        return $query->withCount('likes as likes_count');
    }

    public function scopeWithCommentsCount($query)
    {
        return $query->withCount('comments as comments_count');
    }

    public function scopeLikedByUser($query, $userId)
    {
        return $query->whereHas('likes', function($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }
}
