<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'otp',
        'expires_at',
        'is_verified',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    /**
     * Check if OTP is valid and not expired
     */
    public function isValid(): bool
    {
        return !$this->is_verified && $this->expires_at->isFuture();
    }

    /**
     * Generate a new OTP for the email
     */
    public static function generateOtp(string $email): string
    {
        // Delete any existing unverified OTPs for this email
        self::where('email', $email)
            ->where('is_verified', false)
            ->delete();

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Create new verification record
        self::create([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => now()->addSeconds(60),
        ]);

        return $otp;
    }

    /**
     * Verify the OTP
     */
    public static function verify(string $email, string $otp): bool
    {
        $verification = self::where('email', $email)
            ->where('otp', $otp)
            ->where('is_verified', false)
            ->first();

        if (!$verification) {
            return false;
        }

        if (!$verification->isValid()) {
            return false;
        }

        // Mark as verified
        $verification->update(['is_verified' => true]);

        return true;
    }
}

