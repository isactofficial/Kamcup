<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchGame extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'tournament_id',
        'team1_id',
        'team2_id',
        'stage',
        'match_datetime',
        'status',
        'team1_score',
        'team2_score',
        'winner_id',
        'loser_id',
        'format',
        'location',
    ];

    protected $casts = [
        'match_datetime' => 'datetime',
        'team1_score' => 'integer',
        'team2_score' => 'integer',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class, 'tournament_id');
    }

    public function team1()
    {
        return $this->belongsTo(Team::class, 'team1_id');
    }

    public function team2()
    {
        return $this->belongsTo(Team::class, 'team2_id');
    }

    public function winner()
    {
        return $this->belongsTo(Team::class, 'winner_id');
    }

    public function loser()
    {
        return $this->belongsTo(Team::class, 'loser_id');
    }

    public function scopeByTournament($query, $tournamentId)
    {
        return $query->where('tournament_id', $tournamentId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function getTeam1NameAttribute()
    {
        return $this->team1 ? $this->team1->name : 'TBD';
    }

    public function getTeam2NameAttribute()
    {
        return $this->team2 ? $this->team2->name : 'TBD';
    }

    public function getWinnerNameAttribute()
    {
        return $this->winner ? $this->winner->name : '-';
    }
}