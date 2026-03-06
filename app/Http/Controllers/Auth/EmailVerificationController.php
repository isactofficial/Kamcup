<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerificationMail;
use App\Models\EmailVerification;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class EmailVerificationController extends Controller
{
    /**
     * Send OTP to email for registration verification
     * For testing: OTP is returned in response
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Check if email is already registered
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Email sudah terdaftar. Silakan login atau gunakan email lain.',
            ], 422);
        }

        // Check if there's already a valid OTP for this email
        $existingVerification = EmailVerification::where('email', $request->email)
            ->where('is_verified', false)
            ->where('expires_at', '>', now())
            ->first();

        if ($existingVerification) {
            // If valid OTP already exists, just return success without generating new one
            session(['verification_email' => $request->email]);
            return response()->json([
                'success' => true,
                'message' => 'Kode OTP telah dikirim ke email Anda. Berlaku 60 detik.',
                'expires_in' => 60,
                'otp' => $existingVerification->otp, // For testing only - remove in production!
            ]);
        }

        // Generate new OTP
        $otp = EmailVerification::generateOtp($request->email);

        // Try to send email, but don't fail if email service is not configured
        try {
            Mail::to($request->email)->send(new OtpVerificationMail($otp));
        } catch (\Exception $e) {
            // Log error for debugging
            \Illuminate\Support\Facades\Log::error('Failed to send OTP email: ' . $e->getMessage());
        }

        // Store email in session for verification step
        session(['verification_email' => $request->email]);

        // Return success - include OTP for testing purposes
        return response()->json([
            'success' => true,
            'message' => 'Kode OTP telah dikirim ke email Anda. Berlaku 60 detik.',
            'expires_in' => 60,
            'otp' => $otp, // For testing only - remove in production!
        ]);
    }

    /**
     * Verify OTP and create user account
     */
    public function verifyAndRegister(Request $request)
    {
        // If this is just OTP verification (step 2), only validate OTP
        if ($request->filled('otp') && $request->otp !== 'verified') {
            $email = session('verification_email');

            if (!$email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi verifikasi telah berakhir. Silakan mulai ulang proses registrasi.',
                ], 422);
            }

            // Verify OTP
            if (!EmailVerification::verify($email, $request->otp)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode OTP tidak valid atau sudah expired. Silakan minta kode baru.',
                ], 422);
            }

            // Mark as verified in session
            session(['otp_verified' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Email berhasil diverifikasi!',
            ]);
        }

        // Full registration (step 3)
        $email = session('verification_email');
        $otpVerified = session('otp_verified');

        if (!$email || !$otpVerified) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan verifikasi email terlebih dahulu.',
            ], 422);
        }

        // Validate registration data
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'gender' => 'required|string|in:male,female,other',
            'phone_number' => 'required|regex:/^[0-9]+$/|min:8|max:15',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'phone_number.regex' => 'Nomor telepon hanya boleh berisi angka.',
            'phone_number.min' => 'Nomor telepon minimal 8 digit.',
            'phone_number.max' => 'Nomor telepon maksimal 15 digit.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Create user and profile
        try {
            // Check if email already exists
            if (User::where('email', $email)->exists()) {
                // Clear session as email is already registered
                session()->forget('verification_email');
                session()->forget('otp_verified');
                
                \Illuminate\Support\Facades\Log::warning('Registration attempt with already registered email', [
                    'email' => $email,
                    'ip' => $request->ip()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Email ini sudah terdaftar. Silakan masuk dengan akun Anda atau gunakan email lain untuk registrasi baru.',
                    'error_code' => 'email_already_registered'
                ], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $email,
                'password' => md5($request->password),
                'role' => 'reader',
            ]);

            // Create user profile with full data
            UserProfile::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'email' => $email,
                'birthdate' => $request->birthdate,
                'gender' => $request->gender,
                'phone_number' => $request->phone_number,
            ]);

            // Clear session
            session()->forget('verification_email');
            session()->forget('otp_verified');

            // Login the user
            auth()->login($user);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil! Mengalihkan...',
                'redirect' => route('front.index'),
            ]);
        } catch (\Exception $e) {
            // Log error with full details for debugging
            \Illuminate\Support\Facades\Log::error('Registration failed: ' . $e->getMessage(), [
                'email' => $email,
                'exception_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Determine if this is a database error
            $message = 'Terjadi kesalahan saat membuat akun. Silakan coba lagi atau hubungi support.';
            $httpCode = 500;
            
            // Check for specific database errors
            if ($e instanceof \Illuminate\Database\QueryException) {
                if (str_contains($e->getMessage(), 'Duplicate')) {
                    $message = 'Data pengguna sudah ada dalam sistem. Silakan gunakan email atau data lain yang berbeda.';
                } elseif (str_contains($e->getMessage(), 'Integrity constraint')) {
                    $message = 'Terjadi kesalahan validasi data. Pastikan semua data sudah lengkap dan benar.';
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => $message,
                'error_code' => 'registration_failed',
                // Only show detailed error in development
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], $httpCode);
        }
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $email = session('verification_email');

        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi berakhir. Silakan mulai ulang proses registrasi.',
            ], 422);
        }

        // Generate new OTP for resend
        $otp = EmailVerification::generateOtp($email);

        try {
            Mail::to($email)->send(new OtpVerificationMail($otp));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to resend OTP: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP baru telah dikirim. Berlaku 60 detik.',
            'expires_in' => 60,
            'otp' => $otp, // For testing only
        ]);
    }
}

