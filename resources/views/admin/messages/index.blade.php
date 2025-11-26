@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Manajemen Kontak Masuk</h3>
        <div>
            <form class="d-inline">
                <select name="filter" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="all" {{ $filter==='all' ? 'selected' : '' }}>Semua</option>
                    <option value="unread" {{ $filter==='unread' ? 'selected' : '' }}>Belum Dibaca</option>
                    <option value="read" {{ $filter==='read' ? 'selected' : '' }}>Sudah Dibaca</option>
                </select>
            </form>
        </div>
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
                            <div class="d-flex justify-content-end gap-1 flex-wrap">
                                <a href="{{ route('admin.messages.show', $msg) }}" class="btn btn-sm btn-outline-secondary" title="Baca">
                                    <i class="fas fa-envelope-open-text"></i>
                                </a>
                                <form action="{{ route('admin.messages.toggle', $msg) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-sm btn-outline-primary" title="Tandai">
                                        <i class="fas {{ $msg->is_read ? 'fa-envelope' : 'fa-envelope-open' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.messages.destroy', $msg) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus">
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
