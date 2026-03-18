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

    /**
     * FIX #3: cek apakah user SPESIFIK sudah like.
     * Sebelumnya: $this->likes->isNotEmpty() → selalu true kalau ada siapapun yang like.
     * Sekarang: filter berdasarkan user_id yang dikirim.
     */
    public function likedBy(User $user): bool
    {
        // Kalau likes sudah di-eager load (via withLikesCount + with['likes']),
        // gunakan koleksi yang sudah ada agar tidak query ulang.
        if ($this->relationLoaded('likes')) {
            return $this->likes->contains('user_id', $user->id);
        }

        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function likeCount(): int
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
        return $query->whereHas('likes', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }
}