@extends('layouts.admin')

@section('content')
<style>
/* --- Styling untuk Dropdown Filter --- */
.custom-filter-select {
    background-color: #f8f9fa;
    border-radius: 1rem;
    padding: 0.5rem 2rem 0.5rem 1rem; /* Menambah padding kanan untuk ruang panah */
    font-size: 0.9rem;
    font-weight: 500;
    color: #495057;
    border: 1px solid #dee2e6;
    -webkit-appearance: none; /* Menghilangkan tampilan default browser */
    -moz-appearance: none;
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e"); /* Panah custom */
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
}

/* --- Styling untuk Mobile Card View --- */
.mobile-request-card {
    border: 1px solid #e9ecef;
    border-radius: 1rem;
    background-color: #fff;
    margin-bottom: 1rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.mobile-request-card .card-header {
    background-color: #f8f9fa;
    padding: 0.75rem 1rem;
    font-weight: 600;
    color: #343a40;
    border-bottom: 1px solid #e9ecef;
    font-size: 0.9rem;
}

.mobile-request-card .card-body {
    padding: 1rem;
}

.mobile-request-card .detail-item {
    display: flex;
    align-items: center;
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.mobile-request-card .detail-item i {
    width: 20px;
    text-align: center;
    margin-right: 0.75rem;
    color: #0F62FF;
}

.mobile-request-card .card-footer {
    background-color: #fff;
    border-top: 1px solid #e9ecef;
    padding: 0.75rem 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.mobile-request-card .status-badge {
    font-size: 0.75rem;
    margin-bottom: 0.5rem;
    flex-shrink: 0;
}
</style>

<div class="container-fluid px-4">
    {{-- Host Requests Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid #0F62FF;">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                         style="width: 70px; height: 70px; background-color: rgba(15, 98, 255, 0.1);">
                        <i class="fas fa-medal fs-2" style="color: #0F62FF;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: #0F62FF;">Permintaan Host Turnamen</h2>
                        <p class="text-muted mb-0">Kelola dan tinjau permintaan host dari komunitas aktif kami.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sorting & Filtering Form --}}
    <div class="row mb-4">
        <div class="col-12">
            <form method="GET" class="bg-white p-3 rounded-4 shadow-sm">
                <div class="row g-3 align-items-center">
                    {{-- Filter Urutkan --}}
                    <div class="col-12 col-md-auto d-flex align-items-center">
                        <label for="sort-select" class="text-muted fw-semibold small me-2">Urutkan:</label>
                        <select id="sort-select" name="sort" class="form-select form-select-sm custom-filter-select"
                                onchange="this.form.submit()" style="width: auto; cursor: pointer;">
                            <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        </select>
                    </div>

                    {{-- Filter Status --}}
                    <div class="col-12 col-md-auto d-flex align-items-center ms-md-auto">
                        <label for="status-select" class="text-muted fw-semibold small me-2">Status:</label>
                        <select id="status-select" name="status" class="form-select form-select-sm custom-filter-select"
                                onchange="this.form.submit()" style="width: auto; cursor: pointer;">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Content --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            @if (session('success'))
                <div class="alert alert-success m-3 mb-0 border-0 rounded-3"
                     style="background-color: #e6f7f1; color: #36b37e;">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if($hostRequests->isEmpty())
                <div class="alert alert-info m-3 border-0 rounded-3" style="background-color: #eaf6fe; color: #337ab7;">
                    <i class="fas fa-info-circle me-2"></i>Tidak ada permintaan host yang perlu ditinjau saat ini.
                </div>
            @else
                {{-- Mobile View --}}
                <div class="d-block d-lg-none p-3">
                    @foreach($hostRequests as $request)
                        <div class="mobile-request-card">
                            <div class="card-header">
                                {{ Str::limit($request->tournament_title, 30) }}
                            </div>
                            <div class="card-body">
                                <div class="detail-item">
                                    <i class="fas fa-user"></i>
                                    <span>{{ $request->responsible_name }}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-envelope"></i>
                                    <span>{{ $request->email }}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-phone"></i>
                                    <span>{{ $request->phone }}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>{{ $request->created_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                @php
                                    $statusColor = '';
                                    $statusLabel = '';
                                    switch ($request->status) {
                                        case 'pending': $statusColor = '#f4b704'; $statusLabel = 'Pending'; break;
                                        case 'approved': $statusColor = '#00617a'; $statusLabel = 'Disetujui'; break;
                                        case 'rejected': $statusColor = '#cb2786'; $statusLabel = 'Ditolak'; break;
                                        default: $statusColor = '#6c757d'; $statusLabel = ucfirst($request->status); break;
                                    }
                                @endphp
                                <span class="badge rounded-pill px-3 py-2 text-white status-badge" style="background-color: {{ $statusColor }};">
                                    {{ $statusLabel }}
                                </span>
                                <div class="d-flex gap-2 ms-auto">
                                    <a href="{{ route('admin.host-requests.show', $request->id) }}"
                                       class="btn btn-sm text-white rounded-pill"
                                       style="background-color: #00617a;" title="Lihat Detail">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                    @if ($request->status == 'pending')
                                        <form action="{{ route('admin.host-requests.approve', $request->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm text-dark rounded-pill"
                                                    style="background-color: #f4b704;"
                                                    onclick="confirmAction(event, this.parentElement, 'approve')" title="Setujui">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm text-white rounded-pill"
                                                style="background-color: #cb2786;"
                                                onclick="showRejectModal({{ $request->id }})" title="Tolak">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                    <!-- TOMBOL HAPUS BARU (MOBILE) -->
                                    <form action="{{ route('admin.host-requests.destroy', $request->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger text-white rounded-pill"
                                                onclick="confirmAction(event, this.parentElement, 'delete')" title="Hapus Permanen">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Desktop View --}}
                <div class="table-responsive d-none d-lg-block p-4">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr style="background-color: #f8f9fa;">
                                <th class="py-3" style="color: #6c757d;">Penanggung Jawab</th>
                                <th class="py-3" style="color: #6c757d;">Email Kontak</th>
                                <th class="py-3" style="color: #6c757d;">Telepon</th>
                                <th class="py-3" style="color: #6c757d;">Judul Turnamen</th>
                                <th class="py-3" style="color: #6c757d;">Tanggal Diajukan</th>
                                <th class="py-3" style="color: #6c757d;">Status</th>
                                <th class="py-3" style="color: #6c757d;">Aksi Cepat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hostRequests as $request)
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td class="py-3 fw-semibold">{{ $request->responsible_name }}</td>
                                    <td class="py-3">{{ $request->email }}</td>
                                    <td class="py-3">{{ $request->phone }}</td>
                                    <td class="py-3">{{ Str::limit($request->tournament_title, 30) }}</td>
                                    <td class="py-3 text-muted">{{ $request->created_at->format('d M Y H:i') }}</td>
                                    <td class="py-3">
                                        @php
                                            $statusColor = '';
                                            $statusLabel = '';
                                            switch ($request->status) {
                                                case 'pending': $statusColor = '#f4b704'; $statusLabel = 'Pending'; break;
                                                case 'approved': $statusColor = '#00617a'; $statusLabel = 'Disetujui'; break;
                                                case 'rejected': $statusColor = '#cb2786'; $statusLabel = 'Ditolak'; break;
                                                default: $statusColor = '#6c757d'; $statusLabel = ucfirst($request->status); break;
                                            }
                                        @endphp
                                        <span class="badge rounded-pill px-3 py-2 text-white"
                                              style="background-color: {{ $statusColor }};">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.host-requests.show', $request->id) }}"
                                               class="btn btn-sm text-white rounded-pill"
                                               style="background-color: #00617a;"
                                               title="Lihat Detail">
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                            @if ($request->status == 'pending')
                                                <form
                                                    action="{{ route('admin.host-requests.approve', $request->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm text-dark rounded-pill"
                                                            style="background-color: #f4b704;"
                                                            onclick="confirmAction(event, this.parentElement, 'approve')"
                                                            title="Setujui">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-sm text-white rounded-pill"
                                                        style="background-color: #cb2786;"
                                                        onclick="showRejectModal({{ $request->id }})"
                                                        title="Tolak">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                            <!-- TOMBOL HAPUS BARU (DESKTOP) -->
                                            <form action="{{ route('admin.host-requests.destroy', $request->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger text-white rounded-pill"
                                                        onclick="confirmAction(event, this.parentElement, 'delete')"
                                                        title="Hapus Permanen">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Pagination --}}
    @if ($hostRequests->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $hostRequests->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="rejectModalLabel">
                    <i class="fas fa-times-circle me-2 text-danger"></i>Tolak Permintaan Host
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p class="text-muted mb-3">Harap berikan alasan penolakan untuk permintaan host ini.</p>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Alasan Penolakan:</label>
                        <textarea class="form-control"
                                  id="rejection_reason"
                                  name="rejection_reason"
                                  rows="4"
                                  required
                                  placeholder="Contoh: Venue tidak memenuhi standar, tanggal tidak tersedia, dll."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">
                        Tolak Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> {{-- Pastikan Bootstrap JS di-load --}}
    <script>
        function confirmAction(event, form, actionType) {
            event.preventDefault();
            let title = '';
            let text = '';
            let icon = '';
            let confirmButtonText = '';
            let confirmButtonColor = '';

            if (actionType === 'approve') {
                title = 'Konfirmasi Persetujuan';
                text = "Anda yakin ingin menyetujui permintaan host ini?";
                icon = 'question';
                confirmButtonText = 'Ya, Setujui!';
                confirmButtonColor = '#00617a';
            } else if (actionType === 'delete') { // <-- BLOK BARU UNTUK HAPUS
                title = 'Konfirmasi Hapus';
                text = "Anda yakin ingin menghapus permintaan ini secara permanen? Tindakan ini tidak dapat dibatalkan.";
                icon = 'warning';
                confirmButtonText = 'Ya, Hapus!';
                confirmButtonColor = '#dc3545'; // Warna btn-danger
            } else {
                return; // Tipe aksi tidak dikenal
            }

            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmButtonColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-4',
                    confirmButton: 'rounded-pill px-4',
                    cancelButton: 'rounded-pill px-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        function showRejectModal(requestId) {
            const form = document.getElementById('rejectForm');
            // Pastikan URL route-nya benar. Sesuaikan jika perlu.
            form.action = `/admin/host-requests/${requestId}/reject`;
            const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
            rejectModal.show();
        }

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000,
                customClass: {
                    popup: 'rounded-4'
                }
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: "Terjadi Kesalahan!",
                text: "{{ session('error') }}",
                showConfirmButton: true,
                confirmButtonColor: '#cb2786',
                customClass: {
                    popup: 'rounded-4',
                    confirmButton: 'rounded-pill px-4'
                }
            });
        @endif
    </script>
@endpush