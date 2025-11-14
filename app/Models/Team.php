<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'logo',
        'name',
        'manager_name',
        'contact',
        'location',
        'gender_category',
        'member_count',
        'description',
        'status' // INI YANG DITAMBAHKAN
    ];

    /**
     * Dapatkan pengguna (user) yang memiliki tim ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Dapatkan anggota (members) dari tim ini.
     */
    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }

    /**
     * Dapatkan registrasi turnamen untuk tim ini.
     */
    public function registrations()
    {
        return $this->hasMany(TournamentRegistration::class);
    }

    /**
     * Dapatkan turnamen yang terkait dengan tim ini.
     * (Catatan: Ini mungkin relasi yang aneh jika satu tim bisa ikut banyak turnamen.
     * Mungkin Anda maksud relasi ini ada di model TournamentRegistration?)
     * Jika Anda yakin, ini bisa dipertahankan.
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * =========================================================
     * INI ADALAH TAMBAHAN UNTUK MEMPERBAIKI ERROR HAPUS TIM (team_wins)
     * =========================================================
     *
     * Mendapatkan data kemenangan (wins) yang terkait dengan tim ini.
     * Relasi ini merujuk ke tabel 'team_wins'.
     */
    public function wins()
    {
        // PENTING: Saya berasumsi model Anda untuk tabel 'team_wins'
        // bernama 'TeamWin'. Jika nama modelnya lain (misal: 'Win'),
        // ganti 'TeamWin::class' dengan nama model yang benar.

        // --- PERBAIKAN ---
        // Menghapus 'App\Models\' untuk memperbaiki error duplikasi namespace
        return $this->hasMany(TeamWin::class);
    }

    /**
     * =========================================================
     * TAMBAHAN BARU UNTUK FIX ERROR VOLLEYBALL MATCHES
     * (Relasi ini yang hilang di file Anda sebelumnya)
     * =========================================================
     */

    /**
     * Mendapatkan semua pertandingan voli di mana tim ini adalah Tuan Rumah (Home).
     */
    public function homeVolleyballMatches()
    {
        // Merujuk ke Model VolleyballMatch::class dengan foreign key 'team_home_id'
        return $this->hasMany(VolleyballMatch::class, 'team_home_id');
    }

    /**
     * Mendapatkan semua pertandingan voli di mana tim ini adalah Tamu (Away).
     */
    public function awayVolleyballMatches()
    {
        // Merujuk ke Model VolleyballMatch::class dengan foreign key 'team_away_id'
        return $this->hasMany(VolleyballMatch::class, 'team_away_id');
    }
}