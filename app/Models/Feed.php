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
        'title',
        'content',
        'image',
    ];

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
        return $this->likes->contains('user_id', $user->id);
    }

    public function likeCount()
    {
        return $this->likes->count();
    }
}
