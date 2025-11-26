@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Detail Pesan</h3>
        <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <div class="text-muted small">Pengirim</div>
                <div class="fw-semibold">{{ $message->name }} <span class="text-muted">&lt;{{ $message->email }}&gt;</span></div>
            </div>
            <div class="mb-3">
                <div class="text-muted small">Subjek</div>
                <div class="fw-semibold">{{ $message->subject ?: '-' }}</div>
            </div>
            <div class="mb-3">
                <div class="text-muted small">Tanggal</div>
                <div>{{ $message->created_at->format('d M Y H:i') }}</div>
            </div>
            <hr>
            <p class="mb-0" style="white-space: pre-wrap;">{{ $message->message }}</p>
        </div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <form action="{{ route('admin.messages.toggle', $message) }}" method="POST">
            @csrf
            @method('PUT')
            <button class="btn btn-primary">
                <i class="fas {{ $message->is_read ? 'fa-envelope' : 'fa-envelope-open' }} me-1"></i>
                {{ $message->is_read ? 'Tandai Belum Dibaca' : 'Tandai Sudah Dibaca' }}
            </button>
        </form>
        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger"><i class="fas fa-trash me-1"></i> Hapus</button>
        </form>
    </div>
@endsection
