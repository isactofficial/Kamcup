<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'category',
        'description',
        'status',
        'is_official',
        'image'
    ];

    /**
     * Get the user that created the community.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The users that belong to the community.
     */
    public function members()
    {
        return $this->belongsToMany(User::class)->withPivot('role', 'status')->withTimestamps();
    }
}
