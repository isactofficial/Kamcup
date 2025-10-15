@extends('layouts.admin')

@section('content')
<style>
/* Konsisten dengan halaman Manajemen Artikel (sporty youthful) */
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
.custom-select-dropdown:hover { background-color: #e0f2fe; }
.custom-select-dropdown:focus {
    border-color: #00617a;
    box-shadow: 0 0 0 0.25rem rgba(0, 97, 122, 0.25);
    outline: none;
}
.custom-select-dropdown option { font-weight: normal; color: #495057; }

/* Tombol aksi utama */
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
.btn-sporty-primary:hover { background-color: #004a5c; border-color: #004a5c; transform: translateY(-2px); }

.logo-img { width: 120px; height: 60px; object-fit: contain; border-radius: 0.375rem; }

/* Kartu mobile (meniru gaya mobile-article-card) */
.mobile-sponsor-card {
    border: 1px solid #e9ecef;
    border-radius: 1rem;
    background-color: #fff;
    overflow: hidden;
}
.mobile-sponsor-card .card-thumbnail { width: 100%; height: 150px; object-fit: contain; background:#f8f9fa; border-bottom:1px solid #e9ecef; display:flex; align-items:center; justify-content:center; }
.mobile-sponsor-card .card-thumbnail img { max-width: 100%; max-height: 100%; object-fit: contain; }
.mobile-sponsor-card .card-title { font-weight: 600; color: #343a40; }
.mobile-sponsor-card .action-buttons .btn { border-radius: 0.5rem; font-size: 1.1rem; }
.badge-size {
    background-color: rgba(0, 97, 122, 0.15);
    color: #00617a;
    font-weight: 600;
    padding: 0.35em 0.65em;
    border-radius: 0.5rem;
}

@media (max-width: 575.98px) {
    .logo-img { width: 100px; height: 48px; }
}
</style>

<div class="container-fluid px-4" style="min-height: 100vh;">

    {{-- Header ala Manajemen Artikel --}}
    <div class="bg-white rounded-4 shadow-sm p-3 p-md-4 mb-4" style="border-left: 8px solid #00617a;">
        <div class="d-flex align-items-center">
            <div class="d-flex justify-content-center align-items-center rounded-circle me-3 me-md-4"
                 style="width: 50px; height: 50px; background-color: rgba(0, 97, 122, 0.1);">
                <i class="fas fa-handshake fs-4" style="color: #00617a;"></i>
            </div>
            <div>
                <h2 class="fs-4 fs-md-3 fw-bold mb-1" style="color: #00617a;">Manajemen Sponsor</h2>
                <p class="text-muted mb-0 d-none d-md-block">Kelola daftar sponsor dan informasi kemitraan.</p>
            </div>
        </div>
    </div>

    {{-- Add Button --}}
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-end">
            <a href="{{ route('admin.sponsors.create') }}" class="btn btn-sporty-primary d-flex align-items-center px-4 py-2">
                <i class="fas fa-plus me-2"></i>
                <span class="fw-semibold">Tambah Sponsor Baru</span>
            </a>
        </div>
    </div>

    {{-- Sponsor Table --}}
    {{-- Sporty youthful: rounded card with shadow --}}
    <div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-3 p-md-4">
            @if(session('success'))
                {{-- Expressive: success alert with primary color --}}
                <div class="alert rounded-3 text-white m-0" style="background-color: #00617a; border: none;">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                <h1 class="fs-5 fw-bold mb-3 mb-md-0" style="color: #00617a;">Daftar Sponsor</h1>
                <div>
                    <form method="GET" class="d-flex align-items-center gap-2">
                        <span class="text-muted fw-semibold me-2 d-none d-md-inline">Urutkan:</span>
                        <select name="sort" class="form-select form-select-sm custom-select-dropdown border-0" onchange="this.form.submit()" style="width: auto;">
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama</option>
                            <option value="sponsor_size" {{ request('sort') == 'sponsor_size' ? 'selected' : '' }}>Ukuran Sponsor</option>
                        </select>
                    </form>
                </div>
            </div>
            {{-- Mobile Card View (visible on small screens) --}}
            <div class="d-block d-lg-none">
                @forelse($sponsors as $sponsor)
                    <div class="mobile-sponsor-card shadow-sm mb-3">
                        <div class="card-thumbnail">
                            @if($sponsor->logo)
                                <img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }} logo">
                            @else
                                <span class="text-muted small">Tanpa Logo</span>
                            @endif
                        </div>
                        <div class="p-3">
                            <h3 class="card-title fs-6 mb-2">{{ $sponsor->name }}</h3>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge badge-size">{{ strtoupper($sponsor->sponsor_size) }}</span>
                                @if(!is_null($sponsor->order_number))
                                    <span class="text-muted small">Urutan: {{ $sponsor->order_number }}</span>
                                @endif
                            </div>
                            <p class="mb-3 text-truncate" style="max-width:100%;" title="{{ $sponsor->description }}">{{ $sponsor->description }}</p>

                            <div class="action-buttons d-flex gap-2">
                                <a href="{{ route('admin.sponsors.edit', $sponsor->id) }}" class="btn btn-light border flex-fill" style="color: #f4b704;" title="Edit Sponsor">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.sponsors.destroy', $sponsor->id) }}" method="POST" class="d-flex flex-fill">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-light border w-100" style="color: #cb2786;" title="Hapus Sponsor">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-box-open me-2 fs-3"></i>
                        <p class="mt-2">Belum ada sponsor ditemukan.</p>
                    </div>
                @endforelse
            </div>

            <div class="table-responsive d-none d-lg-block">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr style="background-color: #f8f9fa;"> {{-- Light background for header row --}}
                            <th class="py-3" style="color: #6c757d;">Logo</th>
                            <th class="py-3" style="color: #6c757d;">Nama</th>
                            <th class="py-3" style="color: #6c757d;">Ukuran Sponsor</th>
                            <th class="py-3" style="color: #6c757d;">Deskripsi</th>
                            <th class="py-3" style="color: #6c757d;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sponsors as $sponsor)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td class="py-3">
                                    @if($sponsor->logo)
                                        <img src="{{ asset('storage/' . $sponsor->logo) }}" alt="logo" class="logo-img">
                                    @else
                                        <div class="bg-light rounded-3 d-flex justify-content-center align-items-center" style="width: 120px; height: 60px;">
                                            <span class="text-muted small">Tanpa Logo</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 fw-semibold">{{ $sponsor->name }}</td>
                                <td class="py-3">{{ ucfirst($sponsor->sponsor_size) }}</td>
                                <td class="py-3 text-truncate" style="max-width: 300px;" title="{{ $sponsor->description }}">{{ $sponsor->description }}</td>
                                <td class="py-3">
                                    <div class="d-flex gap-2">
                                        {{-- Interactive care: styled action buttons --}}
                                        <a href="{{ route('admin.sponsors.edit', $sponsor->id) }}" class="btn btn-sm px-2 py-1 rounded-pill" style="background-color: #f4b704; color: #212529;" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        {{-- Rute DELETE sudah benar menggunakan $sponsor->id --}}
                                        <form action="{{ route('admin.sponsors.destroy', $sponsor->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-sm px-2 py-1 rounded-pill" style="background-color: #cb2786; color: white;" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada sponsor ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Custom Pagination --}}
            @if($sponsors->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    <nav aria-label="Sponsor pagination">
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Previous Page Link --}}
                            @if ($sponsors->onFirstPage())
                                <li class="page-item disabled"><span class="page-link rounded-3 border-0">&laquo;</span></li>
                            @else
                                <li class="page-item">
                                    <a class="page-link rounded-3 border-0" href="{{ $sponsors->previousPageUrl() }}" rel="prev" style="color: #00617a;">&laquo;</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @php
                                $currentPage = $sponsors->currentPage();
                                $lastPage = $sponsors->lastPage();
                                $pageRange = 5;

                                $startPage = max(1, $currentPage - floor($pageRange / 2));
                                $endPage = min($lastPage, $currentPage + floor($pageRange / 2));

                                if ($currentPage < floor($pageRange / 2)) {
                                    $endPage = min($lastPage, $pageRange);
                                }

                                if ($currentPage > $lastPage - floor($pageRange / 2)) {
                                    $startPage = max(1, $lastPage - $pageRange + 1);
                                }
                            @endphp

                            @for ($i = $startPage; $i <= $endPage; $i++)
                                @if ($i == $currentPage)
                                    <li class="page-item active">
                                        {{-- Sporty visioner: active page with primary color --}}
                                        <span class="page-link rounded-3 border-0" style="background-color: #00617a; color: white;">{{ $i }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link rounded-3 border-0" href="{{ $sponsors->url($i) }}" style="color: #00617a;">{{ $i }}</a>
                                    </li>
                                @endif
                            @endfor

                            {{-- Next Page Link --}}
                            @if ($sponsors->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link rounded-3 border-0" href="{{ $sponsors->nextPageUrl() }}" rel="next" style="color: #00617a;">&raquo;</a>
                                </li>
                            @else
                                <li class="page-item disabled"><span class="page-link rounded-3 border-0">&raquo;</span></li>
                            @endif
                        </ul>
                    </nav>
                </div>
            @endif

        </div>
    </div>
</div>

{{-- SweetAlert (sesuaikan gaya dengan artikel) --}}
<script>
function confirmDelete(event, form) {
    event.preventDefault();

    Swal.fire({
        title: "Yakin ingin menghapus sponsor ini?",
        text: "Anda tidak akan bisa mengembalikannya!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#cb2786",
        cancelButtonColor: "#808080",
        confirmButtonText: "Ya, Hapus Sekarang!",
        cancelButtonText: "Batalkan",
        customClass: { popup: 'rounded-4', confirmButton: 'rounded-pill px-4', cancelButton: 'rounded-pill px-4' }
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
    text: "Operasi berhasil!",
    showConfirmButton: false,
    timer: 2000,
    customClass: { popup: 'rounded-4' }
});
@endif

@if(session('error'))
Swal.fire({
    icon: 'error',
    title: "Oops...",
    text: "{{ session('error') }}",
    confirmButtonColor: "#cb2786",
    customClass: { popup: 'rounded-4', confirmButton: 'rounded-pill px-4' }
});
@endif
</script>

@endsection
