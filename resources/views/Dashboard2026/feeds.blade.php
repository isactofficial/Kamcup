@extends('layouts.admin')

@section('title', 'Kelola Feeds')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="fw-bold" style="color: #00617a;">Feeds</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFeedModal">
                    <i class="fas fa-plus me-2"></i>Tambah Feed Baru
                </button>
            </div>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Gambar</th>
                            <th>Judul</th>
                            <th>Isi</th>
                            <th>Likes</th>
                            <th>Comments</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($feeds as $feed)
                        <tr>
                            <td>
                                @if ($feed->image)
                                    <img src="{{ asset('storage/' . $feed->image) }}" alt="Feed image" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <i class="fas fa-image text-muted" style="font-size: 24px;"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ Str::limit($feed->title ?? 'Tanpa judul', 50) }}</strong>
                            </td>
                            <td>{{ Str::limit($feed->content ?? '', 100) }}</td>
                            <td><span class="badge bg-primary">{{ $feed->likes_count }}</span></td>
                            <td><span class="badge bg-info">{{ $feed->comments_count }}</span></td>
                            <td>{{ $feed->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <form action="{{ route('admin.feeds.destroy', $feed) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus feed ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-rss fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada feeds. Tambahkan yang pertama!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">
                {{ $feeds->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Add Feed Modal --}}
<div class="modal fade" id="addFeedModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Feed Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.feeds.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul (Opsional)</label>
                        <textarea name="title" class="form-control @error('title') is-invalid @enderror" rows="2" placeholder="Masukkan judul feed (opsional)">{{ old('title') }}</textarea>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Isi (Opsional)</label>
                        <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="5" placeholder="Masukkan isi feed (opsional, minimal 1 field harus terisi)" maxlength="5000">{{ old('content') }}</textarea>
                        <div class="form-text">
                            <span id="char-count">{{ strlen(old('content', '')) }} </span>/ 5000 karakter
                        </div>
                        @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar (Opsional)</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        <div class="form-text small text-muted">Min 1 field harus terisi (judul, isi, atau gambar). Max 2MB</div>
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="alert alert-info">
                        <small><strong>Validasi:</strong> Minimal 1 field harus terisi. Gambar opsional tapi direkomendasikan.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Publikasikan Feed</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.table-hover tbody tr:hover {
    background-color: rgba(203, 39, 134, 0.05);
}
.modal .form-control:focus {
    border-color: #cb2786;
    box-shadow: 0 0 0 0.2rem rgba(203, 39, 134, 0.25);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const content = document.getElementById('content');
    const charCount = document.getElementById('char-count');

    if (content) {
        content.addEventListener('input', function() {
            charCount.textContent = this.value.length + ' / 5000';
        });
    }

    // Form validation JS (server validation primary)
    const form = document.querySelector('#addFeedModal form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const title = this.querySelector('[name=\"title\"]').value.trim();
            const content = this.querySelector('[name=\"content\"]').value.trim();
            const image = this.querySelector('[name=\"image\"]').files[0];
            
            if (!title && !content && !image) {
                e.preventDefault();
                alert('⚠️ Minimal satu field harus terisi (judul, isi, atau gambar)!');
                return false;
            }
        });
    }
});
</script>
@endpush
@endsection
