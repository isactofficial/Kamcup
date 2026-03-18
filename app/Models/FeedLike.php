<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'feed_id',
        'user_id',
    ];

    // Tabel ini tidak punya kolom 'id' — composite key dari feed_id + user_id
    public $incrementing = false;
    protected $primaryKey = null;

    public function feed()
    {
        return $this->belongsTo(Feed::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}