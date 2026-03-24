@extends('layouts.admin')

@section('title', 'Manajemen Feeds')

@section('content')
<style>
/* Custom Style for Feeds Dashboard */
.custom-select-dropdown {
    background-color: #f0f8ff;
    border-radius: 0.75rem;
    padding: 0.6rem 1.2rem;
    font-size: 0.9rem;
    font-weight: 600;
    color: #00617a;
    transition: all 0.3s ease-in-out;
    border: 1px solid rgba(0, 97, 122, 0.2);
}

.btn-sporty-primary {
    background-color: #00617a;
    border-color: #00617a;
    color: white;
    border-radius: 0.75rem;
    padding: 0.6rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease-in-out;
    box-shadow: 0 4px 8px rgba(0, 97, 122, 0.2);
}

.btn-sporty-primary:hover {
    background-color: #004a5c;
    border-color: #004a5c;
    color: white;
    transform: translateY(-2px);
}

/* Badge styling */
.badge-category-meets {
    background-color: rgba(40, 167, 69, 0.15);
    color: #28a745;
    font-weight: 600;
    padding: 0.4em 0.8em;
    border-radius: 0.5rem;
}

.badge-stats {
    background-color: rgba(0, 97, 122, 0.1);
    color: #00617a;
    font-weight: 600;
    padding: 0.3em 0.7em;
    border-radius: 0.4rem;
}

/* Modal styling */
.modal-content {
    border-radius: 1.25rem;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.modal-header {
    border-bottom: 1px solid #f0f0f0;
    padding: 1.5rem;
}

.modal-footer {
    border-top: 1px solid #f0f0f0;
    padding: 1.25rem;
}

/* Mobile Card View */
.mobile-entity-card {
    border: 1px solid #e9ecef;
    border-radius: 1rem;
    background-color: #fff;
    overflow: hidden;
    margin-bottom: 1rem;
    padding: 1.25rem;
}
</style>

<div class="container-fluid px-4 py-4" style="min-height: 100vh;">

    {{-- Header Section --}}
    <div class="bg-white rounded-4 shadow-sm p-3 p-md-4 mb-4" style="border-left: 8px solid #00617a;">
        <div class="d-flex align-items-center">
            <div class="d-flex justify-content-center align-items-center rounded-circle me-3 me-md-4"
                style="width: 50px; height: 50px; background-color: rgba(0, 97, 122, 0.1);">
                <i class="fas fa-rss fs-4" style="color: #00617a;"></i>
            </div>
            <div>
                <h2 class="fs-4 fs-md-3 fw-bold mb-1" style="color: #495057;">Manajemen Feeds & Aktivitas</h2>
                <p class="text-muted mb-0 d-none d-md-block">Kelola postingan pengguna, pantau kumpul bareng (Meets), dan hapus konten yang melanggar.</p>
            </div>
        </div>
    </div>

    {{-- Add Button Row --}}
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-end gap-2">
            <button class="btn btn-sporty-primary d-flex align-items-center px-4 py-2 text-white" data-bs-toggle="modal" data-bs-target="#addFeedModal">
                <i class="fas fa-plus me-2"></i>
                <span class="fw-semibold">Tambah Feed Baru</span>
            </button>
        </div>
    </div>

    {{-- Content Card --}}
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                <h1 class="fs-5 fw-bold mb-3 mb-md-0" style="color: #495057;">Daftar Konten</h1>
            </div>

            {{-- Table View (Desktop) --}}
            <div class="table-responsive d-none d-lg-block">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 border-0">Konten</th>
                            <th class="py-3 border-0">Oleh</th>
                            <th class="py-3 border-0">Isi</th>
                            <th class="py-3 border-0">Interaksi</th>
                            <th class="py-3 border-0">Tanggal</th>
                            <th class="py-3 border-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($feeds as $feed)
                        <tr style="border-bottom: 1px solid #f8f9fa;">
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light me-3 d-flex justify-content-center align-items-center" style="width: 60px; height: 60px; border-radius: 12px; overflow: hidden; border: 1px solid #eee;">
                                        @if ($feed->image)
                                            <img src="{{ asset('storage/' . $feed->image) }}" class="w-100 h-100" style="object-fit: cover;">
                                        @else
                                            <i class="fas fa-image text-muted fs-4"></i>
                                        @endif
                                    </div>
                                    <div>
                                        @if($feed->meet_date)
                                            <span class="badge badge-category-meets mb-1">MEETS</span>
                                        @endif
                                        <div class="fw-bold text-dark text-truncate" style="max-width: 150px;">{{ $feed->title ?? 'Tanpa Judul' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="small fw-bold text-dark">{{ $feed->user->name ?? 'User Tak Dikenal' }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">Join KAMCUP</div>
                            </td>
                            <td class="py-3">
                                <span class="text-muted small d-inline-block text-truncate" style="max-width: 200px;">
                                    {{ Str::limit($feed->meet_description ?? $feed->content ?? '-', 80) }}
                                </span>
                            </td>
                            <td class="py-3">
                                <div class="d-flex gap-2">
                                    <span class="badge-stats"><i class="far fa-heart me-1"></i>{{ $feed->likes_count }}</span>
                                    <span class="badge-stats"><i class="far fa-comment me-1"></i>{{ $feed->comments_count }}</span>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="small text-dark fw-semibold">
                                    @if($feed->meet_date)
                                        Meets: {{ \Carbon\Carbon::parse($feed->meet_date)->format('d M Y') }}
                                    @else
                                        Dibuat: {{ $feed->created_at->format('d/m/y') }}
                                    @endif
                                </div>
                            </td>
                            <td class="py-3">
                                <form action="{{ route('admin.feeds.destroy', $feed) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-sm btn-outline-danger rounded-pill px-3" style="border-color: #cb2786; color: #cb2786;">
                                        <i class="fas fa-trash me-1"></i>Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-rss-slash fa-2x mb-3 d-block"></i>
                                Tidak ada feeds yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Card View (Mobile) --}}
            <div class="d-block d-lg-none">
                @foreach ($feeds as $feed)
                <div class="mobile-entity-card shadow-sm border-0">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-3 bg-light d-flex justify-content-center align-items-center overflow-hidden" style="width: 50px; height: 50px;">
                             @if ($feed->image)
                                <img src="{{ asset('storage/' . $feed->image) }}" class="w-100 h-100" style="object-fit: cover;">
                            @else
                                <i class="fas fa-image text-muted"></i>
                            @endif
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-bold mb-0">{{ $feed->title ?? 'Tanpa Judul' }}</h6>
                            <span class="text-muted small">Oleh: {{ $feed->user->name ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="small text-muted mb-3">{{ Str::limit($feed->content ?? $feed->meet_description ?? '', 100) }}</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            <span class="badge-stats small"><i class="fas fa-heart"></i> {{ $feed->likes_count }}</span>
                            <span class="badge-stats small"><i class="fas fa-comment"></i> {{ $feed->comments_count }}</span>
                        </div>
                        <form action="{{ route('admin.feeds.destroy', $feed) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-sm text-danger fw-bold border-0">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-4 d-flex justify-content-center">
                {{ $feeds->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

{{-- Add Feed Modal --}}
<div class="modal fade" id="addFeedModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" style="color: #00617a;">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Feed Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.feeds.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Judul Feed (Opsional)</label>
                        <input type="text" name="title" class="form-control rounded-3 py-2" placeholder="Masukkan judul menarik..." value="{{ old('title') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Isi Postingan</label>
                        <textarea name="content" id="content" class="form-control rounded-3" rows="5" placeholder="Apa yang ingin kamu bagikan hari ini?" maxlength="5000">{{ old('content') }}</textarea>
                        <div class="form-text d-flex justify-content-between mt-2">
                            <span>Maksimal 5000 karakter</span>
                            <span id="char-count">0 / 5000</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Gambar (Thumbnail)</label>
                        <div class="p-4 border-2 border-dashed rounded-4 text-center bg-light" style="border: 2px dashed rgba(0, 97, 122, 0.2) !important;">
                            <i class="fas fa-cloud-upload-alt fs-2 text-muted mb-2"></i>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            <div class="form-text small mt-2">Format: JPG, PNG, WEBP (Maksimal 2MB)</div>
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="bg-light p-3 rounded-4" style="border-left: 5px solid #cb2786;">
                        <small class="text-muted fw-semibold">
                            <i class="fas fa-info-circle me-1 text-pink"></i> 
                            Ingat: Minimal satu field harus terisi (Judul, Isi, atau Gambar) agar konten bisa dipublikasikan.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sporty-primary rounded-pill px-5">Publikasikan Feed</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SweetAlert Logic --}}
<script>
function confirmDelete(event, form) {
    event.preventDefault();
    Swal.fire({
        title: "Hapus Feed?",
        text: "Postingan and seluruh komentar di dalamnya akan hilang permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#cb2786",
        cancelButtonColor: "#808080",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
        customClass: {
            popup: 'rounded-4'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}

@if(session('success'))
Swal.fire({
    icon: 'success',
    title: "{{ session('success') }}",
    showConfirmButton: false,
    timer: 2000,
    customClass: { popup: 'rounded-4' }
});
@endif

document.addEventListener('DOMContentLoaded', function() {
    const content = document.getElementById('content');
    const charCount = document.getElementById('char-count');
    if (content) {
        content.addEventListener('input', function() {
            charCount.textContent = this.value.length + ' / 5000';
        });
    }
});
</script>
@endsection
