@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid #00617a;">
                    <div class="d-flex align-items-center">
                        <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                            style="width: 70px; height: 70px; background-color: rgba(0, 97, 122, 0.1);">
                            <i class="fas fa-donate fs-2" style="color: #00617a;"></i>
                        </div>
                        <div>
                            <h2 class="fs-3 fw-bold mb-1" style="color: #00617a;">Kelola Donasi</h2>
                            <p class="text-muted mb-0">Kelola dan tinjau donasi yang masuk.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .mobile-sponsor-card {
                border: 1px solid #e9ecef;
                border-radius: 1rem;
                background-color: #fff;
                overflow: hidden;
            }
            .mobile-sponsor-card .card-body { padding: 0.85rem; }
            .mobile-sponsor-card .card-title { font-weight: 600; }
            .mobile-sponsor-card .action-buttons .btn { border-radius: 0.5rem; }

            @media (max-width: 575.98px) {
                .btn-sm { padding: 0.35rem 0.6rem; }
            }
            
            .toolbar-filters { display: flex; flex-wrap: wrap; gap: .75rem 1rem; align-items: center; }
            .toolbar-group { display: flex; align-items: center; gap: .5rem; }
            .toolbar-group .filter-label { white-space: nowrap; color: #6c757d; font-weight: 600; }
            
            @media (max-width: 575.98px) {
                .toolbar-filters { flex-direction: column; align-items: stretch; }
                .toolbar-group { flex-direction: column; align-items: stretch; }
                .toolbar-group .form-select { width: 100% !important; }
            }
        </style>

        {{-- Sorting & Filtering Form --}}
        <div class="row mb-4">
            <div class="col-12">
                <form method="GET" class="toolbar-filters bg-white p-3 rounded-4 shadow-sm">
                    <div class="toolbar-group">
                        <label for="sort-select" class="filter-label">Urutkan:</label>
                        <select id="sort-select" name="sort" class="form-select form-select-sm border-0 bg-light rounded-pill px-3 py-2"
                            onchange="this.form.submit()" style="width: auto; cursor: pointer;">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                            <option value="amount_high" {{ request('sort') == 'amount_high' ? 'selected' : '' }}>Jumlah Tertinggi</option>
                            <option value="amount_low" {{ request('sort') == 'amount_low' ? 'selected' : '' }}>Jumlah Terendah</option>
                        </select>
                    </div>
                    <div class="toolbar-group ms-md-auto">
                        <label for="status-select" class="filter-label">Status:</label>
                        <select id="status-select" name="status" class="form-select form-select-sm border-0 bg-light rounded-pill px-3 py-2"
                            onchange="this.form.submit()" style="width: auto; cursor: pointer;">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Success</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Canceled</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-0">
                @if (session('success'))
                    <div class="alert alert-success m-3 mb-0 border-0 rounded-3"
                        style="background-color: #e6f7f1; color: #36b37e;">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger m-3 mb-0 border-0 rounded-3"
                        style="background-color: #ffe6e6; color: #dc3545;">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    </div>
                @endif

                @if ($donations->isEmpty())
                    <div class="alert alert-info m-3 border-0 rounded-3" style="background-color: #eaf6fe; color: #337ab7;">
                        <i class="fas fa-info-circle me-2"></i>Tidak ada donasi saat ini.
                    </div>
                @else
                    {{-- Mobile Card View --}}
                    <div class="d-block d-lg-none p-3">
                        @foreach ($donations as $donation)
                            <div class="mobile-sponsor-card shadow-sm mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h3 class="card-title fs-6 mb-1">{{ $donation->donor_name }}</h3>
                                            <div class="text-success fw-bold">Rp {{ number_format($donation->amount, 0, ',', '.') }}</div>
                                            <div class="text-muted small">{{ $donation->email }}</div>
                                            <div class="text-muted small">{{ $donation->created_at->format('d M Y H:i') }}</div>
                                        </div>
                                        <div class="text-end">
                                            @if($donation->proof_image)
                                                <a href="{{ asset('storage/' . $donation->proof_image) }}" 
                                                    target="_blank"
                                                    class="btn btn-sm text-white rounded-pill mb-1" 
                                                    style="background-color: #00617a;" 
                                                    title="Lihat Bukti Transfer">
                                                    <i class="fas fa-receipt me-1"></i>
                                                </a>
                                            @endif
                                            @if($donation->foto_donatur)
                                                <a href="{{ asset('storage/' . $donation->foto_donatur) }}" 
                                                    target="_blank"
                                                    class="btn btn-sm text-white rounded-pill mb-1" 
                                                    style="background-color: #f4b704;" 
                                                    title="Lihat Foto Donatur">
                                                    <i class="fas fa-user me-1"></i>
                                                </a>
                                            @endif
                                            @if ($donation->status == 'pending')
                                                <form action="{{ route('admin.donations.updateStatus', $donation->id) }}" 
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="button" 
                                                        onclick="confirmAction(event, this.parentElement, 'approve')" 
                                                        class="btn btn-sm text-white rounded-pill mb-1" 
                                                        style="background-color: #f4b704;" 
                                                        title="Success">
                                                        <i class="fas fa-check-circle me-1"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.donations.updateStatus', $donation->id) }}" 
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="button" 
                                                        onclick="confirmAction(event, this.parentElement, 'reject')" 
                                                        class="btn btn-sm text-white rounded-pill mb-1" 
                                                        style="background-color: #cb2786;" 
                                                        title="Cancel">
                                                        <i class="fas fa-times-circle me-1"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.donations.destroy', $donation->id) }}" 
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                    onclick="confirmDelete(event, this.parentElement)" 
                                                    class="btn btn-sm btn-danger text-white rounded-pill mb-1" 
                                                    title="Hapus">
                                                    <i class="fas fa-trash me-1"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        @if($donation->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($donation->status == 'approved')
                                            <span class="badge bg-success">Success</span>
                                        @else
                                            <span class="badge bg-danger">Canceled</span>
                                        @endif
                                        @if($donation->message)
                                            <span class="badge bg-secondary ms-1">
                                                <i class="fas fa-comment-dots"></i> Ada Pesan
                                            </span>
                                        @endif
                                    </div>
                                    @if($donation->message)
                                        <div class="mt-2 p-2 bg-light rounded">
                                            <small class="text-muted">{{ Str::limit($donation->message, 100) }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Desktop Table View --}}
                    <div class="d-none d-lg-block table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background-color: #00617a; color: white;">
                                <tr>
                                    <th class="px-4 py-3 rounded-start">No</th>
                                    <th class="px-4 py-3">Nama Donatur</th>
                                    <th class="px-4 py-3">Email</th>
                                    <th class="px-4 py-3">Jumlah</th>
                                    <th class="px-4 py-3">Bukti TF</th>
                                    <th class="px-4 py-3">Foto</th>
                                    <th class="px-4 py-3">Tanggal</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3 rounded-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($donations as $donation)
                                    <tr>
                                        <td class="px-4 py-3">{{ $loop->iteration + ($donations->currentPage() - 1) * $donations->perPage() }}</td>
                                        <td class="px-4 py-3">
                                            <strong>{{ $donation->donor_name }}</strong>
                                            @if($donation->message)
                                                <br><small class="text-muted">
                                                    <i class="fas fa-comment-dots"></i> {{ Str::limit($donation->message, 50) }}
                                                </small>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">{{ $donation->email }}</td>
                                        <td class="px-4 py-3">
                                            <strong class="text-success">Rp {{ number_format($donation->amount, 0, ',', '.') }}</strong>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($donation->proof_image)
                                                <a href="{{ asset('storage/' . $donation->proof_image) }}" 
                                                   target="_blank"
                                                   class="btn btn-sm btn-outline-primary rounded-pill">
                                                    <i class="fas fa-receipt"></i>
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($donation->foto_donatur)
                                                <a href="{{ asset('storage/' . $donation->foto_donatur) }}" 
                                                   target="_blank"
                                                   class="btn btn-sm btn-outline-warning rounded-pill">
                                                    <i class="fas fa-user"></i>
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <small>{{ $donation->created_at->format('d M Y') }}</small><br>
                                            <small class="text-muted">{{ $donation->created_at->format('H:i') }}</small>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($donation->status == 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif($donation->status == 'approved')
                                                <span class="badge bg-success">Success</span>
                                            @else
                                                <span class="badge bg-danger">Canceled</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="d-flex gap-1">
                                                @if ($donation->status == 'pending')
                                                    <form action="{{ route('admin.donations.updateStatus', $donation->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="approved">
                                                        <button type="button" 
                                                            class="btn btn-sm text-white rounded-pill"
                                                            style="background-color: #f4b704; border-color: #f4b704;"
                                                            onclick="confirmAction(event, this.parentElement, 'approve')"
                                                            title="Success">
                                                            <i class="fas fa-check-circle me-1"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.donations.updateStatus', $donation->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="button" 
                                                            class="btn btn-sm text-white rounded-pill"
                                                            style="background-color: #cb2786; border-color: #cb2786;"
                                                            onclick="confirmAction(event, this.parentElement, 'reject')"
                                                            title="Cancel">
                                                            <i class="fas fa-times-circle me-1"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('admin.donations.destroy', $donation->id) }}" 
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" 
                                                        onclick="confirmDelete(event, this.parentElement)" 
                                                        class="btn btn-sm btn-danger text-white rounded-pill" 
                                                        title="Hapus">
                                                        <i class="fas fa-trash me-1"></i>
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
        @if ($donations->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $donations->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
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
                text = "Anda yakin bahwa donasi sudah sukses?";
                icon = 'question';
                confirmButtonText = 'Ya, Setujui!';
                confirmButtonColor = '#00617a';
            } else if (actionType === 'reject') {
                title = 'Konfirmasi Penolakan';
                text = "Anda yakin ingin membatalkan donasi ini?";
                icon = 'warning';
                confirmButtonText = 'Ya, Batalkan!';
                confirmButtonColor = '#cb2786';
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
                    confirmButton: 'rounded-pill px-4',
                    cancelButton: 'rounded-pill px-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        function confirmDelete(event, form) {
            event.preventDefault();
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'rounded-pill px-4',
                    cancelButton: 'rounded-pill px-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
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
                    confirmButton: 'rounded-pill px-4',
                    popup: 'rounded-4'
                }
            });
        @endif
    </script>
@endpush