<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar - KAMCUP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('assets/img/lgo.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* KAMCUP Brand Colors */
        :root {
            --kamcup-pink: #cb2786;
            --kamcup-blue-green: #00617a;
            --kamcup-yellow: #f4b704;
            --kamcup-dark-text: #212529;
            --kamcup-light-text: #ffffff;
            --kamcup-light-bg: #f5f7fa;
            --kamcup-gradient-start: #f5f7fa;
            --kamcup-gradient-end: #e6f7f1;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--kamcup-gradient-start) 0%, var(--kamcup-gradient-end) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--kamcup-dark-text);
        }

        .card {
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(8px);
            background-color: rgba(255, 255, 255, 0.95);
            overflow: hidden;
        }

        .card-body {
            padding: 3rem;
        }

        .logo-container {
            width: 90px;
            height: 90px;
            background-color: rgba(244, 183, 4, 0.15);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 16px rgba(244, 183, 4, 0.3);
            transition: transform 0.3s ease;
        }

        .logo-container:hover {
            transform: scale(1.05);
        }

        .logo-icon {
            font-size: 3rem;
            color: var(--kamcup-yellow);
        }

        h1.h3 {
            color: var(--kamcup-blue-green);
            font-weight: 700 !important;
            font-size: 2.25rem;
        }

        .text-muted {
            color: #6c757d !important;
        }

        .form-control, .form-select {
            border-radius: 12px;
            padding: 0.85rem 1.25rem;
            border: 1px solid rgba(0, 97, 122, 0.3);
            background-color: var(--kamcup-light-bg);
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--kamcup-pink);
            box-shadow: 0 0 0 0.25rem rgba(203, 39, 134, 0.15);
            background-color: var(--kamcup-light-text);
        }

        .input-group-text {
            background-color: transparent;
            border: 1px solid rgba(0, 97, 122, 0.3);
            border-right: none;
            border-radius: 12px 0 0 12px;
            color: var(--kamcup-blue-green);
        }

        .form-control.border-start-0, .form-select.border-start-0 {
            border-left: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--kamcup-yellow) 0%, #d49c00 100%);
            border: none;
            border-radius: 12px;
            padding: 0.85rem 1.5rem;
            font-weight: 600;
            box-shadow: 0 6px 16px rgba(244, 183, 4, 0.3);
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--kamcup-dark-text);
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(244, 183, 4, 0.4);
            background: linear-gradient(135deg, #d49c00 0%, var(--kamcup-yellow) 100%);
        }

        .btn-primary:disabled {
            background-color: #e9ecef;
            color: #6c757d;
            box-shadow: none;
            cursor: not-allowed;
            transform: none;
        }

        .btn-outline-secondary {
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border: 1px solid #6c757d;
            color: #6c757d;
        }

        .btn-outline-secondary:hover:not(:disabled) {
            background-color: #6c757d;
            color: white;
        }

        .btn-outline-secondary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .alert {
            border-radius: 12px;
            border: none;
            font-weight: 500;
        }

        .alert-danger {
            background-color: rgba(203, 39, 134, 0.1);
            color: var(--kamcup-pink);
        }

        .text-primary {
            color: var(--kamcup-blue-green) !important;
        }

        .text-primary:hover {
            color: var(--kamcup-pink) !important;
        }

        .social-login {
            background: rgba(203, 39, 134, 0.1);
            border-radius: 12px;
            padding: 1rem;
            transition: all 0.3s ease;
            font-weight: 600;
            color: var(--kamcup-dark-text);
        }

        .social-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(203, 39, 134, 0.2);
            background: rgba(203, 39, 134, 0.2);
        }

        .social-icon {
            width: 24px;
            height: 24px;
            margin-right: 0.75rem;
        }

        .btn-back {
            background-color: var(--kamcup-blue-green);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 97, 122, 0.2);
            transition: all 0.3s ease;
            color: var(--kamcup-light-text);
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 97, 122, 0.3);
            background-color: #004b5c;
        }

        .btn-back i {
            font-size: 1.2rem;
            color: inherit;
        }

        .otp-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--kamcup-pink) 0%, #a01e6b 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .otp-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .success-icon i {
            font-size: 2.5rem;
            color: white;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-item {
            opacity: 0;
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }
        .delay-6 { animation-delay: 0.6s; }
        .delay-7 { animation-delay: 0.7s; }
        .delay-8 { animation-delay: 0.8s; }
        .delay-9 { animation-delay: 0.9s; }
        .delay-10 { animation-delay: 1.0s; }
        .delay-11 { animation-delay: 1.1s; }
        .delay-12 { animation-delay: 1.2s; }

        /* Step indicator */
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .step {
            display: flex;
            align-items: center;
        }

        .step-circle {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .step-circle.active {
            background: linear-gradient(135deg, var(--kamcup-pink) 0%, #a01e6b 100%);
            color: white;
        }

        .step-circle.completed {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: white;
        }

        .step-line {
            width: 50px;
            height: 3px;
            background-color: #e9ecef;
            margin: 0 5px;
        }

        .step-line.active {
            background: linear-gradient(135deg, var(--kamcup-pink) 0%, #a01e6b 100%);
        }

        .otp-input {
            letter-spacing: 12px;
            font-size: 1.75rem !important;
            text-align: center;
            font-weight: bold;
        }

        #otp-timer {
            font-size: 0.9rem;
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 1.5rem;
            }

            .logo-container, .otp-icon, .success-icon {
                width: 70px;
                height: 70px;
            }

            .logo-icon {
                font-size: 2.25rem;
            }

            h1.h3 {
                font-size: 1.8rem;
            }

            .form-control, .form-select {
                padding: 0.65rem 1rem;
            }

            .btn-primary, .btn-back {
                padding: 0.65rem 1rem;
                font-size: 0.95rem;
            }

            .step-line {
                width: 30px;
            }
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-4 py-sm-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-8 col-md-7 col-lg-5 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="logo-container animate-item delay-1">
                                <i class="fas fa-user-plus logo-icon"></i>
                            </div>
                            <h1 class="h3 fw-bold mb-1">Bergabunglah dengan KAMCUP!</h1>
                            <p class="text-muted mb-0">Daftar untuk mulai <strong>berkompetisi</strong> dan <strong>berkembang</strong> bersama kami.</p>
                        </div>

                        <!-- Step Indicator -->
                        <div class="step-indicator animate-item delay-2">
                            <div class="step">
                                <div class="step-circle active" id="step1-circle">1</div>
                            </div>
                            <div class="step-line" id="step1-line"></div>
                            <div class="step">
                                <div class="step-circle" id="step2-circle">2</div>
                            </div>
                            <div class="step-line" id="step2-line"></div>
                            <div class="step">
                                <div class="step-circle" id="step3-circle">3</div>
                            </div>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger d-flex align-items-center mb-3">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('verification.verify-and-register') }}" id="register-form">
                            @csrf
                            
                            <!-- Step 1: Email Input -->
                            <div id="step-email">
                                <div class="mb-3 animate-item delay-4">
                                    <label for="email" class="form-label fw-medium">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input type="email" name="email_temp" id="email" required
                                               class="form-control border-start-0"
                                               placeholder="nama@email.com">
                                    </div>
                                    <small class="text-muted">Kami akan mengirim kode verifikasi ke email ini.</small>
                                </div>

                                <button type="button" id="send-otp-btn" class="btn btn-primary w-100 mb-4 animate-item delay-5">
                                    <i class="fas fa-paper-plane me-2"></i> Kirim Kode OTP
                                </button>
                            </div>

                            <!-- Step 2: OTP Verification -->
                            <div id="step-otp" style="display: none;">
                                <div class="text-center mb-4">
                                    <div class="otp-icon mb-3 animate-item delay-3">
                                        <i class="fas fa-envelope-open-text"></i>
                                    </div>
                                    <h5 class="animate-item delay-4">Cek Email Anda</h5>
                                    <p class="text-muted small animate-item delay-5">Kami telah mengirim kode verifikasi ke<br><strong id="display-email"></strong></p>
                                </div>

                                <div class="mb-3 animate-item delay-6">
                                    <label for="otp" class="form-label fw-medium">Masukkan Kode OTP</label>
                                    <div class="input-group justify-content-center">
                                        <input type="text" name="otp" id="otp" required
                                               class="form-control text-center otp-input"
                                               placeholder="------" maxlength="6">
                                    </div>
                                    <div id="otp-timer" class="text-center mt-3 text-danger">
                                        <i class="fas fa-clock me-1"></i> <span id="countdown">60</span> detik
                                    </div>
                                </div>

                                <button type="button" id="verify-otp-btn" class="btn btn-primary w-100 mb-2 animate-item delay-7" disabled>
                                    <i class="fas fa-check-circle me-2"></i> Verifikasi
                                </button>

                                <button type="button" id="resend-otp-btn" class="btn btn-outline-secondary w-100 mb-4 animate-item delay-8" disabled>
                                    <i class="fas fa-redo me-2"></i> Kirim Ulang OTP
                                </button>
                            </div>

                            <!-- Step 3: Registration Form -->
                            <div id="step-register" style="display: none;">
                                <div class="text-center mb-4">
                                    <div class="success-icon mb-3 animate-item delay-3">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <h5 class="animate-item delay-4">Email Terverifikasi!</h5>
                                    <p class="text-muted small animate-item delay-5">Lanjutkan填写数据注册</p>
                                </div>

                                <div class="mb-3 animate-item delay-6">
                                    <label for="name" class="form-label fw-medium">Nama Lengkap</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" name="name" id="reg-name" required
                                               class="form-control border-start-0"
                                               placeholder="Masukkan nama lengkap Anda" value="{{ old('name') }}">
                                    </div>
                                </div>

                                <input type="hidden" name="email" id="reg-email">

                                <div class="mb-3 animate-item delay-7">
                                    <label for="birthdate" class="form-label fw-medium">Tanggal Lahir</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                        <input type="date" name="birthdate" id="birthdate" required
                                               class="form-control border-start-0" value="{{ old('birthdate') }}">
                                    </div>
                                </div>

                                <div class="mb-3 animate-item delay-8">
                                    <label for="gender" class="form-label fw-medium">Jenis Kelamin</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-venus-mars"></i>
                                        </span>
                                        <select name="gender" id="gender" required class="form-select border-start-0">
                                            <option value="" disabled selected>Pilih jenis kelamin Anda</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 animate-item delay-9">
                                    <label for="phone_number" class="form-label fw-medium">Nomor Telepon</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                        <input type="tel" name="phone_number" id="phone_number" required
                                               class="form-control border-start-0"
                                               placeholder="Contoh: 081234567890" value="{{ old('phone_number') }}"
                                               pattern="[0-9]*"
                                               oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                    </div>
                                </div>

                                <div class="mb-3 animate-item delay-10">
                                    <label for="password" class="form-label fw-medium">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" name="password" id="reg-password" required
                                               class="form-control border-start-0"
                                               placeholder="Masukkan password Anda">
                                    </div>
                                    <small class="text-muted">Minimal 6 karakter.</small>
                                </div>

                                <div class="mb-4 animate-item delay-11">
                                    <label for="password_confirmation" class="form-label fw-medium">Konfirmasi Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" name="password_confirmation" id="password_confirmation" required
                                               class="form-control border-start-0"
                                               placeholder="Ulangi password Anda">
                                    </div>
                                </div>

                                <button type="submit" id="register-btn" class="btn btn-primary w-100 mb-4 animate-item delay-12">
                                    <i class="fas fa-user-plus me-2"></i> Daftar Akun
                                </button>
                            </div>
                        </form>

                        <div class="text-center text-muted mb-4 animate-item delay-12">
                            Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none fw-medium text-primary">Masuk di sini</a>
                        </div>

                        <div class="text-center animate-item delay-12">
                            <p class="text-muted mb-3">Atau daftar dengan</p>
                            <div class="social-login">
                                <form action="{{ route('auth.google') }}" method="POST" id="google-register-form">
                                    @csrf
                                    <input type="hidden" name="temp_cart_data" class="temp-cart-input">
                                    <button type="submit" class="btn btn-link d-flex align-items-center justify-content-center text-decoration-none w-100 p-0 border-0" style="background: none;">
                                        <svg class="social-icon" viewBox="0 0 24 24">
                                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                        </svg>
                                        <span class="ms-2">Google</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="text-center mt-3 animate-item delay-12">
                            <a href="{{ route('front.index') }}" class="btn btn-back w-100">
                                <i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const sendOtpBtn = document.getElementById('send-otp-btn');
            const stepEmail = document.getElementById('step-email');
            const stepOtp = document.getElementById('step-otp');
            const stepRegister = document.getElementById('step-register');
            const displayEmail = document.getElementById('display-email');
            const otpInput = document.getElementById('otp');
            const verifyOtpBtn = document.getElementById('verify-otp-btn');
            const resendOtpBtn = document.getElementById('resend-otp-btn');
            const countdownEl = document.getElementById('countdown');
            const registerForm = document.getElementById('register-form');
            const registerBtn = document.getElementById('register-btn');
            const regEmail = document.getElementById('reg-email');
            
            // Step indicators
            const step1Circle = document.getElementById('step1-circle');
            const step2Circle = document.getElementById('step2-circle');
            const step3Circle = document.getElementById('step3-circle');
            const step1Line = document.getElementById('step1-line');
            const step2Line = document.getElementById('step2-line');
            
            let countdownInterval;
            let isOtpVerified = false;

            function showError(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: message,
                    confirmButtonColor: '#cb2786'
                });
            }

            function showSuccess(message) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: message,
                    confirmButtonColor: '#28a745',
                    timer: 2000,
                    showConfirmButton: false
                });
            }

            function updateStepIndicator(step) {
                if (step === 1) {
                    step1Circle.classList.add('active');
                    step1Circle.classList.remove('completed');
                    step2Circle.classList.remove('active', 'completed');
                    step3Circle.classList.remove('active', 'completed');
                    step1Line.classList.remove('active');
                    step2Line.classList.remove('active');
                } else if (step === 2) {
                    step1Circle.classList.remove('active');
                    step1Circle.classList.add('completed');
                    step1Circle.innerHTML = '<i class="fas fa-check"></i>';
                    step2Circle.classList.add('active');
                    step3Circle.classList.remove('active', 'completed');
                    step1Line.classList.add('active');
                    step2Line.classList.remove('active');
                } else if (step === 3) {
                    step1Circle.classList.remove('active');
                    step1Circle.classList.add('completed');
                    step2Circle.classList.remove('active');
                    step2Circle.classList.add('completed');
                    step2Circle.innerHTML = '<i class="fas fa-check"></i>';
                    step3Circle.classList.add('active');
                    step1Line.classList.add('active');
                    step2Line.classList.add('active');
                }
            }

            // Send OTP
            sendOtpBtn.addEventListener('click', async function() {
                const email = emailInput.value.trim();
                
                if (!email || !email.includes('@')) {
                    showError('Silakan masukkan email yang valid.');
                    return;
                }

                sendOtpBtn.disabled = true;
                sendOtpBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mengirim...';

                try {
                    const response = await fetch('{{ route("verification.send-otp") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ email: email })
                    });

                    const data = await response.json();

                    if (data.success) {
                        showSuccess(data.message);
                        displayEmail.textContent = email;
                        stepEmail.style.display = 'none';
                        stepOtp.style.display = 'block';
                        updateStepIndicator(2);
                        startCountdown();
                        otpInput.focus();
                    } else {
                        if (response.status === 422 && data.message && data.message.includes('sudah terdaftar')) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Email Sudah Terdaftar',
                                html: `<div class="text-start">
                                    <p>${data.message}</p>
                                    <p class="text-muted mt-3">Silakan masuk dengan akun Anda atau gunakan email lain.</p>
                                </div>`,
                                showCancelButton: true,
                                confirmButtonText: 'Ke Halaman Login',
                                cancelButtonText: 'Gunakan Email Lain',
                                confirmButtonColor: '#00617a',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '{{ route("login") }}';
                                }
                            });
                        } else {
                            showError(data.message);
                        }
                        sendOtpBtn.disabled = false;
                        sendOtpBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Kirim Kode OTP';
                    }
                } catch (error) {
                    showError('Terjadi kesalahan. Silakan coba lagi.');
                    sendOtpBtn.disabled = false;
                    sendOtpBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Kirim Kode OTP';
                }
            });

            // Start countdown timer
            function startCountdown() {
                let seconds = 60;
                countdownEl.textContent = seconds;
                resendOtpBtn.disabled = true;
                verifyOtpBtn.disabled = true;

                clearInterval(countdownInterval);
                countdownInterval = setInterval(() => {
                    seconds--;
                    countdownEl.textContent = seconds;

                    if (seconds <= 0) {
                        clearInterval(countdownInterval);
                        resendOtpBtn.disabled = false;
                        resendOtpBtn.innerHTML = '<i class="fas fa-redo me-2"></i> Kirim Ulang OTP';
                    }
                }, 1000);
            }

            // OTP input - enable verify button when 6 digits
            otpInput.addEventListener('input', function() {
                if (this.value.length === 6) {
                    verifyOtpBtn.disabled = false;
                } else {
                    verifyOtpBtn.disabled = true;
                }
            });

            // Verify OTP
            verifyOtpBtn.addEventListener('click', async function() {
                const email = emailInput.value.trim();
                const otp = otpInput.value.trim();

                if (!email) {
                    showError('Silakan masukkan email terlebih dahulu.');
                    return;
                }

                if (!otp || otp.length !== 6) {
                    showError('Silakan masukkan kode OTP 6 digit.');
                    return;
                }

                verifyOtpBtn.disabled = true;
                verifyOtpBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memverifikasi...';

                try {
                    const response = await fetch('{{ route("verification.verify-and-register") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            email: email,
                            otp: otp,
                            name: '',
                            birthdate: '',
                            gender: '',
                            phone_number: '',
                            password: '',
                            password_confirmation: ''
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        isOtpVerified = true;
                        clearInterval(countdownInterval);
                        
                        regEmail.value = email;
                        
                        stepOtp.style.display = 'none';
                        stepRegister.style.display = 'block';
                        updateStepIndicator(3);
                        
                        document.getElementById('reg-name').focus();
                        
                        showSuccess('Email berhasil diverifikasi!');
                    } else {
                        showError(data.message || 'Kode OTP tidak valid.');
                        verifyOtpBtn.disabled = false;
                        verifyOtpBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i> Verifikasi';
                    }
                } catch (error) {
                    console.error('OTP verification error:', error);
                    showError('Terjadi kesalahan. Silakan coba lagi.');
                    verifyOtpBtn.disabled = false;
                    verifyOtpBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i> Verifikasi';
                }
            });

            // Resend OTP
            resendOtpBtn.addEventListener('click', async function() {
                const email = emailInput.value.trim();

                resendOtpBtn.disabled = true;
                resendOtpBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mengirim...';

                try {
                    const response = await fetch('{{ route("verification.resend-otp") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        showSuccess(data.message);
                        otpInput.value = '';
                        verifyOtpBtn.disabled = true;
                        startCountdown();
                        resendOtpBtn.innerHTML = '<i class="fas fa-redo me-2"></i> Kirim Ulang OTP';
                    } else {
                        showError(data.message);
                        resendOtpBtn.disabled = false;
                        resendOtpBtn.innerHTML = '<i class="fas fa-redo me-2"></i> Kirim Ulang OTP';
                    }
                } catch (error) {
                    showError('Terjadi kesalahan. Silakan coba lagi.');
                    resendOtpBtn.disabled = false;
                    resendOtpBtn.innerHTML = '<i class="fas fa-redo me-2"></i> Kirim Ulang OTP';
                }
            });

            // Form submit
            if (registerForm) {
                registerForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    if (!isOtpVerified) {
                        showError('Silakan verifikasi email terlebih dahulu.');
                        return;
                    }

                    const formData = new FormData(this);
                    
                    const emailValue = formData.get('email');
                    if (!emailValue) {
                        showError('Email tidak ditemukan. Silakan ulangi verifikasi OTP.');
                        return;
                    }
                    
                    registerBtn.disabled = true;
                    registerBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mendaftar...';

                    try {
                        const response = await fetch('{{ route("verification.verify-and-register") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                email: emailValue,
                                otp: 'verified',
                                name: formData.get('name'),
                                birthdate: formData.get('birthdate'),
                                gender: formData.get('gender'),
                                phone_number: formData.get('phone_number'),
                                password: formData.get('password'),
                                password_confirmation: formData.get('password_confirmation')
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            showSuccess(data.message);
                            setTimeout(() => {
                                window.location.href = data.redirect || '/';
                            }, 1500);
                        } else {
                            if (data.error_code === 'email_already_registered') {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Email Sudah Terdaftar',
                                    html: `<div class="text-start">
                                        <p>${data.message}</p>
                                        <p class="text-muted mt-3">Pilih salah satu opsi di bawah:</p>
                                    </div>`,
                                    showDenyButton: true,
                                    showCancelButton: true,
                                    confirmButtonText: 'Ke Halaman Login',
                                    denyButtonText: 'Gunakan Email Lain',
                                    cancelButtonText: 'Batal',
                                    confirmButtonColor: '#00617a',
                                    denyButtonColor: '#6c757d',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = '{{ route("login") }}';
                                    } else if (result.isDenied) {
                                        isOtpVerified = false;
                                        emailInput.value = '';
                                        otpInput.value = '';
                                        document.querySelectorAll('input[name="name"], input[name="birthdate"], input[name="gender"], input[name="phone_number"], input[name="password"], input[name="password_confirmation"]').forEach(el => el.value = '');
                                        stepRegister.style.display = 'none';
                                        stepOtp.style.display = 'none';
                                        stepEmail.style.display = 'block';
                                        updateStepIndicator(1);
                                        emailInput.focus();
                                    }
                                });
                            } else if (data.error_code === 'registration_failed') {
                                let errorHtml = `<div class="text-start">
                                    <p>${data.message}</p>`;
                                if (data.debug) {
                                    errorHtml += `<p class="text-danger small mt-2"><strong>Debug:</strong> ${data.debug}</p>`;
                                }
                                errorHtml += `<p class="text-muted small mt-2">Jika masalah terus berlanjut, silakan hubungi customer support.</p></div>`;
                                
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Membuat Akun',
                                    html: errorHtml,
                                    confirmButtonColor: '#00617a'
                                });
                            } else {
                                showError(data.message || 'Registrasi gagal.');
                            }
                            registerBtn.disabled = false;
                            registerBtn.innerHTML = '<i class="fas fa-user-plus me-2"></i> Daftar Akun';
                        }
                    } catch (error) {
                        console.error('Registration error:', error);
                        showError('Terjadi kesalahan. Silakan coba lagi.');
                        registerBtn.disabled = false;
                        registerBtn.innerHTML = '<i class="fas fa-user-plus me-2"></i> Daftar Akun';
                    }
                });
            }
        });
    </script>
</body>
</html>

