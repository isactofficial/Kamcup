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

/* Mobile Responsive Styles */
@media (max-width: 768px) {
    .team-logo-sm {
        width: 50px;
        height: 50px;
    }
    
    /* Hide table on mobile, show cards instead */
    .desktop-table {
        display: none;
    }
    
    .mobile-cards {
        display: block;
    }
    
    .team-card {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .team-card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }
    
    .team-info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .team-info-label {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.875rem;
    }
    
    .team-info-value {
        color: #212529;
        font-size: 0.875rem;
        text-align: right;
    }
    
    .mobile-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #eee;
    }
    
    .mobile-actions .btn {
        flex: 1;
    }
}

@media (min-width: 769px) {
    .desktop-table {
        display: block;
    }
    
    .mobile-cards {
        display: none;
    }
}

/* Responsive Filter Form */
@media (max-width: 576px) {
    .filter-form {
        flex-direction: column !important;
        gap: 0.75rem !important;
    }
    
    .filter-form select {
        width: 100% !important;
    }
    
    .filter-form span {
        align-self: flex-start;
    }
}
</style>

<div class="container-fluid px-4" style="min-height: 100vh;">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-3 p-md-4" style="border-left: 8px solid var(--primary-color);">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <h1 class="h4 h3-md fw-bold mb-1 mb-md-0" style="color: var(--primary-color);">Manajemen Tim</h1>
                        <p class="mb-0 text-muted small">Kelola, setujui, dan edit semua tim yang terdaftar.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sorting & Filtering Form --}}
    <div class="row mb-4">
        <div class="col-12">
            <form method="GET" class="filter-form d-flex align-items-center gap-3 bg-white p-3 rounded-4 shadow-sm">
                <span class="text-muted fw-semibold small">Urutkan:</span>
                <select name="sort" class="form-select form-select-sm border-0 bg-light rounded-pill px-3 py-2"
                    onchange="this.form.submit()" style="width: auto; cursor: pointer;">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                </select>
                <span class="text-muted fw-semibold small ms-auto">Status:</span>
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

    {{-- Desktop Table View --}}
    <div class="desktop-table">
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
                                    <td class="px-4">{{ optional($team->user)->name ?? 'N/A' }}</td>
                                    <td class="px-4">
                                        <span class="badge px-3 py-2 rounded-pill status-{{ $team->status }}">
                                            {{ ucfirst($team->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4">{{ $team->location }}</td>
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

    {{-- Mobile Card View --}}
    <div class="mobile-cards">
        @forelse ($teams as $team)
            <div class="team-card">
                <div class="team-card-header">
                    <img src="{{ $team->logo ? asset('storage/' . $team->logo) : asset('assets/img/team-placeholder.png') }}" 
                         alt="Logo" 
                         class="team-logo-sm">
                    <div class="flex-grow-1">
                        <h6 class="mb-1 fw-bold">{{ $team->name }}</h6>
                        <span class="badge px-2 py-1 rounded-pill status-{{ $team->status }}">
                            {{ ucfirst($team->status) }}
                        </span>
                    </div>
                </div>
                
                <div class="team-info-row">
                    <span class="team-info-label">Manajer</span>
                    <span class="team-info-value">{{ $team->manager_name }}</span>
                </div>
                
                <div class="team-info-row">
                    <span class="team-info-label">Pemilik</span>
                    <span class="team-info-value">{{ optional($team->user)->name ?? 'N/A' }}</span>
                </div>
                
                <div class="team-info-row">
                    <span class="team-info-label">Lokasi</span>
                    <span class="team-info-value">{{ $team->location }}</span>
                </div>
                
                <form id="delete-form-mobile-{{ $team->id }}" action="{{ route('admin.teams.destroy', $team->id) }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
                
                <div class="mobile-actions">
                    <a href="{{ route('admin.teams.edit', $team->id) }}" 
                       class="btn btn-sm rounded-pill" 
                       style="background-color: #f4b704; color: #212529;">
                        <i class="fas fa-pencil-alt me-1"></i> Edit
                    </a>
                    
                    <button type="button" 
                            class="btn btn-sm rounded-pill" 
                            style="background-color: #cb2786; color: white;"
                            onclick="confirmDelete('delete-form-mobile-{{ $team->id }}')">
                        <i class="fas fa-trash-alt me-1"></i> Hapus
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <p class="text-muted mb-0">Tidak ada tim yang ditemukan.</p>
            </div>
        @endforelse
        
        @if ($teams->hasPages())
            <div class="mt-4">
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