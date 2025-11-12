@extends('layouts.admin')

@section('content')
<style>
/* Style kustom untuk badge status tim */
.badge.status-pending {
    background-color: #f4b704; /* Kuning */
    color: #333;
}
.badge.status-approved {
    background-color: #28a745; /* Hijau */
    color: #fff;
}
.badge.status-rejected {
    background-color: #dc3545; /* Merah */
    color: #fff;
}
.team-logo-sm {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 0.375rem; /* rounded */
    border: 2px solid #eee;
}
</style>

<div class="container-fluid px-4" style="min-height: 100vh;">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid var(--primary-color);">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 fw-bold mb-0" style="color: var(--primary-color);">Manajemen Tim</h1>
                        <p class="mb-0 text-muted">Kelola, setujui, dan edit semua tim yang terdaftar.</p>
                    </div>
                    {{-- Admin tidak membuat tim, jadi tombol 'create' dihilangkan --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Sorting & Filtering Form --}}
    <div class="row mb-4">
        <div class="col-12">
            <form method="GET" class="d-flex align-items-center gap-3 bg-white p-3 rounded-4 shadow-sm">
                <span class="text-muted fw-semibold">Urutkan:</span>
                <select name="sort" class="form-select form-select-sm border-0 bg-light rounded-pill px-3 py-2"
                    onchange="this.form.submit()" style="width: auto; cursor: pointer;">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                </select>
                <span class="text-muted fw-semibold ms-auto">Status:</span>
                <select name="status" class="form-select form-select-sm border-0 bg-light rounded-pill px-3 py-2"
                    onchange="this.form.submit()" style="width: auto; cursor: pointer;">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </form>
        </div>
    </div>

    {{-- Tabel Daftar Tim --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light" style="background-color: var(--bg-light) !important;">
                        <tr>
                            <th class="py-3 px-4">Logo</th>
                            <th class="py-3 px-4">Nama Tim</th>
                            <th class="py-3 px-4">Manajer</th>
                            <th class="py-3 px-4">Pemilik</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Lokasi</th>
                            {{-- PERBAIKAN: Mengganti 'classd-flex' menjadi 'class' --}}
                            <th class="py-3 px-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($teams as $team)
                            <tr>
                                <td class="px-4">
                                    <img src="{{ $team->logo ? asset('storage/' . $team->logo) : asset('assets/img/team-placeholder.png') }}" alt="Logo" class="team-logo-sm">
                                </td>
                                <td class="px-4">
                                    <strong class="text-dark">{{ $team->name }}</strong>
                                </td>
                                <td class="px-4">{{ $team->manager_name }}</td>
                                <td class="px-4">{{ $team->user->name ?? 'N/A' }}</td>
                                <td class="px-4">
                                    <span class="badge px-3 py-2 rounded-pill status-{{ $team->status }}">
                                        {{ ucfirst($team->status) }}
                                    </span>
                                </td>
                                <td class="px-4">{{ $team->location }}</td>
                                {{-- PERBAIKAN: Mengubah style tombol aksi --}}
                                <td class="px-4">
                                    <form id="delete-form-{{ $team->id }}" action="{{ route('admin.teams.destroy', $team->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('admin.teams.edit', $team->id) }}" 
                                           class="btn btn-sm px-2 py-1 rounded-pill" 
                                           style="background-color: #f4b704; color: #212529;" 
                                           title="Edit/Approve">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        
                                        <button type="button" 
                                                class="btn btn-sm px-2 py-1 rounded-pill" 
                                                style="background-color: #cb2786; color: white;" 
                                                title="Hapus"
                                                onclick="confirmDelete('delete-form-{{ $team->id }}')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-search fa-2x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Tidak ada tim yang ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if ($teams->hasPages())
            <div class="card-footer bg-white">
                {{ $teams->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(formId) {
    Swal.fire({
        title: 'Konfirmasi Hapus?',
        text: "Anda yakin ingin menghapus tim ini? Data tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batalkan',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

// Tampilkan pesan sukses jika ada
@if(session('success'))
Swal.fire({
    icon: 'success',
    title: "Berhasil!",
    text: "{{ session('success') }}",
    timer: 2000,
    showConfirmButton: false
});
@endif
</script>
@endpush