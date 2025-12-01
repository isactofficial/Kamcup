@extends('layouts.admin')

@section('content')
    <style>
    .btn-action { width: 36px; height: 36px; display:inline-flex; align-items:center; justify-content:center; border-radius: 12px; border:1px solid #e6e9ec; background:#fff; box-shadow: 0 2px 6px rgba(0,0,0,.06); transition: all .2s ease; }
    .btn-action i { font-size: 14px; }
    .btn-action:hover { transform: translateY(-1px); box-shadow: 0 4px 10px rgba(0,0,0,.08); }
    .btn-action.view { color:#0d6efd; border-color:#cfe2ff; background:#eaf2ff; }
    .btn-action.view:hover { background:#dbe9ff; }
    .btn-action.toggle { color:#0aa06a; border-color:#cfe9de; background:#e8f7f0; }
    .btn-action.toggle:hover { background:#daf1e8; }
    .btn-action.delete { color:#dc3545; border-color:#f3c9ce; background:#fdecef; }
    .btn-action.delete:hover { background:#f9dfe3; }

    .btn-slim { padding:.35rem .8rem; border-radius: 999px; font-weight:600; }
    </style>
    <div class="bg-white rounded-4 shadow-sm p-3 p-md-4 mb-3" style="border-left: 8px solid #00617a;">
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex justify-content-center align-items-center rounded-circle" style="width: 50px; height: 50px; background-color: rgba(0, 97, 122, 0.1);">
                <i class="fas fa-address-book fs-4" style="color: #00617a;"></i>
            </div>
            <div>
                <h2 class="fs-4 fs-md-3 fw-bold mb-1" style="color: #00617a;">Manajemen Kontak</h2>
                <p class="text-muted mb-0 d-none d-md-block">Kelola pesan yang masuk dari formulir kontak.</p>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <form class="d-inline">
            <select name="filter" class="form-select form-select-sm btn-slim" onchange="this.form.submit()" style="background:#e8f6fb; color:#00617a; border-color:#c7e6f1; box-shadow: 0 4px 10px rgba(0,0,0,.05); width:auto;">
                <option value="all" {{ $filter==='all' ? 'selected' : '' }}>Semua</option>
                <option value="unread" {{ $filter==='unread' ? 'selected' : '' }}>Belum Dibaca</option>
                <option value="read" {{ $filter==='read' ? 'selected' : '' }}>Sudah Dibaca</option>
            </select>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>PENGIRIM</th>
                    <th>SUBJEK</th>
                    <th>PESAN</th>
                    <th>TANGGAL</th>
                    <th class="text-end">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $i => $msg)
                    <tr class="{{ $msg->is_read ? '' : 'table-warning' }}">
                        <td>{{ $messages->firstItem() + $i }}</td>
                        <td>
                            <div class="fw-semibold">{{ $msg->name }}</div>
                            <div class="text-muted small">{{ $msg->email }}</div>
                            @if(!$msg->is_read)
                                <span class="badge bg-warning text-dark mt-1">Belum Dibaca</span>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $msg->subject ?: '-' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($msg->message, 40) }}</td>
                        <td>
                            {{ $msg->created_at->format('d M Y') }}<br>
                            <small class="text-muted">{{ $msg->created_at->format('H:i') }}</small>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2 flex-wrap">
                                <form action="{{ route('admin.messages.toggle', $msg) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn-action toggle" title="{{ $msg->is_read ? 'Tandai Belum Dibaca' : 'Tandai Sudah Dibaca' }}">
                                        <i class="fas {{ $msg->is_read ? 'fa-envelope' : 'fa-envelope-open' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.messages.destroy', $msg) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-action delete" title="Hapus" onclick="confirmDeleteMessage(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada pesan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $messages->links() }}
    </div>
@endsection

@push('scripts')
<script>
function confirmDeleteMessage(btn){
    const form = btn.closest('form');
    // If SweetAlert2 is available, use it; else fallback
    if (typeof Swal !== 'undefined' && Swal.fire) {
        Swal.fire({
            title: 'Konfirmasi Hapus?',
            html: '<p style="margin:0;color:#6c757d;">Anda yakin ingin menghapus pesan ini? Data tidak bisa dikembalikan!</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batalkan',
            customClass: {
                popup: 'rounded-4',
                confirmButton: 'rounded-pill px-4 py-2',
                cancelButton: 'rounded-pill px-4 py-2'
            },
            buttonsStyling: false,
            // Button colors to match design
            didOpen: () => {
                const confirmBtn = document.querySelector('.swal2-confirm');
                const cancelBtn = document.querySelector('.swal2-cancel');
                if (confirmBtn) {
                    confirmBtn.style.backgroundColor = '#cb2786';
                    confirmBtn.style.color = '#ffffff';
                    confirmBtn.style.border = 'none';
                    confirmBtn.style.marginRight = '10px';
                    confirmBtn.style.borderRadius = '999px';
                }
                if (cancelBtn) {
                    cancelBtn.style.backgroundColor = '#00617a';
                    cancelBtn.style.color = '#ffffff';
                    cancelBtn.style.border = 'none';
                    cancelBtn.style.borderRadius = '999px';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    } else {
        if (confirm('Anda yakin ingin menghapus pesan ini?')) form.submit();
    }
}
</script>
@endpush
