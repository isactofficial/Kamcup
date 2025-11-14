<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model ini merepresentasikan data di tabel 'volleyball_matches'.
 */
class VolleyballMatch extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'volleyball_matches';

    /**
     * The attributes that are mass assignable.
     * (Sesuaikan ini dengan kolom di tabel Anda)
     * @var array<int, string>
     */
    protected $fillable = [
        'tournament_id',
        'team_home_id',
        'team_away_id',
        'match_datetime',
        'status',
        'score_home',
        'score_away',
        'winner_id',
        // ... kolom lain ...
    ];

    /**
     * Mendapatkan data tim (team) yang bermain sebagai tuan rumah (home).
     */
    public function teamHome()
    {
        return $this->belongsTo(Team::class, 'team_home_id');
    }

    /**
     * Mendapatkan data tim (team) yang bermain sebagai tamu (away).
     */
    public function teamAway()
    {
        return $this->belongsTo(Team::class, 'team_away_id');
    }
}