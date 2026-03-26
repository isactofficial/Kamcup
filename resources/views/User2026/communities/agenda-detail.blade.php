@extends('layouts.master_nav')

@section('title', 'Detail Agenda - ' . $feed->title)

@section('content')
<div class="agenda-detail-wrapper">
    <!-- Header -->
    <div class="agenda-header" style="background: linear-gradient(135deg, #00617a 0%, #004a5c 100%); color: white;">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <nav aria-label="breadcrumb" class="mb-2">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('user2026.komunitas') }}" class="text-white">Komunitas</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('user2026.komunitas.show', $community->slug) }}" class="text-white">{{ $community->name }}</a></li>
                            <li class="breadcrumb-item active text-white">{{ $feed->title }}</li>
                        </ol>
                    </nav>
                    <h1 class="h2 fw-bold mb-2">{{ $feed->title }}</h1>
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar-day me-2"></i>
                            <span>{{ \Carbon\Carbon::parse($feed->meet_date)->translatedFormat('d F Y, H:i') }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock me-2"></i>
                            <span>{{ $feed->meet_duration ?? 0 }} jam</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    @if($feed->image)
                        <img src="{{ asset('storage/' . $feed->image) }}" class="img-fluid rounded-3" style="max-height: 150px; object-fit: cover;">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light rounded-3" style="width: 150px; height: 150px; margin-left: auto;">
                            <i class="fas fa-calendar-alt text-muted" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-4">
        <div class="row">
            <!-- Left Content -->
            <div class="col-lg-8">
                <!-- Tabs -->
                <ul class="nav nav-tabs mb-4" id="agendaTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">Details</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="participants-tab" data-bs-toggle="tab" data-bs-target="#participants" type="button" role="tab">
                            Participants ({{ $feed->joins_count ?? 0 }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="chat-tab" data-bs-toggle="tab" data-bs-target="#chat" type="button" role="tab">Chat</button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="agendaTabContent">
                    <!-- Details Tab -->
                    <div class="tab-pane fade show active" id="details" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <!-- Basic Info -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h6 class="fw-bold text-primary mb-3">Informasi Dasar</h6>
                                        <div class="mb-3">
                                            <label class="small text-muted d-block">Judul Agenda</label>
                                            <p class="fw-bold mb-0">{{ $feed->title }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="small text-muted d-block">Tanggal & Waktu</label>
                                            <p class="mb-0">{{ \Carbon\Carbon::parse($feed->meet_date)->translatedFormat('l, d F Y, H:i') }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="small text-muted d-block">Durasi</label>
                                            <p class="mb-0">{{ $feed->meet_duration ?? 0 }} jam</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="small text-muted d-block">Lokasi</label>
                                            <p class="mb-0">{{ $feed->meet_location }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="fw-bold text-primary mb-3">Persyaratan</h6>
                                        <div class="mb-3">
                                            <label class="small text-muted d-block">Jumlah Peserta</label>
                                            <p class="mb-0">{{ $feed->joins_count ?? 0 }} / {{ $feed->meet_max_people }} orang</p>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-primary" style="width: {{ min(($feed->joins_count ?? 0) / $feed->meet_max_people * 100, 100) }}%"></div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="small text-muted d-block">Biaya</label>
                                            <p class="mb-0 fw-bold">
                                                @if($feed->meet_fee > 0)
                                                    Rp {{ number_format($feed->meet_fee, 0, ',', '.') }}
                                                @else
                                                    Gratis
                                                @endif
                                            </p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="small text-muted d-block">Gender</label>
                                            <p class="mb-0">
                                                @if($feed->meet_gender == 'all')
                                                    Semua
                                                @elseif($feed->meet_gender == 'male')
                                                    Pria
                                                @else
                                                    Wanita
                                                @endif
                                            </p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="small text-muted d-block">Kategori Usia</label>
                                            <p class="mb-0">
                                                @if($feed->meet_age_category == 'all')
                                                    Semua Usia
                                                @elseif($feed->meet_age_category == 'junior')
                                                    Junior (&lt; 18 tahun)
                                                @elseif($feed->meet_age_category == 'adult')
                                                    Adult (18-55 tahun)
                                                @else
                                                    Senior (&gt; 55 tahun)
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Notes -->
                                @if($feed->content)
                                <div class="mb-4">
                                    <h6 class="fw-bold text-primary mb-3">Catatan</h6>
                                    <div class="bg-light rounded-3 p-3">
                                        <p class="mb-0">{{ $feed->content }}</p>
                                    </div>
                                </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="d-flex gap-3">
                                    @if($isJoined)
                                        <button 
                                            onclick="toggleJoinMeet(this, {{ $feed->id }})" 
                                            class="btn btn-danger rounded-pill px-4"
                                            data-joined="{{ $feed->current_user_joined ? '1' : '0' }}"
                                        >
                                            <i class="fas fa-times me-2"></i>Batal Ikut
                                        </button>
                                    @else
                                        <button 
                                            onclick="toggleJoinMeet(this, {{ $feed->id }})" 
                                            class="btn btn-primary rounded-pill px-4"
                                            data-joined="{{ $feed->current_user_joined ? '1' : '0' }}"
                                        >
                                            <i class="fas fa-plus me-2"></i>Ikut Agenda
                                        </button>
                                    @endif
                                    <button class="btn btn-outline-primary rounded-pill px-4">
                                        <i class="fas fa-calendar-plus me-2"></i>Add to Calendar
                                    </button>
                                    <button class="btn btn-outline-secondary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addNotesModal">
                                        <i class="fas fa-sticky-note me-2"></i>Add Notes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Participants Tab -->
                    <div class="tab-pane fade" id="participants" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-primary mb-3">Peserta ({{ $feed->joins_count ?? 0 }})</h6>
                                @if($feed->joinedBy->count() > 0)
                                    <div class="row">
                                        @foreach($feed->joinedBy as $participant)
                                            <div class="col-md-6 mb-3">
                                                <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" 
                                                         style="width: 40px; height: 40px;">
                                                        {{ strtoupper(substr($participant->name, 0, 1)) }}
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-bold">{{ $participant->name }}</div>
                                                        <div class="small text-muted">
                                                            Bergabung {{ $participant->pivot->joined_at->diffForHumans() }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5 text-muted">
                                        <i class="fas fa-users fa-2x mb-3"></i>
                                        <p>Belum ada peserta yang bergabung.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Chat Tab -->
                    <div class="tab-pane fade" id="chat" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-primary mb-3">Diskusi Agenda</h6>
                                <div class="chat-container" style="height: 400px; overflow-y: auto;">
                                    @if($feed->comments->count() > 0)
                                        @foreach($feed->comments as $comment)
                                            <div class="mb-3">
                                                <div class="d-flex align-items-start">
                                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 32px; height: 32px; font-size: 12px;">
                                                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="bg-light rounded-3 p-3">
                                                            <div class="fw-bold small">{{ $comment->user->name }}</div>
                                                            <div class="small">{{ $comment->content }}</div>
                                                        </div>
                                                        <div class="small text-muted ms-3">{{ $comment->created_at->diffForHumans() }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center py-5 text-muted">
                                            <i class="fas fa-comments fa-2x mb-3"></i>
                                            <p>Belum ada diskusi. Mulai percakapan!</p>
                                        </div>
                                    @endif
                                </div>
                                @if($isJoined)
                                <div class="mt-3">
                                    <form class="d-flex gap-2">
                                        <input type="text" class="form-control rounded-pill" placeholder="Tulis pesan...">
                                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </form>
                                </div>
                                @else
                                <div class="mt-3 text-center text-muted">
                                    <small>Ikut agenda untuk bisa berpartisipasi dalam diskusi</small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="col-lg-4">
                <!-- Community Info -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-primary mb-3">Komunitas</h6>
                        <div class="d-flex align-items-center mb-3">
                            @if($community->image)
                                <img src="{{ asset('storage/' . $community->image) }}" class="rounded-3 me-3" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="rounded-3 bg-light d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-users text-muted"></i>
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold">{{ $community->name }}</div>
                                <div class="small text-muted">{{ $community->category }}</div>
                            </div>
                        </div>
                        <a href="{{ route('user2026.komunitas.show', $community->slug) }}" class="btn btn-outline-primary btn-sm rounded-pill w-100">
                            Lihat Komunitas
                        </a>
                    </div>
                </div>

                <!-- Organizer Info -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-primary mb-3">Penyelenggara</h6>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3" 
                                 style="width: 40px; height: 40px;">
                                {{ strtoupper(substr($feed->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold">{{ $feed->user->name }}</div>
                                <div class="small text-muted">Admin Komunitas</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Share -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-primary mb-3">Bagikan</h6>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm rounded-circle flex-fill">
                                <i class="fas fa-share"></i>
                            </button>
                            <button class="btn btn-outline-success btn-sm rounded-circle flex-fill">
                                <i class="fab fa-whatsapp"></i>
                            </button>
                            <button class="btn btn-outline-info btn-sm rounded-circle flex-fill">
                                <i class="fab fa-twitter"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm rounded-circle flex-fill">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Notes Modal -->
<div class="modal fade" id="addNotesModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Tambah Catatan Pribadi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <textarea class="form-control rounded-3" rows="4" placeholder="Catatan pribadi tentang agenda ini..."></textarea>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary rounded-pill px-4">Simpan</button>
            </div>
        </div>
    </div>
</div>

<style>
.agenda-header {
    border-bottom: 4px solid #cb2786;
}

.nav-tabs .nav-link {
    color: #6c757d;
    border: none;
    border-bottom: 3px solid transparent;
    background: none;
    font-weight: 600;
}

.nav-tabs .nav-link:hover {
    color: #00617a;
    border-bottom-color: rgba(0, 97, 122, 0.3);
}

.nav-tabs .nav-link.active {
    color: #00617a;
    background: none;
    border-bottom-color: #00617a;
}

.chat-container::-webkit-scrollbar {
    width: 6px;
}

.chat-container::-webkit-scrollbar-thumb {
    background-color: rgba(0,0,0,0.1);
    border-radius: 10px;
}
</style>

<script>
function toggleJoinMeet(button, feedId) {
    const isJoined = button.dataset.joined === '1';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/feeds/${feedId}/join`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update button state
            button.dataset.joined = isJoined ? '0' : '1';
            
            if (isJoined) {
                button.innerHTML = '<i class="fas fa-plus me-2"></i>Ikut Agenda';
                button.className = 'btn btn-primary rounded-pill px-4';
            } else {
                button.innerHTML = '<i class="fas fa-times me-2"></i>Batal Ikut';
                button.className = 'btn btn-danger rounded-pill px-4';
            }
            
            // Refresh page to update participant count
            setTimeout(() => location.reload(), 1000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
    });
}
</script>
@endsection
