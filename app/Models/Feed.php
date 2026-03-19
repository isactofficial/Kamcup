<?php

namespace App\Models;

use App\Models\Community;
use App\Models\FeedComment;
use App\Models\FeedLike;
use App\Models\FeedUserJoin;
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
        'meet_date',
        'meet_location',
        'meet_max_people',
        'meet_description',
    ];

    protected $casts = [
        'meet_date' => 'datetime',
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
     * Check if specific user liked this feed
     */
    public function likedBy(User $user): bool
    {
        if (isset($this->attributes['current_user_liked'])) {
            return (bool) $this->attributes['current_user_liked'];
        }

        if ($this->relationLoaded('likes')) {
            return $this->likes->contains('user_id', $user->id);
        }
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Accessor for current_user_liked (from controller subquery)
     */
    public function getCurrentUserLikedAttribute(): bool
    {
        return (bool) ($this->attributes['current_user_liked'] ?? false);
    }

    public function likeCount(): int
    {
        return $this->attributes['likes_count'] ?? $this->likes()->count();
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

    /**
     * Users who joined this meet
     */
    public function joinedBy()
    {
        return $this->belongsToMany(User::class, 'feed_user_joins')
                    ->using(FeedUserJoin::class)
                    ->withPivot('joined_at')
                    ->withTimestamps()
                    ->orderByPivot('joined_at');
    }

    /**
     * Check if user joined this meet
     */
    public function joinedByUser(User $user): bool
    {
        if (isset($this->attributes['current_user_joined'])) {
            return (bool) $this->attributes['current_user_joined'];
        }

        if ($this->relationLoaded('joinedBy')) {
            return $this->joinedBy->contains('id', $user->id);
        }
        return $this->joinedBy()->where('user_id', $user->id)->exists();
    }

    /**
     * Accessor for current_user_joined (from controller subquery)
     */
    public function getCurrentUserJoinedAttribute(): bool
    {
        return (bool) ($this->attributes['current_user_joined'] ?? false);
    }

    /**
     * Meets only scope
     */
    public function scopeMeets($query)
    {
        return $query->whereNotNull('meet_date');
    }

    /**
     * Accessor for joins_count — FIX: pakai $this->attributes untuk hindari infinite loop
     */
    public function getJoinsCountAttribute()
    {
        return $this->attributes['joins_count'] ?? $this->joinedBy()->count();
    }

    public function scopeWithJoinsCount($query)
    {
        return $query->withCount('joinedBy as joins_count');
    }
}