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
}