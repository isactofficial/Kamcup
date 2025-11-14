<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model ini merepresentasikan data di tabel 'team_wins'.
 */
class TeamWin extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'team_wins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'team_id',
        'tournament_id', // Asumsi ada kolom ini
        'wins',          // Asumsi ada kolom ini
        'draws',         // Asumsi ada kolom ini
        'losses',        // Asumsi ada kolom ini
        'points',        // Asumsi ada kolom ini
        // Sesuaikan daftar ini dengan kolom-kolom di tabel 'team_wins' Anda
    ];

    /**
     * Mendapatkan data tim (team) yang memiliki data kemenangan ini.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * (Opsional) Mendapatkan data turnamen yang terkait.
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}