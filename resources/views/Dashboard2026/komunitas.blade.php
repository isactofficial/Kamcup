@extends('layouts.admin')

@section('title', 'Manajemen Komunitas')

@section('content')
<style>
/* Custom Select Dropdown - Konsisten dengan Manajemen Artikel */
.custom-select-dropdown {
    background-color: #f0f8ff;
    border-radius: 0.75rem;
    padding: 0.6rem 1.2rem;
    font-size: 0.9rem;
    font-weight: 600;
    color: #00617a;
    transition: all 0.3s ease-in-out;
    border: 1px solid rgba(0, 97, 122, 0.2);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.custom-select-dropdown:hover {
    background-color: #e0f2fe;
}

.custom-select-dropdown:focus {
    border-color: #00617a;
    box-shadow: 0 0 0 0.25rem rgba(0, 97, 122, 0.25);
    outline: none;
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
.badge-official {
    background-color: rgba(0, 97, 122, 0.15);
    color: #00617a;
    font-weight: 600;
    padding: 0.4em 0.8em;
    border-radius: 0.5rem;
}

.badge-member {
    background-color: rgba(203, 39, 134, 0.15);
    color: #cb2786;
    font-weight: 600;
    padding: 0.4em 0.8em;
    border-radius: 0.5rem;
}

.badge-status-public {
    background-color: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.badge-status-private {
    background-color: rgba(244, 183, 4, 0.1);
    color: #d39e00;
}

/* Mobile Card View */
.mobile-entity-card {
    border: 1px solid #e9ecef;
    border-radius: 1rem;
    background-color: #fff;
    overflow: hidden;
    margin-bottom: 1rem;
}

.mobile-entity-card .card-logo {
    width: 60px;
    height: 60px;
    object-fit: contain;
    border-radius: 12px;
}

.mobile-entity-card .action-buttons .btn {
    border-radius: 0.5rem;
    font-size: 1.1rem;
}
</style>

<div class="container-fluid px-4 py-4" style="min-height: 100vh;">

    {{-- Header Section --}}
    <div class="bg-white rounded-4 shadow-sm p-3 p-md-4 mb-4" style="border-left: 8px solid #cb2786;">
        <div class="d-flex align-items-center">
            <div class="d-flex justify-content-center align-items-center rounded-circle me-3 me-md-4"
                 style="width: 50px; height: 50px; background-color: rgba(203, 39, 134, 0.1);">
                <i class="fas fa-users fs-4" style="color: #cb2786;"></i>
            </div>
            <div>
                <h2 class="fs-4 fs-md-3 fw-bold mb-1" style="color: #495057;">Manajemen Komunitas</h2>
                <p class="text-muted mb-0 d-none d-md-block">Kelola komunitas, verifikasi klub official, dan pantau keaktifan member.</p>
            </div>
        </div>
    </div>

    {{-- Add Button & Stats --}}
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-end">
            <a href="{{ route('admin.userpages.komunitas.create') }}" class="btn btn-sporty-primary d-flex align-items-center px-4 py-2">
                <i class="fas fa-plus me-2"></i>
                <span class="fw-semibold">Tambah Komunitas Official</span>
            </a>
        </div>
    </div>

    {{-- Content Card --}}
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                <h1 class="fs-5 fw-bold mb-3 mb-md-0" style="color: #495057;">Daftar Komunitas</h1>
                <div>
                    <form method="GET" class="d-flex align-items-center gap-2">
                        <span class="text-muted fw-semibold me-2 d-none d-md-inline">Urutkan:</span>
                        <select name="sort" class="form-select form-select-sm custom-select-dropdown border-0" onchange="this.form.submit()" style="width: auto;">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="members" {{ request('sort') == 'members' ? 'selected' : '' }}>Paling Banyak Member</option>
                            <option value="official" {{ request('sort') == 'official' ? 'selected' : '' }}>Official Teratas</option>
                        </select>
                    </form>
                </div>
            </div>

            {{-- Mobile View --}}
            <div class="d-block d-lg-none">
                @forelse($communities as $community)
                    <div class="mobile-entity-card shadow-sm p-3">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light d-flex justify-content-center align-items-center" style="width: 60px; height: 60px; border-radius: 12px; overflow: hidden;">
                                @if($community->image)
                                    <img src="{{ asset('storage/' . $community->image) }}" class="w-100 h-100" style="object-fit: contain;">
                                @else
                                    <i class="fas fa-users text-muted fs-4"></i>
                                @endif
                            </div>
                            <div class="ms-3">
                                <h3 class="fs-6 fw-bold mb-0 text-dark">{{ $community->name }}</h3>
                                <div class="d-flex gap-1 mt-1">
                                    <span class="badge {{ $community->is_official ? 'badge-official' : 'badge-member' }}" style="font-size: 0.65rem;">
                                        {{ $community->is_official ? 'Official' : 'Member Club' }}
                                    </span>
                                    <span class="badge {{ $community->status == 'public' ? 'badge-status-public' : 'badge-status-private' }}" style="font-size: 0.65rem; border: 1px solid currentColor;">
                                        {{ ucfirst($community->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mb-3 px-1">
                            <span class="text-muted small"><i class="fas fa-basketball-ball me-1"></i>{{ $community->category }}</span>
                            <span class="text-muted small"><i class="fas fa-user-friends me-1"></i>{{ $community->members_count }} Member</span>
                        </div>
                        <div class="action-buttons d-flex gap-2">
                            {{-- Placeholder for details/edit if needed --}}
                            <form action="{{ route('admin.userpages.komunitas.destroy', $community->id) }}" method="POST" class="d-flex flex-fill">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-light border w-100 py-2" style="color: #cb2786;">
                                    <i class="fas fa-trash me-2"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-users-slash fs-2 mb-2"></i>
                        <p>Belum ada komunitas.</p>
                    </div>
                @endforelse
            </div>

            {{-- Desktop View --}}
            <div class="table-responsive d-none d-lg-block">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="py-3">Info</th>
                            <th class="py-3">Pembuat</th>
                            <th class="py-3">Kategori</th>
                            <th class="py-3">Member</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($communities as $community)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light me-3 d-flex justify-content-center align-items-center" style="width: 50px; height: 50px; border-radius: 8px; overflow: hidden; border: 1px solid #eee;">
                                            @if($community->image)
                                                <img src="{{ asset('storage/' . $community->image) }}" class="w-100 h-100" style="object-fit: contain;">
                                            @else
                                                <i class="fas fa-users text-muted p-2"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $community->name }}</div>
                                            <span class="badge {{ $community->is_official ? 'badge-official' : 'badge-member' }}" style="font-size: 0.7rem;">
                                                {{ $community->is_official ? 'Official' : 'Member Club' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="small fw-semibold">{{ $community->creator->name }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $community->created_at->format('d M Y') }}</div>
                                </td>
                                <td class="py-3 fw-semibold">{{ $community->category }}</td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-friends me-2 text-muted"></i>
                                        <span class="fw-bold" style="color: #00617a;">{{ $community->members_count }}</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="badge {{ $community->status == 'public' ? 'badge-status-public' : 'badge-status-private' }} rounded-pill px-3 py-2" style="font-size: 0.75rem; border: 1px solid currentColor;">
                                        {{ ucfirst($community->status) }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <form action="{{ route('admin.userpages.komunitas.destroy', $community->id) }}" method="POST">
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
                                    <i class="fas fa-users-slash fa-2x mb-3 d-block"></i>
                                    Tidak ada komunitas yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4 d-flex justify-content-center">
                {{ $communities->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
            </div>

        </div>
    </div>
</div>

{{-- SweetAlert Logic --}}
<script>
function confirmDelete(event, form) {
    event.preventDefault();
    Swal.fire({
        title: "Hapus Komunitas?",
        text: "Seluruh data member and history komunitas akan hilang permanen!",
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
</script>
@endsection
