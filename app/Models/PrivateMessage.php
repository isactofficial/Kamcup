<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivateMessage extends Model
{
protected $fillable = ['sender_id', 'receiver_id', 'message', 'image_path', 'is_read', 'reply_message_id'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function replyTo()
    {
        return $this->belongsTo(self::class, 'reply_message_id');
    }

    public function replies()
    {
        return $this->hasMany(self::class, 'reply_message_id');
    }
}
