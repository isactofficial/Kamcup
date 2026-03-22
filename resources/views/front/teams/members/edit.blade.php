{{-- resources/views/front/teams/members/edit.blade.php --}}

@extends('../layouts/master_nav')

@section('title', 'Edit Anggota Tim')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
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

            <div class="card shadow-sm profile-edit-card">
                <div class="card-header bg-white text-center py-3">
                    <h4 class="mb-0 profile-section-title">
                        Edit Anggota: {{ $member->name }} <span class="text-muted fw-normal fs-6">({{ $team->name }})</span>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('team.members.update', ['team' => \Illuminate\Support\Facades\Crypt::encryptString($team->id), 'member' => \Illuminate\Support\Facades\Crypt::encryptString($member->id)]) }}"
                          method="POST" enctype="multipart/form-data" id="member-form">
                        @csrf
                        @method('PUT')

                        {{-- Input Foto Anggota --}}
                        <div class="mb-4 text-center">
                            <label class="form-label d-block mb-2 fw-semibold">Foto Anggota</label>

                            <div class="profile-preview-wrapper mx-auto mb-3">
                                <img id="current-photo-preview"
                                     src="{{ $member->photo ? asset('storage/' . $member->photo) : asset('assets/img/profile-placeholder.png') }}"
                                     alt="Foto Anggota">
                                <div class="preview-overlay" onclick="document.getElementById('photo').click();">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 16 16">
                                        <path d="M10.5 8.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                        <path d="M2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2zm.5 2a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm9 2.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0z"/>
                                    </svg>
                                    <span>Ganti Foto</span>
                                </div>
                            </div>

                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                    onclick="document.getElementById('photo').click();">
                                Pilih Foto Anggota
                            </button>

                            <input type="file" class="d-none" id="photo" name="photo" accept="image/*">
                            @error('photo')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                            <input type="hidden" id="cropped_member_photo" name="cropped_member_photo">

                            {{-- Checkbox hapus foto --}}
                            @if($member->photo)
                                <div class="mt-2">
                                    <div class="form-check d-inline-flex align-items-center gap-2">
                                        <input class="form-check-input" type="checkbox"
                                               name="clear_photo" id="clear_photo" value="1">
                                        <label class="form-check-label text-danger small" for="clear_photo">
                                            Hapus foto saat ini
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <div class="form-text mt-1">Klik foto atau tombol untuk memilih & crop gambar (tampil lingkaran).</div>
                        </div>

                        {{-- Input Nama Anggota --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap Anggota</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $member->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Tanggal Lahir --}}
                        <div class="mb-3">
                            <label for="birthdate" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control @error('birthdate') is-invalid @enderror"
                                   id="birthdate" name="birthdate"
                                   value="{{ old('birthdate', $member->birthdate ? \Carbon\Carbon::parse($member->birthdate)->format('Y-m-d') : '') }}">
                            @error('birthdate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Jenis Kelamin --}}
                        <div class="mb-3">
                            <label for="gender" class="form-label">Jenis Kelamin</label>
                            <select class="form-select @error('gender') is-invalid @enderror"
                                    id="gender" name="gender" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="male"   {{ old('gender', $member->gender) == 'male'   ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ old('gender', $member->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                                <option value="other"  {{ old('gender', $member->gender) == 'other'  ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Posisi --}}
                        <div class="mb-3">
                            <label for="position" class="form-label">Posisi (Opsional)</label>
                            <input type="text" class="form-control @error('position') is-invalid @enderror"
                                   id="position" name="position" value="{{ old('position', $member->position) }}"
                                   placeholder="Contoh: Spiker, Libero, Setter">
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Nomor Punggung --}}
                        <div class="mb-3">
                            <label for="jersey_number" class="form-label">Nomor Punggung (Opsional)</label>
                            <input type="number" class="form-control @error('jersey_number') is-invalid @enderror"
                                   id="jersey_number" name="jersey_number"
                                   value="{{ old('jersey_number', $member->jersey_number) }}"
                                   min="1" max="99">
                            @error('jersey_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Kontak Anggota --}}
                        <div class="mb-3">
                            <label for="contact" class="form-label">Kontak Anggota (Opsional)</label>
                            <input type="tel" class="form-control @error('contact') is-invalid @enderror"
                                   id="contact" name="contact" value="{{ old('contact', $member->contact) }}"
                                   placeholder="Contoh: 081234567890"
                                   pattern="[0-9]*" inputmode="numeric"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            @error('contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Email Anggota --}}
                        <div class="mb-4">
                            <label for="email" class="form-label">Email Anggota (Opsional)</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $member->email) }}"
                                   placeholder="email@contoh.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="btn-submit">Update Anggota</button>
                            <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Crop — di luar form & card --}}
<div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="cropModalLabel">✂️ Crop Foto Anggota</h5>
            </div>
            <div class="modal-body text-center pt-2">
                <p class="text-muted small mb-3">
                    Geser dan resize kotak untuk memilih area. Hasil akhir ditampilkan dalam bingkai <strong>lingkaran</strong>.
                </p>
                <div id="crop-image-container" style="max-height: 380px; overflow: hidden; background: #f8f9fa; border-radius: 8px;">
                    <img id="crop-image" src="" alt="Crop" style="display: block; max-width: 100%;">
                </div>
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
                <button type="button" class="btn btn-primary px-4" id="btn-apply-crop">✔ Terapkan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<style>
    .profile-edit-card {
        border-radius: 12px;
        box-shadow:
            8px 8px 0px 0px var(--shadow-color-cf2585, #cf2585),
            5px 5px 15px rgba(0, 0, 0, 0.1) !important;
        border: 1px solid #dee2e6;
    }
    .profile-preview-wrapper {
        position: relative;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #cb2786;
        cursor: pointer;
        background: #f8f9fa;
    }
    .profile-preview-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .preview-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.45);
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
    .profile-preview-wrapper:hover .preview-overlay { opacity: 1; }
    .form-label { font-weight: 500; color: #495057; }
    #crop-image-container .cropper-container { max-height: 380px; }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput      = document.getElementById('photo');
    const cropImage      = document.getElementById('crop-image');
    const currentPreview = document.getElementById('current-photo-preview');
    const croppedInput   = document.getElementById('cropped_member_photo');
    const btnApply       = document.getElementById('btn-apply-crop');
    const btnCancel      = document.getElementById('btn-cancel-crop');
    const form           = document.getElementById('member-form');
    const cropModalEl    = document.getElementById('cropModal');
    const cropModal      = new bootstrap.Modal(cropModalEl, { backdrop: 'static', keyboard: false });
    const clearCheckbox  = document.getElementById('clear_photo');

    let cropper = null;

    cropModalEl.addEventListener('hidden.bs.modal', function () {
        if (cropper) { cropper.destroy(); cropper = null; }
        cropImage.src = '';
    });

    cropModalEl.addEventListener('shown.bs.modal', function () {
        if (cropImage.complete && cropImage.naturalWidth > 0) {
            initCropper();
        } else {
            cropImage.onload = initCropper;
        }
    });

    fileInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;
        if (!file.type.startsWith('image/')) {
            alert('File harus berupa gambar.');
            fileInput.value = '';
            return;
        }
        // Jika user pilih foto baru, uncheck "hapus foto"
        if (clearCheckbox) clearCheckbox.checked = false;

        const reader = new FileReader();
        reader.onload = function (evt) {
            cropImage.src = evt.target.result;
            cropModal.show();
        };
        reader.readAsDataURL(file);
    });

    // Jika "hapus foto" dicentang, reset preview & cropped input
    if (clearCheckbox) {
        clearCheckbox.addEventListener('change', function () {
            if (this.checked) {
                fileInput.value    = '';
                croppedInput.value = '';
                currentPreview.src = '{{ asset('assets/img/profile-placeholder.png') }}';
            }
        });
    }

    function initCropper() {
        if (cropper) { cropper.destroy(); cropper = null; }
        cropper = new Cropper(cropImage, {
            aspectRatio: 1,
            viewMode: 1,
            autoCropArea: 0.8,
            movable: true,
            zoomable: true,
            rotatable: false,
            scalable: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
            crop: updatePreview,
        });
    }

    function updatePreview() {
        if (!cropper) return;
        const canvas = cropper.getCroppedCanvas({ width: 80, height: 80 });
        if (!canvas) return;
        const el = document.getElementById('crop-circle-preview');
        el.style.backgroundImage    = `url(${canvas.toDataURL()})`;
        el.style.backgroundSize     = 'cover';
        el.style.backgroundPosition = 'center';
    }

    btnApply.addEventListener('click', function () {
        if (!cropper) return;
        btnApply.disabled    = true;
        btnApply.textContent = 'Memproses...';
        setTimeout(function () {
            const canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });
            if (!canvas) {
                alert('Gagal memproses gambar. Coba pilih foto lain.');
                btnApply.disabled    = false;
                btnApply.textContent = '✔ Terapkan';
                return;
            }
            croppedInput.value   = canvas.toDataURL('image/jpeg', 0.85);
            currentPreview.src   = croppedInput.value;
            fileInput.value      = '';
            btnApply.disabled    = false;
            btnApply.textContent = '✔ Terapkan';
            cropModal.hide();
        }, 50);
    });

    btnCancel.addEventListener('click', function () {
        fileInput.value    = '';
        croppedInput.value = '';
        cropModal.hide();
    });

    form.addEventListener('submit', function () {
        const btn = document.getElementById('btn-submit');
        btn.disabled    = true;
        btn.textContent = 'Menyimpan...';
    });
});
</script>
@endpush