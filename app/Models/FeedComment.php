<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'feed_id',
        'user_id',
        'content',
        'parent_id',
    ];

    public function feed()
    {
        return $this->belongsTo(Feed::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(FeedComment::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(FeedComment::class, 'parent_id');
    }
}
