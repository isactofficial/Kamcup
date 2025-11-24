@extends('../layouts/master_nav')

@section('title', 'Berikan Donasi')

@section('content')
    <div class="bg-donasi-wrapper">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    {{-- Kartu untuk Form Donasi --}}
                    <div class="card shadow-sm profile-edit-card">
                        <div class="card-header bg-white text-center py-3">
                            <h4 class="mb-0 profile-section-title card-title">Berikan Donasi</h4>
                        </div>
                        <div class="card-body">

                            <form id="donationForm" action="{{ route('donations.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                {{-- NAMA DONATUR BISA DIEDIT, EMAIL READONLY --}}
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="donorName" class="form-label">Nama Pengirim Donasi <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control editable-field @error('donor_name') is-invalid @enderror"
                                               id="donorName" 
                                               name="donor_name" 
                                               value="{{ old('donor_name', Auth::user()->name) }}" 
                                               placeholder="Masukkan nama Anda" 
                                               required>
                                        <small class="text-muted">Nama yang akan ditampilkan sebagai donatur</small>
                                        @error('donor_name')
                                            <div class="field-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email Kontak</label>
                                        <input type="email" 
                                               class="form-control readonly-field" 
                                               value="{{ Auth::user()->email }}" 
                                               readonly>
                                        <small class="text-muted">Email diambil dari akun Anda</small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="amount" class="form-label">Jumlah Donasi <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" 
                                               class="form-control editable-field @error('amount') is-invalid @enderror" 
                                               id="amount" 
                                               name="amount" 
                                               value="{{ old('amount') }}" 
                                               placeholder="Contoh: 50000" 
                                               min="1000"
                                               step="1000"
                                               required>
                                    </div>
                                    <small class="text-muted">Minimal donasi Rp 1.000</small>
                                    @error('amount')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- QR Code Section - Always Visible --}}
                                <div class="mb-3 text-center">
                                    <p class="fw-bold mb-2">Silakan lakukan pembayaran ke QR Code berikut:</p>
                                    <img src="{{ asset('assets/img/QR.JPG') }}" 
                                         alt="QR Code Pembayaran" 
                                         class="img-fluid"
                                         style="max-width: 250px; border-radius: 8px;">
                                </div>

                                <div class="mb-3">
                                    <label for="proofImage" class="form-label">Upload Bukti Transfer <span class="text-danger">*</span></label>
                                    <input type="file" 
                                           class="form-control editable-field @error('proof_image') is-invalid @enderror" 
                                           id="proofImage" 
                                           name="proof_image" 
                                           accept="image/jpeg,image/png,image/jpg"
                                           required>
                                    <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB. Upload bukti transfer pembayaran.</small>
                                    @error('proof_image')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                    
                                    {{-- Image Preview --}}
                                    <div id="imagePreview" class="mt-3" style="display: none;">
                                        <p class="mb-2 small text-muted">Preview Bukti Transfer:</p>
                                        <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; border-radius: 8px;">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="fotoDonatur" class="form-label">Upload Foto Donatur (Opsional)</label>
                                    <input type="file" 
                                           class="form-control editable-field @error('foto_donatur') is-invalid @enderror" 
                                           id="fotoDonatur" 
                                           name="foto_donatur" 
                                           accept="image/jpeg,image/png,image/jpg">
                                    <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB. Upload foto Anda sebagai donatur.</small>
                                    @error('foto_donatur')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                    
                                    {{-- Image Preview 2 --}}
                                    <div id="imagePreview2" class="mt-3" style="display: none;">
                                        <p class="mb-2 small text-muted">Preview Foto Donatur:</p>
                                        <img id="previewImg2" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; border-radius: 8px;">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label">Pesan (Opsional)</label>
                                    <textarea id="message" 
                                              name="message" 
                                              placeholder="Sampaikan pesan atau harapan Anda..." 
                                              class="form-control editable-field" 
                                              rows="4">{{ old('message') }}</textarea>
                                    <small class="text-muted">Maksimal 1000 karakter</small>
                                    @error('message')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-grid gap-2 mt-4">
                                    <button type="submit" id="submitBtn" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i>Kirim Donasi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Background Image */
        .bg-donasi-wrapper {
            background-image: url('{{ asset('assets/img/bg-form.svg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            z-index: 0;
            min-height: 100vh;
            padding-top: 5rem;
            padding-bottom: 5rem;
        }

        /* Card styles */
        .profile-edit-card {
            border-radius: 12px;
            box-shadow:
                8px 8px 0px 0px var(--kamcup-pink),
                5px 5px 15px rgba(0, 0, 0, 0.1) !important;
            position: relative;
            z-index: 1;
            border: 1px solid #dee2e6;
        }

        .profile-section-title {
            color: var(--kamcup-pink);
            font-weight: 600;
        }

        .form-label {
            font-weight: 500;
            color: #495057;
        }

        /* Form control styles yang bisa diedit */
        .form-control.editable-field {
            border-radius: 8px;
            border: 1px solid #e9ecef;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #ffffff;
            color: #495057;
        }

        .form-control.editable-field:focus {
            border-color: var(--kamcup-pink);
            box-shadow: 0 0 0 0.2rem rgba(203, 39, 134, 0.25);
            background-color: #ffffff;
        }

        /* Readonly input styling yang berbeda */
        .form-control.readonly-field {
            background-color: #f8f9fa !important;
            border: 1px solid #e9ecef !important;
            color: #6c757d !important;
            cursor: not-allowed;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
        }

        /* Input group styling */
        .input-group-text {
            background-color: var(--kamcup-pink);
            color: white;
            border: 1px solid var(--kamcup-pink);
            font-weight: 600;
            border-radius: 8px 0 0 8px;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 8px 8px 0 !important;
        }

        /* Required field indicator */
        .text-danger {
            color: #dc3545 !important;
        }

        /* Button styles */
        .btn-primary {
            background-color: var(--kamcup-yellow) !important;
            border-color: var(--kamcup-yellow) !important;
            color: var(--kamcup-dark-text) !important;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #e0ac00 !important;
            border-color: #e0ac00 !important;
            transform: translateY(-2px);
        }

        .btn-primary:disabled {
            background-color: #e0ac00 !important;
            border-color: #e0ac00 !important;
            opacity: 0.7;
            transform: none;
        }

        .field-error {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }

        /* Small text styling */
        small.text-muted {
            font-size: 0.85em;
            color: #6c757d !important;
        }

        /* KAMCUP brand color variables */
        :root {
            --kamcup-pink: #cb2786;
            --kamcup-blue-green: #00617a;
            --kamcup-yellow: #f4b704;
            --kamcup-dark-text: #212529;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .row.mb-4 .col-md-6 {
                margin-bottom: 1rem;
            }
            
            .bg-donasi-wrapper {
                padding-top: 2rem;
                padding-bottom: 2rem;
            }
        }

        /* Pastikan tidak ada CSS yang override input */
        input[readonly] {
            background-color: #f8f9fa !important;
            cursor: not-allowed !important;
        }

        input:not([readonly]) {
            background-color: #ffffff !important;
            cursor: text !important;
        }

        /* Image preview styling */
        .img-thumbnail {
            border: 2px solid #e9ecef;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const donorNameField = document.getElementById('donorName');
            const proofImageInput = document.getElementById('proofImage');
            const fotoDonaturInput = document.getElementById('fotoDonatur');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            const imagePreview2 = document.getElementById('imagePreview2');
            const previewImg2 = document.getElementById('previewImg2');
            const donationForm = document.getElementById('donationForm');
            const submitBtn = document.getElementById('submitBtn');
            const amountInput = document.getElementById('amount');

            // Pastikan field nama tidak readonly
            if (donorNameField) {
                donorNameField.removeAttribute('readonly');
            }

            // Image preview functionality - Bukti Transfer
            proofImageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file type
                    if (!file.type.match('image/jpeg|image/png|image/jpg')) {
                        alert('Format file harus JPG, JPEG, atau PNG');
                        proofImageInput.value = '';
                        imagePreview.style.display = 'none';
                        return;
                    }

                    // Validate file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file maksimal 2MB');
                        proofImageInput.value = '';
                        imagePreview.style.display = 'none';
                        return;
                    }

                    // Show preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Image preview functionality - Foto Donatur
            fotoDonaturInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file type
                    if (!file.type.match('image/jpeg|image/png|image/jpg')) {
                        alert('Format file harus JPG, JPEG, atau PNG');
                        fotoDonaturInput.value = '';
                        imagePreview2.style.display = 'none';
                        return;
                    }

                    // Validate file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file maksimal 2MB');
                        fotoDonaturInput.value = '';
                        imagePreview2.style.display = 'none';
                        return;
                    }

                    // Show preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg2.src = e.target.result;
                        imagePreview2.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Format amount input
            amountInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                e.target.value = value;
            });

            // Form submission
            donationForm.addEventListener('submit', function(e) {
                if(submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';
                    submitBtn.disabled = true;
                }
            });
        });
    </script>
@endpush