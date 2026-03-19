@extends('layouts.master_nav')

@section('title', 'Detail Komunitas - ' . $community->name)

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="position-relative" style="height: 250px; background-color: #f8f9fa;">
                    @if($community->image)
                        <img src="{{ asset('storage/' . $community->image) }}" class="w-100 h-100" style="object-fit: contain;">
                    @else
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-users text-muted opacity-25" style="font-size: 5rem;"></i>
                        </div>
                    @endif
                    <div class="position-absolute bottom-0 start-0 w-100 p-4" style="background: linear-gradient(transparent, rgba(0,0,0,0.78));">
                        <span class="badge bg-primary mb-2 shadow-sm px-3 py-2" style="border-radius: 8px;">{{ $community->category }}</span>
                        <div class="d-flex align-items-center gap-2">
                            <h1 class="text-white fw-bold mb-0" style="text-shadow: 0 2px 4px rgba(0,0,0,0.3);">{{ $community->name }}</h1>
                            @if($isCommunityAdmin)
                                <div class="dropdown ms-2 mt-1">
                                    <button class="btn btn-sm btn-light rounded-circle shadow-sm position-relative p-0" style="width: 32px; height: 32px;" data-bs-toggle="dropdown">
                                        <i class="fas fa-bell small text-primary"></i>
                                        @if($pendingMembers->count() > 0)
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; padding: 0.25em 0.4em;">
                                                {{ $pendingMembers->count() }}
                                            </span>
                                        @endif
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-0 overflow-hidden" style="width: 300px;">
                                        <div class="p-3 bg-light border-bottom">
                                            <h6 class="fw-bold mb-0"><i class="fas fa-bullhorn me-2 text-primary"></i> Notifikasi Klub</h6>
                                        </div>
                                        <div class="max-vh-50 overflow-auto">
                                            @if($pendingMembers->count() > 0)
                                                <div class="p-2 bg-warning-subtle text-warning-emphasis small border-bottom fw-bold px-3">
                                                    Permintaan Bergabung ({{ $pendingMembers->count() }})
                                                </div>
                                                @foreach($pendingMembers as $pending)
                                                    <div class="p-3 border-bottom d-flex align-items-center gap-3">
                                                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">
                                                            <i class="fas fa-user-plus"></i>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <div class="fw-bold text-dark small text-truncate">{{ $pending->name }}</div>
                                                            <div class="d-flex gap-2 mt-1">
                                                                <form action="{{ route('user2026.komunitas.member.approve', [$community->slug, $pending->id]) }}" method="POST" class="w-50">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-sm btn-primary w-100 py-1" style="font-size: 0.7rem;">Approve</button>
                                                                </form>
                                                                <form action="{{ route('user2026.komunitas.member.remove', [$community->slug, $pending->id]) }}" method="POST" class="w-50">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100 py-1" style="font-size: 0.7rem;">X</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                            
                                            {{-- Placeholder for likes/comments --}}
                                            <div class="p-4 text-center text-muted small opacity-50">
                                                <i class="fas fa-info-circle mb-2 d-block fs-4"></i>
                                                Belum ada notifikasi like atau komentar baru untuk saat ini.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Interaction Tabs -->
                <div class="px-4 border-bottom bg-white">
                    <ul class="nav nav-pills custom-nav-pills gap-3 py-3" id="communityTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button" role="tab"><i class="fas fa-info-circle me-1"></i> About</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="posts-tab" data-bs-toggle="tab" data-bs-target="#posts" type="button" role="tab"><i class="fas fa-rss me-1"></i> Posts</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="chat-tab" data-bs-toggle="tab" data-bs-target="#chat" type="button" role="tab"><i class="fas fa-comments me-1"></i> Chat</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="members-tab" data-bs-toggle="tab" data-bs-target="#members" type="button" role="tab"><i class="fas fa-users me-1"></i> Members</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="activities-tab" data-bs-toggle="tab" data-bs-target="#activities" type="button" role="tab"><i class="fas fa-calendar-alt me-1"></i> Activities</button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4 tab-content" id="communityTabContent">
                    <!-- About Tab -->
                    <div class="tab-pane fade show active" id="about" role="tabpanel">
                        <div class="me-auto text-muted small mb-4">
                            Created by <span class="fw-bold text-dark">{{ $community->creator->name }}</span> 
                            &bull; {{ $community->created_at->format('d M Y') }}
                        </div>
                        <h5 class="fw-bold mb-3" style="color: #00617a;">Tentang Komunitas</h5>
                        <p class="text-secondary mb-0" style="line-height: 1.8; font-size: 1.05rem;">
                            {{ $community->description ?? 'Belum ada deskripsi untuk komunitas ini.' }}
                        </p>
                    </div>

                    <!-- Posts Tab -->
                    <div class="tab-pane fade" id="posts" role="tabpanel">
                        @if($isCommunityAdmin)
                            <div class="alert bg-light border-0 d-flex justify-content-between align-items-center mb-4 p-3 rounded-4">
                                <span class="fw-semibold text-dark">Buat postingan baru untuk klub?</span>
                                <button class="btn btn-sm btn-primary px-3 py-2 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#createPostModal">
                                    <i class="fas fa-plus me-1"></i> Post
                                </button>
                            </div>
                        @endif

                        <div class="posts-list d-flex flex-column gap-4">
                            @php
                                $posts = $community->feeds->whereNull('meet_date')->sortByDesc('created_at');
                            @endphp
                            @forelse($posts as $post)
                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                    @if($post->image)
                                        <div style="max-height: 400px; overflow: hidden;">
                                            <img src="{{ asset('storage/' . $post->image) }}" class="w-100" style="object-fit: cover;">
                                        </div>
                                    @endif
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar-sm me-2" style="width: 35px; height: 35px; border-radius: 50%; background-color: #cb278620; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-user-circle text-muted"></i>
                                            </div>
                                            <div class="small">
                                                <div class="fw-bold">{{ $post->user->name ?? 'Admin' }}</div>
                                                <div class="text-muted" style="font-size: 0.75rem;">{{ $post->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                        @if($post->title)
                                            <h5 class="fw-bold mb-2">{{ $post->title }}</h5>
                                        @endif
                                        <p class="text-dark mb-0" style="line-height: 1.6; white-space: pre-line;">{{ $post->content }}</p>
                                    </div>
                                    <div class="card-footer bg-white border-top-0 px-4 pb-4">
                                        <div class="d-flex gap-4">
                                            <button class="btn btn-link text-decoration-none p-0 text-muted small"><i class="far fa-heart me-1"></i> 0 Likes</button>
                                            <button class="btn btn-link text-decoration-none p-0 text-muted small"><i class="far fa-comment me-1"></i> 0 Comments</button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5 text-muted opacity-50">
                                    <i class="fas fa-rss fs-1 mb-3"></i>
                                    <p>Belum ada postingan terbaru dari {{ $community->name }}.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Chat Tab -->
                    <div class="tab-pane fade" id="chat" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-header bg-white border-bottom-0 p-3 d-flex align-items-center justify-content-between">
                                <h6 class="fw-bold mb-0 text-primary"><i class="fas fa-comments me-2"></i> Group Chat {{ $community->name }}</h6>
                                <span class="badge bg-success-subtle text-success small">Realtime</span>
                            </div>
                            <div class="card-body p-0">
                                <div id="chat-box" class="chat-container p-4 overflow-auto bg-light d-flex flex-column gap-3" style="height: 450px;">
                                    <div class="text-center py-5 text-muted small opacity-50" id="chat-status">
                                        <div class="spinner-border spinner-border-sm mb-2" role="status"></div>
                                        <p>Menghubungkan ke obrolan...</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0 p-3">
                                @if($isJoined)
                                    <form id="chat-form" class="d-flex gap-2">
                                        <input type="text" id="chat-input" class="form-control rounded-pill border-light bg-light px-4 shadow-none" placeholder="Tulis pesan kamu..." autocomplete="off">
                                        <button type="submit" class="btn btn-primary rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; flex-shrink: 0;">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </form>
                                @else
                                    <div class="alert alert-info border-0 small mb-0 rounded-pill text-center py-2">
                                        <i class="fas fa-info-circle me-1"></i> Kamu harus bergabung untuk ikut dalam obrolan.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Members Tab -->
                    <div class="tab-pane fade" id="members" role="tabpanel">
                        <h5 class="fw-bold mb-4" style="color: #00617a;">Daftar Anggota ({{ $community->members->count() }})</h5>
                        <div class="row g-3">
                            @foreach($community->members as $member)
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light rounded-4 p-2 shadow-sm">
                                        <div class="card-body p-2 d-flex align-items-center">
                                            <div class="avatar-sm me-3" style="width: 45px; height: 45px; border-radius: 12px; background-color: #cb278620; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-user-circle text-muted" style="font-size: 1.5rem;"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold text-dark small">{{ $member->name }}</div>
                                                <div class="text-muted small" style="font-size: 0.7rem;">
                                                    <span class="badge {{ $member->pivot->role == 'admin' ? 'bg-primary' : 'bg-secondary opacity-50' }} py-1 px-2">
                                                        {{ strtoupper($member->pivot->role) }}
                                                    </span>
                                                    @if($member->id === $community->user_id)
                                                        <span class="ms-1 text-primary italic">(Creator)</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                @if($isCommunityAdmin && $member->id !== $community->user_id && $member->id !== Auth::id())
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-light rounded-circle shadow-sm" data-bs-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-v text-muted"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                                                            @if($member->pivot->role === 'member')
                                                                <li>
                                                                    <form action="{{ route('user2026.komunitas.member.role', [$community->slug, $member->id]) }}" method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="role" value="admin">
                                                                        <button type="submit" class="dropdown-item small"><i class="fas fa-user-shield me-2"></i> Jadikan Admin</button>
                                                                    </form>
                                                                </li>
                                                            @else
                                                                <li>
                                                                    <form action="{{ route('user2026.komunitas.member.role', [$community->slug, $member->id]) }}" method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="role" value="member">
                                                                        <button type="submit" class="dropdown-item small"><i class="fas fa-user me-2"></i> Jadikan Member</button>
                                                                    </form>
                                                                </li>
                                                            @endif
                                                            <li><hr class="dropdown-divider opacity-50"></li>
                                                            <li>
                                                                <form action="{{ route('user2026.komunitas.member.remove', [$community->slug, $member->id]) }}" method="POST" onsubmit="return confirm('Keluarkan anggota ini?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item small text-danger"><i class="fas fa-user-times me-2"></i> Keluarkan</button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @else
                                                    <button class="btn btn-sm btn-light rounded-circle shadow-sm">
                                                        <i class="fas fa-comment text-muted"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Activities Tab -->
                    <div class="tab-pane fade" id="activities" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0" style="color: #cb2786;">Jadwal Pertemuan & Latihan</h5>
                            @if($isCommunityAdmin)
                                <button class="btn btn-sm btn-outline-primary px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#createAgendaModal">
                                    <i class="fas fa-plus me-1"></i> Buat Agenda
                                </button>
                            @endif
                        </div>
                        
                        <div class="row g-4">
                            @php
                                $agendas = $community->feeds->filter(function($f) {
                                    return !is_null($f->meet_date);
                                })->sortBy('meet_date');
                            @endphp

                            @forelse($agendas as $agenda)
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                                        @if($agenda->image)
                                            <img src="{{ asset('storage/' . $agenda->image) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                        @endif
                                        <div class="card-body p-4">
                                            <div class="badge bg-primary-subtle text-primary mb-2 rounded-pill px-3">Agenda</div>
                                            <h6 class="fw-bold mb-3">{{ $agenda->title }}</h6>
                                            
                                            <div class="d-flex flex-column gap-2 mb-4">
                                                <div class="small text-muted d-flex align-items-center">
                                                    <i class="fas fa-calendar-day me-2 text-primary"></i>
                                                    {{ \Carbon\Carbon::parse($agenda->meet_date)->translatedFormat('d F Y, H:i') }}
                                                </div>
                                                <div class="small text-muted d-flex align-items-center">
                                                    <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                                    {{ $agenda->meet_location }}
                                                </div>
                                                <div class="small text-muted d-flex align-items-center">
                                                    <i class="fas fa-users me-2 text-primary"></i>
                                                    {{ $agenda->joins_count ?? 0 }} / {{ $agenda->meet_max_people }} Peserta
                                                </div>
                                            </div>

                                            <div class="d-grid mt-auto pt-3">
                                                <button 
                                                    onclick="toggleJoinMeet(this, {{ $agenda->id }})" 
                                                    class="btn rounded-pill btn-sm fw-bold {{ $agenda->current_user_joined ? 'btn-danger' : 'btn-primary' }}"
                                                    data-joined="{{ $agenda->current_user_joined ? '1' : '0' }}"
                                                >
                                                    <span class="btn-text">
                                                        {{ $agenda->current_user_joined ? 'Batal Ikut' : 'Gabung Sekarang' }}
                                                    </span>
                                                    <i class="fas {{ $agenda->current_user_joined ? 'fa-times' : 'fa-plus' }} ms-1"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert bg-light border-0 text-center py-5 rounded-4">
                                        <i class="fas fa-calendar-times fs-2 mb-3 text-muted"></i>
                                        <h6 class="fw-bold">Belum ada agenda terdekat</h6>
                                        <p class="text-muted small mb-0">Nantinya data ini akan terhubung ke halaman Meets.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar / Action Area -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-top mb-4" style="top: 100px;">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-4">Keanggotaan</h5>
                    <div class="d-flex justify-content-center gap-4 mb-4">
                        <div>
                            <div class="h3 fw-bold mb-0 text-primary">{{ $community->members->count() }}</div>
                            <div class="small text-muted">Anggota</div>
                        </div>
                        <div>
                            <div class="h3 fw-bold mb-0 text-primary">{{ $community->feeds_count }}</div>
                            <div class="small text-muted">Postingan</div>
                        </div>
                    </div>

                    @if($isJoined)
                        <div class="alert alert-success border-0 small mb-3">
                            <i class="fas fa-check-circle me-1"></i> Anda adalah {{ $isCommunityAdmin ? 'Admin' : 'Member' }}
                        </div>
                        @if(!$isCommunityAdmin)
                            <form action="{{ route('user2026.komunitas.member.remove', [$community->slug, Auth::id()]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100 rounded-pill py-2 mb-3">
                                    Keluar Komunitas
                                </button>
                            </form>
                            <form action="{{ route('user2026.komunitas.report', $community->slug) }}" method="POST" onsubmit="return confirm('Laporkan komunitas ini atas konten/perilaku tidak pantas?')">
                                @csrf
                                <button type="submit" class="btn text-danger small w-100 border-0 bg-transparent">
                                    <i class="fas fa-flag me-1"></i> Report Community
                                </button>
                            </form>
                        @endif
                    @elseif($isPending)
                        <div class="alert alert-warning border-0 small mb-4">
                            <i class="fas fa-clock me-1"></i> Menunggu persetujuan admin...
                        </div>
                        <form action="{{ route('user2026.komunitas.member.remove', [$community->slug, Auth::id()]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-link text-danger text-decoration-none w-100">Batalkan Permintaan</button>
                        </form>
                    @else
                        <form action="{{ route('user2026.komunitas.join', $community->slug) }}" method="POST">
                            @csrf
                            <button id="join-btn-sidebar" type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm mb-3">
                                Gabung Sekarang <i class="fas fa-plus ms-2"></i>
                            </button>
                        </form>
                        <p class="small text-muted mb-0">
                            Status: <span class="fw-bold">{{ ucfirst($community->status) }}</span>
                        </p>
                        <form action="{{ route('user2026.komunitas.report', $community->slug) }}" method="POST" onsubmit="return confirm('Laporkan komunitas ini atas konten/perilaku tidak pantas?')">
                            @csrf
                            <button type="submit" class="btn text-danger small w-100 border-0 bg-transparent mt-3">
                                <i class="fas fa-flag me-1"></i> Report Community
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('user2026.komunitas') }}" class="text-muted text-decoration-none small">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Komunitas
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.custom-nav-pills .nav-link {
    color: #6c757d;
    background: transparent;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    padding: 8px 16px;
    transition: all 0.3s;
}

.custom-nav-pills .nav-link:hover {
    color: #cb2786;
    background: rgba(203, 39, 134, 0.05);
}

.custom-nav-pills .nav-link.active {
    color: #cb2786 !important;
    background: rgba(203, 39, 134, 0.1) !important;
}

.chat-container::-webkit-scrollbar {
    width: 6px;
}
.chat-container::-webkit-scrollbar-thumb {
    background-color: rgba(0,0,0,0.1);
    border-radius: 10px;
}
</style>
@if($isCommunityAdmin)
{{-- Create Post Modal --}}
<div class="modal fade" id="createPostModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0 shadow-none">
                <h5 class="fw-bold mb-0">Buat Postingan Klub</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user2026.komunitas.post.store', $community->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Judul (Opsional)</label>
                        <input type="text" name="title" class="form-control rounded-3 border-light bg-light shadow-none" placeholder="Judul postingan...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Konten</label>
                        <textarea name="content" class="form-control rounded-3 border-light bg-light shadow-none" rows="4" placeholder="Apa yang ingin dibagikan ke komunitas?" required></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Foto (Opsional)</label>
                        <input type="file" name="image" class="form-control rounded-3 border-light bg-light shadow-none" accept="image/*">
                        <div class="form-text small">Max 2MB, format support: jpg, png, webp</div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 p-4 justify-content-between">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Publikasikan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Create Agenda Modal --}}
<div class="modal fade" id="createAgendaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0 shadow-none p-4">
                <h5 class="fw-bold mb-0">Buat Agenda Pertemuan / Meets</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user2026.komunitas.agenda.store', $community->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Judul Agenda</label>
                            <input type="text" name="title" class="form-control rounded-3 border-light bg-light shadow-none" placeholder="Contoh: Latihan Rutin Basket" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tanggal & Waktu</label>
                            <input type="datetime-local" name="meet_date" class="form-control rounded-3 border-light bg-light shadow-none" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Maksimal Peserta</label>
                            <input type="number" name="meet_max_people" class="form-control rounded-3 border-light bg-light shadow-none" placeholder="2-1000" min="2" max="1000" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Lokasi</label>
                            <input type="text" name="meet_location" class="form-control rounded-3 border-light bg-light shadow-none" placeholder="Alamat lengkap atau nama tempat" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Deskripsi / Detail Agenda</label>
                            <textarea name="meet_description" class="form-control rounded-3 border-light bg-light shadow-none" rows="4" placeholder="Jelaskan apa saja kegiatannya..." required></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Foto Banner (Opsional)</label>
                            <input type="file" name="image" class="form-control rounded-3 border-light bg-light shadow-none" accept="image/*">
                            <div class="form-text small">Max 2MB, format support: jpg, png, webp</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 p-4 justify-content-between">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Publikasikan Agenda</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatTab = document.querySelector('button[data-bs-target="#chat"]');
        const chatBox = document.getElementById('chat-box');
        const chatStatus = document.getElementById('chat-status');
        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-input');
        const communitySlug = "{{ $community->slug }}";
        const currentUserId = {{ Auth::id() }};
        let messagesLoaded = false;

        // Function to load messages
        async function loadMessages() {
            try {
                const response = await fetch(`/user2026/komunitas/${communitySlug}/messages`);
                const messages = await response.json();
                
                chatStatus.remove();
                chatBox.innerHTML = ''; // Clear box

                if (messages.length === 0) {
                    chatBox.innerHTML = '<div class="text-center py-5 text-muted small opacity-50">Belum ada obrolan. Mulai sapa teman-teman kamu!</div>';
                } else {
                    messages.forEach(msg => appendMessage(msg));
                    scrollToBottom();
                }
                messagesLoaded = true;
            } catch (error) {
                console.error('Error loading messages:', error);
                chatStatus.innerHTML = '<p class="text-danger">Gagal memuat pesan. Coba lagi nanti.</p>';
            }
        }

        // Function to append message to UI
        function appendMessage(msg) {
            const isMe = msg.user.id === currentUserId;
            const msgHtml = `
                <div class="d-flex flex-column ${isMe ? 'align-self-end text-end' : 'align-self-start text-start'}" style="max-width: 80%;">
                    <div class="small fw-bold text-muted mb-1 px-2" style="font-size: 0.7rem;">${msg.user.name}</div>
                    <div class="p-3 shadow-sm" style="border-radius: ${isMe ? '15px 15px 0 15px' : '15px 15px 15px 0'}; background-color: ${isMe ? '#cb2786' : '#fff'}; color: ${isMe ? '#fff' : '#333'};">
                        <div class="small mb-0">${msg.message}</div>
                    </div>
                </div>
            `;
            
            // Remove empty placeholder if exists
            if (chatBox.querySelector('.text-center.py-5')) {
                chatBox.innerHTML = '';
            }

            chatBox.insertAdjacentHTML('beforeend', msgHtml);
            scrollToBottom();
        }

        function scrollToBottom() {
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        // Load messages when tab is shown
        if (chatTab) {
            chatTab.addEventListener('shown.bs.tab', function () {
                if (!messagesLoaded) {
                    loadMessages();
                }
            });
        }

        // Handle sending message
        if (chatForm) {
            chatForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const text = chatInput.value.trim();
                if (!text) return;

                chatInput.value = '';
                
                try {
                    const response = await fetch(`/user2026/komunitas/${communitySlug}/messages`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ message: text })
                    });
                    
                    if (response.ok) {
                        const newMsg = await response.json();
                        appendMessage(newMsg);
                    }
                } catch (error) {
                    console.error('Error sending message:', error);
                    alert('Gagal mengirim pesan.');
                }
            });
        }

        // --- REALTIME PART ---
        // Optional: If Echo is already set up in the project, we can use it here.
        // If not, the user needs to configure Pusher first.
        if (typeof Echo !== 'undefined') {
            Echo.private(`community.{{ $community->id }}`)
                .listen('MessageSent', (e) => {
                    if (e.user.id !== currentUserId) {
                        appendMessage(e);
                    }
                });
        }
    });

    // --- JOIN MEET FUNCTION ---
    window.toggleJoinMeet = async function(btn, feedId) {
        const isJoined = btn.getAttribute('data-joined') === '1';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Show loading state
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Processing...';

        try {
            const response = await fetch(`/feeds/${feedId}/join`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            
            if (response.ok) {
                // Update button UI
                if (data.joined) {
                    btn.classList.add('btn-danger');
                    btn.classList.remove('btn-primary');
                    btn.setAttribute('data-joined', '1');
                    btn.innerHTML = '<span class="btn-text">Batal Ikut</span> <i class="fas fa-times ms-1"></i>';
                } else {
                    btn.classList.add('btn-primary');
                    btn.classList.remove('btn-danger');
                    btn.setAttribute('data-joined', '0');
                    btn.innerHTML = '<span class="btn-text">Gabung Sekarang</span> <i class="fas fa-plus ms-1"></i>';
                }
                
                // Optional: Update joins_count on the card
                const countContainer = btn.closest('.card-body').querySelector('.fa-users').parentElement;
                countContainer.innerHTML = `<i class="fas fa-users me-2 text-primary"></i> ${data.count} / ${countContainer.innerText.split(' / ')[1]}`;
                
                // Show success toast/alert if needed
                alert(data.message);
            } else if (data.message) {
                alert(data.message);
                btn.innerHTML = originalHtml;
            }
        } catch (error) {
            console.error('Error toggling join:', error);
            alert('Gagal memproses permintaan.');
            btn.innerHTML = originalHtml;
        } finally {
            btn.disabled = false;
        }
    }
</script>
@endpush

@endsection
