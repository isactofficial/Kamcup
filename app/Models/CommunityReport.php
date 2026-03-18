<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'community_id',
        'reason',
        'chats_snapshot', // JSON of last 5 messages
        'status', // pending, resolved, dismissed
    ];

    protected $casts = [
        'chats_snapshot' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }
}
