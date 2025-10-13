@extends('layouts.admin')

@section('content')
<style>
/* --- Styling untuk Mobile Card View --- */
.mobile-match-card {
    border: 1px solid #e9ecef;
    border-radius: 1rem;
    background-color: #fff;
    margin-bottom: 1rem;
}

.mobile-match-card .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    font-size: 0.8rem;
    padding: 0.75rem 1rem;
}


.mobile-match-card .match-layout {
    text-align: center; /* Skor akan menjadi center */
}

.mobile-match-card .teams-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
}

.mobile-match-card .team-name {
    font-weight: 600;
    font-size: 1rem; /* Ukuran font dikembalikan agar lebih jelas */
    color: #343a40;
    flex: 1;
    word-break: break-word;
}

.mobile-match-card .vs-separator {
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: bold;
}

.mobile-match-card .match-score {
    font-size: 1.5rem;
    font-weight: bold;
    color: #00617a;
    margin-top: 0.5rem; /* Memberi jarak dari baris nama tim */
}


.mobile-match-card .match-details {
    font-size: 0.85rem;
}

.mobile-match-card .match-details span {
    display: block;
    margin-bottom: 0.3rem;
}

.mobile-match-card .action-buttons .btn {
    border-radius: 0.5rem;
    font-size: 1rem;
}

.winner-trophy {
    color: #f4b704; /* Gold color for the trophy */
}
</style>

<div class="container-fluid px-4" style="min-height: 100vh;">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid #00617a;">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                         style="width: 70px; height: 70px; background-color: rgba(0, 97, 122, 0.1);">
                        <i class="fas fa-volleyball-ball fs-2" style="color: #00617a;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: #00617a;">Kelola Pertandingan</h2>
                        <p class="text-muted mb-0">Lihat dan atur daftar pertandingan yang terdaftar.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Button --}}
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-end">
            <a href="{{ route('admin.matches.create') }}"
               class="btn text-white d-flex align-items-center px-4 py-2 rounded-pill"
               style="background-color: #f4b704; border: none; font-weight: 600;">
                <i class="fas fa-plus me-2"></i>
                <span class="fw-small">Tambah Pertandingan Baru</span>
            </a>
        </div>
    </div>

    {{-- Content Card --}}
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-3 p-md-4">
            @if (session('success'))
                <div class="alert rounded-3 text-white m-0 mb-4" style="background-color: #00617a; border: none;">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            <h1 class="fs-5 fw-semibold mb-4" style="color: #00617a;">Daftar Pertandingan</h1>

            {{-- Mobile View --}}
            <div class="d-block d-lg-none">
                @forelse($matches as $match)
                    <div class="mobile-match-card shadow-sm">
                        <div class="card-header d-flex justify-content-between text-muted small p-3">
                            <span>{{ $match->tournament->title ?? 'N/A' }}</span>
                            <span>{{ \Carbon\Carbon::parse($match->match_datetime)->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="card-body p-3">

                            <div class="match-layout mb-3">
                                <div class="teams-container">
                                    <div class="team-name text-start">{{ $match->team1->name ?? '-' }}</div>
                                    <div class="vs-separator">VS</div>
                                    <div class="team-name text-end">{{ $match->team2->name ?? '-' }}</div>
                                </div>
                                <div class="match-score" id="mobile-score-match-{{ $match->id }}">
                                    @if ($match->status == 'completed' || $match->status == 'in-progress')
                                        {{ $match->team1_score ?? 0 }} - {{ $match->team2_score ?? 0 }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>

                            <hr class="my-3">
                            <div class="match-details d-flex justify-content-between align-items-start mb-3">
                                <div class="text-muted small">
                                    <span><i class="fas fa-map-marker-alt me-2"></i>{{ $match->location }}</span>
                                    <div id="mobile-winner-match-{{ $match->id }}">
                                        @if ($match->status == 'completed' && $match->winner)
                                            <span class="fw-bold"><i class="fas fa-trophy me-2 winner-trophy"></i>{{ $match->winner->name }}</span>
                                        @elseif ($match->status == 'completed' && !$match->winner)
                                            <span class="fw-bold"><i class="fas fa-equals me-2"></i>Draw</span>
                                        @endif
                                    </div>
                                </div>
                                @php
                                    $statusClass = '';
                                    $statusText = ucfirst(str_replace('-', ' ', $match->status));
                                    switch ($match->status) {
                                        case 'scheduled': $statusClass = 'badge bg-secondary'; break;
                                        case 'in-progress': $statusClass = 'badge bg-primary'; break;
                                        case 'completed': $statusClass = 'badge bg-success'; break;
                                        case 'cancelled': $statusClass = 'badge bg-danger'; break;
                                    }
                                @endphp
                                <span class="{{ $statusClass }}">{{ $statusText }}</span>
                            </div>
                            <div class="action-buttons d-flex gap-2">
                                <a href="{{ route('admin.matches.edit', $match->id) }}" class="btn btn-light border flex-fill" style="color: #f4b704;" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.matches.destroy', $match->id) }}" method="POST" class="d-flex flex-fill">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-light border w-100" style="color: #cb2786;" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">Belum ada pertandingan tercatat.</div>
                @endforelse
            </div>

            {{-- Desktop View --}}
            <div class="table-responsive d-none d-lg-block">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr style="background-color: #f8f9fa;">
                            <th class="py-3">Turnamen</th>
                            <th class="py-3">Waktu</th>
                            <th class="py-3">Tim 1</th>
                            <th class="py-3">Skor</th>
                            <th class="py-3">Tim 2</th>
                            <th class="py-3">Lokasi</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Pemenang</th>
                            <th class="py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($matches as $match)
                            <tr data-match-id="{{ $match->id }}">
                                <td>{{ $match->tournament->title ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($match->match_datetime)->format('d M Y H:i') }}</td>
                                <td>{{ $match->team1->name ?? '-' }}</td>
                                <td id="score-match-{{ $match->id }}">
                                    @if ($match->status == 'completed' || $match->status == 'in-progress')
                                        <strong id="score-team1-{{ $match->id }}">{{ $match->team1_score ?? 0 }}</strong> -
                                        <strong id="score-team2-{{ $match->id }}">{{ $match->team2_score ?? 0 }}</strong>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $match->team2->name ?? '-' }}</td>
                                <td>{{ $match->location }}</td>
                                <td>
                                    @php
                                        $statusClass = '';
                                        $statusText = ucfirst(str_replace('-', ' ', $match->status));
                                        switch ($match->status) {
                                            case 'scheduled': $statusClass = 'badge bg-secondary'; break;
                                            case 'in-progress': $statusClass = 'badge bg-primary'; break;
                                            case 'completed': $statusClass = 'badge bg-success'; break;
                                            case 'cancelled': $statusClass = 'badge bg-danger'; break;
                                        }
                                    @endphp
                                    <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                                <td id="winner-match-{{ $match->id }}">
                                    @if ($match->status == 'completed' && $match->winner)
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-trophy text-warning me-2"></i>
                                            <span class="fw-bold text-success">{{ $match->winner->name }}</span>
                                        </div>
                                    @elseif ($match->status == 'completed' && !$match->winner)
                                        <span class="badge bg-info">Draw</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.matches.edit', $match->id) }}"
                                           class="btn btn-sm px-2 py-1 rounded-pill"
                                           style="background-color: #f4b704; color: #212529;" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.matches.destroy', $match->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(event, this.parentElement)"
                                                    class="btn btn-sm px-2 py-1 rounded-pill"
                                                    style="background-color: #cb2786; color: white;" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">Belum ada pertandingan tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Refresh Button --}}
    <div class="row mt-4">
        <div class="col-12 text-end">
            <button onclick="refreshScores()" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                <i class="fas fa-sync-alt me-1"></i>Refresh Skor
            </button>
        </div>
    </div>
</div>

{{-- SweetAlert & Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function confirmDelete(event, form) {
        event.preventDefault();

        Swal.fire({
            title: "Konfirmasi Hapus?",
            text: "Yakin ingin menghapus pertandingan ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#cb2786",
            cancelButtonColor: "#00617a",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal",
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

    // Function untuk refresh skor individual match
    function updateMatchScore(matchId) {
        $.ajax({
            url: `/admin/matches/${matchId}/score`,
            type: 'GET',
            success: function(data) {
                // Update skor (desktop + mobile)
                $(`#score-team1-${matchId}`).text(data.team1_score || 0);
                $(`#score-team2-${matchId}`).text(data.team2_score || 0);
                if (data.status === 'completed' || data.status === 'in-progress') {
                    $(`#mobile-score-match-${matchId}`).html(`${data.team1_score || 0} - ${data.team2_score || 0}`);
                } else {
                    $(`#mobile-score-match-${matchId}`).html(`-`);
                }

                // Update winner (desktop + mobile)
                if (data.status === 'completed') {
                    let winnerHtml = '';
                    if (data.winner) {
                        winnerHtml = `
                            <div class="d-flex align-items-center">
                                <i class="fas fa-trophy text-warning me-2"></i>
                                <span class="fw-bold text-success">${data.winner}</span>
                            </div>`;
                        $(`#mobile-winner-match-${matchId}`).html(`<span class="fw-bold"><i class="fas fa-trophy me-2 winner-trophy"></i>${data.winner}</span>`);
                    } else {
                        winnerHtml = `<span class="badge bg-info">Draw</span>`;
                        $(`#mobile-winner-match-${matchId}`).html(`<span class="fw-bold"><i class="fas fa-equals me-2"></i>Draw</span>`);
                    }
                    $(`#winner-match-${matchId}`).html(winnerHtml);
                }
            },
            error: function(xhr, status, error) {
                console.error(`Error updating score for match ${matchId}:`, error);
            }
        });
    }

    // Function untuk refresh semua skor
    function refreshScores() {
        const matchRows = $('tbody tr[data-match-id]');

        if (matchRows.length === 0) {
            location.reload();
            return;
        }

        matchRows.each(function() {
            const matchId = $(this).data('match-id');
            if (matchId) {
                updateMatchScore(matchId);
            }
        });

        Swal.fire({
            icon: 'success',
            title: 'Skor berhasil direfresh!',
            showConfirmButton: false,
            timer: 1500,
            toast: true,
            position: 'top-end'
        });
    }

    // Auto-refresh setiap 30 detik
    setInterval(function() {
        $('tbody tr').each(function() {
            const statusBadge = $(this).find('.badge.bg-primary'); // in-progress
            if (statusBadge.length > 0) {
                const matchId = $(this).data('match-id');
                if (matchId) {
                    updateMatchScore(matchId);
                }
            }
        });
    }, 30000); // 30 detik

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
</script>
@endsection
