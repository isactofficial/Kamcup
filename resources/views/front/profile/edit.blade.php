{{-- resources/views/front/profile/edit.blade.php --}}

@extends('../layouts/master_nav')

@section('title', 'Edit Profile')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Flash message success/error --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm profile-edit-card fade-in-up">
                <div class="card-header bg-white text-center py-3 fade-in-up" data-delay="100">
                    <h4 class="mb-0 profile-section-title">Edit Informasi Profile</h4>
                </div>
                <div class="card-body">
                    {{-- 
                        PENTING: Form menggunakan POST biasa (bukan fetch/AJAX).
                        _method PUT dikirim lewat hidden input agar Laravel mengenali sebagai PUT request.
                        Ini lebih reliable daripada fetch karena tidak perlu handle JSON response.
                    --}}
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profile-form">
                        @csrf
                        @method('PUT')

                        {{-- ===== BAGIAN FOTO PROFIL DENGAN CROP ===== --}}
                        <div class="mb-4 text-center fade-in-up" data-delay="200">
                            <label class="form-label d-block mb-2 fw-semibold">Foto Profil</label>

                            {{-- Preview foto saat ini (tampilan lingkaran) --}}
                            <div class="profile-preview-wrapper mx-auto mb-3">
                                <img id="current-photo-preview"
                                     src="{{ Auth::user()->profile->profile_photo ? asset('storage/' . Auth::user()->profile->profile_photo) : asset('assets/img/profile-placeholder.png') }}"
                                     alt="Foto Profil Saat Ini">
                                <div class="preview-overlay" onclick="document.getElementById('profile_photo').click();">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 16 16">
                                        <path d="M10.5 8.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                        <path d="M2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2zm.5 2a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm9 2.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0z"/>
                                    </svg>
                                    <span>Ganti Foto</span>
                                </div>
                            </div>

                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                    onclick="document.getElementById('profile_photo').click();">
                                Pilih Foto
                            </button>

                            <input type="file" class="d-none" id="profile_photo" name="profile_photo" accept="image/*">
                            @error('profile_photo')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror

                            {{-- Hidden input untuk menyimpan data base64 hasil crop --}}
                            <input type="hidden" id="cropped_profile_photo" name="cropped_profile_photo">

                            <div class="form-text mt-2">
                                Klik foto atau tombol di atas untuk memilih foto, lalu crop area yang diinginkan.
                                Hasil crop akan ditampilkan dalam bingkai lingkaran.
                            </div>
                        </div>

                        {{-- Input Nama --}}
                        <div class="mb-3 fade-in-up" data-delay="300">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name"
                                   value="{{ old('name', Auth::user()->profile->name ?? Auth::user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Tanggal Lahir --}}
                        <div class="mb-3 fade-in-up" data-delay="400">
                            <label for="birthdate" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control @error('birthdate') is-invalid @enderror"
                                   id="birthdate" name="birthdate"
                                   value="{{ old('birthdate', Auth::user()->profile->birthdate) }}">
                            @error('birthdate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Jenis Kelamin --}}
                        <div class="mb-3 fade-in-up" data-delay="500">
                            <label for="gender" class="form-label">Jenis Kelamin</label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="male"   {{ old('gender', Auth::user()->profile->gender) == 'male'   ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ old('gender', Auth::user()->profile->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                                <option value="other"  {{ old('gender', Auth::user()->profile->gender) == 'other'  ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email (disabled, tidak bisa diubah) --}}
                        <div class="mb-3 fade-in-up" data-delay="600">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email"
                                   value="{{ Auth::user()->email }}" disabled>
                            <div class="form-text">Email tidak dapat diubah di sini.</div>
                        </div>

                        {{-- Input Nomor Telepon --}}
                        <div class="mb-3 fade-in-up" data-delay="700">
                            <label for="phone_number" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control @error('phone_number') is-invalid @enderror"
                                   id="phone_number" name="phone_number"
                                   value="{{ old('phone_number', Auth::user()->profile->phone_number) }}"
                                   pattern="[0-9]*" inputmode="numeric"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Akun Sosial Media --}}
                        <div class="mb-4 fade-in-up" data-delay="800">
                            <label for="social_media" class="form-label">Akun Sosial Media (Link)</label>
                            <input type="url" class="form-control @error('social_media') is-invalid @enderror"
                                   id="social_media" name="social_media"
                                   value="{{ old('social_media', Auth::user()->profile->social_media) }}"
                                   placeholder="https://instagram.com/namapengguna">
                            @error('social_media')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 fade-in-up" data-delay="900">
                            <button type="submit" class="btn btn-primary btn-lg" id="btn-submit">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL CROP — di luar form & card agar tidak ter-block ===== --}}
<div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="cropModalLabel">✂️ Crop Foto Profil</h5>
            </div>
            <div class="modal-body text-center pt-2">
                <p class="text-muted small mb-3">
                    Geser dan resize kotak untuk memilih area. Hasil akhir ditampilkan dalam bingkai <strong>lingkaran</strong>.
                </p>

                {{-- Area Cropper — dibatasi tingginya agar tidak meluber --}}
                <div id="crop-image-container" style="max-height: 380px; overflow: hidden; background: #f8f9fa; border-radius: 8px;">
                    <img id="crop-image" src="" alt="Crop" style="display: block; max-width: 100%;">
                </div>

                {{-- Preview lingkaran real-time --}}
                <div class="mt-3 d-flex align-items-center justify-content-center gap-3">
                    <span class="text-muted small">Preview:</span>
                    <div id="crop-circle-preview"
                         style="width: 80px; height: 80px; border-radius: 50%;
                                border: 3px solid #cb2786; overflow: hidden;
                                background: #eee; flex-shrink: 0;">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" id="btn-cancel-crop">Batal</button>
                <button type="button" class="btn btn-primary px-4" id="btn-apply-crop">
                    ✔ Terapkan
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<style>
    /* ===== KARTU EDIT ===== */
    .profile-edit-card {
        border-radius: 12px;
        box-shadow:
            8px 8px 0px 0px var(--shadow-color-cf2585, #cf2585),
            5px 5px 15px rgba(0, 0, 0, 0.1) !important;
        /* JANGAN set z-index di sini — bisa menghalangi modal */
        border: 1px solid #dee2e6;
    }

    /* Pastikan Cropper.js bisa tampil penuh di dalam container --*/
    #crop-image-container .cropper-container {
        max-height: 380px;
    }

    /* ===== PREVIEW FOTO (LINGKARAN) ===== */
    .profile-preview-wrapper {
        position: relative;
        width: 120px;
        height: 120px;
        border-radius: 50%;         /* <-- lingkaran */
        overflow: hidden;
        border: 3px solid #cb2786;
        cursor: pointer;
        background: #f8f9fa;
    }

    .profile-preview-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;          /* <-- gambar mengisi lingkaran */
        display: block;
    }

    /* Overlay kamera saat hover */
    .preview-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 4px;
        opacity: 0;
        transition: opacity 0.25s ease;
        color: white;
        font-size: 0.7rem;
        border-radius: 50%;
    }

    .profile-preview-wrapper:hover .preview-overlay {
        opacity: 1;
    }

    /* ===== FORM LABEL ===== */
    .form-label {
        font-weight: 500;
        color: #495057;
    }

    /* ===== ANIMASI FADE IN ===== */
    .fade-in-up {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s cubic-bezier(0.25, 0.25, 0.25, 1);
    }
    .fade-in-up.animate {
        opacity: 1;
        transform: translateY(0);
    }

    /* ===== LOADING STATE TOMBOL ===== */
    #btn-submit.loading {
        pointer-events: none;
        opacity: 0.7;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const fileInput      = document.getElementById('profile_photo');
    const cropImage      = document.getElementById('crop-image');
    const currentPreview = document.getElementById('current-photo-preview');
    const croppedInput   = document.getElementById('cropped_profile_photo');
    const btnApply       = document.getElementById('btn-apply-crop');
    const btnCancel      = document.getElementById('btn-cancel-crop');
    const form           = document.getElementById('profile-form');
    const cropModalEl    = document.getElementById('cropModal');

    // Inisialisasi Bootstrap Modal
    const cropModal = new bootstrap.Modal(cropModalEl, {
        backdrop: 'static',
        keyboard: false
    });

    let cropper = null;

    // ─── Destroy cropper & reset saat modal ditutup ──────────────────────
    cropModalEl.addEventListener('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        cropImage.src = '';
    });

    // ─── Inisialisasi Cropper setelah modal BENAR-BENAR tampil ───────────
    cropModalEl.addEventListener('shown.bs.modal', function () {
        // Jika gambar sudah complete (cached), langsung init
        if (cropImage.complete && cropImage.naturalWidth > 0) {
            initCropper();
        } else {
            // Tunggu sampai gambar selesai load
            cropImage.onload = function () {
                initCropper();
            };
        }
    });

    // ─── User pilih file ─────────────────────────────────────────────────
    fileInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        if (!file.type.startsWith('image/')) {
            alert('File harus berupa gambar (JPG, PNG, WebP, dll).');
            fileInput.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (evt) {
            // Set src gambar SEBELUM buka modal
            cropImage.src = evt.target.result;
            cropModal.show();
        };
        reader.readAsDataURL(file);
    });

    // ─── Init Cropper.js ─────────────────────────────────────────────────
    function initCropper() {
        if (cropper) { cropper.destroy(); cropper = null; }

        cropper = new Cropper(cropImage, {
            aspectRatio: 1,       // kotak 1:1 → lingkaran sempurna
            viewMode: 1,          // crop box tidak bisa keluar gambar
            autoCropArea: 0.8,
            movable: true,
            zoomable: true,
            rotatable: false,
            scalable: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
            crop: updatePreview,  // update preview lingkaran real-time
        });
    }

    // ─── Preview lingkaran real-time ─────────────────────────────────────
    function updatePreview() {
        if (!cropper) return;
        // Gunakan canvas kecil (80px) untuk performa
        const canvas = cropper.getCroppedCanvas({ width: 80, height: 80 });
        if (!canvas) return;
        const previewEl = document.getElementById('crop-circle-preview');
        previewEl.style.backgroundImage    = `url(${canvas.toDataURL()})`;
        previewEl.style.backgroundSize     = 'cover';
        previewEl.style.backgroundPosition = 'center';
    }

    // ─── Tombol "Terapkan" ───────────────────────────────────────────────
    btnApply.addEventListener('click', function () {
        if (!cropper) return;

        btnApply.disabled    = true;
        btnApply.textContent = 'Memproses...';

        // Sedikit delay agar UI update dulu
        setTimeout(function () {
            const canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });
            if (!canvas) {
                alert('Gagal memproses gambar. Coba pilih foto lain.');
                btnApply.disabled    = false;
                btnApply.textContent = '✔ Terapkan';
                return;
            }

            const base64 = canvas.toDataURL('image/jpeg', 0.85);

            // Simpan ke hidden input → ikut dikirim saat form submit
            croppedInput.value = base64;

            // Update preview lingkaran di halaman
            currentPreview.src = base64;

            // Reset file input
            fileInput.value = '';

            btnApply.disabled    = false;
            btnApply.textContent = '✔ Terapkan';

            cropModal.hide();
        }, 50);
    });

    // ─── Tombol "Batal" ──────────────────────────────────────────────────
    btnCancel.addEventListener('click', function () {
        fileInput.value  = '';
        croppedInput.value = '';
        cropModal.hide();
        // cropper & cropImage.src di-reset oleh listener hidden.bs.modal di atas
    });

    // ─── Submit form ─────────────────────────────────────────────────────
    form.addEventListener('submit', function () {
        const btn = document.getElementById('btn-submit');
        btn.disabled     = true;
        btn.textContent  = 'Menyimpan...';
    });

    // ─── Scroll animation ────────────────────────────────────────────────
    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            const delay = parseInt(entry.target.dataset.delay || 0);
            setTimeout(function () {
                entry.target.classList.add('animate');
            }, delay);
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.fade-in-up').forEach(function (el) {
        observer.observe(el);
    });
});
</script>
@endpush