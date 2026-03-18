@extends('layouts.admin')

@section('title', 'Laporan Komunitas')

@section('content')
<style>
.chat-snapshot-bubble {
    background-color: #f8f9fa;
    border-radius: 12px;
    padding: 10px 15px;
    margin-bottom: 8px;
    border-left: 4px solid #cb2786;
}
.report-card {
    transition: all 0.3s ease;
    border-radius: 1rem;
}
.report-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
}
</style>

<div class="container-fluid px-4 py-4">
    <div class="bg-white rounded-4 shadow-sm p-4 mb-4" style="border-left: 8px solid #dc3545;">
        <div class="d-flex align-items-center">
            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: rgba(220, 53, 69, 0.1);">
                <i class="fas fa-exclamation-triangle text-danger fs-4"></i>
            </div>
            <div>
                <h2 class="fs-4 fw-bold mb-1">Moderasi Laporan Komunitas</h2>
                <p class="text-muted mb-0">Tinjau laporan masuk dan bukti chat untuk menjaga keamanan ekosistem.</p>
            </div>
        </div>
    </div>

    @forelse($reports as $report)
        <div class="card border-0 shadow-sm mb-4 report-card">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-4 border-end">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2 small me-2">Laporan #{{ $report->id }}</span>
                            <span class="text-muted small">{{ $report->created_at->diffForHumans() }}</span>
                        </div>
                        <h5 class="fw-bold mb-1">{{ $report->community->name }}</h5>
                        <p class="text-muted small mb-3">Dilaporkan oleh: <strong>{{ $report->user->name }}</strong></p>
                        
                        <div class="bg-light p-3 rounded-3 mb-3">
                            <label class="small fw-bold text-muted d-block mb-1">Alasan Laporan:</label>
                            <span class="text-dark">{{ $report->reason }}</span>
                        </div>

                        <div class="d-flex gap-2">
                             <form action="{{ route('admin.userpages.komunitas.destroy', $report->community->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Hapus komunitas ini secara permanen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100 rounded-pill py-2 small">
                                    <i class="fas fa-trash me-1"></i> Banned Klub
                                </button>
                            </form>
                            <form action="{{ route('admin.userpages.reports.dismiss', $report->id) }}" method="POST" class="flex-grow-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-secondary w-100 rounded-pill py-2 small">
                                    Abaikan
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-8 ps-md-4 mt-3 mt-md-0">
                        <h6 class="fw-bold mb-3 small text-muted"><i class="fas fa-history me-1"></i> Snapshot 5 Chat Terakhir:</h6>
                        <div class="chat-snapshot-container">
                            @if(is_array($report->chats_snapshot) && count($report->chats_snapshot) > 0)
                                @foreach($report->chats_snapshot as $chat)
                                    <div class="chat-snapshot-bubble">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="fw-bold text-primary small">{{ $chat['user'] }}</span>
                                            <span class="text-muted" style="font-size: 0.65rem;">{{ $chat['time'] }}</span>
                                        </div>
                                        <div class="text-dark small">{{ $chat['message'] }}</div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4 bg-light rounded-3 text-muted small">
                                    <i class="fas fa-comment-slash d-block mb-2 fs-4"></i>
                                    Tidak ada riwayat chat saat pelaporan dilakukan.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-4 shadow-sm p-5 text-center">
            <div class="mb-3">
                <i class="fas fa-check-circle text-success fs-1 animate__animated animate__bounceIn"></i>
            </div>
            <h4 class="fw-bold text-dark">Beres Bos!</h4>
            <p class="text-muted mb-0">Belum ada laporan masuk dari user. Semua komunitas terlihat aman.</p>
        </div>
    @endforelse
</div>
@endsection
