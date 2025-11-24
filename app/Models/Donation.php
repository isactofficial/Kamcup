<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'donor_name',
        'email',
        'amount',
        'proof_image',
        'foto_donatur',
        'message',
        'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Accessors
    public function getFormattedCreatedAtAttribute()
    {
        // =================================================================
        // PERBAIKAN DI SINI:
        // Tambahkan ->timezone('Asia/Jakarta') untuk konversi ke WIB
        // =================================================================
        if ($this->created_at) {
            return $this->created_at->timezone('Asia/Jakarta')->format('d F Y H:i');
        }
        return 'N/A'; // Pengaman jika created_at null
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge badge-warning">Pending</span>',
            'approved' => '<span class="badge badge-success">Disetujui</span>',
            'rejected' => '<span class="badge badge-danger">Ditolak</span>',
        ];
        
        return $badges[$this->status] ?? $badges['pending'];
    }

    public function getProofImageUrlAttribute()
    {
        if ($this->proof_image) {
            return asset('storage/' . $this->proof_image);
        }
        return null;
    }

    public function getFotoDonaturUrlAttribute()
    {
        if ($this->foto_donatur) {
            return asset('storage/' . $this->foto_donatur);
        }
        return null;
    }

    // Methods
    public function approve()
    {
        $this->status = 'approved';
        return $this->save();
    }

    public function reject()
    {
        $this->status = 'rejected';
        return $this->save();
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }
}